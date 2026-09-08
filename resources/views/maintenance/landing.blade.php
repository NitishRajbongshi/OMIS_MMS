@extends('layouts.app')

@section('content')
<main class="maintenance-landing">
    <section class="maintenance-hero">
        <div>
            <span class="maintenance-eyebrow">Child Portal</span>
            <h1>Maintenance Management</h1>
            <p>Plan, monitor and review maintenance works from one workspace.</p>
        </div>
        <a class="maintenance-parent-link" href="{{ route('portal.landing') }}">
            <i class="fas fa-layer-group"></i>
            <span>OMIS Parent Portal</span>
        </a>
    </section>

    <section class="maintenance-actions" aria-label="Maintenance actions">
        @foreach ($maintenanceLinks as $link)
            <a class="maintenance-card" href="{{ $link['route'] }}">
                <span class="maintenance-card-icon"><i class="{{ $link['icon'] }}"></i></span>
                <span>
                    <strong>{{ $link['title'] }}</strong>
                    <small>{{ $link['subtitle'] }}</small>
                </span>
                <i class="fas fa-arrow-right maintenance-card-arrow"></i>
            </a>
        @endforeach
    </section>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/maintenance-landing.css') }}">
@endpush
