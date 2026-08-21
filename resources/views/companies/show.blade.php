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
                <div class="tc-stat-value-v2">{{ number_format($companyDriversCount) }}</div>
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
            <button type="button" class="tc-company-tab" data-tab="drivers">Drivers ({{ number_format($companyDriversCount) }})</button>
            <button type="button" class="tc-company-tab" data-tab="tickets">Tickets ({{ number_format($companyTicketsCount) }})</button>
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
                                    @if($companyDriversCount > 0)
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
                    <h2 class="tc-company-section-title mb-1">Drivers ({{ number_format($companyDriversCount) }})</h2>
                    <p class="text-sm text-slate-500 m-0">Active drivers for this company. Click a name to open the driver record.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table tc-clean-table mb-0 yajra-datatable w-full" id="companyShowDriversTable" style="min-width: 900px;">
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
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="tc-company-panel" id="tab-tickets">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <div>
                    <h2 class="tc-company-section-title mb-1">Tickets ({{ number_format($companyTicketsCount) }})</h2>
                    <p class="text-sm text-slate-500 m-0">All tickets for this company. Click ticket or driver to open the record.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table tc-clean-table mb-0 yajra-datatable w-full" id="companyShowTicketsTable" style="min-width: 900px;">
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
                    <tbody></tbody>
                </table>
            </div>
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
                <span>Drivers ({{ number_format($companyDriversCount) }})</span>
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
                        <strong>{{ number_format($companyDriversCount) }}</strong>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/plugins/dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables.bootstrap5.min.js') }}"></script>
<script>
(function () {
    const tabs = document.querySelectorAll('.tc-company-tab');
    const panels = document.querySelectorAll('.tc-company-panel');
    let driversTable = null;
    let ticketsTable = null;

    function initDriversTable() {
        if (driversTable || !window.jQuery) return;
        driversTable = $('#companyShowDriversTable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            pageLength: 25,
            autoWidth: false,
            order: [[0, 'asc']],
            dom: "<'dt-controls-bar'l f><'tc-table-scroll-container't><'dt-footer-bar'i p>",
            ajax: {
                url: @json(route($portal.'.companies.drivers-data', $company->id)),
            },
            columns: [
                { data: 'row_number', orderable: true, searchable: false },
                { data: 'name_html', orderable: false },
                { data: 'state', orderable: false },
                { data: 'city', orderable: false },
                { data: 'open_tickets', orderable: false, searchable: false },
                { data: 'closed_tickets', orderable: false, searchable: false },
                { data: 'points_saved', orderable: false, searchable: false },
                { data: 'last_access', orderable: false, searchable: false },
                { data: 'status_html', orderable: false, searchable: false },
            ],
        });
    }

    function initTicketsTable() {
        if (ticketsTable || !window.jQuery) return;
        ticketsTable = $('#companyShowTicketsTable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            pageLength: 25,
            autoWidth: false,
            order: [[0, 'desc']],
            dom: "<'dt-controls-bar'l f><'tc-table-scroll-container't><'dt-footer-bar'i p>",
            ajax: {
                url: @json(route($portal.'.companies.tickets-data', $company->id)),
            },
            columns: [
                { data: 'ticket_html', orderable: true },
                { data: 'driver_html', orderable: false },
                { data: 'date_received', orderable: false, searchable: false },
                { data: 'state', orderable: false },
                { data: 'status_html', orderable: false },
                { data: 'original_points', orderable: false, searchable: false },
                { data: 'final_points', orderable: false, searchable: false },
                { data: 'points_saved', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false, className: 'text-right' },
            ],
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const target = tab.getAttribute('data-tab');
            tabs.forEach(function (t) { t.classList.toggle('active', t === tab); });
            panels.forEach(function (panel) {
                panel.classList.toggle('active', panel.id === 'tab-' + target);
            });
            if (target === 'drivers') initDriversTable();
            if (target === 'tickets') initTicketsTable();
        });
    });
})();
</script>
@endsection
