@extends('layouts.app')
@section('content')
    <div class="content-header mb-4">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('manageRoad') }}">Manage Roads</a>
                </li>
                <li class="breadcrumb-item">Add New Road</li>
            </ol>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody py-3">
            @if (session('failed'))
                <div class="text-sm alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    <strong>Failed!</strong> {{ session('failed') }}
                    @if (session('kmlFormatIssue'))
                        <a href="#" data-toggle="modal" data-target="#helpModal">
                            Click Here To See the Sample Format<i class="fas fa-question-circle"></i>
                        </a>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('invalid'))
                <div class="alert alert-success">
                    {{ session('invalid') }}
                </div>
            @endif
            {{-- @if ($errors->any())
            <div class="text-sm alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif --}}
            <form action="{{ route('road.add-road') }}" method="post" id="add_road_details" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="hdn_asset_plan_id" name="hdn_asset_plan_id" value="{{ $asset_plan_id }}" />
                <input type="hidden" id="hdn_project_cd" name="hdn_project_cd" value="{{ $project_cd }}" />
                <input type="hidden" id="hdn_division_cd" name="hdn_division_cd" value="{{ $division_cd }}" />
                <input type="hidden" id="hdn_sub_division_cd" name="hdn_sub_division_cd" value="{{ $sub_division_cd }}" />
                <input type="hidden" id="hdn_temp_asset_cd" name="hdn_temp_asset_cd" value="{{ $temp_asset_cd }}" />
                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2" style="font-size:14px">Road Information</legend>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <input type="hidden" id="userOfficeType" value="{{ $user->office_type_cd }}">
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="road_name">Road Name<span class="star">*</span></label>
                            <input type="text" id="road_name" class="form-control form-control-sm" name="road_name"
                                value="{{ $asset_name }}">

                            @error('road_name')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="road_category">Road Category <span class="star">*</span></label>
                            <select id="road_category" class="custom-select form-control form-control-sm"
                                name="road_category">
                                <option value="" disable selected hidden>Please Select</option>
                                @foreach ($roadCategories as $roadCategory)
                                    <option value="{{ $roadCategory->rd_catg_cd }}">
                                        {{ $roadCategory->rd_catg_descr }}
                                    </option>
                                @endforeach
                            </select>

                            @error('road_category')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="road_type">Road Type <span class="star">*</span></label>
                            <select id="road_type" class="custom-select form-control form-control-sm" name="road_type">
                                <option value="" disable selected hidden>Please Select</option>
                                @foreach ($roadTypes as $roadType)
                                    <option value="{{ $roadType->rd_type_cd }}">
                                        {{ $roadType->rd_type_descr }}
                                    </option>
                                @endforeach
                            </select>

                            @error('road_type')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="road_length">Road Length (Km) <span class="star">*</span></label>
                            <input type="number" step="0.001" id="road_length" class="form-control form-control-sm"
                                name="road_length" value="{{ $asset_length }}" placeholder="0.000"
                                oninput="restrictDecimalPoints(event)">

                            @error('road_length')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="road_owner">Road Owner <span class="star">*</span></label>
                            <select id="road_owner" class="custom-select form-control form-control-sm" name="road_owner">
                                <option value="" disable selected hidden>Please Select</option>
                                @foreach ($roadOwners as $roadOwner)
                                    <option value="{{ $roadOwner->owner_cd }}">{{ $roadOwner->owner_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('road_owner')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    {{-- added by Saiful 6-Jul-24
                    for uploading kml file of road Start --}}
                    <div class="row mt-2" style="background-color: #efeeee;">
                        <div class="col-12 pt-2">
                            <legend class="w-auto px-2" style="font-size:13px ">
                                Upload Documents
                            </legend>
                            <div class="p-2">
                                <div>
                                    <p class="text-sm text-info text-underline"><strong>
                                            <i class="fa fa-info-circle mr-1 text-sm"></i>Important:
                                        </strong></p>
                                    <ul class="text-xs text-secondary">
                                        <li>
                                            <strong>
                                                File Type:
                                            </strong>
                                            Only KML files are supported for upload in this section.
                                        </li>
                                        <li>
                                            <strong>
                                                File Size Limit:
                                            </strong>
                                            The maximum allowed file size is 2 MB.
                                        </li>
                                    </ul>
                                </div>
                                <div class="row form-1-box my-1">
                                    <div class="col-md-4">
                                        <label for="road_kml_file">1. Upload KML File for this Road:<span
                                                class="star">*</span></label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="file" class="text-xs text-success" id="road_kml_file"
                                            name="road_kml_file" onchange="showRemoveBtn('road_kml_file')">
                                        <button type="button" id="removeBtn_road_kml_file"
                                            class="outline-0 border border-danger text-danger text-xs rounded-1"
                                            style="background:rgb(252, 217, 217); display:none;"
                                            onclick="removeFile('road_kml_file')">
                                            <i class="fa fa-trash mr-1 text-xs"></i>
                                            Remove
                                        </button>
                                        @error('road_kml_file')
                                            <div class="text-danger text-xs">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- added by Saiful 6-Jul-24
                    for uploading kml file of road End --}}
                    <!-- Chainage Information -->
                    <div id="chainage-container"></div>
                </fieldset>
                <div class="text-end">
                    <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2"><i class="fa fa-save"></i>
                        Submit</button>
                    <button class="btn btn-danger btn-sm rounded-0 mt-2"><i class="fa fa-backward"></i><a class="text-white"
                            href="{{ route('manageRoad') }} ">
                            Cancel</a></button>
                </div>
            </form>
        </div>
        <!-- table content -->
        <div class="container-fluid mainBody ">
            <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT ROAD DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border">
                <div class="d-flex text-sm justify-content-end my-2">
                    <button id="freezeBtn" class="btn btn-xs btn-outline-primary rounded-1">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Send data for finalization
                    </button>
                </div>
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="draft_road_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Sl No.</th>
                        <th class="text-center">Road ID</th>
                        <th class="text-center">Road Name</th>
                        <th class="text-center">Road Length(Km)</th>
                        <th class="text-center">Road Category</th>
                        <th class="text-center">Road Type</th>
                        <th class="text-center">Road Owner</th>
                        <!-- Saiful # 20-04-2026 # Start -->
                        <th class="text-center">Project CD</th>
                        <!-- Saiful # 20-04-2026 # End -->
                        <th class="text-center">Is Rejected?</th>
                        <th class="text-center">Reason of Rejection</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Select</th>
                        <th class="text-center">Delete</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($roadDraftDetails as $roadDraftDetail)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $roadDraftDetail->rd_system_id }}
                                </td>
                                <td class="text-uppercase">
                                    {{ $roadDraftDetail->rd_name }}
                                </td>
                                <td class="text-uppercase text-center">
                                    {{ $roadDraftDetail->road_length }}
                                </td>
                                <td class="text-uppercase">
                                    {{ $roadDraftDetail->rd_catg_descr }}
                                </td>
                                <td class="text-uppercase">
                                    {{ $roadDraftDetail->rd_type_descr }}
                                </td>
                                <td class="text-uppercase">
                                    {{ $roadDraftDetail->owner_name }}
                                </td>
                                <!-- Saiful # 20-04-2026 # Start -->
                                <td class="text-uppercase">
                                    {{ $roadDraftDetail->project_cd }}
                                </td>
                                <!-- Saiful # 20-04-2026 # End -->
                                <td>
                                    {{ $roadDraftDetail->is_rejected == 'Y' ? 'YES' : 'NO' }}
                                </td>
                                <td>
                                    {{ $roadDraftDetail->reason_of_rejection }}
                                </td>
                                <th>
                                    <a class="text-warning edit" data-toggle="modal" data-target="#roadEditModal"
                                        data-rd-id="{{ $roadDraftDetail->rd_system_id }}"
                                        data-rd-name="{{ $roadDraftDetail->rd_name }}"
                                        data-rd-length="{{ $roadDraftDetail->road_length }}"
                                        data-rd-catg="{{ $roadDraftDetail->rd_category_cd }}"
                                        data-rd-type="{{ $roadDraftDetail->rd_type_cd }}"
                                        data-rd-owner="{{ $roadDraftDetail->rd_owner_cd }}"
                                        data-rejection="{{ $roadDraftDetail->reason_of_rejection }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </th>
                                <td>
                                    <input type="checkbox" class="selected-asset"
                                        data-road-id="{{ $roadDraftDetail->rd_system_id }}" />
                                </td>
                                <th>
                                    <form action="{{ route('destroy.road') }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" name="road_id" value="{{ $roadDraftDetail->rd_system_id }}">
                                        <button type="submit" class="border-0 bg-transparent"
                                            onclick="return confirm('Are you sure you want to delete this road?')">
                                            <i class="fa fa-trash text-xs text-danger"></i>
                                        </button>
                                    </form>
                                </th>
                            </tr>
                            <?php    $i++; ?>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2 ">
                    @if ($roadDraftDetails->hasPages())
                        {{ $roadDraftDetails->links() }}
                        <span class="text-sm">Click next to load other 200 roads.</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- The Edit Modal -->
    <div class="modal" id="roadEditModal">
        <div class="modal-dialog modal-lg text-xs">
            <form class="editRoadForm" id="editRoadForm" name="editRoadForm" method="POST" action="#"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="edit_road_id" value="">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color: rgb(240, 240, 240);">
                        <strong class="text-md text-uppercase">Update Road Details</strong>
                        <a type="button" data-dismiss="modal"><i class="fas fa-times"></i></a>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row text-xs">
                            <div class="col-sm-12 col-md-4">
                                <label for="edit_road_name">Road Name<span class="star">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="edit_road_name" name="road_name"
                                    value="">
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="edit_road_category">Road Category <span class="star">*</span></label>
                                <select class="form-control form-control-sm" id="edit_road_category" name="road_category">
                                    @foreach ($roadCategories as $roadCategory)
                                        <option value="{{ $roadCategory->rd_catg_cd }}">
                                            {{ $roadCategory->rd_catg_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="edit_road_type">Road Type <span class="star">*</span></label>
                                <select class="form-control form-control-sm" id="edit_road_type" name="road_type">
                                    @foreach ($roadTypes as $roadType)
                                        <option value="{{ $roadType->rd_type_cd }}">
                                            {{ $roadType->rd_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="edit_road_length">Road Length (Km) <span class="star">*</span></label>
                                <input type="number" step="0.001" id="edit_road_length" class="form-control form-control-sm"
                                    name="road_length" placeholder="0.000" value="">
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="edit_road_owner">Road Owner <span class="star">*</span></label>
                                <select class="form-control form-control-sm" id="edit_road_owner" name="road_owner">
                                    @foreach ($roadOwners as $roadOwner)
                                        <option value="{{ $roadOwner->owner_cd }}">
                                            {{ $roadOwner->owner_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mt-4">
                                <label for="edit_road_kml_file">
                                    Upload KML File for this Road:
                                </label>
                                <input type="file" class="text-xs text-success" id="edit_road_kml_file"
                                    name="edit_road_kml_file">
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm text-md mt-2" onclick="editDraftData()">
                            <i class="fa fa-save"></i>
                            Update
                        </button>
                        <button class="btn btn-sm modalClose btn-danger text-md mt-2" data-dismiss="modal">
                            <i class="fa fa-times mr-2"></i> CLOSE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->

    <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg text-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <b class="modal-title" id="helpModalLabel">Information: &lt;Folder&gt; tag With
                        &lt;name&gt;Roads&lt;/name&gt; should Contain in the KML File</b>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    &lt;?xml version="1.0" encoding="UTF-8"?&gt;<br>
                    &lt;kml xmlns="http://www.opengis.net/kml/2.2"
                    xmlns:gx="http://www.google.com/kml/ext/2.2"
                    xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom"&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&lt;Document&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;name&gt;aboi-1.kml&lt;/name&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;Schema name="Aboi_Division"
                    id="S_Aboi_Division_SSDSSD"&gt;xxxxxxx&lt;/Schema&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;StyleMap
                    id="xxxx"&gt;xxxxxx&lt;/StyleMap&gt;<br>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;Folder&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &lt;name&gt;Roads&lt;/name&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&lt;visibility&gt;0&lt;/visibility&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;Placemark&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &lt;name&gt;XXXXX&lt;/name&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &lt;description&gt;XXXXX&lt;/description&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &lt;styleUrl&gt;#style0&lt;/styleUrl&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
                    ;&lt;ExtendedData&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&lt;SchemaData
                    schemaUrl="XXXXXXX"&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;SimpleData
                    name="XXXX"&gt;XXXXXX&lt;/SimpleData&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;SimpleData
                    name="XXXX"&gt;XXXXX&lt;/SimpleData&gt;<br>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/SchemaData&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&lt;/ExtendedData&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &lt;LineString&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&lt;coordinates&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;94.91361799177928,26.31819139340827,0
                    &nbsp;94.91351106897839,26.31848827500818,0<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;94.91341156011089,26.31878086308422,0,
                    &nbsp;.....,.....,.....<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/coordinates&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&lt;/LineString&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/Placemark&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/Folder&gt;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&lt;/Document&gt;<br>
                    &lt;/kml&gt;
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <x-success-modal />
    <x-warning-modal />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <style>
        /* Target the Select2 container */
        .select2-container .select2-selection--single {
            font-size: 13px;
        }

        /* Target the dropdown options */
        .select2-results__option {
            font-size: 13px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>

    <script>
        function removeFile(inputId) {
            var fileInput = document.getElementById(inputId);
            fileInput.value = "";
            hideRemoveBtn(inputId);
        }

        function showRemoveBtn(inputId) {
            var removeButton = document.getElementById("removeBtn_" + inputId);
            removeButton.style.display = "inline-block";
        }

        function hideRemoveBtn(inputId) {
            var removeButton = document.getElementById("removeBtn_" + inputId);
            removeButton.style.display = "none";
        }

        function restrictDecimalPoints(event) {
            const input = event.target;
            const value = input.value;
            const decimalIndex = value.indexOf('.');
            if (decimalIndex !== -1 && value.length - decimalIndex > 4) {
                input.value = value.slice(0, decimalIndex + 4);
            }
        }

        $(function () {
            $("#draft_road_details_table")
                .DataTable({
                    "oLanguage": {
                        "sSearch": "Filter: "
                    },
                    buttons: [
                        "csv",
                        "excel",
                    ],
                })
                .buttons()
                .container()
                .appendTo(".mis-btn-rd");
        });

        $(document).ready(function () {
            // manipulate the edit form
            $('#roadEditModal').on('show.bs.modal ', function (event) {
                var button = $(event.relatedTarget); // get the button property
                let road_id = button.data('rd-id');
                let road_name = button.data('rd-name');
                let road_length = button.data('rd-length');
                let road_category = button.data('rd-catg');
                let road_type = button.data('rd-type');
                let road_owner = button.data('rd-owner');
                let rejection = button.data('rejection');

                $("#edit_road_id").val(road_id);
                $("#edit_road_name").val(road_name);
                $("#edit_road_length").val(road_length);
                $("#edit_road_category").val(road_category);
                $("#edit_road_type").val(road_type);
                $("#edit_road_owner").val(road_owner);

            });

            // send data for finalization
            $("#freezeBtn").on("click", function () {
                const status = confirm("Are you sure?");
                if (status) {
                    var selectedAsset = $(".selected-asset:checked")
                        .map(function () {
                            return $(this).data("road-id");
                        })
                        .get();

                    if (selectedAsset.length === 0) {
                        showDashboardModal("Select atleast one road to send for finalization!");
                    } else {
                        $.ajax({
                            type: "GET",
                            url: "/asset-management/send-road-details-finalization",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            data: {
                                assetList: selectedAsset
                            },
                            cache: false,
                            success: function (response) {
                                console.log(response);
                                if (response.status === 200) {
                                    showSuccessModal(response.message);
                                }
                                if (response.status === 503) {
                                    showDashboardModal(response.message);
                                }

                                if (response.status === 401) {
                                    showDashboardModal(response.message);
                                }

                                if (response.status === 500) {
                                    showDashboardModal(response.message);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log(error);
                            },
                        });
                    }
                }
            });

            $('#road_category').select2();
            $('#road_type').select2();
            $('#road_owner').select2();
            $('#district').select2();
            $('#block').select2();
            $('#district').on('change', () => {
                const district_cd = $('#district').val();
                $.ajax({
                    url: '/asset-management/block/' + district_cd,
                    type: 'GET',
                    cache: false,
                    success: function (response) {
                        if (response.status == 'success') {
                            const selectBlock = $('#block');
                            selectBlock.empty();
                            const selectVillage = $('#village');
                            selectVillage.empty();
                            selectBlock.append('<option value="">Choose One</option>');
                            selectVillage.append('<option value="">Choose One</option>');
                            $.each(response.result, function (index, block) {
                                selectBlock.append('<option value="' + block
                                    .block_cd +
                                    '">' + block.block_name + '</option>');
                            });
                            $('#block').prop('disabled', false);
                        } else {
                            alert("failed to fetch the Block list!");
                        }
                    }
                });

            });

            $('select[name="district_name"]').change(function () {
                const dataId = $(this).data('id');
                const district_cd = $(this).val();
                // console.log("data id: ", dataId);
                // console.log("Id: ", district_cd);
                $.ajax({
                    url: '/asset-management/block/' + district_cd,
                    type: 'GET',
                    cache: false,
                    success: function (response) {
                        if (response.status == 'success') {
                            const selectBlock = $(`#block_${dataId}`);
                            selectBlock.empty();
                            selectBlock.append('<option value="">Choose One</option>');
                            $.each(response.result, function (index, block) {
                                selectBlock.append('<option value="' + block
                                    .block_cd +
                                    '">' + block.block_name + '</option>');
                            });
                            $(`#block_${dataId}`).prop('disabled', false);
                        } else {
                            alert("failed to fetch the Block list!");
                        }
                    }
                });

            });
        })

        function editDraftData() {
            console.log('clicked');
            var formData = $("#editRoadForm").serialize();
            $.ajax({
                type: "POST",
                url: "/asset-management/edit-road",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: formData,
                cache: false,
                success: function (response) {
                    console.log(response);
                    if (response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            location.reload(true);
                        });
                    }
                    if (response.status === "failed") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            location.reload(true);
                        });
                    }
                    if (response.status === 500 || response.status === 409) {
                        console.log(response.message);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            location.reload(true);
                        });
                    }
                },
                error: function (error) {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        showConfirmButton: true,
                        timer: 3000
                    }).then(() => {
                        location.reload(true);
                    });
                },
            });
        }
    </script>
@endpush