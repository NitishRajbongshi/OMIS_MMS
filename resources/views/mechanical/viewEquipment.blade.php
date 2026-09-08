@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-12 col-md-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.equipment') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Manage Machineries</li>
                    </ol>
                </div>
                <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                    @if (session('finalised') === 1)
                        <div class="ms-2">
                            <a href="{{ route('vehicle.finalize') }}" class="btn btn-sm text-light btn-primary">
                                <i class="fas fa-check-circle"></i>
                                Finalize Machinery Details
                            </a>
                        </div>
                    @endif
                    @if (session('dataEntry') === 1)
                        <div class="ms-2">
                            <a href="{{ route('addVehicle') }}" class="btn btn-sm text-light btn-primary">
                                <i class="fas fa-plus-circle"></i>
                                Add Machinery Details
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="d-flex justify-content-start my-2">
        <div class="col-sm-6 col-md-auto">
            <button class="btn btn-sm btn-primary">
                <a href="{{ route('mechanical') }}" class="text-light">
                    <i class="fa fa-eye text-xs"></i>
                    View Equipment Details
                </a>
            </button>
        </div>
        <div class="col-sm-6 col-md-auto">
            <button class="btn btn-sm btn-outline-secondary">
                <a href="{{ route('vehicle') }}" class="text-dark">
                    <i class="fa fa-eye text-xs"></i>
                    View Vehicle Details
                </a>
            </button>
        </div>
    </div>
    <section class="content">
        <!-- table content -->
        <h6 class="p-2 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF FINALIZED EQUIPMENT DETAILS UNDER NAGALAND P W D.
            </span>
        </h6>
        <div class="container-fluid border mainBody py-2">
            <div class="">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="final_equipment_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Sl No.</th>
                        <th class="text-center">Equipment Code</th>
                        <th class="text-center">Equipment Name</th>
                        <th class="text-center">Serial Number</th>
                        <th class="text-center">Model Number</th>
                        <th class="text-center">Purchased year</th>
                        <th class="text-center">Purchased Cost</th>
                        <th class="text-center">Equipment Condition</th>
                        <th class="text-center">Under Warranty?</th>
                        <th class="text-center">Equipment Remarks</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($finalEquipmentDetails as $finalEquipmentDetail)
                            <tr class="text-center">
                                <td class="text-center">{{ $i }}</td>
                                <td>
                                    {{ $finalEquipmentDetail->euipment_cd }}
                                </td>
                                <td>
                                    {{ $finalEquipmentDetail->equipment_name }}
                                </td>
                                <td>
                                    {{ $finalEquipmentDetail->serial_number }}
                                </td>
                                <td>
                                    {{ $finalEquipmentDetail->model_no }}
                                </td>
                                <td>
                                    {{ $finalEquipmentDetail->purchase_year }}
                                </td>
                                <td>
                                    {{ $finalEquipmentDetail->purchase_cost }}
                                </td>
                                <td>
                                    {{ $finalEquipmentDetail->condition_descr }}
                                </td>
                                <td>
                                    @if ($finalEquipmentDetail->is_under_waranty == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $finalEquipmentDetail->equipment_remarks }}
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- table content -->
        <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF DRAFT EQUIPMENT DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
            </span>
        </h6>
        <div class="container-fluid border mainBody py-2">
            <div class="">
                <div class="d-flex text-sm justify-content-end mb-2">
                    <button id="freezeBtn" class="btn btn-sm btn-info rounded-1 text-bold">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Send data for finalization
                    </button>
                </div>
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="draft_equipment_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Sl No.</th>
                        <th class="text-center">Equipment Code</th>
                        <th class="text-center">Equipment Name</th>
                        <th class="text-center">Serial Number</th>
                        <th class="text-center">Model Number</th>
                        <th class="text-center">Purchased year</th>
                        <th class="text-center">Purchased Cost</th>
                        <th class="text-center">Equipment Condition</th>
                        <th class="text-center">Under Warranty?</th>
                        <th class="text-center">Equipment Remarks</th>
                        <th class="text-center">Rejection Reason</th>
                        <th class="text-center">Select</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Delete</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($draftEquipmentDetails as $draftEquipmentDetail)
                            <tr class="text-center">
                                <td class="text-center">{{ $i }}</td>
                                <td>
                                    {{ $draftEquipmentDetail->euipment_cd }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->equipment_name }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->serial_number }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->model_no }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->purchase_year }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->purchase_cost }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->condition_descr }}
                                </td>
                                <td>
                                    @if ($draftEquipmentDetail->is_under_waranty == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->equipment_remarks }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->reason_of_rejection }}
                                </td>
                                <td style="min-width:4rem;" class="text-center">
                                    <input type="checkbox" class="selected-asset"
                                        data-road-id="{{ $draftEquipmentDetail->euipment_cd }}" />
                                </td>
                                <td style="min-width:4rem;" class="text-center">
                                    <a class="text-primary edit" data-toggle="modal"
                                        data-equip-id="{{ $draftEquipmentDetail->euipment_cd }}"
                                        data-equip-name="{{ $draftEquipmentDetail->equipment_name }}"
                                        data-serial-no="{{ $draftEquipmentDetail->serial_number }}"
                                        data-model-no="{{ $draftEquipmentDetail->model_no }}"
                                        data-purchased="{{ $draftEquipmentDetail->purchase_year }}"
                                        data-cost="{{ $draftEquipmentDetail->purchase_cost }}"
                                        data-condition="{{ $draftEquipmentDetail->equipment_condition_cd }}"
                                        data-warranty="{{ $draftEquipmentDetail->is_under_waranty }}"
                                        data-rejection="{{ $draftEquipmentDetail->reason_of_rejection }}"
                                        data-remarks="{{ $draftEquipmentDetail->equipment_remarks }}"
                                        data-target="#equipmentEditModal">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <th style="min-width:4rem;" class="text-center">
                                    <form action="{{ route('destroy.road') }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" name="road_id"
                                            value="{{ $draftEquipmentDetail->euipment_cd }}">
                                        <button type="submit" class="border-0 bg-transparent"
                                            onclick="return confirm('Are you sure you want to delete this equipment?')">
                                            <i class="fa fa-trash text-xs text-danger"></i>
                                        </button>
                                    </form>
                                </th>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- The Edit Modal -->
    <div class="modal" id="equipmentEditModal">
        <div class="modal-dialog modal-lg text-xs">
            <form class="editEquipmentForm" id="editEquipmentForm" name="editEquipmentForm" method="POST"
                action="#" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="equipment_id" name="equipment_id" value="">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color: rgb(240, 240, 240);">
                        <strong class="text-md text-uppercase">Update Equipment Details</strong>
                        <a type="button" data-dismiss="modal"><i class="fas fa-times"></i></a>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row text-xs">
                            <div class="col-12 col-md-4">
                                <label>Equipment ID:<span class="star text-danger">*</span></label><br>
                                <input type="text" class="form-control" id="edit_equipment_id" name="equipment_id"
                                    value="" readonly />
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="equipment_name">Equpment Name:<span class="star">*</span></label>
                                <input type="text" class="form-control" id="edit_equipment_name"
                                    name="equipment_name">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="serial_number">Serial Number:<span class="star">*</span></label>
                                <input type="text" class="form-control" id="edit_serial_number" name="serial_number">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="model_no">Model Number:<span class="star">*</span></label>
                                <input type="text" class="form-control" id="edit_model_no" name="model_no">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="purchase_year">Purchase Year:<span class="star">*</span></label>
                                <select class="form-control" id="edit_purchase_year" name="purchase_year">
                                    <option value="">Choose one</option>
                                    <?php for ($i = Carbon\Carbon::now()->year; $i >= 1950; $i--) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="purchase_cost">Purchase Cost:<span class="star">*</span></label>
                                <input type="text" class="form-control" id="edit_purchase_cost" name="purchase_cost">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="equipment_condition_cd">Equipment Condition:</label>
                                <select class="form-control" id="edit_equipment_condition_cd"
                                    name="equipment_condition_cd">
                                    <option value="">Choose one</option>
                                    @foreach ($conditions as $condition)
                                        <option value="{{ $condition->condition_cd }}">
                                            {{ $condition->condition_descr }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="edit_is_under_waranty">Is under Warranty? <span
                                        class="star">*</span></label><br>
                                <input type="radio" id="yes" name="edit_is_under_waranty" value="Y"
                                    checked>
                                <label for="yes">Yes</label>
                                <input type="radio" id="no" name="edit_is_under_waranty" value="N">
                                <label for="no">No</label>
                            </div>
                            <div class="col-12">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control text-sm" id="edit_remarks" name="remarks" rows="2"
                                    placeholder="Write bridge remarks..."></textarea>
                            </div>
                            <div class="col-12">
                                <label for="reason">Rejection Reason</label>
                                <textarea class="form-control text-sm" id="edit_reason" name="reason" rows="2"
                                    placeholder="No rejection reason available" readonly></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm text-md mt-2" onclick="editDraftData()">
                            <i class="fa fa-save"></i>
                            Update
                        </button>
                        <button class="btn btn-sm modalClose btn-danger text-md mt-2" data-dismiss="modal">
                            <i class="fa fa-times mr-2"></i> CLOSE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->
    <x-success-modal />
    <x-warning-modal />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/mechanical/equipment/script.js') }}" defer></script>
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script>
        $(function() {
            $("#draft_equipment_details_table").DataTable();
            $("#final_equipment_details_table").DataTable();
        });
        $(document).ready(function() {
            $('#equipmentEditModal').on('show.bs.modal ', function(event) {
                var button = $(event.relatedTarget); // get the button property
                let equipment_id = button.data('equip-id');
                let equip_name = button.data('equip-name');
                let serial_no = button.data('serial-no');
                let model_no = button.data('model-no');
                let parchased = button.data('purchased');
                let cost = button.data('cost');
                let condition = button.data('condition');
                let warranty = button.data('warranty');
                let remark = button.data('remarks');
                let reason = button.data('rejection');

                $("#equipment_id").val(equipment_id);
                $("#edit_equipment_id").val(equipment_id);
                $("#edit_equipment_name").val(equip_name);
                $("#edit_serial_number").val(serial_no);
                $("#edit_model_no").val(model_no);
                $("#edit_purchase_cost").val(cost);
                $("#edit_remarks").val(remark);
                $("#edit_equipment_condition_cd").val(condition);
                $("#edit_purchase_year").val(parchased);
                $("#edit_reason").val(reason);
            });
        });

        function editDraftData() {
            var formData = $("#editEquipmentForm").serialize();
            $.ajax({
                type: "POST",
                url: "/asset-management/edit-equipment",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: formData,
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            location.reload(true);
                        });
                    }
                    if (response.status === "failed") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            location.reload(true);
                        });
                    }
                    if (response.status === 500 || response.status === 409) {
                        console.log(response.message);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            location.reload(true);
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        showConfirmButton: true,
                        timer: 3000
                    }).then(() => {
                        location.reload(true);
                    });
                },
            });
        }
    </script>
@endpush
