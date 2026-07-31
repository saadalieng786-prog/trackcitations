<?php $__env->startSection('content'); ?>
        <?php($portal = auth()->user()->portalRoutePrefix())
        <div class="col-span-12 lg:col-span-6 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-primary-500/10 text-primary-500">
                                <i class="ti ti-file text-2xl leading-none"></i>
                            </div>
                        </div>
                        <div class="grow ltr:ml-3 rtl:mr-3">
                            <p class="mb-1">Open Tickets</p>
                            <div class="flex items-center justify-between">
                                <h4 class="mb-0">{{ $stats['tickets'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-success-500/10 text-success-500">
                                <i class="ti ti-users text-2xl leading-none"></i>
                            </div>
                        </div>
                        <div class="grow ltr:ml-3 rtl:mr-3">
                            <p class="mb-1">Drivers</p>
                            <div class="flex items-center justify-between">
                                <h4 class="mb-0">{{ $stats['drivers'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-warning-500/10 text-warning-500">
                                <i class="ti ti-file-check text-2xl leading-none"></i>
                            </div>
                        </div>
                        <div class="grow ltr:ml-3 rtl:mr-3">
                            <p class="mb-1">Closed Tickets</p>
                            <div class="flex items-center justify-between">
                                <h4 class="mb-0">{{ $stats['closed_tickets'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-info-500/10 text-info-500">
                                <i class="ti ti-chart-arrows text-2xl leading-none"></i>
                            </div>
                        </div>
                        <div class="grow ltr:ml-3 rtl:mr-3">
                            <p class="mb-1">Lifetime Points Saved</p>
                            <div class="flex items-center justify-between">
                                <h4 class="mb-0">{{ number_format($stats['points_saved'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-6 md:col-span-6">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="w-12 h-12 rounded-lg inline-flex items-center justify-center bg-danger-500/10 text-danger-500">
                                <i class="ti ti-building text-2xl leading-none"></i>
                            </div>
                        </div>
                        <div class="grow ltr:ml-3 rtl:mr-3">
                            <p class="mb-1">Companies</p>
                            <div class="flex items-center justify-between">
                                <h4 class="mb-0">{{ $stats['companies'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <div class="col-span-12 lg:col-span-7">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="mb-0">Action Needed</h5>
                    <a href="{{ route($portal.'.tickets.index') }}" class="btn btn-secondary btn-sm">All Open Cases</a>
                </div>
                <ul class="rounded-lg *:py-4 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                    @forelse($pendingTickets as $pendingTicket)
                        <li class="list-group-item">
                            <div class="flex items-center justify-between gap-3">
                                <div class="grow">
                                    <h6 class="mb-1">{{ $pendingTicket->name }}</h6>
                                    <p class="mb-0 text-muted">
                                        Company: {{ optional($pendingTicket->company)->name ?: 'N/A' }} |
                                        Attorney: {{ optional(optional($pendingTicket->attorney)->user)->name ?: 'Unassigned' }}
                                    </p>
                                    <p class="mb-0 mt-1 text-muted">
                                        Indicator: {{ $pendingTicket->indicator ?: 'Pending' }} |
                                        Points Saved: {{ number_format($pendingTicket->points_saved, 1) }}
                                    </p>
                                </div>
                                <a href="{{ route($portal.'.tickets.edit', $pendingTicket->id) }}" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                    <i class="ti ti-eye text-xl leading-none"></i>
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item">
                            <div class="flex items-center">
                                <div class="grow mx-1">
                                    No Pending Tickets.
                                </div>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-5">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">Upcoming court dates</h5>
                @forelse($upComingCourtDates as $upComingCourtDate)
                    @php
                        $date = \Carbon\Carbon::parse($upComingCourtDate->court_date);
                        $day = $date->format('M d, D');
                        $time = $date->format('H:i');
                    ?>
                    <div class="card overflow-hidden mb-2">
                        <div class="card-body !px-3 !py-2 border-l-4 border-danger-500">
                            <h6 class="mb-1"><?php echo e($upComingCourtDate->name); ?></h6>
                            <p class="mb-1"><i class="ti ti-calendar"></i> <?php echo e($day); ?> | <?php echo e(optional($upComingCourtDate->company)->name ?: 'N/A'); ?></p>
                            <p class="mb-0"><span class="ti ti-calendar-time"></span> <?php echo e($time); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
                    <div class="card overflow-hidden mb-2">
                        <div class="card-body !px-3 !py-2 border-l-4 border-danger-500">
                            No upcoming court dates!
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-5">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="mb-0">Recently Closed Cases</h5>
                    <a href="<?php echo e(route($portal.'.tickets.index', ['status' => \App\Models\Ticket::TICKET_STATUS_CLOSED])); ?>" class="btn btn-secondary btn-sm">View Closed</a>
                </div>
                <ul class="rounded-lg *:py-4 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                    <?php $__empty_0 = true; $__currentLoopData = $recentClosedTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>
                        <li class="list-group-item">
                            <div class="flex items-center justify-between gap-3">
                                <div class="grow">
                                    <h6 class="mb-1"><?php echo e($ticket->name); ?></h6>
                                    <p class="mb-0 text-muted">
                                        Company: <?php echo e(optional($ticket->company)->name ?: 'N/A'); ?> |
                                        Points Saved: <?php echo e(number_format($ticket->points_saved, 1)); ?>

                                    </p>
                                </div>
                                <a href="<?php echo e(route($portal.'.tickets.edit', $ticket->id)); ?>" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                    <i class="ti ti-eye text-xl leading-none"></i>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
                        <li class="list-group-item">No closed tickets yet.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-7">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="mb-0">Company Snapshot</h5>
                    <a href="<?php echo e(route($portal.'.companies.index')); ?>" class="btn btn-secondary btn-sm">Manage Companies</a>
                </div>
                <ul class="rounded-lg *:py-4 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                    <?php $__empty_0 = true; $__currentLoopData = $companySnapshots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_0 = false; ?>
                        <li class="list-group-item">
                            <div class="flex items-center justify-between gap-3">
                                <div class="grow">
                                    <h6 class="mb-1"><?php echo e($company->name); ?></h6>
                                    <p class="mb-0 text-muted">
                                        Parent: <?php echo e(optional($company->parentCompany)->name ?: 'Top-level company'); ?> |
                                        Drivers: <?php echo e($company->driversCountIncludingChildren()); ?> |
                                        Open: <?php echo e($company->openTicketsCountIncludingChildren()); ?> |
                                        Closed: <?php echo e($company->closedTicketsCountIncludingChildren()); ?>

                                    </p>
                                    <p class="mb-0 mt-1 text-muted">
                                        Lifetime Points Saved: <?php echo e(number_format($company->lifetimePointsSaved(), 1)); ?>

                                    </p>
                                </div>
                                <a href="<?php echo e(route($portal.'.companies.edit', $company->id)); ?>" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-light-secondary">
                                    <i class="ti ti-eye text-xl leading-none"></i>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_0): ?>
                        <li class="list-group-item">No managed companies found.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('pre-scripts'); ?>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\manager\dashboard.blade.php ENDPATH**/ ?>