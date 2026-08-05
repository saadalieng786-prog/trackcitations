<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
        <form action="<?php echo e(route('support.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 lg:col-span-12">
                    <div class="card">
                        <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Support</h5>
                            <span class="text-sm text-muted">If you're encountering an issue please don't hesitate to contact us.</span>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 sm:col-span-12">
                                    <div class="col-span-12 md:col-span-3">
                                        <label class="form-label text-primary text-[18px] font-bold">Subject</label>
                                        <input type="text" class="form-control" name="subject" id="subject" value="<?php echo e(old('subject')); ?>" required/>
                                        <?php if($errors->has('subject')): ?>
                                            <span class="invalid-feedback text-danger">
                                                <strong><?php echo e($errors->first('subject')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-span-12 sm:col-span-12">
                                    <div class="mb-3">
                                        <label class="form-label text-primary text-[18px] font-bold" for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="6" required><?php echo e(old('description')); ?></textarea>
                                        <?php if($errors->has('description')): ?>
                                            <span class="invalid-feedback text-danger">
                                                <strong><?php echo e($errors->first('description')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer flex flex-row-reverse">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\support.blade.php ENDPATH**/ ?>