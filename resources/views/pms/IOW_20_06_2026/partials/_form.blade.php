<form action="{{ $formAction }}" method="POST">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- hidden --}}
    <input type="hidden" name="project_cd" value="{{ $project->project_cd }}">

    <div class="row g-2">
        <div class="col-12 col-md-4">
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
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-2">
            <label class="form-label" for="unit">Item Unit <span class="text-danger">*</span></label>
            <input type="text" name="unit" id="unit" class="form-control form-control-sm"
                placeholder="Select an Item of Work" value="{{ old('unit', $currentItem->unit ?? '') }}" readonly />
        </div>
    </div>

    <hr>

    <div class="row g-2">
        <div class="col-12 col-md-2">
            <label class="form-label" for="quantity">Item Quantity <span class="text-danger">*</span></label>
            <input type="text" name="quantity" id="quantity" class="form-control form-control-sm" placeholder="0.00"
                value="{{ old('quantity', $currentItem->quantity ?? '') }}" />
            @error('quantity')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-2">
            <label class="form-label" for="est_start_date">Start Date <span class="text-danger">*</span></label>
            <input type="date" name="est_start_date" id="est_start_date" class="form-control form-control-sm"
                value="{{ old('est_start_date', $currentItem->est_start_date ?? '') }}" />
            @error('est_start_date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 col-md-2">
            <label class="form-label" for="est_end_date">End Date <span class="text-danger">*</span></label>
            <input type="date" name="est_end_date" id="est_end_date" class="form-control form-control-sm"
                value="{{ old('est_end_date', $currentItem->est_end_date ?? '') }}" />
            @error('est_end_date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <span class="d-block border-top mt-3"></span>
    <div class="text-end">
        <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2">
            <i class="fa fa-save"></i> {{ $isEdit ? 'Update' : 'Submit' }}
        </button>
        <button type="button" class="btn btn-secondary btn-sm rounded-0 mt-2" onclick="history.back()">
            <i class="fa fa-backward"></i> Cancel
        </button>
    </div>
</form>
