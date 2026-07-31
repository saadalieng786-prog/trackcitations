<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
        <form action="<?php echo e(route(auth()->user()->portalRoutePrefix().'.tickets.update', $ticket->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="card">
                <div class="card-body !py-0">
                    <ul class="flex flex-wrap w-full font-medium text-center nav-tabs">
                        <li class="group active">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="ticketDetails"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-file-text ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Ticket Details
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="courtInformation"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-building-bank ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Court information
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="assignedAttorney"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-briefcase ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Assigned Attorney
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="ticketDocuments"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-folder ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Documents
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="ticketNotes"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500 text-muted"
                            >
                                <i class="ti ti-file ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Notes
                            </a>
                        </li>
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="processorInfo"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-user ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Processor info
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div class="block tab-pane" id="ticketDetails">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Ticket Details</h5>
</div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="col-span-12 md:col-span-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Driver</label>
                                                <input type="text" class="form-control" name="name" id="name" value="<?php echo e(old('name', $ticket->name)); ?>"/>
                                                <?php if($errors->has('name')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('name')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Driver Email</label>
                                                <input type="email" name="user_email" class="form-control" id="driverEmail" value="<?php echo e(old('user_email', $ticket->user_email)); ?>" />
                                                <?php if($errors->has('user_email')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('user_email')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Company</label>
                                                <select
                                                    class="form-control"
                                                    name="company_id"
                                                    id="companies"
                                                ></select>
                                                <?php if($errors->has('company_id')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                    <strong><?php echo e($errors->first('company_id')); ?></strong>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="address">Address</label>
                                                <input type="text" class="form-control" name="address" id="address" value="<?php echo e(old('address', $ticket->address)); ?>" />
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
                                                <input type="text" class="form-control" name="city" id="city" value="<?php echo e(old('city', $ticket->city)); ?>"/>
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
                                                <input type="text" class="form-control" name="state" id="state" value="<?php echo e(old('state', $ticket->state)); ?>" />
                                                <?php if($errors->has('state')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('state')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="zip">Zipcode</label>
                                                <input type="text" class="form-control" name="zip" id="zip" value="<?php echo e(old('zip', $ticket->zip)); ?>" />
                                                <?php if($errors->has('zip')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('zip')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Date Received</label>
                                                <div class="input-group date">
                                                    <input type="text" name="date_issued" class="form-control" placeholder="Select date"
                                                           id="dateIssued" value="<?php echo e(\Carbon\Carbon::parse(old('date_issued', $ticket->date_issued))->toDateString()); ?>"/>
                                                    <span class="input-group-text">
                                                      <i class="feather icon-calendar"></i>
                                                    </span>
                                                </div>
                                                <?php if($errors->has('date_issued')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('date_issued')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-12">
                                            <label class="form-label mt-3">Indicator</label>
                                            <div class="mb-3 grid grid-cols-12 gap-6">
                                                <div class="col-span-12 lg:col-span-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <input type="radio" name="indicator" class="form-check-input"
                                                                   id="receivedIndicator" <?php echo e(old('indicator', $ticket->indicator) === \App\Models\Ticket::INDICATOR_RECEIVED ? 'checked' : ''); ?> value="<?php echo e(\App\Models\Ticket::INDICATOR_RECEIVED); ?>"/>
                                                            <label
                                                                class="inline-block ml-2 w-[calc(100%_-_30px)] opacity-100"
                                                                for="receivedIndicator">
                                                                    <span>
                                                                      <span class="text-[12px] font-semibold block">
                                                                        Received
                                                                      </span>
                                                                    </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-12 lg:col-span-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <input type="radio" name="indicator" class="form-check-input"
                                                                   id="sentToAttorneyIndicator" <?php echo e(old('indicator', $ticket->indicator) === \App\Models\Ticket::INDICATOR_SENT_TO_ATTORNEY ? 'checked' : ''); ?> value="<?php echo e(\App\Models\Ticket::INDICATOR_SENT_TO_ATTORNEY); ?>"/>
                                                            <label
                                                                class="inline-block ml-2 w-[calc(100%_-_30px)]"
                                                                for="sentToAttorneyIndicator">
                                                                    <span>
                                                                      <span class="text-[12px] font-semibold block">
                                                                        Sent to attorney
                                                                      </span>
                                                                    </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-12 lg:col-span-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <input type="radio" name="indicator" class="form-check-input"
                                                                   id="cancelledIndicator" <?php echo e(old('indicator', $ticket->indicator) === \App\Models\Ticket::INDICATOR_CANCELLED ? 'checked' : ''); ?> value="<?php echo e(\App\Models\Ticket::INDICATOR_CANCELLED); ?>" />
                                                            <label
                                                                class="inline-block ml-2 w-[calc(100%_-_30px)]"
                                                                for="cancelledIndicator">
                                                                    <span>
                                                                      <span class="text-[12px] font-semibold block">
                                                                        Cancelled
                                                                      </span>
                                                                    </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-12 lg:col-span-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <input type="radio" name="indicator" class="form-check-input"
                                                                   id="disposedIndicator" <?php echo e(old('indicator', $ticket->indicator) === \App\Models\Ticket::INDICATOR_DISPOSED ? 'checked' : ''); ?> value="<?php echo e(\App\Models\Ticket::INDICATOR_DISPOSED); ?>" />
                                                            <label
                                                                class="inline-block ml-2 w-[calc(100%_-_30px)]"
                                                                for="disposedIndicator">
                                                                    <span>
                                                                      <span class="text-[12px] font-semibold block">
                                                                        Disposed
                                                                      </span>
                                                                    </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-12 lg:col-span-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <input type="radio" name="indicator" class="form-check-input"
                                                                   id="continuedIndicator" <?php echo e(old('indicator', $ticket->indicator) === \App\Models\Ticket::INDICATOR_CONTINUED ? 'checked' : ''); ?> value="<?php echo e(\App\Models\Ticket::INDICATOR_CONTINUED); ?>" />
                                                            <label
                                                                class="inline-block ml-2 w-[calc(100%_-_30px)]"
                                                                for="continuedIndicator">
                                                                    <span>
                                                                      <span class="text-[12px] font-semibold block">
                                                                        Continued
                                                                      </span>
                                                                    </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-12 lg:col-span-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <input type="radio" name="indicator" class="form-check-input"
                                                                   id="attorneyAssignedIndicator" <?php echo e(old('indicator', $ticket->indicator) === \App\Models\Ticket::INDICATOR_ASSIGNED_TO_ATTORNEY ? 'checked' : ''); ?> value="<?php echo e(\App\Models\Ticket::INDICATOR_ASSIGNED_TO_ATTORNEY); ?>" />
                                                            <label
                                                                class="inline-block ml-2 w-[calc(100%_-_30px)]"
                                                                for="attorneyAssignedIndicator">
                                                                    <span>
                                                                      <span class="text-[12px] font-semibold block">
                                                                        Attorney Assigned
                                                                      </span>
                                                                    </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if($errors->has('indicator')): ?>
                                                <span class="invalid-feedback text-danger">
                                                    <strong><?php echo e($errors->first('indicator')); ?></strong>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 mt-1">
                                            <div class="mb-3 grid grid-cols-12 gap-6 pt-6">
                                                <div class="col-span-12 lg:col-span-6 border-gray-50 border rounded-lg px-3 py-2">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <p class="mb-1">Class Commercial</p>
                                                        </div>
                                                        <div class="form-check form-switch p-0">
                                                            <input
                                                                name="class_commercial"
                                                                class="form-check-input h4 position-relative m-0"
                                                                type="checkbox"
                                                                role="switch"
                                                                value="Yes"
                                                                <?php echo e(old('class_commercial', $ticket->class_commercial) === 'Yes'? 'checked' : ''); ?>

                                                            />
                                                        </div>
                                                    </div>
                                                    <?php if($errors->has('class_commercial')): ?>
                                                        <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('class_commercial')); ?></strong>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-span-12 lg:col-span-6 border-gray-50 border rounded-lg px-3 py-2">
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <p class="mb-1">Road Side Inspection</p>
                                                        </div>
                                                        <div class="form-check form-switch p-0">
                                                            <input
                                                                name="road_side_inspection"
                                                                class="form-check-input h4 position-relative m-0"
                                                                type="checkbox"
                                                                role="switch"
                                                                value="Yes"
                                                                <?php echo e(old('road_side_inspection', $ticket->road_side_inspection) === 'Yes'? 'checked' : ''); ?>

                                                            />
                                                        </div>
                                                    </div>
                                                    <?php if($errors->has('road_side_inspection')): ?>
                                                        <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('road_side_inspection')); ?></strong>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Vehicle License Number</label>
                                                <input type="text" class="form-control" name="vehicle_lic_no" value="<?php echo e(old('vehicle_lic_no', $ticket->vehicle_lic_no)); ?>" />
                                                <?php if($errors->has('vehicle_lic_no')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('vehicle_lic_no')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Violation</label>
                                                <select  class="form-control" name="violation_id">
                                                    <?php $__currentLoopData = \App\Models\Violation::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $violation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($violation->id); ?>" <?php echo e(old('violation_id', $ticket->violation_id) === $violation->id ? 'selected' : ''); ?>><?php echo e($violation->violation); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <?php if($errors->has('violation_id')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('violation_id')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Citation Number</label>
                                                <input type="text" class="form-control" name="citation_no" value="<?php echo e(old('citation_no', $ticket->citation_no)); ?>" />
                                                <?php if($errors->has('citation_no')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('citation_no')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Ticket type</label>
                                                <textarea class="form-control" name="ticket_type"><?php echo e(old('ticket_type', $ticket->ticket_type)); ?></textarea>
                                                <?php if($errors->has('ticket_type')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('ticket_type')); ?></strong>
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
                <div class="hidden tab-pane" id="courtInformation">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <div class="card">
                                <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Court information</h5>
</div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Court</label>
                                                <input type="text" class="form-control" name="court_name" value="<?php echo e(old('court_name', $ticket->court_name)); ?>" />
                                                <?php if($errors->has('court_name')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('court_name')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Court Date</label>
                                                <div class="input-group date">
                                                    <input type="text" class="form-control" placeholder="Select date"
                                                           id="courtTabDate" name="court_date" value="<?php echo e(old('court_date', $ticket->court_address) ? \Carbon\Carbon::parse(old('court_date', $ticket->court_date))->toDateString() : ''); ?>"/>
                                                </div>
                                                <?php if($errors->has('court_date')): ?>
                                                    <span class="invalid-feedback text-danger">
                                                        <strong><?php echo e($errors->first('court_date')); ?></strong>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Court Address</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="court_address" id="courtAddress" value="<?php echo e(old('court_address', $ticket->court_address)); ?>" />
                                                    <button class="btn btn-outline-secondary" type="button" onclick="showAddressOnMap()">
                                                        Get Location <span class="ti ti-map-pin"></span>
                                                    </button>
                                                    <?php if($errors->has('court_address')): ?>
                                                        <span class="invalid-feedback text-danger">
                                                            <strong><?php echo e($errors->first('court_address')); ?></strong>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <div id="courtMap" class="h-[320px]"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden tab-pane" id="assignedAttorney">
                    <div class="card">
                        <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Assigned attorney</h5>
</div>
                        <div class="card-body">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary text-[18px] font-bold">Attorney Name</label>
                                        <select
                                            class="form-control"
                                            name="attorney_id"
                                            id="attorneys"
                                        ></select>
                                        <?php if($errors->has('attorney_id')): ?>
                                            <span class="invalid-feedback text-danger">
                                                <strong><?php echo e($errors->first('attorney_id')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary text-[18px] font-bold">Attorney Address</label>
                                        <input type="text" class="form-control-plaintext" id="attorneyAddress" readonly="readonly"/>
                                    </div>
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary text-[18px] font-bold">Attorney Phone</label>
                                        <input type="text" class="form-control-plaintext" id="attorneyPhone" readonly="readonly"/>
                                    </div>
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary text-[18px] font-bold">Office Hours</label>
                                        <input type="text" class="form-control-plaintext" id="attorneyOfficeHours" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <label class="form-label mt-3">Attorney Response</label>
                                    <div class="mb-3 grid grid-cols-12 gap-6">
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="border card p-3">
                                                <div class="form-check">
                                                    <input type="radio" name="attorney_response" class="form-check-input"
                                                           id="acceptedAttorneyResponse" <?php echo e(old('attorney_response', $ticket->attorney_response) === \App\Models\Ticket::ATTORENY_RESPONSE_ACCEPTED ? 'checked' : ''); ?> value="<?php echo e(\App\Models\Ticket::ATTORENY_RESPONSE_ACCEPTED); ?>"/>
                                                    <label
                                                        class="inline-block ml-2 w-[calc(100%_-_30px)] opacity-100"
                                                        for="acceptedAttorneyResponse">
                                                                    <span>
                                                                      <span class="text-[12px] font-semibold block">
                                                                        Accepted
                                                                      </span>
                                                                    </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="border card p-3">
                                                <div class="form-check">
                                                    <input type="radio" name="attorney_response" class="form-check-input"
                                                           id="rejectedAttorneyResponse" <?php echo e(old('attorney_response', $ticket->attorney_response) === \App\Models\Ticket::ATTORENY_RESPONSE_REJECTED ? 'checked' : ''); ?> value="<?php echo e(\App\Models\Ticket::ATTORENY_RESPONSE_REJECTED); ?>"/>
                                                    <label
                                                        class="inline-block ml-2 w-[calc(100%_-_30px)] opacity-100"
                                                        for="rejectedAttorneyResponse">
                                                                    <span>
                                                                      <span class="text-[12px] font-semibold block">
                                                                        Rejected
                                                                      </span>
                                                                    </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden tab-pane" id="ticketDocuments">
                    <div class="card">
                        <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Documents</h5>
</div>
                        <div class="card-body">
                            <div id="dZUpload" class="dropzone">
                                <div class="dz-default dz-message">
                                    <svg class="pc-icon block mb-2 mx-auto w-[calc(100%_-_120px)]"> <use xlink:href="#custom-document-upload"></use> </svg>
                                    <span>Drag and drop files here or click to upload</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table mb-0" id="attachmentsTable">
                                    <thead>
                                    <tr>
                                        <th>Document</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $ticket->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <h5 class="mb-0"><?php echo e($attachment->filename); ?></h5>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <a href="<?php echo e($attachment->url); ?>"
                                                   class="filePreviewBtn w-9 h-9 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-eye text-warning text-lg leading-none"></i>
                                                </a>
                                                <a href="<?php echo e($attachment->url); ?>"
                                                   class="w-9 h-9 rounded-xl inline-flex items-center justify-center btn-link-secondary"
                                                   download>
                                                    <i class="ti ti-download text-primary text-lg leading-none"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden tab-pane" id="ticketNotes">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 md:col-span-12">
                            <div class="card">
                                <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Notes</h5>
</div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 md:col-span-12">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Note</label>
                                                <div class="flex items-center flex-col">
                                                    <div class="grow mx-3 w-full">
                                                        <textarea class="form-control" id="newTicketNote" name="note"></textarea>
                                                    </div>
                                                    <div class="shrink-0 mt-3">
                                                        <button type="button" class="btn btn-primary" id="addNoteBtn">Add Note</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6" id="notesList">
                                        <?php $__currentLoopData = $ticket->safeNotes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-span-12 md:col-span-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="mb-4"><?php echo e($note->note); ?></h6>
                                                        <span class="text-muted text-sm float-end"> <?php echo e($note->user->name ?? $ticket->name); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden tab-pane" id="processorInfo">
                    <div class="card">
                        <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Processor Info</h5>
</div>
                        <div class="card-body">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary text-[18px] font-bold">Processor Name</label>
                                        <input type="text" class="form-control" name="processor_name" value="<?php echo e(old('processor_name', $ticket->processor_name)); ?>" />
                                        <?php if($errors->has('processor_name')): ?>
                                            <span class="invalid-feedback text-danger">
                                                <strong><?php echo e($errors->first('processor_name')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary text-[18px] font-bold">Processor Email</label>
                                        <input type="email" class="form-control" name="processor_email" value="<?php echo e(old('processor_email', $ticket->processor_email)); ?>" />
                                        <?php if($errors->has('processor_email')): ?>
                                            <span class="invalid-feedback text-danger">
                                                <strong><?php echo e($errors->first('processor_email')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary text-[18px] font-bold">Processor Phone</label>
                                        <input type="text" class="form-control" name="processor_ph_number" value="<?php echo e(old('processor_ph_number', $ticket->processor_ph_number)); ?>" />
                                        <?php if($errors->has('processor_ph_number')): ?>
                                            <span class="invalid-feedback text-danger">
                                                <strong><?php echo e($errors->first('processor_ph_number')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-span-12 sm:col-span-6">
                                    <div class="mb-3">
                                        <label class="form-label text-primary text-[18px] font-bold">Processor Notes To Attorney</label>
                                        <textarea class="form-control" rows="2" name="processor_notes_to_attorney"><?php echo e(old('processor_notes_to_attorney', $ticket->processor_notes_to_attorney)); ?></textarea>
                                        <?php if($errors->has('processor_notes_to_attorney')): ?>
                                            <span class="invalid-feedback text-danger">
                                                <strong><?php echo e($errors->first('processor_notes_to_attorney')); ?></strong>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 text-right">
                    <button type="reset" class="btn btn-outline-secondary mx-1">Cancel</button>
                    <button type="submit" class="btn btn-primary mx-1">Update Ticket</button>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/ol@v10.2.1/dist/ol.js"></script>
    <script src="<?php echo e(asset('js/plugins/flatpickr.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/plugins/choices.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/plugins/dropzone-amd-module.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/viewerjs@1.10.1/dist/viewer.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;
        let myDropzone = new Dropzone("div#dZUpload", {
            url: "<?php echo e(route('api.tickets-attach.store', $ticket->id)); ?>",
            headers: {
                'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
            }
        });
        // Add an error event handler
        myDropzone.on("error", function(file, response) {
            // Check if response has a message property and display it
            let errorMessage = response.message ? response.message : "An error occurred during the upload.";

            // Optionally, you could append the error to the file preview in Dropzone
            let errorDisplay = file.previewElement.querySelector("[data-dz-errormessage]");
            if (errorDisplay) {
                errorDisplay.textContent = errorMessage;
            }

            Toast.fire({
                icon: 'error',
                title: errorMessage
            });
        });

        myDropzone.on("success", file => {
            let attachmentResponse = JSON.parse(file.xhr.response);
            // Create a new table row for the uploaded file
            const newRow = `
                <tr>
                    <td>
                        <div class="flex items-center">
                            <h5 class="mb-0">${file.name}</h5>
                        </div>
                    </td>
                    <td class="text-right">
                        <a href="${attachmentResponse.path}" class="filePreviewBtn w-9 h-9 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                            <i class="ti ti-eye text-warning text-lg leading-none"></i>
                        </a>
                        <a href="${attachmentResponse.path}" class="w-9 h-9 rounded-xl inline-flex items-center justify-center btn-link-secondary" download>
                            <i class="ti ti-download text-primary text-lg leading-none"></i>
                        </a>
                    </td>
                </tr>
            `;
            document.querySelector('#attachmentsTable tbody').insertAdjacentHTML('beforeend', newRow);

            Toast.fire({
                icon: 'success',
                title: 'Document attached successfully'
            });
        });

        const map = new ol.Map({
            target: 'courtMap',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([-87.6298, 41.8781]), // Corrected center
                zoom: 5
            })
        });
        function showAddressOnMap() {
            let address = document.querySelector('#courtAddress').value;
            address = address.split(',')[0];
            if (address) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const lon = parseFloat(data[0].lon);
                            const lat = parseFloat(data[0].lat);

                            // Set map view to the address location
                            map.getView().setCenter(ol.proj.fromLonLat([lon, lat]));
                            map.getView().setZoom(18);

                            // Add a marker for the address
                            const marker = new ol.Overlay({
                                position: ol.proj.fromLonLat([lon, lat]),
                                positioning: 'center-center',
                                element: document.createElement('div'),
                                stopEvent: false
                            });
                            marker.getElement().className = 'marker';
                            map.addOverlay(marker);
                        } else {
                            alert("Address not found!");
                        }
                    })
                    .catch(error => console.error("Error fetching geolocation data:", error));
            }
        }
        showAddressOnMap();
    </script>


    <script>
        // minimum setup
        flatpickr(document.querySelector('#courtTabDate'));
        flatpickr(document.querySelector('#dateIssued'));

        // Initialize Choices without any choices initially
        // Initialize Choices without any choices initially
        let attorneys = document.querySelector('#attorneys');
        var attorneysChoices = new Choices('#attorneys', {
            placeholder: true,
            placeholderValue: 'Attorney Name',
            maxItemCount: 5,
            shouldSort: false, // Optional: keeps the order of items as provided
        }).setChoices(function () {
            return fetch('<?php echo e(route('api.attorney.index')); ?>')
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    return [{
                        value: '',
                        label: 'Select an option',
                        disabled: true,
                        selected: <?php echo e(!old('attorney_id', $ticket->attorney_id) ? 'true' : 'false'); ?> },
                        ...data.map(function (attorney) {
                            let isSelected = <?php echo e(old('attorney_id', $ticket->attorney_id)); ?> === attorney.roleable.id.toString();
                            if (isSelected) {
                                document.querySelector('#attorneyOfficeHours').value = (attorney.roleable.office_hours_start ?? '') + ' - ' + (attorney.roleable.office_hours_start ?? '');
                                document.querySelector('#attorneyPhone').value = (attorney.phone ?? '');
                                document.querySelector('#attorneyAddress').value = (attorney.address ?? '');
                            }
                            return {
                                value: attorney.roleable.id,
                                label: attorney.name,
                                selected: isSelected,
                                customProperties: {
                                    officeHours: (attorney.roleable.office_hours_start ?? '') + ' - ' + (attorney.roleable.office_hours_start ?? ''),
                                    attorneyPhone: (attorney.phone ?? ''),
                                    attorneyAddress: (attorney.address ?? ''),
                                },
                            };
                        })]
                });
        });
        attorneys.addEventListener('choice', function(event) {
            document.querySelector('#attorneyOfficeHours').value =  event.detail.customProperties.officeHours ?? '';
            document.querySelector('#attorneyPhone').value = event.detail.customProperties.attorneyPhone ?? '';
            document.querySelector('#attorneyAddress').value = event.detail.customProperties.attorneyAddress ?? '';
        });

        var companiesChoices = new Choices('#companies', {
            placeholder: true,
            placeholderValue: 'Company Name',
            maxItemCount: 5,
            shouldSort: false, // Optional: keeps the order of items as provided
        }).setChoices(function () {
            return fetch('<?php echo e(route('api.company.index')); ?>')
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    return [{
                        value: '',
                        label: 'Select an option',
                        disabled: true,
                        selected: <?php echo e(!old('company_id', $ticket->company_id) ? 'true' : 'false'); ?> },
                        ...data.map(function (company) {
                            return {
                                value: company.id,
                                label: company.name,
                                selected: Number(<?php echo e(old('company_id', $ticket->company_id)); ?>) === Number(company.id)
                            };
                        })];
                });
        });
        document.addEventListener('click', function (e) {
            let addNoteBtn = e.target.closest('#addNoteBtn');
            if (addNoteBtn) {
                // Create a new XMLHttpRequest object
                const xhr = new XMLHttpRequest();

                const url = "<?php echo e(route('api.tickets-note.store', $ticket->id)); ?>";

                xhr.open("POST", url, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', '<?php echo e(csrf_token()); ?>');
                xhr.setRequestHeader("Content-Type", "application/json");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 201) {
                            // Request succeeded, handle response here
                            let data = JSON.parse(xhr.responseText);
                            document.getElementById('notesList').innerHTML += ('<div class="col-span-12 md:col-span-6">'+
                                '<div class="card">'+
                                '<div class="card-body">'+
                                '<h6 class="mb-4">'+data.note+'</h6>'+
                                '<span class="text-muted text-sm float-end"> <?php echo e(Auth::user()->name); ?></span>'+
                                '</div>'+
                                '</div>'+
                                '</div>')
                            Toast.fire({
                                icon: 'success',
                                title: 'Note added successfully'
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Something went wrong!'
                            });
                        }
                    }
                };

                const data = JSON.stringify({
                    note: document.getElementById('newTicketNote').value,
                });

                xhr.send(data);
                document.getElementById('newTicketNote').value = '';
            }
        })

    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.2.1/ol.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/flatpickr.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/dropzone.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/choices.min.css')); ?>" />
    <style>
        .marker {
            width: 24px;
            height: 24px;
            background-image: url('https://cdn-icons-png.flaticon.com/512/684/684908.png'); /* Example icon */
            background-size: cover;
            transform: translate(-50%, -100%); /* Offset to place the point at the tip of the icon */
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\manager\tickets\edit.blade.php ENDPATH**/ ?>