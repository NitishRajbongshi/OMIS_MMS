@extends('layouts.app')
@section('title', 'Maintenance Assessment Detail')
@section('content')
<style>
    .maintenance-score-result {
        font-size: 24px;
        font-weight: 700 !important;
        color: #f59e0b !important;
        text-align: right;
    }

    .maintenance-score-result::placeholder {
        color: #f59e0b;
        opacity: 0.5;
    }
</style>
<div class="container-fluid">
    {{-- ================================================================
         PAGE HEADER
    ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Maintenance Assessment</h4>
            <p class="text-muted mb-0">
                Latest inspection inputs for {{ $inspectionDetail->insp_asset_id }}
            </p>
        </div>
        <a href="{{ route('mmis.maintenance-assessment.assets',$inspectionDetail->insp_asset_type) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-arrow-left"></i>
            Asset List
        </a>
    </div>
    {{-- ================================================================
         INFORMATION MESSAGE
    ================================================================= --}}
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        This assessment screen is read-only and does not modify inspection, PCI, habitation, or asset data.
    </div>
    {{-- ================================================================
         ASSET / INSPECTION SUMMARY
    ================================================================= --}}
    <div class="row g-3 mb-3">
        {{-- Asset --}}
        <div class="col-md-6 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Asset
                    </small>
                    <div class="fw-bold">
                        {{$inspectionDetail->asset_type_name ?: $inspectionDetail->insp_asset_type}}
                    </div>
                    <div>
                        {{ $inspectionDetail->insp_asset_id }}
                    </div>

                    <small>
                        {{$inspectionDetail->insp_asset_location?: 'Location unavailable'}}
                    </small>
                </div>
            </div>
        </div>
        {{-- Latest Inspection --}}
        <div class="col-md-6 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-muted">Latest Inspection</small>
                    <div class="fw-bold">{{ $inspectionDetail->insp_cd }}</div>
                    <div>
                        {{ $inspectionDetail->insp_date ? \Illuminate\Support\Carbon::parse($inspectionDetail->insp_date)->format('d M Y') : '—' }}
                    </div>
                    <small>
                        Section:
                        {{ $inspectionDetail->start_section ?? '—' }}
                        –
                        {{ $inspectionDetail->end_section ?? '—' }}
                    </small>
                </div>
            </div>
        </div>
        {{-- PCI --}}
        <div class="col-md-6 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-muted">
                        PCI Value
                    </small>

                    <div class="display-6 fw-bold {{ $pci ? 'text-primary' : 'text-muted' }}">
                        {{ $pci->pci_value ?? '—' }}
                    </div>
                    <small>
                        {{ $pci ? 'Section: '.$pci->pci_section_cd : 'No linked PCI record'}}
                    </small>
                </div>
            </div>
        </div>

        {{-- Average Habitation Score --}}
        <div class="col-md-6 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Average Habitation Score
                    </small>

                    <div class="display-6 fw-bold
                        {{ $habitation && $habitation->scored_habitations > 0
                            ? 'text-primary'
                            : 'text-muted' }}">
                        {{ $habitation && $habitation->scored_habitations > 0
                            ? number_format((float) $habitation->habitation_score, 2)
                            : '—' }}
                    </div>

                    <small>
                        {{ $habitation->total_habitations ?? 0 }}
                        habitation(s) on this road
                    </small>
                </div>
            </div>
        </div>
        {{-- Asset Criticality --}}
        <div class="col-md-6 col-xl">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Asset Criticality Index
                    </small>

                    <div class="display-6 fw-bold {{ $criticality ? 'text-primary' : 'text-muted' }}">
                        {{ $criticality->criticality_index ?? '—' }}
                    </div>
                    <small>
                        {{ $criticality ? 'From master criticality index' : 'No criticality index defined' }}
                    </small>
                </div>
            </div>
        </div>
    </div>


    {{-- ================================================================
         OBSERVATION SUMMARY
    ================================================================= --}}
    <div class="row g-3 mb-3">
        {{-- Total Observation Weight --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Total Observation Weight
                    </small>
                    <div class="h3 mb-0">
                        {{ $totalWeight }}
                    </div>
                </div>
            </div>
        </div>
        {{-- Average Observation Weight --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Average Observation Weight
                    </small>

                    <div class="h3 mb-0 text-primary">
                        {{ $averageWeight ?? '—' }}
                    </div>
                    <small>
                        {{ $observations->count() }}
                        observation(s)
                    </small>
                </div>
            </div>
        </div>


        {{-- Unsatisfactory Weight --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Unsatisfactory Weight
                    </small>
                    <div class="h3 mb-0 text-danger">
                        {{ $unsatisfactoryWeight }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Inspection Criticality --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-muted">
                        Inspection Criticality
                    </small>

                    <div class="h3 mb-0 {{$inspectionCriticality === null ? 'text-muted' : 'text-warning'}}">
                        {{  $inspectionCriticality === null ? '—' : $inspectionCriticality.'%' }}
                    </div>
                    <small>
                        Unsatisfactory weight ÷ total weight
                    </small>
                </div>
            </div>
        </div>
    </div>


    {{-- ================================================================
         INSPECTION OBSERVATIONS
    ================================================================= --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white fw-bold">
            Inspection Observations and Weightage
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table  class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Activity</th>
                            <th>Observation</th>
                            <th>Grading</th>
                            <th class="text-center">Weightage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($observations as $observation)
                            <tr>
                                <td>
                                    <strong>
                                        {{ $observation->activity_cd }}
                                    </strong>
                                </td>

                                <td>
                                    {{  $observation->obsrv_desc ?: '—' }}
                                </td>

                                <td>
                                    <span class="badge {{ strtoupper($observation->grading_cd) === 'U'? 'bg-danger' : 'bg-success'}}">
                                        {{$observation->grading_cd}}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        {{$observation->obsrv_weightage}}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No observations were recorded for this inspection.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ================================================================
         DYNAMIC MAINTENANCE SCORE PARAMETERS
    ================================================================= --}}
    <div class="card shadow-sm mt-3">

        <div class="card-header bg-white fw-bold">
            Maintenance Score Parameters
        </div>

        <form id="maintenanceScoreForm">
            <input type="hidden" name="inspection_id" value="{{ $inspectionDetail->id }}">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Parameter</th>
                                <th class="text-center" style="width: 220px;">Value</th>
                                <th class="text-center" style="width: 220px;">Maximum Range</th>
                                <th class="text-center" style="width: 220px;">Weightage</th>
                            </tr>
                        </thead>
                        <tbody>
                           @forelse($parameters as $parameter)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $parameter->parameter_name }}
                                        </strong>
                                        {{-- Hidden parameter information --}}
                                        <input type="hidden" name="parameters[{{ $loop->index }}][parameter_set_id]" value="{{ $parameter->parameter_set_id }}">
                                        {{-- Parameter ID --}}
                                        <input type="hidden" name="parameters[{{ $loop->index }}][parameter_id]" value="{{ $parameter->parameter_id }}">
                                       {{-- Parameter Code --}}
                                        <input type="hidden" name="parameters[{{ $loop->index }}][parameter_code]" value="{{ $parameter->parameter_code }}">
                                        <input type="hidden" name="parameters[{{ $loop->index }}][parameter_name]" value="{{ $parameter->parameter_name }}">
                                    </td>


                                    {{-- ==================================================
                                        CURRENT VALUE
                                    =================================================== --}}
                                    <td class="text-center">
                                         <input type="text"
                                            name="parameters[{{ $loop->index }}][value]"
                                            value="{{ is_numeric($parameter->current_value) ? number_format((float) $parameter->current_value, 2, '.', '') : 0 }}"
                                            class="form-control text-center parameter-value"
                                            readonly>
                                    </td>


                                    {{-- ==================================================
                                        MAXIMUM RANGE
                                    =================================================== --}}
                                    <td class="text-center">
                                        <input type="text" name="parameters[{{ $loop->index }}][maximum_range]" value="{{ $parameter->upper_range ?? '' }}" class="form-control text-center" readonly>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="parameters[{{ $loop->index }}][weightage]" value="{{ $parameter->weightage ?? '' }}" class="form-control text-center" readonly>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No maintenance score parameters configured for this department and asset type.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-primary" id="calculateMaintenanceScore">
                        <i class="fa fa-calculator"></i>
                        Calculate Maintenance Score
                    </button>
                    <button type="button" class="btn btn-success" id="saveMaintenanceAssessment" disabled>
                        <i class="fa fa-save"></i>
                        Save Assessment
                    </button>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4 col-lg-4 offset-md-8">
                        <label for="masScore" class="form-label fw-semibold">
                            Total Maintenance Assessment Score:
                        </label>
                        <input type="text" id="masScore" name="masScore" class="form-control text-end fw-bold" value="" readonly placeholder="—">
                    </div>
                    <div class="col-md-4" display="none">
                        <!-- <label class="form-label fw-semibold">
                            Condition Score
                        </label> -->
                        <input type="hidden" id="conditionScore" class="form-control fw-bold text-end" readonly placeholder="—">
                    </div>
                    <div class="col-md-4" display="none">
                        <!-- <label class="form-label fw-semibold">
                            Criticality Score
                        </label> -->
                        <input type="hidden" id="criticalityScore" class="form-control fw-bold text-end" readonly placeholder="—">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 offset-md-6" display="none">
                        <!-- <label class="form-label fw-semibold">
                            Maintenance Priority Score (MPS)
                        </label> -->
                        <input type="hidden" id="mpsScore" class="form-control fw-bold text-end" readonly placeholder="—">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

<script>

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('maintenanceScoreForm');
        const calculateButton = document.getElementById('calculateMaintenanceScore');
        const saveButton = document.getElementById('saveMaintenanceAssessment');
        const masField = document.getElementById('masScore');
        const conditionField = document.getElementById('conditionScore');
        const criticalityField = document.getElementById('criticalityScore');
        const mpsField = document.getElementById('mpsScore');
        let calculationCompleted = false;

        // ================================================================
        // CALCULATE
        // ================================================================

        calculateButton.addEventListener('click', function () 
        {
            calculateButton.disabled = true;
            calculateButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Calculating...';
            const formData = new FormData(form);
            fetch("{{ route('mmis.maintenance-assessment.calculate') }}",
                {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                }
            )
            .then(response => response.json())
            .then(data => {
                calculateButton.disabled = false;
                calculateButton.innerHTML ='<i class="fa fa-calculator"></i> Calculate Maintenance Score';
                if (!data.success) {
                    calculationCompleted = false;
                    saveButton.disabled = true;
                    Swal.fire({
                        icon: 'info',
                        title: 'Information',
                        text: data.message || 'Unable to calculate Maintenance Score.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // ---------------------------------------------------------
                // Display scores
                // ---------------------------------------------------------

                masField.value = Number(data.mas).toFixed(2);
                conditionField.value = data.condition_score !== null ? Number(data.condition_score).toFixed(2) : '';
                criticalityField.value = data.criticality_score !== null ? Number(data.criticality_score).toFixed(2) : '';
                mpsField.value = data.mps !== null ? Number(data.mps).toFixed(2) : '';
                calculationCompleted = true;
                saveButton.disabled = false;
            })
            .catch(error => {
                console.error('Maintenance score calculation error:',error);
                calculateButton.disabled = false;
                calculateButton.innerHTML = '<i class="fa fa-calculator"></i> Calculate Maintenance Score';
                calculationCompleted = false;
                saveButton.disabled = true;
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: 'Unable to calculate Maintenance Assessment Score.',
                    confirmButtonText: 'OK'
                });
            });
        });

        // ================================================================
        // SAVE ASSESSMENT
        // ================================================================
        saveButton.addEventListener('click', async  function () {
            if (!calculationCompleted) {
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: 'Please calculate the Maintenance Score first.',
                    confirmButtonText: 'OK'
                });
                return;
            }
           
            const result = await Swal.fire({
                icon: 'warning',
                title: 'Save Maintenance Assessment?',
                text: 'Once saved, this assessment cannot be repeated.',
                showCancelButton: true,
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });

            if (!result.isConfirmed) {
                return;
            }

            saveButton.disabled = true;
            saveButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';
            const formData = new FormData(form);
            fetch("{{ route('mmis.maintenance-assessment.save') }}",
                {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                }
            )
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    saveButton.disabled = false;
                    saveButton.innerHTML ='<i class="fa fa-save"></i> Save Assessment';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Unable to save assessment.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Maintenance Assessment saved successfully.',
                    confirmButtonText: 'OK'
                });
                // Go back to asset list
                window.location.href ="{{ route('mmis.maintenance-assessment.assets',$inspectionDetail->insp_asset_type) }}";
            })
            .catch(error => {
                console.error('Maintenance assessment save error:',error);
                saveButton.disabled = false;
                saveButton.innerHTML = '<i class="fa fa-save"></i> Save Assessment';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to save Maintenance Assessment.',
                    confirmButtonText: 'OK'
                });
            });
        });
    });
</script>