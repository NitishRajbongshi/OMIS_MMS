@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="col-md-3 col-sm-6 mb-3">
            @if ($user_dept_cd == 15)
                <a href="{{ url('#') }}" class="mr-2">Dashboard</a>/ Change Status Mechanical
            @endif

            @if ($user_dept_cd == 6)
                <a href="{{ url('#') }}" class="mr-2">Dashboard</a>/ Change Status Housing
            @endif
            @if ($user_dept_cd == 14)
                <a href="{{ url('#') }}" class="mr-2">Dashboard</a>/ Change Status Roads & Bridges
            @endif

            @if ($user_dept_cd == 3)
                <a href="{{ url('#') }}" class="mr-2">Dashboard</a>/ Change Status NH
            @endif

        </div>
    </div>
    <section class="content" id="statusChangeVehicleSection" name="statusChangeVehicleSection">
        <div class="container-fluid mt-3">
            <div class="container-fluid mt-3">
                Vehicle Details
            </div>
            <table class="table-responsive table table-bordered table-striped user_list text-sm" id="vehDetails">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Regn No.</th>
                    <th class="text-center">Chassis No.</th>
                    <th class="text-center">Eng No.</th>
                    <th class="text-center">Vehicle Type</th>
                    <th class="text-center">Vehcile Name</th>
                    <th class="text-center">Laden Wt</th>
                    <th class="text-center">Unladen Wt</th>
                    <th class="text-center">Vehicle Condition</th>
                    <th class="text-center">Action</th>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($asset_details_1 as $key)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td style="position: relative">
                                {{ $key->vehicle_regn_no }}
                            </td>
                            <td style="position: relative">
                                {{ $key->chassis_no }}
                            </td>
                            <td style="position: relative">
                                {{ $key->engine_no }}
                            </td>
                            <td style="position: relative">
                                {{ $key->vehicle_type }}
                            </td>
                            <td style="position: relative">
                                {{ $key->vehicle_name }}
                            </td>
                            <td style="position: relative">
                                {{ $key->laden_weight }}
                            </td>
                            <td style="position: relative">
                                {{ $key->unladen_weight }}
                            </td>
                            <td style="position: relative">
                                {{ $key->condition_descr }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editAssetCondition{{ $key->vehicle_asset_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span>
                            </td>
                        </tr>
                        <?php $i++; ?>

                        <!-- edit modal Asset Status-->
                        <div class="modal fade" id="editAssetCondition{{ $key->vehicle_asset_cd }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="editAssetCondition_form"
                                        id="editAssetCondition_{{ $key->vehicle_asset_cd }}" method="POST"
                                        action="{{ route('updateAssetStatus') }}">
                                        @csrf

                                        <input type="hidden" name="asset_cd" id ="asset_cd"
                                            value="{{ $key->vehicle_asset_cd }}">
                                        <input type="hidden" name="asset_table_name" id ="asset_table_name"
                                            value="vehicles">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Vehicle Status
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_reason" id="modification_reason" rows="3" class="form-control"
                                                        required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->condition_descr }}</textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <select id="new_value_cd" class="custom-select form-control"
                                                        name="new_value_cd">
                                                        <option value="" disable selected hidden required>
                                                            Please
                                                            Select</option>
                                                        @foreach ($veh_cond_m as $item)
                                                            <option value="{{ $item->condition_cd }}">
                                                                {{ $item->condition_descr }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
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
    </section>

    <section class="content" id="statusChangeEquipmentSection" name="statusChangeEquipmentSection">
        <div class="container-fluid mt-3">
            <div class="container-fluid mt-3">
                Equipment Details
            </div>
            <table class="table-responsive table table-bordered table-striped user_list text-sm" id="eqpDetails">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Equipment Name</th>
                    <th class="text-center">Serial Number</th>
                    <th class="text-center">Modle No</th>
                    <th class="text-center">Purchase Year</th>
                    <th class="text-center">Equipment Condition</th>
                    <th class="text-center">Action</th>


                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($asset_details as $key)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td style="position: relative">
                                {{ $key->equipment_name }}
                            </td>

                            <td style="position: relative">

                                {{ $key->serial_number }}
                            </td>

                            <td style="position: relative">
                                {{ $key->model_no }}
                            </td>
                            <td style="position: relative">
                                {{ $key->purchase_year }}
                            </td>
                            <td style="position: relative">
                                {{ $key->condition_descr }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editAssetCondition{{ $key->euipment_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span>
                            </td>
                        </tr>
                        <?php $i++; ?>
                        <!-- edit modal Asset Status-->
                        <div class="modal fade" id="editAssetCondition{{ $key->euipment_cd }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="editAssetCondition_form" id="editAssetCondition_{{ $key->euipment_cd }}"
                                        method="POST" action="{{ route('updateAssetStatus') }}">
                                        @csrf

                                        <input type="hidden" name="asset_cd" id ="asset_cd"
                                            value="{{ $key->euipment_cd }}">
                                        <input type="hidden" name="asset_table_name" id ="asset_table_name"
                                            value="equipment">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Equipment
                                                Status
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_reason" id="modification_reason" rows="3" class="form-control"
                                                        required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->condition_descr }}</textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <select id="new_value_cd" class="custom-select form-control"
                                                        name="new_value_cd">
                                                        <option value="" disable selected hidden required>
                                                            Please
                                                            Select</option>
                                                        @foreach ($eqp_cond_m as $item)
                                                            <option value="{{ $item->condition_cd }}">
                                                                {{ $item->condition_descr }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
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
    </section>
@endsection
@push('styles')
    <style>
        .iconSpan {
            bottom: 0;
            right: 0;
            position: absolute;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $("#eqpDetails").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#eqpDetails_wrapper .col-md-11:eq(1)');
        });

        $(function() {
            $("#vehDetails").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#vehDetails_wrapper .col-md-11:eq(1)');
        });



        $('.modalClose').click(function() {
            //location.reload();
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
            $('form.editAssetCondition_form').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var asset_cd = $('#asset_cd').val();
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
                            $('#editAssetCondition' + asset_cd).modal().hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: "Asset Status Updated Successfully ",
                                showConfirmButton: true,
                                timer: 5000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'error',
                                text: "Asset Status Could Not be Updated!!!!\nPlease Try Again ...",
                                showConfirmButton: true,
                                timer: 5000
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        $('#editAssetCondition' + asset_cd).modal().hide();
                        Swal.fire({
                            icon: 'error',
                            title: 'error',
                            text: "Asset Status Could Not be Updated!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                            // window.location.replace(location);
                        });
                    }
                });
            });


        });
    </script>
@endpush
