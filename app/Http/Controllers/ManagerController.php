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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $user = request()->user();
        $managedCompanyIds = $user->managedCompanyIds();
        $ticketQuery = Ticket::filterByRole($user);
        $stats = [
            'tickets' => (clone $ticketQuery)->active()->count(),
            'companies' => count($managedCompanyIds),
            'drivers' => Driver::withoutGlobalScopes()->whereIn('company_id', $managedCompanyIds)->count(),
            'closed_tickets' => Ticket::withoutGlobalScopes()
                ->whereIn('company_id', $managedCompanyIds)
                ->where('status', Ticket::TICKET_STATUS_CLOSED)
                ->count(),
            'points_saved' => (float) (Ticket::withoutGlobalScopes()
                ->whereIn('company_id', $managedCompanyIds)
                ->selectRaw('COALESCE(SUM('.Ticket::pointsSavedSql().'), 0) as aggregate')
                ->value('aggregate') ?? 0),
        ];
        $upComingCourtDates = (clone $ticketQuery)
            ->with(['company', 'attorney.user'])
            ->active()
            ->whereBetween('court_date', [now(), now()->addDays(5)])
            ->orderBy('court_date', 'asc')
            ->limit(5)
            ->get();
        $pendingTickets = (clone $ticketQuery)
            ->with(['company', 'attorney.user'])
            ->active()
            ->where('indicator', Ticket::INDICATOR_PENDING)
            ->latest('updated_at')
            ->limit(5)
            ->get();
        $recentClosedTickets = Ticket::withoutGlobalScopes()
            ->with(['company', 'attorney.user'])
            ->whereIn('company_id', $managedCompanyIds)
            ->where('status', Ticket::TICKET_STATUS_CLOSED)
            ->latest('updated_at')
            ->limit(5)
            ->get();
        $companySnapshots = Company::whereIn('id', $managedCompanyIds)
            ->with('parentCompany')
            ->get();

        return view('manager.dashboard', compact('stats', 'upComingCourtDates', 'pendingTickets', 'recentClosedTickets', 'companySnapshots'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = User::role(User::companyAdminRoles())
                ->filterByRole(request()->user());

            if (\request('search')['value']) {
                $search = request('search')['value'];

                // Add search conditions
                $data = $data->where(function ($query) use ($search) {
                    $query->where('id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('state', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Handle ordering
            if (request()->has('order')) {
                $columns = ['id', 'name', 'email', 'state', 'city', 'last_login_at']; // Adjust to match your table columns
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

            $data = $data->paginate($length, ['*'], 'page', $page);

            // Add action column
            $data->getCollection()->transform(function ($item) {
                $item->role_label = User::companyAdminRoleOptions()[$item->getRoleNames()->first()] ?? 'Company Admin';
                $item->last_login_at = $item->last_login_at ? \Carbon\Carbon::parse($item->last_login_at)->diffForHumans() : 'Never';
                $item->action  = '';
                if (auth()->user()->isInternalAdmin() || collect($item->roleable->companies)->pluck('id')->contains(fn ($id) => auth()->user()->canWriteCompany((int) $id))) {
                    $item->action .= '<a href="'.route(auth()->user()->portalRoutePrefix().'.managers.edit', $item->roleable->id).'" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-edit text-xl leading-none"></i>
                                    </a>';
                }


                if (auth()->user()->isInternalAdmin()) {
                    $item->action .= '<form action="'.route(auth()->user()->portalRoutePrefix().'.managers.destroy', $item->roleable->id).'" method="POST" class="inline delete-manager-form">
                                        <input type="hidden" name="_method" value="DELETE">
                                        '. csrf_field() .'
                                        <button href="#" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
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

        return view('managers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $currentUser = request()->user();
        $roleOptions = User::companyAdminRoleOptions();

        if($currentUser->isCompanyAdmin()) {
            if (!$currentUser->roleable->companiesCountWithWriteAccess()) {
                abort(403);
            }
        }
        return view('managers.create', compact('roleOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:'.implode(',', array_keys(User::companyAdminRoleOptions())),
            'dob' => 'nullable|date',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'phone' => 'sometimes|unique:users,phone',
            'timezone' => '',
            'notification_email' => '',
            'notification_sms' => '',
            'notification_push' => '',
            'managerCompany_id' => 'nullable|array',
            'managerCompany_id.*' => 'nullable|exists:companies,id',
            'managerCompany_isWrite' => 'nullable|array',
            'managerCompany_isWrite.*' => 'nullable|in:Yes,No',
        ]);
        $request->merge([
            'notification_email' => $request->has('notification_email'),
            'notification_sms' => $request->has('notification_sms'),
            'notification_push' => $request->has('notification_push'),
        ]);

        foreach ($request->get('managerCompany_id') as $index => $company_id) {
            $currentUser = \auth()->user();
            if ($currentUser->isCompanyAdmin() && !$currentUser->canWriteCompany((int) $company_id)) {
                throw ValidationException::withMessages([
                    "company_id" => "You do not have write access to company {$request->managerCompany_name[$index]}."
                ]);
            }
        }

        $manager = Manager::create([]);

        $user = $manager->user()->create($request->only([
            'name',
            'email',
            'password',
            'dob',
            'address',
            'city',
            'state',
            'zip',
            'phone',
            'timezone',
            'notification_email',
            'notification_sms',
            'notification_push',
        ]));

        foreach ($request->get('managerCompany_id') as $index => $company_id) {
            $manager->companies()->attach($company_id, [
                'is_write_access' => $request->get('managerCompany_isWrite')[$index] == 'Yes'
            ]);

            if ($request->get('managerCompany_isWrite')[$index] == 'Yes') {
                $permissionName = "write.company.{$company_id}";
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $user->givePermissionTo($permissionName);
            }
        }
        $user->assignRole($request->string('role')->toString());
        return redirect()->route(Auth::user()->portalRoutePrefix().'.managers.edit', $manager->id)->with('success', 'Manager created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Manager $manager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manager $manager)
    {
        //
        $roleOptions = User::companyAdminRoleOptions();

        return view('managers.edit', compact('manager', 'roleOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Manager $manager)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|in:'.implode(',', array_keys(User::companyAdminRoleOptions())),
            'dob' => 'nullable|date',
            'password' => 'nullable|string|min:8',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'phone' => '',
            'timezone' => '',
            'notification_email' => '',
            'notification_sms' => '',
            'notification_push' => ''
        ]);
        $request->merge([
            'notification_email' => $request->has('notification_email'),
            'notification_sms' => $request->has('notification_sms'),
            'notification_push' => $request->has('notification_push'),
        ]);
        foreach ($request->get('managerCompany_id') as $index => $company_id) {
            $currentUser = \auth()->user();
            if ($currentUser->isCompanyAdmin() && !$currentUser->canWriteCompany((int) $company_id)) {
                throw ValidationException::withMessages([
                    "company_id" => "You do not have write access to company {$request->managerCompany_name[$index]}."
                ]);
            }
        }

        $data = $request->only(array_merge(
            [
                'name',
                'email',
                'dob',
                'address',
                'city',
                'state',
                'zip',
                'phone',
                'timezone',
                'notification_email',
                'notification_sms',
                'notification_push',
            ],
            $request->password ? ['password'] : []
        ));

        if ($request->password) {
            $data['password'] = bcrypt($request->password); // Hash the password
        }

        $manager->user()->update($data);
        $manager->user->syncRoles([$request->string('role')->toString()]);

        // Remove all old companies.
        $manager->companies()->detach();

        foreach ($request->get('managerCompany_id') as $index => $company_id) {
            $manager->companies()->attach($company_id, [
                'is_write_access' => $request->get('managerCompany_isWrite')[$index] == 'Yes'
            ]);

            if ($request->get('managerCompany_isWrite')[$index] == 'Yes') {
                $permissionName = "write.company.{$company_id}";
                $permission = Permission::firstOrCreate(['name' => $permissionName]);
                $manager->user->givePermissionTo($permissionName);
            }
        }

        return redirect()->route(Auth::user()->portalRoutePrefix().'.managers.edit', $manager->id)->with('success', 'Manager updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manager $manager)
    {
        //
        if (!auth()->user()->isInternalAdmin()) {
            abort(403);
        }
        $manager->user()->delete();

        $manager->delete();
        return redirect()->route(auth()->user()->portalRoutePrefix().'.managers.index')->with('success', 'Manager deleted successfully.');
    }
}
