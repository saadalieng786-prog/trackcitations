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
            'name' => 'required|string',
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
        $conversations = auth()->user()->conversations;
        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('messaging.index', compact('conversations', 'users'));
    }

    public function mainShow(Conversation $currentConversation)
    {
        $conversations = auth()->user()->conversations;
        if (!$currentConversation->users()->where('users.id', auth()->id())->exists()) {
            abort(403);
        }

        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->whereDoesntHave('conversations', function ($query) use ($currentConversation) {
                $query->where('conversations.id', $currentConversation->id);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('messaging.show', compact('currentConversation', 'conversations', 'users'));
    }

    // Add a user to the conversation
    public function addUser(Request $request, Conversation $conversation)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->input('user_id');

        // Check if the user is already in the conversation
        if ($conversation->users()->where('user_id', $userId)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User is already in the conversation.'], 400);
        }

        // Add the user to the conversation
        $conversation->users()->attach($userId);

        return response()->json(['status' => 'success', 'message' => 'User added to the conversation.']);
    }

    // Remove a user from the conversation
    public function removeUser(Conversation $conversation, User $user)
    {
        // Check if the user is part of the conversation
        if (!$conversation->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User is not part of the conversation.'], 400);
        }

        // Remove the user from the conversation
        $conversation->users()->detach($user->id);

        return response()->json(['status' => 'success', 'message' => 'User removed from the conversation.']);
    }
}
