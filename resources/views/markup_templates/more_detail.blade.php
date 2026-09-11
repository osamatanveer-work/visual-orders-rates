@extends('layouts.vertical', ["page_title"=> "Profile"])
@section('css')
    <link href="{{ Vite::asset('node_modules/selectize/dist/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ Vite::asset('node_modules/mohithg-switchery/dist/switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ Vite::asset('node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ Vite::asset('node_modules/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ Vite::asset('node_modules/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<style>
       .hidden {
            display: none;
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
                        <li class="breadcrumb-item"><a href="{{ route('markup.all') }}">Markup Templates</a></li>

                        <li class="breadcrumb-item active">All</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ $data->name }} | Template</h4>
             
            </div>
           
        </div>
    </div>
    <!-- end page title -->
 {{--  Add  Carrier--}}
    <div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title" id="myCenterModalLabel">Add Carrier Markup</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('markup.carrier',['id'=>$data->id])}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Select Carrier</label>
                           <select name="carrier_id" class="form-select" id="">
                            <option value="">Choose...</option>
                            @foreach ($carriers as $val)
                              <option value="{{ $val->id }}">{{ $val->name }}</option>
                            @endforeach
                           </select>
                        </div>

                       

                        
                    
                        <div class="d-flex flex-row justify-content-between gap-3">
                        
                            <div class="mb-3 w-auto">
                                <label for="markup_percent" class="form-label">Markup Value</label>
                                <div class="input-group">
                                    <span class="input-group-text">%</span>
                                    <input type="number" name="markup_percent" size="1.01" id="markup_percent" class="form-control" placeholder="Enter % Value" step="0.01">

                                </div>
                            </div>
                            
                            <div class="mb-3 w-auto">
                                <label for="markup_fixed" class="form-label">Markup Value</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="markup_fixed"  size="1.1" id="markup_fixed" placeholder="Enter $ Value" class="form-control" step="0.01">
                                </div>
                            </div>
                            
                        </div>
             

                        <div class="mb-3">
                            <label for="name" class="form-label">Apply to countries</label>
                            <select name="countries[]" class="form-control select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" data-placeholder="Choose...">
                                <option value="af">Afghanistan</option>
                                <option value="al">Albania</option>
                                <option value="dz">Algeria</option>
                                <option value="ad">Andorra</option>
                                <option value="ao">Angola</option>
                                <option value="ag">Antigua and Barbuda</option>
                                <option value="ar">Argentina</option>
                                <option value="am">Armenia</option>
                                <option value="au">Australia</option>
                                <option value="at">Austria</option>
                                <option value="az">Azerbaijan</option>
                                <option value="bs">Bahamas</option>
                                <option value="bh">Bahrain</option>
                                <option value="bd">Bangladesh</option>
                                <option value="bb">Barbados</option>
                                <option value="by">Belarus</option>
                                <option value="be">Belgium</option>
                                <option value="bz">Belize</option>
                                <option value="bj">Benin</option>
                                <option value="bt">Bhutan</option>
                                <option value="bo">Bolivia</option>
                                <option value="ba">Bosnia and Herzegovina</option>
                                <option value="bw">Botswana</option>
                                <option value="br">Brazil</option>
                                <option value="bn">Brunei</option>
                                <option value="bg">Bulgaria</option>
                                <option value="bf">Burkina Faso</option>
                                <option value="bi">Burundi</option>
                                <option value="cv">Cabo Verde</option>
                                <option value="kh">Cambodia</option>
                                <option value="cm">Cameroon</option>
                                <option value="ca">Canada</option>
                                <option value="cf">Central African Republic</option>
                                <option value="td">Chad</option>
                                <option value="cl">Chile</option>
                                <option value="cn">China</option>
                                <option value="co">Colombia</option>
                                <option value="km">Comoros</option>
                                <option value="cg">Congo</option>
                                <option value="cd">Congo, Democratic Republic of the</option>
                                <option value="cr">Costa Rica</option>
                                <option value="ci">Côte d'Ivoire</option>
                                <option value="hr">Croatia</option>
                                <option value="cu">Cuba</option>
                                <option value="cy">Cyprus</option>
                                <option value="cz">Czech Republic</option>
                                <option value="dk">Denmark</option>
                                <option value="dj">Djibouti</option>
                                <option value="dm">Dominica</option>
                                <option value="do">Dominican Republic</option>
                                <option value="ec">Ecuador</option>
                                <option value="eg">Egypt</option>
                                <option value="sv">El Salvador</option>
                                <option value="gq">Equatorial Guinea</option>
                                <option value="er">Eritrea</option>
                                <option value="ee">Estonia</option>
                                <option value="sz">Eswatini</option>
                                <option value="et">Ethiopia</option>
                                <option value="fj">Fiji</option>
                                <option value="fi">Finland</option>
                                <option value="fr">France</option>
                                <option value="ga">Gabon</option>
                                <option value="gm">Gambia</option>
                                <option value="ge">Georgia</option>
                                <option value="de">Germany</option>
                                <option value="gh">Ghana</option>
                                <option value="gr">Greece</option>
                                <option value="gd">Grenada</option>
                                <option value="gt">Guatemala</option>
                                <option value="gn">Guinea</option>
                                <option value="gw">Guinea-Bissau</option>
                                <option value="gy">Guyana</option>
                                <option value="ht">Haiti</option>
                                <option value="hn">Honduras</option>
                                <option value="hk">Hong Kong</option>
                                <option value="hu">Hungary</option>
                                <option value="is">Iceland</option>
                                <option value="in">India</option>
                                <option value="id">Indonesia</option>
                                <option value="ir">Iran</option>
                                <option value="iq">Iraq</option>
                                <option value="ie">Ireland</option>
                                <option value="il">Israel</option>
                                <option value="it">Italy</option>
                                <option value="jm">Jamaica</option>
                                <option value="jp">Japan</option>
                                <option value="jo">Jordan</option>
                                <option value="kz">Kazakhstan</option>
                                <option value="ke">Kenya</option>
                                <option value="ki">Kiribati</option>
                                <option value="kr">South Korea</option>
                                <option value="kp">North Korea</option>
                                <option value="kw">Kuwait</option>
                                <option value="kg">Kyrgyzstan</option>
                                <option value="la">Laos</option>
                                <option value="lv">Latvia</option>
                                <option value="lb">Lebanon</option>
                                <option value="ls">Lesotho</option>
                                <option value="lr">Liberia</option>
                                <option value="ly">Libya</option>
                                <option value="li">Liechtenstein</option>
                                <option value="lt">Lithuania</option>
                                <option value="lu">Luxembourg</option>
                                <option value="mk">North Macedonia</option>
                                <option value="mg">Madagascar</option>
                                <option value="mw">Malawi</option>
                                <option value="my">Malaysia</option>
                                <option value="mv">Maldives</option>
                                <option value="ml">Mali</option>
                                <option value="mt">Malta</option>
                                <option value="mh">Marshall Islands</option>
                                <option value="mr">Mauritania</option>
                                <option value="mu">Mauritius</option>
                                <option value="mx">Mexico</option>
                                <option value="fm">Micronesia</option>
                                <option value="md">Moldova</option>
                                <option value="mc">Monaco</option>
                                <option value="mn">Mongolia</option>
                                <option value="me">Montenegro</option>
                                <option value="ma">Morocco</option>
                                <option value="mz">Mozambique</option>
                                <option value="mm">Myanmar</option>
                                <option value="na">Namibia</option>
                                <option value="nr">Nauru</option>
                                <option value="np">Nepal</option>
                                <option value="nl">Netherlands</option>
                                <option value="nc">New Caledonia</option>
                                <option value="nz">New Zealand</option>
                                <option value="ni">Nicaragua</option>
                                <option value="ne">Niger</option>
                                <option value="ng">Nigeria</option>
                                <option value="nu">Niue</option>
                                <option value="nf">Norfolk Island</option>
                                <option value="mp">Northern Mariana Islands</option>
                                <option value="no">Norway</option>
                                <option value="om">Oman</option>
                                <option value="pk">Pakistan</option>
                                <option value="pw">Palau</option>
                                <option value="pa">Panama</option>
                                <option value="pg">Papua New Guinea</option>
                                <option value="py">Paraguay</option>
                                <option value="pe">Peru</option>
                                <option value="ph">Philippines</option>
                                <option value="pn">Pitcairn Islands</option>
                                <option value="pl">Poland</option>
                                <option value="pt">Portugal</option>
                                <option value="pr">Puerto Rico</option>
                                <option value="qa">Qatar</option>
                                <option value="re">Réunion</option>
                                <option value="ro">Romania</option>
                                <option value="ru">Russia</option>
                                <option value="rw">Rwanda</option>
                                <option value="bl">Saint Barthélemy</option>
                                <option value="sh">Saint Helena</option>
                                <option value="kn">Saint Kitts and Nevis</option>
                                <option value="lc">Saint Lucia</option>
                                <option value="mf">Saint Martin</option>
                                <option value="pm">Saint Pierre and Miquelon</option>
                                <option value="vc">Saint Vincent and the Grenadines</option>
                                <option value="ws">Samoa</option>
                                <option value="sm">San Marino</option>
                                <option value="st">Sao Tome and Principe</option>
                                <option value="sa">Saudi Arabia</option>
                                <option value="sn">Senegal</option>
                                <option value="rs">Serbia</option>
                                <option value="sc">Seychelles</option>
                                <option value="sl">Sierra Leone</option>
                                <option value="sg">Singapore</option>
                                <option value="sx">Sint Maarten</option>
                                <option value="sk">Slovakia</option>
                                <option value="si">Slovenia</option>
                                <option value="sb">Solomon Islands</option>
                                <option value="so">Somalia</option>
                                <option value="za">South Africa</option>
                                <option value="ss">South Sudan</option>
                                <option value="es">Spain</option>
                                <option value="lk">Sri Lanka</option>
                                <option value="sd">Sudan</option>
                                <option value="sr">Suriname</option>
                                <option value="sz">Eswatini</option>
                                <option value="se">Sweden</option>
                                <option value="ch">Switzerland</option>
                                <option value="sy">Syria</option>
                                <option value="tw">Taiwan</option>
                                <option value="tj">Tajikistan</option>
                                <option value="tz">Tanzania</option>
                                <option value="th">Thailand</option>
                                <option value="tl">Timor-Leste</option>
                                <option value="tg">Togo</option>
                                <option value="tk">Tokelau</option>
                                <option value="to">Tonga</option>
                                <option value="tt">Trinidad and Tobago</option>
                                <option value="tn">Tunisia</option>
                                <option value="tr">Turkey</option>
                                <option value="tm">Turkmenistan</option>
                                <option value="tv">Tuvalu</option>
                                <option value="ug">Uganda</option>
                                <option value="ua">Ukraine</option>
                                <option value="ae">United Arab Emirates</option>
                                <option value="gb">United Kingdom</option>
                                <option value="us">United States</option>
                                <option value="uy">Uruguay</option>
                                <option value="uz">Uzbekistan</option>
                                <option value="vu">Vanuatu</option>
                                <option value="va">Vatican City</option>
                                <option value="ve">Venezuela</option>
                                <option value="vn">Vietnam</option>
                                <option value="wf">Wallis and Futuna</option>
                                <option value="eh">Western Sahara</option>
                                <option value="ye">Yemen</option>
                                <option value="zm">Zambia</option>
                                <option value="zw">Zimbabwe</option>
                            </select>
                        </div>
                        
                        <div class="text-end">
                           
                            <button type="button" class="btn btn-light waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    {{--  --}}
    <div class="row">
        <div class="col-12">
            <div class="d-flex flex-row justify-content-between">
                <h4>Carriers</h4>
                <button type="button" class="btn btn-success waves-effect waves-light mb-2 float-end" data-bs-toggle="modal" data-bs-target="#custom-modal"><i class="mdi mdi-plus-circle me-1"></i> Add Carrier Markup</button>
            </div>
        </div>
    </div>
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
                                                         
                                                          
                                                            <th>Name</th>
                                                            <th>Percent Value</th>
                                                            <th>Fixed Value</th>

                                                     
                                                            <th>Country</th>
                                                            <th>Actions</th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                      @foreach ($data->markupcarriers as $key=>$item)
                                                          <tr>
                                                        
                                                           
                                                            <td>{{ $item->carrier->name }}</td>
                                                            <td>{{ $item->markup_percent }}</td>
                                                            <td>{{ $item->markup_fixed }}</td>
                                                           
                                                       
                                                            <td>
                                                                @if ($item->countries !== null)
                                                                    @foreach (json_decode($item->countries) as $val)
                                                                        <span class="badge bg-primary text-capitalize fs-6 text-white">{{ $val }}</span>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td>   <i data-feather="edit-2" class="icon-dual text-warning" data-bs-toggle="modal" data-bs-target="#update-modal{{$key}}"  style="width: 15px;"></i> <i data-feather="trash-2" class="icon-dual text-danger ms-1"  data-bs-toggle="modal" data-bs-target="#delete-carrier{{$key}}"  style="width: 15px;" ></i>
                                                              {{-- <a href="{{ route('markup.show',['slug'=>$item->slug]) }}"> <i style="width: 15px;" data-feather="eye" class="icon-dual ms-1 text-info"></i></a> --}}
                                                          </td>


                                                          </tr>

                                                          {{-- Delete Carrier --}}

                                                          <div class="modal fade" id="delete-carrier{{$key}}" tabindex="-1"
                                                          aria-labelledby="delete-modal{{$key}}" aria-hidden="true">
                                                          <div class="modal-dialog modal-dialog-centered">
                                                              <div class="modal-content">
                                                                  <div class="modal-header border-0">
                                                                      <h1 class="modal-title fs-5" id="exampleModalLabel{{ $key }}">
                                                                         Delete Carrier</h1>
                                                                      <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                          aria-label="Close"></button>
                                                                  </div>
                                                                  <div class="modal-body py-0">
                                                                      Are you sure you want to delete this Service?
                                                                  </div>
                                                                  <div class="modal-footer border-0">
                                                                      <button type="button" class="btn btn-light"
                                                                          data-bs-dismiss="modal">Close</button>
                                                                    
                                                                          <a  href="{{ route('markup.delete.carrier',['id'=>$item->id]) }}" class="btn btn-danger">Delete</a>
                                                                     
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      </div>
                          

                                                          {{-- Update Carrier --}}
                                                          <div class="modal fade" id="update-modal{{ $key }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-light">
                                                                        <h4 class="modal-title" id="myCenterModalLabel">Update Carrier</h4>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                                    </div>
                                                                    <div class="modal-body p-4">
                                                                        <form action="{{ route('markup.update.carrier',['id'=>$item->id])}}" method="POST">
                                                                            @csrf
                                                                            <div class="mb-3">
                                                                                <label for="name" class="form-label">Select Carrier</label>
                                                                               <select   name="carrier_id" class="form-select" id="">
                                                                                <option value="">First Select Carrier...</option>
                                                                                @foreach ($carriers as $val)
                                                                                  <option @if($item->carrier_id  === $val->id) selected @endif value="{{ $val->id }}">{{ $val->name }}</option>
                                                                                @endforeach
                                                                               </select>
                                                                            </div>
                                            
                                                                            <div class="d-flex flex-row justify-content-between gap-3">
                        
                                                                                <div class="mb-3 w-auto">
                                                                                    <label for="markup_percent" class="form-label">Markup Value</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-text">%</span>
                                                                                        <input type="number" name="markup_percent"  size="1.1" id="markup_percent" value="{{ $item->markup_percent }}" step="0.01" class="form-control" placeholder="Enter % Value">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="mb-3 w-auto">
                                                                                    <label for="markup_fixed" class="form-label">Markup Value</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-text">$</span>
                                                                                        <input type="number" name="markup_fixed" size="1.1" id="markup_fixed" value="{{ $item->markup_fixed }}" step="0.01" placeholder="Enter $ Value" class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                            </div>
                                                                 
                                            

                                                                        
                                                                       
                                                                   
                                                                      

                                                                 

                                                                    <div class="mb-3">
                                                                        <label for="name" class="form-label">Apply to countries</label>
                                                                        <select name="countries[]" class="form-control select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" data-placeholder="Choose...">
                                                                            <option value="af" @if ($item->countries !== null && in_array('af', json_decode($item->countries))) selected @endif>Afghanistan</option>
                                                                            <option value="al" @if ($item->countries !== null && in_array('al', json_decode($item->countries))) selected @endif>Albania</option>
                                                                            <option value="dz" @if ($item->countries !== null && in_array('dz', json_decode($item->countries))) selected @endif>Algeria</option>
                                                                            <option value="ad" @if ($item->countries !== null && in_array('ad', json_decode($item->countries))) selected @endif>Andorra</option>
                                                                            <option value="ao" @if ($item->countries !== null && in_array('ao', json_decode($item->countries))) selected @endif>Angola</option>
                                                                            <option value="ag" @if ($item->countries !== null && in_array('ag', json_decode($item->countries))) selected @endif>Antigua and Barbuda</option>
                                                                            <option value="ar" @if ($item->countries !== null && in_array('ar', json_decode($item->countries))) selected @endif>Argentina</option>
                                                                            <option value="am" @if ($item->countries !== null && in_array('am', json_decode($item->countries))) selected @endif>Armenia</option>
                                                                            <option value="au" @if ($item->countries !== null && in_array('au', json_decode($item->countries))) selected @endif>Australia</option>
                                                                            <option value="at" @if ($item->countries !== null && in_array('at', json_decode($item->countries))) selected @endif>Austria</option>
                                                                            <option value="az" @if ($item->countries !== null && in_array('az', json_decode($item->countries))) selected @endif>Azerbaijan</option>
                                                                            <option value="bs" @if ($item->countries !== null && in_array('bs', json_decode($item->countries))) selected @endif>Bahamas</option>
                                                                            <option value="bh" @if ($item->countries !== null && in_array('bh', json_decode($item->countries))) selected @endif>Bahrain</option>
                                                                            <option value="bd" @if ($item->countries !== null && in_array('bd', json_decode($item->countries))) selected @endif>Bangladesh</option>
                                                                            <option value="bb" @if ($item->countries !== null && in_array('bb', json_decode($item->countries))) selected @endif>Barbados</option>
                                                                            <option value="by" @if ($item->countries !== null && in_array('by', json_decode($item->countries))) selected @endif>Belarus</option>
                                                                            <option value="be" @if ($item->countries !== null && in_array('be', json_decode($item->countries))) selected @endif>Belgium</option>
                                                                            <option value="bz" @if ($item->countries !== null && in_array('bz', json_decode($item->countries))) selected @endif>Belize</option>
                                                                            <option value="bj" @if ($item->countries !== null && in_array('bj', json_decode($item->countries))) selected @endif>Benin</option>
                                                                            <option value="bt" @if ($item->countries !== null && in_array('bt', json_decode($item->countries))) selected @endif>Bhutan</option>
                                                                            <option value="bo" @if ($item->countries !== null && in_array('bo', json_decode($item->countries))) selected @endif>Bolivia</option>
                                                                            <option value="ba" @if ($item->countries !== null && in_array('ba', json_decode($item->countries))) selected @endif>Bosnia and Herzegovina</option>
                                                                            <option value="bw" @if ($item->countries !== null && in_array('bw', json_decode($item->countries))) selected @endif>Botswana</option>
                                                                            <option value="br" @if ($item->countries !== null && in_array('br', json_decode($item->countries))) selected @endif>Brazil</option>
                                                                            <option value="bn" @if ($item->countries !== null && in_array('bn', json_decode($item->countries))) selected @endif>Brunei</option>
                                                                            <option value="bg" @if ($item->countries !== null && in_array('bg', json_decode($item->countries))) selected @endif>Bulgaria</option>
                                                                            <option value="bf" @if ($item->countries !== null && in_array('bf', json_decode($item->countries))) selected @endif>Burkina Faso</option>
                                                                            <option value="bi" @if ($item->countries !== null && in_array('bi', json_decode($item->countries))) selected @endif>Burundi</option>
                                                                            <option value="cv" @if ($item->countries !== null && in_array('cv', json_decode($item->countries))) selected @endif>Cabo Verde</option>
                                                                            <option value="kh" @if ($item->countries !== null && in_array('kh', json_decode($item->countries))) selected @endif>Cambodia</option>
                                                                            <option value="cm" @if ($item->countries !== null && in_array('cm', json_decode($item->countries))) selected @endif>Cameroon</option>
                                                                            <option value="ca" @if ($item->countries !== null && in_array('ca', json_decode($item->countries))) selected @endif>Canada</option>
                                                                            <option value="cf" @if ($item->countries !== null && in_array('cf', json_decode($item->countries))) selected @endif>Central African Republic</option>
                                                                            <option value="td" @if ($item->countries !== null && in_array('td', json_decode($item->countries))) selected @endif>Chad</option>
                                                                            <option value="cl" @if ($item->countries !== null && in_array('cl', json_decode($item->countries))) selected @endif>Chile</option>
                                                                            <option value="cn" @if ($item->countries !== null && in_array('cn', json_decode($item->countries))) selected @endif>China</option>
                                                                            <option value="co" @if ($item->countries !== null && in_array('co', json_decode($item->countries))) selected @endif>Colombia</option>
                                                                            <option value="km" @if ($item->countries !== null && in_array('km', json_decode($item->countries))) selected @endif>Comoros</option>
                                                                            <option value="cg" @if ($item->countries !== null && in_array('cg', json_decode($item->countries))) selected @endif>Congo</option>
                                                                            <option value="cd" @if ($item->countries !== null && in_array('cd', json_decode($item->countries))) selected @endif>Congo, Democratic Republic of the</option>
                                                                            <option value="cr" @if ($item->countries !== null && in_array('cr', json_decode($item->countries))) selected @endif>Costa Rica</option>
                                                                            <option value="ci" @if ($item->countries !== null && in_array('ci', json_decode($item->countries))) selected @endif>Côte d'Ivoire</option>
                                                                            <option value="hr" @if ($item->countries !== null && in_array('hr', json_decode($item->countries))) selected @endif>Croatia</option>
                                                                            <option value="cu" @if ($item->countries !== null && in_array('cu', json_decode($item->countries))) selected @endif>Cuba</option>
                                                                            <option value="cy" @if ($item->countries !== null && in_array('cy', json_decode($item->countries))) selected @endif>Cyprus</option>
                                                                            <option value="cz" @if ($item->countries !== null && in_array('cz', json_decode($item->countries))) selected @endif>Czech Republic</option>
                                                                            <option value="dk" @if ($item->countries !== null && in_array('dk', json_decode($item->countries))) selected @endif>Denmark</option>
                                                                            <option value="dj" @if ($item->countries !== null && in_array('dj', json_decode($item->countries))) selected @endif>Djibouti</option>
                                                                            <option value="dm" @if ($item->countries !== null && in_array('dm', json_decode($item->countries))) selected @endif>Dominica</option>
                                                                            <option value="do" @if ($item->countries !== null && in_array('do', json_decode($item->countries))) selected @endif>Dominican Republic</option>
                                                                            <option value="ec" @if ($item->countries !== null && in_array('ec', json_decode($item->countries))) selected @endif>Ecuador</option>
                                                                            <option value="eg" @if ($item->countries !== null && in_array('eg', json_decode($item->countries))) selected @endif>Egypt</option>
                                                                            <option value="sv" @if ($item->countries !== null && in_array('sv', json_decode($item->countries))) selected @endif>El Salvador</option>
                                                                            <option value="gq" @if ($item->countries !== null && in_array('gq', json_decode($item->countries))) selected @endif>Equatorial Guinea</option>
                                                                            <option value="er" @if ($item->countries !== null && in_array('er', json_decode($item->countries))) selected @endif>Eritrea</option>
                                                                            <option value="ee" @if ($item->countries !== null && in_array('ee', json_decode($item->countries))) selected @endif>Estonia</option>
                                                                            <option value="sz" @if ($item->countries !== null && in_array('sz', json_decode($item->countries))) selected @endif>Eswatini</option>
                                                                            <option value="et" @if ($item->countries !== null && in_array('et', json_decode($item->countries))) selected @endif>Ethiopia</option>
                                                                            <option value="fj" @if ($item->countries !== null && in_array('fj', json_decode($item->countries))) selected @endif>Fiji</option>
                                                                            <option value="fi" @if ($item->countries !== null && in_array('fi', json_decode($item->countries))) selected @endif>Finland</option>
                                                                            <option value="fr" @if ($item->countries !== null && in_array('fr', json_decode($item->countries))) selected @endif>France</option>
                                                                            <option value="ga" @if ($item->countries !== null && in_array('ga', json_decode($item->countries))) selected @endif>Gabon</option>
                                                                            <option value="gm" @if ($item->countries !== null && in_array('gm', json_decode($item->countries))) selected @endif>Gambia</option>
                                                                            <option value="ge" @if ($item->countries !== null && in_array('ge', json_decode($item->countries))) selected @endif>Georgia</option>
                                                                            <option value="de" @if ($item->countries !== null && in_array('de', json_decode($item->countries))) selected @endif>Germany</option>
                                                                            <option value="gh" @if ($item->countries !== null && in_array('gh', json_decode($item->countries))) selected @endif>Ghana</option>
                                                                            <option value="gr" @if ($item->countries !== null && in_array('gr', json_decode($item->countries))) selected @endif>Greece</option>
                                                                            <option value="gd" @if ($item->countries !== null && in_array('gd', json_decode($item->countries))) selected @endif>Grenada</option>
                                                                            <option value="gt" @if ($item->countries !== null && in_array('gt', json_decode($item->countries))) selected @endif>Guatemala</option>
                                                                            <option value="gn" @if ($item->countries !== null && in_array('gn', json_decode($item->countries))) selected @endif>Guinea</option>
                                                                            <option value="gw" @if ($item->countries !== null && in_array('gw', json_decode($item->countries))) selected @endif>Guinea-Bissau</option>
                                                                            <option value="gy" @if ($item->countries !== null && in_array('gy', json_decode($item->countries))) selected @endif>Guyana</option>
                                                                            <option value="ht" @if ($item->countries !== null && in_array('ht', json_decode($item->countries))) selected @endif>Haiti</option>
                                                                            <option value="hn" @if ($item->countries !== null && in_array('hn', json_decode($item->countries))) selected @endif>Honduras</option>
                                                                            <option value="hu" @if ($item->countries !== null && in_array('hu', json_decode($item->countries))) selected @endif>Hungary</option>
                                                                            <option value="is" @if ($item->countries !== null && in_array('is', json_decode($item->countries))) selected @endif>Iceland</option>
                                                                            <option value="in" @if ($item->countries !== null && in_array('in', json_decode($item->countries))) selected @endif>India</option>
                                                                            <option value="id" @if ($item->countries !== null && in_array('id', json_decode($item->countries))) selected @endif>Indonesia</option>
                                                                            <option value="ir" @if ($item->countries !== null && in_array('ir', json_decode($item->countries))) selected @endif>Iran</option>
                                                                            <option value="iq" @if ($item->countries !== null && in_array('iq', json_decode($item->countries))) selected @endif>Iraq</option>
                                                                            <option value="ie" @if ($item->countries !== null && in_array('ie', json_decode($item->countries))) selected @endif>Ireland</option>
                                                                            <option value="il" @if ($item->countries !== null && in_array('il', json_decode($item->countries))) selected @endif>Israel</option>
                                                                            <option value="it" @if ($item->countries !== null && in_array('it', json_decode($item->countries))) selected @endif>Italy</option>
                                                                            <option value="jm" @if ($item->countries !== null && in_array('jm', json_decode($item->countries))) selected @endif>Jamaica</option>
                                                                            <option value="jp" @if ($item->countries !== null && in_array('jp', json_decode($item->countries))) selected @endif>Japan</option>
                                                                            <option value="jo" @if ($item->countries !== null && in_array('jo', json_decode($item->countries))) selected @endif>Jordan</option>
                                                                            <option value="kz" @if ($item->countries !== null && in_array('kz', json_decode($item->countries))) selected @endif>Kazakhstan</option>
                                                                            <option value="ke" @if ($item->countries !== null && in_array('ke', json_decode($item->countries))) selected @endif>Kenya</option>
                                                                            <option value="ki" @if ($item->countries !== null && in_array('ki', json_decode($item->countries))) selected @endif>Kiribati</option>
                                                                            <option value="kw" @if ($item->countries !== null && in_array('kw', json_decode($item->countries))) selected @endif>Kuwait</option>
                                                                            <option value="kg" @if ($item->countries !== null && in_array('kg', json_decode($item->countries))) selected @endif>Kyrgyzstan</option>
                                                                            <option value="la" @if ($item->countries !== null && in_array('la', json_decode($item->countries))) selected @endif>Laos</option>
                                                                            <option value="lv" @if ($item->countries !== null && in_array('lv', json_decode($item->countries))) selected @endif>Latvia</option>
                                                                            <option value="lb" @if ($item->countries !== null && in_array('lb', json_decode($item->countries))) selected @endif>Lebanon</option>
                                                                            <option value="ls" @if ($item->countries !== null && in_array('ls', json_decode($item->countries))) selected @endif>Lesotho</option>
                                                                            <option value="lr" @if ($item->countries !== null && in_array('lr', json_decode($item->countries))) selected @endif>Liberia</option>
                                                                            <option value="ly" @if ($item->countries !== null && in_array('ly', json_decode($item->countries))) selected @endif>Libya</option>
                                                                            <option value="li" @if ($item->countries !== null && in_array('li', json_decode($item->countries))) selected @endif>Liechtenstein</option>
                                                                            <option value="lt" @if ($item->countries !== null && in_array('lt', json_decode($item->countries))) selected @endif>Lithuania</option>
                                                                            <option value="lu" @if ($item->countries !== null && in_array('lu', json_decode($item->countries))) selected @endif>Luxembourg</option>
                                                                            <option value="mg" @if ($item->countries !== null && in_array('mg', json_decode($item->countries))) selected @endif>Madagascar</option>
                                                                            <option value="mw" @if ($item->countries !== null && in_array('mw', json_decode($item->countries))) selected @endif>Malawi</option>
                                                                            <option value="my" @if ($item->countries !== null && in_array('my', json_decode($item->countries))) selected @endif>Malaysia</option>
                                                                            <option value="mv" @if ($item->countries !== null && in_array('mv', json_decode($item->countries))) selected @endif>Maldives</option>
                                                                            <option value="ml" @if ($item->countries !== null && in_array('ml', json_decode($item->countries))) selected @endif>Mali</option>
                                                                            <option value="mt" @if ($item->countries !== null && in_array('mt', json_decode($item->countries))) selected @endif>Malta</option>
                                                                            <option value="mh" @if ($item->countries !== null && in_array('mh', json_decode($item->countries))) selected @endif>Marshall Islands</option>
                                                                            <option value="mr" @if ($item->countries !== null && in_array('mr', json_decode($item->countries))) selected @endif>Mauritania</option>
                                                                            <option value="mu" @if ($item->countries !== null && in_array('mu', json_decode($item->countries))) selected @endif>Mauritius</option>
                                                                            <option value="mx" @if ($item->countries !== null && in_array('mx', json_decode($item->countries))) selected @endif>Mexico</option>
                                                                            <option value="fm" @if ($item->countries !== null && in_array('fm', json_decode($item->countries))) selected @endif>Micronesia</option>
                                                                            <option value="md" @if ($item->countries !== null && in_array('md', json_decode($item->countries))) selected @endif>Moldova</option>
                                                                            <option value="mc" @if ($item->countries !== null && in_array('mc', json_decode($item->countries))) selected @endif>Monaco</option>
                                                                            <option value="mn" @if ($item->countries !== null && in_array('mn', json_decode($item->countries))) selected @endif>Mongolia</option>
                                                                            <option value="me" @if ($item->countries !== null && in_array('me', json_decode($item->countries))) selected @endif>Montenegro</option>
                                                                            <option value="ma" @if ($item->countries !== null && in_array('ma', json_decode($item->countries))) selected @endif>Morocco</option>
                                                                            <option value="mz" @if ($item->countries !== null && in_array('mz', json_decode($item->countries))) selected @endif>Mozambique</option>
                                                                            <option value="mm" @if ($item->countries !== null && in_array('mm', json_decode($item->countries))) selected @endif>Myanmar</option>
                                                                            <option value="na" @if ($item->countries !== null && in_array('na', json_decode($item->countries))) selected @endif>Namibia</option>
                                                                            <option value="nr" @if ($item->countries !== null && in_array('nr', json_decode($item->countries))) selected @endif>Nauru</option>
                                                                            <option value="np" @if ($item->countries !== null && in_array('np', json_decode($item->countries))) selected @endif>Nepal</option>
                                                                            <option value="nl" @if ($item->countries !== null && in_array('nl', json_decode($item->countries))) selected @endif>Netherlands</option>
                                                                            <option value="nz" @if ($item->countries !== null && in_array('nz', json_decode($item->countries))) selected @endif>New Zealand</option>
                                                                            <option value="ni" @if ($item->countries !== null && in_array('ni', json_decode($item->countries))) selected @endif>Nicaragua</option>
                                                                            <option value="ne" @if ($item->countries !== null && in_array('ne', json_decode($item->countries))) selected @endif>Niger</option>
                                                                            <option value="ng" @if ($item->countries !== null && in_array('ng', json_decode($item->countries))) selected @endif>Nigeria</option>
                                                                            <option value="kp" @if ($item->countries !== null && in_array('kp', json_decode($item->countries))) selected @endif>North Korea</option>
                                                                            <option value="mk" @if ($item->countries !== null && in_array('mk', json_decode($item->countries))) selected @endif>North Macedonia</option>
                                                                            <option value="no" @if ($item->countries !== null && in_array('no', json_decode($item->countries))) selected @endif>Norway</option>
                                                                            <option value="om" @if ($item->countries !== null && in_array('om', json_decode($item->countries))) selected @endif>Oman</option>
                                                                            <option value="pk" @if ($item->countries !== null && in_array('pk', json_decode($item->countries))) selected @endif>Pakistan</option>
                                                                            <option value="pw" @if ($item->countries !== null && in_array('pw', json_decode($item->countries))) selected @endif>Palau</option>
                                                                            <option value="pa" @if ($item->countries !== null && in_array('pa', json_decode($item->countries))) selected @endif>Panama</option>
                                                                            <option value="pg" @if ($item->countries !== null && in_array('pg', json_decode($item->countries))) selected @endif>Papua New Guinea</option>
                                                                            <option value="py" @if ($item->countries !== null && in_array('py', json_decode($item->countries))) selected @endif>Paraguay</option>
                                                                            <option value="pe" @if ($item->countries !== null && in_array('pe', json_decode($item->countries))) selected @endif>Peru</option>
                                                                            <option value="ph" @if ($item->countries !== null && in_array('ph', json_decode($item->countries))) selected @endif>Philippines</option>
                                                                            <option value="pl" @if ($item->countries !== null && in_array('pl', json_decode($item->countries))) selected @endif>Poland</option>
                                                                            <option value="pt" @if ($item->countries !== null && in_array('pt', json_decode($item->countries))) selected @endif>Portugal</option>
                                                                            <option value="qa" @if ($item->countries !== null && in_array('qa', json_decode($item->countries))) selected @endif>Qatar</option>
                                                                            <option value="ro" @if ($item->countries !== null && in_array('ro', json_decode($item->countries))) selected @endif>Romania</option>
                                                                            <option value="ru" @if ($item->countries !== null && in_array('ru', json_decode($item->countries))) selected @endif>Russia</option>
                                                                            <option value="rw" @if ($item->countries !== null && in_array('rw', json_decode($item->countries))) selected @endif>Rwanda</option>
                                                                            <option value="kn" @if ($item->countries !== null && in_array('kn', json_decode($item->countries))) selected @endif>Saint Kitts and Nevis</option>
                                                                            <option value="lc" @if ($item->countries !== null && in_array('lc', json_decode($item->countries))) selected @endif>Saint Lucia</option>
                                                                            <option value="vc" @if ($item->countries !== null && in_array('vc', json_decode($item->countries))) selected @endif>Saint Vincent and the Grenadines</option>
                                                                            <option value="ws" @if ($item->countries !== null && in_array('ws', json_decode($item->countries))) selected @endif>Samoa</option>
                                                                            <option value="sm" @if ($item->countries !== null && in_array('sm', json_decode($item->countries))) selected @endif>San Marino</option>
                                                                            <option value="st" @if ($item->countries !== null && in_array('st', json_decode($item->countries))) selected @endif>Sao Tome and Principe</option>
                                                                            <option value="sa" @if ($item->countries !== null && in_array('sa', json_decode($item->countries))) selected @endif>Saudi Arabia</option>
                                                                            <option value="sn" @if ($item->countries !== null && in_array('sn', json_decode($item->countries))) selected @endif>Senegal</option>
                                                                            <option value="rs" @if ($item->countries !== null && in_array('rs', json_decode($item->countries))) selected @endif>Serbia</option>
                                                                            <option value="sc" @if ($item->countries !== null && in_array('sc', json_decode($item->countries))) selected @endif>Seychelles</option>
                                                                            <option value="sl" @if ($item->countries !== null && in_array('sl', json_decode($item->countries))) selected @endif>Sierra Leone</option>
                                                                            <option value="sg" @if ($item->countries !== null && in_array('sg', json_decode($item->countries))) selected @endif>Singapore</option>
                                                                            <option value="sk" @if ($item->countries !== null && in_array('sk', json_decode($item->countries))) selected @endif>Slovakia</option>
                                                                            <option value="si" @if ($item->countries !== null && in_array('si', json_decode($item->countries))) selected @endif>Slovenia</option>
                                                                            <option value="sb" @if ($item->countries !== null && in_array('sb', json_decode($item->countries))) selected @endif>Solomon Islands</option>
                                                                            <option value="so" @if ($item->countries !== null && in_array('so', json_decode($item->countries))) selected @endif>Somalia</option>
                                                                            <option value="za" @if ($item->countries !== null && in_array('za', json_decode($item->countries))) selected @endif>South Africa</option>
                                                                            <option value="kr" @if ($item->countries !== null && in_array('kr', json_decode($item->countries))) selected @endif>South Korea</option>
                                                                            <option value="ss" @if ($item->countries !== null && in_array('ss', json_decode($item->countries))) selected @endif>South Sudan</option>
                                                                            <option value="es" @if ($item->countries !== null && in_array('es', json_decode($item->countries))) selected @endif>Spain</option>
                                                                            <option value="lk" @if ($item->countries !== null && in_array('lk', json_decode($item->countries))) selected @endif>Sri Lanka</option>
                                                                            <option value="sd" @if ($item->countries !== null && in_array('sd', json_decode($item->countries))) selected @endif>Sudan</option>
                                                                            <option value="sr" @if ($item->countries !== null && in_array('sr', json_decode($item->countries))) selected @endif>Suriname</option>
                                                                            <option value="se" @if ($item->countries !== null && in_array('se', json_decode($item->countries))) selected @endif>Sweden</option>
                                                                            <option value="ch" @if ($item->countries !== null && in_array('ch', json_decode($item->countries))) selected @endif>Switzerland</option>
                                                                            <option value="sy" @if ($item->countries !== null && in_array('sy', json_decode($item->countries))) selected @endif>Syria</option>
                                                                            <option value="tw" @if ($item->countries !== null && in_array('tw', json_decode($item->countries))) selected @endif>Taiwan</option>
                                                                            <option value="tj" @if ($item->countries !== null && in_array('tj', json_decode($item->countries))) selected @endif>Tajikistan</option>
                                                                            <option value="tz" @if ($item->countries !== null && in_array('tz', json_decode($item->countries))) selected @endif>Tanzania</option>
                                                                            <option value="th" @if ($item->countries !== null && in_array('th', json_decode($item->countries))) selected @endif>Thailand</option>
                                                                            <option value="tg" @if ($item->countries !== null && in_array('tg', json_decode($item->countries))) selected @endif>Togo</option>
                                                                            <option value="to" @if ($item->countries !== null && in_array('to', json_decode($item->countries))) selected @endif>Tonga</option>
                                                                            <option value="tt" @if ($item->countries !== null && in_array('tt', json_decode($item->countries))) selected @endif>Trinidad and Tobago</option>
                                                                            <option value="tn" @if ($item->countries !== null && in_array('tn', json_decode($item->countries))) selected @endif>Tunisia</option>
                                                                            <option value="tr" @if ($item->countries !== null && in_array('tr', json_decode($item->countries))) selected @endif>Turkey</option>
                                                                            <option value="tm" @if ($item->countries !== null && in_array('tm', json_decode($item->countries))) selected @endif>Turkmenistan</option>
                                                                            <option value="tv" @if ($item->countries !== null && in_array('tv', json_decode($item->countries))) selected @endif>Tuvalu</option>
                                                                            <option value="ug" @if ($item->countries !== null && in_array('ug', json_decode($item->countries))) selected @endif>Uganda</option>
                                                                            <option value="ua" @if ($item->countries !== null && in_array('ua', json_decode($item->countries))) selected @endif>Ukraine</option>
                                                                            <option value="ae" @if ($item->countries !== null && in_array('ae', json_decode($item->countries))) selected @endif>United Arab Emirates</option>
                                                                            <option value="gb" @if ($item->countries !== null && in_array('gb', json_decode($item->countries))) selected @endif>United Kingdom</option>
                                                                            <option value="us" @if ($item->countries !== null && in_array('us', json_decode($item->countries))) selected @endif>United States</option>
                                                                            <option value="uy" @if ($item->countries !== null && in_array('uy', json_decode($item->countries))) selected @endif>Uruguay</option>
                                                                            <option value="uz" @if ($item->countries !== null && in_array('uz', json_decode($item->countries))) selected @endif>Uzbekistan</option>
                                                                            <option value="vu" @if ($item->countries !== null && in_array('vu', json_decode($item->countries))) selected @endif>Vanuatu</option>
                                                                            <option value="va" @if ($item->countries !== null && in_array('va', json_decode($item->countries))) selected @endif>Vatican City</option>
                                                                            <option value="ve" @if ($item->countries !== null && in_array('ve', json_decode($item->countries))) selected @endif>Venezuela</option>
                                                                            <option value="vn" @if ($item->countries !== null && in_array('vn', json_decode($item->countries))) selected @endif>Vietnam</option>
                                                                            <option value="ye" @if ($item->countries !== null && in_array('ye', json_decode($item->countries))) selected @endif>Yemen</option>
                                                                            <option value="zm" @if ($item->countries !== null && in_array('zm', json_decode($item->countries))) selected @endif>Zambia</option>
                                                                            <option value="zw" @if ($item->countries !== null && in_array('zw', json_decode($item->countries))) selected @endif>Zimbabwe</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                          
                                                                       
                                                                           
                                                                         
                                                    
                                                                            <div class="text-end">
                                                                               
                                                                                <button type="button" class="btn btn-light waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
                                                                                <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div><!-- /.modal-content -->
                                                            </div>
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
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-row justify-content-between">
                        <h4>Services</h4>
                        <button type="button" class="btn btn-success waves-effect waves-light mb-2 float-end" data-bs-toggle="modal" data-bs-target="#add-service"><i class="mdi mdi-plus-circle me-1"></i> Add Service Markup</button>
                    </div>
                   
                  
                </div>
            </div>

            {{-- Add Service  --}}
            <div class="modal fade" id="add-service" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h4 class="modal-title" id="myCenterModalLabel">Add Service Markup</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form action="{{ route('markup.services',['id'=>$data->id])}}" method="POST">
                                @csrf
                                <div class="d-flex flex-row gap-2 justify-content-between">
                                    <div class="mb-3 w-50">
                                        <label for="name" class="form-label">Select Carrier</label>
                                       <select id="carriers" name="carrier_id" class="form-select" id="">
                                        <option  value="">Choose...</option>
                                        @foreach ($carriers as $val)
                                          <option value="{{ $val->id }}">{{ $val->name }}</option>
                                        @endforeach
                                       </select>
                                    </div>
                                    <div class="mb-3 w-50">
                                        <label for="name" class="form-label">Select Services</label>
                                        <select id="services" name="services[]" class="form-control select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" data-placeholder="Choose...">    
                                    </select>
                                    </div>
                                  
                                </div>
                               
                                <div class="d-flex flex-row justify-content-center gap-2">
                                <div class="mb-3 " style="width: 30%;">
                                    <label for="name" class="form-label">Type</label>
                                    <select name="markup_type" class="form-select" id="">
                                      <option value="">Choose...</option>
                                      <option value="percent">%</option>
                                      <option value="fixed">$</option>
    
                                    
                                     </select>
                                </div>
    

                                <div class="mb-3 " style="width: 70%;">
                                  <label for="name" class="form-label">Value</label>
                                  <input type="number" name="amount" step="0.01" placeholder="Enter value" class="form-control" >
                              </div>

                            </div>

                             
                         
                            <div class="mb-3">
                                <label for="name" class="form-label">Apply to countries</label>
                                <select name="countries[]" class="form-control select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" data-placeholder="Choose...">
                                    <option value="af">Afghanistan</option>
                                    <option value="al">Albania</option>
                                    <option value="dz">Algeria</option>
                                    <option value="ad">Andorra</option>
                                    <option value="ao">Angola</option>
                                    <option value="ag">Antigua and Barbuda</option>
                                    <option value="ar">Argentina</option>
                                    <option value="am">Armenia</option>
                                    <option value="au">Australia</option>
                                    <option value="at">Austria</option>
                                    <option value="az">Azerbaijan</option>
                                    <option value="bs">Bahamas</option>
                                    <option value="bh">Bahrain</option>
                                    <option value="bd">Bangladesh</option>
                                    <option value="bb">Barbados</option>
                                    <option value="by">Belarus</option>
                                    <option value="be">Belgium</option>
                                    <option value="bz">Belize</option>
                                    <option value="bj">Benin</option>
                                    <option value="bt">Bhutan</option>
                                    <option value="bo">Bolivia</option>
                                    <option value="ba">Bosnia and Herzegovina</option>
                                    <option value="bw">Botswana</option>
                                    <option value="br">Brazil</option>
                                    <option value="bn">Brunei</option>
                                    <option value="bg">Bulgaria</option>
                                    <option value="bf">Burkina Faso</option>
                                    <option value="bi">Burundi</option>
                                    <option value="cv">Cabo Verde</option>
                                    <option value="kh">Cambodia</option>
                                    <option value="cm">Cameroon</option>
                                    <option value="ca">Canada</option>
                                    <option value="cf">Central African Republic</option>
                                    <option value="td">Chad</option>
                                    <option value="cl">Chile</option>
                                    <option value="cn">China</option>
                                    <option value="co">Colombia</option>
                                    <option value="km">Comoros</option>
                                    <option value="cg">Congo</option>
                                    <option value="cd">Congo, Democratic Republic of the</option>
                                    <option value="cr">Costa Rica</option>
                                    <option value="ci">Côte d'Ivoire</option>
                                    <option value="hr">Croatia</option>
                                    <option value="cu">Cuba</option>
                                    <option value="cy">Cyprus</option>
                                    <option value="cz">Czech Republic</option>
                                    <option value="dk">Denmark</option>
                                    <option value="dj">Djibouti</option>
                                    <option value="dm">Dominica</option>
                                    <option value="do">Dominican Republic</option>
                                    <option value="ec">Ecuador</option>
                                    <option value="eg">Egypt</option>
                                    <option value="sv">El Salvador</option>
                                    <option value="gq">Equatorial Guinea</option>
                                    <option value="er">Eritrea</option>
                                    <option value="ee">Estonia</option>
                                    <option value="sz">Eswatini</option>
                                    <option value="et">Ethiopia</option>
                                    <option value="fj">Fiji</option>
                                    <option value="fi">Finland</option>
                                    <option value="fr">France</option>
                                    <option value="ga">Gabon</option>
                                    <option value="gm">Gambia</option>
                                    <option value="ge">Georgia</option>
                                    <option value="de">Germany</option>
                                    <option value="gh">Ghana</option>
                                    <option value="gr">Greece</option>
                                    <option value="gd">Grenada</option>
                                    <option value="gt">Guatemala</option>
                                    <option value="gn">Guinea</option>
                                    <option value="gw">Guinea-Bissau</option>
                                    <option value="gy">Guyana</option>
                                    <option value="ht">Haiti</option>
                                    <option value="hn">Honduras</option>
                                    <option value="hk">Hong Kong</option>
                                    <option value="hu">Hungary</option>
                                    <option value="is">Iceland</option>
                                    <option value="in">India</option>
                                    <option value="id">Indonesia</option>
                                    <option value="ir">Iran</option>
                                    <option value="iq">Iraq</option>
                                    <option value="ie">Ireland</option>
                                    <option value="il">Israel</option>
                                    <option value="it">Italy</option>
                                    <option value="jm">Jamaica</option>
                                    <option value="jp">Japan</option>
                                    <option value="jo">Jordan</option>
                                    <option value="kz">Kazakhstan</option>
                                    <option value="ke">Kenya</option>
                                    <option value="ki">Kiribati</option>
                                    <option value="kr">South Korea</option>
                                    <option value="kp">North Korea</option>
                                    <option value="kw">Kuwait</option>
                                    <option value="kg">Kyrgyzstan</option>
                                    <option value="la">Laos</option>
                                    <option value="lv">Latvia</option>
                                    <option value="lb">Lebanon</option>
                                    <option value="ls">Lesotho</option>
                                    <option value="lr">Liberia</option>
                                    <option value="ly">Libya</option>
                                    <option value="li">Liechtenstein</option>
                                    <option value="lt">Lithuania</option>
                                    <option value="lu">Luxembourg</option>
                                    <option value="mk">North Macedonia</option>
                                    <option value="mg">Madagascar</option>
                                    <option value="mw">Malawi</option>
                                    <option value="my">Malaysia</option>
                                    <option value="mv">Maldives</option>
                                    <option value="ml">Mali</option>
                                    <option value="mt">Malta</option>
                                    <option value="mh">Marshall Islands</option>
                                    <option value="mr">Mauritania</option>
                                    <option value="mu">Mauritius</option>
                                    <option value="mx">Mexico</option>
                                    <option value="fm">Micronesia</option>
                                    <option value="md">Moldova</option>
                                    <option value="mc">Monaco</option>
                                    <option value="mn">Mongolia</option>
                                    <option value="me">Montenegro</option>
                                    <option value="ma">Morocco</option>
                                    <option value="mz">Mozambique</option>
                                    <option value="mm">Myanmar</option>
                                    <option value="na">Namibia</option>
                                    <option value="nr">Nauru</option>
                                    <option value="np">Nepal</option>
                                    <option value="nl">Netherlands</option>
                                    <option value="nc">New Caledonia</option>
                                    <option value="nz">New Zealand</option>
                                    <option value="ni">Nicaragua</option>
                                    <option value="ne">Niger</option>
                                    <option value="ng">Nigeria</option>
                                    <option value="nu">Niue</option>
                                    <option value="nf">Norfolk Island</option>
                                    <option value="mp">Northern Mariana Islands</option>
                                    <option value="no">Norway</option>
                                    <option value="om">Oman</option>
                                    <option value="pk">Pakistan</option>
                                    <option value="pw">Palau</option>
                                    <option value="pa">Panama</option>
                                    <option value="pg">Papua New Guinea</option>
                                    <option value="py">Paraguay</option>
                                    <option value="pe">Peru</option>
                                    <option value="ph">Philippines</option>
                                    <option value="pn">Pitcairn Islands</option>
                                    <option value="pl">Poland</option>
                                    <option value="pt">Portugal</option>
                                    <option value="pr">Puerto Rico</option>
                                    <option value="qa">Qatar</option>
                                    <option value="re">Réunion</option>
                                    <option value="ro">Romania</option>
                                    <option value="ru">Russia</option>
                                    <option value="rw">Rwanda</option>
                                    <option value="bl">Saint Barthélemy</option>
                                    <option value="sh">Saint Helena</option>
                                    <option value="kn">Saint Kitts and Nevis</option>
                                    <option value="lc">Saint Lucia</option>
                                    <option value="mf">Saint Martin</option>
                                    <option value="pm">Saint Pierre and Miquelon</option>
                                    <option value="vc">Saint Vincent and the Grenadines</option>
                                    <option value="ws">Samoa</option>
                                    <option value="sm">San Marino</option>
                                    <option value="st">Sao Tome and Principe</option>
                                    <option value="sa">Saudi Arabia</option>
                                    <option value="sn">Senegal</option>
                                    <option value="rs">Serbia</option>
                                    <option value="sc">Seychelles</option>
                                    <option value="sl">Sierra Leone</option>
                                    <option value="sg">Singapore</option>
                                    <option value="sx">Sint Maarten</option>
                                    <option value="sk">Slovakia</option>
                                    <option value="si">Slovenia</option>
                                    <option value="sb">Solomon Islands</option>
                                    <option value="so">Somalia</option>
                                    <option value="za">South Africa</option>
                                    <option value="ss">South Sudan</option>
                                    <option value="es">Spain</option>
                                    <option value="lk">Sri Lanka</option>
                                    <option value="sd">Sudan</option>
                                    <option value="sr">Suriname</option>
                                    <option value="sz">Eswatini</option>
                                    <option value="se">Sweden</option>
                                    <option value="ch">Switzerland</option>
                                    <option value="sy">Syria</option>
                                    <option value="tw">Taiwan</option>
                                    <option value="tj">Tajikistan</option>
                                    <option value="tz">Tanzania</option>
                                    <option value="th">Thailand</option>
                                    <option value="tl">Timor-Leste</option>
                                    <option value="tg">Togo</option>
                                    <option value="tk">Tokelau</option>
                                    <option value="to">Tonga</option>
                                    <option value="tt">Trinidad and Tobago</option>
                                    <option value="tn">Tunisia</option>
                                    <option value="tr">Turkey</option>
                                    <option value="tm">Turkmenistan</option>
                                    <option value="tv">Tuvalu</option>
                                    <option value="ug">Uganda</option>
                                    <option value="ua">Ukraine</option>
                                    <option value="ae">United Arab Emirates</option>
                                    <option value="gb">United Kingdom</option>
                                    <option value="us">United States</option>
                                    <option value="uy">Uruguay</option>
                                    <option value="uz">Uzbekistan</option>
                                    <option value="vu">Vanuatu</option>
                                    <option value="va">Vatican City</option>
                                    <option value="ve">Venezuela</option>
                                    <option value="vn">Vietnam</option>
                                    <option value="wf">Wallis and Futuna</option>
                                    <option value="eh">Western Sahara</option>
                                    <option value="ye">Yemen</option>
                                    <option value="zm">Zambia</option>
                                    <option value="zw">Zimbabwe</option>
                                </select>
                            </div>
                              
                           
                               
                             
        
                                <div class="text-end">
                                   
                                    <button type="button" class="btn btn-light waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div>
            </div>

            {{--  --}}
          
          
            <div class="card ">
                <div class="card-body">
                    <div class="tab-content pt-0">
                      <div class="table-responsive">
                        <table class="table mb-0" style="font-size: 12px;">
                            <thead class="table-light">
                                <tr>
                                   
                                    <th>Carrier</th>
                                    <th style="width: 50%!important;">Services</th>
                                    <th>Amount</th>
                                    <th>Markup Type</th>
                                    <th>countries</th>
                                    <th>Actions</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->markupservices as $key=>$item) <!-- Assuming $items is the array you are iterating over -->
                                <tr>
                                    <td>{{ $item->carrier->name }}</td>
                                    <td>
                                        @foreach (json_decode($item->services) as $val)
                                            @php
                                                $service_name = DB::table('carrier_services')->where('id', intVal($val))->first();
                                            @endphp
                                            @if ($service_name)
                                                <span class="badge bg-secondary text-white">{{ $service_name->service_name }}</span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->markup_type }}</td>
                                    <td>
                                        @if ($item->countries !== null)
                                            @foreach (json_decode($item->countries) as $val)
                                                <span class="badge bg-primary text-capitalize fs-6 text-white">{{ $val }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <i data-feather="edit-2" class="icon-dual text-warning" data-bs-toggle="modal" data-bs-target="#update-service{{$key}}"  style="width: 15px;"></i> 
                                       
                                        <i data-feather="trash-2" class="icon-dual text-danger ms-1" data-bs-toggle="modal" data-bs-target="#delete-modal{{$key}}" style="width: 15px;" ></i>
                                </tr>
                                {{-- Update Service --}}
                                <div class="modal fade" id="update-service{{ $key }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h4 class="modal-title" id="myCenterModalLabel">Update Service</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form action="{{ route('markup.update.service',['id'=>$item->id])}}" method="POST">
                                                    @csrf
                                                  
                                                   
                                                  
                    
                                                    <div class="d-flex flex-row gap-2 justify-content-between">
                                                        
                                                        <div class="mb-3">
                                                            @php
                                                                $carrier=DB::table('carriers')->where('id',$item->carrier_id)->first();
                                                                $services=DB::table('carrier_services')->where('carrier_name',$carrier->name)->get();
                                                            @endphp
                                                            <label for="name" class="form-label">Select Carrier</label>
                                                            <select id="update-carrier"  name="carrier_id" class="form-select" id="">
                                                                <option value="">First Select Carrier...</option>
                                                                @foreach ($carriers as $val)
                                                                  <option @if($item->carrier_id  === $val->id) selected @endif value="{{ $val->id }}">{{ $val->name }}</option>
                                                                @endforeach
                                                               </select>
    
                                                        </div>
                                                        <div class="mb-3">
                                                            @php
                                                                $carrier=DB::table('carriers')->where('id',$item->carrier_id)->first();
                                                                $services=DB::table('carrier_services')->where('carrier_name',$carrier->name)->get();
                                                            @endphp
                                                            <label for="name" class="form-label">Select Services</label>
                                                            <select  name="services[]" class="form-control select2-multiple update-services" data-toggle="select2" data-width="100%" multiple="multiple" data-placeholder="Choose...">
                                                                @foreach ($services as $service)
                                                                <option value="{{ $service->id }}" @if(in_array($service->id, json_decode($item->services))) selected @endif>
                                                                    {{ $service->service_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
    
                                                        </div>
                                                            
    
                                                      
                                                    </div>
                                                   
                                                    <div class="d-flex flex-row justify-content-center gap-2">
                                                    <div class="mb-3 " style="width: 30%;">
                                                        <label for="name" class="form-label">Type</label>
                                                        <select name="markup_type" class="form-select" id="">
                                                          <option value="">Choose...</option>
                                                          <option @if($item->markup_type === "percent") selected @endif value="percent">%</option>
                                                          <option @if($item->markup_type === "fixed") selected @endif value="fixed">$</option>
                                                         </select>
                                                    </div>
                        
                    
                                                    <div class="mb-3 " style="width: 70%;">
                                                      <label for="name" class="form-label">Value</label>
                                                      <input value="{{ $item->amount }}" type="number" name="amount" step="0.01" placeholder="Enter value" class="form-control" >
                                                  </div>
                    
                                                </div>
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Apply to countries</label>
                                                    <select name="countries[]" class="form-control select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" data-placeholder="Choose...">
                                                        <option value="af" @if ($item->countries !== null && in_array('af', json_decode($item->countries))) selected @endif>Afghanistan</option>
                                                        <option value="al" @if ($item->countries !== null && in_array('al', json_decode($item->countries))) selected @endif>Albania</option>
                                                        <option value="dz" @if ($item->countries !== null && in_array('dz', json_decode($item->countries))) selected @endif>Algeria</option>
                                                        <option value="ad" @if ($item->countries !== null && in_array('ad', json_decode($item->countries))) selected @endif>Andorra</option>
                                                        <option value="ao" @if ($item->countries !== null && in_array('ao', json_decode($item->countries))) selected @endif>Angola</option>
                                                        <option value="ag" @if ($item->countries !== null && in_array('ag', json_decode($item->countries))) selected @endif>Antigua and Barbuda</option>
                                                        <option value="ar" @if ($item->countries !== null && in_array('ar', json_decode($item->countries))) selected @endif>Argentina</option>
                                                        <option value="am" @if ($item->countries !== null && in_array('am', json_decode($item->countries))) selected @endif>Armenia</option>
                                                        <option value="au" @if ($item->countries !== null && in_array('au', json_decode($item->countries))) selected @endif>Australia</option>
                                                        <option value="at" @if ($item->countries !== null && in_array('at', json_decode($item->countries))) selected @endif>Austria</option>
                                                        <option value="az" @if ($item->countries !== null && in_array('az', json_decode($item->countries))) selected @endif>Azerbaijan</option>
                                                        <option value="bs" @if ($item->countries !== null && in_array('bs', json_decode($item->countries))) selected @endif>Bahamas</option>
                                                        <option value="bh" @if ($item->countries !== null && in_array('bh', json_decode($item->countries))) selected @endif>Bahrain</option>
                                                        <option value="bd" @if ($item->countries !== null && in_array('bd', json_decode($item->countries))) selected @endif>Bangladesh</option>
                                                        <option value="bb" @if ($item->countries !== null && in_array('bb', json_decode($item->countries))) selected @endif>Barbados</option>
                                                        <option value="by" @if ($item->countries !== null && in_array('by', json_decode($item->countries))) selected @endif>Belarus</option>
                                                        <option value="be" @if ($item->countries !== null && in_array('be', json_decode($item->countries))) selected @endif>Belgium</option>
                                                        <option value="bz" @if ($item->countries !== null && in_array('bz', json_decode($item->countries))) selected @endif>Belize</option>
                                                        <option value="bj" @if ($item->countries !== null && in_array('bj', json_decode($item->countries))) selected @endif>Benin</option>
                                                        <option value="bt" @if ($item->countries !== null && in_array('bt', json_decode($item->countries))) selected @endif>Bhutan</option>
                                                        <option value="bo" @if ($item->countries !== null && in_array('bo', json_decode($item->countries))) selected @endif>Bolivia</option>
                                                        <option value="ba" @if ($item->countries !== null && in_array('ba', json_decode($item->countries))) selected @endif>Bosnia and Herzegovina</option>
                                                        <option value="bw" @if ($item->countries !== null && in_array('bw', json_decode($item->countries))) selected @endif>Botswana</option>
                                                        <option value="br" @if ($item->countries !== null && in_array('br', json_decode($item->countries))) selected @endif>Brazil</option>
                                                        <option value="bn" @if ($item->countries !== null && in_array('bn', json_decode($item->countries))) selected @endif>Brunei</option>
                                                        <option value="bg" @if ($item->countries !== null && in_array('bg', json_decode($item->countries))) selected @endif>Bulgaria</option>
                                                        <option value="bf" @if ($item->countries !== null && in_array('bf', json_decode($item->countries))) selected @endif>Burkina Faso</option>
                                                        <option value="bi" @if ($item->countries !== null && in_array('bi', json_decode($item->countries))) selected @endif>Burundi</option>
                                                        <option value="cv" @if ($item->countries !== null && in_array('cv', json_decode($item->countries))) selected @endif>Cabo Verde</option>
                                                        <option value="kh" @if ($item->countries !== null && in_array('kh', json_decode($item->countries))) selected @endif>Cambodia</option>
                                                        <option value="cm" @if ($item->countries !== null && in_array('cm', json_decode($item->countries))) selected @endif>Cameroon</option>
                                                        <option value="ca" @if ($item->countries !== null && in_array('ca', json_decode($item->countries))) selected @endif>Canada</option>
                                                        <option value="cf" @if ($item->countries !== null && in_array('cf', json_decode($item->countries))) selected @endif>Central African Republic</option>
                                                        <option value="td" @if ($item->countries !== null && in_array('td', json_decode($item->countries))) selected @endif>Chad</option>
                                                        <option value="cl" @if ($item->countries !== null && in_array('cl', json_decode($item->countries))) selected @endif>Chile</option>
                                                        <option value="cn" @if ($item->countries !== null && in_array('cn', json_decode($item->countries))) selected @endif>China</option>
                                                        <option value="co" @if ($item->countries !== null && in_array('co', json_decode($item->countries))) selected @endif>Colombia</option>
                                                        <option value="km" @if ($item->countries !== null && in_array('km', json_decode($item->countries))) selected @endif>Comoros</option>
                                                        <option value="cg" @if ($item->countries !== null && in_array('cg', json_decode($item->countries))) selected @endif>Congo</option>
                                                        <option value="cd" @if ($item->countries !== null && in_array('cd', json_decode($item->countries))) selected @endif>Congo, Democratic Republic of the</option>
                                                        <option value="cr" @if ($item->countries !== null && in_array('cr', json_decode($item->countries))) selected @endif>Costa Rica</option>
                                                        <option value="ci" @if ($item->countries !== null && in_array('ci', json_decode($item->countries))) selected @endif>Côte d'Ivoire</option>
                                                        <option value="hr" @if ($item->countries !== null && in_array('hr', json_decode($item->countries))) selected @endif>Croatia</option>
                                                        <option value="cu" @if ($item->countries !== null && in_array('cu', json_decode($item->countries))) selected @endif>Cuba</option>
                                                        <option value="cy" @if ($item->countries !== null && in_array('cy', json_decode($item->countries))) selected @endif>Cyprus</option>
                                                        <option value="cz" @if ($item->countries !== null && in_array('cz', json_decode($item->countries))) selected @endif>Czech Republic</option>
                                                        <option value="dk" @if ($item->countries !== null && in_array('dk', json_decode($item->countries))) selected @endif>Denmark</option>
                                                        <option value="dj" @if ($item->countries !== null && in_array('dj', json_decode($item->countries))) selected @endif>Djibouti</option>
                                                        <option value="dm" @if ($item->countries !== null && in_array('dm', json_decode($item->countries))) selected @endif>Dominica</option>
                                                        <option value="do" @if ($item->countries !== null && in_array('do', json_decode($item->countries))) selected @endif>Dominican Republic</option>
                                                        <option value="ec" @if ($item->countries !== null && in_array('ec', json_decode($item->countries))) selected @endif>Ecuador</option>
                                                        <option value="eg" @if ($item->countries !== null && in_array('eg', json_decode($item->countries))) selected @endif>Egypt</option>
                                                        <option value="sv" @if ($item->countries !== null && in_array('sv', json_decode($item->countries))) selected @endif>El Salvador</option>
                                                        <option value="gq" @if ($item->countries !== null && in_array('gq', json_decode($item->countries))) selected @endif>Equatorial Guinea</option>
                                                        <option value="er" @if ($item->countries !== null && in_array('er', json_decode($item->countries))) selected @endif>Eritrea</option>
                                                        <option value="ee" @if ($item->countries !== null && in_array('ee', json_decode($item->countries))) selected @endif>Estonia</option>
                                                        <option value="sz" @if ($item->countries !== null && in_array('sz', json_decode($item->countries))) selected @endif>Eswatini</option>
                                                        <option value="et" @if ($item->countries !== null && in_array('et', json_decode($item->countries))) selected @endif>Ethiopia</option>
                                                        <option value="fj" @if ($item->countries !== null && in_array('fj', json_decode($item->countries))) selected @endif>Fiji</option>
                                                        <option value="fi" @if ($item->countries !== null && in_array('fi', json_decode($item->countries))) selected @endif>Finland</option>
                                                        <option value="fr" @if ($item->countries !== null && in_array('fr', json_decode($item->countries))) selected @endif>France</option>
                                                        <option value="ga" @if ($item->countries !== null && in_array('ga', json_decode($item->countries))) selected @endif>Gabon</option>
                                                        <option value="gm" @if ($item->countries !== null && in_array('gm', json_decode($item->countries))) selected @endif>Gambia</option>
                                                        <option value="ge" @if ($item->countries !== null && in_array('ge', json_decode($item->countries))) selected @endif>Georgia</option>
                                                        <option value="de" @if ($item->countries !== null && in_array('de', json_decode($item->countries))) selected @endif>Germany</option>
                                                        <option value="gh" @if ($item->countries !== null && in_array('gh', json_decode($item->countries))) selected @endif>Ghana</option>
                                                        <option value="gr" @if ($item->countries !== null && in_array('gr', json_decode($item->countries))) selected @endif>Greece</option>
                                                        <option value="gd" @if ($item->countries !== null && in_array('gd', json_decode($item->countries))) selected @endif>Grenada</option>
                                                        <option value="gt" @if ($item->countries !== null && in_array('gt', json_decode($item->countries))) selected @endif>Guatemala</option>
                                                        <option value="gn" @if ($item->countries !== null && in_array('gn', json_decode($item->countries))) selected @endif>Guinea</option>
                                                        <option value="gw" @if ($item->countries !== null && in_array('gw', json_decode($item->countries))) selected @endif>Guinea-Bissau</option>
                                                        <option value="gy" @if ($item->countries !== null && in_array('gy', json_decode($item->countries))) selected @endif>Guyana</option>
                                                        <option value="ht" @if ($item->countries !== null && in_array('ht', json_decode($item->countries))) selected @endif>Haiti</option>
                                                        <option value="hn" @if ($item->countries !== null && in_array('hn', json_decode($item->countries))) selected @endif>Honduras</option>
                                                        <option value="hu" @if ($item->countries !== null && in_array('hu', json_decode($item->countries))) selected @endif>Hungary</option>
                                                        <option value="is" @if ($item->countries !== null && in_array('is', json_decode($item->countries))) selected @endif>Iceland</option>
                                                        <option value="in" @if ($item->countries !== null && in_array('in', json_decode($item->countries))) selected @endif>India</option>
                                                        <option value="id" @if ($item->countries !== null && in_array('id', json_decode($item->countries))) selected @endif>Indonesia</option>
                                                        <option value="ir" @if ($item->countries !== null && in_array('ir', json_decode($item->countries))) selected @endif>Iran</option>
                                                        <option value="iq" @if ($item->countries !== null && in_array('iq', json_decode($item->countries))) selected @endif>Iraq</option>
                                                        <option value="ie" @if ($item->countries !== null && in_array('ie', json_decode($item->countries))) selected @endif>Ireland</option>
                                                        <option value="il" @if ($item->countries !== null && in_array('il', json_decode($item->countries))) selected @endif>Israel</option>
                                                        <option value="it" @if ($item->countries !== null && in_array('it', json_decode($item->countries))) selected @endif>Italy</option>
                                                        <option value="jm" @if ($item->countries !== null && in_array('jm', json_decode($item->countries))) selected @endif>Jamaica</option>
                                                        <option value="jp" @if ($item->countries !== null && in_array('jp', json_decode($item->countries))) selected @endif>Japan</option>
                                                        <option value="jo" @if ($item->countries !== null && in_array('jo', json_decode($item->countries))) selected @endif>Jordan</option>
                                                        <option value="kz" @if ($item->countries !== null && in_array('kz', json_decode($item->countries))) selected @endif>Kazakhstan</option>
                                                        <option value="ke" @if ($item->countries !== null && in_array('ke', json_decode($item->countries))) selected @endif>Kenya</option>
                                                        <option value="ki" @if ($item->countries !== null && in_array('ki', json_decode($item->countries))) selected @endif>Kiribati</option>
                                                        <option value="kw" @if ($item->countries !== null && in_array('kw', json_decode($item->countries))) selected @endif>Kuwait</option>
                                                        <option value="kg" @if ($item->countries !== null && in_array('kg', json_decode($item->countries))) selected @endif>Kyrgyzstan</option>
                                                        <option value="la" @if ($item->countries !== null && in_array('la', json_decode($item->countries))) selected @endif>Laos</option>
                                                        <option value="lv" @if ($item->countries !== null && in_array('lv', json_decode($item->countries))) selected @endif>Latvia</option>
                                                        <option value="lb" @if ($item->countries !== null && in_array('lb', json_decode($item->countries))) selected @endif>Lebanon</option>
                                                        <option value="ls" @if ($item->countries !== null && in_array('ls', json_decode($item->countries))) selected @endif>Lesotho</option>
                                                        <option value="lr" @if ($item->countries !== null && in_array('lr', json_decode($item->countries))) selected @endif>Liberia</option>
                                                        <option value="ly" @if ($item->countries !== null && in_array('ly', json_decode($item->countries))) selected @endif>Libya</option>
                                                        <option value="li" @if ($item->countries !== null && in_array('li', json_decode($item->countries))) selected @endif>Liechtenstein</option>
                                                        <option value="lt" @if ($item->countries !== null && in_array('lt', json_decode($item->countries))) selected @endif>Lithuania</option>
                                                        <option value="lu" @if ($item->countries !== null && in_array('lu', json_decode($item->countries))) selected @endif>Luxembourg</option>
                                                        <option value="mg" @if ($item->countries !== null && in_array('mg', json_decode($item->countries))) selected @endif>Madagascar</option>
                                                        <option value="mw" @if ($item->countries !== null && in_array('mw', json_decode($item->countries))) selected @endif>Malawi</option>
                                                        <option value="my" @if ($item->countries !== null && in_array('my', json_decode($item->countries))) selected @endif>Malaysia</option>
                                                        <option value="mv" @if ($item->countries !== null && in_array('mv', json_decode($item->countries))) selected @endif>Maldives</option>
                                                        <option value="ml" @if ($item->countries !== null && in_array('ml', json_decode($item->countries))) selected @endif>Mali</option>
                                                        <option value="mt" @if ($item->countries !== null && in_array('mt', json_decode($item->countries))) selected @endif>Malta</option>
                                                        <option value="mh" @if ($item->countries !== null && in_array('mh', json_decode($item->countries))) selected @endif>Marshall Islands</option>
                                                        <option value="mr" @if ($item->countries !== null && in_array('mr', json_decode($item->countries))) selected @endif>Mauritania</option>
                                                        <option value="mu" @if ($item->countries !== null && in_array('mu', json_decode($item->countries))) selected @endif>Mauritius</option>
                                                        <option value="mx" @if ($item->countries !== null && in_array('mx', json_decode($item->countries))) selected @endif>Mexico</option>
                                                        <option value="fm" @if ($item->countries !== null && in_array('fm', json_decode($item->countries))) selected @endif>Micronesia</option>
                                                        <option value="md" @if ($item->countries !== null && in_array('md', json_decode($item->countries))) selected @endif>Moldova</option>
                                                        <option value="mc" @if ($item->countries !== null && in_array('mc', json_decode($item->countries))) selected @endif>Monaco</option>
                                                        <option value="mn" @if ($item->countries !== null && in_array('mn', json_decode($item->countries))) selected @endif>Mongolia</option>
                                                        <option value="me" @if ($item->countries !== null && in_array('me', json_decode($item->countries))) selected @endif>Montenegro</option>
                                                        <option value="ma" @if ($item->countries !== null && in_array('ma', json_decode($item->countries))) selected @endif>Morocco</option>
                                                        <option value="mz" @if ($item->countries !== null && in_array('mz', json_decode($item->countries))) selected @endif>Mozambique</option>
                                                        <option value="mm" @if ($item->countries !== null && in_array('mm', json_decode($item->countries))) selected @endif>Myanmar</option>
                                                        <option value="na" @if ($item->countries !== null && in_array('na', json_decode($item->countries))) selected @endif>Namibia</option>
                                                        <option value="nr" @if ($item->countries !== null && in_array('nr', json_decode($item->countries))) selected @endif>Nauru</option>
                                                        <option value="np" @if ($item->countries !== null && in_array('np', json_decode($item->countries))) selected @endif>Nepal</option>
                                                        <option value="nl" @if ($item->countries !== null && in_array('nl', json_decode($item->countries))) selected @endif>Netherlands</option>
                                                        <option value="nz" @if ($item->countries !== null && in_array('nz', json_decode($item->countries))) selected @endif>New Zealand</option>
                                                        <option value="ni" @if ($item->countries !== null && in_array('ni', json_decode($item->countries))) selected @endif>Nicaragua</option>
                                                        <option value="ne" @if ($item->countries !== null && in_array('ne', json_decode($item->countries))) selected @endif>Niger</option>
                                                        <option value="ng" @if ($item->countries !== null && in_array('ng', json_decode($item->countries))) selected @endif>Nigeria</option>
                                                        <option value="kp" @if ($item->countries !== null && in_array('kp', json_decode($item->countries))) selected @endif>North Korea</option>
                                                        <option value="mk" @if ($item->countries !== null && in_array('mk', json_decode($item->countries))) selected @endif>North Macedonia</option>
                                                        <option value="no" @if ($item->countries !== null && in_array('no', json_decode($item->countries))) selected @endif>Norway</option>
                                                        <option value="om" @if ($item->countries !== null && in_array('om', json_decode($item->countries))) selected @endif>Oman</option>
                                                        <option value="pk" @if ($item->countries !== null && in_array('pk', json_decode($item->countries))) selected @endif>Pakistan</option>
                                                        <option value="pw" @if ($item->countries !== null && in_array('pw', json_decode($item->countries))) selected @endif>Palau</option>
                                                        <option value="pa" @if ($item->countries !== null && in_array('pa', json_decode($item->countries))) selected @endif>Panama</option>
                                                        <option value="pg" @if ($item->countries !== null && in_array('pg', json_decode($item->countries))) selected @endif>Papua New Guinea</option>
                                                        <option value="py" @if ($item->countries !== null && in_array('py', json_decode($item->countries))) selected @endif>Paraguay</option>
                                                        <option value="pe" @if ($item->countries !== null && in_array('pe', json_decode($item->countries))) selected @endif>Peru</option>
                                                        <option value="ph" @if ($item->countries !== null && in_array('ph', json_decode($item->countries))) selected @endif>Philippines</option>
                                                        <option value="pl" @if ($item->countries !== null && in_array('pl', json_decode($item->countries))) selected @endif>Poland</option>
                                                        <option value="pt" @if ($item->countries !== null && in_array('pt', json_decode($item->countries))) selected @endif>Portugal</option>
                                                        <option value="qa" @if ($item->countries !== null && in_array('qa', json_decode($item->countries))) selected @endif>Qatar</option>
                                                        <option value="ro" @if ($item->countries !== null && in_array('ro', json_decode($item->countries))) selected @endif>Romania</option>
                                                        <option value="ru" @if ($item->countries !== null && in_array('ru', json_decode($item->countries))) selected @endif>Russia</option>
                                                        <option value="rw" @if ($item->countries !== null && in_array('rw', json_decode($item->countries))) selected @endif>Rwanda</option>
                                                        <option value="kn" @if ($item->countries !== null && in_array('kn', json_decode($item->countries))) selected @endif>Saint Kitts and Nevis</option>
                                                        <option value="lc" @if ($item->countries !== null && in_array('lc', json_decode($item->countries))) selected @endif>Saint Lucia</option>
                                                        <option value="vc" @if ($item->countries !== null && in_array('vc', json_decode($item->countries))) selected @endif>Saint Vincent and the Grenadines</option>
                                                        <option value="ws" @if ($item->countries !== null && in_array('ws', json_decode($item->countries))) selected @endif>Samoa</option>
                                                        <option value="sm" @if ($item->countries !== null && in_array('sm', json_decode($item->countries))) selected @endif>San Marino</option>
                                                        <option value="st" @if ($item->countries !== null && in_array('st', json_decode($item->countries))) selected @endif>Sao Tome and Principe</option>
                                                        <option value="sa" @if ($item->countries !== null && in_array('sa', json_decode($item->countries))) selected @endif>Saudi Arabia</option>
                                                        <option value="sn" @if ($item->countries !== null && in_array('sn', json_decode($item->countries))) selected @endif>Senegal</option>
                                                        <option value="rs" @if ($item->countries !== null && in_array('rs', json_decode($item->countries))) selected @endif>Serbia</option>
                                                        <option value="sc" @if ($item->countries !== null && in_array('sc', json_decode($item->countries))) selected @endif>Seychelles</option>
                                                        <option value="sl" @if ($item->countries !== null && in_array('sl', json_decode($item->countries))) selected @endif>Sierra Leone</option>
                                                        <option value="sg" @if ($item->countries !== null && in_array('sg', json_decode($item->countries))) selected @endif>Singapore</option>
                                                        <option value="sk" @if ($item->countries !== null && in_array('sk', json_decode($item->countries))) selected @endif>Slovakia</option>
                                                        <option value="si" @if ($item->countries !== null && in_array('si', json_decode($item->countries))) selected @endif>Slovenia</option>
                                                        <option value="sb" @if ($item->countries !== null && in_array('sb', json_decode($item->countries))) selected @endif>Solomon Islands</option>
                                                        <option value="so" @if ($item->countries !== null && in_array('so', json_decode($item->countries))) selected @endif>Somalia</option>
                                                        <option value="za" @if ($item->countries !== null && in_array('za', json_decode($item->countries))) selected @endif>South Africa</option>
                                                        <option value="kr" @if ($item->countries !== null && in_array('kr', json_decode($item->countries))) selected @endif>South Korea</option>
                                                        <option value="ss" @if ($item->countries !== null && in_array('ss', json_decode($item->countries))) selected @endif>South Sudan</option>
                                                        <option value="es" @if ($item->countries !== null && in_array('es', json_decode($item->countries))) selected @endif>Spain</option>
                                                        <option value="lk" @if ($item->countries !== null && in_array('lk', json_decode($item->countries))) selected @endif>Sri Lanka</option>
                                                        <option value="sd" @if ($item->countries !== null && in_array('sd', json_decode($item->countries))) selected @endif>Sudan</option>
                                                        <option value="sr" @if ($item->countries !== null && in_array('sr', json_decode($item->countries))) selected @endif>Suriname</option>
                                                        <option value="se" @if ($item->countries !== null && in_array('se', json_decode($item->countries))) selected @endif>Sweden</option>
                                                        <option value="ch" @if ($item->countries !== null && in_array('ch', json_decode($item->countries))) selected @endif>Switzerland</option>
                                                        <option value="sy" @if ($item->countries !== null && in_array('sy', json_decode($item->countries))) selected @endif>Syria</option>
                                                        <option value="tw" @if ($item->countries !== null && in_array('tw', json_decode($item->countries))) selected @endif>Taiwan</option>
                                                        <option value="tj" @if ($item->countries !== null && in_array('tj', json_decode($item->countries))) selected @endif>Tajikistan</option>
                                                        <option value="tz" @if ($item->countries !== null && in_array('tz', json_decode($item->countries))) selected @endif>Tanzania</option>
                                                        <option value="th" @if ($item->countries !== null && in_array('th', json_decode($item->countries))) selected @endif>Thailand</option>
                                                        <option value="tg" @if ($item->countries !== null && in_array('tg', json_decode($item->countries))) selected @endif>Togo</option>
                                                        <option value="to" @if ($item->countries !== null && in_array('to', json_decode($item->countries))) selected @endif>Tonga</option>
                                                        <option value="tt" @if ($item->countries !== null && in_array('tt', json_decode($item->countries))) selected @endif>Trinidad and Tobago</option>
                                                        <option value="tn" @if ($item->countries !== null && in_array('tn', json_decode($item->countries))) selected @endif>Tunisia</option>
                                                        <option value="tr" @if ($item->countries !== null && in_array('tr', json_decode($item->countries))) selected @endif>Turkey</option>
                                                        <option value="tm" @if ($item->countries !== null && in_array('tm', json_decode($item->countries))) selected @endif>Turkmenistan</option>
                                                        <option value="tv" @if ($item->countries !== null && in_array('tv', json_decode($item->countries))) selected @endif>Tuvalu</option>
                                                        <option value="ug" @if ($item->countries !== null && in_array('ug', json_decode($item->countries))) selected @endif>Uganda</option>
                                                        <option value="ua" @if ($item->countries !== null && in_array('ua', json_decode($item->countries))) selected @endif>Ukraine</option>
                                                        <option value="ae" @if ($item->countries !== null && in_array('ae', json_decode($item->countries))) selected @endif>United Arab Emirates</option>
                                                        <option value="gb" @if ($item->countries !== null && in_array('gb', json_decode($item->countries))) selected @endif>United Kingdom</option>
                                                        <option value="us" @if ($item->countries !== null && in_array('us', json_decode($item->countries))) selected @endif>United States</option>
                                                        <option value="uy" @if ($item->countries !== null && in_array('uy', json_decode($item->countries))) selected @endif>Uruguay</option>
                                                        <option value="uz" @if ($item->countries !== null && in_array('uz', json_decode($item->countries))) selected @endif>Uzbekistan</option>
                                                        <option value="vu" @if ($item->countries !== null && in_array('vu', json_decode($item->countries))) selected @endif>Vanuatu</option>
                                                        <option value="va" @if ($item->countries !== null && in_array('va', json_decode($item->countries))) selected @endif>Vatican City</option>
                                                        <option value="ve" @if ($item->countries !== null && in_array('ve', json_decode($item->countries))) selected @endif>Venezuela</option>
                                                        <option value="vn" @if ($item->countries !== null && in_array('vn', json_decode($item->countries))) selected @endif>Vietnam</option>
                                                        <option value="ye" @if ($item->countries !== null && in_array('ye', json_decode($item->countries))) selected @endif>Yemen</option>
                                                        <option value="zm" @if ($item->countries !== null && in_array('zm', json_decode($item->countries))) selected @endif>Zambia</option>
                                                        <option value="zw" @if ($item->countries !== null && in_array('zw', json_decode($item->countries))) selected @endif>Zimbabwe</option>
                                                    </select>
                                                </div>
                                                
                                                  
                                               
                                                   
                                                 
                            
                                                    <div class="text-end">
                                                       
                                                        <button type="button" class="btn btn-light waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div>
                                </div>
                                {{-- Delete Service --}}
                                <div class="modal fade" id="delete-modal{{$key}}" tabindex="-1"
                                aria-labelledby="delete-modal{{$key}}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel{{ $key }}">
                                               Delete Service</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-0">
                                            Are you sure you want to delete this Service?
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Close</button>
                                          
                                                <a  href="{{ route('markup.delete.service',['id'=>$item->id]) }}" class="btn btn-danger">Delete</a>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>

                                {{--  --}}
                            @endforeach
                            </tbody>
                           
                        </table>
                    </div>
                        <!-- end timeline content-->
                        

                        <div class="tab-pane show active" id="settings">
                            <div class="row">
                           
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                         
                                         
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
   
   document.getElementById('markup_type').addEventListener('change', function() {
            const markupType = this.value;
            const markupScope = document.getElementById('markup_scope');
            const options = markupScope.options;

            // Show both options if markup type is 'fixed'
            if (markupType === 'fixed') {
                for (let i = 0; i < options.length; i++) {
                    options[i].classList.remove('hidden');
                }
            } else if (markupType === 'percent') {
                // Show only 'per_order' option if markup type is 'percent'
                for (let i = 0; i < options.length; i++) {
                    if (options[i].value === 'per_item') {
                        options[i].classList.add('hidden');
                    } else {
                        options[i].classList.remove('hidden');
                    }
                }
                // Set to default value if 'per_item' is hidden and was previously selected
                if (markupScope.value === 'per_item') {
                    markupScope.value = 'per_order';
                }
            } else {
                // If no markup type is selected, show both options
                for (let i = 0; i < options.length; i++) {
                    options[i].classList.remove('hidden');
                }
                markupScope.value = '';
            }
        });

        // Initialize on page load
        document.getElementById('markup_type').dispatchEvent(new Event('change'));
    </script>


<script>
    $('#carriers').change(function() {
        var carrierId = $(this).val(); // Get the selected carrier ID

        // Make AJAX request to fetch services based on carrier ID
        $.ajax({
            url: '/carrier/select-service/' + carrierId,
            method: 'GET',
            success: function(response) {
                // Clear current options and add new options based on response
                var servicesSelect = $('#services');
                servicesSelect.empty(); // Clear existing options
                
                // Add default option
                servicesSelect.append('<option value="">Select Service...</option>');
                
                // Add options based on API response
                $.each(response, function(index, service) {
                    servicesSelect.append('<option value="' + service.id + '">' + service.service_name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                // Handle error if needed
                console.error(error);
            }
        });
    });

    // update carrier

    $('#update-carrier').change(function() {
        var carrierId = $(this).val(); // Get the selected carrier ID

        // Make AJAX request to fetch services based on carrier ID
        $.ajax({
            url: '/carrier/select-service/' + carrierId,
            method: 'GET',
            success: function(response) {
                // Clear current options and add new options based on response
                var servicesSelect = $('.update-services');
                servicesSelect.empty(); // Clear existing options
                
                // Add default option
                servicesSelect.append('<option value="">Select Service...</option>');
                
                // Add options based on API response
                $.each(response, function(index, service) {
                    servicesSelect.append('<option value="' + service.id + '">' + service.service_name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                // Handle error if needed
                console.error(error);
            }
        });
    });
</script>
@endpush

@endsection
@section('script')
    @vite(['resources/js/pages/form-advanced.init.js'])
@endsection