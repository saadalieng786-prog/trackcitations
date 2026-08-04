<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="preset-1" data-pc-sidebar-caption="true" data-pc-layout="vertical" data-pc-direction="ltr" dir="ltr" data-pc-theme_contrast="" data-pc-theme="light">
<!-- [Head] start -->

<head>
    <title>{{ env('APP_NAME') }} | Citation Tracking System</title>
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
    <meta name="author" content="{{ env('APP_NAME') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/x-icon" />
    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('fonts/inter/inter.css') }}" id="main-font-link" />
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="{{ asset('fonts/phosphor/duotone/style.css') }}" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/plugins/choices.min.css') }}" />

    {{-- Apply saved theme BEFORE render to prevent flash of wrong theme --}}
    <script>
        (function() {
            var saved = localStorage.getItem('theme');
            if (saved === 'dark') {
                document.documentElement.setAttribute('data-pc-theme', 'dark');
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- [Page specific CSS] start -->
    @yield('css')
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
@yield('body')
<script>
    var layoutValue = 'vertical';
</script>
<!-- Required Js -->
@yield('pre-scripts')
<script src="{{ asset('js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('js/icon/custom-icon.js') }}"></script>
<script src="{{ asset('js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('js/plugins/component.js') }}"></script>
<script src="{{ asset('js/plugins/theme.js') }}"></script>
<script src="{{ asset('js/plugins/script.js') }}"></script>
<script src="{{ asset('js/plugins/sweetalert2.all.min.js') }}"></script>
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
    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        });
    @endif
    @if(session('error'))
    Toast.fire({
        icon: 'error',
        title: '{{ session('error') }}'
    });
    @endif
</script>
@yield('post-scripts')


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
           fetch('{{ route('notifications.markAllRead') }}', {
               method: 'POST',
               headers: {
                   'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
<script src="{{ asset('js/plugins/choices.min.js') }}"></script>
<script>
    (function () {
        var sessionCompaniesEl = document.querySelector('#sessionCompanies');
        if (!sessionCompaniesEl || typeof Choices === 'undefined') {
            return;
        }

        var sessionCompaniesChoices = new Choices(sessionCompaniesEl, {
            placeholder: true,
            placeholderValue: 'Company Name',
            shouldSort: false,
        });
        let selectedSessionCompanies = JSON.parse('{!! json_encode(session('active_company_ids')) !!}');
        sessionCompaniesChoices.setChoices(function () {
            return fetch('{{ route('api.company.index') }}')
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    return [
                        ...data.map(function (company) {
                            return {
                                value: company.id,
                                label: company.name,
                                selected: selectedSessionCompanies?.includes(company.id + '')
                            };
                        })
                    ];
                });
        });
    })();
</script>
<script>
    // ── Sidebar collapse toggle (hamburger button) ────────────────
    function toggleSidebarMenu(e) {
        if (e) e.preventDefault();
        var sidebar = document.querySelector('.pc-sidebar');
        if (!sidebar) return;
        // style.css sibling selectors handle header left + container margin automatically
        sidebar.classList.toggle('pc-sidebar-hide');
    }

    // ── Ctrl / Cmd + K → focus top header search input ───────────
    document.addEventListener('keydown', function (e) {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            var inp = document.getElementById('topHeaderSearchInput');
            if (inp) { inp.focus(); inp.select(); }
        }
    });
</script>
<script src="{{ asset('js/ticket-export.js') }}?v={{ filemtime(public_path('js/ticket-export.js')) }}"></script>
</body>
<!-- [Body] end -->

</html>
