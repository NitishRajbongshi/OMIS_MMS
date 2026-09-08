@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Login Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="row">
                    <div class="col-md-1">
                        <label for="" style="font-size:16px">User-wise :</label>
                    </div>
                    <div class="col-md-4">
                        <select id="user_name" class="form-control user_name" name="user_name">
                            <option value="" disable selected hidden>Select user</option>
                            <option value="all">All users</option>
                            @foreach ($userdetails as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <table id="user_list" class="table table-bordered table-striped user_list">
                        <thead class="theader" style="background-color:#C8C8C8">
                            <tr>
                                <th class="text-center" style="width:8%">Sl. No</th>
                                <th class="text-center" style="width:14%">User Name</th>
                                <th class="text-center" style="width:20%">Email</th>
                                <th class="text-center" style="width:18%">IP Address</th>
                                <th class="text-center" style="width:20%">Action</th>
                                <th class="text-center" style="width:20%">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($logdata as $lg)
                                <tr>
                                    <th class="text-center">{{ $i }}</th>
                                    <th class="text-center">{{ $lg->user_info['username'] }}</th>
                                    <th class="text-center">{{ $lg->user_info['useremail'] }}</th>
                                    <th class="text-center">{{ $lg->ipAddress }}</th>
                                    <th class="text-center">{{ $lg->activityType }}</th>
                                    <th class="text-center">{{ $lg->loginTime }}</th>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $("#user_list").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#user_list_wrapper .col-md-6:eq(1)');
        });
        //reset
        $('#reset').click(function() {
            $('#add_user')[0].reset();
        });
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(1)');

        });
    </script>

    <script>
        $(document).ready(function() {
            $('form.update-user-form').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageUser') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    // url: "{{ route('updateUser') }}",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // data: $('#update_user').serialize(),
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
                            window.location.replace(location)
                        } else if (response.message === 'validationFails') {
                            console.log('validation fails');
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

    <script>
        $('.modal_phoneno').on("blur", function(e) {
            e.preventDefault();
            var ph = $(this).val();
            if (ph.length < 10 || ph.length > 10) {
                console.log('not correct');
                $('.phoneno_validation_error').show();
            } else {
                console.log('correct');
                $('.phoneno_validation_error').hide();
            }
        });

        $('.modal_pin').on("blur", function(e) {
            e.preventDefault();
            var pin = $(this).val();
            if (pin.length < 6 || pin.length > 6) {
                console.log('not correct');
                $('.pin_validation_error').show();
            } else {
                console.log('correct');
                $('.pin_validation_error').hide();
            }
        });

        $('.modalClose').click(function() {
            location.reload();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('form.active-user-form').on("submit", function(e) {
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
                                text: 'Status Updated Successfully',
                                showConfirmButton: true,
                                timer: 3000
                            });
                            location.reload();
                        } else if (response.message == 'validationFails') {
                            console.log('validation fails');
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

    <script>
        $(document).ready(function() {
            $('#user_name').on('change', function() {
                var selectedValue = $(this).val();
                $.ajax({
                    url: '{{ route('getUserOnchange') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'GET',
                    data: {
                        selectedValue: selectedValue
                    },
                    success: function(response) {
                        updateTable(response);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            function updateTable(updatedTableData) {
                var tableBody = $('#user_list tbody');
                tableBody.empty();
                // Iterate over the updated data and create table rows

                var sl = 1;
                for (var i = 0; i < updatedTableData.length; i++) {
                    var row = '<tr>' +
                        '<td class="text-center">' + sl + '</td>' +
                        '<td class="text-center">' + updatedTableData[i].user_info['username'] + '</td>' +
                        '<td class="text-center">' + updatedTableData[i].user_info['useremail'] + '</td>' +
                        '<td class="text-center">' + updatedTableData[i].ipAddress + '</td>' +
                        '<td class="text-center">' + updatedTableData[i].activityType + '</td>' +
                        '<td class="text-center">' + updatedTableData[i].loginTime + '</td>' +
                        '</tr>';

                    tableBody.append(row);
                    sl++;
                }
            }
        });
    </script>
@endpush
