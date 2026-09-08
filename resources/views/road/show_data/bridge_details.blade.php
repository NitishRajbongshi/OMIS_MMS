@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('manageRoad') }}">Manage Roads</a>
                        </li>
                        <li class="breadcrumb-item">Show Bridge Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <x-show-road-info />
        <x-show-road-nav-link />
        <div class="container-fluid mainBody py-3 text-sm">
            <!-- draft table content -->
            <h6 class="p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT BRIDGE DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <!-- table content -->
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="bridge_details_table">
                    <thead class="theader text-xs text-white" style="background-color:#417DBE">
                        <th class="text-center" style="min-width: 4rem;">Sl No.</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Code</th>
                        <!-- saiful # 29-04-2026 # Start -->
                        <th class="text-center" style="min-width: 8rem;">Project Code</th>
                        <!-- saiful # 29-04-2026 # End -->
                        <th class="text-center" style="min-width: 8rem;">Bridge Name</th>
                        <th class="text-center" style="min-width: 8rem;">Chainage (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Type</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Width (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">River Name</th>
                        <th class="text-center" style="min-width: 8rem;">Construction Type</th>
                        <th class="text-center" style="min-width: 8rem;">No. of Span</th>
                        <th class="text-center" style="min-width: 8rem;">Kerb width (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Kerb Height (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Load Capacity</th>
                        <th class="text-center" style="min-width: 8rem;">No. of Pears</th>
                        <th class="text-center" style="min-width: 8rem;">Super Structure Type</th>
                        <th class="text-center" style="min-width: 8rem;">Handrail Type</th>
                        <th class="text-center" style="min-width: 8rem;">Deck Type</th>
                        <th class="text-center" style="min-width: 8rem;">Expansion Joints</th>
                        <th class="text-center" style="min-width: 8rem;">Deck Level</th>
                        <th class="text-center" style="min-width: 8rem;">Carriage Width</th>
                        <th class="text-center" style="min-width: 8rem;">Guard Stone</th>
                        <th class="text-center" style="min-width: 8rem;">Discharge</th>
                        <th class="text-center" style="min-width: 8rem;">Source Depth</th>
                        <th class="text-center" style="min-width: 8rem;">Lowest Water level</th>
                        <th class="text-center" style="min-width: 8rem;">Highest Flood Level</th>
                        <th class="text-center" style="min-width: 4rem;">FRL</th>
                        <th class="text-center" style="min-width: 8rem;">Rehabilitation Year</th>
                        <th class="text-center" style="min-width: 8rem;">Construction Year</th>
                        <th class="text-center" style="min-width: 8rem;">Last Inspection</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Condition</th>
                        <th class="text-center" style="min-width: 8rem;">Expectation Date</th>
                        <th class="text-center" style="min-width: 4rem;">Footpath</th>
                        <th class="text-center" style="min-width: 8rem;">Remarks</th>
                        <th class="text-center" style="min-width: 4rem;">Wing Wall</th>
                        <th class="text-center" style="min-width: 4rem;">Head Wall</th>
                        <th class="text-center" style="min-width: 4rem;">Abutment</th>
                        <th class="text-center" style="min-width: 4rem;">Retain Wall</th>
                        <th class="text-center" style="min-width: 6rem;">Span Details</th>
                        <th class="text-center" style="min-width: 4rem;">Pier Details</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($cd_bridge_details as $item)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">
                                    {{ $item->rd_bridge_cd }}
                                </td>
                                <!-- saiful # 29-04-2026 # Start -->
                                <td class="text-center">
                                    {{ $item->project_cd }}
                                </td>
                                <!-- saiful # 29-04-2026 # End -->
                                <td class="text-center">
                                    {{ $item->bridge_name }}
                                </td>
                                <td class="text-center">
                                    {{ $item->chainage }}
                                </td>
                                <td class="text-center">
                                    {{ $item->bridge_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->bridge_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->river_name }}
                                </td>
                                <td class="text-center">
                                    {{ $item->construction_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_span }}
                                </td>
                                <td class="text-center">
                                    {{ $item->kerb_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->kerb_height }}
                                </td>
                                <td class="text-center">
                                    {{ $item->load_capacity }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_piers }}
                                </td>
                                <td class="text-center">
                                    {{ $item->st_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->hand_rail_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->deck_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->expn_joint_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->deck_level }}
                                </td>
                                <td class="text-center">
                                    {{ $item->carriage_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->guard_stone }}
                                </td>
                                <td class="text-center">
                                    {{ $item->source_depth }}
                                </td>
                                <td class="text-center">
                                    {{ $item->discharge }}
                                </td>
                                <td class="text-center">
                                    {{ $item->lowest_water_level }}
                                </td>
                                <td class="text-center">
                                    {{ $item->highest_flood_level }}
                                </td>
                                <td class="text-center">
                                    {{ $item->rfl }}
                                </td>
                                <td class="text-center">
                                    {{ $item->year_of_rehabilitation }}
                                </td>
                                <td class="text-center">
                                    {{ $item->year_of_construction }}
                                </td>
                                <td class="text-center">
                                    {{ $item->date_of_last_inspection }}
                                </td>
                                <td class="text-center">
                                    {{ $item->rd_condition_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->next_schedule_inspection_date }}
                                </td>
                                <td class="text-center">
                                    @if ($item->footh_path == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $item->bridge_remark }}
                                </td>
                                <td class="text-center">
                                    @if ($item->has_wing_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#wingWallModal{{ $item->rd_bridge_cd }}"
                                            onclick="getWingWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_head_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#headWallModal{{ $item->rd_bridge_cd }}"
                                            onclick="getHeadWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_abutment_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#abutmentWallModal{{ $item->rd_bridge_cd }}"
                                            onclick="getAbutmentWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_retain_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#retainWallModal{{ $item->rd_bridge_cd }}"
                                            onclick="getRetainWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_retain_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#spanDetailsModal{{ $item->rd_bridge_cd }}"
                                            onclick="getSpanValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_retain_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#pierDetailsModal{{ $item->rd_bridge_cd }}"
                                            onclick="getPierValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>
                            </tr>
                            <?php    $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Freeze table data --}}
            <h6 class="mt-4 p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF FINALIZED BRIDGE DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="bridge_details_table_final">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center" style="min-width: 4rem;">Sl No.</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Code</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Name</th>
                        <th class="text-center" style="min-width: 8rem;">Chainage (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Type</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Width (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">River Name</th>
                        <th class="text-center" style="min-width: 8rem;">Construction Type</th>
                        <th class="text-center" style="min-width: 8rem;">No. of Span</th>
                        <th class="text-center" style="min-width: 8rem;">Kerb width (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Kerb Height (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Load Capacity</th>
                        <th class="text-center" style="min-width: 8rem;">No. of Pears</th>
                        <th class="text-center" style="min-width: 8rem;">Super Structure Type</th>
                        <th class="text-center" style="min-width: 8rem;">Handrail Type</th>
                        <th class="text-center" style="min-width: 8rem;">Deck Type</th>
                        <th class="text-center" style="min-width: 8rem;">Expansion Joints</th>
                        <th class="text-center" style="min-width: 8rem;">Deck Level</th>
                        <th class="text-center" style="min-width: 8rem;">Carriage Width</th>
                        <th class="text-center" style="min-width: 8rem;">Guard Stone</th>
                        <th class="text-center" style="min-width: 8rem;">Discharge</th>
                        <th class="text-center" style="min-width: 8rem;">Source Depth</th>
                        <th class="text-center" style="min-width: 8rem;">Lowest Water level</th>
                        <th class="text-center" style="min-width: 8rem;">Highest Flood Level</th>
                        <th class="text-center" style="min-width: 4rem;">FRL</th>
                        <th class="text-center" style="min-width: 8rem;">Rehabilitation Year</th>
                        <th class="text-center" style="min-width: 8rem;">Construction Year</th>
                        <th class="text-center" style="min-width: 8rem;">Last Inspection</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Condition</th>
                        <th class="text-center" style="min-width: 8rem;">Expectation Date</th>
                        <th class="text-center" style="min-width: 4rem;">Footpath</th>
                        <th class="text-center" style="min-width: 8rem;">Remarks</th>
                        <th class="text-center" style="min-width: 4rem;">Wing Wall</th>
                        <th class="text-center" style="min-width: 4rem;">Head Wall</th>
                        <th class="text-center" style="min-width: 4rem;">Abutment</th>
                        <th class="text-center" style="min-width: 4rem;">Retain Wall</th>
                        <th class="text-center" style="min-width: 6rem;">Span Details</th>
                        <th class="text-center" style="min-width: 4rem;">Pier Details</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($cd_bridge_details_final as $item)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">
                                    {{ $item->rd_bridge_cd }}
                                </td>
                                <td class="text-center">
                                    {{ $item->bridge_name }}
                                </td>
                                <td class="text-center">
                                    {{ $item->chainage }}
                                </td>
                                <td class="text-center">
                                    {{ $item->bridge_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->bridge_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->river_name }}
                                </td>
                                <td class="text-center">
                                    {{ $item->construction_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_span }}
                                </td>
                                <td class="text-center">
                                    {{ $item->kerb_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->kerb_distance }}
                                </td>
                                <td class="text-center">
                                    {{ $item->load_capacity }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_piers }}
                                </td>
                                <td class="text-center">
                                    {{ $item->st_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->hand_rail_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->deck_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->expn_joint_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->deck_level }}
                                </td>
                                <td class="text-center">
                                    {{ $item->carriage_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->guard_stone }}
                                </td>
                                <td class="text-center">
                                    {{ $item->source_depth }}
                                </td>
                                <td class="text-center">
                                    {{ $item->discharge }}
                                </td>
                                <td class="text-center">
                                    {{ $item->lowest_water_level }}
                                </td>
                                <td class="text-center">
                                    {{ $item->highest_flood_level }}
                                </td>
                                <td class="text-center">
                                    {{ $item->rfl }}
                                </td>
                                <td class="text-center">
                                    {{ $item->year_of_rehabilitation }}
                                </td>
                                <td class="text-center">
                                    {{ $item->year_of_construction }}
                                </td>
                                <td class="text-center">
                                    {{ $item->date_of_last_inspection }}
                                </td>
                                <td class="text-center">
                                    {{ $item->rd_condition_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->next_schedule_inspection_date }}
                                </td>
                                <td class="text-center">
                                    @if ($item->footh_path == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $item->bridge_remark }}
                                </td>
                                <td class="text-center">
                                    @if ($item->has_wing_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#wingWallModalFinalized{{ $item->rd_bridge_cd }}"
                                            onclick="getFinalizedWingWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_head_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#headWallModalFinalized{{ $item->rd_bridge_cd }}"
                                            onclick="getFinalizedHeadWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_abutment_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#abutmentWallModalFinalized{{ $item->rd_bridge_cd }}"
                                            onclick="getFinalizedAbutmentWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_retain_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#retainWallModalFinalized{{ $item->rd_bridge_cd }}"
                                            onclick="getFinalizedRetainWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_retain_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#spanDetailsModalFinalized{{ $item->rd_bridge_cd }}"
                                            onclick="getFinalizedSpanValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_retain_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#pierDetailsModalFinalized{{ $item->rd_bridge_cd }}"
                                            onclick="getFinalizedPierValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>
                            </tr>
                            <?php    $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- The Modal for wing wall -->
    <div id="wingWallModal" class="wingWallModal">
        <!-- Modal content -->
        <div class="wingWallModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Wing Wall Details
                </p>
                <div>
                    <span class="closeWingWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="wingWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for head wall-->
    <div id="headWallModal" class="headWallModal">
        <!-- Modal content -->
        <div class="headWallModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Head wall Details
                </p>
                <div>
                    <span class="closeHeadWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="headWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for Abutment wall-->
    <div id="abutmentWallModal" class="abutmentWallModal">
        <!-- Modal content -->
        <div class="abutmentWallModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Abutment Wall Details
                </p>
                <div>
                    <span class="closeAbutmentWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="abutmentWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for Retain wall-->
    <div id="retainWallModal" class="retainWallModal">
        <!-- Modal content -->
        <div class="retainWallModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Retain Wall Details
                </p>
                <div>
                    <span class="closeRetainWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="retainWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for Span Details-->
    <div id="spanDetailsModal" class="spanDetailsModal">
        <!-- Modal content -->
        <div class="spanDetailsModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Span Details
                </p>
                <div>
                    <span class="closeSpanModal">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="spanDetailsContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for Piers Details-->
    <div id="pierDetailsModal" class="pierDetailsModal">
        <!-- Modal content -->
        <div class="pierDetailsModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    List of Pier Details
                </p>
                <div>
                    <span class="closePierModal">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="pierDetailsContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for finalized wing wall -->
    <div id="wingWallModalFinalized" class="wingWallModalFinalized">
        <!-- Modal content -->
        <div class="wingWallModalFinalizedContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Wing Wall Details
                </p>
                <div>
                    <span class="closeFinalizedWingWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="finalizedWingWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for finalized head wall-->
    <div id="headWallModalFinalized" class="headWallModalFinalized">
        <!-- Modal content -->
        <div class="headWallModalFinalizedContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Head wall Details
                </p>
                <div>
                    <span class="closeFinalizedHeadWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="finalizedHeadWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for finalized Abutment wall-->
    <div id="abutmentWallModalFinalized" class="abutmentWallModalFinalized">
        <!-- Modal content -->
        <div class="abutmentWallModalFinalizedContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Abutment Wall Details
                </p>
                <div>
                    <span class="closeFinalizedAbutmentWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="finalizedAbutmentWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for finalized Retain wall-->
    <div id="retainWallModalFinalized" class="retainWallModalFinalized">
        <!-- Modal content -->
        <div class="retainWallModalFinalizedContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Retain Wall Details
                </p>
                <div>
                    <span class="closeFinalizedRetainWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="finalizedRetainWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for finalized Span Details-->
    <div id="spanDetailsModalFinalized" class="spanDetailsModalFinalized">
        <!-- Modal content -->
        <div class="spanDetailsModalFinalizedContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Span Details
                </p>
                <div>
                    <span class="closeFinalizedSpanModal">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="finalizedSpanDetailsContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for finalized Piers Details-->
    <div id="pierDetailsModalFinalized" class="pierDetailsModalFinalized">
        <!-- Modal content -->
        <div class="pierDetailsModalFinalizedContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    List of Pier Details
                </p>
                <div>
                    <span class="closeFinalizedPierModal">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="finalizedPierDetailsContainer">
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/road/bridge/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/road/assets/showBridgeAssetModals/script.js') }}" defer></script>
    <script>
        // script for draft data table
        $(function () {
            $("#bridge_details_table").DataTable({}).buttons().container().appendTo(
                '#bridge_details_table_wrapper .col-md-11:eq(1)');
        });

        // script for draft data table
        $(function () {
            $("#bridge_details_table_final").DataTable({}).buttons().container().appendTo(
                '#bridge_details_table_final_wrapper .col-md-11:eq(1)');
        });
    </script>
@endpush