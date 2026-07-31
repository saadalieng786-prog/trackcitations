@extends('layout.master')
@section('content')
    @php
        $portal = auth()->user()->portalRoutePrefix();
    @endphp
    <div class="col-span-12">
        <div class="tab-content">
            <div class="block tab-pane" id="citation">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Synced Companies</p>
                                <h4 class="mb-0">{{ $syncStats['companies'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Synced Tickets</p>
                                <h4 class="mb-0">{{ $syncStats['tickets'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Synced Attachments</p>
                                <h4 class="mb-0">{{ $syncStats['attachments'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Points Saved from SF Tickets</p>
                                <h4 class="mb-0">{{ number_format($syncStats['points_saved'], 1) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">SF Tickets With Point Data</p>
                                <h4 class="mb-0">{{ $syncStats['tickets_with_points'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Original Points Total</p>
                                <h4 class="mb-0">{{ number_format($syncStats['original_points_total'], 1) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Final Points Total</p>
                                <h4 class="mb-0">{{ number_format($syncStats['final_points_total'], 1) }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-8">
                        <div class="card">
                            <div class="card-header flex justify-between items-center gap-3">
                                <h5 class="text-primary text-[28px] font-bold mb-0">Salesforce Sync Monitor</h5>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('salesforce.oauth') }}" class="btn btn-primary btn-sm">Reconnect OAuth</a>
                                    <span class="inline-flex items-center rounded-md px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $salesforce->statusBadgeClass() }}">
                                        Status: {{ $salesforce->statusLabel() }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($salesforce->status == \App\Models\SalesForce::STATUS_FAILED && $salesforce->reason)
                                    <div class="alert alert-danger mb-4">
                                        <strong>Last failure:</strong> {{ $salesforce->reason }}
                                    </div>
                                @endif

                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="border rounded p-3 h-full">
                                            <h6 class="mb-3">Connection Health</h6>
                                            <p class="mb-2 text-muted">Configured: {{ $salesforce->isConfigured() ? 'Yes' : 'No' }}</p>
                                            <p class="mb-2 text-muted">Access Token Stored: {{ $connectionSummary['has_access_token'] ? 'Yes' : 'No' }}</p>
                                            <p class="mb-2 text-muted">Refresh Token Stored: {{ $connectionSummary['has_refresh_token'] ? 'Yes' : 'No' }}</p>
                                            <p class="mb-0 text-muted">Last Settings Update: {{ $syncTimes['updated'] ?: 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="border rounded p-3 h-full">
                                            <h6 class="mb-3">Sync Schedule Targets</h6>
                                            <p class="mb-2 text-muted">Expected pull interval: every 15 minutes</p>
                                            <p class="mb-2 text-muted">Current scheduled command: every 15 minutes</p>
                                            <p class="mb-0 text-muted">Direction: pull from Salesforce only</p>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <div class="border rounded p-3">
                                            <h6 class="mb-3">Last Sync Activity</h6>
                                            <div class="grid grid-cols-12 gap-3">
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">Records Sync</p>
                                                    <p class="mb-0">{{ $syncTimes['records'] }}</p>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">Attachment Sync</p>
                                                    <p class="mb-0">{{ $syncTimes['attachments'] }}</p>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">File Sync</p>
                                                    <p class="mb-0">{{ $syncTimes['files'] }}</p>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">Account Activity Sync</p>
                                                    <p class="mb-0">{{ $syncTimes['account_activity'] }}</p>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">Contact Activity Sync</p>
                                                    <p class="mb-0">{{ $syncTimes['contact_activity'] }}</p>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">Token Issued</p>
                                                    <p class="mb-0">{{ $syncTimes['token_issued'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <div class="border rounded p-3">
                                            <h6 class="mb-3">Connection Details</h6>
                                            <p class="mb-2 text-muted">Login URL: {{ $connectionSummary['login_uri'] ?: 'Not set' }}</p>
                                            <p class="mb-2 text-muted">Callback URL: {{ $connectionSummary['callback_uri'] ?: 'Not set' }}</p>
                                            <p class="mb-0 text-muted">Instance URL: {{ $connectionSummary['instance_url'] ?: 'Not set' }}</p>
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

                                <p class="text-muted mb-3">Pull a specific company/contact set by email for support review or sync troubleshooting.</p>
                                <form action="{{ route($portal.'.salesforce.import') }}" method="post">
                                    @csrf
                                    <label class="text-lg font-bold">Company Email</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Company Email" aria-label="Company Email" aria-describedby="button-addon2" name="Email">
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
