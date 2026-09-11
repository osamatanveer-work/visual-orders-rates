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
                <button type="button" class="btn btn-danger waves-effect waves-light mb-2 float-end" data-bs-toggle="modal" data-bs-target="#custom-modal"><i class="mdi mdi-plus-circle me-1"></i> Add New</button>
            </div>
            <div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h4 class="modal-title" id="myCenterModalLabel">Add New Store</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form action="{{ route('store.create',['id'=>$company->id]) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter name">
                                </div>
                                <div class="mb-3">
                                    <label for="position" class="form-label">Store Url</label>
                                    <input type="url" class="form-control" name="url" id="position" placeholder="Enter store url">
                                </div>
                                <div class="mb-3">
                                    <label for="company" class="form-label">Api Key</label>
                                    <input type="text" class="form-control" name="api_key" id="company" placeholder="Enter Api Key">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Api Secret </label>
                                    <input type="text" class="form-control" name="api_secret" id="exampleInputEmail1" placeholder="Enter Api Secret ">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Api Access Token </label>
                                    <input type="text" class="form-control" name="api_access_token" id="exampleInputEmail1" placeholder="Enter Api Secret ">
                                </div>
                             
        
                                <div class="text-end">
                                   
                                    <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal">Continue</button>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-3 col-xl-3">
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

        <div class="col-lg-9 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill navtab-bg">
                        
                        <li class="nav-item">
                            <a href="#settings" data-bs-toggle="tab" aria-expanded="false" class="nav-link text-start" >
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
                                                <table class="table mb-0" style="font-size: 12px;">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Active</th>
                                                            <th>Platform</th>
                                                            <th>Store Name</th>
                                                            <th>Store Url</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($company->stores as $key=>$item)
                                                        <tr>
                                                            <td><input type="checkbox" checked></td>
                                                            <td>Shopify</td>
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