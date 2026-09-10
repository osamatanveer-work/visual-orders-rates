@extends('layouts.vertical', ["page_title"=> "Profile"])

@section('content')
<style>
    .dataTables_length{
        margin-bottom: 20px!important;
    }
</style>
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Rate Quotes</a></li>

                        <li class="breadcrumb-item active">All</li>
                    </ol>
                </div>
                <h4 class="page-title text-capitalize">All Rate Quotes</h4>
            
            </div>
           
        </div>
    </div>
   
  
      
      <div class="row">
   
        <div class="col-lg-12 col-xl-12">
            <div class="card pt-0">
                <div class="card-body pt-0 px-0">
                    
                    <div class="tab-content">
                       
                        <!-- end timeline content-->

                        <div class="tab-pane show active" >
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                          
                    
                                          
                                            <table class="table table-bordered data-table">
                                                <thead>
                                                    <tr>
                                                        
                                                        <th>Created at</th>
                                                        <th>Store</th>
                                                        <th>Address</th>
                                                        <th>City</th>
                                                        <th>Province</th>
                                                        <th>Country</th>



                                                        <th width="105px">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        
                    
                                          
                                          
                                            
                                           
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
<script type="text/javascript">
    $(function () {
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('rateqoute.all') }}",
          columns: [
              {data: 'created_at', name: 'created_at'},
              {data: 'store', name: 'store' ,orderable: false, searchable: false},
              {data: 'address', name: 'address'},
              {data: 'city', name: 'city'},
              {data: 'province', name: 'province'},
              {data: 'country', name: 'country'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ],
          order: [[0, 'desc']]
      });
         
    });
  </script>

@endsection

