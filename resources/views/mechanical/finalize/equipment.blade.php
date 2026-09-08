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
    <div class="d-flex justify-content-start my-2">
        <div class="col-sm-6 col-md-auto">
            <button class="btn btn-sm btn-outline-secondary">
                <a href="{{ route('vehicle.finalize') }}" class="text-dark">
                    <i class="fa fa-check-circle mr-1"></i>
                    Finalize Vehicle Details
                </a>
            </button>
        </div>
        <div class="col-sm-6 col-md-auto">
            <button class="btn btn-sm btn-primary">
                <a href="{{ route('equipment.finalize') }}" class="text-light">
                    <i class="fa fa-check-circle mr-1"></i>
                    Finalize Equipment Details
                </a>
            </button>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <form id="finalizedFormData" class="">
            @csrf
            <input type="hidden" name="userId" id="userId" value="{{ $user->id }}">
        </form>

        <!-- table content -->
        <h6 class="p-2 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF DRAFT EQUIPMENT DETAILS SEND FOR FINALIZATION UNDER NAGALAND P W D.
            </span>
        </h6>
        <div class="container-fluid border mainBody py-2">
            <div class="">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="draft_equipment_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Sl No.</th>
                        <th class="text-center">Equipment Code</th>
                        <th class="text-center">Equipment Name</th>
                        <th class="text-center">Serial Number</th>
                        <th class="text-center">Model Number</th>
                        <th class="text-center">Purchased year</th>
                        <th class="text-center">Purchased Cost</th>
                        <th class="text-center">Equipment Condition</th>
                        <th class="text-center">Under Warranty?</th>
                        <th class="text-center">Equipment Remarks</th>
                        <th class="text-center">Action</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($draftEquipmentDetails as $draftEquipmentDetail)
                            <tr class="text-center">
                                <td class="text-center">{{ $i }}</td>
                                <td>
                                    {{ $draftEquipmentDetail->euipment_cd }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->equipment_name }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->serial_number }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->model_no }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->purchase_year }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->purchase_cost }}
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->condition_descr }}
                                </td>
                                <td>
                                    @if ($draftEquipmentDetail->is_under_waranty == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $draftEquipmentDetail->equipment_remarks }}
                                </td>
                                <td class="text-center">
                                    <div class="">
                                        <div style="margin-bottom: 0.1rem;">
                                            <button
                                                class="approveBtn btn btn-outline-primary btn-xs text-xs enableMouseEvent"
                                                style="width: 4rem;" data-id="{{ $draftEquipmentDetail->euipment_cd }}"
                                                id="btnApprove_{{ $draftEquipmentDetail->euipment_cd }}"
                                                onmouseover="showToolTip(this);">
                                                Approve
                                            </button>
                                            <button class="rejectBtn btn btn-outline-danger btn-xs text-xs"
                                                style="width: 4rem;" data-id="{{ $draftEquipmentDetail->euipment_cd }}"
                                                id="btnReject_{{ $draftEquipmentDetail->euipment_cd }}">
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
            if (status) {
                const final = confirm("Click OK to procced!");
                if (final) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('equipment.finalize') }}",
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
                        url: "/asset-management/freeze-equipment-details/" + buildingId,
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
                    showDashboardModal("Cancel to finalize!");
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
                            url: "/asset-management/reject-equipment-details/" + vehicleID + "/" + reason,
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
                    showDashboardModal("Cancel the rejection!");
                }
            }
        });
    </script>
@endpush
