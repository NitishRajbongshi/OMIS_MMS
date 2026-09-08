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
                        <li class="breadcrumb-item">Finalize Habitation Details</li>
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
                    LIST OF DRAFT HABITATION DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="bridge_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial Number</th>
                        <th class="text-center">Habitation Code</th>
                        <th class="text-center">District Name</th>
                        <th class="text-center">Block Name</th>
                        <th class="text-center">Village Name</th>
                        <th class="text-center">MLA Constituency</th>
                        <th class="text-center">MP Constituency</th>
                        <th class="text-center">Population Count</th>
                        <th class="text-center">Habitation Remarks</th>
                        <th class="text-center">Facilities</th>
                        <th class="text-center">Action</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($habitationDetails as $habitationDetail)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $habitationDetail->habitation_cd }}
                                </td>
                                <td class="text-uppercase">
                                    {{ $habitationDetail->district }}
                                </td>
                                <td class="text-uppercase">
                                    {{ $habitationDetail->block }}
                                </td>
                                <td class="text-uppercase">
                                    {{ $habitationDetail->village }}
                                </td>
                                <td class="text-uppercase">
                                    {{ $habitationDetail->mla }}
                                </td>
                                <td class="text-uppercase">
                                    {{ $habitationDetail->mp }}
                                </td>
                                <td>
                                    {{ $habitationDetail->total_population }}
                                </td>
                                <td>
                                    {{ $habitationDetail->remarks }}
                                </td>
                                <td>
                                    @if(isset($habitationFacilities[$habitationDetail->habitation_cd]))
                                        @php
                                            $groupedFacilities = collect($habitationFacilities[$habitationDetail->habitation_cd])
                                                ->groupBy('facility_name');
                                        @endphp
                                        <!-- Expand Button -->
                                        <a class="text-primary" data-toggle="collapse"
                                            href="#facility{{ $habitationDetail->habitation_cd }}" role="button">
                                            <i class="fas fa-plus-circle"></i> View
                                        </a>
                                        <div class="collapse mt-2" id="facility{{ $habitationDetail->habitation_cd }}">
                                            <table class="table table-bordered table-sm mb-0 text-xs">
                                                <thead>
                                                    <tr>
                                                        <th style="width:40%">Facility</th>
                                                        <th style="width:60%">Sub Facilities</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($groupedFacilities as $facilityName => $subs)
                                                        <tr>
                                                            <td class="text-left">
                                                                {{ $facilityName }}
                                                            </td>
                                                            <td class="text-left">
                                                                <ul class="mb-0 ps-3">
                                                                    @foreach($subs as $sub)
                                                                        <li>{{ $sub->sub_facility_name }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-muted text-center">
                                            No Facilities Added
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="">
                                        <div style="margin-bottom: 0.1rem;">
                                            <button class="approveBtn btn btn-outline-primary btn-xs text-xs"
                                                style="width: 4rem;" data-id="{{ $habitationDetail->habitation_cd }}">
                                                Approve
                                            </button>
                                        </div>
                                        <div>
                                            <button class="rejectBtn btn btn-outline-danger btn-xs text-xs" style="width: 4rem;"
                                                data-id="{{ $habitationDetail->habitation_cd }}">
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
                    <button id="finalizedData" class="btn btn-sm btn-success rounded-1">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                        Finalize all habitation data
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
            $("#bridge_details_table").DataTable({}).buttons().container().appendTo(
                '#bridge_details_table_wrapper .col-md-11:eq(1)');
        });
    </script>
    <script>
        $(document).ready(function () {
            const location = "{{ route('finalize.road.habitation') }}"
            $('#finalizedData').on('click', () => {
                const status = confirm("Click OK to continue");
                if (status) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('finalize.road.habitation') }}",
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
            const habitationID = $(this).data('id');
            if (habitationID) {
                const final = confirm("Click OK to continue");
                if (final) {
                    $.ajax({
                        type: 'GET',
                        url: "/asset-management/accept-habitation-details/" + habitationID,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        cache: false,
                        success: function (response) {
                            console.log("response: ", response);
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
            const habitationID = $(this).data('id');
            if (habitationID) {
                const final = confirm("Click OK to confirm rejection");
                if (final) {
                    let reason = prompt("Please Enter Reason of Rejection: ", "");
                    if (reason != null) {
                        $.ajax({
                            type: 'GET',
                            url: "/asset-management/reject-habitation-details/" + habitationID + "/" +
                                reason,
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