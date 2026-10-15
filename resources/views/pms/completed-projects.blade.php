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
                            <strong>Completed Projects</strong>
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
                        Completed Projects
                    </h1>
                    <p>
                        Review projects completed during {{ $monthStart->format('F Y') }}.
                    </p>
                </div>
                <div class="command-actions">
                    <a href="{{ route('pms.landing') }}"
                        class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>

            {{-- Card to show completion overview / filter details --}}
            <article class="command-panel mb-4">
                <header id="flip" class="pointer-cursor">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <span>Completion Overview</span>
                            <h2>Selected Criteria & Summary</h2>
                        </div>
                        <div class="text-muted"><i class="fas fa-chevron-up" id="flip-icon"></i></div>
                    </div>
                </header>
                <div id="panel" class="p-3">
                    <div class="row g-3 text-sm">
                        <div class="col-sm-12 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">COMPLETION MONTH</div>
                                <div class="fw-bold">{{ $monthStart->format('F Y') }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">TOTAL COMPLETED</div>
                                <div class="fw-bold text-success">{{ $completedProjects->total() }} Projects</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">DEPARTMENT</div>
                                <div class="fw-bold">
                                    @if ($selectedDepartment)
                                        {{ optional($completedProjects->first())->department_name ?? 'Dept ID: ' . $selectedDepartment }}
                                    @else
                                        All Departments
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">CONTRACTOR</div>
                                <div class="fw-bold">
                                    @if ($selectedContractor)
                                        {{ optional($completedProjects->first())->contractors_name ?? $selectedContractor }}
                                    @else
                                        All Contractors
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
                            <h2>Completed Projects List</h2>
                        </div>
                        <span class="badge bg-primary text-light">
                            {{ $completedProjects->total() }} Total
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
                                    <th class="text-center">Completion Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($completedProjects as $project)
                                    <tr>
                                        <td>
                                            {{ $completedProjects->firstItem() + $loop->index }}
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $project->project_cd }}</strong>
                                        </td>
                                        <td>
                                            {{ $project->department_name ?? '—' }}
                                        </td>
                                        <td>
                                            {{ $project->contractors_name ?? '—' }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary text-light">
                                                <i class="far fa-calendar-check me-1"></i>
                                                {{ \Carbon\Carbon::parse($project->completion_date)->format('d M Y') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No completed projects found for {{ $monthStart->format('F Y') }}.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 pt-3 border-top">
                        <small class="text-muted">
                            Showing {{ $completedProjects->firstItem() ?? 0 }}
                            to {{ $completedProjects->lastItem() ?? 0 }}
                            of {{ $completedProjects->total() }} projects
                        </small>

                        <div>
                            {{ $completedProjects->links() }}
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
