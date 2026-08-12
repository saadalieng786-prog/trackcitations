@extends('layout.master')

@section('content')
@php $portal = auth()->user()->portalRoutePrefix(); @endphp

<style>
    .tc-role-switch.form-check.form-switch {
        display: inline-flex;
        align-items: center;
        margin: 0;
        padding: 0;
        min-height: auto;
    }
    .tc-role-switch .form-check-input {
        width: 2.5em !important;
        height: 1.35em !important;
        margin: 0 !important;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280,0,0,0.25%29'/%3e%3c/svg%3e") !important;
        background-position: left center !important;
        background-size: 1.35em 1.35em !important;
        background-repeat: no-repeat !important;
        border-radius: 9999px !important;
    }
    .tc-role-switch .form-check-input:checked {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e") !important;
        background-position: right center !important;
        border-color: rgb(var(--colors-primary-500));
        background-color: rgb(var(--colors-primary-500));
    }
</style>

<div class="col-span-12">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 m-0">Notification Settings</h1>
            <p class="text-sm text-slate-500 mt-1 mb-0">
                Master switches per role for in-app notifications only. Email and SMS are unchanged and still use each user’s preferences.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="mb-0 ps-5 list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xl:col-span-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Role notification switches</h5></div>
                <div class="card-body">
                    <form method="POST" action="{{ route($portal.'.notifications.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="overflow-x-auto">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Role</th>
                                        <th style="width: 100px;">In-app</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($labels as $role => $label)
                                        <tr>
                                            <td>
                                                <div class="font-semibold text-slate-800">{{ $label }}</div>
                                                <div class="text-xs text-slate-400">{{ $role }}</div>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch tc-role-switch">
                                                    <input type="hidden" name="roles[{{ $role }}]" value="0">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        role="switch"
                                                        id="role_{{ $role }}"
                                                        name="roles[{{ $role }}]"
                                                        value="1"
                                                        @checked((bool) old('roles.'.$role, $settings[$role] ?? false))
                                                    >
                                                    <label class="visually-hidden" for="role_{{ $role }}">Enable {{ $label }}</label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5 flex justify-end">
                            <button type="submit" class="btn btn-primary">Save settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">How this works</h5></div>
                <div class="card-body text-sm text-slate-600 space-y-3">
                    <p class="mb-0"><strong>On:</strong> That role gets in-app notifications and sees the header bell.</p>
                    <p class="mb-0"><strong>Off:</strong> No in-app notifications for that role, and the bell is hidden.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
