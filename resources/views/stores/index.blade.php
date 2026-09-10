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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Company</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Stores</a></li>
                        <li class="breadcrumb-item active">All</li>
                    </ol>
                </div>
                <h4 class="page-title">Company</h4>
                <a href="{{ route('store.create',['id'=>$company->id]) }}" type="button"
                                        class="btn btn-danger waves-effect waves-light me-1 float-end mb-2"><i
                                            class="mdi mdi-plus-circle me-1"></i> Add New</a> 
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-4 col-xl-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="{{Vite::asset('resources/images/companies/' . $company->img) }}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                    <h4 class="mb-0">{{ $company->name }}</h4>
                    <p class="text-muted">Company</p>

                  
                    <div class="text-start mt-3">
                        <h4 class="font-13 text-uppercase">About Me :</h4>
                        <p class="text-muted font-13 mb-3">
                            {{ $company->description }}
                        </p>
                      
                    </div>

                </div>
            </div> <!-- end card -->

         
        </div> <!-- end col-->

        <div class="col-lg-8 col-xl-8">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill navtab-bg">
                        
                        <li class="nav-item">
                            <a href="#settings" data-bs-toggle="tab" aria-expanded="false" class="nav-link active" >
                                All Stores
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                       
                        <!-- end timeline content-->

                        <div class="tab-pane show active" id="settings">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                          
                    
                                            <div class="table-responsive">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>Store Name</th>
                                                            <th>Store Url</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($stores as $key=>$item)
                                                        <tr>
                                                            <td>{{ $key}}</td>
                                                            <td>{{ $item->name }}</td>
                                                            <td>{{ $item->store_url }}</td>
                                                            <td>Edit/Delete</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> <!-- end card -->
                                </div> <!-- end col -->
                    
                            </div>
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