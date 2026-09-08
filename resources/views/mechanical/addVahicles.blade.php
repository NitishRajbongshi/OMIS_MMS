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
                        <li class="breadcrumb-item">Add Vehicle Details</li>
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
                <p class="text-bold text-md text-primary py-2 border-bottom">ADD VEHICLE DETAILS</p>
                <span class="fa fa-info-circle text-xs text-danger"></span> Completion of fields indicated by an
                asterisk (
                <span class="text-danger text-bold">*</span> ) is mandatory.
            </div>

            <form action="{{ route('addVehicle') }}" method="post" class="pb-2" enctype="multipart/form-data">
                @csrf
                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2 text-sm">
                        Vehicle Details
                    </legend>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="vehicle_regn_no">Registration Number<span class="star text-danger">*</span></label>
                            <input type="text" id="vehicle_regn_no"
                                class="form-control form-control-sm @error('vehicle_regn_no') is-invalid @enderror"
                                name="vehicle_regn_no" value="{{ old('vehicle_regn_no') }}"
                                placeholder="Enter registration number">
                            @error('vehicle_regn_no')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="vehicle_name">Vehicle Name<span class="star text-danger">*</span></label>
                            <input type="text" id="vehicle_name"
                                class="form-control form-control-sm @error('vehicle_name') is-invalid @enderror"
                                name="vehicle_name" value="{{ old('vehicle_name') }}" placeholder="Enter vehicle name">
                            @error('vehicle_name')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="chassis_no">Chassis Number<span class="star text-danger">*</span></label>
                            <input type="text" id="chassis_no"
                                class="form-control form-control-sm @error('chassis_no') is-invalid @enderror"
                                name="chassis_no" value="{{ old('chassis_no') }}" placeholder="Enter chassis number">
                            @error('chassis_no')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="engine_no">Engine Number<span class="star text-danger">*</span></label>
                            <input type="text" id="engine_no"
                                class="form-control form-control-sm @error('engine_no') is-invalid @enderror"
                                name="engine_no" value="{{ old('engine_no') }}" placeholder="Enter engine number">
                            @error('engine_no')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="vehicle_type">Vehicle Type <span class="star text-danger">*</span></label>
                            <select class="form-control form-control-sm @error('vehicle_type') is-invalid @enderror"
                                id="vehicle_type" name="vehicle_type">
                                <option value="">Choose one</option>
                                @foreach ($vehTypes as $vehType)
                                    <option value="{{ $vehType->veh_type_cd }}">
                                        {{ $vehType->veh_type_descr }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_type')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="seating_capacity">Seating Capacity <span class="star text-danger"></span></label>
                            <select class="form-control form-control-sm" id="seating_capacity" name="seating_capacity">
                                <option value="">Choose one</option>
                                @for ($i = 1; $i <= 60; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('seating_capacity')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="no_of_wheels">Wheel Count <span class="star text-danger"></span></label>
                            <select class="form-control form-control-sm" id="no_of_wheels" name="no_of_wheels">
                                <option value="">Choose one</option>
                                @for ($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('no_of_wheels')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="maker">Vehicle Maker<span class="star text-danger"></span></label>
                            <select class="form-control form-control-sm" id="maker" name="maker">
                                <option value="">Choose one</option>
                                @foreach ($vehMakers as $vehMaker)
                                    <option value="{{ $vehMaker->maker_cd }}">
                                        {{ $vehMaker->maker_name }}</option>
                                @endforeach
                            </select>
                            @error('maker')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="model">Vehicle model<span class="star text-danger"></span></label>
                            <input type="text" id="model" class="form-control form-control-sm" name="model"
                                value="{{ old('model') }}" placeholder="Enter vehicle model">
                            @error('model')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="fuel_type">Fuel Type <span class="star text-danger"></span></label>
                            <select class="form-control form-control-sm" id="fuel_type" name="fuel_type">
                                <option value="">Choose one</option>
                                @foreach ($fuelTypes as $fuelType)
                                    <option value="{{ $fuelType->fuel_type_cd }}">
                                        {{ $fuelType->fuel_type_descr }}</option>
                                @endforeach
                            </select>
                            @error('fuel_type')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="date_of_purchase">Date of Purchase<span class="star text-danger">*</span></label>
                            <input type="date" id="date_of_purchase"
                                class="form-control form-control-sm @error('date_of_purchase') is-invalid @enderror"
                                name="date_of_purchase" value="{{ old('date_of_purchase') }}">
                            @error('date_of_purchase')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="purchase_cost">Purchase Cost ( Rs.)<span class="star text-danger">*</span></label>
                            <input type="number" step="0.01" placeholder="0.00" id="purchase_cost"
                                class="form-control form-control-sm @error('purchase_cost') is-invalid @enderror"
                                name="purchase_cost" value="{{ old('purchase_cost') }}">
                            @error('purchase_cost')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="vehicle_condition">Condition <span class="star text-danger"></span></label>
                            <select class="form-control form-control-sm" id="vehicle_condition" name="vehicle_condition">
                                <option value="">Choose one</option>
                                @foreach ($conditions as $condition)
                                    <option value="{{ $condition->condition_cd }}">
                                        {{ $condition->condition_descr }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_condition')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="laden_weight">Laden Weight<span class="star text-danger"></span></label>
                            <input type="number" step="0.01" placeholder="0.00" id="laden_weight"
                                class="form-control form-control-sm" name="laden_weight"
                                value="{{ old('laden_weight') }}">
                            @error('laden_weight')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="unladen_weight">Unladen Weight<span class="star text-danger"></span></label>
                            <input type="number" step="0.01" placeholder="0.00" id="unladen_weight"
                                class="form-control form-control-sm" name="unladen_weight"
                                value="{{ old('unladen_weight') }}">
                            @error('unladen_weight')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="alloted_to">Alloted to<span class="star text-danger"></span></label>
                            <input type="text" id="alloted_to" class="form-control form-control-sm" name="alloted_to"
                                value="{{ old('alloted_to') }}">
                            @error('alloted_to')
                                <div class="text-danger fw-bold text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="alloted_from">Alloted From</label>
                            <input type="date" step="0.01" placeholder="0.00" id="alloted_from"
                                class="form-control form-control-sm" name="alloted_from"
                                value="{{ old('alloted_from') }}">
                            @error('alloted_from')
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
                                            <label for="workorder">1. Upload Registration Document:</label>
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
                                            <label for="design_doc">2. Upload Insurance Policy Document:</label>
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
                                            <label for="sanction_order">3. Upload Pollution Document:</label>
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
                                            <label for="inspection_report">4. Upload Purchased Document:</label>
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

                <div class="row mt-2 text-right">
                    <div class="col-12">
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

        <!-- table content -->
        <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF DRAFT VEHICLE DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
            </span>
        </h6>
        <div class="container-fluid mainBody border py-2">
            <div class="container-fluid">
                <div class="d-flex text-sm justify-content-end mb-2">
                    <button id="freezeBtn" class="btn btn-sm btn-info rounded-1 text-bold">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Send data for finalization
                    </button>
                </div>
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="vehicle_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Sl No.</th>
                        <th class="text-center">Vehicle Code</th>
                        <th class="text-center">Vehicle Name</th>
                        <th class="text-center">Registration No.</th>
                        <th class="text-center">Chassis No.</th>
                        <th class="text-center">Engine No.</th>
                        <th class="text-center">Vehicle Type</th>
                        <th class="text-center">Seat Capacity</th>
                        <th class="text-center">Total Wheels</th>
                        <th class="text-center">Vehicle Maker</th>
                        <th class="text-center">Vehicle Model</th>
                        <th class="text-center">Fuel Type</th>
                        <th class="text-center">Purchased Date</th>
                        <th class="text-center">Vehicle Cost</th>
                        <th class="text-center">Vehicle Condition</th>
                        <th class="text-center">Laden Weight</th>
                        <th class="text-center">Unladen Weight</th>
                        <th class="text-center">Alloted To</th>
                        <th class="text-center">Alloted From</th>
                        <th class="text-center">Vehicle Remarks</th>
                        <th class="text-center">Rejection Reason</th>
                        <th class="text-center">Select</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Delete</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($vehicleDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->vehicle_asset_cd }}
                                </td>
                                <td>
                                    {{ $item->vehicle_name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->vehicle_regn_no ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->chassis_no ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->engine_no ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->veh_type_descr ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->seating_capacity ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->no_of_wheels ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->maker_name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->model ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->fuel_type_descr ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->date_of_purchase ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->purchase_cost ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->condition_descr ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->laden_weight ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->unladen_weight ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->alloted_to ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->alloted_from_date ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->remarks ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $item->reason_of_rejection ?? 'N/A' }}
                                </td>
                                <td>
                                    <input type="checkbox" class="selected-asset"
                                        data-road-id="{{ $item->vehicle_asset_cd }}" />
                                </td>
                                <td>
                                    <a class = "text-primary edit" data-toggle = "modal"
                                        data-veh-cd = "{{ $item->vehicle_asset_cd }}"
                                        data-veh-name = "{{ $item->vehicle_name }}"
                                        data-veh-regn-no = "{{ $item->vehicle_regn_no }}"
                                        data-veh-chasi-no = "{{ $item->chassis_no }}"
                                        data-veh-eng-no = "{{ $item->engine_no }}"
                                        data-veh-type-desc = "{{ $item->veh_type_descr }}"
                                        data-veh-type-cd = "{{ $item->vehicle_type }}"
                                        data-veh-seat-cap = "{{ $item->seating_capacity }}"
                                        data-veh-no-of-whl = "{{ $item->no_of_wheels }}"
                                        data-veh-maker-name = "{{ $item->maker_name }}"
                                        data-veh-maker-cd = "{{ $item->maker }}" data-veh-model = "{{ $item->model }}"
                                        data-veh-fuel-type-descr = "{{ $item->fuel_type_descr }}"
                                        data-veh-fuel-type-cd = "{{ $item->fuel_type }}"
                                        data-veh-date-of-purchase = "{{ $item->date_of_purchase }}"
                                        data-veh-purchase-cost = "{{ $item->purchase_cost }}"
                                        data-veh-condition-descr = "{{ $item->condition_descr }}"
                                        data-veh-condition-cd = "{{ $item->vehicle_condition }}"
                                        data-veh-laden-weight = "{{ $item->laden_weight }}"
                                        data-veh-unladen-weight = "{{ $item->unladen_weight }}"
                                        data-veh-alloted-to = "{{ $item->alloted_to }}"
                                        data-veh-alloted-from = "{{ $item->alloted_from }}"
                                        data-veh-remarks = "{{ $item->remarks }}"
                                        data-veh-reason-of-rejection = "{{ $item->reason_of_rejection }}"
                                        data-target = "#editDraftVehicleModal">
                                        <i class="fas fa-edit"></i></a>
                                </td>
                                <th class="text-center">
                                    <form action="{{ route('deleteVehicle') }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->vehicle_asset_cd }}">
                                        <button type="submit" class="border-0 bg-transparent"
                                            onclick="return confirm('Are you sure you want to delete this vehicle?')">
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
    <div class="modal" id="editDraftVehicleModal">
        <div class="modal-dialog modal-lg text-xs">
            <form class="editDraftVehicleClass" id="editDraftVehicleForm" name="editDraftVehicleForm" method="POST"
                action="">
                @csrf

                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color: rgb(240, 240, 240);">
                        <h5><i class="fas fa-clipboard-list text-dark"></i>
                            <strong class="text-md">Update Vehicle Details</strong>
                        </h5>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <fieldset class="border p-3 fl">
                            <div class="row form-1-box">
                                <div class="col-md-3">
                                    <label for="veh_asset_cd">Veh Asset CD: <span
                                            class="star text-danger">*</span></label>
                                    <input type="text" id="veh_asset_cd" class="form-control form-control-sm"
                                        name="veh_asset_cd" value="" readonly>
                                    @error('vehicle_regn_no')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="vehicle_regn_no">Reg. No. <span class="star text-danger">*</span></label>
                                    <input type="text" id="vehicle_regn_no" class="form-control form-control-sm"
                                        name="vehicle_regn_no" value="">
                                    @error('vehicle_regn_no')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="vehicle_name">Vehicle Name<span class="star text-danger">*</span></label>
                                    <input type="text" id="vehicle_name" class="form-control form-control-sm"
                                        name="vehicle_name" value="">
                                    @error('vehicle_name')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="chassis_no">Chassis No. <span class="star text-danger">*</span></label>
                                    <input type="text" id="chassis_no" class="form-control form-control-sm"
                                        name="chassis_no" value="">
                                    @error('chassis_no')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="engine_no">Engine No. <span class="star text-danger">*</span></label>
                                    <input type="text" id="engine_no" class="form-control form-control-sm"
                                        name="engine_no" value="">
                                    @error('engine_no')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="vehicle_type_edit">Vehicle Type <span
                                            class="star text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="vehicle_type_edit"
                                        name="vehicle_type">
                                        <option value="">Choose one</option>
                                        @foreach ($vehTypes as $vehType)
                                            <option value="{{ $vehType->veh_type_cd }}">
                                                {{ $vehType->veh_type_descr }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="seating_capacity_edit">Seating Capacity <span
                                            class="star text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="seating_capacity_edit"
                                        name="seating_capacity">
                                        <option value="">Choose one</option>
                                        @for ($i = 1; $i <= 60; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('seating_capacity')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="no_of_wheels_edit">Wheel Count <span
                                            class="star text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="no_of_wheels_edit"
                                        name="no_of_wheels">
                                        <option value="">Choose one</option>
                                        @for ($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('no_of_wheels')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="maker_edit">Vehicle Maker<span class="star text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="maker_edit" name="maker">
                                        <option value="">Choose one</option>
                                        @foreach ($vehMakers as $vehMaker)
                                            <option value="">
                                                {{ $vehMaker->maker_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('maker')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="model">Vehicle model<span class="star text-danger">*</span></label>
                                    <input type="text" id="model" class="form-control form-control-sm"
                                        name="model" value="">
                                    @error('model')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="fuel_type_edit">Fuel Type <span class="star text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="fuel_type_edit" name="fuel_type">
                                        <option value="">Choose one</option>
                                        @foreach ($fuelTypes as $fuelType)
                                            <option value="{{ $fuelType->fuel_type_cd }}">
                                                {{ $fuelType->fuel_type_descr }}</option>
                                        @endforeach
                                    </select>
                                    @error('fuel_type')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="date_of_purchase">Date of Purchase<span
                                            class="star text-danger">*</span></label>
                                    <input type="date" id="date_of_purchase" class="form-control form-control-sm"
                                        name="date_of_purchase" value="">
                                    @error('date_of_purchase')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="purchase_cost">Purchase Cost ( Rs.)<span
                                            class="star text-danger">*</span></label>
                                    <input type="number" step="0.01" placeholder="0.00" id="purchase_cost"
                                        class="form-control form-control-sm" name="purchase_cost" value="">
                                    @error('purchase_cost')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="vehicle_condition_edit">Condition <span
                                            class="star text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="vehicle_condition_edit"
                                        name="vehicle_condition">
                                        <option value="">Choose one</option>
                                        @foreach ($conditions as $condition)
                                            <option value="{{ $condition->condition_cd }}">
                                                {{ $condition->condition_descr }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_condition')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="laden_weight">Laden Weight<span class="star text-danger">*</span></label>
                                    <input type="number" step="0.01" placeholder="0.00" id="laden_weight"
                                        class="form-control form-control-sm" name="laden_weight" value="">
                                    @error('laden_weight')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="unladen_weight">Unladen Weight<span
                                            class="star text-danger">*</span></label>
                                    <input type="number" step="0.01" placeholder="0.00" id="unladen_weight"
                                        class="form-control form-control-sm" name="unladen_weight" value="">
                                    @error('unladen_weight')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="alloted_to">Alloted to<span class="star text-danger">*</span></label>
                                    <input type="text" id="alloted_to" class="form-control form-control-sm"
                                        name="alloted_to" value="">
                                    @error('alloted_to')
                                        <div class="text-danger fw-bold text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="alloted_from">Alloted From</label>
                                    <input type="date" step="0.01" placeholder="0.00" id="alloted_from"
                                        class="form-control form-control-sm" name="alloted_from" value="">
                                    @error('alloted_from')
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

                            <div class="row form-1-box border py-2">
                                <div class="col-md-12">
                                    <label> Reason Of Rejection <span class="star text-danger">*
                                        </span></label>

                                    <input id="txt_reason_of_rejection" class="form-control form-control-sm"
                                        name="txt_reason_of_rejection" value="" readonly>
                                </div>
                            </div>

                        </fieldset>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-success modalUpBtn" onclick="editDraftData()"><i
                                class="fa fa-check mr-2" aria-hidden="true"></i>Update</button>
                        <a class="btn btn-sm modalClose btn-danger" data-dismiss="modal"><i class="fa fa-times mr-2"
                                aria-hidden="true"></i>Close</a>
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
    <script src="{{ asset('js/mechanical/vehicle/script.js') }}" defer></script>
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script>
        $(function() {
            $("#vehicle_details_table").DataTable();
        });
    </script>
@endpush
