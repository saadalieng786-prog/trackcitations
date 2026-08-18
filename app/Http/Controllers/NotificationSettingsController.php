<?php

namespace App\Http\Controllers;

use App\Models\NotificationRoleSetting;
use Illuminate\Http\Request;

class NotificationSettingsController extends Controller
{
    protected function ensureSystemSettingsManager(): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageSystemSettings(), 403);
    }

    public function index()
    {
        $this->ensureSystemSettingsManager();

        $settings = NotificationRoleSetting::allMapped();
        $labels = NotificationRoleSetting::roleLabels();

        return view('admin.notifications.index', compact('settings', 'labels'));
    }

    public function update(Request $request)
    {
        $this->ensureSystemSettingsManager();

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
