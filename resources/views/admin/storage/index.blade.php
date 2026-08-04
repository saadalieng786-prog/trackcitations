@extends('layout.master')

@section('content')
@php $portal = auth()->user()->portalRoutePrefix(); @endphp

<div class="col-span-12">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 m-0">Storage Settings</h1>
            <p class="text-sm text-slate-500 mt-1 mb-0">Configure attachment storage and verify disk connectivity.</p>
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
                <div class="card-header"><h5 class="mb-0">Storage Configuration</h5></div>
                <div class="card-body">
                    <form method="POST" action="{{ route($portal.'.storage.update') }}" class="grid grid-cols-12 gap-5">
                        @csrf
                        @method('PUT')

                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label">Default Filesystem</label>
                            <select name="filesystem_disk" class="form-select" required>
                                @foreach (['local' => 'Local', 'public' => 'Public', 's3' => 'Amazon S3'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('filesystem_disk', $settings['filesystem_disk']) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label">Ticket Attachments</label>
                            <select name="attachments_disk" class="form-select" required>
                                @foreach (['public' => 'Public', 's3' => 'Amazon S3'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('attachments_disk', $settings['attachments_disk']) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label">Message Attachments</label>
                            <select name="message_attachments_disk" class="form-select" required>
                                @foreach (['public' => 'Public', 's3' => 'Amazon S3'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('message_attachments_disk', $settings['message_attachments_disk']) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12"><hr class="my-1"><h6 class="mt-4 mb-0">Amazon S3 credentials</h6></div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Access Key ID</label>
                            <input name="aws_access_key_id" class="form-control" value="{{ old('aws_access_key_id', $settings['aws_access_key_id']) }}" autocomplete="off">
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Secret Access Key</label>
                            <input type="password" name="aws_secret_access_key" class="form-control" value="{{ old('aws_secret_access_key', $settings['aws_secret_access_key']) }}" autocomplete="new-password">
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label">Region</label>
                            <input name="aws_default_region" class="form-control" value="{{ old('aws_default_region', $settings['aws_default_region']) }}">
                        </div>
                        <div class="col-span-12 md:col-span-8">
                            <label class="form-label">Bucket</label>
                            <input name="aws_bucket" class="form-control" value="{{ old('aws_bucket', $settings['aws_bucket']) }}">
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Public URL (optional)</label>
                            <input type="url" name="aws_url" class="form-control" value="{{ old('aws_url', $settings['aws_url']) }}">
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Endpoint (optional)</label>
                            <input type="url" name="aws_endpoint" class="form-control" value="{{ old('aws_endpoint', $settings['aws_endpoint']) }}">
                        </div>
                        <div class="col-span-12">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="aws_use_path_style_endpoint" value="1" @checked(old('aws_use_path_style_endpoint', $settings['aws_use_path_style_endpoint']))>
                                <span>Use path-style endpoint</span>
                            </label>
                        </div>
                        <div class="col-span-12 flex justify-end">
                            <button class="btn btn-primary" type="submit">Save Storage Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-4 space-y-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Current Status</h5></div>
                <div class="card-body space-y-3">
                    <div class="flex justify-between"><span>Default disk</span><strong>{{ $status['default_disk'] }}</strong></div>
                    <div class="flex justify-between"><span>Ticket attachments</span><strong>{{ $status['attachments_disk'] }}</strong></div>
                    <div class="flex justify-between"><span>Message attachments</span><strong>{{ $status['message_attachments_disk'] }}</strong></div>
                    <div class="flex justify-between"><span>S3 configured</span><strong>{{ $status['s3_ready'] ? 'Yes' : 'No' }}</strong></div>
                    <div class="flex gap-2 pt-3">
                        @foreach (['public', 's3'] as $disk)
                            <form method="POST" action="{{ route($portal.'.storage.test') }}">
                                @csrf
                                <input type="hidden" name="disk" value="{{ $disk }}">
                                <button class="btn btn-outline-secondary btn-sm" type="submit">Test {{ strtoupper($disk) }}</button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Attachment Diagnostics</h5></div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Ticket total</div><strong>{{ number_format($diagnostics['ticket_attachments_total']) }}</strong></div>
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Message total</div><strong>{{ number_format($diagnostics['message_attachments_total']) }}</strong></div>
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Ticket local</div><strong>{{ number_format($diagnostics['ticket_attachment_local_urls']) }}</strong></div>
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Ticket remote</div><strong>{{ number_format($diagnostics['ticket_attachment_remote_urls']) }}</strong></div>
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Message local</div><strong>{{ number_format($diagnostics['message_attachment_local_urls']) }}</strong></div>
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Message remote</div><strong>{{ number_format($diagnostics['message_attachment_remote_urls']) }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection