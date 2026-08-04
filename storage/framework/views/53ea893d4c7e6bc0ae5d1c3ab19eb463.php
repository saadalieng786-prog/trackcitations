<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
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
                            data-pc-target="change-password"
                            class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                        >
                            <i class="ti ti-key ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                            Update Password
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div class="block tab-pane" id="profile">
                <form id="send-verification" method="post" action="<?php echo e(route('verification.send')); ?>">
                    <?php echo csrf_field(); ?>
                </form>
                <form method="post" action="<?php echo e(route('profile.update')); ?>" class="mt-6 space-y-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('patch'); ?>
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
                                                <input type="text"  name="name" id="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required autofocus autocomplete="name"/>
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
                                                <input type="email" name="email" id="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required autocomplete="username"/>
                                                <?php if($errors->has('email')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="phone">Phone</label>
                                                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo e(old('phone', $user->phone)); ?>" />
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
                                                           id="dateOfBirth" value="<?php echo e(old('dob', $user->dob)); ?>"/>
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
                                                <input type="text" name="address" id="address" class="form-control" value="<?php echo e(old('address', $user->address)); ?>" />
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
                                                <input type="text" name="city" id="city" class="form-control" value="<?php echo e(old('city', $user->city)); ?>" />
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
                                                    <option value="AL">Alabama</option>
                                                    <option value="AK">Alaska</option>
                                                    <option value="AZ">Arizona</option>
                                                    <option value="AR">Arkansas</option>
                                                    <option value="CA">California</option>
                                                    <option value="CO">Colorado</option>
                                                    <option value="CT">Connecticut</option>
                                                    <option value="DE">Delaware</option>
                                                    <option value="DC">District Of Columbia</option>
                                                    <option value="FL">Florida</option>
                                                    <option value="GA">Georgia</option>
                                                    <option value="HI">Hawaii</option>
                                                    <option value="ID">Idaho</option>
                                                    <option value="IL">Illinois</option>
                                                    <option value="IN">Indiana</option>
                                                    <option value="IA">Iowa</option>
                                                    <option value="KS">Kansas</option>
                                                    <option value="KY">Kentucky</option>
                                                    <option value="LA">Louisiana</option>
                                                    <option value="ME">Maine</option>
                                                    <option value="MD">Maryland</option>
                                                    <option value="MA">Massachusetts</option>
                                                    <option value="MI">Michigan</option>
                                                    <option value="MN">Minnesota</option>
                                                    <option value="MS">Mississippi</option>
                                                    <option value="MO">Missouri</option>
                                                    <option value="MT">Montana</option>
                                                    <option value="NE">Nebraska</option>
                                                    <option value="NV">Nevada</option>
                                                    <option value="NH">New Hampshire</option>
                                                    <option value="NJ">New Jersey</option>
                                                    <option value="NM">New Mexico</option>
                                                    <option value="NY">New York</option>
                                                    <option value="NC">North Carolina</option>
                                                    <option value="ND">North Dakota</option>
                                                    <option value="OH">Ohio</option>
                                                    <option value="OK">Oklahoma</option>
                                                    <option value="OR">Oregon</option>
                                                    <option value="PA">Pennsylvania</option>
                                                    <option value="RI">Rhode Island</option>
                                                    <option value="SC">South Carolina</option>
                                                    <option value="SD">South Dakota</option>
                                                    <option value="TN">Tennessee</option>
                                                    <option value="TX">Texas</option>
                                                    <option value="UT">Utah</option>
                                                    <option value="VT">Vermont</option>
                                                    <option value="VA">Virginia</option>
                                                    <option value="WA">Washington</option>
                                                    <option value="WV">West Virginia</option>
                                                    <option value="WI">Wisconsin</option>
                                                    <option value="WY">Wyoming</option>
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
                                                <input type="text" name="zip" id="zip" class="form-control" value="<?php echo e(old('zip', $user->zip)); ?>"/>
                                                <?php if($errors->has('zip')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('zip')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()): ?>
                                            <div class="col-span-12 sm:col-span-6">
                                                <div class="alert alert-warning w-full">
                                                    <p class="text-sm mt-2 text-gray-800">
                                                        <?php echo e(__('Your email address is unverified.')); ?>


                                                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            <?php echo e(__('Click here to re-send the verification email.')); ?>

                                                        </button>
                                                    </p>

                                                    <?php if(session('status') === 'verification-link-sent'): ?>
                                                        <p class="mt-2 font-medium text-sm text-green-600">
                                                            <?php echo e(__('A new verification link has been sent to your email address.')); ?>

                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 text-right">
                            <button type="reset" class="btn btn-outline-secondary mx-1">Cancel</button>
                            <button type="submit" class="btn btn-primary mx-1">Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="hidden tab-pane" id="change-password">
                <form method="post" action="<?php echo e(route('password.update')); ?>" class="mt-6 space-y-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('put'); ?>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <?php if(session('status') === 'password-updated'): ?>
                                <div class="alert alert-success">
                                    <?php echo e(__('Password changed successfully.')); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="text-primary text-[28px] font-bold">Update Password</h5>
                                    <span class="text-muted text-sm">
                                        <?php echo e(__('Ensure your account is using a long, random password to stay secure.')); ?>

                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold" for="password">Current Password</label>
                                            <input type="password" name="password" id="password" class="form-control"/>
                                            <?php if($errors->updatePassword->has('password')): ?>
                                                <span class="invalid-feedback text-danger">
                                                    <strong><?php echo e($errors->updatePassword->first('password')); ?></strong>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold" for="current_password">Password</label>
                                            <input type="password" name="current_password" id="current_password" class="form-control"/>
                                            <?php if($errors->updatePassword->has('current_password')): ?>
                                                <span class="invalid-feedback text-danger">
                                                    <strong><?php echo e($errors->updatePassword->first('current_password')); ?></strong>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold" for="password_confirmation">Confirm Password</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"/>
                                            <?php if($errors->updatePassword->has('password_confirmation')): ?>
                                                <span class="invalid-feedback text-danger">
                                                    <strong><?php echo e($errors->updatePassword->first('password_confirmation')); ?></strong>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 text-right">
                            <button type="reset" class="btn btn-outline-secondary mx-1">Cancel</button>
                            <button type="submit" class="btn btn-primary mx-1">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('post-scripts'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\profile\edit.blade.php ENDPATH**/ ?>