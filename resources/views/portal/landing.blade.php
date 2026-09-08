@extends('layouts.app')

@section('content')
<main class="portal-landing">
    <section class="portal-hero">
        <div class="portal-hero-copy">
            <span class="portal-eyebrow">OMIS Parent Portal</span>
            <h1>Choose Your Portal</h1>
            <p>{{ session('department') ?: 'Nagaland Public Works Department' }} &middot; {{ session('office') ?: 'Active office' }}</p>
        </div>
        <div class="portal-office">
            <span>Signed in as</span>
            <strong>{{ session('userName') ?: Auth::user()->name }}</strong>
        </div>
    </section>

    @if (session('failed'))
        <div class="alert alert-info">{{ session('failed') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="portal-grid" aria-label="Portal links">
        @foreach ($portals as $portal)
            <a class="portal-card portal-card-{{ $portal['accent'] }}" href="{{ $portal['route'] }}">
                <span class="portal-icon"><i class="{{ $portal['icon'] }}"></i></span>
                <span class="portal-card-copy">
                    <strong>{{ $portal['title'] }}</strong>
                    <small>{{ $portal['subtitle'] }}</small>
                </span>
                <span class="portal-arrow"><i class="fas fa-arrow-right"></i></span>
            </a>
        @endforeach
    </section>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal-landing.css') }}">
@endpush
