@extends('layout.master')
@section('content')
    <!-- Search API (Regular Expressions) table start -->
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
                                            <label class="form-label text-primary text-[18px] font-bold">Sender Type</label>
                                            <select
                                                class="form-control"
                                                name="sender_type"
                                                id="sender_type"
                                            >
                                                <option value="">Select Type</option>
                                                <option value="{{ \App\Models\OutgoingLog::TYPE_SMS }}">SMS</option>
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
            <div class="card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Outgoing Log</h5>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered yajra-datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="truncate">Request</th>
                            <th class="truncate">Response</th>
                            <th>Sender Type</th>
                            <th>Context</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Search API (Regular Expressions) table end -->
        @endsection

        @section('post-scripts')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="{{ asset('js/plugins/dataTables.min.js') }}"></script>
            <script src="{{ asset('js/plugins/dataTables.bootstrap5.min.js') }}"></script>
            <script>
                var table = $('.yajra-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    ajax: {
                        url: '{{ route("admin.outgoinglogs.index") }}',
                        data: function (d) {
                            d.status = $('#sender_type').val();
                        },
                    },
                    columns: [
                        { data: 'id', name: 'id' },
                        {
                            data: 'request',
                            name: 'request',
                            render: function(data, type, row) {
                                if (type === 'display' && data && data.length > 60) {
                                    return `<span title="${data.replace(/"/g, '&quot;')}">${data.substring(0, 60)}...</span>`;
                                }
                                return data;
                            }
                        },
                        {
                            data: 'response',
                            name: 'response',
                            render: function(data, type, row) {
                                if (type === 'display' && data && data.length > 60) {
                                    return `<span title="${data.replace(/"/g, '&quot;')}">${data.substring(0, 60)}...</span>`;
                                }
                                return data;
                            }
                        },
                        { data: 'sender_type', name: 'sender_type' },
                        { data: 'context_type', name: 'context_type' },
                    ],
                    order: [[0, 'desc']],
                });


                // Apply the filters
                $('#filterForm').on('submit', function (e) {
                    e.preventDefault();
                    table.draw();
                });

                // Reset the filters
                $('#filterForm').on('reset', function () {
                    $('input, select').val('');
                    table.draw();
                });

            </script>
        @endsection

        @section('css')
            <link rel="stylesheet" href="{{ asset('css/plugins/flatpickr.min.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/plugins/choices.min.css') }}" />
            <style>
                td.truncate {
                    max-width: 300px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
            </style>
@endsection
