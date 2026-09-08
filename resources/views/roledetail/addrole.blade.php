@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Add Role</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody px-3">
            <form id="add_role" autocomplete="off">
                @csrf
                <div class="row mb-2">
                    <div class="col-md-4 col-sm-4">
                        <label for="">Role Name <span class="text-danger">*</span></label>
                        <input type="text" id="rolename" class="form-control form-control-sm" name="rolename"
                            placeholder="Role Name">
                        <span class="spanHide text-danger text-xs mt-2" id="rolename_error"></span>
                    </div>
                </div>
                <input type="hidden" name="roletype" value="U">
                <div class="row">
                    <div class="col-12">
                        <label for="">Give Permission To -</label>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="checkbox" id="inserted" name="inserted" value="1" class="mr-1">
                        <label for=""> Create</label>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="checkbox" id="viewed" name="viewed" value="1" class="mr-1">
                        <label for=""> View</label>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="checkbox" id="updated" name="updated" value="1" class="mr-1">
                        <label for=""> Update</label>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="checkbox" id="deleted" name="deleted" value="1" class="mr-1">
                        <label for=""> Delete</label>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="checkbox" id="freeze" name="freeze" value="1" class="mr-1">
                        <label for=""> Finalize</label>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="checkbox" id="uploadFile" name="uploadFile" value="1" class="mr-1">
                        <label for=""> Upload File</label>
                    </div>
                    <!-- Saiful Start -->
                    <div class="col-6 col-md-2">
                        <input type="checkbox" id="reqModify" name="reqModify" value="1" class="mr-1">
                        <label for=""> Request Modify</label>
                    </div>
                    <div class="col-12 col-md-4">
                        <input type="checkbox" id="approveModifyRequest" name="approveModifyRequest" value="1"
                            class="mr-1">
                        <label for="">Approve Modification Request</label>
                    </div>
                    <!-- Saiful End -->
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 col-sm-6">
                        <button type="submit" class="btn btn-primary px-3 btn-sm rounded-2 submitBtn">
                            <i class="fa fa-plus"></i>
                            Save Role
                        </button>
                    </div>
                </div><br>
            </form>
        </div>
        <div class="mt-2 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0 text-dark" style="background-color:rgb(214, 232, 253)">
                    <h3 class="card-title text-bold text-uppercase">
                        List of Roles
                    </h3>
                </div>
                <div class='card-body text-sm rounded-0'>
                    <table class="table
                        table-bordered table-striped role_list w-100 text-sm"
                        id="role_list">
                        <thead class="theader text-xs" style="background-color:#c7c5c5">
                            <tr>
                                <th class="text-center pb-2" rowspan="2">Sl No.</th>
                                <th class="pb-2" rowspan="2">Roles</th>
                                <th class="text-center" colspan="8">Permissions</th>
                                <th class="text-center pb-2" rowspan="2">Edit</th>
                            </tr>
                            <tr>
                                <th class="text-center">Create</th>
                                <th class="text-center">View</th>
                                <th class="text-center">Update</th>
                                <th class="text-center">Delete</th>
                                <th class="text-center">Finalize</th>
                                <th class="text-center" style="width :15px">Request <br> Modification</th>
                                <th class="text-center" style="width :20px">Approve <br> Modification</th>
                                <th class="text-center">Upload <br> File</th>
                            </tr>

                        </thead>
                        <tbody class="text-xs">
                            <?php $i = 1; ?>
                            @foreach ($roledetails as $key)
                                @if ($key->roletype == 'U')
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td class="">{{ $key->rolename }}</td>
                                        <td class="text-center">
                                            @if ($key->inserted == 1)
                                                <span class="text-success text-xs text-bold ml-3">&#x2714;</span>
                                            @else
                                                <span class="text-danger text-xs text-bold ml-3">&#9587;</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($key->viewed == 1)
                                                <span class="text-success text-xs text-bold ml-3">&#x2714;</span>
                                            @else
                                                <span class="text-danger text-xs text-bold ml-3">&#9587;</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($key->updated == 1)
                                                <span class="text-success text-xs text-bold ml-3">&#x2714;</span>
                                            @else
                                                <span class="text-danger text-xs text-bold ml-3">&#9587;</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($key->deleted == 1)
                                                <span class="text-success text-xs text-bold ml-3">&#x2714;</span>
                                            @else
                                                <span class="text-danger text-xs text-bold ml-3">&#9587;</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($key->freeze_data == 1)
                                                <span class="text-success text-xs text-bold ml-3">&#x2714;</span>
                                            @else
                                                <span class="text-danger text-xs text-bold ml-3">&#9587;</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($key->req_modify == 1)
                                                <span class="text-success text-xs text-bold ml-3">&#x2714;</span>
                                            @else
                                                <span class="text-danger text-xs text-bold ml-3">&#9587;</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($key->approve_req_modify == 1)
                                                <span class="text-success text-xs text-bold ml-3">&#x2714;</span>
                                            @else
                                                <span class="text-danger text-xs text-bold ml-3">&#9587;</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($key->upload_file == 1)
                                                <span class="text-success text-xs text-bold ml-3">&#x2714;</span>
                                            @else
                                                <span class="text-danger text-xs text-bold ml-3">&#9587;</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($updated == 1)
                                                <a data-toggle="modal" data-target="#editModal{{ $key->id }}"
                                                    data-backdrop="static"
                                                    class="btn text-warning btn-sm edit rounded-0"><i
                                                        class="fas fa-edit"></i></a>
                                            @else
                                                <span class="text-danger"><i class="fas fa-ban"></i></span>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endif

                                <!-- edit modal -->
                                <div class="modal fade" id="editModal{{ $key->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content text-sm">
                                            <form id="edit_form" action="{{ route('updateRole') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $key->id }}">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-md" id="exampleModalLabel">
                                                        <i class="fas fa-edit mr-2"></i>
                                                        Update Role Permission
                                                    </h5>
                                                    <a type="button" data-dismiss="modal"><i
                                                            class="fas fa-times"></i></a>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-1">
                                                            <label for="name" class="col-form-label">Role
                                                                Name : {{ $key->rolename }}</label>
                                                        </div>
                                                        <div class="col-md-12 mb-1">
                                                            <label for="name" class="col-form-label">Permissions
                                                                :</label>

                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <input type="checkbox" id="inserted" name="inserted"
                                                                class="mr-1" value="1"
                                                                style="width:16px;height:16px"
                                                                {{ $key->inserted == 1 ? 'checked' : '' }}>
                                                            <label for=""> Create</label>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <input type="checkbox" id="viewed" name="viewed"
                                                                class="mr-1" value="1"
                                                                style="width:16px;height:16px"
                                                                {{ $key->viewed == 1 ? 'checked' : '' }}>
                                                            <label for=""> View</label>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <input type="checkbox" id="updated" name="updated"
                                                                class="mr-1" value="1"
                                                                style="width:16px;height:16px"
                                                                {{ $key->updated == 1 ? 'checked' : '' }}>
                                                            <label for=""> Update</label>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <input type="checkbox" id="deleted" name="deleted"
                                                                class="mr-1" value="1"
                                                                style="width:16px;height:16px"
                                                                {{ $key->deleted == 1 ? 'checked' : '' }}>
                                                            <label for=""> Delete</label>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <input type="checkbox" id="freeze" name="freeze"
                                                                class="mr-1" value="1"
                                                                style="width:16px;height:16px"
                                                                {{ $key->freeze_data == 1 ? 'checked' : '' }}>
                                                            <label for=""> Finalize</label>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <input type="checkbox" id="uploadFile" name="uploadFile"
                                                                class="mr-1" value="1"
                                                                style="width:16px;height:16px"
                                                                {{ $key->upload_file == 1 ? 'checked' : '' }}>
                                                            <label for=""> Upload File</label>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <input type="checkbox" id="req_modify" name="req_modify"
                                                                class="mr-1" value="1"
                                                                style="width:16px;height:16px"
                                                                {{ $key->req_modify == 1 ? 'checked' : '' }}>
                                                            <label for=""> Request
                                                                Modification?</label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="checkbox" id="approve_req_modify"
                                                                name="approve_req_modify" class="mr-1" value="1"
                                                                style="width:16px;height:16px"
                                                                {{ $key->approve_req_modify == 1 ? 'checked' : '' }}>
                                                            <label for=""> Approve Request
                                                                Modification?</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-end">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm submitEditBtn rounded-2">
                                                        <i class="fa fa-save mr-1"></i>
                                                        Update Change
                                                    </button>
                                                    <button type="button" class="btn btn-secondary btn-sm rounded-2"
                                                        data-dismiss="modal">
                                                        <i class="fa fa-times mr-1"></i>
                                                        Close
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
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).on("submit", "#add_role", function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('AddRole') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $('#add_role').serialize(),
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.message == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: 'Data Saved Successfully',
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            window.location.replace(location);
                        });
                    } else if (response.message == 'validationFails') {
                        var errors = response.error;
                        $('.spanHide').empty();
                        $.each(errors, function(field, messages) {
                            var errorHtml = '';
                            $.each(messages, function(index, message) {
                                errorHtml += message + '<br>';
                            });
                            $('#' + field + '_error').html(errorHtml);
                        });
                    } else if (response.message == 'duplicate') {
                        var errors = response.error;
                        $('.spanHide').empty();
                        errorHtml =
                            '<i class="fa fa-exclamation-triangle mr-1"></i>This Role Name Already Exist';
                        $('#rolename_error').html(errorHtml);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong',
                            showConfirmButton: true,
                            timer: 3000
                        });
                    }
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $("#role_list").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                // "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#role_list_wrapper .col-md-6:eq(1)');

        });
    </script>
@endpush
