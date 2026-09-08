@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid text-sm">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('manageRoad') }}">Manage Roads</a>
                </li>
                <li class="breadcrumb-item">Add Surface Types</li>
            </ol>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody py-3">
            @if (session('failed'))
                <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Failed!</strong> {{ session('failed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <x-road-info :roadChainage="$roadChainage" />
            <x-road-tab-navigation />

            <form action="{{ route('road.store-surface-type') }}" method="post" autocomplete="off">
                @csrf
                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2" style="font-size:14px">Surface Types Details</legend>
                    {{-- Hidden field --}}
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <input type="hidden" id="road_system_id" class="form-control" name="road_system_id"
                                value="{{ session('system_id') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="created_office" class="form-control" name="created_office"
                                value="{{ auth()->user()->office }}">
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="road_length" class="form-control" name="road_length"
                                value="{{ session('road_length') }}">
                        </div>
                    </div>

                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="surface_type_cd">Surface Types:<span class="star"></span></label>
                            <select class="form-control" id="surface_type_cd" name="surface_type_cd">
                                <option value="">Choose one</option>
                                @foreach ($surface_types as $surface_type)
                                    <option value="{{ $surface_type->surface_cd }}">
                                        {{ $surface_type->surface_descr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="surface_condition">Surface Condition:<span class="star"></span></label>
                            <select class="form-control" id="surface_condition" name="surface_condition">
                                <option value="">Choose one</option>
                                @foreach ($road_conditions as $road_condition)
                                    <option value="{{ $road_condition->rd_condition_cd }}">
                                        {{ $road_condition->rd_condition_descr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="surface_width">Surface Width:<span class="star"></span></label>
                            <input type="number" step="0.001" id="surface_width" class="form-control"
                                name="surface_width" value="{{ old('surface_width') }}" placeholder="00.000">
                        </div>

                        <div class="col-md-3">
                            <label for="shoulder_width">Shoulder Width:<span class="star"></span></label>
                            <input type="number" step="0.001" id="shoulder_width" class="form-control"
                                name="shoulder_width" value="{{ old('shoulder_width') }}" placeholder="00.000">
                        </div>
                    </div>

                    <div class="row form-1-box border pb-2 my-2" style="background-color: #efeeee;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Layer Section</span>
                        </div>
                        <div class="col-md-3">
                            <label for="sub_base_layer_type">Sub Base Layer Type:<span class="star"></span></label>
                            <select class="form-control" id="sub_base_layer_type" name="sub_base_layer_type">
                                <option value="">Choose one</option>
                                @foreach ($subBaseLayerTypes as $item)
                                    <option value="{{ $item->sub_base_layer_type_cd }}">
                                        {{ $item->sub_base_layer_type_descr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sub_base_layer_thickness">Sub Base Layer Thickness(cm):<span
                                    class="star"></span></label>
                            <input type="number" step="0.001" id="sub_base_layer_thickness" class="form-control"
                                name="sub_base_layer_thickness" value="{{ old('sub_base_layer_thickness') }}"
                                placeholder="00.000">
                        </div>
                        <div class="col-md-3">
                            <label for="base_layer_type">Base Layer Type:<span class="star"></span></label>
                            <select class="form-control" id="base_layer_type" name="base_layer_type">
                                <option value="">Choose one</option>
                                @foreach ($baseLayerTypes as $item)
                                    <option value="{{ $item->base_layer_type_cd }}">
                                        {{ $item->base_layer_type_descr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="base_layer_tickness">Base Layer Tickness(cm):<span class="star"></span></label>
                            <input type="number" step="0.001" id="base_layer_tickness" class="form-control"
                                name="base_layer_tickness" value="{{ old('base_layer_tickness') }}"
                                placeholder="00.000">
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="sub_base_cbr">Sub Base CBR <span class="star"></span></label>
                            <input type="text" id="sub_base_cbr" class="form-control" name="sub_base_cbr"
                                value="{{ old('sub_base_cbr') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="base_cbr">Base CBR <span class="star"></span></label>
                            <input type="text" id="base_cbr" class="form-control" name="base_cbr"
                                value="{{ old('base_cbr') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="sub_base_pi">Sub-Base Point of Intersection <span class="star"></span></label>
                            <input type="text" id="sub_base_pi" class="form-control" name="sub_base_pi"
                                value="{{ old('sub_base_pi') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="base_pi">Base Point of Intersection <span class="star"></span></label>
                            <input type="text" id="base_pi" class="form-control" name="base_pi"
                                value="{{ old('base_pi') }}">
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="construction_year">Construction Year</label>
                            <select class="form-control" id="construction_year" name="construction_year">
                                <option value="">Please Select</option>
                                <?php for ($i = Carbon\Carbon::now()->year; $i >= 1950; $i--) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="pavment_type">Pavment Type:<span class="star"></span></label>
                            <select class="form-control" id="pavment_type" name="pavment_type">
                                <option value="">Choose one</option>
                                @foreach ($pavementTypes as $item)
                                    <option value="{{ $item->pavement_type_cd }}">
                                        {{ $item->pavement_type_descr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="shoulder_type">Shoulder Type:<span class="star"></span></label>
                            <select class="form-control" id="shoulder_type" name="shoulder_type">
                                <option value="">Choose one</option>
                                @foreach ($shoulderTypes as $item)
                                    <option value="{{ $item->shoulder_type_cd }}">
                                        {{ $item->shoulder_type_descr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="land_slide">Land Slide:<span class="star"></span></label>
                            <select class="form-control" id="land_slide" name="land_slide">
                                <option value="">Choose one</option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="border rounded-2 mt-2" id="chainage_container" style="display: none;">
                        <fieldset class="p-2">
                            <legend class="w-auto px-2" style="font-size:13px">Chainage</legend>
                            <div class="row form-1-box">
                                <span class="text-danger text-xs">
                                    <i class="fa fa-info mr-1 text-xs border rounded-circle p-1">
                                    </i>
                                    The chainage
                                    value should lies between {{ $roadChainage->chainage_from }} -
                                    {{ $roadChainage->chainage_to }}
                                </span>
                                <div class="col-md-3">
                                    <label for="from_chainage">
                                        Start Chainage
                                        <span class="star"></span>
                                    </label>

                                    @if ($surfaceTypeChainage == null)
                                        <input type="number" id="from_chainage" class="form-control"
                                            name="from_chainage" value="0" readonly>
                                    @else
                                        <input type="number" id="from_chainage" class="form-control"
                                            name="from_chainage" value={{ $surfaceTypeChainage->end_chainage }} readonly>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label for="to_chainage">End Chainage <span class="star"></span></label>
                                    <input type="number" step="0.001" id="to_chainage" class="form-control"
                                        name="to_chainage" value="{{ old('to_chainage') }}" placeholder="00.000"
                                        required disabled>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="maintenance_type">Maintenance Type <span class="star"></span></label>
                            <select class="form-control" id="maintenance_type" name="maintenance_type">
                                <option value="">Choose one</option>
                                @foreach ($maintenanceTypes as $maintenanceType)
                                    <option value="{{ $maintenanceType->maintenance_type_cd }}">
                                        {{ $maintenanceType->maintenance_type_descr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="last_maintenance_date">Last Maintenance Date <span class="star"></span></label>
                            <input type="date" id="last_maintenance_date" class="form-control"
                                name="last_maintenance_date" value="{{ old('last_maintenance_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="drainage">Drainage Type:<span class="star"></span></label>
                            <select class="form-control" id="drainage" name="drainage">
                                <option value="">Choose one</option>
                                @foreach ($drainageTypes as $drainageType)
                                    <option value="{{ $drainageType->drainage_cd }}">
                                        {{ $drainageType->drainage_descr }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-1-box border pb-2 my-2" id="drainage_container"
                        style="background-color: #efeeee; display: none;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Line Drainage Section</span>
                        </div>
                        <div class="col-md-12">
                            <div class="">
                                <label for="line_drainage_side" class="text-danger">
                                    <i class="fa fa-question-circle"></i>
                                    Are the line drainages on both sides?
                                    <span class="star"></span></label>
                                <input type="radio" id="yes" name="line_drainage_side" value="Y">
                                <label for="yes">Yes</label>
                                <input type="radio" id="no" name="line_drainage_side" value="N">
                                <label for="no">No</label>
                            </div>
                        </div>
                    </div>
                    {{-- for one side drainage --}}
                    <div class="row form-1-box border pb-2 my-2 field_wrapper" id="line_drainage"
                        style="background-color: #efeeee; display: none;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Line Drainage</span>
                        </div>
                        <div class="d-flex flex-wrap">
                            <div class="col-md-3">
                                <label for="start_chainage">Start Chainage (Mtrs):<span class="star"></span></label>
                                <input type="number" step="0.001" id="start_chainage" class="form-control"
                                    name="start_chainage[]" placeholder="00.000">
                            </div>
                            <div class="col-md-3">
                                <label for="end_chainage">End Chainage (Mtrs):<span class="star"></span></label>
                                <input type="number" step="0.001" id="end_chainage" class="form-control"
                                    name="end_chainage[]" placeholder="00.000">
                            </div>
                            {{-- <div class="col-md-3">
                                <label for="drainage_length">Drainage Length (Mtrs):<span class="star"></span></label>
                                <input type="number" step="0.001" id="drainage_length" class="form-control"
                                    name="drainage_length[]" placeholder="00.000">
                            </div> --}}
                            <div class="col-md-3">
                                <label for="type_of_line_drainage">Line Drainage Type:<span class="star"></span></label>
                                <select class="form-control" id="type_of_line_drainage" name="type_of_line_drainage[]">
                                    <option value="">Choose one</option>
                                    @foreach ($lineDrainages as $lineDrainage)
                                        <option value="{{ $lineDrainage->line_drainage_type_cd }}">
                                            {{ $lineDrainage->line_drainage_type_descr }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex justify-content-end align-items-end my-1">
                                <a href="javascript:void(0);" class="add_button btn btn-sm bg-primary" title="Add field">
                                    <i class="fa fa-plus-circle mr-2"></i>Add More
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- for both side drainage --}}
                    {{-- left side --}}
                    <div class="row form-1-box border pb-2 my-2 field_wrapper_left" id="line_drainage_left"
                        style="background-color: #efeeee; display: none;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Line Drainage: Left Side</span>
                        </div>
                        <div class="d-flex flex-wrap">
                            <div class="col-md-3">
                                <label for="start_chainage_left">Start Chainage (Mtrs):<span
                                        class="star"></span></label>
                                <input type="number" step="0.001" id="start_chainage_left" class="form-control"
                                    name="start_chainage_left[]" placeholder="00.000">
                            </div>
                            <div class="col-md-3">
                                <label for="end_chainage_left">End Chainage (Mtrs):<span class="star"></span></label>
                                <input type="number" step="0.001" id="end_chainage_left" class="form-control"
                                    name="end_chainage_left[]" placeholder="00.000">
                            </div>
                            <div class="col-md-3">
                                <label for="type_of_line_drainage_left">Line Drainage Type:<span
                                        class="star"></span></label>
                                <select class="form-control" id="type_of_line_drainage_left"
                                    name="type_of_line_drainage_left[]">
                                    <option value="">Choose one</option>
                                    @foreach ($lineDrainages as $lineDrainage)
                                        <option value="{{ $lineDrainage->line_drainage_type_cd }}">
                                            {{ $lineDrainage->line_drainage_type_descr }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex justify-content-end align-items-end my-1">
                                <a href="javascript:void(0);" class="add_button_left btn btn-sm bg-primary"
                                    title="Add field">
                                    <i class="fa fa-plus-circle mr-2"></i>Add More
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- for both side drainage --}}
                    {{-- right side --}}
                    <div class="row form-1-box border pb-2 my-2 field_wrapper_right" id="line_drainage_right"
                        style="background-color: #efeeee; display: none;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Line Drainage: Right Side</span>
                        </div>
                        <div class="d-flex flex-wrap">
                            <div class="col-md-3">
                                <label for="start_chainage_right">Start Chainage (Mtrs):<span
                                        class="star"></span></label>
                                <input type="number" step="0.001" id="start_chainage_right" class="form-control"
                                    name="start_chainage_right[]" placeholder="00.000">
                            </div>
                            <div class="col-md-3">
                                <label for="end_chainage_right">End Chainage (Mtrs):<span class="star"></span></label>
                                <input type="number" step="0.001" id="end_chainage_right" class="form-control"
                                    name="end_chainage_right[]" placeholder="00.000">
                            </div>
                            <div class="col-md-3">
                                <label for="type_of_line_drainage_right">Line Drainage Type:<span
                                        class="star"></span></label>
                                <select class="form-control" id="type_of_line_drainage_right"
                                    name="type_of_line_drainage_right[]">
                                    <option value="">Choose one</option>
                                    @foreach ($lineDrainages as $lineDrainage)
                                        <option value="{{ $lineDrainage->line_drainage_type_cd }}">
                                            {{ $lineDrainage->line_drainage_type_descr }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex justify-content-end align-items-end my-1">
                                <a href="javascript:void(0);" class="add_button_right btn btn-sm bg-primary"
                                    title="Add field">
                                    <i class="fa fa-plus-circle mr-2"></i>Add More
                                </a>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="text-end">
                    <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2"><i class="fa fa-save"></i>
                        Submit</button>
                    <button type="reset" class="btn btn-info btn-sm rounded-0 mt-2">
                        <i class="fa fa-undo" aria-hidden="true"></i>
                        Reset
                    </button>
                    <button class="btn btn-secondary btn-sm rounded-0 mt-2"><i class="fa fa-backward"></i><a
                            class="text-white" href="{{ route('manageRoad') }} ">
                            Cancel</a></button>
                </div>
            </form>
        </div>
        <!-- table content -->
        <div class="container-fluid mainBody">
            <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT SURFACE TYPE DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border py-2">
                <div class="d-flex text-sm justify-content-end mb-2">
                    <button id="freezeBtn" class="btn btn-sm btn-info rounded-1 text-bold">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Send for finalization
                    </button>
                </div>
                <table class="table-responsive text-xs table table-bordered table-striped user_list" id="surfaceDetails">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">Surface Code</th>
                        <th class="text-center">Surface Types</th>
                        <th class="text-center">Condition</th>
                        <th class="text-center">Surface Width</th>
                        <th class="text-center">Shoulder Width</th>
                        <th class="text-center">Start Chainage</th>
                        <th class="text-center">End Chainage</th>
                        <th class="text-center" style="min-width: 5rem;">Base-Layer Type</th>
                        <th class="text-center" style="min-width: 5rem;">Base-Layer Thickness</th>
                        <th class="text-center" style="min-width: 6rem;">SubBase-Layer Thickness</th>
                        <th class="text-center" style="min-width: 6rem;">SubBase-Layer Thickness</th>
                        <th class="text-center">Pavement Type</th>
                        <th class="text-center">Shoulder Type</th>
                        <th class="text-center">Land Slide</th>
                        <th class="text-center">Construction Year</th>
                        <th class="text-center">Base CBR</th>
                        <th class="text-center">Base PI</th>
                        <th class="text-center" style="min-width: 4rem;">Sub-Base CBR</th>
                        <th class="text-center" style="min-width: 4rem;">Sub-Base PI</th>
                        <th class="text-center">Maintenance Type</th>
                        <th class="text-center">Maintenance Date</th>
                        <th class="text-center">Drainage</th>
                        <th class="text-center">Rejection Reason</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Select</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($surfaceTypeDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->rd_surface_cd }}
                                </td>
                                <td>
                                    {{ $item->surface_descr }}
                                </td>
                                <td>
                                    {{ $item->rd_condition_descr }}
                                </td>
                                <td>
                                    {{ $item->surface_width }}
                                </td>
                                <td>
                                    {{ $item->shoulder_width }}
                                </td>
                                <td>
                                    {{ $item->start_chainage }}
                                </td>
                                <td>
                                    {{ $item->end_chainage }}
                                </td>
                                <td>
                                    {{ $item->base_layer_type_descr }}
                                </td>
                                <td>
                                    {{ $item->base_layer_thickness }}
                                </td>
                                <td>
                                    {{ $item->sub_base_layer_type_descr }}
                                </td>
                                <td>
                                    {{ $item->sub_base_layer_thickness }}
                                </td>
                                <td>
                                    {{ $item->pavement_type_descr }}
                                </td>
                                <td>
                                    {{ $item->shoulder_type_descr }}
                                </td>
                                <td>
                                    @if ($item->land_slide == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->construction_year }}
                                </td>
                                <td>
                                    {{ $item->base_cbr }}
                                </td>
                                <td>
                                    {{ $item->base_pi }}
                                </td>
                                <td>
                                    {{ $item->sub_base_cbr }}
                                </td>
                                <td>
                                    {{ $item->sub_base_pi }}
                                </td>
                                <td>
                                    {{ $item->maintenance_type_descr }}
                                </td>
                                <td>
                                    {{ $item->last_maintenance_date }}
                                </td>
                                <td>
                                    {{ $item->drainage_descr }}
                                </td>
                                <td>
                                    {{ $item->reason_of_rejection }}
                                </td>
                                <td class="text-center">
                                    <a class="text-primary edit" data-toggle="modal"
                                        data-target="#editModal{{ $item->rd_surface_cd }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <td>
                                    <input type="checkbox" class="selected-asset"
                                        data-surface-type="{{ $item->rd_surface_cd }}" />
                                </td>
                            </tr>
                            <?php $i++; ?>

                            <!-- The Edit Modal -->
                            <div class="modal" id="editModal{{ $item->rd_surface_cd }}">
                                <div class="modal-dialog modal-lg text-xs">
                                    <form class="updateSurfaceTypeDetails" id="update_bridge_{{ $item->rd_surface_cd }}"
                                        method="POST" action="{{ route('updateSurfaceTypeDetails') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->rd_surface_cd }}">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header" style="background-color: rgb(240, 240, 240);">
                                                <h5><i class="fas fa-clipboard-list text-dark"></i>
                                                    <strong class="text-md">Update Surface Type
                                                        Details</strong>
                                                </h5>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <div class="row text-xs">

                                                    <div class="col-md-4">
                                                        <label for="">Surface Type <span
                                                                class="star"></span></label>
                                                        <select class="form-control" id=""
                                                            name="surface_type_cd">
                                                            <option value="{{ $item->surface_type_cd }}">
                                                                {{ $item->surface_descr }}</option>
                                                            @foreach ($surface_types as $surface_type)
                                                                @if ($item->surface_type_cd != $surface_type->surface_cd)
                                                                    <option value="{{ $surface_type->surface_cd }}">
                                                                        {{ $surface_type->surface_descr }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="road_condition">Surface Condition <span
                                                                class="star"></span></label>
                                                        <select class="form-control" id="road_condition"
                                                            name="road_condition">
                                                            <option value="{{ $item->surface_condition_cd }}">
                                                                {{ $item->rd_condition_descr }}</option>
                                                            @foreach ($road_conditions as $road_condition)
                                                                @if ($item->surface_condition_cd != $road_condition->rd_condition_cd)
                                                                    <option
                                                                        value="{{ $road_condition->rd_condition_cd }}">
                                                                        {{ $road_condition->rd_condition_descr }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="surface_width">Surface Width <span
                                                                class="star"></span></label>
                                                        <input type="number" step="0.001" id="surface_width"
                                                            class="form-control" name="surface_width"
                                                            value="{{ $item->surface_width }}" placeholder="00.000">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="shoulder_width">Shoulder Width <span
                                                                class="star"></span></label>
                                                        <input type="number" step="0.001" id="shoulder_width"
                                                            class="form-control" name="shoulder_width"
                                                            value="{{ $item->shoulder_width }}" placeholder="00.000">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="from_chainage">
                                                            Start Chainage
                                                            <span class="star"></span>
                                                        </label>
                                                        <input type="number" id="from_chainage" class="form-control"
                                                            name="from_chainage" value="{{ $item->start_chainage }}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="to_chainage">End Chainage <span
                                                                class="star"></span></label>
                                                        <input type="number" step="0.001" id="to_chainage"
                                                            class="form-control" name="to_chainage"
                                                            value="{{ $item->end_chainage }}" placeholder="00.000">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">Base Layer Type <span
                                                                class="star"></span></label>
                                                        <select class="form-control" id=""
                                                            name="base_layer_type">
                                                            <option value="{{ $item->base_layer_type }}">
                                                                {{ $item->base_layer_type_descr }}</option>
                                                            @foreach ($baseLayerTypes as $baseLayer)
                                                                @if ($item->base_layer_type != $baseLayer->base_layer_type_cd)
                                                                    <option value="{{ $baseLayer->base_layer_type_cd }}">
                                                                        {{ $baseLayer->base_layer_type_descr }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="base_layer_tickness">Base Layer
                                                            Tickness(cm) <span class="star"></span></label>
                                                        <input type="number" step="0.001" id="base_layer_tickness"
                                                            class="form-control" name="base_layer_tickness"
                                                            value="{{ $item->base_layer_thickness }}"
                                                            placeholder="00.000">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="">Sub Base Layer Type
                                                            <span class="star"></span></label>
                                                        <select class="form-control" id=""
                                                            name="sub_base_layer_type">
                                                            <option value="{{ $item->sub_base_layer_type }}">
                                                                {{ $item->sub_base_layer_type_descr }}
                                                            </option>
                                                            @foreach ($subBaseLayerTypes as $sblt)
                                                                @if ($item->sub_base_layer_type != $sblt->sub_base_layer_type_cd)
                                                                    <option value="{{ $sblt->sub_base_layer_type_cd }}">
                                                                        {{ $sblt->sub_base_layer_type_descr }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="sub_base_layer_thickness">Sub Base Layer
                                                            Thickness(cm) <span class="star"></span></label>
                                                        <input type="number" step="0.001"
                                                            id="sub_base_layer_thickness" class="form-control"
                                                            name="sub_base_layer_thickness"
                                                            value="{{ $item->sub_base_layer_thickness }}"
                                                            placeholder="00.000">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="">Pavment Type <span
                                                                class="star"></span></label>
                                                        <select class="form-control" id="" name="pavment_type">
                                                            <option value="{{ $item->pavement_type }}">
                                                                {{ $item->pavement_type_descr }}</option>
                                                            @foreach ($pavementTypes as $pavementType)
                                                                @if ($item->pavement_type != $pavementType->pavement_type_cd)
                                                                    <option value="{{ $pavementType->pavement_type_cd }}">
                                                                        {{ $pavementType->pavement_type_descr }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="">Shoulder Type <span
                                                                class="star"></span></label>
                                                        <select class="form-control" id="" name="shoulder_type">
                                                            <option value="{{ $item->shoulder_type }}">
                                                                {{ $item->shoulder_type_descr }}</option>
                                                            @foreach ($shoulderTypes as $shoulderType)
                                                                @if ($item->shoulder_type != $shoulderType->shoulder_type_cd)
                                                                    <option
                                                                        value="{{ $shoulderType->shoulder_type_cd }}">
                                                                        {{ $shoulderType->shoulder_type_descr }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="">Land Slide <span
                                                                class="star"></span></label>
                                                        <select class="form-control" id="" name="land_slide">
                                                            <option value="">Choose one</option>
                                                            <option value="Y">Yes</option>
                                                            <option value="N">No</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">Construction Year <span
                                                                class="star"></span></label>
                                                        <input type="number" id="" class="form-control"
                                                            name="construction_year"
                                                            value="{{ $item->construction_year }}" placeholder="YYYY"
                                                            maxlength="4">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="treatment_type">Maintenance Type <span
                                                                class="star"></span></label>
                                                        <select class="form-control" id="treatment_type"
                                                            name="treatment_type">
                                                            <option value="{{ $item->pavement_type }}">
                                                                {{ $item->pavement_type_descr }}</option>
                                                            @foreach ($pavementTypes as $pavementType)
                                                                @if ($item->pavement_type != $pavementType->pavement_type_cd)
                                                                    <option
                                                                        value="{{ $pavementType->pavement_type_cd }}">
                                                                        {{ $pavementType->pavement_type_descr }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="base_cbr">Base CBR <span
                                                                class="star"></span></label>
                                                        <input type="text" id="base_cbr" class="form-control"
                                                            name="base_cbr" value="{{ $item->base_cbr }}">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="base_pi">Base PI <span class="star"></span></label>
                                                        <input type="text" id="base_pi" class="form-control"
                                                            name="base_pi" value="{{ $item->base_pi }}">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="sub_base_cbr">Sub Base CBR <span
                                                                class="star"></span></label>
                                                        <input type="text" id="sub_base_cbr" class="form-control"
                                                            name="sub_base_cbr" value="{{ $item->sub_base_cbr }}">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="sub_base_pi">Sub Base PI <span
                                                                class="star"></span></label>
                                                        <input type="text" id="sub_base_pi" class="form-control"
                                                            name="sub_base_pi" value="{{ $item->sub_base_pi }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">Drainage<span class="star"></span></label>
                                                        <input type="text" id="" class="form-control"
                                                            name="drainage" value="{{ $item->drainage }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="submit"
                                                    class="btn btn-sm btn-success modalUpBtn">Update</button>
                                                <a class="btn btn-sm modalClose btn-danger" data-dismiss="modal">CLOSE</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <x-success-modal />
    <x-warning-modal />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/road/surfaceType/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/road/surfaceType/script.js') }}" defer></script>
    <script>
        $(function() {
            $("#surfaceDetails").DataTable({}).buttons().container().appendTo(
                '#surfaceDetails_wrapper .col-md-11:eq(1)');
        });
    </script>
    <script>
        $(document).ready(function() {
            // script for one sided drainage
            var maxField = 10; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            var fieldHTML =
                '<div class="d-flex flex-wrap">' +
                '<div class="col-md-3">' +
                '<label for="start_chainage">Start Chainage (Mtrs):<span class="star"></span></label>' +
                '<input type="number" step="0.001" class="form-control" id="start_chainage" name="start_chainage[]" placeholder="00.000">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="end_chainage">End Chainage (Mtrs):<span class="star"></span></label>' +
                '<input type="number" step="0.001" class="form-control" id="end_chainage" name="end_chainage[]" placeholder="00.000">' +
                '</div>' +
                // '<div class="col-md-3">' +
                // '<label for="drainage_length">Drainage Length (Mtrs):<span class="star"></span></label>' +
                // '<input type="number" step="0.001" class="form-control" id="drainage_length" name="drainage_length[]" placeholder="00.000">' +
                // '</div>' +
                '<div class="col-md-3">' +
                '<label for="type_of_line_drainage">Line Drainage Type:<span class="star"></span></label>' +
                '<select class="form-control" id="type_of_line_drainage" name="type_of_line_drainage[]">' +
                '<option value="">Choose one</option>' +
                '@foreach ($lineDrainages as $lineDrainage)' +
                '<option value="{{ $lineDrainage->line_drainage_type_cd }}">' +
                '{{ $lineDrainage->line_drainage_type_descr }}' +
                '</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="col-md-3 d-flex justify-content-end align-items-end my-1">' +
                '<a href="javascript:void(0);" class="remove_button btn btn-sm bg-danger"><i class="fa fa-minus-circle mr-2"></i>Remove Row</a>' +
                '</div>' +
                '</div>';

            var x = 1; //Initial field counter is 1

            // Once add button is clicked
            $(addButton).on('click', function() {
                //Check maximum number of input fields
                if (x < maxField) {
                    x++; //Increase field counter
                    $(wrapper).append(fieldHTML); //Add field html
                } else {
                    alert('A maximum of ' + maxField + ' fields are allowed to be added. ');
                }
            });

            // Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove(); //Remove field html
                x--; //Decrease field counter
            });

            // script for both side drainage
            // left side
            var maxFieldLeft = 10; //Input fields increment limitation
            var LeftAddButton = $('.add_button_left'); //Add button selector
            var LeftWrapper = $('.field_wrapper_left'); //Input field LeftWrapper
            var leftHTMLField =
                '<div class="d-flex flex-wrap">' +
                '<div class="col-md-3">' +
                '<label for="start_chainage_left">Start Chainage (Mtrs):<span class="star"></span></label>' +
                '<input type="number" step="0.001" class="form-control" id="start_chainage_left" name="start_chainage_left[]" placeholder="00.000">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="end_chainage_left">End Chainage (Mtrs):<span class="star"></span></label>' +
                '<input type="number" step="0.001" class="form-control" id="end_chainage_left" name="end_chainage_left[]" placeholder="00.000">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="type_of_line_drainage_left">Line Drainage Type:<span class="star"></span></label>' +
                '<select class="form-control" id="type_of_line_drainage_left" name="type_of_line_drainage_left[]">' +
                '<option value="">Choose one</option>' +
                '@foreach ($lineDrainages as $lineDrainage)' +
                '<option value="{{ $lineDrainage->line_drainage_type_cd }}">' +
                '{{ $lineDrainage->line_drainage_type_descr }}' +
                '</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="col-md-3 d-flex justify-content-end align-items-end my-1">' +
                '<a href="javascript:void(0);" class="remove_button_left btn btn-sm bg-danger"><i class="fa fa-minus-circle mr-2"></i>Remove Row</a>' +
                '</div>' +
                '</div>';

            var y = 1; //Initial field counter is 1

            // Once add button is clicked
            $(LeftAddButton).on('click', function() {
                //Check maximum number of input fields
                if (y < maxFieldLeft) {
                    y++; //Increase field counter
                    $(LeftWrapper).append(leftHTMLField); //Add field html
                } else {
                    alert('A maximum of ' + maxFieldLeft + ' fields are allowed to be added. ');
                }
            });

            // Once remove button is clicked
            $(LeftWrapper).on('click', '.remove_button_left', function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove(); //Remove field html
                y--; //Decrease field counter
            });

            // right side
            var maxFieldRight = 10; //Input fields increment limitation
            var rightAddButton = $('.add_button_right'); //Add button selector
            var rightWrapper = $('.field_wrapper_right'); //Input field rightWrapper
            var rightHTMLField =
                '<div class="d-flex flex-wrap">' +
                '<div class="col-md-3">' +
                '<label for="start_chainage_right">Start Chainage (Mtrs):<span class="star"></span></label>' +
                '<input type="number" step="0.001" class="form-control" id="start_chainage_right" name="start_chainage_right[]" placeholder="00.000">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="end_chainage_right">End Chainage (Mtrs):<span class="star"></span></label>' +
                '<input type="number" step="0.001" class="form-control" id="end_chainage_right" name="end_chainage_right[]" placeholder="00.000">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label for="type_of_line_drainage_right">Line Drainage Type:<span class="star"></span></label>' +
                '<select class="form-control" id="type_of_line_drainage_right" name="type_of_line_drainage_right[]">' +
                '<option value="">Choose one</option>' +
                '@foreach ($lineDrainages as $lineDrainage)' +
                '<option value="{{ $lineDrainage->line_drainage_type_cd }}">' +
                '{{ $lineDrainage->line_drainage_type_descr }}' +
                '</option>' +
                '@endforeach' +
                '</select>' +
                '</div>' +
                '<div class="col-md-3 d-flex justify-content-end align-items-end my-1">' +
                '<a href="javascript:void(0);" class="remove_button_right btn btn-sm bg-danger"><i class="fa fa-minus-circle mr-2"></i>Remove Row</a>' +
                '</div>' +
                '</div>';

            var z = 1; //Initial field counter is 1

            // Once add button is clicked
            $(rightAddButton).on('click', function() {
                //Check maximum number of input fields
                if (z < maxFieldRight) {
                    z++; //Increase field counter
                    $(rightWrapper).append(rightHTMLField); //Add field html
                } else {
                    alert('A maximum of ' + maxFieldRight + ' fields are allowed to be added. ');
                }
            });

            // Once remove button is clicked
            $(rightWrapper).on('click', '.remove_button_right', function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove(); //Remove field html
                z--; //Decrease field counter
            });
        })
    </script>
@endpush
