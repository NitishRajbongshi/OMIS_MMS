@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left text-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('manageRoad') }}">Manage Roads</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('showAllRoadModule') }}">Show Roads</a>
                        </li>
                        <li class="breadcrumb-item">Finalize Bridge Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody py-1">
            <x-finalize-road-details />
            <x-finalize-road-nav-link />
        </div>
        <form id="finalizedFormData" class="">
            @csrf
            <input type="hidden" name="userId" id="userId" value="{{ $user->id }}">
            <input type="hidden" name="userId" id="userId" value="{{ $roadID }}">
        </form>
        <!-- draft table content -->
        <div class="container-fluid mainBody">
            <h6 class="p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT BRIDGE DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="bridge_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center" style="min-width: 4rem;">Sl No.</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Code</th>
                        <!-- Saiful # 29-04-2026 # Start -->
                        <th class="text-center" style="min-width: 8rem;">Project Code</th>
                        <!-- Saiful # 29-04-2026 # End -->
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
                        <th class="text-center" style="min-width: 6rem;">Action</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($bridgeDetails as $item)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">
                                    {{ $item->rd_bridge_cd }}
                                </td>
                                <!-- Saiful # 29-04-2026 # Start -->
                                <td class="text-center">
                                    {{ $item->project_cd }}
                                </td>
                                <!-- Saiful # 29-04-2026 # End -->
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
                                <td>
                                    <div class="d-flex justify-content-between gap-1">
                                        <div style="margin-bottom: 0.1rem;">
                                            <button class="approveBtn btn btn-outline-primary btn-xs text-xs"
                                                style="width: 4rem;" data-id="{{ $item->rd_bridge_cd }}">
                                                Approve
                                            </button>
                                        </div>
                                        <div>
                                            <button class="rejectBtn btn btn-outline-danger btn-xs text-xs" style="width: 4rem;"
                                                data-id="{{ $item->rd_bridge_cd }}">
                                                Reject
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php    $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <x-success-modal />
    <x-warning-modal />
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
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/road/finalize/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/road/bridge/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/road/assets/showBridgeAssetModals/script.js') }}" defer></script>
    <script>
        $(function () {
            $("#bridge_details_table").DataTable({}).buttons().container().appendTo(
                '#bridge_details_table_wrapper .col-md-11:eq(1)');
        });

        $(document).ready(function () {
            $('#finalizedData').on('click', () => {
                const status = confirm("Are you sure?");
                if (!status) {
                    showDashboardModal('Abort to finalized');
                    return; // Exit Function
                }
                const final = confirm("Click OK to proceed!");
                if (!final) {
                    showDashboardModal('Abort to finalized');
                    return; // Exit Function
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ route('finalize.road.bridges') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#finalizedFormData').serialize(),
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.status === 200) {
                            showSuccessModal(response.message);
                        } else {
                            showDashboardModal(response.message);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        showDashboardModal("An error occurred while finalizing the data.");
                    }
                });
            });

            $('.approveBtn').on('click', function () {
                const bridgeID = $(this).data('id');
                if (!bridgeID) return; //Check
                const final = confirm("Click OK to continue");
                if (!final) {
                    showDashboardModal("Abort to approve bridge details");
                    return; // Exit Function
                }
                $.ajax({
                    type: 'GET',
                    url: "/asset-management/accept-bridge-details/" + bridgeID,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.status === 200) {
                            showSuccessModal(response.message);
                        } else {
                            showDashboardModal(response.message);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        showDashboardModal("An error occurred while approving bridge details.");
                    }
                });
            });

            $('.rejectBtn').on('click', function () {
                const bridgeID = $(this).data('id');
                if (!bridgeID) return;
                const final = confirm("Click OK to confirm rejection");
                if (!final) {
                    showDashboardModal('Cancel the rejection');
                    return;
                }
                let reason = prompt("Please Enter Reason of Rejection:", "");
                if (!reason) {
                    showDashboardModal("Reason Of Rejection Not Entered!");
                    return;
                }
                $.ajax({
                    type: 'GET',
                    url: "/asset-management/reject-bridge-details/" + bridgeID + "/" + encodeURIComponent(reason),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.status === 200) {
                            showSuccessModal(response.message);
                        } else {
                            showDashboardModal(response.message);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        showDashboardModal("An error occurred while rejecting bridge details.");
                    }
                });
            });
        });
    </script>
@endpush