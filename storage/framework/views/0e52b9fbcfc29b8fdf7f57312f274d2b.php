<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
        <form action="<?php echo e(route(auth()->user()->portalRoutePrefix().'.admins.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="card">
                <div class="card-body !py-0">
                    <ul class="flex flex-wrap w-full font-medium text-center nav-tabs">
                        <li class="group active">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="profile"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-user ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Profile Information
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="notification"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-bell-ringing ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Notification Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div class="block tab-pane" id="profile">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Profile Information</h5>
                                    <span class="text-muted text-sm">
                                            <?php echo e(__("Update your account's profile information and email address.")); ?>

                                        </span>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="name">Name</label>
                                                <input type="text"  name="name" id="name" class="form-control" value="<?php echo e(old('name')); ?>" required autofocus />
                                                <?php if($errors->has('name')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('name')); ?></strong>
                                                        </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="email">Email</label>
                                                <input type="email" name="email" id="email" class="form-control" value="<?php echo e(old('email')); ?>" required />
                                                <?php if($errors->has('email')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('email')); ?></strong>
                                                        </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="password">Password</label>
                                                <input type="password" name="password" id="password" class="form-control"/>
                                                <?php if($errors->has('password')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="role">Access Role</label>
                                                <select name="role" id="role" class="form-control" required>
                                                    <?php $__currentLoopData = $roleOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($value); ?>" <?php echo e(old('role', \App\Models\User::ROLE_STAFF_ADMIN) === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <?php if($errors->has('role')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('role')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="phone">Phone</label>
                                                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo e(old('phone')); ?>" />
                                                <?php if($errors->has('phone')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('phone')); ?></strong>
                                                        </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="dateOfBirth">Date of birth ( optional )</label>
                                                <div class="input-group date">
                                                    <input type="text" name="dob" class="form-control" placeholder="Select date"
                                                           id="dateOfBirth" value="<?php echo e(old('dob')); ?>"/>
                                                    <span class="input-group-text">
                                                          <i class="feather icon-calendar"></i>
                                                        </span>
                                                </div>
                                                <?php if($errors->has('dob')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('dob')); ?></strong>
                                                        </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="address">Address</label>
                                                <input type="text" name="address" id="address" class="form-control" value="<?php echo e(old('address')); ?>" />
                                                <?php if($errors->has('address')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('address')); ?></strong>
                                                        </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="city">City</label>
                                                <input type="text" name="city" id="city" class="form-control" value="<?php echo e(old('city')); ?>" />
                                                <?php if($errors->has('city')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('city')); ?></strong>
                                                        </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="state">State</label>
                                                <select class="form-control" name="state" data-trigger name="state" id="state">
                                                    <option value="AL" <?php echo e(old('state') == 'AL' ? 'selected' : ''); ?>>Alabama</option>
                                                    <option value="AK" <?php echo e(old('state') == 'AK' ? 'selected' : ''); ?>>Alaska</option>
                                                    <option value="AZ" <?php echo e(old('state') == 'AZ' ? 'selected' : ''); ?>>Arizona</option>
                                                    <option value="AR" <?php echo e(old('state') == 'AR' ? 'selected' : ''); ?>>Arkansas</option>
                                                    <option value="CA" <?php echo e(old('state') == 'CA' ? 'selected' : ''); ?>>California</option>
                                                    <option value="CO" <?php echo e(old('state') == 'CO' ? 'selected' : ''); ?>>Colorado</option>
                                                    <option value="CT" <?php echo e(old('state') == 'CT' ? 'selected' : ''); ?>>Connecticut</option>
                                                    <option value="DE" <?php echo e(old('state') == 'DE' ? 'selected' : ''); ?>>Delaware</option>
                                                    <option value="DC" <?php echo e(old('state') == 'DC' ? 'selected' : ''); ?>>District Of Columbia</option>
                                                    <option value="FL" <?php echo e(old('state') == 'FL' ? 'selected' : ''); ?>>Florida</option>
                                                    <option value="GA" <?php echo e(old('state') == 'GA' ? 'selected' : ''); ?>>Georgia</option>
                                                    <option value="HI" <?php echo e(old('state') == 'HI' ? 'selected' : ''); ?>>Hawaii</option>
                                                    <option value="ID" <?php echo e(old('state') == 'ID' ? 'selected' : ''); ?>>Idaho</option>
                                                    <option value="IL" <?php echo e(old('state') == 'IL' ? 'selected' : ''); ?>>Illinois</option>
                                                    <option value="IN" <?php echo e(old('state') == 'IN' ? 'selected' : ''); ?>>Indiana</option>
                                                    <option value="IA" <?php echo e(old('state') == 'IA' ? 'selected' : ''); ?>>Iowa</option>
                                                    <option value="KS" <?php echo e(old('state') == 'KS' ? 'selected' : ''); ?>>Kansas</option>
                                                    <option value="KY" <?php echo e(old('state') == 'KY' ? 'selected' : ''); ?>>Kentucky</option>
                                                    <option value="LA" <?php echo e(old('state') == 'LA' ? 'selected' : ''); ?>>Louisiana</option>
                                                    <option value="ME" <?php echo e(old('state') == 'ME' ? 'selected' : ''); ?>>Maine</option>
                                                    <option value="MD" <?php echo e(old('state') == 'MD' ? 'selected' : ''); ?>>Maryland</option>
                                                    <option value="MA" <?php echo e(old('state') == 'MA' ? 'selected' : ''); ?>>Massachusetts</option>
                                                    <option value="MI" <?php echo e(old('state') == 'MI' ? 'selected' : ''); ?>>Michigan</option>
                                                    <option value="MN" <?php echo e(old('state') == 'MN' ? 'selected' : ''); ?>>Minnesota</option>
                                                    <option value="MS" <?php echo e(old('state') == 'MS' ? 'selected' : ''); ?>>Mississippi</option>
                                                    <option value="MO" <?php echo e(old('state') == 'MO' ? 'selected' : ''); ?>>Missouri</option>
                                                    <option value="MT" <?php echo e(old('state') == 'MT' ? 'selected' : ''); ?>>Montana</option>
                                                    <option value="NE" <?php echo e(old('state') == 'NE' ? 'selected' : ''); ?>>Nebraska</option>
                                                    <option value="NV" <?php echo e(old('state') == 'NV' ? 'selected' : ''); ?>>Nevada</option>
                                                    <option value="NH" <?php echo e(old('state') == 'NH' ? 'selected' : ''); ?>>New Hampshire</option>
                                                    <option value="NJ" <?php echo e(old('state') == 'NJ' ? 'selected' : ''); ?>>New Jersey</option>
                                                    <option value="NM" <?php echo e(old('state') == 'NM' ? 'selected' : ''); ?>>New Mexico</option>
                                                    <option value="NY" <?php echo e(old('state') == 'NY' ? 'selected' : ''); ?>>New York</option>
                                                    <option value="NC" <?php echo e(old('state') == 'NC' ? 'selected' : ''); ?>>North Carolina</option>
                                                    <option value="ND" <?php echo e(old('state') == 'ND' ? 'selected' : ''); ?>>North Dakota</option>
                                                    <option value="OH" <?php echo e(old('state') == 'OH' ? 'selected' : ''); ?>>Ohio</option>
                                                    <option value="OK" <?php echo e(old('state') == 'OK' ? 'selected' : ''); ?>>Oklahoma</option>
                                                    <option value="OR" <?php echo e(old('state') == 'OR' ? 'selected' : ''); ?>>Oregon</option>
                                                    <option value="PA" <?php echo e(old('state') == 'PA' ? 'selected' : ''); ?>>Pennsylvania</option>
                                                    <option value="RI" <?php echo e(old('state') == 'RI' ? 'selected' : ''); ?>>Rhode Island</option>
                                                    <option value="SC" <?php echo e(old('state') == 'SC' ? 'selected' : ''); ?>>South Carolina</option>
                                                    <option value="SD" <?php echo e(old('state') == 'SD' ? 'selected' : ''); ?>>South Dakota</option>
                                                    <option value="TN" <?php echo e(old('state') == 'TN' ? 'selected' : ''); ?>>Tennessee</option>
                                                    <option value="TX" <?php echo e(old('state') == 'TX' ? 'selected' : ''); ?>>Texas</option>
                                                    <option value="UT" <?php echo e(old('state') == 'UT' ? 'selected' : ''); ?>>Utah</option>
                                                    <option value="VT" <?php echo e(old('state') == 'VT' ? 'selected' : ''); ?>>Vermont</option>
                                                    <option value="VA" <?php echo e(old('state') == 'VA' ? 'selected' : ''); ?>>Virginia</option>
                                                    <option value="WA" <?php echo e(old('state') == 'WA' ? 'selected' : ''); ?>>Washington</option>
                                                    <option value="WV" <?php echo e(old('state') == 'WV' ? 'selected' : ''); ?>>West Virginia</option>
                                                    <option value="WI" <?php echo e(old('state') == 'WI' ? 'selected' : ''); ?>>Wisconsin</option>
                                                    <option value="WY" <?php echo e(old('state') == 'WY' ? 'selected' : ''); ?>>Wyoming</option>
                                                </select>
                                                <?php if($errors->has('state')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('state')); ?></strong>
                                                        </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="zip">Zip code</label>
                                                <input type="text" name="zip" id="zip" class="form-control" value="<?php echo e(old('zip')); ?>"/>
                                                <?php if($errors->has('zip')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('zip')); ?></strong>
                                                        </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="timezone">Timezone</label>
                                                <select name="timezone" id="timezone" class="form-control" required>
                                                    <option value="">Select a Timezone</option>
                                                    <option value="UTC-08:00" <?php echo e(old('timezone') == 'UTC-08:00' ? 'selected' : ''); ?>>(UTC-08:00) Pacific Time (US & Canada)</option>
                                                    <option value="UTC-07:00" <?php echo e(old('timezone') == 'UTC-07:00' ? 'selected' : ''); ?>>(UTC-07:00) Mountain Time (US & Canada)</option>
                                                    <option value="UTC-06:00" <?php echo e(old('timezone') == 'UTC-06:00' ? 'selected' : ''); ?>>(UTC-06:00) Central Time (US & Canada)</option>
                                                    <option value="UTC-05:00" <?php echo e(old('timezone') == 'UTC-05:00' ? 'selected' : ''); ?>>(UTC-05:00) Eastern Time (US & Canada)</option>
                                                </select>
                                                <?php if($errors->has('timezone')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('timezone')); ?></strong>
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
                <div class="hidden tab-pane" id="notification">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Notification Settings</h5>
                                    <span class="text-muted text-sm">
                                        <?php echo e(__('Customize notification settings of what you want to get notified about.')); ?>

                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="col-span-6 sm:col-span-6 border-b-2 mb-5 pb-5">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="mb-1">Email Notification</p>
                                                <p class="text-muted text-sm mb-0">Get notified by email.</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input h4 position-relative m-0" type="checkbox"
                                                       name="notification_email" role="switch" <?php echo e(old('notification_email') ? 'checked' : ''); ?>/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-6 sm:col-span-6 border-b-2 mb-5 pb-5">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="mb-1">SMS Notification</p>
                                                <p class="text-muted text-sm mb-0">Get notified by SMS ( Need to have phone number set ).</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input h4 position-relative m-0" type="checkbox"
                                                       name="notification_sms" role="switch" <?php echo e(old('notification_sms') ? 'checked' : ''); ?>/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-6 sm:col-span-6 border-b-2 mb-5 pb-5">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="mb-1">Push Notification</p>
                                                <p class="text-muted text-sm mb-0">Get notified by browser push notification.</p>
                                            </div>
                                            <div class="form-check form-switch p-0">
                                                <input class="form-check-input h4 position-relative m-0" type="checkbox"
                                                       name="notification_push" role="switch" <?php echo e(old('notification_push') ? 'checked' : ''); ?>/>
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
                    <button type="submit" class="btn btn-primary mx-1">Create Administrator</button>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('post-scripts'); ?>
    <script src="<?php echo e(asset('js/plugins/flatpickr.min.js')); ?>"></script>
    <script>
        flatpickr(document.querySelector('#dateOfBirth'));
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.2.1/ol.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/flatpickr.min.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\admins\create.blade.php ENDPATH**/ ?>