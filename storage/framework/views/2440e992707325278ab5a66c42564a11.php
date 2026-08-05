<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
        <form action="<?php echo e(route(auth()->user()->portalRoutePrefix().'.violations.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="tab-content">
                <div class="block tab-pane" id="citation">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="text-primary text-[28px] font-bold">Citation Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="violation">Citation</label>
                                                <input type="text"  name="violation" id="violation" class="form-control" value="<?php echo e(old('violation')); ?>" required autofocus />
                                                <?php if($errors->has('violation')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('violation')); ?></strong>
                                                        </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 text-right">
                    <button type="reset" class="btn btn-outline-secondary mx-1">Cancel</button>
                    <button type="submit" class="btn btn-primary mx-1">Create Citation</button>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('post-scripts'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\violations\create.blade.php ENDPATH**/ ?>