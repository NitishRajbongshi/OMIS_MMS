@extends('layouts.app')
@section('content')
<main class="command-center">
    <section class="command-heading">
        <div>
            <div class="command-breadcrumb">
                <i class="fas fa-house"></i>
                <span><a href="{{ route('dashboard') }}" style="color: inherit; text-decoration: none;">Dashboard</a></span>
                <span>/</span>
                <span><a href="{{ route('manage.housing.index') }}" style="color: inherit; text-decoration: none;">Manage Housing</a></span>
                <span>/</span>
                <strong>Finalize Housing Draft Data</strong>
            </div>
            <h1>Finalize Housing Draft Data</h1>
            <p>Review and finalize housing draft records under {{ session('department') ?: 'Nagaland P.W.D (Housing)' }}</p>
        </div>
        @if (session('dataEntry') == 1)
            <div class="command-actions">
                <a href="{{ route('manage.housing.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Housing Details
                </a>
            </div>
        @endif
    </section>

    <!-- Main content -->
    <section class="content">
        <x-building-flash-message />
        
        <article class="command-panel">
            <header>
                <div>
                    <span>Government Buildings (Housing)</span>
                    <h2>List of Drafted Government Buildings</h2>
                </div>
            </header>
            <div class="table-responsive p-3">
                <table class="table table-striped w-100 text-xs user_list" id="building_details_table">
                    <thead>
                        <tr>
                            <th class="text-center">Sl No.</th>
                            <th class="text-center">Building ID</th>
                            <th class="text-center">Quarter Number</th>
                            <th class="text-center">Building Name</th>
                            <th class="text-center">Building Type</th>
                            <th class="text-center">Maintained by NPWD?</th>
                            <th class="text-center">Residential/Non-Residential</th>
                            <th class="text-center">Asset Owning Department</th>
                            <th class="text-center">View In Map</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; ?>
                    @foreach ($buildingDetails as $item)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td class="text-center">{{ $item->building_system_cd }}</td>
                            <td class="text-center">{{ $item->qtr_no ? $item->qtr_no : 'NA' }}</td>
                            <td>{{ $item->bld_qtr_name ? $item->bld_qtr_name : 'NA' }}</td>
                            <td>{{ $item->building_type_descr }}</td>
                            <td class="text-center">
                                <span class="badge {{ $item->is_maintained_by_npwd == 'Y' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $item->is_maintained_by_npwd == 'Y' ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ $item->building_class_descr }}</td>
                            <td>{{ $item->owning_dept_name }}</td>
                            <td class="text-center">
                                <button class="viewInMapBtn btn btn-info btn-xs"
                                    data-id="{{ $item->building_system_cd }}"
                                    id="{{ $item->building_system_cd }}">
                                    <i class="fas fa-map-location-dot"></i> View Map
                                </button>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="approveBtn btn btn-success btn-xs"
                                        id="btnApprove_{{ $item->building_system_cd }}"
                                        data-id="{{ $item->building_system_cd }}" disabled="disabled">
                                        <i class="fas fa-check-circle"></i> Approve
                                    </button>
                                    <button class="rejectBtn btn btn-danger btn-xs"
                                        id="btnReject_{{ $item->building_system_cd }}"
                                        data-id="{{ $item->building_system_cd }}" disabled>
                                        <i class="fas fa-times-circle"></i> Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>

    <form id="finalizedFormData" class="d-none">
        @csrf
        <input type="hidden" name="userId" id="userId" value="{{ $user->id }}">
    </form>
</section>

<div class="modal fade" id="mapModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <label class="modal-title" id="mapTitle">
                        Nagaland Housing Map - Building Location
                    </label>
                    <div class="text-muted text-xs mt-1" id="lblBuildingInfo"></div>
                </div>
                <button type="button" class="btn-close" id="btnClose" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div id="map" style="width: 100%; height: 500px;"></div>
            </div>

            <div class="modal-footer justify-content-between align-items-center" id="mapFooter">
                <div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="mapShowHideSwitch" id="mapShowHideSwitch" checked />
                        <label class="form-check-label ms-2" for="mapShowHideSwitch" id="lblShowHideMap">Show/Hide Current Building</label>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span id="lblMapOK" class="text-sm me-2">Are the coordinates correct?</span>
                    <input type="hidden" id="buildingID" name="buildingID" value="" />
                    <button class="mapCorrectBtn btn btn-primary btn-sm">
                        <i class="fas fa-check"></i> Yes
                    </button>
                    <button class="mapNotCorrectBtn btn btn-danger btn-sm">
                        <i class="fas fa-times"></i> No
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <x-success-modal />
    <x-warning-modal />
