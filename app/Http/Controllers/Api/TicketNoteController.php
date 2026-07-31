<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class TicketNoteController extends Controller
{
    use AuthorizesRequests;
    //
    public function store(Request $request, Ticket $ticket) {
        $this->authorize('update', $ticket);

        $request->validate([
            'note' => 'required'
        ]);

        if (!Ticket::notesTableExists()) {
            return response()->json([
                'message' => 'Ticket notes are temporarily unavailable on this environment until the notes table is repaired.',
            ], 503);
        }

        $request->merge([
            'is_public' => $request->has('is_public') && $request->get('is_public') === true,
        ]);

        $note = $ticket->notes()->create([
            'note' => request('note'),
            'is_public' => request('is_public'),
            'user_id' => \request()->user()->id
        ]);

        if ($request->is_public) {
            $admins = User::role(User::internalAdminRoles())->get();
            $driver = $ticket->driver?->user;
            $usersToNotify = $driver
                ? $admins->merge(collect([$driver]))
                : $admins;
            Notification::send($usersToNotify, new TicketNotification($ticket, 'response'));
        }

        return $note;
    }
}
