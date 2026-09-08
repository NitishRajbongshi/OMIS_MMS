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
                        <li class="breadcrumb-item">Finalize Road</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <!-- table content -->
        <div class="container-fluid mainBody ">
            <h6 class="p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT ROAD DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary">
                <input type="hidden" id="hdnGetJsonDataUrl" name="hdnGetJsonDataUrl"
                    value="{{ config('customconfigpath.GET_GEOJASON_DATA_OF_ROAD_API') }}" />
                <input type="hidden" id="hdnMergeJsonUrl" name="hdnMergeJsonUrl"
                    value="{{ config('customconfigpath.MERGE_TO_FINAL_GEOJASON_API') }}" />
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="draft_road_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th style="min-width: 1rem;" class="text-center">SlNo.</th>
                        <th style="min-width: 3rem;" class="text-center">Road ID</th>
                        <!-- saiful # 20-04-2026 # start -->
                        <th style="min-width: 3rem;" class="text-center">Project CD</th>
                        <!-- saiful # 20-04-2026 # end -->
                        <th style="min-width: 8rem;" class="text-center">Road Name</th>
                        <th style="min-width: 1rem;" class="text-center">Length (Km)</th>
                        <th style="min-width: 4rem;" class="text-center">Category</th>
                        <th style="min-width: 4rem;" class="text-center">Road Type</th>
                        <th style="min-width: 4rem;" class="text-center">Road Owner</th>
                        <th style="min-width: 4rem;" class="text-center">Division</th>
                        <th style="min-width: 5rem;" class="text-center">View In Map</th>
                        <th style="min-width: 6rem;" class="text-center">Action</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($roadDraftDetails as $roadDraftDetail)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $roadDraftDetail->rd_system_id }}
                                </td>
                                <!-- saiful # 20-04-2026 # start -->
                                <td>
                                    {{ $roadDraftDetail->project_cd }}
                                </td>
                                <!-- saiful # 20-04-2026 # end -->
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
                                <td class="text-uppercase">
                                    {{ $roadDraftDetail->division_name }}
                                </td>
                                <td class="text-uppercase">
                                    <div class="">
                                        <div style="margin-bottom: 0.1rem;">
                                            <button class="viewInMapBtn btn btn-outline-primary btn-xs text-xs"
                                                style="width: 5rem;" data-id="{{ $roadDraftDetail->rd_system_id }}"
                                                id="{{ $roadDraftDetail->rd_system_id }}"
                                                data-road-name-id="{{ $roadDraftDetail->rd_name }}"
                                                data-div-name-id="{{ $roadDraftDetail->division_name }}">
                                                <i class='bx bx-show'></i>View In Map
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="">
                                        <div style="margin-bottom: 0.1rem;">
                                            <button class="approveBtn btn btn-outline-primary btn-xs text-xs enableMouseEvent"
                                                style="width: 4rem;" data-id="{{ $roadDraftDetail->rd_system_id }}"
                                                data-div-cd="{{ $roadDraftDetail->division_cd }}"
                                                id="btnApprove_{{ $roadDraftDetail->rd_system_id }}"
                                                onmouseover="showToolTip(this);" disabled="disabled">
                                                Approve
                                            </button>
                                            <button class="rejectBtn btn btn-outline-danger btn-xs text-xs" style="width: 4rem;"
                                                data-id="{{ $roadDraftDetail->rd_system_id }}"
                                                id="btnReject_{{ $roadDraftDetail->rd_system_id }}" disabled>
                                                Reject
                                            </button>
                                            {{-- <span class="counter">0</span> --}}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php    $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <div class="modal fade" id="mapModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="modal-title" id="mapTitle">Nagaland PWD Road Map, </label>
                    &nbsp;&nbsp;&nbsp;
                    <label id="lblRoadId" name="lblRoadId">

                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label id="lblRoadName" name="lblRoadName">

                    </label>

                    &nbsp;&nbsp;&nbsp;
                    <label id="lblDivName" name="lblDivName">

                    </label>
                    <button type="button" class="btn-close" id="btnClose" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="row justify-content-center align-item-center">
                    <div class="col-md-10 mb-1">
                        <div class="modal-body modal-dialog-centered" id='map' style='width: 100%; height: 500px;'>
                            <form action="#" id="frm_map_modal">
                                @csrf

                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" id='mapFooter'>

                    <div class="col-md-7">
                        <div class="form-check form-switch">

                            <input class="form-check-input form-control" type="checkbox" name="mapShowHideSwitch"
                                id="mapShowHideSwitch" role="switch" checked />
                            <label for="mapShowHideSwitch" id="lblShowHideMap">Show/Hide Current
                                Road</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-1">
                        <label id="lblMapOK">Is the Road Correct:</label>
                        <input type="hidden" id="hdnRoadId" name="hdnRoadId" value="" />
                        <button class="mapCorrectBtn btn btn-outline-primary btn-xs text-xs" style="width: 4rem;">
                            Yes
                        </button>
                        <button class="mapNotCorrectBtn btn btn-outline-danger btn-xs text-xs" style="width: 4rem;">
                            No
                        </button>
                    </div>


                </div>
            </div>
        </div>
    </div>

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
        var approved_geojson_data = null;
        var draft_road_geojson_data = null;
        var data_layer = null;
        var data_layer_2 = null;
        var all_states_geojson_data = null;
        var sh_nh_mdr_geojson_data = null;
        let map;
        $(function () {

            (g => {
                var h, a, k, p = "The Google Maps JavaScript API",
                    c = "google",
                    l = "importLibrary",
                    q = "__ib__",
                    m = document,
                    b = window;
                b = b[c] || (b[c] = {});
                var d = b.maps || (b.maps = {}),
                    r = new Set,
                    e = new URLSearchParams,
                    u = () => h || (h = new Promise(async (f, n) => {
                        await (a = m.createElement("script"));
                        e.set("libraries", [...r] + "");
                        for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[
                            k]);
                        e.set("callback", c + ".maps." + q);
                        a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                        d[q] = f;
                        a.onerror = () => h = n(Error(p + " could not load."));
                        a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                        m.head.append(a)
                    }));
                d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u()
                    .then(
                        () => d[l](f, ...n))
            })({
                key: "AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM",
                v: "beta",
                // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
                // Add other bootstrap parameters as needed, using camel case.
            });

            fetch('/getAllStatesRoadsGeoJsonData')
                .then(response => {
                    console.log("HTTP Response Status:", response.status); // Debug status

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    return response.json();
                })
                .then(data => {
                    if (data && data.status) {
                        let compressedArray = Uint8Array.from(atob(data.all_states_geojson_data), c => c
                            .charCodeAt(0));

                        // Decompress using pako
                        let decompressedData = pako.inflate(compressedArray, {
                            to: 'string'
                        });
                        // all_states_geojson_data = JSON.parse(decompressedData);
                        all_states_geojson_data = decompressedData;
                    } else {
                        console.error("Invalid response format");
                    }
                })
                .catch(error => console.error("Fetch error:", error));


            fetch('/getAllSHNHMDRRoadsGeoJsonData')
                .then(response => {
                    console.log("HTTP Response Status:", response.status); // Debug status

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    return response.json();
                })
                .then(data => {
                    if (data && data.status) {
                        let compressedArray = Uint8Array.from(atob(data.all_sh_nh_mdr_geojson_data), c => c
                            .charCodeAt(0));

                        // Decompress using pako
                        let decompressedData = pako.inflate(compressedArray, {
                            to: 'string'
                        });
                        // sh_nh_mdr_geojson_data = JSON.parse(decompressedData);
                        sh_nh_mdr_geojson_data = decompressedData;
                    } else {
                        console.error("Invalid response format");
                    }
                })
                .catch(error => console.error("Fetch error:", error));
        });


        $("#mapShowHideSwitch").change(function () {

            var ischecked = $(this).is(':checked');
            if (!ischecked)
                data_layer_2.setStyle({
                    visible: false
                });
            else {
                data_layer_2.setStyle({
                    visible: true
                });
                data_layer_2.setStyle(function (feature) {
                    var roadCatg = feature.getProperty('road_category');
                    var strokeColor;
                    var fillColor;
                    var strokeWeight;
                    var strokeOpacity;
                    switch (roadCatg) {
                        case 'NH':
                            strokeColor = '#ffff00';
                            fillColor = '#ffff00';
                            strokeWeight = 2.0;
                            break;
                        case 'SH':
                        case 'State Highway':
                            strokeColor = '#005500';
                            fillColor = '#005500';
                            strokeWeight = 1.7;
                            break;
                        case 'MDR':
                        case 'Major District Roads':
                            strokeColor = '#000000';
                            fillColor = '#000000';
                            strokeWeight = 1.7;
                            break;
                        case 'ODR':
                            strokeColor = '#00007f';
                            fillColor = '#00007f';
                            strokeWeight = 1.0;
                            break;
                        case 'VR':
                            strokeColor = '#ff5500';
                            fillColor = '#ff5500';
                            strokeWeight = 1.0;
                            break;
                        case 'ALR':
                            strokeColor = '#55ff7f';
                            fillColor = '#55ff7f';
                            strokeWeight = 1.0;
                            break;
                        case 'UR':
                            strokeColor = '#aa00ff';
                            fillColor = '#aa00ff';
                            strokeWeight = 1.0;
                            break;
                        case 'RD':
                            strokeColor = '#ffaa7f';
                            fillColor = '#ffaa7f';
                            strokeWeight = 1.0;
                            break;
                        case 'INTER':
                            strokeColor = '#FA0017';
                            fillColor = '#FA0017';
                            strokeWeight = 1.0;
                            break;


                        default:
                            strokeColor = "green";
                            fillColor = "green";
                            strokeWeight = 2.0;

                    }

                    return {
                        strokeColor: strokeColor,
                        fillColor: fillColor,
                        strokeWeight: 4,
                        strokeOpacity: 1.0,
                        fillOpacity: 0.3
                    };
                });
            }
        });


        $(function () {
            $("#draft_road_details_table")
                .DataTable({
                    buttons: [
                        "csv",
                        "excel",
                    ],
                })
                .buttons()
                .container()
                .appendTo(".mis-btn-rd");
        });

        // aprove a single housing data
        $('.approveBtn').on('click', function (event) {
            // var button = $(event.relatedTarget);
            // var roadID = button.data('id');
            // var div_cd = button.data('div-cd');

            const roadID = $(this).data('id');
            const div_cd = $(this).data('div-cd');
            console.log("div_cd at Aprove road:  " + div_cd);
            console.log("roadID at Aprove road:  " + roadID);

            if (roadID) {
                const final = confirm("Click OK to continue");
                if (final) {
                    $.ajax({
                        type: 'GET',
                        url: "/asset-management/freeze-road-details/" + roadID,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        cache: false,
                        success: function (response) {
                            if (response.status === 200) {
                                showSuccessModal(
                                    "Selectd Road is Approved Successfully!");
                                //No need to call this ajax as merging of road geojson data done by cronJob
                                // data = {
                                //     "road_id": roadID,
                                //     "division_cd": div_cd
                                // };
                                // $.ajax({
                                //     type: 'POST',
                                //     url: $('#hdnMergeJsonUrl').val(),
                                //     data: JSON.stringify(data),
                                //     contentType: "application/json; charset=utf-8",
                                //     crossDomain: true,
                                //     dataType: "json",
                                //     headers: {
                                //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                //             'content')
                                //     },
                                //     cache: false,
                                //     success: function (response, status, jqXHR) {

                                //         if (response.status === true || response.data=== true) {
                                //             console.log(
                                //                 "geojson data of this road appended to Final GeoJson File"
                                //             );
                                //             showSuccessModal(
                                //                 "Selectd Road is Approved Successfully!");

                                //         } else {
                                //             console.log(
                                //                 "Could not append to Final GeoJson File"
                                //             );
                                //             data = {
                                //                 "road_id": roadID
                                //             };
                                //             $.ajax({
                                //                 type: 'POST',
                                //                 url: "/moveApprovedRoadToDraftRoad",
                                //                 data: JSON.stringify(
                                //                     data),
                                //                 contentType: "application/json; charset=utf-8",
                                //                 crossDomain: true,
                                //                 dataType: "json",
                                //                 headers: {
                                //                     'X-CSRF-TOKEN': $(
                                //                         'meta[name="csrf-token"]'
                                //                     ).attr(
                                //                         'content')
                                //                 },
                                //                 cache: false,
                                //                 success: function (response, status,
                                //                     jqXHR) {

                                //                     if (response.status ===
                                //                         true) {
                                //                         console.log(
                                //                             "Finalized Reverted Back To Unfinalised!!!"
                                //                         );
                                //                         showDashboardModal(
                                //                             response.message
                                //                         );

                                //                     } else {
                                //                         console.log(
                                //                             "Could Not Revert Back the Finalised Data, Sorry!!!"
                                //                         );
                                //                         showDashboardModal(
                                //                             response.message
                                //                         );
                                //                     }
                                //                 }
                                //             });
                                //         }
                                //     },
                                //     error: function (error) {
                                //         console.log("Some Technical Issue Occured while approving the Road!!");
                                //         console.log(error);
                                //         showDashboardModal("Some Technical Issue Occured while approving the Road!!");
                                //     }
                                // });

                            } else {
                                showDashboardModal(response.message);
                            }
                        }
                    })
                } else {
                    alert('Cancel to finalize');
                }
            }
        });

        // reject a single housing data
        $('.rejectBtn').on('click', function () {
            const roadID = $(this).data('id');
            if (roadID) {
                const final = confirm("Click OK to confirm rejection");

                // const final = 
                if (final) {
                    let reason = prompt("Please Enter Reason of Rejection", "");
                    if (reason != null) {
                        $.ajax({
                            type: 'GET',
                            url: "/asset-management/reject-road-details/" + roadID + "/" + reason,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            cache: false,
                            success: function (response) {
                                console.log(response);
                                if (response.status === 200) {
                                    showSuccessModal(response.message);
                                } else {
                                    showDashboardModal(response.message);
                                }
                            }
                        })
                    } else
                        alert('Reason Of Rejection Not Entered, Road Not Rejected');
                } else {
                    alert('Cancel the rejection');
                }
            }
        });

        $('.viewInMapBtn').on('click', function () {
            // var sh_nh_mdr_cached_geojson_data = @json($shnhmdrDataSetResult);
            // sh_nh_mdr_geojson_data = sh_nh_mdr_cached_geojson_data.sh_nh_mdr_geojson_data;
            const roadID = $(this).data('id');
            const roadName = $(this).data('road-name-id');
            const divName = $(this).data('div-name-id');
            if (roadID) {

                $.ajax({
                    type: 'GET',
                    url: "/getDraftedRoadsWithDivisionRoads/" + roadID,
                    contentType: "application/json; charset=utf-8",
                    crossDomain: true,
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    cache: false,
                    success: function (response, status, jqXHR) {
                        if (response.status === true) {
                            approved_geojson_data = response.approved_geojson_data;
                            draft_road_geojson_data = response.draft_road_geojson_data;
                            div_lat = response.div_lat;
                            div_lng = response.div_lng;
                            console.log("div_lat , div_lng: " + div_lat + " " + div_lng);
                            if (approved_geojson_data == null) {
                                approved_geojson_data = all_states_geojson_data;
                                console.log(
                                    "Division Data could not fetched, Hence Showing All States Geo Json data"
                                );
                            }
                            $('#mapModal').on('shown.bs.modal', function (e) {

                                $('#hdnRoadId').prop("value", roadID);
                                $('#lblRoadId').text("  Road Id : " + roadID);
                                $('#lblRoadName').text("  Road Name : " + roadName);
                                $('#lblDivName').text("  Division Name : " + divName);
                                initMap(JSON.parse(approved_geojson_data),
                                    JSON.parse(draft_road_geojson_data), JSON.parse(
                                        sh_nh_mdr_geojson_data), div_lat, div_lng);
                            }).modal('show');

                        } else {
                            showDashboardModal(response.coordinates);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                        console.log(
                            "Some Technical Issue!!Map Data Could Not Fetched From Server,Please Contact Administrator!!"
                        );
                        showDashboardModal(
                            "Some Technical Issue!!Map Data Could Not Fetched From Server,Please Contact Administrator!!"
                        );

                    }
                });
            }
        });

        const styleNH = {
            strokeColor: "#ffff00",
            strokeWeight: 2.0,
            strokeOpacity: 1.0,
            fillColor: "#ffff00",
            fillOpacity: 0.3,
        };
        const styleSH = {
            strokeColor: "#005500",
            strokeWeight: 1.7,
            strokeOpacity: 1.0,
            fillColor: "#005500",
            fillOpacity: 0.3,
        };
        const styleMDR = {
            strokeColor: "#000000",
            strokeWeight: 1.7,
            strokeOpacity: 1.0,
            fillColor: "#000000",
            fillOpacity: 0.3,
        };
        const styleODR = {
            strokeColor: "#00007f",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#00007f",
            fillOpacity: 0.3,
        };
        const styleVR = {
            strokeColor: "#ff5500",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#ff5500",
            fillOpacity: 0.3,
        };
        const styleALR = {
            strokeColor: "#55ff7f",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#55ff7f",
            fillOpacity: 0.3,
        };
        const styleUR = {
            strokeColor: "#aa00ff",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#aa00ff",
            fillOpacity: 0.3,
        };
        const styleRD = {
            strokeColor: "#ffaa7f",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#ffaa7f",
            fillOpacity: 0.3,
        };
        const styleINTER = {
            strokeColor: "#FA0017",
            strokeWeight: 1.0,
            strokeOpacity: 1.0,
            fillColor: "#FA0017",
            fillOpacity: 0.3,
        };

        async function initMap(approved_geojson_data, draft_road_geojson_data, sh_nh_mdr_geojson_data, lt, ln) {
            const {
                Map
            } = await google.maps.importLibrary("maps");


            let startPosition = {
                lat: 26.094757374299146,
                lng: 94.58979407214116,
            };

            if (lt != null && ln != null)
                startPosition = {
                    lat: parseFloat(lt),
                    lng: parseFloat(ln),
                };

            if (map) {
                map = null;
            }
            map = new Map(document.getElementById('map'), {
                center: startPosition,
                zoom: 9
            });

            map.setZoom(Math.max(map.getZoom(), 10));
            try {

                data_layer = new google.maps.Data({
                    map: map
                });
                data_layer_2 = new google.maps.Data({
                    map: map
                });
                data_layer_3 = new google.maps.Data({
                    map: map
                });
                data_layer.addGeoJson(approved_geojson_data);
                data_layer_2.addGeoJson(draft_road_geojson_data);
                data_layer_3.addGeoJson(sh_nh_mdr_geojson_data);
            } catch (e) {
                console.log(e);
            }

            data_layer.setStyle(function (feature) {
                var roadCatg = feature.getProperty('road_category');
                var strokeColor;
                var fillColor;
                var strokeWeight;
                var strokeOpacity;
                switch (roadCatg) {

                    case 'NH':
                        strokeColor = '#ffff00';
                        fillColor = '#ffff00';
                        strokeWeight = 2.0;
                        break;
                    case 'SH':
                    case 'State Highway':
                        strokeColor = '#005500';
                        fillColor = '#005500';
                        strokeWeight = 1.7;
                        break;
                    case 'MDR':
                    case 'Major District Roads':
                        strokeColor = '#000000';
                        fillColor = '#000000';
                        strokeWeight = 1.7;
                        break;
                    case 'ODR':
                        strokeColor = '#00007f';
                        fillColor = '#00007f';
                        strokeWeight = 1.0;
                        break;
                    case 'VR':
                        strokeColor = '#ff5500';
                        fillColor = '#ff5500';
                        strokeWeight = 1.0;
                        break;
                    case 'ALR':
                        strokeColor = '#55ff7f';
                        fillColor = '#55ff7f';
                        strokeWeight = 1.0;
                        break;
                    case 'UR':
                        strokeColor = '#aa00ff';
                        fillColor = '#aa00ff';
                        strokeWeight = 1.0;
                        break;
                    case 'RD':
                        strokeColor = '#ffaa7f';
                        fillColor = '#ffaa7f';
                        strokeWeight = 1.0;
                        break;
                    case 'INTER':
                        strokeColor = '#FA0017';
                        fillColor = '#FA0017';
                        strokeWeight = 1.0;
                        break;


                    default:
                        strokeColor = "green";
                        fillColor = "green";
                        strokeWeight = 2.0;

                }

                return {
                    strokeColor: strokeColor,
                    fillColor: fillColor,
                    strokeWeight: strokeWeight,
                    strokeOpacity: 1.0,
                    fillOpacity: 0.3
                };
            });


            data_layer_2.setStyle(function (feature) {
                var roadCatg = feature.getProperty('road_category');
                var strokeColor;
                var fillColor;
                var strokeWeight;
                var strokeOpacity;
                switch (roadCatg) {
                    case 'NH':
                        strokeColor = '#ffff00';
                        fillColor = '#ffff00';
                        strokeWeight = 2.0;
                        break;
                    case 'SH':
                    case 'State Highway':
                        strokeColor = '#005500';
                        fillColor = '#005500';
                        strokeWeight = 1.7;
                        break;
                    case 'MDR':
                    case 'Major District Roads':
                        strokeColor = '#000000';
                        fillColor = '#000000';
                        strokeWeight = 1.7;
                        break;
                    case 'ODR':
                        strokeColor = '#00007f';
                        fillColor = '#00007f';
                        strokeWeight = 1.0;
                        break;
                    case 'VR':
                        strokeColor = '#ff5500';
                        fillColor = '#ff5500';
                        strokeWeight = 1.0;
                        break;
                    case 'ALR':
                        strokeColor = '#55ff7f';
                        fillColor = '#55ff7f';
                        strokeWeight = 1.0;
                        break;
                    case 'UR':
                        strokeColor = '#aa00ff';
                        fillColor = '#aa00ff';
                        strokeWeight = 1.0;
                        break;
                    case 'RD':
                        strokeColor = '#ffaa7f';
                        fillColor = '#ffaa7f';
                        strokeWeight = 1.0;
                        break;
                    case 'INTER':
                        strokeColor = '#FA0017';
                        fillColor = '#FA0017';
                        strokeWeight = 1.0;
                        break;


                    default:
                        strokeColor = "green";
                        fillColor = "green";
                        strokeWeight = 2.0;

                }

                return {
                    strokeColor: strokeColor,
                    fillColor: fillColor,
                    strokeWeight: 4,
                    strokeOpacity: 1.0,
                    fillOpacity: 0.3
                };
            });

            data_layer_3.setStyle(function (feature) {
                var roadCatg = feature.getProperty('road_category');
                var strokeColor;
                var fillColor;
                var strokeWeight;
                var strokeOpacity;
                switch (roadCatg) {

                    case 'NH':
                        strokeColor = '#ffff00';
                        fillColor = '#ffff00';
                        strokeWeight = 2.0;
                        break;
                    case 'SH':
                    case 'State Highway':
                        strokeColor = '#005500';
                        fillColor = '#005500';
                        strokeWeight = 1.7;
                        break;
                    case 'MDR':
                    case 'Major District Roads':
                        strokeColor = '#000000';
                        fillColor = '#000000';
                        strokeWeight = 1.7;
                        break;
                    case 'ODR':
                        strokeColor = '#00007f';
                        fillColor = '#00007f';
                        strokeWeight = 1.0;
                        break;
                    case 'VR':
                        strokeColor = '#ff5500';
                        fillColor = '#ff5500';
                        strokeWeight = 1.0;
                        break;
                    case 'ALR':
                        strokeColor = '#55ff7f';
                        fillColor = '#55ff7f';
                        strokeWeight = 1.0;
                        break;
                    case 'UR':
                        strokeColor = '#aa00ff';
                        fillColor = '#aa00ff';
                        strokeWeight = 1.0;
                        break;
                    case 'RD':
                        strokeColor = '#ffaa7f';
                        fillColor = '#ffaa7f';
                        strokeWeight = 1.0;
                        break;
                    case 'INTER':
                        strokeColor = '#FA0017';
                        fillColor = '#FA0017';
                        strokeWeight = 1.0;
                        break;


                    default:
                        strokeColor = "green";
                        fillColor = "green";
                        strokeWeight = 2.0;

                }

                return {
                    strokeColor: strokeColor,
                    fillColor: fillColor,
                    strokeWeight: strokeWeight,
                    strokeOpacity: 1.0,
                    fillOpacity: 0.3
                };
            });
        }

        $('.mapCorrectBtn').on('click', function () {

            var rdid = $('#hdnRoadId').val();

            $('#btnApprove_' + rdid).prop("disabled", false);
            $('#btnReject_' + rdid).prop("disabled", false);
            $('#mapModal').modal('toggle');
        });

        $('.mapNotCorrectBtn').on('click', function () {
            var rdid = $('#hdnRoadId').val();
            $('#btnApprove_' + rdid).prop("disabled", true);
            $('#btnReject_' + rdid).prop("disabled", false);
            $('#mapModal').modal('toggle');
        });

        $('#btnClose').on('click', function () {
            var rdid = $('#hdnRoadId').val();
            $('#btnApprove_' + rdid).prop("disabled", true);
            $('#btnReject_' + rdid).prop("disabled", true);
        });

        $("#mapModal").on("fade.bs.modal", function () {
            alert("fading");
        });

        $(function () {
            $(".enableMouseEvent:disabled").wrap(function () {
                // $(this).css('cursor', 'pointer').attr('title', 'This is a hover textxxxxxxxxxxxxx.');
                return '<div onmouseover="' + $(this).attr('onmouseover') + '" />';

            });
        });

        function showToolTip(el) {
            val = $(el).next(".counter").html(function (i, val) {
                return val + 1

            });
            $(this).css('cursor', 'pointer').attr('title', 'This is a hover.');
        }
        // $(".approveBtn").hover(function() {
        //     $(this).css('cursor', 'pointer').attr('title', 'This is a hover text.');
        // }, function() {
        //     $(this).css('cursor', 'auto');
        // });
    </script>
@endpush