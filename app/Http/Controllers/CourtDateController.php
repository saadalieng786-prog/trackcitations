<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Log;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class CourtDateController extends Controller{
    public function index()
    {
        $stats = [
            'tickets' => Ticket::active()->count(),
            'drivers' => User::role('driver')->count(),
            'attorneys' => User::role('attorney')->count(),
            'companies' => Company::count()
        ];
        $title = 'Up Coming Court Dates';
        $upComingCourtDates = Ticket::whereBetween('court_date', [now(), now()->addDays(5)])
            ->orderBy('court_date', 'asc')
            ->limit(30)
            ->get();
        $pendingTickets = Ticket::where('indicator', Ticket::INDICATOR_PENDING)->orWhereNull('indicator')->limit(5)->get();
        $logs = Log::limit(5)->latest()->get();
        return view('admin.upcoming_court_date', compact('stats', 'upComingCourtDates', 'pendingTickets', 'logs','title'));
    }

}
