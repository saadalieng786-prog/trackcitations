<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Listeners;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class UpdateLastLoginAt
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        //
        $user = $event->user;
        $user->last_login_at = now();
        $user->save();

        $user = auth()->user();
        Log::create([
            'action' => 'User Login',
            'description' => "User {$user->name} Logged in.",
            'user_id' => $user->id,
        ]);
    }
}
