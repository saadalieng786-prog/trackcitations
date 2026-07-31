<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
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
                                        <?php echo e(__("Review this company's parent/child structure and rollup impact.")); ?>

                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 lg:col-span-4">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-1 text-sm text-muted">Parent Company</p>
                                                <p class="mb-0 font-semibold"><?php echo e(optional($company->parentCompany)->name ?: 'Top-level company'); ?></p>
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
                                                <p class="mb-1 text-sm text-muted">Lifetime Points Saved</p>
                                                <p class="mb-0 font-semibold"><?php echo e(number_format($company->lifetimePointsSaved(), 2)); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-3 font-semibold">Child Companies</p>
                                                <?php $__empty_1 = true; $__currentLoopData = $company->childCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCompany): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <div class="flex items-center justify-between border-b py-2 last:border-b-0">
                                                        <span><?php echo e($childCompany->name); ?></span>
                                                        <span class="text-sm text-muted">DOT: <?php echo e($childCompany->dot ?: 'N/A'); ?></span>
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
                                                    <span>Drivers</span>
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
                                            <div class="table-responsive" id="companyContacts">
                                                <table class="table table-hover mb-0">
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
                                                                    <a href="#" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-danger btn-pc-default" id="removeItem">
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
                                                                    <a href="#" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-danger btn-pc-default" id="removeItem">
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
                                            <div class="table-responsive" id="companyContacts">
                                                <table class="table table-hover mb-0">
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
                                                    <?php $__empty_1 = true; $__currentLoopData = $company->managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <?php ($managerUser = $manager->user); ?>
                                                        <tr>
                                                            <td><?php echo e($index + 1); ?></td>
                                                            <td><?php echo e($managerUser?->name ?: 'Manager user missing'); ?></td>
                                                            <td><?php echo e($managerUser?->email ?: 'No linked login email'); ?></td>
                                                            <td><?php echo $manager->pivot->is_write_access ? '<i class="text-success text-lg ti ti-check"></i>' : '<i class="text-danger text-lg ti ti-x"></i>'; ?></td>
                                                            <td class="text-center">
                                                                <a href="<?php echo e(route(auth()->user()->portalRoutePrefix().'.managers.edit', $manager->id)); ?>" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-primary btn-pc-default" id="removeItem">
                                                                    <i class="ti ti-pencil text-xl leading-none"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted py-4">No company managers linked yet.</td>
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
            const rows = document.querySelectorAll('#companyContacts table tbody tr');
            rows.forEach((row, index) => {
                // Set the first cell in each row to the correct index (1-based)
                row.querySelector('td').textContent = index + 1;
            });
        }


        updateRowIndexes()

        document.addEventListener('click', function (e) {
            let removeRepeatedItemBtn = e.target.closest('#removeItem');
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
                let tableBody = document.querySelector('#companyContacts table tbody');
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
                    <a href="#" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-danger btn-pc-default" id="removeItem">
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
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.2.1/ol.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/flatpickr.min.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\companies\edit.blade.php ENDPATH**/ ?>