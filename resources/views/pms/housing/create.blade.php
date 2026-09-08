@extends('layouts.app')
@section('content')
    <section class="content">

        {{-- alert section --}}
        <div id="alertContainer">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        {{-- draft list section --}}
        {{-- @if (!isset($project))
            <div class="text-end my-1">
                <button class="btn btn-info btn-sm" id="listDraft">Saved Draft List</button>
            </div>
        @endif --}}
        <form id="myForm"
            action="{{ isset($project) ? route('project.update', $project->project_cd) : route('manage-project') }}"
            method="POST" enctype="multipart/form-data" class="mt-2">
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
                            <!-- required tag added by dipshikha -->
                            <select name="owner_dept_cd" id="owner_dept_cd" class="form-select form-select-sm" required>
                                <!-- end -->
                                <option value="{{ $department->id }}"
                                    {{ old('owner_dept_cd', $project->owner_dept_cd ?? '') == $department->id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Division Name <span class="text-danger">*</span></label>
                            <!-- required and name tag added by dipshikha -->
                            <select name= "division_cd" id="division_cd" class="form-select form-select-sm"
                                data-user-subdivision="{{ session('userMapping')->sub_division_cd ?? '' }}"
                                @if (session('userMapping')->office_type_cd === 'DO' || session('userMapping')->office_type_cd === 'SDO') selected
                                    disabled @endif
                                required>
                                <!-- end -->
                                <option value="">Select Division</option>
                                @foreach ($div as $d)
                                    <option value="{{ $d->division_cd }}"
                                        @if (session('userMapping')->office_type_cd === 'DO' || session('userMapping')->office_type_cd === 'SDO') selected @endif>
                                        {{ $d->division_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- division field not required because for higher-level users, they are not mapped to a single/main division by dipshikha --}}
                        {{-- <input type="hidden" name="division_cd" value="{{ session('userMapping')->division_cd }}"> --}}

                        <div class="col-sm-3">
                            <label class="form-label">Sub Division Name <span class="text-danger">*</span></label>
                            <!-- required tag added by dipshikha -->
                            <select name="sub_division_cd" id="sub_division_cd" class="form-select form-select-sm" required>
                                <!-- end -->
                                <option value="" disable selected hidden>Choose One</option>
                                {{-- Dynamic content --}}
                            </select>
                        </div>

                        <!-- Project Start Date -->
                        <div class="col-sm-3">
                            <label class="form-label">Project Start Date <span class="text-danger">*</span></label>
                            <!-- required tag added by dipshikha -->
                            <input type="date" name="project_start_date" id="project_start_date"
                                class="form-control form-control-sm"
                                value="{{ old('project_start_date', $project->project_start_date ?? '') }}" required />
                            <!-- end -->
                        </div>

                        <!-- Project End Date -->
                        <div class="col-sm-3">
                            <label class="form-label">Stipulated End Date <span class="text-danger">*</span></label>
                            <!-- required tag added by dipshikha -->
                            <input type="date" name="project_end_date" id="project_end_date"
                                class="form-control form-control-sm"
                                value="{{ old('project_end_date', $project->project_end_date ?? '') }}" required />
                            <!-- end -->
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Project Awarded To <span class="text-danger">*</span></label>
                            <!-- required tag added by dipshikha -->
                            <select name="project_awarded_to" id="project_awarded_to" class="form-select form-select-sm"
                                required>
                                <!-- end -->
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
                            <!-- required tag added by dipshikha -->
                            <input type="number" name="est_proj_cost" id="est_proj_cost"
                                class="form-control form-control-sm"
                                value="{{ old('est_proj_cost', $project->est_proj_cost ?? '') }}"
                                placeholder="Enter the estimated cost" required />
                            <!-- end -->
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Defect Liability Period (In Month)<span
                                    class="text-danger">*</span></label>
                            <!-- required tag added by dipshikha -->
                            <input type="number" name="defect_liability_period" id="defect_liability_period"
                                class="form-control form-control-sm"
                                value="{{ old('defect_liability_period', $project->defect_liability_period ?? '') }}"
                                placeholder="Enter the no of months" required />
                            <!-- end -->
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Work Order Amount (₹)<span class="text-danger">*</span></label>
                            <!-- required tag added by dipshikha -->
                            <input type="number" name="work_order_amount" id="work_order_amount"
                                class="form-control form-control-sm"
                                value="{{ old('work_order_amount', $project->work_order_amount ?? '') }}"
                                placeholder="Enter the work order amount" required />
                            <!-- end -->
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
                            <!-- required tag added by dipshikha -->
                            <input type="date" name="work_order_issue_date" id="work_order_issue_date"
                                class="form-control form-control-sm"
                                value="{{ old('work_order_issue_date', $project->work_order_issue_date ?? '') }}"
                                required />
                            <!-- end -->
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Project Technology Type <span class="text-danger">*</span></label>
                            <select name="tech_type_cd" id="tech_type_cd" class="form-select form-select-sm" required>
                                <option value="">Select Technology</option>
                                @foreach ($technologies as $technology)
                                    <option value="{{ $technology->tech_type_cd }}">
                                        {{ $technology->tech_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Radio button for new technology --}}
                        <div style="display: none;" id="new_technology_info_section">
                            <div class="d-flex border rounded">
                                <div class="col-sm-3">
                                    <label class="form-label d-block">Plastic Waste? <span
                                            class="text-danger"></span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="plastic_waste_yes"
                                            name="plastic_waste" value="Y"
                                            {{ old('plastic_waste', $currentProject?->plastic_waste ?? 'N') == 'Y' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal" for="plastic_waste_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="plastic_waste_no"
                                            name="plastic_waste" value="N"
                                            {{ old('plastic_waste', $currentProject?->plastic_waste ?? 'N') == 'N' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal" for="plastic_waste_no">No</label>
                                    </div>
                                    @error('plastic_waste')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label d-block">Mixing Type? <span
                                            class="text-danger"></span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="hot_mixing"
                                            name="mixing_type" value="hot_mixing"
                                            {{ old('mixing_type', $currentProject?->mixing_type ?? '') == 'hot_mixing' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal" for="hot_mixing">Hot Mixing</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="cold_mixing"
                                            name="mixing_type" value="cold_mixing"
                                            {{ old('mixing_type', $currentProject?->mixing_type ?? '') == 'cold_mixing' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal" for="cold_mixing">Cold Mixing</label>
                                    </div>
                                    @error('mixing_type')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Project Scheme <span class="text-danger">*</span></label>
                            <!-- required tag added by dipshikha -->
                            <select name="scheme_cd" id="scheme_cd" class="form-select form-select-sm" required>
                                <!-- end -->
                                <option value="">Select Scheme</option>
                                @foreach ($schemes as $scheme)
                                    <option value="{{ $scheme->scheme_id }}"
                                        {{ old('scheme_cd', $project->scheme_cd ?? '') == $scheme->scheme_id ? 'selected' : '' }}>
                                        {{ $scheme->scheme_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="fundingAgencyContainer" class="text-xs mt-2"></div>
                </div>
            </div>

            {{-- New Section || Nitish --}}
            <div class="card mb-2" id="newAssetSubAssetCountSection" style="display: none;">
                <div class="card-header text-light fw-bold text-uppercase">
                    Add New Building Details
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="row form-1-box">
                            <div class="col-md-12">
                                <label for="">Is Maintained by NPWD? <span
                                        class="star text-danger">*</span></label>
                                <label for="yes">Yes</label>
                                <input type="radio" id="rdo_yes" name="rdo_maintained_by" value="Y" checked>
                                <label for="no">No</label>
                                <input type="radio" id="rdo_no" name="rdo_maintained_by" value="N"><br>
                                <span class="spanHide text-danger text-xs mt-2" id="maintained_by_error"></span>
                                <input type="hidden" id="maintained_by" name="maintained_by" value="Y">
                            </div>
                            <div class="col-md-3">
                                <label for="">Set Geo Location From Google Map: </label>
                            </div>

                            <div class="col-md-4">
                                <input type="text" id="asset_geo_location" name="asset_geo_location" value=""
                                    class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-md-3">
                                <button type="button"
                                    class="classSetGeoLocation btn btn-xs btn-primary text-xm py-1 rounded-1"
                                    id="btnSetGeoLocation">
                                    Set Geo Location
                                </button>
                            </div>
                            <div class="col-md-12">
                                OR
                            </div>
                            <div class="col-md-3">
                                <label for="asset_geo_location_lat">Enter Latitude <span class="star text-danger">*
                                    </span></label>

                                <input type="text" id="asset_geo_location_lat" class="form-control form-control-sm"
                                    name="asset_geo_location_lat" value="" placeholder="Enter Latitude">
                                <span class="spanHide text-danger text-xs mt-2" id="asset_geo_location_lat_error"></span>

                            </div>
                            <div class="col-md-3">
                                <label for="asset_geo_location_lng">Enter Longitude <span class="star text-danger">*
                                    </span></label>
                                <input type="text" id="asset_geo_location_lng" class="form-control form-control-sm"
                                    name="asset_geo_location_lng" value="" placeholder="Enter Longitude">
                                <span class="spanHide text-danger text-xs mt-2" id="asset_geo_location_lng_error"></span>
                            </div>
                        </div>

                        <div class="row form-1-box border my-2 py-2">
                            <div class="col-sm-12 col-md-4">
                                <label for="">Building Category:<span
                                        class="star text-danger">*</span></label><br>
                                <label for="residential">Residential</label>
                                <input type="radio" id="residential" name="building_class_cd" value="0">
                                <label for="nonResidential">Non Residential</label>
                                <input type="radio" id="nonResidential" name="building_class_cd" value="1">
                                <label for="rental">Rental</label>
                                <input type="radio" id="rental" name="building_class_cd" value="2"><br>
                                <span class="spanHide text-danger text-xs mt-2" id="building_class_cd_error"></span>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label for="building_location_cd">
                                    Location:
                                    <span class="star text-danger">*</span>
                                </label>
                                <select class="form-control form-control-sm" id="building_location_cd"
                                    name="building_location_cd">
                                    <option value="">Choose one</option>
                                </select>
                                <span class="spanHide text-danger text-xs mt-2" id="building_location_cd_error"></span>
                            </div>
                        </div>

                        {{-- ── Housing Details (shown/hidden via JS): nitish ── --}}
                        <div id="housingContainerNewWorks" style="display: none;">
                            <div class="row form-1-box border py-2">
                                <div class="col-md-3" id="quarterNoInput">
                                    <label for="quarter_no">Quarter No:</label>
                                    <input type="text" name="quarter_no" id="quarter_no"
                                        class="form-control form-control-sm text-uppercase"
                                        placeholder="Enter Quarter No">
                                    <span class="spanHide text-danger text-xs mt-2" id="quarter_no_error"></span>
                                </div>
                                <div class="col-md-3" id="buildingNameInput">
                                    <label for="building_name">Name of the Building:</label>
                                    <input type="text" name="building_name" id="building_name"
                                        class="form-control form-control-sm text-uppercase"
                                        placeholder="Enter Name of the Building">
                                    <span class="spanHide text-danger text-xs mt-2" id="building_name_error"></span>
                                </div>
                                <div class="col-md-3">
                                    <label for="building_type_cd">Building Type <span
                                            class="star text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="building_type_cd"
                                        name="building_type_cd">
                                        <option value="">Choose one</option>
                                        {{-- Dynamic Content --}}
                                    </select>
                                    <span class="spanHide text-danger text-xs mt-2" id="building_type_cd_error"></span>
                                </div>
                                <div class="col-md-3">
                                    <label for="owning_dept">Owning Department <span
                                            class="star text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="owning_dept" name="owning_dept">
                                        <option value="">Choose one</option>
                                        @foreach ($departmentDetails as $dept)
                                            <option value="{{ $dept->id }}">
                                                {{ $dept->dept_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="spanHide text-danger text-xs mt-2" id="owning_dept_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- New Section --}}

            {{-- Maintenance Section || Dipshikha --}}
            <div class="card mb-2" id="maintenanceSection" style="display: none;">
                <div class="card-header text-light fw-bold text-uppercase">Select Asset for Maintenance</div>
                <div class="card-body">
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
                                <option value="">--Select Building---</option>
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
            {{-- Maintenance Section --}}

            {{-- Upgradation Section || Dipshikha --}}
            <div class="card mb-2" id="upgradationSection" style="display: none;">
                <div class="card-header text-light fw-bold text-uppercase">
                    Upgradation Asset Section
                </div>
                <div class="card-body">
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
                                <option value="">--Select Building---</option>
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
            {{-- Upgradation Section --}}

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

        <x-pms.contractor-details :districts="$districts" :states="$states" :contractorCategories="$contractorCategories">
        </x-pms.contractor-details>

        <!-- Draft Project Details Table -->
        @if (!isset($project))
            <div id="draftSection">
                <article class="command-panel mb-4">
                    <header>
                        <div>
                            <span>Government Buildings (Housing)</span>
                            <h2>List of Draft Project Details</h2>
                        </div>
                        <div>
                            <button id="freezeBtnForNewProjectHousing" class="btn btn-sm btn-info text-bold">
                                <i class="fa fa-paper-plane mr-1" aria-hidden="true"></i>
                                Send selected new project data for finalization
                            </button>
                        </div>
                    </header>
                    <div class="table-responsive p-3">
                        <table class="table table-striped table-bordered text-xs user_list w-100"
                            id="new_project_details_table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="min-width: 3rem;">Sl No.</th>
                                    <th class="text-center" style="min-width: 6rem;">Project Code</th>
                                    <th class="text-center" style="min-width: 6rem;">Project Name</th>
                                    <th class="text-center" style="min-width: 5rem;">Project Type</th>
                                    <th class="text-center" style="min-width: 6rem;">Owner Department</th>
                                    <th class="text-center" style="min-width: 6rem;">Division</th>
                                    <th class="text-center" style="min-width: 6rem;">Sub Division</th>
                                    <th class="text-center" style="min-width: 8rem;">Project Start Date</th>
                                    <th class="text-center" style="min-width: 8rem;">Project End Date</th>
                                    <th class="text-center" style="min-width: 8rem;">Project Status</th>
                                    <th class="text-center" style="min-width: 8rem;">Project Awarded To</th>
                                    <th class="text-center" style="min-width: 8rem;">Estimated Project Cost</th>
                                    <th class="text-center" style="min-width: 8rem;">Defect Liability Period (in Month)
                                    </th>
                                    <th class="text-center" style="min-width: 8rem;">Work Order Amount(Rs.)</th>
                                    {{-- New: Nitish --}}
                                    <th class="text-center" style="min-width: 8rem;">Work Order Number</th>
                                    <th class="text-center" style="min-width: 8rem;">Work Order Issued</th>
                                    <th class="text-center" style="min-width: 8rem;">Scheme Name</th>
                                    {{-- End: Nitish --}}
                                    <th class="text-center" style="min-width: 8rem;">Technology Name</th>
                                    <th class="text-center" style="min-width: 8rem;">Rejection Reason</th>
                                    <th class="text-center" style="min-width: 8rem;">Asset Details</th>
                                    {{-- New: Dipshikha --}}
                                    <th class="text-center" style="min-width: 8rem;">Funding Agency</th>
                                    {{-- End: Dipshikha --}}
                                    <th class="text-center" style="min-width: 8rem;">Work Item</th>
                                    {{-- New: Dipshikha --}}
                                    <th class="text-center" style="min-width: 10rem;">Action</th>
                                    {{-- End: Dipshikha --}}
                                    <th class="text-center" style="min-width: 8rem;">Select</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($draftProjects as $index => $draft)
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
                                        <td class="text-center">{{ $draft->work_order_no ?? 'N/A' }}</td>
                                        <td class="text-center">{{ $draft->work_order_issue_date ?? 'N/A' }}</td>
                                        <td class="text-center">{{ $draft->scheme_name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            {{ json_decode($draft->others, true)['tech_type_descr'] ?? 'N/A' }}</td>
                                        <td class="text-center">{{ $draft->reason_of_rejection ?? '-' }}</td>
                                        <td class="text-center">
                                            @if ($draft->project_type_cd == 'NEW')
                                                <button
                                                    class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold rounded-0"
                                                    data-toggle="modal"
                                                    data-target="#showAssetForNewWorks{{ $draft->project_cd }}"
                                                    onclick="getNewWorksAssetDetailsHousing('{{ $draft->project_cd }}')">
                                                    <i class="fas fa-eye text-xs"></i>
                                                    View
                                                </button>
                                            @elseif($draft->project_type_cd == 'UPG')
                                                @if ($hasUpgradationAssets)
                                                    <button
                                                        class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold rounded-0"
                                                        data-toggle="modal"
                                                        onclick="showModalNewAssetDetailBuilding('{{ $draft->project_cd }}', 'draft')">
                                                        <i class="fas fa-eye text-xs"></i>
                                                        View
                                                    </button>
                                                @else
                                                    <span class="text-danger text-bold">NA</span>
                                                @endif
                                            @elseif($draft->project_type_cd == 'MTN')
                                                @if ($hasMaintenanceAssets)
                                                    <button
                                                        class="text-sm outline-0 btn btn-xs btn-outline-secondary inline fw-bold rounded-0"
                                                        onclick="showModalMaintenanceDetailBuilding('{{ $draft->project_cd }}','draft')">
                                                        <i class="fas fa-eye text-xs"></i>
                                                        View
                                                    </button>
                                                @else
                                                    <span class="text-danger text-bold">NA</span>
                                                @endif
                                            @endif
                                        </td>
                                        <!-- by dipshikha -->
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
                                        <!-- end -->
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
                                            <!-- by dipshikha -->
                                            <a href="{{ route('pms.work-item.index', ['project' => $draft->project_cd]) }}"
                                                class="btn btn-xs btn-outline-primary fw-bold rounded-0"
                                                style="padding: 0.27rem;">
                                                <i class="fas fa-plus-circle text-xs"></i>
                                                Add
                                            </a>
                                            <!-- end -->
                                        </td>

                                        <!-- by dipshikha -->
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
                                        <!-- end -->
                                        <td class="text-center">
                                            <input type="checkbox" class="selected-asset"
                                                data-project-cd="{{ $draft->project_cd }}" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </article>
            </div>
        @endif

        <x-building.coordinates.set-coordinate />

        <div id="showAssetForNewWorks" class="showAssetForNewWorks">
            <!-- Modal content -->
            <div class="ItemsModalContent modal-md">
                <div class="modal-header m-1 p-0">
                    <h5 class="modal-title text-uppercase text-md text-primary font-bold" id="diseaseWiseTitle">
                        New Assets Details for the New Works
                    </h5>
                    <button type="button" class="btn-close btn-xs" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row justify-content-center align-item-center text-xs p-1"
                    id="dataSectionForNewWorks">
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
            <p class="text-md text-bold text-rose-primary">
                <i class="fa fa-bars" aria-hidden="true"></i>
                Asset Details
            </p>

            <div id="modalValContainerNewAsset" class="row text-xs"></div>
        </div>
    </div>


    <div id="showModalMaintenance" class="showModalMaintenance">
        <div class="showModalContentMaintenanace">
            <span class="closeShowModalMaintenance">&times;</span>
            <p class="text-md text-bold text-rose-primary">
                <i class="fa fa-bars" aria-hidden="true"></i>
                Asset Details
            </p>
            <div class="row text-xs" id="modalValContainerMaintenance">
            </div>
        </div>
    </div>


    <div id="ItemsModal" class="ItemsModal">
        <div class="ItemsModalContent modal-md">
            <div class="modal-header m-1 p-0">
                <p class="text-md text-bold text-rose-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    List of Items of Work under the selected project
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row justify-content-center align-item-center text-xs m-1 p-0" id="modalItemsContainer">
            </div>
        </div>
    </div>

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
                <button type="button" id="saveEditSubItemBtn" class="btn btn-primary btn-sm">Save Changes</button>
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

    <!-- by dipshikha -->
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
    <!-- end -->
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
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
            }
        }
    </style>
@endpush

@push('scripts')
    {{-- script for dynamic section by project type --}}
    <script src="{{ asset('js/pms/road/dynamicSectionByType/script.js') }}"></script>
    <script src="{{ asset('js/pms/building/modal/upgradationAssetModal.js') }}"></script>
    <script src="{{ asset('js/pms/building/modal/maintenanceAssetModal.js') }}"></script>
    <script src="{{ asset('js/pms/showModalDetail/showWorkItems.js') }}"></script>
    <script src="{{ asset('js/pms/showModalDetail/newAssetItem.js') }}"></script>

    {{-- script to inherite the buidling functionality --}}
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/building/script.js') }}" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM"></script>

    <script src="{{ asset('js/road/assets/removeSelectedFile/script.js') }}" defer></script>
    <script src="{{ asset('js/common/restrict_decimal_points.js') }}" defer></script>
    <script src="{{ asset('js/pms/finalization/scirpt.js') }}" defer></script>
    <script src="{{ asset('js/pms/loadDraft/script.js') }}" defer></script>
    <script src="{{ asset('js/pms/validation/script.js') }}" defer></script>
    <script src="{{ asset('js/pms/building/upgradation/script.js') }}" defer></script>
    {{-- by dipshikha --}}
    <script src="{{ asset('js/pms/common/showFundingAgencies.js') }}" defer></script>
    <script src="{{ asset('js/pms/showModalDetail/fundingAgencyDetails.js') }}"></script>
    {{-- end --}}
    <script>
        const allSubItems = @json($workSubItems);


        {{--  $(function () {
			$("#project_rejection_table").DataTable();
		});		  --}}
    </script>
    <script src="{{ asset('js/pms/showModalDetail/script.js') }}" defer></script>
    <script src="{{ asset('js/pms/editPms/script.js') }}" defer></script>

    <script>
        var table1, table2, table3;

        $(document).ready(function() {
            table1 = $("#new_project_details_table").DataTable();
            table2 = $("#project_details_table_maintenance").DataTable();
            table3 = $("#project_details_table_upg").DataTable();

            function toggleTechnologySection() {
                if ($('#tech_type_cd').val() == '1') {
                    $('#new_technology_info_section').show();
                } else {
                    $('#new_technology_info_section').hide();
                }
            }

            $('#tech_type_cd').on('change', toggleTechnologySection);
            toggleTechnologySection();
        });

        // open modal
        $('#add-constructor').click(function() {
            $('#contractorModal').modal('show');
        });

        // submit contractor form - pulak
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

                    Swal.fire({
                        icon: res.status,
                        title: res.status.toUpperCase(),
                        text: res.message,
                        timer: 2000
                    })
                },
                error: function(xhr) {
                    if (xhr.status === 422) {

                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';

                        $.each(errors, function(key, value) {
                            errorMsg += value[0] + '<br>';
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMsg
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'ERROR',
                            text: 'Something went wrong!'
                        });

                    }
                }
            });
        });
        // {{-- End By Pulak --}}


        document.getElementById('myForm').addEventListener('submit', function(e) {
            let isValid = true;

            const projectType = document.getElementById('projectTypeSelect').value;

            if (projectType === 'UPG') {
                const categoryUpg = document.getElementById('buildingCategoryUpgradation').value;
                const buildingUpg = document.getElementById('upgBuildings').value;
                const section = document.getElementById("housingContainerOtherUpg");

                document.getElementById("buildingCategoryUpgradation_error").innerText = "";
                document.getElementById("upgBuildings_error").innerText = "";

                if (!categoryUpg) {
                    document.getElementById("buildingCategoryUpgradation_error").innerText =
                        "Building Category is required";
                    isValid = false;
                }

                if (!buildingUpg) {
                    document.getElementById("upgBuildings_error").innerText = "Building is required";
                    isValid = false;
                }


                if (section && !section.classList.contains("d-none")) {

                    const buildingType = document.getElementById("building_type_upg");
                    const dept = document.getElementById("owning_dept_upg");
                    const quarter = document.getElementById("quarter_no");
                    const buildingCategoryUpg = document.getElementById("buildingCategoryUpg").value;
                    const quarterContainer = document.getElementById("quarterContainer");

                    // clear previous errors
                    document.getElementById("building_type_cd_error").innerText = "";
                    document.getElementById("owning_dept_error").innerText = "";
                    document.getElementById("buildingCategoryUpg_error").innerText = "";
                    document.getElementById("quarter_no_error").innerText = "";

                    if (!buildingType.value) {
                        document.getElementById("building_type_cd_error").innerText = "Building Type is required";
                        isValid = false;
                    }

                    if (!dept.value) {
                        document.getElementById("owning_dept_error").innerText = "Owning Department is required";
                        isValid = false;
                    }

                    if (!buildingCategoryUpg) {
                        document.getElementById("buildingCategoryUpg_error").innerText =
                            "Building Category is required";
                        isValid = false;
                    }

                    if (!quarterContainer.classList.contains("d-none")) {
                        if (!quarter.value) {
                            document.getElementById("quarter_no_error").innerText = "Field is required";
                            isValid = false;
                        }
                    }
                }
            }

            if (projectType === 'MTN') {
                const categoryMtn = document.getElementById('buildingCategory').value;
                const buildingMtn = document.getElementById('maintBuildings').value;

                document.getElementById("buildingCategory_error").innerText = "";
                document.getElementById("maintBuildings_error").innerText = "";

                if (!categoryMtn) {
                    document.getElementById("buildingCategory_error").innerText = "Building Category is required";
                    isValid = false;
                }

                if (!buildingMtn) {
                    document.getElementById("maintBuildings_error").innerText = "Building is required";
                    isValid = false;
                }
            }

            if (projectType === 'NEW') {
                const buldingLocation = document.getElementById('building_location_cd').value;
                const buildingCategory = document.querySelector('input[name="building_class_cd"]:checked');
                const latVal = document.getElementById('asset_geo_location_lat').value;
                const lonVal = document.getElementById('asset_geo_location_lng').value;

                document.getElementById("building_location_cd_error").innerText = "";
                document.getElementById("building_class_cd_error").innerText = "";
                document.getElementById("asset_geo_location_lat_error").innerText = "";
                document.getElementById("asset_geo_location_lng_error").innerText = "";

                if (!latVal) {
                    document.getElementById("asset_geo_location_lat_error").innerText = "Latitude is required";
                    isValid = false;
                }

                if (!lonVal) {
                    document.getElementById("asset_geo_location_lng_error").innerText = "Longitude is required";
                    isValid = false;
                }

                if (!buldingLocation) {
                    document.getElementById("building_location_cd_error").innerText = "Location is required";
                    isValid = false;
                }

                if (!buildingCategory) {
                    document.getElementById("building_class_cd_error").innerText = "Building Category is required";
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                return;
            }

            const division = document.getElementById('division_cd');
            if (division && division.disabled) {
                division.disabled = false;
            }

            $('#PmsSaveBtn')
                .prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        });
    </script>
@endpush
