@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="container-fluid mainBody">

            {{-- Page Header --}}
            <h6 class="p-2 mt-4 border border-primary text-light bg-primary shadow-sm">
                <span class="text-uppercase text-sm fw-bold">
                    <i class="fas fa-list me-1"></i>
                    Track Project Status — Nagaland P.W.D.
                </span>
            </h6>

            {{-- Results Table --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">

                    {{-- No-results message --}}
                    @if ($projects->count() === 0)
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-2x text-light mb-2"></i>
                            <p class="text-muted mb-0 fw-semibold">No projects found.</p>
                        </div>
                    @else
                        {{-- Table wrapper --}}
                        <div id="tableWrapper">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover text-xs mb-0" id="trackTable"
                                    style="width:100%">
                                    <thead style="background-color:#417DBE;" class="text-white text-uppercase">
                                        <tr>
                                            <th class="text-center py-2" style="width:2rem;">#</th>
                                            <th class="text-center py-2" style="min-width:5rem;">Project Code</th>
                                            <th class="text-center py-2" style="min-width:8rem;">Project Name</th>
                                            <th class="text-center py-2" style="min-width:6rem;">Status</th>
                                            <th class="text-center py-2" style="min-width:8rem;">Overall Progress %</th>
                                            <th class="text-center py-2" style="min-width:4rem;">Type</th>
                                            <th class="text-center py-2" style="min-width:7rem;">Department</th>
                                            <th class="text-center py-2" style="min-width:7rem;">Division</th>
                                            <th class="text-center py-2" style="min-width:7rem;">Sub-Division</th>
                                            <th class="text-center py-2" style="min-width:5rem;">Start Date</th>
                                            <th class="text-center py-2" style="min-width:5rem;">End Date</th>
                                            <th class="text-center py-2" style="min-width:6rem;">Est. Cost (₹)</th>
                                            <th class="text-center py-2" style="min-width:6rem;">Work order Amount(₹)</th>
                                            <th class="text-center py-2" style="min-width:4rem;">Defect Liability Period
                                                (Months)</th>
                                            <th class="text-center py-2" style="min-width:6rem;">Awarded To</th>
                                            <th class="text-center py-2" style="min-width:6rem;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        @foreach ($projects as $index => $project)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="text-center fw-bold text-primary">{{ $project->project_cd }}</td>
                                                <td class="fw-semibold text-dark">{{ $project->project_name ?? '—' }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $status = $project->project_status ?? 'Unknown';
                                                        $badgeClass = match (strtolower($status)) {
                                                            'completed' => 'bg-success',
                                                            'in progress' => 'bg-primary',
                                                            'draft' => 'bg-warning text-dark',
                                                            'cancelled' => 'bg-secondary',
                                                            default => 'bg-info text-dark',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="badge {{ $badgeClass }} rounded-pill px-2 shadow-xs">{{ $status }}</span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="progress shadow-xs bg-light"
                                                        style="height: 20px; border-radius: 7px;"
                                                        title="{{ $project->progress_percent }}% Complete">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                            role="progressbar"
                                                            style="width: {{ $project->progress_percent }}%; font-size: 12px; line-height: 16px;"
                                                            aria-valuenow="{{ $project->progress_percent }}"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            {{ $project->progress_percent }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $project->project_type ?? '—' }}</td>
                                                <td class="text-center">{{ $project->department_name ?? '—' }}</td>
                                                <td class="text-center">{{ $project->division_name ?? '—' }}</td>
                                                <td class="text-center">{{ $project->sub_div_name ?? '—' }}</td>
                                                <td class="text-center">
                                                    {{ $project->project_start_date ? \Carbon\Carbon::parse($project->project_start_date)->format('d-M-Y') : '—' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $project->project_end_date ? \Carbon\Carbon::parse($project->project_end_date)->format('d-M-Y') : '—' }}
                                                </td>
                                                <td class="text-end fw-semibold">
                                                    {{ $project->est_proj_cost ? number_format($project->est_proj_cost, 2) : '—' }}
                                                </td>
                                                <td class="text-end fw-semibold">
                                                    {{ $project->work_order_amount ? number_format($project->work_order_amount, 2) : '—' }}
                                                </td>
                                                <td class="text-center">{{ $project->defect_liability_period ?? '—' }}</td>
                                                <td class="text-center">{{ $project->contractor_name ?? '—' }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-xs viewItems shadow-xs"
                                                        data-project="{{ $project->project_cd }}"
                                                        data-name="{{ $project->project_name }}">
                                                        <i class="fas fa-eye me-1"></i> Details
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>{{-- /tableWrapper --}}
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════ --}}
            {{-- Draft Projects — Pending Finalization / Rejected Section  --}}
            {{-- ══════════════════════════════════════════════════════════ --}}
            <h6 class="p-2 mt-4 border border-primary text-light bg-primary shadow-sm">
                <span class="text-uppercase text-sm fw-bold">
                    <i class="fas fa-clock me-1"></i>
                    Draft Projects — Pending Finalization &amp; Rejection Status
                </span>
            </h6>

            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    @if ($draftProjects->count() === 0)
                        <div class="text-center py-4">
                            <p class="text-muted mb-0 fw-semibold">No pending or rejected draft projects found.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-xs mb-0" id="draftTable"
                                style="width:100%">
                                <thead style="background-color:#417DBE;" class="text-white text-uppercase">
                                    <tr>
                                        <th class="text-center py-2" style="width:2rem;">#</th>
                                        <th class="text-center py-2" style="min-width:5rem;">Project Code</th>
                                        <th class="text-center py-2" style="min-width:10rem;">Project Name</th>
                                        <th class="text-center py-2" style="min-width:7rem;">Draft Status</th>
                                        <th class="text-center py-2" style="min-width:8rem;">Sent For Finalization to</th>
                                        <th class="text-center py-2" style="min-width:7rem;">Sent On</th>
                                        <th class="text-center py-2" style="min-width:8rem;">Rejection Reason</th>
                                        <th class="text-center py-2" style="min-width:7rem;">Rejected By</th>
                                        <th class="text-center py-2" style="min-width:6rem;">Rejected On</th>
                                        <th class="text-center py-2" style="min-width:4rem;">Type</th>
                                        <th class="text-center py-2" style="min-width:7rem;">Division</th>
                                        <th class="text-center py-2" style="min-width:7rem;">Sub-Division</th>
                                        <th class="text-center py-2" style="min-width:5rem;">Start Date</th>
                                        <th class="text-center py-2" style="min-width:5rem;">End Date</th>
                                        <th class="text-center py-2" style="min-width:6rem;">Est. Cost (₹)</th>
                                        <th class="text-center py-2" style="min-width:6rem;">Work Order Amt (₹)</th>
                                        <th class="text-center py-2" style="min-width:6rem;">Awarded To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($draftProjects as $index => $draft)
                                        @php
                                            $isPending = $draft->sent_for_finalize === 'Y';
                                            $isRejected =
                                                $draft->is_rejected === 'Y' && $draft->sent_for_finalize === 'N';
                                        @endphp
                                        <tr class="{{ $isRejected ? 'table-danger' : '' }}">
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center fw-bold text-primary">{{ $draft->project_cd }}</td>
                                            <td class="fw-semibold text-dark">{{ $draft->project_name ?? '—' }}</td>

                                            {{-- Draft Status Badge --}}
                                            <td class="text-center">
                                                @if ($isRejected)
                                                    <span class="badge bg-danger rounded-pill px-2 shadow-xs">
                                                        <i class="fas fa-times-circle me-1"></i>Rejected
                                                    </span>
                                                @elseif($isPending)
                                                    <span class="badge bg-warning text-dark rounded-pill px-2 shadow-xs">
                                                        <i class="fas fa-hourglass-half me-1"></i>Pending Approval
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-secondary rounded-pill px-2 shadow-xs">Draft</span>
                                                @endif
                                            </td>

                                            {{-- Sent By --}}
                                            <td class="text-center">
                                                @if ($draft->sent_for_finalize === 'Y')
                                                    <span class="fw-semibold text-primary">
                                                        <i class="fas fa-user me-1"></i>{{ $draft->sent_by_name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            {{-- Sent On --}}
                                            <td class="text-center">
                                                {{ $draft->sent_for_finalize_on ? \Carbon\Carbon::parse($draft->sent_for_finalize_on)->format('d-M-Y') : '—' }}
                                            </td>

                                            {{-- Rejection Reason --}}
                                            <td class="text-center">
                                                @if ($isRejected && $draft->reason_of_rejection)
                                                    <span class="text-danger fw-semibold" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ $draft->reason_of_rejection }}">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ \Illuminate\Support\Str::limit($draft->reason_of_rejection, 30) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            {{-- Rejected By --}}
                                            <td class="text-center">
                                                @if ($isRejected)
                                                    <span class="fw-semibold text-danger">
                                                        <i
                                                            class="fas fa-user-times me-1"></i>{{ $draft->rejected_by_name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            {{-- Rejected On --}}
                                            <td class="text-center">
                                                {{ $isRejected && $draft->date_of_rejection
                                                    ? \Carbon\Carbon::parse($draft->date_of_rejection)->format('d-M-Y')
                                                    : '—' }}
                                            </td>

                                            <td class="text-center">{{ $draft->project_type ?? '—' }}</td>
                                            <td class="text-center">{{ $draft->division_name ?? '—' }}</td>
                                            <td class="text-center">{{ $draft->sub_div_name ?? '—' }}</td>
                                            <td class="text-center">
                                                {{ $draft->project_start_date ? \Carbon\Carbon::parse($draft->project_start_date)->format('d-M-Y') : '—' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $draft->project_end_date ? \Carbon\Carbon::parse($draft->project_end_date)->format('d-M-Y') : '—' }}
                                            </td>
                                            <td class="text-end fw-semibold">
                                                {{ $draft->est_proj_cost ? number_format($draft->est_proj_cost, 2) : '—' }}
                                            </td>
                                            <td class="text-end fw-semibold">
                                                {{ $draft->work_order_amount ? number_format($draft->work_order_amount, 2) : '—' }}
                                            </td>
                                            <td class="text-center">{{ $draft->contractor_name ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>

    {{-- Modal for Project Details --}}
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title text-sm fw-bold uppercase" id="modalTitle">Project Progress Details</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped text-xs mb-0" id="modalTable">
                            <thead style="background-color:#417DBE;" class="text-white">
                                <tr>
                                    <th class="text-center py-2">Sl No.</th>
                                    <th class="text-center py-2">Item Of Work</th>
                                    {{-- <th class="text-center py-2">BOQ Item Name</th> --}}
                                    <th class="text-center py-2">Total Qty</th>
                                    <th class="text-center py-2">Qty Done</th>
                                    {{-- <th class="text-center py-2">Qty Done (BOQ)</th> --}}
                                    <th class="text-center py-2">Progress %</th>
                                </tr>
                            </thead>
                            <tbody id="itemTableBody">
                                {{-- Content added via AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer py-1 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom DataTables styling to match theme */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 15px;
            font-size: 11px;
        }

        #trackTable thead th {
            vertical-align: middle;
            font-weight: 700;
            border-bottom: 2px solid #36679b;
            font-size: 10px;
        }

        #trackTable tbody td {
            vertical-align: middle;
        }

        #draftTable thead th {
            vertical-align: middle;
            font-weight: 700;
            border-bottom: 2px solid #36679b;
            font-size: 10px;
        }

        #draftTable tbody td {
            vertical-align: middle;
        }

        /* Better Shadowing */
        .shadow-xs {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }

        .shadow-sm {
            box-shadow: 0 .25rem .5rem rgba(0, 0, 0, .05);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            const DETAILS_URL = "{{ url('/project-management/pms-item-wise-progress') }}";

            /* ---- DataTables Initialization ---- */
            let table = $('#trackTable').DataTable({
                "pageLength": 10,
                "ordering": true,
                "language": {
                    "search": "Filter Projects:",
                    "emptyTable": "No projects found"
                }
            });

            /* ---- Draft table DataTables ---- */
            $('#draftTable').DataTable({
                "pageLength": 10,
                "ordering": true,
                "language": {
                    "search": "Filter Drafts:",
                    "emptyTable": "No draft projects found"
                }
            });

            /* ---- Init Bootstrap tooltips ---- */
            $('[data-bs-toggle="tooltip"]').tooltip();

            /* ---- details modal logic ---- */
            $(document).on("click", ".viewItems", function() {
                const project_cd = $(this).data("project");
                const project_name = $(this).data("name");

                $("#modalTitle").text("Project Progress Details - " + project_name);
                $("#itemTableBody").html(
                    "<tr><td colspan='7' class='text-center py-3'><div class='spinner-border spinner-border-sm text-primary me-2'></div>Loading details...</td></tr>"
                    );
                $("#itemModal").modal("show");

                $.ajax({
                    url: DETAILS_URL + "/" + project_cd,
                    type: "GET",
                    success: function(res) {
                        let html = "";
                        if (res.length === 0) {
                            html =
                                "<tr><td colspan='7' class='text-center py-3 text-secondary'>No progress data found for this project.</td></tr>";
                        } else {
                            res.forEach((row, i) => {
                                html += `<tr>
                                <td class="text-center">${i + 1}</td>
                                <td>${row.item_name ?? ''}</td>
                                {{-- <td>${row.boq_item_name ?? ''}</td> --}}
                                <td class="text-center">${row.quantity ?? 0} ${row.iow_unit ?? ''}</td>
                                <td class="text-center">${row.quantity_done ?? 0} ${row.iow_unit ?? ''}</td>
                                                                {{-- <td class="text-center">${row.boq_quantity_done ?? 0} ${row.boq_unit ?? ''}</td> --}}
                                <td class="text-center fw-bold text-primary">${row.progress_percent ?? 0}%</td>
                            </tr>`;
                            });
                        }
                        $("#itemTableBody").html(html);
                    },
                    error: function() {
                        $("#itemTableBody").html(
                            "<tr><td colspan='7' class='text-center py-3 text-danger'>Failed to load details. Please try again.</td></tr>"
                            );
                    }
                });
            });
        });
    </script>
@endpush
