<?php

namespace App\Http\Controllers;

use App\Integrations\Salesforce\SalesforceSyncService;
use App\Models\Company;
use App\Models\SalesForce;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use Carbon\Carbon;
use App\Services\SalesforceService;
use Illuminate\Http\Request;

class SalesForceController extends Controller
{

    public function __construct(private SalesforceSyncService $sfSyncService)
    {
    }

    public function index()
    {
        $salesforce = $this->settings();

        $syncStats = [
            'companies' => Company::whereNotNull('sf_id')->count(),
            'tickets' => Ticket::withoutGlobalScopes()->whereNotNull('sf_id')->count(),
            'attachments' => TicketAttachment::whereNotNull('sf_id')->count(),
            'tickets_with_points' => Ticket::withoutGlobalScopes()
                ->whereNotNull('sf_id')
                ->where(function ($query) {
                    $query->whereNotNull('total_dver_points__c')
                        ->where('total_dver_points__c', '!=', '')
                        ->orWhereNotNull('total_dver_points_removed__c')
                        ->where('total_dver_points_removed__c', '!=', '');
                })
                ->count(),
            'original_points_total' => (float) (Ticket::withoutGlobalScopes()
                ->whereNotNull('sf_id')
                ->selectRaw('COALESCE(SUM('.Ticket::normalizedPointsSql('total_dver_points__c').'), 0) as aggregate')
                ->value('aggregate') ?? 0),
            'final_points_total' => (float) (Ticket::withoutGlobalScopes()
                ->whereNotNull('sf_id')
                ->selectRaw('COALESCE(SUM('.Ticket::normalizedPointsSql('total_dver_points_removed__c').'), 0) as aggregate')
                ->value('aggregate') ?? 0),
            'points_saved' => (float) (Ticket::withoutGlobalScopes()
                ->whereNotNull('sf_id')
                ->selectRaw('COALESCE(SUM('.Ticket::pointsSavedSql().'), 0) as aggregate')
                ->value('aggregate') ?? 0),
        ];

        $syncTimes = [
            'records' => $this->formatSyncTime($salesforce->sf_last_sync_time),
            'attachments' => $this->formatSyncTime($salesforce->sf_att_last_sync_time),
            'files' => $this->formatSyncTime($salesforce->sf_file_last_sync_time),
            'account_activity' => $this->formatSyncTime($salesforce->sf_account_activity_synced_at),
            'contact_activity' => $this->formatSyncTime($salesforce->sf_contact_activity_synced_at),
            'token_issued' => $this->formatSyncTime($salesforce->sf_issued_at),
            'updated' => optional($salesforce->updated_at)?->format('M j, Y g:i A'),
        ];

        $connectionSummary = [
            'instance_url' => $salesforce->sf_instance_url ?: env('SF_LOGIN_URL'),
            'callback_uri' => $salesforce->redirect_uri ?: env('SF_CALLBACK_URI'),
            'login_uri' => $salesforce->login_uri ?: env('SF_LOGIN_URL'),
            'has_access_token' => filled($salesforce->sf_access_token),
            'has_refresh_token' => filled($salesforce->sf_refresh_token),
        ];

        $legacyTooling = [
            'present' => is_dir(base_path('sfdc_datasync')),
            'enabled' => filter_var(env('LEGACY_SFDC_DATASYNC_ENABLED', false), FILTER_VALIDATE_BOOL),
            'allowed_ips' => env('LEGACY_SFDC_ALLOWED_IPS', ''),
            'redirect_uri' => env('LEGACY_SFDC_REDIRECT_URI', ''),
        ];

        $recentSync = [
            'tickets' => Ticket::withoutGlobalScopes()
                ->with('company')
                ->whereNotNull('sf_id')
                ->latest('updated_at')
                ->limit(5)
                ->get(),
            'companies' => Company::query()
                ->whereNotNull('sf_id')
                ->latest('updated_at')
                ->limit(5)
                ->get(),
            'attachments' => TicketAttachment::with(['ticket.company'])
                ->whereNotNull('sf_id')
                ->latest('updated_at')
                ->limit(5)
                ->get(),
        ];

        $syncAlerts = collect([
            ! $salesforce->isConfigured() ? 'Salesforce tokens/instance URL are not fully configured yet.' : null,
            blank($salesforce->sf_last_sync_time) ? 'No contact/ticket sync has completed yet on this environment.' : null,
            $salesforce->status === SalesForce::STATUS_FAILED && filled($salesforce->reason)
                ? 'The last sync failed and should be reviewed before depending on current status data.'
                : null,
        ])->filter()->values();

        return view('admin.salesforce.index', compact('salesforce', 'syncStats', 'syncTimes', 'connectionSummary', 'recentSync', 'syncAlerts', 'legacyTooling'));
    }

