<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 m-0">Edit Company</h1>
                <div class="text-xs text-slate-500 mt-1">
                    <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.companies.index')); ?>" class="text-slate-500 hover:text-indigo-600">Companies</a>
                    <span class="mx-1.5 text-slate-300">/</span>
                    <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.companies.show', $company->id)); ?>" class="text-slate-500 hover:text-indigo-600"><?php echo e($company->name); ?></a>
                    <span class="mx-1.5 text-slate-300">/</span>
                    <span class="font-medium text-slate-700">Edit</span>
                </div>
            </div>
            <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.companies.show', $company->id)); ?>" class="btn btn-outline-secondary btn-sm">
                View Company Overview
            </a>
        </div>
        <form action="<?php echo e(route(auth()->user()->portalRoutePrefix().'.companies.update', $company->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="card">
                <div class="card-body !py-0">
                    <ul class="flex flex-wrap w-full font-medium text-center nav-tabs">
                        <li class="group active">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="company"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-building ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Company Information
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="companyContactsTab"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-phone-calling ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Company Contacts
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="companyManagers"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-users ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Company Managers
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="companyDriversTab"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-steering-wheel ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Drivers (<?php echo e($companyDrivers->count()); ?>)
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="companyTicketsTab"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-ticket ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Tickets (<?php echo e($companyTickets->count()); ?>)
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="companyHierarchyTab"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-sitemap ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Company Hierarchy
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div class="block tab-pane" id="company">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Company Information</h5>
                                    <span class="text-muted text-sm">
                                            <?php echo e(__("company's information and citation tracker details.")); ?>

                                        </span>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="name">Company Name</label>
                                                <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name', $company->name)); ?>" />
                                                <?php if($errors->has('name')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('name')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="ct_email">Email</label>
                                                <input type="email" name="ct_email" id="ct_email" class="form-control" value="<?php echo e(old('ct_email', $company->ct_email)); ?>" />
                                                <?php if($errors->has('ct_email')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('ct_email')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="ct_fname">Firstname</label>
                                                <input type="text" name="ct_fname" id="ct_fname" class="form-control" value="<?php echo e(old('ct_fname', $company->ct_fname)); ?>" />
                                                <?php if($errors->has('ct_fname')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('ct_fname')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="ct_lname">Lastname</label>
                                                <input type="text" name="ct_lname" id="ct_lname" class="form-control" value="<?php echo e(old('ct_lname', $company->ct_lname)); ?>" />
                                                <?php if($errors->has('ct_lname')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('ct_lname')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="dot">DOT Number</label>
                                                <input type="text" name="dot" id="dot" class="form-control" value="<?php echo e(old('dot', $company->dot)); ?>" />
                                                <?php if($errors->has('dot')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('dot')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="sf_id">Salesforce ID ( Optional )</label>
                                                <input type="text" name="sf_id" id="sf_id" class="form-control" value="<?php echo e(old('sf_id', $company->sf_id)); ?>" />
                                                <?php if($errors->has('sf_id')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('sf_id')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="parent_company_id">Parent Company</label>
                                                <select name="parent_company_id" id="parent_company_id" class="form-control">
                                                    <option value="">Top-level company</option>
                                                    <?php $__currentLoopData = $parentCompanyOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parentCompany): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($parentCompany->id); ?>" <?php echo e((string) old('parent_company_id', $company->parent_company_id) === (string) $parentCompany->id ? 'selected' : ''); ?>>
                                                            <?php echo e($parentCompany->name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <?php if($errors->has('parent_company_id')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('parent_company_id')); ?></strong>
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
                <div class="hidden tab-pane" id="companyHierarchyTab">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Company Hierarchy</h5>
                                    <span class="text-muted text-sm">
                                        Parent Company → Trucking Company → Drivers
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12">
                                            <div class="rounded border p-4 mb-2 bg-slate-50 dark:bg-transparent">
                                                <p class="mb-1 text-xs uppercase tracking-wide text-muted">Hierarchy Path</p>
                                                <p class="mb-0 text-sm font-medium">
                                                    <?php if($company->parentCompany): ?>
                                                        <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.companies.show', $company->parentCompany->id)); ?>" class="text-primary">
                                                            <?php echo e($company->parentCompany->name); ?>

                                                        </a>
                                                        <span class="mx-1 text-muted">→</span>
                                                    <?php endif; ?>
                                                    <span><?php echo e($company->name); ?></span>
                                                    <span class="mx-1 text-muted">→</span>
                                                    <span>Drivers (<?php echo e($companyDrivers->count()); ?>)</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-4">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-1 text-sm text-muted">Parent Company</p>
                                                <?php if($company->parentCompany): ?>
                                                    <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.companies.show', $company->parentCompany->id)); ?>" class="mb-0 font-semibold text-primary">
                                                        <?php echo e($company->parentCompany->name); ?>

                                                    </a>
                                                <?php else: ?>
                                                    <p class="mb-0 font-semibold">Top-level company</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-4">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-1 text-sm text-muted">Child Companies</p>
                                                <p class="mb-0 font-semibold"><?php echo e($company->childCompanies->count()); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-4">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-1 text-sm text-muted">Drivers On This Company</p>
                                                <p class="mb-0 font-semibold"><?php echo e($companyDrivers->count()); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-3 font-semibold">Child Trucking Companies</p>
                                                <?php $__empty_1 = true; $__currentLoopData = $company->childCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCompany): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <div class="flex items-center justify-between border-b py-2 last:border-b-0 gap-3">
                                                        <div>
                                                            <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.companies.show', $childCompany->id)); ?>" class="font-medium text-primary">
                                                                <?php echo e($childCompany->name); ?>

                                                            </a>
                                                            <p class="mb-0 text-xs text-muted">DOT: <?php echo e($childCompany->dot ?: 'N/A'); ?></p>
                                                        </div>
                                                        <span class="text-sm text-muted whitespace-nowrap">
                                                            Drivers: <?php echo e((int) ($childCompanyDriverCounts[$childCompany->id] ?? 0)); ?>

                                                        </span>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <p class="mb-0 text-sm text-muted">No child companies linked yet.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-3 font-semibold">Rollup Snapshot</p>
                                                <div class="flex items-center justify-between border-b py-2">
                                                    <span>Drivers (incl. children)</span>
                                                    <span><?php echo e($company->driversCountIncludingChildren()); ?></span>
                                                </div>
                                                <div class="flex items-center justify-between border-b py-2">
                                                    <span>Open Tickets</span>
                                                    <span><?php echo e($company->openTicketsCountIncludingChildren()); ?></span>
                                                </div>
                                                <div class="flex items-center justify-between py-2">
                                                    <span>Closed Tickets</span>
                                                    <span><?php echo e($company->closedTicketsCountIncludingChildren()); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden tab-pane" id="companyContactsTab">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Company Contacts</h5>
                                    <span class="text-muted text-sm">
                                        <?php echo e(__("All of these contacts will get notified.")); ?>

                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12">
                                            <div class="table-responsive" id="companyContactsList">
                                                <table class="table table-hover mb-0" id="companyContactsTable">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><span class="text-danger-500">*</span>Name</th>
                                                        <th><span class="text-danger-500">*</span>Email</th>
                                                        <th>Phone</th>
                                                        <th>Cell</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if(old('companyContactName')): ?>
                                                        <?php $__currentLoopData = old('companyContactName'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $companyContact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($index + 1); ?></td>
                                                                <td><input type="text" name="companyContactName[<?php echo e($index); ?>]" class="form-control" placeholder="Name" value="<?php echo e(old("companyContactName")[$index]); ?>" required /></td>
                                                                <td><input type="email" name="companyContactEmail[<?php echo e($index); ?>]" class="form-control" placeholder="Email" value="<?php echo e(old("companyContactEmail")[$index]); ?>"  required /></td>
                                                                <td><input type="text" name="companyContactPhone[<?php echo e($index); ?>]" class="form-control" placeholder="Phone" value="<?php echo e(old("companyContactPhone")[$index]); ?>" /></td>
                                                                <td><input type="text" name="companyContactCell[<?php echo e($index); ?>]" class="form-control" placeholder="Cell" value="<?php echo e(old("companyContactCell")[$index]); ?>" /></td>
                                                                <td class="text-center">
                                                                    <a href="#" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-danger btn-pc-default js-remove-contact-row">
                                                                        <i class="ti ti-trash text-xl leading-none"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <?php $__currentLoopData = $company->contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($index + 1); ?></td>
                                                                <td><input type="text" name="companyContactName[<?php echo e($index); ?>]" class="form-control" placeholder="Name" value="<?php echo e($contact->name); ?>" required /></td>
                                                                <td><input type="email" name="companyContactEmail[<?php echo e($index); ?>]" class="form-control" placeholder="Email" value="<?php echo e($contact->email); ?>"  required /></td>
                                                                <td><input type="text" name="companyContactPhone[<?php echo e($index); ?>]" class="form-control" placeholder="Phone" value="<?php echo e($contact->phone); ?>" /></td>
                                                                <td><input type="text" name="companyContactCell[<?php echo e($index); ?>]" class="form-control" placeholder="Cell" value="<?php echo e($contact->cell); ?>" /></td>
                                                                <td class="text-center">
                                                                    <a href="#" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-danger btn-pc-default js-remove-contact-row">
                                                                        <i class="ti ti-trash text-xl leading-none"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-left">
                                                <hr class="my-4 mt-1 border-t-theme-border dark:border-t-themedark-border opacity-50" />
                                                <button class="btn btn-light-primary flex items-center gap-2" id="addItem">
                                                    <i class="ti ti-plus"></i> Add new contact
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden tab-pane" id="companyManagers">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="text-primary text-[28px] font-bold">Company Managers</h5>
                                    <span class="text-muted text-sm">
                                        <?php echo e(__("Who Manages this company with/without write access.")); ?>

                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12">
                                            <div class="table-responsive" id="companyManagersList">
                                                <table class="table table-hover mb-0" id="companyManagersTable">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><span class="text-danger-500">*</span>Name</th>
                                                        <th><span class="text-danger-500">*</span>Email</th>
                                                        <th><span class="text-danger-500">*</span>Write Access</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $companyManagers = $company->managers
                                                            ->filter(fn ($manager) => filled(optional($manager->user)->email))
                                                            ->values();
                                                    ?>
                                                    <?php $__empty_1 = true; $__currentLoopData = $companyManagers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <?php $managerUser = $manager->user; ?>
                                                        <tr>
                                                            <td><?php echo e($index + 1); ?></td>
                                                            <td>
                                                                <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.managers.edit', $manager->id)); ?>" class="font-medium text-primary">
                                                                    <?php echo e($managerUser->name ?: 'Unnamed manager'); ?>

                                                                </a>
                                                            </td>
                                                            <td><?php echo e($managerUser->email); ?></td>
                                                            <td><?php echo $manager->pivot->is_write_access ? '<i class="text-success text-lg ti ti-check"></i>' : '<i class="text-danger text-lg ti ti-x"></i>'; ?></td>
                                                            <td class="text-center">
                                                                <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.managers.edit', $manager->id)); ?>" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-primary btn-pc-default" title="Edit manager">
                                                                    <i class="ti ti-pencil text-xl leading-none"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted py-4">
                                                                No company managers linked yet.
                                                                <?php if($companyDrivers->isNotEmpty()): ?>
                                                                    <span class="d-block mt-1 text-xs">
                                                                        If Salesforce Account emails match a Driver email, a separate company manager login is not created automatically. Add a manager from the Managers page, or use a different Account contact email in Salesforce.
                                                                    </span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden tab-pane" id="companyDriversTab">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <h5 class="text-primary text-[28px] font-bold mb-0">Drivers (<?php echo e($companyDrivers->count()); ?>)</h5>
                                        <span class="text-muted text-sm">
                                            Drivers associated with <?php echo e($company->name); ?>. Click a driver name to open the profile.
                                        </span>
                                    </div>
                                    <div class="w-full sm:w-72">
                                        <input type="search" id="companyDriversSearch" class="form-control" placeholder="Search drivers by name, email, city, state..." onkeydown="if(event.key==='Enter'){event.preventDefault();}" />
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="companyDriversTable">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Driver Name</th>
                                                <th>Email</th>
                                                <th>State</th>
                                                <th>City</th>
                                                <th>Open Tickets</th>
                                                <th>Closed Tickets</th>
                                                <th>Points Saved</th>
                                                <th>Last Access</th>
                                                <th>Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $companyDrivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php
                                                    $driverUser = $driver->user;
                                                    $emailKey = strtolower((string) ($driverUser?->email ?? ''));
                                                    $stats = $driverTicketStats->get($emailKey);
                                                    $openCount = (int) ($stats->open_count ?? 0);
                                                    $closedCount = (int) ($stats->closed_count ?? 0);
                                                    $pointsSaved = (float) ($stats->points_saved ?? 0);
                                                    $searchBlob = strtolower(trim(implode(' ', array_filter([
                                                        $driverUser?->name,
                                                        $driverUser?->email,
                                                        $driverUser?->city,
                                                        $driverUser?->state,
                                                    ]))));
                                                ?>
                                                <tr data-driver-search="<?php echo e($searchBlob); ?>">
                                                    <td><?php echo e($index + 1); ?></td>
                                                    <td>
                                                        <?php if($driverUser): ?>
                                                            <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.drivers.edit', $driver->id)); ?>" class="font-medium text-primary">
                                                                <?php echo e($driverUser->name ?: 'Unnamed driver'); ?>

                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">Driver user missing</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($driverUser?->email ?: '—'); ?></td>
                                                    <td><?php echo e($driverUser?->state ?: '—'); ?></td>
                                                    <td><?php echo e($driverUser?->city ?: '—'); ?></td>
                                                    <td><?php echo e($openCount); ?></td>
                                                    <td><?php echo e($closedCount); ?></td>
                                                    <td><?php echo e(number_format($pointsSaved, 1)); ?></td>
                                                    <td>
                                                        <?php if($driverUser?->last_login_at): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($driverUser->last_login_at)->format('M j, Y g:i A')); ?>

                                                        <?php else: ?>
                                                            —
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($driverUser?->email): ?>
                                                            <span class="badge bg-success-50 text-success">Portal Access</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning-50 text-warning">No Login</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.drivers.edit', $driver->id)); ?>" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-primary btn-pc-default" title="Edit driver">
                                                            <i class="ti ti-pencil text-xl leading-none"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="11" class="text-center text-muted py-4">No drivers are linked to this company yet.</td>
                                                </tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p id="companyDriversEmptyFilter" class="mb-0 mt-3 text-sm text-muted hidden">No drivers match your search.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden tab-pane" id="companyTicketsTab">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <div class="card">
                                <div class="card-header flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <h5 class="text-primary text-[28px] font-bold mb-0">Tickets (<?php echo e($companyTickets->count()); ?>)</h5>
                                        <span class="text-muted text-sm">
                                            All tickets for <?php echo e($company->name); ?>. Click a ticket or driver to open the record.
                                        </span>
                                    </div>
                                    <div class="w-full sm:w-80">
                                        <input type="search" id="companyTicketsSearch" class="form-control" placeholder="Search tickets by ID, driver, state, status..." onkeydown="if(event.key==='Enter'){event.preventDefault();}" />
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="companyTicketsTable">
                                            <thead>
                                            <tr>
                                                <th>Ticket #</th>
                                                <th>Driver Name</th>
                                                <th>Date Received</th>
                                                <th>State</th>
                                                <th>Status / Indicator</th>
                                                <th>Original Points</th>
                                                <th>Final Points</th>
                                                <th>Points Saved</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $companyTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php
                                                    $emailKey = strtolower((string) ($ticket->user_email ?? ''));
                                                    $linkedDriver = $driversByEmail->get($emailKey);
                                                    $driverName = $ticket->name ?: ($linkedDriver?->user?->name ?: '—');
                                                    $statusLabel = match ((int) ($ticket->status ?? -1)) {
                                                        \App\Models\Ticket::TICKET_STATUS_CLOSED => 'Closed',
                                                        \App\Models\Ticket::TICKET_STATUS_ARCHIVED => 'Archived',
                                                        \App\Models\Ticket::TICKET_STATUS_OPEN => 'Open',
                                                        default => 'Open',
                                                    };
                                                    $indicator = $ticket->indicator ?: '—';
                                                    $searchBlob = strtolower(trim(implode(' ', array_filter([
                                                        (string) $ticket->id,
                                                        (string) ($ticket->ticket_number ?? ''),
                                                        (string) ($ticket->citation_no ?? ''),
                                                        $driverName,
                                                        $ticket->user_email,
                                                        $ticket->state,
                                                        $statusLabel,
                                                        $indicator,
                                                    ]))));
                                                ?>
                                                <tr data-ticket-search="<?php echo e($searchBlob); ?>">
                                                    <td>
                                                        <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.tickets.show', $ticket->id)); ?>" class="font-medium text-primary">
                                                            #<?php echo e($ticket->id); ?>

                                                        </a>
                                                        <?php if($ticket->ticket_number): ?>
                                                            <div class="text-xs text-muted"><?php echo e($ticket->ticket_number); ?></div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($linkedDriver): ?>
                                                            <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.drivers.edit', $linkedDriver->id)); ?>" class="font-medium text-primary">
                                                                <?php echo e($driverName); ?>

                                                            </a>
                                                        <?php else: ?>
                                                            <?php echo e($driverName); ?>

                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($ticket->date_issued): ?>
                                                            <?php echo e(\Carbon\Carbon::parse($ticket->date_issued)->format('M j, Y')); ?>

                                                        <?php else: ?>
                                                            —
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($ticket->state ?: '—'); ?></td>
                                                    <td>
                                                        <div><?php echo e($statusLabel); ?></div>
                                                        <div class="text-xs text-muted"><?php echo e($indicator); ?></div>
                                                    </td>
                                                    <td><?php echo e(number_format((float) $ticket->original_points_value, 1)); ?></td>
                                                    <td><?php echo e(number_format((float) $ticket->final_points_value, 1)); ?></td>
                                                    <td><?php echo e(number_format((float) $ticket->points_saved, 1)); ?></td>
                                                    <td class="text-center">
                                                        <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.tickets.show', $ticket->id)); ?>" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-primary btn-pc-default" title="View ticket">
                                                            <i class="ti ti-eye text-xl leading-none"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted py-4">No tickets are linked to this company yet.</td>
                                                </tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p id="companyTicketsEmptyFilter" class="mb-0 mt-3 text-sm text-muted hidden">No tickets match your search.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 text-right">
                    <button type="reset" class="btn btn-outline-secondary mx-1">Cancel</button>
                    <button type="submit" class="btn btn-primary mx-1">Update Company</button>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('post-scripts'); ?>
    <script src="<?php echo e(asset('js/plugins/flatpickr.min.js')); ?>"></script>
    <script>
        // Function to update row index numbers
        function updateRowIndexes() {
            const rows = document.querySelectorAll('#companyContactsTable tbody tr');
            rows.forEach((row, index) => {
                const firstCell = row.querySelector('td');
                if (firstCell) {
                    firstCell.textContent = index + 1;
                }
            });
        }


        updateRowIndexes()

        document.addEventListener('click', function (e) {
            let removeRepeatedItemBtn = e.target.closest('.js-remove-contact-row');
            if (removeRepeatedItemBtn) {
                e.preventDefault();
                let parentRow = removeRepeatedItemBtn.closest('tr'); // Find the closest <tr> element
                if (parentRow) {
                    parentRow.remove(); // Remove the parent <tr> element
                    updateRowIndexes()
                }
            }

            let addItemBtn = e.target.closest('#addItem');
            if (addItemBtn) {
                e.preventDefault();
                // Get the table body where the rows should be added
                let tableBody = document.querySelector('#companyContactsTable tbody');
                if (tableBody) {
                    // Get the index for the new row based on current row count
                    let newIndex = tableBody.querySelectorAll('tr').length;

                    // Create a new row with the correct structure and incremented names
                    let newRow = document.createElement('tr');
                    newRow.innerHTML = `
                <td>${newIndex + 1}</td>
                <td><input type="text" name="companyContactName[${newIndex}]" class="form-control" placeholder="Name" /></td>
                <td><input type="email" name="companyContactEmail[${newIndex}]" class="form-control" placeholder="Email" /></td>
                <td><input type="text" name="companyContactPhone[${newIndex}]" class="form-control" placeholder="Phone" /></td>
                <td><input type="text" name="companyContactCell[${newIndex}]" class="form-control" placeholder="Cell" /></td>
                <td class="text-center">
                    <a href="#" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-danger btn-pc-default js-remove-contact-row">
                        <i class="ti ti-trash text-xl leading-none"></i>
                    </a>
                </td>
            `;
                    // Append the new row to the table body
                    tableBody.appendChild(newRow);
                    updateRowIndexes()
                }
            }
        });

        (function () {
            const searchInput = document.getElementById('companyDriversSearch');
            const table = document.getElementById('companyDriversTable');
            const emptyMessage = document.getElementById('companyDriversEmptyFilter');
            if (!searchInput || !table) return;

            searchInput.addEventListener('input', function () {
                const query = (searchInput.value || '').trim().toLowerCase();
                let visible = 0;

                table.querySelectorAll('tbody tr[data-driver-search]').forEach(function (row) {
                    const blob = row.getAttribute('data-driver-search') || '';
                    const match = !query || blob.indexOf(query) !== -1;
                    row.style.display = match ? '' : 'none';
                    if (match) visible += 1;
                });

                if (emptyMessage) {
                    emptyMessage.classList.toggle('hidden', visible !== 0 || table.querySelectorAll('tbody tr[data-driver-search]').length === 0);
                }
            });
        })();

        (function () {
            const searchInput = document.getElementById('companyTicketsSearch');
            const table = document.getElementById('companyTicketsTable');
            const emptyMessage = document.getElementById('companyTicketsEmptyFilter');
            if (!searchInput || !table) return;

            searchInput.addEventListener('input', function () {
                const query = (searchInput.value || '').trim().toLowerCase();
                let visible = 0;

                table.querySelectorAll('tbody tr[data-ticket-search]').forEach(function (row) {
                    const blob = row.getAttribute('data-ticket-search') || '';
                    const match = !query || blob.indexOf(query) !== -1;
                    row.style.display = match ? '' : 'none';
                    if (match) visible += 1;
                });

                if (emptyMessage) {
                    emptyMessage.classList.toggle('hidden', visible !== 0 || table.querySelectorAll('tbody tr[data-ticket-search]').length === 0);
                }
            });
        })();
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.2.1/ol.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/flatpickr.min.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\companies\edit.blade.php ENDPATH**/ ?>