<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        return auth()->user()->conversations()->with('users')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|array|min:1',
            'user_id.*' => 'required|integer|distinct|exists:users,id',
        ]);

        $conversation = Conversation::create(['name' => $request->name]);
        $participantIds = collect($request->user_id)
            ->map(fn ($id) => (int) $id)
            ->push((int) auth()->id())
            ->unique()
            ->values()
            ->all();
        $conversation->users()->attach($participantIds);

        return redirect()->route('messaging.show', $conversation->id)->with('success', 'Conversation Created Successfully.');
    }

    public function mainIndex()
    {
        $conversations = auth()->user()
            ->conversations()
            ->with(['messages' => fn ($q) => $q->latest()->limit(1)])
            ->get();

        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('messaging.index', compact('conversations', 'users'));
    }

    public function mainShow(Conversation $currentConversation)
    {
        if (! $currentConversation->users()->where('users.id', auth()->id())->exists()) {
            abort(403);
        }

        $currentConversation->load([
            'users.roles',
            'messages' => fn ($q) => $q->orderBy('created_at'),
            'messages.sender',
            'messages.attachments',
            'messages.reads',
        ]);

        $conversations = auth()->user()
            ->conversations()
            ->with(['messages' => fn ($q) => $q->latest()->limit(1)])
            ->get();

        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->whereDoesntHave('conversations', function ($query) use ($currentConversation) {
                $query->where('conversations.id', $currentConversation->id);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        $unread = $currentConversation->messages
            ->where('sender_id', '!=', auth()->id())
            ->filter(fn ($message) => ! $message->reads->contains('user_id', auth()->id()));

        foreach ($unread as $message) {
            $message->reads()->firstOrCreate(['user_id' => auth()->id()]);
        }

        return view('messaging.show', compact('currentConversation', 'conversations', 'users'));
    }

    public function addUser(Request $request, Conversation $conversation)
    {
        if (! auth()->user()->isInternalAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = (int) $request->input('user_id');

        if ($conversation->users()->where('users.id', $userId)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User is already in the conversation.'], 400);
        }

        $conversation->users()->attach($userId);

        return response()->json(['status' => 'success', 'message' => 'User added to the conversation.']);
    }

    public function removeUser(Conversation $conversation, User $user)
    {
        if (! auth()->user()->isInternalAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if (! $conversation->users()->where('users.id', $user->id)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User is not part of the conversation.'], 400);
        }

        $conversation->users()->detach($user->id);

        return response()->json(['status' => 'success', 'message' => 'User removed from the conversation.']);
    }
}
