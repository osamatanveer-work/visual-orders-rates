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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Carrier</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $data->name }}</a></li>
                        <li class="breadcrumb-item active">Services</li>
                    </ol>
                    
                </div>
                <h3 class="page-title">Carrier:{{ $data->name }}</h3>
          
            </div>
           
        

          
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
     
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body pt-0">
                   
                    <div class="tab-content">
                        <h5>All Services</h5>
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
                                                            <th>Service Type</th>
                                                            <th>Service Name</th>
                                                            <th>Code</th>
                                                            <th>Description</th>
                                                           
                                                            <th>Api Allowed</th>
                                                            <th>Api Name</th>
                                                            <th>Action</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       
                                                        @foreach($services as $key=>$item)
                                                        <tr>
                                                            <td>{{ $item->service_type }}</td>
                                                            <td>{{ $item->service_name }}</td>
                                                            <td>{{ $item->code }}</td>
                                                            <td>{{ $item->description }}</td>
                                                            <td>
                                                                <input type="checkbox" class="apiAllowedCheckbox" data-service-id="{{ $item->id }}" @if($item->api_allowed) checked @endif>
                                                            </td>
                                                            <td>{{ $item->api_name }}</td>
                                                     
                                                            <td>   <i data-feather="edit-2" class="icon-dual text-warning" data-bs-toggle="modal" data-bs-target="#update-modal{{$key}}"  style="width: 15px;"></i>
                                    
                                                            </td>
                                                          
                                                        </tr>
                                                        <div class="modal fade" id="update-modal{{$key}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-light">
                                                                        <h4 class="modal-title" id="myCenterModalLabel">Update Service {{ $item->name }}</h4>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                                    </div>
                                                                    <div class="modal-body p-4">
                                                                        <form action="{{route('update.service',['id'=>$item->id]) }}" method="POST" enctype="multipart/form-data">
                                                                            @csrf
                                                                            @method('PUT')
                                                                           
                                                                            <div class="mb-3">
                                                                                <label for="name_{{ $key }}" class="form-label">Service Type</label>
                                                                                <input type="text" class="form-control text-muted" id="name_{{ $key }}" value="{{ $item->service_type }}" readonly placeholder="Enter Service Type">
                                                                                @error('name')
                                                                                <span class="text-danger">{{ $message }}</span>   
                                                                                @enderror
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="name_{{ $key }}" class="form-label">Service Name</label>
                                                                                <input type="text" class="form-control text-muted"  id="name_{{ $key }}" readonly value="{{ $item->service_name }}" placeholder="Enter Service Name">
                                                                                @error('name')
                                                                                <span class="text-danger">{{ $message }}</span>   
                                                                                @enderror
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="name_{{ $key }}" class="form-label">Code</label>
                                                                                <input type="text" class="form-control text-muted"  readonly id="name_{{ $key }}" value="{{ $item->code }}" placeholder="Enter Code">
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
                                                                                <label for="description_{{ $key }}" class="form-label">Api Name</label>
                                                                                <input type="text" class="form-control" name="api_name" id="name_{{ $key }}" value="{{ $item->api_name }}" placeholder="Enter name">
                                                                                @error('description')
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

        </div> <!-- end col -->
    </div>
    <!-- end row-->

</div> <!-- container -->
@push('scripts')
    

<script>
  $(document).ready(function() {
            $('.apiAllowedCheckbox').on('change', function() {
                var isChecked = $(this).is(':checked');
                var serviceId = $(this).data('service-id');

                // AJAX POST request
                $.ajax({
                    method: 'POST',
                    url: '{{ route('service.allow') }}', // Replace with your actual route for ServiceAllow method
                    data: {
                        _token: '{{ csrf_token() }}', // CSRF token
                        service_id: serviceId,
                        api_allowed: isChecked ? 1 : 0 // Sending 1 if checked, 0 if unchecked
                    },
                    success: function(response) {
                        alert(response.message); // Show success message
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + status + ' - ' + error); // Show error message
                    }
                });
            });
        });
    </script>
@endpush

@endsection
