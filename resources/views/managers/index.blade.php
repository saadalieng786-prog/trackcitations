@extends('layout.master')
@section('content')
    <div class="col-span-12">
        <div class="pc-component">
            <div class="card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Company Admins list</h5>
                        @if(auth()->user()->isCompanyAdmin() && auth()->user()->roleable->companiesCountWithWriteAccess())
                        <div>
                            <a href="{{ route(Auth::user()->portalRoutePrefix().'.managers.create') }}" class="btn btn-primary">Create Company Admin</a>
                        </div>
                        @endif
                        @if(auth()->user()->isInternalAdmin())
                        <div>
                            <a href="{{ route(Auth::user()->portalRoutePrefix().'.managers.create') }}" class="btn btn-primary">Create Company Admin</a>
                        </div>
                        @endif

                    </div>
                </div>
                <div class="card-body">
                    <div id="managers-table-wrapper" class="w-full" style="max-width: 100%; overflow-x: auto; overflow-y: hidden; -webkit-overflow-scrolling: touch;">
                        <table class="table table-bordered yajra-datatable" style="width:100%; min-width: 900px;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>State</th>
                                <th>City</th>
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
                    responsive: false,
                    bSortCellsTop: false,
                    ajax: {
                        url: '{{ route(auth()->user()->portalRoutePrefix().".managers.index") }}',
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'role_label', name: 'role_label', orderable: false},
                        {data: 'email', name: 'email'},
                        {data: 'state', name: 'state'},
                        {data: 'city', name: 'city'},
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

                const managersTableWrapper = document.getElementById('managers-table-wrapper');
                if (managersTableWrapper) {
                    managersTableWrapper.addEventListener('wheel', function (event) {
                        if (Math.abs(event.deltaY) > Math.abs(event.deltaX)) {
                            managersTableWrapper.scrollLeft += event.deltaY;
                            event.preventDefault();
                        }
                    }, { passive: false });
                }

                document.addEventListener('submit', function (e) {
                    const deleteForm = e.target.closest('.delete-manager-form');
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