    public function oauth()
    {
        $salesforce = $this->settings();

        $auth_url = rtrim($salesforce->login_uri ?: config('services.salesforce.login_url'), '/') .
            "/services/oauth2/authorize?response_type=code&client_id=" .
            urlencode($salesforce->client_id ?: config('services.salesforce.consumer_key')) . "&redirect_uri=" .
            urlencode($salesforce->redirect_uri ?: config('services.salesforce.callback_uri'));
        return redirect($auth_url);

    }
    public function callback(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $salesforce = $this->settings();

        $response = \Illuminate\Support\Facades\Http::asForm()->post(
            rtrim($salesforce->login_uri ?: config('services.salesforce.login_url'), '/') . '/services/oauth2/token',
            [
                'grant_type' => 'authorization_code',
                'code' => $request->string('code')->toString(),
                'client_id' => $salesforce->client_id ?: config('services.salesforce.consumer_key'),
                'client_secret' => $salesforce->client_secret ?: config('services.salesforce.consumer_secret'),
                'redirect_uri' => $salesforce->redirect_uri ?: config('services.salesforce.callback_uri'),
            ]
        );

        if (! $response->successful()) {
            $salesforce->update([
                'status' => SalesForce::STATUS_FAILED,
                'reason' => 'OAuth callback failed: ' . $response->body(),
            ]);

            return redirect()->route(auth()->user()->portalRoutePrefix().'.salesforce.index')
                ->with('error', 'Salesforce connection failed. Review the sync monitor for details.');
        }

        $data = $response->json();
        $idParts = explode('/', $data['id'] ?? '');
        $userId = end($idParts) ?: null;

        $salesforce->update([
            'sf_access_id' => $userId,
            'sf_access_token' => $data['access_token'] ?? '',
            'sf_refresh_token' => $data['refresh_token'] ?? $salesforce->sf_refresh_token,
            'sf_instance_url' => $data['instance_url'] ?? '',
            'sf_signature' => $data['signature'] ?? null,
            'sf_issued_at' => $data['issued_at'] ?? now()->toDateTimeString(),
            'status' => SalesForce::STATUS_FINISHED,
            'reason' => '',
        ]);

        return redirect()->route(auth()->user()->portalRoutePrefix().'.salesforce.index')
            ->with('success', 'Salesforce connection updated successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'client_id' => 'nullable|string',
            'client_secret' => 'nullable|string',
            'redirect_uri' => 'nullable|url',
            'login_uri' => 'nullable|url',
        ]);

        $salesforce = $this->settings();
        $salesforce->update([
            'client_id' => $request->input('client_id', $salesforce->client_id),
            'client_secret' => $request->input('client_secret', $salesforce->client_secret),
            'redirect_uri' => $request->input('redirect_uri', $salesforce->redirect_uri),
            'login_uri' => $request->input('login_uri', $salesforce->login_uri),
        ]);

        return redirect()->route(auth()->user()->portalRoutePrefix().'.salesforce.index')
            ->with('success', 'Salesforce settings updated successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
        ]);

        $salesForce = $this->settings();

        $salesForce->update(['status' => SalesForce::STATUS_RUNNING, 'reason' => '']);

        $sf = new \App\Integrations\Salesforce\SalesforceService(
            $salesForce->sf_instance_url,
            $salesForce->sf_access_token
        );

        $sf->resetSFConnection();

        $records = $sf->fetchContacts(["Account.Primary_Contact_Email__c" => $request->Email]);

        if($records['totalSize'] > 0) {
            $this->sfSyncService->sync($records['records']);
            // rarely when records more than 2000
            while(isset($records['nextRecordsUrl']) && $records['nextRecordsUrl']!='') {
                $records = $sf->apiCall($records['nextRecordsUrl']);
                $this->sfSyncService->sync($records['records']);
            }
        }

        return redirect()->route(auth()->user()->portalRoutePrefix().'.salesforce.index')->with(['success' => 'Salesforce imported '. $records['totalSize'] . ' records']);
    }

    protected function settings(): SalesForce
    {
        return SalesForce::firstOrCreate(
            ['id' => 1],
            [
                'client_id' => config('services.salesforce.consumer_key', ''),
                'client_secret' => config('services.salesforce.consumer_secret', ''),
                'redirect_uri' => config('services.salesforce.callback_uri', ''),
                'login_uri' => config('services.salesforce.login_url', ''),
                'sf_access_token' => '',
                'sf_refresh_token' => '',
                'sf_instance_url' => '',
                'status' => SalesForce::STATUS_FINISHED,
                'reason' => '',
            ]
        );
    }

    protected function formatSyncTime(?string $value): string
    {
        if (blank($value)) {
            return 'Not synced yet';
        }

        try {
            return Carbon::parse($value)->format('M j, Y g:i A');
        } catch (\Throwable) {
            return (string) $value;
        }
    }
}
