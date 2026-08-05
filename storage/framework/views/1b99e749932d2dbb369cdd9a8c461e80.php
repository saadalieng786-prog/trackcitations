<?php $__env->startSection('content'); ?>
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
                                            <label class="form-label text-primary text-[18px] font-bold">Court Date</label>
                                            <div class="input-group date">
                                                <input type="text" id="courtDate" name="court_date" placeholder="Select date range"  name="date_issued" class="form-control" />
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
                                                <option value="<?php echo e(\App\Models\Ticket::TICKET_STATUS_OPEN); ?>">Open</option>
                                                <option value="<?php echo e(\App\Models\Ticket::TICKET_STATUS_CLOSED); ?>">Closed</option>
                                                <option value="<?php echo e(\App\Models\Ticket::TICKET_STATUS_ARCHIVED); ?>">Archived</option>
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
                        <h5 class="mb-3 sm:mb-0">Tickets list</h5>
                        <div>
                            <a href="#!" class="js-download-tickets btn btn-success"><span class="fa fa-file-excel mr-2"></span>Download Tickets</a>
                            <a href="<?php echo e(route('driver.tickets.create')); ?>" class="btn btn-primary">Create Ticket</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered yajra-datatable">
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
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Search API (Regular Expressions) table end -->
        <?php $__env->stopSection(); ?>

        <?php $__env->startSection('post-scripts'); ?>
            <script src="<?php echo e(asset('js/plugins/flatpickr.min.js')); ?>"></script>
            <script src="<?php echo e(asset('js/plugins/choices.min.js')); ?>"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="<?php echo e(asset('js/plugins/dataTables.min.js')); ?>"></script>
            <script src="<?php echo e(asset('js/plugins/dataTables.bootstrap5.min.js')); ?>"></script>
            <script>
                var table = $('.yajra-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    ajax: {
                        url: '<?php echo e(route("driver.tickets.index")); ?>',
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
                        {data: 'name', name: 'name'},
                        {data: 'date_issued', name: 'date_issued'},
                        {data: 'state', name: 'state'},
                        {
                            data: 'company.name',
                            name: 'company.name',
                            orderable: false,
                            searchable: false
                        },
                        {data: 'indicator', name: 'indicator'},
                        {data: 'original_points_value', name: 'original_points_value', orderable: false, searchable: false},
                        {data: 'final_points_value', name: 'final_points_value', orderable: false, searchable: false},
                        {data: 'points_saved', name: 'points_saved', orderable: false, searchable: false},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    order: [[0, 'desc']], // Default sort by the first column (id) in descending order
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


                // minimum setup
                flatpickr(document.querySelector('#courtDate'), {
                    mode: 'range',
                    <?php if(Request::get('court_date')): ?>
                    defaultDate: [new Date('<?php echo e(explode(' to ',  Request::get('court_date'))[0]); ?>'), new Date('<?php echo e(explode(' to ',  Request::get('court_date'))[1]); ?>')]
                    <?php endif; ?>
                });

                // document.querySelector('#bulkActionForm').addEventListener('submit', function (e) {
                //     const form = e.target;
                //     const selectedAction = document.querySelector('#bulkAction').value;
                //     const selectedCheckboxes = Array.from(document.querySelectorAll('input[type="checkbox"][data-ticketid]:checked'));
                //
                //     // Ensure an action is selected and at least one checkbox is checked
                //     if (!selectedAction || selectedCheckboxes.length === 0) {
                //         e.preventDefault(); // Stop form submission
                //         return Toast.fire({
                //             icon: 'info',
                //             title: 'Please select an action and at least one item.'
                //         });
                //     }
                //
                //     // Remove any existing hidden inputs for IDs
                //     form.querySelectorAll('input[name="ids[]"]').forEach(input => input.remove());
                //
                //     // Append the checked IDs as hidden inputs to the form
                //     selectedCheckboxes.forEach(checkbox => {
                //         const hiddenInput = document.createElement('input');
                //         hiddenInput.type = 'hidden';
                //         hiddenInput.name = 'ids[]';
                //         hiddenInput.value = checkbox.dataset.ticketid;
                //         form.appendChild(hiddenInput);
                //     });
                // });

                document.addEventListener('submit', function (e) {
                    const deleteForm = e.target.closest('.delete-ticket-form');
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

        <?php $__env->startSection('css'); ?>
            <link rel="stylesheet" href="<?php echo e(asset('css/plugins/flatpickr.min.css')); ?>" />
            <link rel="stylesheet" href="<?php echo e(asset('css/plugins/choices.min.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\driver\tickets\index.blade.php ENDPATH**/ ?>