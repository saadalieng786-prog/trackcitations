@extends('layout.master')
@section('content')
    @php $portal = auth()->user()->portalRoutePrefix(); @endphp
    <div class="col-span-12 lg:col-span-4 md:col-span-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-primary-500/10 text-primary-500">
                            <i class="ti ti-file text-2xl leading-none"></i>
                        </div>
                    </div>
                    <div class="grow ltr:ml-3 rtl:mr-3">
                        <p class="mb-1">Open Tickets</p>
                        <h4 class="mb-0">{{ $stats['open_tickets'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-4 md:col-span-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-success-500/10 text-success-500">
                            <i class="ti ti-file-check text-2xl leading-none"></i>
                        </div>
                    </div>
                    <div class="grow ltr:ml-3 rtl:mr-3">
                        <p class="mb-1">Closed Tickets</p>
                        <h4 class="mb-0">{{ $stats['closed_tickets'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-4 md:col-span-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-info-500/10 text-info-500">
                            <i class="ti ti-chart-arrows text-2xl leading-none"></i>
                        </div>
                    </div>
                    <div class="grow ltr:ml-3 rtl:mr-3">
                        <p class="mb-1">Lifetime Points Saved</p>
                        <h4 class="mb-0">{{ number_format($stats['points_saved'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-5">
        <div class="card">
            <div class="card-header">
                <div class="sm:flex items-center justify-between">
                    <h5>Next Important Date</h5>
                    <a href="{{ route($portal.'.tickets.index') }}" class="btn btn-secondary">View All</a>
                </div>
            </div>
            <div class="card-body">
                @if($upcomingTicket)
                    <div class="card overflow-hidden mb-0">
                        <div class="card-body !px-3 !py-3 border-l-4 border-danger-500">
                            <h6 class="mb-1">{{ $upcomingTicket->name }}</h6>
                            <p class="mb-1 text-muted">
                                Company: {{ optional($upcomingTicket->company)->name ?: 'N/A' }} |
                                Indicator: {{ $upcomingTicket->indicator ?: 'Received' }}
                            </p>
                            <p class="mb-1 text-muted">
                                Court: {{ $upcomingTicket->court_date ? \Carbon\Carbon::parse($upcomingTicket->court_date)->format('M j, Y g:i A') : 'Not scheduled' }}
                            </p>
                            <p class="mb-0 text-muted">
                                Points Saved: {{ number_format($upcomingTicket->points_saved, 1) }}
                            </p>
                        </div>
                    </div>
                @else
                    <p class="mb-0 text-muted">No upcoming court dates in the next 14 days.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-7">
        <div class="card">
            <div class="card-header">
                <div class="sm:flex items-center justify-between">
                    <h5>Tickets</h5>
                    <a href="{{ route($portal.'.tickets.index') }}" class="btn btn-secondary">View All</a>
                </div>
            </div>
            <div class="card-body !px-0 sm:!px-3">
                <div class="tc-table-scroll-container tc-driver-tickets-table">
                    <table class="table tc-clean-table mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th class="tc-col-optional">State</th>
                            <th class="tc-col-optional">Company</th>
                            <th>Status</th>
                            <th class="tc-col-optional">Pts Saved</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td class="tc-col-name">{{ $ticket->name }}</td>
                            <td>{{ $ticket->date_issued ? \Carbon\Carbon::parse($ticket->date_issued)->format('M j, Y') : '—' }}</td>
                            <td class="tc-col-optional">{{ $ticket->state ?: '—' }}</td>
                            <td class="tc-col-optional">{{ optional($ticket->company)->name ?: '—' }}</td>
                            <td>{{ $ticket->indicator ?: '—' }}</td>
                            <td class="tc-col-optional">{{ number_format($ticket->points_saved, 1) }}</td>
                            <td>
                                <a href="{{ route($portal.'.tickets.show', $ticket->id) }}" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                    <i class="ti ti-eye text-xl leading-none"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center !whitespace-normal">
                                    <h4 class="my-4">
                                        You don't have any tickets, You can submit one from here
                                    </h4>
                                    <a href="{{ route($portal.'.tickets.create') }}" class="btn btn-primary">Create Ticket</a>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recently Closed Tickets</h5>
            </div>
            <div class="card-body">
                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                    @forelse($recentClosedTickets as $ticket)
                        <li class="list-group-item">
                            <div class="flex items-center justify-between gap-3">
                                <div class="grow">
                                    <h6 class="mb-1">{{ $ticket->name }}</h6>
                                    <p class="mb-0 text-muted">
                                        Company: {{ optional($ticket->company)->name ?: 'N/A' }} |
                                        Final Status: {{ $ticket->indicator ?: 'Closed' }}
                                    </p>
                                    <p class="mb-0 mt-1 text-muted">
                                        Updated: {{ optional($ticket->updated_at)?->format('M j, Y g:i A') ?: 'N/A' }} |
                                        Points Saved: {{ number_format($ticket->points_saved, 1) }}
                                    </p>
                                </div>
                                <a href="{{ route($portal.'.tickets.show', $ticket->id) }}" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                    <i class="ti ti-eye text-xl leading-none"></i>
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No closed tickets yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('pre-scripts')
    <script src="{{ asset('js/plugins/apexcharts.min.js') }}"></script>
@endsection

@section('post-scripts')
    <script src="{{ asset('js/widgets/invites-goal-chart.js') }}"></script>
@endsection
