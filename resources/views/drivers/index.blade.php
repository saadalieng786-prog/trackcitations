@extends('layout.master')
@section('content')
    <div class="col-span-12">
        <div class="pc-component">
            <div class="card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Drivers list</h5>
                        @if (!(request()->has('status') && request()->get('status') == 0))
                        @if(auth()->user()->isInternalAdmin())
                        <div>
                            <a href="{{ route(auth()->user()->portalRoutePrefix().'.drivers.create') }}" class="btn btn-primary">Create Driver</a>
                        </div>
                        @endif
                        @if(auth()->user()->isCompanyAdmin() && auth()->user()->roleable->companiesCountWithWriteAccess())
                        <div>
                            <a href="{{ route(auth()->user()->portalRoutePrefix().'.drivers.create') }}" class="btn btn-primary">Create Driver</a>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered yajra-datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Open</th>
                            <th>Closed</th>
                            <th>Points Saved</th>
                            <th>Last access</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
                        url: '{{ route(auth()->user()->portalRoutePrefix().".drivers.index") }}',
                        data: function (d) {
                            d.status = {{ request('status', 1) }};
                        },
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'company_name', name: 'company_name', orderable: false},
                        {data: 'state', name: 'state'},
                        {data: 'city', name: 'city'},
                        {data: 'open_tickets', name: 'open_tickets', orderable: false, searchable: false},
                        {data: 'closed_tickets', name: 'closed_tickets', orderable: false, searchable: false},
                        {data: 'lifetime_points_saved', name: 'lifetime_points_saved', orderable: false, searchable: false},
                        {data: 'last_login_at', name: 'last_login_at'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    order: [[0, 'desc']], // Default sort by the first column (id) in descending order
                });

                document.addEventListener('submit', function (e) {
                    const deleteForm = e.target.closest('.delete-driver-form');
                    if (deleteForm) {
                        e.preventDefault(); // Prevent form submission

                        // Show SweetAlert confirmation
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
                                deleteForm.submit(); // Submit the form if confirmed
                            }
                        });
                    }
                });
            </script>
@endsection
