<?php $__env->startSection('content'); ?>
    <div class="col-span-12 lg:col-span-12">
        <div class="card">
            <div class="card-header">
                <div class="sm:flex items-center justify-between">
                    <h5>Tickets</h5>
                    <a href="<?php echo e(route('attorney.tickets.index')); ?>" class="btn btn-secondary">View All</a>
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
                            <td>
                                <a href="<?php echo e(route('attorney.tickets.show', $ticket->id)); ?>" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                    <i class="ti ti-eye text-xl leading-none"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <h4 class="my-4">
                                    You don't have any tickets
                                </h4>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
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

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\attorney\dashboard.blade.php ENDPATH**/ ?>