<form action="{{ $formAction }}" method="POST">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <div class="row g-3">
        <div class="col-md-4 col-sm-6">
            <label class="form-label text-bold" for="location_name">Location Name<span class="text-danger">*</span></label>
            <input type="text" id="location_name" class="form-control form-control-sm" name="location_name"
                placeholder="Enter location name" value="{{ old('location_name', $location->location_name ?? '') }}">
            @error('location_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2 col-sm-6">
            <label class="form-label text-bold" for="building_class_cd">Building Class<span class="text-danger">*</span></label>
            <select id="building_class_cd" class="form-select form-select-sm" name="building_class_cd">
                <option value="" selected>Choose One</option>
                @foreach ($buildingClasses as $buildingClass)
                    <option value="{{ $buildingClass->building_class_cd }}"
                        {{ old('building_class_cd', $location->building_class_cd ?? '') == $buildingClass->building_class_cd ? 'selected' : '' }}>
                        {{ $buildingClass->building_class_descr }}
                    </option>
                @endforeach
            </select>
            @error('building_class_cd')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3 col-sm-6">
            <label class="form-label text-bold" for="division_cd">Division Office<span class="text-danger">*</span></label>
            <select id="division_cd" class="form-select form-select-sm" name="division_cd">
                <option value="" disabled selected hidden>Choose One</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->division_cd }}"
                        {{ old('division_cd', $location->division_cd ?? '') == $division->division_cd ? 'selected' : '' }}>
                        {{ $division->division_name }}
                    </option>
                @endforeach
            </select>
            @error('division_cd')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3 col-sm-6">
            <label class="form-label text-bold" for="sub_division_cd">Sub Division Office<span class="text-danger">*</span></label>
            <select id="sub_division_cd" class="form-select form-select-sm" name="sub_division_cd"
                data-selected="{{ old('sub_division_cd', $location->sub_division_cd ?? '') }}">
                <option value="" disabled selected hidden>Choose One</option>
                {{-- Options populated via JS based on division_cd selection --}}
                {{-- Pass selected value for edit mode --}}
            </select>
            @error('sub_division_cd')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary btn-sm px-3">
                <i class="fa fa-save me-1"></i> {{ $isEdit ? 'Update' : 'Submit' }}
            </button>
            <button type="reset" class="btn btn-light btn-sm px-3 border ms-1">
                <i class="fa fa-undo me-1" aria-hidden="true"></i> Reset
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 ms-1" onclick="history.back()">
                <i class="fa fa-arrow-left me-1"></i> Back
            </button>
        </div>
    </div>
</form>

