<!DOCTYPE html>
<html lang="en" data-sidebar-icon="twotones" dir="{{ $rtl ?? 'ltl' }}">

<head>
    @include('layouts.shared/title-meta', ['title' => $page_title])
    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])
{{--    <script src="{{ Vite::asset('node_modules/jquery/dist/jquery.js') }}"></script>--}}
{{--    <script src="{{ Vite::asset('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>--}}
    @vite(['resources/js/head.js'])

    @vite(['resources/scss/config/default/bootstrap.scss', 'resources/scss/icons.scss'])
</head>

<body class="loading" @yield('body-extra')>
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
