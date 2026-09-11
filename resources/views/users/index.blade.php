@extends('layouts.vertical', ['page_title' => 'Profile'])

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">All Users</a></li>

                            <li class="breadcrumb-item active">All</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Users</h4>
                    <button type="button" class="btn btn-success waves-effect waves-light mb-2 float-end"
                        data-bs-toggle="modal" data-bs-target="#custom-modal"><i class="mdi mdi-plus-circle me-1"></i> Add
                        New User</button>
                </div>
                <div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light">
                                <h4 class="modal-title" id="myCenterModalLabel">Add New User</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <div class="modal-body p-4">
                                <form method="POST" action="{{ route('users.store') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="fullname" class="form-label">Full Name</label>
                                        <input class="form-control" type="text" name="name" id="fullname"
                                            placeholder="Enter your name" required value="">
                                    </div>
                                    <div class="mb-3">
                                        <label for="emailaddress" class="form-label">Email address</label>
                                        <input class="form-control" type="email" name="email" id="emailaddress" required
                                            placeholder="Enter your email" value="" autocomplete="off">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Enter your password" value="">
                                            <div class="input-group-text" id="togglePassword">
                                                <i class="fas fa-eye"></i>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Password confirmation</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password_confirmation" name="password_confirmation"
                                                class="form-control" placeholder="Enter your password confirmation"
                                                value="">
                                            <div class="input-group-text" id="togglePasswordConfirmation">
                                                <i class="fas fa-eye"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="button" class="btn btn-light border waves-effect waves-light"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit"
                                            class="btn btn-success waves-effect waves-light">Submit</button>
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

                        <div class="tab-content p-0 m-0">
                            <div class="tab-pane show active" id="settings">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">


                                                <div class="table-responsive">
                                                    <table class="table mb-0" style="font-size: 12px;">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Created At</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($data as $key => $item)
                                                                <tr>
                                                                    <td>#{{ $key }}</td>
                                                                    <td>{{ $item->name }}</td>
                                                                    <td>{{ $item->email }}</td>
                                                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                                                    <td> <i data-feather="edit-2"
                                                                            class="icon-dual text-warning"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#update-modal{{ $key }}"
                                                                            style="width: 15px;"></i> <i
                                                                            data-feather="trash-2"
                                                                            class="icon-dual text-danger ms-1"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#delete-modal{{ $key }}"
                                                                            style="width: 15px;"></i>
                                                                        {{-- <a href="{{ route('markup.show',['slug'=>$item->slug]) }}"> <i style="width: 15px;" data-feather="eye" class="icon-dual ms-1 text-info"></i></a> --}}
                                                                    </td>

                                                                </tr>
                                                                <div class="modal fade"
                                                                    id="delete-modal{{ $key }}" tabindex="-1"
                                                                    aria-labelledby="delete-modal{{ $key }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header border-0">
                                                                                <h1 class="modal-title fs-5"
                                                                                    id="exampleModalLabel{{ $key }}">
                                                                                    {{ $item->name }}</h1>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body py-0">
                                                                                Are you sure you want to delete this User?
                                                                            </div>
                                                                            <div class="modal-footer border-0">
                                                                                <button type="button"
                                                                                    class="btn btn-light"
                                                                                    data-bs-dismiss="modal">Close</button>

                                                                                <a href="{{ route('users.destroy', ['id' => $item->id]) }}"
                                                                                    class="btn btn-danger">Delete</a>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{-- Edit --}}
                                                                <div class="modal fade"
                                                                    id="update-modal{{ $key }}" tabindex="-1"
                                                                    role="dialog" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-light">
                                                                                <h4 class="modal-title"
                                                                                    id="myCenterModalLabel">Update User
                                                                                </h4>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-hidden="true"></button>
                                                                            </div>
                                                                            <div class="modal-body p-4">
                                                                                <form method="POST"
                                                                                    action="{{ route('update.user', ['id' => $item->id]) }}">
                                                                                    @csrf

                                                                                    <div class="mb-3">
                                                                                        <label for="fullname"
                                                                                            class="form-label">Full
                                                                                            Name</label>
                                                                                        <input class="form-control"
                                                                                            type="text" name="name"
                                                                                            id="fullname"
                                                                                            value="{{ $item->name }}"
                                                                                            placeholder="Enter your name"
                                                                                            required value="">
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="emailaddress"
                                                                                            class="form-label">Email
                                                                                            address</label>
                                                                                        <input class="form-control"
                                                                                            type="email"
                                                                                            name="email_address"
                                                                                            id="emailaddress"
                                                                                            value="{{ $item->email }}"
                                                                                            required
                                                                                            placeholder="Enter your email"
                                                                                            value=""
                                                                                            autocomplete="off">
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="password"
                                                                                            class="form-label">Password</label>
                                                                                        <div
                                                                                            class="input-group input-group-merge">
                                                                                            <input type="password"
                                                                                                name="password"
                                                                                                id="password"
                                                                                                class="form-control"
                                                                                                placeholder="Enter your password"
                                                                                                value="">
                                                                                            <div class="input-group-text"
                                                                                                id="togglePassword">
                                                                                                <i class="fas fa-eye"></i>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>


                                                                                    <div class="mb-3">
                                                                                        <label for="password_confirmation"
                                                                                            class="form-label">Password
                                                                                            confirmation</label>
                                                                                        <div
                                                                                            class="input-group input-group-merge">
                                                                                            <input type="password"
                                                                                                id="password_confirmation"
                                                                                                name="password_confirmation"
                                                                                                class="form-control"
                                                                                                placeholder="Enter your password confirmation"
                                                                                                value="">
                                                                                            <div class="input-group-text"
                                                                                                id="togglePasswordConfirmation">
                                                                                                <i class="fas fa-eye"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="text-end">
                                                                                        <button type="button"
                                                                                            class="btn btn-light border waves-effect waves-light"
                                                                                            data-bs-dismiss="modal">Cancel</button>
                                                                                        <button type="submit"
                                                                                            class="btn btn-primary waves-effect waves-light">Update</button>
                                                                                    </div>

                                                                                </form>
                                                                            </div>
                                                                        </div><!-- /.modal-content -->
                                                                    </div><!-- /.modal-dialog -->
                                                                </div><!-- /.modal -->
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
