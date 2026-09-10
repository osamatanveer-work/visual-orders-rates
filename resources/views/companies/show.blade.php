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
                            <li class="breadcrumb-item"><a href="{{ route('company.all') }}">Companies</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $company->name }}</a></li>

                        </ol>
                    </div>
                    <h4 class="page-title">{{ $company->name }}</h4>
                    <button type="button" class="btn btn-success waves-effect waves-light mb-2 float-end" data-bs-toggle="modal" data-bs-target="#custom-modal"><i class="mdi mdi-plus-circle me-1"></i> Add New Store</button>
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
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="position" class="form-label">Store Url</label>
                                        <input type="url" class="form-control" name="url" id="position" placeholder="https://your-shop.myshopify.com">
                                        <small class="form-text text-muted">
                                            Must be the permanent <code>.myshopify.com</code> address
                                            (Shopify &rarr; Settings &rarr; Domains), not the custom storefront
                                            domain &mdash; the Admin API is not served on custom domains.
                                        </small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="client_id" class="form-label">Shopify Client ID</label>
                                        <input type="text" class="form-control" name="client_id" id="client_id"
                                               placeholder="Dev Dashboard &rarr; your app &rarr; Settings">
                                    </div>
                                    <div class="mb-3">
                                        <label for="client_secret" class="form-label">Shopify Client Secret</label>
                                        <input type="password" class="form-control" name="client_secret" id="client_secret"
                                               autocomplete="new-password"
                                               placeholder="Dev Dashboard &rarr; your app &rarr; Settings">
                                    </div>

                                    {{--
                                        Legacy permanent token. Shopify retired the ability to CREATE these
                                        on 1 Jan 2026 but did not revoke the ones already issued, so a store
                                        being migrated onto this instance can still be connected with one.

                                        Field name is deliberately shopify_access_token, NOT access_token:
                                        the "Add Carrier" modal further down this page posts its own
                                        access_token to the same store.update route, and the two would
                                        otherwise overwrite each other.
                                    --}}
                                    <div class="mb-3">
                                        <label for="shopify_access_token" class="form-label">
                                            Shopify API Access Token
                                            <small class="text-muted">&mdash; legacy apps only</small>
                                        </label>
                                        <input type="password" class="form-control" name="shopify_access_token" id="shopify_access_token"
                                               autocomplete="new-password"
                                               placeholder="Only for stores migrating from a pre-2026 custom app">
                                        <small class="form-text text-muted">
                                            Fill in <strong>either</strong> the Client ID and Secret above,
                                            <strong>or</strong> a legacy access token &mdash; not both.
                                        </small>
                                    </div>

                                    <div class="text-end">

                                        <button type="button" class="btn btn-light waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
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

            <div class="col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-body pt-0">

                        <div class="tab-content">

                            <!-- end timeline content-->

                            <div class="tab-pane show active" id="settings">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body pt-0 px-0">
                                                <label for="">Markup Template</label>
                                                <form action="{{route('markup.status.update',['id'=>$company->id])}}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    <div class="d-flex flex-row gap-2">



                                                        <select  name="markup_id" id="" class="form-select w-25 mb-3 mt-2">
                                                            @foreach ($markup_templates as $template)
                                                                <option @if($company->markup_id === $template->id) selected @endif value="{{ $template->id }}">{{ $template->name }}</option>
                                                            @endforeach

                                                        </select>

                                                        <div>
                                                            <button class="btn btn-primary mt-2" type="button" data-bs-toggle="modal" data-bs-target="#confirm-modal">Update</button>
                                                        </div>
                                                        <div class="modal fade" id="confirm-modal" tabindex="-1"
                                                             aria-labelledby="confirm-modal" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header border-0">
                                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                            Markup Template Update</h1>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body py-0">
                                                                        Do you want to update this markup template?
                                                                    </div>
                                                                    <div class="modal-footer border-0">
                                                                        <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Close</button>

                                                                        <button  type="submit"  class="btn btn-primary">Update</button>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </div>
                                                </form>
                                                <div class="table-responsive">
                                                    <table class="table mb-0" style="font-size: 12px;">
                                                        <thead class="table-light">
                                                        <tr>

                                                            <th>Platform</th>
                                                            <th>Store Name</th>
                                                            <th>Store Url</th>
                                                            <th>Connection</th>


                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($company->stores as $key=>$item)
                                                            <tr  id="tooltip-container">

                                                                <td>Shopify</td>
                                                                <td>{{ $item->name }}</td>
                                                                <td>{{ $item->store_url }}</td>
                                                                <td>
                                                                    @if ($item->auth_mode === \App\Models\Store::AUTH_CLIENT_CREDENTIALS)
                                                                        <span class="badge bg-success">Dev Dashboard app</span>
                                                                    @elseif ($item->auth_mode === \App\Models\Store::AUTH_LEGACY_TOKEN)
                                                                        <span class="badge bg-warning text-dark"
                                                                              title="Still working, but it cannot be reissued if revoked. Migrate when convenient.">Legacy token</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Not connected</span>
                                                                    @endif

                                                                    @if (empty($item->shopify_id))
                                                                        <span class="badge bg-secondary" title="No carrier service is registered in Shopify for this store.">Not registered</span>
                                                                    @endif
                                                                </td>

                                                                <td>
                                                                    <div class="d-flex justify-content-center flex-row">

                                                                        <span data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Store"><i data-feather="edit-2"  class="icon-dual text-warning mx-2" data-bs-toggle="modal" data-bs-target="#update-modal{{$key}}"  style="width: 15px;"></i></span>
                                                                        <span data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Store"><i data-feather="trash-2" class="icon-dual text-danger" data-bs-toggle="modal" data-bs-target="#delete-modal{{$key}}" style="width: 15px;" ></i></span></td>
                                                </div>
                                                </tr>
                                                {{-- Delete --}}
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
                                                                Are you sure you want to delete this store?
                                                                @if (!empty($item->shopify_id))
                                                                    <div class="mt-2 text-muted">
                                                                        This also removes the "Rate Shopper Carriers" service from
                                                                        the merchant's Shopify. If Shopify cannot be reached, the
                                                                        store is still deleted here and the service is left behind
                                                                        &mdash; remove it under Settings &rarr; Shipping and delivery.
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer border-0">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>

                                                                <a  href="{{ route('store.destroy',['id'=>$item->id]) }}" class="btn btn-danger">Delete</a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Edit --}}
                                                <div class="modal fade" id="update-modal{{ $key }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-light">
                                                                <h4 class="modal-title" id="updateModalLabel{{ $key }}">Update Store</h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <form action="{{ route('store.update',['id'=>$item->id]) }}" method="POST">
                                                                    @csrf

                                                                    <div class="mb-3">
                                                                        <label class="form-label">Shopify connection</label>
                                                                        <div>
                                                                            @if ($item->auth_mode === \App\Models\Store::AUTH_CLIENT_CREDENTIALS)
                                                                                <span class="badge bg-success">Dev Dashboard app</span>
                                                                            @elseif ($item->auth_mode === \App\Models\Store::AUTH_LEGACY_TOKEN)
                                                                                <span class="badge bg-warning text-dark">Legacy access token</span>
                                                                                <small class="text-muted d-block mt-1">
                                                                                    Still working, but it can never be reissued if revoked.
                                                                                    Add a Client ID and Secret below when convenient &mdash;
                                                                                    they take over automatically and the token stays as a fallback.
                                                                                </small>
                                                                            @else
                                                                                <span class="badge bg-danger">Not connected</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="name{{ $key }}" class="form-label">Name</label>
                                                                        <input type="text" class="form-control" value="{{ $item->name }}" name="name" id="name{{ $key }}" placeholder="Enter name">
                                                                        @if (!empty($item->shopify_id))
                                                                            <small class="form-text text-muted">
                                                                                Renaming changes the display name only. The internal address
                                                                                stays fixed so the live Shopify callback keeps working.
                                                                            </small>
                                                                        @endif
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="url{{ $key }}" class="form-label">Store Url</label>
                                                                        <input type="url" class="form-control"  value="{{ $item->store_url }}"  name="url" id="url{{ $key }}" placeholder="https://your-shop.myshopify.com">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="client_id{{ $key }}" class="form-label">Shopify Client ID</label>
                                                                        <input type="text" class="form-control" value="{{ $item->shopify_client_id }}"
                                                                               name="client_id" id="client_id{{ $key }}"
                                                                               placeholder="Dev Dashboard &rarr; your app &rarr; Settings">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="client_secret{{ $key }}" class="form-label">Shopify Client Secret</label>
                                                                        <input type="password" class="form-control"
                                                                               name="client_secret" id="client_secret{{ $key }}"
                                                                               autocomplete="new-password"
                                                                               placeholder="{{ $item->shopify_client_secret ? 'Stored — leave blank to keep' : 'Dev Dashboard → your app → Settings' }}">
                                                                    </div>

                                                                    {{-- Write-only. The previous version rendered the live token into
                                                                         page source on every request via value="{{ '{{' }} $item->access_token }}". --}}
                                                                    <div class="mb-3">
                                                                        <label for="shopify_access_token{{ $key }}" class="form-label">
                                                                            Shopify API Access Token
                                                                            <small class="text-muted">&mdash; legacy apps only</small>
                                                                        </label>
                                                                        <input type="password" class="form-control"
                                                                               name="shopify_access_token" id="shopify_access_token{{ $key }}"
                                                                               autocomplete="new-password"
                                                                               placeholder="{{ $item->access_token ? 'Stored — leave blank to keep' : 'Legacy stores only' }}">
                                                                    </div>

                                                                    <div class="text-end">

                                                                        <button type="button" class="btn btn-light waves-effect waves-light" data-bs-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div>
                                                {{--  --}}
                                                {{--
                                                    Add Carrier

                                                    NOTE: this modal is currently unreachable - nothing on the page
                                                    targets #view-modal{{ '{{' }}$key}}, so it never opens. It also posts
                                                    carrier_id to store.update, which does not handle that field, so
                                                    submitting it would only save the store's own attributes.

                                                    Left in place in case it is being wired up. Two things were fixed:
                                                      - the inner loop used $item, shadowing the store being edited for
                                                        the remainder of the outer iteration; it now uses $carrier
                                                      - its access_token field no longer collides with the Shopify
                                                        credential, which is posted as shopify_access_token above
                                                --}}
                                                {{-- View --}}
                                                <div class="modal fade" id="view-modal{{$key}}" tabindex="-1"
                                                     aria-labelledby="view-modal{{$key}}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-light">
                                                                <h4 class="modal-title" id="addCarrierLabel{{ $key }}">Add Carrier</h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <form action="{{ route('store.update',['id'=>$item->id]) }}" method="POST">
                                                                    @csrf
                                                                    <div class="mb-2">
                                                                        <label for="carrier_id{{ $key }}" class="form-label">Select Carrier</label>
                                                                        <select  name="carrier_id" id="carrier_id{{ $key }}" class="form-select ">
                                                                            <option value="">Choose</option>
                                                                            @foreach ($all_carriers as $carrier)
                                                                                <option value="{{ $carrier->id }}">{{ $carrier->name }}</option>
                                                                            @endforeach

                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="carrier_access_token{{ $key }}" class="form-label">Access Token</label>
                                                                        <input type="text" class="form-control" name="access_token" id="carrier_access_token{{ $key }}" placeholder="Enter Access Token">
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
@section('scripts')