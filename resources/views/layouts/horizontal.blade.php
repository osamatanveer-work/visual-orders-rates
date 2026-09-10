<!DOCTYPE html>
<html lang="en" data-layout="horizontal" data-bs-theme="{{ $theme ?? 'light' }}" dir="{{ $rtl ?? 'ltl' }}">

<head>
    @include('layouts.shared/title-meta', ['title' => $page_title])
    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])


    @vite(['resources/scss/config/default/bootstrap.scss', 'resources/scss/icons.scss', 'resources/js/head.js'])

</head>

<body @yield('body-extra')>

    <!-- Begin page -->
    <div id="wrapper">

        @include('layouts.shared/horizontal')

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            @include('layouts.shared/topbar')
            <div class="content">
                @yield('content')
            </div>
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
