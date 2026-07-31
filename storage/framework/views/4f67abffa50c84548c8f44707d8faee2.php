<?php $__env->startSection('content'); ?>
    <?php
        $portal = auth()->user()->portalRoutePrefix();
    ?>
    <div class="col-span-12">
        <div class="pc-component">
            <div class="card">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between">
                        <h5 class="mb-3 sm:mb-0">Administrators list</h5>
                        <div>
                            <a href="<?php echo e(route($portal.'.admins.create')); ?>" class="btn btn-primary">Create Administrator</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered yajra-datatable">
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
                    ajax: {
                        url: '<?php echo e(route($portal.".admins.index")); ?>',
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

                document.addEventListener('submit', function (e) {
                    const deleteForm = e.target.closest('.delete-admin-form');
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

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\admins\index.blade.php ENDPATH**/ ?>