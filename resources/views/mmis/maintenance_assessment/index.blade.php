@extends('layouts.app')
@section('title', 'Maintenance Assessment')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Maintenance Assessment</h4>
            <p class="text-muted mb-0">Select an asset type to review its latest inspection-based maintenance inputs.</p>
        </div>
        <a href="{{ route('maintenance.landing') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-arrow-left"></i> Maintenance Portal
        </a>
    </div>
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold"><i class="fa fa-filter text-primary me-1"></i> Asset Type</div>
        <div class="card-body">
            <div class="row g-3">
                @forelse($assetTypes as $assetType)
                    <div class="col-12 col-md-6 col-xl-4">
                        <a href="{{ route('mmis.maintenance-assessment.assets', $assetType->code) }}" class="card h-100 text-decoration-none border assessment-type-card">
                            <div class="card-body d-flex align-items-center">
                                <span class="rounded-circle bg-primary-subtle text-primary p-3 me-3">
                                    <i class="fa fa-road"></i>
                                </span>
                                <span class="flex-grow-1 text-dark">
                                    <strong class="d-block">{{ $assetType->description }}</strong>
                                    <small class="text-muted">{{ $assetType->asset_count }} asset(s) with inspection reports</small>
                                </span>
                                <i class="fa fa-chevron-right text-muted"></i>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">No inspected asset types are available for assessment.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .assessment-type-card 
    { 
        transition: box-shadow .15s ease, transform .15s ease; 
    } 
    .assessment-type-card:hover 
    { 
        box-shadow: 0 .35rem .8rem rgba(0,0,0,.12); 
        transform: translateY(-2px); 
    }
    </style>
@endpush
