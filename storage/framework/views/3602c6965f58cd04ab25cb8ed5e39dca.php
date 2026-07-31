<?php $__env->startSection('body'); ?>
    <?php
        $dashboardUrl = route('dashboard');
        $violationOptions = $violations->take(250);
    ?>

    
    <nav class="hp-nav">
        <a href="<?php echo e(url('/')); ?>" class="flex items-center gap-3 no-underline">
            <img src="<?php echo e(asset('images/logo-dark.png')); ?>" class="front-logo h-14 md:h-16 w-auto object-contain py-1" alt="CDL CONSULTANT Logo">
        </a>

        <div class="flex items-center gap-3">
            <a href="#ticket-form" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-plus me-1"></i> Submit Ticket
            </a>
            <a href="<?php echo e($dashboardUrl); ?>" class="btn btn-primary btn-sm">
                <i class="ti ti-dashboard me-1"></i>
                <?php echo e(auth()->check() ? 'Dashboard' : 'Client Login'); ?>

            </a>
        </div>
    </nav>

    
    <div class="hp-hero">
        <div class="max-w-[1140px] mx-auto">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-bold mb-4">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                Secure Ticket Intake Portal
            </div>
            <h1 class="hp-hero-title">
                Submit & Track Your<br>
                <em>Citation Cases</em>
            </h1>
            <p class="hp-hero-sub">
                Fast, secure ticket submission for drivers and fleet companies. Our team reviews every case and works to minimize your violations.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="#ticket-form" class="btn btn-primary px-6 py-2.5">
                    <i class="ti ti-send me-2"></i> Submit a Ticket
                </a>
                <a href="<?php echo e($dashboardUrl); ?>" class="btn btn-outline-secondary px-6 py-2.5 bg-white/10 text-white border-white/20 hover:bg-white/20">
                    <i class="ti ti-login me-2"></i>
                    <?php echo e(auth()->check() ? 'Open Dashboard' : 'Client Login'); ?>

                </a>
            </div>
        </div>
    </div>

    
    <div class="hp-main">

        <?php if($errors->any()): ?>
            <div class="max-w-[1140px] mx-auto mb-6">
                <div class="alert alert-danger p-4 rounded-xl text-sm flex gap-3 align-items-start">
                    <i class="ti ti-alert-circle text-lg shrink-0 mt-0.5"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-1 ps-4">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="max-w-[1140px] mx-auto mb-6">
                <div class="alert alert-success p-4 rounded-xl text-sm flex gap-3 align-items-center">
                    <i class="ti ti-circle-check text-lg"></i>
                    <?php echo e(session('success')); ?>

                </div>
            </div>
        <?php endif; ?>

        <div class="hp-grid">

            
            <div>
                <div class="card p-6 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-4 w-fit">
                        <i class="ti ti-info-circle"></i> Public Ticket Intake
                    </span>

                    <h2 class="text-xl font-bold text-slate-900 mb-2 leading-tight">
                        Submit a citation and our team will review it
                    </h2>

                    <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                        This portal is for drivers or support staff who need to open a new citation case. Fill out the form and we'll get started immediately.
                    </p>

                    <hr class="my-4 border-slate-100">

                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                        What to prepare
                    </div>

                    <ul class="list-none p-0 m-0 mb-6">
                        <li class="flex items-start gap-3 py-2 text-sm text-slate-600 border-b border-slate-100">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="ti ti-check"></i>
                            </span>
                            Driver full name and email address
                        </li>
                        <li class="flex items-start gap-3 py-2 text-sm text-slate-600 border-b border-slate-100">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="ti ti-check"></i>
                            </span>
                            Citation number and violation type
                        </li>
                        <li class="flex items-start gap-3 py-2 text-sm text-slate-600 border-b border-slate-100">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="ti ti-check"></i>
                            </span>
                            Date received, state, city, and plate
                        </li>
                        <li class="flex items-start gap-3 py-2 text-sm text-slate-600">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="ti ti-check"></i>
                            </span>
                            Ticket images or supporting documents
                        </li>
                    </ul>

                    <div class="flex flex-col gap-2">
                        <a href="#ticket-form" class="btn btn-primary w-full py-2.5">
                            <i class="ti ti-send me-2"></i> Go to Submission Form
                        </a>
                        <a href="<?php echo e($dashboardUrl); ?>" class="btn btn-outline-secondary w-full py-2.5">
                            <?php echo e(auth()->check() ? 'Open Dashboard' : 'Login to Existing Account'); ?>

                        </a>
                    </div>
                </div>

                
                <div class="p-4 rounded-xl bg-slate-900 text-white flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-300 flex items-center justify-center text-xl shrink-0">
                        <i class="ti ti-shield-lock"></i>
                    </div>
                    <div>
                        <div class="font-bold text-xs text-white">Secure & Confidential</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">All submissions are encrypted and handled with care</div>
                    </div>
                </div>
            </div>

            
            <div id="ticket-form">
                <div class="card overflow-hidden">
                    <div class="p-6 bg-gradient-to-r from-indigo-600 to-indigo-800 text-white flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-white m-0">New Ticket Submission</h2>
                            <p class="text-xs text-indigo-100 m-0 mt-1">Enter the citation details below to open a new case</p>
                        </div>
                        <span class="px-3 py-1 rounded-md bg-white/10 text-xs font-semibold text-white flex items-center gap-1.5 border border-white/20">
                            <i class="ti ti-lock text-xs"></i> Secure Intake
                        </span>
                    </div>

                    <div class="p-6">
                        <form action="<?php echo e(route('submit.ticket')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>

                            
                            <div class="mb-6">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="ti ti-user text-indigo-600"></i> Driver Information
                                </div>
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-6">
                                        <label class="form-label text-xs font-bold text-slate-700" for="name">Driver Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" value="<?php echo e(old('name')); ?>" placeholder="Full name of driver">
                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-span-12 md:col-span-6">
                                        <label class="form-label text-xs font-bold text-slate-700" for="user_email">Driver Email <span class="text-red-500">*</span></label>
                                        <input type="email" name="user_email" class="form-control <?php $__errorArgs = ['user_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="user_email" value="<?php echo e(old('user_email')); ?>" placeholder="driver@example.com">
                                        <?php $__errorArgs = ['user_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-span-12">
                                        <label class="form-label text-xs font-bold text-slate-700" for="company_name">Company Name</label>
                                        <input type="text" name="company_name" class="form-control <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="company_name" value="<?php echo e(old('company_name')); ?>" placeholder="Optional — company or fleet name">
                                        <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mb-6">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="ti ti-file-description text-indigo-600"></i> Citation Details
                                </div>
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-6">
                                        <label class="form-label text-xs font-bold text-slate-700" for="citation_no">Citation Number <span class="text-red-500">*</span></label>
                                        <input type="text" name="citation_no" class="form-control <?php $__errorArgs = ['citation_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="citation_no" value="<?php echo e(old('citation_no')); ?>" placeholder="e.g. TC-2024-00123">
                                        <?php $__errorArgs = ['citation_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-span-12 md:col-span-6">
                                        <label class="form-label text-xs font-bold text-slate-700" for="date_issued">Date Received <span class="text-red-500">*</span></label>
                                        <input type="text" name="date_issued" class="form-control <?php $__errorArgs = ['date_issued'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="date_issued" value="<?php echo e(old('date_issued')); ?>" placeholder="Select date received">
                                        <?php $__errorArgs = ['date_issued'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-span-12">
                                        <label class="form-label text-xs font-bold text-slate-700" for="violation_id">Violation Type <span class="text-red-500">*</span></label>
                                        <select name="violation_id" class="form-control <?php $__errorArgs = ['violation_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="violation_id">
                                            <option value="">— Select a violation —</option>
                                            <?php $__currentLoopData = $violationOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $violation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($violation->id); ?>" <?php echo e((string) old('violation_id') === (string) $violation->id ? 'selected' : ''); ?>>
                                                    <?php echo e($violation->violation); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['violation_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-span-12">
                                        <label class="form-label text-xs font-bold text-slate-700" for="description">Ticket Details</label>
                                        <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" rows="4" placeholder="Describe the situation or special instructions..."><?php echo e(old('description')); ?></textarea>
                                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mb-6">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="ti ti-car text-indigo-600"></i> Vehicle & Location
                                </div>
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-4">
                                        <label class="form-label text-xs font-bold text-slate-700" for="state">License State</label>
                                        <input type="text" name="state" class="form-control <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="state" value="<?php echo e(old('state', 'MD')); ?>" placeholder="State (e.g. MD)">
                                        <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <label class="form-label text-xs font-bold text-slate-700" for="city">License City</label>
                                        <input type="text" name="city" class="form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="city" value="<?php echo e(old('city')); ?>" placeholder="City">
                                        <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <label class="form-label text-xs font-bold text-slate-700" for="vehicle_lic_no">Vehicle Plate <span class="text-red-500">*</span></label>
                                        <input type="text" name="vehicle_lic_no" class="form-control <?php $__errorArgs = ['vehicle_lic_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="vehicle_lic_no" value="<?php echo e(old('vehicle_lic_no')); ?>" placeholder="Plate number">
                                        <?php $__errorArgs = ['vehicle_lic_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mb-6">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="ti ti-paperclip text-indigo-600"></i> Attachments
                                </div>
                                <div class="p-3 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50">
                                    <div class="grid grid-cols-12 gap-3">
                                        <div class="col-span-12 md:col-span-4">
                                            <label class="text-[11px] text-slate-400 font-bold">File 1</label>
                                            <input type="file" name="attachments[]" class="form-control text-xs" accept="image/*,.pdf,.doc,.docx">
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <label class="text-[11px] text-slate-400 font-bold">File 2</label>
                                            <input type="file" name="attachments[]" class="form-control text-xs" accept="image/*,.pdf,.doc,.docx">
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <label class="text-[11px] text-slate-400 font-bold">File 3</label>
                                            <input type="file" name="attachments[]" class="form-control text-xs" accept="image/*,.pdf,.doc,.docx">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-6">
                                <p class="text-xs text-slate-400 m-0">
                                    <i class="ti ti-shield-check me-1"></i> Submissions are SSL encrypted
                                </p>
                                <button type="submit" class="btn btn-primary px-8 py-2.5">
                                    <i class="ti ti-send me-2"></i> Submit Ticket
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div class="max-w-[1140px] mx-auto mt-8 text-center text-xs text-slate-400">
            &copy; <?php echo e(date('Y')); ?> Track Citations &mdash; All submissions are secure and confidential.
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/flatpickr.min.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>
    <script src="<?php echo e(asset('js/plugins/flatpickr.min.js')); ?>"></script>
    <script>
        flatpickr("#date_issued", {
            dateFormat: "m/d/Y",
            allowInput: true
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.partials.body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\homepage.blade.php ENDPATH**/ ?>