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
                        <li class="breadcrumb-item">Add Designation</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="mainBody px-3 py-2">
            <form id="add_desg">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-4 col-sm-12">
                        <label for="">Designation Name <span class="text-danger">*</span></label>
                        <input type="text" id="desg_name" class="form-control form-control-sm" name="desg_name"
                            placeholder="Enter Designation Name" required>
                        <span class="spanHide text-danger" id="desg_name_error"></span>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 px-3 fw-bold">
                            <i class="fa fa-plus"></i> Add Designation
                        </button>
                    </div>
                </div><br>
            </form>
        </div>

        <div class="mt-2 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0 text-dark" style="background-color:rgb(214, 232, 253)">
                    <h3 class="card-title text-bold text-uppercase">
                        List of Designations
                    </h3>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped user_list w-100 text-sm" id="desgDetail">
                        <thead class="theader" style="background-color:#e7effc">
                            <th class="text-center">Serial No.</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Edit Designation</th>
                            {{-- <th class="text-center">Delete</th> --}}
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($desg_details as $key)
                                <tr class="text-xs">
                                    <td class="text-center">{{ $i }}</td>
                                    <td class="text-start">{{ $key->desg_name }}</td>
                                    <td class="text-center">
                                        @if (session('updated') == 1)
                                            <a data-toggle="modal" data-target="#editModal{{ $key->id }}"
                                                data-backdrop="static" class="btn text-warning btn-sm edit rounded-0"><i
                                                    class="fas fa-edit text-xs"></i></a>
                                        @else
                                            <span class="text-danger"><i class="fas fa-ban text-xs"></i></span>
                                        @endif
                                    </td>
                                    {{-- <td class="text-center">
                                            @if (session('deleted') == 1)
                                                <a class="btn btn-sm delete rounded-0 ms-2"
                                                    href="{{ route('deleteDesignation', ['id' => $key->id]) }}" style="color:red"><i
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
                                            <form class="update-desg-form" id="update_desg_{{ $key->id }}"
                                                method="POST" action="{{ route('updateDesignation') }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $key->id }}">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-md" id="exampleModalLabel">
                                                        <i class="fas fa-edit mr-2"></i>
                                                        Update Designation Details
                                                    </h5>
                                                    <a type="button" data-dismiss="modal"><i class="fas fa-times"></i></a>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row text-sm">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="name" class="col-form-label">Designation
                                                                Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control form-control-sm"
                                                                style="width: 100%;" id="modal_desg_name" name="desg_name"
                                                                value="{{ $key->desg_name }}" required>
                                                            <span class="verify_edit_desg text-danger"
                                                                style="display:none">This field is
                                                                required</span>
                                                        </div>

                                                        <br>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-end">
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm submitEditBtn rounded-2">
                                                        <i class="fa fa-save mr-1"></i> Save
                                                        Change</button>
                                                    <button type="button"
                                                        class="btn btn-secondary btn-sm modalClose rounded-2"
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
    <style>
        #desgDetail td,
        #desgDetail th {
            padding: 0.25rem;
        }
    </style>
    <script type="text/javascript">
        $(document).on("submit", "#add_desg", function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('manageDesignation') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $('#add_desg').serialize(),
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
                        errorHtml = 'This Designation Name Already Exist';
                        $('#desg_name_error').html(errorHtml);
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
            $("#desgDetail").DataTable({}).buttons().container().appendTo('#desgDetail_wrapper .col-md-6:eq(1)');
        });

        $('.modalClose').click(function() {
            location.reload();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('form.update-desg-form').on("submit", function(e) {
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
                        if (response.message == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: 'Data Updated Successfully',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                window.location.replace(location)
                            });
                        } else if (response.message == 'validationFails') {
                            console.log('validation fails');
                        } else if (response.message == 'duplicate') {
                            console.log('duplicate');
                            alert('This Designation already exist');
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
        });
    </script>
@endsection
