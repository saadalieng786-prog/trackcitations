<?php $__env->startSection('body'); ?>
    <form method="POST" action="<?php echo e(route('password.email')); ?>">
        <?php echo csrf_field(); ?>
        <div class="auth-main relative">
            <div class="auth-wrapper v1 flex items-center w-full h-full min-h-screen">
                <div class="auth-form flex items-center justify-center grow flex-col min-h-screen bg-cover relative p-6 bg-[url('<?php echo e(asset('images/authentication/img-auth-bg.jpg')); ?>')] dark:bg-none dark:bg-themedark-bodybg">
                    <div class="card sm:my-12 w-full max-w-[480px] shadow-none">
                        <div class="card-body !p-10">
                            <div class="text-center">
                                <a href="#"><img src="<?php echo e(asset('images/logo-dark.png')); ?>" alt="img" class="mx-auto h-header-height"/></a>
                            </div>
                            <div class="relative my-5">
                                <div aria-hidden="true" class="absolute flex inset-0 items-center">
                                    <div class="w-full border-t border-theme-border dark:border-themedark-border"></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <span class="px-4 bg-theme-cardbg dark:bg-themedark-cardbg">Forget Password</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <?php if(session('status')): ?>
                                    <div class="mb-4 font-medium text-sm text-green-600">
                                        <?php echo e(session('status')); ?>

                                    </div>
                                <?php endif; ?>
                                <div class="mb-4 text-sm text-gray-600">
                                    <?php echo e(__('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.')); ?>

                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" id="floatingInput" value="<?php echo e(old('email')); ?>" placeholder="Email Address" required autofocus />
                                <?php if($errors->has('email')): ?>
                                    <span class="invalid-feedback text-danger">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-full"><?php echo e(__('Email Password Reset Link')); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.partials.body', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>