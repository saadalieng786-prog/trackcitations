<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Events\MessageRead;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageReadController extends Controller
{
    //
    public function store(Request $request, Message $message)
    {
        // Ensure the user belongs to the conversation
        if (!$message->conversation->users->contains(auth()->id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $read = MessageRead::updateOrCreate([
            'message_id' => $message->id,
            'user_id' => auth()->id(),
        ]);

        broadcast(new MessageRead($read))->toOthers();

        return response()->json($read);
    }
    public function storeBatch(Conversation $currentConversation)
    {
        $user = auth()->user();

        // Ensure the user is part of the conversation
        if (!$currentConversation->users->contains($user->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Mark all unread messages as read
        $currentConversation->messages()
            ->whereNull('read_at') // Only unread messages
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All messages marked as read']);
    }
}
