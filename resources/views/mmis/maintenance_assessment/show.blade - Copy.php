@extends('layouts.app')

@section('title', 'Maintenance Assessment Detail')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h4 class="mb-1">Maintenance Assessment</h4><p class="text-muted mb-0">Latest inspection inputs for {{ $inspectionDetail->insp_asset_id }}</p></div>
        <a href="{{ route('mmis.maintenance-assessment.assets', $inspectionDetail->insp_asset_type) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-arrow-left"></i> Asset List</a>
    </div>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> This assessment screen is read-only and does not modify inspection, PCI, habitation, or asset data.</div>
    <div class="row g-3 mb-3">
        <div class="col-md-6 col-xl"><div class="card h-100"><div class="card-body"><small class="text-muted">Asset</small><div class="fw-bold">{{ $inspectionDetail->asset_type_name ?: $inspectionDetail->insp_asset_type }}</div><div>{{ $inspectionDetail->insp_asset_id }}</div><small>{{ $inspectionDetail->insp_asset_location ?: 'Location unavailable' }}</small></div></div></div>
        <div class="col-md-6 col-xl"><div class="card h-100"><div class="card-body"><small class="text-muted">Latest Inspection</small><div class="fw-bold">{{ $inspectionDetail->insp_cd }}</div><div>{{ $inspectionDetail->insp_date ? \Illuminate\Support\Carbon::parse($inspectionDetail->insp_date)->format('d M Y') : '—' }}</div><small>Section: {{ $inspectionDetail->start_section ?? '—' }} – {{ $inspectionDetail->end_section ?? '—' }}</small></div></div></div>
        <div class="col-md-6 col-xl"><div class="card h-100"><div class="card-body"><small class="text-muted">PCI Value</small><div class="display-6 fw-bold {{ $pci ? 'text-primary' : 'text-muted' }}">{{ $pci->pci_value ?? '—' }}</div><small>{{ $pci ? 'Section: '.$pci->pci_section_cd : 'No linked PCI record' }}</small></div></div></div>
        <div class="col-md-6 col-xl"><div class="card h-100"><div class="card-body"><small class="text-muted">Habitation Score</small><div class="display-6 fw-bold {{ $habitation ? 'text-primary' : 'text-muted' }}">{{ $habitation->habitation_score ?? '—' }}</div><small>{{ $habitation ? ($habitation->village_name ?: 'Linked road habitation data') : 'No linked habitation record' }}</small></div></div></div>
        <div class="col-md-6 col-xl"><div class="card h-100"><div class="card-body"><small class="text-muted">Asset Criticality Index</small><div class="display-6 fw-bold {{ $criticality ? 'text-primary' : 'text-muted' }}">{{ $criticality->criticality_index ?? '—' }}</div><small>{{ $criticality ? 'From master criticality index' : 'No criticality index defined' }}</small></div></div></div>
    </div>
    <div class="row g-3 mb-3"><div class="col-md-6 col-xl-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Total Observation Weight</small><div class="h3 mb-0">{{ $totalWeight }}</div></div></div></div><div class="col-md-6 col-xl-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Average Observation Weight</small><div class="h3 mb-0 text-primary">{{ $averageWeight ?? '—' }}</div><small>{{ $observations->count() }} observation(s)</small></div></div></div><div class="col-md-6 col-xl-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Unsatisfactory Weight</small><div class="h3 mb-0 text-danger">{{ $unsatisfactoryWeight }}</div></div></div></div><div class="col-md-6 col-xl-3"><div class="card h-100"><div class="card-body"><small class="text-muted">Inspection Criticality</small><div class="h3 mb-0 {{ $inspectionCriticality === null ? 'text-muted' : 'text-warning' }}">{{ $inspectionCriticality === null ? '—' : $inspectionCriticality.'%' }}</div><small>Unsatisfactory weight ÷ total weight</small></div></div></div></div>
    <div class="card shadow-sm"><div class="card-header bg-white fw-bold">Inspection Observations and Weightage</div><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Activity</th><th>Observation</th><th>Grading</th><th class="text-center">Weightage</th></tr></thead><tbody>@forelse($observations as $observation)<tr><td><strong>{{ $observation->activity_cd }}</strong></td><td>{{ $observation->obsrv_desc ?: '—' }}</td><td><span class="badge {{ strtoupper($observation->grading_cd) === 'U' ? 'bg-danger' : 'bg-success' }}">{{ $observation->grading_cd }}</span></td><td class="text-center"><span class="badge bg-secondary">{{ $observation->obsrv_weightage }}</span></td></tr>@empty<tr><td colspan="4" class="text-center text-muted py-4">No observations were recorded for this inspection.</td></tr>@endforelse</tbody></table></div></div></div>
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-white fw-bold">Maintenance Score Parameters</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Parameter</th>
                            <th class="text-center" style="width: 220px;">Value</th>
                            <th class="text-center" style="width: 220px;">Maximum Range</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Average Observation Weight</td>
                            <td class="text-center fw-semibold">{{ $averageWeight ?? '—' }}</td>
                            <td class="text-center fw-semibold">5</td>
                        </tr>
                        <tr>
                            <td>PCI Value</td>
                            <td class="text-center fw-semibold">{{ $pci->pci_value ?? '—' }}</td>
                            <td class="text-center fw-semibold">100</td>
                        </tr>
                        <tr>
                            <td>Habitation Score</td>
                            <td class="text-center fw-semibold">{{ $habitation->habitation_score ?? '—' }}</td>
                            <td class="text-center fw-semibold">100</td>
                        </tr>
                        <tr>
                            <td>Asset Criticality Score</td>
                            <td class="text-center fw-semibold">{{ $criticality->criticality_index ?? '—' }}</td>
                            <td class="text-center fw-semibold">100</td>
                        </tr>
                        <tr>
                            <td>Connectivity Index </td>
                            <td class="text-center fw-semibold">0</td>
                            <td class="text-center fw-semibold">100</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-end">
            <button type="button" class="btn btn-primary" title="Maintenance score calculation will be added after the scoring logic is finalized.">
                <i class="fa fa-calculator"></i> Calculate Maintenance Score
            </button>
        </div>
    </div>
</div>
@endsection
