@extends('layout.master')
@section('content')
    <div class="col-span-12">
        <form action="{{ route(Auth::user()->portalRoutePrefix().'.managers.update', $manager->id) }}" method="POST">
            @csrf
            @method('PUT')
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
                        <li class="group">
                            <a
                                href="javascript:void(0);"
                                data-pc-toggle="tab"
                                data-pc-target="companiesTab"
                                class="inline-flex items-center mr-6 py-4 transition-all duration-300 ease-linear border-t-2 border-b-2 border-transparent group-[.active]:text-primary-500 group-[.active]:border-b-primary-500 hover:text-primary-500 active:text-primary-500"
                            >
                                <i class="ti ti-building ltr:mr-2 rtl:ml-2 text-lg leading-none"></i>
                                Manager Companies
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
                                            {{ __("Update your account's profile information and email address.") }}
                                        </span>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="name">Name</label>
                                                <input type="text"  name="name" id="name" class="form-control" value="{{ old('name', $manager->user->name) }}" required autofocus />
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback text-danger">
                                                            <strong>{{ $errors->first('name') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="email">Email</label>
                                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $manager->user->email) }}" required/>
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback text-danger">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="password">Password</label>
                                                <input type="password" name="password" id="password" class="form-control"/>
                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback text-danger">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="role">Access Role</label>
                                                <select name="role" id="role" class="form-control" required>
                                                    @foreach($roleOptions as $value => $label)
                                                        <option value="{{ $value }}" {{ old('role', $manager->user->getRoleNames()->first()) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('role'))
                                                    <span class="invalid-feedback text-danger">
                                                        <strong>{{ $errors->first('role') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="phone">Phone</label>
                                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $manager->user->phone) }}" />
                                                @if ($errors->has('phone'))
                                                    <span class="invalid-feedback text-danger">
                                                            <strong>{{ $errors->first('phone') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="dateOfBirth">Date of birth ( optional )</label>
                                                <div class="input-group date">
                                                    <input type="text" name="dob" class="form-control" placeholder="Select date"
                                                           id="dateOfBirth" value="{{ old('dob', $manager->user->dob) }}"/>
                                                    <span class="input-group-text">
                                                          <i class="feather icon-calendar"></i>
                                                        </span>
                                                </div>
                                                @if ($errors->has('dob'))
                                                    <span class="invalid-feedback text-danger">
                                                            <strong>{{ $errors->first('dob') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="address">Address</label>
                                                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $manager->user->address) }}" />
                                                @if ($errors->has('address'))
                                                    <span class="invalid-feedback text-danger">
                                                            <strong>{{ $errors->first('address') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="city">City</label>
                                                <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $manager->user->city) }}" />
                                                @if ($errors->has('city'))
                                                    <span class="invalid-feedback text-danger">
                                                            <strong>{{ $errors->first('city') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="state">State</label>
                                                <select class="form-control" name="state" data-trigger name="state" id="state">
                                                    <option value="AL" {{ old('state', $manager->user->state) == 'AL' ? 'selected' : '' }}>Alabama</option>
                                                    <option value="AK" {{ old('state', $manager->user->state) == 'AK' ? 'selected' : '' }}>Alaska</option>
                                                    <option value="AZ" {{ old('state', $manager->user->state) == 'AZ' ? 'selected' : '' }}>Arizona</option>
                                                    <option value="AR" {{ old('state', $manager->user->state) == 'AR' ? 'selected' : '' }}>Arkansas</option>
                                                    <option value="CA" {{ old('state', $manager->user->state) == 'CA' ? 'selected' : '' }}>California</option>
                                                    <option value="CO" {{ old('state', $manager->user->state) == 'CO' ? 'selected' : '' }}>Colorado</option>
                                                    <option value="CT" {{ old('state', $manager->user->state) == 'CT' ? 'selected' : '' }}>Connecticut</option>
                                                    <option value="DE" {{ old('state', $manager->user->state) == 'DE' ? 'selected' : '' }}>Delaware</option>
                                                    <option value="DC" {{ old('state', $manager->user->state) == 'DC' ? 'selected' : '' }}>District Of Columbia</option>
                                                    <option value="FL" {{ old('state', $manager->user->state) == 'FL' ? 'selected' : '' }}>Florida</option>
                                                    <option value="GA" {{ old('state', $manager->user->state) == 'GA' ? 'selected' : '' }}>Georgia</option>
                                                    <option value="HI" {{ old('state', $manager->user->state) == 'HI' ? 'selected' : '' }}>Hawaii</option>
                                                    <option value="ID" {{ old('state', $manager->user->state) == 'ID' ? 'selected' : '' }}>Idaho</option>
                                                    <option value="IL" {{ old('state', $manager->user->state) == 'IL' ? 'selected' : '' }}>Illinois</option>
                                                    <option value="IN" {{ old('state', $manager->user->state) == 'IN' ? 'selected' : '' }}>Indiana</option>
                                                    <option value="IA" {{ old('state', $manager->user->state) == 'IA' ? 'selected' : '' }}>Iowa</option>
                                                    <option value="KS" {{ old('state', $manager->user->state) == 'KS' ? 'selected' : '' }}>Kansas</option>
                                                    <option value="KY" {{ old('state', $manager->user->state) == 'KY' ? 'selected' : '' }}>Kentucky</option>
                                                    <option value="LA" {{ old('state', $manager->user->state) == 'LA' ? 'selected' : '' }}>Louisiana</option>
                                                    <option value="ME" {{ old('state', $manager->user->state) == 'ME' ? 'selected' : '' }}>Maine</option>
                                                    <option value="MD" {{ old('state', $manager->user->state) == 'MD' ? 'selected' : '' }}>Maryland</option>
                                                    <option value="MA" {{ old('state', $manager->user->state) == 'MA' ? 'selected' : '' }}>Massachusetts</option>
                                                    <option value="MI" {{ old('state', $manager->user->state) == 'MI' ? 'selected' : '' }}>Michigan</option>
                                                    <option value="MN" {{ old('state', $manager->user->state) == 'MN' ? 'selected' : '' }}>Minnesota</option>
                                                    <option value="MS" {{ old('state', $manager->user->state) == 'MS' ? 'selected' : '' }}>Mississippi</option>
                                                    <option value="MO" {{ old('state', $manager->user->state) == 'MO' ? 'selected' : '' }}>Missouri</option>
                                                    <option value="MT" {{ old('state', $manager->user->state) == 'MT' ? 'selected' : '' }}>Montana</option>
                                                    <option value="NE" {{ old('state', $manager->user->state) == 'NE' ? 'selected' : '' }}>Nebraska</option>
                                                    <option value="NV" {{ old('state', $manager->user->state) == 'NV' ? 'selected' : '' }}>Nevada</option>
                                                    <option value="NH" {{ old('state', $manager->user->state) == 'NH' ? 'selected' : '' }}>New Hampshire</option>
                                                    <option value="NJ" {{ old('state', $manager->user->state) == 'NJ' ? 'selected' : '' }}>New Jersey</option>
                                                    <option value="NM" {{ old('state', $manager->user->state) == 'NM' ? 'selected' : '' }}>New Mexico</option>
                                                    <option value="NY" {{ old('state', $manager->user->state) == 'NY' ? 'selected' : '' }}>New York</option>
                                                    <option value="NC" {{ old('state', $manager->user->state) == 'NC' ? 'selected' : '' }}>North Carolina</option>
                                                    <option value="ND" {{ old('state', $manager->user->state) == 'ND' ? 'selected' : '' }}>North Dakota</option>
                                                    <option value="OH" {{ old('state', $manager->user->state) == 'OH' ? 'selected' : '' }}>Ohio</option>
                                                    <option value="OK" {{ old('state', $manager->user->state) == 'OK' ? 'selected' : '' }}>Oklahoma</option>
                                                    <option value="OR" {{ old('state', $manager->user->state) == 'OR' ? 'selected' : '' }}>Oregon</option>
                                                    <option value="PA" {{ old('state', $manager->user->state) == 'PA' ? 'selected' : '' }}>Pennsylvania</option>
                                                    <option value="RI" {{ old('state', $manager->user->state) == 'RI' ? 'selected' : '' }}>Rhode Island</option>
                                                    <option value="SC" {{ old('state', $manager->user->state) == 'SC' ? 'selected' : '' }}>South Carolina</option>
                                                    <option value="SD" {{ old('state', $manager->user->state) == 'SD' ? 'selected' : '' }}>South Dakota</option>
                                                    <option value="TN" {{ old('state', $manager->user->state) == 'TN' ? 'selected' : '' }}>Tennessee</option>
                                                    <option value="TX" {{ old('state', $manager->user->state) == 'TX' ? 'selected' : '' }}>Texas</option>
                                                    <option value="UT" {{ old('state', $manager->user->state) == 'UT' ? 'selected' : '' }}>Utah</option>
                                                    <option value="VT" {{ old('state', $manager->user->state) == 'VT' ? 'selected' : '' }}>Vermont</option>
                                                    <option value="VA" {{ old('state', $manager->user->state) == 'VA' ? 'selected' : '' }}>Virginia</option>
                                                    <option value="WA" {{ old('state', $manager->user->state) == 'WA' ? 'selected' : '' }}>Washington</option>
                                                    <option value="WV" {{ old('state', $manager->user->state) == 'WV' ? 'selected' : '' }}>West Virginia</option>
                                                    <option value="WI" {{ old('state', $manager->user->state) == 'WI' ? 'selected' : '' }}>Wisconsin</option>
                                                    <option value="WY" {{ old('state', $manager->user->state) == 'WY' ? 'selected' : '' }}>Wyoming</option>
                                                </select>
                                                @if ($errors->has('state'))
                                                    <span class="invalid-feedback text-danger">
                                                            <strong>{{ $errors->first('state') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="zip">Zip code</label>
                                                <input type="text" name="zip" id="zip" class="form-control" value="{{ old('zip', $manager->user->zip) }}"/>
                                                @if ($errors->has('zip'))
                                                    <span class="invalid-feedback text-danger">
                                                            <strong>{{ $errors->first('zip') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="timezone">Timezone</label>
                                                <select name="timezone" id="timezone" class="form-control">
                                                    <option value="">Select a Timezone</option>
                                                    <option value="UTC-08:00" {{ old('timezone', $manager->user->timezone) == 'UTC-08:00' ? 'selected' : '' }}>(UTC-08:00) Pacific Time (US & Canada)</option>
                                                    <option value="UTC-07:00" {{ old('timezone', $manager->user->timezone) == 'UTC-07:00' ? 'selected' : '' }}>(UTC-07:00) Mountain Time (US & Canada)</option>
                                                    <option value="UTC-06:00" {{ old('timezone', $manager->user->timezone) == 'UTC-06:00' ? 'selected' : '' }}>(UTC-06:00) Central Time (US & Canada)</option>
                                                    <option value="UTC-05:00" {{ old('timezone', $manager->user->timezone) == 'UTC-05:00' ? 'selected' : '' }}>(UTC-05:00) Eastern Time (US & Canada)</option>
                                                </select>
                                                @if ($errors->has('timezone'))
                                                    <span class="invalid-feedback text-danger">
                                                        <strong>{{ $errors->first('timezone') }}</strong>
                                                    </span>
                                                @endif
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
                                        {{ __('Customize notification settings of what you want to get notified about.') }}
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
                                                       name="notification_email" role="switch" {{ old('notification_email', $manager->user->notification_email) ? 'checked' : '' }}/>
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
                                                       name="notification_sms" role="switch" {{ old('notification_sms', $manager->user->notification_sms) ? 'checked' : '' }}/>
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
                                                       name="notification_push" role="switch" {{ old('notification_push', $manager->user->notification_push) ? 'checked' : '' }}/>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden tab-pane" id="companiesTab">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 lg:col-span-12">
                            <div class="card">
                                <div class="card-header">
    <h5 class="text-primary text-[28px] font-bold">Company Access</h5>
                                    <span class="text-muted text-sm">
                                        {{ __("Companies that this company admin can access data for.") }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6 items-center">
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold">Company</label>
                                                <select
                                                    class="form-control"
                                                    name="company_id"
                                                    id="companies"
                                                ></select>
                                                @if ($errors->has('company_id'))
                                                    <span class="invalid-feedback text-danger">
                                                    <strong>{{ $errors->first('company_id') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-2">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="write_access">Write Access ?</label>
                                                <div class="form-check form-switch">
                                                    <input
                                                        id="writeAccess"
                                                        class="form-check-input h4 position-relative m-0"
                                                        type="checkbox"
                                                        role="switch"
                                                        value="Yes"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-2">
                                            <div class="mb-3 mt-5">
                                                <button class="btn btn-primary px-6" id="addCompanyToManager">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-1" id="companyManagersList">
                        @if (old('managerCompany_id'))
                            @foreach(old('managerCompany_id') as $index => $managerCompany)
                                <div class="col-span-12 lg:col-span-6 xl:col-span-4 company-manager-item">
                                    <div class="card">
                                        <div class="card-body flex justify-between align-middle items-center">
                                            <div class="font-bold">
                                                <h5 class="mb-3 block">{{ old('managerCompany_name')[$index] }}</h5>
                                                <span class="ti {{ old('managerCompany_name')[$index] === 'Yes' ?  'text-success ti-check' : 'text-danger ti-x'}} text-right text-[20px]"></span> Write Access
                                            </div>
                                            <div class="text-right">
                                                <a href="#" id="removeCompanyManagerBtn" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <input type="hidden" name="managerCompany_id[]" value="{{ old('managerCompany_id')[$index] }}">
                                        <input type="hidden" name="managerCompany_name[]" value="{{ old('managerCompany_name')[$index] }}">
                                        <input type="hidden" name="managerCompany_isWrite[]" value="{{ old('managerCompany_isWrite')[$index] }}">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @foreach($manager->companies as $managerCompany)
                                <div class="col-span-12 lg:col-span-6 xl:col-span-4 company-manager-item">
                                    <div class="card">
                                        <div class="card-body flex justify-between align-middle items-center">
                                            <div class="font-bold">
                                                <h5 class="mb-3 block">{{ $managerCompany->name }}</h5>
                                                <span class="ti {{ $managerCompany->pivot->is_write_access ?  'text-success ti-check' : 'text-danger ti-x'}} text-right text-[20px]"></span> Write Access
                                            </div>
                                            <div class="text-right">
                                                <a href="#" id="removeCompanyManagerBtn" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                                    <i class="ti ti-trash text-xl leading-none"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <input type="hidden" name="managerCompany_id[]" value="{{ $managerCompany->id }}">
                                        <input type="hidden" name="managerCompany_name[]" value="{{ $managerCompany->name }}">
                                        <input type="hidden" name="managerCompany_isWrite[]" value="{{ $managerCompany->pivot->is_write_access ? 'Yes' : 'No' }}">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-span-12 text-right">
                    <button type="reset" class="btn btn-outline-secondary mx-1">Cancel</button>
                    <button type="submit" class="btn btn-primary mx-1">Update Company Admin</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('post-scripts')
    <script src="{{ asset('js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/plugins/choices.min.js') }}"></script>
    <script>
        flatpickr(document.querySelector('#dateOfBirth'));

        var companiesChoices = new Choices('#companies', {
            placeholder: true,
            placeholderValue: 'Company Name',
            maxItemCount: 5,
            shouldSort: false, // Optional: keeps the order of items as provided
        })
        companiesChoices.setChoices(function () {
            return fetch('{{ route('api.company.index') }}')
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    return [{
                        value: '',
                        label: 'Select an option',
                        disabled: true,
                        selected: {{ !old('company_id') ? 'true' : 'false' }} },
                        ...data.map(function (company) {
                        return {
                            value: company.id,
                            label: company.name,
                            selected: Number('{{ old('company_id') }}') === Number(company.id)
                        };
                    })];
                });
        });

        document.addEventListener('click', function (e) {
            let addCompanyToManagerBtn = e.target.closest('#addCompanyToManager');
            if (addCompanyToManagerBtn) {
                e.preventDefault();
                let selectedCompany = companiesChoices.getValue();
                const writeAccessCheckbox = document.getElementById("writeAccess");
                const hasWriteAccess = writeAccessCheckbox.checked ? "Yes" : "No";
                let icon = writeAccessCheckbox.checked ? 'text-success ti-check' : 'text-danger ti-x';
                if (!selectedCompany || selectedCompany.value === "") {
                    return Toast.fire({
                        icon: 'info',
                        title: 'Please select a company from company list.'
                    });
                }

                let companyName = selectedCompany.label;
                let companyId = selectedCompany.value;

                // Check if the company is already added
                const existingCompanies = document.querySelectorAll(
                    '#companyManagersList input[name="managerCompany_id[]"]'
                );
                for (let existingCompany of existingCompanies) {
                    if (Number(existingCompany.value) === Number(companyId)) {
                        return Toast.fire({
                            icon: 'info',
                            title: 'This company is already added.'
                        });
                    }
                }

                // Template to add
                const template = `
                    <div class="col-span-12 lg:col-span-6 xl:col-span-4 company-manager-item">
                        <div class="card">
                            <div class="card-body flex justify-between align-middle items-center">
                                <div class="font-bold">
                                    <h5 class="mb-3 block">${companyName}</h5>
                                    <span class="ti ${icon} text-right text-[20px]"></span> Write Access
                                </div>
                                <div class="text-right">
                                    <a href="#" id="removeCompanyManagerBtn" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary remove-entry">
                                        <i class="ti ti-trash text-xl leading-none"></i>
                                    </a>
                                </div>
                            </div>
                            <input type="hidden" name="managerCompany_id[]" value="${companyId}">
                            <input type="hidden" name="managerCompany_name[]" value="${companyName}">
                            <input type="hidden" name="managerCompany_isWrite[]" value="${hasWriteAccess}">
                        </div>
                    </div>
                `;

                // Append the template to the container (adjust the container selector as needed)
                const container = document.querySelector("#companyManagersList");
                container.insertAdjacentHTML("beforeend", template);
            }

            let removeCompanyManagerBtn = e.target.closest('#removeCompanyManagerBtn');
            if(removeCompanyManagerBtn) {
                e.preventDefault();
                e.target.closest('.company-manager-item').remove();
            }
        });
    </script>
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.2.1/ol.css">
    <link rel="stylesheet" href="{{ asset('css/plugins/flatpickr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/plugins/choices.min.css') }}" />
@endsection
