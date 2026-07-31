@extends('layout.partials.body')

@section('body')
    <!-- [ Sidebar Menu ] start -->
    @include('layout.partials.sidebar')
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    @include('layout.partials.header')
    <!-- [ Header ] end -->
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            @include('layout.partials.breadcrumb')
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->

            <div class="grid grid-cols-12 gap-x-6">
                @yield('content')
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    <!-- [ Main Content ] end -->
    @include('layout.partials.footer')

@endsection
