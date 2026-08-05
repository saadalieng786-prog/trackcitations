<?php $__env->startSection('body'); ?>
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
                                <span class="px-4 bg-theme-cardbg dark:bg-themedark-cardbg">Email verification</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <?php if(session('status') === 'verification-link-sent'): ?>
                                <div class="mb-4 font-medium text-sm text-green-600">
                                    <?php echo e(__('A new verification link has been sent to the email address you provided during registration.')); ?>

                                </div>
                            <?php endif; ?>
                            <div class="mb-4 text-sm text-gray-600">
                                <?php echo e(__('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.')); ?>

                            </div>
                        </div>
                        <div class="mt-4">
                            <form method="POST" action="<?php echo e(route('verification.send')); ?>">
                                <?php echo csrf_field(); ?>

                                <div>
                                    <button type="submit" class="btn btn-primary w-full"><?php echo e(__('Resend Verification Email')); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.partials.body', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>