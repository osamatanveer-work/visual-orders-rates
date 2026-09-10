@extends('layouts.vertical', ['page_title' => 'Create Project'])

@section('css')
    @vite(['node_modules/select2/dist/css/select2.min.css', 'node_modules/flatpickr/dist/flatpickr.min.css'])
    <style>
.avatar-upload {
  position: relative;
  max-width: 205px;
  margin: 50px auto;
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Rateshopper</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Companies</a></li>
                            <li class="breadcrumb-item active">Create Company</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Create Company</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('company.update',['id'=>$data->id]) }}" method="post" enctype="multipart/form-data"  id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                            @csrf
                            @method('PUT')
                            <div class="row">
                            <div class="col-xl-6">
                                <div class="mb-3">
                                    <label for="companyname" class="form-label">Company Name<span class="text-danger">*</span></label>
                                    <input type="text" id="companyname" name="name" class="form-control" value="{{ $data->name }}" placeholder="Enter company name">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="companyname" class="form-label">Company Url<span class="text-danger">*</span></label>
                                    <input type="text" name="company_url" class="form-control" value="{{ $data->shop_url }}" placeholder="Enter company url">
                                    @error('company_url')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 border-bottom pb-4">
                                    <label for="company-overview" class="form-label">Company Overview</label>
                                    <textarea class="form-control" name="description" value="{{ $data->description }}" id="company-overview" rows="5" placeholder="Enter some brief about company..">{{ $data->description }}</textarea>
                                </div>
                               

                               

                            </div> <!-- end col-->

                            <div class="col-xl-6" >

                                <div class="my-3 mt-xl-0">
                                    <label for="projectname" class="mb-0 form-label d-block text-center">Logo</label>
                                    <p class="text-muted font-14 text-center">Recommended thumbnail size 800x400 (px).</p>

                                   
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' name="img" id="imageUpload" />
                                            <label for="imageUpload"></label>
                                        </div>
                                        <div class="avatar-preview">
                                        <div id="imagePreview" style="background-image: url('{{{Vite::asset('resources/images/companies/'.$data->img)}}}');">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preview -->
                                    <div class="dropzone-previews mt-3" id="file-previews"></div>
                                    @error('img')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                    <!-- file preview template -->
                                    <div class="d-none" id="uploadPreviewTemplate">
                                        <div class="card mt-1 mb-0 shadow-none border">
                                            <div class="p-2">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                                                    </div>
                                                    <div class="col ps-0">
                                                        <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                                        <p class="mb-0" data-dz-size></p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <!-- Button -->
                                                        <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                                            <i class="mdi mdi-close"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end file preview template -->
                                </div>

                         
                            </div> <!-- end col-->
                        </div>
                        <!-- end row -->


                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success waves-effect waves-light m-1"><i class="fe-check-circle me-1"></i>Update</button>
                                <a href="{{ route('company.all') }}"  class="btn btn-light waves-effect waves-light m-1"><i class="fe-x me-1"></i> Cancel</a>
                            </div>
                        </div>
                    </form>

                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- end row-->

    </div> <!-- container -->
@endsection

@section('script')
    @vite(['resources/js/pages/create-project.init.js'])
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
