@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid" style="position: relative;">
            <div class="row text-sm">
                <div class="col-sm-12 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Manage Projects</li>
                    </ol>
                </div>
            </div>
            {{-- alert section --}}
            <div id="alertContainer" style="text-align: right">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert"
                        style="position: absolute; top: 0; right: 0; z-index: 1;">
                        <strong> <i class="fa fa-check-circle mr-1"></i> Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close btn-xs" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-inline-block" role="alert"
                        style="position: absolute; top: 1px; right: 2px; z-index: 1;">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <section class="content mt-1">
        {{-- draft list section --}}

        <form id="myForm"
            action="{{ isset($project) ? route('project.update', $project->project_cd) : route('manage-project') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($project))
                @method('PUT')
                @php
                    $others = json_decode($project->others ?? '{}', true);
                @endphp
            @endif

            <h4 id="editModeText" style="display:none;"></h4>
            <input type="hidden" id="project_cd" name="project_cd">
            <input type="hidden" id="deleted_images" name="deleted_images">
            <input type="hidden" id="deleted_documents" name="deleted_documents">

            <!-- Hidden field to track if form is submitted as draft -->
            <input type="hidden" name="draft_id" value="">

            {{-- project information section --}}
            <div class="card mb-2">
                <div class="card-header text-light fw-bold text-uppercase">Project Information</div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-sm-3">
                            <label class="form-label">Project Type <span class="text-danger">*</span></label>
                            @php
                                $hasExceptionOrError = $errors->any() || session('error') || session('failed');
                            @endphp
                            <select name="projectTypeSelect" id="projectTypeSelect" class="form-select form-select-sm"
                                required>
                                <option value="">Select</option>

                                @foreach ($projectTypes as $projectType)
                                    <option value="{{ $projectType->proj_type_cd }}"
                                        {{ !$hasExceptionOrError && old('projectTypeSelect', $others['project_type'] ?? '') === $projectType->proj_type_cd ? 'selected' : '' }}>
                                        {{ $projectType->proj_type_descr }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="project_name" id="project_name" class="form-control form-control-sm"
                                value="{{ old('project_name', $project->project_name ?? '') }}" minlength="3"
                                maxlength="100" placeholder="Enter project name" required />
                            <small id="projectNameError" class="text-danger"></small>
                        </div>

                        <!-- Owner Department -->
                        <div class="col-sm-3">
                            <label class="form-label">Owner Department <span class="text-danger">*</span></label>
                            <select name="owner_dept_cd" id="owner_dept_cd" class="form-select form-select-sm" required>
                                <option value="{{ $department->id }}"
                                    {{ old('owner_dept_cd', $project->owner_dept_cd ?? '') == $department->id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Division Name <span class="text-danger">*</span></label>
                            <select id="division_cd" name = "division_cd" class="form-select form-select-sm"
                                data-user-subdivision="{{ session('userMapping')->sub_division_cd ?? '' }}"
                                @if (session('userMapping')->office_type_cd === 'DO' || session('userMapping')->office_type_cd === 'SDO') selected
                                    disabled @endif
                                required>
                                <option value="">Select Division</option>
                                @foreach ($div as $d)
                                    <option value="{{ $d->division_cd }}"
                                        @if (session('userMapping')->office_type_cd === 'DO' || session('userMapping')->office_type_cd === 'SDO') selected @endif>
                                        {{ $d->division_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- division field not required because for higher-level users, they are not mapped to a single/main division --}}
                        {{-- <input type="hidden" name="division_cd" value="{{ session('userMapping')->division_cd }}"> --}}

                        <div class="col-sm-3">
                            <label class="form-label">Sub Division Name <span class="text-danger">*</span></label>
                            <select name="sub_division_cd" id="sub_division_cd" class="form-select form-select-sm" required>
                                <option value="" disable selected hidden>Choose One</option>
                                {{-- Dynamic content --}}
                            </select>
                        </div>

                        <!-- Project Start Date -->
                        <div class="col-sm-3">
                            <label class="form-label">Project Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="project_start_date" id="project_start_date"
                                class="form-control form-control-sm"
                                value="{{ old('project_start_date', $project->project_start_date ?? '') }}" required />
                        </div>

                        <!-- Project End Date -->
                        <div class="col-sm-3">
                            <label class="form-label">Stipulated End Date <span class="text-danger">*</span></label>
                            <input type="date" name="project_end_date" id="project_end_date"
                                class="form-control form-control-sm"
                                value="{{ old('project_end_date', $project->project_end_date ?? '') }}" required />
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Project Awarded To <span class="text-danger">*</span></label>
                            <select name="project_awarded_to" id="project_awarded_to" class="form-select form-select-sm"
                                required>
                                <option value="">Select Constructor</option>
                                @foreach ($constructors as $constructor)
                                    <option value="{{ $constructor->regn_no }}"
                                        {{ old('project_awarded_to', $project->project_awarded_to ?? '') == $constructor->regn_no ? 'selected' : '' }}>
                                        {{ $constructor->contractors_name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                Have not found what you are looking for?
                                <a href="javascript:void(0)" class="text-primary" id="add-constructor">Click to add</a>
                            </small>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Project Estimated Cost (₹)<span class="text-danger">*</span></label>
                            <input type="number" name="est_proj_cost" id="est_proj_cost"
                                class="form-control form-control-sm"
                                value="{{ old('est_proj_cost', $project->est_proj_cost ?? '') }}"
                                placeholder="Enter the estimated cost" required />
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Defect Liability Period <span class="text-danger">*</span></label>
                            <input type="number" name="defect_liability_period" id="defect_liability_period"
                                class="form-control form-control-sm"
                                value="{{ old('defect_liability_period', $project->defect_liability_period ?? '') }}"
                                placeholder="Enter the no of months" required />
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Work Order Amount (₹)<span class="text-danger">*</span></label>
                            <input type="number" name="work_order_amount" id="work_order_amount"
                                class="form-control form-control-sm"
                                value="{{ old('work_order_amount', $project->work_order_amount ?? '') }}"
                                placeholder="Enter the work order amount" required />
                        </div>

                        {{-- New fields added: nitish --}}
                        <div class="col-sm-3">

                            <label class="form-label">Work Order Number <span class="text-danger">*</span></label>
                            <input type="text" name="work_order_no" id="work_order_no"
                                class="form-control form-control-sm"
                                value="{{ old('work_order_no', $project->work_order_no ?? '') }}"
                                placeholder="Enter work order number" required />
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Work Order Issue Date <span class="text-danger">*</span></label>
                            <input type="date" name="work_order_issue_date" id="work_order_issue_date"
                                class="form-control form-control-sm"
                                value="{{ old('work_order_issue_date', $project->work_order_issue_date ?? '') }}"
                                required />
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Project Scheme <span class="text-danger">*</span></label>
                            <select name="scheme_cd" id="scheme_cd" class="form-select form-select-sm" required>
                                <option value="">Select Scheme</option>
                                @foreach ($schemes as $scheme)
                                    <option value="{{ $scheme->scheme_id }}"
                                        {{ old('scheme_cd', $project->scheme_cd ?? '') == $scheme->scheme_id ? 'selected' : '' }}>
                                        {{ $scheme->scheme_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- End of new fields: nitish --}}
                    </div>
                    {{-- section to show funding agency details: Nitish --}}
                    <div id="fundingAgencyContainer" class="text-xs mt-2"></div>
                </div>
            </div>

            {{-- referential asset section || load when project type is 'New works and Upgradation' --}}
            <div class="card mb-2" id="upgradationSection">
                <div class="card-header text-light fw-bold text-uppercase" id="referentialAssetLabel">
                    Upgradation Section for Asset
                </div>
                <div class="card-body">
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
                                    <label class="form-label">Start Chainage (KM)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictInput(event)" name="txt_start_chainage" id="txt_start_chainage"
                                        class="form-control form-control-sm" value="{{ old('txt_start_chainage') }}" />
                                </div>

                                <div class="col-md-3 myTooltip">
                                    <label class="form-label">End Chainage (KM)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictInput(event)" name="txt_end_chainage" id="txt_end_chainage"
                                        class="form-control form-control-sm" value="{{ old('txt_end_chainage') }}" />
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
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label">List of Pavements</label>
                            <select id="refPVSelect" class="form-select form-select-sm" multiple size="4">
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
                                    <th>Culverts</th>
                                    <th>Bridges</th>
                                    <th>Retaining Walls</th>
                                    <th>Pavements</th>
                                    <th>New Culverts</th>
                                    <th>New Bridges</th>
                                    <th>New Retaining Walls</th>
                                    <th>New Pavements</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic Rows -->
                            </tbody>
                        </table>
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
                            <div class="col-md-4">
                                <label class="form-label">New Road Name</label>
                                <input type="text" class="form-control" name="new_road_name" id="new_road_name">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Road Length (in KM)</label>
                                <input type="number" class="form-control" name="road_length" id="road_length"
                                    min="0" step="0.01">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">No of New Culverts</label>
                                <input type="number" class="form-control"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="new_culverts"
                                    id="new_culverts" min="0">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">No of New Bridges</label>
                                <input type="number" class="form-control"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="new_bridges"
                                    id="new_bridges" min="0">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">No of New Retaining Walls</label>
                                <input type="number" class="form-control"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="new_retaining_walls"
                                    id="new_retaining_walls" min="0">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">No of New Pavements</label>
                                <input type="number" class="form-control"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="new_pavements"
                                    id="new_pavements" min="0">
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            {{-- New Assets/Sub Assets Count Section --}}
            <div class="card mb-2" id="newAssetSubAssetCountSection">
                <div class="card-header text-light fw-bold text-uppercase">
                    Add Count of New Assets/Sub Assets
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
                    <div id="divNewRd">
                        <div class="row mb-1 pb-2">
                            <div class="col-12 col-md-3">
                                <label class="form-label">New Road Name: </label>
                                <input class="form-control form-control-sm" type="text" id="slnewRdNew"
                                    name='slnewRdNew' value="{{ old('slnewRdNew') }}" size="8"
                                    placeholder="Enter Road Name" />
                                <small id="roadNameError" class="text-danger"></small>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Road Length(in KM): </label>
                                <input class="form-control form-control-sm" type="number" min="0" id="rdLength"
                                    name='rdLength' value="{{ old('rdLength') }}" size="8"
                                    placeholder="Enter Road Length" />
                            </div>

                            <div class="col-12">
                                <hr>
                            </div>

                            <div class="col-sm-12 col-md-3">
                                <label class="form-label fw-bold">Total Number of New Culvert:</label>
                                <input type="hidden" name="culvert" value="CD Works">

                                <input type="number" class="form-control form-control-sm " id="noOfCl"
                                    name="new_work_culvert_count" value="{{ old('new_work_culvert_count', '0') }}"
                                    min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>

                            <div class="col-sm-12 col-md-3">
                                <label class="form-label fw-bold">Total Number of New Bridge:</label>
                                <input type="hidden" name="bridge" value="Bridges">

                                <input type="number" class="form-control form-control-sm " id="noOfBr"
                                    name="new_work_bridge_count" value="{{ old('new_work_bridge_count', '0') }}"
                                    min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>

                            <div class="col-sm-12 col-md-3">
                                <label class="form-label fw-bold">Total Number of New Retaining
                                    Wall:</label>
                                <input type="hidden" name="retain_wall" value="Retain Wall">

                                <input type="number" class="form-control form-control-sm " id="noOfrtw"
                                    name="new_work_retain_wall_count"
                                    value="{{ old('new_work_retain_wall_count', '0') }}" min="0"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>

                            <div class="col-sm-12 col-md-3">
                                <label class="form-label fw-bold">Total Number of New Pavement(s):</label>
                                <input type="hidden" name="pavements" value="Pavements">

                                <input type="number" class="form-control form-control-sm " id="noOfPvm"
                                    name="new_work_pavement_count" value="{{ old('new_work_pavement_count', '0') }}"
                                    min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                    <th>Pavement(s) </th>
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

            <!-- Maintenance Asset Section -->
            <div class="card mb-2" id="maintenanceSection" style="display: none;">
                <div class="card-header text-light fw-bold text-uppercase">Select Asset for Maintenance
                </div>
                <div class="card-body">
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
                        <div class="col">
                            <label class="form-label">Pavements</label>
                            <select id="maintPv" class="form-select form-select-sm" multiple size="5">
                                <option value="">-- NA --</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-start">
                        <button type="button" class="btn btn-primary btn-sm" onclick="addMaintenanceAssets()">
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
                                    <th>Pavements</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic Rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Upload Documents -->
            <div class="row form-1-box border mt-2" style="margin: 0 1.5px;" id="asset_image_container">
                <div class="col-md-12 pt-2" style="background-color: #efeeee;">
                    <fieldset class="">
                        <legend class="w-auto px-2" style="font-size:13px ">
                            Upload Site Images
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
                                        Only JPG, JPEG files are supported for upload in this section.
                                    </li>
                                    <li>
                                        <strong>
                                            File Size Limit:
                                        </strong>
                                        The maximum allowed file size is 1 MB.
                                    </li>
                                    <li>
                                        <strong>
                                            Note:
                                        </strong>
                                        Multiple files can be uploaded.
                                    </li>
                                </ul>
                            </div>
                            <div class="row form-1-box my-1">
                                <div class="col-md-4">
                                    <label for="images">Upload Site Images: </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" class="text-xs text-success" id="images" name="images[]"
                                        accept=".jpg,.jpeg" multiple>
                                    <div id="imagesPreview" class="row mt-2"></div>
                                    @error('images.*')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

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
                                    <label for="workorder">1. Upload Saction Order (PDF):</label>
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
            <div class="text-end">
                <button type="submit" id="PmsSaveBtn" class="btn btn-success btn-sm rounded-0 mt-2">
                    <i class="fa fa-save"></i> Submit
                </button>
                <button type="button" id="PmsUpdateBtn" class="btn btn-primary btn-sm rounded-0 mt-2"
                    style="display: none;">
                    <i class="fa fa-save"></i> Update
                </button>
                <button type="button" class="btn btn-secondary btn-sm rounded-0 mt-2"
                    onclick="{{ isset($project_cd) && !empty($project_cd) ? 'history.back()' : 'location.reload()' }}">
                    <i class="fa fa-backward"></i> Cancel
                </button>

            </div>
            <input type="hidden" id="subItems" name="sub_items">
        </form>

        {{-- coded by nitish: modal to add master data --}}
        <div class="modal fade" id="contractorModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Add Contractor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="contractorForm" class="row" enctype="multipart/form-data">
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
                            <!-- start by Pulak -->
                            <div class="col-12 mt-2">
                                <h6 class="text-xs font-weight-bold text-primary border-bottom pb-1">Bank
                                    Details</h6>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label>PAN No.</label>
                                <input type="text" name="pan_no" class="form-control form-control-sm"
                                    placeholder="Enter PAN Number" maxlength="12">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label>Bank Account No.</label>
                                <input type="text" name="bank_acc_no" id="modal_bank_acc_no"
                                    class="form-control form-control-sm numeric-only" placeholder="Enter Account Number"
                                    maxlength="16">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label>Confirm Bank Account No.</label>
                                <input type="text" name="confirm_bank_acc_no" id="modal_confirm_bank_acc_no"
                                    class="form-control form-control-sm numeric-only" placeholder="Confirm Account Number"
                                    maxlength="16" onpaste="return false;">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label>IFSC Code</label>
                                <input type="text" name="ifsc_code" class="form-control form-control-sm"
                                    placeholder="Enter IFSC Code" maxlength="20">
                            </div>

                            <div class="row form-1-box border mt-2" style="margin: 0 1.5px;"
                                id="asset_document_container">
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
                                                            Pdf file Size Limit:
                                                        </strong>
                                                        The maximum allowed file size is 2 MB.
                                                    </li>
                                                    <li>
                                                        <strong>
                                                            Photo file Size Limit:
                                                        </strong>
                                                        The maximum allowed file size is 1 MB.
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="row form-1-box my-1">
                                                <div class="col-md-4">
                                                    <label for="passportPhoto">1. Upload Passport Size Photo
                                                        (jpg,jpeg):</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="file" class="text-xs text-success" id="passportPhoto"
                                                        name="_passportPhoto_raw" accept=".jpg,.jpeg">

                                                    {{-- Hidden input that will carry the cropped image as base64 --}}
                                                    <input type="hidden" name="passportPhoto" id="passportPhotoCropped">

                                                    {{-- Preview of final cropped image --}}
                                                    <div id="passportPreviewContainer"
                                                        style="margin-top:10px; display:none;">
                                                        <p class="text-xs text-muted mb-1">Final Preview:</p>
                                                        <img id="passportPreview"
                                                            style="width:120px; height:150px; object-fit:cover; border:2px solid #28a745; border-radius:5px;">
                                                    </div>

                                                    <button type="button" id="removeBtn_passportPhoto"
                                                        class="outline-0 border border-danger text-danger text-xs rounded-0 mt-1"
                                                        style="background:rgb(252, 217, 217); display:none;"
                                                        onclick="removePhoto('passportPhoto')">
                                                        <i class="fa fa-trash mr-1 text-xs"></i> Remove
                                                    </button>

                                                    @error('passportPhoto')
                                                        <div class="text-danger text-xs">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row form-1-box my-1">
                                                <div class="col-md-4">
                                                    <label for="panCardDoc">2. Upload PAN Card (PDF):</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="file" name="panCardDoc" id="panCardDoc"
                                                        class="text-xs text-success" accept=".pdf">
                                                    <button type="button" id="removeBtn_panCardDoc"
                                                        class="outline-0 border border-danger text-danger text-xs rounded-0"
                                                        style="background:rgb(252, 217, 217); display:none;"
                                                        onclick="removeFile('panCardDoc')">
                                                        <i class="fa fa-trash mr-1 text-xs"></i>
                                                        Remove
                                                    </button>
                                                    @error('panCardDoc')
                                                        <div class="text-danger text-xs">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <!-- end by Pulak -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-sm btn-primary">Save Contractor</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal code ended --}}

        <!-- Draft Project Details Table -->
        @if (!isset($project))
            <div id="draftSection">
                <h6 class="p-2 mt-2 border border-primary text-light bg-primary">
                    <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                        LIST OF DRAFT PROJECT DETAILS FOR NEW WORKS
                    </span>
                </h6>
                <div class="d-flex text-sm justify-content-end mb-2">
                    <button id="freezeBtnForNewProjectRoad" class="btn btn-sm btn-outline-info rounded-0 text-bold">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Send selected new project data for finalization
                    </button>
                </div>
                <div class="container-fluid border py-2">
                    <table class="table-responsive text-xs rounded-0 table table-bordered table-striped user_list"
                        id="project_details_table">
                        <thead class="theader text-white" style="background-color:#417DBE">
                            <th class="text-center" style="min-width: 3rem;">Sl No.</th>
                            <th class="text-center" style="min-width: 6rem;">Project Code</th>
                            <th class="text-center" style="min-width: 8rem;">Project Name</th>
                            <th class="text-center" style="min-width: 5rem;">Project Type</th>
                            <th class="text-center" style="min-width: 6rem;">Owner Department</th>
                            <th class="text-center" style="min-width: 6rem;">Division</th>
                            <th class="text-center" style="min-width: 6rem;">Sub Division</th>
                            <th class="text-center" style="min-width: 8rem;">Project Start Date</th>
                            <th class="text-center" style="min-width: 8rem;">Project End Date</th>
                            <th class="text-center" style="min-width: 8rem;">Project Status</th>
                            <th class="text-center" style="min-width: 8rem;">Project Awarded To</th>
                            <th class="text-center" style="min-width: 8rem;">Estimated Project Cost</th>
                            <th class="text-center" style="min-width: 8rem;">Defect Liability Period (in
                                Month)</th>
                            <th class="text-center" style="min-width: 8rem;">Work Order Amount(Rs.)</th>
                            {{-- New: Nitish --}}
                            <th class="text-center" style="min-width: 8rem;">Work Order Number</th>
                            <th class="text-center" style="min-width: 8rem;">Work Order Issued</th>
                            <th class="text-center" style="min-width: 8rem;">Scheme Name</th>
                            {{-- End: Nitish --}}
                            <th class="text-center" style="min-width: 8rem;">Rejection Reason</th>
                            <th class="text-center" style="min-width: 8rem;">New Asset</th>
                            <th class="text-center" style="min-width: 8rem;">Funding Agency</th>
                            <th class="text-center" style="min-width: 8rem;">Work Item</th>
                            <th class="text-center" style="min-width: 10rem;">Action</th>
                            <th class="text-center" style="min-width: 8rem;">Select</th>
                        </thead>
                        <tbody>
                            @foreach ($draftDetailsForNewWorks as $index => $draft)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $draft->project_cd }}</td>
                                    <td class="text-center">{{ $draft->project_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_type ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->owner_department ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->division_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->sub_div_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_start_date ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_end_date ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_status ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->contractor_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->est_proj_cost ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->defect_liability_period ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">{{ $draft->work_order_amount ?? 'N/A' }}</td>
                                    {{-- New: Nitish --}}
                                    <td class="text-center">{{ $draft->work_order_no ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->work_order_issue_date ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">{{ $draft->scheme_name ?? 'N/A' }}</td>

                                    {{-- End: Nitish --}}
                                    <td class="text-center">{{ $draft->reason_of_rejection ?? '-' }}</td>
                                    <td class="text-center">
                                        <button
                                            class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold rounded-0"
                                            data-toggle="modal"
                                            data-target="#showAssetForNewWorks{{ $draft->project_cd }}"
                                            onclick="getNewWorksAssetDetails('{{ $draft->project_cd }}')">
                                            <i class="fas fa-eye text-xs"></i>
                                            View
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button
                                            class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold rounded-0"
                                            data-toggle="modal"
                                            data-target="#showFundingAgencyDetails{{ $draft->scheme_cd }}"
                                            onclick="getFundingAgencyDetails('{{ $draft->scheme_cd }}', '{{ $draft->work_order_amount }}')">
                                            <i class="fas fa-eye text-xs"></i>
                                            View
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        @if (in_array($draft->project_cd, $workItems_exist))
                                            <button
                                                class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold rounded-0"
                                                data-toggle="modal" data-target="#ItemsModal{{ $draft->project_cd }}"
                                                onclick="showItemsDetail('{{ $draft->project_cd }}')">
                                                <i class="fas fa-eye text-xs"></i>
                                                View
                                            </button>
                                        @endif
                                        {{-- Link to work item module: nitish --}}
                                        <a href="{{ route('pms.work-item.index', $draft->project_cd) }}"
                                            class="btn btn-xs btn-outline-primary p-1 fw-bold rounded-0">
                                            <i class="fas fa-plus-circle"></i>
                                            Add
                                        </a>
                                        {{-- End of linking --}}
                                    </td>
                                    <td class="text-center">

                                        <button
                                            class="text-sm outline-0 btn btn-xs btn-outline-primary inline fw-bold rounded-0"
                                            onclick="editWithLocal('{{ $draft->project_cd }}', '{{ $department->id }}')">

                                            <i class="fas fa-edit"></i> Edit
                                        </button>


                                        <button
                                            class="text-sm outline-0 btn btn-xs btn-outline-danger inline fw-bold rounded-0 ms-1"
                                            onclick="openDeleteModal('{{ $draft->project_cd }}')">

                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="selected-asset"
                                            data-project-cd="{{ $draft->project_cd }}" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if (!isset($project))
            <div id="draftSectionUpg">
                <h6 class="p-2 mt-2 border border-primary text-light bg-primary">
                    <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                        LIST OF DRAFT PROJECT DETAILS FOR UPGRADATION
                    </span>
                </h6>
                <div class="d-flex text-sm justify-content-end mb-2">
                    <button id="freezeBtnForUpgProjectRoad" class="btn btn-sm btn-outline-info rounded-0 text-bold">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Send selected upgradation project data for finalization
                    </button>
                </div>
                <div class="container-fluid border py-2">
                    <table class="table-responsive text-xs rounded-0 table table-bordered table-striped user_list"
                        id="project_details_table_upg">
                        <thead class="theader text-white" style="background-color:#417DBE">
                            <th class="text-center" style="min-width: 3rem;">Sl No.</th>
                            <th class="text-center" style="min-width: 6rem;">Project Code</th>
                            <th class="text-center" style="min-width: 8rem;">Project Name</th>
                            <th class="text-center" style="min-width: 5rem;">Project Type</th>
                            <th class="text-center" style="min-width: 6rem;">Owner Department</th>
                            <th class="text-center" style="min-width: 6rem;">Division</th>
                            <th class="text-center" style="min-width: 6rem;">Sub Division</th>
                            <th class="text-center" style="min-width: 8rem;">Project Start Date</th>
                            <th class="text-center" style="min-width: 8rem;">Project End Date</th>
                            <th class="text-center" style="min-width: 8rem;">Project Status</th>
                            <th class="text-center" style="min-width: 8rem;">Project Awarded To</th>
                            <th class="text-center" style="min-width: 8rem;">Estimated Project Cost</th>
                            <th class="text-center" style="min-width: 8rem;">Defect Liability Period (in
                                Month)</th>
                            <th class="text-center" style="min-width: 8rem;">Work Order Amount(Rs.)</th>
                            {{-- New: Nitish --}}
                            <th class="text-center" style="min-width: 8rem;">Work Order Number</th>
                            <th class="text-center" style="min-width: 8rem;">Work Order Issued</th>
                            <th class="text-center" style="min-width: 8rem;">Scheme Name</th>
                            <th class="text-center" style="min-width: 8rem;">Agency Name</th>
                            {{-- End: Nitish --}}
                            <th class="text-center" style="min-width: 8rem;">Rejection Reason</th>
                            <th class="text-center" style="min-width: 8rem;">New Asset</th>
                            {{-- New: Dipshikha --}}
                            <th class="text-center" style="min-width: 8rem;">Funding Agency</th>
                            {{-- End: Dipshikha --}}
                            <th class="text-center" style="min-width: 8rem;">Work Item</th>
                            <th class="text-center" style="min-width: 8rem;">Action</th>
                            <th class="text-center" style="min-width: 8rem;">Select</th>
                        </thead>
                        <tbody>
                            @foreach ($draftDetailsforUpgradation as $index => $draft)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $draft->project_cd }}</td>
                                    <td class="text-center">{{ $draft->project_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_type ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->owner_department ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->division_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->sub_div_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_start_date ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_end_date ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_status ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->contractor_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->est_proj_cost ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->defect_liability_period ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->work_order_amount ?? 'N/A' }}</td>
                                    {{-- New: Nitish --}}
                                    <td class="text-center">{{ $draft->work_order_no ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->work_order_issue_date ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->scheme_cd ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->funding_agency_cd ?? 'N/A' }}</td>
                                    {{-- End: Nitish --}}
                                    <td class="text-center">{{ $draft->reason_of_rejection ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($draft->hasUpgradationAssets)
                                            <button
                                                class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold rounded-0"
                                                onclick="showModalNewAssetDetail('{{ $draft->project_cd }}','draft')">
                                                <i class="fas fa-eye text-xs"></i>
                                            </button>
                                        @else
                                            <span class="text-danger text-bold">NA</span>
                                        @endif
                                    </td>
                                    {{-- by dipshikha start --}}
                                    <td class="text-center">
                                        <button
                                            class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold rounded-0"
                                            data-toggle="modal"
                                            data-target="#showFundingAgencyDetails{{ $draft->scheme_cd }}"
                                            onclick="getFundingAgencyDetails('{{ $draft->scheme_cd }}', '{{ $draft->work_order_amount }}')">
                                            <i class="fas fa-eye text-xs"></i>
                                            View
                                        </button>
                                    </td>
                                    {{-- end by dipshikha --}}
                                    <td class="text-center">
                                        @if (in_array($draft->project_cd, $workItems_exist))
                                            <button
                                                class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold"
                                                style="padding: 0.15rem 0.5rem;" data-toggle="modal"
                                                data-target="#ItemsModal{{ $draft->project_cd }}"
                                                onclick="showItemsDetail('{{ $draft->project_cd }}')">
                                                <i class="fas fa-eye text-xs"></i>
                                                View
                                            </button>
                                        @endif
                                        {{-- by dipshikha --}}
                                        <a href="{{ route('pms.work-item.index', ['project' => $draft->project_cd]) }}"
                                            class="btn btn-xs btn-outline-primary p-1 fw-bold">
                                            <i class="fas fa-plus-circle"></i>
                                            Add
                                        </a>
                                        {{-- end --}}
                                    </td>
                                    <td class="text-center">

                                        <button class="btn btn-xs btn-outline-primary fw-bold"
                                            onclick="editWithLocal('{{ $draft->project_cd }}', '{{ $department->id }}')">

                                            <i class="fas fa-edit"></i> Edit
                                        </button>


                                        <button class="btn btn-xs btn-outline-danger fw-bold ms-1"
                                            onclick="openDeleteModal('{{ $draft->project_cd }}')">

                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="selected-asset"
                                            data-project-cd="{{ $draft->project_cd }}" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if (!isset($project))
            <div id="draftSectionUpMaint">
                <h6 class="p-2 mt-2 border border-primary text-light bg-primary">
                    <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                        LIST OF DRAFT PROJECT DETAILS FOR MAINTENANCE
                    </span>
                </h6>
                <div class="d-flex text-sm justify-content-end mb-2">
                    <button id="freezeBtnForMtnProjectRoad" class="btn btn-sm btn-outline-info rounded-0 text-bold">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Send selected maintenance project data for finalization
                    </button>
                </div>
                <div class="container-fluid border py-2">
                    <table class="table-responsive text-xs rounded-0 table table-bordered table-striped user_list"
                        id="project_details_table_maintenance">
                        <thead class="theader text-white" style="background-color:#417DBE">
                            <th class="text-center" style="min-width: 3rem;">Sl No.</th>
                            <th class="text-center" style="min-width: 6rem;">Project Code</th>
                            <th class="text-center" style="min-width: 8rem;">Project Name</th>
                            <th class="text-center" style="min-width: 5rem;">Project Type</th>
                            <th class="text-center" style="min-width: 6rem;">Owner Department</th>
                            <th class="text-center" style="min-width: 6rem;">Division</th>
                            <th class="text-center" style="min-width: 6rem;">Sub Division</th>
                            <th class="text-center" style="min-width: 8rem;">Project Start Date</th>
                            <th class="text-center" style="min-width: 8rem;">Project End Date</th>
                            <th class="text-center" style="min-width: 8rem;">Project Status</th>
                            <th class="text-center" style="min-width: 8rem;">Project Awarded To</th>
                            <th class="text-center" style="min-width: 8rem;">Estimated Project Cost</th>
                            <th class="text-center" style="min-width: 8rem;">Defect Liability Period (in
                                Month)</th>
                            <th class="text-center" style="min-width: 8rem;">Work Order Amount(Rs.)</th>
                            {{-- New: Nitish --}}
                            <th class="text-center" style="min-width: 8rem;">Work Order Number</th>
                            <th class="text-center" style="min-width: 8rem;">Work Order Issued</th>
                            <th class="text-center" style="min-width: 8rem;">Scheme Name</th>
                            {{-- End: Nitish --}}
                            <th class="text-center" style="min-width: 8rem;">Rejection Reason</th>
                            <th class="text-center" style="min-width: 8rem;">Asset</th>
                            {{-- New: Dipshikha --}}
                            <th class="text-center" style="min-width: 8rem;">Funding Agency</th>
                            {{-- End: Dipshikha --}}
                            <th class="text-center" style="min-width: 8rem;">Work Item</th>
                            <th class="text-center" style="min-width: 8rem;">Action</th>
                            <th class="text-center" style="min-width: 8rem;">Select</th>
                        </thead>
                        <tbody>
                            @foreach ($draftDetailsforMaintenance as $index => $draft)
                                <tr data-project-type="{{ $draft->project_type_cd }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $draft->project_cd }}</td>
                                    <td class="text-center">{{ $draft->project_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_type ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->owner_department ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->division_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->sub_div_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_start_date ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">{{ $draft->project_end_date ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->project_status ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->contractor_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->est_proj_cost ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        {{ $draft->defect_liability_period ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">{{ $draft->work_order_amount ?? 'N/A' }}</td>
                                    {{-- New: Nitish --}}
                                    <td class="text-center">{{ $draft->work_order_no ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $draft->work_order_issue_date ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">{{ $draft->scheme_name ?? 'N/A' }}</td>

                                    {{-- End: Nitish --}}
                                    <td class="text-center">{{ $draft->reason_of_rejection ?? '-' }}</td>
                                    {{-- by dipshikha start --}}
                                    <td class="text-center">
                                        @if ($hasMaintenanceAssets)
                                            <button
                                                class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold rounded-0"
                                                onclick="showModalMaintenanceDetail('{{ $draft->project_cd }}','draft')">
                                                <i class="fas fa-eye text-xs"></i>
                                            </button>
                                        @else
                                            <span class="text-danger text-bold">NA</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button
                                            class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold rounded-0"
                                            data-toggle="modal"
                                            data-target="#showFundingAgencyDetails{{ $draft->scheme_cd }}"
                                            onclick="getFundingAgencyDetails('{{ $draft->scheme_cd }}', '{{ $draft->work_order_amount }}')">
                                            <i class="fas fa-eye text-xs"></i>
                                            View
                                        </button>
                                    </td>
                                    {{-- end by dipshikha --}}
                                    <td class="text-center">
                                        @if (in_array($draft->project_cd, $workItems_exist))
                                            <button
                                                class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold"
                                                style="padding: 0.15rem 0.5rem;" data-toggle="modal"
                                                data-target="#ItemsModal{{ $draft->project_cd }}"
                                                onclick="showItemsDetail('{{ $draft->project_cd }}')">
                                                <i class="fas fa-eye text-xs"></i>
                                                View
                                            </button>
                                        @endif
                                        {{-- Link to work item module: nitish --}}
                                        <a href="{{ route('pms.work-item.index', $draft->project_cd) }}"
                                            class="btn btn-xs btn-outline-primary p-1 fw-bold rounded-0">
                                            <i class="fas fa-plus-circle"></i>
                                            Add
                                        </a>
                                        {{-- End of linking --}}
                                    </td>
                                    <td class="text-center">

                                        <button class="btn btn-xs btn-outline-primary fw-bold"
                                            onclick="editWithLocal('{{ $draft->project_cd }}', '{{ $department->id }}')">

                                            <i class="fas fa-edit"></i> Edit
                                        </button>


                                        <button class="btn btn-xs btn-outline-danger fw-bold ms-1"
                                            onclick="openDeleteModal('{{ $draft->project_cd }}')">

                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="selected-asset"
                                            data-project-cd="{{ $draft->project_cd }}" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Items Modal : Nitish -->
        <div id="ItemsModal" class="ItemsModal">
            <!-- Modal content -->
            <div class="ItemsModalContent">
                <div class="modal-header m-1 p-0">
                    <h5 class="modal-title text-uppercase text-md text-primary font-bold" id="diseaseWiseTitle">
                        List of Items of Work under the selected project
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row justify-content-center align-item-center text-xs m-1 p-0"
                    id="modalItemsContainer">
                </div>
            </div>
        </div>

        <div id="showAssetForNewWorks" class="showAssetForNewWorks">
            <!-- Modal content -->
            <div class="ItemsModalContent modal-md">
                <div class="modal-header m-1 p-0">
                    <h5 class="modal-title text-uppercase text-md text-primary font-bold" id="diseaseWiseTitle">
                        New Assets Details for the New Works
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row justify-content-center align-item-center text-xs p-1"
                    id="dataSectionForNewWorks">
                </div>
            </div>
        </div>

        <div id="showFundingAgencyDetails" class="showFundingAgencyDetails">
            <!-- Modal content -->
            <div class="ItemsModalContent modal-md">
                <div class="modal-header m-1 p-0">
                    <h5 class="modal-title text-uppercase text-md text-primary font-bold" id="diseaseWiseTitle">
                        Funding Agency Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row justify-content-center align-item-center text-xs p-1"
                    id="dataSectionForAgencyDetails">
                </div>
            </div>
        </div>
    </section>
    <x-success-modal />
    <x-warning-modal />
    <!-- Custom Show Modal -->
    <div id="showModal" class="showModal">
        <!-- Modal content -->
        <div class="showModalContent">
            <span class="closeShowModal">&times;</span>
            <p class="text-md text-bold text-primary">
                <i class="fa fa-bars" aria-hidden="true"></i>
                List of Sub Asset Details
            </p>
            <div class="row text-xs" id="modalValContainer">
            </div>
        </div>
    </div>

    <div id="showModalNewAsset" class="showModalNewAsset">
        <div class="showModalNewAssetContent">
            <span class="closeShowModalNewAsset">&times;</span>
            <p class="text-md text-bold text-primary">
                <i class="fa fa-bars" aria-hidden="true"></i>
                Existing & New Asset Details
            </p>

            <div id="modalValContainerNewAsset" class="row text-xs"></div>
        </div>
    </div>

    {{-- -start by Dipshikha --}}
    <div id="showModalMaintenance" class="showModalMaintenance">
        <div class="showModalContentMaintenanace">
            <span class="closeShowModalMaintenance">&times;</span>
            <p class="text-md text-bold text-primary">
                <i class="fa fa-bars" aria-hidden="true"></i>
                Asset Details
            </p>
            <div class="row text-xs" id="modalValContainerMaintenance">
            </div>
        </div>
    </div>
    {{-- -end by Dipshikha --}}
    {{-- -start by Pulak --}}
    <div class="modal fade" id="deleteDraftModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Delete Draft</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="delete_project_cd">

                    <div class="mb-3">
                        <label>Remarks</label>



                        <textarea id="delete_remarks" class="form-control" placeholder="Enter reason for deletion..."></textarea>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                </div>

            </div>
        </div>
    </div>
    {{-- end by Pulak-- }}




    <!-- Partial Draft Projects Modal -->
    <div class="modal fade" id="partialProjectsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Saved Drafts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul id="draftList" class="list-group"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Add Sub Items Modal -->
    <div id="subItemModal" class="showModal">
        <div class="showModalContent modal-lg">
            <span class="closeShowModal">&times;</span>

            <p class="text-md text-bold text-primary mb-2">
                <i class="fa fa-bars"></i> Add Sub Items
            </p>

            <!-- ITEM TYPE -->
            <div class="mb-3">
                <label class="form-label">Item Type</label>
                <input type="text" id="modalItemType" class="form-control form-control-sm " readonly>
            </div>

            <!-- SUB ITEMS DROPDOWN -->
            <div class="mb-3">
                <label class="form-label">Sub Items</label>
                <div class="dropdown-checkbox">
                    <button type="button" id="subItemDropdown" class="form-control form-control-sm  text-start">
                        Select subitems
                    </button>
                    <div id="subItemMenu" class="dropdown-checkbox-menu shadow-sm">
                        <input type="search" id="subItemSearch" placeholder="Search..." />
                        <div id="subItemsOptions">
                            @foreach ($workSubItems as $item)
                            <label>
                                <input type="checkbox" class="subItemCheckbox" value="{{ $item->sub_item_cd }}"
                                    data-name="{{ $item->sub_item_name }}">
                                <span class="ms-2">{{ $item->sub_item_name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- DYNAMIC FIELDS GENERATED HERE -->
            <div id="subItemDetailsContainer"></div>
            <div class="text-end mt-3">
                <button type="button" id="addSubItemBtn" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> ADD
                </button>
                <button type="button" class="btn btn-sm btn-danger closeButton">CLOSE</button>
            </div>
        </div>
    </div>

    <!-- Custom add edit Sub Items Modal -->
    <div id="editSubItemModal" class="showModal">
        <!-- Modal content -->
        <div class="showModalContent">
            <span class="closeShowModal">&times;</span>
            <p class="text-md text-bold text-primary mb-2">
                <i class="fa fa-edit" aria-hidden="true"></i>
                Edit Sub Items
            </p>

            <!-- Item Type -->
            <div class="mb-3">
                <label class="form-label">Item Type</label>
                <input type="text" id="editModalItemType" class="form-control form-control-sm " readonly>
            </div>

            <!-- Select Sub Items -->
            <div class="mb-3">
                <label class="form-label">Select Sub Items</label>

                <div class="dropdown-checkbox">
                    <button type="button" id="editSubItemDropdown" class="form-control form-control-sm  text-start">
                        Select Sub Items
                    </button>

                    <div id="editSubItemMenu" class="dropdown-checkbox-menu shadow-sm">
                        <input type="search" id="editSubItemSearch" class="form-control form-control-sm  mb-2"
                            placeholder="Search sub items..." />

                        <div id="editSubItemOptions">
                            @foreach ($workSubItems as $item)
                            <label>
                                <input type="checkbox" value="{{ $item->sub_item_cd }}">
                                <span class="ms-2">{{ $item->sub_item_name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- <input type="hidden" name="editSubItemSelect[]" id="editSubItemHidden"> --}}
    </div>

    <!-- DYNAMIC FIELDS GENERATED HERE -->
    <div id="editsubItemDetailsContainer"></div>

    <!-- Buttons -->
    <div class="text-end">
        <button type="button" id="saveEditSubItemBtn" class="btn btn-primary btn-sm">Save
            Changes</button>
        <button type="button" class="btn btn-sm btn-danger closeButton">CLOSE</button>
    </div>
    </div>
    </div>


    <!-- View Sub Items Modal -->
    <div id="viewSubItemsModal" class="showModal">
        <!-- Modal content -->
        <div class="showModalContent">
            <span class="closeShowModal" id="closeViewSubItems">&times;</span>
            <p class="text-md text-bold text-primary mb-2">
                <i class="fa fa-bars" aria-hidden="true"></i>
                Sub Items Details
            </p>

            <!-- Table inside modal -->
            <div class="table-responsive text-xs">
                <table class="table table-bordered table-sm mb-0" id="viewSubItemsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Sub Item</th>
                            <th>Quantity</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- View Upgradation Sub Assets Modal -->
    <div id="viewUpgradationSubAssetsModal" class="showModal">
        <div class="showModalContent">
            <span class="closeShowModal" id="closeUpgradationSubAssets">&times;</span>

            <p class="text-md text-bold text-primary mb-2">
                <i class="fa fa-road" aria-hidden="true"></i>
                Upgradation Sub-Asset Details
            </p>

            <div class="table-responsive text-xs">
                <table class="table table-bordered table-sm mb-0" id="viewUpgradationSubAssetsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Road</th>
                            <th>Start Chainage</th>
                            <th>End Chainage</th>
                            <th>Culverts</th>
                            <th>Bridges</th>
                            <th>Walls</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
    <!-- View Maintenance Sub Assets Modal -->
    <div id="viewMaintenanceSubAssetsModal" class="showModal">
        <div class="showModalContent">
            <span class="closeShowModal" id="closeMaintenanceSubAssets">&times;</span>

            <p class="text-md text-bold text-primary mb-2">
                <i class="fa fa-wrench" aria-hidden="true"></i>
                Maintenance Sub-Asset Details
            </p>

            <div class="table-responsive text-xs">
                <table class="table table-bordered table-sm mb-0" id="viewMaintenanceSubAssetsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Road</th>
                            <th>Start Chainage</th>
                            <th>End Chainage</th>
                            <th>Culverts</th>
                            <th>Bridges</th>
                            <th>Walls</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
    {{-- -Start By Pulak --}}
    {{-- Crop Modal --}}
    <div class="modal fade" id="cropModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title text-sm"><i class="fa fa-crop mr-1"></i>Adjust Passport Photo
                    </h6>
                </div>
                <div class="modal-body text-center" style="background:#f5f5f5;">
                    <div style="max-height: 400px; overflow:hidden;">
                        <img id="cropperImage" src="" style="max-width:100%; display:block;">
                    </div>
                    <div class="mt-2 d-flex justify-content-center flex-wrap gap-1">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="cropperInstance.rotate(-90)" title="Rotate Left">
                            <i class="fa fa-undo"></i> Rotate Left
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="cropperInstance.rotate(90)" title="Rotate Right">
                            <i class="fa fa-redo"></i> Rotate Right
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="cropperInstance.scaleX(cropperInstance.getData().scaleX === -1 ? 1 : -1)"
                            title="Flip Horizontal">
                            <i class="fa fa-arrows-alt-h"></i> Flip H
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="cropperInstance.scaleY(cropperInstance.getData().scaleY === -1 ? 1 : -1)"
                            title="Flip Vertical">
                            <i class="fa fa-arrows-alt-v"></i> Flip V
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="cropperInstance.reset()" title="Reset">
                            <i class="fa fa-sync"></i> Reset
                        </button>
                    </div>
                    <p class="text-xs text-muted mt-2">Drag to reposition · Scroll to zoom · Use buttons to
                        rotate/flip
                    </p>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-success btn-sm" id="cropConfirmBtn">
                        <i class="fa fa-check mr-1"></i> Confirm & Use Photo
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" id="cropCancelBtn">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- -end by Pulak --}}
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pms/common/modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    {{-- added by pulak --}}

    <style>
        :root {
            --primary: var(--oamis-primary, #0b6b4a);
            --secondary: color-mix(in srgb, var(--oamis-primary, #0b6b4a) 14%, #ffffff);
            --accent: color-mix(in srgb, var(--oamis-primary, #0b6b4a) 14%, #ffffff);
        }

        .text-rose-primary {
            color: var(--primary) !important;
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
            background: var(--oamis-table-head-bg, #e8f5ee) !important;
        }

        .table thead th {
            color: var(--oamis-table-head-text, #064e3b) !important;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.6rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        html[data-theme="dark"] .card-header {
            background: #0b6b4a !important;
            border-color: #0b6b4a !important;
            color: #ffffff !important;
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

        {{-- CSS-Added By Pulak --}} #contractorModal .modal-body {
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script> {{-- -added by Pulak --}}
    <script src="{{ asset('js/pms/road/dynamicSectionByType/script.js') }}"></script>
    <script src="{{ asset('js/pms/showModalDetail/showWorkItems.js') }}"></script>
    <script src="{{ asset('js/pms/showModalDetail/fundingAgencyDetails.js') }}"></script>
    <script src="{{ asset('js/pms/showModalDetail/upgAssetItem.js') }}"></script>
    <script src="{{ asset('js/pms/showModalDetail/MtnAssetItem.js') }}"></script>
    <script src="{{ asset('js/pms/showModalDetail/newAssetItem.js') }}"></script>
    <script src="{{ asset('js/road/assets/removeSelectedFile/script.js') }}" defer></script>
    <script src="{{ asset('js/common/restrict_decimal_points.js') }}" defer></script>
    <script src="{{ asset('js/pms/finalization/scirpt.js') }}" defer></script>
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/pms/loadDraft/script.js') }}" defer></script>
    <script src="{{ asset('js/pms/validation/script.js') }}" defer></script>
    <script src="{{ asset('js/pms/upgradation/script.js') }}" defer></script>
    <script src="{{ asset('js/pms/common/showFundingAgencies.js') }}" defer></script>
    <script>
        const allSubItems = @json($workSubItems);

        $(function() {
            $("#project_rejection_table").DataTable();
        });
    </script>
    <script src="{{ asset('js/pms/showModalDetail/script.js') }}" defer></script>
    <script src="{{ asset('js/pms/script.js') }}" defer></script>
    <script src="{{ asset('js/pms/editPms/script.js') }}" defer></script>

    <script>
        var table1, table2, table3;

        $(document).ready(function() {
            table1 = $("#project_details_table").DataTable();
            table2 = $("#project_details_table_maintenance").DataTable();
            table3 = $("#project_details_table_upg").DataTable();
        });

        // open contractor modal
        $('#add-constructor').click(function() {
            $('#contractorModal').modal('show');
        });
        {{-- Start By Pulak --}}
        // Keep contractor modal scrollable whenever it opens
        $('#contractorModal').on('shown.bs.modal', function() {
            $(this).find('.modal-body').css('overflow-y', 'auto');
        });

        // When crop modal is about to show
        $('#cropModal').on('show.bs.modal', function() {
            // Save scroll position of contractor modal body
            var contractorBody = document.querySelector('#contractorModal .modal-body');
            if (contractorBody) {
                window._contractorScrollTop = contractorBody.scrollTop;
            }
        });

        // When crop modal finishes showing - restore contractor modal scroll
        $('#cropModal').on('shown.bs.modal', function() {
            var contractorModal = document.querySelector('#contractorModal');
            var contractorBody = document.querySelector('#contractorModal .modal-body');
            if (contractorModal) contractorModal.style.overflowY = 'auto';
            if (contractorBody) contractorBody.style.overflowY = 'auto';
        });

        // When crop modal is fully hidden - restore everything
        $('#cropModal').on('hidden.bs.modal', function() {
            var contractorModal = document.querySelector('#contractorModal');
            var contractorBody = document.querySelector('#contractorModal .modal-body');

            if (contractorModal) contractorModal.style.overflowY = 'auto';

            setTimeout(function() {
                if (contractorBody) {
                    contractorBody.style.overflowY = 'auto';
                    // Restore scroll position
                    if (window._contractorScrollTop !== undefined) {
                        contractorBody.scrollTop = window._contractorScrollTop;
                    }
                }
                // Make sure body doesn't stay locked
                document.body.style.overflow = '';
                document.body.classList.add('modal-open');
            }, 100);
        });

        // submit contractor form
        $('#contractorForm').submit(function(e) {
            e.preventDefault();

            let accNo = $('#modal_bank_acc_no').val().trim();
            let confirmAccNo = $('#modal_confirm_bank_acc_no').val().trim();

            if (accNo) {
                if (accNo.length < 9 || accNo.length > 16) {
                    alert("Account number must be between 9 and 16 digits.");
                    return;
                }
                if (accNo !== confirmAccNo) {
                    alert("Bank Account Number and Confirm Account Number do not match!");
                    return;
                }
            }

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('contractor.store') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
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
        {{-- End By Pulak --}}

        // save btn validation
        document.getElementById('PmsSaveBtn').closest('form').addEventListener('submit', function(e) {
            const projectType = document.getElementById('projectTypeSelect').value;


            const table = document.getElementById('refAssetTable');
            const mtnTable = document.getElementById('mtnAssetTable');
            const rowCount = table.querySelectorAll('tbody tr').length;
            const mtnRowCount = mtnTable.querySelectorAll('tbody tr').length;

            const newAssetsForm = document.getElementById('newAssetsForm');

            if (projectType === 'UPG' && rowCount === 0) {
                e.preventDefault();
                alert('Please add at least one asset.');
                return;
            }

            if (projectType === 'MTN' && mtnRowCount === 0) {
                e.preventDefault();
                alert('Please add at least one asset.');
                return;
            }

            if (projectType === 'UPG' && newAssetsForm.style.display !== 'none') {

                const roadNameInput = newAssetsForm.querySelector('input[name="new_road_name"]');
                const roadLengthInput = newAssetsForm.querySelector('input[name="road_length"]');

                const otherFields = ['new_culverts', 'new_bridges', 'new_retaining_walls', 'new_pavements'];

                let anyOtherFilled = otherFields.some(name => {
                    const el = newAssetsForm.querySelector(`input[name="${name}"]`);
                    return el && el.value && el.value.trim() !== '' && parseInt(el.value) > 0;
                });

                const roadNameEmpty = !roadNameInput.value.trim();
                const roadLengthEmpty = !roadLengthInput.value.trim();

                const roadLengthFilled = roadLengthInput.value && roadLengthInput.value.trim() !== '';

                if ((anyOtherFilled && (roadNameEmpty || roadLengthEmpty)) || (roadLengthFilled && roadNameEmpty)) {
                    e.preventDefault();
                    alert('Please enter Road Name and Road Length.');
                    return;
                }
            }

            //by dipshikha
            const division_cd = document.getElementById('division_cd');

            if (division_cd && division_cd.disabled) {
                division_cd.disabled = false;
            }
            //end

            const btn = document.getElementById('PmsSaveBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';
        });

        {{-- Start By pulak --}}

        // numeric only inputs
        $(document).on('input', '.numeric-only', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // cropper instance
        let cropperInstance = null;

        function removePhoto(inputId) {
            const fileInput = document.getElementById('passportPhoto');
            if (fileInput) fileInput.value = "";

            document.getElementById('passportPhotoCropped').value = "";
            document.getElementById('passportPreviewContainer').style.display = 'none';
            document.getElementById('passportPreview').src = '';
            document.getElementById('removeBtn_passportPhoto').style.display = 'none';

            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }
        }

        document.getElementById('passportPhoto').addEventListener('change', function() {
            const file = this.files[0];
            const allowedTypes = ["image/jpeg", "image/jpg"];
            const maxSize = 1024 * 1024;

            if (!file) return;

            if (!allowedTypes.includes(file.type)) {
                alert("Only JPG/JPEG files are allowed.");
                this.value = "";
                return;
            }

            if (file.size > maxSize) {
                alert("File size must be less than 1MB.");
                this.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const cropperImg = document.getElementById('cropperImage');
                cropperImg.src = e.target.result;

                if (cropperInstance) {
                    cropperInstance.destroy();
                    cropperInstance = null;
                }

                // Save scroll position before opening crop modal
                var contractorBody = document.querySelector('#contractorModal .modal-body');
                if (contractorBody) {
                    window._contractorScrollTop = contractorBody.scrollTop;
                }

                $('#cropModal').modal('show');

                $('#cropModal').one('shown.bs.modal', function() {
                    // Restore contractor modal scroll after crop modal opens
                    if (contractorBody) {
                        contractorBody.style.overflowY = 'auto';
                        contractorBody.scrollTop = window._contractorScrollTop || 0;
                    }

                    cropperInstance = new Cropper(cropperImg, {
                        aspectRatio: 3 / 4,
                        viewMode: 2,
                        dragMode: 'move',
                        autoCropArea: 0.9,
                        responsive: true,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                    });
                });
            };
            reader.readAsDataURL(file);
        });

        // Confirm crop
        document.getElementById('cropConfirmBtn').addEventListener('click', function() {
            if (!cropperInstance) return;

            const canvas = cropperInstance.getCroppedCanvas({
                width: 300,
                height: 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
                fillColor: '#fff',
            });

            const base64 = canvas.toDataURL('image/jpeg', 0.85);

            document.getElementById('passportPhotoCropped').value = base64;
            document.getElementById('passportPreview').src = base64;
            document.getElementById('passportPreviewContainer').style.display = 'block';
            document.getElementById('removeBtn_passportPhoto').style.display = 'inline-block';

            cropperInstance.destroy();
            cropperInstance = null;
            $('#cropModal').modal('hide');
        });

        // Cancel crop
        document.getElementById('cropCancelBtn').addEventListener('click', function() {
            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }
            document.getElementById('passportPhoto').value = "";
            $('#cropModal').modal('hide');
        });
        {{-- End by Pulak --}}
    </script>
@endpush
