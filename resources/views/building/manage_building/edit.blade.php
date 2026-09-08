@extends('layouts.app')
@section('content')
<main class="command-center">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-12 col-md-6">
                    <div class="command-breadcrumb">
                        <i class="fas fa-house"></i>
                        <span><a href="{{ route('dashboard.housing') }}" style="color: inherit; text-decoration: none;">Dashboard</a></span>
                        <span>/</span>
                        <span><a href="{{ route('manage.housing.index') }}" style="color: inherit; text-decoration: none;">Manage Housing</a></span>
                        <span>/</span>
                        <strong>Edit Building</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="command-heading px-3">
        <div>
            <h1>Edit Building</h1>
            <p>Modify government building details under {{ session('department') ?: 'Nagaland P.W.D (Housing)' }}</p>
        </div>
    </section>

    <section class="content px-3">
        <x-building-flash-message />
        
        <article class="command-panel mb-4">
            <header>
                <div>
                    <span>Form</span>
                    <h2>Edit Housing Details</h2>
                </div>
            </header>
            <div class="p-3">
                <p class="text-sm text-secondary mb-3">
                    <span class="fa fa-info-circle text-primary"></span>
                    All fields marked with <span class="star text-danger text-md text-bold">*</span> is mandatory
                </p>

                @include('building.manage_building.partials._form', [
                    'mode' => 'edit',
                    'action' => route('manage.housing.update', $building->building_system_cd),
                    'building' => $building,
                ])
            </div>
        </article>
    </section>

    <x-building.coordinates.set-coordinate />
    <x-success-modal />
    <x-warning-modal />
</main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/selectOptionStyleSheet.css') }}">
@endpush

@push('scripts')
    {{-- Bootstrap the saved values so script.js can pre-populate the dependent dropdowns --}}
    <script>
        window.housingEditData = {
            building_class_cd: "{{ $building->building_class_cd }}",
            building_location_cd: "{{ $building->building_location_cd }}",
            building_type_cd: "{{ $building->building_type_cd }}",
        };
    </script>

    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/building/script.js') }}" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM"></script>
    <script>
        const bld_class_master = @json($buildingClasses);
        const bld_type_master = @json($buildingTypes);
    </script>
@endpush
