<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        {{-- ── LOGO BRAND HEADER ─── --}}
        <div class="m-header">
            <a href="{{ route('dashboard') }}" class="b-brand">
                <img src="{{ asset('images/logo-dark.png') }}"
                     class="logo-lg"
                     alt="Track Citations Logo"/>
                <img src="{{ asset('images/favicon.svg') }}"
                     class="logo-sm"
                     alt="Track Citations Icon"/>
            </a>
        </div>

        {{-- ── SIDEBAR BODY ─────────────────────── --}}
        <div class="navbar-content h-[calc(100vh_-_72px)] py-2">

            {{-- USER PROFILE SUMMARY CARD --}}
            <div class="card pc-user-card mx-4 mb-3 p-3">
                <div class="flex items-center gap-3">
                    @php
                        $userInitials = collect(explode(' ', Auth::user()->name))->map(fn($p)=>strtoupper(substr($p,0,1)))->take(2)->implode('');
                        $roleName = Auth::user()->roles->first()?->name ?? 'User';
                    @endphp
                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">
                        {{ $userInitials ?: 'LS' }}
                    </div>
                    <div class="grow min-w-0">
                        <div class="text-xs font-bold text-slate-800 truncate">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="text-[11px] text-slate-400 capitalize truncate">
                            {{ str_replace('_', ' ', $roleName) }}
                        </div>
                    </div>
                    <a class="shrink-0 text-slate-400 hover:text-slate-600"
                       data-pc-toggle="collapse"
                       href="#pc_sidebar_userlink">
                        <i class="ti ti-chevron-down text-xs"></i>
                    </a>
                </div>

                {{-- User expand menu --}}
                <div class="hidden pc-user-links mt-2 pt-2 border-t border-slate-100" id="pc_sidebar_userlink">
                    <a href="{{ route('profile.edit') }}" class="text-xs text-slate-600 hover:text-indigo-600 block py-1">
                        <i class="ti ti-user me-1"></i> My Account
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="text-xs text-red-600 hover:underline block py-1 w-full text-left bg-transparent border-0 cursor-pointer">
                            <i class="ti ti-power me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- NAV MENU GROUPS --}}
            @php($portal = auth()->user()->portalRoutePrefix())
            <ul class="pc-navbar">

                {{-- ──── MAIN ──── --}}
                <li class="pc-item pc-caption">
                    <label>MAIN</label>
                </li>

                <li class="pc-item {{ request()->routeIs($portal.'.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-smart-home"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs('messaging.*') ? 'active' : '' }}">
                    <a href="{{ route('messaging.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-messages"></i></span>
                        <span class="pc-mtext">Messaging</span>
                        @php($unReadMessagesCount = auth()->user()->unReadMessages()->count())
                        @if ($unReadMessagesCount)
                            <span class="pc-badge ms-auto">{{ $unReadMessagesCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs('upcoming_court_date') ? 'active' : '' }}">
                    <a href="{{ route('upcoming_court_date') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
                        <span class="pc-mtext">Upcoming Court Dates</span>
                    </a>
                </li>

                {{-- ──── TICKETS ──── --}}
                <li class="pc-item pc-caption">
                    <label>TICKETS</label>
                </li>

                <li class="pc-item {{ request()->routeIs($portal.'.tickets.index') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.tickets.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-ticket"></i></span>
                        <span class="pc-mtext">Tickets</span>
                    </a>
                </li>

                @hasanyrole('admin|super_admin|staff_admin')
                <li class="pc-item {{ request()->routeIs($portal.'.tickets.archive') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.tickets.archive') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-archive"></i></span>
                        <span class="pc-mtext">Archived Tickets</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs($portal.'.tickets.pending') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.tickets.pending') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-clock"></i></span>
                        <span class="pc-mtext">Pending Tickets</span>
                        <span class="pc-badge ms-auto">12</span>
                    </a>
                </li>
                @endhasanyrole

                {{-- ──── USERS & ENTITIES ──── --}}
                @hasanyrole('admin|super_admin|staff_admin|manager|company_admin')
                <li class="pc-item pc-caption">
                    <label>USERS & ENTITIES</label>
                </li>
                @endhasanyrole

                @hasanyrole('admin|super_admin|staff_admin')
                <li class="pc-item {{ request()->routeIs($portal.'.admins.*') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.admins.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-user-check"></i></span>
                        <span class="pc-mtext">Administrators</span>
                    </a>
                </li>
                @endhasanyrole

                @hasanyrole('admin|super_admin|staff_admin|manager|company_admin')
                <li class="pc-item {{ request()->routeIs($portal.'.companies.*') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.companies.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-building"></i></span>
                        <span class="pc-mtext">Companies</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs($portal.'.managers.*') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.managers.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-users"></i></span>
                        <span class="pc-mtext">Managers</span>
                    </a>
                </li>

                <li class="pc-item pc-hasmenu {{ request()->routeIs($portal.'.drivers.*') ? 'active' : '' }}">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-truck-delivery"></i></span>
                        <span class="pc-mtext">Drivers</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route($portal.'.drivers.index') }}">Registered Drivers</a>
                        </li>
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route($portal.'.drivers.index', ['status' => 0]) }}">Ticketed (Non-Registered)</a>
                        </li>
                    </ul>
                </li>
                @endhasanyrole

                @hasanyrole('admin|super_admin|staff_admin')
                <li class="pc-item {{ request()->routeIs($portal.'.attorneys.*') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.attorneys.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-briefcase"></i></span>
                        <span class="pc-mtext">Attorneys</span>
                    </a>
                </li>

                {{-- ──── ADMINISTRATION ──── --}}
                <li class="pc-item pc-caption">
                    <label>ADMINISTRATION</label>
                </li>

                <li class="pc-item {{ request()->routeIs($portal.'.violations.*') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.violations.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-alert-triangle"></i></span>
                        <span class="pc-mtext">Citations</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs($portal.'.logs.*') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.logs.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-list"></i></span>
                        <span class="pc-mtext">Logs</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs($portal.'.outgoinglogs.*') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.outgoinglogs.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-send"></i></span>
                        <span class="pc-mtext">Outgoing Logs</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs($portal.'.salesforce.*') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.salesforce.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-refresh"></i></span>
                        <span class="pc-mtext">SF Sync Settings</span>
                    </a>
                </li>

                <li class="pc-item {{ request()->routeIs($portal.'.storage.*') ? 'active' : '' }}">
                    <a href="{{ route($portal.'.storage.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-cloud"></i></span>
                        <span class="pc-mtext">Storage Settings</span>
                    </a>
                </li>
                @endhasanyrole

                {{-- ──── SUPPORT ──── --}}
                <li class="pc-item pc-caption">
                    <label>SUPPORT</label>
                </li>

                <li class="pc-item {{ request()->routeIs('support.*') ? 'active' : '' }}">
                    <a href="{{ route('support.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-headset"></i></span>
                        <span class="pc-mtext">Support</span>
                        <span class="pc-badge ms-auto">24</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
