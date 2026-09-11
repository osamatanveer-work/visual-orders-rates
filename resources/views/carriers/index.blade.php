@extends('layouts.vertical', ['page_title' => 'Contacts & Members Listing'])

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
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Rate Shopper</a></li>
                            <li class="breadcrumb-item active">Carriers</li>
                          
                        </ol>
                    </div>
                    <h4 class="page-title">Carriers</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        {{-- <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-md-8">
                                <form class="d-flex flex-wrap align-items-center">
                                    <label for="inputPassword2" class="visually-hidden">Search</label>
                                    <div class="me-3">
                                        <input type="search" class="form-control my-1 my-md-0" id="inputPassword2" placeholder="Search...">
                                    </div>
                                    <label for="status-select" class="me-2">Sort By</label>
                                    <div class="me-sm-3">
                                        <select class="form-select my-1 my-md-0" id="status-select">
                                            <option selected="">All</option>
                                            <option value="1">Name</option>
                                            <option value="2">Post</option>
                                            <option value="3">Followers</option>
                                            <option value="4">Followings</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="text-md-end mt-3 mt-md-0">
                                    <button type="button" class="btn btn-success waves-effect waves-light me-1"><i class="mdi mdi-cog"></i></button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#custom-modal"><i class="mdi mdi-plus-circle me-1"></i> Add New</button>
                                </div>
                            </div><!-- end col-->
                        </div> <!-- end row -->
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div> --}}
        <!-- end row -->

       
    <div class="row">
        @forelse ($data as $key => $item)
        <div class="col-lg-4">
            <div class="text-center card">
                <div class="card-body">
                    <div class="pt-2 pb-2">
                       
                        <a href="{{route('carrier.show',['slug'=>$item->slug])}}"><img src="{{ asset('assets/images/carriers/'.$item->img) }}" class="rounded-circle img-thumbnail avatar-xl" style="object-fit: cover;" alt="profile-image">
                        </a>
                        <h4 class="mt-3"><a href="extras-profile.html" class="text-dark">{{ $item->name }}</a></h4>
                        <p class="text-muted">@ {{ $item->name }} <span> | </span> <span> <a href="https://{{ $item->name }}.com" class="text-pink">{{ $item->name }}.com</a> </span></p>

                        <button type="button" class="btn btn-light btn-sm waves-effect waves-light d-none " data-bs-toggle="modal" data-bs-target="#edit-modal_{{$key}}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm waves-effect d-none"  data-bs-toggle="modal" data-bs-target="#delete-modal-{{$key}}">Delete</button>
                    </div>
                    <div class="modal fade" id="delete-modal-{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                          <div class="modal-content">
                            <div class="modal-header border-0">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $item->name }}</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body py-0">
                              <p class="text-start">Are you want to confirm to delete ?</p>
                            </div>
                            <div class="modal-footer border-0">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <a href="{{ route('carrier.destroy',['id'=>$item->id]) }}" type="button" class="btn btn-danger">Delete</a>
                            </div>
                          </div>
                        </div>
                </div>

                    <div class="modal fade" id="edit-modal_{{$key}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-light">
                                    <h4 class="modal-title" id="myCenterModalLabel">Update {{ $item->name }}</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form action="{{ route('carrier.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="img_{{ $key }}" class="form-label">Logo</label>
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type="file" name="img" id="img_{{ $key }}" />
                                                    <label for="img_{{ $key }}"></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <div id="imagePreview" style="background-image: url('{{ asset('assets/images/carriers/'.$item->img) }}');">
                                                    </div>
                                                </div>
                                            </div>
                                            @error('img')
                                            <span class="text-danger">{{ $message }}</span>   
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="name_{{ $key }}" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" id="name_{{ $key }}" value="{{ $item->name }}" placeholder="Enter name">
                                            @error('name')
                                            <span class="text-danger">{{ $message }}</span>   
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description_{{ $key }}" class="form-label">Description</label>
                                            <textarea class="form-control" name="description" id="description_{{ $key }}" cols="30" rows="5" placeholder="Enter Description">{{ $item->description }}</textarea>
                                            @error('description')
                                            <span class="text-danger">{{ $message }}</span>   
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="client_id_{{ $key }}" class="form-label">Client ID</label>
                                            <input type="text" class="form-control" name="client_id" id="client_id_{{ $key }}" value="{{ $item->client_id }}" placeholder="Enter Client ID">
                                            @error('client_id')
                                            <span class="text-danger">{{ $message }}</span>   
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="client_secreat_key_{{ $key }}" class="form-label">Client Secret Key</label>
                                            <input type="text" class="form-control" name="client_secret_key" id="client_secret_key_{{ $key }}" value="{{ $item->client_secret_key }}" placeholder="Enter Client Secret Key">
                                            @error('client_secret_key')
                                            <span class="text-danger">{{ $message }}</span>   
                                            @enderror
                                        </div>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-light border waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success waves-effect waves-light">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.col -->
        @empty
        <div class="col-12 py-5 ">
            <i data-feather="inbox" style="height: 50px"class="w-100"></i>
        <h4 class="text-center text-muted">No Carriers Exists ...</h4>
    </div>
    </div><!-- /.row -->


     
        @endforelse
        <div class="row">
            <div class="col-12">
                <div class="text-end">
                   {{ $data->links() }}
                </div>
            </div>
        </div>
        <!-- end row -->

    
        <!-- end row -->

    </div>
    <!-- Modal -->
    <div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title" id="myCenterModalLabel">Add New Carrier</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('carrier.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Logo</label>
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' name="img" id="imageUpload" />
                                    <label for="imageUpload"></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url(https://2.bp.blogspot.com/-l9nGy2e3PnA/XLzG5A6u_cI/AAAAAAAAAgI/31bl8XZOrTwN0kTN8c18YOG3OhNiTUrsQCLcBGAs/s1600/rocket.png);">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter name">
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>   
                            @enderror
                        </div>
                       
                        <div class="mb-3">
                            <label for="position" class="form-label">Description</label>
                           <textarea   class="form-control" id="" name="description" cols="30" rows="5" placeholder="Enter Description"></textarea>
                           @error('description')
                           <span class="text-danger">{{ $message }}</span>   
                           @enderror
                        </div>
                        <div class="mb-3">
                            <label for="company" class="form-label">Client ID</label>
                            <input type="text" class="form-control" name="client_id" id="company" placeholder="Enter Client ID">
                            @error('client_id')
                            <span class="text-danger">{{ $message }}</span>   
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Client Secreat Key</label>
                            <input type="text" class="form-control" name="client_secreat_key" id="exampleInputEmail1" placeholder="Enter Client Secreat Key">
                            @error('client_secreat_key')
                            <span class="text-danger">{{ $message }}</span>   
                            @enderror
                        </div>

                        <div class="text-end">
                         
                            <button type="button" class="btn btn-light border waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success waves-effect waves-light">Publish</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('script')
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
<script>
    function readURL(input) {
if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
        $('#imagePreview').css('background-image', 'url('+e.target.result +')');
        $('#imagePreview').hide();
        $('#imagePreview').fadeIn(650);
    }
    reader.readAsDataURL(input.files[0]);
}
}
$("#imageUpload").change(function() {
readURL(this);
});
</script>
@endsection
