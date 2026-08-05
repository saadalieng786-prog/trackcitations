<?php $__env->startSection('content'); ?>
    <?php $portal = auth()->user()->portalRoutePrefix(); ?>
    <div class="col-span-12">

        
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 m-0 tracking-tight">Pending Tickets list</h1>
                <div class="text-xs text-slate-500 mt-1">
                    <a href="<?php echo e(route('dashboard')); ?>" class="text-slate-500 hover:text-indigo-600">Dashboard</a>
                    <span class="mx-1.5 text-slate-300">/</span>
                    <a href="<?php echo e(route($portal.'.tickets.index')); ?>" class="text-slate-500 hover:text-indigo-600">Tickets</a>
                    <span class="mx-1.5 text-slate-300">/</span>
                    <span class="font-medium text-slate-700">Pending</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="#!" 
                   class="js-download-tickets btn btn-outline-secondary btn-sm flex items-center gap-2"
                   title="Export tickets in background">
                    <i class="ti ti-download text-base"></i> Download Tickets
                </a>
                <a href="<?php echo e(route($portal.'.tickets.create')); ?>" class="btn btn-primary btn-sm flex items-center gap-2">
                    <i class="ti ti-plus text-base"></i> Create Ticket
                </a>
            </div>
        </div>

        
        <div class="card p-0 overflow-hidden">
            <table class="table tc-clean-table yajra-datatable w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date Received</th>
                        <th>State</th>
                        <th>Company Name</th>
                        <th>Indicator</th>
                        <th>DVER</th>
                        <th>Updated</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($ticket->id); ?></td>
                            <td>
                                <div class="font-semibold text-slate-900"><?php echo e($ticket->name); ?></div>
                            </td>
                            <td><?php echo e($ticket->date_issued ?? '—'); ?></td>
                            <td><?php echo e($ticket->state ?? '—'); ?></td>
                            <td><?php echo e($ticket->company?->name ?? '—'); ?></td>
                            <td>
                                <span class="tc-badge-soft-orange"><?php echo e($ticket->indicator ?? 'Pending'); ?></span>
                            </td>
                            <td>
                                <?php if($ticket->isDverDataq()['DVER']): ?>
                                    <span class="ti ti-circle-check-filled text-emerald-500 text-lg"></span>
                                <?php else: ?>
                                    <span class="text-slate-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::parse($ticket->updated_at)->diffForHumans()); ?></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="<?php echo e(route($portal.'.tickets.show', $ticket->id)); ?>" 
                                       class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-slate-100 transition-colors"
                                       title="View Ticket">
                                        <i class="ti ti-eye text-lg"></i>
                                    </a>
                                    <a href="<?php echo e(route($portal.'.tickets.edit', $ticket->id)); ?>" 
                                       class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-slate-100 transition-colors"
                                       title="Edit Ticket">
                                        <i class="ti ti-edit text-lg"></i>
                                    </a>
                                    <form action="<?php echo e(route($portal.'.tickets.destroy', $ticket->id)); ?>" method="POST" class="inline delete-ticket-form">
                                        <?php echo method_field('DELETE'); ?>
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors"
                                                title="Delete Ticket">
                                            <i class="ti ti-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/dataTables.bootstrap5.min.css')); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('post-scripts'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="<?php echo e(asset('js/plugins/dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/plugins/dataTables.bootstrap5.min.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            $('.yajra-datatable').DataTable({
                paging: true,
                autoWidth: false,
                dom: "<'dt-controls-bar'l f><'tc-table-scroll-container't><'dt-footer-bar'i p>",
                order: [[0, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search pending tickets...",
                    lengthMenu: "_MENU_ entries per page"
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views\admin\tickets\pending.blade.php ENDPATH**/ ?>