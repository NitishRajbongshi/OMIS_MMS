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
                        <li class="breadcrumb-item">Finalize Culvert Details</li>
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

        {{-- Hidden field --}}
        <form id="finalizedFormData" class="">
            @csrf
            <input type="hidden" name="userId" id="userId" value="{{ $user->id }}">
            <input type="hidden" name="userId" id="userId" value="{{ $roadID }}">
        </form>

        <!-- table content -->
        <div class="container-fluid mainBody">
            <h6 class="p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT CULVERT DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="cd_work_details_table_draft">
                    <thead class="theader text-xs text-white" style="background-color:#417DBE">
                        <th class="text-center" style="min-width: 3rem;">Sl No.</th>
                        <th class="text-center" style="min-width: 6rem;">Culvert Code</th>
                        <th class="text-center" style="min-width: 5rem;">Culvert No.</th>
                        <!-- Saiful 23-04-2026 Start -->
                        <th class="text-center" style="min-width: 5rem;">Project CD</th>
                        <!-- Saiful 23-04-2026 End -->
                        <th class="text-center" style="min-width: 4rem;">Chainage</th>
                        <th class="text-center" style="min-width: 6rem;">Discharge</th>
                        <th class="text-center" style="min-width: 8rem;">Construction Year</th>
                        <th class="text-center" style="min-width: 8rem;">Rehabilitation Year</th>
                        <th class="text-center" style="min-width: 8rem;">Culvert Condition</th>
                        <th class="text-center" style="min-width: 8rem;">Culvert Type</th>

                        {{-- Box Culvert --}}
                        <th class="text-center" style="min-width: 6rem;">No. of Cell</th>
                        <th class="text-center" style="min-width: 8rem;">Each Cell Length(Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Each Cell Width(Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Each Cell Height(Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Side Wall Thickness(Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Top slab Thickness(Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Bottom Thickness(Mtrs)</th>

                        {{-- Slab Culvert --}}
                        <th class="text-center" style="min-width: 6rem;">Span (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Slab Width (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Total Wing Wall</th>
                        <th class="text-center" style="min-width: 8rem;">Abutment Type</th>
                        <th class="text-center" style="min-width: 10rem;">Abutment Height (Mtrs)</th>
                        <th class="text-center" style="min-width: 6rem;">Bearing Type</th>

                        {{-- Hume Culvert --}}
                        <th class="text-center" style="min-width: 6rem;">No. of Row</th>
                        <th class="text-center" style="min-width: 6rem;">Cussion(Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Pipe Diameter</th>
                        <th class="text-center" style="min-width: 8rem;">Culvert Width</th>
                        <th class="text-center" style="min-width: 8rem;">Pipe Specification</th>

                        <th class="text-center" style="min-width: 10rem;">Construction Materials</th>

                        {{-- Safety Apron --}}
                        <th class="text-center" style="min-width: 8rem;">Safety Apron?</th>
                        <th class="text-center" style="min-width: 6rem;">Safety Type</th>
                        <th class="text-center" style="min-width: 8rem;">Apron Outlet (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Slab Thickness (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Apron Width (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Apron Length (Mtrs)</th>

                        {{-- Catch Pit --}}
                        <th class="text-center" style="min-width: 6rem;">Catch Pit?</th>
                        <th class="text-center" style="min-width: 8rem;">Catch Pit Type</th>
                        <th class="text-center" style="min-width: 8rem;">Catch Pit Width (Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Catch Pit Length (Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Catch Pit Height (Mtrs)</th>
                        <th class="text-center" style="min-width: 10rem;">Catch Pit Thickness (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Catch Pit Condition</th>
                        <th class="text-center" style="min-width: 8rem;">Remarks</th>
                        <th class="text-center" style="min-width: 4rem;">Wing Wall</th>
                        <th class="text-center" style="min-width: 4rem;">Head Wall</th>
                        <th class="text-center" style="min-width: 6rem;">Action</th>
                    </thead>

                    <tbody class="text-center">
                        <?php $i = 1; ?>
                        @foreach ($CDWorksDetails as $item)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">
                                    {{ $item->rd_cdwork_cd }}
                                </td>
                                <td class="text-center">
                                    {{ $item->culvert_no }}
                                </td>
                                <!-- Saiful 23-04-2026 Start -->
                                <td class="text-center">
                                    {{ $item->project_cd }}
                                </td>
                                <!-- Saiful 23-04-2026 End -->
                                <td class="text-center">
                                    {{ $item->chainage }}
                                </td>
                                <td class="text-center">
                                    {{ $item->discharge }}
                                </td>
                                <td class="text-center">
                                    {{ $item->year_of_construction }}
                                </td>
                                <td class="text-center">
                                    {{ $item->year_of_rehabilitation }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cd_condition }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwoerk_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_cell }}
                                </td>
                                <td class="text-center">
                                    {{ $item->length_span }}
                                </td>
                                <td class="text-center">
                                    {{ $item->width_each_cell }}
                                </td>
                                <td class="text-center">
                                    {{ $item->heigth_each_cell }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_thickness_side_wall }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_thickness_top_slab }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_thickness_bottom_slab }}
                                </td>
                                <td class="text-center">
                                    {{ $item->span }}
                                </td>
                                <td class="text-center">
                                    {{ $item->slab_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_wing_wall }}
                                </td>
                                <td class="text-center">
                                    {{ $item->abutment_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->abutment_height }}
                                </td>
                                <td class="text-center">
                                    {{ $item->bearing_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_rows }}
                                </td>
                                <td class="text-center">
                                    {{ $item->height_of_earth_cushion }}
                                </td>
                                <td class="text-center">
                                    {{ $item->pipe_diameter }}
                                </td>
                                <td class="text-center">
                                    {{ $item->culvert_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->hume_pipe_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->const_material_type_descr }}
                                </td>
                                <td class="text-center">
                                    @if ($item->cdwork_has_safety_apron == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $item->apron_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_safety_apron_outlet }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_safety_apron_slab_thickness }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_safety_apron_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cdwork_safety_apron_length }}
                                </td>
                                <td class="text-center">
                                    @if ($item->catch_pit_availability == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $item->catch_pit_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->catch_pit_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->catch_pit_breadth }}
                                </td>
                                <td class="text-center">
                                    {{ $item->catch_pit_heigth }}
                                </td>
                                <td class="text-center">
                                    {{ $item->catch_pit_thickness }}
                                </td>
                                <td class="text-center">
                                    {{ $item->cp_condition }}
                                </td>
                                <td>
                                    {{ $item->cdwork_remark }}
                                </td>
                                <td class="text-center">
                                    @if ($item->cdwork_has_wing_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#wingWallModal{{ $item->rd_cdwork_cd }}"
                                            onclick="getWingWallValue('{{ $item->rd_cdwork_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->cdwork_has_head_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#headWallModal{{ $item->rd_cdwork_cd }}"
                                            onclick="getHeadWallValue('{{ $item->rd_cdwork_cd }}')">
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
                                                style="width: 4rem;" data-id="{{ $item->rd_cdwork_cd }}">
                                                Approve
                                            </button>
                                        </div>
                                        <div>
                                            <button class="rejectBtn btn btn-outline-danger btn-xs text-xs" style="width: 4rem;"
                                                data-id="{{ $item->rd_cdwork_cd }}">
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
                {{-- <div class="d-flex text-sm justify-content-end mb-2">
                    <button id="finalizedCDWorksData" class="btn btn-sm btn-success rounded-1">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                        Finalize all culvert details
                    </button>
                </div> --}}
            </div>
        </div>
    </section>
    <x-success-modal />
    <x-warning-modal />

    <!-- The Modal for wing wall -->
    <div id="wingWallModal" class="wingWallModal">
        <!-- Modal content -->
        <div class="wingWallModalContent">
            <span class="closeWingWallModal">&times;</span>
            <p class="text-md text-bold text-primary">
                <i class="fa fa-bars" aria-hidden="true"></i>
                List of wing wall value
            </p>
            <div class="row text-xs" id="wingWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for head wall-->
    <div id="headWallModal" class="headWallModal">
        <!-- Modal content -->
        <div class="headWallModalContent">
            <span class="closeHeadWall">&times;</span>
            <p class="text-md text-bold text-primary">
                <i class="fa fa-bars" aria-hidden="true"></i>
                List of head wall value
            </p>
            <div class="row text-xs" id="headWallValContainer">
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/road/finalize/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/road/common/modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/road/assets/showAssetModals/script.js') }}" defer></script>
    <script>
        // script for draft data table
        $(function () {
            $("#cd_work_details_table_draft").DataTable({}).buttons().container().appendTo(
                '#cd_work_details_table_draft_wrapper .col-md-11:eq(1)');
        });
        // script for finalizing all the culvert data 
        $(document).ready(function () {
            const location = "{{ route('finalize.road.cdworks') }}"
            $('#finalizedCDWorksData').on('click', () => {
                const status = confirm("Are you sure?");
                if (status) {
                    const final = confirm("Click OK to procced!");
                    if (final) {
                        console.log('confirmed');
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('finalize.road.cdworks') }}",
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
                            }
                        })
                    } else {
                        alert('abort to finalized');
                    }
                } else {
                    alert('Abort to finalized');
                }
            });

            $('.approveBtn').on('click', function () {
                const culvetId = $(this).data('id');
                if (culvetId) {
                    const final = confirm("Click OK to continue");
                    if (final) {
                        $.ajax({
                            type: 'GET',
                            url: "/asset-management/accept-cdworks-details/" + culvetId,
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
                            }
                        })
                    } else {
                        alert('Cancel to finalize');
                    }
                }
            });

            $('.rejectBtn').on('click', function () {
                const culvetId = $(this).data('id');
                if (culvetId) {
                    const final = confirm("Click OK to confirm rejection");
                    if (final) {
                        let reason = prompt("Please Enter Reason of Rejection: ", "");
                        if (reason != null) {
                            $.ajax({
                                type: 'GET',
                                url: "/asset-management/reject-cdworks-details/" + culvetId + "/" + reason,
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
                                }
                            })
                        } else
                            showDashboardModal("Reason Of Rejection Not Entered!");
                    } else {
                        alert('Cancel the rejection');
                    }
                }
            });

            $('#culvert_type_edit').on('change', () => {
                $('#homePipeCulvertEdit').hide();
                $('#slabCulvertEdit').hide();
                $('#boxArcCulvertEdit').hide();
                $('#ventedCausewayEdit').hide();
                let culvertType = $('#culvert_type_edit').val();
                console.log(culvertType);
                if (culvertType == 'HPC') {
                    $('#homePipeCulvertEdit').show();
                }

                if (culvertType == 'SLB') {
                    $('#slabCulvertEdit').show();
                }

                if (culvertType == 'BXC') {
                    $('#boxArcCulvertEdit').show();
                }

                if (culvertType == 'VNCW') {
                    $('#ventedCausewayEdit').show();
                }
            })
        });
    </script>
    <script>
        const safetyApronRadioEdit = document.querySelectorAll('input[name="safety_apron_edit"]');
        const apronWidthContainerEdit = document.getElementById('apron_width_container_edit');
        const apronWidthInputEdit = document.getElementById('apron_width_edit');
        safetyApronRadioEdit.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'Y') {
                    apronWidthContainerEdit.style.display = 'block';
                    apronWidthInputEdit.removeAttribute('disabled');
                } else {
                    apronWidthContainerEdit.style.display = 'none';
                    apronWidthInputEdit.setAttribute('disabled', 'disabled');
                }
            });
        });
    </script>
    <script>
        const catchPitRadioEdit = document.querySelectorAll('input[name="catch_pit_availability_edit"]');
        const catchPitFieldsEdit = document.getElementById('catch_pit_fields_edit');

        catchPitRadioEdit.forEach(radio => {
            radio.addEventListener('change', function () {
                console.log(catchPitFieldsEdit);
                if (this.value === 'Y') {
                    catchPitFieldsEdit.style.display = 'flex';
                    catchPitFieldsEdit.querySelectorAll('input, select').forEach(field => field
                        .removeAttribute(
                            'disabled'));
                } else {
                    catchPitFieldsEdit.style.display = 'none';
                    catchPitFieldsEdit.querySelectorAll('input, select').forEach(field => field
                        .setAttribute(
                            'disabled', 'disabled'));
                }
            });
        });
    </script>
    <script>
        const wingWallRadioEdit = document.querySelectorAll('input[name="wing_wall_edit"]');
        const wingWallFieldsEdit = document.getElementById('wing_wall_fields_edit');

        wingWallRadioEdit.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'Y') {
                    wingWallFieldsEdit.style.display = 'flex';
                    wingWallFieldsEdit.querySelectorAll('input, select').forEach(field => field
                        .removeAttribute(
                            'disabled'));
                } else {
                    wingWallFieldsEdit.style.display = 'none';
                    wingWallFieldsEdit.querySelectorAll('input, select').forEach(field => field
                        .setAttribute(
                            'disabled', 'disabled'));
                }
            });
        });
    </script>
@endpush