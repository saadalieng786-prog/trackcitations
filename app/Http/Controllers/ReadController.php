<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageRead;
use Illuminate\Http\Request;

class ReadController extends Controller
{
    public function store(Request $request, $messageId)
    {
        $read = MessageRead::updateOrCreate([
            'message_id' => $messageId,
            'user_id' => auth()->id(),
        ]);

        return response()->json($read);
    }

    public function getMessageReads($messageId)
    {
        // Find the message or return a 404 response
        $message = Message::findOrFail($messageId);
        if (!$message->conversation->users->contains(auth()->id())) {
            abort(403);
        }
        // Get users who read the message
        $reads = $message->reads()
            ->with(['user:id,name'])
            ->get()
            ->map(function ($read) {
                return [
                    'id' => $read->id,
                    'user' => $read->user,
                    'read_at' => $read->created_at_for_humans, // Use the accessor
                ];
            });

        return response()->json([
            'status' => 'success',
            'message_id' => $message->id,
            'reads' => $reads,
        ]);
    }
}
