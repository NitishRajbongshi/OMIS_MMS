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
                            <span><a href="{{ route('building.view.index') }}" style="color: inherit; text-decoration: none;">Housing List</a></span>
                            <span>/</span>
                            <span><a href="{{ route('building.unit.index', $buildingId) }}" style="color: inherit; text-decoration: none;">Building Units</a></span>
                            <span>/</span>
                            <span><a href="{{ route('building.occupancy.index', [$buildingId, $unit->unit_id]) }}" style="color: inherit; text-decoration: none;">Manage Occupancies</a></span>
                            <span>/</span>
                            <strong>Edit Occupancy</strong>
                        </div>
                    </div>
                </div>
                <x-common.alert-module />
            </div>
        </div>

        <!-- Main content -->
        <section class="content px-3">
            {{-- Page Header --}}
            <div class="command-heading mb-4">
                <div>
                    <h1>
                        <i class="fa fa-edit text-primary me-2"></i>
                        Edit Occupancy Record
                    </h1>
                    <p>
                        Update the allocation and details for the selected occupant of this building unit.
                    </p>
                </div>
                <div class="command-actions">
                    <a href="{{ route('building.occupancy.index', [$buildingId, $unit->unit_id]) }}" class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to Occupancies
                    </a>
                </div>
            </div>

            {{-- Unit Details --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Asset Specification</span>
                        <h2>Unit Details — {{ $unit->unit_name }}</h2>
                    </div>
                </header>
                <div class="p-3">
                    <div class="row g-3 text-sm">
                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">Unit Name</div>
                                <div class="fw-bold">{{ $unit->unit_name }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">Unit Number</div>
                                <div class="fw-bold">{{ $unit->unit_no }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">Unit Type</div>
                                <div class="fw-bold">{{ $unit->unitType?->unit_type_name ?? $unit->unit_type_cd }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">Floor Number</div>
                                <div class="fw-bold">{{ $unit->floor_no }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">Plinth Area</div>
                                <div class="fw-bold">{{ $unit->plinth_area }} sq.ft</div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Form Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Update Entry</span>
                        <h2>Edit Occupancy Record</h2>
                    </div>
                </header>
                <div class="p-3">
                    @include('building.unit_allocation.partials._form', [
                        'formAction' => route('building.occupancy.update', [
                            $buildingId,
                            $unit->unit_id,
                            $currentOccupancy->occupancy_id,
                        ]),
                        'isEdit' => true,
                        'currentOccupancy' => $currentOccupancy,
                        'departments' => $departments,
                        'grades' => $grades,
                        'varificationStatuses' => $varificationStatuses,
                        'buildingId' => $buildingId,
                    ])
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
    </style>
@endpush
@push('scripts')
@endpush

