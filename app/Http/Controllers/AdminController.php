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
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $search = trim((string) $request->input('q', ''));
        [$dateFilter, $startDate, $endDate] = $this->resolveDashboardDateFilter(
            $request->input('period'),
            $request->input('from'),
            $request->input('to')
        );

        $from = $startDate->toDateString();
        $to = $endDate->toDateString();

        $stats = [
            'tickets' => Ticket::active()
                ->where(function ($query) use ($from, $to) {
                    $query->where(function ($issued) use ($from, $to) {
                        $issued->whereDate('date_issued', '>=', $from)
                            ->whereDate('date_issued', '<=', $to);
                    })->orWhere(function ($inner) use ($from, $to) {
                        $inner->whereNull('date_issued')
                            ->whereDate('created_at', '>=', $from)
                            ->whereDate('created_at', '<=', $to);
                    });
                })
                ->count(),
            'drivers' => User::role('driver')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->count(),
            'attorneys' => User::role('attorney')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->count(),
            'companies' => Company::query()
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->count(),
            'closed_tickets' => Ticket::where('status', Ticket::TICKET_STATUS_CLOSED)
                ->whereDate('updated_at', '>=', $from)
                ->whereDate('updated_at', '<=', $to)
                ->count(),
            'points_saved' => (float) (Ticket::withoutGlobalScopes()
                ->where(function ($query) use ($from, $to) {
                    $query->where(function ($issued) use ($from, $to) {
                        $issued->whereDate('date_issued', '>=', $from)
                            ->whereDate('date_issued', '<=', $to);
                    })->orWhere(function ($inner) use ($from, $to) {
                        $inner->whereNull('date_issued')
                            ->whereDate('created_at', '>=', $from)
                            ->whereDate('created_at', '<=', $to);
                    });
                })
                ->selectRaw('COALESCE(SUM('.Ticket::pointsSavedSql().'), 0) as aggregate')
                ->value('aggregate') ?? 0),
        ];
        $upComingCourtDates = Ticket::whereBetween('court_date', [now(), now()->addDays(5)])
            ->orderBy('court_date', 'asc')
            ->limit(5)
            ->get();
        $pendingTickets = Ticket::where('indicator', Ticket::INDICATOR_PENDING)
            ->orWhereNull('indicator')
            ->limit(5)
            ->get();
        $logs = Log::limit(5)->latest()->get();

        $chartPeriod = in_array($request->input('chart'), ['this_month', 'this_year', 'last_year'], true)
            ? $request->input('chart')
            : 'this_year';
        $ticketOverview = $this->buildTicketOverviewChart($chartPeriod);
        $chartPeriodLabel = match ($chartPeriod) {
            'this_month' => 'This Month',
            'last_year' => 'Last Year',
            default => 'This Year',
        };

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

        $dateRangeLabel = match ($dateFilter) {
            'this_year' => 'This Year',
            'last_year' => 'Last Year',
            default => $startDate->format('M j, Y').' – '.$endDate->format('M j, Y'),
        };

        return view('admin.dashboard', compact(
            'stats',
            'upComingCourtDates',
            'pendingTickets',
            'logs',
            'search',
            'searchResults',
            'startDate',
            'endDate',
            'dateFilter',
            'dateRangeLabel',
            'ticketOverview',
            'chartPeriod',
            'chartPeriodLabel'
        ));
    }

    /**
     * @return array{0: string, 1: Carbon, 2: Carbon}
     */
    protected function resolveDashboardDateFilter(?string $period, ?string $from, ?string $to): array
    {
        $period = in_array($period, ['this_year', 'last_year', 'custom'], true) ? $period : 'this_year';

        if ($period === 'last_year') {
            return [
                'last_year',
                now()->subYear()->startOfYear()->startOfDay(),
                now()->subYear()->endOfYear()->startOfDay(),
            ];
        }

        if ($period === 'custom') {
            $startDate = $this->parseDashboardDate($from) ?? now()->startOfYear()->startOfDay();
            $endDate = $this->parseDashboardDate($to) ?? now()->endOfYear()->startOfDay();

            if ($startDate->gt($endDate)) {
                [$startDate, $endDate] = [$endDate, $startDate];
            }

            return ['custom', $startDate->startOfDay(), $endDate->startOfDay()];
        }

        return [
            'this_year',
            now()->startOfYear()->startOfDay(),
            now()->endOfYear()->startOfDay(),
        ];
    }

    protected function parseDashboardDate(?string $date): ?Carbon
    {
        if (! is_string($date) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function buildTicketOverviewChart(string $period): array
    {
        if ($period === 'this_year') {
            $start = now()->startOfYear()->startOfDay();
            $end = now()->endOfYear()->endOfDay();
            $groupFormat = '%Y-%m';
            $labelFormat = 'M';
            $points = 12;
            $cursor = $start->copy();
            $labels = [];
            for ($i = 0; $i < $points; $i++) {
                $labels[] = $cursor->copy()->addMonthsNoOverflow($i)->format($labelFormat);
            }
        } elseif ($period === 'last_year') {
            $start = now()->subYear()->startOfYear()->startOfDay();
            $end = now()->subYear()->endOfYear()->endOfDay();
            $groupFormat = '%Y-%m';
            $labelFormat = 'M';
            $points = 12;
            $cursor = $start->copy();
            $labels = [];
            for ($i = 0; $i < $points; $i++) {
                $labels[] = $cursor->copy()->addMonthsNoOverflow($i)->format($labelFormat);
            }
        } else {
            $start = now()->startOfMonth()->startOfDay();
            $end = now()->endOfMonth()->endOfDay();
            $groupFormat = '%Y-%m-%d';
            $labelFormat = 'j';
            $points = (int) $start->daysInMonth;
            $labels = [];
            for ($i = 1; $i <= $points; $i++) {
                $labels[] = (string) $i;
            }
        }

        $base = Ticket::withoutGlobalScopes()
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('date_issued', [$start->toDateString(), $end->toDateString()])
                    ->orWhere(function ($inner) use ($start, $end) {
                        $inner->whereNull('date_issued')
                            ->whereBetween('created_at', [$start, $end]);
                    });
            });

        $rows = (clone $base)
            ->selectRaw("DATE_FORMAT(COALESCE(date_issued, created_at), '{$groupFormat}') as bucket")
            ->selectRaw('SUM(CASE WHEN status IS NULL OR status NOT IN (?, ?) THEN 1 ELSE 0 END) as open_count', [
                Ticket::TICKET_STATUS_CLOSED,
                Ticket::TICKET_STATUS_ARCHIVED,
            ])
            ->selectRaw('SUM(CASE WHEN indicator = ? OR indicator IS NULL THEN 1 ELSE 0 END) as pending_count', [
                Ticket::INDICATOR_PENDING,
            ])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as closed_count', [
                Ticket::TICKET_STATUS_CLOSED,
            ])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->keyBy('bucket');

        $open = [];
        $pending = [];
        $closed = [];

        for ($i = 0; $i < $points; $i++) {
            if ($period === 'this_month') {
                $key = $start->copy()->addDays($i)->format('Y-m-d');
            } else {
                $key = $start->copy()->addMonthsNoOverflow($i)->format('Y-m');
            }

            $row = $rows->get($key);
            $open[] = (int) ($row->open_count ?? 0);
            $pending[] = (int) ($row->pending_count ?? 0);
            $closed[] = (int) ($row->closed_count ?? 0);
        }

        $max = max(1, max(array_merge($open, $pending, $closed)));

        return [
            'labels' => $labels,
            'open' => $open,
            'pending' => $pending,
            'closed' => $closed,
            'max' => $max,
        ];
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
        $request->merge([
            'phone' => $request->filled('phone') ? $request->phone : null,
        ]);

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
            'phone' => 'nullable|unique:users,phone',
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
        $user = $admin->user;
        if (! $user) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'This administrator does not have a linked user account.',
            ]);
        }

        $request->merge([
            'phone' => $request->filled('phone') ? $request->phone : null,
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:'.implode(',', array_keys(User::internalAdminRoleOptions())),
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
            $request->filled('password') ? ['password'] : []
        ));

        $user->update($data);
        $user->syncRoles([$request->string('role')->toString()]);

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
