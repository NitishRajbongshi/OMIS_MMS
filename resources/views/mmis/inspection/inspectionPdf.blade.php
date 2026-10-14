<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inspection Report - {{ $inspection->insp_cd }}</title>

    {{-- DomPDF does not support bootstrap, flexbox or grid. Use simple tables and plain CSS only. --}}
    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        h2 {
            text-align: center;
            margin: 0 0 4px 0;
        }

        .sub-title {
            text-align: center;
            color: #555;
            margin-bottom: 12px;
        }

        .section-title {
            background: #343a40;
            color: #fff;
            padding: 5px 8px;
            font-weight: bold;
            margin-top: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* label / value table */
        table.details td {
            border: 1px solid #bbb;
            padding: 5px 6px;
            vertical-align: top;
        }
        table.details td.label {
            background: #f1f1f1;
            font-weight: bold;
            width: 18%;
        }
        table.details td.value {
            width: 32%;
        }

        /* grid table (observations) */
        table.grid th,
        table.grid td {
            border: 1px solid #bbb;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }
        table.grid th {
            background: #e9ecef;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #777;
        }
    </style>
</head>
<body>

    <h2>Inspection Report</h2>
    <div class="sub-title">{{ $inspection->insp_cd }}</div>

    {{-- 1. INSPECTION DETAILS --}}
    <div class="section-title">Inspection Details</div>
    <table class="details">
        <tr>
            <td class="label">Inspection ID</td>
            <td class="value">{{ $inspection->insp_cd }}</td>
            <td class="label">Inspection Date</td>
            <td class="value">
                {{ $inspection->insp_date ? \Carbon\Carbon::parse($inspection->insp_date)->format('d-m-Y') : '-' }}
            </td>
        </tr>
        <tr>
            <td class="label">Inspection Type</td>
            <td class="value">{{ $inspection->insp_type ?: '-' }}</td>
            <td class="label">Defects Observed</td>
            <td class="value">{{ $inspection->is_defects_observed == 'Y' ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td class="label">Inspector Name</td>
            <td class="value">{{ $inspection->inspector_name ?: '-' }}</td>
            <td class="label">Inspector Designation</td>
            <td class="value">{{ $inspection->inspector_designation ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Asset Type</td>
            <td class="value">{{ $inspection->asset_type ?: '-' }}</td>
            <td class="label">Asset ID</td>
            <td class="value">{{ $inspection->insp_asset_id ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Road Name</td>
            <td class="value">{{ $inspection->road_name ?: '-' }}</td>
            <td class="label">Section (Start - End)</td>
            <td class="value">{{ $inspection->start_section ?? '-' }} - {{ $inspection->end_section ?? '-' }}</td>
        </tr>
    </table>

    {{-- BRIDGE DETAILS (only for bridge inspections) --}}
    @if ($bridge)
        <div class="section-title">Bridge Details</div>
        <table class="details">
            <tr>
                <td class="label">Bridge ID</td>
                <td class="value">{{ $bridge->rd_bridge_cd ?? '-' }}</td>
                <td class="label">Bridge Name</td>
                <td class="value">{{ $bridge->bridge_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Bridge Chainage</td>
                <td class="value">{{ $bridge->bridge_chainage ?? '-' }}</td>
                <td class="label">Bridge Classification</td>
                <td class="value">{{ $bridge->bridge_classification ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Number of Spans</td>
                <td class="value">{{ $bridge->number_of_spans ?? '-' }}</td>
                <td class="label">Span Length</td>
                <td class="value">{{ $bridge->span_length ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Total Length</td>
                <td class="value">{{ $bridge->total_length ?? '-' }}</td>
                <td class="label">Foundation Configuration</td>
                <td class="value">{{ $bridge->foundation_configuration ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Highest Flood Level</td>
                <td class="value">{{ $bridge->highest_flood_level ?? '-' }}</td>
                <td class="label">Ordinary Flood Level</td>
                <td class="value">{{ $bridge->ordinary_flood_level ?? '-' }}</td>
            </tr>
        </table>
    @endif

    {{-- CULVERT DETAILS (only for culvert inspections) --}}
    @if ($culvert)
        <div class="section-title">Culvert Details</div>
        <table class="details">
            <tr>
                <td class="label">Culvert ID</td>
                <td class="value">{{ $culvert->rd_cdwork_cd ?? '-' }}</td>
                <td class="label">Road ID</td>
                <td class="value">{{ $culvert->rd_system_id ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Culvert No</td>
                <td class="value">{{ $culvert->culvert_no ?? '-' }}</td>
                <td class="label">Chainage</td>
                <td class="value">{{ $culvert->chainage ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Culvert Type</td>
                <td class="value">{{ $culvert->culvert_type_cd ?? '-' }}</td>
                <td class="label">Culvert Width</td>
                <td class="value">{{ $culvert->culvert_width ?? '-' }}</td>
            </tr>
        </table>
    @endif

    {{-- 2. CONDITIONS --}}
    <div class="section-title">Conditions</div>
    <table class="details">
        <tr>
            <td class="label">Overall Condition</td>
            <td class="value">{{ $inspection->overall_condition ?: '-' }}</td>
            <td class="label">Pavement Condition</td>
            <td class="value">{{ $inspection->pavement_condition ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Drainage Condition</td>
            <td class="value">{{ $inspection->drainage_condition ?: '-' }}</td>
            <td class="label">Shoulder Condition</td>
            <td class="value">{{ $inspection->shoulder_condition ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Structural Condition</td>
            <td class="value">{{ $inspection->structural_condition ?: '-' }}</td>
            <td class="label">Safety Features Condition</td>
            <td class="value">{{ $inspection->safety_condition ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Signage Condition</td>
            <td class="value">{{ $inspection->signage_condition ?: '-' }}</td>
            <td class="label"></td>
            <td class="value"></td>
        </tr>
    </table>

    {{-- 3. RISK & RECOMMENDATION --}}
    <div class="section-title">Risk &amp; Recommendation</div>
    <table class="details">
        <tr>
            <td class="label">Risk Type</td>
            <td class="value">{{ $inspection->risk_name ?: '-' }}</td>
            <td class="label">Recommended Action</td>
            <td class="value">{{ $inspection->action_name ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Priority</td>
            <td class="value">{{ $inspection->priority_name ?: '-' }}</td>
            <td class="label">Recommended Work</td>
            <td class="value">{{ $inspection->recmnd_work ?: '-' }}</td>
        </tr>
    </table>

    {{-- 4. OBSERVATIONS --}}
    <div class="section-title">Observations ({{ $observations->count() }})</div>
    <table class="grid">
        <thead>
            <tr>
                <th width="30">#</th>
                <th>Activity</th>
                <th>Observation</th>
                <th>Grading</th>
                <th width="60">Weightage</th>
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
                    <td colspan="5" style="text-align:center;">No observations found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 5. PCI DETAILS --}}
    <div class="section-title">PCI Details</div>
    @if ($pci)
        <table class="details">
            <tr>
                <td class="label">PCI Section Code</td>
                <td class="value">{{ $pci->pci_section_cd }}</td>
                <td class="label">PCI Year</td>
                <td class="value">{{ $pci->pci_year ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Section Length (m)</td>
                <td class="value">{{ $pci->pci_section_length_in_meter ?? '-' }}</td>
                <td class="label">Chainage</td>
                <td class="value">{{ $pci->chainage ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Cracking (%)</td>
                <td class="value">{{ $pci->cracking_percent ?? '-' }}</td>
                <td class="label">Ravelling (%)</td>
                <td class="value">{{ $pci->ravelling_percent ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Pot Holes (%)</td>
                <td class="value">{{ $pci->pot_holes_percent ?? '-' }}</td>
                <td class="label">Shoving (%)</td>
                <td class="value">{{ $pci->shoving_percent ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Patching (%)</td>
                <td class="value">{{ $pci->patching_percent ?? '-' }}</td>
                <td class="label">Settlement / Depression (%)</td>
                <td class="value">{{ $pci->settlement_depression_percent ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Rut Depth</td>
                <td class="value">{{ $pci->rut_depth ?? '-' }}</td>
                <td class="label">Motorized Traffic / Day</td>
                <td class="value">{{ $pci->tot_motorized_traffic_per_day ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Commercial Vehicle Traffic / Day</td>
                <td class="value">{{ $pci->tot_comm_veh_traffic_per_day ?? '-' }}</td>
                <td class="label">Pavement Traffic Light</td>
                <td class="value">{{ $pci->pv_traffic_light ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">PCI Value</td>
                <td class="value">{{ $pci->pci_value ?? '-' }}</td>
                <td class="label">PCI Remarks</td>
                <td class="value">{{ $pci->pci_remarks ?: '-' }}</td>
            </tr>
        </table>
    @else
        <table class="details">
            <tr>
                <td>No PCI details linked to this inspection.</td>
            </tr>
        </table>
    @endif

    {{-- 6. REMARKS --}}
    <div class="section-title">Remarks</div>
    <table class="details">
        <tr>
            <td>{{ $inspection->remarks ?: '-' }}</td>
        </tr>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d-m-Y h:i A') }}
    </div>

</body>
</html>