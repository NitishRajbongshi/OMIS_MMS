@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center py-2">
                <div class="text-sm">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Manage National Highway</li>
                    </ol>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    @if ($inserted == 1)
                        @if ($user->office_type_cd == 'SDO')
                            <div class="ms-2">
                                <a href="{{ url('add-national-highway') }}" class="btn btn-sm btn-outline-primary"><i
                                        class="fas fa-plus"></i> Add National Highway</a>
                            </div>
                        @else
                            <div class="">
                                <a href="{{ route('chainage') }}" class="btn btn-sm btn-outline-dark"><i
                                        class="fas fa-plus"></i> Chainage</a>
                            </div>
                            <div class="ms-2">
                                <a href="{{ url('add-national-highway') }}" class="btn btn-sm btn-outline-dark"><i
                                        class="fas fa-plus"></i> Add National Highway</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mt-3">
            <table class="table-responsive text-xs table table-bordered table-striped user_list" id="roadDetail">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Road ID</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">Road No.</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">Road length</th>
                    <th class="text-center">Road Owner</th>
                    <th class="text-center">CD Work</th>
                    <th class="text-center">CD Bridge</th>
                    <th class="text-center">Pavement</th>
                    <th class="text-center">Suface Type</th>
                    <th class="text-center">Habitation</th>
                    <th class="text-center">Chainage</th>
                    <th class="text-center">View</th>
                    <th class="text-center">Update</th>
                    <th class="text-center">Delete</th>
                </thead>

                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($roadDetails as $roadDetail)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td>
                                {{ $roadDetail->rd_system_id }}
                            </td>

                            <td>
                                {{ $roadDetail->rd_catg_descr }}
                            </td>

                            <td>
                                {{ $roadDetail->rd_number }}
                            </td>
                            <td>
                                {{ $roadDetail->rd_name }}
                            </td>
                            <td>
                                {{ $roadDetail->rd_type_descr }}
                            </td>
                            <td>
                                {{ $roadDetail->road_length }}
                            </td>
                            <td>
                                {{ $roadDetail->owner_name }}
                            </td>
                            <td class="text-center cd-work-details">
                                @if ($inserted == 1 && $user->office_type_cd == 'SDO')
                                    <a href="{{ route('manageCDWorks', ['id' => $roadDetail->rd_system_id]) }}"><i
                                            class="fa fa-plus"></i></a>
                                @else
                                    @if ($viewed == 1)
                                        <a href="{{ url('/asset-management/road/show-cd-works/' . $roadDetail->rd_system_id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center cd-bridge-details">
                                @if ($inserted == 1 && $user->office_type_cd == 'SDO')
                                    <a href="{{ route('road.cd-bridge-details', ['id' => $roadDetail->rd_system_id]) }}"><i
                                            class="fa fa-plus"></i></a>
                                @else
                                    @if ($viewed == 1)
                                        <a href="{{ url('/asset-management/road/show-bridge-data/' . $roadDetail->rd_system_id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>

                            <td class="text-center cd-pavement-details">
                                @if ($inserted == 1 && $user->office_type_cd == 'SDO')
                                    <a href="{{ route('managePavement', ['id' => $roadDetail->rd_system_id]) }}"><i
                                            class="fa fa-plus"></i></a>
                                @else
                                    @if ($viewed == 1)
                                        <a href="{{ url('/asset-management/road/show-pavement-data/' . $roadDetail->rd_system_id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>


                            <td class="text-center">
                                @if ($inserted == 1 && $user->office_type_cd == 'SDO')
                                    <a href="{{ url('/asset-management/manage-surface-type/' . $roadDetail->rd_system_id) }}"><i
                                            class="fa fa-plus"></i></a>
                                @else
                                    @if ($viewed == 1)
                                        <a href="{{ url('/asset-management/road/show-surface-type-data/' . $roadDetail->rd_system_id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>
                            {{-- <td class="text-center">
                                        @if ($inserted == 1)
                                            <a href={{ 'road/add-traffic-intensity/' . $roadDetail->rd_system_id }}><i
                                                    class="fa fa-plus"></i></a>
                                        @else
                                            <i class="fa fa-lock"></i>
                                        @endif

                                        @if ($inserted == 0 && $deleted == 0 && $updated == 0 && $viewed == 0)
                                            <i class="fa fa-lock"></i>
                                        @endif
                                    </td> --}}
                            <td class="text-center">
                                {{-- @if ($inserted == 1)
                                            <a href="{{ route('road.habitation', ['id' => $roadDetail->rd_system_id]) }}"><i
                                                    class="fa fa-plus"></i></a>
                                        @else
                                            <i class="fa fa-lock"></i>
                                        @endif

                                        @if ($inserted == 0 && $deleted == 0 && $updated == 0 && $viewed == 0)
                                            <i class="fa fa-lock"></i>
                                        @endif --}}

                                @if ($inserted == 1 && $user->office_type_cd == 'SDO')
                                    <a href="{{ route('road.habitation', ['id' => $roadDetail->rd_system_id]) }}"><i
                                            class="fa fa-plus"></i></a>
                                @else
                                    @if ($viewed == 1)
                                        <a href="#">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                @endif
                            </td>

                            <td class="text-center">
                                {{-- @if ($inserted == 1)
                                            <a href={{ 'road/add-crb-value/' . $roadDetail->rd_system_id }}><i
                                                    class="fa fa-plus"></i></a>
                                        @else
                                            <i class="fa fa-lock"></i>
                                        @endif

                                        @if ($inserted == 0 && $deleted == 0 && $updated == 0 && $viewed == 0)
                                            <i class="fa fa-lock"></i>
                                        @endif --}}

                                {{-- @if ($inserted == 1 && $user->office_type_cd == 'SDO') --}}
                                <a href="{{ url('/asset-management/chainage/' . $roadDetail->rd_system_id) }}"><i class="fa fa-eye"></i></a>
                                {{-- @else
                                            @if ($viewed == 1)
                                                <a href="#">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @else
                                                <i class="fa fa-lock"></i>
                                            @endif
                                        @endif --}}
                            </td>

                            <td class="text-center">
                                @if ($inserted == 0 && $deleted == 0 && $updated == 0 && $viewed == 0)
                                    <i class="fa fa-lock"></i>
                                @else
                                    <a href="{{ route('showRoad', ['id' => $roadDetail->rd_system_id]) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($inserted == 0 && $deleted == 0 && $updated == 0 && $viewed == 0)
                                    <i class="fa fa-lock"></i>
                                @endif

                                @if ($updated == 1)
                                    <a href=""><i class="fa fa-pen"></i></a>
                                @else
                                    <i class="fa fa-lock"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($inserted == 0 && $deleted == 0 && $updated == 0 && $viewed == 0)
                                    <i class="fa fa-lock"></i>
                                @endif

                                @if ($deleted == 1)
                                    <a href=""><i class="fa fa-trash"></i></a>
                                @else
                                    <i class="fa fa-lock"></i>
                                @endif
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>

        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $("#roadDetail").DataTable({}).buttons().container().appendTo('#roadDetail_wrapper .col-md-11:eq(1)');
        });

        $('.modalClose').click(function() {
            $('.modal-body :input:not([readonly]), .modal-body textarea:not([readonly])').val('');
        });

        $('#modalDate').on('change', function() {
            var selectedDate = $(this).val();
            var dateObj = new Date(selectedDate);
            var year = dateObj.getFullYear();
            var month = ('0' + (dateObj.getMonth() + 1)).slice(-2);
            var day = ('0' + dateObj.getDate()).slice(-2);
            var formattedDate = year + '-' + month + '-' + day;
            $(this).val(formattedDate);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('form.update-user-form1').on("submit", function(e) {
                e.preventDefault();
                let loc = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            //location.reload();
                            $('.editModal1').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form2').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal2').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form3').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal3').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form4').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal4').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form5').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal5').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form6').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal6').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form7').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal7').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form8').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal8').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form9').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal9').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form10').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal10').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form11').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal11').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('form.update-user-form12').on("submit", function(e) {
                e.preventDefault();
                let location = "{{ route('manageRoad') }}";
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
                        if (response.message == 'success1' || response.message == 'success2') {
                            alert("Data Saved Successfully");
                            $('.editModal12').modal('hide');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });

            $('.modifyReq').on("submit", function(e) {
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
                            alert("Request Sent Successfully");
                            location.reload();
                        } else if (response.message == 'notExist') {
                            alert("Update at least one column");
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        alert("Something went wrong. Try Again !!");
                    }
                });
            });
        });
    </script>
@endpush
