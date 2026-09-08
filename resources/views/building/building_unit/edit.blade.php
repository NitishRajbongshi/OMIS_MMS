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
                            <span><a href="{{ route('building.view.index') }}"
                                    style="color: inherit; text-decoration: none;">Housing List</a></span>
                            <span>/</span>
                            <span><a href="{{ route('manage.housing.show', $buildingId) }}"
                                    style="color: inherit; text-decoration: none;">Building Details</a></span>
                            <span>/</span>
                            <span><a href="{{ route('building.unit.index', $buildingId) }}"
                                    style="color: inherit; text-decoration: none;">Manage Building Units</a></span>
                            <span>/</span>
                            <strong>Edit Building Unit</strong>
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
                        Edit Building Unit
                    </h1>
                    <p>
                        Unit: <strong>{{ $currentUnit->unit_name }}</strong> | Building:
                        <strong>{{ $building->bld_qtr_name ?? $building->qtr_no }}</strong>
                        (Code: <strong>{{ $building->building_system_cd }}</strong>)
                    </p>
                </div>
                <div class="command-actions">
                    <a href="{{ route('building.unit.index', $buildingId) }}" class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to Units
                    </a>
                </div>
            </div>

            {{-- Form Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Modify Details</span>
                        <h2>Edit Unit Information</h2>
                    </div>
                </header>
                <div class="p-3">
                    @include('building.building_unit.partials._form', [
                        'formAction' => route('building.unit.update', [$buildingId, $currentUnit->unit_id]),
                        'isEdit' => true,
                        'currentUnit' => $currentUnit,
                        'buildingUnitTypes' => $buildingUnitTypes,
                        'varificationStatuses' => $varificationStatuses,
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
        .command-center {
            color: var(--oamis-ink);
        }
    </style>
@endpush

@push('scripts')
@endpush
