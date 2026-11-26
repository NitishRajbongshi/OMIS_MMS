@extends('layouts.app')

@section('content')
    @php
        $totalCount = $inspections->count();
        $assessedCount = $inspections
            ->filter(function ($item) {
                return $item->mas_score !== null || $item->mps_score !== null;
            })
            ->count();
        $pendingCount = max($totalCount - $assessedCount, 0);
        $uniqueAssetsCount = $inspections
            ->filter(fn($item) => !empty($item->insp_asset_id))
            ->map(fn($item) => ($item->insp_asset_type ?? '') . ':' . $item->insp_asset_id)
            ->unique()
            ->count();
        $scoredMas = $inspections->pluck('mas_score')->filter(fn($score) => $score !== null);
        $avgMas = $scoredMas->isNotEmpty() ? $scoredMas->avg() : null;
        $statusLabel = match ($status) {
            'assessed' => 'Assessed',
            'pending' => 'Pending Assessment',
            default => 'All Inspections',
        };
    @endphp

    <main class="command-center">
        <div class="content-header">
            <div class="container-fluid" style="position: relative;">
                <div class="row text-sm">
                    <div class="col-sm-12 col-md-10">
                        <div class="command-breadcrumb">
                            <i class="fas fa-house"></i>
                            <span><a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">Home</a></span>
                            <span>/</span>
                            <span><a href="{{ route('maintenance.landing') }}"
                                    style="color: inherit; text-decoration: none;">Maintenance Dashboard</a></span>
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
                        View and manage inspection records based on their assessment status.
                    </p>
                </div>
                <div class="command-actions">
                    <a href="{{ route('maintenance.landing') }}" class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>

            {{-- Card to show inspection overview / filter details --}}
            <article class="command-panel mb-4">
                <header id="flip" class="pointer-cursor">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <span>Inspection Overview</span>
                            <h2>Selected Criteria & Summary</h2>
                        </div>
                        <div class="text-muted"><i class="fas fa-chevron-up" id="flip-icon"></i></div>
                    </div>
                </header>
                <div id="panel" class="p-3">
                    <div class="row g-3 text-sm">
                        <div class="col-sm-12 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">ASSESSMENT STATUS</div>
                                <div class="fw-bold">{{ $statusLabel }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">TOTAL RECORDS</div>
                                <div
                                    class="fw-bold {{ $status === 'assessed' ? 'text-success' : ($status === 'pending' ? 'text-warning' : 'text-primary') }}">
                                    {{ number_format($totalCount) }} Inspections
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">AVERAGE MAS SCORE</div>
                                <div class="fw-bold">
                                    {{ $avgMas !== null ? number_format((float) $avgMas, 2) : '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">
                                    {{ $status === 'all' ? 'ASSESSED / PENDING' : '' }}
                                </div>
                                <div class="fw-bold">
                                    @if ($status === 'all')
                                        {{ number_format($assessedCount) }} Assessed / {{ number_format($pendingCount) }}
                                        Pending
                                        {{-- @else
                                        {{ number_format($uniqueAssetsCount) }} Assets --}}
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
                            {{ number_format($totalCount) }} Total
                        </span>
                    </div>
                </header>
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table table-hover w-100 user_list" id="inspectionStatusTable">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Inspection ID</th>
                                    <th>Inspection Code</th>
                                    <th class="text-center">Inspection Date</th>
                                    <th>Asset Type</th>
                                    <th>Asset ID</th>
                                    <th>Location</th>
                                    <th class="text-end">MAS Score</th>
                                    <th class="text-end">MPS Score</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inspections as $inspection)
                                    @php
                                        $isAssessed =
                                            $inspection->mas_score !== null || $inspection->mps_score !== null;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $inspection->id }}</td>
                                        <td>
                                            <strong class="text-primary">{{ $inspection->insp_cd ?: '—' }}</strong>
                                        </td>
                                        <td class="text-center"
                                            data-order="{{ $inspection->insp_date ? \Carbon\Carbon::parse($inspection->insp_date)->format('Y-m-d') : '' }}">
                                            @if ($inspection->insp_date)
                                                <span class="badge bg-secondary text-light">
                                                    <i class="far fa-calendar-check me-1"></i>
                                                    {{ \Carbon\Carbon::parse($inspection->insp_date)->format('d M Y') }}
                                                </span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            {{ $inspection->asset_type_name ?: $inspection->insp_asset_type ?: '—' }}
                                        </td>
                                        <td>{{ $inspection->insp_asset_id ?: '—' }}</td>
                                        <td>{{ $inspection->insp_asset_location ?: '—' }}</td>
                                        <td class="text-end">
                                            {{ $inspection->mas_score !== null ? number_format((float) $inspection->mas_score, 2) : '—' }}
                                        </td>
                                        <td class="text-end">
                                            {{ $inspection->mps_score !== null ? number_format((float) $inspection->mps_score, 2) : '—' }}
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{ $isAssessed ? 'bg-success text-light' : 'bg-warning text-dark' }}">
                                                {{ $isAssessed ? 'Assessed' : 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

            $('#inspectionStatusTable').DataTable({
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                ordering: true,
                searching: true,
                paging: true,
                info: true,
                order: [
                    [3, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }],
                language: {
                    search: "Search inspections:",
                    emptyTable: "No inspection records available",
                    zeroRecords: "No matching inspection records found"
                }
            });
        });
    </script>
@endpush
