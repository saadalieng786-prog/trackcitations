@extends('layout.master')
@section('content')
    <div class="col-span-12">
        <form action="{{ route(auth()->user()->portalRoutePrefix().'.companies.store') }}" method="POST">
            @csrf
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
                                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" />
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
                                                <input type="email" name="ct_email" id="ct_email" class="form-control" value="{{ old('ct_email') }}" />
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
                                                <input type="text" name="ct_fname" id="ct_fname" class="form-control" value="{{ old('ct_fname') }}" />
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
                                                <input type="text" name="ct_lname" id="ct_lname" class="form-control" value="{{ old('ct_lname') }}" />
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
                                                <input type="text" name="dot" id="dot" class="form-control" value="{{ old('dot') }}" />
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
                                                <input type="text" name="sf_id" id="sf_id" class="form-control" value="{{ old('sf_id') }}" />
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
                                                        <option value="{{ $parentCompany->id }}" {{ (string) old('parent_company_id') === (string) $parentCompany->id ? 'selected' : '' }}>
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
                                        {{ __("Link this company under a parent company if it belongs to a broader organization.") }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="rounded border p-4">
                                        <p class="mb-2 font-semibold">How this works</p>
                                        <p class="mb-2 text-sm text-muted">Leave the parent blank for a top-level company. Select a parent when this record should roll up under a larger company account.</p>
                                        <p class="mb-0 text-sm text-muted">Child companies inherit dashboard rollups for tickets, drivers, and points saved through their parent structure.</p>
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
                                                    @if (old('companyContactName'))
                                                        @foreach(old('companyContactName') as $index => $companyContact)
                                                            <tr>
                                                                <td>{{ $index + 1}}</td>
                                                                <td><input type="text" name="companyContactName[{{ $index }}]" class="form-control" placeholder="Name" value="{{ old("companyContactName")[$index] }}" required /></td>
                                                                <td><input type="email" name="companyContactEmail[{{ $index }}]" class="form-control" placeholder="Email" value="{{ old("companyContactEmail")[$index] }}"  required /></td>
                                                                <td><input type="text" name="companyContactPhone[{{ $index }}]" class="form-control" placeholder="Phone" value="{{ old("companyContactPhone")[$index] }}" /></td>
                                                                <td><input type="text" name="companyContactCell[{{ $index }}]" class="form-control" placeholder="Cell" value="{{ old("companyContactCell")[$index] }}" /></td>
                                                                <td class="text-center">
                                                                    <a href="#" class="w-10 h-10 inline-flex items-center rounded-lg justify-center btn-link-danger btn-pc-default" id="removeItem">
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
                <div class="col-span-12 text-right">
                    <button type="reset" class="btn btn-outline-secondary mx-1">Cancel</button>
                    <button type="submit" class="btn btn-primary mx-1">Create Company</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('post-scripts')
    <script src="{{ asset('js/plugins/flatpickr.min.js') }}"></script>
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
                <td><input type="text" name="companyContactName[${newIndex}]" class="form-control" placeholder="Name" required /></td>
                <td><input type="email" name="companyContactEmail[${newIndex}]" class="form-control" placeholder="Email" required /></td>
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
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v10.2.1/ol.css">
    <link rel="stylesheet" href="{{ asset('css/plugins/flatpickr.min.css') }}" />
@endsection
