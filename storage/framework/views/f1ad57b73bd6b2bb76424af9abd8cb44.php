<header class="pc-header">
    <?php
        $unreadNotificationCount = auth()->user()->unreadNotifications()->count();
        $unreadNotifications     = auth()->user()->unreadNotifications()->latest()->limit(10)->get();
        $userInitials            = collect(explode(' ', Auth::user()->name))->map(fn($p)=>strtoupper(substr($p,0,1)))->take(2)->implode('');
    ?>

    <div class="header-wrapper flex items-center justify-between px-6 w-full h-[64px]">

        
        <div class="flex items-center gap-4 grow max-w-[600px]">
            <a href="#!" class="pc-head-link text-slate-500 hover:text-slate-700" id="sidebar-hide" title="Toggle sidebar" onclick="toggleSidebarMenu(event)">
                <i class="ti ti-menu-2 text-xl"></i>
            </a>

            
            <?php
                $portal = auth()->user()->portalRoutePrefix();
                $searchRoute = route($portal.'.tickets.index');
            ?>
            <form action="<?php echo e($searchRoute); ?>" method="GET" class="tc-top-search-wrap hidden md:block grow m-0">
                <i class="ti ti-search tc-top-search-icon"></i>
                <input
                    type="text"
                    name="q"
                    id="topHeaderSearchInput"
                    class="tc-top-search-input"
                    placeholder="Search tickets, companies, drivers... (Press Enter)"
                    value="<?php echo e(request('q')); ?>"
                >
                <span class="tc-top-search-kbd cursor-pointer" onclick="document.getElementById('topHeaderSearchInput')?.focus()">⌘ K</span>
            </form>
        </div>

        
        <div class="flex items-center gap-2">

            
            <?php if(auth()->user()->isInternalAdmin() || auth()->user()->isCompanyAdmin()): ?>
            <div class="dropdown">
                <a class="pc-head-link dropdown-toggle" data-pc-toggle="dropdown" href="#" role="button" title="Filter by Company">
                    <svg class="pc-icon w-5 h-5"><use xlink:href="#custom-layer"></use></svg>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-4 w-[320px] rounded-xl shadow-lg border border-slate-200">
                    <div class="mb-3">
                        <h6 class="font-bold text-slate-800 text-sm mb-1">Filter by Company</h6>
                        <p class="text-xs text-slate-400">Select companies to restrict dashboard view</p>
                    </div>
                    <form action="<?php echo e(route('api.setSessionCompanies')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <select class="form-control" name="company_ids[]" id="sessionCompanies" data-trigger multiple></select>
                        <div class="flex gap-2 mt-3 pt-3 border-t border-slate-100">
                            <button class="btn btn-outline-secondary btn-sm grow" type="button" onclick="this.closest('form').submit()">Reset</button>
                            <button class="btn btn-primary btn-sm grow" type="submit">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="dropdown">
                <a class="pc-head-link dropdown-toggle" data-pc-toggle="dropdown" href="#" role="button" title="Theme">
                    <svg class="pc-icon w-5 h-5"><use xlink:href="#custom-sun-1"></use></svg>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-2 w-[160px] rounded-xl shadow-lg border border-slate-200">
                    <a href="#!" class="dropdown-item rounded-lg text-xs py-2" onclick="layout_change('light')">
                        <svg class="pc-icon w-4 h-4 me-2"><use xlink:href="#custom-sun-1"></use></svg> Light Mode
                    </a>
                    <a href="#!" class="dropdown-item rounded-lg text-xs py-2" onclick="layout_change('dark')">
                        <svg class="pc-icon w-4 h-4 me-2"><use xlink:href="#custom-moon"></use></svg> Dark Mode
                    </a>
                </div>
            </div>

            
            <div class="dropdown">
                <a class="pc-head-link dropdown-toggle relative" data-pc-toggle="dropdown" href="#" role="button" title="Notifications">
                    <svg class="pc-icon w-5 h-5"><use xlink:href="#custom-notification"></use></svg>
                    <?php if($unreadNotificationCount > 0): ?>
                        <span id="notificationCount" class="tc-notification-badge">
                            <?php echo e($unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount); ?>

                        </span>
                    <?php else: ?>
                        <span id="notificationCount" class="tc-notification-badge hidden">0</span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-end w-[360px] rounded-xl shadow-xl border border-slate-200 p-0 overflow-hidden">
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h6 class="font-bold text-slate-800 text-sm m-0">Notifications</h6>
                            <p class="text-xs text-slate-400 m-0"><?php echo e($unreadNotificationCount); ?> unread</p>
                        </div>
                        <?php if($unreadNotificationCount): ?>
                            <a id="markAllRead" href="#!" class="text-xs font-semibold text-indigo-600 hover:underline">Mark all read</a>
                        <?php endif; ?>
                    </div>
                    <div id="notificationContainer" class="max-h-[300px] overflow-y-auto p-3">
                        <?php $__empty_1 = true; $__currentLoopData = $unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-3 mb-2 rounded-lg border border-slate-100 hover:bg-slate-50 cursor-pointer notificationItem" data-url="<?php echo e($notification->data['url']); ?>">
                                <div class="font-semibold text-xs text-slate-800"><?php echo e($notification->data['title']); ?></div>
                                <div class="text-[11px] text-slate-400 mt-1"><?php echo e(\Carbon\Carbon::parse($notification->created_at)->diffForHumans()); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="py-6 text-center text-slate-400 text-xs">No new notifications</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="dropdown ms-2">
                <a class="dropdown-toggle arrow-none" data-pc-toggle="dropdown" href="#" role="button">
                    <div class="tc-header-user-avatar">
                        <?php echo e($userInitials ?: 'U'); ?>

                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end w-[240px] rounded-xl shadow-xl border border-slate-200 p-2">
                    <div class="px-3 py-2 border-b border-slate-100 mb-1">
                        <div class="font-bold text-xs text-slate-800"><?php echo e(Auth::user()->name); ?></div>
                        <div class="text-[11px] text-slate-400 truncate"><?php echo e(Auth::user()->email); ?></div>
                    </div>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="dropdown-item rounded-lg text-xs py-2 flex items-center gap-2">
                        <i class="ti ti-user text-sm"></i> My Profile
                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item rounded-lg text-xs py-2 text-red-600 flex items-center gap-2 w-full text-left">
                            <i class="ti ti-logout text-sm"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</header>
<?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views/layout/partials/header.blade.php ENDPATH**/ ?>