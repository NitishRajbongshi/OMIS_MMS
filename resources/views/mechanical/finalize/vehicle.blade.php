@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard.equipment') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('mechanical') }}">Manage Machineries</a>
                        </li>
                        <li class="breadcrumb-item">Finalize Vehicle Details</li>
                    </ol>
                </div>
                @if (session('dataEntry') == 1)
                    <div class="col-md-2 col-sm-6 d-flex justify-content-end">
                        <a href="{{ route('addVehicle') }}" class="btn btn-xs text-light btn-primary">
                            <i class="fas fa-plus-circle"></i>
                            Add Machinery Data
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Main content -->
    <div class="d-flex justify-content-start my-2">
        <div class="col-sm-6 col-md-auto">
            <button class="btn btn-sm btn-primary">
                <a href="{{ route('vehicle.finalize') }}" class="text-light">
                    <i class="fa fa-check-circle mr-1"></i>
                    Finalize Vehicle Details
                </a>
            </button>
        </div>
        <div class="col-sm-6 col-md-auto">
            <button class="btn btn-sm btn-outline-secondary">
                <a href="{{ route('equipment.finalize') }}" class="text-dark">
                    <i class="fa fa-check-circle mr-1"></i>
                    Finalize Equipment Details
                </a>
            </button>
        </div>
    </div>
    <section class="content">
        <form id="finalizedFormData" class="">
            @csrf
            <input type="hidden" name="userId" id="userId" value="{{ $user->id }}">
            {{-- <input type="hidden" name="buildingId" id="buildingId" value="{{ $item->building_system_cd }}"> --}}
        </form>

        <!-- table content -->
        <h6 class="p-2 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF DRAFT VEHICLE DETAILS SEND FOR FINALIZATION UNDER NAGALAND P W D.
            </span>
        </h6>
        <div class="container-fluid mainBody border py-2">
            <div class="">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="draft_equipment_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Sl No.</th>
                        <th class="text-center">Vehicle Code</th>
                        <th class="text-center">Vehicle Name</th>
                        <th class="text-center">Registration No.</th>
                        <th class="text-center">Chassis No.</th>
                        <th class="text-center">Engine No.</th>
                        <th class="text-center">Vehicle Type</th>
                        <th class="text-center">Seat Capacity</th>
                        <th class="text-center">Total Wheels</th>
                        <th class="text-center">Vehicle Maker</th>
                        <th class="text-center">Vehicle Model</th>
                        <th class="text-center">Fuel Type</th>
                        <th class="text-center">Purchased Date</th>
                        <th class="text-center">Vehicle Cost</th>
                        <th class="text-center">Vehicle Condition</th>
                        <th class="text-center">Laden Weight</th>
                        <th class="text-center">Unladen Weight</th>
                        <th class="text-center">Alloted To</th>
                        <th class="text-center">Alloted From</th>
                        <th class="text-center">Vehicle Remarks</th>
                        <th class="text-center">Action</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($draftVehicleDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->vehicle_asset_cd }}
                                </td>
                                <td>
                                    {{ $item->vehicle_name }}
                                </td>
                                <td>
                                    {{ $item->vehicle_regn_no }}
                                </td>
                                <td>
                                    {{ $item->chassis_no }}
                                </td>
                                <td>
                                    {{ $item->engine_no }}
                                </td>
                                <td>
                                    {{ $item->veh_type_descr }}
                                </td>
                                <td>
                                    {{ $item->seating_capacity }}
                                </td>
                                <td>
                                    {{ $item->no_of_wheels }}
                                </td>
                                <td>
                                    {{ $item->maker_name }}
                                </td>
                                <td>
                                    {{ $item->model }}
                                </td>
                                <td>
                                    {{ $item->fuel_type_descr }}
                                </td>
                                <td>
                                    {{ $item->date_of_purchase }}
                                </td>
                                <td>
                                    {{ $item->purchase_cost }}
                                </td>
                                <td>
                                    {{ $item->condition_descr }}
                                </td>
                                <td>
                                    {{ $item->laden_weight }}
                                </td>
                                <td>
                                    {{ $item->unladen_weight }}
                                </td>
                                <td>
                                    {{ $item->alloted_to }}
                                </td>
                                <td>
                                    {{ $item->alloted_from_date }}
                                </td>
                                <td>
                                    {{ $item->remarks }}
                                </td>
                                <td class="text-center">
                                    <div class="">
                                        <div style="margin-bottom: 0.1rem;">
                                            <button
                                                class="approveBtn btn btn-outline-primary btn-xs text-xs enableMouseEvent"
                                                style="width: 4rem;" data-id="{{ $item->vehicle_asset_cd }}"
                                                id="btnApprove_{{ $item->vehicle_asset_cd }}"
                                                onmouseover="showToolTip(this);">
                                                Approve
                                            </button>
                                            <button class="rejectBtn btn btn-outline-danger btn-xs text-xs"
                                                style="width: 4rem;" data-id="{{ $item->vehicle_asset_cd }}"
                                                id="btnReject_{{ $item->vehicle_asset_cd }}">
                                                Reject
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
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
        $(function() {
            $("#draft_equipment_details_table").DataTable();
        });

        $('#finalizedData').on('click', () => {
            const status = confirm("Are you sure?");
            // const header = 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content');
            if (status) {
                const final = confirm("Click OK to procced!");
                if (final) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('vehicle.finalize') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $('#finalizedFormData').serialize(),
                        cache: false,
                        success: function(response) {
                            console.log(response);
                            if (response.status === 'success') {
                                Swal.fire({
                                        icon: 'success',
                                        title: 'success',
                                        text: response.message,
                                        showConfirmButton: true,
                                        timer: 3000
                                    })
                                    .then(() => {
                                        window.location.replace(location)
                                    });
                            }

                            if (response.status === 'failed') {
                                Swal.fire({
                                        icon: 'failed',
                                        title: 'failed',
                                        text: response.message,
                                        showConfirmButton: true,
                                        timer: 3000
                                    })
                                    .then(() => {
                                        window.location.replace(location)
                                    });
                            }

                            if (response.status === 'error') {
                                Swal.fire({
                                        icon: 'failed',
                                        title: 'error',
                                        text: 'Internal Server Error',
                                        showConfirmButton: true,
                                        timer: 3000
                                    })
                                    .then(() => {
                                        window.location.replace(location)
                                    });
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

        // aprove a single housing data
        $('.approveBtn').on('click', function() {
            const buildingId = $(this).data('id');
            if (buildingId) {
                const final = confirm("Click OK to continue");
                if (final) {
                    $.ajax({
                        type: 'GET',
                        url: "/asset-management/freeze-vehicle-details/" + buildingId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        cache: false,
                        success: function(response) {
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

        // reject a single housing data
        $('.rejectBtn').on('click', function() {
            const vehicleID = $(this).data('id');
            if (vehicleID) {
                const final = confirm("Click OK to confirm rejection");
                if (final) {
                    let reason = prompt("Please Enter Reason of Rejection", "");
                    if (reason != null) {
                        $.ajax({
                            type: 'GET',
                            url: "/asset-management/reject-vehicle-details/" + vehicleID + "/" + reason,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            cache: false,
                            success: function(response) {
                                console.log(response);
                                if (response.status === 200) {
                                    showSuccessModal(response.message);
                                } else {
                                    showDashboardModal(response.message);
                                }
                            }
                        })
                    } else
                        alert('Reason Of Rejection Not Entered, Road Not Rejected');
                } else {
                    alert('Cancel the rejection');
                }
            }
        });
    </script>
@endpush
