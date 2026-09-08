@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ '#' }}" class="mr-2">Dashboard</a>/Update Data {{ $asset_title }}
        </div>
    </div>
    <section class="content" style="border-radius: .2rem;">
        <div class="container-fluid mainBody py-2" style="border-radius: .2rem;">
            <div class="border" style="border-radius: .3rem;">
                <div class="tab-content clearfix">
                    <div class="container-fluid mainBody py-2">
                        <span class="mis-btn-rd"></span>
                        <table class="table-responsive text-xs table table-bordered table-striped user_list"
                            id="roadDetail">
                            <tbody>

                                <form class="fromUpdateRoaddetails" id="fromUpdateRoaddetails{{ $tblData->rd_system_id }}"
                                    action="{{ route('saveUnlockedAssetDetails') }}" method="post">
                                    @php
                                        $arr_fields = $arr_fields_to_update;
                                        // print_r($arrUnlockCDs);
                                        // print_r($arr_fields);
                                    @endphp
                                    @csrf
                                    <input type="hidden" name="asset_name" id="asset_name" value="Roads(SR)">
                                    <input type="hidden" name="asset_cd" id="asset_cd"
                                        value="{{ $tblData->rd_system_id }}">
                                    <input type="hidden" name="asset_type_cd" id="asset_type_cd" value="10">
                                    <input type="hidden" name="arrUnlockCDs" id="arrUnlockCDs" value="{{ $arrUnlockCDs }}">
                                    <input type="hidden" name="fields_to_update" id="fields_to_update"
                                        value="{{ $arr_fields }}">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header" style="background-color: rgb(240, 240, 240);">
                                            <h5><i class="fas fa-clipboard-list text-dark"></i>
                                                <strong class="text-md">Update Unloked Raod Data</strong>
                                            </h5>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <div class="row text-xs">
                                                <div class="col-md-4">
                                                    <label for="chainage">Road ID:
                                                    </label>
                                                    <input type="text" id="road_system_id" class="form-control"
                                                        name="road_system_id" value="{{ $tblData->rd_system_id }}"
                                                        readonly />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="chainage">Road Category:
                                                    </label>
                                                    @if (str_contains($arr_fields, 'rd_category_cd'))
                                                        <select id ="rd_category_cd" name="rd_category_cd"
                                                            class="form-control">
                                                            @foreach ($road_categories as $item)
                                                                @if ($item->rd_catg_cd == $tblData->rd_category_cd)
                                                                    <option value="{{ $item->rd_catg_cd }}" selected>
                                                                        {{ $item->rd_catg_descr }} </option>
                                                                @else
                                                                    <option value="{{ $item->rd_catg_cd }}">
                                                                        {{ $item->rd_catg_descr }} </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="hidden" id="rd_category_cd" name="rd_category_cd"
                                                            class="form-control" value="{{ $tblData->rd_category_cd }}" />
                                                        <input type="text" id="txt_rd_category_cd"
                                                            name="txt_rd_category_cd" class="form-control"
                                                            value="{{ $tblData->rd_catg_descr }}" disabled />
                                                    @endif
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="chainage">Road No.:
                                                    </label>
                                                    @if (str_contains($arr_fields, 'rd_number'))
                                                        <input type="text" id="rd_number" name="rd_number"
                                                            class="form-control" value="{{ $tblData->rd_number }}" />
                                                    @else
                                                        <input type="text" id="rd_number" name="rd_number"
                                                            class="form-control" value="{{ $tblData->rd_number }}"
                                                            readonly />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row text-xs">
                                                <div class="col-md-4">
                                                    <label for="chainage">Road Name:
                                                    </label>
                                                    @if (str_contains($arr_fields, 'rd_name'))
                                                        <input type="text" id="rd_name" name="rd_name"
                                                            class="form-control" value="{{ $tblData->rd_name }}" />
                                                    @else
                                                        <input type="text" id="rd_name" name="rd_name"
                                                            class="form-control" value="{{ $tblData->rd_name }}"
                                                            readonly />
                                                    @endif
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="chainage">Road
                                                        Type:
                                                    </label>
                                                    @if (str_contains($arr_fields, 'rd_type_cd'))
                                                        <select id ="rd_type_cd" name="rd_type_cd" class="form-control">
                                                            @foreach ($road_types as $item)
                                                                @if ($item->rd_type_cd == $tblData->rd_type_cd)
                                                                    <option value="{{ $item->rd_type_cd }}" selected>
                                                                        {{ $item->rd_type_descr }} </option>
                                                                @else
                                                                    <option value="{{ $item->rd_type_cd }}">
                                                                        {{ $item->rd_type_descr }} </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="hidden" id="rd_type_cd" name="rd_type_cd"
                                                            class="form-control" value="{{ $tblData->rd_type_cd }}"
                                                            readonly />
                                                        <input type="text" id="txt_rd_type_cd" name="txt_rd_type_cd"
                                                            class="form-control" value="{{ $tblData->rd_type_descr }}"
                                                            disabled />
                                                    @endif
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="chainage">Road Length:
                                                    </label>
                                                    @if (str_contains($arr_fields, 'road_length'))
                                                        <input type="text" id="road_length" name="road_length"
                                                            class="form-control" value="{{ $tblData->road_length }}" />
                                                    @else
                                                        <input type="text" id="road_length" name="road_length"
                                                            class="form-control" value="{{ $tblData->road_length }}"
                                                            readonly />
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row text-xs">
                                                <div class="col-md-4">
                                                    <label for="chainage">Road Owner:
                                                    </label>
                                                    @if (str_contains($arr_fields, 'rd_owner_cd'))
                                                        <select id ="rd_owner_cd" name="rd_owner_cd"
                                                            class="form-control">
                                                            @foreach ($rd_owner_m as $item)
                                                                @if ($item->owner_cd == $tblData->rd_owner_cd)
                                                                    <option value="{{ $item->owner_cd }}" selected>
                                                                        {{ $item->owner_name }} </option>
                                                                @else
                                                                    <option value="{{ $item->owner_cd }}">
                                                                        {{ $item->owner_name }} </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        {{-- <input type="text" id="txt_owner_name"
                                                                    name="txt_owner_name" class="form-control"
                                                                    value="{{ $tblData->owner_name }}" /> --}}
                                                    @else
                                                        <input type="hidden" id="rd_owner_cd" name="rd_owner_cd"
                                                            class="form-control" value="{{ $tblData->rd_owner_cd }}"
                                                            readonly />
                                                        <input type="text" id="txt_rd_owner_cd" name="txtrd_owner_cd"
                                                            class="form-control" value="{{ $tblData->owner_name }}"
                                                            readonly />
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="chainage">District
                                                        Name:
                                                    </label>
                                                    @if (str_contains($arr_fields, 'district_name'))
                                                        <input type="text" id="district_name" name="district_name"
                                                            class="form-control" value="{{ $tblData->district_name }}" />
                                                    @else
                                                        <input type="text" id="district_name" name="district_name"
                                                            class="form-control" value="{{ $tblData->district_name }}"
                                                            readonly />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row text-xs">
                                                <div class="col-md-4">
                                                    <label for="chainage">Block Name:
                                                    </label>
                                                    @if (str_contains($arr_fields, 'block_name'))
                                                        <input type="text" id="block_name" name="block_name"
                                                            class="form-control" value="{{ $tblData->block_name }}" />
                                                    @else
                                                        <input type="text" id="block_name" name="block_name"
                                                            class="form-control" value="{{ $tblData->block_name }}"
                                                            readonly />
                                                    @endif
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="chainage">Division
                                                        Name:
                                                    </label>
                                                    @if (str_contains($arr_fields, 'division_name'))
                                                        <input type="text" id="division_name" name="division_name"
                                                            class="form-control" value="{{ $tblData->division_name }}" />
                                                    @else
                                                        <input type="text" id="division_name" name="division_name"
                                                            class="form-control" value="{{ $tblData->division_name }}"
                                                            readonly />
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="row text-xs">
                                                <div class="col-md-2">
                                                    <label>1.
                                                        <span class="star">*</span>
                                                        Remarks:</label>
                                                </div>

                                                <div class="col-md-10">

                                                    <textarea type="text" name="txtRemarks" id="txtRemarks" rows="3" class="form-control" required></textarea>
                                                </div>


                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="fa fa-save btn btn-success">Update</button>
                                            <a class="btn btn-sm modalClose btn-danger" data-dismiss="modal">CLOSE</a>
                                        </div>
                                    </div>
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loader/style.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/admin/UnlockData.js') }}" defer></script>
@endpush
