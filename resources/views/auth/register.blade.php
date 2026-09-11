<!DOCTYPE html>
<html lang="en">

<head>

    @include('layouts.shared/title-meta', ['title' => 'Register & Signup'])
    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])
    @vite(['resources/scss/icons.scss'])
    <style>
  
        i{
    cursor: pointer;
}

    </style>
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
                                    <a href="#" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            {{-- <img src="{{ Vite::asset('resources/images/logo-dark.png') }}" alt="" height="22"> --}}
                                            <h5 class="text-italic fw-bold">Rate Shopper</h5>
                                        </span>
                                    </a>

                                    <a href="#" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            {{-- <img src="{{ Vite::asset('resources/images/logo-light.png') }}" alt="" height="22"> --}}
                                            <h5 class="text-italic fw-bold">Rate Shopper</h5>
                                        </span>
                                    </a>
                                </div>
                                <p class="text-muted mb-4 mt-3">Don't have an account? Create your account, it takes less than a minute</p>
                            </div>

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                                <br>
                            @endif
                            @if (session('success'))
                                <div class=" alert alert-success">{{ session('success') }}
                                </div>
                                <br>
                            @endif

                            @if (sizeof($errors) > 0)
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-danger">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Full Name</label>
                                    <input class="form-control" type="text" name="name" id="fullname" placeholder="Enter your name" required value="">
                                </div>
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email address</label>
                                    <input class="form-control" type="email" name="email" id="emailaddress" required placeholder="Enter your email" value="" autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" value="">
                                        <div class="input-group-text" id="togglePassword">
               <i class="fas fa-eye"></i>
                                           
                                        </div>
                                    </div>
                                </div>

                           
<div class="mb-3">
    <label for="password_confirmation" class="form-label">Password confirmation</label>
    <div class="input-group input-group-merge">
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Enter your password confirmation" value="">
        <div class="input-group-text" id="togglePasswordConfirmation">
         <i class="fas fa-eye"></i>
        </div>
    </div>
</div>
                             
                                <div class="text-center d-grid">
                                    <button class="btn btn-primary" type="submit"> Sign Up </button>
                                </div>

                            </form>

                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <p class="">Already have account? <a href="{{ route('login') }}" class=""><b>Sign In</b></a></p>
                                </div> <!-- end col -->
                            </div>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                 
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

  
    @vite(['resources/js/app.js'])
    @include('layouts.shared/footer-script')
</body>
<script>
    // Function to toggle password visibility
function togglePasswordVisibility(inputId, toggleButtonId) {
    const passwordInput = document.getElementById(inputId);
    const togglePasswordButton = document.getElementById(toggleButtonId);

    togglePasswordButton.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle the eye icon class
        const eyeIcon = this.querySelector('i');
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
}

// Call the function for each password field
togglePasswordVisibility('password', 'togglePassword');
togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation');

</script>
</html>
