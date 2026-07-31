<?php $__env->startSection('content'); ?>
    <div class="col-span-12">
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
                            class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
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
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Driver Name</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->name); ?>" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Driver Email</label>
                                            <input type="email" class="form-control-plaintext" value="<?php echo e($ticket->user_email); ?>" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Company</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->company?->name); ?>" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Address</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->address); ?>" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">City</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->city); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">State</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->state); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Zipcode</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->zip); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Date Received</label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control-plaintext" placeholder="Select date"
                                                       id="pc-datepicker-2" value="<?php echo e(\Carbon\Carbon::parse($ticket->date_issued)->toDateString()); ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-12">
                                        <label class="form-label mt-3">Indicator</label>
                                        <div class="mb-3 grid grid-cols-12 gap-6">
                                            <div class="col-span-12 lg:col-span-2">
                                                <div class="border card p-3 <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_RECEIVED ? 'bg-success text-white' : ''); ?>">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="customCheckdefhor1" readonly="readonly" disabled <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_RECEIVED ? 'checked' : ''); ?>/>
                                                        <label
                                                            class="inline-block ml-2 w-[calc(100%_-_30px)] opacity-100"
                                                            for="customCheckdefhor1">
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
                                                <div class="border card p-3 <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_SENT_TO_ATTORNEY ? 'bg-warning text-black' : ''); ?>">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="customCheckdefhor2" readonly="readonly" disabled <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_SENT_TO_ATTORNEY ? 'checked' : ''); ?>/>
                                                        <label
                                                            class="inline-block ml-2 w-[calc(100%_-_30px)]"
                                                            for="customCheckdefhor2">
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
                                                <div class="border card p-3 <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_CANCELLED ? 'bg-danger text-white' : ''); ?>">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="customCheckdefhor3" readonly="readonly" disabled <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_CANCELLED ? 'checked' : ''); ?>/>
                                                        <label
                                                            class="inline-block ml-2 w-[calc(100%_-_30px)]"
                                                            for="customCheckdefhor3">
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
                                                <div class="border card p-3 <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_DISPOSED ? 'bg-primary text-white' : ''); ?>">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="customCheckdefhor4" readonly="readonly" disabled <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_DISPOSED ? 'checked' : ''); ?>/>
                                                        <label
                                                            class="inline-block ml-2 w-[calc(100%_-_30px)]"
                                                            for="customCheckdefhor4">
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
                                                <div class="border card p-3 <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_CONTINUED ? 'bg-info text-white' : ''); ?>">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="continuedIndicator" readonly="readonly" disabled <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_CONTINUED ? 'checked' : ''); ?>/>
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
                                                <div class="border card p-3 <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_ASSIGNED_TO_ATTORNEY ? 'bg-secondary text-white' : ''); ?>">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="customCheckdefhor4" readonly="readonly" disabled <?php echo e($ticket->indicator === \App\Models\Ticket::INDICATOR_ASSIGNED_TO_ATTORNEY ? 'checked' : ''); ?>/>
                                                        <label
                                                            class="inline-block ml-2 w-[calc(100%_-_30px)]"
                                                            for="customCheckdefhor4">
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
                                    </div>
                                    <div class="col-span-12 sm:col-span-6 mt-1">
                                        <div class="mb-3 grid grid-cols-12 gap-6 pt-6">
                                            <div class="col-span-12 lg:col-span-6 border-gray-50 border rounded-lg px-3 py-2">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <p class="mb-1">Class Commercial</p>
                                                    </div>
                                                    <div class="form-check form-switch p-0">
                                                        <input name="class_commercial" class="form-check-input h4 position-relative m-0" type="checkbox"
                                                               role="switch" value="Yes" <?php echo e($ticket->class_commercial === 'Yes'? 'checked' : ''); ?> disabled/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6 border-gray-50 border rounded-lg px-3 py-2">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <p class="mb-1">Road Side Inspection</p>
                                                    </div>
                                                    <div class="form-check form-switch p-0">
                                                        <input name="road_side_inspection" class="form-check-input h4 position-relative m-0" type="checkbox"
                                                               role="switch" value="Yes" <?php echo e($ticket->road_side_inspection === 'Yes'? 'checked' : ''); ?> disabled />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Vehicle License Number</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->vehicle_lic_no); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Violation</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->violation?->violation); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Citation Number</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->citation_no); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3 flex flex-row justify-center">
                                            <div class="form-check mb-2 inline-block pr-4">
                                                <span class="ti <?php echo e($ticket->isDverDataq()['DATAQ'] ? 'ti-square-check' : 'ti-square'); ?> mt-2 text-[20px] text-primary"></span>
                                                <label class="form-check-label" for="flexCheckChecked">DataQ</label>
                                            </div>
                                            <div class="form-check mb-2 inline-block">
                                                <span class="ti <?php echo e($ticket->isDverDataq()['DVER'] ? 'ti-square-check' : 'ti-square'); ?> mt-2 text-[20px] text-primary"></span>
                                                <label class="form-check-label" for="flexCheckChecked">DVER</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Ticket type</label>
                                            <textarea class="form-control-plaintext" readonly="readonly"><?php echo e($ticket->ticket_type); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Beginning Fine amount</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->beginning_fine_amount); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Final Fine amount</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->final_fine_amount); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Total DVER Points</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->total_dver_points__c); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Total DVER Points Removed</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->total_dver_points_removed__c); ?>" readonly="readonly" />
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
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->court_name); ?>" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Court Date</label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control-plaintext" placeholder="Select date"
                                                       id="pc-datepicker-2" value="<?php echo e($ticket->court_date ? \Carbon\Carbon::parse($ticket->court_date)->toDateString() : ''); ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Court Address</label>
                                            <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->court_address); ?>" readonly="readonly" />
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
                                    <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->attorney?->user->name); ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Attorney Address</label>
                                    <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->attorney?->user->address); ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Attorney Phone</label>
                                    <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->attorney?->user->phone); ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Office Hours</label>
                                    <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->attorney?->office_hours_start ? \Carbon\Carbon::parse($ticket->attorney?->office_hours_start)->toTimeString() : ''); ?> - <?php echo e($ticket->attorney?->office_hours_end ? \Carbon\Carbon::parse($ticket->attorney?->office_hours_end)->toTimeString() : ''); ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label mt-3">Attorney Response</label>
                                <div class="mb-3 grid grid-cols-12 gap-6">
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="border card p-3 <?php echo e($ticket->attorney_response === \App\Models\Ticket::ATTORENY_RESPONSE_ACCEPTED ? 'bg-success text-white' : ''); ?>">
                                            <div class="form-check">
                                                <input type="radio" name="attorney_response" class="form-check-input"
                                                       id="acceptedAttorneyResponse" <?php echo e($ticket->attorney_response === \App\Models\Ticket::ATTORENY_RESPONSE_ACCEPTED ? 'checked' : ''); ?> value="<?php echo e(\App\Models\Ticket::ATTORENY_RESPONSE_ACCEPTED); ?>" disabled/>
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
                                        <div class="border card p-3 <?php echo e($ticket->attorney_response === \App\Models\Ticket::ATTORENY_RESPONSE_REJECTED ? 'bg-success text-white' : ''); ?>">
                                            <div class="form-check">
                                                <input type="radio" name="attorney_response" class="form-check-input"
                                                       id="rejectedAttorneyResponse" <?php echo e($ticket->attorney_response === \App\Models\Ticket::ATTORENY_RESPONSE_REJECTED ? 'checked' : ''); ?> value="<?php echo e(\App\Models\Ticket::ATTORENY_RESPONSE_REJECTED); ?>" disabled/>
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
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Processor Notes To Attorney</label>
                                    <textarea class="form-control-plaintext" rows="2" readonly="readonly"><?php echo e($ticket->processor_notes_to_attorney); ?></textarea>
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
                    <?php if(count($ticket->attachments)): ?>
                    <div class="card-body table-card">
                        <div class="table-responsive">
                            <table class="table mb-0">
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
                                           class="w-9 h-9 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-download text-lg leading-none"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="card-body">
                            <div class="grid grid-cols-12 gap-6">
                                <div class="col-span-12 md:col-span-12">
                                    <div class="mb-3 flex flex-col items-center bg-yellow-100 p-4">
                                        <!-- SVG Icon -->
                                        <span class="ti ti-mood-empty text-blue-400 block text-[50px]"></span>
                                        <!-- Warning Message -->
                                        <span class="font-bold">This ticket has no attached documents.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hidden tab-pane" id="ticketNotes">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 md:col-span-12">
                        <div class="card">
                            <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Notes</h5>
