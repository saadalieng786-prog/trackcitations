@extends('layout.master')

@section('content')
    @php $portal = auth()->user()->portalRoutePrefix(); @endphp
    <div class="col-span-12">

        {{-- ── PAGE TOP TITLE & ACTIONS ─────────────────── --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 m-0 tracking-tight">Tickets list</h1>
                <div class="text-xs text-slate-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-indigo-600">Dashboard</a>
                    <span class="mx-1.5 text-slate-300">/</span>
                    <span class="font-medium text-slate-700">Tickets</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" 
                        class="btn btn-outline-secondary btn-sm flex items-center gap-2"
                        onclick="$('#filterCard').toggleClass('hidden')">
                    <i class="ti ti-adjustments-horizontal text-base"></i> Filter Options
                </button>
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

        {{-- ── FILTER ACCORDION CARD (Hidden by default, 0 height) ── --}}
        <div class="hidden mb-4" id="filterCard">
            <div class="card p-4">
                <form method="GET" id="filterForm">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Driver</label>
                            <input type="text" class="form-control" name="name" value="{{ Request::get('name') }}" placeholder="Driver name">
                        </div>
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Company</label>
                            <select class="form-control" name="company_id" id="companies"></select>
                        </div>
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Attorney</label>
                            <select class="form-control" name="attorney_id" id="attorneys"></select>
                        </div>
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Court Date</label>
                            <input type="text" id="courtDate" name="court_date" placeholder="Select date range" class="form-control" />
                        </div>
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label text-xs font-bold text-slate-700 uppercase">Ticket Status</label>
                            <select class="form-control" name="status" id="ticketStatus">
                                <option value="">Select Ticket Status</option>
                                <option value="{{ \App\Models\Ticket::TICKET_STATUS_OPEN }}">Open</option>
                                <option value="{{ \App\Models\Ticket::TICKET_STATUS_CLOSED }}">Closed</option>
                                <option value="{{ \App\Models\Ticket::TICKET_STATUS_ARCHIVED }}">Archived</option>
                            </select>
                        </div>
                        <div class="col-span-12 flex items-end justify-end gap-2 mt-2">
                            <button type="reset" class="btn btn-outline-secondary btn-sm">Reset</button>
                            <button type="submit" class="btn btn-primary btn-sm">Apply Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── TABLE CARD ─────────────────────────────────── --}}
        <div class="card p-0 overflow-hidden">
            <table class="table tc-clean-table yajra-datatable w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date Received</th>
                        <th>State</th>
                        <th>Company</th>
                        <th>Indicator</th>
                        <th>Original Points</th>
                        <th>Final Points</th>
                        <th>Points Saved</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/plugins/flatpickr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/plugins/choices.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/plugins/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('post-scripts')
    <script src="{{ asset('js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/plugins/choices.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                autoWidth: false,
                dom: "<'dt-controls-bar'l f><'tc-table-scroll-container't><'dt-footer-bar'i p>",
                ajax: {
                    url: '{{ route(auth()->user()->portalRoutePrefix().".tickets.index") }}',
                    data: function (d) {
                        d.q = new URLSearchParams(window.location.search).get('q') || '';
                        d.name = $('input[name="name"]').val();
                        d.company_id = $('#companies').val();
                        d.attorney_id = $('#attorneys').val();
                        d.court_date = $('input[name="court_date"]').val();
                        d.status = $('#ticketStatus').val();
                    },
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name', defaultContent: '—'},
                    {data: 'date_issued', name: 'date_issued', defaultContent: '—'},
                    {data: 'state', name: 'state', defaultContent: '—'},
                    {
                        data: 'company.name',
                        name: 'company.name',
                        defaultContent: '—',
                        orderable: false,
                        searchable: false
                    },
                    {data: 'indicator', name: 'indicator', defaultContent: '—'},
                    {data: 'original_points_value', name: 'original_points_value', orderable: false, searchable: false, defaultContent: '0'},
                    {data: 'final_points_value', name: 'final_points_value', orderable: false, searchable: false, defaultContent: '0'},
                    {data: 'points_saved', name: 'points_saved', orderable: false, searchable: false, defaultContent: '0'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-right'
                    },
                ],
                order: [[0, 'desc']]
            });

            // Apply filters
            $('#filterForm').on('submit', function (e) {
                e.preventDefault();
                table.draw();
            });

            // Reset filters
            $('#filterForm').on('reset', function () {
                $('input, select').val('');
                table.draw();
            });

            // Datepicker
            flatpickr('#courtDate', {
                mode: 'range'
            });

            // Companies Dropdown Choices
            if (document.querySelector('#companies')) {
                new Choices('#companies', {
                    placeholder: true,
                    placeholderValue: 'Company Name',
                    maxItemCount: 5,
                    shouldSort: false
                }).setChoices(function () {
                    return fetch('{{ route("api.company.index") }}')
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            return [{
                                value: '',
                                label: 'Select an option',
                                disabled: false,
                                selected: true
                            }, ...data.map(function (company) {
                                return {
                                    value: company.id,
                                    label: company.name
                                };
                            })];
                        });
                });
            }

            // Attorneys Dropdown Choices
            if (document.querySelector('#attorneys')) {
                new Choices('#attorneys', {
                    placeholder: true,
                    placeholderValue: 'Attorney Name',
                    maxItemCount: 5,
                    shouldSort: false
                }).setChoices(function () {
                    return fetch('{{ route("api.attorney.index") }}', {
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        credentials: 'include'
                    }).then(function (res) { return res.json(); })
                    .then(function (data) {
                        return [{
                            value: '',
                            label: 'Select an option',
                            disabled: true,
                            selected: true
                        }, ...data.map(function (attorney) {
                            return {
                                value: attorney.roleable.id,
                                label: attorney.name
                            };
                        })];
                    });
                });
            }
        });
    </script>
@endsection
