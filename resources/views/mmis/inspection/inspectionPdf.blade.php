@php
    // Logo is read from public/images/main_logo.png and embedded in the PDF.
    // If the file is missing, the report is still generated (without the logo).
    $logoPath = public_path('images/main_logo.png');
    $logo = file_exists($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;

    // Road = asset type 10. PCI and the detailed conditions are only for roads.
    $isRoad = (string) $inspection->insp_asset_type === '10';

    // Protection Wall = asset type 12. Defects / affected chainage are only for walls,
    // and the Observations section is not for walls.
    $isWall = (string) $inspection->insp_asset_type === '12';

    // Section counter: sections 1 to 3 are fixed, the rest are numbered automatically
    $sec = 3;

    $inspDate = $inspection->insp_date
        ? \Carbon\Carbon::parse($inspection->insp_date)->format('d-m-Y')
        : '-';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inspection Report - {{ $inspection->insp_cd }}</title>

    {{-- DomPDF does not support bootstrap, flexbox or grid. Use simple tables and plain CSS only. --}}
    <style>
        @page {
            margin: 30px 35px 55px 35px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        tr {
            page-break-inside: avoid;
        }

        /* ---------- Header ---------- */
        table.header td {
            vertical-align: middle;
        }
        .logo {
            height: 60px;
        }
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        /* Put your department / organisation name here if you want it printed under the title */
        .org-name {
            text-align: center;
            font-size: 11px;
            color: #444;
            margin-top: 3px;
        }
        .meta-box td {
            font-size: 10px;
            padding: 2px 0;
        }
        .header-line {
            border-top: 2px solid #1f3a5f;
            border-bottom: 1px solid #1f3a5f;
            height: 3px;
            margin: 8px 0 12px 0;
        }

        /* ---------- Section heading ---------- */
        .section-title {
            background: #1f3a5f;
            color: #fff;
            padding: 5px 8px;
            font-weight: bold;
            font-size: 11px;
            margin-top: 14px;
        }
        .sub-section-title {
            background: #e3e9f1;
            color: #1f3a5f;
            padding: 4px 8px;
            font-weight: bold;
            border: 1px solid #bbb;
            border-bottom: none;
            margin-top: 8px;
        }

        /* ---------- label / value table ---------- */
        table.details td {
            border: 1px solid #bbb;
            padding: 5px 6px;
            vertical-align: top;
        }
        table.details td.label {
            background: #f1f4f8;
            font-weight: bold;
            width: 18%;
        }
        table.details td.value {
            width: 32%;
        }

        /* ---------- grid table (conditions, observations) ---------- */
        table.grid th,
        table.grid td {
            border: 1px solid #bbb;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }
        table.grid th {
            background: #e3e9f1;
        }

        /* ---------- Signature block ---------- */
        table.sign td {
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }
        .sign-line {
            border-top: 1px solid #333;
            margin-top: 45px;
            padding-top: 4px;
        }

        /* ---------- Footer (repeats on every page) ---------- */
        #footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -35px;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #999;
            padding-top: 4px;
        }
        #footer .right {
            float: right;
        }
        #footer .page:after {
            content: counter(page);
        }
    </style>