</div>
                            <?php if($ticket->safeNotes()->isNotEmpty()): ?>
                            <div class="card-body">
                                <div class="grid grid-cols-12 gap-6">
                                <?php $__currentLoopData = $ticket->safeNotes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-span-12 md:col-span-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-4"><?php echo e($note->note); ?></h6>
                                            <span class="absolute left-0 bottom-0-0 bg-info-950 px-2 py-1 text-white rounded"> <span class="ti ti-clock"></span> <?php echo e(\Carbon\Carbon::parse($note->created_at)->diffForHumans()); ?></span>
                                            <span class="absolute right-0 bottom-0-0 bg-success px-2 py-1 text-white rounded"> <span class="ti ti-user"></span> <?php echo e($note->user->name ?? $ticket->name); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                            </div>
                            <?php else: ?>
                            <div class="card-body">
                                <div class="grid grid-cols-12 gap-6">
                                    <div class="col-span-12 md:col-span-12">
                                        <div class="mb-3 flex flex-col items-center bg-yellow-100 p-4">
                                            <!-- SVG Icon -->
                                            <span class="ti ti-mood-empty text-blue-400 block text-[50px]"></span>
                                            <!-- Warning Message -->
                                            <span class="font-bold">This ticket has no notes.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
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
                                    <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->processor_name); ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Processor Email</label>
                                    <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->processor_email); ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Processor Phone</label>
                                    <input type="text" class="form-control-plaintext" value="<?php echo e($ticket->processor_ph_number); ?>" readonly="readonly" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/ol@v10.2.1/dist/ol.js"></script>
    <script>
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
            let address = '<?php echo e($ticket->court_address); ?>';
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.2.1/ol.css">
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

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\driver\tickets\show.blade.php ENDPATH**/ ?>