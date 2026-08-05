<?php $__env->startSection('content'); ?>
    




    <?php
    $chunks = $upComingCourtDates->chunk(15);
?>

    <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-span-6">
            <div class="card">
                <div class="card-body">
                    <!--<h5 class="mb-4">Upcoming court dates</h5>-->
                    <?php $__empty_1 = true; $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $upComingCourtDate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $date = \Carbon\Carbon::parse($upComingCourtDate->court_date);
                            $day = $date->format('M d, D');
                            $time = $date->format('H:i');
                        ?>
                        <div class="card overflow-hidden mb-2">
                            <div class="card-body !px-3 !py-2 border-l-4 border-danger-500">
                                <h6 class="mb-1"><?php echo e($upComingCourtDate->name); ?></h6>
                                <p class="mb-1"><i class="ti ti-calendar"></i> <?php echo e($day); ?></p>
                                <p class="mb-0"><span class="ti ti-calendar-time"></span> <?php echo e($time); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="card overflow-hidden mb-2">
                            <div class="card-body !px-3 !py-2 border-l-4 border-danger-500">
                                No upcoming court dates!
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>






<?php $__env->stopSection(); ?>

<?php $__env->startSection('pre-scripts'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\admin\upcoming_court_date.blade.php ENDPATH**/ ?>