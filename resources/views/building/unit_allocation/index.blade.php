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
                            <strong>Manage Occupancies</strong>
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
                        <i class="fa fa-users-cog text-primary me-2"></i>
                        Manage Occupancies
                    </h1>
                    <p>
                        Manage housing allocation, occupancy records, and historical registries for building units.
                    </p>
                </div>
                <div class="command-actions">
                    <a href="{{ route('building.unit.index', $buildingId) }}" class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to Units
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
                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">Water Supply</div>
                                <div>
                                    <span class="badge {{ $unit->has_water_supply === 'Y' ? 'bg-success text-light' : 'bg-danger text-light' }}">
                                        {{ $unit->has_water_supply === 'Y' ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">Electricity</div>
                                <div>
                                    <span class="badge {{ $unit->has_electricity === 'Y' ? 'bg-success text-light' : 'bg-danger text-light' }}">
                                        {{ $unit->has_electricity === 'Y' ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">Sanitary</div>
                                <div>
                                    <span class="badge {{ $unit->has_sanitary === 'Y' ? 'bg-success text-light' : 'bg-danger text-light' }}">
                                        {{ $unit->has_sanitary === 'Y' ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if ($unit->remarks)
                            <div class="col-12">
                                <div class="detail-item">
                                    <div class="text-muted small mb-1">Remarks</div>
                                    <div class="fw-bold text-wrap text-break" style="white-space: pre-line;">{{ $unit->remarks }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </article>

            {{-- Form Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>New Entry</span>
                        <h2>Add Occupancy Record</h2>
                    </div>
                </header>
                <div class="p-3">
                    @include('building.unit_allocation.partials._form', [
                        'formAction' => route('building.occupancy.store', [$buildingId, $unit->unit_id]),
                        'isEdit' => false,
                        'currentOccupancy' => null,
                        'departments' => $departments,
                        'grades' => $grades,
                        'varificationStatuses' => $varificationStatuses,
                        'buildingId' => $buildingId,
                    ])
                </div>
            </article>

            {{-- List Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Registry</span>
                        <h2>Occupancy History — {{ $unit->unit_name }}</h2>
                    </div>
                </header>
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table table-hover w-100" id="building_unit_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Occupant Name</th>
                                    <th>Department</th>
                                    <th>Grade</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Current</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($occupancies as $index => $occupancy)
                                    <tr class="{{ $occupancy->is_current === 'Y' ? 'table-primary' : '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $occupancy->occupant_name }}</strong></td>
                                        <td>{{ $occupancy->department?->dept_name ?? '—' }}</td>
                                        <td>{{ $occupancy->grade?->grade_descr ?? $occupancy->occupant_grade_cd }}</td>
                                        <td>{{ $occupancy->occupied_from ? \Carbon\Carbon::parse($occupancy->occupied_from)->format('d M Y') : '—' }}</td>
                                        <td>{{ $occupancy->occupied_to ? \Carbon\Carbon::parse($occupancy->occupied_to)->format('d M Y') : '—' }}</td>
                                        <td>
                                            <span class="badge {{ $occupancy->is_current === 'Y' ? 'bg-success text-light' : 'bg-secondary text-light' }}">
                                                {{ $occupancy->is_current === 'Y' ? 'Current' : 'Past' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('building.occupancy.edit', [$buildingId, $unit->unit_id, $occupancy->occupancy_id]) }}"
                                                    class="btn btn-warning btn-xs text-dark" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('building.occupancy.destroy', [$buildingId, $unit->unit_id, $occupancy->occupancy_id]) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this occupancy record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs text-light" title="Delete">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No occupancy records found for this unit.</td>
                                    </tr>
                                @endforelse
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
        .gap-1 {
            gap: 0.25rem !important;
        }
    </style>
@endpush
@push('scripts')
@endpush

