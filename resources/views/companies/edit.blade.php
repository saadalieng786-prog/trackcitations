@extends('layout.master')
@section('content')
    <div class="col-span-12">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 m-0">Edit Company</h1>
                <div class="text-xs text-slate-500 mt-1">
                    <a href="{{ route(auth()->user()->portalRoutePrefix().'.companies.index') }}" class="text-slate-500 hover:text-indigo-600">Companies</a>
                    <span class="mx-1.5 text-slate-300">/</span>
                    <a href="{{ route(auth()->user()->portalRoutePrefix().'.companies.show', $company->id) }}" class="text-slate-500 hover:text-indigo-600">{{ $company->name }}</a>
                    <span class="mx-1.5 text-slate-300">/</span>
                    <span class="font-medium text-slate-700">Edit</span>
                </div>
            </div>
            <a href="{{ route(auth()->user()->portalRoutePrefix().'.companies.show', $company->id) }}" class="btn btn-outline-secondary btn-sm">
                View Company Overview
            </a>
        </div>
        <form action="{{ route(auth()->user()->portalRoutePrefix().'.companies.update', $company->id) }}" method="POST">
            @csrf
            @method('PUT')
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
                                Drivers ({{ number_format($companyDriversCount) }})
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
                                Tickets ({{ number_format($companyTicketsCount) }})
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
                                            {{ __("company's information and citation tracker details.") }}
                                        </span>
                                </div>
                                <div class="card-body">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="name">Company Name</label>
                                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $company->name) }}" />
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback text-danger">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="ct_email">Email</label>
                                                <input type="email" name="ct_email" id="ct_email" class="form-control" value="{{ old('ct_email', $company->ct_email) }}" />
                                                @if ($errors->has('ct_email'))
                                                    <span class="invalid-feedback text-danger">
                                                        <strong>{{ $errors->first('ct_email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="ct_fname">Firstname</label>
                                                <input type="text" name="ct_fname" id="ct_fname" class="form-control" value="{{ old('ct_fname', $company->ct_fname) }}" />
                                                @if ($errors->has('ct_fname'))
                                                    <span class="invalid-feedback text-danger">
                                                        <strong>{{ $errors->first('ct_fname') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="ct_lname">Lastname</label>
                                                <input type="text" name="ct_lname" id="ct_lname" class="form-control" value="{{ old('ct_lname', $company->ct_lname) }}" />
                                                @if ($errors->has('ct_lname'))
                                                    <span class="invalid-feedback text-danger">
                                                        <strong>{{ $errors->first('ct_lname') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="dot">DOT Number</label>
                                                <input type="text" name="dot" id="dot" class="form-control" value="{{ old('dot', $company->dot) }}" />
                                                @if ($errors->has('dot'))
                                                    <span class="invalid-feedback text-danger">
                                                        <strong>{{ $errors->first('dot') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="sf_id">Salesforce ID ( Optional )</label>
                                                <input type="text" name="sf_id" id="sf_id" class="form-control" value="{{ old('sf_id', $company->sf_id) }}" />
                                                @if ($errors->has('sf_id'))
                                                    <span class="invalid-feedback text-danger">
                                                        <strong>{{ $errors->first('sf_id') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <div class="mb-3">
                                                <label class="form-label text-primary text-[18px] font-bold" for="parent_company_id">Parent Company</label>
                                                <select name="parent_company_id" id="parent_company_id" class="form-control">
                                                    <option value="">Top-level company</option>
                                                    @foreach($parentCompanyOptions as $parentCompany)
                                                        <option value="{{ $parentCompany->id }}" {{ (string) old('parent_company_id', $company->parent_company_id) === (string) $parentCompany->id ? 'selected' : '' }}>
                                                            {{ $parentCompany->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('parent_company_id'))
                                                    <span class="invalid-feedback text-danger">
                                                        <strong>{{ $errors->first('parent_company_id') }}</strong>
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
                                                    @if($company->parentCompany)
                                                        <a href="{{ route(auth()->user()->portalRoutePrefix().'.companies.show', $company->parentCompany->id) }}" class="text-primary">
                                                            {{ $company->parentCompany->name }}
                                                        </a>
                                                        <span class="mx-1 text-muted">→</span>
                                                    @endif
                                                    <span>{{ $company->name }}</span>
                                                    <span class="mx-1 text-muted">→</span>
                                                    <span>Drivers ({{ number_format($companyDriversCount) }})</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-4">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-1 text-sm text-muted">Parent Company</p>
                                                @if($company->parentCompany)
                                                    <a href="{{ route(auth()->user()->portalRoutePrefix().'.companies.show', $company->parentCompany->id) }}" class="mb-0 font-semibold text-primary">
                                                        {{ $company->parentCompany->name }}
                                                    </a>
                                                @else
                                                    <p class="mb-0 font-semibold">Top-level company</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-4">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-1 text-sm text-muted">Child Companies</p>
                                                <p class="mb-0 font-semibold">{{ $company->childCompanies->count() }}</p>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-4">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-1 text-sm text-muted">Drivers On This Company</p>
                                                <p class="mb-0 font-semibold">{{ number_format($companyDriversCount) }}</p>
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-3 font-semibold">Child Trucking Companies</p>
                                                @forelse($company->childCompanies as $childCompany)
                                                    <div class="flex items-center justify-between border-b py-2 last:border-b-0 gap-3">
                                                        <div>
                                                            <a href="{{ route(auth()->user()->portalRoutePrefix().'.companies.show', $childCompany->id) }}" class="font-medium text-primary">
                                                                {{ $childCompany->name }}
                                                            </a>
                                                            <p class="mb-0 text-xs text-muted">DOT: {{ $childCompany->dot ?: 'N/A' }}</p>
                                                        </div>
                                                        <span class="text-sm text-muted whitespace-nowrap">
                                                            Drivers: {{ (int) ($childCompanyDriverCounts[$childCompany->id] ?? 0) }}
                                                        </span>
                                                    </div>
                                                @empty
                                                    <p class="mb-0 text-sm text-muted">No child companies linked yet.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="col-span-12 lg:col-span-6">
                                            <div class="rounded border p-4 h-full">
                                                <p class="mb-3 font-semibold">Rollup Snapshot</p>
                                                <div class="flex items-center justify-between border-b py-2">
                                                    <span>Drivers (incl. children)</span>
                                                    <span>{{ $company->driversCountIncludingChildren() }}</span>
                                                </div>
                                                <div class="flex items-center justify-between border-b py-2">
                                                    <span>Open Tickets</span>
                                                    <span>{{ $company->openTicketsCountIncludingChildren() }}</span>
                                                </div>
                                                <div class="flex items-center justify-between py-2">
                                                    <span>Closed Tickets</span>
                                                    <span>{{ $company->closedTicketsCountIncludingChildren() }}</span>
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
                                        {{ __("All of these contacts will get notified.") }}
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
                                                    @if (old('companyContactName'))
                                                        @foreach(old('companyContactName') as $index => $companyContact)
                                                            <tr>
                                                                <td>{{ $index + 1}}</td>
                                                                <td><input type="text" name="companyContactName[{{ $index }}]" class="form-control" placeholder="Name" value="{{ old("companyContactName")[$index] }}" required /></td>
                                                                <td><input type="email" name="companyContactEmail[{{ $index }}]" class="form-control" placeholder="Email" value="{{ old("companyContactEmail")[$index] }}"  required /></td>
                                                                <td><input type="text" name="companyContactPhone[{{ $index }}]" class="form-control" placeholder="Phone" value="{{ old("companyContactPhone")[$index] }}" /></td>
                                                                <td><input type="text" name="companyContactCell[{{ $index }}]" class="form-control" placeholder="Cell" value="{{ old("companyContactCell")[$index] }}" /></td>
                                                                <td class="text-center">
                                                                    <a href="#" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-danger btn-pc-default js-remove-contact-row">
                                                                        <i class="ti ti-trash text-xl leading-none"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        @foreach($company->contacts as $index => $contact)
                                                            <tr>
                                                                <td>{{ $index + 1}}</td>
                                                                <td><input type="text" name="companyContactName[{{ $index }}]" class="form-control" placeholder="Name" value="{{ $contact->name }}" required /></td>
                                                                <td><input type="email" name="companyContactEmail[{{ $index }}]" class="form-control" placeholder="Email" value="{{ $contact->email }}"  required /></td>
                                                                <td><input type="text" name="companyContactPhone[{{ $index }}]" class="form-control" placeholder="Phone" value="{{ $contact->phone }}" /></td>
                                                                <td><input type="text" name="companyContactCell[{{ $index }}]" class="form-control" placeholder="Cell" value="{{ $contact->cell }}" /></td>
                                                                <td class="text-center">
                                                                    <a href="#" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-danger btn-pc-default js-remove-contact-row">
                                                                        <i class="ti ti-trash text-xl leading-none"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
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
                                        {{ __("Who Manages this company with/without write access.") }}
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
                                                    @php
                                                        $companyManagers = $company->managers
                                                            ->filter(fn ($manager) => filled(optional($manager->user)->email))
                                                            ->values();
                                                    @endphp
                                                    @forelse($companyManagers as $index => $manager)
                                                        @php $managerUser = $manager->user; @endphp
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <a href="{{ route(auth()->user()->portalRoutePrefix().'.managers.edit', $manager->id) }}" class="font-medium text-primary">
                                                                    {{ $managerUser->name ?: 'Unnamed manager' }}
                                                                </a>
                                                            </td>
                                                            <td>{{ $managerUser->email }}</td>
                                                            <td>{!! $manager->pivot->is_write_access ? '<i class="text-success text-lg ti ti-check"></i>' : '<i class="text-danger text-lg ti ti-x"></i>' !!}</td>
                                                            <td class="text-center">
                                                                <a href="{{ route(auth()->user()->portalRoutePrefix().'.managers.edit', $manager->id) }}" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-primary btn-pc-default" title="Edit manager">
                                                                    <i class="ti ti-pencil text-xl leading-none"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted py-4">
                                                                No company managers linked yet.
                                                                @if($companyDriversCount > 0)
                                                                    <span class="d-block mt-1 text-xs">
                                                                        If Salesforce Account emails match a Driver email, a separate company manager login is not created automatically. Add a manager from the Managers page, or use a different Account contact email in Salesforce.
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforelse
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
                                        <h5 class="text-primary text-[28px] font-bold mb-0">Drivers ({{ number_format($companyDriversCount) }})</h5>
                                        <span class="text-muted text-sm">
                                            Drivers associated with {{ $company->name }}. Click a driver name to open the profile.
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0 yajra-datatable w-full" id="companyDriversTable" style="min-width: 1000px;">
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
                                            <tbody></tbody>
                                        </table>
                                    </div>
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
                                        <h5 class="text-primary text-[28px] font-bold mb-0">Tickets ({{ number_format($companyTicketsCount) }})</h5>
                                        <span class="text-muted text-sm">
                                            All tickets for {{ $company->name }}. Click a ticket or driver to open the record.
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0 yajra-datatable w-full" id="companyTicketsTable" style="min-width: 1000px;">
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
                                            <tbody></tbody>
                                        </table>
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
@endsection
@section('post-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/flatpickr.min.js') }}"></script>
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
            let driversTable = null;
            let ticketsTable = null;

            function initDriversTable() {
                if (driversTable || !window.jQuery) return;
                driversTable = $('#companyDriversTable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    pageLength: 25,
                    autoWidth: false,
                    order: [[0, 'asc']],
                    dom: "<'dt-controls-bar'l f><'tc-table-scroll-container't><'dt-footer-bar'i p>",
                    ajax: {
                        url: @json(route(auth()->user()->portalRoutePrefix().'.companies.drivers-data', $company->id)),
                    },
                    columns: [
                        { data: 'row_number', orderable: true, searchable: false },
                        { data: 'driver_name', orderable: false },
                        { data: 'email', orderable: false },
                        { data: 'state', orderable: false },
                        { data: 'city', orderable: false },
                        { data: 'open_tickets', orderable: false, searchable: false },
                        { data: 'closed_tickets', orderable: false, searchable: false },
                        { data: 'points_saved', orderable: false, searchable: false },
                        { data: 'last_access', orderable: false, searchable: false },
                        { data: 'status_html', orderable: false, searchable: false },
                        { data: 'action', orderable: false, searchable: false, className: 'text-center' },
                    ],
                });
            }

            function initTicketsTable() {
                if (ticketsTable || !window.jQuery) return;
                ticketsTable = $('#companyTicketsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    pageLength: 25,
                    autoWidth: false,
                    order: [[0, 'desc']],
                    dom: "<'dt-controls-bar'l f><'tc-table-scroll-container't><'dt-footer-bar'i p>",
                    ajax: {
                        url: @json(route(auth()->user()->portalRoutePrefix().'.companies.tickets-data', $company->id)),
                    },
                    columns: [
                        { data: 'ticket_html', orderable: true },
                        { data: 'driver_html', orderable: false },
                        { data: 'date_received', orderable: false, searchable: false },
                        { data: 'state', orderable: false },
                        { data: 'status_html', orderable: false },
                        { data: 'original_points', orderable: false, searchable: false },
                        { data: 'final_points', orderable: false, searchable: false },
                        { data: 'points_saved', orderable: false, searchable: false },
                        { data: 'action', orderable: false, searchable: false, className: 'text-center' },
                    ],
                });
            }

            document.querySelectorAll('[data-pc-toggle="tab"]').forEach(function (el) {
                el.addEventListener('click', function () {
                    const target = el.getAttribute('data-pc-target') || '';
                    if (target === 'companyDriversTab') setTimeout(initDriversTable, 50);
                    if (target === 'companyTicketsTab') setTimeout(initTicketsTable, 50);
                });
            });
        })();
    </script>
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.2.1/ol.css">
    <link rel="stylesheet" href="{{ asset('css/plugins/flatpickr.min.css') }}" />
@endsection
