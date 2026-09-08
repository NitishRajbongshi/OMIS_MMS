<?php $brdcstMsg = Cache::get('global_message'); ?>

@if ($brdcstMsg)
    <div class="alert alert-danger mb-0 rounded-0 text-center" role="alert">
        <i class="fas fa-circle-exclamation mr-1"></i>{{ $brdcstMsg }}
    </div>
@endif

<nav class="main-header navbar navbar-expand oamis-topbar">
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <label class="oamis-search mb-0" aria-label="Search OAMIS">
        <i class="fas fa-search"></i>
        <input type="search" placeholder="Search assets, roads, projects or reports">
        <span class="badge badge-light">Ctrl K</span>
    </label>

    <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item d-none d-md-block">
            <a href="{{ route('getWelcomeDashBoard') }}" class="btn btn-light">
                <i class="fas fa-users mr-1"></i>Citizen Portal
            </a>
        </li>
        <li class="nav-item ml-1">
            <button type="button" class="oamis-theme-toggle" data-theme-toggle aria-pressed="false">
                <i class="fas fa-moon"></i><span>Dark</span>
            </button>
        </li>
        <li class="nav-item ml-1">
            <a class="nav-link" href="#" aria-label="Notifications">
                <i class="far fa-bell"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" aria-label="Fullscreen">
                <i class="fas fa-expand"></i>
            </a>
        </li>
        <li class="oamis-user-copy">
            <strong>{{ session('userName') ?: 'OAMIS User' }}</strong>
            <span>{{ session('office') ?: 'Nagaland PWD' }}</span>
        </li>
        <li class="nav-item dropdown position-relative">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-label="User profile">
                <i class="far fa-user-circle fa-lg"></i>
                <span class="oamis-status-dot"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">Account & Workspace</span>
                <div class="dropdown-divider"></div>
                <a href="{{ route('getProfile') }}" class="dropdown-item"><i class="far fa-user mr-2"></i>View Profile</a>
                <a href="{{ route('editProfile') }}" class="dropdown-item"><i class="fas fa-lock mr-2"></i>Reset Password</a>
                @if (session('officeType') == 'SO' || session('officeType') == 'ADM')
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('viewBuildingWings') }}" class="dropdown-item"><i class="far fa-building mr-2"></i>Housing</a>
                    <a href="{{ route('viewEquipmentWings') }}" class="dropdown-item"><i class="fas fa-gears mr-2"></i>Mechanical</a>
                    <a href="{{ route('viewRoadWings') }}" class="dropdown-item"><i class="fas fa-road mr-2"></i>Roads & Bridges</a>
                    <a href="{{ route('viewNHWings') }}" class="dropdown-item"><i class="fas fa-route mr-2"></i>National Highway</a>
                @endif
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-block"><i class="fas fa-power-off mr-1"></i>Logout</button>
                </form>
            </div>
        </li>
    </ul>
</nav>
