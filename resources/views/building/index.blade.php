@extends('layouts.app')
@section('content')
    <main class="command-center">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row text-sm">
                    <div class="col-sm-12 col-md-6">
                        <div class="command-breadcrumb">
                            <i class="fas fa-house"></i>
                            <span><a href="{{ route('dashboard.housing') }}"
                                    style="color: inherit; text-decoration: none;">Dashboard</a></span>
                            <span>/</span>
                            <strong>Manage Housing</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="command-heading px-3">
            <div>
                <h1>Manage Government Buildings</h1>
                <p>List of finalized and drafted government buildings under
                    {{ session('department') ?: 'Nagaland P.W.D (Housing)' }}
                </p>
            </div>
            <div class="command-actions">
                <a href="{{ route('building.view.index') }}" class="btn btn-danger btn-sm text-light">
                    <i class="fas fa-plus-circle mr-1"></i>
                    Allot Unit
                </a>
                @if (session('finalised') === 1)
                    <a href="{{ route('housing.finalize') }}" class="btn btn-primary btn-sm text-light">
                        <i class="fas fa-check-circle mr-1"></i>
                        Finalize Building Details
                    </a>
                @endif
                @if (session('dataEntry') === 1 || session('dataEntry') === 'Y')
                    <a href="{{ route('manage.housing.create') }}" class="btn btn-primary btn-sm text-light">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Add Building Details
                    </a>
                @endif
                @if (
                    (session('user_dept_cd') === 6 && session('users_office_type_cd') === 'DA') ||
                        session('userRoleId') == 1 ||
                        session('users_office_type_cd') === 'SDO')
                    <a href="{{ route('building-location.index') }}" class="btn btn-primary btn-sm text-light">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Add Location
                    </a>
                @endif
            </div>
        </section>

        <!-- Main content -->
        <section class="content px-3">
            <x-building-flash-message />

            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Government Buildings (Housing)</span>
                        <h2>List of Finalized Government Buildings</h2>
                    </div>
                </header>
                <div class="table-responsive p-3">
                    <table class="table table-striped table-bordered text-xs user_list w-100"
                        id="freezed_building_details_table">
                        <thead>
                            <tr>
                                <th class="text-center">Sl No.</th>
                                <th class="text-center">Building ID</th>
                                <th class="text-center">Quarter Number</th>
                                <th class="text-center">Building Name</th>
                                <th class="text-center">Building Type</th>
                                <th class="text-center">Maintained by NPWD?</th>
                                <th class="text-center">Residential/Non-Residential</th>
                                <th class="text-center">Owning Department</th>
                                <th class="text-center">Building Category</th>
                                <th class="text-center">Access Type</th>
                                <th class="text-center">Fencing Type</th>
                                <th class="text-center">Plinth Area(Sq.ft)</th>
                                <th class="text-center">Plot Area</th>
                                <th class="text-center">Construction Year</th>
                                <th class="text-center">Construction Cost(Rs.)</th>
                                <th class="text-center">Water Supply(External)</th>
                                <th class="text-center">Electricity</th>
                                <th class="text-center">Sanitary</th>
                                <th class="text-center">Present Occupant</th>
                                <th class="text-center">Occupant Department</th>
                                <th class="text-center">Remarks</th>
                                <th class="text-center">Additional Details</th>
                                <th class="text-center">Unit Allocation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($finalizedBuildingDetails as $item)
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td class="text-center">{{ $item->building_system_cd }}</td>
                                    <td class="text-center">{{ $item->qtr_no ? $item->qtr_no : 'N/A' }}</td>
                                    <td>{{ $item->bld_qtr_name ? $item->bld_qtr_name : 'N/A' }}</td>
                                    <td>{{ $item->building_type_descr ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $item->is_maintained_by_npwd == 'Y' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->is_maintained_by_npwd == 'Y' ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->building_class_descr ?? 'N/A' }}</td>
                                    <td>{{ $item->owning_dept_name ?? 'N/A' }}</td>
                                    <td>{{ $item->building_catg_descr ?? 'N/A' }}</td>
                                    <td>{{ $item->access_type_descr ?? 'N/A' }}</td>
                                    <td>{{ $item->fenching_type_descr ? $item->fenching_type_descr : 'N/A' }}</td>
                                    <td class="text-end">{{ $item->plinth_area ?? 'N/A' }}</td>
                                    <td class="text-end">{{ $item->plot_area ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $item->construction_year ?? 'N/A' }}</td>
                                    <td class="text-end">{{ $item->construction_cost ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $item->has_water_supply == 'Y' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->has_water_supply == 'Y' ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $item->has_electricity == 'Y' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->has_electricity == 'Y' ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $item->has_sanitary == 'Y' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->has_sanitary == 'Y' ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->occupant_name ?? 'N/A' }}</td>
                                    <td>{{ $item->department_name ?? 'N/A' }}</td>
                                    <td>{{ $item->remark ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        @if ($item->is_partial_data == 'Y' && (session('dataEntry') === 1 || session('dataEntry') === 'Y'))
                                            <a href="{{ route('manage.housing.additional.index', $item->building_system_cd) }}"
                                                class="btn btn-sm btn-outline-primary px-3">Add Details</a>
                                        @else
                                            <span class="text-success text-bold">Completed</span>
                                        @endif
                                    </td>
									<td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            @if ($item->is_partial_data == 'Y')
                                                <span class="text-danger text-bold">Data Incomplete</span>
                                            @else
                                                <a href="{{ route('building.unit.index', $item->building_system_cd) }}"
                                                    class="btn btn-primary btn-xs text-light" title="Building Units">
                                                    <i class="fa fa-cubes"></i> Units
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Government Buildings (Housing)</span>
                        <h2>List of Drafted Government Buildings</h2>
                    </div>
                    <div>
                        <button id="freezeBtn" class="btn btn-sm btn-info text-bold">
                            <i class="fa fa-check-circle mr-1" aria-hidden="true"></i>
                            Send data for finalization
                        </button>
                    </div>
                </header>
                <div class="table-responsive p-3">
                    <table class="table table-striped table-bordered text-xs user_list w-100" id="building_details_table">
                        <thead>
                            <tr>
                                <th class="text-center">Building ID</th>
                                <th class="text-center">Quarter Number</th>
                                <th class="text-center">Building Name</th>
                                <th class="text-center">Building Type</th>
                                <th class="text-center">Maintained by NPWD?</th>
                                <th class="text-center">Residential/Non-Residential</th>
                                <th class="text-center">Asset Owning Department</th>
                                <th class="text-center">Rejection Reason</th>
                                <th class="text-center">Select</th>
                                <th class="text-center">Delete</th>
                                <th class="text-center">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buildingDetails as $item)
                                <tr>
                                    <td class="text-center">{{ $item->building_system_cd }}</td>
                                    <td class="text-center">{{ $item->qtr_no ? $item->qtr_no : 'N/A' }}</td>
                                    <td>{{ $item->bld_qtr_name ? $item->bld_qtr_name : 'N/A' }}</td>
                                    <td>{{ $item->building_type_descr ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $item->is_maintained_by_npwd == 'Y' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->is_maintained_by_npwd == 'Y' ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->building_class_descr ?? 'N/A' }}</td>
                                    <td>{{ $item->owning_dept_name ?? 'N/A' }}</td>
                                    <td>{{ $item->reason_of_rejection ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <input type="checkbox" class="selected-asset"
                                            data-housing-id="{{ $item->building_system_cd }}" />
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('manage.housing.destroy', $item->building_system_cd) }}"
                                            method="post" class="d-inline">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" name="building_id"
                                                value="{{ $item->building_system_cd }}">
                                            <button type="submit" class="border-0 bg-transparent text-danger p-0"
                                                onclick="return confirm('Are you sure you want to delete this building?')">
                                                <i class="fa fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <a class="text-warning edit" href="#" data-toggle="modal"
                                            data-bld-system-cd="{{ $item->building_system_cd }}"
                                            data-bld-is-mntd-by-npwd="{{ $item->is_maintained_by_npwd }}"
                                            data-qtr-no="{{ $item->qtr_no }}" data-bld-name="{{ $item->bld_qtr_name }}"
                                            data-bld-class-cd="{{ $item->building_class_cd }}"
                                            data-bld-type-cd="{{ $item->building_type_cd }}"
                                            data-bld-owning-dept-cd="{{ $item->asset_owning_dept_cd }}"
                                            data-bld-lat="{{ $item->lat }}" data-bld-lon="{{ $item->lon }}"
                                            data-reason-rejection="{{ $item->reason_of_rejection }}"
                                            data-target="#editDraftBuildingModal">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
        </section>

        <!-- The Edit Modal -->
        <div class="modal fade" id="editDraftBuildingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg text-xs">
                <form class="editDraftBuildingForm" id="editDraftBuildingForm" name="editDraftBuildingForm"
                    method="POST" action="#" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="building_id" name="building_id" value="">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title text-bold" id="editUserModalLabel">
                                <i class="fas fa-edit mr-2 text-primary"></i>
                                Edit Building Details
                            </h5>
                            <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body text-dark">
                            <fieldset class="border rounded p-3">
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-bold">Is Maintained by NPWD? <span
                                                class="star text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-3">
                                        <select id="is_mntd_by_npwd" name="is_mntd_by_npwd"
                                            class="form-select form-select-sm">
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-bold">Building Class <span
                                                class="star text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="bld_class_cd" name="bld_class_cd">
                                            <option value="0">Residential</option>
                                            <option value="1">Non Residential</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-bold">Building CD <span
                                                class="star text-danger">*</span></label>
                                        <input class="form-control form-control-sm bg-light" id="bld_sys_cd"
                                            name="bld_sys_cd" value="" readonly />
                                    </div>
                                </div>

                                <div id="buildingDetailsContainer" class="mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-4" id="buildingNameInput">
                                            <label class="form-label text-bold">Building Name/ Qtr No.</label>
                                            <input type="text" name="building_name_or_qtr_no"
                                                id="building_name_or_qtr_no" class="form-control form-control-sm"
                                                required>
                                            <span class="spanHide text-danger text-xs mt-2"
                                                id="building_name_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label text-bold">Building Type <span
                                                    class="star text-danger">*</span></label>
                                            <select class="form-control form-select form-select-sm" id="bld_type_cd"
                                                name="bld_type_cd">
                                                <option value="">Choose one</option>
                                            </select>
                                            <span class="spanHide text-danger text-xs mt-2" id="bld_type_cd_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label text-bold">Owning Department <span
                                                    class="star text-danger">*</span></label>
                                            <select class="form-control form-select form-select-sm text-xs"
                                                id="bld_owning_dept" name="bld_owning_dept">
                                                <option value="">Choose one</option>
                                                @foreach ($departmentDetails as $departmentDetail)
                                                    <option value="{{ $departmentDetail->id }}">
                                                        {{ $departmentDetail->dept_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="spanHide text-danger text-xs mt-2"
                                                id="bld_owning_dept_error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div id="buildingGeolocationContainer" class="mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-bold">Enter Latitude <span
                                                    class="star text-danger">*</span></label>
                                            <input id="bld_asset_geo_location_lat" class="form-control form-control-sm"
                                                name="bld_asset_geo_location_lat" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-bold">Enter Longitude <span
                                                    class="star text-danger">*</span></label>
                                            <input id="bld_asset_geo_location_lng" class="form-control form-control-sm"
                                                name="bld_asset_geo_location_lng" value="">
                                        </div>
                                    </div>
                                </div>

                                <div id="buildingReasonofRejectionContainer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label text-bold">Reason Of Rejection <span
                                                    class="star text-danger">*</span></label>
                                            <input id="txt_reason_of_rejection"
                                                class="form-control form-control-sm bg-light"
                                                name="txt_reason_of_rejection" value="" readonly>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-sm text-bold" onclick="editDraftData()">
                                <i class="fa fa-save mr-1"></i> Update
                            </button>
                            <button type="button" class="btn btn-danger btn-sm text-bold" data-dismiss="modal"
                                data-bs-dismiss="modal">
                                <i class="fa fa-times mr-1"></i> CLOSE
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Modal Edit Building Draft Data --}}
        <x-success-modal />
        <x-warning-modal />
    </main>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <style>
        .closeable-div {
            position: relative;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            width: 100%;
            margin: 10px;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            color: #881818;
        }

        .close-button:hover {
            color: #000;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/building/script.js') }}" defer></script>
    <script>
        $(function() {
            $("#building_details_table").DataTable({}).buttons().container().appendTo(
                '#building_details_table_wrapper .col-md-11:eq(1)');
        });

        $(function() {
            $("#freezed_building_details_table").DataTable();
        });

        $(document).ready(function() {
            $('#editDraftBuildingModal').on('show.bs.modal ', function(event) {

                var button = $(event.relatedTarget);

                var bld_system_cd = button.data('bld-system-cd');
                var is_mntnd_by_npwd = button.data('bld-is-mntd-by-npwd');
                var qtr_no = button.data('qtr-no');
                var bld_name = button.data('bld-name');
                var bld_class_cd = button.data('bld-class-cd');
                var bld_type_code = button.data('bld-type-cd');
                var owning_dept_cd = button.data('bld-owning-dept-cd');
                let lat = button.data('bld-lat');
                let lon = button.data('bld-lon');
                let reasonRejection = button.data('reason-rejection');

                console.log("bld_system_cd:::   " + bld_system_cd);
                console.log("is_mntnd_by_npwd:::   " + is_mntnd_by_npwd);
                console.log("qtr_no:::   " + qtr_no);
                console.log("bld_name:::   " + bld_name);
                console.log("bld_class_cd:::   " + bld_class_cd);
                console.log("bld_type_code:::   " + bld_type_code);
                console.log("owning_dept_cd:::   " + owning_dept_cd);
                console.log("lat:::   " + lat);
                console.log("lon:::   " + lon);

                $('#bld_type_cd').empty();
                if (is_mntnd_by_npw = undefined)
                    $('#is_mntd_by_npwd').val("N");
                else
                    $('#is_mntd_by_npwd').val(is_mntnd_by_npwd);
                $('#bld_asset_geo_location_lat').val(lat);
                $('#bld_asset_geo_location_lng').val(lon);
                $('#bld_sys_cd').val(bld_system_cd);

                $.each(bld_type_master, function(index, option) {
                    if (bld_class_cd == option.building_class_cd)
                        $('#bld_type_cd').append(new Option(option.building_type_descr, option
                            .building_type_cd));
                });
                $('#bld_type_cd').val(bld_type_code);
                $('#bld_owning_dept').val(owning_dept_cd);
                $('#bld_class_cd').val(bld_class_cd);

                if (bld_class_cd == 0)
                    $('#building_name_or_qtr_no').val(qtr_no);
                else
                    $('#building_name_or_qtr_no').val(bld_name);
                $('#txt_reason_of_rejection').val(reasonRejection);
            });

            $('#bld_class_cd').on("change", function() {
                var selectedValue = $('#bld_class_cd').val();

                $('#bld_type_cd').empty();
                $.each(bld_type_master, function(index, option) {
                    if (selectedValue === option.building_class_cd)
                        $('#bld_type_cd').append(new Option(option.building_type_descr, option
                            .building_type_cd));
                });

            });
        });

        const bld_class_master = @json($buildingClasses);
        const bld_type_master = @json($buildingTypes);
    </script>
@endpush
