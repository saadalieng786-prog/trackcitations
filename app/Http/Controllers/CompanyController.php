<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

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

        Driver::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->whereDoesntHave('user')
            ->delete();

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

        $companyDrivers = Driver::withoutGlobalScopes()
            ->with('user')
            ->where('company_id', $company->id)
            ->whereHas('user')
            ->orderBy('id')
            ->get();

        $driverEmails = $companyDrivers
            ->map(fn (Driver $driver) => $driver->user?->email)
            ->filter()
            ->values();

        $driverTicketStats = collect();
        if ($driverEmails->isNotEmpty()) {
            $driverTicketStats = Ticket::withoutGlobalScopes()
                ->where('company_id', $company->id)
                ->whereIn('user_email', $driverEmails)
                ->select([
                    'user_email',
                    DB::raw('SUM(CASE WHEN status IS NULL OR status NOT IN ('.Ticket::TICKET_STATUS_ARCHIVED.','.Ticket::TICKET_STATUS_CLOSED.') THEN 1 ELSE 0 END) as open_count'),
                    DB::raw('SUM(CASE WHEN status = '.Ticket::TICKET_STATUS_CLOSED.' THEN 1 ELSE 0 END) as closed_count'),
                    DB::raw('COALESCE(SUM('.Ticket::pointsSavedSql().'), 0) as points_saved'),
                ])
                ->groupBy('user_email')
                ->get()
                ->keyBy(fn ($row) => strtolower((string) $row->user_email));
        }

        $childCompanyDriverCounts = Driver::withoutGlobalScopes()
            ->whereIn('company_id', $company->childCompanies->pluck('id'))
            ->select('company_id', DB::raw('COUNT(*) as drivers_count'))
            ->groupBy('company_id')
            ->pluck('drivers_count', 'company_id');

        $companyTickets = Ticket::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->orderByDesc('id')
            ->get();

        $driversByEmail = $companyDrivers
            ->filter(fn (Driver $driver) => filled($driver->user?->email))
            ->keyBy(fn (Driver $driver) => strtolower((string) $driver->user->email));

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
            'companyDrivers',
            'driverTicketStats',
            'childCompanyDriverCounts',
            'companyTickets',
            'driversByEmail',
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
