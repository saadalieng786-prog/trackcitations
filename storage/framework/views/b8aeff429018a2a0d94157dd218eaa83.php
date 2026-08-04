<?php $__env->startSection('content'); ?>
    <?php $portal = auth()->user()->portalRoutePrefix(); ?>
    <div class="col-span-12 lg:col-span-4 md:col-span-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-primary-500/10 text-primary-500">
                            <i class="ti ti-file text-2xl leading-none"></i>
                        </div>
                    </div>
                    <div class="grow ltr:ml-3 rtl:mr-3">
                        <p class="mb-1">Open Tickets</p>
                        <h4 class="mb-0"><?php echo e($stats['open_tickets']); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-4 md:col-span-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-success-500/10 text-success-500">
                            <i class="ti ti-file-check text-2xl leading-none"></i>
                        </div>
                    </div>
                    <div class="grow ltr:ml-3 rtl:mr-3">
                        <p class="mb-1">Closed Tickets</p>
                        <h4 class="mb-0"><?php echo e($stats['closed_tickets']); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-4 md:col-span-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-info-500/10 text-info-500">
                            <i class="ti ti-chart-arrows text-2xl leading-none"></i>
                        </div>
                    </div>
                    <div class="grow ltr:ml-3 rtl:mr-3">
                        <p class="mb-1">Lifetime Points Saved</p>
                        <h4 class="mb-0"><?php echo e(number_format($stats['points_saved'], 2)); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-5">
        <div class="card">
            <div class="card-header">
                <div class="sm:flex items-center justify-between">
                    <h5>Next Important Date</h5>
                    <a href="<?php echo e(route($portal.'.tickets.index')); ?>" class="btn btn-secondary">View All</a>
                </div>
            </div>
            <div class="card-body">
                <?php if($upcomingTicket): ?>
                    <div class="card overflow-hidden mb-0">
                        <div class="card-body !px-3 !py-3 border-l-4 border-danger-500">
                            <h6 class="mb-1"><?php echo e($upcomingTicket->name); ?></h6>
                            <p class="mb-1 text-muted">
                                Company: <?php echo e(optional($upcomingTicket->company)->name ?: 'N/A'); ?> |
                                Indicator: <?php echo e($upcomingTicket->indicator ?: 'Received'); ?>

                            </p>
                            <p class="mb-1 text-muted">
                                Court: <?php echo e($upcomingTicket->court_date ? \Carbon\Carbon::parse($upcomingTicket->court_date)->format('M j, Y g:i A') : 'Not scheduled'); ?>

                            </p>
                            <p class="mb-0 text-muted">
                                Points Saved: <?php echo e(number_format($upcomingTicket->points_saved, 1)); ?>

                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="mb-0 text-muted">No upcoming court dates in the next 14 days.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-7">
        <div class="card">
            <div class="card-header">
                <div class="sm:flex items-center justify-between">
                    <h5>Tickets</h5>
                    <a href="<?php echo e(route($portal.'.tickets.index')); ?>" class="btn btn-secondary">View All</a>
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
                        <th>Points Saved</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($ticket->id); ?></td>
                        <td><?php echo e($ticket->name); ?></td>
                        <td><?php echo e($ticket->date_issued); ?></td>
                        <td><?php echo e($ticket->state); ?></td>
                        <td><?php echo e($ticket->company->name); ?></td>
                        <td><?php echo e($ticket->indicator); ?></td>
                        <td><?php echo e(number_format($ticket->points_saved, 2)); ?></td>
                        <td>
                            <a href="<?php echo e(route($portal.'.tickets.show', $ticket->id)); ?>" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                <i class="ti ti-eye text-xl leading-none"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <h4 class="my-4">
                                    You don't have any tickets, You can submit one from here
                                </h4>
                                <a href="<?php echo e(route($portal.'.tickets.create')); ?>" class="btn btn-primary">Create Ticket</a></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-span-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recently Closed Tickets</h5>
            </div>
            <div class="card-body">
                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                    <?php $__empty_1 = true; $__currentLoopData = $recentClosedTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="list-group-item">
                            <div class="flex items-center justify-between gap-3">
                                <div class="grow">
                                    <h6 class="mb-1"><?php echo e($ticket->name); ?></h6>
                                    <p class="mb-0 text-muted">
                                        Company: <?php echo e(optional($ticket->company)->name ?: 'N/A'); ?> |
                                        Final Status: <?php echo e($ticket->indicator ?: 'Closed'); ?>

                                    </p>
                                    <p class="mb-0 mt-1 text-muted">
                                        Updated: <?php echo e(optional($ticket->updated_at)?->format('M j, Y g:i A') ?: 'N/A'); ?> |
                                        Points Saved: <?php echo e(number_format($ticket->points_saved, 1)); ?>

                                    </p>
                                </div>
                                <a href="<?php echo e(route($portal.'.tickets.show', $ticket->id)); ?>" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                    <i class="ti ti-eye text-xl leading-none"></i>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="list-group-item text-muted">No closed tickets yet.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('pre-scripts'); ?>
    <script src="<?php echo e(asset('js/plugins/apexcharts.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>
    <script src="<?php echo e(asset('js/widgets/invites-goal-chart.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views/driver/dashboard.blade.php ENDPATH**/ ?>