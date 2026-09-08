<form action="{{ $formAction }}" method="POST">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <div class="row g-3">

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="unit_type_cd">Building Unit Type <span class="text-danger">*</span></label>
            <select name="unit_type_cd" id="unit_type_cd" class="form-select form-select-sm">
                <option value="">Select Building Unit Type</option>
                @foreach ($buildingUnitTypes as $unitType)
                    <option value="{{ $unitType->unit_type_cd }}"
                        {{ old('unit_type_cd', $currentUnit?->unit_type_cd ?? '') == $unitType->unit_type_cd ? 'selected' : '' }}>
                        {{ $unitType->unit_type_name }}
                    </option>
                @endforeach
            </select>
            @error('unit_type_cd')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="unit_name">Building Unit Name <span class="text-danger">*</span></label>
            <input type="text" id="unit_name" class="form-control form-control-sm" name="unit_name"
                placeholder="Building unit name..." value="{{ old('unit_name', $currentUnit?->unit_name ?? '') }}">
            @error('unit_name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="unit_no">Building Unit Number <span class="text-danger">*</span></label>
            <input type="text" id="unit_no" class="form-control form-control-sm" name="unit_no"
                placeholder="Building unit number..." value="{{ old('unit_no', $currentUnit?->unit_no ?? '') }}">
            @error('unit_no')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="floor_no">Building Floor Number <span class="text-danger">*</span></label>
            <input type="number" step="1" id="floor_no" class="form-control form-control-sm" name="floor_no"
                placeholder="Building floor number..." value="{{ old('floor_no', $currentUnit?->floor_no ?? '') }}">
            @error('floor_no')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-3">
            @php
                $totalAdded = $totalAddedPlinthArea ?? 0;
                $buildingTotal = $building->plinth_area ?? 0;
                $currentUnitArea = $currentUnit?->plinth_area ?? 0;
                $otherUnitsArea = $isEdit ? ($totalAdded - $currentUnitArea) : $totalAdded;
                $remainingForThisInput = $buildingTotal - $otherUnitsArea;
                $displayRemaining = $buildingTotal - $totalAdded;
            @endphp
            <label class="form-label" for="plinth_area">Unit Area (sq.ft) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" id="plinth_area" class="form-control form-control-sm"
                name="plinth_area" placeholder="Building plinth area..."
                value="{{ old('plinth_area', $currentUnit?->plinth_area ?? '') }}"
                min="0" max="{{ $remainingForThisInput }}">
            <small class="text-muted" id="total_added_plinth_area">Total added plinth area:
                {{ $totalAdded }}
            </small>
            <small class="text-muted" id="total_plinth_area">|| Building plinth area:
                {{ $buildingTotal }}
            </small>
            <br>
            <small class="text-success" id="remaining_plinth_area">Remaining Plinth Area:
                {{ $displayRemaining }}
            </small>
            <div class="text-danger small d-none" id="frontend_plinth_area_error"></div>

            @error('plinth_area')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Water Supply --}}
        <div class="col-sm-6 col-md-2">
            <label class="form-label d-block">Water Supply? <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline mt-2">
                <input class="form-check-input" type="radio" id="water_yes" name="has_water_supply" value="Y"
                    {{ old('has_water_supply', $currentUnit?->has_water_supply ?? 'Y') == 'Y' ? 'checked' : '' }}>
                <label class="form-check-label fw-normal" for="water_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline mt-2">
                <input class="form-check-input" type="radio" id="water_no" name="has_water_supply" value="N"
                    {{ old('has_water_supply', $currentUnit?->has_water_supply ?? 'Y') == 'N' ? 'checked' : '' }}>
                <label class="form-check-label fw-normal" for="water_no">No</label>
            </div>
            @error('has_water_supply')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Electricity --}}
        <div class="col-sm-6 col-md-2">
            <label class="form-label d-block">Electricity? <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline mt-2">
                <input class="form-check-input" type="radio" id="electricity_yes" name="has_electricity"
                    value="Y"
                    {{ old('has_electricity', $currentUnit?->has_electricity ?? 'Y') == 'Y' ? 'checked' : '' }}>
                <label class="form-check-label fw-normal" for="electricity_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline mt-2">
                <input class="form-check-input" type="radio" id="electricity_no" name="has_electricity"
                    value="N"
                    {{ old('has_electricity', $currentUnit?->has_electricity ?? 'Y') == 'N' ? 'checked' : '' }}>
                <label class="form-check-label fw-normal" for="electricity_no">No</label>
            </div>
            @error('has_electricity')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Sanitary --}}
        <div class="col-sm-6 col-md-2">
            <label class="form-label d-block">Sanitary? <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline mt-2">
                <input class="form-check-input" type="radio" id="sanitary_yes" name="has_sanitary" value="Y"
                    {{ old('has_sanitary', $currentUnit?->has_sanitary ?? 'Y') == 'Y' ? 'checked' : '' }}>
                <label class="form-check-label fw-normal" for="sanitary_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline mt-2">
                <input class="form-check-input" type="radio" id="sanitary_no" name="has_sanitary" value="N"
                    {{ old('has_sanitary', $currentUnit?->has_sanitary ?? 'Y') == 'N' ? 'checked' : '' }}>
                <label class="form-check-label fw-normal" for="sanitary_no">No</label>
            </div>
            @error('has_sanitary')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label class="form-label" for="remarks">Remarks</label>
            <textarea class="form-control form-control-sm" id="remarks" name="remarks" rows="2"
                placeholder="Write remarks (if any)...">{{ old('remarks', $currentUnit?->remarks ?? '') }}</textarea>
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
                <i class="fa fa-undo me-1" aria-hidden="true"></i> Reset
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm mt-2 px-3" onclick="history.back()">
                <i class="fa fa-arrow-left me-1"></i> Back
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const plinthInput = document.getElementById('plinth_area');
    const remainingEl = document.getElementById('remaining_plinth_area');
    const errorEl = document.getElementById('frontend_plinth_area_error');
    
    if (plinthInput) {
        const submitBtn = plinthInput.form ? plinthInput.form.querySelector('button[type="submit"]') : document.querySelector('button[type="submit"]');
        const buildingTotal = parseFloat("{{ $buildingTotal }}") || 0;
        const otherUnitsArea = parseFloat("{{ $otherUnitsArea }}") || 0;

        function validatePlinthArea() {
            const val = parseFloat(plinthInput.value) || 0;
            const remaining = buildingTotal - (otherUnitsArea + val);

            // Update remaining plinth area display
            remainingEl.textContent = 'Remaining Plinth Area: ' + remaining.toFixed(2);

            if (val < 0) {
                remainingEl.className = 'text-danger';
                errorEl.textContent = 'Plinth area cannot be negative.';
                errorEl.classList.remove('d-none');
                if (submitBtn) submitBtn.disabled = true;
            } else if (remaining < 0) {
                remainingEl.className = 'text-danger';
                errorEl.textContent = 'Unit Area exceeds the remaining plinth area of ' + (buildingTotal - otherUnitsArea).toFixed(2) + ' sq.ft.';
                errorEl.classList.remove('d-none');
                if (submitBtn) submitBtn.disabled = true;
            } else {
                remainingEl.className = 'text-success';
                errorEl.textContent = '';
                errorEl.classList.add('d-none');
                if (submitBtn) submitBtn.disabled = false;
            }
        }

        plinthInput.addEventListener('input', validatePlinthArea);
        plinthInput.addEventListener('change', validatePlinthArea);
        
        // Initial run in case there is old input or edit data
        validatePlinthArea();
    }
});
</script>
@endpush
