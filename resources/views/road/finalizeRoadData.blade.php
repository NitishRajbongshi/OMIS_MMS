@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('manageRoad') }}">Manage Roads</a>
                        </li>
                        <li class="breadcrumb-item">Freeze Data</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody pb-2">
            <div class="row form-1-box">
                <div class="col-md-3 d-flex flex-wrap">
                    <label for="bearings">Search Data</label>
                    <select class="form-control" id="bearings" name="bearings">
                        <option value="bridgeData" id="bridgeData">Bridge Data</option>
                        <option value="cdWorkData" id="cdWorkData">CD Work Data</option>
                    </select>
                </div>
                <div class="col-md-9 d-flex justify-content-end align-items-center">
                    <button id="finalizedData" class="btn btn-primary rounded-0">Freeze Bridge Data</button>
                </div>
            </div>
        </div>

        <form id="finalizedFormData" class="">
            @csrf
            <input type="hidden" name="userId" id="userId" value="{{ $user->id }}">
        </form>

        <!-- draft table content -->
        <div class="container-fluid mainBody py-3">
            <div class="container-fluid mt-3">
                <h6 class="text-center"> <span class="border text-blue-800 px-3 py-1">Draft Bridge
                        Details</span>
                </h6>
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="bridge_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">SlNo.</th>
                        <th class="text-center">Bridge Type</th>
                        <th class="text-center">Bridge Name</th>
                        <th class="text-center">Bridge Number</th>
                        <th class="text-center">Bridge Location</th>
                        <th class="text-center">Chainage From</th>
                        <th class="text-center">Chainage To</th>
                        <th class="text-center">Bridge Lane</th>
                        <th class="text-center">River Name</th>
                        <th class="text-center">Bridge Length</th>
                        <th class="text-center">Construction Type</th>
                        <th class="text-center">Construction Year</th>
                        <th class="text-center">Last Inspection</th>
                        <th class="text-center">Span</th>
                        <th class="text-center">Span Length</th>
                        <th class="text-center">Kerbe Distance</th>
                        <th class="text-center">Kerbe width</th>
                        <th class="text-center">Foundation Type</th>

                        <th class="text-center">Pile Diameter</th>
                        <th class="text-center">Pile Length</th>
                        <th class="text-center">Pile Type</th>
                        <th class="text-center">Well Type</th>
                        <th class="text-center">Well Size</th>
                        <th class="text-center">Open Foundation Size</th>
                        <th class="text-center">Depth Open Found. Size</th>

                        <th class="text-center">Rehabilitation Year</th>
                        <th class="text-center">No. of Pears</th>
                        <th class="text-center">Pier Size</th>
                        <th class="text-center">Aboutment Type</th>
                        <th class="text-center">Super Structure Type</th>
                        <th class="text-center">Handrail Type</th>
                        <th class="text-center">Deck Type</th>
                        <th class="text-center">Carriage</th>
                        <th class="text-center">Guard Stone</th>
                        <th class="text-center">Load Capacity</th>
                        <th class="text-center">Signs?</th>
                        <th class="text-center">Lowest Water level</th>
                        <th class="text-center">Highest Flood Level</th>
                        <th class="text-center">rfl</th>
                        <th class="text-center">Source Depth</th>
                        <th class="text-center">Discharge</th>
                        <th class="text-center">Deck Level</th>
                        <th class="text-center">Footpath</th>
                        <th class="text-center">Bearings</th>
                        <th class="text-center">Expansion Joints</th>
                        <th class="text-center">Bridge Condition</th>
                        <th class="text-center">Expectation Date</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Edit</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($cd_bridge_details as $item)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td>
                                    {{ $item->bridge_type_descr }}
                                </td>
                                <td>
                                    {{ $item->bridge_name }}
                                </td>
                                <td>
                                    {{ $item->bridge_number }}
                                </td>
                                <td>
                                    {{ $item->bridge_location }}
                                </td>
                                <td>
                                    {{ $item->chainage_from }}
                                </td>
                                <td>
                                    {{ $item->chainage_to }}
                                </td>
                                <td>
                                    {{ $item->bridge_lane }}
                                </td>
                                <td>
                                    {{ $item->river_name }}
                                </td>
                                <td>
                                    {{ $item->cd_bridge_length }}
                                </td>
                                <td>
                                    {{ $item->construction_type_descr }}
                                </td>
                                <td>
                                    {{ $item->year_of_construction }}
                                </td>
                                <td>
                                    {{ $item->date_of_last_inspection }}
                                </td>
                                <td>
                                    {{ $item->no_of_span }}
                                </td>
                                <td>
                                    {{ $item->span_length }}
                                </td>
                                <td>
                                    {{ $item->kerb_distance }}
                                </td>
                                <td>
                                    {{ $item->kerb_width }}
                                </td>
                                <td>
                                    {{ $item->foundation_descr }}
                                </td>

                                <td>
                                    {{ $item->pile_diameter }}
                                </td>
                                <td>
                                    {{ $item->pile_length }}
                                </td>
                                <td>
                                    {{ $item->pile_type }}
                                </td>
                                <td>
                                    {{ $item->well_type }}
                                </td>
                                <td>
                                    {{ $item->well_size }}
                                </td>
                                <td>
                                    {{ $item->open_foundation_size }}
                                </td>
                                <td>
                                    {{ $item->depth_open_foundation_size }}
                                </td>

                                <td>
                                    {{ $item->year_of_rehabilitation }}
                                </td>
                                <td>
                                    {{ $item->no_of_piers }}
                                </td>
                                <td>
                                    {{ $item->pier_size }}
                                </td>
                                <td>
                                    {{ $item->abutment_type_descr }}
                                </td>
                                <td>
                                    {{ $item->st_type_descr }}
                                </td>

                                <td>
                                    {{ $item->hand_rail_type_descr }}
                                </td>
                                <td>
                                    {{ $item->deck_type_descr }}
                                </td>
                                <td>
                                    {{ $item->carriage }}
                                </td>
                                <td>
                                    {{ $item->guard_stone }}
                                </td>
                                <td>
                                    {{ $item->load_capacity }}
                                </td>
                                <td>
                                    @if ($item->signs == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->lowest_water_level }}
                                </td>
                                <td>
                                    {{ $item->highest_flood_level }}
                                </td>
                                <td>
                                    {{ $item->rfl }}
                                </td>
                                <td>
                                    {{ $item->source_depth }}
                                </td>
                                <td>
                                    {{ $item->discharge }}
                                </td>

                                <td>
                                    {{ $item->deck_level }}
                                </td>
                                <td>
                                    @if ($item->footh_path == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif

                                </td>
                                <td>
                                    {{ $item->bearing_type_descr }}
                                </td>
                                <td>
                                    {{ $item->expn_joint_descr }}
                                </td>
                                <td>
                                    {{ $item->rd_condition_descr }}
                                </td>
                                <td>
                                    {{ $item->next_schedule_inspection_date }}
                                </td>
                                <td>
                                    {{ $item->bridge_remark }}
                                </td>
                                <td class="text-center">
                                    @if ($updated == 1)
                                        <a class="text-primary edit" data-toggle="modal"
                                            data-target="#editModal{{ $item->rd_bridge_cd }}"><i
                                                class="fas fa-edit"></i></a>
                                    @else
                                        <span class="text-danger text-bold"><i class="fas fa-ban"></i></span>
                                    @endif
                                </td>
                            </tr>
                            <?php $i++; ?>

                            <!-- The Edit Modal -->
                            <div class="modal" id="editModal{{ $item->rd_bridge_cd }}">
                                <div class="modal-dialog modal-lg text-xs">
                                    <form class="updateBridgeDetails" id="update_bridge_{{ $item->rd_bridge_cd }}"
                                        method="POST" action="{{ route('bridge.update') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->rd_bridge_cd }}">
                                        <div class="modal-content">
                                            <!-- Modal Header -->
                                            <div class="modal-header" style="background-color: rgb(240, 240, 240);">
                                                <h5><i class="fas fa-clipboard-list text-dark"></i>
                                                    <strong class="text-md">Update Bridge Details</strong>
                                                </h5>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <div class="row text-xs">
                                                    <div class="col-md-6">
                                                        <label for="bridge_name">Bridge Name <span
                                                                class="star">*</span></label>
                                                        <input type="text" class="form-control" name="bridge_name"
                                                            value="{{ $item->bridge_name }}" required>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="bridge_no">Bridge Number <span
                                                                class="star">*</span></label>
                                                        <input type="text" class="form-control" name="bridge_no"
                                                            value="{{ $item->bridge_number }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="location">Location<span
                                                                class="star">*</span></label>
                                                        <input type="text" class="form-control modal_phoneno"
                                                            name="location" value="{{ $item->bridge_location }}"
                                                            required>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="chainage_from">Chainage From <span
                                                                class="star">*</span></label>
                                                        <input type="text" class="form-control" name="chainage_from"
                                                            value="{{ $item->chainage_from }}" required>

                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="chainage_to">Chainage To <span
                                                                class="star">*</span></label>
                                                        <input type="text" class="form-control" name="chainage_to"
                                                            value="{{ $item->chainage_to }}" required>

                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="bridge_lane">Bridge Lane <span
                                                                class="star">*</span></label>
                                                        <input type="text" class="form-control" name="bridge_lane"
                                                            value="{{ $item->bridge_lane }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="river_name">River Name <span
                                                                class="star">*</span></label>
                                                        <input type="text" class="form-control" name="river_name"
                                                            value="{{ $item->river_name }}" required>
                                                        <span class="text-danger" id="address2_error"></span>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="bridge_length"> Bridge Length<span
                                                                class="star">*</span></label>
                                                        <input type="number" class="form-control modal_pin"
                                                            name="bridge_length" value="{{ $item->cd_bridge_length }}"
                                                            required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="construction_type">Construction Type <span
                                                                class="star">*</span></label>
                                                        <select id="construction_type" class="form-control custom-select"
                                                            name="construction_type">
                                                            <option value="{{ $item->construction_type_cd }}">
                                                                {{ $item->construction_type_descr }}</option>
                                                            @foreach ($constructionTypes as $constTyp)
                                                                @if ($constTyp->construction_type_descr != $item->construction_type_descr)
                                                                    <option value="{{ $item->construction_type_cd }}">
                                                                        {{ $constTyp->construction_type_descr }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="submit"
                                                    class="btn btn-sm btn-success modalUpBtn">Update</button>
                                                <a class="btn btn-sm modalClose btn-danger" data-dismiss="modal">CLOSE</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal -->
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Freeze table data --}}
        <div class="container-fluid mainBody py-3">
            <div class="container-fluid mt-3">
                <h6 class="text-center"> <span class="border text-blue-800 px-3 py-1">Finalized Bridge
                        Details</span>
                </h6>
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="bridge_details_table_final">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">SlNo.</th>
                        <th class="text-center">Bridge Type</th>
                        <th class="text-center">Bridge Name</th>
                        <th class="text-center">Bridge Number</th>
                        <th class="text-center">Bridge Location</th>
                        <th class="text-center">Chainage From</th>
                        <th class="text-center">Chainage To</th>
                        <th class="text-center">Bridge Lane</th>
                        <th class="text-center">River Name</th>
                        <th class="text-center">Bridge Length</th>
                        <th class="text-center">Construction Type</th>
                        <th class="text-center">Construction Year</th>
                        <th class="text-center">Last Inspection</th>
                        <th class="text-center">Span</th>
                        <th class="text-center">Span Length</th>
                        <th class="text-center">Kerbe Distance</th>
                        <th class="text-center">Kerbe width</th>
                        <th class="text-center">Foundation Type</th>

                        <th class="text-center">Pile Diameter</th>
                        <th class="text-center">Pile Length</th>
                        <th class="text-center">Pile Type</th>
                        <th class="text-center">Well Type</th>
                        <th class="text-center">Well Size</th>
                        <th class="text-center">Open Foundation Size</th>
                        <th class="text-center">Depth Open Found. Size</th>

                        <th class="text-center">Rehabilitation Year</th>
                        <th class="text-center">No. of Pears</th>
                        <th class="text-center">Pier Size</th>
                        <th class="text-center">Aboutment Type</th>
                        <th class="text-center">Super Structure Type</th>
                        <th class="text-center">Handrail Type</th>
                        <th class="text-center">Deck Type</th>
                        <th class="text-center">Carriage</th>
                        <th class="text-center">Guard Stone</th>
                        <th class="text-center">Load Capacity</th>
                        <th class="text-center">Signs?</th>
                        <th class="text-center">Lowest Water level</th>
                        <th class="text-center">Highest Flood Level</th>
                        <th class="text-center">rfl</th>
                        <th class="text-center">Source Depth</th>
                        <th class="text-center">Discharge</th>
                        <th class="text-center">Deck Level</th>
                        <th class="text-center">Footpath</th>
                        <th class="text-center">Bearings</th>
                        <th class="text-center">Expansion Joints</th>
                        <th class="text-center">Bridge Condition</th>
                        <th class="text-center">Expectation Date</th>
                        <th class="text-center">Remarks</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($cd_bridge_details_final as $item)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td>
                                    {{ $item->bridge_type_descr }}
                                </td>
                                <td>
                                    {{ $item->bridge_name }}
                                </td>
                                <td>
                                    {{ $item->bridge_number }}
                                </td>
                                <td>
                                    {{ $item->bridge_location }}
                                </td>
                                <td>
                                    {{ $item->chainage_from }}
                                </td>
                                <td>
                                    {{ $item->chainage_to }}
                                </td>
                                <td>
                                    {{ $item->bridge_lane }}
                                </td>
                                <td>
                                    {{ $item->river_name }}
                                </td>
                                <td>
                                    {{ $item->cd_bridge_length }}
                                </td>
                                <td>
                                    {{ $item->construction_type_descr }}
                                </td>
                                <td>
                                    {{ $item->year_of_construction }}
                                </td>
                                <td>
                                    {{ $item->date_of_last_inspection }}
                                </td>
                                <td>
                                    {{ $item->no_of_span }}
                                </td>
                                <td>
                                    {{ $item->span_length }}
                                </td>
                                <td>
                                    {{ $item->kerb_distance }}
                                </td>
                                <td>
                                    {{ $item->kerb_width }}
                                </td>
                                <td>
                                    {{ $item->foundation_descr }}
                                </td>

                                <td>
                                    {{ $item->pile_diameter }}
                                </td>
                                <td>
                                    {{ $item->pile_length }}
                                </td>
                                <td>
                                    {{ $item->pile_type }}
                                </td>
                                <td>
                                    {{ $item->well_type }}
                                </td>
                                <td>
                                    {{ $item->well_size }}
                                </td>
                                <td>
                                    {{ $item->open_foundation_size }}
                                </td>
                                <td>
                                    {{ $item->depth_open_foundation_size }}
                                </td>

                                <td>
                                    {{ $item->year_of_rehabilitation }}
                                </td>
                                <td>
                                    {{ $item->no_of_piers }}
                                </td>
                                <td>
                                    {{ $item->pier_size }}
                                </td>
                                <td>
                                    {{ $item->abutment_type_descr }}
                                </td>
                                <td>
                                    {{ $item->st_type_descr }}
                                </td>

                                <td>
                                    {{ $item->hand_rail_type_descr }}
                                </td>
                                <td>
                                    {{ $item->deck_type_descr }}
                                </td>
                                <td>
                                    {{ $item->carriage }}
                                </td>
                                <td>
                                    {{ $item->guard_stone }}
                                </td>
                                <td>
                                    {{ $item->load_capacity }}
                                </td>
                                <td>
                                    @if ($item->signs == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->lowest_water_level }}
                                </td>
                                <td>
                                    {{ $item->highest_flood_level }}
                                </td>
                                <td>
                                    {{ $item->rfl }}
                                </td>
                                <td>
                                    {{ $item->source_depth }}
                                </td>
                                <td>
                                    {{ $item->discharge }}
                                </td>

                                <td>
                                    {{ $item->deck_level }}
                                </td>
                                <td>
                                    @if ($item->footh_path == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif

                                </td>
                                <td>
                                    {{ $item->bearing_type_descr }}
                                </td>
                                <td>
                                    {{ $item->expn_joint_descr }}
                                </td>
                                <td>
                                    {{ $item->rd_condition_descr }}
                                </td>
                                <td>
                                    {{ $item->next_schedule_inspection_date }}
                                </td>
                                <td>
                                    {{ $item->bridge_remark }}
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#bridge_details_table").DataTable({}).buttons().container().appendTo(
                '#bridge_details_table_wrapper .col-md-11:eq(1)');
        });
        $(function() {
            $("#bridge_details_table_final").DataTable({}).buttons().container().appendTo(
                '#bridge_details_table_wrapper .col-md-11:eq(1)');
        });
    </script>

    <script>
        $(document).ready(function() {
            const location = "{{ route('FinalDataRoad') }}"
            $('#finalizedData').on('click', () => {
                const status = confirm("Are you sure?");
                if (status) {
                    const final = confirm("Click OK to procced!");
                    if (final) {
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('FinalDataRoad') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: $('#finalizedFormData').serialize(),
                            cache: false,
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'success',
                                        text: response.message,
                                        showConfirmButton: true,
                                        timer: 3000
                                    });
                                    window.location.replace(location)
                                }

                                if (response.status === 'failed') {
                                    Swal.fire({
                                        icon: 'failed',
                                        title: 'failed',
                                        text: response.message,
                                        showConfirmButton: true,
                                        timer: 3000
                                    });
                                    window.location.replace(location)
                                }

                                if (response.status === 'error') {
                                    Swal.fire({
                                        icon: 'failed',
                                        title: 'error',
                                        text: 'Internal Server Error',
                                        showConfirmButton: true,
                                        timer: 3000
                                    });
                                    window.location.replace(location)
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
    </script>
@endpush
