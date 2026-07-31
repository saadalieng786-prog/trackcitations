<?php $__env->startSection('content'); ?>
    <?php
        $portal = auth()->user()->portalRoutePrefix();
    ?>
    <div class="col-span-12">
        <div class="tab-content">
            <div class="block tab-pane" id="citation">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Synced Companies</p>
                                <h4 class="mb-0"><?php echo e($syncStats['companies']); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Synced Tickets</p>
                                <h4 class="mb-0"><?php echo e($syncStats['tickets']); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Synced Attachments</p>
                                <h4 class="mb-0"><?php echo e($syncStats['attachments']); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Points Saved from SF Tickets</p>
                                <h4 class="mb-0"><?php echo e(number_format($syncStats['points_saved'], 1)); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">SF Tickets With Point Data</p>
                                <h4 class="mb-0"><?php echo e($syncStats['tickets_with_points']); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Original Points Total</p>
                                <h4 class="mb-0"><?php echo e(number_format($syncStats['original_points_total'], 1)); ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-3 md:col-span-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1">Final Points Total</p>
                                <h4 class="mb-0"><?php echo e(number_format($syncStats['final_points_total'], 1)); ?></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-8">
                        <div class="card">
                            <div class="card-header flex justify-between items-center gap-3">
                                <h5 class="text-primary text-[28px] font-bold mb-0">Salesforce Sync Monitor</h5>
                                <div class="flex items-center gap-2">
                                    <a href="<?php echo e(route('salesforce.oauth')); ?>" class="btn btn-primary btn-sm">Reconnect OAuth</a>
                                    <span class="inline-flex items-center rounded-md px-3 py-1 text-xs font-medium ring-1 ring-inset <?php echo e($salesforce->statusBadgeClass()); ?>">
                                        Status: <?php echo e($salesforce->statusLabel()); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if($salesforce->status == \App\Models\SalesForce::STATUS_FAILED && $salesforce->reason): ?>
                                    <div class="alert alert-danger mb-4">
                                        <strong>Last failure:</strong> <?php echo e($salesforce->reason); ?>

                                    </div>
                                <?php endif; ?>

                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="border rounded p-3 h-full">
                                            <h6 class="mb-3">Connection Health</h6>
                                            <p class="mb-2 text-muted">Configured: <?php echo e($salesforce->isConfigured() ? 'Yes' : 'No'); ?></p>
                                            <p class="mb-2 text-muted">Access Token Stored: <?php echo e($connectionSummary['has_access_token'] ? 'Yes' : 'No'); ?></p>
                                            <p class="mb-2 text-muted">Refresh Token Stored: <?php echo e($connectionSummary['has_refresh_token'] ? 'Yes' : 'No'); ?></p>
                                            <p class="mb-0 text-muted">Last Settings Update: <?php echo e($syncTimes['updated'] ?: 'N/A'); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="border rounded p-3 h-full">
                                            <h6 class="mb-3">Sync Schedule Targets</h6>
                                            <p class="mb-2 text-muted">Expected pull interval: every 15 minutes</p>
                                            <p class="mb-2 text-muted">Current scheduled command: every 15 minutes</p>
                                            <p class="mb-0 text-muted">Direction: pull from Salesforce only</p>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <div class="border rounded p-3">
                                            <h6 class="mb-3">Last Sync Activity</h6>
                                            <div class="grid grid-cols-12 gap-3">
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">Records Sync</p>
                                                    <p class="mb-0"><?php echo e($syncTimes['records']); ?></p>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">Attachment Sync</p>
                                                    <p class="mb-0"><?php echo e($syncTimes['attachments']); ?></p>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">File Sync</p>
                                                    <p class="mb-0"><?php echo e($syncTimes['files']); ?></p>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">Account Activity Sync</p>
                                                    <p class="mb-0"><?php echo e($syncTimes['account_activity']); ?></p>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">Contact Activity Sync</p>
                                                    <p class="mb-0"><?php echo e($syncTimes['contact_activity']); ?></p>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <p class="mb-1 text-muted">Token Issued</p>
                                                    <p class="mb-0"><?php echo e($syncTimes['token_issued']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <div class="border rounded p-3">
                                            <h6 class="mb-3">Connection Details</h6>
                                            <p class="mb-2 text-muted">Login URL: <?php echo e($connectionSummary['login_uri'] ?: 'Not set'); ?></p>
                                            <p class="mb-2 text-muted">Callback URL: <?php echo e($connectionSummary['callback_uri'] ?: 'Not set'); ?></p>
                                            <p class="mb-0 text-muted">Instance URL: <?php echo e($connectionSummary['instance_url'] ?: 'Not set'); ?></p>
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

                                <p class="text-muted mb-3">Pull a specific company/contact set by email for support review or sync troubleshooting.</p>
                                <form action="<?php echo e(route($portal.'.salesforce.import')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <label class="text-lg font-bold">Company Email</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Company Email" aria-label="Company Email" aria-describedby="button-addon2" name="Email">
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

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\PHP\trackcitations\resources\views\admin\salesforce\index.blade.php ENDPATH**/ ?>