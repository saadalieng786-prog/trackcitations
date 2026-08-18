<?php

namespace App\Http\Controllers;

use App\Models\SupportSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SupportSettingsController extends Controller
{
    protected function ensureSystemSettingsManager(): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageSystemSettings(), 403);
    }

    public function index()
    {
        $this->ensureSystemSettingsManager();

        $setting = SupportSetting::current();

        return view('admin.support.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $this->ensureSystemSettingsManager();

        $validated = $request->validate([
            'recipient_emails' => 'nullable|string|max:2000',
        ]);

        $raw = trim((string) ($validated['recipient_emails'] ?? ''));
        $emails = [];

        if ($raw !== '') {
            $parts = preg_split('/[\s,;]+/', $raw) ?: [];
            $emails = collect($parts)
                ->map(fn ($email) => strtolower(trim($email)))
                ->filter()
                ->unique()
                ->values()
                ->all();

            $invalid = collect($emails)->reject(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))->values()->all();
            if ($invalid !== []) {
                throw ValidationException::withMessages([
                    'recipient_emails' => 'Invalid email address(es): '.implode(', ', $invalid),
                ]);
            }
        }

        $setting = SupportSetting::current();
        $setting->update([
            'recipient_emails' => $emails === [] ? null : implode(', ', $emails),
        ]);
        SupportSetting::clearCache();

        return redirect()
            ->route(auth()->user()->portalRoutePrefix().'.support.settings')
            ->with('success', 'Support recipient settings updated successfully.');
    }
}
