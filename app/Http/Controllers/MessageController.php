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
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use App\Support\AttachmentStorage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    //

    public function store(Request $request, $conversationId)
    {
        $request->validate([
            'content' => 'nullable|string',
            'attachments' => 'nullable|file|max:5120|mimes:jpeg,png,jpg,gif,svg,heic,heif,pdf,doc,docx',
            'conversation_id' => 'nullable|exists:conversations,id',
        ]);

        if (! $request->filled('content') && ! $request->hasFile('attachments')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Message content or attachment is required.',
            ], 422);
        }

        $currentConversation = Conversation::findOrFail($conversationId);
        if (! $currentConversation->users()->where('users.id', auth()->id())->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not a participant in this conversation.',
            ], 403);
        }

        $message = Message::create([
            'content' => (string) $request->input('content', ''),
            'conversation_id' => $currentConversation->id,
            'sender_id' => auth()->id(),
        ]);

        $currentConversation->touch();

        $attachment = null;
        if ($request->hasFile('attachments')) {
            $file = $request->file('attachments');
            if (! $file->isValid()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Uploaded file is invalid: '.$file->getErrorMessage(),
                ], 422);
            }

            try {
                $extension = strtolower((string) $file->getClientOriginalExtension());
                if ($extension === '') {
                    $extension = strtolower((string) $file->extension()) ?: 'bin';
                }

                $filename = 'msg-'.auth()->id().'-'.time().'-'.Str::lower(Str::random(6)).'.'.$extension;
                $stored = AttachmentStorage::storeMessageUpload($file, 'attachments/messages', $filename);

                $attachment = MessageAttachment::create([
                    'message_id' => $message->id,
                    'file_path' => $stored['path'],
                    'file_name' => $file->getClientOriginalName() ?: basename($stored['path']),
                ]);
            } catch (\Throwable $e) {
                report($e);
                \Log::error('Message attachment upload failed', [
                    'message_id' => $message->id,
                    'user_id' => auth()->id(),
                    'disk' => AttachmentStorage::messageDisk(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'error' => $e->getMessage(),
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Message saved, but attachment upload failed: '.$e->getMessage(),
                ], 500);
            }
        }

        try {
            broadcast(new \App\Events\MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            report($e);
        }

        $message->load(['sender', 'attachments', 'conversation']);

        try {
            $recipients = $currentConversation->users()
                ->where('users.id', '!=', auth()->id())
                ->whereNotNull('email')
                ->get();

            if ($recipients->isNotEmpty()) {
                Notification::send($recipients, new NewMessageNotification($message));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json([
            'status' => 'success',
            'id' => $message->id,
            'message' => $message,
            'attachment' => $attachment,
        ]);
    }

    public function downloadAttachment(MessageAttachment $attachment)
    {
        $attachment->loadMissing('message.conversation');
        $conversation = $attachment->message?->conversation;

        if (! $conversation || ! $conversation->users()->where('users.id', auth()->id())->exists()) {
            abort(403);
        }

        $disk = AttachmentStorage::messageDisk();
        $relativePath = AttachmentStorage::relativePathFromStoredPath($attachment->file_path);

        if (blank($relativePath)) {
            abort(404, 'Attachment path is missing.');
        }

        $disksToTry = array_values(array_unique([
            AttachmentStorage::messageDisk(),
            AttachmentStorage::ticketDisk(),
            'public',
            'local',
        ]));

        foreach ($disksToTry as $tryDisk) {
            try {
                if (! config("filesystems.disks.{$tryDisk}")) {
                    continue;
                }

                if (! Storage::disk($tryDisk)->exists($relativePath)) {
                    continue;
                }

                if (config("filesystems.disks.{$tryDisk}.driver") === 's3') {
                    return redirect()->away(
                        Storage::disk($tryDisk)->temporaryUrl($relativePath, now()->addMinutes(30))
                    );
                }

                return Storage::disk($tryDisk)->response(
                    $relativePath,
                    $attachment->file_name ?: basename($relativePath),
                    [],
                    'inline'
                );
            } catch (\Throwable $e) {
                report($e);
            }
        }

        abort(404, 'Attachment file was not found in storage. It may have been uploaded before Spaces was connected. Please re-upload the file.');
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
            try {
                broadcast(new MessageRead($message))->toOthers();
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'All messages marked as read']);
    }
    public function markAsRead(Request $request)
    {
        $user = auth()->user();
        $messageId = $request->message_id;

        $message = Message::findOrFail($messageId);

        // Check if the user is part of the conversation
        if (! $message->conversation->users()->where('users.id', $user->id)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if ($message->sender_id === $user->id) {
            return response()->json(['status' => 'success', 'message' => 'Can\'t mark own message as read.']);
        }

        // Create a read entry if not already marked
        $message->reads()->firstOrCreate(['user_id' => $user->id]);

        // Broadcast a single MessageRead event
        try {
            broadcast(new MessageRead($message))->toOthers();
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['status' => 'success', 'message' => 'Message marked as read']);
    }
}
