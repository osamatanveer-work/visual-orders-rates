@extends('layouts.vertical', ["page_title"=> "Profile"])

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
                        
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
                <h4 class="page-title">Profile</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-4 col-xl-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRhtMRbtowke9ZnnGtyYJmIuJaB2Q1y5I-3IA&s" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                    <h4 class="mb-0">{{ $user->name }}</h4>
                  

                  
                    <div class="text-start mt-3">
                        <h4 class="font-13 text-uppercase">About Me :</h4>
                        <p class="text-muted font-13 mb-3">
                           {{ $user->bio }}
                        </p>
                        <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ms-2">{{ $user->name }}</span></p>

                       

                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ms-2">{{ $user->email }}</span></p>

                       
                    </div>

                    {{-- <ul class="social-list list-inline mt-3 mb-0">
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="mdi mdi-facebook"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="mdi mdi-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="mdi mdi-github"></i></a>
                        </li>
                    </ul> --}}
                </div>
            </div> <!-- end card -->

         
        </div> <!-- end col-->

        <div class="col-lg-8 col-xl-8">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill navtab-bg">
                        
                        <li class="nav-item">
                            <a href="#settings" data-bs-toggle="tab" aria-expanded="false" class="nav-link active" >
                                Settings
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                       
                        <!-- end timeline content-->

                        <div class="tab-pane show active" id="settings">
                            <form action="{{ route('update.user',['id'=>$user->id]) }}" method="POST">
                                @csrf
                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Personal Info</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="firstname" value="{{ $user->name }}" name="name" placeholder="Enter first name">
                                        </div>
                                    </div>
                                   
                                  
                                  
                                </div> <!-- end row -->

                            

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="useremail" name="email_address" placeholder="Enter email" value="{{ $user->email }}">
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="userpassword" class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password"  placeholder="Enter password">
                                           
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                            
                             

                            
                                <div class="text-end">
                                    <a  class="btn btn-danger waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Deactivate</a>
                                    <button type="submit" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Save</button>
                                </div>
                            </form>
                        </div>
                        <!-- end settings content-->

                    </div> <!-- end tab-content -->
                </div>
            </div> <!-- end card-->

        </div> <!-- end col -->
    </div>
    <!-- end row-->

</div> <!-- container -->
@endsection