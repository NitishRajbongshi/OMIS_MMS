@extends('layouts.app')
@section('content')
    <main class="command-center">
        <div class="content-header">
            <div class="container-fluid" style="position: relative;">
                <div class="row text-sm">
                    <div class="col-sm-12 col-md-10">
                        <div class="command-breadcrumb">
                            <i class="fas fa-house"></i>
                            <span><a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">Home</a></span>
                            <span>/</span>
                            <span><a href="{{ route('pms.landing') }}"
                                    style="color: inherit; text-decoration: none;">PMS Dashboard</a></span>
                            <span>/</span>
                            <strong>{{ $pageTitle }}</strong>
                        </div>
                    </div>
                </div>
                {{-- alert section --}}
                <div id="alertContainer" style="text-align: right">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert"
                            style="position: absolute; top: 0; right: 0; z-index: 100;">
                            <strong> <i class="fa fa-check-circle mr-1"></i> Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close btn-xs" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-inline-block" role="alert"
                            style="position: absolute; top: 1px; right: 2px; z-index: 100;">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <section class="content px-3">
            {{-- Page Header --}}
            <div class="command-heading mb-4">
                <div>
                    <h1>
                        {{ $pageTitle }}
                    </h1>
                    <p>
                        @if ($type === 'total')
                            Review list of all published projects.
                        @else
                            Review projects past their scheduled end date.
                        @endif
                    </p>
                </div>
                <div class="command-actions">
                    <a href="{{ route('pms.landing') }}"
                        class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>

            {{-- Card to show overview / filter details --}}
            <article class="command-panel mb-4">
                <header id="flip" class="pointer-cursor">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <span>Portfolio Overview</span>
                            <h2>Selected Criteria & Summary</h2>
                        </div>
                        <div class="text-muted"><i class="fas fa-chevron-up" id="flip-icon"></i></div>
                    </div>
                </header>
                <div id="panel" class="p-3">
                    <div class="row g-3 text-sm">
                        <div class="col-sm-12 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">PROJECT CATEGORY</div>
                                <div class="fw-bold">{{ $pageTitle }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">TOTAL LISTED</div>
                                <div class="fw-bold {{ $type === 'delayed' ? 'text-danger' : 'text-success' }}">
                                    {{ $projects->total() }} Projects
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">STATUS FILTER</div>
                                <div class="fw-bold">
                                    @if ($type === 'delayed')
                                        Past End Date (&lt; 100% Progress)
                                    @else
                                        All Published Projects
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">DEPARTMENT</div>
                                <div class="fw-bold">
                                    @if (Auth::user() && Auth::user()->department && optional($projects->first())->department_name)
                                        {{ optional($projects->first())->department_name }}
                                    @else
                                        All Departments
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Data table --}}
            <article class="command-panel mb-4">
                <header>
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <span>Registry</span>
                            <h2>{{ $pageTitle }} List</h2>
                        </div>
                        <span class="badge bg-primary text-light">
                            {{ $projects->total() }} Total
                        </span>
                    </div>
                </header>
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table table-hover w-100 user_list" id="office_table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Project Code</th>
                                    <th>Department</th>
                                    <th>Contractor</th>
                                    <th class="text-center">Scheduled End Date</th>
                                    <th class="text-center">Physical Progress</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($projects as $project)
                                    @php
                                        $progress = (float) $project->physical_progress;

                                        if ($progress >= 100) {
                                            $status = 'Completed';
                                            $badgeClass = 'bg-success';
                                        } elseif (
                                            $project->project_end_date &&
                                            \Carbon\Carbon::parse($project->project_end_date)
                                                ->startOfDay()
                                                ->lt(now()->startOfDay())
                                        ) {
                                            $status = 'Delayed';
                                            $badgeClass = 'bg-danger';
                                        } elseif ($progress <= 0) {
                                            $status = 'Not Started';
                                            $badgeClass = 'bg-secondary';
                                        } else {
                                            $status = 'Ongoing';
                                            $badgeClass = 'bg-primary';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $projects->firstItem() + $loop->index }}
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $project->project_cd }}</strong>
                                        </td>
                                        <td>
                                            {{ $project->department_name ?? '—' }}
                                        </td>
                                        <td>
                                            {{ $project->contractor_name ?? '—' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($project->project_end_date)
                                                <span class="badge bg-secondary text-light">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ \Carbon\Carbon::parse($project->project_end_date)->format('d M Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center justify-content-center gap-2">
                                                <div class="progress" style="width: 70px; height: 6px; background-color: var(--oamis-border, #e2e8f0);">
                                                    <div class="progress-bar {{ $progress >= 100 ? 'bg-success' : ($status === 'Delayed' ? 'bg-danger' : 'bg-primary') }}"
                                                        role="progressbar"
                                                        style="width: {{ min(max($progress, 0), 100) }}%;"
                                                        aria-valuenow="{{ $progress }}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <span class="fw-bold">{{ number_format($progress, 2) }}%</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $badgeClass }} text-light">
                                                {{ $status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No projects found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 pt-3 border-top">
                        <small class="text-muted">
                            Showing {{ $projects->firstItem() ?? 0 }}
                            to {{ $projects->lastItem() ?? 0 }}
                            of {{ $projects->total() }} projects
                        </small>

                        <div>
                            {{ $projects->links() }}
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/selectOptionStyleSheet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
    <style>
        .detail-item {
            padding: 10px 14px;
            background: var(--oamis-soft);
            border: 1px solid var(--oamis-border);
            border-radius: 8px;
            height: 100%;
        }

        .detail-item .text-muted {
            color: var(--oamis-muted) !important;
        }

        .detail-item .fw-bold {
            color: var(--oamis-ink);
        }

        .command-center {
            color: var(--oamis-ink);
        }

        .table th,
        .table td {
            font-size: 14px !important;
        }

        .badge {
            font-size: 11px !important;
            padding: 5px 9px !important;
        }

        .pointer-cursor {
            cursor: pointer;
        }

        .pagination {
            margin-bottom: 0;
            gap: 2px;
        }

        .pagination .page-item .page-link {
            border-radius: 6px;
            color: var(--oamis-primary, #0b6b4a);
            font-size: 13px;
            padding: 5px 10px;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--oamis-primary, #0b6b4a);
            border-color: var(--oamis-primary, #0b6b4a);
            color: #fff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#flip").click(function() {
                $("#panel").slideToggle("slow", function() {
                    let isVisible = $("#panel").is(":visible");
                    if (isVisible) {
                        $("#flip-icon").removeClass("fa-chevron-down").addClass("fa-chevron-up");
                    } else {
                        $("#flip-icon").removeClass("fa-chevron-up").addClass("fa-chevron-down");
                    }
                });
            });
        });
    </script>
@endpush
