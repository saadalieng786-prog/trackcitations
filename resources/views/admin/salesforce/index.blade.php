@extends('layout.master')
@section('content')
    @php
        $portal = auth()->user()->portalRoutePrefix();
        $statusTone = match ((int) $salesforce->status) {
            \App\Models\SalesForce::STATUS_RUNNING => 'bg-sky-50 text-sky-700 ring-sky-600/20',
            \App\Models\SalesForce::STATUS_FAILED => 'bg-rose-50 text-rose-700 ring-rose-600/20',
            default => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        };
    @endphp
    <div class="col-span-12">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 m-0 tracking-tight">Salesforce Sync</h1>
                <p class="text-sm text-slate-500 mt-1 mb-0">Monitor connection health, pull schedule, and recent Salesforce imports.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset {{ $statusTone }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-80"></span>
                    {{ $salesforce->statusLabel() }}
                </span>
                <form action="{{ route($portal.'.salesforce.sync') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm whitespace-nowrap">Run Sync Now</button>
                </form>
                <a href="{{ route($portal.'.salesforce.sync-log') }}" class="btn btn-outline-secondary btn-sm whitespace-nowrap">View Sync Log</a>
                <a href="{{ route('salesforce.oauth') }}" class="btn btn-outline-primary btn-sm whitespace-nowrap">Reconnect OAuth</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        <div class="tab-content">
            <div class="block tab-pane" id="citation">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Synced Companies</p>
                                <h4 class="mb-0">{{ $syncStats['companies'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Synced Tickets</p>
                                <h4 class="mb-0">{{ $syncStats['tickets'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Synced Attachments</p>
                                <h4 class="mb-0">{{ $syncStats['attachments'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Points Saved from SF Tickets</p>
                                <h4 class="mb-0">{{ number_format($syncStats['points_saved'], 1) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">SF Tickets With Point Data</p>
                                <h4 class="mb-0">{{ $syncStats['tickets_with_points'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Original Points Total</p>
                                <h4 class="mb-0">{{ number_format($syncStats['original_points_total'], 1) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Final Points Total</p>
                                <h4 class="mb-0">{{ number_format($syncStats['final_points_total'], 1) }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Sync Monitor</h5>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                                @endif
                                @if($salesforce->status == \App\Models\SalesForce::STATUS_FAILED && $salesforce->reason)
                                    <div class="alert alert-danger mb-4">
                                        <strong>Last failure:</strong> {{ $salesforce->reason }}
                                    </div>
                                @endif

                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="rounded-xl border border-theme-border dark:border-themedark-border p-4 h-full">
                                            <h6 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Connection Health</h6>
                                            <dl class="m-0 space-y-2.5">
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Configured</dt>
                                                    <dd class="mb-0 text-sm font-medium">{{ $salesforce->isConfigured() ? 'Yes' : 'No' }}</dd>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Access Token</dt>
                                                    <dd class="mb-0 text-sm font-medium">{{ $connectionSummary['has_access_token'] ? 'Stored' : 'Missing' }}</dd>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Refresh Token</dt>
                                                    <dd class="mb-0 text-sm font-medium">{{ $connectionSummary['has_refresh_token'] ? 'Stored' : 'Missing' }}</dd>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Settings Updated</dt>
                                                    <dd class="mb-0 text-sm font-medium text-right">{{ $syncTimes['updated'] ?: 'N/A' }}</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="rounded-xl border border-theme-border dark:border-themedark-border p-4 h-full">
                                            <h6 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Sync Schedule</h6>
                                            <dl class="m-0 space-y-2.5">
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Pull Interval</dt>
                                                    <dd class="mb-0 text-sm font-medium">Every 15 minutes</dd>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Scheduler</dt>
                                                    <dd class="mb-0 text-sm font-medium">Every 15 minutes</dd>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Direction</dt>
                                                    <dd class="mb-0 text-sm font-medium">Pull from Salesforce</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <div class="rounded-xl border border-theme-border dark:border-themedark-border p-4">
                                            <h6 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Last Sync Activity</h6>
                                            <p class="mb-3 text-xs text-muted">
                                                “Last sync run” is when Track Citations last finished a sync.
                                                The other timestamps are Salesforce watermarks (record <code>SystemModstamp</code>), not the clock time of the last button click.
                                            </p>
                                            <div class="grid grid-cols-12 gap-3">
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Last Sync Run</p>
                                                    <p class="mb-0 text-sm font-medium">{{ $syncTimes['last_run'] }}</p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Records Watermark</p>
                                                    <p class="mb-0 text-sm font-medium">{{ $syncTimes['records'] }}</p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Attachment Watermark</p>
                                                    <p class="mb-0 text-sm font-medium">{{ $syncTimes['attachments'] }}</p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">File Watermark</p>
                                                    <p class="mb-0 text-sm font-medium">{{ $syncTimes['files'] }}</p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Account Activity</p>
                                                    <p class="mb-0 text-sm font-medium">{{ $syncTimes['account_activity'] }}</p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Contact Activity</p>
                                                    <p class="mb-0 text-sm font-medium">{{ $syncTimes['contact_activity'] }}</p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Token Issued</p>
                                                    <p class="mb-0 text-sm font-medium">{{ $syncTimes['token_issued'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <div class="rounded-xl border border-theme-border dark:border-themedark-border p-4">
                                            <h6 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Connection Details</h6>
                                            <dl class="m-0 space-y-2.5">
                                                <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                                    <dt class="mb-0 text-sm text-muted shrink-0">Login URL</dt>
                                                    <dd class="mb-0 text-sm font-medium break-all text-right">{{ $connectionSummary['login_uri'] ?: 'Not set' }}</dd>
                                                </div>
                                                <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                                    <dt class="mb-0 text-sm text-muted shrink-0">Callback URL</dt>
                                                    <dd class="mb-0 text-sm font-medium break-all text-right">{{ $connectionSummary['callback_uri'] ?: 'Not set' }}</dd>
                                                </div>
                                                <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                                    <dt class="mb-0 text-sm text-muted shrink-0">Instance URL</dt>
                                                    <dd class="mb-0 text-sm font-medium break-all text-right">{{ $connectionSummary['instance_url'] ?: 'Not set' }}</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Connection Settings</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route($portal.'.salesforce.update') }}" method="post" class="mb-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Client ID</label>
                                        <input type="text" class="form-control" name="client_id" value="{{ old('client_id', $salesforce->client_id) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Client Secret</label>
                                        <input type="text" class="form-control" name="client_secret" value="{{ old('client_secret', $salesforce->client_secret) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Redirect URI</label>
                                        <input type="url" class="form-control" name="redirect_uri" value="{{ old('redirect_uri', $salesforce->redirect_uri) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Login URL</label>
                                        <input type="url" class="form-control" name="login_uri" value="{{ old('login_uri', $salesforce->login_uri) }}">
                                    </div>
                                    <button type="submit" class="btn btn-secondary">Save Settings</button>
                                </form>

                                <p class="text-muted mb-3">Import one company/contact set by email (Contact Email, Primary Contact Email, Contact Email, CT User Email, or Alternate Email). Account must still have Export = TRUE.</p>
                                <form action="{{ route($portal.'.salesforce.import') }}" method="post">
                                    @csrf
                                    <label class="form-label">Company / Contact Email</label>
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control" placeholder="email@example.com" aria-label="Company Email" aria-describedby="button-addon2" name="Email" required>
                                        <button class="btn btn-primary" type="submit" id="button-addon2">
                                            Import
                                        </button>
                                    </div>
                                </form>

                                <div class="border rounded p-3 mt-4">
                                    <h6 class="mb-2">Discovery Notes</h6>
                                    <p class="mb-2 text-muted">This page is intended for internal staff/admin monitoring only.</p>
                                    <p class="mb-0 text-muted">The Salesforce inspector remains a temporary developer/admin tool and should stay restricted outside discovery/testing.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Legacy Sync Tooling</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2 text-muted">Directory Present: {{ $legacyTooling['present'] ? 'Yes' : 'No' }}</p>
                                <p class="mb-2 text-muted">Legacy Access Enabled: {{ $legacyTooling['enabled'] ? 'Yes' : 'No' }}</p>
                                <p class="mb-2 text-muted">Allowed IPs: {{ $legacyTooling['allowed_ips'] ?: 'Not configured' }}</p>
                                <p class="mb-3 text-muted">Legacy Redirect URI: {{ $legacyTooling['redirect_uri'] ?: 'Not configured' }}</p>
                                <div class="border rounded p-3">
                                    <p class="mb-2 text-muted">Recommendation: keep this disabled unless a temporary maintenance task explicitly requires it.</p>
                                    <p class="mb-0 text-muted">All normal Salesforce operations should run through the Laravel-managed sync and admin monitor.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-5">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Synced Tickets</h5>
                            </div>
                            <div class="card-body">
                                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                                    @forelse($recentSync['tickets'] as $ticket)
                                        <li class="list-group-item">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="grow">
                                                    <h6 class="mb-1">{{ $ticket->name ?: 'Ticket #'.$ticket->id }}</h6>
                                                    <p class="mb-0 text-muted">
                                                        Company: {{ optional($ticket->company)->name ?: 'N/A' }} |
                                                        Citation: {{ $ticket->citation_no ?: 'N/A' }}
                                                    </p>
                                                    <p class="mb-0 mt-1 text-muted">
                                                        Updated: {{ optional($ticket->updated_at)?->format('M j, Y g:i A') ?: 'N/A' }} |
                                                        Salesforce ID: {{ $ticket->sf_id }}
                                                    </p>
                                                    <p class="mb-0 mt-1 text-muted">
                                                        Original Points: {{ $ticket->original_points_value !== null ? number_format($ticket->original_points_value, 1) : 'N/A' }} |
                                                        Final Points: {{ $ticket->final_points_value !== null ? number_format($ticket->final_points_value, 1) : 'N/A' }} |
                                                        Saved: {{ number_format($ticket->points_saved, 1) }}
                                                    </p>
                                                </div>
                                                <a href="{{ route($portal.'.tickets.edit', $ticket->id) }}" class="btn btn-light-primary btn-sm">Open</a>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item">No Salesforce-linked tickets found yet.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Sync Alerts</h5>
                            </div>
                            <div class="card-body">
                                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                                    @forelse($syncAlerts as $alert)
                                        <li class="list-group-item text-muted">{{ $alert }}</li>
                                    @empty
                                        <li class="list-group-item text-muted">No immediate sync warnings detected.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Synced Companies</h5>
                            </div>
                            <div class="card-body">
                                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                                    @forelse($recentSync['companies'] as $company)
                                        <li class="list-group-item">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="grow">
                                                    <h6 class="mb-1">{{ $company->name }}</h6>
                                                    <p class="mb-0 text-muted">
                                                        DOT: {{ $company->dot ?: 'N/A' }} |
                                                        Email: {{ $company->ct_email ?: 'N/A' }}
                                                    </p>
                                                    <p class="mb-0 mt-1 text-muted">
                                                        Updated: {{ optional($company->updated_at)?->format('M j, Y g:i A') ?: 'N/A' }}
                                                    </p>
                                                </div>
                                                <a href="{{ route($portal.'.companies.edit', $company->id) }}" class="btn btn-light-primary btn-sm">Open</a>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item">No Salesforce-linked companies found yet.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Synced Attachments</h5>
                            </div>
                            <div class="card-body">
                                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                                    @forelse($recentSync['attachments'] as $attachment)
                                        <li class="list-group-item">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="grow">
                                                    <h6 class="mb-1">{{ $attachment->filename }}</h6>
                                                    <p class="mb-0 text-muted">
                                                        Ticket: {{ optional($attachment->ticket)->name ?: 'N/A' }} |
                                                        Company: {{ optional(optional($attachment->ticket)->company)->name ?: 'N/A' }}
                                                    </p>
                                                    <p class="mb-0 mt-1 text-muted">
                                                        Updated: {{ optional($attachment->updated_at)?->format('M j, Y g:i A') ?: 'N/A' }} |
                                                        Salesforce ID: {{ $attachment->sf_id }}
                                                    </p>
                                                </div>
                                                @if(optional($attachment->ticket)->id)
                                                    <a href="{{ route($portal.'.tickets.edit', $attachment->ticket->id) }}" class="btn btn-light-primary btn-sm">Open Ticket</a>
                                                @endif
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item">No Salesforce-linked attachments found yet.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('post-scripts')
@endsection
@section('css')

@endsection
