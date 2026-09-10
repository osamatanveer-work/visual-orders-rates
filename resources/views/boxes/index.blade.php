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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">All Boxes</a></li>

                        <li class="breadcrumb-item active">All</li>
                    </ol>
                </div>
                <h4 class="page-title">Boxes</h4>
                <button type="button" class="btn btn-success waves-effect waves-light mb-2 float-end" data-bs-toggle="modal" data-bs-target="#custom-modal"><i class="mdi mdi-plus-circle me-1"></i> Add New Box</button>
            </div>
            <div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h4 class="modal-title" id="myCenterModalLabel">Add New Box</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form method="POST" action="{{ route('box.store') }}">
                                @csrf

                                <div class="mb-2">
                                    <label for="fullname" class="form-label">Package Type</label>
                                    <input class="form-control" type="text" name="name" id="fullname" placeholder="Enter Package Type"  value="">
                                </div>
                                <div class="mb-2">
                                    <label for="emailaddress" class="form-label">Length(in)</label>
                                    <input class="form-control" type="number" name="length"   placeholder="Enter box length" value="" autocomplete="off">
                                </div>

                                <div class="mb-2">
                                    <label for="emailaddress" class="form-label">Width(in)</label>
                                    <input class="form-control" type="number" name="width"   placeholder="Enter box width" value="" autocomplete="off">
                                </div>

                                <div class="mb-2">
                                    <label for="emailaddress" class="form-label">Height(in)</label>
                                    <input class="form-control" type="number" name="height"   placeholder="Enter box height" value="" autocomplete="off">
                                </div>
                               

                                <div class="mb-2">
                                    <label for="emailaddress" class="form-label">Package Weight(oz)</label>
                                    <input class="form-control" step="0.1" type="number" name="package_weight" required   placeholder="Enter package weight" value="" autocomplete="off">
                                </div>


                                <div class="mb-2">
                                    <label for="emailaddress" class="form-label">Max Weight(oz)</label>
                                    <input class="form-control" step="0.1" type="number" name="max_weight"   placeholder="Enter package max weight" value="" autocomplete="off">
                                </div>

                                
                   
<div class="text-end">
    <button type="button" class="btn btn-light border waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-success waves-effect waves-light">Publish</button>
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
   
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body p-0">
                  
                    <div class="tab-content">
                       
                        <!-- end timeline content-->

                        <div class="tab-pane show active" id="settings">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                          
                    
                                            <div class="table-responsive">
                                                <table class="table mb-0" style="font-size: 12px;">
                                                    <thead class="table-light">
                                                        <tr>
                                                          
                                                            <th class="fw-bold">Package Type</th>
                                                          
                                                            <th class="fw-bold">length(in)</th>
                                                            <th class="fw-bold">width(in)</th>
                                                            <th class="fw-bold">height(in)</th>
                                                            <th class="fw-bold">Package Weight(oz)</th>
                                                            <th class="fw-bold">Max Weight(oz)</th>
                                                         
                                                         
                                                            <th class="fw-bold">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data as $key=>$item)
                                                        <tr>
                                                        
                                                            <td>{{ $item->name }}</td>
                                                            <td>{{ $item->length }}</td>
                                                 
                                                            <td>{{ $item->width }}</td>

                                                            <td>{{ $item->height }}</td>
                                                        
                                                            <td>{{ $item->package_weight }}</td>
                                                            <td>{{ $item->max_weight }}</td>
                                                         
                                                 
                                                           <td>   <i data-feather="edit-2" class="icon-dual text-warning" data-bs-toggle="modal" data-bs-target="#update-modal{{$key}}"  style="width: 15px;"></i> <i data-feather="trash-2" class="icon-dual text-danger ms-1" data-bs-toggle="modal" data-bs-target="#delete-modal{{$key}}" style="width: 15px;" ></i>
                                                            {{-- <a href="{{ route('markup.show',['slug'=>$item->slug]) }}"> <i style="width: 15px;" data-feather="eye" class="icon-dual ms-1 text-info"></i></a> --}}
                                                        </td>
                                                          
                                                        </tr>
                                                        <div class="modal fade" id="delete-modal{{$key}}" tabindex="-1"
                                                        aria-labelledby="delete-modal{{$key}}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header border-0">
                                                                    <h1 class="modal-title fs-5" id="exampleModalLabel{{ $key }}">
                                                                        {{ $item->name }}</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body py-0">
                                                                    Are you sure you want to delete this Box?
                                                                </div>
                                                                <div class="modal-footer border-0">
                                                                    <button type="button" class="btn btn-light"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                  
                                                                        <a  href="{{ route('box.destroy',['id'=>$item->id]) }}" class="btn btn-danger">Delete</a>
                                                                   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- Edit --}}
                                                    <div class="modal fade" id="update-modal{{ $key }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-light">
                                                                    <h4 class="modal-title" id="myCenterModalLabel">Update Box</h4>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                                </div>
                                                                <div class="modal-body p-4">
                                                                    <form method="POST" action="{{ route('box.update',['id'=>$item->id]) }}">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="mb-2">
                                                                            <label for="fullname" class="form-label">Package Type</label>
                                                                            <input class="form-control" type="text" value="{{ $item->name }}" name="name" id="fullname" placeholder="Enter Package Type"  value="">
                                                                        </div>
                                                                        <div class="mb-2">
                                                                            <label for="emailaddress" class="form-label">Length(in)</label>
                                                                            <input class="form-control" type="number" value="{{ $item->length }}" name="length"   placeholder="Enter box length" value="" autocomplete="off">
                                                                        </div>
                                        
                                                                        <div class="mb-2">
                                                                            <label for="emailaddress" class="form-label">Width(in)</label>
                                                                            <input class="form-control" type="number" value="{{ $item->width }}" name="width"   placeholder="Enter box width" value="" autocomplete="off">
                                                                        </div>
                                        
                                                                        <div class="mb-2">
                                                                            <label for="emailaddress" class="form-label">Height(in)</label>
                                                                            <input class="form-control" type="number " value="{{ $item->height }}" name="height"   placeholder="Enter box height" value="" autocomplete="off">
                                                                        </div>
                                                                       
                                        
                                                                        <div class="mb-2">
                                                                            <label for="emailaddress" class="form-label">Package Weight(oz)</label>
                                                                            <input class="form-control" step="0.1"  value="{{ $item->package_weight }}" type="number" name="package_weight" required   placeholder="Enter package weight" value="" autocomplete="off">
                                                                        </div>
                                        
                                        
                                                                        <div class="mb-2">
                                                                            <label for="emailaddress" class="form-label">Max Weight(oz)</label>
                                                                            <input class="form-control" step="0.1"   value="{{ $item->max_weight }}"  type="number" name="max_weight"   placeholder="Enter package max weight" value="" autocomplete="off">
                                                                        </div>
                                        
                                                                      
                                                           
                                                           
                                        <div class="text-end">
                                            <button type="button" class="btn btn-light border waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
                                        </div>
                                        
                                                                    </form>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div>
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
            {{ $data->links() }}
        </div> <!-- end col -->
    </div>
    <!-- end row-->

</div> <!-- container -->
@endsection