<?php $__env->startSection('content'); ?>
<?php $portal = auth()->user()->portalRoutePrefix(); ?>

<div class="col-span-12">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 m-0">Storage Settings</h1>
            <p class="text-sm text-slate-500 mt-1 mb-0">Configure attachment storage and verify disk connectivity.</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="mb-0 ps-5 list-disc"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xl:col-span-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Storage Configuration</h5></div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route($portal.'.storage.update')); ?>" class="grid grid-cols-12 gap-5">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label">Default Filesystem</label>
                            <select name="filesystem_disk" class="form-select" required>
                                <?php $__currentLoopData = ['local' => 'Local', 'public' => 'Public', 's3' => 'Amazon S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php if(old('filesystem_disk', $settings['filesystem_disk']) === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label">Ticket Attachments</label>
                            <select name="attachments_disk" class="form-select" required>
                                <?php $__currentLoopData = ['public' => 'Public', 's3' => 'Amazon S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php if(old('attachments_disk', $settings['attachments_disk']) === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label">Message Attachments</label>
                            <select name="message_attachments_disk" class="form-select" required>
                                <?php $__currentLoopData = ['public' => 'Public', 's3' => 'Amazon S3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php if(old('message_attachments_disk', $settings['message_attachments_disk']) === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-span-12"><hr class="my-1"><h6 class="mt-4 mb-0">Amazon S3 credentials</h6></div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Access Key ID</label>
                            <input name="aws_access_key_id" class="form-control" value="<?php echo e(old('aws_access_key_id', $settings['aws_access_key_id'])); ?>" autocomplete="off">
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Secret Access Key</label>
                            <input type="password" name="aws_secret_access_key" class="form-control" value="<?php echo e(old('aws_secret_access_key', $settings['aws_secret_access_key'])); ?>" autocomplete="new-password">
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label">Region</label>
                            <input name="aws_default_region" class="form-control" value="<?php echo e(old('aws_default_region', $settings['aws_default_region'])); ?>">
                        </div>
                        <div class="col-span-12 md:col-span-8">
                            <label class="form-label">Bucket</label>
                            <input name="aws_bucket" class="form-control" value="<?php echo e(old('aws_bucket', $settings['aws_bucket'])); ?>">
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Public URL (optional)</label>
                            <input type="url" name="aws_url" class="form-control" value="<?php echo e(old('aws_url', $settings['aws_url'])); ?>">
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Endpoint (optional)</label>
                            <input type="url" name="aws_endpoint" class="form-control" value="<?php echo e(old('aws_endpoint', $settings['aws_endpoint'])); ?>">
                        </div>
                        <div class="col-span-12">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="aws_use_path_style_endpoint" value="1" <?php if(old('aws_use_path_style_endpoint', $settings['aws_use_path_style_endpoint'])): echo 'checked'; endif; ?>>
                                <span>Use path-style endpoint</span>
                            </label>
                        </div>
                        <div class="col-span-12 flex justify-end">
                            <button class="btn btn-primary" type="submit">Save Storage Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-4 space-y-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Current Status</h5></div>
                <div class="card-body space-y-3">
                    <div class="flex justify-between"><span>Default disk</span><strong><?php echo e($status['default_disk']); ?></strong></div>
                    <div class="flex justify-between"><span>Ticket attachments</span><strong><?php echo e($status['attachments_disk']); ?></strong></div>
                    <div class="flex justify-between"><span>Message attachments</span><strong><?php echo e($status['message_attachments_disk']); ?></strong></div>
                    <div class="flex justify-between"><span>S3 configured</span><strong><?php echo e($status['s3_ready'] ? 'Yes' : 'No'); ?></strong></div>
                    <div class="flex gap-2 pt-3">
                        <?php $__currentLoopData = ['public', 's3']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $disk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <form method="POST" action="<?php echo e(route($portal.'.storage.test')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="disk" value="<?php echo e($disk); ?>">
                                <button class="btn btn-outline-secondary btn-sm" type="submit">Test <?php echo e(strtoupper($disk)); ?></button>
                            </form>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Attachment Diagnostics</h5></div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Ticket total</div><strong><?php echo e(number_format($diagnostics['ticket_attachments_total'])); ?></strong></div>
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Message total</div><strong><?php echo e(number_format($diagnostics['message_attachments_total'])); ?></strong></div>
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Ticket local</div><strong><?php echo e(number_format($diagnostics['ticket_attachment_local_urls'])); ?></strong></div>
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Ticket remote</div><strong><?php echo e(number_format($diagnostics['ticket_attachment_remote_urls'])); ?></strong></div>
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Message local</div><strong><?php echo e(number_format($diagnostics['message_attachment_local_urls'])); ?></strong></div>
                        <div class="rounded-lg bg-slate-50 p-3"><div class="text-slate-500">Message remote</div><strong><?php echo e(number_format($diagnostics['message_attachment_remote_urls'])); ?></strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views/admin/storage/index.blade.php ENDPATH**/ ?>