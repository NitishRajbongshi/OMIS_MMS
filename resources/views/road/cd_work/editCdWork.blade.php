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
                <li class="breadcrumb-item">CD Works</li>
            </ol>
        </div>
    </div>

    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase text-md text-primary font-bold" id="diseaseWiseTitle">Map to
                        show culvert location under Nagaland PWD R&B
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="p-3 row justify-content-center align-item-center">
                    <div class="col-12 col-md-9">
                        <div class="modal-body modal-dialog-centered" id='map' style='width: 100%; height: 500px;'>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div style="display: none;" class="alertMgs alert alert-danger" role="alert">
                            Culvert coordinates not found!
                        </div>
                        <div>
                            <h6 class="border-bottom pb-2">Culvert Details</h6>
                        </div>
                        <div class="building_details_container">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody py-3">
            @if (session('failed'))
                <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa fa-info" aria-hidden="true"></i>
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

            <x-road-info :roadChainage="$roadChainage" />
            <x-road-tab-navigation />
            {{-- modified by Pulak 30-04-26 --}}
            @if ($culvert_id)
                <div class="alert alert-info" id="editModeAlert">
                </div>
            @endif
            {{-- modified by Pulak 30-04-26 --}}


            <form action="{{ route('updateCDWorks', $culvert_id) }}" id="cd_work_form" method="post" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!--modified by Pulak-- 29-04-2026-->
                <input type="hidden" name="culvert_no" id="culvert_no" value="">
                <!--modification end by Pulak-- 29-04-2026-->
                <!-- saiful # 21-04-2026 # Start -->
                <input type="hidden" id="hdn_asset_plan_id" name="hdn_asset_plan_id" value="{{ $assetPlanId}}" />
                <input type="hidden" id="hdn_redefine_asset_from_project" name="hdn_redefine_asset_from_project"
                    value="{{ $redefineAssetFromProject }}" />
                <!-- saiful # 21-04-2026 # End -->
                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2" style="font-size:14px; margin-bottom: 15px;">CD Works Details</legend>
                    <div style="line-height: 2px;">
                        <p class="text-info text-sm">Completion of fields indicated by an asterisk (
                            <span class="text-danger text-bold">*</span> ) is mandatory.
                        </p><br>
                        <p class="text-info text-sm">Decimal inputs are accepted with a precision of up to three decimal
                            places. </p>
                    </div>

                    {{-- Hidden field --}}
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <input type="hidden" id="road_system_id" class="form-control form-control-sm"
                                name="road_system_id" value="{{ session('system_id') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="road_start_chainage" class="form-control form-control-sm"
                                name="road_start_chainage" value="{{ $roadChainage->chainage_from }}">
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="road_end_chainage" class="form-control form-control-sm"
                                name="road_end_chainage" value="{{ $roadChainage->chainage_to }}">
                        </div>
                    </div>

                    <div class="row form-1-box">
                        <div class="col-md-3 myTooltip">
                            <label for="chainage">Culvert Chainage: <span class="star text-danger">*</span></label>
                            <div class="tooltiptext">
                                <i class="fa fa-info-circle me-1"></i>
                                Chainage should between {{ $roadChainage->chainage_from }}
                                - {{ $roadChainage->chainage_to }}
                            </div>
                            <input type="number" step="0.001" placeholder="0.000" id="chainage"
                                class="form-control form-control-sm" name="chainage" value="{{ old('chainage') }}"
                                oninput="restrictDecimalPoints(event)" />
                            @error('chainage')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="culvert_type">Culvert Type: <span class="star text-danger">*</span></label>
                            <select class="form-control form-control-sm" id="culvert_type" name="culvert_type">
                                <option value="">Choose one</option>
                                @foreach ($cdWorkTypes as $item)
                                    <option value="{{ $item->cdwork_cd }}">{{ $item->cdwoerk_descr }}
                                    </option>
                                @endforeach
                            </select>

                            @error('culvert_type')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <x-get-coordinate.component />
                    <div class="row form-1-box" id="common_cdworks_fields" style="display: none;">
                        <div class="col-md-3">
                            <label for="discharge">Discharge:</label>
                            <input type="number" min="0" placeholder="0.000" step="0.001" id="discharge"
                                class="form-control form-control-sm" name="discharge" value="{{ old('discharge') }}"
                                oninput="restrictDecimalPoints(event)">
                            @error('discharge')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="year_of_contruction">Year of Construction:</label>
                            <select class="form-control form-control-sm" id="year_of_contruction"
                                name="year_of_contruction">
                                <option value="" disable selected hidden>Please Select</option>
                            </select>
                            @error('year_of_contruction')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="year_of_rehabilitation">Year of Rehabilitation:</label>
                            <select class="form-control form-control-sm" id="year_of_rehabilitation"
                                name="year_of_rehabilitation">
                                <option value="" disable selected hidden>Please Select</option>
                            </select>
                            @error('year_of_rehabilitation')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="condition">Condition:</label>
                            <select class="form-control form-control-sm" id="condition" name="condition">
                                <option value="">Choose one</option>
                                @foreach ($roadConditions as $item)
                                    <option value="{{ $item->rd_condition_cd }}">
                                        {{ $item->rd_condition_descr }}
                                    </option>
                                @endforeach
                            </select>

                            @error('condition')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- start of dynamic div --}}
                    <div id="boxArcCulvert" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Box/Arc Culvert</span>
                            </div>
                            <div class="col-md-3">
                                <label for="cell_no">No. of Cell:</label>
                                <input type="number" min="0" id="cell_no" class="form-control form-control-sm"
                                    name="cell_no" value="{{ old('cell_no') }}" placeholder="0">
                                @error('cell_no')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="length_span_bxc">Each Cell Length/Span (Mtrs):</label>
                                <input type="number" min="0" step="0.001" id="length_span_bxc"
                                    class="form-control form-control-sm" name="length_span_bxc"
                                    value="{{ old('length_span_bxc') }}" placeholder="0.000"
                                    oninput="restrictDecimalPoints(event)">
                                @error('length_span_bxc')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="each_cell_width">Each Cell Width (Mtrs): </label>
                                <input type="number" min="0" step="0.001" id="each_cell_width"
                                    class="form-control form-control-sm" name="each_cell_width"
                                    value="{{ old('each_cell_width') }}" placeholder="0.000"
                                    oninput="restrictDecimalPoints(event)" />

                                @error('each_cell_width')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="each_cell_height">Each Cell Height (Mtrs): </label>
                                <input type="number" min="0" step="0.001" id="each_cell_height"
                                    class="form-control form-control-sm" name="each_cell_height"
                                    value="{{ old('each_cell_height') }}" placeholder="0.000"
                                    oninput="restrictDecimalPoints(event)" />

                                @error('each_cell_height')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="thickness_of_side_wall">Thickness of Side Wall (Mtrs):</label>
                                <input type="number" min="0" step="0.001" id="thickness_of_side_wall"
                                    class="form-control form-control-sm" name="thickness_of_side_wall"
                                    value="{{ old('thickness_of_side_wall') }}" placeholder="0.000"
                                    oninput="restrictDecimalPoints(event)" />
                                @error('thickness_of_side_wall')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="thickness_of_top_slab">Thickness of Top Slab (Mtrs):</label>
                                <input type="number" min="0" step="0.001" id="thickness_of_top_slab"
                                    class="form-control form-control-sm" name="thickness_of_top_slab"
                                    value="{{ old('thickness_of_top_slab') }}" placeholder="0.000"
                                    oninput="restrictDecimalPoints(event)" />
                                @error('thickness_of_top_slab')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="thickness_of_bottom_slab">Thickness of Bottom Slab (Mtrs):</label>
                                <input type="number" min="0" placeholder="0.000" step="0.001" id="thickness_of_bottom_slab"
                                    class="form-control form-control-sm" name="thickness_of_bottom_slab"
                                    value="{{ old('thickness_of_bottom_slab') }}" oninput="restrictDecimalPoints(event)" />
                                @error('thickness_of_bottom_slab')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="box_construction_material">Construction Materials:</label>
                                <select class="form-control form-control-sm" id="box_construction_material"
                                    name="box_construction_material">
                                    <option value="">Choose one</option>
                                    @foreach ($constructionMaterials as $constructionMaterial)
                                        <option value="{{ $constructionMaterial->const_material_type_cd }}">
                                            {{ $constructionMaterial->const_material_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row form-1-box border mt-2">
                            <div class="col-md-12 pt-2" style="background-color: #efeeee;">
                                <fieldset class="">
                                    <legend class="w-auto px-2" style="font-size:13px ">
                                        Wing Wall
                                    </legend>
                                    <div>
                                        <label for="wing_wall">
                                            Does the culvert have a wing wall?
                                        </label>
                                        <input type="radio" id="yes" name="wing_wall" value="Y">
                                        <label for="yes">Yes</label>
                                        <input type="radio" id="no" name="wing_wall" value="N" checked>
                                        <label for="no">No</label>
                                    </div>
                                    <div id="wingWallDimensionContainer" style="display: none;">
                                        <label for="is_same_wing_wall" class="text-danger">
                                            Are all the wing walls the same dimension?
                                        </label>
                                        <input type="radio" id="yes" name="is_same_wing_wall" value="Y">
                                        <label for="yes">Yes</label>
                                        <input type="radio" id="no" name="is_same_wing_wall" value="N">
                                        <label for="no">No</label>
                                    </div>
                                </fieldset>
                            </div>
                            <div id="box_wing_wall_fields" style="display: none;" class="col-md-12">
                                <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                    <div class="col-md-3">
                                        <label for="wing_wall_type_box">Wing Wall Type </label>
                                        <select class="form-control form-control-sm" id="wing_wall_type_box"
                                            name="wing_wall_type_box">
                                            <option value="">Choose one</option>
                                            @foreach ($wingWallTypes as $wingWallType)
                                                <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                    {{ $wingWallType->wing_wall_type_descr }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="length">Length (Mtrs)</label>
                                        <input type="number" min='0' step="0.001" placeholder="0.000" {{-- modified by Pulak
                                            30-04-26 --}} id="length_box" class="form-control form-control-sm" name="length"
                                            disabled value="{{ old('length') }}" oninput="restrictDecimalPoints(event)">

                                        @error('length')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="top_width">Top Width (Mtrs)</label>
                                        <input type="number" min='0' step="0.001" placeholder="0.000" {{-- modified by Pulak
                                            30-04-26 --}} id="top_width_box" class="form-control form-control-sm"
                                            name="top_width" disabled value="{{ old('top_width') }}"
                                            oninput="restrictDecimalPoints(event)">

                                        @error('top_width')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="bottom_width">Bottom Width (Mtrs)</label>
                                        <input type="number" min='0' step="0.001" placeholder="0.000" {{-- modified by Pulak
                                            30-04-26 --}} id="bottom_width_box" class="form-control form-control-sm"
                                            name="bottom_width" disabled value="{{ old('bottom_width') }}"
                                            oninput="restrictDecimalPoints(event)">

                                        @error('bottom_width')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="height1">Height 1 (Mtrs)</label>
                                        <input type="number" min='0' step="0.001" placeholder="0.000" {{-- modified by Pulak
                                            30-04-26 --}} id="height1_box" class="form-control form-control-sm"
                                            name="height1" disabled value="{{ old('height1') }}"
                                            oninput="restrictDecimalPoints(event)">

                                        @error('height1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="height2">Height 2 (Mtrs)</label>
                                        <input type="number" min='0' step="0.001" placeholder="0.000" {{-- modified by Pulak
                                            30-04-26 --}} id="height2_box" class="form-control form-control-sm"
                                            name="height2" disabled value="{{ old('height2') }}"
                                            oninput="restrictDecimalPoints(event)" wire:>

                                        @error('height2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="slope">Slope (Mtrs)</label>
                                        <input type="number" min='0' step="0.001" placeholder="0.000" {{-- modified by Pulak
                                            30-04-26 --}} id="slope_box" class="form-control form-control-sm" name="slope"
                                            disabled value="{{ old('slope') }}" oninput="restrictDecimalPoints(event)">

                                        @error('slope')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3" id="angleContainer" style="display: none;">
                                        <label for="angle_box">Angle (Mtrs)</label>
                                        <input type="number" min='0' step="0.001" placeholder="0.000" id="angle_box"
                                            class="form-control form-control-sm" name="angle_box" disabled
                                            value="{{ old('angle_box') }}" oninput="restrictDecimalPoints(event)">

                                        @error('angle_box')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3" style="display: none;" id="radiusContainer">
                                        <label for="radius_box">Radius (Mtrs)</label>
                                        <input type="number" min='0' step="0.001" placeholder="0.000" id="radius_box"
                                            class="form-control form-control-sm" name="radius_box" disabled
                                            value="{{ old('radius_box') }}" oninput="restrictDecimalPoints(event)">

                                        @error('radius_box')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div id="box_wing_wall_fields_multiple" style="display: none;" class="col-md-12">
                                <div class="row form-1-box pb-2 radius-angle-container" data-section="1"
                                    style="background-color: #efeeee;">
                                    <div class="col-12">
                                        <span class="text-xs text-danger fw-bold">
                                            <i class="fa fa-caret-right mr-1"></i>
                                            Wing wall 1
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="box_wing_wall_type_1">Wing Wall Type </label>
                                        <select class="form-control form-control-sm wing-wall-type"
                                            id="box_wing_wall_type_1" name="box_wing_wall_type_1" data-section="1">
                                            <option value="">Choose one</option>
                                            @foreach ($wingWallTypes as $wingWallType)
                                                <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                    {{ $wingWallType->wing_wall_type_descr }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="length_1">Length (Mtrs)</label>
                                        {{-- modified by Pulak 2-05-26 --}}
                                        <input type="number" step="0.001" placeholder="0.000" id="length_box_1"
                                            class="form-control form-control-sm" name="length_1" disabled
                                            value="{{ old('length_1') }}" oninput="restrictDecimalPoints(event)">

                                        @error('length_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="top_width_1">Top Width (Mtrs)</label>
                                        {{-- modified by Pulak 2-05-26 --}}
                                        <input type="number" step="0.001" placeholder="0.000" id="top_width_box_1"
                                            class="form-control form-control-sm" name="top_width_1" disabled
                                            value="{{ old('top_width_1') }}" oninput="restrictDecimalPoints(event)">

                                        @error('top_width_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="bottom_width_1">Bottom Width (Mtrs)</label>
                                        {{-- modified by Pulak 2-05-26 --}}
                                        <input type="number" step="0.001" placeholder="0.000" id="bottom_width_box_1"
                                            class="form-control form-control-sm" name="bottom_width_1" disabled
                                            value="{{ old('bottom_width_1') }}" oninput="restrictDecimalPoints(event)">

                                        @error('bottom_width_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="height1_1">Height 1 (Mtrs)</label>
                                        {{-- modified by Pulak 2-05-26 --}}
                                        <input type="number" step="0.001" placeholder="0.000" id="height1_box_1"
                                            class="form-control form-control-sm" name="height1_1" disabled
                                            value="{{ old('height1_1') }}" oninput="restrictDecimalPoints(event)">

                                        @error('height1_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="height2_1">Height 2 (Mtrs)</label>
                                        {{-- modified by Pulak 2-05-26 --}}
                                        <input type="number" step="0.001" placeholder="0.000" id="height2_box_1"
                                            class="form-control form-control-sm" name="height2_1" disabled
                                            value="{{ old('height2_1') }}" oninput="restrictDecimalPoints(event)">

                                        @error('height2_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="slope_1">Slope (Mtrs)</label>
                                        {{-- modified by Pulak 2-05-26 --}}
                                        <input type="number" step="0.001" placeholder="0.000" id="slope_box_1"
                                            class="form-control form-control-sm" name="slope_1" disabled
                                            value="{{ old('slope_1') }}" oninput="restrictDecimalPoints(event)">

                                        @error('slope_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3" id="boxAngleContainer_1" style="display: none;">
                                        <label for="box_angle_1">Angle (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="box_angle_1"
                                            class="form-control form-control-sm" value="{{ old('box_angle_1') }}"
                                            data-section="1" name="box_angle_1" disabled
                                            oninput="restrictDecimalPoints(event)">

                                        @error('box_angle_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3" id="boxRadiusContainer_1" style="display: none;">
                                        <label for="box_radius_1">Radius (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="box_radius_1"
                                            class="form-control form-control-sm" data-section="1" name="box_radius_1"
                                            value="{{ old('box_radius_1') }}" disabled
                                            oninput="restrictDecimalPoints(event)">

                                        @error('box_radius_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="row form-1-box pb-2 radius-angle-container" data-section="2"
                                    style="background-color: #efeeee;">
                                    <div class="col-12">
                                        <span class="text-xs text-danger fw-bold">
                                            <i class="fa fa-caret-right mr-1"></i>
                                            Wing wall 2
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="box_wing_wall_type_2">Wing Wall Type </label>
                                        <select class="form-control form-control-sm wing-wall-type" data-section="2"
                                            id="box_wing_wall_type_2" name="box_wing_wall_type_2">
                                            <option value="">Choose one</option>
                                            @foreach ($wingWallTypes as $wingWallType)
                                                <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                    {{ $wingWallType->wing_wall_type_descr }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="length_box_2">Length (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="length_box_2"
                                            class="form-control form-control-sm" name="length_2" disabled
                                            value="{{ old('length_box_2') }}" oninput="restrictDecimalPoints(event)">

                                        @error('length_box_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="top_width_box_2">Top Width (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="top_width_box_2"
                                            class="form-control form-control-sm" name="top_width_2" disabled
                                            value="{{ old('top_width_box_2') }}" oninput="restrictDecimalPoints(event)">

                                        @error('top_width_box_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="bottom_width_box_2">Bottom Width (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="bottom_width_box_2"
                                            class="form-control form-control-sm" name="bottom_width_2" disabled
                                            value="{{ old('bottom_width_box_2') }}" oninput="restrictDecimalPoints(event)">

                                        @error('bottom_width_box_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="height1_box_2">Height 1 (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="height1_box_2"
                                            class="form-control form-control-sm" name="height1_2" disabled
                                            value="{{ old('height1_2') }}" oninput="restrictDecimalPoints(event)">

                                        @error('height1_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="height2_box_2">Height 2 (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="height2_box_2"
                                            class="form-control form-control-sm" name="height2_2" disabled
                                            value="{{ old('height2_2') }}" oninput="restrictDecimalPoints(event)">

                                        @error('height2_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <label for="slope_box_2">Slope (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="slope_box_2"
                                            class="form-control form-control-sm" name="slope_2" disabled
                                            value="{{ old('slope_2') }}" oninput="restrictDecimalPoints(event)">

                                        @error('slope_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3" id="boxAngleContainer_2" style="display: none;">
                                        <label for="box_angle_2">Angle (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="box_angle_2"
                                            class="form-control form-control-sm" value="{{ old('box_angle_2') }}"
                                            data-section="2" name="box_angle_2" disabled
                                            oninput="restrictDecimalPoints(event)">

                                        @error('box_angle_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3" id="boxRadiusContainer_2" style="display: none;">
                                        <label for="box_radius_2">Radius (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="box_radius_2"
                                            class="form-control form-control-sm" value="{{ old('box_radius_2') }}"
                                            data-section="2" name="box_radius_2" disabled
                                            oninput="restrictDecimalPoints(event)">

                                        @error('box_radius_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row form-1-box pb-2 radius-angle-container" data-section="3"
                                    style="background-color: #efeeee;">
                                    <div class="col-12">
                                        <span class="text-xs text-danger fw-bold">
                                            <i class="fa fa-caret-right mr-1"></i>
                                            Wing wall 3
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="box_wing_wall_type_3">Wing Wall Type </label>
                                        <select class="form-control form-control-sm wing-wall-type" data-section="3"
                                            id="box_wing_wall_type_3" name="box_wing_wall_type_3">
                                            <option value="">Choose one</option>
                                            @foreach ($wingWallTypes as $wingWallType)
                                                <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                    {{ $wingWallType->wing_wall_type_descr }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <label for="length_box_3">Length (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="length_box_3"
                                            class="form-control form-control-sm" name="length_3" disabled
                                            value="{{ old('length_3') }}" oninput="restrictDecimalPoints(event)">

                                        @error('length_3')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <label for="top_width_box_3">Top Width (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="top_width_box_3"
                                            class="form-control form-control-sm" name="top_width_3" disabled
                                            value="{{ old('top_width_3') }}" oninput="restrictDecimalPoints(event)">

                                        @error('top_width_3')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <label for="bottom_width_3">Bottom Width (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="bottom_width_box_3"
                                            class="form-control form-control-sm" name="bottom_width_3" disabled
                                            value="{{ old('bottom_width_3') }}" oninput="restrictDecimalPoints(event)">

                                        @error('bottom_width_3')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <label for="height1_box_3">Height 1 (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="height1_box_3"
                                            class="form-control form-control-sm" name="height1_3" disabled
                                            value="{{ old('height1_3') }}" oninput="restrictDecimalPoints(event)">

                                        @error('height1_3')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <label for="height2_box_3">Height 2 (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="height2_box_3"
                                            class="form-control form-control-sm" name="height2_3" disabled
                                            value="{{ old('height2_3') }}" oninput="restrictDecimalPoints(event)">

                                        @error('height2_3')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <label for="slope_box_3">Slope (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="slope_box_3"
                                            class="form-control form-control-sm" name="slope_3" disabled
                                            value="{{ old('slope_3') }}" oninput="restrictDecimalPoints(event)">

                                        @error('slope_3')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3" id="boxAngleContainer_3" style="display: none;">
                                        <label for="box_angle_3">Angle (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="box_angle_3"
                                            class="form-control form-control-sm" value="{{ old('box_angle_3') }}"
                                            data-section="3" name="box_angle_3" disabled
                                            oninput="restrictDecimalPoints(event)">

                                        @error('box_angle_3')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3" id="boxRadiusContainer_3" style="display: none;">
                                        <label for="box_radius_3">Radius (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="box_radius_3"
                                            class="form-control form-control-sm" value="{{ old('box_radius_3') }}"
                                            data-section="3" name="box_radius_3" disabled
                                            oninput="restrictDecimalPoints(event)">

                                        @error('box_radius_3')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="row form-1-box pb-2" data-section="4" style="background-color: #efeeee;">
                                    <div class="col-12">
                                        <span class="text-xs text-danger fw-bold">
                                            <i class="fa fa-caret-right mr-1"></i>
                                            Wing wall 4
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="box_wing_wall_type_4">Wing Wall Type:</label>
                                        <select class="form-control form-control-sm wing-wall-type" data-section="4"
                                            id="box_wing_wall_type_4" name="box_wing_wall_type_4">
                                            <option value="">Choose one</option>
                                            @foreach ($wingWallTypes as $wingWallType)
                                                <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                    {{ $wingWallType->wing_wall_type_descr }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <label for="length_box_4">Length (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="length_box_4"
                                            class="form-control form-control-sm" name="length_4" disabled
                                            value="{{ old('length_4') }}" oninput="restrictDecimalPoints(event)">

                                        @error('length_4')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <label for="top_width_box_4">Top Width (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="top_width_box_4"
                                            class="form-control form-control-sm" name="top_width_4" disabled
                                            value="{{ old('top_width_4') }}" oninput="restrictDecimalPoints(event)">

                                        @error('top_width_4')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <label for="bottom_width_box_4">Bottom Width (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="bottom_width_box_4"
                                            class="form-control form-control-sm" name="bottom_width_4" disabled
                                            value="{{ old('bottom_width_4') }}" oninput="restrictDecimalPoints(event)">

                                        @error('bottom_width_4')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="height1_box_4">Height 1 (Mtrs)</label>
                                        {{-- -modified by pulak 02-05-26 --}}
                                        <input type="number" step="0.001" placeholder="0.000" id="height1_box_4"
                                            class="form-control form-control-sm" name="height1_4" disabled
                                            value="{{ old('height1_4') }}" oninput="restrictDecimalPoints(event)">

                                        @error('height1_4')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        {{-- modified by Pulak 02-05-26 --}}
                                        <label for="height2_box_4">Height 2 (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="height2_box_4"
                                            class="form-control form-control-sm" name="height2_4" disabled
                                            value="{{ old('height2_4') }}" oninput="restrictDecimalPoints(event)">

                                        @error('height2_4')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        {{-- modified by Pulak 02-05-26 --}}
                                        <label for="slope_box_4">Slope (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="slope_box_4"
                                            class="form-control form-control-sm" name="slope_4" disabled
                                            value="{{ old('slope_4') }}" oninput="restrictDecimalPoints(event)">

                                        @error('slope_4')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3" id="boxAngleContainer_4" style="display: none;">
                                        <label for="box_angle_4">Angle (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="box_angle_4"
                                            class="form-control form-control-sm" data-section="3" name="box_angle_4"
                                            disabled value="{{ old('box_angle_4') }}"
                                            oninput="restrictDecimalPoints(event)">

                                        @error('box_angle_4')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3" id="boxRadiusContainer_4" style="display: none;">
                                        <label for="box_radius_4">Radius (Mtrs)</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="box_radius_4"
                                            class="form-control form-control-sm" data-section="3" name="box_radius_4"
                                            disabled value="{{ old('box_radius_4') }}"
                                            oninput="restrictDecimalPoints(event)">

                                        @error('box_radius_4')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="humePipeCulvert" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Hume/Pipe Culvert</span>
                            </div>
                            <div class="col-md-3">
                                <label for="no_of_rows">No of rows </label>
                                <input type="number" id="no_of_rows" class="form-control form-control-sm" name="no_of_rows"
                                    value="{{ old('no_of_rows') }}" />

                                @error('no_of_rows')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="cd_cussion">Cussion (Mtrs) </label>
                                <input type="number" step="0.001" id="cd_cussion" class="form-control form-control-sm"
                                    name="cd_cussion" value="{{ old('cd_cussion') }}" placeholder="0.000"
                                    placeholder="0.000" oninput="restrictDecimalPoints(event)" />

                                @error('cd_cussion')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="pipe_diameter">Pipe Diameter </label>
                                <input type="number" step="0.001" id="pipe_diameter" class="form-control form-control-sm"
                                    name="pipe_diameter" value="{{ old('pipe_diameter') }}" placeholder="0.000"
                                    oninput="restrictDecimalPoints(event)" />

                                @error('pipe_diameter')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="culvert_width">Culvert Width</label>
                                <input type="number" step="0.001" id="culvert_width" class="form-control form-control-sm"
                                    name="culvert_width" value="{{ old('culvert_width') }}" placeholder="0.000"
                                    oninput="restrictDecimalPoints(event)" />
                                @error('culvert_width')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="pipe_specification">Pipe Specification</label>
                                <select class="form-control form-control-sm" id="pipe_specification"
                                    name="pipe_specification">
                                    <option value="">Choose one</option>
                                    @foreach ($humePipeSpecifications as $humePipeSpecification)
                                        <option value="{{ $humePipeSpecification->hume_pipe_cd }}">
                                            {{ $humePipeSpecification->hume_pipe_descr }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pipe_specification')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-1-box border mt-2">
                            <div class="col-md-12 py-2" style="background-color: #efeeee;">
                                <fieldset class="">
                                    <legend class="w-auto px-2" style="font-size:13px ">
                                        Head Wall
                                    </legend>
                                    <div>
                                        <label for="head_wall">
                                            Does the culvert have a head wall?
                                        </label>
                                        <input type="radio" id="yes" name="head_wall" value="Y">
                                        <label for="yes">Yes</label>
                                        <input type="radio" id="no" name="head_wall" value="N" checked>
                                        <label for="no">No</label>
                                    </div>

                                    <div class="row form-1-box" id="headWallContainer1" style="display: none;">
                                        <div class="col-12">
                                            <span class="text-xs text-danger fw-bold">
                                                <i class="fa fa-caret-right mr-1"></i>
                                                Head Wall 1
                                            </span>
                                        </div>


                                        <div class="col-md-3">
                                            <label for="hume_head_wall_stream_type_1">Stream Type:</label>
                                            <select class="form-control form-control-sm" id="hume_head_wall_stream_type_1"
                                                name="hume_head_wall_stream_type_1">
                                                <option value="U">Upstream</option>
                                            </select>

                                            @error('hume_head_wall_stream_type_1')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="hume_head_wall_type_1">Head Wall Type:</label>
                                            <select class="form-control form-control-sm" id="hume_head_wall_type_1"
                                                name="hume_head_wall_type_1">
                                                <option value="">Choose one</option>
                                                @foreach ($headWalls as $item)
                                                    <option value="{{ $item->head_wall_cd }}">
                                                        {{ $item->head_wall_descr }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('hume_head_wall_type_1')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="hume_head_wall_length_1">Length (Mtrs):</label>
                                            <input type="number" step="0.001" placeholder="0.000"
                                                id="hume_head_wall_length_1" class="form-control form-control-sm"
                                                name="hume_head_wall_length_1" value="{{ old('hume_head_wall_length_1') }}"
                                                oninput="restrictDecimalPoints(event)" />

                                            @error('hume_head_wall_length_1')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="hume_head_wall_width_1">Top Width (Mtrs):</label>
                                            <input type="number" step="0.001" placeholder="0.000"
                                                id="hume_head_wall_width_1" class="form-control form-control-sm"
                                                name="hume_head_wall_width_1" value="{{ old('hume_head_wall_width_1') }}"
                                                oninput="restrictDecimalPoints(event)" />

                                            @error('hume_head_wall_width_1')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="hume_head_wall_height_1">Height (Mtrs):</label>
                                            <input type="number" step="0.001" placeholder="0.000"
                                                id="hume_head_wall_height_1" class="form-control form-control-sm"
                                                name="hume_head_wall_height_1" value="{{ old('hume_head_wall_height_1') }}"
                                                oninput="restrictDecimalPoints(event)" />

                                            @error('hume_head_wall_height_1')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row form-1-box" id="headWallContainer2" style="display: none;">
                                        <div class="col-12">
                                            <span class="text-xs text-danger fw-bold">
                                                <i class="fa fa-caret-right mr-1"></i>
                                                Head Wall 2
                                            </span>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="hume_head_wall_stream_type_2">Stream Type:</label>
                                            <select class="form-control form-control-sm" id="hume_head_wall_stream_type_2"
                                                name="hume_head_wall_stream_type_2">
                                                <option value="D">Downstream</option>
                                            </select>

                                            @error('hume_head_wall_stream_type_2')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="hume_head_wall_type_2">Head Wall Type:</label>
                                            <select class="form-control form-control-sm" id="hume_head_wall_type_2"
                                                name="hume_head_wall_type_2">
                                                <option value="">Choose one</option>
                                                @foreach ($headWalls as $item)
                                                    <option value="{{ $item->head_wall_cd }}">
                                                        {{ $item->head_wall_descr }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('hume_head_wall_type_2')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="hume_head_wall_length_2">Length (Mtrs):</label>
                                            <input type="number" step="0.001" placeholder="0.000"
                                                id="hume_head_wall_length_2" class="form-control form-control-sm"
                                                name="hume_head_wall_length_2" value="{{ old('hume_head_wall_length_2') }}"
                                                oninput="restrictDecimalPoints(event)" />

                                            @error('hume_head_wall_length_2')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="hume_head_wall_width_2">Top Width (Mtrs):</label>
                                            <input type="number" step="0.001" placeholder="0.000"
                                                id="hume_head_wall_width_2" class="form-control form-control-sm"
                                                name="hume_head_wall_width_2" value="{{ old('hume_head_wall_width_2') }}"
                                                oninput="restrictDecimalPoints(event)" />

                                            @error('hume_head_wall_width_2')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="hume_head_wall_height_2">Height (Mtrs):</label>
                                            <input type="number" step="0.001" placeholder="0.000"
                                                id="hume_head_wall_height_2" class="form-control form-control-sm"
                                                name="hume_head_wall_height_2" value="{{ old('hume_head_wall_height_2') }}"
                                                oninput="restrictDecimalPoints(event)" />

                                            @error('hume_head_wall_height_2')
                                                <div class="text-danger text-xs">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div id="slabCulvert" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Slab Culvert</span>
                            </div>
                            <div class="col-md-3">
                                <label for="span">Span (Mtrs)</label>
                                <input type="number" min="0" step="0.001" id="span" class="form-control form-control-sm"
                                    name="span" value="{{ old('span') }}" placeholder="0.000"
                                    oninput="restrictDecimalPoints(event)">
                                @error('span')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="slab_width_slb">Slab Width (Mtrs):</label>
                                <input type="number" min="0" step="0.001" id="slab_width_slb"
                                    class="form-control form-control-sm" name="slab_width_slb"
                                    value="{{ old('slab_width_slb') }}" placeholder="0.000"
                                    oninput="restrictDecimalPoints(event)" />

                                @error('slab_width_slb')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="no_of_wing_wall">Total Wing Wall:</label>
                                <input type="number" min="0" id="no_of_wing_wall" class="form-control form-control-sm"
                                    name="no_of_wing_wall" value="{{ old('no_of_wing_wall') }}" placeholder="0" />

                                @error('no_of_wing_wall')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="abutment_type_slb">Abutment Type:</label>
                                <select class="form-control form-control-sm" id="abutment_type_slb"
                                    name="abutment_type_slb">
                                    <option value="">Choose one</option>
                                    @foreach ($abutmentTypes as $abutmentType)
                                        <option value="{{ $abutmentType->abutment_type_cd }}">
                                            {{ $abutmentType->abutment_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="abutment_height_slb">Abutment Height (Mtrs):</label>
                                <input type="number" min="0" step="0.001" id="abutment_height_slb"
                                    class="form-control form-control-sm" name="abutment_height_slb"
                                    value="{{ old('abutment_height_slb') }}" placeholder="0"
                                    oninput="restrictDecimalPoints(event)" />

                                @error('abutment_height_slb')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="slab_construction_material">Construction Materials:</label>
                                <select class="form-control form-control-sm" id="slab_construction_material"
                                    name="slab_construction_material">
                                    <option value="">Choose one</option>
                                    @foreach ($constructionMaterials as $constructionMaterial)
                                        <option value="{{ $constructionMaterial->const_material_type_cd }}">
                                            {{ $constructionMaterial->const_material_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="has_bearing">
                                    Has Bearings?
                                </label><br>
                                <input type="radio" id="yes" name="has_bearing" value="Y">
                                <label for="yes">Yes</label>
                                <input type="radio" id="no" name="has_bearing" value="N" checked>
                                <label for="no">No</label>
                            </div>
                            <div class="col-md-3" id="bearing_type_container" style="display: none;">
                                <label for="bearing_type">Bearing Type:</label>
                                <select class="form-control form-control-sm" id="bearing_type" name="bearing_type">
                                    <option value="">Choose one</option>
                                    @foreach ($bearingTypes as $bearingType)
                                        <option value="{{ $bearingType->bearing_type_cd }}">
                                            {{ $bearingType->bearing_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="slab_wing_wall">
                                    Does the slab culvert contain a wing wall?
                                </label>
                                <input type="radio" id="yes" name="slab_wing_wall" value="Y">
                                <label for="yes">Yes</label>
                                <input type="radio" id="no" name="slab_wing_wall" value="N" checked>
                                <label for="no">No</label>
                            </div>
                        </div>
                    </div>

                    <!-- The div to show/hide -->
                    <div id="wing_wall_container" class="row form-1-box border mt-2" style="display: none;">
                        <div class="col-md-12 pt-2" style="background-color: #efeeee;">
                            <fieldset class="">
                                <legend class="w-auto px-2" style="font-size:13px ">
                                    Wing Wall
                                </legend>
                                <div class="">
                                    <label for="is_same_wing_wall_slab_vented" class="text-danger">
                                        Are all the wing walls the same dimension?
                                    </label>
                                    <input type="radio" id="yes" name="is_same_wing_wall_slab_vented" value="Y">
                                    <label for="yes">Yes</label>
                                    <input type="radio" id="no" name="is_same_wing_wall_slab_vented" value="N">
                                    <label for="no">No</label>
                                </div>
                            </fieldset>
                        </div>

                        <div id="slab_vented_wing_wall_fields" style="display: none;" class="col-md-12">
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-md-3">
                                    <label for="wing_wall_type">Wing Wall Type:</label>
                                    <select class="form-control form-control-sm" id="wing_wall_type_slab_vented"
                                        name="wing_wall_type">
                                        <option value="">Choose one</option>
                                        @foreach ($wingWallTypes as $wingWallType)
                                            <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                {{ $wingWallType->wing_wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="length">Length (Mtrs):</label>
                                    <input type="number" min='0' step="0.001" placeholder="0.000" id="length"
                                        class="form-control form-control-sm" name="length" disabled
                                        value="{{ old('length') }}" oninput="restrictDecimalPoints(event)">

                                    @error('length')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="top_width">Top Width (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="top_width"
                                        class="form-control form-control-sm" name="top_width" disabled
                                        value="{{ old('top_width') }}" oninput="restrictDecimalPoints(event)">

                                    @error('top_width')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="bottom_width">Bottom Width (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="bottom_width"
                                        class="form-control form-control-sm" name="bottom_width" disabled
                                        value="{{ old('bottom_width') }}" oninput="restrictDecimalPoints(event)">

                                    @error('bottom_width')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="height1">Height 1 (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="height1"
                                        class="form-control form-control-sm" name="height1" disabled
                                        value="{{ old('height1') }}" oninput="restrictDecimalPoints(event)">

                                    @error('height1')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="height2">Height 2 (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="height2"
                                        class="form-control form-control-sm" name="height2" disabled
                                        value="{{ old('height2') }}" oninput="restrictDecimalPoints(event)">

                                    @error('height2')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="slope">Slope (Mtrs):</label>
                                    <input type="number" min='0' step="0.001" placeholder="0.000" id="slope"
                                        class="form-control form-control-sm" name="slope" disabled
                                        value="{{ old('slope') }}" oninput="restrictDecimalPoints(event)">

                                    @error('slope')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="angleContainerSlabVented" style="display: none;">
                                    <label for="angleSlabVented">Angle (Mtrs):</label>
                                    <input type="number" min='0' step="0.001" placeholder="0.000" id="angleSlabVented"
                                        class="form-control form-control-sm" name="angle" disabled
                                        value="{{ old('angle') }}" oninput="restrictDecimalPoints(event)">

                                    @error('angle')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3" style="display: none;" id="radiusContainerSlabVented">
                                    <label for="radiusSlabVented">Radius (Mtrs):</label>
                                    <input type="number" min='0' step="0.001" placeholder="0.000" id="radiusSlabVented"
                                        class="form-control form-control-sm" name="radius" disabled
                                        value="{{ old('radius') }}" oninput="restrictDecimalPoints(event)">

                                    @error('radius')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="slab_vented_wing_wall_fields_multiple" style="display: none;" class="col-md-12">
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;" data-section="1">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Wing wall 1
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="slab_vented_wing_wall_type_1">Wing Wall Type:</label>
                                    <select class="form-control form-control-sm wing-wall-type"
                                        id="slab_vented_wing_wall_type_1" name="wing_wall_type_1" data-section="1">
                                        <option value="">Choose one</option>
                                        @foreach ($wingWallTypes as $wingWallType)
                                            <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                {{ $wingWallType->wing_wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="length_1">Length (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="length_1"
                                        class="form-control form-control-sm" name="length_1" disabled
                                        value="{{ old('length_1') }}" oninput="restrictDecimalPoints(event)">

                                    @error('length_1')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="top_width_1">Top Width (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="top_width_1"
                                        class="form-control form-control-sm" name="top_width_1" disabled
                                        value="{{ old('top_width_1') }}" oninput="restrictDecimalPoints(event)">

                                    @error('top_width_1')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="bottom_width_1">Bottom Width (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="bottom_width_1"
                                        class="form-control form-control-sm" name="bottom_width_1" disabled
                                        value="{{ old('bottom_width_1') }}" oninput="restrictDecimalPoints(event)">

                                    @error('bottom_width_1')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="height1_1">Height 1 (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="height1_1"
                                        class="form-control form-control-sm" name="height1_1" disabled
                                        value="{{ old('height1_1') }}" oninput="restrictDecimalPoints(event)">

                                    @error('height1_1')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="height2_1">Height 2 (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="height2_1"
                                        class="form-control form-control-sm" name="height2_1" disabled
                                        value="{{ old('height2_1') }}" oninput="restrictDecimalPoints(event)">

                                    @error('height2_1')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="slope_1">Slope (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slope_1"
                                        class="form-control form-control-sm" name="slope_1" disabled
                                        value="{{ old('slope_1') }}" oninput="restrictDecimalPoints(event)">

                                    @error('slope_1')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="slabVentedAngleContainer_1" style="display: none;">
                                    <label for="slab_vented_angle_1">Angle (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slab_vented_angle_1"
                                        class="form-control form-control-sm" value="{{ old('angle_1') }}" data-section="1"
                                        name="angle_1" disabled oninput="restrictDecimalPoints(event)">

                                    @error('angle_1')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="slabVentedRadiusContainer_1" style="display: none;">
                                    <label for="slab_vented_radius_1">Radius (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slab_vented_radius_1"
                                        class="form-control form-control-sm" data-section="1" name="radius_1"
                                        value="{{ old('radius_1') }}" disabled oninput="restrictDecimalPoints(event)">

                                    @error('radius_1')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Wing wall 2
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="slab_vented_wing_wall_type_2">Wing Wall Type:</label>
                                    <select class="form-control form-control-sm wing-wall-type"
                                        id="slab_vented_wing_wall_type_2" name="wing_wall_type_2" data-section="2">
                                        <option value="">Choose one</option>
                                        @foreach ($wingWallTypes as $wingWallType)
                                            <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                {{ $wingWallType->wing_wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="length_2">Length (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="length_2"
                                        class="form-control form-control-sm" name="length_2" disabled
                                        value="{{ old('length_2') }}" oninput="restrictDecimalPoints(event)">

                                    @error('length_2')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="top_width_2">Top Width (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="top_width_2"
                                        class="form-control form-control-sm" name="top_width_2" disabled
                                        value="{{ old('top_width_2') }}" oninput="restrictDecimalPoints(event)">

                                    @error('top_width_2')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="bottom_width_2">Bottom Width (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="bottom_width_2"
                                        class="form-control form-control-sm" name="bottom_width_2" disabled
                                        value="{{ old('bottom_width_2') }}" oninput="restrictDecimalPoints(event)">

                                    @error('bottom_width_2')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="height1_2">Height 1 (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="height1_2"
                                        class="form-control form-control-sm" name="height1_2" disabled
                                        value="{{ old('height1_2') }}" oninput="restrictDecimalPoints(event)">

                                    @error('height1_2')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="height2_2">Height 2 (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="height2_2"
                                        class="form-control form-control-sm" name="height2_2" disabled
                                        value="{{ old('height2_2') }}" oninput="restrictDecimalPoints(event)">

                                    @error('height2_2')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="slope_2">Slope (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slope_2"
                                        class="form-control form-control-sm" name="slope_2" disabled
                                        value="{{ old('slope_2') }}" oninput="restrictDecimalPoints(event)">

                                    @error('slope_2')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="slabVentedAngleContainer_2" style="display: none;">
                                    <label for="slab_vented_angle_2">Angle (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slab_vented_angle_2"
                                        class="form-control form-control-sm" value="{{ old('angle_2') }}" data-section="2"
                                        name="angle_2" disabled oninput="restrictDecimalPoints(event)">

                                    @error('angle_2')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="slabVentedRadiusContainer_2" style="display: none;">
                                    <label for="slab_vented_radius_2">Radius (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slab_vented_radius_2"
                                        class="form-control form-control-sm" data-section="2" name="radius_2"
                                        value="{{ old('radius_2') }}" disabled oninput="restrictDecimalPoints(event)">

                                    @error('radius_2')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Wing wall 3
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="slab_vented_wing_wall_type_3">Wing Wall Type:</label>
                                    <select class="form-control form-control-sm wing-wall-type"
                                        id="slab_vented_wing_wall_type_3" name="wing_wall_type_3" data-section="3">
                                        <option value="">Choose one</option>
                                        @foreach ($wingWallTypes as $wingWallType)
                                            <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                {{ $wingWallType->wing_wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="length_3">Length (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="length_3"
                                        class="form-control form-control-sm" name="length_3" disabled
                                        value="{{ old('length_3') }}" oninput="restrictDecimalPoints(event)">

                                    @error('length_3')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="top_width_3">Top Width (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="top_width_3"
                                        class="form-control form-control-sm" name="top_width_3" disabled
                                        value="{{ old('top_width_3') }}" oninput="restrictDecimalPoints(event)">

                                    @error('top_width_3')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="bottom_width_3">Bottom Width (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="bottom_width_3"
                                        class="form-control form-control-sm" name="bottom_width_3" disabled
                                        value="{{ old('bottom_width_3') }}" oninput="restrictDecimalPoints(event)">

                                    @error('bottom_width_3')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="height1_3">Height 1 (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="height1_3"
                                        class="form-control form-control-sm" name="height1_3" disabled
                                        value="{{ old('height1_3') }}" oninput="restrictDecimalPoints(event)">

                                    @error('height1_3')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="height2_3">Height 2 (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="height2_3"
                                        class="form-control form-control-sm" name="height2_3" disabled
                                        value="{{ old('height2_3') }}" oninput="restrictDecimalPoints(event)">

                                    @error('height2_3')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="slope_3">Slope (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slope_3"
                                        class="form-control form-control-sm" name="slope_3" disabled
                                        value="{{ old('slope_3') }}" oninput="restrictDecimalPoints(event)">

                                    @error('slope_3')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="slabVentedAngleContainer_3" style="display: none;">
                                    <label for="slab_vented_angle_3">Angle (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slab_vented_angle_3"
                                        class="form-control form-control-sm" value="{{ old('angle_3') }}" data-section="3"
                                        name="angle_3" disabled oninput="restrictDecimalPoints(event)">

                                    @error('angle_3')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="slabVentedRadiusContainer_3" style="display: none;">
                                    <label for="slab_vented_radius_3">Radius (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slab_vented_radius_3"
                                        class="form-control form-control-sm" data-section="3" name="radius_3"
                                        value="{{ old('radius_3') }}" disabled oninput="restrictDecimalPoints(event)">

                                    @error('radius_3')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Wing wall 4
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="slab_vented_wing_wall_type_4">Wing Wall Type:</label>
                                    <select class="form-control form-control-sm wing-wall-type"
                                        id="slab_vented_wing_wall_type_4" name="wing_wall_type_4" data-section="4">
                                        <option value="">Choose one</option>
                                        @foreach ($wingWallTypes as $wingWallType)
                                            <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                {{ $wingWallType->wing_wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="length_4">Length (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="length_4"
                                        class="form-control form-control-sm" name="length_4" disabled
                                        value="{{ old('length_4') }}" oninput="restrictDecimalPoints(event)">

                                    @error('length_4')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="top_width_4">Top Width (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="top_width_4"
                                        class="form-control form-control-sm" name="top_width_4" disabled
                                        value="{{ old('top_width_4') }}" oninput="restrictDecimalPoints(event)">

                                    @error('top_width_4')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="bottom_width_4">Bottom Width (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="bottom_width_4"
                                        class="form-control form-control-sm" name="bottom_width_4" disabled
                                        value="{{ old('bottom_width_4') }}" oninput="restrictDecimalPoints(event)">

                                    @error('bottom_width_4')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="height1_4">Height 1 (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="height1_4"
                                        class="form-control form-control-sm" name="height1_4" disabled
                                        value="{{ old('height1_4') }}" oninput="restrictDecimalPoints(event)">

                                    @error('height1_4')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="height2_4">Height 2 (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="height2_4"
                                        class="form-control form-control-sm" name="height2_4" disabled
                                        value="{{ old('height2_4') }}" oninput="restrictDecimalPoints(event)">

                                    @error('height2_4')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="slope_4">Slope (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slope_4"
                                        class="form-control form-control-sm" name="slope_4" disabled
                                        value="{{ old('slope_4') }}" oninput="restrictDecimalPoints(event)">

                                    @error('slope_4')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="slabVentedAngleContainer_4" style="display: none;">
                                    <label for="slab_vented_angle_4">Angle (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slab_vented_angle_4"
                                        class="form-control form-control-sm" value="{{ old('angle_4') }}" data-section="3"
                                        name="angle_4" disabled oninput="restrictDecimalPoints(event)">

                                    @error('angle_4')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="slabVentedRadiusContainer_4" style="display: none;">
                                    <label for="slab_vented_radius_4">Radius (Mtrs):</label>
                                    <input type="number" step="0.001" placeholder="0.000" id="slab_vented_radius_4"
                                        class="form-control form-control-sm" data-section="4" name="radius_4"
                                        value="{{ old('radius_4') }}" disabled oninput="restrictDecimalPoints(event)">

                                    @error('radius_4')
                                        <div class="text-danger text-xs">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="head_wall_container" class="row form-1-box border mt-2" style="display: none;">
                        <div class="col-md-12 py-2" style="background-color: #efeeee;">
                            <fieldset class="">
                                <legend class="w-auto px-2" style="font-size:13px ">
                                    Head Wall
                                </legend>
                                <div class="row form-1-box">
                                    <div class="col-12">
                                        <span class="text-xs text-danger fw-bold">
                                            <i class="fa fa-caret-right mr-1"></i>
                                            Head Wall 1
                                        </span>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="head_wall_stream_type_1">Stream Type:</label>
                                        <select class="form-control form-control-sm" id="head_wall_stream_type_1"
                                            name="head_wall_stream_type_1">
                                            <option value="U">Upstream</option>
                                        </select>

                                        @error('head_wall_stream_type_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="head_wall_type_1">Head Wall Type:</label>
                                        <select class="form-control form-control-sm" id="head_wall_type_1"
                                            name="head_wall_type_1">
                                            <option value="">Choose one</option>
                                            @foreach ($headWalls as $item)
                                                <option value="{{ $item->head_wall_cd }}">
                                                    {{ $item->head_wall_descr }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('head_wall_type_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="head_wall_length_1">Length (Mtrs):</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="head_wall_length_1"
                                            class="form-control form-control-sm" name="head_wall_length_1"
                                            value="{{ old('head_wall_length_1') }}"
                                            oninput="restrictDecimalPoints(event)" />

                                        @error('head_wall_length_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="head_wall_width_1">Top Width (Mtrs):</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="head_wall_width_1"
                                            class="form-control form-control-sm" name="head_wall_width_1"
                                            value="{{ old('head_wall_width_1') }}" oninput="restrictDecimalPoints(event)" />

                                        @error('head_wall_width_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="head_wall_height_1">Height (Mtrs):</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="head_wall_height_1"
                                            class="form-control form-control-sm" name="head_wall_height_1"
                                            value="{{ old('head_wall_height_1') }}"
                                            oninput="restrictDecimalPoints(event)" />

                                        @error('head_wall_height_1')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row form-1-box">
                                    <div class="col-12">
                                        <span class="text-xs text-danger fw-bold">
                                            <i class="fa fa-caret-right mr-1"></i>
                                            Head Wall 2
                                        </span>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="head_wall_stream_type_2">Stream Type:</label>
                                        <select class="form-control form-control-sm" id="head_wall_stream_type_2"
                                            name="head_wall_stream_type_2">
                                            <option value="D">Downstream</option>
                                        </select>

                                        @error('head_wall_stream_type_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="head_wall_type_2">Head Wall Type:</label>
                                        <select class="form-control form-control-sm" id="head_wall_type_2"
                                            name="head_wall_type_2">
                                            <option value="">Choose one</option>
                                            @foreach ($headWalls as $item)
                                                <option value="{{ $item->head_wall_cd }}">
                                                    {{ $item->head_wall_descr }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('head_wall_type_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="head_wall_length_2">Length (Mtrs):</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="head_wall_length_2"
                                            class="form-control form-control-sm" name="head_wall_length_2"
                                            value="{{ old('head_wall_length_2') }}"
                                            oninput="restrictDecimalPoints(event)" />

                                        @error('head_wall_length_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="head_wall_width_2">Top Width (Mtrs):</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="head_wall_width_2"
                                            class="form-control form-control-sm" name="head_wall_width_2"
                                            value="{{ old('head_wall_width_2') }}" oninput="restrictDecimalPoints(event)" />

                                        @error('head_wall_width_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="head_wall_height_2">Height (Mtrs):</label>
                                        <input type="number" step="0.001" placeholder="0.000" id="head_wall_height_2"
                                            class="form-control form-control-sm" name="head_wall_height_2"
                                            value="{{ old('head_wall_height_2') }}"
                                            oninput="restrictDecimalPoints(event)" />

                                        @error('head_wall_height_2')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    {{-- End of dynomic div --}}

                    <div class="row form-1-box" id="safety_apron_container" style="display: none;">
                        <div class="col-md-3 px-2">
                            <label for="safety_apron">Is a Safety Apron available for this culvert?</label><br>
                            <input type="radio" id="yes" name="safety_apron" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="safety_apron" value="N" checked>
                            <label for="no">No</label>

                            @error('safety_apron')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row form-1-box border pb-2" id="apron_container"
                        style="display: none; background-color: #efeeee;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Culvert Safety Apron</span>
                        </div>
                        <div class="col-md-3">
                            <label for="cdwork_safety_apron_type">Apron Type:</label>
                            <select class="form-control form-control-sm" id="cdwork_safety_apron_type"
                                name="cdwork_safety_apron_type">
                                <option value="">Choose one</option>
                                @foreach ($saftyApronTypes as $saftyApronType)
                                    <option value="{{ $saftyApronType->apron_type_cd }}">
                                        {{ $saftyApronType->apron_type_descr }}
                                    </option>
                                @endforeach
                            </select>

                            @error('cdwork_safety_apron_type')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cdwork_safety_apron_outlet">Apron Outlet (Mtrs):</label>
                            <input type="number" min="0" step="0.001" placeholder="0.000" id="cdwork_safety_apron_outlet"
                                class="form-control form-control-sm" name="cdwork_safety_apron_outlet"
                                value="{{ old('cdwork_safety_apron_outlet') }}" oninput="restrictDecimalPoints(event)">

                            @error('cdwork_safety_apron_outlet')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cdwork_safety_apron_slab_thickness">Thickness of Slab (Mtrs):</label>
                            <input type="number" min="0" step="0.001" placeholder="0.000"
                                id="cdwork_safety_apron_slab_thickness" class="form-control form-control-sm"
                                name="cdwork_safety_apron_slab_thickness"
                                value="{{ old('cdwork_safety_apron_slab_thickness') }}"
                                oninput="restrictDecimalPoints(event)">

                            @error('cdwork_safety_apron_slab_thickness')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cdwork_safety_apron_width">Apron Width (Mtrs):</label>
                            <input type="number" min="0" step="0.001" placeholder="0.000" id="cdwork_safety_apron_width"
                                class="form-control form-control-sm" name="cdwork_safety_apron_width"
                                value="{{ old('cdwork_safety_apron_width') }}" oninput="restrictDecimalPoints(event)">

                            @error('cdwork_safety_apron_width')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cdwork_safety_apron_length">Apron Length (Mtrs):</label>
                            <input type="number" min="0" step="0.001" placeholder="0.000" id="cdwork_safety_apron_length"
                                class="form-control form-control-sm" name="cdwork_safety_apron_length"
                                value="{{ old('cdwork_safety_apron_length') }}" oninput="restrictDecimalPoints(event)">

                            @error('cdwork_safety_apron_length')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row form-1-box" id="catch_pit_container" style="display: none;">
                        <div class="col-md-3 px-2">
                            <label for="catch_pit_availability">Is a Catch Pit available for this culvert?</label><br>
                            <input type="radio" id="yes" name="catch_pit_availability" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="catch_pit_availability" value="N" checked>
                            <label for="no">No</label>

                            @error('catch_pit_availability')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row form-1-box border pb-2" id="catch_pit_fields"
                        style="display: none; background-color: #efeeee;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Catch Pit</span>
                        </div>
                        <div class="col-md-3">
                            <label for="catch_pit_type_cd">Catch Pits Type:</label>
                            <select class="form-control form-control-sm" id="catch_pit_type_cd" name="catch_pit_type_cd">
                                <option value="">Choose one</option>
                                @foreach ($pitTypes as $item)
                                    <option value="{{ $item->catch_pit_type_cd }}">
                                        {{ $item->catch_pit_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="catch_pit_width">Catch Pit Width (Mtrs):</label>
                            <input type="number" min="0" step="0.001" id="catch_pit_width"
                                class="form-control form-control-sm" name="catch_pit_width" disabled
                                value="{{ old('catch_pit_width') }}" placeholder="0.000"
                                oninput="restrictDecimalPoints(event)">
                        </div>
                        <div class="col-md-3">
                            <label for="catch_pit_breadth">Catch Pit Length (Mtrs):</label>
                            <input type="number" min="0" step="0.001" id="catch_pit_breadth"
                                class="form-control form-control-sm" name="catch_pit_breadth" disabled
                                value="{{ old('catch_pit_breadth') }}" placeholder="0.000"
                                oninput="restrictDecimalPoints(event)">
                        </div>
                        <div class="col-md-3">
                            <label for="catch_pit_heigth">Catch Pit Height (Mtrs):</label>
                            <input type="number" min="0" step="0.001" id="catch_pit_heigth"
                                class="form-control form-control-sm" name="catch_pit_heigth" disabled
                                value="{{ old('catch_pit_heigth') }}" placeholder="0.000"
                                oninput="restrictDecimalPoints(event)">
                        </div>
                        <div class="col-md-3">
                            <label for="catch_pit_thickness">Catch Pit Thickness (Mtrs):</label>
                            <input type="number" min="0" step="0.001" id="catch_pit_thickness"
                                class="form-control form-control-sm" name="catch_pit_thickness" disabled
                                value="{{ old('catch_pit_thickness') }}" placeholder="0.000"
                                oninput="restrictDecimalPoints(event)">
                        </div>
                        <div class="col-md-3">
                            <label for="catch_pit_condition">Catch Pit Condition:</label>
                            <select class="form-control form-control-sm" id="catch_pit_condition"
                                name="catch_pit_condition">
                                <option value="">Choose one</option>
                                @foreach ($roadConditions as $item)
                                    <option value="{{ $item->rd_condition_cd }}">
                                        {{ $item->rd_condition_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-1-box" style="display: none;" id="cdworks_remarks_container">
                        <div class="col-md-12">
                            <label for="cdwork_remark">Remarks:</label>
                            <textarea class="form-control form-control-sm text-sm" id="cdwork_remark" name="cdwork_remark"
                                rows="2" placeholder="Write CD Works remarks..."></textarea>
                        </div>
                    </div>
                    {{-- Upload Image --}}
                    <x-asset-resources.asset-image />
                    {{-- End of upload image --}}
                    {{-- upload documents --}}
                    <x-asset-resources.asset-document />
                    {{-- End of upload documents --}}
                </fieldset>
                <div class="text-end">
                    <!-- modified by pulak 30-04-26 --->
                    <button type="submit" id="saveBtn" class="btn btn-success btn-sm rounded-0 mt-2" disabled>
                        <i class="fa fa-save"></i> <span id="saveBtnLabel">Update</span>
                    </button>
                    <button type="button" id="cancelEditBtn" class="btn btn-warning btn-sm rounded-0 mt-2">
                        <i class="fa fa-times"></i><a class="text-white"
                            href="{{ route('manageCDWorks', $road_system_id) }}"> Cancel Edit</a>
                    </button>
                    <!-- modified by pulak 30-04-26 --->
                    <button class="btn btn-secondary btn-sm rounded-0 mt-2"><i class="fa fa-backward"></i>
                        <a class="text-white" href="{{ route('manageRoad') }} ">Cancel</a>
                    </button>
                </div>
            </form>
        </div>

        <!-- table content -->
        <div class="container-fluid mainBody">
            <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT CULVERT DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border py-2">
                <div class="d-flex text-sm justify-content-end mb-2">
                    <button id="freezeBtn" class="btn btn-sm btn-info rounded-1 text-bold">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Send for finalization
                    </button>
                </div>
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="cd_work_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center" style="min-width: 3rem;">Sl No.</th>
                        <th class="text-center" style="min-width: 6rem;">Culvert Code</th>
                        <th class="text-center" style="min-width: 5rem;">Culvert No.</th>
                        <!-- Saiful # Apr-2026 # Start-->
                        <th class="text-center" style="min-width: 5rem;">Project CD</th>
                        <!-- Saiful # Apr-2026 # End-->
                        <th class="text-center" style="min-width: 4rem;">Chainage</th>
                        <th class="text-center" style="min-width: 6rem;">Discharge</th>
                        <th class="text-center" style="min-width: 8rem;">Construction Year</th>
                        <th class="text-center" style="min-width: 8rem;">Rehabilitation Year</th>
                        <th class="text-center" style="min-width: 8rem;">Culvert Condition</th>
                        <th class="text-center" style="min-width: 8rem;">Culvert Type</th>

                        {{-- Box Culvert --}}
                        <th class="text-center" style="min-width: 6rem;">No. of Cell</th>
                        <th class="text-center" style="min-width: 8rem;">Each Cell Length(Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Each Cell Width(Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Each Cell Height(Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Side Wall Thickness(Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Top slab Thickness(Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Bottom Thickness(Mtrs)</th>

                        {{-- Slab Culvert --}}
                        <th class="text-center" style="min-width: 6rem;">Span (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Slab Width (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Total Wing Wall</th>
                        <th class="text-center" style="min-width: 8rem;">Abutment Type</th>
                        <th class="text-center" style="min-width: 10rem;">Abutment Height (Mtrs)</th>
                        <th class="text-center" style="min-width: 6rem;">Bearing Type</th>

                        {{-- Hume Culvert --}}
                        <th class="text-center" style="min-width: 6rem;">No. of Row</th>
                        <th class="text-center" style="min-width: 6rem;">Cussion(Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Pipe Diameter</th>
                        <th class="text-center" style="min-width: 8rem;">Culvert Width</th>
                        <th class="text-center" style="min-width: 8rem;">Pipe Specification</th>

                        <th class="text-center" style="min-width: 10rem;">Construction Materials</th>

                        {{-- Safety Apron --}}
                        <th class="text-center" style="min-width: 8rem;">Safety Apron?</th>
                        <th class="text-center" style="min-width: 6rem;">Safety Type</th>
                        <th class="text-center" style="min-width: 8rem;">Apron Outlet (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Slab Thickness (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Apron Width (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Apron Length (Mtrs)</th>

                        {{-- Catch Pit --}}
                        <th class="text-center" style="min-width: 6rem;">Catch Pit?</th>
                        <th class="text-center" style="min-width: 8rem;">Catch Pit Type</th>
                        <th class="text-center" style="min-width: 8rem;">Catch Pit Width (Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Catch Pit Length (Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Catch Pit Height (Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Catch Pit Thickness (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Catch Pit Condition</th>
                        <th class="text-center" style="min-width: 8rem;">Remarks</th>
                        <th class="text-center" style="min-width: 4rem;">Wing Wall</th>
                        <th class="text-center" style="min-width: 4rem;">Head Wall</th>
                        <th class="text-center" style="min-width: 6rem;">Culvert Location</th>
                        <th class="text-center" style="min-width: 4rem;">Edit</th>
                        <th class="text-center" style="min-width: 4rem;">Select</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($cd_work_details as $item)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">
                                    {{ $item->rd_cdwork_cd ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->culvert_no ?? 'NA' }}
                                </td>
                                <!-- Saiful # Apr-2026 # Start-->
                                <td class="text-center">
                                    {{ $item->project_cd ?? '-' }}
                                </td>
                                <!-- Saiful # Apr-2026 # End-->
                                <td class="text-center">
                                    {{ $item->chainage ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->discharge ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->year_of_construction ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->year_of_rehabilitation ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cd_condition ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwoerk_descr ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_cell ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->length_span ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->width_each_cell ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->heigth_each_cell ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_thickness_side_wall ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_thickness_top_slab ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_thickness_bottom_slab ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->span ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->slab_width ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_wing_wall ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->abutment_type_descr ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->abutment_height ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->bearing_type_descr ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_rows ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->height_of_earth_cushion ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->pipe_diameter ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->culvert_width ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->hume_pipe_descr ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->const_material_type_descr ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    @if ($item->cdwork_has_safety_apron == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $item->apron_type_descr ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_safety_apron_outlet ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_safety_apron_slab_thickness ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_safety_apron_width ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_safety_apron_length ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    @if ($item->catch_pit_availability == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $item->catch_pit_type_descr ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->catch_pit_width ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->catch_pit_breadth ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->catch_pit_heigth ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->catch_pit_thickness ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cp_condition ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->cdwork_remark ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    @if ($item->cdwork_has_wing_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#wingWallModal{{ $item->rd_cdwork_cd }}"
                                            onclick="getWingWallValue('{{ $item->rd_cdwork_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->cdwork_has_head_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#headWallModal{{ $item->rd_cdwork_cd }}"
                                            onclick="getHeadWallValue('{{ $item->rd_cdwork_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class='btn btn-xs btn-outline-primary' data-culvert-id="{{ $item->rd_cdwork_cd }}"
                                        culvert_number="{{ $item->culvert_no }}" culvert_type="{{ $item->cdwoerk_descr }}"
                                        culvert_location="{{ $item->culvert_location }}" id='btnShowInMap'
                                        onclick='showMap(this)'>
                                        <i class='fa fa-eye mr-1'></i>Show
                                    </button>
                                </td>
                                <td class="text-center">
                                    @if (session('updated') == 1)
                                        {{-- modified by Pulak 30-04-26 --}}
                                        <a class="text-primary edit" href="{{ route('editCDWorks', $item->rd_cdwork_cd) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @else
                                        <span class="text-danger text-bold"><i class="fas fa-ban"></i></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="selected-asset" data-cdwork-cd="{{ $item->rd_cdwork_cd }}" />
                                </td>
                            </tr>
                            <?php    $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/road/cdwork/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/selectOptionStyleSheet.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/road/cdworks/script.js') }}" defer></script>
    <script src="{{ asset('js/common/restrict_decimal_points.js') }}" defer></script>
    <script src="{{ asset('js/common/asset_coordinates_script.js') }}" defer></script>
    <!-- modified by Pulak 30-04-26 -->
    <script src="{{ asset('js/road/cdworks/editScript.js') }}" defer></script>

    <script>
        // modified by Pulak 30-04 - 26
        const cdWorkData = @json($culvert_id ?? null);
        const UPDATE_ROUTE_BASE = "{{ url('/asset-management/update-cdworks') }}";
        // modified by Pulak 30-04 - 26
        (g => {
            var h, a, k, p = "The Google Maps JavaScript API",
                c = "google",
                l = "importLibrary",
                q = "__ib__",
                m = document,
                b = window;
            b = b[c] || (b[c] = {});
            var d = b.maps || (b.maps = {}),
                r = new Set,
                e = new URLSearchParams,
                u = () => h || (h = new Promise(async (f, n) => {
                    await (a = m.createElement("script"));
                    e.set("libraries", [...r] + "");
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                    e.set("callback", c + ".maps." + q);
                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                    d[q] = f;
                    a.onerror = () => h = n(Error(p + " could not load."));
                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                    m.head.append(a)
                }));
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(
                () =>
                    d[l](f, ...n))
        })
            ({
                key: "AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM",
                v: "beta"
            });
    </script>
    {{-- Script for remove button when file is selected --}}
    <script>
        $(function () {
            $("#cd_work_details_table").DataTable();
        });

        document.getElementById('cd_work_form').addEventListener('submit', function () {
            document.getElementById('angle_box').removeAttribute('disabled');
            document.getElementById('radius_box').removeAttribute('disabled');
            document.getElementById('box_angle_1').removeAttribute('disabled');
            document.getElementById('box_angle_2').removeAttribute('disabled');
            document.getElementById('box_angle_3').removeAttribute('disabled');
            document.getElementById('box_angle_4').removeAttribute('disabled');
            document.getElementById('box_radius_1').removeAttribute('disabled');
            document.getElementById('box_radius_2').removeAttribute('disabled');
            document.getElementById('box_radius_3').removeAttribute('disabled');
            document.getElementById('box_radius_4').removeAttribute('disabled');
            document.getElementById('angleSlabVented').removeAttribute('disabled');
            document.getElementById('radiusSlabVented').removeAttribute('disabled');
            document.getElementById('slab_vented_angle_1').removeAttribute('disabled');
            document.getElementById('slab_vented_angle_2').removeAttribute('disabled');
            document.getElementById('slab_vented_angle_3').removeAttribute('disabled');
            document.getElementById('slab_vented_angle_4').removeAttribute('disabled');
            document.getElementById('slab_vented_radius_1').removeAttribute('disabled');
            document.getElementById('slab_vented_radius_2').removeAttribute('disabled');
            document.getElementById('slab_vented_radius_3').removeAttribute('disabled');
            document.getElementById('slab_vented_radius_4').removeAttribute('disabled');
        });

        $(document).ready(function () {
            // desabled submit button 
            $('#saveBtn').prop('disabled', true);

            $('#culvert_type, #year_of_contruction, #year_of_rehabilitation, #condition, #outlet_type_cd, #box_construction_material, #slab_construction_material, #abutment_type_slb, #bearing_type, #box_wing_wall_type_1, #box_wing_wall_type_2, #box_wing_wall_type_3, #box_wing_wall_type_4, #wing_wall_type_box, #wing_wall_type_slab_vented, #slab_vented_wing_wall_type_1, #slab_vented_wing_wall_type_2, #slab_vented_wing_wall_type_3, #slab_vented_wing_wall_type_4, #cdwork_safety_apron_type, #catch_pit_type_cd, #catch_pit_condition')
                .select2();

            function toggleAngleRadius(selector, angleContainer, radiusContainer, angleField, radiusField) {
                $(selector).on('change', function () {
                    const wallTypeCd = $(this).val();
                    $(angleContainer).hide();
                    $(radiusContainer).hide();
                    $(angleField).prop('disabled', true); // Use prop to set disabled attribute
                    $(radiusField).prop('disabled', true); // Use prop to set disabled attribute

                    if ((wallTypeCd === '1') || (wallTypeCd === '4')) {
                        $(angleContainer).show();
                        $(angleField).prop('disabled', false);
                    }

                    if (wallTypeCd === '3') {
                        $(angleField).prop('disabled', false);
                        $(radiusField).prop('disabled', false);
                        $(angleContainer).show();
                        $(radiusContainer).show();
                    }
                });
            }

            toggleAngleRadius("#wing_wall_type_slab_vented", "#angleContainerSlabVented",
                "#radiusContainerSlabVented", "#angleSlabVented", "#radiusSlabVented");
            toggleAngleRadius("#slab_vented_wing_wall_type_1", "#slabVentedAngleContainer_1",
                "#slabVentedRadiusContainer_1", "#slab_vented_angle_1", "#slab_vented_radius_1");
            toggleAngleRadius("#slab_vented_wing_wall_type_2", "#slabVentedAngleContainer_2",
                "#slabVentedRadiusContainer_2", "#slab_vented_angle_2", "#slab_vented_radius_2");
            toggleAngleRadius("#slab_vented_wing_wall_type_3", "#slabVentedAngleContainer_3",
                "#slabVentedRadiusContainer_3", "#slab_vented_angle_3", "#slab_vented_radius_3");
            toggleAngleRadius("#slab_vented_wing_wall_type_4", "#slabVentedAngleContainer_4",
                "#slabVentedRadiusContainer_4", "#slab_vented_angle_4", "#slab_vented_radius_4");
            toggleAngleRadius("#wing_wall_type_box", "#angleContainer", "#radiusContainer", "#angle_box",
                "#radius_box");
            toggleAngleRadius("#box_wing_wall_type_1", "#boxAngleContainer_1", "#boxRadiusContainer_1",
                "#box_angle_1", "#box_radius_1");
            toggleAngleRadius("#box_wing_wall_type_2", "#boxAngleContainer_2", "#boxRadiusContainer_2",
                "#box_angle_2", "#box_radius_2");
            toggleAngleRadius("#box_wing_wall_type_3", "#boxAngleContainer_3", "#boxRadiusContainer_3",
                "#box_angle_3", "#box_radius_3");
            toggleAngleRadius("#box_wing_wall_type_4", "#boxAngleContainer_4", "#boxRadiusContainer_4",
                "#box_angle_4", "#box_radius_4");

            $('form.updateCDWorksDetails').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('createCDWorks') }}";
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
                    success: function (response) {
                        console.log(response);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            })
                                .then(() => {
                                    window.location.replace(location)
                                });
                        } else if (response.status === 'failed') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            })
                                .then(() => {
                                    window.location.replace(location)
                                });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something Went Wrong!',
                                showConfirmButton: true,
                                timer: 3000
                            })
                                .then(() => {
                                    window.location.replace(location)
                                });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                    }
                });
            });

            $('#chainage').on('input', () => {
                const rd_end_chainage = Number($('#road_end_chainage').val());
                const rd_srt_chainage = Number($('#road_start_chainage').val());
                const enteredChainage = Number($('#chainage').val());
                if (enteredChainage > rd_end_chainage || enteredChainage < rd_srt_chainage) {
                    alert(`Chainage value should between ${rd_srt_chainage} - ${rd_end_chainage}`);
                    $('#chainage').val('');
                }
            });

            $('input[name="slab_wing_wall"]').change(function () {
                $('#wing_wall_container').hide();
                var selectedValue = $('input[name="slab_wing_wall"]:checked').val();
                if (selectedValue === 'Y') {
                    $('#wing_wall_container').show();
                }
            });

            $('#culvert_type').on('change', () => {
                $('#humePipeCulvert').hide();
                $('#slabCulvert').hide();
                $('#boxArcCulvert').hide();
                $('#wing_wall_container').hide();
                $('#head_wall_container').hide();
                $("#catch_pit_container").hide();
                $('#common_cdworks_fields').show();
                $('#safety_apron_container').show();
                $('#asset_image_container').show();
                $('#asset_document_container').show();
                $('#cdworks_remarks_container').show();
                const catchPitFields = document.getElementById('catch_pit_fields');
                if (catchPitFields) { // Check if catchPitFields exists
                    catchPitFields.style.display = 'none';
                }

                let culvertType = $('#culvert_type').val();

                if (culvertType === 'HPC') {
                    $('#humePipeCulvert').show();
                    $("#catch_pit_container").show();
                }

                if (culvertType === 'SLB') {
                    $('#slabCulvert').show();
                    $("#catch_pit_container").show();
                }

                if (culvertType === 'BXC') {
                    $('#boxArcCulvert').show();
                    $("#catch_pit_container").hide();
                    if (catchPitFields) { // Check if catchPitFields exists
                        catchPitFields.style.display = 'none';
                    }
                }
            })

            // Manipulate the safty aprone edit form
            $(".safety-apron-toggle").change(function () {
                // Find the closest parent with the class 'col-md-4'
                var parentCol = $(this).closest(".is_safty_aprone");

                // Find the next sibling with the class 'apron-width-container'
                var apronWidthContainer = parentCol.next(".apron-width-container");

                // Toggle the display based on the selected value
                apronWidthContainer.toggle(this.value === "Y");

                // Disable/enable the input based on the selected value
                apronWidthContainer.find('input[name="apron_width"]').prop('disabled', this.value !== "Y");
            });

            $(".catch-pit-toggle").change(function () {
                var parentCol = $(this).closest(".is_catch_pit");
                var catchPitFields = parentCol.next(".catch-pit-fields");

                catchPitFields.toggle(this.value === "Y");
                catchPitFields.find('select, input').prop('disabled', this.value !==
                    "Y");
            });

            $(".culvert-type-edit").change(function () {
                var selectedValue = $(this).val();
                $(".box-arc-culvert-edit, .hume-pipe-culvert-edit, .slab-culvert-edit, .vented-causeway-culvert-edit, .wing-wall-edit, .wing-wall-multi-edit, .head-wall-container-edit, .wing-wall-single-edit, .wing-wall-multi-edit, .choose-wing-head-both-edit")
                    .hide();

                if (selectedValue === "BXC") {
                    $(".box-arc-culvert-edit, .wing-wall-edit").show();
                }

                if (selectedValue === "HPC") {
                    $(".hume-pipe-culvert-edit, .head-wall-container-edit").show();
                }

                if (selectedValue === "SLB") {
                    $(".slab-culvert-edit, .choose-wing-head-both-edit").show();
                }
            });

            $(".wing-wall-dimension-toggle-edit").change(function () {
                var selectedValue = $(this).val();
                console.log(selectedValue);
                $(".wing-wall-single-edit, .wing-wall-multi-edit").hide();

                if (selectedValue === "Y") {
                    $(".wing-wall-single-edit").show();
                    $(".wing-wall-single-edit input").prop('disabled', false);
                } else if (selectedValue === "N") {
                    $(".wing-wall-multi-edit").show();
                    $(".wing-wall-multi-edit input").prop('disabled', false);
                }
            });

            $(".culvert-wall-selection-edit").change(function () {
                $(".wing-wall-edit, .head-wall-container-edit, .wing-wall-multi-edit, .wing-wall-single-edit")
                    .hide();

                const chooseWallType = $(this).val();

                if (chooseWallType === "cww") {
                    $(".wing-wall-edit").show();
                }
                if (chooseWallType === "chw") {
                    $(".head-wall-container-edit").show();
                }
                if (chooseWallType === "cbw") {
                    $(".wing-wall-edit").show();
                    $(".head-wall-container-edit").show();
                }
            })

            const bearingRadio = document.querySelectorAll('input[name="has_bearing"]');
            bearingRadio.forEach(radio => {
                radio.addEventListener('change', function () {
                    $('#bearing_type_container').hide();
                    if (this.value === 'Y') {
                        $('#bearing_type_container').show();
                    }
                });
            });

            const safetyApronRadio = document.querySelectorAll('input[name="safety_apron"]');
            const apronContainer = document.getElementById('apron_container');
            const apronWidthInput = document.getElementById('apron_width');
            const appronTypeInput = document.getElementById('cdwork_safety_apron_type');
            const handRailTypeContainer = document.getElementById('hand_rail_container');
            const handRailTypeInput = document.getElementById('cdwork_safety_apron_hand_rail_type');

            safetyApronRadio.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'Y') {
                        if (apronContainer) { // Check if the element exists before manipulating it
                            apronContainer.style.display = 'flex';
                        }
                        if (apronWidthInput) { // Check if the element exists
                            apronWidthInput.removeAttribute('disabled');
                        }
                        if (appronTypeInput) { // Check if the element exists
                            appronTypeInput.removeAttribute('disabled');
                        }
                    } else {
                        if (apronContainer) { // Check if the element exists
                            apronContainer.style.display = 'none';
                        }
                        if (apronWidthInput) { // Check if the element exists
                            apronWidthInput.setAttribute('disabled', 'disabled');
                        }
                        if (appronTypeInput) { // Check if the element exists
                            appronTypeInput.setAttribute('disabled', 'disabled');
                        }
                    }
                });
            });

            if (appronTypeInput) { // Check if appronTypeInput exists before adding an event listener
                appronTypeInput.addEventListener('change', function () {
                    if (this.value === '1') {
                        if (handRailTypeContainer) {
                            handRailTypeContainer.style.display = 'block';
                        }
                        if (handRailTypeInput) {
                            handRailTypeInput.removeAttribute('disabled');
                        }
                    } else {
                        if (handRailTypeContainer) {
                            handRailTypeContainer.style.display = 'none';
                        }
                        if (handRailTypeInput) {
                            handRailTypeInput.setAttribute('disabled', 'disabled');
                        }
                    }
                });
            }

            const catchPitRadio = document.querySelectorAll('input[name="catch_pit_availability"]');
            const catchPitFields = document.getElementById('catch_pit_fields');

            catchPitRadio.forEach(radio => {
                radio.addEventListener('change', function () {
                    console.log(catchPitFields);
                    if (this.value === 'Y') {
                        if (catchPitFields) { // Check if the element exists
                            catchPitFields.style.display = 'flex';
                            catchPitFields.querySelectorAll('input, select').forEach(field => field
                                .removeAttribute(
                                    'disabled'));
                        }
                    } else {
                        if (catchPitFields) { // Check if the element exists
                            catchPitFields.style.display = 'none';
                            catchPitFields.querySelectorAll('input, select').forEach(field => field
                                .setAttribute(
                                    'disabled', 'disabled'));
                        }
                    }
                });
            });

            const catchToeRadio = document.querySelectorAll('input[name="catch_toe_availability"]');
            const catchToeFields = document.getElementById('catch_toe_fields');

            catchToeRadio.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'Y') {
                        if (catchToeFields) { // Check if the element exists
                            catchToeFields.style.display = 'flex';
                            catchToeFields.querySelectorAll('input, select').forEach(field => field
                                .removeAttribute(
                                    'disabled'));
                        }
                    } else {
                        if (catchToeFields) { // Check if the element exists
                            catchToeFields.style.display = 'none';
                            catchToeFields.querySelectorAll('input, select').forEach(field => field
                                .setAttribute(
                                    'disabled', 'disabled'));
                        }
                    }
                });
            });

            const wingWall = document.querySelectorAll('input[name="wing_wall"]');
            const wingWallDimension = document.getElementById('wingWallDimensionContainer');
            const wingWallRadio = document.querySelectorAll('input[name="is_same_wing_wall"]');
            const wingWallFields = document.getElementById('box_wing_wall_fields');
            const multipleWingWallFields = document.getElementById('box_wing_wall_fields_multiple');

            wingWall.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'Y') {
                        if (wingWallDimension) { // Check if wingWallDimension exists
                            wingWallDimension.style.display = 'block';
                        }
                    } else {
                        if (wingWallDimension) { // Check if wingWallDimension exists
                            wingWallDimension.style.display = 'none';
                        }
                        if (multipleWingWallFields) { // Check if multipleWingWallFields exists
                            multipleWingWallFields.style.display = 'none';
                        }
                        if (wingWallFields) { // Check if wingWallFields exists
                            wingWallFields.style.display = 'none';
                        }
                    }
                });
            });

            wingWallRadio.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'Y') {
                        if (wingWallFields) { // Check if wingWallFields exists
                            wingWallFields.style.display = 'block';
                            wingWallFields.querySelectorAll('input, select').forEach(field => field
                                .removeAttribute(
                                    'disabled'));
                        }
                        if (multipleWingWallFields) { // Check if multipleWingWallFields exists
                            multipleWingWallFields.style.display = 'none';
                            multipleWingWallFields.querySelectorAll('input, select').forEach(
                                field => field
                                    .setAttribute(
                                        'disabled', 'disabled'));
                        }
                    } else {
                        if (wingWallFields) { // Check if wingWallFields exists
                            wingWallFields.style.display = 'none';
                            wingWallFields.querySelectorAll('input, select').forEach(field => field
                                .setAttribute(
                                    'disabled', 'disabled'));
                        }
                        if (multipleWingWallFields) { // Check if multipleWingWallFields exists
                            multipleWingWallFields.style.display = 'block';
                            multipleWingWallFields.querySelectorAll('input, select').forEach(
                                field => field
                                    .removeAttribute(
                                        'disabled'));
                        }
                    }
                });
            });

            const headWall = document.querySelectorAll('input[name="head_wall"]');
            const headWallContainer1 = document.getElementById('headWallContainer1');
            const headWallContainer2 = document.getElementById('headWallContainer2');
            headWall.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'Y') {
                        if (headWallContainer1) { // Check if headWallContainer1 exists
                            headWallContainer1.style.display = 'flex';
                        }
                        if (headWallContainer2) { // Check if headWallContainer2 exists
                            headWallContainer2.style.display = 'flex';
                        }
                    } else {
                        if (headWallContainer1) { // Check if headWallContainer1 exists
                            headWallContainer1.style.display = 'none';
                        }
                        if (headWallContainer2) { // Check if headWallContainer2 exists
                            headWallContainer2.style.display = 'none';
                        }
                    }
                });
            });

            const slabVentedwingWallRadio = document.querySelectorAll(
                'input[name="is_same_wing_wall_slab_vented"]');
            const slabVentedWingWallFields = document.getElementById('slab_vented_wing_wall_fields');
            const slabVentedMultipleWingWallFields = document.getElementById(
                'slab_vented_wing_wall_fields_multiple');

            slabVentedwingWallRadio.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'Y') {
                        if (slabVentedWingWallFields) { // Check if slabVentedWingWallFields exists
                            slabVentedWingWallFields.style.display = 'block';
                            slabVentedWingWallFields.querySelectorAll('input, select').forEach(
                                field => field
                                    .removeAttribute(
                                        'disabled'));
                        }
                        if (slabVentedMultipleWingWallFields) { // Check if slabVentedMultipleWingWallFields exists
                            slabVentedMultipleWingWallFields.style.display = 'none';
                            slabVentedMultipleWingWallFields.querySelectorAll('input, select')
                                .forEach(field =>
                                    field
                                        .setAttribute(
                                            'disabled', 'disabled'));
                        }
                    } else {
                        if (slabVentedWingWallFields) { // Check if slabVentedWingWallFields exists
                            slabVentedWingWallFields.style.display = 'none';
                            slabVentedWingWallFields.querySelectorAll('input, select').forEach(
                                field => field
                                    .setAttribute(
                                        'disabled', 'disabled'));
                        }
                        if (slabVentedMultipleWingWallFields) { // Check if slabVentedMultipleWingWallFields exists
                            slabVentedMultipleWingWallFields.style.display = 'block';
                            slabVentedMultipleWingWallFields.querySelectorAll('input, select')
                                .forEach(field =>
                                    field
                                        .removeAttribute(
                                            'disabled'));
                        }
                    }
                });
            });

            const wingWallRadioEdit = document.querySelectorAll('input[name="wing_wall_edit"]');
            const wingWallFieldsEdit = document.getElementById('wing_wall_fields_edit');

            wingWallRadioEdit.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'Y') {
                        if (wingWallFieldsEdit) { // Check if wingWallFieldsEdit exists
                            wingWallFieldsEdit.style.display = 'flex';
                            wingWallFieldsEdit.querySelectorAll('input, select').forEach(field =>
                                field
                                    .removeAttribute(
                                        'disabled'));
                        }
                    } else {
                        if (wingWallFieldsEdit) { // Check if wingWallFieldsEdit exists
                            wingWallFieldsEdit.style.display = 'none';
                            wingWallFieldsEdit.querySelectorAll('input, select').forEach(field =>
                                field
                                    .setAttribute(
                                        'disabled', 'disabled'));
                        }
                    }
                });
            });

            var currentYear = new Date().getFullYear();
            var yearDropdown = document.getElementById("year_of_contruction");
            if (yearDropdown) { // Check if yearDropdown exists
                for (var year = currentYear; year >= 1950; year--) {
                    var option = document.createElement("option");
                    option.value = year;
                    option.text = year;
                    yearDropdown.appendChild(option);
                }
            }


            var yearDropdown = document.getElementById("year_of_rehabilitation");
            if (yearDropdown) { // Check if yearDropdown exists
                for (var year = currentYear; year >= 1950; year--) {
                    var option = document.createElement("option");
                    option.value = year;
                    option.text = year;
                    yearDropdown.appendChild(option);
                }
            }


            const $yearDropdown = $('.construnctionYearEdit');
            const $rehYearDropDown = $('.rehabilitationYearEdit')

            for (let year = currentYear; year >= 1950; year--) {
                const $option = $('<option>');
                $option.val(year);
                $option.html(year);
                $yearDropdown.append($option);
            }
            for (let year = currentYear; year >= 1950; year--) {
                const $option = $('<option>');
                $option.val(year);
                $option.html(year);
                $rehYearDropDown.append($option);
            }
        })

        document.getElementById('cd_work_form').addEventListener('submit', function () {
            const toChainageElement = document.getElementsByName('to_chainage')[0];
            if (toChainageElement) {
                toChainageElement.disabled = false;
            }
        });
    </script>
@endpush