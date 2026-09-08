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
            <button class="btn btn-sm btn-outline-secondary">
                <a href="{{ route('mechanical') }}" class="text-dark">
                    <i class="text-xs fa fa-eye"></i>
                    View Equipment Details
                </a>
            </button>
        </div>
        <div class="col-sm-6 col-md-auto">
            <button class="btn btn-sm btn-primary">
                <a href="{{ route('vehicle') }}" class="text-light">
                    <i class="text-xs fa fa-eye"></i>
                    View Vehicle Details
                </a>
            </button>
        </div>
    </div>
    <section class="content">
        <!-- table content -->
        <h6 class="p-2 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF FINALIZED VEHICLE DETAILS UNDER NAGALAND P W D.
            </span>
        </h6>
        <div class="container-fluid border mainBody py-2">
            <div class="">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="final_equipment_details_table">
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
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($finalVehicleDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->vehicle_asset_cd }}
                                </td>
                                <td>
                                    {{ $item->vehicle_name }}
                                </td>
                                <td>
                                    {{ $item->vehicle_regn_no }}
                                </td>
                                <td>
                                    {{ $item->chassis_no }}
                                </td>
                                <td>
                                    {{ $item->engine_no }}
                                </td>
                                <td>
                                    {{ $item->veh_type_descr }}
                                </td>
                                <td>
                                    {{ $item->seating_capacity }}
                                </td>
                                <td>
                                    {{ $item->no_of_wheels }}
                                </td>
                                <td>
                                    {{ $item->maker_name }}
                                </td>
                                <td>
                                    {{ $item->model }}
                                </td>
                                <td>
                                    {{ $item->fuel_type_descr }}
                                </td>
                                <td>
                                    {{ $item->date_of_purchase }}
                                </td>
                                <td>
                                    {{ $item->purchase_cost }}
                                </td>
                                <td>
                                    {{ $item->condition_descr }}
                                </td>
                                <td>
                                    {{ $item->laden_weight }}
                                </td>
                                <td>
                                    {{ $item->unladen_weight }}
                                </td>
                                <td>
                                    {{ $item->alloted_to }}
                                </td>
                                <td>
                                    {{ $item->alloted_from }}
                                </td>
                                <td>
                                    {{ $item->remarks }}
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
                LIST OF DRAFT VEHICLE DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
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
                        @foreach ($draftVehicleDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->vehicle_asset_cd }}
                                </td>
                                <td>
                                    {{ $item->vehicle_name }}
                                </td>
                                <td>
                                    {{ $item->vehicle_regn_no }}
                                </td>
                                <td>
                                    {{ $item->chassis_no }}
                                </td>
                                <td>
                                    {{ $item->engine_no }}
                                </td>
                                <td>
                                    {{ $item->veh_type_descr }}
                                </td>
                                <td>
                                    {{ $item->seating_capacity }}
                                </td>
                                <td>
                                    {{ $item->no_of_wheels }}
                                </td>
                                <td>
                                    {{ $item->maker_name }}
                                </td>
                                <td>
                                    {{ $item->model }}
                                </td>
                                <td>
                                    {{ $item->fuel_type_descr }}
                                </td>
                                <td>
                                    {{ $item->date_of_purchase }}
                                </td>
                                <td>
                                    {{ $item->purchase_cost }}
                                </td>
                                <td>
                                    {{ $item->condition_descr }}
                                </td>
                                <td>
                                    {{ $item->laden_weight }}
                                </td>
                                <td>
                                    {{ $item->unladen_weight }}
                                </td>
                                <td>
                                    {{ $item->alloted_to }}
                                </td>
                                <td>
                                    {{ $item->alloted_from_date }}
                                </td>
                                <td>
                                    {{ $item->remarks }}
                                </td>
                                <td>
                                    {{ $item->reason_of_rejection }}
                                </td>
                                <td class="text-center">
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
                                <td class="text-center">
                                    <input type="checkbox" class="selected-asset"
                                        data-road-id="{{ $item->vehicle_asset_cd }}" />
                                </td>
                                <th class="text-center">
                                    <form action="{{ route('destroy.road') }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" name="road_id" value="{{ $item->vehicle_asset_cd }}">
                                        <button type="submit" class="border-0 bg-transparent"
                                            onclick="return confirm('Are you sure you want to delete this road?')">
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
                                    <input type="text" id="veh_asset_cd" class="form-control" name="veh_asset_cd"
                                        value="" readonly>
                                    @error('vehicle_regn_no')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="vehicle_regn_no">Reg. No. <span class="star text-danger">*</span></label>
                                    <input type="text" id="vehicle_regn_no" class="form-control"
                                        name="vehicle_regn_no" value="">
                                    @error('vehicle_regn_no')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="vehicle_name">Vehicle Name<span class="star text-danger">*</span></label>
                                    <input type="text" id="vehicle_name" class="form-control" name="vehicle_name"
                                        value="">
                                    @error('vehicle_name')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="chassis_no">Chassis No. <span class="star text-danger">*</span></label>
                                    <input type="text" id="chassis_no" class="form-control" name="chassis_no"
                                        value="">
                                    @error('chassis_no')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="engine_no">Engine No. <span class="star text-danger">*</span></label>
                                    <input type="text" id="engine_no" class="form-control" name="engine_no"
                                        value="">
                                    @error('engine_no')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="vehicle_type">Vehicle Type <span class="star text-danger">*</span></label>
                                    <select class="form-control" id="vehicle_type" name="vehicle_type">
                                        <option value="">Choose one</option>
                                        @foreach ($vehTypes as $vehType)
                                            <option value="{{ $vehType->veh_type_cd }}">
                                                {{ $vehType->veh_type_descr }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-md-3">
                                    <label for="seating_capacity">Seating Capacity <span
                                            class="star text-danger">*</span></label>
                                    <select class="form-control" id="seating_capacity" name="seating_capacity">
                                        <option value="">Choose one</option>
                                        @for ($i = 1; $i <= 60; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('seating_capacity')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="no_of_wheels">Wheel Count <span class="star text-danger">*</span></label>
                                    <select class="form-control" id="no_of_wheels" name="no_of_wheels">
                                        <option value="">Choose one</option>
                                        @for ($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('no_of_wheels')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="maker">Vehicle Maker<span class="star text-danger">*</span></label>
                                    <select class="form-control" id="maker" name="maker">
                                        <option value="">Choose one</option>
                                        @foreach ($vehMakers as $vehMaker)
                                            <option value="">
                                                {{ $vehMaker->maker_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('maker')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="model">Vehicle model<span class="star text-danger">*</span></label>
                                    <input type="text" id="model" class="form-control" name="model"
                                        value="">
                                    @error('model')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="fuel_type">Fuel Type <span class="star text-danger">*</span></label>
                                    <select class="form-control" id="fuel_type" name="fuel_type">
                                        <option value="">Choose one</option>
                                        @foreach ($fuelTypes as $fuelType)
                                            <option value="{{ $fuelType->fuel_type_cd }}">
                                                {{ $fuelType->fuel_type_descr }}</option>
                                        @endforeach
                                    </select>
                                    @error('fuel_type')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="date_of_purchase">Date of Purchase<span
                                            class="star text-danger">*</span></label>
                                    <input type="date" id="date_of_purchase" class="form-control"
                                        name="date_of_purchase" value="">
                                    @error('date_of_purchase')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="purchase_cost">Purchase Cost ( Rs.)<span
                                            class="star text-danger">*</span></label>
                                    <input type="number" step="0.01" placeholder="0.00" id="purchase_cost"
                                        class="form-control" name="purchase_cost" value="">
                                    @error('purchase_cost')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="vehicle_condition">Condition <span
                                            class="star text-danger">*</span></label>
                                    <select class="form-control" id="vehicle_condition" name="vehicle_condition">
                                        <option value="">Choose one</option>
                                        @foreach ($conditions as $condition)
                                            <option value="{{ $condition->condition_cd }}">
                                                {{ $condition->condition_descr }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_condition')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="laden_weight">Laden Weight<span class="star text-danger">*</span></label>
                                    <input type="number" step="0.01" placeholder="0.00" id="laden_weight"
                                        class="form-control" name="laden_weight" value="">
                                    @error('laden_weight')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="unladen_weight">Unladen Weight<span
                                            class="star text-danger">*</span></label>
                                    <input type="number" step="0.01" placeholder="0.00" id="unladen_weight"
                                        class="form-control" name="unladen_weight" value="">
                                    @error('unladen_weight')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="alloted_to">Alloted to<span class="star text-danger">*</span></label>
                                    <input type="text" id="alloted_to" class="form-control" name="alloted_to"
                                        value="">
                                    @error('alloted_to')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="alloted_from">Alloted From</label>
                                    <input type="date" step="0.01" placeholder="0.00" id="alloted_from"
                                        class="form-control" name="alloted_from" value="">
                                    @error('alloted_from')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row form-1-box">
                                <div class="col-md-12">
                                    <label for="remarks">Remarks</label>
                                    <textarea class="form-control text-sm" id="remarks" name="remarks" rows="2"
                                        placeholder="Write bridge remarks..."></textarea>
                                    @error('remarks')
                                        <div class="text-danger mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row form-1-box border py-2">
                                <div class="col-md-12">
                                    <label> Reason Of Rejection <span class="star text-danger">*
                                        </span></label>

                                    <input id="txt_reason_of_rejection" class="form-control"
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
@endpush
@push('scripts')
    <script src="{{ asset('js/mechanical/vehicle/script.js') }}" defer></script>
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script>
        $(function() {
            $("#draft_equipment_details_table").DataTable();
            $("#final_equipment_details_table").DataTable();
        });
    </script>
@endpush
