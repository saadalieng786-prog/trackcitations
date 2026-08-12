@extends('layout.master')
@section('content')
    @php $portal = auth()->user()->portalRoutePrefix(); @endphp
    <div class="col-span-12">
        <div class="pc-component">
            <a class="btn mb-3 btn-secondary px-5" data-pc-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                <svg class="inline pc-icon w-[22px] h-[22px]">
                    <use xlink:href="#custom-document-filter"></use>
                </svg>
                <span class="inline font-bold">Filter</span>
            </a>
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <div class="mt-2 hidden multi-collapse" id="multiCollapseExample1" style="display: none;">
                        <form method="GET" id="filterForm">
                            <div class="card">
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 md:col-span-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Court Date</label>
                                            <div class="input-group date">
                                                <input type="text" id="courtDate" name="court_date" placeholder="Select date range" class="form-control" />
                                                <span class="input-group-text">
                                                      <i class="feather icon-calendar"></i>
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Ticket Status</label>
                                            <select
                                                class="form-control"
                                                name="status"
                                                id="ticketStatus"
                                            >
                                                <option value="">Select Ticket Status</option>
                                                <option value="{{ \App\Models\Ticket::TICKET_STATUS_OPEN }}">Open</option>
                                                <option value="{{ \App\Models\Ticket::TICKET_STATUS_CLOSED }}">Closed</option>
                                                <option value="{{ \App\Models\Ticket::TICKET_STATUS_ARCHIVED }}">Archived</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="flex justify-end">
                                        <button type="submit" class="btn btn-primary ltr:mr-1 rtl:ml-1">Apply</button>
                                        <button type="reset" class="btn btn-link-secondary">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card p-0 overflow-hidden">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between gap-3">
                        <h5 class="mb-3 sm:mb-0">Tickets list</h5>
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="#!" class="js-download-tickets btn btn-success"><span class="fa fa-file-excel mr-2"></span>Download Tickets</a>
                            <a href="{{ route($portal.'.tickets.create') }}" class="btn btn-primary">Create Ticket</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
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
        $(document).ready(function () {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                autoWidth: false,
                dom: "<'dt-controls-bar'l f><'tc-table-scroll-container't><'dt-footer-bar'i p>",
                ajax: {
                    url: '{{ route($portal.".tickets.index") }}',
                    data: function (d) {
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
                order: [[0, 'desc']],
            });

            $('#filterForm').on('submit', function (e) {
                e.preventDefault();
                table.draw();
            });

            $('#filterForm').on('reset', function () {
                setTimeout(function () {
                    $('input, select', '#filterForm').val('');
                    table.draw();
                }, 0);
            });

            flatpickr(document.querySelector('#courtDate'), {
                mode: 'range',
                @if (Request::get('court_date'))
                defaultDate: [new Date('{{ explode(' to ',  Request::get('court_date'))[0] }}'), new Date('{{ explode(' to ',  Request::get('court_date'))[1] ?? explode(' to ', Request::get('court_date'))[0] }}')]
                @endif
            });

            document.addEventListener('submit', function (e) {
                const deleteForm = e.target.closest('.delete-ticket-form');
                if (deleteForm) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteForm.submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
