<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Events\MessageRead;
use App\Events\NewMessage;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageAttachment;
use Illuminate\Http\Request;
use App\Support\AttachmentStorage;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    //

    public function store(Request $request, $conversationId)
    {
        $request->validate([
            'content' => 'required|string',
            'attachments' => 'nullable|file|max:5120|mimes:jpeg,png,jpg,gif,svg,heic,heif,pdf,doc,docx',
            'conversation_id' => 'required|exists:conversations,id',
        ]);
        $currentConversation = Conversation::findOrFail($conversationId);
        if (!$currentConversation->users->contains(auth()->id())) {
            abort(403);
        }

        // Step 1: Create the message
        $message = Message::create([
            'content' => $request->input('content'),
            'conversation_id' => $request->conversation_id,
            'sender_id' => auth()->id(),  // Assuming the user is authenticated
        ]);

        // Step 2: Handle file upload if a file is provided
        if ($request->hasFile('attachments')) {
            $file = $request->file('attachments');
            $filename = auth()->user()->name . '-' . time() . '.' . $file->getClientOriginalExtension();
            $stored = AttachmentStorage::storeMessageUpload($file, 'attachments/messages', $filename);

            // Step 3: Create an attachment record in the database
            $attachment = MessageAttachment::create([
                'message_id' => $message->id,
                'file_path' => $stored['url'],
                'file_name' => basename($stored['path']),
            ]);
        }

        // Step 4: Broadcast the message event (optional)
        broadcast(new \App\Events\MessageSent($message))->toOthers();

        // Step 5: Return a response
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'attachment' => $attachment ?? null,
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = auth()->user();
        $conversationId = $request->conversation_id;

        // Fetch all unread messages in the conversation
        $unreadMessages = Message::where('conversation_id', $conversationId)
            ->whereDoesntHave('reads', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        if ($unreadMessages->isEmpty()) {
            return response()->json(['status' => 'success', 'message' => 'No unread messages']);
        }

        // Create read entries for all unread messages
        foreach ($unreadMessages as $message) {
            if ($message->sender_id === $user->id) {
                continue;
            }
            $message->reads()->create(['user_id' => $user->id]);

            // Broadcast a MessageRead event for each message
            broadcast(new MessageRead($message))->toOthers();
        }

        return response()->json(['status' => 'success', 'message' => 'All messages marked as read']);
    }
    public function markAsRead(Request $request)
    {
        $user = auth()->user();
        $messageId = $request->message_id;

        $message = Message::findOrFail($messageId);

        // Check if the user is part of the conversation
        if (!$message->conversation->users->contains($user->id)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if ($message->sender_id === $user->id) {
            return response()->json(['status' => 'success', 'message' => 'Can\'t mark own message as read.']);
        }

        // Create a read entry if not already marked
        $message->reads()->firstOrCreate(['user_id' => $user->id]);

        // Broadcast a single MessageRead event
        broadcast(new MessageRead($message, $user))->toOthers();

        return response()->json(['status' => 'success', 'message' => 'Message marked as read']);
    }
}
