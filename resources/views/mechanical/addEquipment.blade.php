@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.equipment') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('mechanical') }}">Manage Machineries</a>
                        </li>
                        <li class="breadcrumb-item">Add Equipment Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        @if (session('failed'))
            <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <strong>Failed!</strong> {{ session('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle" aria-hidden="true"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- <x-mechanical-flash-message /> --}}
        <div class="row my-2">
            <x-mechanical-nav-button />
        </div>
        <div class="container-fluid border" style="background-color: white">
            <div class="text-secondary text-bold text-xs pb-2">
                <p class="text-bold text-md text-primary py-2 border-bottom">ADD EQUIPMENT DETAILS</p>
                <span class="fa fa-info-circle text-xs text-danger"></span> Completion of fields indicated by an
                asterisk (
                <span class="text-danger text-bold">*</span> ) is mandatory.
            </div>

            <form action="{{ route('addEquipment') }}" method="post" class="pb-2" enctype="multipart/form-data">
                @csrf
                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2 text-sm">
                        Equipment Details
                    </legend>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="equipment_name">Equipment Name:<span class="star">*</span></label>
                            <input type="text" id="equipment_name"
                                class="form-control form-control-sm @error('equipment_name') is-invalid @enderror"
                                name="equipment_name" placeholder="Enter Equipment Name">
                            @error('equipment_name')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="serial_number">Serial Number:<span class="star">*</span></label>
                            <input type="text" id="serial_number"
                                class="form-control form-control-sm @error('serial_number') is-invalid @enderror"
                                name="serial_number" placeholder="Enter Serial Number">
                            @error('serial_number')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="model_no">Model Number:<span class="star">*</span></label>
                            <input type="text" id="model_no"
                                class="form-control form-control-sm @error('model_no') is-invalid @enderror" name="model_no"
                                placeholder="Enter Model Number">
                            @error('model_no')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="purchase_year">Purchase Year:<span class="star"></span></label>
                            <select class="form-control form-control-sm" id="purchase_year" name="purchase_year">
                                <option value="">Choose one</option>
                            </select>
                            @error('purchase_year')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="purchase_cost">Purchase Cost:<span class="star"></span></label>
                            <input type="text" id="purchase_cost" class="form-control form-control-sm"
                                name="purchase_cost" placeholder="Enter Purchase Cost">
                            @error('purchase_cost')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="equipment_condition_cd">Equipment Condition:</label>
                            <select class="form-control form-control-sm" id="equipment_condition_cd"
                                name="equipment_condition_cd">
                                <option value="">Choose one</option>
                                @foreach ($conditions as $condition)
                                    <option value="{{ $condition->condition_cd }}">
                                        {{ $condition->condition_descr }}</option>
                                @endforeach
                            </select>
                            @error('equipment_condition_cd')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="is_under_waranty">Is under Warranty? <span class="star"></span></label><br>
                            <input type="radio" id="yes" name="is_under_waranty" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="is_under_waranty" value="N">
                            <label for="no">No</label>
                            @error('is_under_waranty')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row form-1-box">
                        <div class="col-md-12">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control form-control-sm text-sm" id="remarks" name="remarks" rows="2"
                                placeholder="Write bridge remarks..."></textarea>
                            @error('remarks')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row form-1-box border mt-2">
                        <div class="col-md-12 pt-2" style="background-color: #efeeee;">
                            <fieldset class="">
                                <legend class="w-auto px-2" style="font-size:13px ">
                                    Upload Documents
                                </legend>
                                <div class="p-2">
                                    <div>
                                        <p class="text-sm text-info text-underline"><strong>
                                                <i class="fa fa-info-circle mr-1 text-xs"></i>Important:
                                            </strong></p>
                                        <ul class="text-xs text-secondary">
                                            <li>
                                                <strong>
                                                    File Type:
                                                </strong>
                                                Only PDF files are supported for upload in this section.
                                            </li>
                                            <li>
                                                <strong>
                                                    File Size Limit:
                                                </strong>
                                                The maximum allowed file size is 2 MB.
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="row form-1-box my-1">
                                        <div class="col-md-4">
                                            <label for="workorder">1. Upload workorder:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="file" class="text-xs text-success" id="workorder"
                                                name="workorder" onchange="showRemoveBtn('workorder')">
                                            <button type="button" id="removeBtn_workorder"
                                                class="outline-0 border border-danger text-danger text-xs rounded-1"
                                                style="background:rgb(252, 217, 217); display:none;"
                                                onclick="removeFile('workorder')">
                                                <i class="fa fa-trash mr-1 text-xs"></i>
                                                Remove
                                            </button>
                                            @error('workorder')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="row form-1-box my-1">
                                        <div class="col-md-4">
                                            <label for="design_doc">2. Upload Design Document:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="file" class="text-xs text-success" id="design_doc"
                                                name="design_doc" onchange="showRemoveBtn('design_doc')">
                                            <button type="button" id="removeBtn_design_doc"
                                                class="outline-0 border border-danger text-danger text-xs rounded-1"
                                                style="background:rgb(252, 217, 217); display:none;"
                                                onclick="removeFile('design_doc')">
                                                <i class="fa fa-trash mr-1 text-xs"></i>
                                                Remove
                                            </button>
                                            @error('design_doc')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="row form-1-box my-1">
                                        <div class="col-md-4">
                                            <label for="sanction_order">3. Upload Saction Order:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="file" class="text-xs text-success" id="sanction_order"
                                                name="sanction_order" onchange="showRemoveBtn('sanction_order')">
                                            <button type="button" id="removeBtn_sanction_order"
                                                class="outline-0 border border-danger text-danger text-xs rounded-1"
                                                style="background:rgb(252, 217, 217); display:none;"
                                                onclick="removeFile('sanction_order')">
                                                <i class="fa fa-trash mr-1 text-xs"></i>
                                                Remove
                                            </button>
                                            @error('sanction_order')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="row form-1-box my-1">
                                        <div class="col-md-4">
                                            <label for="inspection_report">4. Upload Last Inspection
                                                Report:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="file" class="text-xs text-success" id="inspection_report"
                                                name="inspection_report" onchange="showRemoveBtn('inspection_report')">
                                            <button type="button" id="removeBtn_inspection_report"
                                                class="outline-0 border border-danger text-danger text-xs rounded-1"
                                                style="background:rgb(252, 217, 217); display:none;"
                                                onclick="removeFile('inspection_report')">
                                                <i class="fa fa-trash mr-1 text-xs"></i>
                                                Remove
                                            </button>
                                            @error('inspection_report')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </fieldset>

                <div class="row mt-2">
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2">
                            <i class="fa fa-save"></i>
                            Save
                        </button>
                        <button type="reset" class="btn btn-info btn-sm rounded-0 mt-2">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Reset
                        </button>
                        <button class="btn btn-secondary btn-sm rounded-0 mt-2">
                            <a href={{ route('mechanical') }} class="text-white"><i class="fa fa-backward"></i>
                                Back</a>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- table content -->
    <div class="container-fluid mainBody py-2">
        <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF DRAFT EQUIPMENT DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
            </span>
        </h6>
        <div class="container-fluid border">
            <div class="d-flex text-sm justify-content-end mb-2">
                <button id="freezeBtn" class="btn btn-sm btn-info mt-1 rounded-1 text-bold">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    Send data for finalization
                </button>
            </div>
            <table class="table-responsive text-xs table table-bordered table-striped user_list"
                id="equipment_details_table">
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

                    @foreach ($equipmentDetails as $item)
                        <tr class="text-center">
                            <td>{{ $i }}</td>
                            <td>
                                {{ $item->euipment_cd }}
                            </td>
                            <td>
                                {{ $item->equipment_name ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $item->serial_number ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $item->model_no ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $item->purchase_year ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $item->purchase_cost ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $item->condition_descr ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $item->is_under_waranty == 'Y' ? 'YES' : 'NO' }}
                            </td>
                            <td>
                                {{ $item->equipment_remarks ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $item->reason_of_rejection ?? 'N/A' }}
                            </td>
                            <td style="min-width:4rem;" class="text-center">
                                <input type="checkbox" class="selected-asset" data-road-id="{{ $item->euipment_cd }}" />
                            </td>
                            <td style="min-width:4rem;" class="text-center">
                                <a class="text-primary edit" data-toggle="modal"
                                    data-equip-id="{{ $item->euipment_cd }}"
                                    data-equip-name="{{ $item->equipment_name }}"
                                    data-serial-no="{{ $item->serial_number }}" data-model-no="{{ $item->model_no }}"
                                    data-purchased="{{ $item->purchase_year }}" data-cost="{{ $item->purchase_cost }}"
                                    data-condition="{{ $item->equipment_condition_cd }}"
                                    data-warranty="{{ $item->is_under_waranty }}"
                                    data-rejection="{{ $item->reason_of_rejection }}"
                                    data-remarks="{{ $item->equipment_remarks }}" data-target="#equipmentEditModal">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                            <th class="text-center">
                                <form action="{{ route('deleteEquipment') }}" method="post">
                                    @method('delete')
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->euipment_cd }}">
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
                                <input type="text" class="form-control form-control-sm" id="edit_equipment_id"
                                    name="equipment_id" value="" readonly />
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="equipment_name">Equpment Name:<span class="star">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="edit_equipment_name"
                                    name="equipment_name">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="serial_number">Serial Number:<span class="star">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="edit_serial_number"
                                    name="serial_number">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="model_no">Model Number:<span class="star">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="edit_model_no"
                                    name="model_no">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="purchase_year">Purchase Year:<span class="star">*</span></label>
                                <select class="form-control form-control-sm" id="edit_purchase_year"
                                    name="purchase_year">
                                    <option value="">Choose one</option>
                                    <?php for ($i = Carbon\Carbon::now()->year; $i >= 1950; $i--) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="purchase_cost">Purchase Cost:<span class="star">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="edit_purchase_cost"
                                    name="purchase_cost">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="equipment_condition_cd">Equipment Condition:</label>
                                <select class="form-control form-control-sm" id="edit_equipment_condition_cd"
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
                                <textarea class="form-control form-control-sm text-sm" id="edit_remarks" name="remarks" rows="2"
                                    placeholder="Write bridge remarks..."></textarea>
                            </div>
                            <div class="col-12">
                                <label for="reason">Rejection Reason</label>
                                <textarea class="form-control form-control-sm text-sm" id="edit_reason" name="reason" rows="2"
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
    <style>
        /* Target the Select2 container */
        .select2-container .select2-selection--single {
            font-size: 13px;
        }

        /* Target the dropdown options */
        .select2-results__option {
            font-size: 13px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/mechanical/equipment/script.js') }}" defer></script>
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script>
        const buildingConditions = @json($conditions);
        var currentYear = new Date().getFullYear();
        var yearDropdown = document.getElementById("purchase_year");
        for (var year = currentYear; year >= 1950; year--) {
            var option = document.createElement("option");
            option.value = year;
            option.text = year;
            yearDropdown.appendChild(option);
        }
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
    <script>
        $(function() {
            $("#equipment_details_table").DataTable();
        });
    </script>
@endpush
