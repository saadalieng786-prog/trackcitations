<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Log;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CourtDateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $title = 'Upcoming Court Dates';

        $upComingCourtDatesQuery = Ticket::query()
            ->whereBetween('court_date', [now(), now()->addDays(5)])
            ->orderBy('court_date', 'asc');

        if ($user) {
            if ($user->hasRole(User::ROLE_DRIVER)) {
                // Drivers only see their own tickets.
                $upComingCourtDatesQuery->where('user_email', $user->email);
            } else {
                $upComingCourtDatesQuery->filterByRole($user);
            }
        } else {
            $upComingCourtDatesQuery->whereRaw('1 = 0');
        }

        $upComingCourtDates = $upComingCourtDatesQuery
            ->limit(30)
            ->get();

        $stats = [
            'tickets' => Ticket::active()->filterByRole($user)->count(),
            'drivers' => User::role('driver')->count(),
            'attorneys' => User::role('attorney')->count(),
            'companies' => Company::count(),
        ];
        $pendingTickets = Ticket::query()
            ->filterByRole($user)
            ->where(function ($query) {
                $query->where('indicator', Ticket::INDICATOR_PENDING)->orWhereNull('indicator');
            })
            ->limit(5)
            ->get();
        $logs = Log::limit(5)->latest()->get();

        return view('admin.upcoming_court_date', compact('stats', 'upComingCourtDates', 'pendingTickets', 'logs', 'title'));
    }
}