</head>
<body>

    {{-- Footer: report number, generated time and page number --}}
    <div id="footer">
        <span>Inspection Report: {{ $inspection->insp_cd }} &nbsp;|&nbsp; Generated on {{ now()->format('d-m-Y h:i A') }}</span>
        <span class="right">Page <span class="page"></span></span>
    </div>

    {{-- ================= HEADER ================= --}}
    <table class="header">
        <tr>
            <td style="width:20%;">
                @if ($logo)
                    <img src="{{ $logo }}" class="logo" alt="Logo">
                @endif
            </td>
            <td style="width:55%;">
                <div class="report-title">INSPECTION REPORT</div>
                <div class="org-name">{{ $inspection->asset_type ?: 'Asset' }} Inspection</div>
            </td>
            <td style="width:25%;">
                <table class="meta-box">
                    <tr>
                        <td><b>Report No:</b></td>
                        <td>{{ $inspection->insp_cd }}</td>
                    </tr>
                    <tr>
                        <td><b>Generated On:</b></td>
                        <td>{{ now()->format('d-m-Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="header-line"></div>

    {{-- ================= 1. GENERAL INFORMATION ================= --}}
    <div class="section-title">General Information</div>
    <table class="details">
        <tr>
            <td class="label">Inspection ID</td>
            <td class="value">{{ $inspection->insp_cd }}</td>
            <td class="label">Inspection Date</td>
            <td class="value">{{ $inspDate }}</td>
        </tr>
        <tr>
            <td class="label">Inspection Type</td>
            <td class="value">{{ $inspection->insp_type ?: '-' }}</td>
            @if ($isWall)
                <td class="label">Defects Observed</td>
                <td class="value">{{ $inspection->is_defects_observed == 'Y' ? 'Yes' : 'No' }}</td>
            @else
                <td class="label"></td>
                <td class="value"></td>
            @endif
        </tr>
        <tr>
            <td class="label">Inspector Name</td>
            <td class="value">{{ $inspection->inspector_name ?: '-' }}</td>
            <td class="label">Inspector Designation</td>
            <td class="value">{{ $inspection->inspector_designation ?: '-' }}</td>
        </tr>
        @if ($isWall)
            <tr>
                <td class="label">Affected Start Chainage</td>
                <td class="value">{{ $inspection->affected_start_chainage ?? '-' }}</td>
                <td class="label">Affected End Chainage</td>
                <td class="value">{{ $inspection->affected_end_chainage ?? '-' }}</td>
            </tr>
        @endif
    </table>

    {{-- ================= 2. ASSET DETAILS ================= --}}
    <div class="section-title">Asset Details</div>
    <table class="details">
        <tr>
            <td class="label">Asset Type</td>
            <td class="value">{{ $inspection->asset_type ?: '-' }}</td>
            <td class="label">Asset ID</td>
            <td class="value">{{ $inspection->insp_asset_id ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Road Name</td>
            <td class="value">{{ $inspection->road_name ?: '-' }}</td>
            {{-- Start / end section only for asset type 10 (Road) --}}
            @if ($isRoad)
                <td class="label">Inspected Section</td>
                <td class="value">{{ $inspection->start_section ?? '-' }} to {{ $inspection->end_section ?? '-' }}</td>
            @else
                <td class="label"></td>
                <td class="value"></td>
            @endif
        </tr>
    </table>

    {{-- BRIDGE DETAILS (only for bridge inspections) --}}
    @if ($bridge)
        <div class="sub-section-title">Bridge Details</div>
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
        <div class="sub-section-title">Culvert Details</div>
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

    {{-- PROTECTION WALL DETAILS (only for protection wall inspections) --}}
    @if ($wall)
        <div class="sub-section-title">Protection Wall Details</div>
        <table class="details">
            <tr>
                <td class="label">Protection Wall Code</td>
                <td class="value">{{ $wall->protection_wall_cd ?? '-' }}</td>
                <td class="label">Chainage</td>
                <td class="value">{{ $wall->chainage ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Wall Type</td>
                <td class="value">{{ $wall->wall_type_cd ?? '-' }}</td>
                <td class="label">Structure Type</td>
                <td class="value">{{ $wall->structure_type_cd ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Bottom Width</td>
                <td class="value">{{ $wall->bottom_width ?? '-' }}</td>
                <td class="label">Top Width</td>
                <td class="value">{{ $wall->top_width ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Length</td>
                <td class="value">{{ $wall->length ?? '-' }}</td>
                <td class="label">Height</td>
                <td class="value">{{ $wall->height ?? '-' }}</td>
            </tr>
        </table>
    @endif

    {{-- ================= 3. CONDITION ASSESSMENT ================= --}}
    <div class="section-title">Condition Assessment</div>
    <table class="grid">
        <thead>
            <tr>
                <th width="40">Sl.</th>
                <th>Component</th>
                <th width="35%">Condition</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><b>Overall Condition</b></td>
                <td><b>{{ $inspection->overall_condition ?: '-' }}</b></td>
            </tr>
            {{-- Other conditions are shown only for asset type 10 (Road) --}}
            @if ((string) $inspection->insp_asset_type === '10')
                <tr>
                    <td>2</td>
                    <td>Pavement</td>
                    <td>{{ $inspection->pavement_condition ?: '-' }}</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Drainage</td>
                    <td>{{ $inspection->drainage_condition ?: '-' }}</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Shoulder</td>
                    <td>{{ $inspection->shoulder_condition ?: '-' }}</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Structural</td>
                    <td>{{ $inspection->structural_condition ?: '-' }}</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Safety Features</td>
                    <td>{{ $inspection->safety_condition ?: '-' }}</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Signage</td>
                    <td>{{ $inspection->signage_condition ?: '-' }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- ================= OBSERVATIONS (not for protection walls) ================= --}}
    @if (!$isWall)
    <div class="section-title">Observations ({{ $observations->count() }})</div>
    <table class="grid">
        <thead>
            <tr>
                <th width="40">Sl.</th>
                <th width="22%">Activity</th>
                <th>Observation</th>
                <th width="15%">Grading</th>
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
                    <td colspan="5" style="text-align:center;">No observations recorded</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @endif {{-- end of observations (not for walls) --}}

    {{-- ================= PAVEMENT CONDITION INDEX (roads only) ================= --}}
    @if ($isRoad)
    <div class="section-title">Pavement Condition Index (PCI)</div>
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
                <td class="value"><b>{{ $pci->pci_value ?? '-' }}</b></td>
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
    @endif {{-- end of road-only PCI block --}}

    {{-- ================= RISK & RECOMMENDATION ================= --}}
    {{-- <div class="section-title">{{ ++$sec }}. Risk &amp; Recommendation</div>
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
    </table> --}}

    {{-- ================= 7. REMARKS ================= --}}
    <div class="section-title">Remarks</div>
    <table class="details">
        <tr>
            <td style="height:35px;">{{ $inspection->remarks ?: '-' }}</td>
        </tr>
    </table>

    {{-- ================= SIGNATURE BLOCK ================= --}}
    {{-- <table class="sign" style="margin-top:20px; page-break-inside: avoid;">
        <tr>
            <td>
                <div class="sign-line">
                    <b>Inspected By</b><br>
                    {{ $inspection->inspector_name ?: '-' }}<br>
                    {{ $inspection->inspector_designation ?: '' }}
                </div>
            </td>
            <td>
                <div class="sign-line">
                    <b>Date</b><br>
                    {{ $inspDate }}
                </div>
            </td>
        </tr>
    </table> --}}

</body>
</html>