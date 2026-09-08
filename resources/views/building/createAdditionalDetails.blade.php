@extends('layouts.app')

@section('content')
    <main class="command-center">
        <div class="content-header">
            <div class="container-fluid" style="position: relative;">
                <div class="row text-sm">
                    <div class="col-sm-12 col-md-10">
                        <div class="command-breadcrumb">
                            <i class="fas fa-house"></i>
                            <span><a href="{{ route('dashboard.housing') }}"
                                    style="color: inherit; text-decoration: none;">Dashboard</a></span>
                            <span>/</span>
                            <span><a href="{{ route('manage.housing.index') }}"
                                    style="color: inherit; text-decoration: none;">Manage Housing</a></span>
                            <span>/</span>
                            <strong>Add Additional Details</strong>
                        </div>
                    </div>
                </div>
                <x-common.alert-module />
            </div>
        </div>

        <!-- Main content -->
        <section class="content px-3">

            {{-- Page Header --}}
            <div class="command-heading mb-4">
                <div>
                    <h1>
                        Add Housing Additional Details
                    </h1>
                    <p class="text-sm text-secondary">
                        All fields marked with <span class="star text-danger text-md text-bold">*</span> are mandatory
                    </p>
                </div>
                <div class="command-actions">
                    <a href="{{ route('manage.housing.index') }}" class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>

            {{-- Selected Building Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Building Details</span>
                        <h2>Selected Building</h2>
                    </div>
                </header>
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table table-hover w-100 mb-0">
                            <thead>
                                <tr>
                                    <th>Building ID</th>
                                    <th>Quarter Number</th>
                                    <th>Building Name</th>
                                    <th>Building Type</th>
                                    <th class="text-center">Maintained by NPWD?</th>
                                    <th>Residential/Non-Residential</th>
                                    <th>Owning Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ $buildingDetails->building_system_cd }}
                                    </td>
                                    <td>
                                        {{ $buildingDetails->qtr_no ? $buildingDetails->qtr_no : 'NA' }}
                                    </td>
                                    <td>
                                        {{ $buildingDetails->bld_qtr_name ? $buildingDetails->bld_qtr_name : 'NA' }}
                                    </td>
                                    <td>
                                        {{ $buildingDetails->building_type_descr ?? '__' }}
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $buildingDetails->is_maintained_by_npwd == 'Y' ? 'bg-success text-light' : 'bg-danger text-light' }}">
                                            {{ $buildingDetails->is_maintained_by_npwd == 'Y' ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $buildingDetails->building_class_descr }}
                                    </td>
                                    <td>
                                        {{ $buildingDetails->owning_dept_name }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>

            {{-- Form Panel --}}
            <form action="{{ route('manage.housing.additional.store', $buildingID) }}" method="post" class="pb-2"
                id="housingForm">
                @csrf
                <article class="command-panel mb-4">
                    <header>
                        <div>
                            <span>Input Details</span>
                            <h2>Add Additional Details</h2>
                        </div>
                    </header>
                    <div class="p-3">
                        <input type="hidden" name="bld_sys_cd" value="{{ $buildingID }}">
                        <div id="housingContainer">
                            <div class="row g-3">
                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label" for="bld_catg">Building Class</label>
                                    <select class="form-select form-select-sm" id="bld_catg" name="bld_catg">
                                        <option value="">Choose one</option>
                                        @foreach ($buildingCategories as $buildingCategory)
                                            <option value="{{ $buildingCategory->building_catg_cd }}">
                                                {{ $buildingCategory->building_catg_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="spanHide text-danger text-xs mt-2" id="bld_catg_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3" id="floorContainer" style="display: none;">
                                    <label class="form-label" for="floor_type_cd">Floor Type</label>
                                    <select class="form-select form-select-sm" id="floor_type_cd" name="floor_type_cd">
                                        <option value="">Choose one</option>
                                        @foreach ($builingFloorTypes as $builingFloorType)
                                            <option value="{{ $builingFloorType->floor_type_cd }}">
                                                {{ $builingFloorType->floor_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="spanHide text-danger text-xs mt-2" id="floor_type_cd_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3" id="emgExitContainer" style="display: none;">
                                    <label class="form-label d-block" for="has_emergency_exit">Emergency Exit? <span
                                            class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="has_emergency_exit_yes"
                                            name="has_emergency_exit" value="Y" checked>
                                        <label class="form-check-label fw-normal" for="has_emergency_exit_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="has_emergency_exit_no"
                                            name="has_emergency_exit" value="N">
                                        <label class="form-check-label fw-normal" for="has_emergency_exit_no">No</label>
                                    </div>
                                    <span class="spanHide text-danger text-xs mt-2" id="has_emergency_exit_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3" id="staircaseContainer" style="display: none;">
                                    <label class="form-label d-block" for="has_staircase">Staircase? <span
                                            class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="has_staircase_yes"
                                            name="has_staircase" value="Y" checked>
                                        <label class="form-check-label fw-normal" for="has_staircase_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="has_staircase_no"
                                            name="has_staircase" value="N">
                                        <label class="form-check-label fw-normal" for="has_staircase_no">No</label>
                                    </div>
                                    <span class="spanHide text-danger text-xs mt-2" id="has_staircase_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3" id="liftContainer" style="display: none;">
                                    <label class="form-label d-block" for="has_lift">Lift? <span
                                            class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="has_lift_yes" name="has_lift"
                                            value="Y" checked>
                                        <label class="form-check-label fw-normal" for="has_lift_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="has_lift_no" name="has_lift"
                                            value="N">
                                        <label class="form-check-label fw-normal" for="has_lift_no">No</label>
                                    </div>
                                    <span class="spanHide text-danger text-xs mt-2" id="has_lift_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3" id="rampContainer" style="display: none;">
                                    <label class="form-label d-block" for="has_ramp">Ramp? <span
                                            class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="has_ramp_yes" name="has_ramp"
                                            value="Y" checked>
                                        <label class="form-check-label fw-normal" for="has_ramp_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="has_ramp_no" name="has_ramp"
                                            value="N">
                                        <label class="form-check-label fw-normal" for="has_ramp_no">No</label>
                                    </div>
                                    <span class="spanHide text-danger text-xs mt-2" id="has_ramp_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label" for="plinth_area">Plinth Area (Sq.Ft) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="plinth_area" class="form-control form-control-sm"
                                        step="0.01" name="plinth_area" value="{{ old('plinth_area') }}"
                                        placeholder="Plinth Area">
                                    <span class="spanHide text-danger text-xs mt-2" id="plinth_area_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label" for="plot_area">Plot Area (As per Patta) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="plot_area" class="form-control form-control-sm"
                                        step="0.01" name="plot_area" value="{{ old('plot_area') }}"
                                        placeholder="Plot Area">
                                    <span class="spanHide text-danger text-xs mt-2" id="plot_area_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label" for="buildingAccess">Access to Building <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="buildingAccess" name="buildingAccess">
                                        <option value="">Choose one</option>
                                        @foreach ($accessTypes as $accessType)
                                            <option value="{{ $accessType->access_type_cd }}">
                                                {{ $accessType->access_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="spanHide text-danger text-xs mt-2" id="buildingAccess_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label" for="construction_year">Construction Year</label>
                                    <select class="form-select form-select-sm" id="construction_year"
                                        name="construction_year">
                                        <option value="">Please Select</option>
                                        @for ($i = Carbon\Carbon::now()->year; $i >= 1950; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <span class="spanHide text-danger text-xs mt-2" id="construction_year_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label" for="construction_cost">Construction Cost (Rs.) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="construction_cost" class="form-control form-control-sm"
                                        name="construction_cost" value="{{ old('construction_cost') }}"
                                        placeholder="Construction Cost">
                                    <span class="spanHide text-danger text-xs mt-2" id="construction_cost_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label" for="scheme_cd">Construction Scheme <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="scheme_cd" name="scheme_cd">
                                        <option value="">Choose one</option>
                                        @foreach ($builingSchemes as $builingScheme)
                                            <option value="{{ $builingScheme->scheme_cd }}">
                                                {{ $builingScheme->scheme_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="spanHide text-danger text-xs mt-2" id="scheme_cd_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label d-block" for="is_pwd_friendly">PWD Friendly? <span
                                            class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="pwd_friendly_yes"
                                            name="is_pwd_friendly" value="Y" checked>
                                        <label class="form-check-label fw-normal" for="pwd_friendly_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="pwd_friendly_no"
                                            name="is_pwd_friendly" value="N">
                                        <label class="form-check-label fw-normal" for="pwd_friendly_no">No</label>
                                    </div>
                                    <span class="spanHide text-danger text-xs mt-2" id="is_pwd_friendly_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label d-block" for="is_fire_safety_available">Fire Safety? <span
                                            class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="fire_safety_yes"
                                            name="is_fire_safety_available" value="Y" checked>
                                        <label class="form-check-label fw-normal" for="fire_safety_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="fire_safety_no"
                                            name="is_fire_safety_available" value="N">
                                        <label class="form-check-label fw-normal" for="fire_safety_no">No</label>
                                    </div>
                                    <span class="spanHide text-danger text-xs mt-2"
                                        id="is_fire_safety_available_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label d-block" for="security_fencing">Security Fencing? <span
                                            class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="security_fencing_yes"
                                            name="security_fencing" value="Y">
                                        <label class="form-check-label fw-normal" for="security_fencing_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="security_fencing_no"
                                            name="security_fencing" value="N" checked>
                                        <label class="form-check-label fw-normal" for="security_fencing_no">No</label>
                                    </div>
                                    <span class="spanHide text-danger text-xs mt-2" id="security_fencing_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3" id="div_fencing_type" style="display: none;">
                                    <label class="form-label" for="fencing_type">Fencing Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="fencing_type" name="fencing_type">
                                        <option value="">Choose one</option>
                                        @foreach ($securityFenchingTypes as $securityFenchingType)
                                            <option value="{{ $securityFenchingType->fenching_type_cd }}">
                                                {{ $securityFenchingType->fenching_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="spanHide text-danger text-xs mt-2" id="fencing_type_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label d-block" for="repaired">Repaired? <span
                                            class="text-danger">*</span></label>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="repaired_yes" name="repaired"
                                            value="Y">
                                        <label class="form-check-label fw-normal" for="repaired_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" id="repaired_no" name="repaired"
                                            value="N" checked>
                                        <label class="form-check-label fw-normal" for="repaired_no">No</label>
                                    </div>
                                    <span class="spanHide text-danger text-xs mt-2" id="repaired_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3" id="repairedCost" style="display: none;">
                                    <label class="form-label" for="last_repaired_cost">Last Repaired Cost <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="last_repaired_cost" class="form-control form-control-sm"
                                        name="last_repaired_cost" value="{{ old('last_repaired_cost') }}">
                                    <span class="spanHide text-danger text-xs mt-2" id="last_repaired_cost_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3" id="repairedScheme" style="display: none;">
                                    <label class="form-label" for="last_repaired_scheme_cd">Scheme <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="last_repaired_scheme_cd"
                                        name="last_repaired_scheme_cd">
                                        <option value="">Choose one</option>
                                        @foreach ($builingSchemes as $builingScheme)
                                            <option value="{{ $builingScheme->scheme_cd }}">
                                                {{ $builingScheme->scheme_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="spanHide text-danger text-xs mt-2"
                                        id="last_repaired_scheme_cd_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3" id="repairedYear" style="display: none;">
                                    <label class="form-label" for="year_of_last_repaired">Last Repaired Year <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="year_of_last_repaired"
                                        name="year_of_last_repaired">
                                        <option value="">Please Select</option>
                                        @for ($i = Carbon\Carbon::now()->year; $i >= 1950; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <span class="spanHide text-danger text-xs mt-2"
                                        id="year_of_last_repaired_error"></span>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <label class="form-label" for="total_no_of_units">Total No. of Units <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="total_no_of_units" class="form-control form-control-sm"
                                        placeholder="Eg.: 1" name="total_no_of_units"
                                        value="{{ old('total_no_of_units') }}" min="1">
                                    <span class="spanHide text-danger text-xs mt-2" id="total_no_of_units_error"></span>
                                </div>

                                <div class="col-12">
                                    <label class="form-label" for="remark">Remark</label>
                                    <textarea class="form-control form-control-sm text-sm" id="remark" name="remark" rows="2"
                                        placeholder="Write bridge remark...">{{ old('remark') }}</textarea>
                                    <span class="spanHide text-danger text-xs mt-2" id="_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                <i class="fa fa-save me-1"></i> Save
                            </button>
                            <button type="reset" class="btn btn-light btn-sm px-3 border">
                                <i class="fa fa-undo me-1"></i> Reset
                            </button>
                            <a href="{{ route('manage.housing.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="fa fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>
                </article>
            </form>
        </section>
    </main>
    <x-success-modal />
    <x-warning-modal />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/selectOptionStyleSheet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <style>
        .command-center {
            color: var(--oamis-ink);
        }

        .table th,
        .table td {
            font-size: 14px !important;
        }

        .badge {
            font-size: 11px !important;
            padding: 5px 9px !important;
        }

        .gap-1 {
            gap: 0.25rem !important;
        }

        .closeable-div {
            position: relative;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            width: 100%;
            margin: 10px;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            color: #881818;
        }

        .close-button:hover {
            color: #000;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/building/script.js') }}" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM"></script>
    {{-- Script for Show JQuery Table --}}
    <script>
        $(function() {
            $("#building_details_table").DataTable({}).buttons().container().appendTo(
                '#building_details_table_wrapper .col-md-11:eq(1)');
        });
        $(document).ready(function() {
            $('#editDraftBuildingModal').on('show.bs.modal ', function(event) {

                var button = $(event.relatedTarget);

                var bld_system_cd = button.data('bld-system-cd');
                var is_mntnd_by_npwd = button.data('bld-is-mntd-by-npwd');
                var qtr_no = button.data('qtr-no');
                var bld_name = button.data('bld-name');
                var bld_class_cd = button.data('bld-class-cd');
                var bld_type_code = button.data('bld-type-cd');
                var owning_dept_cd = button.data('bld-owning-dept-cd');
                let lat = button.data('bld-lat');
                let lon = button.data('bld-lon');
                let reasonRejection = button.data('reason-rejection');

                console.log("bld_system_cd:::   " + bld_system_cd);
                console.log("is_mntnd_by_npwd:::   " + is_mntnd_by_npwd);
                console.log("qtr_no:::   " + qtr_no);
                console.log("bld_name:::   " + bld_name);
                console.log("bld_class_cd:::   " + bld_class_cd);
                console.log("bld_type_code:::   " + bld_type_code);
                console.log("owning_dept_cd:::   " + owning_dept_cd);
                console.log("lat:::   " + lat);
                console.log("lon:::   " + lon);

                if (is_mntnd_by_npw = undefined)
                    $('#is_mntd_by_npwd').val("N");
                else
                    $('#is_mntd_by_npwd').val(is_mntnd_by_npwd);
                $('#bld_asset_geo_location_lat').val(lat);
                $('#bld_asset_geo_location_lng').val(lon);
                $('#bld_sys_cd').val(bld_system_cd);

                $('#bld_type_cd').empty();
                $.each(bld_type_master, function(index, option) {
                    if (bld_class_cd == option.building_class_cd)
                        $('#bld_type_cd').append(new Option(option.building_type_descr, option
                            .building_type_cd));
                });
                $('#bld_type_cd').val(bld_type_code);
                $('#bld_owning_dept').val(owning_dept_cd);
                $('#bld_class_cd').val(bld_class_cd);

                if (bld_class_cd == 0)
                    $('#building_name_or_qtr_no').val(qtr_no);
                else
                    $('#building_name_or_qtr_no').val(bld_name);
                $('#txt_reason_of_rejection').val(reasonRejection);
            });

            $('#bld_class_cd').on("change", function() {
                var selectedValue = $('#bld_class_cd').val();

                $('#bld_type_cd').empty();
                $.each(bld_type_master, function(index, option) {
                    if (selectedValue === option.building_class_cd)
                        $('#bld_type_cd').append(new Option(option.building_type_descr, option
                            .building_type_cd));
                });

            });

            $('#bld_catg').on("change", function() {
                var selectedValue = $('#bld_catg').val();
                $('#floorContainer').hide();
                $('#emgExitContainer').hide();
                $('#liftContainer').hide();
                $('#staircaseContainer').hide();
                $('#rampContainer').hide();
                if (selectedValue == '0') {
                    $('#floorContainer').show();
                    $('#emgExitContainer').show();
                    $('#liftContainer').show();
                    $('#staircaseContainer').show();
                    $('#rampContainer').show();
                } else {
                    $('#floorContainer').hide();
                    $('#emgExitContainer').hide();
                    $('#liftContainer').hide();
                    $('#staircaseContainer').hide();
                    $('#rampContainer').hide();

                }
            });

            // logic for positive number only for building unit
            $('#total_no_of_units').on("input", function() {
                var selectedValue = $('#total_no_of_units').val();
                if (selectedValue < 1) {
                    $('#total_no_of_units').val('');
                }
            });
        });

        const bld_class_master = @json($buildingClasses);
        const bld_type_master = @json($buildingTypes);
    </script>
@endpush
