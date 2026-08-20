@extends('layout.master')
@section('content')
    <div class="col-span-12">
        <div class="card">
            <div class="card-body !py-0">
                <ul class="flex flex-wrap w-full font-medium text-center nav-tabs items-center">
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
                    <li class="group ml-auto">
                        <a
                            href="{{ route(auth()->user()->portalRoutePrefix().'.tickets.edit', $ticket->id) }}"
                            data-pc-toggle="tab"
                            class="inline-flex btn bg-primary text-white px-3 py-2 items-center mr-6 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-white group-[.active]:border-b-white hover:text-white active:text-white"
                        >
                            <i class="ti ti-pencil ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                            Edit Ticket
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
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->name }}" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Driver Email</label>
                                            <input type="email" class="form-control-plaintext" value="{{ $ticket->user_email }}" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Driver Phone</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->phone }}" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Company</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->company?->name }}" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Address</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->address }}" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">City</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->city }}" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">State</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->state }}" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Zipcode</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->zip }}" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Date Received</label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control-plaintext" placeholder="Select date"
                                                       id="pc-datepicker-2" value="{{ \Carbon\Carbon::parse($ticket->date_issued)->toDateString() }}" readonly="readonly"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-12">
                                        <label class="form-label mt-3">Indicator</label>
                                        <div class="mb-3 grid grid-cols-12 gap-6">
                                            <div class="col-span-12 lg:col-span-2">
                                                <div class="border card p-3 {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_RECEIVED ? 'bg-success text-white' : '' }}">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="customCheckdefhor1" readonly="readonly" disabled {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_RECEIVED ? 'checked' : '' }}/>
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
                                                <div class="border card p-3 {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_SENT_TO_ATTORNEY ? 'bg-warning text-black' : '' }}">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="customCheckdefhor2" readonly="readonly" disabled {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_SENT_TO_ATTORNEY ? 'checked' : '' }}/>
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
                                                <div class="border card p-3 {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_CANCELLED ? 'bg-danger text-white' : '' }}">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="customCheckdefhor3" readonly="readonly" disabled {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_CANCELLED ? 'checked' : '' }}/>
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
                                                <div class="border card p-3 {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_DISPOSED ? 'bg-primary text-white' : '' }}">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="customCheckdefhor4" readonly="readonly" disabled {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_DISPOSED ? 'checked' : '' }}/>
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
                                                <div class="border card p-3 {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_CONTINUED ? 'bg-info text-white' : '' }}">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="continuedIndicator" readonly="readonly" disabled {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_CONTINUED ? 'checked' : '' }}/>
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
                                                <div class="border card p-3 {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_ASSIGNED_TO_ATTORNEY ? 'bg-secondary text-white' : '' }}">
                                                    <div class="form-check">
                                                        <input type="radio" name="radio3" class="form-check-input"
                                                               id="customCheckdefhor4" readonly="readonly" disabled {{ $ticket->indicator === \App\Models\Ticket::INDICATOR_ASSIGNED_TO_ATTORNEY ? 'checked' : '' }}/>
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
                                                               role="switch" value="Yes" {{ $ticket->class_commercial === 'Yes'? 'checked' : '' }} disabled/>
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
                                                               role="switch" value="Yes" {{ $ticket->road_side_inspection === 'Yes'? 'checked' : '' }} disabled />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Vehicle License Number</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->vehicle_lic_no }}" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Violation</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->violation?->violation }}" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Citation Number</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->citation_no }}" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3 flex flex-row justify-center">
                                            <div class="form-check mb-2 inline-block pr-4">
                                                <span class="ti {{ $ticket->isDverDataq()['DATAQ'] ? 'ti-square-check' : 'ti-square' }} mt-2 text-[20px] text-primary"></span>
                                                <label class="form-check-label" for="flexCheckChecked">DataQ</label>
                                            </div>
                                            <div class="form-check mb-2 inline-block">
                                                <span class="ti {{ $ticket->isDverDataq()['DVER'] ? 'ti-square-check' : 'ti-square' }} mt-2 text-[20px] text-primary"></span>
                                                <label class="form-check-label" for="flexCheckChecked">DVER</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Ticket type</label>
                                            <textarea class="form-control-plaintext" readonly="readonly">{{ $ticket->ticket_type }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Beginning Fine amount</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->beginning_fine_amount }}" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Final Fine amount</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->final_fine_amount }}" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Total DVER Points</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->total_dver_points__c }}" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Total DVER Points Removed</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->total_dver_points_removed__c }}" readonly="readonly" />
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
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->court_name }}" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Court Date</label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control-plaintext" placeholder="Select date"
                                                       id="pc-datepicker-2" value="{{ $ticket->court_date ? \Carbon\Carbon::parse($ticket->court_date)->toDateString() : '' }}" readonly="readonly"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <div class="mb-3">
                                            <label class="form-label text-primary text-[18px] font-bold">Court Address</label>
                                            <input type="text" class="form-control-plaintext" value="{{ $ticket->court_address }}" readonly="readonly" />
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
                                    <input type="text" class="form-control-plaintext" value="{{ $ticket->attorney?->user->name }}" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Attorney Address</label>
                                    <input type="text" class="form-control-plaintext" value="{{ $ticket->attorney?->user->address }}" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Attorney Phone</label>
                                    <input type="text" class="form-control-plaintext" value="{{ $ticket->attorney?->user->phone }}" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Office Hours</label>
                                    <input type="text" class="form-control-plaintext" value="{{ $ticket->attorney?->office_hours_start ? \Carbon\Carbon::parse($ticket->attorney?->office_hours_start)->toTimeString() : '' }} - {{ $ticket->attorney?->office_hours_end ? \Carbon\Carbon::parse($ticket->attorney?->office_hours_end)->toTimeString() : '' }}" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label class="form-label mt-3">Attorney Response</label>
                                <div class="mb-3 grid grid-cols-12 gap-6">
                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="border card p-3 {{ $ticket->attorney_response === \App\Models\Ticket::ATTORENY_RESPONSE_ACCEPTED ? 'bg-success text-white' : '' }}">
                                            <div class="form-check">
                                                <input type="radio" name="attorney_response" class="form-check-input"
                                                       id="acceptedAttorneyResponse" {{ $ticket->attorney_response === \App\Models\Ticket::ATTORENY_RESPONSE_ACCEPTED ? 'checked' : '' }} value="{{ \App\Models\Ticket::ATTORENY_RESPONSE_ACCEPTED }}" disabled/>
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
                                        <div class="border card p-3 {{ $ticket->attorney_response === \App\Models\Ticket::ATTORENY_RESPONSE_REJECTED ? 'bg-success text-white' : '' }}">
                                            <div class="form-check">
                                                <input type="radio" name="attorney_response" class="form-check-input"
                                                       id="rejectedAttorneyResponse" {{ $ticket->attorney_response === \App\Models\Ticket::ATTORENY_RESPONSE_REJECTED ? 'checked' : '' }} value="{{ \App\Models\Ticket::ATTORENY_RESPONSE_REJECTED }}" disabled/>
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
                                    <textarea class="form-control-plaintext" rows="2" readonly="readonly">{{ $ticket->processor_notes_to_attorney }}</textarea>
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
                    @if (count($ticket->attachments))
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
                                @foreach($ticket->attachments as $attachment)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <h5 class="mb-0">{{ $attachment->filename }}</h5>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <a href="#"
                                           data-file="{{ url('/ticket-attachments/'.$attachment->id.'/preview') }}"
                                           data-attachment-id="{{ $attachment->id }}"
                                           data-filename="{{ $attachment->filename }}"
                                           data-filetype="{{ $attachment->preview_type }}"
                                           class="w-9 h-9 rounded-xl inline-flex items-center justify-center btn-link-secondary preview-link">
                                            <i class="ti ti-eye text-warning text-lg leading-none"></i>
                                        </a>
                                        <a href="{{ $attachment->url }}"
                                           class="w-9 h-9 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-download text-lg leading-none"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
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
                    @endif
                </div>
            </div>
            <div class="hidden tab-pane" id="ticketNotes">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 md:col-span-12">
                        <div class="card">
                            <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Notes</h5>
