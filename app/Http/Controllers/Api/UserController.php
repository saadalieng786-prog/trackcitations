<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function exclude(?Conversation $conversation)
    {
        if ($conversation) {
            return User::whereDoesntHave('conversations', function ($query) use ($conversation) {
                $query->where('conversations.id', $conversation->id);
            })->get(['id', 'name']);
        }
        return User::get(['id', 'name']);
    }
}
