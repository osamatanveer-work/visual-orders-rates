<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/title-meta', ['title' => 'Home'])
    @vite(['resources/scss/config/default/bootstrap.scss', 'resources/scss/icons.scss', 'resources/scss/landing.scss'])
</head>

<body>

    <div class="alert alert-warning text-dark py-2 border-0 mb-0 rounded-0 text-center" role="alert">
        Ubold v3.0 is available now! It has a refreshed look and flexibility!
    </div>

    <!--Navbar Start-->
    <nav class="navbar navbar-expand-lg defaultscroll navbar-sticky navbar-light border-0 shadow-none nav-sticky navbar-custom z-3" style="z-index: 1000;">
        <div class="container">
            <!-- LOGO -->
            <a class="logo" href="javascript:void(0);">
                <img src="{{ Vite::asset('resources/images/logo-light.png') }}" alt="" class="logo-dark" height="22">
                <img src="{{ Vite::asset('resources/images/logo-dark.png') }}" alt="" class="logo-light" height="22">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fe-menu"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">

                <ul class="navbar-nav mx-auto navbar-center">
                    <li class="nav-item active">
                        <a href="#home" class="nav-link active">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#demos" class="nav-link">Demos</a>
                    </li>
                    <li class="nav-item">
                        <a href="#faq" class="nav-link">Faqs</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto navbar-center">
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">Sign Up</a>
                    </li>
                </ul>
                <a class="btn btn-danger" href="https://1.envato.market/uboldlaravel" target="_blank">
                    <i class="fe-shopping-bag mr-1"></i>
                    Download Now
                </a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- START HERO -->
    <section class="position-relative overflow-hidden m-t-80" id="home">
        <div class="overlay-1"></div>
        <div class="container pt-10 pb-6">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="pr-lg-5">
                        <div class="badge badge-primary f-14">Version v3.0</div>
                        <h2 class="mb-4 mt-3">
                            The Most Complete Responsive Web UI Kit & Dashboard Template
                        </h2>
                        <p class="mb-4 pt-2 f-16">Ubold is a fully featured dashboard and admin template comes with tones of well designed UI elements, components, widgets and applications and secondary pages.</p>
                        <a href="#demos" class="btn btn-primary btn-lg cursor-pointer"><i class="fe-eye mr-1"></i> Preview <i class="fe-arrow-right ml-1"></i></a>
                    </div>
                </div>
                <div class="col-md-7">
                    <a href="layouts/default/index.html" target="_blank">
                        <img src="{{ Vite::asset('resources/images/landing/main.png') }}" class="img-fluid shadow-lg animate" />
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- END HERO -->

    <!-- available demos start -->
    <section class="section pb-6" id="demos">
        <div class="container pb-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <h2>Multiple Demos with flexible layouts</h2>
                        <p class="text-muted mt-3 mb-5">There are six different demos available now with multiple layout options to cater the needs of any modern web application.</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="shadow bg-white mt-4 p-2 rounded">
                        <a href="{{ route('any', 'index') }}" class="text-dark">
                            <img src="{{ Vite::asset('resources/images/landing/demo/layout-1.png') }}" alt="" class="img-fluid mx-auto d-block">
                            <h5 class="mb-0 py-2 text-center">Default (Vertical)</h5>
                            <div class="my-2 text-center">
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('any', 'index') }}" target="_blank">Light</a>
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'default-dark']) }}" target="_blank">Dark</a>
{{--                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'default-rtl']) }}" target="_blank">RTL</a>--}}
                            </div>
                        </a>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-lg-4 col-md-6">
                    <div class="shadow bg-white mt-4 p-2 animate rounded">
                        <a href="{{ route('second', ['demos', 'creative-horizontal']) }}" class="text-dark">
                            <img src="{{ Vite::asset('resources/images/landing/demo/layout-2.png') }}" alt="" class="img-fluid mx-auto d-block">
                            <h5 class="mb-0 py-2 text-center">Creative (Horizontal)</h5>
                            <div class="my-2 text-center">
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'creative-horizontal']) }}" target="_blank">Light</a>
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'creative-horizontal-dark']) }}" target="_blank">Dark</a>
{{--                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'creative-horizontal-rtl']) }}" target="_blank">RTL</a>--}}
                            </div>
                        </a>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-lg-4 col-md-6">
                    <div class="shadow bg-white mt-4 p-2 animate rounded">
                        <a href="{{ route('second', ['demos', 'material']) }}" class="text-dark">
                            <img src="{{ Vite::asset('resources/images/landing/demo/layout-3.png') }}" alt="" class="img-fluid mx-auto d-block">
                            <h5 class="mb-0 py-2 text-center">Material</h5>
                            <div class="my-2 text-center">
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'material']) }}" target="_blank">Light</a>
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'material-dark']) }}" target="_blank">Dark</a>
{{--                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'material-rtl']) }}" target="_blank">RTL</a>--}}
                            </div>
                        </a>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-lg-4 col-md-6">
                    <div class="shadow bg-white mt-4 p-2 animate rounded">
                        <a href="{{ route('second', ['demos', 'modern-detached']) }}" class="text-dark">
                            <img src="{{ Vite::asset('resources/images/landing/demo/layout-4.png') }}" alt="" class="img-fluid mx-auto d-block">
                            <h5 class="mb-0 py-2 text-center">Modern (Detached)</h5>
                            <div class="my-2 text-center">
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'modern-detached']) }}" target="_blank">Light</a>
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'modern-detached-dark']) }}" target="_blank">Dark</a>
{{--                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'modern-detached-rtl']) }}" target="_blank">RTL</a>--}}
                            </div>
                        </a>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-lg-4 col-md-6">
                    <div class="shadow bg-white mt-4 p-2 animate rounded">
                        <a href="{{ route('second', ['demos', 'saas-two-column']) }}" class="text-dark">
                            <img src="{{ Vite::asset('resources/images/landing/demo/layout-6.png') }}" alt="" class="img-fluid mx-auto d-block">
                            <h5 class="mb-0 py-2 text-center">Saas (Two-Column)</h5>
                            <div class="my-2 text-center">
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'saas-two-column']) }}" target="_blank">Light</a>
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'saas-two-column-dark']) }}" target="_blank">Dark</a>
{{--                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'saas-two-column-rtl']) }}" target="_blank">RTL</a>--}}
                            </div>
                        </a>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-lg-4 col-md-6">
                    <div class="shadow bg-white mt-4 p-2 animate rounded">
                        <a href="{{ route('second', ['demos', 'purple']) }}" class="text-dark">
                            <img src="{{ Vite::asset('resources/images/landing/demo/layout-5.png') }}" alt="" class="img-fluid mx-auto d-block">
                            <h5 class="mb-0 py-2 text-center">Purple</h5>
                            <div class="my-2 text-center">
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'purple']) }}" target="_blank">Light</a>
                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'purple-dark']) }}" target="_blank">Dark</a>
{{--                                <a class="btn btn-success font-weight-bold btn-sm" href="{{ route('second', ['demos', 'purple-rtl']) }}" target="_blank">RTL</a>--}}
                            </div>
                        </a>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12">
                    <div class="text-center mt-5">
                        <a href="{{ route('any', 'index') }}" class="btn btn-primary btn-lg">More <i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- end container-fluid -->
    </section>
    <!-- available demos end -->

    <!-- START FAQ -->
    <section class="section" id="faq">
        <div class="container pt-6 pb-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <h2>Frequently Asked Questions</h2>
                        <p class="text-muted mt-1">
                            Here are some of the basic types of questions for our customers
                        </p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div id="faqContent">

                        <div class="card mb-3 shadow-none border">
                            <a class="accordion" style="cursor: pointer;" aria-expanded="true" aria-controls="collapseOne" data-bs-target="#collapseOne" data-bs-toggle="collapse">
                                <div class="card-header border-0" id="headingOne">
                                    <h6 class="mb-0">Can I use this template for my client?</h6>
                                </div>
                            </a>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#faqContent">
                                <div class="card-body pt-1">
                                    <p class="text-muted mb-0">Yup, the marketplace license allows you to use this theme
                                        in any end products.
                                        For more information on licenses, please refere <a href="https://themeforest.net/licenses/standard" target="_blank">here</a>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 border">
                            <a class="accordion collapsed" style="cursor: pointer;" aria-controls="collapseTwo" data-bs-target="#collapseTwo" data-bs-toggle="collapse">
                                <div class="card-header border-0" id="headingTwo">
                                    <h6 class="mb-0">Can this theme work with Wordpress?</h6>
                                </div>
                            </a>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#faqContent">
                                <div class="card-body pt-1">
                                    <p class="text-muted mb-0">No. This is a HTML template. It won't directly with
                                        wordpress, though you can convert this into wordpress compatible theme</p>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 border">
                            <a class="accordion collapsed" style="cursor: pointer;" aria-controls="collapseThree" data-bs-target="#collapseThree" data-bs-toggle="collapse">
                                <div class="card-header border-0" id="headingThree">
                                    <h6 class="mb-0">How do I get help with the theme?</h6>
                                </div>
                            </a>

                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#faqContent">
                                <div class="card-body pt-1">
                                    <p class="text-muted mb-0">
                                        Use our dedicated support email (support@coderthemes.com) to send your issues or feedback. We are here to help anytime
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 border">
                            <a class="accordion collapsed" style="cursor: pointer;" aria-controls="collapseFour" data-bs-target="#collapseFour" data-bs-toggle="collapse">
                                <div class="card-header border-0" id="headingfour">
                                    <h6 class="mb-0">Will you regularly give updates of Ubold ?</h6>
                                </div>
                            </a>

                            <div id="collapseFour" class="collapse" aria-labelledby="headingfour" data-bs-parent="#faqContent">
                                <div class="card-body pt-1">
                                    <p class="text-muted mb-0">Yes, We will update the Ubold regularly. All the
                                        future updates would be available without any cost</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- END FAQ -->

    <!-- START CTA -->
    <section class="section bg-dark section-footer-1">
        <div class="container pt-10 pb-5">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ Vite::asset('resources/images/logo-light.png') }}" class="logo-light" alt="" height="24" />

                    <p class="text-muted mt-4">Ubold makes it easier to build better websites with great speed. Save
                        hundreds of hours of design and development by using it.</p>
                </div>

                <div class="col-md-3 offset-md-3">
                    <h5 class="text-white f-17">Help</h5>
                    <ul class="footer-links mb-0 mt-4">
                        <li><a href="#">Documentation</a></li>
                        <li><a target="_blank" href="https://themeforest.net/item/ubold-responsive-web-app-kit/13489470/support">Support</a></li>
                        <li><a href="#">Changelog</a></li>
                    </ul>
                </div>

            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="text-center">
                        <p class="text-muted mb-0">© 2015 -
                            <script>
                                document.write(new Date().getFullYear())
                            </script> UBold. Design and coded by Coderthemes
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- footer end -->

    <script type="text/javascript">
        window.onscroll = function() {
            navbarSticky()
        };
        var navbar = document.getElementsByTagName("nav")[0];

        function navbarSticky() {
            if (window.pageYOffset >= 70) {
                navbar.setAttribute('style', 'position: fixed; top: 0; background-color: white; width: 100%; box-shadow: 0 10px 33px -14px rgba(0,0,0,.1) !important; border-radius: 0; z-index: 999; margin-bottom: 0; transition: all .2s ease-in-out;')
            } else {
                navbar.removeAttribute('style')
            }
        }
    </script>
    @vite(['resources/js/app.js'])
</body>

</html>
