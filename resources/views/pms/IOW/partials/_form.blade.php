<form action="{{ $formAction }}" method="POST">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- hidden --}}
    <input type="hidden" name="project_cd" value="{{ $project->project_cd }}">

    <div class="row g-3">
        <div class="col-sm-12 col-md-6">
            <label class="form-label" for="item_cd">Item of Work <span class="text-danger">*</span></label>
            <select name="item_cd" id="item_cd" class="form-select form-select-sm">
                <option value="">Select Item of Work</option>
                @foreach ($WorkItemList as $workItem)
                    <option value="{{ $workItem->item_cd }}" data-unit="{{ $workItem->unit_cd }}"
                        {{ old('item_cd', $currentItem->item_cd ?? '') == $workItem->item_cd ? 'selected' : '' }}>
                        {{ $workItem->item_name }}
                    </option>
                @endforeach
            </select>
            @error('item_cd')
                <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="unit">Item Unit <span class="text-danger">*</span></label>
            <input type="text" name="unit" id="unit" class="form-control form-control-sm bg-light"
                placeholder="Select Item of Work" value="{{ old('unit', $currentItem->unit ?? '') }}" readonly />
        </div>

        <div class="col-sm-6 col-md-3">
            <label class="form-label" for="quantity">Item Quantity <span class="text-danger">*</span></label>
            <input type="number" name="quantity" id="quantity" class="form-control form-control-sm" placeholder="0.00"
                value="{{ old('quantity', $currentItem->quantity ?? '') }}" step="0.01" min="0" />
            @error('quantity')
                <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        {{-- <div class="col-sm-6 col-md-4">
            <label class="form-label" for="est_start_date">Start Date <span class="text-danger">*</span></label>
            <input type="date" name="est_start_date" id="est_start_date" class="form-control form-control-sm"
                value="{{ old('est_start_date', $currentItem->est_start_date ?? '') }}" />
            @error('est_start_date')
                <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}
                </div>
            @enderror
        </div> --}}

        {{-- <div class="col-sm-6 col-md-4">
            <label class="form-label" for="est_end_date">End Date <span class="text-danger">*</span></label>
            <input type="date" name="est_end_date" id="est_end_date" class="form-control form-control-sm"
                value="{{ old('est_end_date', $currentItem->est_end_date ?? '') }}" />
            @error('est_end_date')
                <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}
                </div>
            @enderror
        </div> --}}
        
        <div class="col-12" id="sub_items_container" style="display: none;">
            <label class="form-label font-bold text-secondary">Available Sub Items</label>
            <div id="sub_items_checkboxes" class="d-flex flex-wrap gap-2 p-3 border rounded bg-light">
                <!-- Checkboxes will be populated dynamically via AJAX -->
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary btn-sm px-3 me-1">
                <i class="fa fa-save me-1"></i> {{ $isEdit ? 'Update' : 'Submit' }}
            </button>
            @if ($isEdit)
                <a href="{{ route('pms.work-item.index', $project->project_cd) }}"
                    class="btn btn-outline-secondary btn-sm px-3">
                    <i class="fa fa-times me-1"></i> Cancel
                </a>
            @else
                <button type="reset" class="btn btn-light btn-sm px-3 border">
                    <i class="fa fa-undo me-1"></i> Reset
                </button>
            @endif
        </div>
    </div>
</form>

<style>
    .sub-item-card {
        background: #ffffff;
        border: 1px solid var(--oamis-border, #e5e7eb);
        border-radius: 8px;
        padding: 8px 12px 8px 32px;
        transition: all 0.2s ease;
        cursor: pointer;
        min-width: 180px;
    }
    .sub-item-card:hover {
        border-color: var(--oamis-primary, #0b6b4a);
        background: rgba(11, 107, 74, 0.05);
    }
    .sub-item-card .form-check-input:checked {
        background-color: var(--oamis-primary, #0b6b4a);
        border-color: var(--oamis-primary, #0b6b4a);
    }
    .sub-item-card .form-check-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--oamis-ink, #1f2937);
        cursor: pointer;
    }
</style>

