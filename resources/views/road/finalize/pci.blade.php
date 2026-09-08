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
                        <li class="breadcrumb-item">Finalize PCI Details</li>
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
                    LIST OF DRAFT PCI DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="bridge_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">PCI Code</th>
                        <th class="text-center">PCI Length</th>
                        <th class="text-center">PCI Chainage</th>
                        <th class="text-center">PCI Value</th>
                        <th class="text-center">Cracking percentages</th>
                        <th class="text-center">Ravelling percentages</th>
                        <th class="text-center">Pot Holes percentages</th>
                        <th class="text-center">Shoving percentages</th>
                        <th class="text-center">Patching percentages</th>
                        <th class="text-center">Settlement percentages</th>
                        <th class="text-center">Rut Depth</th>
                        <th class="text-center">M. Traffic/Day</th>
                        <th class="text-center">Traffice Light</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Action</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($surfaceTypeDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->pci_section_cd }}
                                </td>
                                <td>
                                    {{ $item->pci_section_length_in_meter }}
                                </td>
                                <td>
                                    {{ $item->chainage }}
                                </td>
                                <td>
                                    {{ $item->pci_value }}
                                </td>
                                <td>
                                    {{ $item->cracking_percent ? $item->cracking_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->ravelling_percent ? $item->ravelling_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->pot_holes_percent ? $item->pot_holes_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->shoving_percent ? $item->shoving_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->patching_percent ? $item->patching_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->settlement_depression_percent ? $item->settlement_depression_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->rut_depth ? $item->rut_depth : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->tot_motorized_traffic_per_day }}
                                </td>
                                <td>
                                    @if ($item->pv_traffic_light == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->pci_remarks }}
                                </td>
                                <td>
                                    <div class="">
                                        <div style="margin-bottom: 0.1rem;">
                                            <button class="approveBtn btn btn-outline-primary btn-xs text-xs"
                                                style="width: 4rem;" data-id="{{ $item->pci_section_cd }}">
                                                Approve
                                            </button>
                                        </div>
                                        <div>
                                            <button class="rejectBtn btn btn-outline-danger btn-xs text-xs"
                                                style="width: 4rem;" data-id="{{ $item->pci_section_cd }}">
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
                {{-- <div class="d-flex text-sm justify-content-end mb-2">
                        <button id="finalizedData" class="btn btn-sm btn-success rounded-1">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                            Finalize all PCI data
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
        $(function() {
            $("#bridge_details_table").DataTable({}).buttons().container().appendTo(
                '#bridge_details_table_wrapper .col-md-11:eq(1)');
        });
    </script>
    <script>
        $(document).ready(function() {
            const location = "{{ route('finalize.road.pci') }}"
            $('#finalizedData').on('click', () => {
                const status = confirm("Are you sure?");
                if (status) {
                    const final = confirm("Click OK to procced!");
                    if (final) {
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('finalize.road.pci') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: $('#finalizedFormData').serialize(),
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
                        alert('Abort to finalized');
                    }
                } else {
                    alert('Abort to finalized');
                }
            });
        });

        $('.approveBtn').on('click', function() {
            const PCIID = $(this).data('id');
            if (PCIID) {
                const final = confirm("Click OK to continue");
                if (final) {
                    $.ajax({
                        type: 'GET',
                        url: "/asset-management/accept-pci-details/" + PCIID,
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

        $('.rejectBtn').on('click', function() {
            const PCIID = $(this).data('id');
            if (PCIID) {
                const final = confirm("Click OK to confirm rejection");
                if (final) {
                    let reason = prompt("Please Enter Reason of Rejection: ", "");
                    if (reason != null) {
                        $.ajax({
                            type: 'GET',
                            url: "/asset-management/reject-pci-details/" + PCIID + "/" + reason,
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
                        showDashboardModal("Reason Of Rejection Not Entered!");
                } else {
                    showDashboardModal("Cancel the rejection!");
                }
            }
        });
    </script>
@endpush