</div>
                            @if ($ticket->safeNotes()->isNotEmpty())
                            <div class="card-body">
                                <div class="grid grid-cols-12 gap-6">
                                @foreach($ticket->safeNotes() as $note)
                                <div class="col-span-12 md:col-span-6">
                                    <div class="card">
                                        <div class="card-body">
                                            @if(!$note->is_public)
                                                <div class="absolute right-0 top-0 bg-secondary rounded px-2 py-1 text-white">
                                                    Private
                                                    <svg class="pc-icon h-[1.25em] w-[2em] inline">
                                                        <use xlink:href="#custom-lock-outline"></use>
                                                    </svg>
                                                </div>
                                            @endif
                                        <h6 class="mb-4">{{ $note->note }}</h6>
                                            <span class="absolute left-0 bottom-0-0 bg-info-950 px-2 py-1 text-white rounded"> <span class="ti ti-clock"></span> {{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}</span>
                                            <span class="absolute right-0 bottom-0-0 bg-success px-2 py-1 text-white rounded"> <span class="ti ti-user"></span> {{ $note->user->name ?? $ticket->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                </div>

                            </div>
                            @else
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
                            @endif
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
                                    <input type="text" class="form-control-plaintext" value="{{ $ticket->processor_name }}" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Processor Email</label>
                                    <input type="text" class="form-control-plaintext" value="{{ $ticket->processor_email }}" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <div class="mb-3">
                                    <label class="form-label text-primary text-[18px] font-bold">Processor Phone</label>
                                    <input type="text" class="form-control-plaintext" value="{{ $ticket->processor_ph_number }}" readonly="readonly" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="exampleModalLive" class="modal fade hidden">
        <div class="modal-dialog modal-fullscreen tc-preview-dialog">
            <div class="modal-content tc-preview-content">
                <!-- Modal Header -->
                <div class="modal-header shrink-0">
                    <h5 class="modal-title">File Preview</h5>
                    <button data-pc-modal-dismiss="#exampleModalLive" class="text-lg flex items-center justify-center rounded w-7 h-7 text-secondary-500 hover:bg-danger-500/10 hover:text-danger-500">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <!-- Modal Body -->
                <div id="modalBody" class="modal-body tc-preview-body p-2 sm:p-4">
                    <!-- Preview content will be loaded here -->
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer p-3 sm:p-4 border-t shrink-0">
                    <button type="button" class="btn btn-secondary px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600" data-pc-modal-dismiss="#exampleModalLive">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('post-scripts')
    <script src="https://cdn.jsdelivr.net/npm/ol@v10.2.1/dist/ol.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
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
            let address = '{{ $ticket->court_address }}';
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
        document.addEventListener('click', function (e) {
            let previewBtn = e.target.closest('.preview-link');
            const modal = document.getElementById('exampleModalLive');
            const modalBody = document.getElementById('modalBody');
            if (previewBtn) {
                e.preventDefault();
                const attachmentId = previewBtn.getAttribute('data-attachment-id');
                const filePath = attachmentId
                    ? ('/ticket-attachments/' + attachmentId + '/preview')
                    : (previewBtn.getAttribute('data-file') || '').replace('/download', '/preview');
                const fileName = previewBtn.getAttribute('data-filename') || filePath;
                const declaredType = (previewBtn.getAttribute('data-filetype') || '').toLowerCase();

                modalBody.innerHTML = '<p class="text-gray-500 text-center py-8">Loading preview...</p>';
                modal.classList.add('animate');
                modal.classList.remove('hidden');
                modal.classList.add('show');

                openAttachmentPreview(filePath, fileName, declaredType, modalBody);
            }

            let closePreviewBtn = e.target.closest('[data-pc-modal-dismiss="#exampleModalLive"]');
            if (closePreviewBtn) {
                e.preventDefault();
                modal.classList.add('hidden');
            }

            let modalItem = e.target.closest('#exampleModalLive');
            if (modalItem) {
                modal.classList.add('hidden');
            }
        });

        function extensionFromName(name) {
            const clean = (name || '').split('?')[0];
            const ext = clean.includes('.') ? clean.split('.').pop().toLowerCase() : '';
            if (['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'doc', 'docx'].includes(ext)) {
                return ext === 'jpeg' ? 'jpg' : ext;
            }
            return '';
        }

        function sniffBytes(bytes) {
            if (!bytes || bytes.length < 4) return '';
            if (bytes[0] === 0x25 && bytes[1] === 0x50 && bytes[2] === 0x44 && bytes[3] === 0x46) return 'pdf';
            if (bytes[0] === 0xFF && bytes[1] === 0xD8 && bytes[2] === 0xFF) return 'jpg';
            if (bytes[0] === 0x89 && bytes[1] === 0x50 && bytes[2] === 0x4E && bytes[3] === 0x47) return 'png';
            if (bytes[0] === 0x47 && bytes[1] === 0x49 && bytes[2] === 0x46) return 'gif';
            if (bytes[0] === 0x52 && bytes[1] === 0x49 && bytes[2] === 0x46 && bytes[3] === 0x46) return 'webp';
            return '';
        }

        function mimeForType(type) {
            if (type === 'pdf') return 'application/pdf';
            if (type === 'jpg' || type === 'jpeg') return 'image/jpeg';
            if (type === 'png') return 'image/png';
            if (type === 'gif') return 'image/gif';
            if (type === 'webp') return 'image/webp';
            return 'application/octet-stream';
        }

        async function openAttachmentPreview(url, fileName, declaredType, container) {
            if (!url || url.includes('digitaloceanspaces.com') || url.includes('amazonaws.com')) {
                container.innerHTML = `<p class="text-red-500 text-center py-8">Preview URL is invalid. Please download the file instead.</p>`;
                return;
            }
            url = url.replace('/download', '/preview');

            try {
                // Same-origin only (no S3 redirect) — used to force correct MIME so images render full-size.
                const res = await fetch(url, { credentials: 'same-origin', redirect: 'manual' });
                if (!res.ok || res.type === 'opaqueredirect') {
                    throw new Error('Preview stream unavailable');
                }

                const buffer = await res.arrayBuffer();
                const header = new Uint8Array(buffer.slice(0, 16));
                let fileType = sniffBytes(header)
                    || extensionFromName(fileName)
                    || (declaredType === 'jpeg' ? 'jpg' : (declaredType || ''))
                    || 'unknown';

                const mime = mimeForType(fileType);
                const objectUrl = URL.createObjectURL(new Blob([buffer], { type: mime }));

                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileType)) {
                    container.innerHTML = `<div class="tc-preview-wrap"><img src="${objectUrl}" alt="Preview" class="tc-preview-image"></div>`;
                    return;
                }

                if (fileType === 'pdf') {
                    container.innerHTML = `<iframe src="${objectUrl}#toolbar=1&navpanes=0&zoom=page-width" class="tc-preview-frame" title="PDF preview"></iframe>`;
                    return;
                }

                if (['doc', 'docx'].includes(fileType)) {
                    URL.revokeObjectURL(objectUrl);
                    container.innerHTML = `<p class="text-gray-700 text-center py-8">Word documents cannot be previewed securely. Please download the file instead.</p>`;
                    return;
                }

                // Unknown: try as image first with jpeg MIME, then PDF frame.
                const imageUrl = URL.createObjectURL(new Blob([buffer], { type: 'image/jpeg' }));
                container.innerHTML = `<div class="tc-preview-wrap">
                    <img src="${imageUrl}" alt="Preview" class="tc-preview-image"
                         onerror="this.parentElement.innerHTML='<iframe src=\\'${objectUrl}#zoom=page-width\\' class=\\'tc-preview-frame\\' title=\\'File preview\\'></iframe>';">
                </div>`;
            } catch (err) {
                console.error('Preview failed:', err);
                // Last resort: direct same-origin iframe (still no S3).
                container.innerHTML = `<iframe src="${url}" class="tc-preview-frame" title="File preview"></iframe>`;
            }
        }
    </script>
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.2.1/ol.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.6.172/web/pdf_viewer.min.css">
    <style>
        .marker {
            width: 24px;
            height: 24px;
            background-image: url('https://cdn-icons-png.flaticon.com/512/684/684908.png');
            background-size: cover;
            transform: translate(-50%, -100%);
        }

        #exampleModalLive.modal,
        #exampleModalLive .tc-preview-dialog {
            width: 100vw;
            max-width: 100vw;
            height: 100dvh;
            margin: 0;
            padding: 0;
        }

        #exampleModalLive .tc-preview-content {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100dvh;
            max-height: 100dvh;
        }

        #exampleModalLive .tc-preview-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow: auto;
            background: #f1f5f9;
        }

        #exampleModalLive .tc-preview-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: calc(100dvh - 9rem);
            padding: 0.75rem;
        }

        #exampleModalLive .tc-preview-image {
            display: block;
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: calc(100dvh - 10rem);
            object-fit: contain;
            background: #fff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.15);
        }

        #exampleModalLive .tc-preview-frame {
            display: block;
            width: 100%;
            height: calc(100dvh - 9rem);
            min-height: 60vh;
            border: 0;
            background: #fff;
        }

        @media (max-width: 640px) {
            #exampleModalLive .tc-preview-wrap {
                min-height: calc(100dvh - 8rem);
                padding: 0.5rem;
            }
            #exampleModalLive .tc-preview-image {
                max-height: calc(100dvh - 8.5rem);
            }
            #exampleModalLive .tc-preview-frame {
                height: calc(100dvh - 8rem);
            }
        }
    </style>
@endsection
