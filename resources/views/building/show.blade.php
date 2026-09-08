@extends('layouts.app')

@section('content')
<main class="command-center">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-12 col-md-10">
                    <div class="command-breadcrumb">
                        <i class="fas fa-house"></i>
                        <span><a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">Home</a></span>
                        <span>/</span>
                        <span><a href="{{ url('project-management/manage-project?mode=create') }}" style="color: inherit; text-decoration: none;">Manage Housing</a></span>
                        <span>/</span>
                        <strong>Housing List</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content px-3">
        <x-common.alert-module />

        {{-- Page Header --}}
        <div class="command-heading mb-4">
            <div>
                <h1>
                    <i class="fa fa-building text-primary me-2"></i>
                    {{ $building->bld_qtr_name ?? $building->qtr_no }}
                </h1>
                <p>Building Code: <strong>{{ $building->building_system_cd }}</strong></p>
            </div>
            <div class="command-actions">
                <a href="{{ route('building.unit.index', $building->building_system_cd) }}"
                    class="btn btn-primary btn-sm text-light">
                    <i class="fa fa-cubes me-1"></i> Manage Units
                </a>
                <a href="{{ route('building.view.index') }}" class="btn btn-secondary btn-sm text-light">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Column 1: Left (Basic, Location, metadata) --}}
            <div class="col-md-8">
                {{-- 1. Basic Information --}}
                <article class="command-panel mb-4">
                    <header>
                        <div>
                            <span>Core Details</span>
                            <h2>Basic Information</h2>
                        </div>
                    </header>
                    <div class="p-3">
                        <div class="row g-3 text-sm">
                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Asset Name</div>
                                    <div class="fw-bold">{{ $building->asset_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Building Type</div>
                                    <div class="fw-bold">{{ $building->buildingType?->building_type_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Building Class</div>
                                    <div class="fw-bold">{{ $building->buildingClass?->building_class_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Building Category</div>
                                    <div class="fw-bold">{{ $building->buildingCategory?->building_catg_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Quarter Name</div>
                                    <div class="fw-bold">{{ $building->bld_qtr_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Quarter No</div>
                                    <div class="fw-bold">{{ $building->qtr_no ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Construction Year</div>
                                    <div class="fw-bold">{{ $building->construction_year ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Floor Type</div>
                                    <div class="fw-bold">{{ $building->floor_type_cd ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Plinth Area</div>
                                    <div class="fw-bold">{{ $building->plinth_area ? $building->plinth_area . ' sq.ft' : '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Plot Area</div>
                                    <div class="fw-bold">{{ $building->plot_area ? $building->plot_area . ' sq.ft' : '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Construction Cost</div>
                                    <div class="fw-bold text-success">
                                        {{ $building->construction_cost ? '₹ ' . number_format($building->construction_cost, 2) : '—' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Scheme</div>
                                    <div class="fw-bold">{{ $building->scheme_cd ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Partial Data?</div>
                                    <div>
                                        <span class="badge {{ $building->is_partial_data === 'Y' ? 'bg-warning text-dark' : 'bg-success text-light' }}">
                                            {{ $building->is_partial_data === 'Y' ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Maintained by NPWD?</div>
                                    <div>
                                        <span class="badge {{ $building->is_maintained_by_npwd === 'Y' ? 'bg-success text-light' : 'bg-secondary text-light' }}">
                                            {{ $building->is_maintained_by_npwd === 'Y' ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if ($building->remark)
                                <div class="col-12 mt-2">
                                    <div class="detail-item">
                                        <div class="text-muted small mb-1">Remarks</div>
                                        <div class="fw-bold text-wrap text-break" style="white-space: pre-line;">{{ $building->remark }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>

                {{-- 2. Location --}}
                <article class="command-panel mb-4">
                    <header>
                        <div>
                            <span>Spatial & Geography</span>
                            <h2>Location</h2>
                        </div>
                    </header>
                    <div class="p-3">
                        <div class="row g-3 text-sm">
                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Division</div>
                                    <div class="fw-bold">{{ $building->division?->division_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Sub-Division</div>
                                    <div class="fw-bold">{{ $building->subDivision?->sub_div_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">District</div>
                                    <div class="fw-bold">{{ $building->dist_cd ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Latitude</div>
                                    <div class="fw-bold">{{ $building->lat ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Longitude</div>
                                    <div class="fw-bold">{{ $building->lon ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Access Type</div>
                                    <div class="fw-bold">{{ $building->accessType?->access_type_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Security Fencing Type</div>
                                    <div class="fw-bold">{{ $building->securityFencingType?->fenching_type_name ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- 6. Record Metadata --}}
                <article class="command-panel mb-4">
                    <header>
                        <div>
                            <span>System Logs</span>
                            <h2>Record Information</h2>
                        </div>
                    </header>
                    <div class="p-3">
                        <div class="row g-3 text-sm">
                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Created By</div>
                                    <div class="fw-bold">{{ $building->createdBy?->name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Created At Office</div>
                                    <div class="fw-bold">{{ $building->createdAtOffice?->office_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Created At</div>
                                    <div class="fw-bold">{{ $building->created_at ? $building->created_at->format('d M Y, h:i A') : '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Last Updated At</div>
                                    <div class="fw-bold">{{ $building->updated_at ? $building->updated_at->format('d M Y, h:i A') : '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Approved By</div>
                                    <div class="fw-bold">{{ $building->approvedBy?->name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Approved At</div>
                                    <div class="fw-bold">{{ $building->approved_at ? $building->approved_at->format('d M Y, h:i A') : '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            {{-- Column 2: Right (Occupancy, Amenities, Repair) --}}
            <div class="col-md-4">
                {{-- 4. Occupancy --}}
                <article class="command-panel mb-4">
                    <header>
                        <div>
                            <span>Resident Context</span>
                            <h2>Occupancy</h2>
                        </div>
                    </header>
                    <div class="p-3">
                        <div class="row g-3 text-sm">
                            <div class="col-12">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Occupant Name</div>
                                    <div class="fw-bold">{{ $building->occupant_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Occupant Department</div>
                                    <div class="fw-bold">{{ $building->occupantDept?->dept_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Occupant Grade</div>
                                    <div class="fw-bold">{{ $building->occupant_grade_cd ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Asset Owning Department</div>
                                    <div class="fw-bold">{{ $building->owningDept?->dept_name ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Occupied From</div>
                                    <div class="fw-bold">{{ $building->occupied_from ? $building->occupied_from->format('d M Y') : '—' }}</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Occupied To</div>
                                    <div class="fw-bold">{{ $building->occupied_to ? $building->occupied_to->format('d M Y') : '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- 3. Amenities --}}
                <article class="command-panel mb-4">
                    <header>
                        <div>
                            <span>Features</span>
                            <h2>Amenities & Features</h2>
                        </div>
                    </header>
                    <div class="p-3">
                        <div class="d-flex flex-column gap-2">
                            @foreach ([
                                'has_water_supply' => ['Water Supply', 'fa-droplet'],
                                'has_electricity' => ['Electricity', 'fa-bolt'],
                                'has_sanitary' => ['Sanitary', 'fa-toilet'],
                                'has_emergency_exit' => ['Emergency Exit', 'fa-door-open'],
                                'has_staircase' => ['Staircase', 'fa-stairs'],
                                'has_lift' => ['Lift', 'fa-elevator'],
                                'has_ramp' => ['Ramp', 'fa-wheelchair'],
                                'is_pwd_friendly' => ['PWD Friendly', 'fa-universal-access'],
                                'is_fire_safety_available' => ['Fire Safety', 'fa-fire-extinguisher'],
                            ] as $field => $data)
                                <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: var(--oamis-soft); border: 1px solid var(--oamis-border);">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa {{ $data[1] }} text-muted" style="width: 20px;"></i>
                                        <span class="text-xs fw-semibold">{{ $data[0] }}</span>
                                    </div>
                                    <span class="badge {{ $building->$field === 'Y' ? 'bg-success text-light' : 'bg-danger text-light' }}">
                                        {{ $building->$field === 'Y' ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>

                {{-- 5. Last Repair --}}
                <article class="command-panel mb-4">
                    <header>
                        <div>
                            <span>Maintenance History</span>
                            <h2>Last Repair</h2>
                        </div>
                    </header>
                    <div class="p-3">
                        <div class="row g-3 text-sm">
                            <div class="col-12">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Year of Last Repair</div>
                                    <div class="fw-bold">{{ $building->year_of_last_repaired ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Last Repair Cost</div>
                                    <div class="fw-bold text-danger">
                                        {{ $building->last_repaired_cost ? '₹ ' . number_format($building->last_repaired_cost, 2) : '—' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Last Repair Scheme</div>
                                    <div class="fw-bold">{{ $building->last_repaired_scheme_cd ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
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
        .gap-2 {
            gap: 0.5rem !important;
        }
    </style>
@endpush

@push('scripts')
@endpush
