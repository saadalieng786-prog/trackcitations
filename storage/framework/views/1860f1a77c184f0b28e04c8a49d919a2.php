<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" dir="ltr" data-pc-theme_contrast="" data-pc-theme="light">
<!-- [Head] start -->

<head>
    <title><?php echo e(env('APP_NAME')); ?> | Citation Tracking System</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="description"
        content="Able Pro is trending dashboard template made using Bootstrap 5 design framework. Able Pro is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies."
    />
    <meta
        name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard"
    />
    <meta name="author" content="<?php echo e(env('APP_NAME')); ?>" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- [Favicon] icon -->
    <link rel="icon" href="<?php echo e(asset('images/favicon.svg')); ?>" type="image/x-icon" />
    <!-- [Font] Family -->
    <link rel="stylesheet" href="<?php echo e(asset('fonts/inter/inter.css')); ?>" id="main-font-link" />
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="<?php echo e(asset('fonts/phosphor/duotone/style.css')); ?>" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="<?php echo e(asset('fonts/tabler-icons.min.css')); ?>" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="<?php echo e(asset('fonts/feather.css')); ?>" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="<?php echo e(asset('fonts/fontawesome.css')); ?>" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="<?php echo e(asset('fonts/material.css')); ?>" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>" id="main-style-link" />
    <link rel="stylesheet" href="<?php echo e(asset('css/custom.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('css/plugins/choices.min.css')); ?>" />

    
    <script>
        (function() {
            var saved = localStorage.getItem('theme');
            if (saved === 'dark') {
                document.documentElement.setAttribute('data-pc-theme', 'dark');
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <!-- [Page specific CSS] start -->
    <?php echo $__env->yieldContent('css'); ?>
    <!-- [Page specific CSS] end -->
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
<!-- [ Pre-loader ] start -->
<div class="loader-bg fixed inset-0 bg-white z-[1034]">
    <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
        <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 transition-[transform_0.2s_linear] origin-left animate-[2.1s_cubic-bezier(0.65,0.815,0.735,0.395)_0s_infinite_normal_none_running_loader-animate]"></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->
<?php echo $__env->yieldContent('body'); ?>
<script>
    var layoutValue = 'vertical';
</script>
<!-- Required Js -->
<?php echo $__env->yieldContent('pre-scripts'); ?>
<script src="<?php echo e(asset('js/plugins/simplebar.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/plugins/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/icon/custom-icon.js')); ?>"></script>
<script src="<?php echo e(asset('js/plugins/feather.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/plugins/component.js')); ?>"></script>
<script src="<?php echo e(asset('js/plugins/theme.js')); ?>"></script>
<script src="<?php echo e(asset('js/plugins/script.js')); ?>"></script>
<script src="<?php echo e(asset('js/plugins/sweetalert2.all.min.js')); ?>"></script>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
    <?php if(session('success')): ?>
        Toast.fire({
            icon: 'success',
            title: '<?php echo e(session('success')); ?>'
        });
    <?php endif; ?>
    <?php if(session('error')): ?>
    Toast.fire({
        icon: 'error',
        title: '<?php echo e(session('error')); ?>'
    });
    <?php endif; ?>
</script>
<?php echo $__env->yieldContent('post-scripts'); ?>


<script>
    // Restore saved theme from localStorage (dark or light), default light
    layout_change(localStorage.getItem('theme') === 'dark' ? 'dark' : 'light');
</script>

<script>
    layout_theme_contrast_change('false');
</script>

<script>
    change_box_container('false');
</script>

<script>
    layout_caption_change('true');
</script>

<script>
    layout_rtl_change('false');
</script>

<script>
    preset_change('preset-1');
</script>

<script>
    main_layout_change('vertical');
</script>
<script>
    document.addEventListener('click', function (e) {
       const markAllReadBtn = e.target.closest('#markAllRead');
       if (markAllReadBtn) {
           fetch('<?php echo e(route('notifications.markAllRead')); ?>', {
               method: 'POST',
               headers: {
                   'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                   'Content-Type': 'application/json',
               },
           })
               .then(response => response.json())
               .then(data => {
                   let notificationContainer = document.querySelector('#notificationContainer');
                   notificationContainer.innerHTML = '<div class="flex justify-center flex-col align-middle items-center">'+
                       '<span class="text-[50px] text-secondary icon-bold fa fa-bell-slash grow mb-6"></span>'+
                   '<span class="text-muted">'+
                                       'You\'re all caught up! No new notifications at the moment.'+
                                    '</span>'+
               '</div>';
                   markAllReadBtn.classList.add('hidden');
                   var countEl = document.querySelector('#notificationCount');
                   if (countEl) {
                       countEl.textContent = '0';
                       countEl.classList.add('hidden');
                   }
               })
               .catch(error => console.error('Error:', error));

       }
       const notificationItem = e.target.closest('.notificationItem');
        if (notificationItem) {
            let notificationId = notificationItem.dataset.notificationId;
            let notificationUrl = notificationItem.dataset.url;
            console.log(notificationId);
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    location.href = notificationUrl;
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>
<script src="<?php echo e(asset('js/plugins/choices.min.js')); ?>"></script>
<script>
    (function () {
        var sessionCompaniesEl = document.querySelector('#sessionCompanies');
        if (!sessionCompaniesEl || typeof Choices === 'undefined') {
            return;
        }

        var filterMenu = document.querySelector('.tc-company-filter-menu');
        if (filterMenu) {
            filterMenu.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        var sessionCompaniesChoices = new Choices(sessionCompaniesEl, {
            placeholder: true,
            placeholderValue: 'Select companies',
            searchEnabled: true,
            searchPlaceholderValue: 'Search companies...',
            removeItemButton: true,
            itemSelectText: '',
            shouldSort: false,
            position: 'bottom',
            allowHTML: false,
        });

        var selectedSessionCompanies = JSON.parse('<?php echo json_encode(array_map("strval", session("active_company_ids", []))); ?>');

        sessionCompaniesChoices.setChoices(function () {
            return fetch('<?php echo e(route('api.company.index')); ?>')
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    return data.map(function (company) {
                        return {
                            value: company.id,
                            label: company.name,
                            selected: selectedSessionCompanies.includes(String(company.id))
                        };
                    });
                });
        });

        var resetBtn = document.getElementById('sessionCompaniesReset');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                sessionCompaniesChoices.removeActiveItems();
                document.getElementById('sessionCompaniesForm')?.submit();
            });
        }
    })();
</script>
<script>
    (function () {
        function closeMobileSidebar() {
            var sidebar = document.querySelector('.pc-sidebar');
            if (!sidebar) return;
            sidebar.classList.remove('mob-sidebar-active');
            var overlay = sidebar.querySelector('.pc-menu-overlay');
            if (overlay) overlay.remove();
        }

        function openMobileSidebar() {
            var sidebar = document.querySelector('.pc-sidebar');
            if (!sidebar) return;
            sidebar.classList.add('mob-sidebar-active');
            if (!sidebar.querySelector('.pc-menu-overlay')) {
                var overlay = document.createElement('div');
                overlay.className = 'pc-menu-overlay';
                overlay.addEventListener('click', closeMobileSidebar);
                sidebar.appendChild(overlay);
            }
        }

        window.toggleSidebarMenu = function (e) {
            if (e) e.preventDefault();
            var sidebar = document.querySelector('.pc-sidebar');
            if (!sidebar) return;

            if (window.innerWidth < 1024) {
                if (sidebar.classList.contains('mob-sidebar-active')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
                return;
            }

            sidebar.classList.toggle('pc-sidebar-hide');
        };

        function bindSidebarToggle() {
            var btn = document.getElementById('sidebar-hide');
            if (!btn || btn.dataset.tcSidebarBound === '1') return;
            btn.dataset.tcSidebarBound = '1';
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                window.toggleSidebarMenu(e);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', bindSidebarToggle);
        } else {
            bindSidebarToggle();
        }
    })();

    // ── Ctrl / Cmd + K → focus top header search input ───────────
    document.addEventListener('keydown', function (e) {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            var inp = document.getElementById('topHeaderSearchInput');
            if (inp) { inp.focus(); inp.select(); }
        }
    });
</script>
<script src="<?php echo e(asset('js/ticket-export.js')); ?>?v=<?php echo e(filemtime(public_path('js/ticket-export.js'))); ?>"></script>
</body>
<!-- [Body] end -->

</html>
<?php /**PATH C:\MAMP\htdocs\trackcitations\resources\views/layout/partials/body.blade.php ENDPATH**/ ?>