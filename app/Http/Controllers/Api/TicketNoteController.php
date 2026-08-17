<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketNote;
use App\Models\User;
use App\Notifications\TicketNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TicketNoteController extends Controller
{
    use AuthorizesRequests;
    //
    public function store(Request $request, Ticket $ticket) {
        $this->authorize('update', $ticket);

        $request->validate([
            'note' => 'required|string',
        ]);

        $note = TicketNote::query()->create([
            'ticket_id' => $ticket->id,
            'note' => $request->input('note'),
            'is_public' => $request->boolean('is_public'),
            'user_id' => $request->user()->id,
        ]);

        $note->load('user');

        if ($note->is_public) {
            $admins = User::role(User::internalAdminRoles())->get();
            $driver = $ticket->driver?->user;
            $usersToNotify = $driver
                ? $admins->merge(collect([$driver]))
                : $admins;
            Notification::send($usersToNotify, new TicketNotification($ticket, 'response'));
        }

        return response()->json($note, 201);
    }
}
