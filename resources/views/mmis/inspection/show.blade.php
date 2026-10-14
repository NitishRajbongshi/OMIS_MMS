{{-- Save as: resources/views/mmis/inspection/show.blade.php --}}
@extends('layouts.app')

@section('content')

<style>
    /* Label / value look for the detail boxes */
    .detail-label {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 0;
    }
    .detail-value {
        font-weight: 500;
        margin-bottom: 12px;
        word-wrap: break-word;
        overflow-wrap: anywhere;
    }
</style>

<div class="container-fluid py-4">

    {{-- Page heading + back button --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Inspection Details - {{ $inspection->insp_cd }}</h4>
        <a href="{{ route('mmis.inspection.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>

    {{-- 1. INSPECTION DETAILS --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Inspection Details</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="detail-label">Inspection ID</p>
                    <p class="detail-value">{{ $inspection->insp_cd }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Inspection Date</p>
                    <p class="detail-value">
                        {{ $inspection->insp_date ? \Carbon\Carbon::parse($inspection->insp_date)->format('d-m-Y') : '-' }}
                    </p>
                </div>
                {{-- <div class="col-md-3">
                    <p class="detail-label">Inspection Type</p>
                    <p class="detail-value">{{ $inspection->insp_type ?: '-' }}</p>
                </div> --}}
                <div class="col-md-3">
                    <p class="detail-label">Defects Observed</p>
                    <p class="detail-value">{{ $inspection->is_defects_observed == 'Y' ? 'Yes' : 'No' }}</p>
                </div>

                <div class="col-md-3">
                    <p class="detail-label">Inspector Name</p>
                    <p class="detail-value">{{ $inspection->inspector_name ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Inspector Designation</p>
                    <p class="detail-value">{{ $inspection->inspector_designation ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Asset Type</p>
                    <p class="detail-value">{{ $inspection->asset_type ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Asset ID</p>
                    <p class="detail-value">{{ $inspection->insp_asset_id ?: '-' }}</p>
                </div>

                <div class="col-md-3">
                    <p class="detail-label">Road Name</p>
                    <p class="detail-value">{{ $inspection->road_name ?: '-' }}</p>
                </div>
                @if($inspection->asset_type === 'Roads')
                <div class="col-md-3">
                    <p class="detail-label">Start Section</p>
                    <p class="detail-value">{{ $inspection->start_section ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">End Section</p>
                    <p class="detail-value">{{ $inspection->end_section ?? '-' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- BRIDGE DETAILS (shown only for bridge inspections) --}}
    @if ($bridge)
    <div class="card mb-3">
        <div class="card-header fw-bold">Bridge Details</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="detail-label">Bridge ID</p>
                    <p class="detail-value">{{ $bridge->rd_bridge_cd ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Bridge Name</p>
                    <p class="detail-value">{{ $bridge->bridge_name ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Bridge Chainage</p>
                    <p class="detail-value">{{ $bridge->bridge_chainage ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Bridge Classification</p>
                    <p class="detail-value">{{ $bridge->bridge_classification ?? '-' }}</p>
                </div>

                <div class="col-md-3">
                    <p class="detail-label">Number of Spans</p>
                    <p class="detail-value">{{ $bridge->number_of_spans ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Span Length</p>
                    <p class="detail-value">{{ $bridge->span_length ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Total Length</p>
                    <p class="detail-value">{{ $bridge->total_length ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Foundation Configuration</p>
                    <p class="detail-value">{{ $bridge->foundation_configuration ?? '-' }}</p>
                </div>

                <div class="col-md-3">
                    <p class="detail-label">Highest Flood Level</p>
                    <p class="detail-value">{{ $bridge->highest_flood_level ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Ordinary Flood Level</p>
                    <p class="detail-value">{{ $bridge->ordinary_flood_level ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- CULVERT DETAILS (shown only for culvert inspections) --}}
    @if ($culvert)
    <div class="card mb-3">
        <div class="card-header fw-bold">Culvert Details</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="detail-label">Culvert ID</p>
                    <p class="detail-value">{{ $culvert->rd_cdwork_cd ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Road ID</p>
                    <p class="detail-value">{{ $culvert->rd_system_id ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Culvert No</p>
                    <p class="detail-value">{{ $culvert->culvert_no ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Chainage</p>
                    <p class="detail-value">{{ $culvert->chainage ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Culvert Type</p>
                    <p class="detail-value">{{ $culvert->culvert_type_cd ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Culvert Width</p>
                    <p class="detail-value">{{ $culvert->culvert_width ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 2. CONDITIONS --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Conditions</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="detail-label">Overall Condition</p>
                    <p class="detail-value">{{ $inspection->overall_condition ?: '-' }}</p>
                </div>
                @if($inspection->asset_type === 'Roads')
                <div class="col-md-3">
                    <p class="detail-label">Pavement Condition</p>
                    <p class="detail-value">{{ $inspection->pavement_condition ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Drainage Condition</p>
                    <p class="detail-value">{{ $inspection->drainage_condition ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Shoulder Condition</p>
                    <p class="detail-value">{{ $inspection->shoulder_condition ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Structural Condition</p>
                    <p class="detail-value">{{ $inspection->structural_condition ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Safety Features Condition</p>
                    <p class="detail-value">{{ $inspection->safety_condition ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Signage Condition</p>
                    <p class="detail-value">{{ $inspection->signage_condition ?: '-' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 3. RECOMMENDATION --}}
    {{-- <div class="card mb-3">
        <div class="card-header fw-bold">Risk & Recommendation</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="detail-label">Risk Type</p>
                    <p class="detail-value">{{ $inspection->risk_name ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Recommended Action</p>
                    <p class="detail-value">{{ $inspection->action_name ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Priority</p>
                    <p class="detail-value">{{ $inspection->priority_name ?: '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="detail-label">Recommended Work</p>
                    <p class="detail-value">{{ $inspection->recmnd_work ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- 4. OBSERVATIONS --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Observations ({{ $observations->count() }})</div>
        <div class="card-body">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Activity</th>
                        <th>Observation</th>
                        <th>Grading</th>
                        <th width="100">Weightage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($observations as $obs)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $obs->activity_name ?: '-' }}</td>
                            <td>{{ $obs->obsrv_desc ?: '-' }}</td>
                            <td>{{ $obs->grading_name ?: '-' }}</td>
                            <td>{{ $obs->obsrv_weightage ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No observations found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 5. PCI DETAILS --}}
    @if($inspection->asset_type === 'Roads')
    <div class="card mb-3">
        <div class="card-header fw-bold">PCI Details</div>
        <div class="card-body">
            @if ($pci)
                <div class="row">
                    <div class="col-md-3">
                        <p class="detail-label">PCI Section Code</p>
                        <p class="detail-value">{{ $pci->pci_section_cd }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">PCI Year</p>
                        <p class="detail-value">{{ $pci->pci_year ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">Section Length (m)</p>
                        <p class="detail-value">{{ $pci->pci_section_length_in_meter ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">Chainage</p>
                        <p class="detail-value">{{ $pci->chainage ?? '-' }}</p>
                    </div>

                    {{-- <div class="col-md-3">
                        <p class="detail-label">Cracking (%)</p>
                        <p class="detail-value">{{ $pci->cracking_percent ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">Ravelling (%)</p>
                        <p class="detail-value">{{ $pci->ravelling_percent ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">Pot Holes (%)</p>
                        <p class="detail-value">{{ $pci->pot_holes_percent ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">Shoving (%)</p>
                        <p class="detail-value">{{ $pci->shoving_percent ?? '-' }}</p>
                    </div>

                    <div class="col-md-3">
                        <p class="detail-label">Patching (%)</p>
                        <p class="detail-value">{{ $pci->patching_percent ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">Settlement / Depression (%)</p>
                        <p class="detail-value">{{ $pci->settlement_depression_percent ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">Rut Depth</p>
                        <p class="detail-value">{{ $pci->rut_depth ?? '-' }}</p>
                    </div> --}}
                    <div class="col-md-3">
                        <p class="detail-label">Motorized Traffic / Day</p>
                        <p class="detail-value">{{ $pci->tot_motorized_traffic_per_day ?? '-' }}</p>
                    </div>

                    <div class="col-md-3">
                        <p class="detail-label">Commercial Vehicle Traffic / Day</p>
                        <p class="detail-value">{{ $pci->tot_comm_veh_traffic_per_day ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">Pavement Traffic Light</p>
                        <p class="detail-value">{{ $pci->pv_traffic_light ?: '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">PCI Value</p>
                        <p class="detail-value">{{ $pci->pci_value ?? '-' }}</p>
                    </div>
                    <div class="col-md-3">
                        <p class="detail-label">PCI Remarks</p>
                        <p class="detail-value">{{ $pci->pci_remarks ?: '-' }}</p>
                    </div>
                </div>
            @else
                <p class="mb-0 text-muted">No PCI details linked to this inspection.</p>
            @endif
        </div>
    </div>
    @endif

    {{-- 6. REMARKS --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Remarks</div>
        <div class="card-body">
            <p class="mb-0">{{ $inspection->remarks ?: '-' }}</p>
        </div>
    </div>

</div>
@endsection