@extends('layouts.index')

@section('content')
    <main class="oamis-login d-flex flex-column justify-content-between min-vh-100">
        <nav class="oamis-login-nav w-100">
            <a class="oamis-login-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/main_logo.png') }}" alt="Nagaland PWD logo">
                <span>
                    <strong>OMIS Nagaland PWD</strong>
                    <small>Online Asset Management & Information System</small>
                </span>
            </a>
            <div class="oamis-login-actions">
                <button type="button" class="oamis-theme-toggle" data-theme-toggle aria-pressed="false">
                    <i class="fas fa-moon"></i><span>Dark</span>
                </button>
                <a href="{{ url('/') }}" class="btn btn-light">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </div>
        </nav>

        <div class="d-flex flex-column align-items-center justify-content-center flex-grow-1 my-5 px-3">
            <div class="oamis-login-card text-center p-4 p-md-5" style="max-width: 520px; width: 100%;">
                <div class="d-flex justify-content-center mb-4">
                    <span class="oamis-login-mark"
                        style="width: 80px; height: 80px; font-size: 32px; border-radius: 20px; background: rgba(245, 158, 11, 0.12); color: var(--oamis-warning);">
                        <i class="fa-solid fa-hourglass-end"></i>
                    </span>
                </div>

                <h2 class="mb-3" style="font-weight: 800; font-size: 24px; color: var(--oamis-ink);">Session Expired!</h2>
                <p class="mb-4" style="font-size: 15px; line-height: 1.6; color: var(--oamis-muted) !important;">
                    Your session has timed out due to inactivity. To protect your work and access resources, please log in
                    again.
                </p>

                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}"
                        class="btn btn-primary oamis-login-submit d-flex align-items-center justify-content-center gap-2"
                        style="font-weight: 700; border-radius: 12px;">
                        <span>Login to Continue</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div style="height: 60px;"></div>
    </main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/oamis-login.css') }}">
    <style>
        .footer-container {
            position: absolute !important;
            bottom: 0;
            width: 100%;
            z-index: 10;
        }

        body {
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }
    </style>
@endpush
