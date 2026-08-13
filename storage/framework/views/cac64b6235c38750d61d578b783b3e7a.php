<?php $__env->startSection('content'); ?>
    <?php
        $portal = auth()->user()->portalRoutePrefix();
        $statusTone = match ((int) $salesforce->status) {
            \App\Models\SalesForce::STATUS_RUNNING => 'bg-sky-50 text-sky-700 ring-sky-600/20',
            \App\Models\SalesForce::STATUS_FAILED => 'bg-rose-50 text-rose-700 ring-rose-600/20',
            default => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        };
    ?>
    <div class="col-span-12">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 m-0 tracking-tight">Salesforce Sync</h1>
                <p class="text-sm text-slate-500 mt-1 mb-0">Monitor connection health, pull schedule, and recent Salesforce imports.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset <?php echo e($statusTone); ?>">
                    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-80"></span>
                    <?php echo e($salesforce->statusLabel()); ?>

                </span>
                <form action="<?php echo e(route($portal.'.salesforce.sync')); ?>" method="POST" class="m-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-primary btn-sm whitespace-nowrap">Run Sync Now</button>
                </form>
                <a href="<?php echo e(route($portal.'.salesforce.sync-log')); ?>" class="btn btn-outline-secondary btn-sm whitespace-nowrap">View Sync Log</a>
                <a href="<?php echo e(route('salesforce.oauth')); ?>" class="btn btn-outline-primary btn-sm whitespace-nowrap">Reconnect OAuth</a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success mb-4"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger mb-4"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <div class="tab-content">
            <div class="block tab-pane" id="citation">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Synced Companies</p>
                                <h4 class="mb-0"><?php echo e($syncStats['companies']); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Synced Tickets</p>
                                <h4 class="mb-0"><?php echo e($syncStats['tickets']); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Synced Attachments</p>
                                <h4 class="mb-0"><?php echo e($syncStats['attachments']); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Points Saved from SF Tickets</p>
                                <h4 class="mb-0"><?php echo e(number_format($syncStats['points_saved'], 1)); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">SF Tickets With Point Data</p>
                                <h4 class="mb-0"><?php echo e($syncStats['tickets_with_points']); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Original Points Total</p>
                                <h4 class="mb-0"><?php echo e(number_format($syncStats['original_points_total'], 1)); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1 text-sm text-muted">Final Points Total</p>
                                <h4 class="mb-0"><?php echo e(number_format($syncStats['final_points_total'], 1)); ?></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Sync Monitor</h5>
                            </div>
                            <div class="card-body">
                                <?php if(session('success')): ?>
                                    <div class="alert alert-success mb-4"><?php echo e(session('success')); ?></div>
                                <?php endif; ?>
                                <?php if($salesforce->status == \App\Models\SalesForce::STATUS_FAILED && $salesforce->reason): ?>
                                    <div class="alert alert-danger mb-4">
                                        <strong>Last failure:</strong> <?php echo e($salesforce->reason); ?>

                                    </div>
                                <?php endif; ?>

                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="rounded-xl border border-theme-border dark:border-themedark-border p-4 h-full">
                                            <h6 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Connection Health</h6>
                                            <dl class="m-0 space-y-2.5">
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Configured</dt>
                                                    <dd class="mb-0 text-sm font-medium"><?php echo e($salesforce->isConfigured() ? 'Yes' : 'No'); ?></dd>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Access Token</dt>
                                                    <dd class="mb-0 text-sm font-medium"><?php echo e($connectionSummary['has_access_token'] ? 'Stored' : 'Missing'); ?></dd>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Refresh Token</dt>
                                                    <dd class="mb-0 text-sm font-medium"><?php echo e($connectionSummary['has_refresh_token'] ? 'Stored' : 'Missing'); ?></dd>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Settings Updated</dt>
                                                    <dd class="mb-0 text-sm font-medium text-right"><?php echo e($syncTimes['updated'] ?: 'N/A'); ?></dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="rounded-xl border border-theme-border dark:border-themedark-border p-4 h-full">
                                            <h6 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Sync Schedule</h6>
                                            <dl class="m-0 space-y-2.5">
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Pull Interval</dt>
                                                    <dd class="mb-0 text-sm font-medium">Every 15 minutes</dd>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Scheduler</dt>
                                                    <dd class="mb-0 text-sm font-medium">Every 15 minutes</dd>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <dt class="mb-0 text-sm text-muted">Direction</dt>
                                                    <dd class="mb-0 text-sm font-medium">Pull from Salesforce</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <div class="rounded-xl border border-theme-border dark:border-themedark-border p-4">
                                            <h6 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Last Sync Activity</h6>
                                            <p class="mb-3 text-xs text-muted">
                                                “Last sync run” is when Track Citations last finished a sync.
                                                The other timestamps are Salesforce watermarks (record <code>SystemModstamp</code>), not the clock time of the last button click.
                                            </p>
                                            <div class="grid grid-cols-12 gap-3">
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Last Sync Run</p>
                                                    <p class="mb-0 text-sm font-medium"><?php echo e($syncTimes['last_run']); ?></p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Records Watermark</p>
                                                    <p class="mb-0 text-sm font-medium"><?php echo e($syncTimes['records']); ?></p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Attachment Watermark</p>
                                                    <p class="mb-0 text-sm font-medium"><?php echo e($syncTimes['attachments']); ?></p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">File Watermark</p>
                                                    <p class="mb-0 text-sm font-medium"><?php echo e($syncTimes['files']); ?></p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Account Activity</p>
                                                    <p class="mb-0 text-sm font-medium"><?php echo e($syncTimes['account_activity']); ?></p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Contact Activity</p>
                                                    <p class="mb-0 text-sm font-medium"><?php echo e($syncTimes['contact_activity']); ?></p>
                                                </div>
                                                <div class="col-span-12 sm:col-span-6 md:col-span-4">
                                                    <p class="mb-1 text-xs uppercase tracking-wide text-muted">Token Issued</p>
                                                    <p class="mb-0 text-sm font-medium"><?php echo e($syncTimes['token_issued']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <div class="rounded-xl border border-theme-border dark:border-themedark-border p-4">
                                            <h6 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Connection Details</h6>
                                            <dl class="m-0 space-y-2.5">
                                                <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                                    <dt class="mb-0 text-sm text-muted shrink-0">Login URL</dt>
                                                    <dd class="mb-0 text-sm font-medium break-all text-right"><?php echo e($connectionSummary['login_uri'] ?: 'Not set'); ?></dd>
                                                </div>
                                                <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                                    <dt class="mb-0 text-sm text-muted shrink-0">Callback URL</dt>
                                                    <dd class="mb-0 text-sm font-medium break-all text-right"><?php echo e($connectionSummary['callback_uri'] ?: 'Not set'); ?></dd>
                                                </div>
                                                <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                                    <dt class="mb-0 text-sm text-muted shrink-0">Instance URL</dt>
                                                    <dd class="mb-0 text-sm font-medium break-all text-right"><?php echo e($connectionSummary['instance_url'] ?: 'Not set'); ?></dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Connection Settings</h5>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo e(route($portal.'.salesforce.update')); ?>" method="post" class="mb-4">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="mb-3">
                                        <label class="form-label">Client ID</label>
                                        <input type="text" class="form-control" name="client_id" value="<?php echo e(old('client_id', $salesforce->client_id)); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Client Secret</label>
                                        <input type="text" class="form-control" name="client_secret" value="<?php echo e(old('client_secret', $salesforce->client_secret)); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Redirect URI</label>
                                        <input type="url" class="form-control" name="redirect_uri" value="<?php echo e(old('redirect_uri', $salesforce->redirect_uri)); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Login URL</label>
                                        <input type="url" class="form-control" name="login_uri" value="<?php echo e(old('login_uri', $salesforce->login_uri)); ?>">
                                    </div>
                                    <button type="submit" class="btn btn-secondary">Save Settings</button>
                                </form>

                                <p class="text-muted mb-3">Import one company/contact set by email (Contact Email, Primary Contact Email, Contact Email, CT User Email, or Alternate Email). Account must still have Export = TRUE.</p>
                                <form action="<?php echo e(route($portal.'.salesforce.import')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <label class="form-label">Company / Contact Email</label>
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control" placeholder="email@example.com" aria-label="Company Email" aria-describedby="button-addon2" name="Email" required>
                                        <button class="btn btn-primary" type="submit" id="button-addon2">
                                            Import
                                        </button>
                                    </div>
                                </form>

                                <div class="border rounded p-3 mt-4">
                                    <h6 class="mb-2">Discovery Notes</h6>
                                    <p class="mb-2 text-muted">This page is intended for internal staff/admin monitoring only.</p>
                                    <p class="mb-0 text-muted">The Salesforce inspector remains a temporary developer/admin tool and should stay restricted outside discovery/testing.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Legacy Sync Tooling</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2 text-muted">Directory Present: <?php echo e($legacyTooling['present'] ? 'Yes' : 'No'); ?></p>
                                <p class="mb-2 text-muted">Legacy Access Enabled: <?php echo e($legacyTooling['enabled'] ? 'Yes' : 'No'); ?></p>
                                <p class="mb-2 text-muted">Allowed IPs: <?php echo e($legacyTooling['allowed_ips'] ?: 'Not configured'); ?></p>
                                <p class="mb-3 text-muted">Legacy Redirect URI: <?php echo e($legacyTooling['redirect_uri'] ?: 'Not configured'); ?></p>
                                <div class="border rounded p-3">
                                    <p class="mb-2 text-muted">Recommendation: keep this disabled unless a temporary maintenance task explicitly requires it.</p>
                                    <p class="mb-0 text-muted">All normal Salesforce operations should run through the Laravel-managed sync and admin monitor.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-5">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Synced Tickets</h5>
                            </div>
                            <div class="card-body">
                                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                                    <?php $__empty_1 = true; $__currentLoopData = $recentSync['tickets']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <li class="list-group-item">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="grow">
                                                    <h6 class="mb-1"><?php echo e($ticket->name ?: 'Ticket #'.$ticket->id); ?></h6>
                                                    <p class="mb-0 text-muted">
                                                        Company: <?php echo e(optional($ticket->company)->name ?: 'N/A'); ?> |
                                                        Citation: <?php echo e($ticket->citation_no ?: 'N/A'); ?>

                                                    </p>
                                                    <p class="mb-0 mt-1 text-muted">
                                                        Updated: <?php echo e(optional($ticket->updated_at)?->format('M j, Y g:i A') ?: 'N/A'); ?> |
                                                        Salesforce ID: <?php echo e($ticket->sf_id); ?>

                                                    </p>
                                                    <p class="mb-0 mt-1 text-muted">
                                                        Original Points: <?php echo e($ticket->original_points_value !== null ? number_format($ticket->original_points_value, 1) : 'N/A'); ?> |
                                                        Final Points: <?php echo e($ticket->final_points_value !== null ? number_format($ticket->final_points_value, 1) : 'N/A'); ?> |
                                                        Saved: <?php echo e(number_format($ticket->points_saved, 1)); ?>

                                                    </p>
                                                </div>
                                                <a href="<?php echo e(route($portal.'.tickets.edit', $ticket->id)); ?>" class="btn btn-light-primary btn-sm">Open</a>
                                            </div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <li class="list-group-item">No Salesforce-linked tickets found yet.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Sync Alerts</h5>
                            </div>
                            <div class="card-body">
                                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                                    <?php $__empty_1 = true; $__currentLoopData = $syncAlerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <li class="list-group-item text-muted"><?php echo e($alert); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <li class="list-group-item text-muted">No immediate sync warnings detected.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Synced Companies</h5>
                            </div>
                            <div class="card-body">
                                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                                    <?php $__empty_1 = true; $__currentLoopData = $recentSync['companies']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <li class="list-group-item">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="grow">
                                                    <h6 class="mb-1"><?php echo e($company->name); ?></h6>
                                                    <p class="mb-0 text-muted">
                                                        DOT: <?php echo e($company->dot ?: 'N/A'); ?> |
                                                        Email: <?php echo e($company->ct_email ?: 'N/A'); ?>

                                                    </p>
                                                    <p class="mb-0 mt-1 text-muted">
                                                        Updated: <?php echo e(optional($company->updated_at)?->format('M j, Y g:i A') ?: 'N/A'); ?>

                                                    </p>
                                                </div>
                                                <a href="<?php echo e(route($portal.'.companies.edit', $company->id)); ?>" class="btn btn-light-primary btn-sm">Open</a>
                                            </div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <li class="list-group-item">No Salesforce-linked companies found yet.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Synced Attachments</h5>
                            </div>
                            <div class="card-body">
                                <ul class="rounded-lg *:py-3 divide-y divide-inherit border-theme-border dark:border-themedark-border">
                                    <?php $__empty_1 = true; $__currentLoopData = $recentSync['attachments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <li class="list-group-item">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="grow">
                                                    <h6 class="mb-1"><?php echo e($attachment->filename); ?></h6>
                                                    <p class="mb-0 text-muted">
                                                        Ticket: <?php echo e(optional($attachment->ticket)->name ?: 'N/A'); ?> |
                                                        Company: <?php echo e(optional(optional($attachment->ticket)->company)->name ?: 'N/A'); ?>

                                                    </p>
                                                    <p class="mb-0 mt-1 text-muted">
                                                        Updated: <?php echo e(optional($attachment->updated_at)?->format('M j, Y g:i A') ?: 'N/A'); ?> |
                                                        Salesforce ID: <?php echo e($attachment->sf_id); ?>

                                                    </p>
                                                </div>
                                                <?php if(optional($attachment->ticket)->id): ?>
                                                    <a href="<?php echo e(route($portal.'.tickets.edit', $attachment->ticket->id)); ?>" class="btn btn-light-primary btn-sm">Open Ticket</a>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <li class="list-group-item">No Salesforce-linked attachments found yet.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('post-scripts'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\admin\salesforce\index.blade.php ENDPATH**/ ?>