@extends('layouts.vertical', ['page_title' => 'Companies'])


@section('css')
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<style>

.avatar-upload {
  position: relative;
  max-width: 205px;
  margin: 0px auto;
}
.avatar-upload .avatar-edit {
  position: absolute;
  right: 12px;
  z-index: 1;
  top: 10px;
}
.avatar-upload .avatar-edit input {
  display: none;
}
.avatar-upload .avatar-edit input + label {
  display: inline-block;
  width: 34px;
  height: 34px;
  margin-bottom: 0;
  border-radius: 100%;
  background: #ffffff;
  border: 1px solid transparent;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
  cursor: pointer;
  font-weight: normal;
  transition: all 0.2s ease-in-out;
}
.avatar-upload .avatar-edit input + label:hover {
  background: #f1f1f1;
  border-color: #d6d6d6;
}
.avatar-upload .avatar-edit input + label:after {
  content: "\f040";
  font-family: "FontAwesome";
  color: #757575;
  position: absolute;
  top: 10px;
  left: 0;
  right: 0;
  text-align: center;
  margin: auto;
}
.avatar-upload .avatar-preview {
  width: 192px;
  height: 192px;
  position: relative;
  border-radius: 100%;
  border: 6px solid #f8f8f8;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
}
.avatar-upload .avatar-preview > div {
  width: 100%;
  height: 100%;
  border-radius: 100%;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}
</style>
@endsection


