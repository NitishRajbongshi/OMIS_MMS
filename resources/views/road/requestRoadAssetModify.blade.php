@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ route('dashboard') }}" class="mr-2">Dashboard</a>/ Road Details
        </div>
    </div>
    <section class="content">
        <div class="container-fluid mt-3">
            <form class="frm_request_modify_for" id="frm_request_modify_for" method="POST" action="">
                @csrf
                <div class="row">
                    <div class="col-md-2 mb-1">
                        <label for="name" class="col-form-label">Request Modification in:</label>
                    </div>
                    <div class="col-md-3 mb-1">
                        <select id="select_asset_cd" class="custom-select form-control" name="select_asset_cd">
                            {{-- <option value="" disable selected hidden required>
                                    Please Select</option> --}}
                            <option value="0">Road</option>
                            <option value="1">Culvert</option>
                            <option value="2">Bridges</option>
                            <option value="3">Surface Types</option>
                            <option value="4">Pavement</option>
                            <option value="5">PCI</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- Main content -->
    <section class="content" id="roadModifySection" name="roadModifySection">
        <div class="container-fluid mt-3">
            <table class="table-responsive table table-bordered table-striped user_list" id="roadDetail">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Road System ID</th>
                    <th class="text-center">Road Category</th>
                    <th class="text-center">Road No.</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">Road Type</th>
                    <th class="text-center">Road length</th>
                    <th class="text-center">Road Owner</th>
                    <th class="text-center">District</th>
                    <th class="text-center">Block</th>
                    {{-- <th class="text-center">Modify Request</th> --}}
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($r_details as $key)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td style="position: relative">
                                {{ $key->rd_system_id }}
                            </td>

                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editRoadCatgModal{{ $key->rd_system_id }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->rd_catg_descr }}
                            </td>

                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editRoadNumberModal{{ $key->rd_system_id }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->rd_number }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editRoadNameModal{{ $key->rd_system_id }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->rd_name }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editRoadTypeModal{{ $key->rd_system_id }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->rd_type_descr }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editRoadLengthModal{{ $key->rd_system_id }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->road_length }}
                            </td>
                            <td style="position: relative">
                                <span class="iconSpan">
                                    <a data-toggle="modal" data-target="#editRoadOwner{{ $key->rd_system_id }}"
                                        data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                        <i class="fas fa-edit"></i></a>
                                </span><br>
                                {{ $key->owner_name }}
                            </td>
                            <td style="position: relative">
                                {{-- <span class="iconSpan">
                                        <a data-toggle="modal" data-target="#editModal10{{ $key->rd_system_id }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br> --}}
                                {{ $key->district_name }}
                            </td>
                            <td style="position: relative">
                                {{-- <span class="iconSpan">
                                        <a data-toggle="modal" data-target="#editModal11{{ $key->rd_system_id }}"
                                            data-backdrop="static" class="btn text-danger btn-xs edit rounded-0">
                                            <i class="fas fa-edit"></i></a>
                                    </span><br> --}}
                                {{ $key->block_name }}
                            </td>
                        </tr>
                        <?php $i++; ?>



                        <!-- edit modal Road Category-->
                        <div class="modal fade" id="editRoadCatgModal{{ $key->rd_system_id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="update_road_catg_form" id="update_road_category_{{ $key->rd_system_id }}"
                                        method="POST" action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" name="req_table_name" value="asset_road_details">
                                        <input type="hidden" name="req_asset_name" value="Road">
                                        <input type="hidden" name="req_is_sub_asset" value="N">
                                        <input type="hidden" name="req_sub_asset_cd" value="">
                                        <input type="hidden" name="user_field_name" value="Road category">
                                        <input type="hidden" name="table_field_name" value="rd_category_cd">
                                        <input type="hidden" name="rd_system_id" id ="rd_system_id"
                                            value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="old_value_cd" value="{{ $key->rd_category_cd }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Road
                                                Category</h5>
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
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->rd_catg_descr }}</textarea>
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
                                                        @foreach ($roadCategories as $roadCategory)
                                                            <option value="{{ $roadCategory->rd_catg_cd }}">
                                                                {{ $roadCategory->rd_catg_descr }}</option>
                                                        @endforeach
                                                    </select>
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


                        <!-- edit modal Road Number-->
                        <div class="modal fade" id="editRoadNumberModal{{ $key->rd_system_id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="update_road_number_form"
                                        id="update_road_number_{{ $key->rd_system_id }}" method="POST"
                                        action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" name="req_table_name" value="asset_road_details">
                                        <input type="hidden" name="req_asset_name" value="Road">
                                        <input type="hidden" name="req_is_sub_asset" value="N">
                                        <input type="hidden" name="req_sub_asset_cd" value="">
                                        <input type="hidden" name="user_field_name" value="Road Number">
                                        <input type="hidden" name="table_field_name" value="rd_number">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="old_value_cd" value="{{ $key->rd_number }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Road
                                                Number</h5>
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
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->rd_number }}</textarea>
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
                                                            @foreach ($roadCategories as $roadCategory)
                                                                <option value="{{ $roadCategory->rd_catg_cd }}">
                                                                    {{ $roadCategory->rd_catg_descr }}</option>
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


                        <!-- edit modal Road Name-->
                        <div class="modal fade" id="editRoadNameModal{{ $key->rd_system_id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="update_road_name_form" id="update_road_name_{{ $key->rd_system_id }}"
                                        name = "update_road_name" method="POST"
                                        action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" name="req_table_name" value="asset_road_details">
                                        <input type="hidden" name="req_asset_name" value="Road">
                                        <input type="hidden" name="req_is_sub_asset" value="N">
                                        <input type="hidden" name="req_sub_asset_cd" value="">
                                        <input type="hidden" name="user_field_name" value="Road Name">
                                        <input type="hidden" name="table_field_name" value="rd_name">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="old_value_cd" value="{{ $key->rd_name }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Road
                                                Name</h5>
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
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->rd_name }}</textarea>
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
                                                            @foreach ($roadCategories as $roadCategory)
                                                                <option value="{{ $roadCategory->rd_catg_cd }}">
                                                                    {{ $roadCategory->rd_catg_descr }}</option>
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

                        <!-- edit modal Road Type-->
                        <div class="modal fade" id="editRoadTypeModal{{ $key->rd_system_id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="update_road_type_form" id="update_road_type_{{ $key->rd_system_id }}"
                                        name = "update_road_type" method="POST"
                                        action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" name="req_table_name" value="asset_road_details">
                                        <input type="hidden" name="req_asset_name" value="Road">
                                        <input type="hidden" name="req_is_sub_asset" value="N">
                                        <input type="hidden" name="req_sub_asset_cd" value="">
                                        <input type="hidden" name="user_field_name" value="Road Type">
                                        <input type="hidden" name="table_field_name" value="rd_type_cd">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="old_value_cd" value="{{ $key->rd_type_cd }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Road
                                                Type</h5>
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
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->rd_type_descr }}</textarea>
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
                                                        @foreach ($roadTypes as $item)
                                                            <option value="{{ $item->rd_type_cd }}">
                                                                {{ $item->rd_type_descr }}</option>
                                                        @endforeach
                                                    </select>
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


                        <!-- edit modal Road Length-->
                        <div class="modal fade" id="editRoadLengthModal{{ $key->rd_system_id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="update_road_length_form" id="update_road_type_{{ $key->rd_system_id }}"
                                        name = "update_road_type" method="POST"
                                        action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" name="req_table_name" value="asset_road_details">
                                        <input type="hidden" name="req_asset_name" value="Road">
                                        <input type="hidden" name="req_is_sub_asset" value="N">
                                        <input type="hidden" name="req_sub_asset_cd" value="">
                                        <input type="hidden" name="user_field_name" value="Road Length">
                                        <input type="hidden" name="table_field_name" value="road_length">
                                        <input type="hidden" name="rd_system_id" id="rd_system_id"
                                            value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="old_value_cd" value="{{ $key->road_length }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Road
                                                Length</h5>
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
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->road_length }}</textarea>
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
                                                            @foreach ($roadTypes as $item)
                                                                <option value="{{ $item->rd_type_cd }}">
                                                                    {{ $item->rd_type_descr }}</option>
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


                        <!-- edit modal Road Core Network-->
                        <div class="modal fade" id="editRoadCoreNetworkModal{{ $key->rd_system_id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="update_road_core_network_form"
                                        id="update_road_type_{{ $key->rd_system_id }}" name = "update_road_type"
                                        method="POST" action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" name="req_table_name" value="asset_road_details">
                                        <input type="hidden" name="req_asset_name" value="Road">
                                        <input type="hidden" name="req_is_sub_asset" value="N">
                                        <input type="hidden" name="req_sub_asset_cd" value="">
                                        <input type="hidden" name="user_field_name" value="Core Network">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Road
                                                Core Network</h5>
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
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->included_in_core_network }}</textarea>
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

                                                        <option value="Y">Yes</option>
                                                        <option value="N">No</option>

                                                    </select>
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


                        <!-- edit modal Road Owner-->
                        <div class="modal fade" id="editRoadOwner{{ $key->rd_system_id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form class="update_road_owner_form" id="update_road_type_{{ $key->rd_system_id }}"
                                        name = "update_road_type" method="POST"
                                        action="{{ route('reqEditRoadAssets') }}">
                                        @csrf
                                        <input type="hidden" name="req_table_name" value="asset_road_details">
                                        <input type="hidden" name="req_asset_name" value="Road">
                                        <input type="hidden" name="req_is_sub_asset" value="N">
                                        <input type="hidden" name="req_sub_asset_cd" value="">
                                        <input type="hidden" name="user_field_name" value="Road Owner">
                                        <input type="hidden" name="table_field_name" value="rd_owner_cd">
                                        <input type="hidden" name="rd_system_id" value="{{ $key->rd_system_id }}">
                                        <input type="hidden" name="old_value_cd" value="{{ $key->rd_owner_cd }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Road
                                                Owner Type</h5>
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
                                                    <textarea name="old_value_descr" id="old_value_descr" rows="3" class="form-control" readonly>{{ $key->owner_name }}</textarea>
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
                                                        @foreach ($roadOwners as $item)
                                                            <option value="{{ $item->owner_cd }}">
                                                                {{ $item->owner_name }}</option>
                                                        @endforeach
                                                    </select>
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
        </div>
    </section>

    <section class="content" id="culvertModifySection" name="culvertModifySection">

        <div class="container-fluid mt-3">
            <label for="name" class="col-form-label">Seclect a Road to Get List of Culvert Details</label>
            <table class="table-responsive table table-bordered table-striped user_list" id="roadDetailForCulvert">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Road System ID</th>
                    <th class="text-center">Road No.</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">Road Owner</th>
                    <th class="text-center">Action</th>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($r_details as $key)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td style="position: relative">
                                {{ $key->rd_system_id }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_number }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_name }}
                            </td>
                            <td style="position: relative">
                                {{ $key->owner_name }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('handleDataModifyReqOnARoad', ['id' => $key->rd_system_id, 'sub_asset_name' => 'CDWORK']) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
            {{-- </form> --}}
        </div>
    </section>


    <section class="content" id="bridgesModifySection" name="bridgesModifySection">
        <div class="container-fluid mt-3">
            <label for="name" class="col-form-label">Seclect a Road to Get List of Bridges</label>
            <table class="table-responsive table table-bordered table-striped user_list" id="roadDetailForBridges">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Road System ID</th>
                    <th class="text-center">Road No.</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">Road Owner</th>
                    <th class="text-center">Action</th>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($r_details as $key)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td style="position: relative">
                                {{ $key->rd_system_id }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_number }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_name }}
                            </td>
                            <td style="position: relative">
                                {{ $key->owner_name }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('handleDataModifyReqOnARoad', ['id' => $key->rd_system_id, 'sub_asset_name' => 'BRIDGE']) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
            {{-- </form> --}}
        </div>
    </section>

    <section class="content" id="surfaceTypeModifySection" name="surfaceTypeModifySection">
        <div class="container-fluid mt-3">
            <label for="name" class="col-form-label">Seclect a Road to Get List of Surface Type
                Details</label>
            <table class="table-responsive table table-bordered table-striped user_list" id="roadDetailForSurfaceTypes">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Road System ID</th>
                    <th class="text-center">Road No.</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">Road Owner</th>
                    <th class="text-center">Action</th>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($r_details as $key)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td style="position: relative">
                                {{ $key->rd_system_id }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_number }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_name }}
                            </td>
                            <td style="position: relative">
                                {{ $key->owner_name }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('handleDataModifyReqOnARoad', ['id' => $key->rd_system_id, 'sub_asset_name' => 'SURFACE_TYPE']) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
            {{-- </form> --}}
        </div>
    </section>

    <section class="content" id="pavementModifySection" name="pavementModifySection">
        <div class="container-fluid mt-3">
            <label for="name" class="col-form-label">Seclect a Road to Get List of Pavement
                Details</label>
            <table class="table-responsive table table-bordered table-striped user_list" id="roadDetailForPavement">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Road System ID</th>
                    <th class="text-center">Road No.</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">Road Owner</th>
                    <th class="text-center">Action</th>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($r_details as $key)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td style="position: relative">
                                {{ $key->rd_system_id }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_number }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_name }}
                            </td>
                            <td style="position: relative">
                                {{ $key->owner_name }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('handleDataModifyReqOnARoad', ['id' => $key->rd_system_id, 'sub_asset_name' => 'PAVEMENT']) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
            {{-- </form> --}}
        </div>
    </section>

    <section class="content" id="PCIModifySection" name="PCIModifySection">
        <div class="container-fluid mt-3">
            <label for="name" class="col-form-label">Seclect a Road to Get List of PCI
                Details</label>
            <table class="table-responsive table table-bordered table-striped user_list" id="roadDetailForPCI">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SlNo.</th>
                    <th class="text-center">Road System ID</th>
                    <th class="text-center">Road No.</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">Road Owner</th>
                    <th class="text-center">Action</th>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($r_details as $key)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td style="position: relative">
                                {{ $key->rd_system_id }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_number }}
                            </td>
                            <td style="position: relative">
                                {{ $key->rd_name }}
                            </td>
                            <td style="position: relative">
                                {{ $key->owner_name }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('handleDataModifyReqOnARoad', ['id' => $key->rd_system_id, 'sub_asset_name' => 'PCI']) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
            {{-- </form> --}}
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

        $(function() {
            $("#roadDetailForBridges").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetailForBridges_wrapper .col-md-4:eq(1)');
        });

        $(function() {
            $("#roadDetailForSurfaceTypes").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetailForSurfaceTypes_wrapper .col-md-4:eq(1)');
        });

        $(function() {
            $("#roadDetailForPavement").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetailForPavement_wrapper .col-md-4:eq(1)');
        });

        $(function() {
            $("#roadDetailForPCI").DataTable({
                //   "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetailForPCI_wrapper .col-md-4:eq(1)');
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
            $("#roadModifySection").show();
            $("#culvertModifySection").hide();
            $("#bridgesModifySection").hide();
            $("#surfaceTypeModifySection").hide();
            $("#pavementModifySection").hide();
            $("#PCIModifySection").hide();

            $('#select_asset_cd').on('change', function(e) {
                var valueSelected = this.value;

                //if value Selected as Road
                if (valueSelected == "0") {
                    $("#roadModifySection").show();
                    $("#culvertModifySection").hide();
                    $("#bridgesModifySection").hide();
                    $("#surfaceTypeModifySection").hide();
                    $("#pavementModifySection").hide();
                    $("#PCIModifySection").hide();
                }

                //if value Selected as CD Work
                if (valueSelected == "1") {
                    $("#roadModifySection").hide();
                    $("#culvertModifySection").show();
                    $("#bridgesModifySection").hide();
                    $("#surfaceTypeModifySection").hide();
                    $("#pavementModifySection").hide();
                    $("#PCIModifySection").hide();
                }

                //if value Selected as Bridge
                if (valueSelected == "2") {
                    $("#roadModifySection").hide();
                    $("#culvertModifySection").hide();
                    $("#bridgesModifySection").show();
                    $("#surfaceTypeModifySection").hide();
                    $("#pavementModifySection").hide();
                    $("#PCIModifySection").hide();
                }

                //if value Selected as Surface Type
                if (valueSelected == "3") {
                    $("#roadModifySection").hide();
                    $("#culvertModifySection").hide();
                    $("#bridgesModifySection").hide();
                    $("#surfaceTypeModifySection").show();
                    $("#pavementModifySection").hide();
                    $("#PCIModifySection").hide();
                }

                //if value Selected as Pavement
                if (valueSelected == "4") {
                    $("#roadModifySection").hide();
                    $("#culvertModifySection").hide();
                    $("#bridgesModifySection").hide();
                    $("#surfaceTypeModifySection").hide();
                    $("#pavementModifySection").show();
                    $("#PCIModifySection").hide();
                }

                //if value Selected as PCI
                if (valueSelected == "5") {
                    $("#roadModifySection").hide();
                    $("#culvertModifySection").hide();
                    $("#bridgesModifySection").hide();
                    $("#surfaceTypeModifySection").hide();
                    $("#pavementModifySection").hide();
                    $("#PCIModifySection").show();
                }

            });
            $('form.update_road_catg_form').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var rd_sys_id = $('#rd_system_id').val();
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        if (response.message == 'success') {
                            $('#editRoadcatgModal' + rd_sys_id).modal().hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: "Road category Update Request Sent Successfully!!!!\nRequest Id: " +
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
                        $('#editRoadcatgModal' + rd_sys_id).modal().hide();
                        Swal.fire({
                            icon: 'error',
                            title: 'error',
                            text: "Road Category Update Request Could Not Be Sent Successfully!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                            // window.location.replace(location);
                        });
                    }
                });
            });

            $('form.update_road_number_form').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var rd_sys_id = $('#rd_system_id').val();
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        if (response.message == 'success') {
                            $('#editRoadNumberModal' + rd_sys_id).modal().hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: "Road Number Update Request Sent Successfully!!!!\nRequest Id: " +
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
                            text: "Road Number Update Request Could Not Be Sent Successfully!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                            // window.location.replace(location);
                        });
                    }
                });
            });

            $('form.update_road_name_form').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var rd_sys_id = $('#rd_system_id').val();
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
                            $('#editRoadNameModal' + rd_sys_id).modal().hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: "Road Name Update Request Sent Successfully!!!!\nRequest Id: " +
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
                            text: "Road Name Update Request Could Not Be Sent Successfully!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                            // window.location.replace(location);
                        });
                    }
                });
            });

            $('form.update_road_type_form').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var rd_sys_id = $('#rd_system_id').val();
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
                            $('#editRoadTypeModal' + rd_sys_id).modal().hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: "Road Type Update Request Sent Successfully!!!!\nRequest Id: " +
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
                            text: "Road Type Update Request Could Not Be Sent Successfully!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                            // window.location.replace(location);
                        });
                    }
                });
            });


            $('form.update_road_length_form').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var rd_sys_id = $('#rd_system_id').val();
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
                            $('#editRoadLengthModal' + rd_sys_id).modal().hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: "Road Length Modification Request Sent Successfully!!!!\nRequest Id: " +
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
                            text: "Road Length Update Request Could Not Be Sent Successfully!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                            // window.location.replace(location);
                        });
                    }
                });
            });



            $('form.update_road_core_network_form').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var rd_sys_id = $('#rd_system_id').val();
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
                            $('#editRoadCoreNetworkModal' + rd_sys_id).modal().hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: "Road Core Network Modification Request Sent Successfully!!!!\nRequest Id: " +
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
                            text: "Road Core Network Update Request Could Not Be Sent Successfully!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                            // window.location.replace(location);
                        });
                    }
                });
            });

            $('form.update_road_owner_form').on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var rd_sys_id = $('#rd_system_id').val();
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
                            $('#editRoadOwner' + rd_sys_id).modal().hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: "Road Owner Modification Request Sent Successfully!!!!\nRequest Id: " +
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
                            text: "Road Owner Update Request Could Not Be Sent Successfully!!!!\nPlease Try Again...",
                            showConfirmButton: true,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                            // window.location.replace(location);
                        });
                    }
                });
            });
        });
    </script>
@endpush
