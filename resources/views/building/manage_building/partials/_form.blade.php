{{--
    Shared Housing Form Partial
    Variables expected:
      - $mode        : 'create' | 'edit'
      - $action      : form action URL
      - $building    : (edit only) AssetBuildingDetailDraft model instance
      - $departmentDetails, $buildingClasses, $buildingTypes : master data collections
--}}

@php
    $isEdit = ($mode ?? 'create') === 'edit';
    $building = $building ?? null;
@endphp

<form action="{{ $action }}" method="post" class="pb-2" id="housingForm">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <fieldset class="border py-2 px-3">

        {{-- ── Maintained by NPWD ── --}}
        <div class="row form-1-box">
            <div class="col-md-12">
                <label>Is Maintained by NPWD? <span class="star text-danger">*</span></label>
                <label for="rdo_yes">Yes</label>
                <input type="radio" id="rdo_yes" name="rdo_maintained_by" value="Y"
                    {{ old('rdo_maintained_by', $building?->is_maintained_by_npwd ?? 'Y') === 'Y' ? 'checked' : '' }}>
                <label for="rdo_no">No</label>
                <input type="radio" id="rdo_no" name="rdo_maintained_by" value="N"
                    {{ old('rdo_maintained_by', $building?->is_maintained_by_npwd) === 'N' ? 'checked' : '' }}>
                <br>
                <span class="spanHide text-danger text-xs mt-2" id="maintained_by_error"></span>
                <input type="hidden" id="maintained_by" name="maintained_by"
                    value="{{ old('maintained_by', $building?->is_maintained_by_npwd ?? 'Y') }}">
            </div>

            {{-- ── Geo Location ── --}}
            <div class="col-md-3">
                <label>Set Geo Location From Google Map:</label>
            </div>
            <div class="col-md-4">
                <input type="text" id="asset_geo_location" name="asset_geo_location" value=""
                    class="form-control form-control-sm" readonly>
            </div>
            <div class="col-md-3">
                <button type="button" class="classSetGeoLocation btn btn-xs btn-primary text-xm py-1 rounded-1"
                    id="btnSetGeoLocation">
                    Set Geo Location
                </button>
            </div>
            <div class="col-md-12">OR</div>
            <div class="col-md-3">
                <label for="asset_geo_location_lat">Enter Latitude <span class="star text-danger">*</span></label>
                <input type="text" id="asset_geo_location_lat" class="form-control form-control-sm"
                    name="asset_geo_location_lat" value="{{ old('asset_geo_location_lat', $building?->lat) }}"
                    placeholder="Enter Latitude" required>
            </div>
            <div class="col-md-3">
                <label for="asset_geo_location_lng">Enter Longitude <span class="star text-danger">*</span></label>
                <input type="text" id="asset_geo_location_lng" class="form-control form-control-sm"
                    name="asset_geo_location_lng" value="{{ old('asset_geo_location_lng', $building?->lon) }}"
                    placeholder="Enter Longitude" required>
            </div>
        </div>

        {{-- ── Building Category & Location ── --}}
        <div class="row form-1-box border my-2 py-2">
            <div class="col-sm-12 col-md-4">
                <label>Building Category: <span class="star text-danger">*</span></label><br>
                @foreach ($buildingClasses as $class)
                    <label for="building_class_{{ $class->building_class_cd }}">
                        {{ $class->building_class_descr }}
                    </label>
                    <input type="radio" id="building_class_{{ $class->building_class_cd }}" name="building_class_cd"
                        value="{{ $class->building_class_cd }}"
                        {{ old('building_class_cd', $building?->building_class_cd) == $class->building_class_cd ? 'checked' : '' }}>
                @endforeach
                <br>
                <span class="spanHide text-danger text-xs mt-2" id="building_class_cd_error"></span>
            </div>
            <div class="col-sm-12 col-md-3">
                <label for="building_location_cd">Location: <span class="star text-danger">*</span></label>
                <select class="form-control form-control-sm" id="building_location_cd" name="building_location_cd">
                    <option value="">Choose one</option>
                    {{-- Dynamic Content --}}
                </select>
                <span class="spanHide text-danger text-xs mt-2" id="building_location_cd_error"></span>
            </div>
        </div>

        {{-- ── Housing Details (shown/hidden via JS) ── --}}
        <div id="housingContainer" style="{{ $isEdit ? '' : 'display: none;' }}">
            <div class="row form-1-box border py-2">
                <div class="col-md-3" id="quarterNoInput"
                    style="{{ $isEdit && $building?->building_class_cd == 0 ? '' : 'display: none;' }}">
                    <label for="quarter_no">Quarter No:</label>
                    <input type="text" name="quarter_no" id="quarter_no"
                        class="form-control form-control-sm text-uppercase" placeholder="Enter Quarter No"
                        value="{{ old('quarter_no', $building?->qtr_no) }}">
                    <span class="spanHide text-danger text-xs mt-2" id="quarter_no_error"></span>
                </div>
                <div class="col-md-3" id="buildingNameInput"
                    style="{{ $isEdit && $building?->building_class_cd != 0 ? '' : 'display: none;' }}">
                    <label for="building_name">Name of the Building:</label>
                    <input type="text" name="building_name" id="building_name"
                        class="form-control form-control-sm text-uppercase" placeholder="Enter Name of the Building"
                        value="{{ old('building_name', $building?->bld_qtr_name) }}">
                    <span class="spanHide text-danger text-xs mt-2" id="building_name_error"></span>
                </div>
                <div class="col-md-3">
                    <label for="building_type_cd">Building Type <span class="star text-danger">*</span></label>
                    <select class="form-control form-control-sm" id="building_type_cd" name="building_type_cd">
                        <option value="">Choose one</option>
                        {{-- Dynamic Content --}}
                    </select>
                    <span class="spanHide text-danger text-xs mt-2" id="building_type_cd_error"></span>
                </div>
                <div class="col-md-3">
                    <label for="owning_dept">Owning Department <span class="star text-danger">*</span></label>
                    <select class="form-control form-control-sm" id="owning_dept" name="owning_dept">
                        <option value="">Choose one</option>
                        @foreach ($departmentDetails as $dept)
                            <option value="{{ $dept->id }}"
                                {{ old('owning_dept', $building?->asset_owning_dept_cd) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->dept_name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="spanHide text-danger text-xs mt-2" id="owning_dept_error"></span>
                </div>
            </div>
        </div>

    </fieldset>

    {{-- ── Action Buttons ── --}}
    <div class="row mt-2 text-end">
        <div class="col-12">
            <button type="submit" class="btn btn-success btn-sm text-md rounded-0 mt-2">
                <i class="fa fa-save"></i>
                {{ $isEdit ? 'Update' : 'Save' }}
            </button>
            <button type="reset" class="btn btn-info btn-sm text-md rounded-0 mt-2">
                <i class="fa fa-undo" aria-hidden="true"></i>
                Reset
            </button>
            <button type="button" class="btn btn-secondary btn-sm rounded-0 mt-2" onclick="history.back()">
                <i class="fa fa-backward"></i> Back
            </button>
        </div>
    </div>

</form>
