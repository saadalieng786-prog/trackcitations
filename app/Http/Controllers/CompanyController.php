<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Driver;
use App\Models\Manager;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    use AuthorizesRequests;

    protected function parentCompanyOptions(?Company $company = null)
    {
        $query = Company::query()
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->orderBy('name');

        $currentUser = request()->user();

        if ($currentUser->isCompanyAdmin()) {
            $query->whereIn('id', $currentUser->writableCompanyIds());
        }

        if ($company) {
            $query->where('id', '!=', $company->id)
                ->whereNotIn('id', $company->descendantCompanyIds());
        }

        return $query->get(['id', 'name', 'parent_company_id']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        if (request()->ajax()) {
            $data = Company::query();
            $currentUser = \request()->user();

            // Always exclude companies with empty or null names
            $data->whereNotNull('name')->where('name', '!=', '');

            if (\request('search')['value']) {
                $search = request('search')['value'];

                // Add search conditions
                $data = $data->where(function ($query) use ($search) {
                    $query->where('id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('ct_fname', 'like', "%{$search}%")
                        ->orWhere('ct_lname', 'like', "%{$search}%")
                        ->orWhere('dot', 'like', "%{$search}%");
                });
            }


            // Making sure none access companies not assigned to his account.
            if ($currentUser->isInternalAdmin()) {

            } else if ($currentUser->isCompanyAdmin()) {
                $data->whereIn('id', $currentUser->managedCompanyIds());
            }

            // Handle ordering
            if (request()->has('order')) {
                $columns = ['id', 'name', 'ct_fname', 'dot']; // Adjust to match your table columns
                $order = request('order', 'id');
                $columnIndex = $order[0]['column'];
                $direction = $order[0]['dir'];
                // Ensure column exists in your defined columns array
                if (isset($columns[$columnIndex])) {
                    $data = $data->orderBy($columns[$columnIndex], $direction);
                }
            }

            // Pagination
            $length = request('length', 10);
            $start = request('start', 0);
            $page = ($start / $length) + 1;

            $data = $data->with(['parentCompany', 'childCompanies'])->paginate($length, ['*'], 'page', $page);

            $role = auth()->user()->portalRoutePrefix();
            // Add action column
            $data->getCollection()->transform(function ($item) use ($role) {
                $item->ct_fname = $item->ct_fname . ' ' . $item->ct_lname;
                $item->parent_name = optional($item->parentCompany)->name ?: 'Top-level';
                $item->children_count = $item->childCompanies->count();
                $item->name = '<a href="'.route($role.'.companies.show', $item->id).'" class="font-semibold text-primary hover:underline">'
                    .e($item->name)
                    .'</a>';
                $item->action = '<a href="'.route($role.'.companies.show', $item->id).'" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary" title="View company">'
                    .'<i class="ti ti-eye text-xl leading-none"></i>'
                    .'</a>';
                if (auth()->user()->isInternalAdmin() || auth()->user()->canWriteCompany($item->id)) {
                    $item->action .= '<a href="'.route($role.'.companies.edit', $item->id).'" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary" title="Edit company">
                                        <i class="ti ti-edit text-xl leading-none"></i>
                                    </a>';
                }

                if (auth()->user()->isInternalAdmin()) {
                    $item->action .= '<form action="'.route($role.'.companies.destroy', $item->id).'" method="POST" class="inline delete-company-form">
                                        <input type="hidden" name="_method" value="DELETE">
                        '. csrf_field() .'
                                        <button href="#" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary" title="Delete company">
                                            <i class="ti ti-trash text-xl leading-none"></i>
                                        </button>';
                }
                return $item;
            });

            // Response
            return response()->json([
                'draw' => request()->get('draw'),
                'recordsTotal' => $data->total(),
                'recordsFiltered' => $data->total(),
                'data' => $data->items(),
            ]);
        }

        return view('companies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $this->authorize('create', Company::class);
        $parentCompanyOptions = $this->parentCompanyOptions();

        return view('companies.create', compact('parentCompanyOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->authorize('create', Company::class);
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_company_id' => 'nullable|exists:companies,id',
            'ct_email' => 'nullable|email',
            'ct_fname' => '',
            'ct_lname' => '',
            'dot' => '',
            'companyContactName' => '',
            'companyContactEmail' => '',
            'companyContactPhone' => '',
            'companyContactCell' => '',
        ]);
        $company = Company::create($request->only([
            'name',
            'parent_company_id',
            'ct_email',
            'ct_fname',
            'ct_lname',
            'dot',
            'sf_id',
        ]));
        if ($request->companyContactName) {
            foreach ($request->companyContactName as $index => $contactName) {
                $company->contacts()->create([
                    'name' => $request->companyContactName[$index],
                    'email' => $request->companyContactEmail[$index],
                    'phone' => $request->companyContactPhone[$index],
                    'cell' => $request->companyContactCell[$index],
                ]);
            }
        }

        return redirect()->route(auth()->user()->portalRoutePrefix().'.companies.show', $company->id)->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $this->authorize('view', $company);

        return view('companies.show', $this->companyOverviewData($company));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $this->authorize('update', $company);

        $data = $this->companyOverviewData($company);
        $data['parentCompanyOptions'] = $this->parentCompanyOptions($company);

        return view('companies.edit', $data);
    }

    /**
     * Server-side drivers table for company show/edit (handles large fleets).
     */
    public function driversData(Request $request, Company $company)
    {
        $this->authorize('view', $company);

        $portal = $request->user()->portalRoutePrefix();
        $driverType = Driver::class;
        $length = max(1, min(50, (int) $request->input('length', 25)));
        $start = max(0, (int) $request->input('start', 0));
        $search = trim((string) data_get($request->input('search'), 'value', ''));
        $orderDir = strtolower((string) data_get($request->input('order'), '0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        $base = Driver::withoutGlobalScopes()
            ->from('drivers')
            ->join('users', function ($join) use ($driverType) {
                $join->on('users.roleable_id', '=', 'drivers.id')
                    ->where('users.roleable_type', '=', $driverType);
            })
            ->where('drivers.company_id', $company->id);

        $recordsTotal = (int) (clone $base)->toBase()->distinct()->count('drivers.id');

        $query = (clone $base)->select([
            'drivers.id',
            'users.name as user_name',
            'users.email as user_email',
            'users.city as user_city',
            'users.state as user_state',
            'users.last_login_at as user_last_login_at',
        ]);

        if ($search !== '') {
            $like = "%{$search}%";
            $query->where(function ($inner) use ($like) {
                $inner->where('users.name', 'like', $like)
                    ->orWhere('users.email', 'like', $like)
                    ->orWhere('users.city', 'like', $like)
                    ->orWhere('users.state', 'like', $like);
            });
            $recordsFiltered = (int) (clone $query)->toBase()->distinct()->count('drivers.id');
        } else {
            $recordsFiltered = $recordsTotal;
        }

        $drivers = $query
            ->orderBy('drivers.id', $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        $emails = $drivers
            ->pluck('user_email')
            ->filter()
            ->unique()
            ->values();

        $driverTicketStats = collect();
        if ($emails->isNotEmpty()) {
            // Fast counts only (no REGEXP / points SQL) for the current page emails.
            $driverTicketStats = Ticket::withoutGlobalScopes()
                ->where('company_id', $company->id)
                ->whereIn('user_email', $emails->all())
                ->select([
                    'user_email',
                    DB::raw('SUM(CASE WHEN status IS NULL OR status NOT IN ('.Ticket::TICKET_STATUS_ARCHIVED.','.Ticket::TICKET_STATUS_CLOSED.') THEN 1 ELSE 0 END) as open_count'),
                    DB::raw('SUM(CASE WHEN status = '.Ticket::TICKET_STATUS_CLOSED.' THEN 1 ELSE 0 END) as closed_count'),
                    DB::raw('COALESCE(SUM(GREATEST(0, COALESCE(CAST(NULLIF(total_dver_points__c, \'\') AS DECIMAL(10,2)), 0) - COALESCE(CAST(NULLIF(total_dver_points_removed__c, \'\') AS DECIMAL(10,2)), 0))), 0) as points_saved'),
                ])
                ->groupBy('user_email')
                ->get()
                ->keyBy(fn ($row) => (string) $row->user_email);
        }

        $rows = $drivers->values()->map(function ($driver, int $index) use ($start, $portal, $driverTicketStats) {
            $email = (string) ($driver->user_email ?? '');
            $stats = $driverTicketStats->get($email);
            $name = $driver->user_name ?: 'Unnamed driver';
            $editUrl = route($portal.'.drivers.edit', $driver->id);

            return [
                'row_number' => $start + $index + 1,
                'name_html' => '<a href="'.e($editUrl).'" class="font-semibold text-indigo-600 hover:underline">'.e($name).'</a>'
                    .'<div class="text-xs text-slate-400">'.e($email).'</div>',
                'driver_name' => '<a href="'.e($editUrl).'" class="font-medium text-primary">'.e($name).'</a>',
                'email' => e($email !== '' ? $email : '—'),
                'state' => e((string) ($driver->user_state ?: '—')),
                'city' => e((string) ($driver->user_city ?: '—')),
                'open_tickets' => (int) ($stats->open_count ?? 0),
                'closed_tickets' => (int) ($stats->closed_count ?? 0),
                'points_saved' => number_format((float) ($stats->points_saved ?? 0), 1),
                'last_access' => $driver->user_last_login_at
                    ? e(\Carbon\Carbon::parse($driver->user_last_login_at)->format('M j, Y g:i A'))
                    : '—',
                'status_html' => $email !== ''
                    ? '<span class="badge bg-success-50 text-success">Portal Access</span>'
                    : '<span class="badge bg-warning-50 text-warning">No Login</span>',
                'action' => '<a href="'.e($editUrl).'" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-primary btn-pc-default" title="Edit driver"><i class="ti ti-pencil text-xl leading-none"></i></a>',
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows,
        ]);
    }

    /**
     * Server-side tickets table for company show/edit (handles large fleets).
     */
    public function ticketsData(Request $request, Company $company)
    {
        $this->authorize('view', $company);

        $portal = $request->user()->portalRoutePrefix();
        $driverType = Driver::class;
        $length = max(1, min(50, (int) $request->input('length', 25)));
        $start = max(0, (int) $request->input('start', 0));
        $search = trim((string) data_get($request->input('search'), 'value', ''));
        $orderDir = strtolower((string) data_get($request->input('order'), '0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $base = Ticket::withoutGlobalScopes()
            ->where('company_id', $company->id);

        $recordsTotal = (int) (clone $base)->count();

        $query = (clone $base)->select([
            'id',
            'ticket_number',
            'name',
            'user_email',
            'date_issued',
            'state',
            'status',
            'indicator',
            'total_dver_points__c',
            'total_dver_points_removed__c',
        ]);

        if ($search !== '') {
            $like = "%{$search}%";
            $query->where(function ($inner) use ($like, $search) {
                if (ctype_digit($search)) {
                    $inner->where('id', (int) $search);
                }
                $inner->orWhere('ticket_number', 'like', $like)
                    ->orWhere('citation_no', 'like', $like)
                    ->orWhere('name', 'like', $like)
                    ->orWhere('user_email', 'like', $like)
                    ->orWhere('state', 'like', $like)
                    ->orWhere('indicator', 'like', $like);
            });
            $recordsFiltered = (int) (clone $query)->count();
        } else {
            $recordsFiltered = $recordsTotal;
        }

        $tickets = $query
            ->orderBy('id', $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        $emails = $tickets
            ->pluck('user_email')
            ->filter()
            ->unique()
            ->values();

        $driversByEmail = collect();
        if ($emails->isNotEmpty()) {
            $driversByEmail = DB::table('users')
                ->join('drivers', function ($join) use ($driverType) {
                    $join->on('drivers.id', '=', 'users.roleable_id')
                        ->where('users.roleable_type', '=', $driverType);
                })
                ->where('drivers.company_id', $company->id)
                ->whereIn('users.email', $emails->all())
                ->select(['users.email', 'drivers.id as driver_id', 'users.name as driver_name'])
                ->get()
                ->keyBy(fn ($row) => (string) $row->email);
        }

        $rows = $tickets->map(function (Ticket $ticket) use ($portal, $driversByEmail) {
            $email = (string) ($ticket->user_email ?? '');
            $linked = $driversByEmail->get($email);
            $driverName = $ticket->name ?: ($linked->driver_name ?? '—');
            $statusLabel = match ((int) ($ticket->status ?? -1)) {
                Ticket::TICKET_STATUS_CLOSED => 'Closed',
                Ticket::TICKET_STATUS_ARCHIVED => 'Archived',
                default => 'Open',
            };
            $indicator = $ticket->indicator ?: '—';
            $showUrl = route($portal.'.tickets.show', $ticket->id);

            $ticketHtml = '<a href="'.e($showUrl).'" class="font-semibold text-indigo-600 hover:underline">#'.e((string) $ticket->id).'</a>';
            if ($ticket->ticket_number) {
                $ticketHtml .= '<div class="text-xs text-slate-400">'.e((string) $ticket->ticket_number).'</div>';
            }

            if ($linked) {
                $driverHtml = '<a href="'.e(route($portal.'.drivers.edit', $linked->driver_id)).'" class="font-medium text-indigo-600 hover:underline">'.e($driverName).'</a>';
            } else {
                $driverHtml = e($driverName);
            }

            return [
                'ticket_html' => $ticketHtml,
                'driver_html' => $driverHtml,
                'date_received' => $ticket->date_issued
                    ? e(\Carbon\Carbon::parse($ticket->date_issued)->format('M j, Y'))
                    : '—',
                'state' => e((string) ($ticket->state ?: '—')),
                'status_html' => '<div>'.e($statusLabel).'</div><div class="text-xs text-slate-400">'.e($indicator).'</div>',
                'original_points' => number_format((float) $ticket->original_points_value, 1),
                'final_points' => number_format((float) $ticket->final_points_value, 1),
                'points_saved' => number_format((float) $ticket->points_saved, 1),
                'action' => '<a href="'.e($showUrl).'" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary" title="View ticket"><i class="ti ti-eye text-lg"></i></a>',
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows,
        ]);
    }

    /**
     * Shared payload for company overview / edit relationship tabs.
     */
    protected function companyOverviewData(Company $company): array
    {
        $company->load([
            'parentCompany',
            'childCompanies',
            'managers.user',
            'contacts',
        ]);

        $companyDriversCount = Driver::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->whereHas('user')
            ->count();

            // Avoid expensive orphan sweeps on very large fleets (e.g. CDL Driver).
        if ($companyDriversCount < 500) {
            $orphanDriverIds = Driver::withoutGlobalScopes()
                ->where('company_id', $company->id)
                ->whereDoesntHave('user')
                ->limit(200)
                ->pluck('id');

            if ($orphanDriverIds->isNotEmpty()) {
                Driver::withoutGlobalScopes()->whereIn('id', $orphanDriverIds)->delete();
            }

            $orphanManagerIds = Manager::query()
                ->whereDoesntHave('user')
                ->whereHas('companies', fn ($query) => $query->where('companies.id', $company->id))
                ->pluck('id');

            if ($orphanManagerIds->isNotEmpty()) {
                $company->managers()->detach($orphanManagerIds);
                Manager::query()
                    ->whereIn('id', $orphanManagerIds)
                    ->whereDoesntHave('companies')
                    ->delete();
            }

            $company->unsetRelation('managers');
            $company->load(['managers.user']);
        }

        $childCompanyDriverCounts = Driver::withoutGlobalScopes()
            ->whereIn('company_id', $company->childCompanies->pluck('id'))
            ->select('company_id', DB::raw('COUNT(*) as drivers_count'))
            ->groupBy('company_id')
            ->pluck('drivers_count', 'company_id');

        $companyTicketsCount = Ticket::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->count();

        $openTicketsCount = Ticket::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->active()
            ->count();

        $closedTicketsCount = Ticket::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->where('status', Ticket::TICKET_STATUS_CLOSED)
            ->count();

        $pointsSavedTotal = (float) (Ticket::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->selectRaw('COALESCE(SUM('.Ticket::pointsSavedSql().'), 0) as aggregate')
            ->value('aggregate') ?? 0);

        return compact(
            'company',
            'companyDriversCount',
            'childCompanyDriverCounts',
            'companyTicketsCount',
            'openTicketsCount',
            'closedTicketsCount',
            'pointsSavedTotal'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
        $this->authorize('update', $company);
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_company_id' => [
                'nullable',
                'exists:companies,id',
                Rule::notIn([$company->id]),
                function (string $attribute, mixed $value, \Closure $fail) use ($company) {
                    if ($value && in_array((int) $value, $company->descendantCompanyIds(), true)) {
                        $fail('A child company cannot be selected as the parent company.');
                    }
                },
            ],
            'ct_email' => 'nullable|email',
            'ct_fname' => '',
            'ct_lname' => '',
            'dot' => '',
            'companyContactName' => '',
            'companyContactEmail' => '',
            'companyContactPhone' => '',
            'companyContactCell' => '',
        ]);

        $company->update($request->only([
            'name',
            'parent_company_id',
            'ct_email',
            'ct_fname',
            'ct_lname',
            'dot',
            'sf_id',
        ]));
        // Remove all old contacts.
        $company->contacts()->delete();
        if ($request->companyContactName) {
            foreach ($request->companyContactName as $index => $contactName) {
                $company->contacts()->create([
                    'name' => $request->companyContactName[$index],
                    'email' => $request->companyContactEmail[$index],
                    'phone' => $request->companyContactPhone[$index],
                    'cell' => $request->companyContactCell[$index],
                ]);
            }
        }

        return redirect()->route(auth()->user()->portalRoutePrefix().'.companies.show', $company->id)->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
        if (!auth()->user()->isInternalAdmin()) {
            abort(403);
        }

        $company->delete();
        return redirect()->route(auth()->user()->portalRoutePrefix().'.companies.index')->with('success', 'Company deleted successfully.');
    }
}
