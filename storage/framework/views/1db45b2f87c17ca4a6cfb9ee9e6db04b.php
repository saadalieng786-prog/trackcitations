<?php $__env->startSection('body'); ?>
    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        
        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
            <a href="<?php echo e(url('/')); ?>" class="flex justify-center mb-8">
                <img src="<?php echo e(asset('images/logo-dark.png')); ?>" alt="CDL CONSULTANT Logo" class="h-14 w-auto opacity-90 hover:opacity-100 transition-opacity" />
            </a>
            <h2 class="text-center text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Welcome back</h2>
            <p class="text-center text-slate-500 text-sm font-medium">Log in to manage your citations and fleet.</p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
            <div class="bg-white py-10 px-6 sm:px-10 shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100">
                <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    
                    
                    <?php if($errors->any()): ?>
                        <div class="p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3 shadow-sm mb-6">
                            <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="ti ti-alert-circle text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-red-800 font-bold text-xs uppercase tracking-wider mb-1 mt-1">Login Failed</h4>
                                <ul class="list-disc ps-4 mb-0 text-sm text-red-700 space-y-1">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div>
                        <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ti ti-mail text-slate-400"></i>
                            </div>
                            <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus
                                class="form-control w-full pl-11 pr-4 py-3.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php echo e($errors->has('email') ? 'border-red-500 bg-red-50/50' : 'bg-slate-50/50'); ?>"
                                placeholder="name@example.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Password</label>
                            <a href="<?php echo e(route('password.request')); ?>" class="text-xs font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                                Forgot your password?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="ti ti-lock text-slate-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="form-control w-full pl-11 pr-4 py-3.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 bg-slate-50/50"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <input id="remember" name="remember" type="checkbox" <?php echo e(old('remember') ? 'checked' : ''); ?>

                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer mt-0.5">
                            <label for="remember" class="block text-sm text-slate-600 cursor-pointer font-medium m-0">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="btn btn-primary w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all">
                            <i class="ti ti-login me-2 text-lg"></i> Sign in to Dashboard
                        </button>
                    </div>
                </form>
            </div>
            
            <p class="mt-8 text-center text-sm text-slate-500 font-medium">
                Secure & Encrypted <i class="ti ti-shield-check text-emerald-500 ms-1"></i>
            </p>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.partials.body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views/auth/login.blade.php ENDPATH**/ ?>