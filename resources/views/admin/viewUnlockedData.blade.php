@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ '#' }}" class="mr-2">Dashboard</a>/Unlock Data {{ $asset_title }}
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

                                <form class="fromUnlockRoaddetails" id="unlockRoaddetails{{ $tblData->rd_system_id }}"
                                    action="{{ route('saveunlockdata') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="asset_name" id="asset_name" value="Roads(SR)">
                                    <input type="hidden" name="asset_cd" id="asset_cd"
                                        value="{{ $tblData->rd_system_id }}">
                                    <input type="hidden" name="asset_type_cd" id="asset_type_cd" value="10">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header" style="background-color: rgb(240, 240, 240);">
                                            <h5><i class="fas fa-clipboard-list text-dark"></i>
                                                <strong class="text-md">Unlock Raod
                                                    Data</strong>
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
                                                    <label for="chainage">Road
                                                        Category:
                                                    </label>
                                                    <input type="checkbox" id="rd_category_cd" name="rd_category_cd"
                                                        value="unlock">Unlock
                                                    <input type="text" id="rd_catg" name="rd_catg" class="form-control"
                                                        value="{{ $tblData->rd_catg_descr }}" readonly />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="chainage">Road No.:
                                                    </label>
                                                    <input type="checkbox" id="rd_number" name="rd_number"
                                                        value="unlock">Unlock
                                                    <input type="text" id="txt_rd_number" name="txt_rd_number"
                                                        class="form-control" value="{{ $tblData->rd_number }}" readonly />
                                                </div>
                                            </div>
                                            <div class="row text-xs">
                                                <div class="col-md-4">
                                                    <label for="chainage">Road Name:
                                                    </label>
                                                    <input type="checkbox" id="rd_name" name="rd_name"
                                                        value="unlock">Unlock
                                                    <input type="text" id="txt_rd_name" name="txt_rd_name"
                                                        class="form-control" value="{{ $tblData->rd_name }}" readonly />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="chainage">Road
                                                        Type:
                                                    </label>
                                                    <input type="checkbox" id="rd_type_cd" name="rd_type_cd"
                                                        value="unlock">Unlock
                                                    <input type="text" id="txt_rd_type_cd" name="txt_rd_type_cd"
                                                        class="form-control" value="{{ $tblData->rd_type_descr }}"
                                                        readonly />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="chainage">Road Length:
                                                    </label>
                                                    <input type="checkbox" id="road_length" name="road_length"
                                                        value="unlock">Unlock
                                                    <input type="text" id="txt_road_length" name="txt_road_length"
                                                        class="form-control" value="{{ $tblData->road_length }}"
                                                        readonly />
                                                </div>
                                            </div>

                                            <div class="row text-xs">
                                                <div class="col-md-4">
                                                    <label for="chainage">Road Owner:
                                                    </label>
                                                    <input type="checkbox" id="rd_owner_cd" name="rd_owner_cd"
                                                        value="unlock">Unlock
                                                    <input type="text" id="txt_owner_name" name="txt_owner_name"
                                                        class="form-control" value="{{ $tblData->owner_name }}"
                                                        readonly />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="chainage">District
                                                        Name:
                                                    </label>
                                                    <input type="checkbox" id="district_name" name="district_name"
                                                        value="unlock">Unlock
                                                    <input type="text" id="txt_district_name" name="txt_district_name"
                                                        class="form-control" value="{{ $tblData->district_name }}"
                                                        readonly />
                                                </div>
                                            </div>
                                            <div class="row text-xs">
                                                <div class="col-md-4">
                                                    <label for="chainage">Block Name:
                                                    </label>
                                                    <input type="checkbox" id="block_name" name="block_name"
                                                        value="unlock">Unlock
                                                    <input type="text" id="txt_block_name" name="txt_block_name"
                                                        class="form-control" value="{{ $tblData->block_name }}"
                                                        readonly />
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="chainage">Division
                                                        Name:
                                                    </label>
                                                    <input type="checkbox" id="division_name" name="division_name"
                                                        value="unlock">Unlock
                                                    <input type="text" id="txt_division_name" name="txt_division_name"
                                                        class="form-control" value="{{ $tblData->division_name }}"
                                                        readonly />
                                                </div>
                                            </div>
                                            <div class="row text-xs">
                                                <div class="col-md-4">
                                                    <label for="chainage">Unlock will
                                                        remain upto
                                                    </label>
                                                    <input type="Date" id="txt_unlock_valid_upto"
                                                        name="txt_unlock_valid_upto" class="form-control"
                                                        value="{{ $last_date_of_validity }}" required />
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="chainage">
                                                        No. of Days allowed to modify:
                                                    </label>
                                                    <input type="text" id="txt_no_of_days" name="txt_no_of_days"
                                                        class="form-control" value="{{ $unlock_validity }}" readonly />
                                                </div>
                                            </div>
                                            <div class="row text-xs">
                                                <div class="col-md-12" style="background-color: #efeeee;">
                                                    <label for="chainage" style="font-size:13px ">
                                                        Upload Documents
                                                    </label>
                                                </div>

                                                <div class="col-md-12 text-sm text-info text-underline"
                                                    style="background-color: #efeeee;">
                                                    <strong>
                                                        <i class="fa fa-info-circle mr-1 text-xs"></i>Important:
                                                    </strong>

                                                    <ul class="text-xs text-secondary">
                                                        <li>
                                                            <strong>
                                                                File Type:
                                                            </strong>
                                                            Only PDF files are supported
                                                            for upload in this section.
                                                        </li>
                                                        <li>
                                                            <strong>
                                                                File Size Limit:
                                                            </strong>
                                                            The maximum allowed file size is 2 MB.
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row text-xs">
                                                <div class="col-md-2">
                                                    <label>1.
                                                        <span class="star">*</span>
                                                        Upload First File:</label>
                                                </div>

                                                <div class="col-md-4">
                                                    <input type="file" id="firstFile" name="firstFile">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>2.
                                                        Upload Second File:</label>
                                                </div>

                                                <div class="col-md-4">
                                                    <input type="file" class="text-xs text-success" id="secondFile"
                                                        name="secondFile">
                                                </div>

                                            </div>
                                            <div class="row text-xs">

                                                <div class="col-md-2">
                                                    <label>3. Upload Third File:</label>
                                                </div>

                                                <div class="col-md-4">
                                                    <input type="file" class="text-xs text-success" id="thirdFile"
                                                        name="thirdFile">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>4. Upload Fourth File:</label>
                                                </div>

                                                <div class="col-md-4">
                                                    <input type="file" class="text-xs text-success" id="fourthFile"
                                                        name="fourthFile">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit"
                                                class="btn btn-sm btn-success fa-unlock">Unlock</button>
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
