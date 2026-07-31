@extends('layout.master')

@section('content')
    @php($portal = auth()->user()->portalRoutePrefix())
    <div class="col-span-12">

        {{-- ── PAGE TOP TITLE & ACTIONS ─────────────────── --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 m-0 tracking-tight">Pending Tickets list</h1>
                <div class="text-xs text-slate-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-indigo-600">Dashboard</a>
                    <span class="mx-1.5 text-slate-300">/</span>
                    <a href="{{ route($portal.'.tickets.index') }}" class="text-slate-500 hover:text-indigo-600">Tickets</a>
                    <span class="mx-1.5 text-slate-300">/</span>
                    <span class="font-medium text-slate-700">Pending</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="#!" 
                   class="js-download-tickets btn btn-outline-secondary btn-sm flex items-center gap-2"
                   title="Export tickets in background">
                    <i class="ti ti-download text-base"></i> Download Tickets
                </a>
                <a href="{{ route($portal.'.tickets.create') }}" class="btn btn-primary btn-sm flex items-center gap-2">
                    <i class="ti ti-plus text-base"></i> Create Ticket
                </a>
            </div>
        </div>

        {{-- ── TABLE CARD WITH RESPONSIVE SCROLL & DATATABLES CONTROLS ── --}}
        <div class="card p-0 overflow-hidden">
            <table class="table tc-clean-table yajra-datatable w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date Received</th>
                        <th>State</th>
                        <th>Company Name</th>
                        <th>Indicator</th>
                        <th>DVER</th>
                        <th>Updated</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>
                                <div class="font-semibold text-slate-900">{{ $ticket->name }}</div>
                            </td>
                            <td>{{ $ticket->date_issued ?? '—' }}</td>
                            <td>{{ $ticket->state ?? '—' }}</td>
                            <td>{{ $ticket->company?->name ?? '—' }}</td>
                            <td>
                                <span class="tc-badge-soft-orange">{{ $ticket->indicator ?? 'Pending' }}</span>
                            </td>
                            <td>
                                @if($ticket->isDverDataq()['DVER'])
                                    <span class="ti ti-circle-check-filled text-emerald-500 text-lg"></span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($ticket->updated_at)->diffForHumans() }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route($portal.'.tickets.show', $ticket->id) }}" 
                                       class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-slate-100 transition-colors"
                                       title="View Ticket">
                                        <i class="ti ti-eye text-lg"></i>
                                    </a>
                                    <a href="{{ route($portal.'.tickets.edit', $ticket->id) }}" 
                                       class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-slate-100 transition-colors"
                                       title="Edit Ticket">
                                        <i class="ti ti-edit text-lg"></i>
                                    </a>
                                    <form action="{{ route($portal.'.tickets.destroy', $ticket->id) }}" method="POST" class="inline delete-ticket-form">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" 
                                                class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors"
                                                title="Delete Ticket">
                                            <i class="ti ti-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/plugins/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('post-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.yajra-datatable').DataTable({
                paging: true,
                autoWidth: false,
                dom: "<'dt-controls-bar'l f><'tc-table-scroll-container't><'dt-footer-bar'i p>",
                order: [[0, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search pending tickets...",
                    lengthMenu: "_MENU_ entries per page"
                }
            });
        });
    </script>
@endsection
