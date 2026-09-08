@extends('layouts.app')

@section('content')
<main class="command-center">
    <section class="command-heading">
        <div>
            <div class="command-breadcrumb"><i class="fas fa-house"></i><span>Dashboard</span><span>/</span><strong>Executive Overview</strong></div>
            <h1>Infrastructure Command Center</h1>
            <p>Statewide operational overview for {{ session('department') ?: 'Nagaland Public Works Department' }}</p>
        </div>
        <div class="command-actions">
            <form action="{{ route('office.switch') }}" method="POST" class="office-switch">
                @csrf
                <i class="fas fa-building-shield"></i>
                <select name="office" id="office" required aria-label="Choose office">
                    <option value="">Choose office</option>
                    @foreach (session('officeList') ?? [] as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Switch</button>
            </form>
        </div>
    </section>

    @if ($secreteCode === '0')
        <div class="alert alert-warning d-flex align-items-center">
            <i class="fas fa-shield-halved mr-2"></i>
            <span><strong>Security action required.</strong> Set your secret code to protect approval activities.
                <a href="{{ URL::temporarySignedRoute('secretCode', now()->addMinutes(5), ['user' => session('userName')]) }}">Set secret code</a>
            </span>
        </div>
    @endif
    @if (session('failed'))<div class="alert alert-info">{{ session('failed') }}</div>@endif
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <section class="kpi-grid" aria-label="Asset overview">
        <a class="kpi-card" href="{{ route('manageRoad') }}">
            <span class="kpi-icon green"><i class="fas fa-road"></i></span>
            <span class="kpi-label">Road Assets</span><strong>Road Network</strong>
            <span class="kpi-meta positive"><i class="fas fa-arrow-trend-up"></i> Registry & condition</span>
            <span class="kpi-spark"><i></i><i></i><i></i><i></i><i></i><i></i></span>
        </a>
        <a class="kpi-card" href="{{ route('searchBridge') }}">
            <span class="kpi-icon blue"><i class="fas fa-bridge"></i></span>
            <span class="kpi-label">Structures</span><strong>Bridges</strong>
            <span class="kpi-meta">Inspection records</span>
            <span class="kpi-spark blue"><i></i><i></i><i></i><i></i><i></i><i></i></span>
        </a>
        <a class="kpi-card" href="{{ route('searchBuilding') }}">
            <span class="kpi-icon amber"><i class="fas fa-building"></i></span>
            <span class="kpi-label">Built Assets</span><strong>Buildings</strong>
            <span class="kpi-meta">Occupancy & condition</span>
            <span class="kpi-spark amber"><i></i><i></i><i></i><i></i><i></i><i></i></span>
        </a>
        <a class="kpi-card" href="{{ route('searchCdWorks') }}">
            <span class="kpi-icon violet"><i class="fas fa-water"></i></span>
            <span class="kpi-label">Drainage Assets</span><strong>Culverts</strong>
            <span class="kpi-meta">Cross-drainage works</span>
            <span class="kpi-spark violet"><i></i><i></i><i></i><i></i><i></i><i></i></span>
        </a>
    </section>

    <section class="command-grid">
        <article class="command-panel health-panel">
            <header><div><span>Infrastructure Health</span><h2>Asset condition overview</h2></div><a href="{{ route('searchPCI') }}">View assessment <i class="fas fa-arrow-right"></i></a></header>
            <div class="health-content">
                <div class="health-ring"><div><strong>78</strong><span>Health score</span></div></div>
                <div class="health-bars">
                    <div><span>Good condition <b>64%</b></span><i><em style="width:64%"></em></i></div>
                    <div><span>Fair condition <b>24%</b></span><i><em class="fair" style="width:24%"></em></i></div>
                    <div><span>Requires attention <b>12%</b></span><i><em class="risk" style="width:12%"></em></i></div>
                </div>
            </div>
            <footer><span><i class="fas fa-circle-check"></i> Condition records available</span><span>Open PCI module for live assessment</span></footer>
        </article>

        <article class="command-panel priority-panel">
            <header><div><span>Priority Actions</span><h2>Operational queue</h2></div><button class="panel-menu"><i class="fas fa-ellipsis"></i></button></header>
            <a href="{{ route('project.request.list') }}"><i class="fas fa-file-signature amber"></i><span><strong>Pending approvals</strong><small>Review project modification requests</small></span><i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('searchPCI') }}"><i class="fas fa-clipboard-check red"></i><span><strong>Condition assessments</strong><small>Review road condition and risk</small></span><i class="fas fa-chevron-right"></i></a>
            <a href="{{ route('pms.project.list') }}"><i class="fas fa-diagram-project green"></i><span><strong>Project monitoring</strong><small>Track physical and financial progress</small></span><i class="fas fa-chevron-right"></i></a>
        </article>

        <article class="command-panel map-panel">
            <header><div><span>State Infrastructure Map</span><h2>Nagaland asset intelligence</h2></div><a class="btn btn-primary" href="{{ route('dashboard') }}"><i class="fas fa-map-location-dot mr-1"></i>Open GIS map</a></header>
            <div class="map-stage">
                <div class="map-controls"><button><i class="fas fa-layer-group"></i> Layers</button><button><i class="fas fa-filter"></i> Filters</button><button><i class="fas fa-magnifying-glass"></i> Find asset</button></div>
                <div class="map-road r1"></div><div class="map-road r2"></div><div class="map-road r3"></div><div class="map-road r4"></div>
                <span class="map-pin p1"><i class="fas fa-road"></i></span><span class="map-pin p2 amber"><i class="fas fa-bridge"></i></span><span class="map-pin p3"><i class="fas fa-building"></i></span><span class="map-pin p4 red"><i class="fas fa-triangle-exclamation"></i></span>
                <div class="map-legend"><span><i class="good"></i>Good</span><span><i class="fair"></i>Monitor</span><span><i class="risk"></i>Priority</span></div>
            </div>
        </article>

        <article class="command-panel insights-panel">
            <header><div><span>Infrastructure Insights</span><h2>Executive signals</h2></div><span class="live-badge"><i></i> Operational</span></header>
            <div class="insight-card"><i class="fas fa-chart-line"></i><div><strong>Condition intelligence</strong><p>Use PCI reports to identify deteriorating road segments and prioritize inspections.</p></div></div>
            <div class="insight-card"><i class="fas fa-indian-rupee-sign"></i><div><strong>Budget oversight</strong><p>Compare project physical progress with financial utilization in one workflow.</p></div></div>
            <a href="{{ route('project.progress.report') }}">Open analytics reports <i class="fas fa-arrow-right"></i></a>
        </article>

        <article class="command-panel activity-panel">
            <header><div><span>Recent Activity</span><h2>Workspace timeline</h2></div><a href="{{ url('log') }}">View activity</a></header>
            <div class="activity-item"><i class="fas fa-user-check"></i><div><strong>Active workspace</strong><p>{{ session('office') ?: 'Nagaland PWD' }}</p><small>Current session</small></div></div>
            <div class="activity-item"><i class="fas fa-building-circle-check"></i><div><strong>Department context</strong><p>{{ session('department') ?: 'Public Works Department' }}</p><small>Access based on assigned permissions</small></div></div>
            <div class="activity-item"><i class="fas fa-mobile-screen"></i><div><strong>Field application</strong><p>Mobile data collection tools available</p><small>For authorized field officers</small></div></div>
        </article>
    </section>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
@endpush
