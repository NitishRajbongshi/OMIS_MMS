
@extends('layouts.app')

@section('title', 'Completed Maintenance Assessments')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Completed Maintenance Assessments</h4>
            <p class="text-muted mb-0">
                View completed assessments and identify assets requiring urgent attention.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('maintenance.landing') }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-arrow-left"></i> Maintenance Portal
            </a>
        </div>
    </div>

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @php
        /*
         * Asset type codes
         * 10 = Roads
         * 0  = Culverts / CD Works
         * 1  = Bridges
         * 12 = Protection Walls
         */

        $assetTabs = [
            '10' => 'Roads',
            '0'  => 'Culverts',
            '1'  => 'Bridges',
            '12' => 'Protection Walls',
        ];

        /*
         * Group the assessments only once.
         */
        $assessmentsByType = $assessments->groupBy(function ($item) {
            return (string) $item->insp_asset_type;
        });

        $knownTypes = array_keys($assetTabs);
        $otherAssessments = collect();

        foreach ($assessmentsByType as $type => $items) {
            if (!in_array((string) $type, $knownTypes, true)) {
                $otherAssessments = $otherAssessments->concat($items);
            }
        }

        /*
         * Summary counts.
         */
        $urgentCount = 0;
        $highCount = 0;

        foreach ($assessments as $item) {
            if (!is_numeric($item->mps_score ?? null)) {
                continue;
            }

            $mps = (float) $item->mps_score;

            if ($mps >= 70) {
                $urgentCount++;
            } elseif ($mps >= 40) {
                $highCount++;
            }
        }

        /*
         * Priority badge and row colors.
         */
        $getPriority = function ($mps) {
            if (!is_numeric($mps)) {
                return [
                    'label' => 'Not scored',
                    'badge' => 'bg-secondary',
                    'row' => ''
                ];
            }

            $mps = (float) $mps;

            if ($mps >= 70) {
                return [
                    'label' => 'Urgent',
                    'badge' => 'bg-danger',
                    'row' => 'table-danger'
                ];
            }

            if ($mps >= 40) {
                return [
                    'label' => 'High',
                    'badge' => 'bg-warning text-dark',
                    'row' => 'table-warning'
                ];
            }

            if ($mps >= 20) {
                return [
                    'label' => 'Medium',
                    'badge' => 'bg-info text-dark',
                    'row' => ''
                ];
            }

            return [
                'label' => 'Low',
                'badge' => 'bg-success',
                'row' => ''
            ];
        };
    @endphp

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small">Completed Assessments</div>
                    <div class="fs-2 fw-bold text-success">
                        {{ $assessments->count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small">Urgent Priority</div>
                    <div class="fs-2 fw-bold text-danger">
                        {{ $urgentCount }}
                    </div>
                    <small class="text-muted">MPS ≥ 70</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted small">High Priority</div>
                    <div class="fs-2 fw-bold text-warning">
                        {{ $highCount }}
                    </div>
                    <small class="text-muted">MPS 40–69.99</small>
                </div>
            </div>
        </div>

    </div>

    {{-- Asset Type Tabs --}}
    <ul class="nav nav-tabs" id="assessmentTabs" role="tablist">

        @foreach($assetTabs as $type => $label)
            @php
                $tabCount = $assessmentsByType->get($type, collect())->count();
            @endphp

            <li class="nav-item" role="presentation">
                <button
                    class="nav-link {{ $loop->first ? 'active' : '' }}"
                    id="tab-{{ $type }}"
                    data-bs-toggle="tab"
                    data-bs-target="#pane-{{ $type }}"
                    type="button"
                    role="tab"
                    aria-controls="pane-{{ $type }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">

                    {{ $label }}

                    <span class="badge bg-secondary ms-1">
                        {{ $tabCount }}
                    </span>
                </button>
            </li>
        @endforeach

        <li class="nav-item" role="presentation">
            <button
                class="nav-link"
                id="tab-other"
                data-bs-toggle="tab"
                data-bs-target="#pane-other"
                type="button"
                role="tab"
                aria-controls="pane-other"
                aria-selected="false">

                Other Assets

                <span class="badge bg-secondary ms-1">
                    {{ $otherAssessments->count() }}
                </span>
            </button>
        </li>

    </ul>

    {{-- Tab Content --}}
    <div class="tab-content border border-top-0 rounded-bottom shadow-sm bg-white"
         id="assessmentTabContent">

        @foreach($assetTabs as $type => $label)

            @php
                $tabAssessments = $assessmentsByType->get($type, collect());
            @endphp

            <div
                class="tab-pane fade {{ $loop->first ? 'show active' : '' }} p-3"
                id="pane-{{ $type }}"
                role="tabpanel"
                aria-labelledby="tab-{{ $type }}">

                @if($tabAssessments->isEmpty())

                    <div class="text-center text-muted py-5">
                        <i class="fa fa-inbox fa-2x mb-2"></i>
                        <div>No completed assessments in this category.</div>
                    </div>

                @else

                    <div class="table-responsive">
                        <table id="assessmentTable{{ $type }}" class="table table-hover table-striped align-middle mb-0 assessment-data-table">

                            <thead class="table-light">
                                <tr>
                                    <th>Priority</th>
                                    <th>Asset ID</th>
                                    <th>Asset Type</th>
                                    <th>Location</th>
                                    <th>Assessment Code</th>
                                    <th>Inspection Date</th>
                                    <th class="text-center">MAS</th>
                                    <th>Assessment Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($tabAssessments as $assessment)

                                    @php
                                        // $priority = $getPriority($assessment->mps_score ?? null);
                                        $mas = is_numeric($assessment->mas_score ?? null)
                                            ? (float) $assessment->mas_score
                                            : null;

                                        if ($mas === null) {
                                            $masBadgeClass = 'bg-secondary';
                                        } elseif ($mas >= 70) {
                                            $masBadgeClass = 'bg-danger';
                                        } elseif ($mas >= 40) {
                                            $masBadgeClass = 'bg-warning text-dark';
                                        } elseif ($mas >= 20) {
                                            $masBadgeClass = 'bg-info text-dark';
                                        } else {
                                            $masBadgeClass = 'bg-success';
                                        }
                                    @endphp

                                    <tr>
                                        <td>
                                            <span class="badge {{ $masBadgeClass }}">
                                                @if($mas === null)
                                                    Not scored
                                                @elseif($mas >= 70)
                                                    Critical
                                                @elseif($mas >= 40)
                                                    High
                                                @elseif($mas >= 20)
                                                    Medium
                                                @else
                                                    Low
                                                @endif
                                            </span>
                                        </td>

                                        <td class="fw-semibold">
                                            {{ $assessment->insp_asset_id }}
                                        </td>

                                        <td>
                                            {{ $assessment->asset_type_name ?: $assessment->insp_asset_type }}
                                        </td>

                                        <td>
                                            {{ $assessment->insp_asset_location ?: '—' }}
                                        </td>

                                        <td>
                                            <span class="fw-semibold">
                                                {{ $assessment->insp_cd }}
                                            </span>

                                            @if(isset($assessment->start_section) || isset($assessment->end_section))
                                                <div class="small text-muted">
                                                    Section:
                                                    {{ $assessment->start_section ?? '—' }}
                                                    –
                                                    {{ $assessment->end_section ?? '—' }}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            {{ !empty($assessment->insp_date)
                                                ? \Illuminate\Support\Carbon::parse($assessment->insp_date)->format('d M Y')
                                                : '—' }}
                                        </td>

                                        <td class="text-center">
                                            <span class="badge rounded-pill {{ $masBadgeClass }}"
                                                style="min-width: 58px; padding: 7px 10px;">
                                                {{ $mas !== null ? number_format($mas, 2) : '—' }}
                                            </span>
                                        </td>


                                        <td>
                                            {{ !empty($assessment->calculated_at)
                                                ? \Illuminate\Support\Carbon::parse($assessment->calculated_at)->format('d M Y H:i')
                                                : '—' }}
                                        </td>

                                        <td class="text-center">
                                            <a
                                                href="{{ route('mmis.completed-maintenance-assessment.show', $assessment->assessment_id) }}"
                                                class="btn btn-sm btn-outline-primary">

                                                <i class="fa fa-eye"></i>
                                                View
                                            </a>
                                        </td>

                                    </tr>

                                @endforeach
                            </tbody>

                        </table>
                    </div>

                @endif

            </div>

        @endforeach

        {{-- Other Assets Tab --}}
        <div class="tab-pane fade p-3"
             id="pane-other"
             role="tabpanel"
             aria-labelledby="tab-other">

            @if($otherAssessments->isEmpty())

                <div class="text-center text-muted py-5">
                    <i class="fa fa-inbox fa-2x mb-2"></i>
                    <div>No completed assessments in this category.</div>
                </div>

            @else

                <div class="table-responsive">
                    <table id="assessmentTableOther" class="table table-hover table-striped align-middle mb-0 assessment-data-table">
                        <thead class="table-light">
                            <tr>
                                <th>Priority</th>
                                <th>Asset ID</th>
                                <th>Asset Type</th>
                                <th>Location</th>
                                <th>Assessment Code</th>
                                <th>Inspection Date</th>
                                <th class="text-center">MAS</th>
                                <th>Assessment Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($otherAssessments as $assessment)

                               @php
                                    $mas = is_numeric($assessment->mas_score ?? null)
                                        ? (float) $assessment->mas_score
                                        : null;

                                    if ($mas === null) {
                                        $masBadgeClass = 'bg-secondary';
                                        $masPriority = 'Not scored';
                                    } elseif ($mas >= 70) {
                                        $masBadgeClass = 'bg-danger';
                                        $masPriority = 'Critical';
                                    } elseif ($mas >= 40) {
                                        $masBadgeClass = 'bg-warning text-dark';
                                        $masPriority = 'High';
                                    } elseif ($mas >= 20) {
                                        $masBadgeClass = 'bg-info text-dark';
                                        $masPriority = 'Medium';
                                    } else {
                                        $masBadgeClass = 'bg-success';
                                        $masPriority = 'Low';
                                    }
                                @endphp

                                <tr>
                                        <td>
                                            <span class="badge {{ $masBadgeClass }}">
                                                @if($mas === null)
                                                    Not scored
                                                @elseif($mas >= 70)
                                                    Critical
                                                @elseif($mas >= 40)
                                                    High
                                                @elseif($mas >= 20)
                                                    Medium
                                                @else
                                                    Low
                                                @endif
                                            </span>
                                        </td>

                                    <td class="fw-semibold">
                                        {{ $assessment->insp_asset_id }}
                                    </td>

                                    <td>
                                        {{ $assessment->asset_type_name ?: $assessment->insp_asset_type }}
                                    </td>

                                    <td>
                                        {{ $assessment->insp_asset_location ?: '—' }}
                                    </td>

                                    <td>{{ $assessment->insp_cd }}</td>

                                    <td>
                                        {{ !empty($assessment->insp_date)
                                            ? \Illuminate\Support\Carbon::parse($assessment->insp_date)->format('d M Y')
                                            : '—' }}
                                    </td>

                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $masBadgeClass }}"
                                            style="min-width: 58px; padding: 7px 10px;">
                                            {{ $mas !== null ? number_format($mas, 2) : '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ !empty($assessment->calculated_at)
                                            ? \Illuminate\Support\Carbon::parse($assessment->calculated_at)->format('d M Y H:i')
                                            : '—' }}
                                    </td>

                                    <td class="text-center">
                                        <a
                                            href="{{ route('mmis.completed-maintenance-assessment.show', $assessment->assessment_id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>

                                </tr>

                            @endforeach
                        </tbody>

                    </table>
                </div>

            @endif

        </div>

    </div>

    {{-- Priority Legend --}}
    <div class="d-flex flex-wrap gap-2 mt-3 small">
        <span class="badge bg-danger">Urgent: MAS ≥ 70</span>
        <span class="badge bg-warning text-dark">High: MAS 40–69.99</span>
        <span class="badge bg-info text-dark">Medium: MAS 20–39.99</span>
        <span class="badge bg-success">Low: MAS &lt; 20</span>
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function () {

    // Initialize a DataTable only once per table.
    function initializeAssessmentTable(table) {
        if (!$.fn.DataTable.isDataTable(table)) {
            $(table).DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                order: [
                    [6, 'desc'] // MAS: highest priority first
                ],
                responsive: true,
                autoWidth: false,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ records",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoEmpty: "No records available",
                    zeroRecords: "No matching assessments found",
                    emptyTable: "No completed assessments available",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                },
                columnDefs: [
                    {
                        targets: [0, 8],
                        orderable: false
                    }
                ]
            });
        }
    }

    // Initialize tables in the currently visible tab.
    $('#assessmentTabContent .tab-pane.active table.assessment-data-table')
        .each(function () {
            initializeAssessmentTable(this);
        });

    // Initialize the selected tab's table when a tab becomes visible.
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (event) {
        const target = $(event.target).attr('data-bs-target');

        if (!target) {
            return;
        }

        $(target).find('table.assessment-data-table').each(function () {
            initializeAssessmentTable(this);

            // Recalculate column widths after the hidden tab becomes visible.
            $(this).DataTable().columns.adjust();
        });
    });

});
</script>
@endpush
@endsection

