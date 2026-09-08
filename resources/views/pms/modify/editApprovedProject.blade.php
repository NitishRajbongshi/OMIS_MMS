{{-- New file created by Pulak: 14-10-2025- 18:21 --}}
@extends('layouts.app')
@section('content')
    <div class="content-header mb-1">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('project.request.list') }}">
                        Approved Projects
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Edit Modification Approved Project
                </li>
            </ol>
            <div class="clearfix"></div>

            <h4 id="editModeText" class="mt-2"></h4>
        </div>
    </div>

    <section class="content">
        <form id="myForm"  action="{{ isset($project) ? route('request.modification') : '' }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="project_cd" value="{{ $project_cd }}">
            @php
                $others = $project->others;
            @endphp
            <div class="card mb-2">
                <div class="card-header text-light fw-bold text-uppercase">Project Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-3">
                            <label class="form-label">Project Type </label>

                            <select name="projectTypeSelect" id="projectTypeSelect" class="form-select form-select-sm"
                                disabled>
                                <option value="">Select</option>

                                @foreach ($projectTypes as $projectType)
                                    <option value="{{ $projectType->proj_type_cd }}" {{ old('projectTypeSelect', $others['project_type'] ?? '') === $projectType->proj_type_cd ? 'selected' : '' }}>
                                        {{ $projectType->proj_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                             <input type="hidden" name="projectTypeSelectHidden" value="{{$others['project_type']}}">
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label fw-bold">Project Name</label>

                            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                <span id="project_name"></span>

                                <div class="form-check">
                                    <input class="form-check-input toggleEdit" type="checkbox" data-target="#project_name_div">
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>

                            <div id="project_name_div" class="mt-2 d-none">
                                <input type="text" name="project_name_nv"  id="project_name_nv" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Owner Department</label>
                            <select name="owner_dept_cd" id="owner_dept_cd" class="form-select form-select-sm" required>
                                <option value="{{ $department->id }}" {{ old('owner_dept_cd', $project->owner_dept_cd ?? '') == $department->id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            </select>
                            <input type="hidden" name="owner_dept_cd_hidden" value="{{ $project->owner_dept_cd }}">
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Division Name</label>
                            {{-- Add new tag Name dipshikha 16-05-2026 Start --}}
                            <select id="division_cd" name="division_cd" class="form-select form-select-sm"
                                data-user-subdivision="{{ session('userMapping')->sub_division_cd ?? '' }}" @if (session('userMapping')->office_type_cd === 'DO' || session('userMapping')->office_type_cd === 'SDO') selected disabled @endif required>
                                <option value="">Select Division</option>
                                @foreach ($div as $d)
                                    <option value="{{ $d->division_cd }}" @if (session('userMapping')->office_type_cd === 'DO' || session('userMapping')->office_type_cd === 'SDO') selected @endif>
                                        {{ $d->division_name }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Add new tag Name dipshikha 16-05-2026 End --}}
                        </div>
                        {{-- division field not required because for higher-level users, they are not mapped to a
                        single/main division Dipshika--}}
                        {{-- <input type="hidden" name="division_cd" value="{{ session('userMapping')->division_cd }}"> --}}
                        <div class="col-sm-3">
                            <label class="form-label">Sub Division Name</label>
                            <select name="sub_division_cd" id="sub_division_cd" class="form-select form-select-sm">
                                <option value="" disable selected hidden>Choose One</option>
                                {{-- Dynamic content --}}
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Project Start Date</label>
                            <input type="date" name="project_start_date" id="project_start_date"
                                class="form-control form-control-sm"
                                value="{{ old('project_start_date', $project->project_start_date ?? '') }}" readonly />
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label fw-bold">Stipulated End Date</label>

                            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                <span id="project_end_date"></span>

                                <div class="form-check">
                                    <input class="form-check-input toggleEdit" type="checkbox" data-target="#project_end_date_div">
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>

                            <div id="project_end_date_div" class="mt-2 d-none">
                                <input type="date" name="project_end_date_nv" id="project_end_date_nv" class="form-control form-control-sm">
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <label class="form-label fw-bold">Project Awarded To</label>

                            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                <span>{{ optional($constructors->firstWhere('regn_no',$project->project_awarded_to))->contractors_name }}</span>

                                <div class="form-check">
                                    <input class="form-check-input toggleEdit" type="checkbox" data-target="#project_awarded_to_div">
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>

                            <div id="project_awarded_to_div" class="mt-2 d-none">
                                <select name="project_awarded_to" id="project_awarded_to" class="form-select form-select-sm">
                                    @foreach($constructors as $constructor)
                                        <option value="{{ $constructor->regn_no }}"
                                            {{ $project->project_awarded_to==$constructor->regn_no?'selected':'' }}>
                                            {{ $constructor->contractors_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    Have not found what you are looking for?
                                    <a href="javascript:void(0)" class="text-primary" id="add-constructor">Click to add</a>
                                </small>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label fw-bold">Project Estimated Cost (₹)</label>

                            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                <span id="est_proj_cost"></span>

                                <div class="form-check">
                                    <input class="form-check-input toggleEdit" type="checkbox" data-target="#est_proj_cost_div">
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>

                            <div id="est_proj_cost_div" class="mt-2 d-none">
                                <input type="number" name="est_proj_cost_nv" id="est_proj_cost_nv" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label fw-bold">Defect Liability Period</label>

                            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                <span id="defect_liability_period"></span>

                                <div class="form-check">
                                    <input class="form-check-input toggleEdit" type="checkbox" data-target="#defect_liability_period_div">
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>

                            <div id="defect_liability_period_div" class="mt-2 d-none">
                                <input type="number" name="defect_liability_period_nv" id="defect_liability_period_nv" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label fw-bold">Work Order Amount (₹)</label>

                            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                <span id="work_order_amount"></span>

                                <div class="form-check">
                                    <input class="form-check-input toggleEdit" type="checkbox" data-target="#work_order_amount_div">
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>

                            <div id="work_order_amount_div" class="mt-2 d-none">
                                <input type="number" name="work_order_amount_nv" id="work_order_amount_nv" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label fw-bold">Work Order Number</label>

                            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                <span id="work_order_no"></span>

                                <div class="form-check">
                                    <input class="form-check-input toggleEdit" type="checkbox" data-target="#work_order_no_div">
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>

                            <div id="work_order_no_div" class="mt-2 d-none">
                                <input type="text" name="work_order_no_nv" id="work_order_no_nv" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label fw-bold">Work Order Issue Date</label>

                            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                <span id="work_order_issue_date"></span>

                                <div class="form-check">
                                    <input class="form-check-input toggleEdit" type="checkbox" data-target="#work_order_issue_date_div">
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>

                            <div id="work_order_issue_date_div" class="mt-2 d-none">
                                <input type="date" name="work_order_issue_date_nv" id="work_order_issue_date_nv" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label fw-bold">Project Scheme</label>

                            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                <span id="scheme_text" data-scheme-id="{{ $project->scheme_cd }}">
                                    {{ optional($schemes->firstWhere('scheme_id',$project->scheme_cd))->scheme_name }}
                                </span>

                                <div class="form-check">
                                    <input class="form-check-input toggleEdit" type="checkbox" data-target="#scheme_div">
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>

                            <div id="scheme_div" class="mt-2 d-none">
                                <select name="scheme_cd" id="scheme_cd" class="form-select form-select-sm">
                                    @foreach($schemes as $scheme)
                                        <option value="{{ $scheme->scheme_id }}"
                                            {{ $project->scheme_cd==$scheme->scheme_id?'selected':'' }}>
                                            {{ $scheme->scheme_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label fw-bold">Project Technology Type</label>

                            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                <span>
                                    {{ optional($technologies->firstWhere('tech_type_cd', $others['tech_type_cd'] ?? null))->tech_type_descr ?? 'Not specified' }}
                                </span>

                                <div class="form-check">
                                    <input class="form-check-input toggleEdit" type="checkbox" data-target="#tech_div">
                                    <label class="form-check-label">Edit</label>
                                </div>
                            </div>

                            <div id="tech_div" class="mt-2 d-none">
                                <select name="tech_type_cd" id="tech_type_cd" class="form-select form-select-sm">
                                    @foreach($technologies as $technology)
                                        <option value="{{ $technology->tech_type_cd }}"
                                            {{ ($others['tech_type_cd'] ?? null) == $technology->tech_type_cd ? 'selected' : '' }}>
                                            {{ $technology->tech_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="fundingAgencyContainer" class="text-xs mt-2"></div>
                    {{-- end --}}
                </div>
             </div>



            {{-- referential asset section || load when project type is 'New works and Upgradation' --}}
            <div class="card mb-2" id="upgradationSection" style="display: none;">
                <div class="card-header text-light fw-bold text-uppercase" id="referentialAssetLabel">
                    Upgradation Section for Asset
                </div>
                <div class="card-body" id="divUpgRd" style="display:none;">
                    <!-- Radio Buttons -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ref_asset" id="rdoRoadWithSubAsset"
                                    value="exist" checked />
                                <label class="form-check-label" for="rdoRoadWithSubAsset">Road and/or Sub
                                    Assets</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ref_asset" id="rdoSubAsset"
                                    value="notExist" />
                                <label class="form-check-label" for="rdoSubAsset">Sub Asset of
                                    Asset</label>
                            </div>
                        </div>
                    </div>

                    <div id="asset_n_sub_asset_fields">
                        <!-- Select Road -->
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Select Road</label>
                                <select id="refRoadSelect" name='refRoadSelect' class="form-select form-select-sm">
                                    <option value="">Choose a road</option>
                                </select>
                            </div>
                        </div>

                        <!-- Scope Definition -->
                        <div class="nested-card p-2 mb-1">
                            <h6 class="text-rose-primary fw-bold">
                                <i class="fa-solid fa-ruler-combined me-2"></i>Scope Definition
                            </h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div id="txt_chainage_msg" class="text-danger fw-bold fs-6"></div>
                                </div>
                                <!-- Start Chainage -->
                                <div class="col-md-3 myTooltip">
                                    <label id="startChainageLabel" class="form-label">Upgradation From Chainage (KM)</label>
                                    <input type="number" step="0.001" placeholder="0.000" oninput="restrictInput(event)"
                                        name="txt_start_chainage" id="txt_start_chainage"
                                        class="form-control form-control-sm" value="{{ old('txt_start_chainage') }}" />
                                </div>

                                <div class="col-md-3 myTooltip">
                                    <label id="endChainageLabel" class="form-label">Upgradation To Chainage (KM)</label>
                                    <input type="number" step="0.001" placeholder="0.000" oninput="restrictInput(event)"
                                        name="txt_end_chainage" id="txt_end_chainage" class="form-control form-control-sm"
                                        value="{{ old('txt_end_chainage') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Culverts -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label">List of Culverts</label>
                            <select id="refCulvertSelect" class="form-select form-select-sm" multiple size="4">
                                <option value="">-- NA --</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label">List of Bridges</label>
                            <select id="refBridgeSelect" class="form-select form-select-sm" multiple size="4">
                                <option value="">-- NA --</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label">List of Retaining Walls</label>
                            <select id="refWallSelect" class="form-select form-select-sm" multiple size="4">
                                <option value="">-- NA --</option>
                            </select>
                        </div>
					</div>
                    <!-- Add Button -->

                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <button type="button" id="addRefAssetBtn" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-plus me-1"></i>
                                Add Assets
                            </button>
                        </div>
                    </div>


                    <!-- To count the rows -->
                    <input type="hidden" name="rowCount" id="rowCount" value="0">

                    <!-- Table -->
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered" id="refAssetTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Road</th>
									<th>From Chainage (KM)</th>
                                    <th>To Chainage (KM)</th>
                                    <th>Culverts</th>
                                    <th>Bridges</th>
									<th>Retaining Walls</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic Rows -->
                            </tbody>
                        </table>
                    </div>



                        <div id="newRoadDetailsForm" class="row g-3">
                            <div class="col-md-4">
                                <label for="total_road_length" class="form-label fw-bold">Total Road Length (Km)</label>
                                <input type="text" id="total_road_length" name="total_road_length" class="form-control" value="" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Road Category</label>
                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                    <span>
                                        {{optional($roadCategories->firstWhere('rd_catg_cd',$others['upgradation']['new_asset']['road_category'] ?? ''))->rd_catg_descr ?? '-'}}
                                    </span>

                                    <div class="form-check m-0">
                                        <input type="checkbox"
                                            class="form-check-input toggleEdit"
                                            data-target="#roadCategoryDiv">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                </div>

                                <div id="roadCategoryDiv" class="mt-2 d-none">
                                    <select id="road_category"
                                            name="road_category"
                                            class="form-select form-select-sm">
                                        @foreach($roadCategories as $roadCategory)
                                            <option value="{{ $roadCategory->rd_catg_cd }}"
                                                {{ ($others['upgradation']['new_asset']['road_category'] ?? '') == $roadCategory->rd_catg_cd ? 'selected' : '' }}>
                                                {{ $roadCategory->rd_catg_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Road Type</label>
                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">

                                    <span>
                                        {{optional($roadTypes->firstWhere('rd_type_cd',$others['upgradation']['new_asset']['road_type'] ?? ''))->rd_type_descr ?? '-'}}
                                    </span>

                                    <div class="form-check m-0">
                                        <input type="checkbox"
                                            class="form-check-input toggleEdit"
                                            data-target="#roadTypeDiv">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                </div>

                                <div id="roadTypeDiv" class="mt-2 d-none">
                                    <select id="road_type"
                                            name="road_type"
                                            class="form-select form-select-sm">
                                        @foreach($roadTypes as $roadType)
                                            <option value="{{ $roadType->rd_type_cd }}"
                                                {{ ($others['upgradation']['new_asset']['road_type'] ?? '') == $roadType->rd_type_cd ? 'selected' : '' }}>
                                                {{ $roadType->rd_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Road Owner</label>
                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">

                                    <span>
                                        {{optional($roadOwners->firstWhere('owner_cd',$others['upgradation']['new_asset']['road_owner'] ?? ''))->owner_name ?? '-'}}
                                    </span>

                                    <div class="form-check m-0">
                                        <input type="checkbox"
                                            class="form-check-input toggleEdit"
                                            data-target="#roadOwnerDiv">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                </div>

                                <div id="roadOwnerDiv" class="mt-2 d-none">
                                    <select id="road_owner"
                                            name="road_owner"
                                            class="form-select form-select-sm">
                                        @foreach($roadOwners as $roadOwner)
                                            <option value="{{ $roadOwner->owner_cd }}"
                                                {{ ($others['upgradation']['new_asset']['road_owner'] ?? '') == $roadOwner->owner_cd ? 'selected' : '' }}>
                                                {{ $roadOwner->owner_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    <div class="row g-3 align-items-end mt-2">
                        <div class="col-md-2">
                            <button type="button" id="addRefAssetNewBtn" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-plus me-2"></i>
                                Add New Asset
                            </button>
                        </div>
                    </div>

                    <div id="newAssetsForm" class="mt-3" style="display:none;">
                        <div class="d-flex justify-content-end mb-2">
                            <button type="button" id="closeNewAssetsForm" class="btn btn-danger btn-sm">
                                Remove
                            </button>
                    </div>

					<div class="row g-3">
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-bold">New Road Name</label>

                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center" id="newRoadNameCol">
                                    <span id="new_road_name"></span>

                                    <div class="form-check m-0">
                                        <input type="checkbox"
                                            class="form-check-input toggleEdit"
                                            data-target="#new_road_nameDiv">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                </div>

                                <div id="new_road_nameDiv" class="mt-2 d-none">
                                    <input class="form-control form-control-sm"
                                        type="text"
                                        id="new_road_name_nv"
                                        name="new_road_name_nv"
                                        placeholder="Enter Road Name">
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label fw-bold">Road Length(in KM)</label>

                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center" id="roadLengthCol">
                                    <span id="road_length"></span>

                                    <div class="form-check">
                                        <input class="form-check-input toggleEdit" type="checkbox" data-target="#road_length_div">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                </div>

                                <div id="road_length_div" class="mt-2 d-none">
                                   <input class="form-control form-control-sm" type="number" min="0" id="road_length_nv"
                                    name='road_length_nv' size="8"
                                    placeholder="Enter Road Length"/>
                                </div>
                            </div>
                        </div>
                    </div>

				<div class="card mt-3" id="priorityCard" style="display:none;">
                       <div class="card-header">
                            Road Alignment Sequence
                        </div>

                        <div class="card-body">
                            <p class="text-muted mb-2">
                                Drag the roads to define the order in which the existing/ new roads will be connected/merged to form the upgraded road.
                            </p>

                            <div id="roadPriorityList" class="list-group">
                            </div>
                    </div>
                </div>
			</div>

                <div class="card-body" id="divUpgHousing" style="display:none;">
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label">Building Category:</label>
                            <select class="form-select form-select-sm" id="buildingCategoryUpgradation">
                                <option value="">--Select Category--</option>
                            </select>
							<span class="spanHide text-danger text-xs mt-2" id="buildingCategoryUpgradation_error"></span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Select An Existing Building:</label>
                            <select class="form-select form-select-sm" id="upgBuildings" name="upgBuildings">
                                <option value="">--Loading Building---</option>
                            </select>
							<span class="spanHide text-danger text-xs mt-2" id="upgBuildings_error"></span>
                        </div>
                    </div>
                    <div class="row form-1-box" id="housingContainerUpg">
                        <div class="col-md-12">
                            <label for="">Is Maintained by NPWD?</label>
                            <label for="yes">Yes</label>
                            <input type="radio" id="rdo_yes_upg" name="rdo_maintained_by_upg" value="Y">
                            <label for="no">No</label>
                            <input type="radio" id="rdo_no_upg" name="rdo_maintained_by_upg" value="N"><br>
                        </div>

                        <div class="col-md-3">
                            <label for="asset_geo_location_lat">Latitude</label>
                            <input type="text" id="asset_geo_location_lat_upg" class="form-control form-control-sm"
                                name="asset_geo_location_lat_upg" value="" placeholder="Latitude" disabled>

                        </div>
                        <div class="col-md-3">
                            <label for="asset_geo_location_lng">Longitude</label>
                            <input type="text" id="asset_geo_location_lng_upg" class="form-control form-control-sm"
                                name="asset_geo_location_lng_upg" value="" placeholder="Longitude" disabled>
                        </div>

                        <div class="col-md-3">
                            <label for="building_location_id_upg">Location</label>
                            <input type="text" id="building_location_id_upg" class="form-control form-control-sm"
                                name="building_location_id_upg" value="" placeholder="Location" disabled>
                        </div>
                    </div>
                    <div class="row form-1-box border my-2 py-2" id="housingContainerOtherUpg">
                        <div class="col-sm-12 col-md-3">
                            <label for="building_type_upg">Building Type <span class="star text-danger">*</span></label>
                            <select id="building_type_upg" name="building_type_upg" class="form-control form-control-sm">
                                <option value="">--Select Building Type--</option>
                            </select>
                            <span class="spanHide text-danger text-xs mt-2" id="building_type_cd_error"></span>
                        </div>

                        <div class="col-sm-12 col-md-3">
                            <label for="owning_dept_upg">Owning Department <span class="star text-danger">*</span></label>
                            <select id="owning_dept_upg" name="owning_dept_upg" class="form-control form-control-sm">
                                <option value="">--Select Owning Department--</option>
                            </select>
                            <span class="spanHide text-danger text-xs mt-2" id="owning_dept_error"></span>
                        </div>

                        <div class="col-sm-12 col-md-3">
                            <label for="buildingCategoryUpg">
                                Building Category <small class="text-muted">(Change if required)</small>
                            </label>
                            <select class="form-select form-select-sm" name="buildingCategoryUpg"
                                id="buildingCategoryUpg">
                                <option value="">--Select Category--</option>
                            </select>
							<span class="spanHide text-danger text-xs mt-2" id="buildingCategoryUpg_error"></span>
                        </div>

                        <div class="col-sm-12 col-md-3" id="quarterContainer">
                            <label for="quarter_no">Quarter No <span class="star text-danger">*</span></label>
                            <input type="text" id="quarter_no" class="form-control form-control-sm" name="quarter_no"
                                value="" placeholder="Enter Quarter No">
                            <span class="spanHide text-danger text-xs mt-2" id="quarter_no_error"></span>	  
                        </div>
                    </div>
                </div>
            </div>

            {{-- New Assets/Sub Assets Count Section --}}
            <div class="card mb-2" id="newAssetSubAssetCountSection">
                <div class="card-header text-light fw-bold text-uppercase">
                    Add New Assets
                </div>
                <div class="card-body">
                    {{-- hidden section as asked by client --}}
                    {{-- Friendly remainder: do not remove, will thraw lots of JS error --}}
                    <div class="form-check form-check-inline" style="display: none;">
                        <input class="form-check-input" type="radio" name="rdo_type" id="opt1" value="1"
                            checked />
                        <label class="form-check-label text-sm" for="opt1">New road with/without sub
                            asset(s)</label>
                    </div>
                    {{-- end of hidden section --}}

                    <!-- Section for new road with or without sub asset(s) -->
                    <div id="divNewRd" style="display:none;">
                        <div class="row mb-1 pb-2">
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-bold">New Road Name</label>

                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                    <span id="slnewRdNew"></span>

                                    <div class="form-check m-0">
                                        <input type="checkbox"
                                            class="form-check-input toggleEdit"
                                            data-target="#newRoadNameDiv">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                </div>

                                <div id="newRoadNameDiv" class="mt-2 d-none">
                                    <input class="form-control form-control-sm"
                                        type="text"
                                        id="slnewRdNew_nv"
                                        name="slnewRdNew_nv"
                                        placeholder="Enter Road Name">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Road Category</label>
                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">

                                    <span>
                                        {{ optional($roadCategories->firstWhere('rd_catg_cd', $others['road_category'] ?? ''))->rd_catg_descr ?? '-' }}
                                    </span>

                                    <div class="form-check m-0">
                                        <input type="checkbox"
                                            class="form-check-input toggleEdit"
                                            data-target="#roadCategoryNewDiv">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                </div>

                                <div id="roadCategoryNewDiv" class="mt-2 d-none">
                                    <select id="road_category_new"
                                            name="road_category_new"
                                            class="form-select form-select-sm">
                                        @foreach($roadCategories as $roadCategory)
                                            <option value="{{ $roadCategory->rd_catg_cd }}"
                                                {{ ($others['road_category'] ?? '') == $roadCategory->rd_catg_cd ? 'selected' : '' }}>
                                                {{ $roadCategory->rd_catg_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Road Type</label>
                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">

                                    <span>
                                        {{ optional($roadTypes->firstWhere('rd_type_cd', $others['road_type'] ?? ''))->rd_type_descr ?? '-' }}
                                    </span>

                                    <div class="form-check m-0">
                                        <input type="checkbox"
                                            class="form-check-input toggleEdit"
                                            data-target="#roadTypeNewDiv">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                </div>

                                <div id="roadTypeNewDiv" class="mt-2 d-none">
                                    <select id="road_type_new"
                                            name="road_type_new"
                                            class="form-select form-select-sm">
                                        @foreach($roadTypes as $roadType)
                                            <option value="{{ $roadType->rd_type_cd }}"
                                                {{ ($others['road_type'] ?? '') == $roadType->rd_type_cd ? 'selected' : '' }}>
                                                {{ $roadType->rd_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <label class="form-label fw-bold">Road Length(in KM)</label>

                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                    <span id="rdLength"></span>

                                    <div class="form-check">
                                        <input class="form-check-input toggleEdit" type="checkbox" data-target="#rdLength_div">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                </div>

                                <div id="rdLength_div" class="mt-2 d-none">
                                   <input class="form-control form-control-sm" type="number" min="0" id="rdLength_nv"
                                    name='rdLength_nv' size="8"
                                    placeholder="Enter Road Length"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Road Owner</label>
                                <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">

                                    <span>
                                        {{ optional($roadOwners->firstWhere('owner_cd', $others['road_owner'] ?? ''))->owner_name ?? '-' }}
                                    </span>

                                    <div class="form-check m-0">
                                        <input type="checkbox"
                                            class="form-check-input toggleEdit"
                                            data-target="#roadOwnerNewDiv">
                                        <label class="form-check-label">Edit</label>
                                    </div>
                                </div>

                                <div id="roadOwnerNewDiv" class="mt-2 d-none">
                                    <select id="road_owner_new"
                                            name="road_owner_new"
                                            class="form-select form-select-sm">
                                        @foreach($roadOwners as $roadOwner)
                                            <option value="{{ $roadOwner->owner_cd }}"
                                                {{ ($others['road_owner'] ?? '') == $roadOwner->owner_cd ? 'selected' : '' }}>
                                                {{ $roadOwner->owner_name }}
                                            </option>
                                        @endforeach
                                    </select>
								</div>
                            </div>
						</div>
					</div>

                    <div class="card-body" id="divNewHousing" style="display:none;">
                        <div class="col-md-12">
                            <div class="row form-1-box">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Is Maintained by NPWD?</label>

                                    <div class="border rounded p-2 bg-light d-inline-flex align-items-center gap-3">
                                        <span id="maintained_by_text">
                                            {{ ($others['new_building_maintain_by_npwd'] ?? 'Y') == 'Y' ? 'Yes' : 'No' }}
                                        </span>

                                        <div class="form-check">
                                            <input class="form-check-input toggleEdit"
                                                type="checkbox"
                                                data-target="#maintainedByDiv">
                                            <label class="form-check-label">Edit</label>
                                        </div>
                                    </div>

                                    <div id="maintainedByDiv" class="mt-2 d-none">
                                        <label for="rdo_yes">Yes</label>
                                        <input type="radio" id="rdo_yes" name="rdo_maintained_by" value="Y">

                                        <label for="rdo_no" class="ms-2">No</label>
                                        <input type="radio" id="rdo_no" name="rdo_maintained_by" value="N">

                                        <span class="text-danger text-xs d-block mt-1" id="maintained_by_error"></span>
                                        <input type="hidden" id="e" name="maintained_by" value="Y">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Geo Location</label>
                                    <div class="border rounded p-2 bg-light d-inline-flex align-items-center gap-3">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <span id="geo_location_text" name="geo_location_text">
                                                    {{ ($others['new_building_lat'] ?? '-') }},
                                                    {{ ($others['new_building_lng'] ?? '-') }}
                                                </span>
                                            </div>

                                            <div class="col-auto">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input toggleEdit"
                                                        type="checkbox"
                                                        data-target="#geoLocationDiv">
                                                    <label class="form-check-label">Edit</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="geoLocationDiv" class="mt-3 d-none">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Set Geo Location From Google Map</label>
                                            </div>

                                            <div class="col-md-4">
                                                <input type="text"
                                                    id="asset_geo_location"
                                                    name="asset_geo_location"
                                                    class="form-control form-control-sm"
                                                    readonly>
                                            </div>

                                            <div class="col-md-3">
                                                <button type="button" class="classSetGeoLocation btn btn-xs btn-primary text-xm py-1 rounded-1"
                                                    id="btnSetGeoLocation">
                                                    Set Geo Location
                                                </button>
                                            </div>

                                            <div class="col-md-12 text-center my-2">
                                                <strong>OR</strong>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Latitude <span class="text-danger">*</span></label>
                                                <input type="text" id="asset_geo_location_lat" name="asset_geo_location_lat" class="form-control form-control-sm" placeholder="Enter Latitude">
                                            </div>

                                            <div class="col-md-3">
                                                <label>Longitude <span class="text-danger">*</span></label>
                                                <input type="text" id="asset_geo_location_lng" name="asset_geo_location_lng" class="form-control form-control-sm" placeholder="Enter Longitude">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="row form-1-box border my-2 py-2">
                                <div class="col-sm-12 col-md-4 mb-3">
                                    <label class="form-label fw-bold">Building Category</label>

                                    <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                        <span id="building_category_text">
                                            {{ ($others['new_building_class_name'] ?? '')}}
                                        </span>

                                        <div class="form-check">
                                            <input class="form-check-input toggleEdit"
                                                type="checkbox"
                                                data-target="#buildingCategoryDiv">
                                            <label class="form-check-label">Edit</label>
                                        </div>
                                    </div>

                                    <div id="buildingCategoryDiv" class="mt-2 d-none">
                                        <label for="residential">Residential</label>
                                        <input type="radio" id="residential" name="building_class_cd" value="0">

                                        <label for="nonResidential" class="ms-2">Non Residential</label>
                                        <input type="radio" id="nonResidential" name="building_class_cd" value="1">

                                        <label for="rental" class="ms-2">Rental</label>
                                        <input type="radio" id="rental" name="building_class_cd" value="2">

                                        <span class="text-danger text-xs d-block mt-1" id="building_class_cd_error"></span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 mb-3">
                                    <label class="form-label fw-bold">Location</label>

                                    <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center">
                                        <span id="building_location_text">{{ ($others['new_building_location_name'] ?? '')}}</span>

                                        <div class="form-check">
                                            <input class="form-check-input toggleEdit"
                                                type="checkbox"
                                                data-target="#buildingLocationDiv">
                                            <label class="form-check-label">Edit</label>
                                        </div>
                                    </div>

                                    <div id="buildingLocationDiv" class="mt-2 d-none">
                                        <select class="form-control form-control-sm"
                                                id="building_location_cd"
                                                name="building_location_cd">
                                            <option value="">Choose one</option>
                                        </select>

                                        <span class="text-danger text-xs d-block mt-1" id="building_location_cd_error"></span>	  
                                </div>
                            </div>
						</div>
					</div>
				</div>


                    {{-- hidden section --}}
                    <!-- Add Button -->
                    <div class="row g-3 align-items-end" style="display:none;">
                        <div class="col-md-2">
                            <button type="button" id="adNoOfAssetsBtn" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i>
                                Add Assets
                            </button>
                        </div>
                    </div>

                    <!-- Table of Added Items -->
                    <div class="table-responsive mt-3" style="display: none;">
                        <table class="table table-bordered table-sm" id="noOfAssetsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Road Name</th>
                                    <th>Length </th>
                                    <th>Culvert(s) </th>
                                    <th>Bridge(s) </th>
                                    <th>Retaining Wall(s) </th>
                                    <th style="width:60px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="noOfAssetsTableBody">
                                <!-- rows inserted here -->
                            </tbody>
                        </table>
                    </div>
                    {{-- end of hidden section --}}
                </div>
            </div>
			</div>

            <!-- Maintenance Asset Section -->
            <div class="card mb-2" id="maintenanceSection" style="display: none;">
                <div class="card-header text-light fw-bold text-uppercase">Select Asset for Maintenance</div>
                <div class="card-body" id="divMtnRd" style="display:none;">
                    <div class="row mb-2">
                        <div class="col">
                            <label class="form-label">List of Roads</label>
                            <select id="maintRoads" class="form-select form-select-sm" multiple size="5">
                                <option value="">-- NA --</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">List of Culverts</label>
                            <select id="maintCulverts" class="form-select form-select-sm" multiple size="5">
                                <option value="">-- NA --</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Select Bridges</label>
                            <select class="form-select form-select-sm" id="maintBridges" multiple size="5">
                                <option value="">--NA---</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Select Retaining Walls</label>
                            <select class="form-select form-select-sm" id="maintWalls" multiple size="5">
                                <option value="">--NA---</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-start">
                        <button type="button" class="btn btn-primary btn-sm" id="addMaintAssetBtn" onclick="addMaintenanceAssets()">
                            <i class="fa-solid fa-plus me-2"></i>
                            Add Assets
                        </button>
                    </div>

                    <!-- For counting the row of the table -->
                    <input type="hidden" name="mnt_rowCount" id="mnt_rowCount" value="0">

                    <div class="table-responsive mt-2">
                        <table class="table table-bordered" id="mtnAssetTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Road(s)</th>
                                    <th>Culverts</th>
                                    <th>Bridges</th>
                                    <th>Retaining Walls</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic Rows -->
                            </tbody>
                        </table>
                    </div>
				</div>

                <div class="card-body" id="divMtnHousing" style="display:none;">
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label">Building Category:</label>
                            <select class="form-select form-select-sm" id="buildingCategory">
                                <option value="">--Select Category--</option>
                            </select>
						<span class="spanHide text-danger text-xs mt-2" id="buildingCategory_error"></span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Select An Existing Building:</label>
                            <select class="form-select form-select-sm" id="maintBuildings" name="maintBuildings">
                                <option value="">--Loading Building---</option>
                            </select>
						<span class="spanHide text-danger text-xs mt-2" id="maintBuildings_error"></span>
                        </div>
                    </div>
                    <div class="row form-1-box" id="housingContainer">
                        <div class="col-md-12">
                            <label for="">Is Maintained by NPWD?</label>
                            <label for="yes">Yes</label>
                            <input type="radio" id="rdo_yes_maint" name="rdo_maintained_by" value="Y">
                            <label for="no">No</label>
                            <input type="radio" id="rdo_no_maint" name="rdo_maintained_by" value="N"><br>
                        </div>

                        <div class="col-md-3">
                            <label for="asset_geo_location_lat_maint">Latitude</label>
                            <input type="text" id="asset_geo_location_lat_maint" class="form-control form-control-sm"
                                name="asset_geo_location_lat_maint" value="" placeholder="Latitude" disabled>

                        </div>
                        <div class="col-md-3">
                            <label for="asset_geo_location_lng_maint">Longitude</label>
                            <input type="text" id="asset_geo_location_lng_maint" class="form-control form-control-sm"
                                name="asset_geo_location_lng_maint" value="" placeholder="Longitude" disabled>
                        </div>
                    </div>
                    <div class="row form-1-box border my-2 py-2" id="housingContainerOther">
                        <div class="col-sm-12 col-md-3">
                            <label for="building_location_id">Location</label>
                            <input type="text" id="building_location_id" class="form-control form-control-sm"
                                name="building_location_id" value="" placeholder="Location" disabled>
                        </div>

                        <div class="col-sm-12 col-md-3">
                            <label for="building_type">Building Type</label>
                            <input type="text" id="building_type" class="form-control form-control-sm"
                                name="building_type" value="" placeholder="Building Type" disabled>
                        </div>

                        <div class="col-sm-12 col-md-3">
                            <label for="owning_dept_maint">Owning Department</label>
                            <input type="text" id="owning_dept_maint" class="form-control form-control-sm"
                                name="owning_dept_maint" value="" placeholder="Owning Department" disabled>
                        </div>
                    </div>	  
                </div>
            </div>

            <div class="row mt-2" id="kmlCard" style="background-color: #efeeee; display: none;">
                <div class="col-12 pt-2">
                    <legend class="w-auto px-2" style="font-size:13px ">
                        Upload Documents
                    </legend>
                    <div class="p-2">
                        <div>
                            <p class="text-sm text-info text-underline"><strong>
                                    <i class="fa fa-info-circle mr-1 text-sm"></i>Important:
                                </strong></p>
                            <ul class="text-xs text-secondary">
                                <li>
                                    <strong>
                                        File Type:
                                    </strong>
                                    Only KML files are supported for upload in this section.
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
                                <label for="road_kml_file">1. Upload KML File for this Road:<span
                                        class="star">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="file" class="text-xs text-success" id="road_kml_file"
                                    name="road_kml_file" onchange="showRemoveBtn('road_kml_file')">
                                <button type="button" id="removeBtn_road_kml_file"
                                    class="outline-0 border border-danger text-danger text-xs rounded-1"
                                    style="background:rgb(252, 217, 217); display:none;"
                                    onclick="removeFile('road_kml_file')">
                                    <i class="fa fa-trash mr-1 text-xs"></i>
                                    Remove
                                </button>
                                @error('road_kml_file')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-2" id="workItemSection">
                <div class="card-header text-light fw-bold text-uppercase">
                    Item of Work
                </div>

                <div class="card-body">
                    <input type="hidden" id="work_rowCount" name="work_rowCount" value="0">

                    <div class="row g-3">

                        <div class="col-sm-12 col-md-6">
                            <label class="form-label" for="item_cd">Item of Work <span class="text-danger">*</span></label>
                            <select name="item_cd" id="item_cd" class="form-select form-select-sm">
                                <option value="">Select Item of Work</option>
                                @foreach ($itemOfWorks as $workItem)
                                    <option
                                        value="{{ $workItem->item_cd }}"
                                        data-unit="{{ $workItem->unit_cd }}"
                                        data-sub-items='@json($workItem->sub_items)'
                                        {{ old('item_cd', $currentItem->item_cd ?? '') == $workItem->item_cd ? 'selected' : '' }}>
                                        {{ $workItem->item_name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-danger small" id="item_cd_error"></span>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label class="form-label" for="unit">Item Unit <span class="text-danger">*</span></label>
                            <input type="text" name="unit" id="unit" class="form-control form-control-sm bg-light"
                                placeholder="Item unit" value="{{ old('unit', $currentItem->unit ?? '') }}" readonly />
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label class="form-label" for="quantity">Item Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control form-control-sm" placeholder="0.00"
                                value="{{ old('quantity', $currentItem->quantity ?? '') }}" step="0.01" min="0" />
                            <span class="text-danger small" id="quantity_error"></span>
                        </div>

                        <div class="col-12" id="sub_items_container" style="display: none;">
                            <label class="form-label font-bold text-secondary">Available Sub Items</label>
                            <div id="sub_items_checkboxes" class="d-flex flex-wrap gap-2 p-3 border rounded bg-light">
                                <!-- Checkboxes will be populated dynamically via AJAX -->
                            </div>
                        </div>

                        <div class="col-sm-1 d-flex align-items-end">
                            <button type="button"
                                id="addWorkItemBtn"
                                class="btn btn-primary btn-sm w-100">
                                Add
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered" id="workItemTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Sub Item</th>
                                    <th>Unit</th>
                                    <th>Qty</th>
                                    <th>Work Plan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="workPlanModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Work Plans</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" id="currentWorkItemId">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Start Date</label>
                                    <input type="date" id="planStart" class="form-control">
                                    <span class="text-danger small" id="planStart_error"></span>
                                </div>

                                <div class="col-md-4">
                                    <label>End Date</label>
                                    <input type="date" id="planEnd" class="form-control">
                                    <span class="text-danger small" id="planEnd_error"></span>
                                </div>

                                <div class="col-md-4">
                                    <label>Predecessor</label>
                                    <select id="precedenceItem" class="form-select">
                                        <option value="">No Predecessor</option>
                                    </select>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary mt-3" id="addPlanRow">
                                Add Plan
                            </button>
                            <hr>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Predecessor</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="planTableBody">
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="savePlans">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="work_items_json" id="work_items_json">

            <div class="row form-1-box border mt-2" style="margin: 0 1.5px;" id="asset_document_container">
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
                                            File Size Limit:
                                        </strong>
                                        The maximum allowed file size is 2 MB.
                                    </li>
                                </ul>
                            </div>
                            <div class="row form-1-box my-1">
                                <div class="col-md-4">
                                    <label for="workorder">1. Upload Sanction Order (PDF):</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" class="text-xs text-success" id="workOrder" name="workOrder"
                                        accept=".pdf">
                                    <button type="button" id="removeBtn_workOrder"
                                        class="outline-0 border border-danger text-danger text-xs rounded-0"
                                        style="background:rgb(252, 217, 217); display:none;"
                                        onclick="removeFile('workOrder')">
                                        <i class="fa fa-trash mr-1 text-xs"></i>
                                        Remove
                                    </button>
                                    @error('workOrder')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row form-1-box my-1">
                                <div class="col-md-4">
                                    <label for="projectPlan">2. Upload Work Order (PDF):</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" name="projectPlan" id="projectPlan"
                                        class="text-xs text-success" accept=".pdf">
                                    <button type="button" id="removeBtn_projectPlan"
                                        class="outline-0 border border-danger text-danger text-xs rounded-0"
                                        style="background:rgb(252, 217, 217); display:none;"
                                        onclick="removeFile('projectPlan')">
                                        <i class="fa fa-trash mr-1 text-xs"></i>
                                        Remove
                                    </button>
                                    @error('projectPlan')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row form-1-box my-1">
                                <div class="col-md-4">
                                    <label for="drpDocument">3. Upload Work Plans (PDF):</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" class="text-xs text-success" id="drpDocument"
                                        name="drpDocument" accept=".pdf">
                                    <button type="button" id="removeBtn_drpDocument"
                                        class="outline-0 border border-danger text-danger text-xs rounded-0"
                                        style="background:rgb(252, 217, 217); display:none;"
                                        onclick="removeFile('drpDocument')">
                                        <i class="fa fa-trash mr-1 text-xs"></i>
                                        Remove
                                    </button>
                                    @error('drpDocument')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row form-1-box my-1">
                                <div class="col-md-4">
                                    <label for="design_doc">4. Upload Agreement (PDF):</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" class="text-xs text-success" id="designDoc" name="designDoc"
                                        accept=".pdf">
                                    <button type="button" id="removeBtn_designDoc"
                                        class="outline-0 border border-danger text-danger text-xs rounded-0"
                                        style="background:rgb(252, 217, 217); display:none;"
                                        onclick="removeFile('designDoc')">
                                        <i class="fa fa-trash mr-1 text-xs"></i>
                                        Remove
                                    </button>
                                    @error('designDoc')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-header text-light fw-bold text-uppercase">
                    Reason & Document
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="reason" class="form-label">
                                Enter Reason
                            </label>
                            <textarea class="form-control" name="reason" id="reason" rows="4" placeholder="Enter reason..."></textarea>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label fw-bold">Modification Order<span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="supporting_document" id="supporting_document" accept="application/pdf">
                            <small class="text-muted">
                                Only PDF allowed (Max: 2MB)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button type="submit" id="PmsUpdateBtn" class="btn btn-primary btn-sm rounded-0 mt-2">
                    <i class="fa fa-save"></i> Update
                </button>
                <button type="button" class="btn btn-secondary btn-sm rounded-0 mt-2"
                    onclick="{{ isset($project_cd) && !empty($project_cd) ? 'history.back()' : 'location.reload()' }}">
                    <i class="fa fa-backward"></i> Cancel
                </button>
				
				
            <input type="hidden" id="subItems" name="sub_items">
        </form>
		<x-building.coordinates.set-coordinate />

        {{-- coded by nitish: modal to add master data --}}
        <div class="modal fade" id="contractorModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Add Contractor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="contractorForm" class="row">
                            @csrf
                            <div class="col-md-6 mb-2">
                                <label>Registration No</label>
                                <input type="text" name="regn_no" class="form-control form-control-sm"
                                    placeholder="Enter registration number" required>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Contractor Name</label>
                                <input type="text" name="contractors_name" class="form-control form-control-sm"
                                    placeholder="Enter Contractor Name" required>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Category</label>
                                <select name="category_cd" class="form-control form-control-sm">
                                    @foreach ($contractorCategories as $cat)
                                        <option value="{{ $cat->category_cd }}">{{ $cat->category_descr }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Address line 1</label>
                                <input type="text" name="address_line_1" class="form-control form-control-sm"
                                    placeholder="Enter Address Line 1">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Address line 2</label>
                                <input type="text" name="address_line_2" class="form-control form-control-sm"
                                    placeholder="Enter Address Line 2">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>District</label>
                                <select name="district_cd" class="form-control form-control-sm">
                                    @foreach ($districts as $dist)
                                        <option value="{{ $dist->dist_code }}">{{ $dist->dist_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>State</label>
                                <select name="state_cd" class="form-control form-control-sm">
                                    @foreach ($states as $state)
                                        <option value="{{ $state->state_code }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Phone</label>
                                <input type="tel" maxlength="10" minlength="10" pattern="[0-9]{10}" name="phone_no"
                                    placeholder="Enter Phone Number" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="Enter Email Address"
                                    class="form-control form-control-sm">
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-sm btn-primary">Save Contractor</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-success-modal />
    <x-warning-modal />
@endsection
@push('scripts')
<script src="{{ asset('js/pms/modification/requestMdfy.js') }}" defer></script>
<script src="{{ asset('js/pms/common/showFundingAgencies.js') }}" defer></script>
<script src="{{ asset('js/pms/validation/script.js') }}" defer></script>
<script src="{{ asset('js/modal/script.js') }}" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const projectData = @json($project ?? null);
    const changesData = @json($changes ?? null);
    const documents = @json($documents ?? []);
    const department = @json($department->id ?? null);
    const workItems = @json($projectWorkItems ?? []);
    const itemOfWorks = @json($itemOfWorks);

    if (typeof loadDataIntoForm === 'function') {
        loadDataIntoForm(projectData, changesData,workItems,itemOfWorks, documents, department);
    }
});
</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pms/common/modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">

    <style>
        :root {
            --primary: var(--oamis-primary, #0b6b4a);
            --secondary: color-mix(in srgb, var(--oamis-primary, #0b6b4a) 14%, #ffffff);
            --accent: color-mix(in srgb, var(--oamis-primary, #0b6b4a) 14%, #ffffff);
        }

        .text-rose-primary {
            color: var(--primary) !important;
        }

		.badge-rose-primary {
            background-color: var(--secondary);
            color: var(--primary);
            border: 1px solid color-mix(in srgb, var(--primary) 25%, white);
            font-weight: 600;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
        }

        .card-body {
            border: none;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            padding: 10px;

        }

        .card-header {
            background: #0b6b4a !important;
            border: 1px solid #0b6b4a !important;
            border-bottom: 0 !important;
            color: #ffffff !important;
            font-family: var(--oamis-font) !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            letter-spacing: .04em;
            padding: 0.85rem 1rem;
            text-align: left;
            text-transform: uppercase;

        }

        .card-header,
        .card-header * {
            color: #ffffff !important;
        }

            {
                {

                -- .form-control form-control-sm,
                .form-select form-select-sm {
                    border-radius: 10px;
                    border: 2px solid #f3f4f6;
                    transition: all 0.3s;
                    overflow-x: auto;
                    font-size: small;
                }

                --
            }
        }

            {
                {

                -- .form-control form-control-sm:focus,
                .form-select form-select-sm:focus {
                    border-color: #9fc9f6ff;
                    box-shadow: 0 0 0 0.2rem #9fc9f6ff;
                }

                --
            }
        }

        .btn-rose {
            background: rgba(11, 107, 74, .1);
            border: 1px solid rgba(11, 107, 74, .24);
            border-radius: 999px;
            color: var(--oamis-primary, #0b6b4a);
            transition: all 0.3s;
        }

        .btn-rose:hover {
            background-color: #0b6b4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(11, 107, 74, .18);
            color: white;
        }

            {
                {
                -- .form-check-input:checked {
                    background-color: var(--primary);
                    border-color: var(--primary);
                }

                --
            }
        }

        .table {
            border-radius: 12px;
            overflow-y: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table thead {
            background: linear-gradient(135deg, var(--accent) 0%, #9fc9f6ff 100%);
        }

        .table thead th {
            color: var(--primary);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.6rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .nested-card {
            background: #f9fafb;
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
        }

        .upload-zone {
            border-radius: 12px;
            padding: 0.5rem;
            transition: all 0.3s;
        }

        .dropdown-checkbox {
            position: relative;
        }

        .dropdown-checkbox-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1050;
            display: none;
        }

        .dropdown-checkbox-menu.show {
            display: block;
        }

        .dropdown-checkbox-menu label {
            display: flex;
            align-items: center;
            padding: 4px 10px;
            cursor: pointer;
        }

        .dropdown-checkbox-menu label:hover {
            background-color: #f8f9fa;
        }

        .dropdown-checkbox input[type="search"] {
            border: none;
            border-bottom: 1px solid #ddd;
            width: 100%;
            padding: 5px 10px;
            outline: none;
        }

        {{-- CSS-Added By Pulak --}}

        #contractorModal .modal-body {
            overflow-y: auto !important;
            max-height: 75vh;
        }

        #cropModal {
            z-index: 1060 !important;
        }

        /* Prevent body scroll lock from affecting contractor modal */
        body.modal-open #contractorModal .modal-body {
            overflow-y: auto !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    {{-- script for dynamic section by project type --}}
    <script src="{{ asset('js/pms/road/dynamicSectionByType/script.js') }}"></script>
    <script src="{{ asset('js/pms/script.js') }}"></script>
    <script src="{{ asset('js/pms/upgradation/script.js') }}"></script>
	<script src="{{ asset('js/building/script.js') }}" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM"></script>																  
    <script src="{{ asset('js/road/assets/removeSelectedFile/script.js') }}" defer></script>

    <script>

        $(document).on('change', '.toggleEdit', function () {

            let target = $(this).data('target');

            if ($(this).is(':checked')) {
                $(target).removeClass('d-none');
            } else {
                $(target).addClass('d-none');
                $(target).find('input,textarea').val('');
            }
        });
        // open modal
        $('#add-constructor').click(function() {
            $('#contractorModal').modal('show');
        });

        // submit contractor form
        $('#contractorForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('contractor.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(res) {

                    // add new contractor to dropdown
                    $('#project_awarded_to').append(
                        `<option value="${res.regn_no}" selected>${res.contractors_name}</option>`
                    );

                    // close modal
                    $('#contractorModal').modal('hide');

                    // reset form
                    $('#contractorForm')[0].reset();

                    alert("Contractor added and selected successfully");
                },
                error: function(err) {
                    alert("Something went wrong");
                }
            });
        });
    </script>
@endpush
