<!DOCTYPE html>
<html lang="en" data-layout-mode="detached" data-bs-theme="{{ $theme ?? 'light' }}" dir="{{ $rtl ?? 'ltl' }}">

<head>
    @include('layouts.shared/title-meta', ['title' => $page_title])
    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])

    @yield('css')
    @vite(['resources/scss/icons.scss', 'resources/js/head.js'])
</head>

<body @yield('body-extra')>

    <!-- Begin page -->
    <div id="wrapper">

        @include('layouts.shared/left-sidebar')

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            @include('layouts.shared/topbar')
            <div class="content">
                @yield('content')
            </div>
            <!-- content -->
            @include('layouts.shared/footer')
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    @include('layouts.shared/right-sidebar')

    @include('layouts.shared/footer-script')
    @vite(['resources/js/app.js', 'resources/js/layout.js'])
</body>

</html>
