<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
        <div class="pc-component">
            <div class="card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Drivers list</h5>
                        <?php if(!(request()->has('status') && request()->get('status') == 0)): ?>
                        <?php if(auth()->user()->isInternalAdmin()): ?>
                        <div>
                            <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.drivers.create')); ?>" class="btn btn-primary">Create Driver</a>
                        </div>
                        <?php endif; ?>
                        <?php if(auth()->user()->isCompanyAdmin() && auth()->user()->roleable->companiesCountWithWriteAccess()): ?>
                        <div>
                            <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.drivers.create')); ?>" class="btn btn-primary">Create Driver</a>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div id="drivers-table-wrapper" class="w-full" style="max-width: 100%; overflow-x: auto; overflow-y: hidden; -webkit-overflow-scrolling: touch;">
                        <table class="table table-bordered yajra-datatable" style="width:100%; min-width: 900px;">
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
        </div>
        <?php $__env->stopSection(); ?>
        <?php $__env->startSection('post-scripts'); ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="<?php echo e(asset('js/plugins/dataTables.min.js')); ?>"></script>
            <script src="<?php echo e(asset('js/plugins/dataTables.bootstrap5.min.js')); ?>"></script>
            <script>
                var table = $('.yajra-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    responsive: false,
                    bSortCellsTop: false,
                    ajax: {
                        url: '<?php echo e(route(auth()->user()->portalRoutePrefix().".drivers.index")); ?>',
                        data: function (d) {
                            d.status = <?php echo e(request('status', 1)); ?>;
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

                const driversTableWrapper = document.getElementById('drivers-table-wrapper');
                if (driversTableWrapper) {
                    driversTableWrapper.addEventListener('wheel', function (event) {
                        if (Math.abs(event.deltaY) > Math.abs(event.deltaX)) {
                            driversTableWrapper.scrollLeft += event.deltaY;
                            event.preventDefault();
                        }
                    }, { passive: false });
                }

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views/drivers/index.blade.php ENDPATH**/ ?>