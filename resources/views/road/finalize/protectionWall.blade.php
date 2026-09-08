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
                        <li class="breadcrumb-item">Finalize Protection Wall Details</li>
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
                    LIST OF DRAFT PROTECTION WALL DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="protection_wall_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">Protection-Wall Code</th>
                        <!--Saiful -- 05-05-2026 -- Start-->
                        <th class="text-center">Project CD</th>
                        <!--Saiful -- 05-05-2026 -- End-->
                        <th class="text-center">Chainage (Kms)</th>
                        <th class="text-center">Protection-Wall Type</th>
                        <th class="text-center">WallStructure Type</th>
                        <th class="text-center">Bottom Width(Mtrs)</th>
                        <th class="text-center">Top Width(Mtrs)</th>
                        <th class="text-center">Length (Mtrs)</th>
                        <th class="text-center">Height (Mtrs)</th>
                        <th class="text-center">Construction Year</th>
                        <th class="text-center">Renovation Year</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Action</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($protectionWallDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->protection_wall_cd }}
                                </td>
                                <!--Saiful -- 05-05-2026 -- Start-->
                                <td>
                                    {{ $item->project_cd }}
                                </td>
                                <!--Saiful -- 05-05-2026 -- End-->
                                <td>
                                    {{ $item->chainage }}
                                </td>
                                <td>
                                    {{ $item->wall_type_descr }}
                                </td>
                                <td>
                                    {{ $item->structure_type_descr }}
                                </td>
                                <td>
                                    {{ $item->bottom_width }}
                                </td>
                                <td>
                                    {{ $item->top_width }}
                                </td>
                                <td>
                                    {{ $item->length }}
                                </td>
                                <td>
                                    {{ $item->height }}
                                </td>
                                <td>
                                    {{ $item->year_of_construction }}
                                </td>
                                <td>
                                    {{ $item->year_of_renovation }}
                                </td>
                                <td>
                                    {{ $item->remarks }}
                                </td>
                                <td>
                                    <div class="">
                                        <div style="margin-bottom: 0.1rem;">
                                            <button class="approveBtn btn btn-outline-primary btn-xs text-xs"
                                                style="width: 4rem;" data-id="{{ $item->protection_wall_cd }}">
                                                Approve
                                            </button>
                                        </div>
                                        <div>
                                            <button class="rejectBtn btn btn-outline-danger btn-xs text-xs" style="width: 4rem;"
                                                data-id="{{ $item->protection_wall_cd }}">
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
                    <button id="finalizedData" class="btn btn-sm btn-danger rounded-1">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                        Finalize all darft data
                    </button>
                </div> --}}
            </div>
        </div>
    </section>
    <x-success-modal />
    <x-warning-modal />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/road/finalize/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script>
        $(function () {
            $("#protection_wall_details_table").DataTable({}).buttons().container().appendTo(
                '#protection_wall_details_table_wrapper .col-md-11:eq(1)');
        });

        $(document).ready(function () {
            const location = "{{ route('finalize.road.protectionWall') }}"
            $('#finalizedData').on('click', () => {
                const status = confirm("Click OK to procced!");
                if (status) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('finalize.road.protectionWall') }}",
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
                    showDashboardModal("Cancel to finalize!");
                }
            });
        });

        $('.approveBtn').on('click', function () {
            const protectionWallId = $(this).data('id');
            if (protectionWallId) {
                const final = confirm("Click OK to continue");
                if (final) {
                    $.ajax({
                        type: 'GET',
                        url: "/asset-management/accept-protection-wall-details/" + protectionWallId,
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
                    showDashboardModal("Cancel to finalize!");
                }
            }
        });

        $('.rejectBtn').on('click', function () {
            const protectionWallId = $(this).data('id');
            if (protectionWallId) {
                const final = confirm("Click OK to confirm rejection");
                if (final) {
                    let reason = prompt("Please Enter Reason of Rejection: ", "");
                    if (reason != null) {
                        $.ajax({
                            type: 'GET',
                            url: "/asset-management/reject-protection-wall-details/" + protectionWallId + "/" + reason,
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
                    showDashboardModal("Cancel the rejection!");
                }
            }
        });
    </script>
@endpush