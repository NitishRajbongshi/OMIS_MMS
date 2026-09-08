<!--
*Note:- Edited by Pulak on 07-10-2025 15:00
I have created a new css class named "nav-iconn" for the sidebar icons to adjust the icon color.
-->

<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-1">
    <!-- Brand Logo -->
    <a href="{{ route('portal.landing') }}" class="brand-link">
        <span class="oamis-brand-mark"><i class="fas fa-road"></i></span>
        <span class="oamis-brand-copy">
            <strong>OMIS Nagaland</strong>
            <span>Infrastructure Command</span>
        </span>
    </a>
    @php
        $isPmsPortal = request()->is('project-management*');
        $isOmisPortal = request()->is('asset-management*');
        $isMtncPortal = request()->is('maintenance-management*');
        $isGisPortal = request()->is('asset-management/dashboard*');
    @endphp
    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                {{-- Portal Navigation --}}
                <li class="nav-item" id="menu_portal_navigation">
                    <a href="#" class="nav-link text-dark">
                        <i class="nav-iconn fas fa-layer-group mr-1"></i>
                        <p>Portal Navigation</p>
                        <i class="nav-iconn right fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview text-sm">
                        <li class="nav-item">
                            <a href="{{ route('portal.landing') }}" class="nav-link" style="color:dark">
                                <i class="nav-iconn fas fa-house-user mr-1 ml-3"></i>
                                <p>OMIS Parent Portal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link" style="color:dark">
                                <i class="nav-iconn fas fa-network-wired mr-1 ml-3"></i>
                                <p>AMIS Portal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pms.landing') }}" class="nav-link" style="color:dark">
                                <i class="nav-iconn fas fa-diagram-project mr-1 ml-3"></i>
                                <p>PMIS Portal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('maintenance.landing') }}" class="nav-link" style="color:dark">
                                <i class="nav-iconn fas fa-screwdriver-wrench mr-1 ml-3"></i>
                                <p>MMIS Portal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link" style="color:dark">
                                <i class="nav-iconn fas fa-map-location-dot mr-1 ml-3"></i>
                                <p>GIS Portal</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Organisational Structure --}}
                <li class="nav-item">
                    <a href="{{ route('org-structure') }}" class="nav-link text-dark">
                        {{-- -old code...<i class="fa-solid fa-folder-tree" aria-hidden="true"></i>
                        - --}}
                        {{-- new code start...07-10-2025 15:00 By Pulak- --}}
                        <i class="nav-iconn fa-solid fa-folder-tree" aria-hidden="true"></i>
                        {{-- -new code end...07-10-2025 15:00 By Pulak- --}}
                        <p>Organisational Structure</p>
                    </a>
                </li>
                @if ($isOmisPortal)
                    {{-- Landing Page --}}
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link text-dark">
                            <i class="nav-iconn fas fa-home mr-1"></i>
                            <p>OMIS Dashboard</p>
                            {{-- <p>{{ implode(', ', session('user_role_ids')) }}</p> --}}
                        </a>
                    </li>


                    {{-- Department Dashboard --}}
                    {{-- Housing --}}
                    @if (session('user_dept_cd') === 6)
                        <li class="nav-item">
                            <a href="{{ route('dashboard.housing') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-tachometer-alt mr-1"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                    @endif
                    {{-- R&B and NH --}}
                    {{-- Since the admin is also belongs to the R&B department, need to exclude him by role id --}}
                    @if (session('user_dept_cd') === 14 && session('userRoleId') != 1)
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-tachometer-alt mr-1"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                    @endif
                    @if (session('user_dept_cd') === 3 && session('userRoleId') != 1)
                        <li class="nav-item">
                            <a href="{{ route('dashboardNH') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-tachometer-alt mr-1"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                    @endif
                    {{-- Equipment --}}
                    @if (session('user_dept_cd') === 15)
                        <li class="nav-item">
                            <a href="{{ route('dashboard.equipment') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-tachometer-alt mr-1"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                    @endif
                @endif
                {{-- Master Menu added By pulak--}}
                @if (session('users_office_type_cd') == 'ADM')
                    <li class="nav-item" id="menu_master">
                        <a href="#" class="nav-link text-dark">
                            <i class="nav-iconn fas fa-database mr-1" aria-hidden="true"></i>
                            <p>Master</p>
                            <i class="nav-iconn right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview text-sm">
                            @if ($isPmsPortal)
                                <li class="nav-item" id="menu_projects">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Projects</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                    <ul class="nav nav-treeview text-sm ml-3">
                                        <li class="nav-item">
                                            <a href="{{ route('contractorCategory.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Contractor Category</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('contractorDetails.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Contractor Details</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('itemUnit.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Item Units</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('workplanActivity.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Workplan Activities</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('itemOfWorkMaster.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Item of Work</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('boqItem.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>BOQ Items</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('subItemOfWork.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Sub Item of Work</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('projectType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Project Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('scheme.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Schemes</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('fundingAgnecies.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Funding Agencies</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('mapping.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Scheme Funding Mapping</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('officeDetails.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Office Details</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            @if($isOmisPortal)
                                {{-- not required --}}
                                {{-- <li class="nav-item" id="menu_mstr_user">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Users</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                    <ul class="nav nav-treeview text-sm ml-3">
                                        <li class="nav-item">
                                            <a href="{{ route('user.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Users</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('role.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Roles</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li> --}}

                                <li class="nav-item" id="menu_administrative">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Administrative</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                    <ul class="nav nav-treeview text-sm ml-3">
                                        <li class="nav-item">
                                            <a href="{{ route('districts.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>District</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('zone.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Zone</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('circle.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Circle</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('division.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Division</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('subdivision.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Sub-Division</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('block.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Block</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('village.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Village</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item" id="menu_mstr_building">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Buildings</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                    <ul class="nav nav-treeview text-sm ml-3">
                                        <li class="nav-item">
                                            <a href="{{ route('buildingAccessType.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Access</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('buildingBeamType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Beam</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('buildingCategory.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Category</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('buildingClass.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Class</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('buildingColumnType.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Column</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('buildingCondition.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Condition</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('buildingFloorType.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Floor</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('buildingFoundationType.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Foundation</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('buildingAccessibility.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Accessibility</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('buildingTypes.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('buildingWallType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Building Wall Types</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item" id="menu_mstr_roads">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Roads</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                    <ul class="nav nav-treeview text-sm ml-3">
                                        <li class="nav-item">
                                            <a href="{{ route('roadCategory.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Road Category</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('roadCondition.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Road Condition</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('roadOwner.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Road Owner</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('chainageStep.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Chainage Steps</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('habitationFacilities.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Habitation Facilities</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('habitationSubFacilities.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Habitation Sub-Facilities</p>
                                            </a>
                                        </li>
                                        {{-- <li class="nav-item">
                                            <a href="{{ route('GetRoadSubAsset') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Road Sub-Assets</p>
                                            </a>
                                        </li> --}}
                                    </ul>
                                </li>
                                <li class="nav-item" id="menu_mstr_bridges">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Bridge/Structural Asset</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                    <ul class="nav nav-treeview text-sm ml-3">
                                        <li class="nav-item">
                                            <a href="{{ route('bridgeType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Bridge Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('bearingType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Bearing Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('abutmentType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Abutment Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('deckType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Deck Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('pierType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Pier Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('pileType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Pile Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('expansionJointType.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Expansion Joints</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('headWall.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Head Walls</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('streamType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Head Wall Stream Types</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item" id="menu_mstr_drainage_asset">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Drainage Asset</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                    <ul class="nav nav-treeview text-sm ml-3">
                                        <li class="nav-item">
                                            <a href="{{ route('drainageType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Drainage Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('lineDrainageType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Line Drainage Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('drainageSide.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Drainage Sides</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item" id="menu_mstr_mech_veh">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Mechanical/Vehicle Asset</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                    <ul class="nav nav-treeview text-sm ml-3">
                                        <li class="nav-item">
                                            <a href="{{ route('vehicleType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Vehicle Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('vehicleMaker.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Vehicle Makers</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('vehicleModels.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Vehicle Models</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('vehicleCondition.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Vehicle Conditions</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('equipmentType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Equipment Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('equipmentCondition.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Equipment Conditions</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('fuelType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Fuel Types</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item" id="menu_mstr_constr">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Construction/Material</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                    <ul class="nav nav-treeview text-sm ml-3">
                                        <li class="nav-item">
                                            <a href="{{ route('constructionType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Construction Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('materialType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Material Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('foundationType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Foundation Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('faceWallType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Face Wall Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('cdWorkType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>CD Work Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('retainWallType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Retain Wall Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('toeWallType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Toe Wall Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('wingWallType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Wing Wall Types</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item" id="menu_mstr_pvmnt">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Pavement and Surface</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                    <ul class="nav nav-treeview text-sm ml-3">
                                        <li class="nav-item">
                                            <a href="{{ route('pavementType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Pavement Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('pavementCondition.index') }}" class="nav-link"
                                                style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Pavement Conditions</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('topographyType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Topography Types</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('surfaceType.index') }}" class="nav-link" style="color:dark">
                                                <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                                <p>Surface Types</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item" id="menu_mstr_misc">
                                    <a href="#" class="nav-link text-dark">
                                        <i class="nav-iconn fas fa-database mr-1 ml-3" aria-hidden="true"></i>
                                        <p>Miscellaneous</p>
                                        <i class="nav-iconn right fas fa-angle-left"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                {{-- -End by Pulak --}}
                @if($isPmsPortal)
                    {{-- @if (session('users_office_type_cd') == 'ADM') --}}
                    <li class="nav-item" id="menu_pms">
                        <a href="#" class="nav-link text-dark">
                            <i class="nav-iconn fa fa-briefcase mr-1" aria-hidden="true"></i>
                            <p>PMS</p>
                            <i class="nav-iconn right fas fa-angle-left"></i>
                        </a>

                        <ul class="nav nav-treeview text-sm">
                            {{-- <li class="nav-item">
                                <a href="{{ route('manage-project') }}" class="nav-link" style="color:dark">
                                    <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                    <p>Create Project</p>

                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('manage-project') }}" class="nav-link" style="color:dark">
                                    <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                    <p>Manage Project</p>

                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('manage-project') }}" class="nav-link" style="color:dark">
                                    <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                    <p>View Projects</p>

                                </a>
                            </li> --}}
                            @if (in_array(17, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ url('project-management/manage-project?mode=create') }}" class="nav-link"
                                        style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Create & Manage</p>
                                    </a>
                                </li>
                            @endif
                            @if (session('finalised') === 1 && in_array(18, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ route('finalize.project') }}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Review & Finalise</p>
                                    </a>
                                </li>
                            @endif

                            @if (in_array(19, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ route('project.verified.list') }}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Approved Projects</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array(20, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ route('project.request.list') }}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Request for Modification</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array(21, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ route('pendingproject.request.list') }}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Modification Approval</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array(22, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ route('track') }}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Track Project Status</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array(23, session('menu')))
                                <li class="nav-item">
                                    <!-- Added by Pulak -->
                                    <a href="{{ route('pms.project.list') }}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Add Progress</p>
                                    </a>
                                    <!-- End by Pulak -->
                                </li>
                            @endif
                            @if (in_array(25, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ route('progress.verify.index') }}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Verify Progress</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array(24, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ route('project.progress.report') }}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Progress Report</p>

                                    </a>
                                </li>
                            @endif
                            @if (in_array(26, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ route('project.pms-completion-report') }}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Verify/Approve Completion Report</p>
                                    </a>
                                </li>
                            @endif
                            <!-- Modified by Pulak 19-06-26 -->
                            @if (in_array(27, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ route('pms.certificates.index')}}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Issue Completion Certificate</p>
                                    </a>
                                </li>
                            @endif
                            @if (in_array(28, session('menu')))
                                <li class="nav-item">
                                    <a href="{{ route('pms.progress.financial') }}" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Financial Progress</p>
                                    </a>
                                </li>
                            @endif
                            <!-- <li class="nav-item">
                                    <a href="#" class="nav-link" style="color:dark">
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        <p>Inspection Report</p>
                                    </a>
                                </li> -->
                        </ul>
                    </li>
                @endif
                {{-- -End by Pulak --}}
                {{-- @endif --}}


                {{-- Wrap the admin level option under one drop down list --}}
                @php
                    $menuItems = session('menu', []);
                    $validMenuItems = [1, 2, 3, 4, 5, 6];
                    $showDropdown = count(array_intersect($validMenuItems, $menuItems)) > 0;
                @endphp
                @if ($showDropdown)
                    @if($isOmisPortal)
                        <li class="nav-item" id="menu_manage_system">
                            <a href="#" class="nav-link text-dark">
                                <i class="nav-iconn fa-solid fa-gear mr-1"></i>
                                <p>Manage System</p>
                                {{-- -old code...<i class="right fas fa-angle-left"></i>
                                - --}}
                                {{-- new code start...07-10-2025 15:02 By Pulak- --}}
                                <i class="nav-iconn right fas fa-angle-left"></i>
                                {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                            </a>
                            <ul class="nav nav-treeview">
                                @if (in_array(1, session('menu')))
                                    <li class="nav-item">
                                        <a href="{{ route('GetAddDepartment') }}" class="nav-link text-dark">
                                            {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                            - --}}
                                            {{-- -new code start...07-10-2025 15:56 By Pulak- --}}
                                            <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                            {{-- -new code end...07-10-2025 15:56 By Pulak- --}}
                                            <p>Manage Department</p>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array(4, session('menu')))
                                    <li class="nav-item">
                                        <a href="{{ route('manageDesignation') }}" class="nav-link text-dark">
                                            {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                            - --}}
                                            {{-- -new code start...07-10-2025 15:56 By Pulak- --}}
                                            <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                            {{-- -new code end...07-10-2025 15:56 By Pulak- --}}
                                            <p>Manage Designations</p>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array(3, session('menu')))
                                    <li class="nav-item">
                                        <a href="{{ route('manageOffice') }}" class="nav-link text-dark">
                                            {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                            - --}}
                                            {{-- -new code start...07-10-2025 15:56 By Pulak- --}}
                                            <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                            {{-- -new code end...07-10-2025 15:56 By Pulak- --}}
                                            <p>Manage Offices</p>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array(5, session('menu')))
                                    <li class="nav-item">
                                        <a href="{{ route('managePost') }}" class="nav-link text-dark">
                                            {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                            - --}}
                                            {{-- -new code start...07-10-2025 15:56 By Pulak- --}}
                                            <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                            {{-- -new code end...07-10-2025 15:56 By Pulak- --}}
                                            <p>Manage Posts</p>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array(6, session('menu')))
                                    <li class="nav-item">
                                        <a href="{{ route('GetAddRole') }}" class="nav-link text-dark">
                                            {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                            - --}}
                                            {{-- -new code start...07-10-2025 15:56 By Pulak- --}}
                                            <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                            {{-- -new code end...07-10-2025 15:56 By Pulak- --}}
                                            <p>Manage Roles</p>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array(2, session('menu')) || in_array(2, session('user_role_ids')))
                                    <li class="nav-item">
                                        <a href="{{ route('manageUser') }}" class="nav-link text-dark">
                                            {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                            - --}}
                                            {{-- -new code start...07-10-2025 15:56 By Pulak- --}}
                                            <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                            {{-- -new code end...07-10-2025 15:56 By Pulak- --}}
                                            <p>Manage Users</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                @endif

                <!-- Saiful Temp Start-->
                @if(($isOmisPortal || $isPmsPortal) && in_array(29, session('menu')))
                    <li class="nav-item">
                        <a href="{{ route('project.list.assets') }}" class="nav-link text-dark"><i class="fas fa-road"></i>
                            <p>Asset from Projects</p>
                        </a>
                    </li>
                @endif
                <!-- Saiful Temp End-->

                @if($isOmisPortal)
                    @if (
                            session('users_office_type_cd') == 'ADM' ||
                            session('users_office_type_cd') == 'SO' ||
                            session('user_dept_cd') === 16 ||
                            session('user_dept_cd') === 18
                        )
                        <li class="nav-item" id="menu_dashboard">

                            <a href="#" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-tachometer-alt mr-1"></i>
                                <p>Dashboard</p>
                                {{-- -old code...<i class="right fas fa-angle-left"></i>
                                - --}}
                                {{-- new code start...07-10-2025 15:02 By Pulak- --}}
                                <i class="nav-iconn right fas fa-angle-left"></i>
                                {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('dashboard') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>R&B Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dashboardNH') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>NH Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dashboard.housing') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>Housing Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dashboard.equipment') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>Mechanical Dashboard</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endif
                @if($isOmisPortal)
                    @if (in_array(7, session('menu')))
                        <li class="nav-item">
                            <a href="{{ route('manageRoad') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-road"></i>
                                <p>Manage Roads</p>
                            </a>
                        </li>
                    @endif
                @endif
                @if($isOmisPortal)
                    @if (in_array(16, session('menu')))
                        <li class="nav-item">
                            <a href="{{ route('viewRoadsInMapToDelete') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-road"></i>
                                <p>Delete Road From Map</p>
                            </a>
                        </li>
                    @endif

                    @if (in_array(8, session('menu')) && session('user_dept_cd') == 6 && session('can_req_modify') == 1)
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark">
                                <i class="nav-iconn fa-circle nav-icon"></i>
                                <p>Request Building Modify</p>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($menu == 8 && session('user_dept_cd') == 14 && session('can_req_modify') == 1)
                    <li class="nav-item">
                        <a href="{{ route('GetAllRoads') }}" class="nav-link text-dark">
                            <i class="fa fa-sticky-note mr-1" aria-hidden="true"></i>
                            <p>Request Road Modify</p>
                        </a>
                    </li>
                    @endif --}}
                    {{-- @if ($menu == 8 && session('user_dept_cd') == 15 && session('can_req_modify') == 1) --}}
                    @if (in_array(8, session('menu')) && session('user_dept_cd') == 15 && session('can_req_modify') == 1)
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark">
                                <i class="nav-iconn fa-circle nav-icon"></i>
                                <p>Request Mechanicals Modify</p>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($menu == 9) --}}
                    @if (in_array(9, session('menu')))
                        <li class="nav-item">
                            <a href="{{ route('manage.housing.index') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-building mr-1" style="margin-left: 0.1rem;"></i>
                                <p>Manage Housing</p>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($menu == 10) --}}
                    @if (in_array(10, session('menu')))
                        <li class="nav-item">
                            <a href="{{ route('mechanical') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-car" style="margin-right: 0.1rem;"></i>
                                <p>Manage Machineries</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('listOfAssetToChangeStatus') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-car" style="margin-right: 0.1rem;"></i>
                                <p>Change Status</p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('handleMakerChecker') }}" class="nav-link text-dark">

                            <i class="nav-iconn fa-solid fa-diamond-turn-right"></i>
                            <p>Manage Workflow</p>
                        </a>
                    </li>
                    @if (
                            session('users_office_type_cd') == 'ADM' ||
                            session('users_office_type_cd') == 'SO' ||
                            session('users_office_type_cd') == 'DA' ||
                            in_array(2, session('user_role_ids'))
                        )
                        <li class="nav-item">
                            <a href="{{ route('viewUsers') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-user mr-1"></i>
                                <p>View Users</p>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($menu == 11) --}}
                    @if (in_array(11, session('menu')))
                        <li class="nav-item">
                            <a href="{{ route('nationalHighway') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-road"></i>
                                <p>Manage NH</p>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($menu == 12 && session('user_dept_cd') == 6 && session('can_aprv_modify_req') == 1) --}}
                    {{-- @if (in_array(12, session('menu')) && session('user_dept_cd') == 6 &&
                    session('can_aprv_modify_req') == 1)
                    <li class="nav-item">
                        <a href="{{ route('GetModifyRoad') }}" class="nav-link text-dark">
                            <i class="nav-iconn fa-circle nav-icon"></i>
                            <p>Approve Buidling Modify Request</p>
                            <p class="roadPendingCount ml-2">{{ $roadReqForUpdate }}</p>
                        </a>
                    </li>
                    @endif --}}
                    {{-- @if ($menu == 12 && session('user_dept_cd') == 14 && session('can_aprv_modify_req') == 1) --}}
                    @if (in_array(12, session('menu')) && session('user_dept_cd') == 14 && session('can_aprv_modify_req') == 1)
                        <li class="nav-item">
                            <a href="{{ route('GetModifyRoad') }}" class="nav-link text-dark">
                                <i class="nav-iconn fa-circle nav-icon"></i>
                                <p>Approve Request</p>
                                <p class="roadPendingCount ml-2">{{ $roadReqForUpdate }}</p>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($menu == 12 && session('user_dept_cd') == 15 && session('can_aprv_modify_req') == 1) --}}
                    @if (in_array(12, session('menu')) && session('user_dept_cd') == 15 && session('can_aprv_modify_req') == 1)
                        <li class="nav-item">
                            <a href="{{ route('GetModifyRoad') }}" class="nav-link text-dark">
                                <i class="nav-iconn fa-circle nav-icon"></i>
                                <p>Approve Mechanicals Modify Request</p>
                                <p class="roadPendingCount ml-2">{{ $roadReqForUpdate }}</p>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($menu == 15) --}}
                    @if (in_array(15, session('menu')))
                        <li class="nav-item">
                            <a href="{{ route('building-location.index') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-building mr-1" style="margin-left: 0.1rem;"></i>
                                <p>Add Housing Locations</p>
                            </a>
                        </li>
                    @endif
                    {{-- @endforeach --}}

                    @if (session('user_dept_cd') == 14 && session('users_office_type_cd') == 'SDO')
                        <li class="nav-item">
                            <a href="{{ route('list.distress') }}" class="nav-link text-dark">
                                <i class="nav-iconn fas fa-road"></i>
                                <p>Manage Road Distresses</p>
                            </a>
                        </li>
                    @endif
                    {{-- @if (session('userRoleId') != '1') --}}
                    <li class="nav-item" id="menu_report">
                        <a href="#" class="nav-link text-dark">
                            {{-- -old code...<i class="fa-solid fa-diamond-turn-right"></i>
                            - --}}
                            {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                            <i class="nav-iconn fa fa-sticky-note mr-1" aria-hidden="true"></i>
                            {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                            <p>Report</p>
                            <i class="nav-iconn right fas fa-angle-left"></i>
                        </a>
                        @if (
                                session('user_dept_cd') === 14 ||
                                session('user_dept_cd') === 3 ||
                                session('users_office_type_cd') == 'ADM' ||
                                session('users_office_type_cd') == 'SO' ||
                                session('users_office_type_cd') == 'ECO'
                            )
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('abstract.r&b') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>R&B Abstract</p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                        @if (
                                session('user_dept_cd') === 6 ||
                                session('users_office_type_cd') == 'ADM' ||
                                session('users_office_type_cd') == 'SO' ||
                                session('users_office_type_cd') == 'ECO'
                            )
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('abstract') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>Housing Abstract</p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                        @if (
                                session('user_dept_cd') === 14 ||
                                session('users_office_type_cd') == 'ADM' ||
                                session('users_office_type_cd') == 'SO' ||
                                session('users_office_type_cd') == 'ECO'
                            )
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('viewRoadWings') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>R&B Report</p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                        @if (
                                session('user_dept_cd') === 3 ||
                                session('users_office_type_cd') == 'ADM' ||
                                session('users_office_type_cd') == 'SO' ||
                                session('users_office_type_cd') == 'ECO'
                            )
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('viewNHWings') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>NH Report</p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                        @if (
                                session('user_dept_cd') === 6 ||
                                session('users_office_type_cd') == 'ADM' ||
                                session('users_office_type_cd') == 'SO' ||
                                session('users_office_type_cd') == 'ECO'
                            )
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('viewBuildingWings') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>Housing Report</p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                        @if (
                                session('user_dept_cd') === 15 ||
                                session('users_office_type_cd') == 'ADM' ||
                                session('users_office_type_cd') == 'SO' ||
                                session('users_office_type_cd') == 'ECO'
                            )
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('viewEquipmentWings') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>Mechanical Report</p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('userMIS') }}" class="nav-link" style="color:dark">
                                    {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                    - --}}
                                    {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                    <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                    {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                    <p>User Report</p>
                                </a>
                            </li>
                        </ul>
                        @if (
                                session('user_dept_cd') == 14 ||
                                session('user_dept_cd') == 16 ||
                                session('users_office_type_cd') == 'SO' ||
                                session('users_office_type_cd') == 'ADM' ||
                                session('users_office_type_cd') == 'ECO'
                            )
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('getDistressDetails') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>Distress Report</p>
                                    </a>
                                </li>
                            </ul>
                        @endif

                        @if (
                                session('users_office_type_cd') == 'SO' ||
                                session('users_office_type_cd') == 'ADM' ||
                                session('users_office_type_cd') == 'ECO' ||
                                session('users_office_type_cd') == 'HQ'
                            )
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('misOfficesWithoutOfficer') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>Offices without Officer</p>
                                    </a>
                                </li>
                            </ul>
                        @endif
                        {{-- <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('org-structure') }}" class="nav-link" style="color:dark">
                                    <i class="far fa-circle nav-icon text-xs"></i>
                                    <p>ORG Structure</p>
                                </a>
                            </li>
                        </ul> --}}
                    </li>
                    {{-- @endif --}}
                    @if (session('uploadFile') === 1)
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark">
                                {{-- -old code...<i class="fa fa-sticky-note me-1 aria-hidden=" true"></i>
                                - --}}
                                {{-- -new code start...07-10-2025 16:00 By Pulak- --}}
                                <i class="nav-iconn fa fa-sticky-note mr-1" aria-hidden="true"></i>
                                {{-- -new code end...07-10-2025 16:00 By Pulak- --}}
                                <p>Upload</p>
                                <i class="right fas fa-angle-left"></i>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('uploadNotification') }}" class="nav-link" style="color:dark">
                                        <i class="far fa-circle nav-icon text-xs"></i>
                                        <p>Notification/Circulars</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('uploadTender') }}" class="nav-link" style="color:dark">
                                        {{-- ---old code...<i class="far fa-circle nav-icon text-xs"></i>
                                        - --}}
                                        {{-- -new code start...07-10-2025 15:02 By Pulak- --}}
                                        <i class="nav-iconn far fa-circle nav-icon text-xs"></i>
                                        {{-- -new code end...07-10-2025 15:02 By Pulak- --}}
                                        <p>Tenders</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (
                            session('users_office_type_cd') == 'ADM' ||
                            session('users_office_type_cd') == 'DA' ||
                            in_array(2, session('user_role_ids'))
                        )
                        <li class="nav-item">
                            <a href="{{ route('unlockPage') }}" class="nav-link text-dark">
                                {{-- -old code...<i class="fa fa-unlock" aria-hidden="true"></i>
                                } --}}
                                {{-- -new code start...07-10-2025 16:02 By Pulak- --}}
                                <i class="nav-iconn fa fa-unlock" aria-hidden="true"></i>
                                {{-- -new code end...07-10-2025 16:02 By Pulak- --}}
                                <p>Unlock Data to Modify</p>
                            </a>
                        </li>
                    @endif

                    @if (session('user_dept_cd') === 14 && session('userId') === 35)
                        <li class="nav-item">
                            <a href="{{ route('loadUnlockedDataToUpdate') }}" class="nav-link" style="color:dark">
                                <i class="fa fa-unlock"></i>
                                <p>Update Unlocked Data</p>
                            </a>
                        </li>
                    @endif

                    @if (session('userId') === 1)
                        <li class="nav-item">
                            <a href="{{ route('loadShortMsgPage') }}" class="nav-link text-dark" style="color:dark;">
                                {{-- -old code...<i class="fa fa-bullhorn"></i>- --}}
                                {{-- -new code start...07-10-2025 16:05 By Pulak- --}}
                                <i class="nav-iconn fa fa-bullhorn"></i>
                                {{-- -new code end...07-10-2025 16:05 By Pulak- --}}
                                <p>Broadcast Message</p>
                            </a>
                        </li>
                    @endif

                    {{-- @if (session('userId'))
                    <li class="nav-item">
                        <a href="http://43.205.45.246:8087/getURLAssetManagementAPK/gAAAAABnwFsv-vdYlkcK63yAZxKjJEgHh0VxA3oux-noTCJlIIXqeeJ8ZvUjVFnimI-SJ-bWYTBZzKTE487PwH990KRXtCVUarUcHhUaibelaN4GLE-2JSOPQgCSEcNHNhoknbcWYp0fUyLjhtDTAuG81_YooiFynndIKOydtMaOElzqdespbSQ="
                            class="nav-link text-dark" style="color:dark">
                            <i class="fa fa-mobile-alt"></i>
                            <p>Download Mobile App</p>
                        </a>
                    </li>
                    @endif --}}
                @endif
            </ul>
        </nav>
    </div>
</aside>