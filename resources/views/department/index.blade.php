@extends('layouts.app')
@section('content')
    {{-- <div class="content-wrapper"> --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Manage Department</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_dept">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-4 col-sm-12">
                        <label for="">Department Name <span class="star">*</span></label>
                        <input type="text" id="department_name" class="form-control form-control-sm"
                            name="department_name" placeholder="Enter Department Name" required>
                        <span class="spanHide text-danger" id="department_name_error"></span>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 py-1 px-3 mt-2 fw-bold">
                            <i class="fa fa-plus"></i> Add Department
                        </button>
                    </div>
                </div><br>
            </form>
        </div>

        <div class="mt-2 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0 text-dark" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">
                        List of Departments
                    </h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped user_list w-100" id="deptDetail">
                        <thead class="theader" style="background-color:#e7effc;">
                            <th class="text-center">Serial Number</th>
                            <th class="text-center">Department Name</th>
                            <th class="text-center">Edit Department</th>
                            {{-- <th class="text-center">Delete</th> --}}
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($d_details as $key)
                                <tr class="text-xs">
                                    <td class="text-center">{{ $i }}</td>
                                    <td class="text-start">{{ $key->department_name }}</td>
                                    <td class="text-center">
                                        @if (session('updated') == 1)
                                            <a data-toggle="modal" data-target="#editModal{{ $key->id }}"
                                                data-backdrop="static" class="btn btn-sm edit rounded-0 text-warning"><i
                                                    class="fas fa-edit text-xs"></i></a>
                                        @else
                                            <span class="text-danger"><i class="fas fa-ban text-xs"></i></span>
                                        @endif
                                    </td>
                                    {{-- <td class="text-center">
                                            @if (session('deleted') == 1)
                                                <a style="color:red" class="btn btn-sm delete rounded-0 ms-2 text-danger"
                                                    href="{{ route('deleteDepartment', ['id' => $key->id]) }}"><i
                                                        class="fas fa-trash-alt text-xs"></i></a>
                                            @else
                                                <span style="color:red"><i class="fas fa-ban text-xs"></i></span>
                                            @endif
                                        </td> --}}
                                </tr>
                                <?php $i++; ?>

                                <!-- edit modal -->
                                <div class="modal fade" id="editModal{{ $key->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <form class="update-dept-form" id="update_dept_{{ $key->id }}"
                                                method="POST" action="{{ route('updateDepartment') }}">
                                                {{-- <form id="update_dept"> --}}
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $key->id }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="exampleModalLabel">
                                                        <i class="fas fa-edit mr-2 text-xs"></i>Update
                                                        Department Details
                                                    </h6>
                                                    <a type="button" data-dismiss="modal"><i class="fas fa-times"></i></a>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="name" class="col-form-label">Department Name
                                                                <span class="star">*</span></label>
                                                            <input type="text" class="form-control form-control-sm"
                                                                id="modal_department_name" name="department_name"
                                                                value="{{ $key->department_name }}" required>
                                                            <span class="text-danger" id="department_name_editerror"></span>
                                                        </div>
                                                        <br>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-right">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm submitEditBtn rounded-1 px-2"><i
                                                            class="fa fa-save mr-2 text-xs"></i>Save
                                                        Change</button>
                                                    <button type="button"
                                                        class="btn btn-secondary btn-sm modalClose rounded-1 px-2"
                                                        data-dismiss="modal">
                                                        <i class="fa fa-times mr-2 text-xs" aria-hidden="true"></i>Close
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- end modal -->
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    {{-- </div> --}}
@endsection
@push('styles')
    <style>
        #deptDetail td,
        #deptDetail th {
            padding: 0.25rem;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $("#deptDetail").DataTable();
        });

        $('.modalClose').click(function() {
            location.reload();
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on("submit", "#add_dept", function(e) {
                e.preventDefault();
                let location = "{{ route('GetAddDepartment') }}";
                $.ajax({
                    type: "POST",
                    url: "{{ route('AddDepartment') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#add_dept').serialize(),
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                window.location.replace(location)
                            });
                        } else if (response.status == 'failed') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                window.location.replace(location)
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong!',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                window.location.replace(location)
                            });
                        }
                    }
                });
            });

            $('form.update-dept-form').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                window.location.replace(location)
                            });
                        } else if (response.status == 'failed') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                window.location.replace(location)
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong!',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                window.location.replace(location)
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
