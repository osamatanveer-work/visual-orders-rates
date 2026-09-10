<!DOCTYPE html>
<html lang="en">

<head>

    @include('layouts.shared/title-meta', ['title' => 'Forgot Password'])
    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])

</head>

<body class="loading authentication-bg authentication-bg-pattern">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <div class="auth-brand">
                                    <a href="{{ route('any', 'index') }}" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <img src="{{ Vite::asset('resources/images/logo-dark.png') }}" alt="" height="22">
                                        </span>
                                    </a>

                                    <a href="{{ route('any', 'index') }}" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img src="{{ Vite::asset('resources/images/logo-light.png') }}" alt="" height="22">
                                        </span>
                                    </a>
                                </div>
                                <p class="text-muted mb-4 mt-3">Enter your email address and we'll send you an email with instructions to reset your password.</p>
                            </div>

                            <form action="#">

                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email address</label>
                                    <input class="form-control" type="email" id="emailaddress" required="" placeholder="Enter your email">
                                </div>

                                <div class="text-center d-grid">
                                    <button class="btn btn-primary" type="submit"> Reset Password </button>
                                </div>

                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-white-50">Back to <a href="{{ route('second', ['auth', 'login']) }}" class="text-white ms-1"><b>Log in</b></a></p>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


    <footer class="footer footer-alt">
        2015 -
        <script>
            document.write(new Date().getFullYear())
        </script> &copy; UBold theme by <a href="" class="text-white-50">Coderthemes</a>
    </footer>

    @include('layouts.shared/footer-script')

</body>

</html>
