<?php $__env->startSection('content'); ?>
    <div class="col-span-12 lg:col-span-12">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="mb-0">Logs</h5>
                    <div class="dropdown">
                        <a
                            class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary dropdown-toggle arrow-none"
                            href="#"
                            data-pc-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            <i class="ti ti-dots-vertical text-lg leading-none"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Today</a>
                            <a class="dropdown-item" href="#">Weekly</a>
                            <a class="dropdown-item" href="#">Monthly</a>
                        </div>
                    </div>
                </div>
                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <span class="fa fa-info"></span>
                                </div>
                                <div class="grow mx-3">
                                    <h6 class="mb-0"><?php echo e($log->description); ?></h6>
                                </div>
                                <div class="shrink-0">
                                    <p class="mb-0 text-muted">
                                        <?php echo e(\Carbon\Carbon::parse($log->created_at)->format('M j, Y | g:i A')); ?>

                                    </p>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div class="card-footer">
                <?php echo e($logs->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\admin\logs\index.blade.php ENDPATH**/ ?>