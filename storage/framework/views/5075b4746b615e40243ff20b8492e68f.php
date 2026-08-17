<?php $__env->startSection('body'); ?>
    <?php
        $dashboardUrl = route('dashboard');
        $violationOptions = $violations->take(250);
    ?>

    
    <nav class="hp-nav">
        <a href="<?php echo e(url('/')); ?>" class="hp-nav-brand flex items-center gap-3 no-underline">
            <img src="<?php echo e(asset('images/logo-dark.png')); ?>" class="front-logo h-14 md:h-16 w-auto object-contain py-1" alt="CDL CONSULTANT Logo">
        </a>

        <div class="hp-nav-actions">
            <a href="#" data-ticket-modal-open class="btn btn-outline-secondary btn-sm bg-white hover:bg-slate-50 border-slate-200 text-slate-700 shadow-sm transition-all">
                <i class="ti ti-plus me-1 text-indigo-500"></i> Submit Ticket
            </a>
            <a href="<?php echo e($dashboardUrl); ?>" class="btn btn-primary btn-sm bg-gradient-to-r from-indigo-600 to-violet-600 border-0 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
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
            <div class="hp-hero-actions flex flex-wrap gap-3 sm:gap-4 mt-8">
                <a href="#" data-ticket-modal-open class="btn btn-primary px-8 py-3.5 bg-gradient-to-r from-indigo-500 to-violet-500 border-0 shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all text-base font-semibold rounded-xl">
                    <i class="ti ti-send me-2 text-lg"></i> Submit a Ticket
                </a>
                <a href="<?php echo e($dashboardUrl); ?>" class="btn btn-outline-secondary px-8 py-3.5 bg-white/5 text-white border-white/20 hover:bg-white/10 hover:border-white/40 backdrop-blur-sm transition-all text-base font-semibold rounded-xl">
                    <i class="ti ti-login me-2 text-lg"></i>
                    <?php echo e(auth()->check() ? 'Open Dashboard' : 'Client Login'); ?>

                </a>
            </div>
        </div>
    </div>

    
    <div class="hp-main p-0">

        <?php if(session('success')): ?>
            <div class="max-w-[1140px] mx-auto mt-6 px-4">
                <div class="alert alert-success p-4 rounded-xl text-sm flex gap-3 align-items-center shadow-sm">
                    <i class="ti ti-circle-check text-lg"></i>
                    <?php echo e(session('success')); ?>

                </div>
            </div>
        <?php endif; ?>

        
        <div class="bg-white border-b border-slate-200 relative z-20 shadow-sm">
            <div class="max-w-[1140px] mx-auto px-6 py-10 flex flex-wrap md:flex-nowrap justify-between gap-8 text-center items-center">
                <div class="flex-1">
                    <div class="text-4xl md:text-5xl font-black text-indigo-600 mb-2">99%</div>
                    <div class="text-sm font-bold text-slate-800 uppercase tracking-wide">Success Tracking</div>
                    <div class="text-[13px] text-slate-500 mt-1">Citations processed accurately</div>
                </div>
                <div class="hidden md:block w-px h-16 bg-slate-200"></div>
                <div class="flex-1">
                    <div class="text-4xl md:text-5xl font-black text-indigo-600 mb-2">24/7</div>
                    <div class="text-sm font-bold text-slate-800 uppercase tracking-wide">Secure Portal</div>
                    <div class="text-[13px] text-slate-500 mt-1">Bank-level SSL encryption</div>
                </div>
                <div class="hidden md:block w-px h-16 bg-slate-200"></div>
                <div class="flex-1">
                    <div class="text-4xl md:text-5xl font-black text-indigo-600 mb-2">10k+</div>
                    <div class="text-sm font-bold text-slate-800 uppercase tracking-wide">Tickets Managed</div>
                    <div class="text-[13px] text-slate-500 mt-1">Trusted by top fleets</div>
                </div>
            </div>
        </div>

        
        <div class="py-24 px-6 max-w-[1140px] mx-auto">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-widest mb-4">
                    Core Functionality
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">Everything you need to manage citations</h2>
                <p class="text-slate-500 max-w-[600px] mx-auto text-lg leading-relaxed">A unified system designed specifically for drivers, fleet managers, and attorneys to handle traffic citations seamlessly.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="card p-8 border-0 shadow-md hover:shadow-2xl transition-all duration-300 rounded-2xl group bg-white">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                        <i class="ti ti-steering-wheel"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">For Drivers</h3>
                    <p class="text-slate-500 leading-relaxed mb-0 text-sm">
                        Submit a ticket in seconds directly from your phone. Upload photos of citations instantly and track status updates through our secure portal without any hassle.
                    </p>
                </div>
                
                <div class="card p-8 border-0 shadow-md hover:shadow-2xl transition-all duration-300 rounded-2xl group bg-white">
                    <div class="w-16 h-16 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-violet-600 group-hover:text-white transition-all shadow-sm">
                        <i class="ti ti-briefcase"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">For Fleet Managers</h3>
                    <p class="text-slate-500 leading-relaxed mb-0 text-sm">
                        Maintain a centralized dashboard of all company vehicles. Track citations across your entire fleet, monitor court dates, and stay compliant effortlessly.
                    </p>
                </div>
                
                <div class="card p-8 border-0 shadow-md hover:shadow-2xl transition-all duration-300 rounded-2xl group bg-white">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                        <i class="ti ti-file-certificate"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">For Attorneys</h3>
                    <p class="text-slate-500 leading-relaxed mb-0 text-sm">
                        Direct access to case files, supporting attachments, and historical data. Streamline communications with clients and organize upcoming court appearances.
                    </p>
                </div>
            </div>
        </div>

        
        <div class="py-24 px-6 bg-slate-50 border-y border-slate-200">
            <div class="max-w-[1140px] mx-auto">
                <div class="text-center mb-20">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">How it works</h2>
                    <p class="text-slate-500 max-w-[500px] mx-auto text-lg leading-relaxed">Our streamlined process ensures your citations are handled quickly and professionally.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                    <div class="hidden md:block absolute top-12 left-[15%] right-[15%] h-1 bg-indigo-100 rounded-full"></div>
                    
                    <div class="relative text-center z-10 group">
                        <div class="w-24 h-24 mx-auto bg-white border-4 border-indigo-50 shadow-xl rounded-full flex items-center justify-center text-3xl text-indigo-600 mb-6 font-black transition-transform group-hover:scale-110 group-hover:border-indigo-100">1</div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Submit Ticket</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Fill out our secure intake form with the citation details and upload any supporting documents directly from your device.</p>
                    </div>
                    <div class="relative text-center z-10 group">
                        <div class="w-24 h-24 mx-auto bg-white border-4 border-indigo-50 shadow-xl rounded-full flex items-center justify-center text-3xl text-indigo-600 mb-6 font-black transition-transform group-hover:scale-110 group-hover:border-indigo-100">2</div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Expert Review</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Our team and network of specialized attorneys immediately review the case file and determine the best course of action.</p>
                    </div>
                    <div class="relative text-center z-10 group">
                        <div class="w-24 h-24 mx-auto bg-white border-4 border-indigo-50 shadow-xl rounded-full flex items-center justify-center text-3xl text-indigo-600 mb-6 font-black transition-transform group-hover:scale-110 group-hover:border-indigo-100">3</div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">Resolution</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Track progress through your dashboard as we work relentlessly to minimize points, fines, and keep you on the road safely.</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="py-24 px-6 relative overflow-hidden bg-slate-900">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/80 to-violet-900/80 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-50"></div>
            
            <div class="max-w-[800px] mx-auto text-center relative z-10">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight leading-tight">Ready to handle your citation?</h2>
                <p class="text-lg md:text-xl text-indigo-100 mb-10 max-w-[600px] mx-auto font-medium">
                    Don't let a ticket slow down your fleet or your career. Submit your citation now and let our professionals take it from here.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#" data-ticket-modal-open class="btn btn-primary px-8 py-4 bg-gradient-to-r from-indigo-500 to-violet-500 border-0 shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all text-lg font-bold rounded-xl w-full sm:w-auto">
                        <i class="ti ti-send me-2"></i> Submit Citation Now
                    </a>
                    <a href="<?php echo e($dashboardUrl); ?>" class="btn btn-outline-light px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white border-slate-700 hover:border-slate-600 transition-all text-lg font-bold rounded-xl w-full sm:w-auto">
                        Client Login
                    </a>
                </div>
            </div>
        </div>

        
        <footer class="bg-white py-12 px-6 border-t border-slate-200">
            <div class="max-w-[1140px] mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <img src="<?php echo e(asset('images/logo-dark.png')); ?>" class="h-10 w-auto object-contain opacity-80 hover:opacity-100 transition-opacity" alt="CDL CONSULTANT Logo">
                </div>
                <div class="text-sm text-slate-500 font-medium">
                    &copy; <?php echo e(date('Y')); ?> Track Citations. All rights reserved.
                </div>
                <div class="flex gap-4">
                    <span class="text-xs text-slate-500 font-bold tracking-wider flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-lg border border-slate-100">
                        <i class="ti ti-lock text-emerald-500 text-base"></i> SECURE & ENCRYPTED
                    </span>
                </div>
            </div>
        </footer>

        
        <div class="ticket-modal" id="ticketSubmissionModal" hidden aria-labelledby="ticketSubmissionModalLabel" aria-modal="true" role="dialog">
            <div class="ticket-modal-backdrop" data-ticket-modal-close></div>
            <div class="ticket-modal-dialog">
                <div class="modal-content border-0 overflow-hidden shadow-2xl rounded-2xl">
                    <div class="p-8 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex items-center justify-between border-b border-white/10 relative overflow-hidden">
                        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent)] pointer-events-none"></div>
                        <div class="relative z-10">
                            <h2 class="text-xl font-extrabold text-white m-0 tracking-tight" id="ticketSubmissionModalLabel">New Ticket Submission</h2>
                            <p class="text-sm text-indigo-200/80 m-0 mt-1.5 font-medium">Enter the citation details below to open a new case</p>
                        </div>
                        <div class="relative z-10 flex items-center gap-3">
                            <span class="hidden sm:flex px-3 py-1 rounded-md bg-white/10 text-xs font-semibold text-white items-center gap-1.5 border border-white/20">
                                <i class="ti ti-lock text-xs"></i> Secure Intake
                            </span>
                            <button type="button" class="btn-close btn-close-white opacity-100 hover:opacity-80 transition-opacity" data-ticket-modal-close aria-label="Close"></button>
                        </div>
                    </div>

                    <div class="p-6 md:p-8 bg-slate-50/50">
                        <form action="<?php echo e(route('submit.ticket')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>

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
                                    <h3 class="text-lg font-bold text-slate-800 m-0">Driver Information</h3>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="name">Driver Name <span class="text-red-500">*</span></label>
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
                                        <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="user_email">Driver Email <span class="text-red-500">*</span></label>
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
                                    <div class="md:col-span-2">
                                        <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="company_name">Company Name</label>
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
                                </div>
                            </div>

                            
                            <div class="mb-8 p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                                        <i class="ti ti-file-description text-lg"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800 m-0">Citation Details</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="citation_no">Citation Number <span class="text-red-500">*</span></label>
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
                                        <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="date_issued">Date Received <span class="text-red-500">*</span></label>
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
                                        <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="violation_id">Violation Type <span class="text-red-500">*</span></label>
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
                                        <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="description">Ticket Details</label>
                                        <textarea name="description" class="form-control w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all text-sm shadow-sm hover:border-slate-300 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" rows="3" placeholder="Describe the situation or special instructions..."><?php echo e(old('description')); ?></textarea>
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
                                    <h3 class="text-lg font-bold text-slate-800 m-0">Vehicle & Location</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                    <div>
                                        <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="state">License State</label>
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
                                        <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="city">License City</label>
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
                                        <label class="form-label text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" for="vehicle_lic_no">Vehicle Plate <span class="text-red-500">*</span></label>
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
                                <div class="p-8 border-2 border-dashed border-indigo-100 hover:border-indigo-300 transition-colors rounded-2xl bg-indigo-50/30">
                                    <div class="text-center mb-6">
                                        <i class="ti ti-cloud-upload text-4xl text-indigo-300 mb-3"></i>
                                        <p class="text-sm text-slate-600 font-medium m-0">Upload images or PDF documents of the citation</p>
                                        <p class="text-[11px] text-slate-400 mt-1">Maximum file size: 5MB per file</p>
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

                            
                            <div class="flex flex-col sm:flex-row items-center justify-between pt-2">
                                <p class="text-xs text-slate-500 m-0 font-medium flex items-center gap-2 mb-6 sm:mb-0">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                        <i class="ti ti-shield-check text-sm"></i>
                                    </span>
                                    256-bit SSL Encrypted Submit
                                </p>
                                <button type="submit" class="btn btn-primary px-10 py-4 bg-gradient-to-r from-indigo-600 to-violet-600 border-0 shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all rounded-xl font-bold text-base w-full sm:w-auto">
                                    <i class="ti ti-send me-2"></i> Submit Citation For Review
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/flatpickr.min.css')); ?>" />
    <style>
        .ticket-modal { position: fixed; inset: 0; z-index: 2000; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .ticket-modal[hidden] { display: none !important; }
        .ticket-modal-backdrop { position: absolute; inset: 0; background: rgba(15, 23, 42, .72); backdrop-filter: blur(4px); }
        .ticket-modal-dialog { position: relative; z-index: 1; width: min(1100px, 100%); max-height: calc(100vh - 40px); }
        .ticket-modal .modal-content { display: flex; flex-direction: column; max-height: calc(100vh - 40px); background: #fff; border-radius: 16px; }
        .ticket-modal .modal-content > .p-6 { overflow-y: auto; overscroll-behavior: contain; }
        body.ticket-modal-open { overflow: hidden; }
        @media (max-width: 640px) {
            .ticket-modal { padding: 8px; }
            .ticket-modal-dialog, .ticket-modal .modal-content { max-height: calc(100vh - 16px); }
            .ticket-modal .modal-content > .p-6 { padding: 18px !important; }
            .ticket-modal .p-8 { padding: 20px !important; }
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

        const ticketModal = document.getElementById('ticketSubmissionModal');
        const openTicketModal = () => {
            ticketModal.hidden = false;
            document.body.classList.add('ticket-modal-open');
            ticketModal.querySelector('input, select, textarea, button')?.focus();
        };
        const closeTicketModal = () => {
            ticketModal.hidden = true;
            document.body.classList.remove('ticket-modal-open');
        };

        document.querySelectorAll('[data-ticket-modal-open]').forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                openTicketModal();
            });
        });
        ticketModal.querySelectorAll('[data-ticket-modal-close]').forEach((button) => {
            button.addEventListener('click', closeTicketModal);
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !ticketModal.hidden) closeTicketModal();
        });

        <?php if($errors->any()): ?>
            openTicketModal();
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.partials.body', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views/homepage.blade.php ENDPATH**/ ?>