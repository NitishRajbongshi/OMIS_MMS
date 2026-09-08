@extends('layouts.index')
@section('content')
    <main class="public-list-page">
        <section class="public-list-hero">
            <div class="public-list-brand">
                <img src="{{ asset('images/main_logo.png') }}" alt="Nagaland PWD logo">
                <div>
                    <span class="public-list-eyebrow">Citizen Information</span>
                    <h1>Privacy Policy</h1>
                    <p>Nagaland PWD</p>
                </div>
            </div>
            <div class="public-list-actions">
                <button type="button" class="oamis-theme-toggle" data-theme-toggle aria-pressed="false">
                    <i class="fas fa-moon"></i><span>Dark</span>
                </button>
                <a href="{{ route('getWelcomeDashBoard') }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </section>

        <section class="public-list-card">
            <div class="public-list-title">
                <h2>Privacy Policy</h2>
                <span>NPWD</span>
            </div>
            <div class="privacy-policy-content text-justify">
                <p>Thank you for visiting our website and for taking an interest in our services and products & we trust that
                    you have enjoyed the visit. NPWD would like to safeguard the information you provide to us about yourself
                    and inform you of options available to you when using our website and related services. Please read the
                    following policy to understand how personal information you provide us will be used. As this policy may be
                    modified from time to time, please check back periodically. </p>
                <p>Generally, we will use the personal data which you have provided to us for the purposes of fulfilling your
                    application for any of our services and for understanding how we can improve our services to you. Where
                    appropriate, we may also use the information for internal research on our users demographics, interests and
                    behaviour, for providing you with the latest and relevant information. </p>
                <p>Normally, NPWD may disclose your information only in special cases when we have reason to believe that
                    disclosing this information is necessary to identify, contact or bring legal action against someone who may
                    be causing injury to or interference with (either intentionally or unintentionally) NPWD's rights or
                    property, other NPWD users, or anyone else that could be harmed by such activities. NPWD may disclose user
                    information when we believe in good faith that the law requires it. </p>
                <p>Unfortunately, no data transmissions over the internet can be guaranteed to be 100% secure. As a result,
                    while we strive to protect your personal information, NPWD cannot ensure or warrant the security of any
                    information you transmit to us via the internet, and you do so at your own risk. Once we have received your
                    transmission, we will use our best efforts to ensure its security on our systems.</p>
            </div>
        </section>
    </main>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-list.css') }}">
    <style>
        .privacy-policy-content {
            color: var(--oamis-muted);
            font-size: 15px;
            line-height: 1.75;
            max-width: 1100px;
        }

        .privacy-policy-content p:last-child {
            margin-bottom: 0;
        }
    </style>
@endpush
