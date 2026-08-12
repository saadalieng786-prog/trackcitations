<?php $__env->startSection('content'); ?>
    <?php
        $portal = auth()->user()->portalRoutePrefix();
        $userName = Auth::user()->name;
        $s = $stats;
    ?>

    
    <div class="col-span-12 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 m-0 tracking-tight">Dashboard</h1>
                <p class="text-sm text-slate-500 mt-1 mb-0">
                    Welcome back, <span class="font-semibold text-slate-700"><?php echo e($userName); ?></span>!
                    Showing activity for <span class="font-semibold text-slate-700"><?php echo e($dateRangeLabel); ?></span>.
                </p>
            </div>
            <div class="relative" id="dashboardDateFilter">
                <button type="button" id="dashboardDateFilterBtn" class="tc-date-filter-btn" aria-expanded="false" aria-haspopup="true">
                    <i class="ti ti-calendar text-slate-400 text-sm"></i>
                    <span id="dashboardDateLabel"><?php echo e($dateRangeLabel); ?></span>
                    <i class="ti ti-chevron-down text-slate-400 text-xs ms-1"></i>
                </button>
                <div id="dashboardDateMenu" class="tc-date-filter-menu" role="menu">
                    <button type="button" class="tc-date-filter-option <?php echo e($dateFilter === 'this_year' ? 'active' : ''); ?>" data-period="this_year">
                        This Year
                    </button>
                    <button type="button" class="tc-date-filter-option <?php echo e($dateFilter === 'last_year' ? 'active' : ''); ?>" data-period="last_year">
                        Last Year
                    </button>
                    <button type="button" class="tc-date-filter-option <?php echo e($dateFilter === 'custom' ? 'active' : ''); ?>" data-period="custom" id="dashboardCustomRangeBtn">
                        Custom Date Range
                    </button>
                </div>
                <input
                    type="text"
                    id="dashboardDatePicker"
                    class="sr-only"
                    value="<?php echo e($startDate->toDateString()); ?> to <?php echo e($endDate->toDateString()); ?>"
                    data-from="<?php echo e($startDate->toDateString()); ?>"
                    data-to="<?php echo e($endDate->toDateString()); ?>"
                    data-period="<?php echo e($dateFilter); ?>"
                    autocomplete="off"
                    aria-hidden="true"
                />
            </div>
        </div>
    </div>

    
    <div class="col-span-12 grid grid-cols-12 gap-6 mb-6">
        
        <div class="col-span-12 lg:col-span-3 md:col-span-6">
            <div class="tc-stat-card-v2">
                <div class="flex items-start justify-between">
                    <div class="tc-stat-icon-v2 blue">
                        <i class="ti ti-file-text"></i>
                    </div>
                    <button type="button" class="tc-card-menu-btn" title="Options">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                </div>
                <div class="mt-3">
                    <div class="tc-stat-label-v2">Open Tickets</div>
                    <div class="tc-stat-value-v2"><?php echo e(number_format($s['tickets'])); ?></div>
                    <div class="tc-stat-trend up">
                        <i class="ti ti-arrow-up-right"></i>
                        <span>12.5% from last month</span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-span-12 lg:col-span-3 md:col-span-6">
            <div class="tc-stat-card-v2">
                <div class="flex items-start justify-between">
                    <div class="tc-stat-icon-v2 orange">
                        <i class="ti ti-user"></i>
                    </div>
                    <button type="button" class="tc-card-menu-btn" title="Options">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                </div>
                <div class="mt-3">
                    <div class="tc-stat-label-v2">Drivers</div>
                    <div class="tc-stat-value-v2"><?php echo e(number_format($s['drivers'])); ?></div>
                    <div class="tc-stat-trend none">
                        <span>No change</span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-span-12 lg:col-span-3 md:col-span-6">
            <div class="tc-stat-card-v2">
                <div class="flex items-start justify-between">
                    <div class="tc-stat-icon-v2 green">
                        <i class="ti ti-briefcase"></i>
                    </div>
                    <button type="button" class="tc-card-menu-btn" title="Options">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                </div>
                <div class="mt-3">
                    <div class="tc-stat-label-v2">Attorneys</div>
                    <div class="tc-stat-value-v2"><?php echo e(number_format($s['attorneys'])); ?></div>
                    <div class="tc-stat-trend up">
                        <i class="ti ti-arrow-up-right"></i>
                        <span>8.2% from last month</span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-span-12 lg:col-span-3 md:col-span-6">
            <div class="tc-stat-card-v2">
                <div class="flex items-start justify-between">
                    <div class="tc-stat-icon-v2 red">
                        <i class="ti ti-building"></i>
                    </div>
                    <button type="button" class="tc-card-menu-btn" title="Options">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                </div>
                <div class="mt-3">
                    <div class="tc-stat-label-v2">Companies</div>
                    <div class="tc-stat-value-v2"><?php echo e(number_format($s['companies'])); ?></div>
                    <div class="tc-stat-trend down">
                        <i class="ti ti-arrow-down-right"></i>
                        <span>3.4% from last month</span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-span-12 lg:col-span-3 md:col-span-6">
            <div class="tc-stat-card-v2">
                <div class="flex items-start justify-between">
                    <div class="tc-stat-icon-v2 cyan">
                        <i class="ti ti-circle-check"></i>
                    </div>
                    <button type="button" class="tc-card-menu-btn" title="Options">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                </div>
                <div class="mt-3">
                    <div class="tc-stat-label-v2">Closed Tickets</div>
                    <div class="tc-stat-value-v2"><?php echo e(number_format($s['closed_tickets'] ?? 0)); ?></div>
                    <div class="tc-stat-trend up">
                        <i class="ti ti-check"></i>
                        <span>Completed</span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-span-12 lg:col-span-3 md:col-span-6">
            <div class="tc-stat-card-v2">
                <div class="flex items-start justify-between">
                    <div class="tc-stat-icon-v2 purple">
                        <i class="ti ti-award"></i>
                    </div>
                    <button type="button" class="tc-card-menu-btn" title="Options">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                </div>
                <div class="mt-3">
                    <div class="tc-stat-label-v2">Lifetime Points Saved</div>
                    <div class="tc-stat-value-v2"><?php echo e(number_format($s['points_saved'] ?? 1390, 1)); ?></div>
                    <div class="tc-stat-trend up">
                        <i class="ti ti-shield-check"></i>
                        <span>Total Saved</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-span-12 grid grid-cols-12 gap-6 mb-6">
        
        <div class="col-span-12 lg:col-span-6">
            <div class="card h-full">
                <div class="card-header flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h5 class="tc-card-title m-0">Action Needed</h5>
                        <span class="tc-badge-soft-blue">5</span>
                    </div>
                    <a href="<?php echo e(route($portal.'.tickets.pending')); ?>" class="text-xs font-semibold text-indigo-600 hover:underline">
                        View all
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-slate-100">
                        <?php
                            $avatarClasses = ['purple', 'pink', 'pink', 'pink', 'mint'];
                            $dueTexts = ['Due Today', 'Due in 2 days', 'Due in 3 days', 'Due in 5 days', 'Due in 7 days'];
                        ?>

                        <?php $__empty_1 = true; $__currentLoopData = $pendingTickets->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pendingTicket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $initials = collect(explode(' ', $pendingTicket->name))->map(fn($p)=>strtoupper(substr($p,0,1)))->take(2)->implode('');
                                $company = optional($pendingTicket->company)->name ?: '—';
                                $avColor = $avatarClasses[$index % count($avatarClasses)];
                                $dueTxt  = $dueTexts[$index % count($dueTexts)];
                            ?>
                            <div class="tc-list-item">
                                <div class="flex items-center gap-3 min-w-0 grow">
                                    <div class="tc-avatar-initials <?php echo e($avColor); ?>">
                                        <?php echo e($initials ?: 'RW'); ?>

                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-xs text-slate-800 truncate">
                                            <?php echo e($pendingTicket->name); ?>

                                        </div>
                                        <div class="text-[11px] text-slate-400 truncate">
                                            <?php echo e($company); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="tc-badge-soft-orange">Pending</span>
                                    <span class="text-xs text-slate-400 flex items-center gap-1">
                                        <i class="ti ti-calendar text-xs <?php echo e($index === 0 ? 'text-red-500 font-bold' : ''); ?>"></i>
                                        <span class="<?php echo e($index === 0 ? 'text-red-500 font-bold' : ''); ?>"><?php echo e($dueTxt); ?></span>
                                    </span>
                                    <a href="<?php echo e(route($portal.'.tickets.edit', $pendingTicket->id)); ?>" class="btn-outline-soft">
                                        View <i class="ti ti-chevron-right text-[10px] ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="tc-list-item">
                                <div class="flex items-center gap-3 min-w-0 grow">
                                    <div class="tc-avatar-initials purple">RW</div>
                                    <div>
                                        <div class="font-bold text-xs text-slate-800">Richard White</div>
                                        <div class="text-[11px] text-slate-400">Black Eagle Marketing Group</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="tc-badge-soft-orange">Pending</span>
                                    <span class="text-xs text-red-500 font-semibold flex items-center gap-1">
                                        <i class="ti ti-calendar text-xs"></i> Due Today
                                    </span>
                                    <a href="#" class="btn-outline-soft">View <i class="ti ti-chevron-right text-[10px] ms-1"></i></a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-span-12 lg:col-span-6">
            <div class="card h-full">
                <div class="card-header flex items-center justify-between">
                    <h5 class="tc-card-title m-0">Upcoming Court Dates</h5>
                    <a href="<?php echo e(route('upcoming_court_date')); ?>" class="text-xs font-semibold text-indigo-600 hover:underline">
                        View calendar
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $upComingCourtDates->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $upComingCourtDate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $date    = \Carbon\Carbon::parse($upComingCourtDate->court_date);
                                $month   = strtoupper($date->format('M'));
                                $day     = $date->format('d');
                                $time    = $date->format('g:i A');
                            ?>
                            <div class="tc-court-date-row">
                                <div class="tc-court-date-box">
                                    <div class="tc-court-red-indicator"></div>
                                    <div class="tc-court-date-num">
                                        <div class="month"><?php echo e($month); ?></div>
                                        <div class="day"><?php echo e($day); ?></div>
                                    </div>
                                    <div class="ms-2">
                                        <div class="font-bold text-xs text-slate-800">
                                            <?php echo e($upComingCourtDate->name); ?>

                                        </div>
                                        <div class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                            <i class="ti ti-clock text-xs"></i> <?php echo e($time); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="tc-badge-soft-purple">Court Hearing</span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="tc-court-date-row">
                                <div class="tc-court-date-box">
                                    <div class="tc-court-red-indicator"></div>
                                    <div class="tc-court-date-num">
                                        <div class="month">JUN</div>
                                        <div class="day">25</div>
                                    </div>
                                    <div class="ms-2">
                                        <div class="font-bold text-xs text-slate-800">Cooper Douglas Oakley</div>
                                        <div class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                            <i class="ti ti-clock text-xs"></i> 9:30 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="tc-badge-soft-purple">Court Hearing</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-span-12 grid grid-cols-12 gap-6 mb-6">
        
        <div class="col-span-12 lg:col-span-8">
            <div class="card h-full">
                <div class="card-header flex items-center justify-between">
                    <h5 class="tc-card-title m-0">Tickets Overview</h5>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3 text-xs">
                            <span class="flex items-center gap-1 text-slate-600 font-medium"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span> Open</span>
                            <span class="flex items-center gap-1 text-slate-600 font-medium"><span class="w-2.5 h-2.5 rounded-full bg-orange-400 inline-block"></span> Pending</span>
                            <span class="flex items-center gap-1 text-slate-600 font-medium"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Closed</span>
                        </div>
                        <div class="relative" id="ticketOverviewFilter">
                            <button type="button" id="ticketOverviewFilterBtn" class="btn-outline-soft py-1 px-3 text-xs">
                                <?php echo e($chartPeriodLabel); ?> <i class="ti ti-chevron-down text-[10px] ms-1"></i>
                            </button>
                            <div id="ticketOverviewMenu" class="tc-date-filter-menu" role="menu">
                                <button type="button" class="tc-date-filter-option <?php echo e($chartPeriod === 'this_month' ? 'active' : ''); ?>" data-chart="this_month">This Month</button>
                                <button type="button" class="tc-date-filter-option <?php echo e($chartPeriod === 'this_year' ? 'active' : ''); ?>" data-chart="this_year">This Year</button>
                                <button type="button" class="tc-date-filter-option <?php echo e($chartPeriod === 'last_year' ? 'active' : ''); ?>" data-chart="last_year">Last Year</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        $chartW = 700;
                        $chartH = 180;
                        $padTop = 10;
                        $padBottom = 10;
                        $usableH = $chartH - $padTop - $padBottom;
                        $count = max(1, count($ticketOverview['labels']));
                        $stepX = $count > 1 ? $chartW / ($count - 1) : $chartW;
                        $maxVal = max(1, (int) $ticketOverview['max']);

                        $toPoints = function (array $series) use ($stepX, $usableH, $padTop, $maxVal, $chartH) {
                            $pts = [];
                            foreach ($series as $i => $value) {
                                $x = round($i * $stepX, 2);
                                $y = round($padTop + ($usableH * (1 - ($value / $maxVal))), 2);
                                $pts[] = [$x, $y];
                            }
                            return $pts;
                        };

                        $toLinePath = function (array $pts) {
                            if (empty($pts)) {
                                return '';
                            }
                            $d = 'M '.$pts[0][0].' '.$pts[0][1];
                            for ($i = 1; $i < count($pts); $i++) {
                                $d .= ' L '.$pts[$i][0].' '.$pts[$i][1];
                            }
                            return $d;
                        };

                        $toAreaPath = function (array $pts) use ($chartH) {
                            if (empty($pts)) {
                                return '';
                            }
                            $d = 'M '.$pts[0][0].' '.$pts[0][1];
                            for ($i = 1; $i < count($pts); $i++) {
                                $d .= ' L '.$pts[$i][0].' '.$pts[$i][1];
                            }
                            $last = end($pts);
                            $first = $pts[0];
                            $d .= ' L '.$last[0].' '.$chartH.' L '.$first[0].' '.$chartH.' Z';
                            return $d;
                        };

                        $openPts = $toPoints($ticketOverview['open']);
                        $pendingPts = $toPoints($ticketOverview['pending']);
                        $closedPts = $toPoints($ticketOverview['closed']);
                        $labelStep = max(1, (int) ceil($count / 7));
                    ?>
                    <div class="relative w-full h-[220px]">
                        <svg class="w-full h-full" viewBox="0 0 <?php echo e($chartW); ?> <?php echo e($chartH); ?>" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="blueGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.0"/>
                                </linearGradient>
                                <linearGradient id="orangeGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#f97316" stop-opacity="0.2"/>
                                    <stop offset="100%" stop-color="#f97316" stop-opacity="0.0"/>
                                </linearGradient>
                                <linearGradient id="greenGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#10b981" stop-opacity="0.2"/>
                                    <stop offset="100%" stop-color="#10b981" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>
                            <line x1="0" y1="40" x2="700" y2="40" stroke="#f1f5f9" stroke-dasharray="4"/>
                            <line x1="0" y1="90" x2="700" y2="90" stroke="#f1f5f9" stroke-dasharray="4"/>
                            <line x1="0" y1="140" x2="700" y2="140" stroke="#f1f5f9" stroke-dasharray="4"/>

                            <path d="<?php echo e($toAreaPath($openPts)); ?>" fill="url(#blueGrad)"/>
                            <path d="<?php echo e($toLinePath($openPts)); ?>" fill="none" stroke="#3b82f6" stroke-width="3"/>

                            <path d="<?php echo e($toAreaPath($pendingPts)); ?>" fill="url(#orangeGrad)"/>
                            <path d="<?php echo e($toLinePath($pendingPts)); ?>" fill="none" stroke="#f97316" stroke-width="2.5"/>

                            <path d="<?php echo e($toAreaPath($closedPts)); ?>" fill="url(#greenGrad)"/>
                            <path d="<?php echo e($toLinePath($closedPts)); ?>" fill="none" stroke="#10b981" stroke-width="2"/>
                        </svg>
                        <div class="flex justify-between text-[11px] text-slate-400 mt-2 px-1">
                            <?php $__currentLoopData = $ticketOverview['labels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($i % $labelStep === 0 || $i === $count - 1): ?>
                                    <span><?php echo e($label); ?></span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-span-12 lg:col-span-4">
            <div class="card h-full">
                <div class="card-header flex items-center justify-between">
                    <h5 class="tc-card-title m-0">Recent Activity</h5>
                    <a href="<?php echo e(route($portal.'.logs.index')); ?>" class="text-xs font-semibold text-indigo-600 hover:underline">
                        View all
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-slate-100">
                        <div class="tc-list-item">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 text-sm">
                                    <i class="ti ti-file-text"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs text-slate-800">New ticket #TKT-090161 created</div>
                                    <div class="text-[11px] text-slate-400">By System</div>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400 shrink-0">2 min ago</span>
                        </div>

                        <div class="tc-list-item">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 text-sm">
                                    <i class="ti ti-briefcase"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs text-slate-800">Court date updated for Cooper</div>
                                    <div class="text-[11px] text-slate-400">By Admin</div>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400 shrink-0">15 min ago</span>
                        </div>

                        <div class="tc-list-item">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 text-sm">
                                    <i class="ti ti-message"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-xs text-slate-800">New message from Richard White</div>
                                    <div class="text-[11px] text-slate-400">By User</div>
                                </div>
                            </div>
                            <span class="text-[11px] text-slate-400 shrink-0">1 hour ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/flatpickr.min.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>
    <script src="<?php echo e(asset('js/plugins/flatpickr.min.js')); ?>"></script>
    <script>
        (function () {
            var wrap = document.getElementById('dashboardDateFilter');
            var button = document.getElementById('dashboardDateFilterBtn');
            var menu = document.getElementById('dashboardDateMenu');
            var input = document.getElementById('dashboardDatePicker');
            if (!wrap || !button || !menu || !input) {
                return;
            }

            var currentFrom = input.dataset.from;
            var currentTo = input.dataset.to;
            var currentPeriod = input.dataset.period || 'this_year';

            function navigate(period, from, to) {
                var url = new URL(window.location.href);
                url.searchParams.set('period', period);
                if (period === 'custom' && from && to) {
                    url.searchParams.set('from', from);
                    url.searchParams.set('to', to);
                } else {
                    url.searchParams.delete('from');
                    url.searchParams.delete('to');
                }
                url.searchParams.delete('date');
                window.location.href = url.toString();
            }

            function closeMenu() {
                menu.classList.remove('is-open');
                button.setAttribute('aria-expanded', 'false');
            }

            function openMenu() {
                menu.classList.add('is-open');
                button.setAttribute('aria-expanded', 'true');
            }

            button.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (menu.classList.contains('is-open')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) {
                    closeMenu();
                }
            });

            var picker = null;
            if (typeof flatpickr !== 'undefined') {
                picker = flatpickr(input, {
                    mode: 'range',
                    defaultDate: [currentFrom, currentTo],
                    dateFormat: 'Y-m-d',
                    altInput: false,
                    allowInput: false,
                    positionElement: button,
                    onClose: function (selectedDates) {
                        if (selectedDates.length !== 2) {
                            return;
                        }

                        var from = flatpickr.formatDate(selectedDates[0], 'Y-m-d');
                        var to = flatpickr.formatDate(selectedDates[1], 'Y-m-d');

                        if (currentPeriod === 'custom' && from === currentFrom && to === currentTo) {
                            return;
                        }

                        navigate('custom', from, to);
                    }
                });
            }

            menu.querySelectorAll('[data-period]').forEach(function (option) {
                option.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var period = option.getAttribute('data-period');

                    if (period === 'custom') {
                        closeMenu();
                        if (picker) {
                            picker.open();
                        }
                        return;
                    }

                    if (period === currentPeriod) {
                        closeMenu();
                        return;
                    }

                    navigate(period);
                });
            });
        })();

        (function () {
            var wrap = document.getElementById('ticketOverviewFilter');
            var button = document.getElementById('ticketOverviewFilterBtn');
            var menu = document.getElementById('ticketOverviewMenu');
            if (!wrap || !button || !menu) {
                return;
            }

            function closeMenu() {
                menu.classList.remove('is-open');
            }

            button.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                menu.classList.toggle('is-open');
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) {
                    closeMenu();
                }
            });

            menu.querySelectorAll('[data-chart]').forEach(function (option) {
                option.addEventListener('click', function (e) {
                    e.preventDefault();
                    var chart = option.getAttribute('data-chart');
                    var url = new URL(window.location.href);
                    url.searchParams.set('chart', chart);
                    window.location.href = url.toString();
                });
            });
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>