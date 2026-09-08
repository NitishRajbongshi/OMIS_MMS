@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="col-md-3 col-sm-6 mb-3">
            @if ($sub_asset_name == 'CDWORK')
                <a href="#" class="mr-2">Dashboard</a>/ CD Work Details
            @endif
            @if ($sub_asset_name == 'BRIDGE')
                <a href="#" class="mr-2">Dashboard</a>/ Bridge Details
            @endif
        </div>
    </div>
    <!-- Main content -->
    <section class="content" id="cdWorkModifySection" name="cdWorkModifySection">
        <div class="container-fluid mt-3">
            @if ($sub_asset_name == 'CDWORK')
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
                        @foreach ($subAssetList as $key)
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
                                        <a data-toggle="modal"
                                            data-target="#editCulvertNumberModal{{ $key->rd_cdwork_cd }}"
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
                                        <a data-toggle="modal"
                                            data-target="#editCdWorkWidthModal{{ $key->rd_cdwork_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->cdwork_width }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editCdWorkHeigthModal{{ $key->rd_cdwork_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->cdwork_height }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editCdWorkLengthModal{{ $key->rd_cdwork_cd }}"
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
                                        <a data-toggle="modal"
                                            data-target="#editCdWorkCussionModal{{ $key->rd_cdwork_cd }}"
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
                            <div class="modal fade" id="editCulvertNumberModal{{ $key->rd_cdwork_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_number_{{ $key->rd_system_id }}"
                                            name = "update_culvert_number" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Number">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="culvert_no">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->culvert_no }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Culvert
                                                    No.
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
                                                    style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                    Send
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
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_chainage_{{ $key->rd_system_id }}"
                                            name = "update_culvert_number" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Chainage">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="chainage">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
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
                                                    style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                    Send
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
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Width">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="cdwork_width">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->cdwork_width }}">
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
                                                    style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                    Send
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
                            <div class="modal fade" id="editCdWorkHeigthModal{{ $key->rd_cdwork_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Height">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="cdwork_height">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->cdwork_height }}">
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
                                                    style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                    Send
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
                            <div class="modal fade" id="editCdWorkLengthModal{{ $key->rd_cdwork_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Length">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="cdwork_length">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->cdwork_length }}">
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
                                                    style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                    Send
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
                            <div class="modal fade" id="editCdWorkCulvertTypeModal{{ $key->rd_cdwork_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Type">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="culvert_type_cd">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->culvert_type_cd }}">
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
                                                            <option value="" disable selected hidden required>
                                                                Please
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
                                                    style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                    Send
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
                            <div class="modal fade" id="editCdWorkCussionModal{{ $key->rd_cdwork_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Cussion">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="cussion">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
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
                                                        @php
                                                            if ($key->cussion == 'Y') {
                                                                $old_val_dscr = 'Yes';
                                                            } else {
                                                                $old_val_dscr = 'No';
                                                            }
                                                        @endphp
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> 
                                                                {{ $old_val_dscr }}
                                                            </textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
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
                                                    style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                    Send
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
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Condition">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="cdwork_condition">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->cdwork_condition }}">
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
                                                            <option value="" disable selected hidden required>
                                                                Please
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
                                                    style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                    Send
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
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Discharge">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="discharge">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->discharge }}">
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
                                                    style="border-radius:5px"><i class="fa-solid fa-floppy-disk"></i>
                                                    Send
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
                            <div class="modal fade"
                                id="editCdWorkCulvertYrConstructionModal{{ $key->rd_cdwork_cd }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Year Of Construction">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="year_of_construction">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
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
                                                    style="border-radius:5px"><i
                                                        class="fa-solid fa-floppy-disk"></i> Send
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

                            <!-- edit modal Culvert Year of Rehabilitation-->
                            <div class="modal fade"
                                id="editCdWorkCulvertYrRehabilitationModal{{ $key->rd_cdwork_cd }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Year Of Rehabilitation">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="year_of_rehabilitation">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->year_of_rehabilitation }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update
                                                    Culvert
                                                    Year of Rehabilitation
                                                </h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of
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
                                                    style="border-radius:5px"><i
                                                        class="fa-solid fa-floppy-disk"></i> Send
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


                            <!-- edit modal Culvert Span-->
                            <div class="modal fade" id="editCdWorkCulvertSpanModal{{ $key->rd_cdwork_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Span">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="span">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->span }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update
                                                    Culvert
                                                    Span
                                                </h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of
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
                                                    style="border-radius:5px"><i
                                                        class="fa-solid fa-floppy-disk"></i> Send
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

                            <!-- edit modal Culvert Carriageway-->
                            <div class="modal fade" id="editCdWorkCulvertCarriageWayModal{{ $key->rd_cdwork_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="update_culvert_width_{{ $key->rd_system_id }}"
                                            name = "update_culvert_width" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editCulvertNumberModal{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_cdwork_details">
                                            <input type="hidden" name="req_asset_name" value="CD Works">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_cdwork_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Culvert Carriageway">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="carriage_way">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_cdwork_cd"
                                                value="{{ $key->rd_cdwork_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->carriage_way }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update
                                                    Culvert
                                                    Carriage way
                                                </h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of
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
                                                    style="border-radius:5px"><i
                                                        class="fa-solid fa-floppy-disk"></i> Send
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
                        @endforeach
                    </tbody>
                </table>
            @endif


            @if ($sub_asset_name == 'BRIDGE')
                <table class="table-responsive table table-bordered table-striped user_list" id="roadDetail">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">SlNo.</th>
                        <th class="text-center">Road System ID</th>
                        <th class="text-center">Bridge CD</th>
                        <th class="text-center">Bridge Type</th>
                        <th class="text-center">Bridge Name</th>
                        <th class="text-center">Chainage</th>
                        <th class="text-center">Bridge Lane</th>
                        <th class="text-center">River Name</th>
                        <th class="text-center">Length of Bridge</th>
                        <th class="text-center">Construction Type</th>
                        <th class="text-center">Year of Construction</th>
                        <th class="text-center">Year of Rehabilitation</th>
                        <th class="text-center">No of Span</th>
                        <th class="text-center">Length of Span</th>
                        <th class="text-center">Kerb Distance</th>
                        <th class="text-center">Foundation Type</th>
                        <th class="text-center">No of Piers</th>
                        <th class="text-center">Size of Piers</th>
                        <th class="text-center">Abutment Type</th>
                        <th class="text-center">Superstructure Type</th>
                        <th class="text-center">Hand Rail Type</th>
                        <th class="text-center">Deck Type</th>
                        <th class="text-center">Carriage Width</th>
                        <th class="text-center">Guard Stone</th>
                        <th class="text-center">Load Capacity</th>
                        <th class="text-center">Signs</th>
                        <th class="text-center">Lowest Water Level</th>
                        <th class="text-center">Highest Flood Level</th>
                        <th class="text-center">LBL</th>
                        <th class="text-center">Source Depth</th>
                        <th class="text-center">Discharge</th>
                        <th class="text-center">Deck Level</th>
                        <th class="text-center">footh_path</th>
                        <th class="text-center">bearings</th>
                        <th class="text-center">Expansion Joint Type</th>
                        <th class="text-center">Bridge Condition</th>
                        <th class="text-center">Last Inspection Date</th>
                        <th class="text-center">Next Schedule Date</th>
                        <th class="text-center">Bridge Number</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Kerb Width</th>
                        <th class="text-center">Minimum Water Level</th>
                        <th class="text-center">Pile Diameter</th>
                        <th class="text-center">Plie Length</th>
                        <th class="text-center">Pile Type</th>
                        <th class="text-center">Well Type</th>
                        <th class="text-center">Open Foundation Size</th>
                        <th class="text-center">Open Foundation Depth</th>
                        <th class="text-center">Bridge Width</th>
                        <th class="text-center">Has Safety Apron</th>
                        <th class="text-center">Apron Type</th>
                        <th class="text-center">Apron Width</th>
                        <th class="text-center">Kerb Height</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($subAssetList as $key)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td style="position: relative">
                                    {{ $key->rd_system_id }}
                                </td>
                                <td style="position: relative">
                                    {{ $key->rd_bridge_cd }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeTypeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->bridge_type_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeNameModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->bridge_name }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeChainageModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->chainage }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeLaneModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->bridge_lane }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeRiverNameModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->river_name }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeLengthModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->cd_bridge_length }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeConstructionTypeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->construction_type_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeYrOfConstructionModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->year_of_construction }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeYrOfRehabilitationModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->year_of_rehabilitation }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeNoOfSpanModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->no_of_span }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeSpanLengthModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->span_length }}
                                </td>


                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeKerbDistanceModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->kerb_distance }}
                                </td>

                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeFoundationTypeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->foundation_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeNoOfPiersModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->no_of_piers }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgePierSizeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->pier_size }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeAbutmentModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->abutment_type_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeSuperStructureModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->st_type_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeHandRailTypeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->hand_rail_type_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeDeckTypeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->deck_type_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeCarriageWidthModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->carriage_width }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeGuardStoneModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->guard_stone }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeLoadCapacityModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->load_capacity }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeSignsModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->signs }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeLWLModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->lowest_water_level }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeHFLModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->highest_flood_level }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeLBLModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->rfl }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeSourceDepthModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->source_depth }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeDischargeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->discharge }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeDeckLevelModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->deck_level }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeFoothPathModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->footh_path }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeBearingsModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->bearings }}
                                </td>

                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeExpansionJointModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->expn_joint_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeConditionModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->rd_condition_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeLastInspectionDateModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->date_of_last_inspection }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeNextScheduleInspectionModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->next_schedule_inspection_date }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeNumberModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->bridge_number }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeLocationModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->bridge_location }}
                                </td>

                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeKerbWidthModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->kerb_width }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeMWLModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->minimum_water_level }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgePileDiameterModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->pile_diameter }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgePileLengthModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->pile_length }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgePlieTypeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->pile_type_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeWellTypeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->well_type_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeOpenFoundationSizeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->open_foundation_size }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeDepthOpenFoundationModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->depth_open_foundation_size }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeBridgeWidthModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->bridge_width }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeHasSaftyApronModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->has_safety_apron }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeSafetyApronTypeModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->apron_type_descr }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeApronWidthModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->apron_width }}
                                </td>
                                <td style="position: relative">
                                    <span class="iconSpan">
                                        <a data-toggle="modal"
                                            data-target="#editBridgeKerbHeightModal{{ $key->rd_bridge_cd }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br>
                                    {{ $key->kerb_height }}
                                </td>
                            </tr>
                            <?php $i++; ?>

                            <!-- edit modal Bridge editBridgeKerbHeightModal-->
                            <div class="modal fade" id="editBridgeKerbHeightModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeKerbHeightModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Kerb Height">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="kerb_height">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->kerb_height }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Kerb Height</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->kerb_height }}</textarea>
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
                                                                    @foreach ($wellTypeMaster as $item)
                                                                        <option value="{{ $item->well_type_cd }}">
                                                                            {{ $item->well_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge editBridgeApronWidthModal-->
                            <div class="modal fade" id="editBridgeApronWidthModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeApronWidthModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Width of Safety Apron">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="apron_width">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->apron_width }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Width of Safety Apron</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->apron_width }}</textarea>
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
                                                                    @foreach ($wellTypeMaster as $item)
                                                                        <option value="{{ $item->well_type_cd }}">
                                                                            {{ $item->well_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge editBridgeSafetyApronTypeModal-->
                            <div class="modal fade" id="editBridgeSafetyApronTypeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeSafetyApronTypeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Type of Safety Apron">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="safety_apron_type">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->safety_apron_type }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Type of Safety Apron</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->apron_type_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}

                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($wellTypeMaster as $item)
                                                                <option value="{{ $item->well_type_cd }}">
                                                                    {{ $item->well_type_descr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge editBridgeHasSaftyApronModal-->
                            <div class="modal fade" id="editBridgeHasSaftyApronModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeHasSaftyApronModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Has Safety Apron">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="has_safety_apron">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->has_safety_apron }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Has Safety Apron</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->has_safety_apron }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            <option value="Y">Yes
                                                                Select</option>
                                                            <option value="N">No</option>
                                                        </select>
                                                        {{-- <select id="new_value_cd"
                                                                    class="custom-select form-control"
                                                                    name="new_value_cd">
                                                                    <option value="" disable selected hidden
                                                                        required>Please
                                                                        Select</option>
                                                                    @foreach ($wellTypeMaster as $item)
                                                                        <option value="{{ $item->well_type_cd }}">
                                                                            {{ $item->well_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge editBridgeBridgeWidthModal-->
                            <div class="modal fade" id="editBridgeBridgeWidthModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeBridgeWidthModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Width">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="bridge_width">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->bridge_width }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Width</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->bridge_width }}</textarea>
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
                                                                    @foreach ($wellTypeMaster as $item)
                                                                        <option value="{{ $item->well_type_cd }}">
                                                                            {{ $item->well_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge editBridgeDepthOpenFoundationModal-->
                            <div class="modal fade"
                                id="editBridgeDepthOpenFoundationModal{{ $key->rd_bridge_cd }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeDepthOpenFoundationModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Open Foundation Depth">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="depth_open_foundation_size">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->depth_open_foundation_size }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Open Foundation Depth</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->depth_open_foundation_size }}</textarea>
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
                                                                @foreach ($wellTypeMaster as $item)
                                                                    <option value="{{ $item->well_type_cd }}">
                                                                        {{ $item->well_type_descr }}</option>
                                                                @endforeach
                                                            </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->


                            <!-- edit modal Bridge editBridgeOpenFoundationSizeModal-->
                            <div class="modal fade" id="editBridgeOpenFoundationSizeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeOpenFoundationSizeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Open Foundation Size">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="open_foundation_size">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->open_foundation_size }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Open Foundation Size</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->open_foundation_size }}</textarea>
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
                                                                    @foreach ($wellTypeMaster as $item)
                                                                        <option value="{{ $item->well_type_cd }}">
                                                                            {{ $item->well_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge editBridgeWellTypeModal-->
                            <div class="modal fade" id="editBridgeWellTypeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeWellTypeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Well Type">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="well_type">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->well_type }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Well Type</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->well_type_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($wellTypeMaster as $item)
                                                                <option value="{{ $item->well_type_cd }}">
                                                                    {{ $item->well_type_descr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge editBridgePlieTypeModal-->
                            <div class="modal fade" id="editBridgePlieTypeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgePlieTypeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Pile Type">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="pile_type">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->pile_type }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Plie Type</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->pile_type_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($pileTypeMaster as $item)
                                                                <option value="{{ $item->pile_type_cd }}">
                                                                    {{ $item->pile_type_descr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->


                            <!-- edit modal Bridge editBridgePileLengthModal-->
                            <div class="modal fade" id="editBridgePileLengthModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgePileLengthModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Pile Length">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="pile_length">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->pile_length }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Plie Length</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->pile_length }}</textarea>
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
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge editBridgePileDiameterModal-->
                            <div class="modal fade" id="editBridgePileDiameterModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgePileDiameterModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Pile Diameter">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="pile_diameter">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->pile_diameter }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Plie Diameter</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->pile_diameter }}</textarea>
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
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge MWL-->
                            <div class="modal fade" id="editBridgeMWLModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeMWLModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Minimum Water Level">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="minimum_water_level">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->minimum_water_level }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Minimum Water Level</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->minimum_water_level }}</textarea>
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
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Kerb Width-->
                            <div class="modal fade" id="editBridgeKerbWidthModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeKerbWidthModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Kerb Width">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="kerb_width">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->kerb_width }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Kerb Width</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->kerb_width }}</textarea>
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
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Location-->
                            <div class="modal fade" id="editBridgeLocationModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeLocationModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Location">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="bridge_location">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->bridge_location }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Location</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->bridge_location }}</textarea>
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
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Number-->
                            <div class="modal fade" id="editBridgeNumberModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeNumberModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Number">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="bridge_number">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->bridge_number }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Number</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->bridge_number }}</textarea>
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
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Last Inspection Date-->
                            <div class="modal fade" id="editBridgeLastInspectionDateModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeLastInspectionDateModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Last Inspection Date">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="date_of_last_inspection">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->date_of_last_inspection }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Last Inspection Date</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->date_of_last_inspection }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <input type="date" value="" name="new_value_cd"
                                                            id="new_value_cd" class="form-control" required>
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
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Next Inspection Date-->
                            <div class="modal fade"
                                id="editBridgeNextScheduleInspectionModal{{ $key->rd_bridge_cd }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeNextScheduleInspectionModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Next Inspection Date">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="next_schedule_inspection_date">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->next_schedule_inspection_date }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Next Inspection Date</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->next_schedule_inspection_date }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        <input type="date" value="" name="new_value_cd"
                                                            id="new_value_cd" class="form-control" required>
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
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
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Condition-->
                            <div class="modal fade" id="editBridgeConditionModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeConditionModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Condition">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="bridge_condition">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->bridge_condition }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Condition</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->rd_condition_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
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
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Exapnsion Joint-->
                            <div class="modal fade" id="editBridgeExpansionJointModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeExpansionJointModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Expansion Joint">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="expansion_join_cd">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->expansion_join_cd }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Exapnsion Joint</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->expn_joint_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($expnJointTypeMaster as $item)
                                                                <option value="{{ $item->expn_joint_cd }}">
                                                                    {{ $item->expn_joint_descr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bearing-->
                            <div class="modal fade" id="editBridgeBearingsModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeBearingsModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bearings">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="bearings">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->bearings }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Bearing</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->bearings }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Footh Path-->
                            <div class="modal fade" id="editBridgeFoothPathModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeFoothPathModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Footh Path">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="footh_path">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->footh_path }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Foot Path</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->footh_path }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Deck Level-->
                            <div class="modal fade" id="editBridgeDeckLevelModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeDeckLevelModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Deck Level">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="deck_level">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->deck_level }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Deck Level</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->deck_level }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Discharge-->
                            <div class="modal fade" id="editBridgeDischargeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeDischargeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Discharge">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="discharge">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->discharge }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Discharge</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->discharge }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Source Depth-->
                            <div class="modal fade" id="editBridgeSourceDepthModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeSourceDepthModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Source Depth">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="source_depth">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->source_depth }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Source Depth</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->source_depth }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal LBL-->
                            <div class="modal fade" id="editBridgeLBLModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeLBLModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="LBL">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="rfl">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->rfl }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    LBL</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->rfl }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal HFL-->
                            <div class="modal fade" id="editBridgeHFLModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeHFLModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Highest Flood Level">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="highest_flood_level">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->highest_flood_level }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Highest Flood Level</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->highest_flood_level }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal LWL-->
                            <div class="modal fade" id="editBridgeLWLModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeLWLModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Lowest Water Level">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="lowest_water_level">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->lowest_water_level }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Lowest Water Level</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->lowest_water_level }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Signs-->
                            <div class="modal fade" id="editBridgeSignsModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeSignsModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Signs">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="signs">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->signs }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Signs</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->signs }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            <option value="Y">Yes</option> Select
                                                            </option>
                                                            <option value="N">No</option>
                                                        </select>
                                                        {{-- <select id="new_value_cd"
                                                                    class="custom-select form-control"
                                                                    name="new_value_cd">
                                                                    <option value="" disable selected hidden
                                                                        required>Please
                                                                        Select</option>
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Load capacity-->
                            <div class="modal fade" id="editBridgeLoadCapacityModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeLoadCapacityModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Load Capacity">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="load_capacity">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->load_capacity }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Load
                                                    Capacity</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->load_capacity }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->


                            <!-- edit modal Guard Stone-->
                            <div class="modal fade" id="editBridgeGuardStoneModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeGuardStoneModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Guard Stone">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="guard_stone">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->guard_stone }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Guard
                                                    Stone</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->guard_stone }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Carriage Width-->
                            <div class="modal fade" id="editBridgeCarriageWidthModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeCarriageWidthModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Carriage Width">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="carriage_width">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->carriage_width }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update
                                                    Carriage Width</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->carriage_width }}</textarea>
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
                                                                    @foreach ($superStuctureTypeMaster as $item)
                                                                        <option value="{{ $item->st_type_cd }}">
                                                                            {{ $item->st_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Deck Type-->
                            <div class="modal fade" id="editBridgeDeckTypeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeDeckTypeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Deck Type">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="deck_type_cd">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->deck_type_cd }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Deck
                                                    Type</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->deck_type_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($deckTypeMaster as $item)
                                                                <option value="{{ $item->deck_type_cd }}">
                                                                    {{ $item->deck_type_descr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Hand Rail Type-->
                            <div class="modal fade" id="editBridgeHandRailTypeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeHandRailTypeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Hand Rail Type">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="handrail_type_cd">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->handrail_type_cd }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Hand
                                                    Rail Type</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->hand_rail_type_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($handRailTypeMaster as $item)
                                                                <option value="{{ $item->hand_rail_type_cd }}">
                                                                    {{ $item->hand_rail_type_descr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Super Structure Type-->
                            <div class="modal fade" id="editBridgeSuperStructureModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeSuperStructureModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Super Structure Type">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="super_structure_type_cd">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->super_structure_type_cd }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Super
                                                    Structure Type</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->st_type_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($superStuctureTypeMaster as $item)
                                                                <option value="{{ $item->st_type_cd }}">
                                                                    {{ $item->st_type_descr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Abutment Type-->
                            <div class="modal fade" id="editBridgeAbutmentModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeAbutmentModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Abutment Type">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="abutment_type_cd">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->abutment_type_cd }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update
                                                    Abutment Type</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->abutment_type_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($abutmentTypeMaster as $item)
                                                                <option value="{{ $item->abutment_type_cd }}">
                                                                    {{ $item->abutment_type_descr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Bridge Size of Piers-->
                            <div class="modal fade" id="editBridgePierSizeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgePierSizeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Size of Piers">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="pier_size">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->pier_size }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Size
                                                    of Piers</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->pier_size }}</textarea>
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
                                                                    @foreach ($xxx as $item)
                                                                        <option value="{{ $item->xxx }}">
                                                                            {{ $item->xxx }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Bridge No of Piers-->
                            <div class="modal fade" id="editBridgeNoOfPiersModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeNoOfPiersModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="No of Piers">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="no_of_piers">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->no_of_piers }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update No. of
                                                    Piers</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->no_of_piers }}</textarea>
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
                                                                    @foreach ($xxx as $item)
                                                                        <option value="{{ $item->xxx }}">
                                                                            {{ $item->xxx }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Bridge Foundation Type-->
                            <div class="modal fade" id="editBridgeFoundationTypeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeFoundationTypeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Foundation Type">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="foundation_type_cd">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->foundation_type_cd }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Foundation Type</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly> {{ $key->foundation_descr }} </textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($foundationTypesMaster as $item)
                                                                <option value="{{ $item->foundation_cd }}">
                                                                    {{ $item->foundation_descr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Kerb Distance-->
                            <div class="modal fade" id="editBridgeKerbDistanceModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeKerbDistanceModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Kerb Distance">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="kerb_distance">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->kerb_distance }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Kerb
                                                    Distance</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->kerb_distance }}</textarea>
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
                                                                    @foreach ($constTypeMaster as $item)
                                                                        <option value="{{ $item->xxxx }}">
                                                                            {{ $item->xxxxx }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Length of Span-->
                            <div class="modal fade" id="editBridgeSpanLengthModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeSpanLengthModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Span Length">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="span_length">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->span_length }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Length
                                                    of Span</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->span_length }}</textarea>
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
                                                                    @foreach ($constTypeMaster as $item)
                                                                        <option value="{{ $item->xxxx }}">
                                                                            {{ $item->xxxxx }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Bridge No of Span-->
                            <div class="modal fade" id="editBridgeNoOfSpanModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeNoOfSpanModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Year of Rehabilitation">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="no_of_span">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->no_of_span }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    No of Span</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->no_of_span }}</textarea>
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
                                                                    @foreach ($constTypeMaster as $item)
                                                                        <option value="{{ $item->xxxx }}">
                                                                            {{ $item->xxxxx }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Yr of Rehabilitation-->
                            <div class="modal fade" id="editBridgeYrOfRehabilitationModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeYrOfRehabilitationModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Year of Rehabilitation">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="year_of_rehabilitation">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->year_of_rehabilitation }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Rehabilitation Year</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->year_of_rehabilitation }}</textarea>
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
                                                                    @foreach ($constTypeMaster as $item)
                                                                        <option value="{{ $item->xxxx }}">
                                                                            {{ $item->xxxxx }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Yr of Construction-->
                            <div class="modal fade" id="editBridgeYrOfConstructionModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeYrOfConstructionModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Year of Construction">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="year_of_construction">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->year_of_construction }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Construction Year</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->year_of_construction }}</textarea>
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
                                                                    @foreach ($constTypeMaster as $item)
                                                                        <option
                                                                            value="{{ $item->xxxxx }}">
                                                                            {{ $item->xxxx }}
                                                                        </option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Construction Type-->
                            <div class="modal fade" id="editBridgeConstructionTypeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeConstructionTypeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Construction Type">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="construction_type_cd">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->construction_type_cd }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Construction Type</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->construction_type_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($constTypeMaster as $item)
                                                                <option value="{{ $item->construction_type_cd }}">
                                                                    {{ $item->construction_type_descr }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Bridge Length-->
                            <div class="modal fade" id="editBridgeLengthModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_bridge_cd }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeLengthModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Length">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="cd_bridge_length">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->cd_bridge_length }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update River
                                                    Name</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update <span
                                                                class="text-danger text-bold">*</span></label>
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->cd_bridge_length }}</textarea>
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
                                                                    @foreach ($bridgeTypeMaster as $item)
                                                                        <option value="{{ $item->bridge_type_cd }}">
                                                                            {{ $item->bridge_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            <!-- edit modal Rivaer Name-->
                            <div class="modal fade" id="editBridgeRiverNameModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_system_id }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeRiverNameModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Lane">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="river_name">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->river_name }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update River
                                                    Name</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->river_name }}</textarea>
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
                                                                        @foreach ($bridgeTypeMaster as $item)
                                                                            <option value="{{ $item->bridge_type_cd }}">
                                                                                {{ $item->bridge_type_descr }}</option>
                                                                        @endforeach
                                                                    </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Bridge Lane-->
                            <div class="modal fade" id="editBridgeLaneModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_system_id }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeLaneModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Lane">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="bridge_lane">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->bridge_lane }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update Bridge
                                                    Lane</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of Update
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->bridge_lane }}</textarea>
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
                                                                    @foreach ($bridgeTypeMaster as $item)
                                                                        <option value="{{ $item->bridge_type_cd }}">
                                                                            {{ $item->bridge_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px">
                                                    <i class="fa-solid fa-floppy-disk"></i> Send Request
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm modalClose"
                                                    style="border-radius:5px" data-dismiss="modal">
                                                    <i class="fa-solid fa-close"></i> Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- edit modal Bridge Chainage-->
                            <div class="modal fade" id="editBridgeChainageModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_system_id }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeChainageModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Chainage">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="chainage">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->chainage }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update
                                                    Bridge Chainage
                                                </h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of
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
                                                                    @foreach ($bridgeTypeMaster as $item)
                                                                        <option value="{{ $item->bridge_type_cd }}">
                                                                            {{ $item->bridge_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px"><i
                                                        class="fa-solid fa-floppy-disk"></i> Send
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

                            <!-- edit modal Bridge Name-->
                            <div class="modal fade" id="editBridgeNameModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_system_id }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeNameModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Name">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="bridge_name">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->bridge_name }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update
                                                    Bridge Name
                                                </h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->bridge_name }}</textarea>
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
                                                                    @foreach ($bridgeTypeMaster as $item)
                                                                        <option value="{{ $item->bridge_type_cd }}">
                                                                            {{ $item->bridge_type_descr }}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px"><i
                                                        class="fa-solid fa-floppy-disk"></i> Send
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

                            <!-- edit modal Bridge Type-->
                            <div class="modal fade" id="editBridgeTypeModal{{ $key->rd_bridge_cd }}"
                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form class="frm_sub_asset_request_modification"
                                            id="frm_sub_asset_request_modification_{{ $key->rd_system_id }}"
                                            name = "frm_sub_asset_request_modification" method="POST"
                                            action="{{ route('reqEditRoadAssets') }}">
                                            @csrf
                                            <input type="hidden" id="model_id" name="model_id"
                                                value="editBridgeTypeModal{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="req_table_name"
                                                value="asset_road_bridge_details">
                                            <input type="hidden" name="req_asset_name" value="BRIDGE">
                                            <input type="hidden" name="req_is_sub_asset" value="Y">
                                            <input type="hidden" id="req_sub_asset_cd" name="req_sub_asset_cd"
                                                value="{{ $key->rd_bridge_cd }}">
                                            <input type="hidden" name="user_field_name" id="user_field_name"
                                                value="Bridge Type">
                                            <input type="hidden" name="table_field_name" id="table_field_name"
                                                value="bridge_type_cd">
                                            <input type="hidden" name="rd_system_id"
                                                value="{{ $key->rd_system_id }}">
                                            <input type="hidden" name="rd_bridge_cd"
                                                value="{{ $key->rd_bridge_cd }}" />
                                            <input type="hidden" name="old_value_cd"
                                                value="{{ $key->bridge_type_cd }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Update
                                                    Bridge Type
                                                </h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-2 mb-1">
                                                        <label for="name" class="col-form-label">Reason
                                                            of
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
                                                        <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->bridge_type_descr }}</textarea>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <label for="name" class="col-form-label">To</label>
                                                    </div>
                                                    <div class="col-md-5 mb-1">
                                                        {{-- <textarea name="new_value_cd" id="new_value_cd" rows="3" class="form-control" required></textarea> --}}
                                                        <select id="new_value_cd" class="custom-select form-control"
                                                            name="new_value_cd">
                                                            <option value="" disable selected hidden required>
                                                                Please
                                                                Select</option>
                                                            @foreach ($bridgeTypeMaster as $item)
                                                                <option value="{{ $item->bridge_type_cd }}">
                                                                    {{ $item->bridge_type_descr }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-sm submitEditBtn"
                                                    style="border-radius:5px"><i
                                                        class="fa-solid fa-floppy-disk"></i> Send
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
                        @endforeach
                    </tbody>
                </table>
            @endif
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
        $('form.frm_sub_asset_request_modification').on("submit", function(e) {
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