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
                            <span><a href="{{ route('building-location.index') }}"
                                    style="color: inherit; text-decoration: none;">Manage Locations</a></span>
                            <span>/</span>
                            <strong>Edit Location</strong>
                        </div>
                    </div>
                </div>
                <x-common.alert-module />
            </div>
        </div>

        <section class="content px-3">
            {{-- Page Header --}}
            <div class="command-heading mb-4">
                <div>
                    <h1>
                        <i class="fa fa-map-marker-alt text-primary me-2"></i>
                        Edit Location
                    </h1>
                    <p>
                        Update geographical building locations and administrative division mappings.
                    </p>
                </div>
                <div class="command-actions">
                    <a href="{{ route('building-location.index') }}" class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to Locations
                    </a>
                </div>
            </div>

            {{-- Form Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Modify Entry</span>
                        <h2>Location Details</h2>
                    </div>
                </header>
                <div class="p-3">
                    @include('building.location.partials._form', [
                        'formAction' => route('building-location.update', $location->location_cd),
                        'isEdit'     => true,
                        'location'   => $location,
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
    <script src="{{ asset('js/location/script.js') }}" defer></script>
@endpush