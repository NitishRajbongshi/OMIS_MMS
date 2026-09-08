@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('dashboard') }}" class="mr-2">Dashboard</a>/ CD Work Details
        </div>
    </div>
    <!-- Main content -->
    <section class="content" id="cdWorkModifySection" name="cdWorkModifySection">
        <div class="container-fluid mt-3">
            <table class="table-responsive table table-bordered table-striped user_list" id="roadDetail">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Road System ID</th>
                    <th class="text-center">Culvert CD</th>
                    <th class="text-center">Culvert No.</th>
                    <th class="text-center">Chainage</th>
                    <th class="text-center">Width</th>
                    <th class="text-center">Height</th>
                    <th class="text-center">Length</th>
                    <th class="text-center">Culvert Type</th>
                    <th class="text-center">Cussion</th>
                    <th class="text-center">Condition</th>
                    <th class="text-center">Discharge</th>
                    <th class="text-center">Year of Construction</th>
                    <th class="text-center">Year of Rehabilitation</th>
                    <th class="text-center">Span</th>
                    <th class="text-center">Carriage Way</th>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($cdworkList as $key)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td style="position: relative">
                                {{ $key->rd_system_id }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_cdwork_cd }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editCulvertNumberModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->culvert_no }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editChainageModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->chainage }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editCdWorkWidthModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->cdwork_width }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editCdWorkHeigthModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->cdwork_height }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editCdWorkLengthModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->cdwork_length }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal"
                                        data-target="#editCdWorkCulvertTypeModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->culvert_type_cd }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editCdWorkCussionModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                @if ($key->cussion == 'Y')
                                    Yes
                                @else
                                    No
                                @endif
                                {{-- {{ $key->cussion }} --}}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal"
                                        data-target="#editCdWorkCulvertConditionModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->rd_condition_descr }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal"
                                        data-target="#editCdWorkCulvertDischargeModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->discharge }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal"
                                        data-target="#editCdWorkCulvertYrConstructionModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->year_of_construction }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal"
                                        data-target="#editCdWorkCulvertYrRehabilitationModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->year_of_rehabilitation }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal"
                                        data-target="#editCdWorkCulvertSpanModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->span }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal"
                                        data-target="#editCdWorkCulvertCarriageWayModal{{ $key->rd_cdwork_cd }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->carriage_way }}
                            </td>
                        </tr>
                        <?php $i++; ?>


                        <!-- edit modal Culvert Number-->
                        <div class="modal fade" id="editCulvertNumberModal{{ $key->rd_cdwork_cd }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_number_{{ $key->rd_system_id }}"
                                        name = "update_culvert_number" method="POST"
                                        action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Number">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="culvert_no">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd" value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->culvert_no }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert No.
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->culvert_no }}</textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea>
                                                    {{-- <select id="new_value_cd"
                                                                class="custom-select form-control"
                                                                name="new_value_cd">
                                                                <option value="" disable selected hidden
                                                                    required>Please
                                                                    Select</option>
                                                                @foreach ($roadOwners as $item)
                                                                    <option value="{{ $item->owner_cd }}">
                                                                        {{ $item->owner_name }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- edit modal Culvert Chainage-->
                        <div class="modal fade" id="editChainageModal{{ $key->rd_cdwork_cd }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_chainage_{{ $key->rd_system_id }}"
                                        name = "update_culvert_number" method="POST"
                                        action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Chainage">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="chainage">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd" value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->chainage }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Chainage
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->chainage }}</textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea>
                                                    {{-- <select id="new_value_cd"
                                                                class="custom-select form-control"
                                                                name="new_value_cd">
                                                                <option value="" disable selected hidden
                                                                    required>Please
                                                                    Select</option>
                                                                @foreach ($roadOwners as $item)
                                                                    <option value="{{ $item->owner_cd }}">
                                                                        {{ $item->owner_name }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- edit modal Culvert Width-->
                        <div class="modal fade" id="editCdWorkWidthModal{{ $key->rd_cdwork_cd }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}" name = "update_culvert_width"
                                        method="POST" action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Width">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="cdwork_width">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd" value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->cdwork_width }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Width
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->cdwork_width }}</textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea>
                                                    {{-- <select id="new_value_cd"
                                                                class="custom-select form-control"
                                                                name="new_value_cd">
                                                                <option value="" disable selected hidden
                                                                    required>Please
                                                                    Select</option>
                                                                @foreach ($roadOwners as $item)
                                                                    <option value="{{ $item->owner_cd }}">
                                                                        {{ $item->owner_name }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- edit modal Culvert Height-->
                        <div class="modal fade" id="editCdWorkHeigthModal{{ $key->rd_cdwork_cd }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}" name = "update_culvert_width"
                                        method="POST" action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Height">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="cdwork_height">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd" value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->cdwork_height }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Height
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->cdwork_height }}</textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea>
                                                    {{-- <select id="new_value_cd"
                                                                class="custom-select form-control"
                                                                name="new_value_cd">
                                                                <option value="" disable selected hidden
                                                                    required>Please
                                                                    Select</option>
                                                                @foreach ($roadOwners as $item)
                                                                    <option value="{{ $item->owner_cd }}">
                                                                        {{ $item->owner_name }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- edit modal Culvert Length-->
                        <div class="modal fade" id="editCdWorkLengthModal{{ $key->rd_cdwork_cd }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}" name = "update_culvert_width"
                                        method="POST" action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Length">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="cdwork_length">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd" value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->cdwork_length }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Length
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> {{ $key->cdwork_length }} </textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea>
                                                    {{-- <select id="new_value_cd"
                                                                class="custom-select form-control"
                                                                name="new_value_cd">
                                                                <option value="" disable selected hidden
                                                                    required>Please
                                                                    Select</option>
                                                                @foreach ($roadOwners as $item)
                                                                    <option value="{{ $item->owner_cd }}">
                                                                        {{ $item->owner_name }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- edit modal Culvert Type-->
                        <div class="modal fade" id="editCdWorkCulvertTypeModal{{ $key->rd_cdwork_cd }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}" name = "update_culvert_width"
                                        method="POST" action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Type">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="culvert_type_cd">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd" value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->culvert_type_cd }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Type
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> {{ $key->cdwoerk_descr }} </textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                    <select id="new_value_cd" class="custom-select form-control"
                                                        name="new_value_cd">
                                                        <option value="" disable selected hidden required>Please
                                                            Select</option>
                                                        @foreach ($culvertTypeMaster as $item)
                                                            <option value="{{ $item->cdwork_cd }}">
                                                                {{ $item->cdwoerk_descr }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- edit modal Culvert Cussion -->
                        <div class="modal fade" id="editCdWorkCussionModal{{ $key->rd_cdwork_cd }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}" name = "update_culvert_width"
                                        method="POST" action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Cussion">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="cussion">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd" value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->cussion }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Cussion
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> 
                                                    @if ($key->cussion == 'Y')
Yes
@else
No
@endif
                                                </textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                    <select id="new_value_cd" class="custom-select form-control"
                                                        name="new_value_cd">
                                                        <option value="" disable selected hidden required>Please
                                                            Select</option>

                                                        <option value="Y">
                                                            Yes</option>
                                                        <option value="N">
                                                            No</option>

                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- edit modal Culvert Condition-->
                        <div class="modal fade" id="editCdWorkCulvertConditionModal{{ $key->rd_cdwork_cd }}"
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}" name = "update_culvert_width"
                                        method="POST" action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Condition">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="cdwork_condition">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd" value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->cdwork_condition }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Condition
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> {{ $key->rd_condition_descr }} </textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                    <select id="new_value_cd" class="custom-select form-control"
                                                        name="new_value_cd">
                                                        <option value="" disable selected hidden required>Please
                                                            Select</option>
                                                        @foreach ($conditionMaster as $item)
                                                            <option value="{{ $item->rd_condition_cd }}">
                                                                {{ $item->rd_condition_descr }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->


                        <!-- edit modal Culvert Discharge-->
                        <div class="modal fade" id="editCdWorkCulvertDischargeModal{{ $key->rd_cdwork_cd }}"
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}" name = "update_culvert_width"
                                        method="POST" action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Discharge">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="discharge">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd" value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->discharge }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Discharge
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> {{ $key->discharge }} </textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea>
                                                    {{-- <select id="new_value_cd"
                                                                class="custom-select form-control"
                                                                name="new_value_cd">
                                                                <option value="" disable selected hidden
                                                                    required>Please
                                                                    Select</option>
                                                                @foreach ($conditionMaster as $item)
                                                                    <option value="{{ $item->rd_condition_cd }}">
                                                                        {{ $item->rd_condition_descr }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i> Send
                                                Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- edit modal Culvert Year of Construction-->
                        <div class="modal fade" id="editCdWorkCulvertYrConstructionModal{{ $key->rd_cdwork_cd }}"
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}" name = "update_culvert_width"
                                        method="POST" action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Year Of Construction">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="year_of_construction">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd" value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd"
                                            value="{{ $key->year_of_construction }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Year of Construction
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> {{ $key->year_of_construction }} </textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea>
                                                    {{-- <select id="new_value_cd"
                                                                class="custom-select form-control"
                                                                name="new_value_cd">
                                                                <option value="" disable selected hidden
                                                                    required>Please
                                                                    Select</option>
                                                                @foreach ($conditionMaster as $item)
                                                                    <option value="{{ $item->rd_condition_cd }}">
                                                                        {{ $item->rd_condition_descr }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                Send Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- edit modal Culvert Year of Rehabilitation-->
                        <div class="modal fade" id="editCdWorkCulvertYrRehabilitationModal{{ $key->rd_cdwork_cd }}"
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}"
                                        name = "update_culvert_width" method="POST"
                                        action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Year Of Rehabilitation">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="year_of_rehabilitation">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd"
                                            value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd"
                                            value="{{ $key->year_of_rehabilitation }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Year of Rehabilitation
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> {{ $key->year_of_rehabilitation }} </textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea>
                                                    {{-- <select id="new_value_cd"
                                                                class="custom-select form-control"
                                                                name="new_value_cd">
                                                                <option value="" disable selected hidden
                                                                    required>Please
                                                                    Select</option>
                                                                @foreach ($conditionMaster as $item)
                                                                    <option value="{{ $item->rd_condition_cd }}">
                                                                        {{ $item->rd_condition_descr }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                Send Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->


                        <!-- edit modal Culvert Span-->
                        <div class="modal fade" id="editCdWorkCulvertSpanModal{{ $key->rd_cdwork_cd }}"
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}"
                                        name = "update_culvert_width" method="POST"
                                        action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Span">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="span">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd"
                                            value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->span }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Span
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> {{ $key->span }} </textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea>
                                                    {{-- <select id="new_value_cd"
                                                                class="custom-select form-control"
                                                                name="new_value_cd">
                                                                <option value="" disable selected hidden
                                                                    required>Please
                                                                    Select</option>
                                                                @foreach ($conditionMaster as $item)
                                                                    <option value="{{ $item->rd_condition_cd }}">
                                                                        {{ $item->rd_condition_descr }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                Send Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->

                        <!-- edit modal Culvert Carriageway-->
                        <div class="modal fade" id="editCdWorkCulvertCarriageWayModal{{ $key->rd_cdwork_cd }}"
                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="frm_cdwork_request_modification"
                                        id="update_culvert_width_{{ $key->rd_system_id }}"
                                        name = "update_culvert_width" method="POST"
                                        action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" id="model_id" name="model_id"
                                            value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="req_table_name" value="asset_road_cdwork_details">
                                        <input type="hidden" name="req_asset_name" value="CD Works">
                                        <input type="hidden" name="req_is_sub_asset" value="Y">
                                        <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                            value="{{ $key->rd_cdwork_cd }}">
                                        <input type="hidden" name="user_field_name" id="user_field_name"
                                            value="Culvert Carriageway">
                                        <input type="hidden" name="table_field_name" id="table_field_name"
                                            value="carriage_way">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="rd_cdwork_cd"
                                            value="{{ $key->rd_cdwork_cd }}" />
                                        <input type="hidden" name="old_value_cd" value="{{ $key->carriage_way }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                Carriage way
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Reason of
                                                        Update
                                                        <span class="text-danger text-bold">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <textarea type="text" name="modification_req_reason" id="modification_req_reason" rows="3"
                                                        class="form-control" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 mb-1">
                                                    <label for="name" class="col-form-label">Update
                                                        From</label>
                                                </div>
                                                <div class="col-md-4 mb-1">
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> {{ $key->carriage_way }} </textarea>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <label for="name" class="col-form-label">To</label>
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea>
                                                    {{-- <select id="new_value_cd"
                                                                class="custom-select form-control"
                                                                name="new_value_cd">
                                                                <option value="" disable selected hidden
                                                                    required>Please
                                                                    Select</option>
                                                                @foreach ($conditionMaster as $item)
                                                                    <option value="{{ $item->rd_condition_cd }}">
                                                                        {{ $item->rd_condition_descr }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                Send Request</button>
                                            <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                style="border-radius:5px" data-dismiss="modal"><i
                                                    class="fa-solid fa-close"></i> Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end modal -->
                    @endforeach
                </tbody>
            </table>
            <table class="table-responsive table table-bordered table-striped user_list" id="roadDetail">
                <tr>
                    <th class="text-center">
                        <button id="btnCancel" name ="btnCancel" type="button" class="btn btn-secondary"
                            style="border-radius:5px" onclick="history.back();"><i class="fa-solid fa-close"></i>
                            Back</button>
                    </th>
                </tr>
            </table>

        </div>
    </section>
@endsection
@push('styles')
    <style>
        .iconSpan {
            bottom: 0;
            right: 0;
            position: absolute;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $("#roadDetail").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetail_wrapper .col-md-11:eq(1)');
        });
        $(function() {
            $("#roadDetailForCulvert").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetailForCulvert_wrapper .col-md-4:eq(1)');
        });
        $('.modalClose').click(function() {
            //location.reload();
            $('.modal-body :input:not([readonly]), .modal-body textarea:not([readonly])').val('');
        });

        $('#modalDate').on('change', function() {
            var selectedDate = $(this).val();
            var dateObj = new Date(selectedDate);
            var year = dateObj.getFullYear();
            var month = ('0' + (dateObj.getMonth() + 1)).slice(-2);
            var day = ('0' + dateObj.getDate()).slice(-2);
            var formattedDate = year + '-' + month + '-' + day;
            $(this).val(formattedDate);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('form.frm_cdwork_request_modification').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var asset_cd = $("#req_sub_asset_cd").val();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function(response) {
                        if (response.message == 'success') {
                            var field_name = $("#user_field_name").val();
                            $("#editCulvertNumberModal" + asset_cd).modal().hide();

                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: response.field_name +
                                    " Update Request Sent Successfully!!!!\nRequest Id: " +
                                    response.req_id,
                                showConfirmButton: true,
                                timer: 5000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'error',
                                text: "Request Could Not Sent Successfully!!!!\nPlease Try Again ...",
                                showConfirmButton: true,
                                timer: 5000
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'error',
                            title: 'error',
                            text: "Culvert Number Update Request Could Not Be Sent Successfully!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });



            // $('form.update_chainage_form').on("submit", function(e) {
            //     e.preventDefault();
            //     var form = $(this);
            //     var formData = form.serialize();
            //     var asset_cd = $("#req_sub_asset_cd").val();
            //     $.ajax({
            //         type: "POST",
            //         url: form.attr('action'),
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: formData,
            //         cache: false,
            //         success: function(response) {
            //             if (response.message == 'success') {
            //                 alert("Culvert Number Update Request Sent Successfully!!!!\nRequest Id: " +
            //                     response.req_id);
            //                 $("#editCulvertNumberModal" + asset_cd).modal().hide();

            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'success',
            //                     text: "Culvert Number Update Request Sent Successfully!!!!\nRequest Id: " +
            //                         response.req_id,
            //                     showConfirmButton: true,
            //                     timer: 5000
            //                 }).then(() => {
            //                     location.reload();
            //                 });
            //             } else {
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'error',
            //                     text: "Request Could Not Sent Successfully!!!!\nPlease Try Again ...",
            //                     showConfirmButton: true,
            //                     timer: 5000
            //                 }).then(() => {
            //                     location.reload();
            //                 });
            //             }
            //         },
            //         error: function(response) {
            //             console.log(response);
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'error',
            //                 text: "Culvert Number Update Request Could Not Be Sent Successfully!!!!\nPlease Try Again...",
            //                 showConfirmButton: true,
            //                 timer: 5000
            //             }).then(() => {
            //                 location.reload();
            //             });
            //         }
            //     });
            // });
        });
    </script>
@endpush
