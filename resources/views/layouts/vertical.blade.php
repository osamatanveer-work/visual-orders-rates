<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ $theme ?? 'light' }}" data-topbar-color="{{ $topbar ?? 'dark' }}" dir="{{ $rtl ?? 'ltl' }}">

<head>
    @include('layouts.shared/title-meta', ['title' => $page_title])
    @yield('css')
    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    @if(Request::is("rate-qoutes/all"))
    @vite(['resources/scss/icons.scss', 'resources/js/head-2.js'])
    @else
    @vite(['resources/scss/icons.scss', 'resources/js/head.js'])
    @endif
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
   
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> 
</head>

<body>
    <!-- Begin page -->
    <div id="wrapper">
        @include('layouts.shared/left-sidebar')

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            @include('layouts.shared/topbar')
            <div class="content">
                @if(session('success'))
                <div id="success" >
                <div class="alert alert-success alert-dismissible px-3"   role="alert" style="
                top: 80px;
                position: absolute;
                width: auto%;
                z-index: 9999;
                right: 25px;
                background:#CEEBE6;">
                    <i class="mdi mdi-check-all me-2"></i><strong>Success -</strong>  {{ session('success') }}
                </div>
                </div>
                @endif
            
                @if(session('error'))
                <div id="error" >
                <div class="alert alert-danger px-3" role="alert"  style="
                top: 80px;
                position: absolute;
                width: auto%;
                z-index: 9999;
                right: 25px;
                background:#FCE1E5;">
                    <i class="mdi mdi-alert me-2"></i><strong>Error</strong>  {{ session('error') }}
                </div>
                </div>
                @endif
                <!-- content -->
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
  
    @yield('script')
    @vite(['resources/js/app.js', 'resources/js/layout.js'])
    <script>
          document.addEventListener('DOMContentLoaded', function() {
         setTimeout(function() {
            $("#success").hide();
            }, 3000);

            setTimeout(function() {
            $("#error").hide();
            }, 3000);
          });

    </script>
    
    @stack('scripts')
</body>



</html>
