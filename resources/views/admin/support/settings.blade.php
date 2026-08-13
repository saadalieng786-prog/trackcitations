@extends('layout.master')

@section('content')
@php $portal = auth()->user()->portalRoutePrefix(); @endphp

<div class="col-span-12">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 m-0">Support Settings</h1>
            <p class="text-sm text-slate-500 mt-1 mb-0">
                Set the global email recipient(s) for all support form submissions. Support emails are sent only to the addresses listed here — if this is blank, no support email is sent.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="mb-0 ps-5 list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xl:col-span-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Support email recipients</h5></div>
                <div class="card-body">
                    <form method="POST" action="{{ route($portal.'.support.settings.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label font-semibold" for="recipient_emails">Recipient email(s)</label>
                            <textarea
                                class="form-control"
                                id="recipient_emails"
                                name="recipient_emails"
                                rows="4"
                                placeholder="support@example.com, ops@example.com"
                            >{{ old('recipient_emails', $setting->recipient_emails) }}</textarea>
                            <div class="text-xs text-slate-500 mt-2">
                                Enter one or more emails separated by commas, spaces, or new lines.
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary">Save settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
