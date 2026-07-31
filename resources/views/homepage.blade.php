@extends('layout.partials.body')

@section('body')
    @php
        $dashboardUrl = route('dashboard');
        $violationOptions = $violations->take(250);
    @endphp

    {{-- ═══════════════════════════════════════════════
         PUBLIC TOP NAVIGATION
    ══════════════════════════════════════════════════ --}}
    <nav class="hp-nav">
        <a href="{{ url('/') }}" class="flex items-center gap-3 no-underline">
            <img src="{{ asset('images/logo-dark.png') }}" class="front-logo h-14 md:h-16 w-auto object-contain py-1" alt="CDL CONSULTANT Logo">
        </a>

        <div class="flex items-center gap-3">
            <a href="#ticket-form" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-plus me-1"></i> Submit Ticket
            </a>
            <a href="{{ $dashboardUrl }}" class="btn btn-primary btn-sm">
                <i class="ti ti-dashboard me-1"></i>
                {{ auth()->check() ? 'Dashboard' : 'Client Login' }}
            </a>
        </div>
    </nav>

    {{-- ═══════════════════════════════════════════════
         HERO SECTION
    ══════════════════════════════════════════════════ --}}
    <div class="hp-hero">
        <div class="max-w-[1140px] mx-auto">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-bold mb-4">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                Secure Ticket Intake Portal
            </div>
            <h1 class="hp-hero-title">
                Submit & Track Your<br>
                <em>Citation Cases</em>
            </h1>
            <p class="hp-hero-sub">
                Fast, secure ticket submission for drivers and fleet companies. Our team reviews every case and works to minimize your violations.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="#ticket-form" class="btn btn-primary px-6 py-2.5">
                    <i class="ti ti-send me-2"></i> Submit a Ticket
                </a>
                <a href="{{ $dashboardUrl }}" class="btn btn-outline-secondary px-6 py-2.5 bg-white/10 text-white border-white/20 hover:bg-white/20">
                    <i class="ti ti-login me-2"></i>
                    {{ auth()->check() ? 'Open Dashboard' : 'Client Login' }}
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         MAIN CONTENT AREA
    ══════════════════════════════════════════════════ --}}
    <div class="hp-main">

        @if ($errors->any())
            <div class="max-w-[1140px] mx-auto mb-6">
                <div class="alert alert-danger p-4 rounded-xl text-sm flex gap-3 align-items-start">
                    <i class="ti ti-alert-circle text-lg shrink-0 mt-0.5"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-1 ps-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="max-w-[1140px] mx-auto mb-6">
                <div class="alert alert-success p-4 rounded-xl text-sm flex gap-3 align-items-center">
                    <i class="ti ti-circle-check text-lg"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="hp-grid">

            {{-- ─── INFO PANEL (Left) ────────────────── --}}
            <div>
                <div class="card p-6 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-4 w-fit">
                        <i class="ti ti-info-circle"></i> Public Ticket Intake
                    </span>

                    <h2 class="text-xl font-bold text-slate-900 mb-2 leading-tight">
                        Submit a citation and our team will review it
                    </h2>

                    <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                        This portal is for drivers or support staff who need to open a new citation case. Fill out the form and we'll get started immediately.
                    </p>

                    <hr class="my-4 border-slate-100">

                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                        What to prepare
                    </div>

                    <ul class="list-none p-0 m-0 mb-6">
                        <li class="flex items-start gap-3 py-2 text-sm text-slate-600 border-b border-slate-100">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="ti ti-check"></i>
                            </span>
                            Driver full name and email address
                        </li>
                        <li class="flex items-start gap-3 py-2 text-sm text-slate-600 border-b border-slate-100">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="ti ti-check"></i>
                            </span>
                            Citation number and violation type
                        </li>
                        <li class="flex items-start gap-3 py-2 text-sm text-slate-600 border-b border-slate-100">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="ti ti-check"></i>
                            </span>
                            Date received, state, city, and plate
                        </li>
                        <li class="flex items-start gap-3 py-2 text-sm text-slate-600">
                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                <i class="ti ti-check"></i>
                            </span>
                            Ticket images or supporting documents
                        </li>
                    </ul>

                    <div class="flex flex-col gap-2">
                        <a href="#ticket-form" class="btn btn-primary w-full py-2.5">
                            <i class="ti ti-send me-2"></i> Go to Submission Form
                        </a>
                        <a href="{{ $dashboardUrl }}" class="btn btn-outline-secondary w-full py-2.5">
                            {{ auth()->check() ? 'Open Dashboard' : 'Login to Existing Account' }}
                        </a>
                    </div>
                </div>

                {{-- Trust Card --}}
                <div class="p-4 rounded-xl bg-slate-900 text-white flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-300 flex items-center justify-center text-xl shrink-0">
                        <i class="ti ti-shield-lock"></i>
                    </div>
                    <div>
                        <div class="font-bold text-xs text-white">Secure & Confidential</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">All submissions are encrypted and handled with care</div>
                    </div>
                </div>
            </div>

            {{-- ─── FORM PANEL (Right) ──────────────── --}}
            <div id="ticket-form">
                <div class="card overflow-hidden">
                    <div class="p-6 bg-gradient-to-r from-indigo-600 to-indigo-800 text-white flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-white m-0">New Ticket Submission</h2>
                            <p class="text-xs text-indigo-100 m-0 mt-1">Enter the citation details below to open a new case</p>
                        </div>
                        <span class="px-3 py-1 rounded-md bg-white/10 text-xs font-semibold text-white flex items-center gap-1.5 border border-white/20">
                            <i class="ti ti-lock text-xs"></i> Secure Intake
                        </span>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('submit.ticket') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Driver Info --}}
                            <div class="mb-6">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="ti ti-user text-indigo-600"></i> Driver Information
                                </div>
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-6">
                                        <label class="form-label text-xs font-bold text-slate-700" for="name">Driver Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" placeholder="Full name of driver">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-span-12 md:col-span-6">
                                        <label class="form-label text-xs font-bold text-slate-700" for="user_email">Driver Email <span class="text-red-500">*</span></label>
                                        <input type="email" name="user_email" class="form-control @error('user_email') is-invalid @enderror" id="user_email" value="{{ old('user_email') }}" placeholder="driver@example.com">
                                        @error('user_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-span-12">
                                        <label class="form-label text-xs font-bold text-slate-700" for="company_name">Company Name</label>
                                        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" id="company_name" value="{{ old('company_name') }}" placeholder="Optional — company or fleet name">
                                        @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Citation Info --}}
                            <div class="mb-6">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="ti ti-file-description text-indigo-600"></i> Citation Details
                                </div>
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-6">
                                        <label class="form-label text-xs font-bold text-slate-700" for="citation_no">Citation Number <span class="text-red-500">*</span></label>
                                        <input type="text" name="citation_no" class="form-control @error('citation_no') is-invalid @enderror" id="citation_no" value="{{ old('citation_no') }}" placeholder="e.g. TC-2024-00123">
                                        @error('citation_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-span-12 md:col-span-6">
                                        <label class="form-label text-xs font-bold text-slate-700" for="date_issued">Date Received <span class="text-red-500">*</span></label>
                                        <input type="text" name="date_issued" class="form-control @error('date_issued') is-invalid @enderror" id="date_issued" value="{{ old('date_issued') }}" placeholder="Select date received">
                                        @error('date_issued')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-span-12">
                                        <label class="form-label text-xs font-bold text-slate-700" for="violation_id">Violation Type <span class="text-red-500">*</span></label>
                                        <select name="violation_id" class="form-control @error('violation_id') is-invalid @enderror" id="violation_id">
                                            <option value="">— Select a violation —</option>
                                            @foreach ($violationOptions as $violation)
                                                <option value="{{ $violation->id }}" {{ (string) old('violation_id') === (string) $violation->id ? 'selected' : '' }}>
                                                    {{ $violation->violation }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('violation_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-span-12">
                                        <label class="form-label text-xs font-bold text-slate-700" for="description">Ticket Details</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="4" placeholder="Describe the situation or special instructions...">{{ old('description') }}</textarea>
                                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Vehicle Info --}}
                            <div class="mb-6">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="ti ti-car text-indigo-600"></i> Vehicle & Location
                                </div>
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-4">
                                        <label class="form-label text-xs font-bold text-slate-700" for="state">License State</label>
                                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" id="state" value="{{ old('state', 'MD') }}" placeholder="State (e.g. MD)">
                                        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <label class="form-label text-xs font-bold text-slate-700" for="city">License City</label>
                                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" id="city" value="{{ old('city') }}" placeholder="City">
                                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-span-12 md:col-span-4">
                                        <label class="form-label text-xs font-bold text-slate-700" for="vehicle_lic_no">Vehicle Plate <span class="text-red-500">*</span></label>
                                        <input type="text" name="vehicle_lic_no" class="form-control @error('vehicle_lic_no') is-invalid @enderror" id="vehicle_lic_no" value="{{ old('vehicle_lic_no') }}" placeholder="Plate number">
                                        @error('vehicle_lic_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Attachments --}}
                            <div class="mb-6">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                                    <i class="ti ti-paperclip text-indigo-600"></i> Attachments
                                </div>
                                <div class="p-3 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50">
                                    <div class="grid grid-cols-12 gap-3">
                                        <div class="col-span-12 md:col-span-4">
                                            <label class="text-[11px] text-slate-400 font-bold">File 1</label>
                                            <input type="file" name="attachments[]" class="form-control text-xs" accept="image/*,.pdf,.doc,.docx">
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <label class="text-[11px] text-slate-400 font-bold">File 2</label>
                                            <input type="file" name="attachments[]" class="form-control text-xs" accept="image/*,.pdf,.doc,.docx">
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <label class="text-[11px] text-slate-400 font-bold">File 3</label>
                                            <input type="file" name="attachments[]" class="form-control text-xs" accept="image/*,.pdf,.doc,.docx">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit Footer --}}
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-6">
                                <p class="text-xs text-slate-400 m-0">
                                    <i class="ti ti-shield-check me-1"></i> Submissions are SSL encrypted
                                </p>
                                <button type="submit" class="btn btn-primary px-8 py-2.5">
                                    <i class="ti ti-send me-2"></i> Submit Ticket
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div class="max-w-[1140px] mx-auto mt-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Track Citations &mdash; All submissions are secure and confidential.
        </div>

    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/plugins/flatpickr.min.css') }}" />
@endsection

@section('post-scripts')
    <script src="{{ asset('js/plugins/flatpickr.min.js') }}"></script>
    <script>
        flatpickr("#date_issued", {
            dateFormat: "m/d/Y",
            allowInput: true
        });
    </script>
@endsection
