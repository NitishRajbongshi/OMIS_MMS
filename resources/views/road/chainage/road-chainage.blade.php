@extends('layouts.app')
@section('content')
    <div class="content-header mb-4">
        <div class="container-fluid">
            <div class="flex justify-between items-center">
                <div class="text-sm">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('manageRoad') }}">Manage Roads</a>
                        </li>
                        <li class="breadcrumb-item">Chainage</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Shown road to the user -->
    <section class="content">
        <div class="mainBody py-1">
            @if (session('failed'))
                <div class="alert alert-info alert-dismissible fade show text-sm" role="alert">

                    <strong>Failed!</strong> {{ session('failed') }}
                    @if (session('kmlFormatIssue'))
                        <a href="#" data-toggle="modal" data-target="#helpModal">
                            Click Here To See the Sample Format<i class="fas fa-question-circle"></i>
                        </a>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('invalid'))
                <div class="alert alert-info alert-dismissible fade show text-sm" role="alert">
                    <strong>Failed!</strong> Invalid Value Found.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show text-sm" role="alert">
                    <strong>Success!</strong> Chainage created successfully for Road id: {{ session('road_id') }}.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="#" method="post" id="show_roads_to_create_chainage" autocomplete="off" class="text-xs"
                enctype="multipart/form-data">
                @csrf
                <fieldset class="p-2 fl">
                    <legend class="w-auto px-2 text-bold" style="font-size:14px;">
                        Create Road Chainage
                    </legend>
                    <div class="border-bottom"></div>
                    <div class="text-sm mt-2">
                        <div id="toggleButton" class="btn btn-primary btn-sm rounded-0">
                            <span class="text-bold">Click here to view available road list</span>
                        </div>

                        <div class="rounded-0" id="toggleTable" style="display: none;">
                            <div class='card rounded-0'>
                                <div class="card-header text-light rounded-0" style="background-color:#82b3e7">
                                    <h3 class="card-title" style="font-weight: bold; font-size:.8rem">
                                        List Of Roads</h3>
                                </div>
                                <div class='card-body rounded-0'>
                                    <table class="table table-bordered table-striped user_list text-xs" id="roadDetails">
                                        <thead class="theader" style="background-color:#82b3e7">
                                            <th class="text-center">Road ID</th>
                                            <th class="text-center">Road Name</th>
                                            <th class="text-center">Road Number</th>
                                            <th class="text-center">Road Length</th>
                                            {{-- <th class="text-center">Remaining Chainage</th> --}}
                                            <th class="text-center">Segment ( start - end ) </th>
                                            {{-- <th class="text-center">Road Type</th> --}}
                                            <th class="text-center">Road Category</th>
                                            <th class="text-center">Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($roadDetails as $item)
                                                <tr class="w-full">
                                                    <td class="text-center">{{ $item->rd_system_id }}</td>
                                                    <td class="text-center">{{ $item->rd_name }}</td>
                                                    <td class="text-center">{{ $item->rd_number }}</td>
                                                    <td class="text-center">{{ $item->road_length }}</td>
                                                    {{-- <td class="text-center">
                                                            {{ $item->chainage_to - $item->chainage_from }}</td> --}}
                                                    <td class="text-center">
                                                        <span class="start_chainage_val">{{ $item->chainage_from }}</span>
                                                        - <span
                                                            class="end_chainage_val_{{ $item->rd_system_id }}">{{ $item->chainage_to }}</span>
                                                    </td>
                                                    {{-- @endif --}}
                                                    {{-- <td class="text-center road_type_{{ $item->id }}">
                                                            {{ $item->road_type }}
                                                        </td> --}}
                                                    <td class="text-center road_catg_{{ $item->id }}">
                                                        {{ $item->rd_catg_descr }}
                                                    </td>

                                                    <td class="text-center">
                                                        <button id="btnShowChainageDetails"
                                                            class="btnShowChainageDetails select-road text-xs btn btn-xs btn-outline-primary"
                                                            data-chainage-pk = "{{ $item->id }}"
                                                            data-road-id="{{ $item->rd_system_id }}"
                                                            data-road-name="{{ $item->rd_name }}"
                                                            data-road-number="{{ $item->rd_number }}"
                                                            data-road-type="{{ $item->road_type }}"
                                                            data-chainage-from="{{ $item->chainage_from }}"
                                                            data-chainage-to="{{ $item->chainage_to }}"
                                                            data-remaining-chainage-length="{{ $item->remaining_chainage_length }}"
                                                            data-chainage-step-id="{{ $item->chainage_step_id }}"
                                                            data-road-length="{{ $item->road_length }}"
                                                            data-calculated-length="{{ $item->calculated_length }}"
                                                            data-chainage-created-at="{{ $item->chainage_created_at }}"
                                                            data-chainage-zone-cd="{{ $item->zone_cd }}"
                                                            data-chainage-circle-cd="{{ $item->circle_cd }}"
                                                            data-chainage-division-cd="{{ $item->division_cd }}"
                                                            data-chainage-sub-division-cd="{{ $item->sub_division_cd }}"
                                                            data-chainage-zone-name="{{ $item->zone_name }}"
                                                            data-chainage-circle-name="{{ $item->circle_name }}"
                                                            data-chainage-division-name="{{ $item->division_name }}"
                                                            data-chainage-sub-division-name="{{ $item->sub_div_name }}"
                                                            data-user-office-type-cd="{{ $user->office_type_cd }}"
                                                            data-target = "#showChainageDetailsForm">Select
                                                            Road</button>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>


            <form action="{{ route('chainage') }}" method="post" id="store_chainage" autocomplete="off"
                class="text-xs" enctype="multipart/form-data">
                @csrf
                <fieldset class="p-2 fl">
                    <div class="row form-1-box mt-3">
                        {{-- <div class="w-full text-md my-2 px-2 text-bold text-primary">
                                Selected Road Details
                            </div> --}}
                        <div class="col-md-2">
                            <label for="">Road Id</label>
                            <input type="text" id="road_id" class="form-control rounded-0" name="road_id" required
                                readonly>
                            <span class="error" id="road_id_error"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="road_name">Road Name<span class="star">*</span></label>
                            <input type="text" id="road_name" class="form-control rounded-0" name="road_name"
                                required readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="road_number">Road Number<span class="star">*</span></label>
                            <input type="text" id="road_number" class="form-control rounded-0" name="road_number"
                                required readonly>
                        </div>
                        <div class="col-md-4" style="background-color: rgb(236, 236, 236)">
                            <label for="txtLasetChainage">
                                Last Chainage Status
                            </label>

                            <textarea id="txtLasetChainage" class="form-control rounded-0 text-xs text-bold text-danger" value="" readonly
                                aria-multiline="true"></textarea>
                            <div class="text-bold text-xs" id="last_chainage">

                            </div>
                        </div>

                    </div>

                    {{-- Hidden fields --}}
                    <div class="row form-1-box mt-3">
                        <div class="col-md-3">
                            <input type="hidden" id="chainage_pk" class="form-control" name="chainage_pk" required
                                readonly value=""> {{-- Hidden fields --}}
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="zone_cd" class="form-control" name="zone_cd" required readonly
                                value=""> {{-- Hidden fields --}}
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="circle_cd" class="form-control" name="circle_cd" required readonly
                                value=""> {{-- Hidden fields --}}
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="division_cd" class="form-control" name="division_cd" required
                                readonly value=""> {{-- Hidden fields --}}
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="sub_division_cd" class="form-control" name="sub_division_cd"
                                required readonly value=""> {{-- Hidden fields --}}
                        </div>


                        <div class="col-md-3">
                            <input type="hidden" id="road_length" class="form-control rounded-0 road_length"
                                name="road_length" value="" readonly>{{-- Hidden fields --}}
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="calculated_length" class="form-control rounded-0"
                                name="calculated_length" value="" readonly>{{-- Hidden fields --}}
                        </div>
                        <div class="col-md-3">
                            {{-- <label for="">Start chainage</label> --}}
                            <input type="hidden" id="hidden_start_chainage_val" class="form-control rounded-0"
                                name="hidden_start_chainage_val" required readonly> {{-- Hidden fields --}}
                        </div>
                        <div class="col-md-3">
                            {{-- <label for="">Start chainage</label> --}}
                            <input type="hidden" id="hidden_end_chainage_val" class="form-control rounded-0"
                                name="hidden_end_chainage_val" required readonly> {{-- Hidden fields --}}
                        </div>

                        <div class="col-md-3">
                            <input type="hidden" id="user_office_type" class="form-control rounded-0" value=""
                                required readonly> {{-- Hidden fields --}}
                        </div>

                        <div class="col-md-3">
                            <input type="hidden" id="hidden_road_type" class="form-control rounded-0" value=""
                                required readonly> {{-- Hidden fields --}}
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="chainage_step_id" class="form-control rounded-0" value=""
                                required readonly> {{-- Hidden fields --}}
                        </div>

                    </div>

                    <div class="mt-1">
                        <fieldset class="border p-3" style="">
                            <legend class="w-auto px-2 text-bold" style="font-size:14px;">
                                Create Chainage
                            </legend>
                            <div class="row form-1-box">
                                <div class="col-md-3">

                                    @if ($user->office_type_cd == 'HQ')
                                        <label for="chainage_office">Zones <span class="star">*</span></label>
                                    @endif
                                    @if ($user->office_type_cd == 'ZO')
                                        <label for="chainage_office">Circles <span class="star">*</span></label>
                                    @endif
                                    @if ($user->office_type_cd == 'CO')
                                        <label for="chainage_office">Divisions <span class="star">*</span></label>
                                    @endif
                                    @if ($user->office_type_cd == 'DO')
                                        <label for="chainage_office">Sub Divisions <span class="star">*</span></label>
                                    @endif
                                    <select id="chainage_office" class="form-control rounded-0" name="chainage_office">
                                        @if ($user->office_type_cd == 'HQ')
                                            <option value="" disable selected hidden>Select Zone</option>
                                        @endif
                                        @if ($user->office_type_cd == 'ZO')
                                            <option value="" disable selected hidden>Select Circle</option>
                                        @endif
                                        @if ($user->office_type_cd == 'CO')
                                            <option value="" disable selected hidden>Select Division</option>
                                        @endif
                                        @if ($user->office_type_cd == 'DO')
                                            <option value="" disable selected hidden>Select Sub Division</option>
                                        @endif
                                        @foreach ($chainageOffices as $roadCategory)
                                            @if ($user->office_type_cd == 'HQ')
                                                <option value="{{ $roadCategory->zone_cd }}">
                                                    {{ $roadCategory->zone_name }}
                                                </option>
                                            @endif
                                            @if ($user->office_type_cd == 'ZO')
                                                <option value="{{ $roadCategory->circle_cd }}">
                                                    {{ $roadCategory->circle_name }}
                                                </option>
                                            @endif
                                            @if ($user->office_type_cd == 'CO')
                                                <option value="{{ $roadCategory->division_cd }}">
                                                    {{ $roadCategory->division_name }}
                                                </option>
                                            @endif
                                            @if ($user->office_type_cd == 'DO')
                                                <option value="{{ $roadCategory->sub_div_cd }}">
                                                    {{ $roadCategory->sub_div_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                    <span class="error" id="chainage_office_error"></span>
                                </div>

                                <div class="col-md-3">
                                    <label for="start_chainage">Start Chainage:<span class="star">*</span></label>
                                    <input type="text" id="start_chainage" class="form-control rounded-0"
                                        name="start_chainage" value="0">
                                    <span class="error" id="start_chainage_error"></span>
                                </div>
                                <div class="col-md-3">
                                    <label for="end_chainage">End Chainage:</label>
                                    <input type="text" id="end_chainage" class="form-control rounded-0"
                                        name="end_chainage">
                                    <span class="error" id="end_chainage_error"></span>
                                </div>
                                <div class="col-md-3">
                                    <label for="remaining">Remaining Chainage<span class="star">*</span></label>
                                    <input type="text" id="remaining" class="form-control rounded-0" name="remaining"
                                        readonly>
                                    <span class="error" id="remaining_error"></span>
                                </div>

                            </div>

                            <br>
                            <div class="row form-1-box kml_file_class">
                                <div class="col-md-4">
                                    <label for="road_kml_file">Upload KML File for this Division:<span
                                            class="star">*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" class="text-xs text-success" id="road_kml_file"
                                        name="road_kml_file" onchange="showRemoveBtn('road_kml_file')">
                                    <span class="error" id="road_kml_file_error"></span>
                                    <button type="button" id="removeBtn_road_kml_file"
                                        class="outline-0 border border-danger text-danger text-xs rounded-1"
                                        style="background:rgb(252, 217, 217); display:none;"
                                        onclick="removeFile('road_kml_file')">
                                        <i class="fa fa-trash mr-1 text-xs"></i>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                </fieldset>
                <button type="submit" class="btn btn-success btn-sm rounded-0 m-2">
                    <i class="fa fa-save mr-1"></i> Submit
                </button>
                <button class="btn btn-danger btn-sm rounded-0 m-2">
                    <i class="fa fa-backward mr-1"></i>
                    <a class="text-white" href="{{ route('manageRoad') }} ">
                        Cancel</a>
                </button>
            </form>
        </div>
    </section>

    <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel"
        aria-hidden="true">
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
@endsection
@push('scripts')
    <style>
        .error {
            color: red;
        }
    </style>
    {{-- Script for toggle the road details --}}
    <script>
        $(document).ready(function() {
            $('#chainage_office').select2();
            $("#toggleButton").click(function() {
                $("#toggleTable").toggle();
            });
        });
    </script>

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
        $(document).ready(function() {
            $(".kml_file_class").hide();
            $(".btnShowChainageDetails").on("click", function(e) {

                e.preventDefault();

                $("#toggleTable").toggle();
                var data_chainage_pk = this.getAttribute('data-chainage-pk');
                var data_rd_system_id = this.getAttribute('data-road-id');
                var data_road_name = this.getAttribute('data-road-name');
                var data_rd_number = this.getAttribute('data-road-number');
                var data_road_type = this.getAttribute('data-road-type');
                var data_chainage_from = this.getAttribute('data-chainage-from');
                var data_chainage_to = this.getAttribute('data-chainage-to');
                var data_remaining_chainage_length = this.getAttribute('data-remaining-chainage-length');
                var data_chainage_step_id = this.getAttribute('data-chainage-step-id');

                var data_road_length = this.getAttribute('data-road-length');
                var data_calculated_length = this.getAttribute('data-calculated-length');
                var data_chainage_created_at = this.getAttribute('data-chainage-created-at');
                var data_chainage_zone_cd = this.getAttribute('data-chainage-zone-cd');
                var data_chainage_circle_cd = this.getAttribute('data-chainage-circle-cd');
                var data_chainage_division_cd = this.getAttribute('data-chainage-division-cd');
                var data_chainage_sub_division_cd = this.getAttribute('data-chainage-sub-division-cd');
                var data_chainage_zone_name = this.getAttribute('data-chainage-zone-name');
                var data_chainage_circle_name = this.getAttribute('data-chainage-circle-name');
                var data_chainage_division_name = this.getAttribute('data-chainage-division-name');
                var data_chainage_sub_division_name = this.getAttribute('data-chainage-sub-division-name');
                var data_user_office_type_cd = this.getAttribute('data-user-office-type-cd');
                var create_at = null;

                if (data_chainage_zone_name != '')
                    create_at = "At Zone: " + data_chainage_zone_name;
                if (data_chainage_circle_name != '')
                    create_at = "At Circle: " + data_chainage_circle_name;
                if (data_chainage_division_name != '')
                    create_at = "At Division: " + data_chainage_division_name;
                if (data_chainage_sub_division_name != '')
                    create_at = "At Sub Division: " + data_chainage_sub_division_name;
                $('#txtLasetChainage').val("Last Chainage Created From " + data_chainage_from + " Km to " +
                    data_chainage_to + "KM at " + create_at);

                $('#road_name').val(data_road_name);
                $('#road_number').val(data_rd_number);


                var $button = $('<button>', {
                    text: 'View All Chainage Details',
                    id: 'myButton',
                    'data-url': '/asset-management/chainage/' +
                        data_rd_system_id,
                    click: function(e) {
                        e.preventDefault();
                        var url = $(this).data('url');
                        window.open(url, '_blank');
                    },
                    'data-id': data_rd_system_id
                });
                $('#last_chainage').empty();
                $('#last_chainage').append($button);
                $('#zone_cd').val(data_chainage_zone_cd);
                $('#circle_cd').val(data_chainage_circle_cd);
                $('#division_cd').val(data_chainage_division_cd);
                $('#sub_division_cd').val(data_chainage_sub_division_cd);
                $('#chainage_pk').val(data_chainage_pk);
                $('#road_id').val(data_rd_system_id);
                $('#user_office_type').val(data_user_office_type_cd);

                $('#chainage_step_id').val(data_chainage_step_id);
                $('#hidden_start_chainage_val').val(data_chainage_from);
                $('#hidden_end_chainage_val').val(data_chainage_to);
                $('#hidden_road_type').val(data_road_type);
                $('#road_length').val(data_road_length);
                $('#calculated_length').val(data_calculated_length);
                $("#start_chainage").val(data_chainage_from);
                $("#end_chainage").val(''); // User Will Input This Value

                // if (data_road_type == 'NH' && data_user_office_type_cd== "CO")
                // Changed as discussed with client
                if (data_user_office_type_cd == "CO")
                    $(".kml_file_class").show();
                else
                    $(".kml_file_class").hide();
                console.log('NH road found');

                $.ajax({

                    url: '/asset-management/chainage/get-nh-chainage/' + data_rd_system_id + "/" + data_chainage_pk,
                    type: 'GET',
                    success: function(response) {
                        console.log(response);

                        if (response.success) {
                            data = response.data;
                            console.log("data found at down level chainage");
                            if (data.zone_name != null)
                                create_at = "At Zone: " + data.zone_name;
                            if (data.circle_name != null)
                                create_at = "At Circle: " + data.circle_name;
                            if (data.division_name != null)
                                create_at = "At Division: " + data.division_name;
                            if (data.sub_div_name != null)
                                create_at = "At Sub Division: " +
                                data.sub_div_name;

                            $('#txtLasetChainage').val("Last Chainage Created From " + data
                                .chainage_from + " Km to " +
                                data.chainage_to + "KM at " + create_at);

                            $('#chainage_step_id').val(data.chainage_step_id);
                            $('#hidden_road_type').val(data.road_type);
                            // $('#hidden_start_chainage_val').val(data.chainage_from);
                            // $('#hidden_end_chainage_val').val(data.chainage_to);
                            $('#road_length').val(data.road_length);
                            $('#calculated_length').val(data.calculated_length);
                            $("#start_chainage").val(data.chainage_to);
                            if (data.chainage_step_id == 0) {

                                $("#start_chainage").val(0);
                            } else {
                                $("#start_chainage").val(data.chainage_to);
                            }
                            if ($('#hidden_road_type').val() == 'SR') {
                                $("#start_chainage").attr('readonly', 'readonly');
                            }
                        } else
                            console.log("No Chainage data Found at down level");

                    }
                });
            });
            $('#start_chainage').on('focusout', function() {
                const calculatedLength = $('#calculated_length');
                const remainingChainageField = $('#remaining');
                let calculatedLengthVal;
                let remainingChainage;
                const road_length = $('#hidden_end_chainage_val').val();

                let start_chainage_val = $('#start_chainage').val();

                if (start_chainage_val == "NaN" || start_chainage_val == "" || start_chainage_val == null) {
                    alert("Inavlid Start Chainage Value");

                    return;
                }


                const startChainage = parseFloat($(this).val());
                if ($('#chainage_step_id').val() == 0 && $('#hidden_road_type').val() == 'NH') {
                    if (startChainage > 0) {
                        calculatedLengthVal = startChainage + parseFloat(road_length);
                        $('#calculated_length').val(calculatedLengthVal); //update with new length
                    }
                }

                if ($('#chainage_step_id').val() == 0 && $('#hidden_road_type').val() == 'SR' && $(
                        '#hidden_start_chainage_val').val() == 0) {
                    if (startChainage != 0) {
                        alert("Start Chainage should Always be 0 for First Chainage");
                        $(this).val("0");

                        return;
                    }
                }
            });

            $('#end_chainage').on('focusout', function() {
                const remainingChainageField = $('#remaining');
                let calculatedLengthVal = parseFloat($('#calculated_length').val());
                let rd_len = $('#hidden_end_chainage_val').val();
                if (rd_len == "" || rd_len == null)
                    rd_len = $('#road_length').val();
                let road_length = parseFloat(rd_len);

                if (calculatedLengthVal > road_length)
                    road_length = calculatedLengthVal;
                let start_chainage_val = $('#start_chainage').val();
                let end_chainage_val = $('#end_chainage').val();

                if (start_chainage_val == "NaN" || start_chainage_val == "" || start_chainage_val == null) {
                    alert("Inavlid Start Chainage Value");
                    return;
                }
                if (end_chainage_val == "NaN" || end_chainage_val == "" || end_chainage_val == null) {
                    alert("Inavlid End Chainage Value");
                    return;
                }

                if (parseFloat($(this).val()) > road_length) {
                    alert("End chainage cannot be greater than : " + road_length);
                    $('#remaining').val('');
                    return;
                }

                if (parseFloat($(this).val()) < parseFloat(start_chainage_val)) {
                    alert("End Chainage Value Cannot be Lesser Than Start Chainage: " + start_chainage_val);
                    return;
                } else {
                    const remainingChainage = road_length - parseFloat($(this).val());
                    remainingChainageField.val(remainingChainage.toFixed(3));
                    $('#calculated_length').val($('#end_chainage').val());
                    return;
                }
            });



        });

        $('#store_chainage').on('submit', function(e) {

            e.preventDefault();
            let isValid = true;

            let rd_len = $('#hidden_end_chainage_val').val();
            if (rd_len == "" || rd_len == null)
                rd_len = $('#road_length').val();
            let road_length = parseFloat(rd_len);

            $('.error').text('');

            if ($('#hidden_road_type').val() === "NH" && $('#user_office_type').val() == "CO" && $('#road_kml_file')
                .val() === '') {
                // Prevent form submission

                alert('Please select a KML file for Division before submitting.');
                $('#road_kml_file_error').text('Please select a KML file for Division before submitting.');
                isValid = false;
            }

            if ($('#chainage_office').val() == "") {
                $('#chainage_office_error').text('This field Cannot be Empty!!!');
                isValid = false;
            }

            if ($('#chainage_step_id').val() == 0 && $('#hidden_road_type').val() == 'SR' && $(
                    '#hidden_start_chainage_val').val() == 0) {
                if ($('#start_chainage').val() != 0) {
                    $('#start_chainage_error').text("Start Chainage should Always be 0 for First Chainage");
                    isValid = false;
                }
            }
            if ($('#start_chainage').val() == "NaN" || $('#start_chainage').val() == "" || $('#start_chainage')
                .val() == null) {
                $('#start_chainage_error').text("Invalid Start Chainage Value");
                isValid = false;
            }

            if ($('#end_chainage').val() == "NaN" || $('#end_chainage').val() == "" || $('#end_chainage').val() ==
                null) {
                $('#end_chainage_error').text("Invalid End Chainage Value");
                isValid = false;
            } else if (parseFloat($('#end_chainage').val()) > road_length) {
                $('#end_chainage_error').text("End chainage cannot be greater than : " + road_length);
                isValid = false;
            }
            if (parseFloat($('#end_chainage').val()) < parseFloat($('#start_chainage').val())) {
                $('#end_chainage_error').text("End Chainage Value Cannot be Lesser Than Start Chainage: " + $(
                    '#start_chainage').val());
                isValid = false;
            }

            if ($('#remaining').val() == "NaN" || $('#remaining').val() == "" || $('#remaining').val() == null) {
                $('#remaining_error').text("Invalid Remaining Chainage");
                isValid = false;
            }
            if ($('#road_id').val() == "") {
                $('#road_id_error').text("Select A Road First");
                isValid = false;
            }







            if (isValid) {
                $('#store_chainage').submit();
            }
        });
    </script>

    <script>
        $(function() {
            $("#roadDetails").DataTable({
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#roadDetails_wrapper .col-md-11:eq(1)');
        });
    </script>
@endpush