@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Rate Shopper</a></li>
                        
                            <li class="breadcrumb-item active">Companies</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Companies</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-md-8">
                                <form class="d-flex flex-wrap align-items-center">
                                    <label for="inputPassword2" class="visually-hidden">Search</label>
                                    {{-- <div class="me-3">
                                        <input type="search" class="form-control my-1 my-md-0" id="inputPassword2"
                                            placeholder="Search...">
                                    </div> --}}
                                    {{-- <label for="status-select" class="me-2">Sort By</label> --}}
                                    {{-- <div class="me-sm-3">
                                        <select class="form-select my-1 my-md-0" id="status-select">
                                            <option>Select</option>
                                            <option>Date</option>
                                            <option selected>Name</option>
                                            <option>Revenue</option>
                                            <option>Employees</option>
                                        </select>
                                    </div> --}}
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="text-md-end mt-3 mt-md-0">
                              
                                            <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#custom-modal"><i class="mdi mdi-plus-circle me-1"></i> Add New Company</button>
                                </div>
                            </div><!-- end col-->
                        </div> <!-- end row -->
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row -->

        <div class="row">
            @forelse ($data as $key => $item)
                <div class="col-lg-4">
                    <div class="card bg-pattern">
                        <div class="card-body">
                            <div class="text-center">
                               <a href="{{ route('company.show',['slug'=>$item->slug]) }}">
                         
                                <img class="avatar-xl rounded-circle" src="{{ asset('assets/images/companies/'.$item->img) }}" 
                                onerror="this.src='https://lh5.googleusercontent.com/proxy/t08n2HuxPfw8OpbutGWjekHAgxfPFv-pZZ5_-uTfhEGK8B5Lp-VN4VjrdxKtr8acgJA93S14m9NdELzjafFfy13b68pQ7zzDiAmn4Xg8LvsTw1jogn_7wStYeOx7ojx5h63Gliw'"
                                alt="logo"></a> 
                                <h4 class="mb-1 font-20">{{ $item->name }}</h4>
                               
                            </div>

                            <p class="font-14 text-center text-muted">
                                {{ Str::words($item->description, 15, '...') }}
                            </p>

                            <div class="d-flex gap-1 flex-row justify-content-center mt-2">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#edit-modal{{$key}}"
                                class="btn btn-sm btn-light">Edit</button>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#delete-modal{{$key}}"
                                    class="btn btn-sm btn-danger">Delete</button>
                            </div>

                            {{-- Edit --}}

                            <div class="modal fade" id="edit-modal{{ $key }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <h4 class="modal-title" id="myCenterModalLabel">Update Company</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form action="{{ route('company.update',['id'=>$item->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3 text-center">
                                                    <label for="img_edit_{{ $key }}" class="text-center">Company Logo</label>
                                                </div>
                                                <div class="avatar-upload">
                                                    <div class="avatar-edit">
                                                        <input type="file" name="img" id="img_edit_{{ $key }}" class="image-input" />
                                                        <label for="img_edit_{{ $key }}"></label>
                                                    </div>
                                                    <div class="avatar-preview">
                                                        <div id="imagePreview_edit_{{ $key }}" style="background-image: url('{{ asset('assets/images/companies/'.$item->img) }}');">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="name_{{ $key }}" class="form-label">Name</label>
                                                    <input type="text" class="form-control" value="{{ $item->name }}" name="name" id="name_{{ $key }}" placeholder="Enter name">
                                                    @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="name_add" class="form-label">Markup ID</label>
                                                    <select name="markup_id" class="form-select" id="">
                                                        <option value="">Choose template...</option>
                                                        @foreach ($markup_templates as $val)
                                                            <option @if($item->markup_id === $val->id) selected @endif value="{{ $val->id }}">{{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('markup_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description_{{ $key }}" class="form-label">Description</label>
                                                    <textarea name="description" class="form-control" id="description_{{ $key }}" cols="30" rows="3" placeholder="Enter Description">{{ $item->description }}</textarea>
                                                    @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                            
                                                <div class="text-end">
                                                    <button type="button" class="btn btn-light border waves-effect waves-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /#edit-modal{{ $key }} -->
                            
                            

                            {{--  --}}

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
                                            Are you sure you want to delete this company?
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Close</button>
                                          
                                                <a  href="{{ route('company.destroy',['id'=>$item->id]) }}" class="btn btn-danger">Delete</a>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            @empty
                <div class="col-12 py-5 text-center">
                    <i data-feather="inbox" style="height: 50px" class="w-100"></i>
                    <h4 class="text-muted">No Companies Exist...</h4>
                </div>
            @endforelse
        </div> <!-- end row -->

        <div class="row">
            <div class="col-12">
                <div class="text-end">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
        <!-- end row -->


        <!-- end row -->

    </div> <!-- container -->

    <div class="modal fade" id="custom-modal"   index="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title" id="myCenterModalLabel">Add New Company</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
    
                        <div class="mb-3 text-center">
                            <label for="img_add" class="text-center">Company Logo</label>
                        </div>
                        <div class="avatar-upload">
                            <div class="avatar-edit">
                                <input type="file" name="img" id="img_add" class="image-input" />
                                <label for="img_add"></label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview_add" style="background-image: url('https://2.bp.blogspot.com/-l9nGy2e3PnA/XLzG5A6u_cI/AAAAAAAAAgI/31bl8XZOrTwN0kTN8c18YOG3OhNiTUrsQCLcBGAs/s1600/rocket.png');">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="name_add" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name_add" placeholder="Enter name">
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                     
                        <div class="mb-3">
                            <label for="name_add" class="form-label">Markup ID</label>
                            <select name="markup_id" class="form-select" id="">
                                <option value="">Choose template...</option>
                                @foreach ($markup_templates as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('markup_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description_add" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="description_add" cols="30" rows="3" placeholder="Enter Description"></textarea>
                            @error('description')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
    
                        <div class="text-end">
                            <button type="button" class="btn btn-light border waves-effect waves-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /#custom-modal -->
    {{ $data->links() }}
    
    <!-- /.modal -->

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        function readURL(input, previewElement) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(previewElement).css('background-image', 'url(' + e.target.result + ')');
                    $(previewElement).hide();
                    $(previewElement).fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Handle file input change for image preview in Edit Modal
     
        // Handle file input change for image preview in Add Modal
        $("#img_add").change(function() {
            var previewElement = $('#imagePreview_add');
            readURL(this, previewElement);
        });
    });
</script>
@endsection