<?php $__env->startSection('body'); ?>
    <?php
        $dashboardUrl = route('dashboard');
        $violationOptions = $violations->take(250);
        $hour = (int) now()->format('G');
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    ?>

    <div class="hpx">
        <div class="hpx-mesh" aria-hidden="true"></div>

        <header class="hpx-top">
            <a href="<?php echo e(url('/')); ?>" class="hpx-logo">
                <img src="<?php echo e(asset('images/logo-dark.png')); ?>" alt="CDL CONSULTANT Logo">
            </a>
            <div class="hpx-top-right">
                <span class="hpx-date"><?php echo e(now()->format('D, M j')); ?></span>
                <a href="#" data-ticket-modal-open class="hpx-ghost">Submit ticket</a>
                <a href="<?php echo e($dashboardUrl); ?>" class="hpx-chip"><?php echo e(auth()->check() ? 'Dashboard' : 'Client login'); ?></a>
            </div>
        </header>

        <main class="hpx-main">
            <?php if(session('success')): ?>
                <div class="hpx-alert"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <section class="hpx-stage">
                <div class="hpx-hero">
                    <div>
                        <span class="hpx-pill">Client portal</span>
                        <h5 class="hpx-hello">Submit & Track Your Citation Cases</h5>
                        <p class="hpx-sub">Fast, secure ticket submission for drivers and fleet companies. Our team reviews every case and works to minimize your violations.</p>
                    </div>
                    <div class="hpx-preview">
                        <div class="hpx-preview-card">
                            <div class="hpx-preview-dots"><span></span><span></span><span></span></div>
                            <p class="hpx-preview-kicker">Live portal</p>
                            <p class="hpx-preview-type">
                                <span id="hpxTypeText"></span><span class="hpx-caret"></span>
                            </p>
                        </div>
                        <div class="hpx-preview-card back"></div>
                    </div>
                </div>

                <div class="hpx-bento">
                    <a href="<?php echo e($dashboardUrl); ?>" class="hpx-card hpx-card-main">
                        <span class="hpx-icon"><i class="ti ti-login"></i></span>
                        <span class="hpx-go"><i class="ti ti-arrow-right"></i></span>
                        <span class="hpx-card-label">Account</span>
                        <strong><?php echo e(auth()->check() ? 'Open dashboard' : 'Client login'); ?></strong>
                        <em><?php echo e(auth()->check() ? 'Continue to your workspace' : 'Access your company account'); ?></em>
                    </a>
                    <a href="#" data-ticket-modal-open class="hpx-card">
                        <span class="hpx-icon"><i class="ti ti-file-plus"></i></span>
                        <span class="hpx-go"><i class="ti ti-arrow-right"></i></span>
                        <span class="hpx-card-label">Intake</span>
                        <strong>Submit ticket</strong>
                        <em>Send citation details for review</em>
                    </a>
                </div>
            </section>

            <p class="hpx-foot">&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?></p>
        </main>
    </div>

    <div class="ticket-modal" id="ticketSubmissionModal" hidden aria-labelledby="ticketSubmissionModalLabel" aria-modal="true" role="dialog">
        <div class="ticket-modal-backdrop" data-ticket-modal-close></div>
        <div class="ticket-modal-dialog">
            <div class="modal-content border-0 overflow-hidden shadow-2xl rounded-2xl">
                <div class="p-6 md:p-8 bg-slate-900 text-white flex items-center justify-between border-b border-white/10">
                    <div>
                        <h2 class="text-xl font-bold text-white m-0 tracking-tight" id="ticketSubmissionModalLabel">Submit ticket</h2>
                        <p class="text-sm text-slate-300 m-0 mt-1.5">Enter the citation details below.</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-100 hover:opacity-80 transition-opacity" data-ticket-modal-close aria-label="Close"></button>
                </div>

                <div class="ticket-modal-body p-6 md:p-8 bg-slate-50">
                    <form id="ticketSubmitForm" action="<?php echo e(route('submit.ticket')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="hidden" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="">

                        <?php if($errors->any()): ?>
                            <div class="mb-8 p-5 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-4 shadow-sm">
                                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0 shadow-sm">
                                    <i class="ti ti-alert-circle text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-red-800 font-bold text-[13px] uppercase tracking-wide mb-2 mt-0.5">Please fix the following errors:</h4>
                                    <ul class="list-disc ps-5 mb-0 text-sm text-red-700 space-y-1">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-8 p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <i class="ti ti-user text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 m-0">Driver information</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="name">Driver name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" value="<?php echo e(old('name')); ?>" placeholder="Full name of driver">
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="user_email">Driver email <span class="text-red-500">*</span></label>
                                    <input type="email" name="user_email" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['user_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="user_email" value="<?php echo e(old('user_email')); ?>" placeholder="driver@example.com">
                                    <?php $__errorArgs = ['user_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="company_name">Company name</label>
                                    <input type="text" name="company_name" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="company_name" value="<?php echo e(old('company_name')); ?>" placeholder="Optional — company or fleet name">
                                    <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="phone">Phone <span class="text-red-500">*</span></label>
                                    <input type="tel" name="phone" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="phone" value="<?php echo e(old('phone')); ?>" placeholder="e.g. (555) 123-4567">
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                                    <i class="ti ti-file-description text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 m-0">Citation details</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="citation_no">Citation number <span class="text-red-500">*</span></label>
                                    <input type="text" name="citation_no" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['citation_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="citation_no" value="<?php echo e(old('citation_no')); ?>" placeholder="e.g. TC-2024-00123">
                                    <?php $__errorArgs = ['citation_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="date_issued">Date received <span class="text-red-500">*</span></label>
                                    <input type="text" name="date_issued" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 bg-white <?php $__errorArgs = ['date_issued'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="date_issued" value="<?php echo e(old('date_issued')); ?>" placeholder="Select date received">
                                    <?php $__errorArgs = ['date_issued'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="violation_id">Violation type <span class="text-red-500">*</span></label>
                                    <select name="violation_id" class="form-select w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['violation_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
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
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="description">Ticket details</label>
                                    <textarea name="description" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" rows="3" placeholder="Notes or special instructions"><?php echo e(old('description')); ?></textarea>
                                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <i class="ti ti-car text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 m-0">Vehicle and location</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="state">License state</label>
                                    <input type="text" name="state" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="state" value="<?php echo e(old('state', 'MD')); ?>" placeholder="e.g. MD">
                                    <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="city">License city</label>
                                    <input type="text" name="city" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="city" value="<?php echo e(old('city')); ?>" placeholder="City">
                                    <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="vehicle_lic_no">Vehicle plate <span class="text-red-500">*</span></label>
                                    <input type="text" name="vehicle_lic_no" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['vehicle_lic_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="vehicle_lic_no" value="<?php echo e(old('vehicle_lic_no')); ?>" placeholder="Plate number">
                                    <?php $__errorArgs = ['vehicle_lic_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <i class="ti ti-paperclip text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 m-0">Attachments</h3>
                            </div>
                            <div class="p-8 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50">
                                <div class="text-center mb-6">
                                    <p class="text-sm text-slate-600 font-medium m-0">Upload images or PDF documents of the citation</p>
                                    <p class="text-[11px] text-slate-400 mt-1">Maximum file size: 10MB per file</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <input type="file" name="attachments[]" class="form-control w-full text-xs py-2.5 bg-white rounded-xl border-slate-200 cursor-pointer shadow-sm hover:border-slate-300" accept="image/*,.pdf,.doc,.docx">
                                    </div>
                                    <div>
                                        <input type="file" name="attachments[]" class="form-control w-full text-xs py-2.5 bg-white rounded-xl border-slate-200 cursor-pointer shadow-sm hover:border-slate-300" accept="image/*,.pdf,.doc,.docx">
                                    </div>
                                    <div>
                                        <input type="file" name="attachments[]" class="form-control w-full text-xs py-2.5 bg-white rounded-xl border-slate-200 cursor-pointer shadow-sm hover:border-slate-300" accept="image/*,.pdf,.doc,.docx">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="math_answer">
                                Quick check: what is <?php echo e($mathQuestion); ?>? <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="math_answer" id="math_answer" inputmode="numeric" class="form-control w-full max-w-xs px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['math_answer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('math_answer')); ?>" placeholder="Enter the total">
                            <?php $__errorArgs = ['math_answer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback text-xs mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-xs text-slate-400 mt-2 mb-0">This helps keep spam submissions out.</p>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-end pt-2">
                            <button type="submit" class="btn btn-primary px-10 py-3.5 border-0 rounded-xl font-semibold text-base w-full sm:w-auto">
                                Submit ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/flatpickr.min.css')); ?>" />
    <style>
        :root {
            --hpx-bg: #f8fafc;
            --hpx-ink: #0f172a;
            --hpx-muted: #64748b;
            --hpx-line: #e2e8f0;
            --hpx-indigo: #4f46e5;
        }
        body { background: #f8fafc !important; color: #0f172a !important; }
        .hpx {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            color: var(--hpx-ink);
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
        }
        .hpx-mesh {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(42% 36% at 8% 0%, rgba(79, 70, 229, 0.16), transparent 58%),
                radial-gradient(38% 32% at 96% 8%, rgba(59, 130, 246, 0.14), transparent 56%),
                radial-gradient(30% 28% at 70% 100%, rgba(99, 102, 241, 0.10), transparent 60%),
                #f8fafc;
        }
        .hpx-top, .hpx-main { position: relative; z-index: 1; }
        .hpx-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 28px;
            background: rgba(255,255,255,.82);
            border-bottom: 1px solid #e2e8f0;
            backdrop-filter: blur(16px);
        }
        .hpx-logo {
            display: inline-flex;
            align-items: center;
        }
        .hpx-logo img {
            height: 56px;
            width: auto;
            display: block;
        }
        .hpx-top-right { display: flex; align-items: center; gap: 10px; }
        .hpx-date {
            color: var(--hpx-muted);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .hpx-chip {
            display: inline-flex;
            align-items: center;
            height: 36px;
            padding: 0 14px;
            border-radius: 999px;
            background: var(--hpx-indigo);
            border: 0;
            color: #fff;
            text-decoration: none !important;
            font-size: 13px;
            font-weight: 700;
        }
        .hpx-chip:hover { background: #4338ca; color: #fff; }
        .hpx-ghost {
            display: inline-flex;
            align-items: center;
            height: 36px;
            padding: 0 14px;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #0f172a;
            text-decoration: none !important;
            font-size: 13px;
            font-weight: 700;
        }
        .hpx-ghost:hover { border-color: #c7d2fe; color: #4f46e5; }
        .hpx-main {
            width: min(1080px, calc(100% - 40px));
            margin: 40px auto 0;
        }
        .hpx-stage {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 28px;
            padding: 36px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.06);
        }
        .hpx-hero {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 32px;
            align-items: center;
            margin-bottom: 28px;
        }
        .hpx-pill {
            display: inline-flex;
            margin-bottom: 14px;
            padding: 6px 12px;
            border-radius: 999px;
            background: #eef2ff;
            color: #4f46e5;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
        }
        .hpx-preview {
            position: relative;
            height: 180px;
        }
        .hpx-preview-card {
            position: absolute;
            inset: 10px 10px 0 30px;
            border-radius: 20px;
            background: linear-gradient(180deg, #eef2ff, #fff);
            border: 1px solid #c7d2fe;
            box-shadow: 0 18px 40px rgba(79, 70, 229, 0.12);
            padding: 18px;
        }
        .hpx-preview-card.back {
            inset: 0 0 18px 8px;
            background: #4f46e5;
            border: 0;
            transform: rotate(-6deg);
            z-index: 0;
        }
        .hpx-preview-card:not(.back) { z-index: 1; }
        .hpx-preview-dots { margin-bottom: 18px; }
        .hpx-preview-dots span {
            display: inline-block;
            width: 8px;
            height: 8px;
            margin-right: 6px;
            border-radius: 50%;
            background: #c7d2fe;
        }
        .hpx-preview-kicker {
            margin: 0 0 8px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #6366f1;
        }
        .hpx-preview-type {
            margin: 0;
            min-height: 64px;
            font-size: 22px;
            line-height: 1.3;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #312e81;
        }
        .hpx-caret {
            display: inline-block;
            width: 2px;
            height: 1em;
            margin-left: 2px;
            background: #4f46e5;
            vertical-align: -2px;
            animation: hpx-blink .8s steps(1) infinite;
        }
        @keyframes hpx-blink {
            50% { opacity: 0; }
        }
        .hpx-alert {
            margin-bottom: 20px;
            padding: 12px 14px;
            border-radius: 16px;
            background: #f0fdf4;
            color: #047857;
            font-weight: 700;
            font-size: 14px;
        }
        .hpx-eyebrow {
            margin: 0 0 10px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--hpx-indigo);
        }
        .hpx-hello {
            margin: 0;
            font-size: clamp(29px, 6vw, 32px);
            line-height: 0.94;
            letter-spacing: -0.06em;
            font-weight: 800;
        }
        .hpx-sub {
            margin: 14px 0 0;
            color: var(--hpx-muted);
            font-weight: 500;
            max-width: 440px;
        }
        .hpx-bento {
            display: grid;
            grid-template-columns: 1.35fr 1fr;
            gap: 16px;
        }
        .hpx-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 210px;
            padding: 26px;
            border-radius: 22px;
            text-decoration: none !important;
            color: #0f172a;
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .hpx-card:hover {
            transform: translateY(-3px);
            border-color: #c7d2fe;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.08);
            color: #0f172a;
        }
        .hpx-card-main {
            background: linear-gradient(145deg, #6366f1 0%, #4f46e5 48%, #4338ca 100%);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 16px 40px rgba(79, 70, 229, 0.28);
        }
        .hpx-card-main:hover {
            color: #fff;
            box-shadow: 0 20px 46px rgba(79, 70, 229, 0.34);
        }
        .hpx-go {
            position: absolute;
            top: 24px;
            right: 24px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            color: #4f46e5;
        }
        .hpx-card-main .hpx-go {
            background: rgba(255,255,255,.16);
            color: #fff;
        }
        .hpx-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eef2ff;
            color: #4f46e5;
            font-size: 20px;
            margin-bottom: auto;
        }
        .hpx-card-main .hpx-icon {
            background: rgba(255,255,255,.16);
            color: #fff;
        }
        .hpx-card-label {
            margin-top: 28px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            opacity: .55;
        }
        .hpx-card strong {
            display: block;
            margin-top: 6px;
            font-size: 28px;
            letter-spacing: -0.04em;
            font-weight: 800;
        }
        .hpx-card em {
            display: block;
            margin-top: 6px;
            font-style: normal;
            font-size: 15px;
            opacity: .72;
        }
        .hpx-foot {
            margin: 22px 4px 36px;
            color: #94a3b8;
            font-size: 13px;
        }

        html[data-pc-theme="dark"] body,
        html.dark body {
            background: #0f172a !important;
            color: #f8fafc !important;
        }
        html[data-pc-theme="dark"] .hpx,
        html.dark .hpx {
            color: #f8fafc;
        }
        html[data-pc-theme="dark"] .hpx-mesh,
        html.dark .hpx-mesh {
            background:
                radial-gradient(42% 36% at 8% 0%, rgba(79, 70, 229, 0.28), transparent 58%),
                radial-gradient(38% 32% at 96% 8%, rgba(59, 130, 246, 0.18), transparent 56%),
                #0f172a;
        }
        html[data-pc-theme="dark"] .hpx-top,
        html.dark .hpx-top {
            background: rgba(15, 23, 42, .88);
            border-bottom-color: #334155;
        }
        html[data-pc-theme="dark"] .hpx-logo,
        html.dark .hpx-logo {
            background: #fff;
            border-radius: 12px;
            padding: 4px 8px;
        }
        html[data-pc-theme="dark"] .hpx-date,
        html.dark .hpx-date,
        html[data-pc-theme="dark"] .hpx-sub,
        html.dark .hpx-sub,
        html[data-pc-theme="dark"] .hpx-foot,
        html.dark .hpx-foot {
            color: #94a3b8;
        }
        html[data-pc-theme="dark"] .hpx-ghost,
        html.dark .hpx-ghost {
            background: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }
        html[data-pc-theme="dark"] .hpx-ghost:hover,
        html.dark .hpx-ghost:hover {
            border-color: #6366f1;
            color: #c7d2fe;
        }
        html[data-pc-theme="dark"] .hpx-stage,
        html.dark .hpx-stage,
        html[data-pc-theme="dark"] .hpx-card,
        html.dark .hpx-card {
            background: #1e293b;
            border-color: #334155;
            color: #f8fafc;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.25);
        }
        html[data-pc-theme="dark"] .hpx-card:hover,
        html.dark .hpx-card:hover {
            border-color: #6366f1;
            color: #f8fafc;
        }
        html[data-pc-theme="dark"] .hpx-card-main,
        html.dark .hpx-card-main,
        html[data-pc-theme="dark"] .hpx-card-main:hover,
        html.dark .hpx-card-main:hover {
            background: linear-gradient(145deg, #6366f1 0%, #4f46e5 48%, #4338ca 100%);
            border-color: transparent;
            color: #fff;
        }
        html[data-pc-theme="dark"] .hpx-icon,
        html.dark .hpx-icon,
        html[data-pc-theme="dark"] .hpx-go,
        html.dark .hpx-go {
            background: #312e81;
            color: #c7d2fe;
        }
        html[data-pc-theme="dark"] .hpx-preview-card,
        html.dark .hpx-preview-card {
            background: linear-gradient(180deg, #312e81, #1e293b);
            border-color: #4338ca;
        }
        html[data-pc-theme="dark"] .hpx-preview-kicker,
        html.dark .hpx-preview-kicker {
            color: #a5b4fc;
        }
        html[data-pc-theme="dark"] .hpx-preview-type,
        html.dark .hpx-preview-type {
            color: #eef2ff;
        }
        html[data-pc-theme="dark"] .hpx-hello,
        html.dark .hpx-hello {
            color: #f8fafc;
        }

        .ticket-modal { position: fixed; inset: 0; z-index: 2000; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .ticket-modal[hidden] { display: none !important; }
        .ticket-modal-backdrop { position: absolute; inset: 0; background: rgba(15, 23, 42, .55); backdrop-filter: blur(10px); }
        .ticket-modal-dialog { position: relative; z-index: 1; width: min(1100px, 100%); max-height: calc(100vh - 40px); }
        .ticket-modal .modal-content { display: flex; flex-direction: column; max-height: calc(100vh - 40px); background: #fff; border-radius: 24px; overflow: hidden; }
        .ticket-modal-body { overflow-y: auto; overscroll-behavior: contain; }
        body.ticket-modal-open { overflow: hidden; }
        @media (max-width: 760px) {
            .hpx-top { padding: 16px; }
            .hpx-logo img { height: 48px; }
            .hpx-date { display: none; }
            .hpx-main { margin-top: 4vh; }
            .hpx-ghost { display: none; }
            .hpx-stage { padding: 22px 18px; border-radius: 22px; }
            .hpx-hero { grid-template-columns: 1fr; gap: 12px; }
            .hpx-preview { display: none; }
            .hpx-hello { font-size: 18px; }
            .hpx-sub { font-size: 10px; }
            .hpx-bento { grid-template-columns: 1fr; }
            .hpx-card { min-height: 180px; border-radius: 24px; }
            .ticket-modal { padding: 8px; }
            .ticket-modal-dialog, .ticket-modal .modal-content { max-height: calc(100vh - 16px); }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>
    <script src="<?php echo e(asset('js/plugins/flatpickr.min.js')); ?>"></script>
    <script>
        flatpickr("#date_issued", {
            dateFormat: "m/d/Y",
            allowInput: true
        });

        (function () {
            const el = document.getElementById('hpxTypeText');
            if (!el) return;
            const lines = ['Client login', 'Submit a ticket', 'Track your cases'];
            let line = 0;
            let i = 0;
            let deleting = false;

            const tick = () => {
                const text = lines[line];
                el.textContent = text.slice(0, i);
                if (!deleting && i < text.length) {
                    i += 1;
                    setTimeout(tick, 70);
                    return;
                }
                if (!deleting && i === text.length) {
                    deleting = true;
                    setTimeout(tick, 1400);
                    return;
                }
                if (deleting && i > 0) {
                    i -= 1;
                    setTimeout(tick, 40);
                    return;
                }
                deleting = false;
                line = (line + 1) % lines.length;
                setTimeout(tick, 280);
            };
            tick();
        })();

        const ticketModal = document.getElementById('ticketSubmissionModal');
        const openTicketModal = () => {
            if (!ticketModal) return;
            ticketModal.hidden = false;
            document.body.classList.add('ticket-modal-open');
            ticketModal.querySelector('input, select, textarea, button')?.focus();
        };
        const closeTicketModal = () => {
            if (!ticketModal) return;
            ticketModal.hidden = true;
            document.body.classList.remove('ticket-modal-open');
        };

        document.querySelectorAll('[data-ticket-modal-open]').forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                openTicketModal();
            });
        });
        ticketModal?.querySelectorAll('[data-ticket-modal-close]').forEach((button) => {
            button.addEventListener('click', closeTicketModal);
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && ticketModal && !ticketModal.hidden) closeTicketModal();
        });

        <?php if($errors->any()): ?>
            openTicketModal();
        <?php endif; ?>

        <?php if(!empty($recaptchaSiteKey)): ?>
        const recaptchaSiteKey = <?php echo json_encode($recaptchaSiteKey, 15, 512) ?>;
        const ticketForm = document.getElementById('ticketSubmitForm');
        if (ticketForm) {
            ticketForm.addEventListener('submit', function (event) {
                if (ticketForm.dataset.recaptchaReady === '1') return;
                event.preventDefault();
                grecaptcha.ready(function () {
                    grecaptcha.execute(recaptchaSiteKey, { action: 'submit_ticket' }).then(function (token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        ticketForm.dataset.recaptchaReady = '1';
                        ticketForm.submit();
                    });
                });
            });
        }
        <?php endif; ?>
    </script>
    <?php if(!empty($recaptchaSiteKey)): ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo e($recaptchaSiteKey); ?>"></script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.partials.body', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views/homepage.blade.php ENDPATH**/ ?>