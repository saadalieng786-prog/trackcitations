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
                                            <label class="form-label text-primary text-[18px] font-bold">Driver</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo e(Request::get('name')); ?>" placeholder="Driver name">
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Company</label>
                                            <select
                                                class="form-control"
                                                name="company_id"
                                                id="companies"
                                            ></select>
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Attorney</label>
                                            <select
                                                class="form-control"
                                                name="attorney_id"
                                                id="attorneys"
                                            ></select>
                                        </div>
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
            <div class="card p-0 overflow-hidden">
                <div class="card-header">
                    <div class="sm:flex items-center justify-between gap-3">
                        <h5 class="mb-3 sm:mb-0">Tickets list</h5>
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="#!" class="js-download-tickets btn btn-success"><span class="fa fa-file-excel mr-2"></span>Download Tickets</a>
                            <?php if(auth()->user()->roleable->companiesCountWithWriteAccess()): ?>
                                <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.tickets.create')); ?>" class="btn btn-primary">Create Ticket</a>
                            <?php endif; ?>
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
        <!-- Search API (Regular Expressions) table end -->
        <?php $__env->stopSection(); ?>

        <?php $__env->startSection('post-scripts'); ?>
            <script src="<?php echo e(asset('js/plugins/flatpickr.min.js')); ?>"></script>
            <script src="<?php echo e(asset('js/plugins/choices.min.js')); ?>"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <script src="<?php echo e(asset('js/plugins/dataTables.min.js')); ?>"></script>
            <script src="<?php echo e(asset('js/plugins/dataTables.bootstrap5.min.js')); ?>"></script>
            <script>
                $(document).ready(function () {
                var table = $('.yajra-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    autoWidth: false,
                    dom: "<'dt-controls-bar'l f><'tc-table-scroll-container't><'dt-footer-bar'i p>",
                    ajax: {
                        url: '<?php echo e(route(auth()->user()->portalRoutePrefix().".tickets.index")); ?>',
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
                            data: 'company_html',
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
                    order: [[0, 'desc']], // Default sort by the first column (id) in descending order
                });

                // Apply the filters
                $('#filterForm').on('submit', function (e) {
                    e.preventDefault();
                    table.draw();
                });

                // Reset the filters
                $('#filterForm').on('reset', function () {
                    setTimeout(function () {
                        $('input, select', '#filterForm').val('');
                        table.draw();
                    }, 0);
                });


                // minimum setup
                flatpickr(document.querySelector('#courtDate'), {
                    mode: 'range',
                    <?php if(Request::get('court_date')): ?>
                    defaultDate: [new Date('<?php echo e(explode(' to ',  Request::get('court_date'))[0]); ?>'), new Date('<?php echo e(explode(' to ',  Request::get('court_date'))[1] ?? explode(' to ', Request::get('court_date'))[0]); ?>')]
                    <?php endif; ?>
                });

                var companiesChoices = new Choices('#companies', {
                    placeholder: true,
                    placeholderValue: 'Company Name',
                    maxItemCount: 5,
                    shouldSort: false, // Optional: keeps the order of items as provided
                }).setChoices(function () {
                    return fetch('<?php echo e(route('api.company.index')); ?>')
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function (data) {
                            return [{
                                value: '',
                                label: 'Select an option',
                                disabled: false,
                                selected: <?php echo e(!Request::has('company_id') ? 'true' : 'false'); ?> },
                                ...data.map(function (company) {
                                    return {
                                        value: company.id,
                                        label: company.name,
                                        selected: Number('<?php echo e(Request::get('company_id')); ?>') === Number(company.id)
                                    };
                                })];
                        });
                });

                var attorneysChoices = new Choices('#attorneys', {
                    placeholder: true,
                    placeholderValue: 'Attorney Name',
                    maxItemCount: 5,
                    shouldSort: false, // Optional: keeps the order of items as provided
                }).setChoices(function () {
                    return fetch('<?php echo e(route('api.attorney.index')); ?>', {
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },credentials: 'include'
                    }).then(function (response) {
                        return response.json();
                    })
                        .then(function (data) {
                            return [{
                                value: '',
                                label: 'Select an option',
                                disabled: true,
                                selected: <?php echo e(!Request::has('attorney_id') ? 'true' : 'false'); ?> },
                                ...data.map(function (attorney) {
                                    return {
                                        value: attorney.roleable.id,
                                        label: attorney.name,
                                        selected: Number('<?php echo e(Request::get('attorney_id')); ?>') === Number(attorney.roleable.id)
                                    };
                                })];
                        });
                });

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
                });
            </script>
        <?php $__env->stopSection(); ?>

        <?php $__env->startSection('css'); ?>
            <link rel="stylesheet" href="<?php echo e(asset('css/plugins/flatpickr.min.css')); ?>" />
            <link rel="stylesheet" href="<?php echo e(asset('css/plugins/choices.min.css')); ?>" />
            <link rel="stylesheet" href="<?php echo e(asset('css/plugins/dataTables.bootstrap5.min.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\manager\tickets\index.blade.php ENDPATH**/ ?>