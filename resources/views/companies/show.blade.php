@extends('layout.master')

@section('content')
@php
    $portal = auth()->user()->portalRoutePrefix();
    $canEdit = auth()->user()->can('update', $company);
    $managers = $company->managers->filter(fn ($manager) => $manager->user)->values();
    $contacts = $company->contacts;
@endphp

<div class="col-span-12 tc-company-overview">
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
        <div>
            <div class="text-xs text-slate-500 mb-2">
                <a href="{{ route($portal.'.companies.index') }}" class="text-slate-500 hover:text-indigo-600">Companies</a>
                <span class="mx-1.5 text-slate-300">/</span>
                <span class="font-medium text-slate-700">{{ $company->name }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="tc-company-avatar">{{ strtoupper(substr($company->name, 0, 1)) }}</div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 m-0 tracking-tight">{{ $company->name }}</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        @if($company->dot)
                            <span class="tc-company-chip">DOT {{ $company->dot }}</span>
                        @endif
                        @if($company->sf_id)
                            <span class="tc-company-chip muted">SF {{ $company->sf_id }}</span>
                        @endif
                        @if($company->parentCompany)
                            <span class="tc-company-chip muted">
                                Parent:
                                <a href="{{ route($portal.'.companies.show', $company->parentCompany->id) }}" class="text-indigo-600 hover:underline">
                                    {{ $company->parentCompany->name }}
                                </a>
                            </span>
                        @else
                            <span class="tc-company-chip muted">Top-level company</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route($portal.'.companies.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
            @if($canEdit)
                <a href="{{ route($portal.'.companies.edit', $company->id) }}" class="btn btn-primary btn-sm flex items-center gap-2">
                    <i class="ti ti-pencil"></i> Edit Company
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4 mb-5">
        <div class="col-span-6 md:col-span-3">
            <div class="tc-stat-card-v2">
                <div class="flex items-center justify-between mb-3">
                    <span class="tc-stat-label-v2">Drivers</span>
                    <span class="tc-stat-icon-v2 blue"><i class="ti ti-steering-wheel"></i></span>
                </div>
                <div class="tc-stat-value-v2">{{ $companyDrivers->count() }}</div>
            </div>
        </div>
        <div class="col-span-6 md:col-span-3">
            <div class="tc-stat-card-v2">
                <div class="flex items-center justify-between mb-3">
                    <span class="tc-stat-label-v2">Open Tickets</span>
                    <span class="tc-stat-icon-v2 orange"><i class="ti ti-ticket"></i></span>
                </div>
                <div class="tc-stat-value-v2">{{ $openTicketsCount }}</div>
            </div>
        </div>
        <div class="col-span-6 md:col-span-3">
            <div class="tc-stat-card-v2">
                <div class="flex items-center justify-between mb-3">
                    <span class="tc-stat-label-v2">Closed Tickets</span>
                    <span class="tc-stat-icon-v2 green"><i class="ti ti-circle-check"></i></span>
                </div>
                <div class="tc-stat-value-v2">{{ $closedTicketsCount }}</div>
            </div>
        </div>
        <div class="col-span-6 md:col-span-3">
            <div class="tc-stat-card-v2">
                <div class="flex items-center justify-between mb-3">
                    <span class="tc-stat-label-v2">Points Saved</span>
                    <span class="tc-stat-icon-v2 purple"><i class="ti ti-chart-arrows"></i></span>
                </div>
                <div class="tc-stat-value-v2">{{ number_format($pointsSavedTotal, 1) }}</div>
            </div>
        </div>
    </div>

    <div class="tc-company-shell">
        <div class="tc-company-tabs" role="tablist">
            <button type="button" class="tc-company-tab active" data-tab="overview">Overview</button>
            <button type="button" class="tc-company-tab" data-tab="contacts">Contacts ({{ $contacts->count() }})</button>
            <button type="button" class="tc-company-tab" data-tab="managers">Managers ({{ $managers->count() }})</button>
            <button type="button" class="tc-company-tab" data-tab="drivers">Drivers ({{ $companyDrivers->count() }})</button>
            <button type="button" class="tc-company-tab" data-tab="tickets">Tickets ({{ $companyTickets->count() }})</button>
            <button type="button" class="tc-company-tab" data-tab="hierarchy">Hierarchy</button>
        </div>

        <div class="tc-company-panel active" id="tab-overview">
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 lg:col-span-7">
                    <h2 class="tc-company-section-title">Company Information</h2>
                    <div class="tc-company-info-grid">
                        <div>
                            <span class="tc-company-info-label">Company Name</span>
                            <span class="tc-company-info-value">{{ $company->name }}</span>
                        </div>
                        <div>
                            <span class="tc-company-info-label">Email</span>
                            <span class="tc-company-info-value">{{ $company->ct_email ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="tc-company-info-label">DOT Number</span>
                            <span class="tc-company-info-value">{{ $company->dot ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="tc-company-info-label">Salesforce ID</span>
                            <span class="tc-company-info-value">{{ $company->sf_id ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="tc-company-info-label">Parent Company</span>
                            <span class="tc-company-info-value">
                                @if($company->parentCompany)
                                    <a href="{{ route($portal.'.companies.show', $company->parentCompany->id) }}" class="text-indigo-600 hover:underline">{{ $company->parentCompany->name }}</a>
                                @else
                                    Top-level
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="tc-company-info-label">Primary Contact</span>
                            <span class="tc-company-info-value">{{ trim(($company->ct_fname ?? '').' '.($company->ct_lname ?? '')) ?: '—' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-5">
                    <h2 class="tc-company-section-title">Quick Relationships</h2>
                    <div class="tc-company-quick-list">
                        <div class="tc-company-quick-item">
                            <span>Companies Managed</span>
                            <strong>{{ $company->childCompanies->count() }}</strong>
                        </div>
                        <div class="tc-company-quick-item">
                            <span>Company managers</span>
                            <strong>{{ $managers->count() }}</strong>
                        </div>
                        <div class="tc-company-quick-item">
                            <span>Company contacts</span>
                            <strong>{{ $contacts->count() }}</strong>
                        </div>
                        <div class="tc-company-quick-item">
                            <span>Driver Total</span>
                            <strong>{{ $company->driversCountIncludingChildren() }}</strong>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 mt-4 mb-0">
                        Use the Drivers and Tickets tabs for the full operational path:
                        Company → Drivers → Driver, or Company → Tickets → Ticket.
                    </p>
                </div>
            </div>
        </div>

        <div class="tc-company-panel" id="tab-contacts">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="tc-company-section-title mb-1">Company Contacts</h2>
                    <p class="text-sm text-slate-500 m-0">Contact records for this company.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table tc-clean-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Cell</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $index => $contact)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-medium">{{ $contact->name ?: '—' }}</td>
                                <td>
                                    @if($contact->email)
                                        <a href="mailto:{{ $contact->email }}" class="text-indigo-600 hover:underline">{{ $contact->email }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $contact->phone ?: '—' }}</td>
                                <td>{{ $contact->cell ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-slate-400 py-5">No contacts on this company yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tc-company-panel" id="tab-managers">
            <div class="mb-4">
                <h2 class="tc-company-section-title mb-1">Company Managers</h2>
                <p class="text-sm text-slate-500 m-0">Assigned company administrators and managers.</p>
            </div>
            <div class="table-responsive">
                <table class="table tc-clean-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Access / Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($managers as $index => $manager)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-medium">{{ $manager->user?->name ?: '—' }}</td>
                                <td>{{ $manager->user?->email ?: '—' }}</td>
                                <td>
                                    @if($manager->user?->email)
                                        <span class="badge bg-success-50 text-success">Portal Access</span>
                                    @else
                                        <span class="badge bg-warning-50 text-warning">No Login</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-400 py-5">
                                    No managers assigned to this company yet.
                                    @if($companyDrivers->isNotEmpty())
                                        <div class="text-xs text-slate-400 mt-2">If Salesforce Account emails match a Driver email, a separate manager login is not created automatically.</div>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tc-company-panel" id="tab-drivers">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="tc-company-section-title mb-1">Drivers ({{ $companyDrivers->count() }})</h2>
                    <p class="text-sm text-slate-500 m-0">Active drivers for this company. Click a name to open the driver record.</p>
                </div>
                <div class="w-full sm:w-80">
                    <input type="search" id="companyShowDriversSearch" class="form-control" placeholder="Search drivers by name, email, city, state..." />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table tc-clean-table mb-0" id="companyShowDriversTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Driver Name</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Open Tickets</th>
                            <th>Closed Tickets</th>
                            <th>Points Saved</th>
                            <th>Last Access</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companyDrivers as $index => $driver)
                            @php
                                $driverUser = $driver->user;
                                $emailKey = strtolower((string) ($driverUser?->email ?? ''));
                                $stats = $driverTicketStats->get($emailKey);
                                $searchBlob = strtolower(trim(implode(' ', array_filter([
                                    $driverUser?->name,
                                    $driverUser?->email,
                                    $driverUser?->city,
                                    $driverUser?->state,
                                ]))));
                            @endphp
                            <tr data-search="{{ $searchBlob }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($driverUser)
                                        <a href="{{ route($portal.'.drivers.edit', $driver->id) }}" class="font-semibold text-indigo-600 hover:underline">
                                            {{ $driverUser->name ?: 'Unnamed driver' }}
                                        </a>
                                        <div class="text-xs text-slate-400">{{ $driverUser->email }}</div>
                                    @else
                                        <span class="text-slate-400">Driver user missing</span>
                                    @endif
                                </td>
                                <td>{{ $driverUser?->state ?: '—' }}</td>
                                <td>{{ $driverUser?->city ?: '—' }}</td>
                                <td>{{ (int) ($stats->open_count ?? 0) }}</td>
                                <td>{{ (int) ($stats->closed_count ?? 0) }}</td>
                                <td>{{ number_format((float) ($stats->points_saved ?? 0), 1) }}</td>
                                <td>
                                    @if($driverUser?->last_login_at)
                                        {{ \Carbon\Carbon::parse($driverUser->last_login_at)->format('M j, Y g:i A') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($driverUser?->email)
                                        <span class="badge bg-success-50 text-success">Portal Access</span>
                                    @else
                                        <span class="badge bg-warning-50 text-warning">No Login</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-slate-400 py-5">No drivers are linked to this company yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p id="companyShowDriversEmpty" class="mb-0 mt-3 text-sm text-slate-400 hidden">No drivers match your search.</p>
        </div>

        <div class="tc-company-panel" id="tab-tickets">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="tc-company-section-title mb-1">Tickets ({{ $companyTickets->count() }})</h2>
                    <p class="text-sm text-slate-500 m-0">All tickets for this company. Click ticket or driver to open the record.</p>
                </div>
                <div class="w-full sm:w-80">
                    <input type="search" id="companyShowTicketsSearch" class="form-control" placeholder="Search tickets by ID, driver, state, status..." />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table tc-clean-table mb-0" id="companyShowTicketsTable">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Driver Name</th>
                            <th>Date Received</th>
                            <th>State</th>
                            <th>Status / Indicator</th>
                            <th>Original Points</th>
                            <th>Final Points</th>
                            <th>Points Saved</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companyTickets as $ticket)
                            @php
                                $emailKey = strtolower((string) ($ticket->user_email ?? ''));
                                $linkedDriver = $driversByEmail->get($emailKey);
                                $driverName = $ticket->name ?: ($linkedDriver?->user?->name ?: '—');
                                $statusLabel = match ((int) ($ticket->status ?? -1)) {
                                    \App\Models\Ticket::TICKET_STATUS_CLOSED => 'Closed',
                                    \App\Models\Ticket::TICKET_STATUS_ARCHIVED => 'Archived',
                                    default => 'Open',
                                };
                                $indicator = $ticket->indicator ?: '—';
                                $searchBlob = strtolower(trim(implode(' ', array_filter([
                                    (string) $ticket->id,
                                    (string) ($ticket->ticket_number ?? ''),
                                    $driverName,
                                    $ticket->state,
                                    $statusLabel,
                                    $indicator,
                                ]))));
                            @endphp
                            <tr data-search="{{ $searchBlob }}">
                                <td>
                                    <a href="{{ route($portal.'.tickets.show', $ticket->id) }}" class="font-semibold text-indigo-600 hover:underline">#{{ $ticket->id }}</a>
                                    @if($ticket->ticket_number)
                                        <div class="text-xs text-slate-400">{{ $ticket->ticket_number }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($linkedDriver)
                                        <a href="{{ route($portal.'.drivers.edit', $linkedDriver->id) }}" class="font-medium text-indigo-600 hover:underline">{{ $driverName }}</a>
                                    @else
                                        {{ $driverName }}
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->date_issued)
                                        {{ \Carbon\Carbon::parse($ticket->date_issued)->format('M j, Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $ticket->state ?: '—' }}</td>
                                <td>
                                    <div>{{ $statusLabel }}</div>
                                    <div class="text-xs text-slate-400">{{ $indicator }}</div>
                                </td>
                                <td>{{ number_format((float) $ticket->original_points_value, 1) }}</td>
                                <td>{{ number_format((float) $ticket->final_points_value, 1) }}</td>
                                <td>{{ number_format((float) $ticket->points_saved, 1) }}</td>
                                <td class="text-right">
                                    <a href="{{ route($portal.'.tickets.show', $ticket->id) }}" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary" title="View ticket">
                                        <i class="ti ti-eye text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-slate-400 py-5">No tickets are linked to this company yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p id="companyShowTicketsEmpty" class="mb-0 mt-3 text-sm text-slate-400 hidden">No tickets match your search.</p>
        </div>

        <div class="tc-company-panel" id="tab-hierarchy">
            <div class="mb-4">
                <h2 class="tc-company-section-title mb-1">Company Hierarchy</h2>
                <p class="text-sm text-slate-500 m-0">Parent → Company → Drivers relationship at a glance.</p>
            </div>
            <div class="tc-company-hierarchy-path mb-4">
                @if($company->parentCompany)
                    <a href="{{ route($portal.'.companies.show', $company->parentCompany->id) }}">{{ $company->parentCompany->name }}</a>
                    <span>/</span>
                @endif
                <strong>{{ $company->name }}</strong>
                <span>/</span>
                <span>Drivers ({{ $companyDrivers->count() }})</span>
            </div>
            <div class="grid grid-cols-12 gap-4 mb-5">
                <div class="col-span-12 md:col-span-4">
                    <div class="tc-company-quick-item block">
                        <span>Parent Company</span>
                        <strong>
                            @if($company->parentCompany)
                                <a href="{{ route($portal.'.companies.show', $company->parentCompany->id) }}" class="text-indigo-600 hover:underline">{{ $company->parentCompany->name }}</a>
                            @else
                                Top-level
                            @endif
                        </strong>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div class="tc-company-quick-item block">
                        <span>Drivers on this company</span>
                        <strong>{{ $companyDrivers->count() }}</strong>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div class="tc-company-quick-item block">
                        <span>Child trucking companies</span>
                        <strong>{{ $company->childCompanies->count() }}</strong>
                    </div>
                </div>
            </div>

            <h3 class="text-sm font-bold uppercase tracking-wide text-slate-500 mb-3">Child Companies</h3>
            <div class="table-responsive">
                <table class="table tc-clean-table mb-0">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Drivers</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($company->childCompanies as $childCompany)
                            <tr>
                                <td>
                                    <a href="{{ route($portal.'.companies.show', $childCompany->id) }}" class="font-semibold text-indigo-600 hover:underline">
                                        {{ $childCompany->name }}
                                    </a>
                                </td>
                                <td>{{ (int) ($childCompanyDriverCounts[$childCompany->id] ?? 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-slate-400 py-5">No child companies under this company.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('post-scripts')
<script>
(function () {
    const tabs = document.querySelectorAll('.tc-company-tab');
    const panels = document.querySelectorAll('.tc-company-panel');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const target = tab.getAttribute('data-tab');
            tabs.forEach(function (t) { t.classList.toggle('active', t === tab); });
            panels.forEach(function (panel) {
                panel.classList.toggle('active', panel.id === 'tab-' + target);
            });
        });
    });

    function bindSearch(inputId, tableId, emptyId) {
        const input = document.getElementById(inputId);
        const table = document.getElementById(tableId);
        const empty = document.getElementById(emptyId);
        if (!input || !table) return;

        input.addEventListener('input', function () {
            const query = (input.value || '').trim().toLowerCase();
            let visible = 0;
            table.querySelectorAll('tbody tr[data-search]').forEach(function (row) {
                const match = !query || (row.getAttribute('data-search') || '').indexOf(query) !== -1;
                row.style.display = match ? '' : 'none';
                if (match) visible += 1;
            });
            if (empty) {
                empty.classList.toggle('hidden', visible !== 0 || table.querySelectorAll('tbody tr[data-search]').length === 0);
            }
        });
    }

    bindSearch('companyShowDriversSearch', 'companyShowDriversTable', 'companyShowDriversEmpty');
    bindSearch('companyShowTicketsSearch', 'companyShowTicketsTable', 'companyShowTicketsEmpty');
})();
</script>
@endsection
