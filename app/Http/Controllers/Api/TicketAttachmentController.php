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
use Illuminate\Support\Facades\Notification;
use App\Support\AttachmentStorage;

class TicketAttachmentController extends Controller
{
    use AuthorizesRequests;
    //
    public function store(Request $request, Ticket $ticket) {
        $this->authorize('update', $ticket);

        $request->validate([
            'file' => 'required|max:10000|mimes:doc,docx,pdf,png,jpg'
        ]);

        $file = $request->file('file');
        $stored = AttachmentStorage::storeTicketUpload($file);

        $admins = User::role(User::internalAdminRoles())->get(); // Collection of internal admins
        $driver = $ticket->driver?->user;    // Single user instance or null
        $usersToNotify = $driver
            ? $admins->merge(collect([$driver]))
            : $admins; // Combine admins with the driver if it exists
        Notification::send($usersToNotify, new TicketNotification($ticket, 'document_uploaded'));

        return $ticket->attachments()->create([
            'filename' => $file->getClientOriginalName(),
            'path' => $stored['url'],
        ]);
    }
}
