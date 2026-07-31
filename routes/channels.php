<?php

use Illuminate\Support\Facades\Broadcast;
Broadcast::routes(); // Remove or comment out this line

Broadcast::channel('conversations.{conversation}', function ($user, \App\Models\Conversation $conversation) {
    return $conversation->users->contains($user);
});
