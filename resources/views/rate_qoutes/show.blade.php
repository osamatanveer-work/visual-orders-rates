@extends('layouts.vertical', ["page_title"=> "Profile"])

@section('content')
@php
    $markup = $data->first();
@endphp
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/rate-qoutes/all">Rate Quotes List</a></li>
                        <li class="breadcrumb-item ">Rate Quotes Details</li>
                        <!-- <li class="breadcrumb-item ">{{ $rate_qoute->name }}</li> -->
                    </ol>
                </div>
                <h4 class="page-title text-capitalize">Rate Quotes Details</h4>
             
            </div>
           
        </div>
    </div>
   
    <div class="card w-100 bg-primary text-white">
        <div class="card-body">
            <table class="table table-borderless text-white">
                <tr>
                    <th class="text-white">Store : {{ $rate_qoute->store->name }}</th>
                   
                    <th class="text-white">App : {{ $rate_qoute->app }}</th>
                  
                    <th class="text-white">Markup Rule: {{ $markup->markup ? $markup->markup->name : 'Null' }}</th>
                    

                   
                </tr>
               
             
                <tr>
                <th class="text-white">Address : {{ $rate_qoute->address }}</th>
                    <th class="text-white">City : {{ $rate_qoute->city }}</th>
                    <th class="text-white">Province/State : {{ $rate_qoute->province }}</th>
                    <th class="text-white">Country : {{ $rate_qoute->country }}</th>
                </tr>
              
               
            </table>
        </div>
    </div>
    
    <!-- end page title -->
   
      
    <div class="row">
   
        <div class="col-lg-12 col-xl-12">
            <div class="card pt-0">
                <div class="card-body pt-0 px-0">
                    
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
                                                            <th>Carrier</th>
                                                            <th>Service Name</th>
                                                       
                                                        
                                                       
                                                            <th>Retail Rate</th>
                                                            <th>Quoted Amount</th>
                                                            <th>Profit Margin</th>
                                                         
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data as $key=>$item)
                                                        <tr id="tooltip-container">
                                                            <td>{{ $item->carrier->name }}</td>
                                                            <td>{{ $item->service_name }}</td>
                                                            
                                                         
                                                            
                                                            <td>{{ $item->retail_price }}</td>
                                                            <td >{{ $item->qoute_amount }}</td>
                                                            <td class="fw-bold text-primary">{{ $item->profit_margin }}</td>
                                                           
                                                        </tr>
                                                    
                                                       
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
@endsection