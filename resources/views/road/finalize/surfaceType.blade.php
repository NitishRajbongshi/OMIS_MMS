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
                        <li class="breadcrumb-item">Finalize Surface Type Details</li>
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
                    LIST OF DRAFT SURFACE TYPE DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="bridge_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">Surface Code</th>
                        <th class="text-center">Surface Types</th>
                        <th class="text-center">Condition</th>
                        <th class="text-center">Surface Width</th>
                        <th class="text-center">Shoulder Width</th>
                        <th class="text-center">Start Chainage</th>
                        <th class="text-center">End Chainage</th>
                        <th class="text-center" style="min-width: 5rem;">Base-Layer Type</th>
                        <th class="text-center" style="min-width: 5rem;">Base-Layer Thickness</th>
                        <th class="text-center" style="min-width: 6rem;">SubBase-Layer Thickness</th>
                        <th class="text-center" style="min-width: 6rem;">SubBase-Layer Thickness</th>
                        <th class="text-center">Pavement Type</th>
                        <th class="text-center">Shoulder Type</th>
                        <th class="text-center">Land Slide</th>
                        <th class="text-center">Construction Year</th>
                        <th class="text-center">Base CBR</th>
                        <th class="text-center">Base PI</th>
                        <th class="text-center" style="min-width: 4rem;">Sub-Base CBR</th>
                        <th class="text-center" style="min-width: 4rem;">Sub-Base PI</th>
                        <th class="text-center">Maintenance Type</th>
                        <th class="text-center">Maintenance Date</th>
                        <th class="text-center">Drainage</th>
                        <th class="text-center">Action</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($surfaceTypeDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->rd_surface_cd }}
                                </td>
                                <td>
                                    {{ $item->surface_descr }}
                                </td>
                                <td>
                                    {{ $item->surface_width }}
                                </td>
                                <td>
                                    {{ $item->shoulder_width }}
                                </td>
                                <td>
                                    {{ $item->rd_condition_descr }}
                                </td>
                                <td>
                                    {{ $item->start_chainage }}
                                </td>
                                <td>
                                    {{ $item->end_chainage }}
                                </td>
                                <td>
                                    {{ $item->base_layer_type_descr }}
                                </td>
                                <td>
                                    {{ $item->base_layer_thickness }}
                                </td>
                                <td>
                                    {{ $item->sub_base_layer_type_descr }}
                                </td>
                                <td>
                                    {{ $item->sub_base_layer_thickness }}
                                </td>
                                <td>
                                    {{ $item->pavement_type_descr }}
                                </td>
                                <td>
                                    {{ $item->shoulder_type_descr }}
                                </td>
                                <td>
                                    @if ($item->land_slide == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->construction_year }}
                                </td>
                                <td>
                                    {{ $item->base_cbr }}
                                </td>
                                <td>
                                    {{ $item->base_pi }}
                                </td>
                                <td>
                                    {{ $item->sub_base_cbr }}
                                </td>
                                <td>
                                    {{ $item->sub_base_pi }}
                                </td>
                                <td>
                                    {{ $item->maintenance_type_descr }}
                                </td>
                                <td>
                                    {{ $item->last_maintenance_date }}
                                </td>
                                <td>
                                    {{ $item->drainage_descr }}
                                </td>
                                <td>
                                    <div class="">
                                        <div style="margin-bottom: 0.1rem;">
                                            <button class="approveBtn btn btn-outline-primary btn-xs text-xs"
                                                style="width: 4rem;" data-id="{{ $item->rd_surface_cd }}">
                                                Approve
                                            </button>
                                        </div>
                                        <div>
                                            <button class="rejectBtn btn btn-outline-danger btn-xs text-xs"
                                                style="width: 4rem;" data-id="{{ $item->rd_surface_cd }}">
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
                            Finalize all SurfaceType data
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
            const location = "{{ route('finalize.road.surface.types') }}"
            $('#finalizedData').on('click', () => {
                const status = confirm("Are you sure?");
                if (status) {
                    const final = confirm("Click OK to procced!");
                    if (final) {
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('finalize.road.surface.types') }}",
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
                        alert('abort to finalized');
                    }
                } else {
                    alert('Abort to finalized');
                }
            });
        });

        $('.approveBtn').on('click', function() {
            const surfaceTypeCD = $(this).data('id');
            if (surfaceTypeCD) {
                const final = confirm("Click OK to continue");
                if (final) {
                    $.ajax({
                        type: 'GET',
                        url: "/asset-management/accept-surface-type-details/" + surfaceTypeCD,
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
            const surfaceTypeCD = $(this).data('id');
            if (surfaceTypeCD) {
                const final = confirm("Click OK to confirm rejection");
                if (final) {
                    let reason = prompt("Please Enter Reason of Rejection: ", "");
                    if (reason != null) {
                        $.ajax({
                            type: 'GET',
                            url: "/asset-management/reject-surface-type-details/" + surfaceTypeCD + "/" + reason,
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
