<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function markAllAsRead()
    {
        $user = auth()->user();

        // Ensure the user is authenticated
        if ($user) {
            $user->unreadNotifications->markAsRead();
            return response()->json(['message' => 'All notifications marked as read.'], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function markAsRead($id)
    {
        $user = auth()->user();

        // Ensure the user is authenticated
        if ($user) {
            $notification = $user->unreadNotifications->where('id', $id)->first();

            if ($notification) {
                $notification->markAsRead();
                return response()->json(['message' => 'Notification marked as read.'], 200);
            }

            return response()->json(['message' => 'Notification not found.'], 404);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

}
