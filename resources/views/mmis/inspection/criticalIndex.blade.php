@extends('layouts.app')

@section('title', 'Critical Index Assessment')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Critical Index Assessment - {{ $inspection->insp_cd }}</h4>
        <a href="{{ route('mmis.inspection.show', $inspection->id) }}" class="btn btn-sm btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Inspection
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <strong>Road:</strong> {{ $inspection->insp_asset_location }}
            &nbsp;|&nbsp;
            <strong>Asset ID:</strong> {{ $inspection->insp_asset_id }}
            &nbsp;|&nbsp;
            <strong>Section:</strong> {{ $inspection->start_section }} - {{ $inspection->end_section }}
        </div>
    </div>

    <form action="{{ route('mmis.inspection.critical-index.store', $inspection->id) }}" method="POST" id="ciForm">
        @csrf

        <div class="card mb-3">
            <div class="card-header fw-bold">Assessment Parameters</div>
            <div class="card-body">
                <div class="small text-muted mb-3">
                    All five parameters are direct: a higher raw value always means higher criticality.
                    Weights are fixed and not officer-adjustable.
                </div>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Parameter</th>
                            <th>Raw Value</th>
                            <th style="width: 110px;">Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- PCI -->
                        <tr>
                            <td>
                                <strong>PCI (Pavement Condition Index)</strong>
                                <div class="small text-muted">Raw scale 0-5. Scaled as (raw / 5) &times; 100.</div>
                            </td>
                            <td>
                                @if ($pciValue !== null)
                                    <input type="text" class="form-control"
                                           value="{{ $pciValue }} (Category: {{ $pciCategory }})" readonly>
                                    <input type="hidden" name="pci_value" id="pciValue" value="{{ $pciValue }}">
                                @else
                                    <input type="number" name="pci_value" id="pciValue" class="form-control"
                                           min="0" max="5" step="0.01"
                                           value="{{ old('pci_value') }}" placeholder="0-5" required>
                                    <div class="small text-muted mt-1">No PCI record found for this inspection - please enter the raw PCI value manually.</div>
                                @endif
                            </td>
                            <td class="text-center fw-bold">40</td>
                        </tr>

                        <!-- Road Connectivity -->
                        <tr>
                            <td>
                                <strong>Road Connectivity</strong>
                                <div class="small text-muted">Raw scale 1-10. Scaled as (raw / 10) &times; 100.</div>
                            </td>
                            <td>
                                <input type="number" name="connectivity_raw" id="connectivityRaw" class="form-control"
                                       min="1" max="10" step="1"
                                       value="{{ old('connectivity_raw') }}" placeholder="1-10" required>
                            </td>
                            <td class="text-center fw-bold">20</td>
                        </tr>

                        <!-- Habitation Score -->
                        <tr>
                            <td>
                                <strong>Habitation Score</strong>
                                <div class="small text-muted">Pre-computed score (0-100) as per PMGSY-III Annexure-I (population, market, education, medical, transport). Already 0-100, used as-is.</div>
                            </td>
                            <td>
                                <input type="number" name="habitation_score" id="habitationScore"
                                       class="form-control" min="0" max="100" step="0.01"
                                       value="{{ old('habitation_score') }}" placeholder="0-100" required>
                            </td>
                            <td class="text-center fw-bold">10</td>
                        </tr>

                        <!-- Inspection Grading (auto, read-only) -->
                        <tr>
                            <td>
                                <strong>Inspection Grading</strong>
                                <div class="small text-muted">Auto-computed from field observations (weight &times; S/U grading, 0-100). Already 0-100, used as-is.</div>
                            </td>
                            <td>
                                <input type="text" class="form-control" value="{{ $inspectionGradingRaw }}" readonly>
                                <input type="hidden" id="inspectionGradingRaw" value="{{ $inspectionGradingRaw }}">
                            </td>
                            <td class="text-center fw-bold">20</td>
                        </tr>

                        <!-- Priority Index -->
                        <tr>
                            <td>
                                <strong>Priority Index</strong>
                                <div class="small text-muted">Raw scale 1-10. Scaled as (raw / 10) &times; 100.</div>
                            </td>
                            <td>
                                <input type="number" name="priority_index_raw" id="priorityIndexRaw" class="form-control"
                                       min="1" max="10" step="1"
                                       value="{{ old('priority_index_raw') }}" placeholder="1-10" required>
                            </td>
                            <td class="text-center fw-bold">10</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mb-0">
                    <label class="form-label fw-bold">Remarks (optional)</label>
                    <textarea name="remarks" class="form-control" rows="2">{{ old('remarks') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Critical Index Preview -->
        <div class="card mb-3">
            <div class="card-header fw-bold">Critical Index Preview</div>
            <div class="card-body">
                <div class="row row-cols-5 g-2 text-center mb-3">
                    <div class="col">
                        <div class="small text-muted">PCI Scaled</div>
                        <div class="fs-5" id="previewPci">-</div>
                    </div>
                    <div class="col">
                        <div class="small text-muted">Connectivity Scaled</div>
                        <div class="fs-5" id="previewConn">-</div>
                    </div>
                    <div class="col">
                        <div class="small text-muted">Habitation Scaled</div>
                        <div class="fs-5" id="previewHab">-</div>
                    </div>
                    <div class="col">
                        <div class="small text-muted">Inspection Grading Scaled</div>
                        <div class="fs-5" id="previewInsp">-</div>
                    </div>
                    <div class="col">
                        <div class="small text-muted">Priority Index Scaled</div>
                        <div class="fs-5" id="previewPriorityIdx">-</div>
                    </div>
                </div>
                <hr>
                <div id="previewAlert" class="alert alert-secondary text-center mb-0">
                    <div class="fs-4 fw-bold" id="previewIndex">Critical Index: -</div>
                    <div id="previewPriority">Fill in all parameters to see the live result</div>
                </div>
            </div>
        </div>

        <!-- Maintenance Recommendation & Confirmation -->
        <div class="card mb-3">
            <div class="card-header fw-bold">Maintenance Recommendation</div>
            <div class="card-body">
                <div class="alert alert-info mb-3" id="recommendationBox">
                    <span id="recommendationText">Fill in all parameters above to see the recommendation.</span>
                </div>

                <label class="form-label fw-bold">Confirm Maintenance Type</label>
                <div id="maintenanceTypeOptions">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="maintenance_type"
                               id="typeUrgent" value="Urgent"
                               @checked(old('maintenance_type') == 'Urgent')>
                        <label class="form-check-label" for="typeUrgent">
                            <span class="badge bg-danger">Urgent</span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="maintenance_type"
                               id="typeScheduled" value="Scheduled Maintenance"
                               @checked(old('maintenance_type') == 'Scheduled Maintenance')>
                        <label class="form-check-label" for="typeScheduled">
                            <span class="badge bg-warning text-dark">Scheduled Maintenance</span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="maintenance_type"
                               id="typeNonScheduled" value="Non-Scheduled Maintenance"
                               @checked(old('maintenance_type') == 'Non-Scheduled Maintenance')>
                        <label class="form-check-label" for="typeNonScheduled">
                            <span class="badge bg-success">Non-Scheduled Maintenance</span>
                        </label>
                    </div>
                </div>

                <div id="overrideBox" class="mt-3 d-none">
                    <label class="form-label fw-bold">Reason for Override (optional)</label>
                    <textarea name="override_reason" id="overrideReason" class="form-control" rows="2"
                              placeholder="Explain why you're choosing a different maintenance type than recommended...">{{ old('override_reason') }}</textarea>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                <i class="fa fa-check"></i> Confirm and move to maintenance table
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    // Mirrors CriticalIndexController's PHP scoring logic exactly (Step 1
    // scaling + Step 2 fixed-weight formula), so the preview shown here
    // always matches what gets saved on submit.

    function clamp(v, min, max) {
        return Math.max(min, Math.min(max, v));
    }

    // Step 1 - direct scaling, no inversions anywhere.
    function scalePci(raw) {
        return Math.round((clamp(raw, 0, 5) / 5) * 100 * 100) / 100;
    }
    function scaleConnectivity(raw) {
        return Math.round((clamp(raw, 0, 10) / 10) * 100 * 100) / 100;
    }
    function scaleHabitation(raw) {
        return Math.round(clamp(raw, 0, 100) * 100) / 100;
    }
    function scaleInspectionGrading(raw) {
        return Math.round(clamp(raw, 0, 100) * 100) / 100;
    }
    function scalePriorityIndex(raw) {
        return Math.round((clamp(raw, 0, 10) / 10) * 100 * 100) / 100;
    }

    // Step 2 - fixed weights: PCI 40, Connectivity 20, Habitation 10,
    // Inspection Grading 20, Priority Index 10.
    function computeCriticalIndex(pci, conn, hab, insp, pri) {
        const weightedSum = (pci * 40) + (conn * 20) + (hab * 10) + (insp * 20) + (pri * 10);
        return Math.round((weightedSum / 100) * 100) / 100;
    }

    // Track whether the officer has manually touched a radio button,
    // so auto-selection doesn't fight their choice once they've picked one.
    let userTouchedType = false;
    document.querySelectorAll('input[name="maintenance_type"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            userTouchedType = true;
            toggleOverrideBox();
        });
    });

    function recommendType(priority) {
        if (priority === 'High Priority') return 'Urgent';
        if (priority === 'Medium Priority') return 'Non-Scheduled Maintenance';
        return 'Scheduled Maintenance';
    }

    function toggleOverrideBox() {
        const recommended = document.getElementById('recommendationBox').dataset.recommended || '';
        const selected = document.querySelector('input[name="maintenance_type"]:checked');
        const overrideBox = document.getElementById('overrideBox');

        if (selected && recommended && selected.value !== recommended) {
            overrideBox.classList.remove('d-none');
        } else {
            overrideBox.classList.add('d-none');
        }
    }

    function recalc() {
        const pciRaw          = parseFloat(document.getElementById('pciValue').value);
        const connRaw         = parseFloat(document.getElementById('connectivityRaw').value);
        const habRaw          = parseFloat(document.getElementById('habitationScore').value);
        const inspGradingRaw  = parseFloat(document.getElementById('inspectionGradingRaw').value);
        const priRaw          = parseFloat(document.getElementById('priorityIndexRaw').value);

        const pciScaled  = isNaN(pciRaw)  ? null : scalePci(pciRaw);
        const connScaled = isNaN(connRaw) ? null : scaleConnectivity(connRaw);
        const habScaled  = isNaN(habRaw)  ? null : scaleHabitation(habRaw);
        const inspScaled = isNaN(inspGradingRaw) ? null : scaleInspectionGrading(inspGradingRaw);
        const priScaled  = isNaN(priRaw)  ? null : scalePriorityIndex(priRaw);

        document.getElementById('previewPci').textContent          = pciScaled  !== null ? pciScaled  : '-';
        document.getElementById('previewConn').textContent         = connScaled !== null ? connScaled : '-';
        document.getElementById('previewHab').textContent          = habScaled  !== null ? habScaled  : '-';
        document.getElementById('previewInsp').textContent         = inspScaled !== null ? inspScaled : '-';
        document.getElementById('previewPriorityIdx').textContent  = priScaled  !== null ? priScaled  : '-';

        const submitBtn = document.getElementById('submitBtn');
        const recommendationBox = document.getElementById('recommendationBox');
        const recommendationText = document.getElementById('recommendationText');

        if (pciScaled === null || connScaled === null || habScaled === null || inspScaled === null || priScaled === null) {
            document.getElementById('previewIndex').textContent = 'Critical Index: -';
            document.getElementById('previewPriority').textContent = 'Fill in all parameters to see the live result';
            document.getElementById('previewAlert').className = 'alert alert-secondary text-center mb-0';
            recommendationText.textContent = 'Fill in all parameters above to see the recommendation.';
            recommendationBox.dataset.recommended = '';
            submitBtn.disabled = true;
            return;
        }

        const index = computeCriticalIndex(pciScaled, connScaled, habScaled, inspScaled, priScaled);

        let priority, alertClass;
        if (index >= 70) {
            priority = 'High Priority';
            alertClass = 'alert alert-danger text-center mb-0';
        } else if (index >= 40) {
            priority = 'Medium Priority';
            alertClass = 'alert alert-warning text-center mb-0';
        } else {
            priority = 'Low Priority';
            alertClass = 'alert alert-success text-center mb-0';
        }

        document.getElementById('previewIndex').textContent = 'Critical Index: ' + index;
        document.getElementById('previewPriority').textContent = priority;
        document.getElementById('previewAlert').className = alertClass;

        // Recommendation + auto-select (only if officer hasn't manually chosen yet)
        const recommended = recommendType(priority);
        recommendationBox.dataset.recommended = recommended;
        recommendationText.innerHTML = 'Based on a Critical Index of <strong>' + index + '</strong> ('
            + priority + '), the recommended maintenance type is <strong>' + recommended + '</strong>.';

        if (!userTouchedType) {
            document.querySelectorAll('input[name="maintenance_type"]').forEach(function (radio) {
                radio.checked = (radio.value === recommended);
            });
        }

        toggleOverrideBox();
        submitBtn.disabled = false;
    }

    document.querySelectorAll('#pciValue, #connectivityRaw, #habitationScore, #priorityIndexRaw')
        .forEach(el => {
            el.addEventListener('change', recalc);
            el.addEventListener('input', recalc);
        });

    document.addEventListener('DOMContentLoaded', recalc);
</script>
@endpush