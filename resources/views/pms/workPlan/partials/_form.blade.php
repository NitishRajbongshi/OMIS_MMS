<form action="{{ $formAction }}" method="POST">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-sm-6 col-md-4">
            <label class="form-label" for="plan_start_date">
                Estimated Start Date <span class="text-danger">*</span>
            </label>
            <input type="date" name="plan_start_date" id="plan_start_date"
                class="form-control form-control-sm @error('plan_start_date') is-invalid @enderror"
                value="{{ old('plan_start_date', isset($currentItem->plan_start_date) ? \Carbon\Carbon::parse($currentItem->plan_start_date)->format('Y-m-d') : '') }}" />
            @error('plan_start_date')
                <div class="invalid-feedback">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-4">
            <label class="form-label" for="plan_end_date">
                Estimated End Date <span class="text-danger">*</span>
            </label>
            <input type="date" name="plan_end_date" id="plan_end_date"
                class="form-control form-control-sm @error('plan_end_date') is-invalid @enderror"
                value="{{ old('plan_end_date', isset($currentItem->plan_end_date) ? \Carbon\Carbon::parse($currentItem->plan_end_date)->format('Y-m-d') : '') }}" />
            @error('plan_end_date')
                <div class="invalid-feedback">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-sm-6 col-md-4">
            <label class="form-label" for="wid_precedence_item_cd">
                Work Plan Predecesor <span class="text-danger">*</span>
            </label>
            <select name="wid_precedence_item_cd" id="wid_precedence_item_cd" class="form-select form-select-sm">
                <option value="">No predecessor</option>
                @foreach ($listOfPredessor as $predecessor)
                    <option value="{{ $predecessor->item_cd }}"
                        {{ old('wid_precedence_item_cd', $currentItem->wid_precedence_item_cd ?? $workItem->item_cd) == $predecessor->item_cd ? 'selected' : '' }}>
                        {{ $predecessor->item_name }}
                    </option>
                @endforeach
            </select>
            @error('wid_precedence_item_cd')
                <div class="invalid-feedback">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary btn-sm px-3 me-1">
                <i class="fa fa-save me-1"></i> {{ $isEdit ? 'Update' : 'Submit' }}
            </button>
            @if ($isEdit)
                <a href="{{ route('pms.work-plan.index', [$project->project_cd, $workItem->id]) }}"
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
