<form action="{{ $formAction }}" method="POST">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3">

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="occupant_name">Occupant Name <span class="text-danger">*</span></label>
            <input type="text" id="occupant_name" name="occupant_name"
                class="form-control form-control-sm"
                placeholder="Occupant full name..."
                value="{{ old('occupant_name', $currentOccupancy?->occupant_name ?? '') }}">
            @error('occupant_name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="occupant_dept_cd">Department <span class="text-danger">*</span></label>
            <select name="occupant_dept_cd" id="occupant_dept_cd" class="form-select form-select-sm">
                <option value="">Select Department</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}"
                        {{ old('occupant_dept_cd', $currentOccupancy?->occupant_dept_cd ?? '') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->dept_name }}
                    </option>
                @endforeach
            </select>
            @error('occupant_dept_cd')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="occupant_grade_cd">Occupant Grade <span class="text-danger">*</span></label>
            <select name="occupant_grade_cd" id="occupant_grade_cd" class="form-select form-select-sm">
                <option value="">Select Grade</option>
                @foreach ($grades as $grade)
                    <option value="{{ $grade->grade_cd }}"
                        {{ old('occupant_grade_cd', $currentOccupancy?->occupant_grade_cd ?? '') == $grade->grade_cd ? 'selected' : '' }}>
                        {{ $grade->grade_descr }}
                    </option>
                @endforeach
            </select>
            @error('occupant_grade_cd')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="occupied_from">Occupied From <span class="text-danger">*</span></label>
            <input type="date" id="occupied_from" name="occupied_from"
                class="form-control form-control-sm"
                value="{{ old('occupied_from', isset($currentOccupancy?->occupied_from) ? \Carbon\Carbon::parse($currentOccupancy->occupied_from)->format('Y-m-d') : '') }}">
            @error('occupied_from')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="occupied_to">Occupied To</label>
            <input type="date" id="occupied_to" name="occupied_to"
                class="form-control form-control-sm"
                value="{{ old('occupied_to', isset($currentOccupancy?->occupied_to) ? \Carbon\Carbon::parse($currentOccupancy->occupied_to)->format('Y-m-d') : '') }}">
            @error('occupied_to')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- <div class="col-sm-6 col-md-3">
            <label class="form-label" for="status_cd">Status <span class="text-danger">*</span></label>
            <select name="status_cd" id="status_cd" class="form-select form-select-sm">
                <option value="">Select Status</option>
                @foreach ($varificationStatuses as $status)
                    <option value="{{ $status->status_cd }}"
                        {{ old('status_cd', $currentOccupancy?->status_cd ?? '') == $status->status_cd ? 'selected' : '' }}>
                        {{ $status->status_descr }}
                    </option>
                @endforeach
            </select>
            @error('status_cd')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div> --}}

        <div class="col-12">
            <label class="form-label" for="remarks">Remarks</label>
            <textarea class="form-control form-control-sm" id="remarks" name="remarks" rows="2"
                placeholder="Write remarks (if any)...">{{ old('remarks', $currentOccupancy?->remarks ?? '') }}</textarea>
            @error('remarks')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

    </div>

    <div class="row mt-4">
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary btn-sm mt-2 px-3">
                <i class="fa fa-save me-1"></i> {{ $isEdit ? 'Update' : 'Submit' }}
            </button>
            <button type="reset" class="btn btn-light btn-sm mt-2 px-3 border">
                <i class="fa fa-undo me-1"></i> Reset
            </button>
            <a href="{{ route('building.unit.index', $buildingId) }}"
                class="btn btn-outline-secondary btn-sm mt-2 px-3">
                <i class="fa fa-arrow-left me-1"></i> Back to Units
            </a>
        </div>
    </div>

</form>
