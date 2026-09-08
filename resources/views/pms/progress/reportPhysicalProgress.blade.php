@extends('layouts.app')

@section('content')
    <div class="content-header pms-progress-page">
        <div class="container-fluid">
            <div class="pms-page-breadcrumb">
                <ol class="breadcrumb text-sm mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Physical Project Progress</li>
                </ol>
            </div>

            <section class="pms-progress-hero">
                <div>
                    <span class="pms-eyebrow">Project Monitoring System</span>
                    <h1>Physical Progress of Projects</h1>
                    <p>
                        Review project completion, item-wise work progress, financial movement, and progress history
                        for Nagaland PWD projects.
                    </p>
                </div>
                <div class="pms-hero-chip">
                    <i class="fas fa-chart-line"></i>
                    Live progress register
                </div>
            </section>

            <section class="pms-summary-grid">
                <article class="pms-summary-card">
                    <span>Total Projects</span>
                    <strong>{{ count($project_list) }}</strong>
                    <small>Projects available in this report</small>
                </article>
                <article class="pms-summary-card">
                    <span>Module</span>
                    <strong>PMS</strong>
                    <small>Physical and financial progress</small>
                </article>
                <article class="pms-summary-card">
                    <span>Action</span>
                    <strong>Drill Down</strong>
                    <small>Use View Details for item-wise status</small>
                </article>
            </section>
        </div>

        <div class="container-fluid mainBody pms-report-card">
            <div class="pms-card-header">
                <div>
                    <span class="pms-eyebrow">Progress Register</span>
                    <h2>Projects Under Nagaland PWD</h2>
                </div>
                <div class="pms-table-tools">
                    <span><i class="fas fa-search"></i> Search enabled</span>
                    <span><i class="fas fa-sort"></i> Sortable columns</span>
                </div>
            </div>

            <div class="pms-table-wrap">
                <table class="table table-bordered table-striped projectTable" id="project_details_table">
                    <thead class="theader">
                        <tr>
                            <th class="text-center" style="min-width: 1rem;">Sl<br>No.</th>
                            <th class="text-center" style="min-width: 4rem;">Project<br>Code</th>
                            <th class="text-center" style="min-width: 2rem;">Project<br>Name</th>
                            <th class="text-center" style="min-width: 4rem;">Project<br>Type</th>
                            <th class="text-center" style="min-width: 4rem;">Division</th>
                            <th class="text-center" style="min-width: 4rem;">Sub<br>Division</th>
                            <th class="text-center" style="min-width: 4rem;">Start Date</th>
                            <th class="text-center" style="min-width: 4rem;">End<br>Date</th>
                            <th class="text-center" style="min-width: 2rem;">Progress</th>
                            <th class="text-center pms-action-col" style="min-width: 4rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project_list as $index => $prj)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <span class="pms-code-pill">{{ $prj->project_cd }}</span>
                                </td>
                                <td class="pms-project-name">{{ $prj->project_name }}</td>
                                <td>{{ $prj->proj_type_descr }}</td>
                                <td>{{ $prj->division_name }}</td>
                                <td>{{ $prj->sub_div_name }}</td>
                                <td class="text-center">
                                    {{ $prj->project_start_date ? \Carbon\Carbon::parse($prj->project_start_date)->format('d-m-Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $prj->project_end_date ? \Carbon\Carbon::parse($prj->project_end_date)->format('d-m-Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="pms-progress-pill">{{ $prj->progress_percent }}%</span>
                                </td>
                                <td class="text-center pms-action-col">
                                    <button type="button" class="btn btn-primary btn-sm viewItems"
                                        data-project="{{ $prj->project_cd }}" data-name="{{ $prj->project_name }}">
                                        <i class="fas fa-eye"></i>
                                        <span>View Details</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade pms-progress-modal" id="itemModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <span class="pms-eyebrow">Project Drill-Down</span>
                            <h5 id="modalTitle" class="modal-title">Project Progress Details</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <ul class="nav nav-tabs pms-progress-tabs" id="progressTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="physical-tab" data-bs-toggle="tab"
                                    data-bs-target="#physicalProgress" type="button" role="tab">
                                    <i class="fas fa-tasks"></i>
                                    Physical Progress
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="financial-tab" data-bs-toggle="tab"
                                    data-bs-target="#financialProgress" type="button" role="tab">
                                    <i class="fas fa-indian-rupee-sign"></i>
                                    Financial Progress
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="gantt-tab" data-bs-toggle="tab"
                                    data-bs-target="#ganttProgress" type="button" role="tab">
                                    <i class="fas fa-calendar-days"></i>
                                    Gantt Chart
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pms-progress-tab-content">
                            <div class="tab-pane fade show active" id="physicalProgress" role="tabpanel">
                                <div class="pms-modal-section-head">
                                    <div>
                                        <h6>Item-Wise Physical Progress</h6>
                                        <p>Work quantity, completed quantity, and completion percentage.</p>
                                    </div>
                                    <button onclick="loadPreviousProgress()" class="btn btn-light custom-accordion-btn"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#previousProgressContainer">
                                        <i class="fas fa-clock-rotate-left"></i>
                                        View Previous Progress
                                    </button>
                                </div>

                                <div class="pms-table-wrap pms-modal-table">
                                    <table class="table table-bordered table-striped text-xs">
                                        <thead class="theader">
                                            <tr>
                                                <th>Item Of Work</th>
                                                <th>Total Qty</th>
                                                <th>Qty Done</th>
                                                <th>Progress</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemTableBody"></tbody>
                                    </table>
                                </div>

                                <div id="previousProgressContainer" class="pms-history-panel"></div>
                            </div>

                            <div class="tab-pane fade" id="financialProgress" role="tabpanel">
                                <div class="pms-modal-section-head">
                                    <div>
                                        <h6>Financial Progress</h6>
                                        <p>Payment entries, references, remaining amount, and payment progress.</p>
                                    </div>
                                </div>

                                <div class="pms-table-wrap pms-modal-table">
                                    <table class="table table-bordered table-striped text-xs">
                                        <thead class="theader">
                                            <tr>
                                                <th>Date</th>
                                                <th>Payment Amount</th>
                                                <th>Reference No.</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="financialTableBody">
                                            <tr>
                                                <td colspan="4" class="text-center pms-empty-state">
                                                    No Financial Data
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="ganttProgress" role="tabpanel">
                                <div class="pms-modal-section-head">
                                    <div>
                                        <h6>Item of Work Schedule</h6>
                                        <p>Planned start date, end date, duration, and quantity for the selected project.</p>
                                    </div>
                                </div>
                                <div id="ganttChartContainer" class="pms-gantt-container">
                                    <div class="pms-empty-state text-center">Loading project schedule...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pms-progress-page,
        .pms-progress-page *,
        .pms-progress-modal,
        .pms-progress-modal * {
            font-family: var(--oamis-font, "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif) !important;
        }

        .pms-progress-page {
            color: var(--oamis-ink);
        }

        .pms-page-breadcrumb {
            margin-bottom: 14px;
        }

        .pms-progress-hero {
            align-items: center;
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 24px;
            box-shadow: var(--oamis-shadow);
            display: flex;
            gap: 18px;
            justify-content: space-between;
            margin-bottom: 16px;
            padding: 24px;
        }

        .pms-eyebrow {
            color: var(--oamis-primary);
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .1em;
            margin-bottom: 7px;
            text-transform: uppercase;
        }

        .pms-progress-hero h1,
        .pms-card-header h2,
        .pms-modal-section-head h6 {
            color: var(--oamis-ink);
            font-weight: 700;
            margin: 0;
        }

        .pms-progress-hero h1 {
            font-size: 28px;
            letter-spacing: -.03em;
        }

        .pms-progress-hero p,
        .pms-modal-section-head p,
        .pms-summary-card small {
            color: var(--oamis-muted);
            margin: 7px 0 0;
        }

        .pms-hero-chip,
        .pms-code-pill,
        .pms-progress-pill {
            align-items: center;
            border-radius: 999px;
            display: inline-flex;
            font-weight: 700;
            gap: 7px;
            white-space: nowrap;
        }

        .pms-hero-chip {
            background: rgba(11, 107, 74, .1);
            color: var(--oamis-primary);
            font-size: 13px;
            padding: 9px 13px;
        }

        .pms-summary-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 16px;
        }

        .pms-summary-card {
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            box-shadow: var(--oamis-shadow);
            padding: 17px 18px;
        }

        .pms-summary-card span {
            color: var(--oamis-muted);
            display: block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .pms-summary-card strong {
            color: var(--oamis-ink);
            display: block;
            font-size: 24px;
            font-weight: 700;
            margin-top: 7px;
        }

        .pms-report-card {
            background: var(--oamis-card) !important;
            border: 1px solid var(--oamis-border) !important;
            border-radius: 24px !important;
            box-shadow: var(--oamis-shadow);
            margin-top: 0;
            overflow: hidden;
            padding: 0 !important;
        }

        .pms-card-header {
            align-items: center;
            border-bottom: 1px solid var(--oamis-border);
            display: flex;
            gap: 16px;
            justify-content: space-between;
            padding: 20px 22px;
        }

        .pms-card-header h2 {
            font-size: 19px;
        }

        .pms-table-tools {
            align-items: center;
            color: var(--oamis-muted);
            display: flex;
            flex-wrap: wrap;
            font-size: 12px;
            font-weight: 700;
            gap: 10px;
        }

        .pms-table-tools span {
            background: var(--oamis-soft);
            border: 1px solid var(--oamis-border);
            border-radius: 999px;
            padding: 7px 10px;
        }

        .pms-table-wrap {
            overflow-x: auto;
            padding: 16px;
        }

        .projectTable {
            margin-bottom: 0 !important;
            width: 100% !important;
        }

        .projectTable th,
        .projectTable td {
            font-size: 14px !important;
            vertical-align: middle !important;
        }

        .pms-project-name {
            font-weight: 700;
            min-width: 260px;
        }

        .pms-code-pill {
            background: var(--oamis-soft);
            color: var(--oamis-ink);
            font-size: 12px;
            padding: 6px 9px;
        }

        .pms-progress-pill {
            background: rgba(21, 128, 61, .12);
            color: var(--oamis-green);
            font-size: 12px;
            padding: 6px 10px;
        }

        .pms-action-col {
            min-width: 138px !important;
            white-space: nowrap !important;
        }

        .viewItems {
            align-items: center;
            display: inline-flex !important;
            font-size: 13px !important;
            gap: 7px;
            justify-content: center;
            min-width: 118px;
            white-space: nowrap;
        }

        .pms-progress-modal .modal-dialog {
            max-width: min(1180px, calc(100vw - 32px));
        }

        .pms-progress-modal .modal-content {
            border-radius: 30px !important;
        }

        .pms-progress-modal .modal-header {
            align-items: flex-start;
            padding: 22px 24px;
        }

        .pms-progress-modal .modal-title {
            color: var(--oamis-ink);
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -.02em;
            margin: 0;
        }

        .pms-progress-tabs {
            background: var(--oamis-soft);
            border: 1px solid var(--oamis-border);
            border-radius: 999px;
            display: inline-flex;
            gap: 6px;
            padding: 6px;
        }

        .pms-progress-tabs .nav-link {
            align-items: center;
            border: 0 !important;
            border-radius: 999px !important;
            color: var(--oamis-muted) !important;
            display: inline-flex;
            font-size: 13px !important;
            font-weight: 700;
            gap: 8px;
            padding: 10px 14px;
        }

        .pms-progress-tabs .nav-link.active {
            background: #0b6b4a !important;
            box-shadow: 0 10px 24px rgba(11, 107, 74, .22);
            color: #fff !important;
        }

        .pms-progress-tab-content {
            margin-top: 18px;
        }

        .pms-modal-section-head {
            align-items: center;
            display: flex;
            gap: 14px;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .pms-modal-section-head h6 {
            font-size: 16px;
        }

        .pms-modal-table {
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            padding: 0;
        }

        .pms-modal-table table {
            margin: 0 !important;
        }

        .custom-accordion-btn {
            align-items: center;
            border-radius: 999px !important;
            display: inline-flex;
            font-size: 13px !important;
            font-weight: 700;
            gap: 8px;
            padding: 9px 13px;
            transition: background-color .2s ease, color .2s ease, transform .2s ease;
        }

        .custom-accordion-btn:hover {
            transform: translateY(-1px);
        }

        .pms-summary-row td {
            background: color-mix(in srgb, var(--oamis-soft) 70%, var(--oamis-card)) !important;
            color: var(--oamis-ink) !important;
            font-weight: 700;
        }

        .pms-empty-state {
            color: var(--oamis-muted) !important;
            font-weight: 700;
            padding: 26px !important;
        }

        .pms-history-panel {
            margin-top: 14px;
        }

        .pms-history-panel:empty {
            display: none;
        }

        .pms-gantt-container {
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            overflow-x: auto;
            padding: 16px;
        }

        .pms-gantt-chart {
            min-width: 900px;
        }

        .pms-gantt-axis,
        .pms-gantt-row {
            display: grid;
            grid-template-columns: minmax(210px, 28%) minmax(600px, 72%);
        }

        .pms-gantt-axis {
            border-bottom: 1px solid var(--oamis-border);
            color: var(--oamis-muted);
            font-size: 12px;
            font-weight: 700;
            padding-bottom: 10px;
        }

        .pms-gantt-axis-dates {
            display: flex;
            justify-content: space-between;
        }

        .pms-gantt-row {
            align-items: center;
            border-bottom: 1px solid var(--oamis-border);
            min-height: 68px;
        }

        .pms-gantt-row:last-child {
            border-bottom: 0;
        }

        .pms-gantt-item {
            padding: 10px 16px 10px 0;
        }

        .pms-gantt-item strong,
        .pms-gantt-item small {
            display: block;
        }

        .pms-gantt-item strong {
            color: var(--oamis-ink);
            font-size: 13px;
        }

        .pms-gantt-item small {
            color: var(--oamis-muted);
            font-size: 11px;
            margin-top: 4px;
        }

        .pms-gantt-track {
            background-image: repeating-linear-gradient(to right, transparent, transparent calc(10% - 1px), var(--oamis-border) 10%);
            height: 38px;
            position: relative;
        }

        .pms-gantt-bar {
            align-items: center;
            background: linear-gradient(90deg, #0b6b4a, #2f9e67);
            border-radius: 7px;
            color: #fff;
            display: flex;
            font-size: 11px;
            font-weight: 700;
            height: 28px;
            justify-content: center;
            min-width: 7px;
            overflow: hidden;
            padding: 0 7px;
            position: absolute;
            top: 5px;
            white-space: nowrap;
        }

        .pms-history-panel .accordion-item {
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            overflow: hidden;
        }

        .pms-history-panel .accordion-button {
            background: var(--oamis-soft) !important;
            color: var(--oamis-ink) !important;
            font-size: 13px !important;
            font-weight: 700;
            gap: 8px;
            padding: 12px 14px !important;
        }

        .pms-history-panel .accordion-body {
            background: var(--oamis-card);
            color: var(--oamis-ink);
            padding: 14px;
        }

        html[data-theme="dark"] .pms-hero-chip,
        html[data-theme="dark"] .pms-progress-pill {
            background: rgba(52, 211, 153, .13);
            color: #6ee7b7;
        }

        html[data-theme="dark"] .pms-summary-row td {
            background: #162235 !important;
            color: #f8fafc !important;
        }

        @media (max-width: 991px) {
            .pms-progress-hero,
            .pms-card-header,
            .pms-modal-section-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .pms-summary-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575px) {
            .pms-progress-hero,
            .pms-card-header,
            .pms-table-wrap {
                padding: 14px;
            }

            .pms-progress-tabs {
                border-radius: 18px;
                display: flex;
                width: 100%;
            }

            .pms-progress-tabs .nav-link {
                justify-content: center;
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        let selectedProjectCd = null;

        $(document).ready(function () {
            $('.projectTable').DataTable({
                "pageLength": 25,
                "ordering": true,
                "responsive": false,
                "columnDefs": [
                    { "targets": -1, "orderable": false, "searchable": false }
                ]
            });
        });

        $(document).on("click", ".viewItems", function () {
            let project_cd = $(this).data("project");
            let project_name = $(this).data("name");
            selectedProjectCd = project_cd;

            $("#modalTitle").text("Project Progress Details - " + project_name);

            var itemProgressUrl = "{{ url('/project-management/pms-item-wise-progress') }}";
            $.ajax({
                url: itemProgressUrl + "/" + project_cd,
                type: "GET",
                success: function (res) {
                    let html = "";

                    if (res.length === 0) {
                        html = "<tr><td colspan='4' class='text-center pms-empty-state'>No Data Found</td></tr>";
                    } else {
                        let totalPercent = 0;
                        let count = 0;

                        $.each(res, function (i, row) {
                            let percent = parseFloat(row.progress_percent) || 0;
                            totalPercent += percent;
                            count++;
                            html += `<tr>
                                <td>${row.item_name ?? ''}</td>
                                <td>${row.quantity ?? 0} ${row.iow_unit ?? ''}</td>
                                <td>${row.quantity_done ?? 0} ${row.iow_unit ?? ''}</td>
                                <td><span class="pms-progress-pill">${row.progress_percent ?? 0}%</span></td>
                            </tr>`;
                        });

                        let avgPercent = count > 0 ? totalPercent / count : 0;
                        html += `<tr class="pms-summary-row">
                            <td colspan="3" class="text-end">Overall Progress</td>
                            <td><span class="pms-progress-pill">${avgPercent.toFixed(2)}%</span></td>
                        </tr>`;
                    }

                    $("#itemTableBody").html(html);
                    $("#previousProgressContainer").html("");
                    loadFinancialProgress(project_cd);
                    loadProjectGantt(project_cd);
                    $("#itemModal").modal("show");
                }
            });
        });

        function loadFinancialProgress(project_cd) {
            var finProgessUrl = "{{ url('/project-management/get-financial-progress') }}";
            $.ajax({
                url: finProgessUrl + "/" + project_cd,
                type: "GET",
                success: function (res) {
                    let html = "";

                    if (res.length === 0) {
                        html = "<tr><td colspan='4' class='text-center pms-empty-state'>No Financial Data Found</td></tr>";
                    } else {
                        let totalProjectCost = 0;
                        let totalPayment = 0;
                        let remainingAmount = 0;

                        $.each(res, function (i, row) {
                            totalProjectCost = parseFloat(row.work_order_amount) || 0;
                            totalPayment += parseFloat(row.payment_amount) || 0;
                            remainingAmount = parseFloat(row.remaining_amount) || 0;
                            html += `<tr>
                                <td>${row.payment_date ?? ''}</td>
                                <td>${row.payment_amount ?? 0}</td>
                                <td>${row.payment_reference ?? ''}</td>
                                <td>${row.remarks ?? ''}</td>
                            </tr>`;
                        });

                        let paymentPercentage = 0;
                        if ((totalPayment + remainingAmount) > 0) {
                            paymentPercentage = (totalPayment / (totalPayment + remainingAmount)) * 100;
                        }

                        html += `
                            <tr class="pms-summary-row">
                                <td colspan="3" class="text-end">Total Work Order Amount</td>
                                <td>${totalProjectCost.toFixed(2)}</td>
                            </tr>
                            <tr class="pms-summary-row">
                                <td colspan="3" class="text-end">Total Payment</td>
                                <td>${totalPayment.toFixed(2)}</td>
                            </tr>
                            <tr class="pms-summary-row">
                                <td colspan="3" class="text-end">Remaining Amount</td>
                                <td>${remainingAmount.toFixed(2)}</td>
                            </tr>
                            <tr class="pms-summary-row">
                                <td colspan="3" class="text-end">Payment Progress</td>
                                <td><span class="pms-progress-pill">${paymentPercentage.toFixed(2)}%</span></td>
                            </tr>`;
                    }

                    $("#financialTableBody").html(html);
                }
            });
        }

        function loadProjectGantt(project_cd) {
            var ganttUrl = "{{ url('/project-management/pms-project-gantt') }}";
            $("#ganttChartContainer").html('<div class="pms-empty-state text-center">Loading project schedule...</div>');

            $.ajax({
                url: ganttUrl + "/" + project_cd,
                type: "GET",
                success: function (rows) {
                    if (!rows.length) {
                        $("#ganttChartContainer").html('<div class="pms-empty-state text-center">No scheduled Item of Work found for this project.</div>');
                        return;
                    }

                    var validRows = rows.filter(function (row) {
                        return !isNaN(new Date(row.start_date).getTime()) && !isNaN(new Date(row.end_date).getTime());
                    });
                    if (!validRows.length) {
                        $("#ganttChartContainer").html('<div class="pms-empty-state text-center">No valid schedule dates found.</div>');
                        return;
                    }

                    var minDate = new Date(Math.min.apply(null, validRows.map(function (row) { return new Date(row.start_date).getTime(); })));
                    var maxDate = new Date(Math.max.apply(null, validRows.map(function (row) { return new Date(row.end_date).getTime(); })));
                    var totalDays = Math.max(1, Math.ceil((maxDate - minDate) / 86400000) + 1);
                    var html = '<div class="pms-gantt-chart"><div class="pms-gantt-axis"><span>Item of Work / Quantity</span><div class="pms-gantt-axis-dates"><span>' + formatGanttDate(minDate) + '</span><span>' + formatGanttDate(maxDate) + '</span></div></div>';

                    validRows.forEach(function (row) {
                        var start = new Date(row.start_date);
                        var end = new Date(row.end_date);
                        var offsetDays = Math.max(0, Math.floor((start - minDate) / 86400000));
                        var durationDays = Math.max(1, Math.ceil((end - start) / 86400000) + 1);
                        var left = (offsetDays / totalDays) * 100;
                        var width = Math.max((durationDays / totalDays) * 100, 1);
                        var itemName = escapeGanttHtml(row.item_name || 'Unnamed Item of Work');
                        var quantity = escapeGanttHtml(String(row.quantity ?? 0));
                        var unit = escapeGanttHtml(row.unit || '');
                        var dateRange = formatGanttDate(start) + ' - ' + formatGanttDate(end);

                        html += '<div class="pms-gantt-row"><div class="pms-gantt-item"><strong>' + itemName + '</strong><small>Quantity: ' + quantity + ' ' + unit + '</small></div><div class="pms-gantt-track"><div class="pms-gantt-bar" style="left:' + left + '%;width:' + width + '%" title="' + dateRange + ' | Quantity: ' + quantity + ' ' + unit + '">' + durationDays + ' day' + (durationDays === 1 ? '' : 's') + '</div></div></div>';
                    });

                    $("#ganttChartContainer").html(html + '</div>');
                },
                error: function () {
                    $("#ganttChartContainer").html('<div class="pms-empty-state text-center">Unable to load the project schedule.</div>');
                }
            });
        }

        function formatGanttDate(date) {
            return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
        }

        function escapeGanttHtml(value) {
            return $('<div>').text(value).html();
        }

        function loadPreviousProgress() {
            if (!selectedProjectCd) {
                alert("Project not selected");
                return;
            }

            fetch(`/project-management/get-previous-progress/` + selectedProjectCd)
                .then(res => res.json())
                .then(data => renderPreviousProgressModal(data));
        }

        function renderPreviousProgressModal(response) {
            let data = response.data;
            let html = '';

            Object.keys(data).forEach((key) => {
                let group = data[key];
                let header = group[0];
                let badgeClass = header.status === 'A' ? 'bg-success' : 'bg-danger';

                html += `<div class="accordion mb-2" id="accordion_${key}">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading_${key}">
                            <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse_${key}">
                                <i class="fas fa-calendar-check"></i>
                                <span>${header.progress_date}</span>
                                <span class="badge ${badgeClass}">
                                    ${header.status === 'A' ? 'Approved' : 'Rejected'}
                                </span>
                            </button>
                        </h2>

                        <div id="collapse_${key}"
                            class="accordion-collapse collapse"
                            data-bs-parent="#accordion_${key}">
                            <div class="accordion-body">
                                <div class="pms-table-wrap pms-modal-table">
                                    <table class="table table-bordered table-striped text-xs">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Qty Done</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                group.forEach(row => {
                    html += `<tr>
                        <td>${row.item_name ?? ''}</td>
                        <td>${row.quantity_done ?? 0} ${row.iow_unit ?? ''}</td>
                    </tr>`;
                });

                html += `</tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            });

            document.getElementById('previousProgressContainer').innerHTML = html;
        }
    </script>
@endpush