</main>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <style>
        .building-marker {
            background-image: url("/images/marker_icons/home_1.png");
            background-size: cover;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            /*border: 2px solid #000;*/
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    {{-- Script for Show JQuery Table --}}
    <script>
        $(function() {
            // $("#building_details_table").DataTable({}).buttons().container().appendTo(
            //     '#building_details_table_wrapper.mt-3:eq(1)');
            $("#building_details_table").DataTable({

                })
                .buttons()
                .container()
                .appendTo(".mis-btn-rd");
        });

        // $(function() {
        //     $("#freezed_building_details_table").DataTable();
        // });
    </script>

    {{-- Updation --}}
    <script>
        $('.viewInMapBtn').on('click', function() {
            var buildingID = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: "/asset-management/housing-coordinates/" + buildingID,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache: false,
                success: function(response) {
                    // console.log(response.result.lat);
                    if (response.status === 200) {
                        // showSuccessModal(response.message);
                        building_id = buildingID
                        lat = parseFloat(response.result.lat);
                        lon = parseFloat(response.result.lon);
                        var building_name = '';
                        var qtr_no = '';
                        if (response.qtr_no != "")
                            building_name = response.qtr_no;
                        if (response.bld_qtr_name != "")
                            building_name = response.bld_qtr_name;
                        $('#mapModal').on('shown.bs.modal', function(e) {
                            $('#buildingID').prop("value", buildingID);

                            $('#lblBuildingInfo').text("Geolocation: [" + lat +
                                "," + lon + "],  Building Id : " +
                                buildingID + ", Building name/ QtrNo.: " +
                                building_name);
                            initMap(lat, lon, building_name, buildingID,
                                response.all_approved_cordinated);
                        }).modal('show');
                    } else {
                        showDashboardModal(response.message);
                    }
                }
            })
        });
        // $(document).ready(function() {
        // START: Google Map
        var map;
        let lat = null;
        let lon = null;
        let building_cd = null;
        let bld_markers;
        let markers_to_toggle = [];

        const map_id = "3d95c646e2e4e33c";
        async function initMap(bld_lat, bld_lon, bld_bld_name_qtr_no, building_system_cd,
            arr_other_cordinates) {
            building_cd = building_system_cd;
            const {
                Map
            } = await google.maps.importLibrary("maps");

            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");

            const startPosition = {
                lat: 26.094757374299146,
                lng: 94.58979407214116,
            };

            const bld_marker_position = {
                lat: parseFloat(bld_lat),
                lng: parseFloat(bld_lon),
            };

            const styleOptions = {
                strokeColor: "blue",
                strokeWeight: 2,
                strokeOpacity: 1,
                fillColor: "green",
                fillOpacity: 0.3,
            };


            map = new Map(document.getElementById("map"), {
                zoom: 9,
                center: bld_marker_position,
                mapId: map_id
            });


            var approved_marker_data = [];
            for (var i = 0; i < arr_other_cordinates.length; i++) {
                var qtr_name_no = "";
                if (arr_other_cordinates[i].building_class_cd == "0")
                    qtr_name_no = "Quarter No: " + arr_other_cordinates[i].qtr_no;
                else
                    qtr_name_no = "Quarter Name: " + arr_other_cordinates[i].bld_qtr_name;
                var marker_json_data = {
                    id: arr_other_cordinates[i].building_system_cd,
                    position: {
                        lat: parseFloat(arr_other_cordinates[i].lon),
                        lng: parseFloat(arr_other_cordinates[i].lat)
                    },
                    title: qtr_name_no
                };

                const approved_marker = new google.maps.marker.AdvancedMarkerElement({
                    position: marker_json_data.position,
                    map: map,
                    title: marker_json_data.title,
                    // content: buildingIcon
                });
                approved_marker.id = marker_json_data.id;
                approved_marker_data.push(approved_marker);

            }

            const buildingIcon = document.createElement("div");
            buildingIcon.className = "building-marker";



            bld_markers = new AdvancedMarkerElement({
                position: bld_marker_position,
                map: map,
                title: bld_bld_name_qtr_no,
                content: buildingIcon //this is new way to set own marker icon in AdvancedMarkerElement
            });

            bld_markers.id = building_cd;

            markers_to_toggle.push(bld_markers);

            bld_markers.addListener("click", function() {
                alert("Showing Buildings Information!");
            });
        }



        // END: Google Map
        $("#mapShowHideSwitch").change(function() {
            var ischecked = $(this).is(':checked');

            const mrkr = markers_to_toggle.find((m) => m.id === building_cd);
            if (!ischecked) {
                if (bld_markers) {

                    bld_markers.map = null; // Removes the marker from the map
                }
            } else {
                if (bld_markers) {
                    bld_markers.map = map; // Adds the marker back to the map
                }
            }


        });
        $('.mapCorrectBtn').on('click', function() {
            var rdid = $('#buildingID').val();
            $('#btnApprove_' + rdid).prop("disabled", false);
            $('#btnReject_' + rdid).prop("disabled", false);
            $('#mapModal').modal('toggle');
        });

        $('.mapNotCorrectBtn').on('click', function() {
            var rdid = $('#buildingID').val();
            $('#btnApprove_' + rdid).prop("disabled", true);
            $('#btnReject_' + rdid).prop("disabled", false);
            $('#mapModal').modal('toggle');
        });

        $('form.updateBuilding').on("submit", function(e) {
            e.preventDefault();
            let location = "{{ route('manage.housing.index') }}";
            var form = $(this);
            var formData = form.serialize();
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
                    if (response.status == 'success') {
                        Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            })
                            .then(() => {
                                window.location.replace(location)
                            });
                    } else if (response.status === 'failed') {
                        Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            })
                            .then(() => {
                                window.location.replace(location)
                            });
                    } else {
                        Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something Went Wrong!',
                                showConfirmButton: true,
                                timer: 3000
                            })
                            .then(() => {
                                window.location.replace(location)
                            });
                    }
                }
            });
        });

        $('#finalizedData').on('click', () => {
            const status = confirm("Are you sure?");
            if (status) {
                const final = confirm("Click OK to procced!");
                if (final) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('freezeBuilding') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: $('#finalizedFormData').serialize(),
                        cache: false,
                        success: function(response) {
                            console.log(response);
                            if (response.status === 'success') {
                                Swal.fire({
                                        icon: 'success',
                                        title: 'success',
                                        text: response.message,
                                        showConfirmButton: true,
                                        timer: 3000
                                    })
                                    .then(() => {
                                        window.location.replace(location)
                                    });
                            }

                            if (response.status === 'failed') {
                                Swal.fire({
                                        icon: 'failed',
                                        title: 'failed',
                                        text: response.message,
                                        showConfirmButton: true,
                                        timer: 3000
                                    })
                                    .then(() => {
                                        window.location.replace(location)
                                    });
                            }

                            if (response.status === 'error') {
                                Swal.fire({
                                        icon: 'failed',
                                        title: 'error',
                                        text: 'Internal Server Error',
                                        showConfirmButton: true,
                                        timer: 3000
                                    })
                                    .then(() => {
                                        window.location.replace(location)
                                    });
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

        // aprove a single housing data
        $('.approveBtn').on('click', function() {
            const buildingId = $(this).data('id');
            if (buildingId) {
                const final = confirm("Click OK to continue");
                if (final) {
                    $.ajax({
                        type: 'GET',
                        url: "/asset-management/freeze-building-details/" + buildingId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        cache: false,
                        success: function(response) {
                            console.log(response);
                            if (response.status === 200) {
                                showSuccessModal(response.message);
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
        $('.rejectBtn').on('click', function() {
            const buildingId = $(this).data('id');
            if (buildingId) {
                const final = confirm("Click OK to confirm rejection");
                if (final) {
                    let reason = prompt("Please Enter Reason of Rejection", "");
                    if (reason != null) {
                        $.ajax({
                            type: 'GET',
                            url: "/asset-management/reject-building-details/" + buildingId + "/" + reason,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            cache: false,
                            success: function(response) {
                                console.log(response);
                                if (response.status === 200) {
                                    showSuccessModal(response.message);
                                } else {
                                    showDashboardModal(response.message);
                                }
                            }
                        })
                    } else {
                        alert('Reason Of Rejection Not Entered, Building Not Rejected');
                    }
                } else {
                    alert('Cancel the rejection');
                }
            }
        });
        // });
    </script>
    <script>
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
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                    e.set("callback", c + ".maps." + q);
                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                    d[q] = f;
                    a.onerror = () => h = n(Error(p + " could not load."));
                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                    m.head.append(a)
                }));
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
                d[l](f, ...n))
        })({
            key: "AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM",
            v: "weekly",
        });
    </script>
@endpush
