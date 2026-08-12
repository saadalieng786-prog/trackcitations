<?php

namespace App\Http\Controllers;

use App\Models\NotificationRoleSetting;
use Illuminate\Http\Request;

class NotificationSettingsController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasAnyRole(['super_admin', 'staff_admin']), 403);

        $settings = NotificationRoleSetting::allMapped();
        $labels = NotificationRoleSetting::roleLabels();

        return view('admin.notifications.index', compact('settings', 'labels'));
    }

    public function update(Request $request)
    {
        abort_unless(auth()->user()->hasAnyRole(['super_admin', 'staff_admin']), 403);

        $roles = array_keys(NotificationRoleSetting::defaults());

        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'nullable|boolean',
        ]);

        $rolesEnabled = [];
        foreach ($roles as $role) {
            $rolesEnabled[$role] = (bool) data_get($validated, "roles.{$role}", false);
        }

        NotificationRoleSetting::syncFromRequest($rolesEnabled);

        return redirect()
            ->route(auth()->user()->portalRoutePrefix().'.notifications.settings')
            ->with('success', 'Notification role settings updated successfully.');
    }
}
