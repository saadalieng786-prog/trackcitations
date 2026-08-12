<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Driver;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DriverController extends Controller
{
    use AuthorizesRequests;  // Ensure this is included

    public function dashboard()
    {
        $user = request()->user();
        $driver = $user->roleable;
        $ticketQuery = Ticket::whereHas('driver')
            ->filterByRole($user)
            ->with(['company', 'attorney.user']);
        $tickets = (clone $ticketQuery)
            ->latest('updated_at')
            ->limit(5)
            ->get();
        $upcomingTicket = (clone $ticketQuery)
            ->active()
            ->whereBetween('court_date', [now(), now()->addDays(14)])
            ->orderBy('court_date', 'asc')
            ->first();
        $recentClosedTickets = (clone $ticketQuery)
            ->where('status', Ticket::TICKET_STATUS_CLOSED)
            ->latest('updated_at')
            ->limit(3)
            ->get();
        $stats = [
            'open_tickets' => $driver instanceof Driver ? $driver->openTicketsCount() : 0,
            'closed_tickets' => $driver instanceof Driver ? $driver->closedTicketsCount() : 0,
            'points_saved' => $driver instanceof Driver ? $driver->lifetimePointsSaved() : 0,
        ];

        return view('driver.dashboard', compact('tickets', 'stats', 'upcomingTicket', 'recentClosedTickets'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        if (request()->ajax()) {
            if ($request->has('status') && (int) $request->get('status') === 0) {
                $data = Ticket::select('name', 'city', 'state', 'company_id', DB::raw('id AS ticket_id'))->filterByRole(request()->user())->whereDoesntHave('driver')->with('company');
            } else {
                $data = User::role('driver')
                    ->with('roleable.company')
                    ->filterByRole(request()->user());
            }

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
                $columns = ['id', 'name', 'city', 'state', 'last_login_at']; // Adjust to match your table columns
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
                $item->id = $item->id ?: '';
                $item->last_login_at = $item->last_login_at ? \Carbon\Carbon::parse($item->last_login_at)->diffForHumans() : 'Never';
                $item->company_name = $item->roleable ? optional($item->roleable->company)->name : optional($item->company)->name;
                $item->open_tickets = $item->roleable ? $item->roleable->openTicketsCount() : '';
                $item->closed_tickets = $item->roleable ? $item->roleable->closedTicketsCount() : '';
                $item->lifetime_points_saved = $item->roleable ? number_format($item->roleable->lifetimePointsSaved(), 2, '.', '') : '';
                $item->action  = '';
                if ($item->roleable) {
                    if (auth()->user()->isInternalAdmin() || auth()->user()->canWriteCompany((int) $item->roleable->company_id)) {
                        $item->action .= '<a href="'.route(auth()->user()->portalRoutePrefix().'.drivers.edit', $item->roleable->id).'" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-edit text-xl leading-none"></i>
                                    </a>';
                    }


                    if (auth()->user()->isInternalAdmin()) {
                        $item->action .= '<form action="'.route(auth()->user()->portalRoutePrefix().'.drivers.destroy', $item->roleable->id).'" method="POST" class="inline delete-manager-form">
                                        <input type="hidden" name="_method" value="DELETE">
                                        '. csrf_field() .'
                                        <button href="#" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-trash text-xl leading-none"></i>
                                        </button>';
                    }
                } else {
                    $item->action .= '
                    <a href="' . route(auth()->user()->portalRoutePrefix().'.drivers.create', ['ticket_id' => $item->ticket_id]). '" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-success" title="Register this driver">
                                                <i class="ti ti-user-plus text-xl leading-none"></i>
                                            </a>';
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




        return view('drivers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $ticket = null;
        if ($request->get('ticket_id')) {
            // Find the ticket and authorize the view action
            $ticket = Ticket::findOrFail($request->get('ticket_id'));
            $this->authorize('view', $ticket);
        }

        $this->authorize('create', new Driver());


        return view('drivers.create', compact('ticket'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->authorize('create', new Driver());

        $request->merge([
            'phone' => $request->filled('phone') ? $request->phone : null,
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'dob' => 'nullable|date',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'phone' => 'nullable|unique:users,phone',
            'timezone' => '',
            'notification_email' => '',
            'notification_sms' => '',
            'notification_push' => '',
            'company_id' => 'required|exists:companies,id'
        ]);
        $request->merge([
            'notification_email' => $request->has('notification_email'),
            'notification_sms' => $request->has('notification_sms'),
            'notification_push' => $request->has('notification_push'),
        ]);
        $currentUser = \auth()->user();
        if ($currentUser->isCompanyAdmin() && !$currentUser->canWriteCompany((int) $request->company_id)) {
            $company = Company::findOrFail($request->company_id);
            throw ValidationException::withMessages([
                "company_id" => "You do not have write access to company {$company->name}."
            ]);
        }
        $driver = Driver::create([
            'company_id' => $request->get('company_id')
        ]);
        $user = $driver->user()->create($request->only([
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
        $user->assignRole('driver');
        return redirect()->route(auth()->user()->portalRoutePrefix().'.drivers.edit', $driver->id)->with('success', 'Driver created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        //
        $this->authorize('update', $driver);
        $driver->load('user');

        if (! $driver->user) {
            return redirect()
                ->route(auth()->user()->portalRoutePrefix().'.drivers.index')
                ->with('error', 'This driver record has no linked login user and cannot be edited. It was likely created by an incomplete Salesforce sync.');
        }

        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        //
        $this->authorize('update', $driver);

        $user = $driver->user;
        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'This driver does not have a linked user account.',
            ]);
        }

        $currentUser = \auth()->user();
        if ($currentUser->isCompanyAdmin() && !$currentUser->canWriteCompany((int) $request->company_id)) {
            $company = Company::findOrFail($request->company_id);
            throw ValidationException::withMessages([
                "company_id" => "You do not have write access to company {$company->name}."
            ]);
        }

        $request->merge([
            'phone' => $request->filled('phone') ? $request->phone : null,
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'dob' => 'nullable|date',
            'password' => 'nullable|string|min:8',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'phone' => 'nullable|unique:users,phone,'.$user->id,
            'timezone' => '',
            'notification_email' => '',
            'notification_sms' => '',
            'notification_push' => '',
            'company_id' => 'required|exists:companies,id'
        ]);
        $request->merge([
            'notification_email' => $request->has('notification_email'),
            'notification_sms' => $request->has('notification_sms'),
            'notification_push' => $request->has('notification_push'),
        ]);
        $driver->update(['company_id' => $request->company_id]);
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
            $request->filled('password') ? ['password'] : []
        ));

        $user->update($data);

        return redirect()->route(auth()->user()->portalRoutePrefix().'.drivers.edit', $driver->id)->with('success', 'Driver updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        //
        if (!auth()->user()->isInternalAdmin()) {
            abort(403);
        }
        $driver->user()->delete();

        $driver->delete();
        return redirect()->route(auth()->user()->portalRoutePrefix().'.drivers.index')->with('success', 'Driver deleted successfully.');
    }
}
