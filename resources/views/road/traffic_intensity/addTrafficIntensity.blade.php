<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: "SF Pro Text", "Myriad Set Pro", "SF Pro Icons", "Apple Legacy Chevron", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
        }

        .mainBody {
            background-color: #FEFBFA;
            box-shadow: 2px 2px 2px 2px grey;
        }

        .row {
            padding-left: 2%;
            padding-right: 2%;
            padding-top: 1%;
        }

        label {
            font-size: 14px;
        }

        .modal-header {
            background-image: linear-gradient(to right, #484646, #C2C0C0);
            color: white;
        }

        .star {
            color: red;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('layouts/header')
        @include('sweet::alert')
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-1">
                        <a href="{{ route('dashboard') }}" class="mr-2">Dashboard</a>/ Add Department
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid mainBody">
                    <form id="add_dept">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label for="">Department Name <span class="star">*</span></label>
                                <input type="text" id="department_name" class="form-control" name="department_name"
                                    required>
                                <span class="spanHide text-danger" id="department_name_error"></span>
                            </div>
                            <div class="col-md-6 col-sm-6 mt-4">
                                <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2"
                                    style="width:20%"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div><br>
                    </form>
                </div>

                <div class="container-fluid mt-3">
                    <div class='card'>
                        <div class="card-header text-dark" style="background-color:#C8C8C8">
                            <h3 class="card-title" style="font-weight: bold; text-transform:uppercase;">list of
                                departments</h3>
                        </div>
                        <div class='card-body'>
                            <table class="table table-bordered table-striped user_list w-100" id="deptDetail">
                                <thead class="theader" style="background-color:#C8C8C8">
                                    <th class="text-center" style="width:10%">Sl No.</th>
                                    <th class="text-center" style="width:60%">Department Name</th>
                                    <th class="text-center" style="width:15%">Edit</th>
                                    <th class="text-center" style="width:15%">Delete</th>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($d_details as $key)
                                        <tr>
                                            <td class="text-center">{{ $i }}</td>
                                            <td class="text-center">{{ $key->department_name }}</td>
                                            <td class="text-center">
                                                @if ($updated == 1)
                                                    <a data-toggle="modal" data-target="#editModal{{ $key->id }}"
                                                        data-backdrop="static"
                                                        class="btn btn-sm edit rounded-0 text-warning"><i
                                                            class="fas fa-edit"></i></a>
                                                @else
                                                    <span class="text-danger"><i class="fas fa-ban"></i></span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($deleted == 1)
                                                    <a style="color:red"
                                                        class="btn btn-sm delete rounded-0 ms-2 text-danger"
                                                        href="{{ route('deleteDepartment', ['id' => $key->id]) }}"><i
                                                            class="fas fa-trash-alt"></i></a>
                                                @else
                                                    <span style="color:red"><i class="fas fa-ban"></i></span>
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $i++; ?>

                                        <!-- edit modal -->
                                        <div class="modal fade" id="editModal{{ $key->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <form class="update-dept-form" id="update_dept_{{ $key->id }}"
                                                        method="POST" action="{{ route('updateDepartment') }}">
                                                        {{-- <form id="update_dept"> --}}
                                                        @csrf
                                                        <input type="hidden" name="id"
                                                            value="{{ $key->id }}">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title" id="exampleModalLabel">Update
                                                                Department Details</h6>
                                                            <a type="button" data-dismiss="modal"><i
                                                                    class="fas fa-times"></i></a>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="name"
                                                                        class="col-form-label">Department Name <span
                                                                            class="star">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        id="modal_department_name"
                                                                        name="department_name"
                                                                        value="{{ $key->department_name }}" required>
                                                                    <span class="text-danger"
                                                                        id="department_name_editerror"></span>
                                                                </div>

                                                                <br>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="submit"
                                                                class="btn btn-primary btn-sm submitEditBtn"
                                                                style="border-radius:5px"><i
                                                                    class="fa-solid fa-floppy-disk"></i> Save
                                                                Change</button>
                                                            <button type="button"
                                                                class="btn btn-secondary btn-sm modalClose"
                                                                style="border-radius:5px" data-dismiss="modal"><i
                                                                    class="fa-solid fa-close"></i> Close</button>
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

        </div>

        @include('layouts/footer')
</body>


<script type="text/javascript">
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
                if (response.message == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: 'Data Saved Successfully',
                        showConfirmButton: true,
                        timer: 3000
                    });
                    window.location.replace(location)
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
                    errorHtml = 'This Department Name Already Exist';
                    $('#department_name_error').html(errorHtml);
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
        $("#deptDetail").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf"]
        }).buttons().container().appendTo('#deptDetail_wrapper .col-md-6:eq(1)');

    });

    $('.modalClose').click(function() {
        location.reload();
    });
</script>

<script>
    $(document).ready(function() {
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
                    if (response.message == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: 'Data Updated Successfully',
                            showConfirmButton: true,
                            timer: 3000
                        });
                        //window.location.replace(location)
                        location.reload();
                    } else if (response.message == 'validationFails') {
                        console.log('validation fails');
                    } else if (response.message == 'duplicate') {
                        console.log('duplicate');
                        alert('This Department Name already exist');
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

</html>
