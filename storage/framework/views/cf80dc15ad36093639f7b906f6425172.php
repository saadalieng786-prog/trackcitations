<?php $__env->startSection('body'); ?>
    <form method="POST" action="<?php echo e(route('login')); ?>">
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
                                <span class="px-4 bg-theme-cardbg dark:bg-themedark-cardbg">Login with your email</span>
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
                        <div class="mb-4">
                            <input type="password" name="password" class="form-control" id="floatingInput1" placeholder="Password" />
                        </div>
                        <div class="flex mt-1 justify-between items-center flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input input-primary" type="checkbox" id="remember" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?> />
                                <label class="form-check-label text-muted" for="remember">Remember me?</label>
                            </div>
                            <h6 class="font-normal text-primary-500 mb-0">
                                <a href="<?php echo e(route('password.request')); ?>"> Forgot Password? </a>
                            </h6>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-full">Login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.partials.body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\auth\login.blade.php ENDPATH**/ ?>