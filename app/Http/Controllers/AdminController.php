<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Log;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $search = trim((string) request('q', ''));
        $stats = [
            'tickets' => Ticket::active()->count(),
            'drivers' => User::role('driver')->count(),
            'attorneys' => User::role('attorney')->count(),
            'companies' => Company::count(),
            'closed_tickets' => Ticket::where('status', Ticket::TICKET_STATUS_CLOSED)->count(),
            'points_saved' => (float) (Ticket::withoutGlobalScopes()
                ->selectRaw('COALESCE(SUM('.Ticket::pointsSavedSql().'), 0) as aggregate')
                ->value('aggregate') ?? 0),
        ];
        $upComingCourtDates = Ticket::whereBetween('court_date', [now(), now()->addDays(5)])
            ->orderBy('court_date', 'asc')
            ->limit(5)
            ->get();
        $pendingTickets = Ticket::where('indicator', Ticket::INDICATOR_PENDING)->orWhereNull('indicator')->limit(5)->get();
        $logs = Log::limit(5)->latest()->get();

        $searchResults = [
            'tickets' => collect(),
            'drivers' => collect(),
            'companies' => collect(),
        ];

        if ($search !== '') {
            $searchResults['tickets'] = Ticket::withoutGlobalScopes()
                ->with([
                    'company.parentCompany',
                    'attorney.user',
                    'driver.user',
                ])
                ->where(function ($query) use ($search) {
                    $query->where('id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('user_email', 'like', "%{$search}%")
                        ->orWhere('ticket_number', 'like', "%{$search}%")
                        ->orWhere('citation_no', 'like', "%{$search}%")
                        ->orWhere('sf_id', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('dot', 'like', "%{$search}%")
                                ->orWhere('sf_id', 'like', "%{$search}%");
                        });
                })
                ->latest()
                ->limit(5)
                ->get();

            $searchResults['drivers'] = User::role(User::ROLE_DRIVER)
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                ->with('roleable.company')
                ->latest()
                ->limit(5)
                ->get();

            $searchResults['companies'] = Company::query()
                ->with(['parentCompany', 'childCompanies', 'contacts'])
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('dot', 'like', "%{$search}%")
                        ->orWhere('ct_email', 'like', "%{$search}%")
                        ->orWhere('sf_id', 'like', "%{$search}%");
                })
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('admin.dashboard', compact('stats', 'upComingCourtDates', 'pendingTickets', 'logs', 'search', 'searchResults'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        if (request()->ajax()) {
            $data = User::role(User::internalAdminRoles());

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
            $portal = auth()->user()->portalRoutePrefix();
            $data->getCollection()->transform(function ($item) use ($portal) {
                $item->role_label = User::internalAdminRoleOptions()[$item->getRoleNames()->first()] ?? 'Admin';
                $item->last_login_at = $item->last_login_at ? \Carbon\Carbon::parse($item->last_login_at)->diffForHumans() : 'Never';
                $item->action = '<a href="'.route($portal.'.admins.edit', $item->roleable->id).'" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-edit text-xl leading-none"></i>
                                    </a>
                                    <form action="'.route($portal.'.admins.destroy', $item->roleable->id).'" method="POST" class="inline delete-admin-form">
                                        <input type="hidden" name="_method" value="DELETE">
                                        '. csrf_field() .'
                                        <button href="#" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-trash text-xl leading-none"></i>
                                        </button>';
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


        return view('admins.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $roleOptions = User::internalAdminRoleOptions();

        return view('admins.create', compact('roleOptions'));
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
            'role' => 'required|in:'.implode(',', array_keys(User::internalAdminRoleOptions())),
            'dob' => 'nullable|date',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'phone' => 'unique:users,phone',
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
        $admin = Admin::create([]);
        $user = $admin->user()->create($request->only([
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
        $user->assignRole($request->string('role')->toString());
        return redirect()->route(auth()->user()->portalRoutePrefix().'.admins.edit', $admin->id)->with('success', 'Administrator created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
        $roleOptions = User::internalAdminRoleOptions();

        return view('admins.edit', compact('admin', 'roleOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|in:'.implode(',', array_keys(User::internalAdminRoleOptions())),
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

        $admin->user()->update($data);
        $admin->user->syncRoles([$request->string('role')->toString()]);

        return redirect()->route(auth()->user()->portalRoutePrefix().'.admins.edit', $admin->id)->with('success', 'Administrator updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
        $admin->user()->delete();
        $admin->delete();
        return redirect()->route(auth()->user()->portalRoutePrefix().'.admins.index')->with('success', 'Administrator deleted successfully.');
    }
}
