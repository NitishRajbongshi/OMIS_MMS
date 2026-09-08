$(document).ready(function () {
    $("#road_list").select2();
    let lastClickedFeatureIds = [];
    let lastInteractedFeatureIds = [];
    let markers = [];
    let marker = null;
    let map = null;
    let all_states_geojson_data = null;
    var data_layer;
    let geojsonUrl = "/getAllStatesRoadsGeoJsonDataWithLazyLoading";
    initMap();
    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        let startPosition = {
            lat: 26.094757374299146,
            lng: 94.58979407214116,
        };
        map = new Map(document.getElementById("map"), {
            center: startPosition,
            zoom: 9,
        });

        const loader = document.getElementById("loader");
        loader.style.display = "block"; // Show loader

        var dt = new Date();
        var time =
            dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
        console.log("calling Ajax to load Data At : " + time);

        all_states_geojson_data =
            all_state_cached_geojson_data.all_states_geojson_data;

        fetch("/getAllStatesRoadsGeoJsonData")
            .then((response) => {
                console.log("HTTP Response Status:", response.status); // Debug status

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                return response.json();
            })
            .then((data) => {
                if (data && data.status) {
                    let compressedArray = Uint8Array.from(
                        atob(data.all_states_geojson_data),
                        (c) => c.charCodeAt(0)
                    );

                    // Decompress using pako
                    let decompressedData = pako.inflate(compressedArray, {
                        to: "string",
                    });

                    // console.log("GeoJSON Data:", data.all_states_geojson_data);
                    // all_state_data = data.all_states_geojson_data;
                    all_states_geojson_data = JSON.parse(decompressedData);

                    var dt = new Date();
                    var time =
                        dt.getHours() +
                        ":" +
                        dt.getMinutes() +
                        ":" +
                        dt.getSeconds();
                    console.log("Retrieved Data from Server At : " + time);
                    loadGeoJsonDataIntoMap(
                        all_states_geojson_data,
                        "26.094757374299146",
                        "94.58979407214116"
                    );
                    var dt = new Date();
                    var time =
                        dt.getHours() +
                        ":" +
                        dt.getMinutes() +
                        ":" +
                        dt.getSeconds();
                    console.log("loaded Data in Map At : " + time);
                } else {
                    console.error("Invalid response format");
                }
            })
            .catch((error) => console.error("Fetch error:", error));
    }

    async function loadGeoJsonDataIntoMap(dataset) {
        const loader = document.getElementById("loader");
        try {
            data_layer = new google.maps.Data({
                map: map,
            });
            data_layer.addGeoJson(dataset);
            loader.style.display = "none";
        } catch (e) {
            console.log(e);
            return;
        }

        data_layer.setStyle(applyStyle(0, 0));

        data_layer.addListener("click", handleClickOnDataSet);
        map.addListener("zoom_changed", handleZoomChangeEvent);
        loadRoadDistressMarkers();
    }

    async function initMapLazyLoading() {
        const { Map } = await google.maps.importLibrary("maps");
        let startPosition = {
            lat: 26.094757374299146,
            lng: 94.58979407214116,
        };
        map = new Map(document.getElementById("map"), {
            center: startPosition,
            zoom: 9,
        });
        const loader = document.getElementById("loader");
        loader.style.display = "block"; // Show loader

        try {
            data_layer = new google.maps.Data({
                map: map,
            });
            // data_layer.addGeoJson(dataset);
        } catch (e) {
            console.log(e);
            return;
        }

        loadGeoJsonForCurrentViewport();

        // Add event listeners for map zoom and drag to load data dynamically
        map.addListener("bounds_changed", loadGeoJsonForCurrentViewport);
        data_layer.setStyle(applyStyle(0, 0));
        data_layer.addListener("click", handleClickOnDataSet);
        map.addListener("zoom_changed", handleZoomChangeEvent);

        //   // Map event listener.
        map.addListener("mousemove", () => {
            console.log("Inside Map mouseMove");
            if (lastInteractedFeatureIds?.length) {
                lastInteractedFeatureIds = [];
            }
        });

        loadRoadDistressMarkers();
    }

    function loadGeoJsonForCurrentViewport() {
        // Get the current map bounds
        const bounds = map.getBounds();
        if (!bounds) return;
        const ne = bounds.getNorthEast(); // North-east corner of the viewport
        const sw = bounds.getSouthWest(); // South-west corner of the viewport

        // Define the bounding box (as a rectangle)
        const bbox = {
            north: ne.lat(),
            south: sw.lat(),
            east: ne.lng(),
            west: sw.lng(),
        };

        // Example: Fetch filtered data from the server based on the bounding box
        // Replace the URL with your own endpoint that supports bounding box filtering
        const url = `${geojsonUrl}?west=${bbox.west}&south=${bbox.south}&east=${bbox.east}&north=${bbox.north}`;
        console.log("Fetching GeoJSON for bbox:", bbox);

        // Fetch and load GeoJSON data for the current bounding box
        data_layer.loadGeoJson(url, {}, function (features) {
            loader.style.display = "none";
            console.log(
                `Loaded ${features.length} features for this viewport.`
            );
        });
    }

    function applyStyle(zoomedStrokeWt, user_clicked_rd_id) {
        return function (feature) {
            var roadCatg = feature.getProperty("road_category");
            var strokeColor;
            var fillColor;
            var strokeWeight;
            switch (roadCatg) {
                case "NH":
                    strokeColor = "#ffff00";
                    fillColor = "#ffff00";
                    strokeWeight = 2.0;
                    break;
                case "SH":
                case "State Highway":
                    strokeColor = "#005500";
                    fillColor = "#005500";
                    strokeWeight = 1.7;
                    break;
                case "MDR":
                case "Major District Roads":
                    strokeColor = "#000000";
                    fillColor = "#000000";
                    strokeWeight = 1.7;
                    break;
                case "ODR":
                    strokeColor = "#00007f";
                    fillColor = "#00007f";
                    strokeWeight = 1.0;
                    break;
                case "VR":
                    strokeColor = "#ff5500";
                    fillColor = "#ff5500";
                    strokeWeight = 1.0;
                    break;
                case "ALR":
                    strokeColor = "#55ff7f";
                    fillColor = "#55ff7f";
                    strokeWeight = 1.0;
                    break;
                case "UR":
                    strokeColor = "#aa00ff";
                    fillColor = "#aa00ff";
                    strokeWeight = 1.0;
                    break;
                case "RD":
                    strokeColor = "#ffaa7f";
                    fillColor = "#ffaa7f";
                    strokeWeight = 1.0;
                    break;
                case "INTER":
                    strokeColor = "#FA0017";
                    fillColor = "#FA0017";
                    strokeWeight = 1.0;
                    break;

                default:
                    strokeColor = "green";
                    fillColor = "green";
                    strokeWeight = 2.0;
            }
            if (zoomedStrokeWt != 0) strokeWeight = zoomedStrokeWt;

            if (feature.getProperty("road_id") == user_clicked_rd_id) {
                strokeWeight = 5.0;
            }
            return {
                strokeColor: strokeColor,
                fillColor: fillColor,
                strokeWeight: strokeWeight,
                strokeOpacity: 1.0,
                fillOpacity: 0.3,
            };
        };
    }
    function calculateStrokeWeight(zoomLevel) {
        console.log("zomm Level: " + zoomLevel);
        if (zoomLevel < 10) zoomLevel = 10;
        var strokeWeight = Math.pow(2, zoomLevel - 10);
        if (strokeWeight > 4.0) strokeWeight = 4.0;
        return strokeWeight;
    }
    function handleZoomChangeEvent() {
        var zoomLevel = map.getZoom();
        var strokeWeight = calculateStrokeWeight(zoomLevel);
        data_layer.setStyle(applyStyle(strokeWeight, 0));
    }
    function handleClickOnDataSet(/* MouseEvent */ e) {
        var span = document.getElementsByClassName("closeRdInfoModal")[0];
        if (e.feature) {
            lastClickedFeatureIds = e.feature.getProperty("road_id");
            var rd_name = e.feature.getProperty("Name");
            var rd_id = e.feature.getProperty("road_id");
            var division_name = e.feature.getProperty("division_name");
            var road_length = e.feature.getProperty("road_length");
            var road_catg = e.feature.getProperty("road_category");

            var tableHtml =
                '<div><table class="table table-striped"><tbody>' +
                "<tr> <td> Road Name </td><td>" +
                rd_name +
                "</td > </tr>" +
                "<tr> <td> Road No </td><td>" +
                rd_id +
                "</td> </tr>" +
                "<tr> <td> Road Length </td><td>" +
                road_length +
                "</td> </tr>" +
                "<tr> <td> Division Name </td><td>" +
                division_name +
                "</td> </tr>" +
                "<tr> <td> Road Category </td><td>" +
                road_catg +
                "</td> </tr>" +
                "</tbody></table></div>";
            data_layer.setStyle(applyStyle(0, lastClickedFeatureIds));
        }

        // @ts-ignore
        $(".modal-title").html("NL PWD ROAD INFORMATION");
        $(".modal-body").html(tableHtml);
        $("#myModal").modal("show");
    }

    function loadRoadDistressMarkers() {
        for (var i = 0; i < distress_dtls_array.length; i++) {
            var strt_lat = distress_dtls_array[i]["start_lat"];
            var strt_lon = distress_dtls_array[i]["start_lon"];

            const startPosition = {
                lat: parseFloat(strt_lat),
                lng: parseFloat(strt_lon),
            };

            marker = new google.maps.Marker({
                id: distress_dtls_array[i]["rd_distress_cd"],
                position: startPosition,
                map: map,
                title: "Click to get distress details",

                icon: {
                    url: "/images/marker_icons/Red_circle.gif",
                    scaledSize: new google.maps.Size(30, 30),
                },
            });

            google.maps.event.addListener(marker, "click", function () {
                let markerId = this.id;
                if (markerId != undefined) {
                    getDistressDetails(markerId);
                }
            });
        }
    }

    // Function to create markers based on the response
    function createMarkers(response) {
        // Clear existing markers from the map
        markers.forEach((marker) => marker.setMap(null));
        markers = []; // Reset the markers array

        if (response.status === "success") {
            console.log(response.message);
            // Transform the response into the desired format
            var transformedResponse = response.message.map(function (
                item,
                index
            ) {
                return {
                    id: item.id,
                    coords: {
                        lat: parseFloat(item.coords.lat),
                        lng: parseFloat(item.coords.lng),
                    },
                };
            });

            // Create markers for each set of coordinates
            markers = transformedResponse.map(({ id, coords }, index) => {
                const subAssetMarker = new google.maps.Marker({
                    map: map,
                    position: coords,
                    id: id,
                    title: `Culvert: ${index + 1}`,
                    animation: google.maps.Animation.DROP,
                    icon: {
                        url: "/images/marker_icons/culvert_1.png",
                        scaledSize: new google.maps.Size(30, 30),
                    },
                });

                // Add click event listener to each marker
                subAssetMarker.addListener("click", () => {
                    map.setZoom(16);
                    map.panTo(coords);
                    map.setCenter(coords);
                    // Add animation to the marker
                    subAssetMarker.setAnimation(google.maps.Animation.BOUNCE);
                    setTimeout(() => {
                        subAssetMarker.setAnimation(null);
                    }, 700);

                    // Display information or perform other actions if needed
                    infowindow.setContent(
                        `Click to zoom - Culvert ${index + 1}`
                    );
                    infowindow.open(map, subAssetMarker);

                    getAndShowSubAsset(id);
                });

                return subAssetMarker;
            });
        }

        if (response.status === "failed") {
            console.log(response.message);
        }
    }

    // Handle radio button click event
    $('input[type="radio"]').on("change", function () {
        var selectedRoadId = $("#selected_road_id").text();
        if ($(this).is(":checked")) {
            var selectedValue = $(this).val();
            if (selectedValue === "culvert") {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/get-culvert-lat-lng/" + selectedRoadId,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    cache: false,
                    success: function (response) {
                        createMarkers(response);
                    },
                });
            }
        } else {
            console.log("Radio button unchecked");
        }
    });

    // Get and view sub asset details
    function getAndShowSubAsset(cdwork_id) {
        console.log("Marker ID:", cdwork_id);

        $.ajax({
            type: "GET",
            url: "/asset-management/get-culvert-details/" + cdwork_id,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            cache: false,
            success: function (response) {
                console.log(response.status);
                if (response.status === 404) {
                    // alert("Culvert details not found!");
                    $("#sub_asset_header").html(
                        "<i class='fa fa-circle text-xs mr-1' aria-hidden='true'></i><strong>Culvert Details</strong>"
                    );
                    $("#status_message").html(
                        "<span id='text-info'><i class='fa fa-exclamation-triangle text-xs mr-1' aria-hidden='true'></i>Culvert Details not found!</span>"
                    );
                    // showDashboardModal("Culvert Details not available!");
                } else {
                    console.log(response.message);
                    // Define the HTML content you want to add
                    $("#sub_asset_header").html(
                        "<i class='fa fa-circle text-xs mr-1' aria-hidden='true'></i><strong>Culvert Details</strong>"
                    );
                    var content =
                        '<div class="row p-1">' +
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="border-4 ps-1 text-sm border-start border-primary"><span>Culvert ID:<span> <span>' +
                        response.message.rd_cdwork_cd +
                        "</span></p>" +
                        '<p class="border-4 ps-1 text-sm border-start border-primary"><span>Road ID:<span> <span>' +
                        response.message.rd_system_id +
                        "</span></p>" +
                        "</div>" +
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="border-4 ps-1 text-sm border-start border-primary"><span>Culvert No:<span> <span>' +
                        response.message.culvert_no +
                        "</span></p>" +
                        '<p class="border-4 ps-1 text-sm border-start border-primary"><span>Chainage:<span> <span>' +
                        response.message.chainage +
                        "</span></p>" +
                        "</div>" +
                        '<div class="col-md-4">' +
                        '<p class="border-4 ps-1 text-sm border-start border-primary"><span>Other:<span> <span></span></p>' +
                        '<p class="border-4 ps-1 text-sm border-start border-primary"><span>Other:<span> <span></span></p>' +
                        "</div>" +
                        "</div>";

                    // Select the div element by its ID and set its HTML content
                    $("#status_message").html(content);
                }
            },
        });
    }

    function getDistressDetails(id) {
        let imageList = [];
        $.ajax({
            url: "/asset-management/distress-details/" + id,
            type: "GET",
            cache: false,
            success: function (response) {
                if (response.status === 200) {
                    // get distress images
                    $.ajax({
                        url:
                            "/asset-management/distress-images/" +
                            response.result.rd_distress_cd,
                        type: "GET",
                        cache: false,
                        success: function (imgResponse) {
                            if (imgResponse.status == "success") {
                                imageList = imgResponse.imageLists;

                                // Generate image HTML
                                let imagesHtml = '<div class="row">';
                                imageList.forEach(function (imgUrl, index) {
                                    imagesHtml += `
                                    <div class="col-3 mb-3">
                                        <a href="${imgUrl['img_url']}" target="_blank">
                                        <img src="${imgUrl['img_url']}" class="img-fluid img-thumbnail" style="max-height: 120px; object-fit: cover;" />
                                        </a>
                                    </div>
                                `;
                                });
                                imagesHtml += "</div>";

                                // modal for distress details
                                let distressDetails =
                                    "<div>" +
                                    '<table class="table table-striped">' +
                                    "<tbody>" +
                                    "<tr><td>Road Name</td><td>" +
                                    response.result.rd_name +
                                    "</td></tr>" +
                                    "<tr><td>Blockage Type</td><td>" +
                                    response.result.distress_type_descr +
                                    "</td></tr>" +
                                    "<tr><td>Distress Landmark: </td><td>" +
                                    response.result.start_landmark +
                                    " To " +
                                    response.result.end_landmark +
                                    "</td></tr>" +
                                    "<tr><td>Distress Position</td><td>[" +
                                    response.result.start_lat +
                                    " , " +
                                    response.result.start_lon +
                                    "] to [" +
                                    response.result.end_lat +
                                    " , " +
                                    response.result.end_lon +
                                    "]</td></tr>" +
                                    "<tr><td>From Date</td><td>" +
                                    response.result.date_of_occurance +
                                    "</td></tr>" +
                                    "<tr><td>Estimated Restoration</td><td>" +
                                    response.result.days_to_restore +
                                    " Day(s)</td></tr>" +
                                    "<tr><td>Departmental Note</td><td>" +
                                    response.result.distress_remarks +
                                    "</td></tr>" +
                                    "</tbody></table>" +
                                    imagesHtml +
                                    "</div>";

                                $(".modal-title").html(
                                    "<span class='text-bold text-md'>NAGALAND PWD ROAD BLOCKAGE INFORMATION</span>"
                                );
                                $(".modal-body").html(distressDetails);
                                $("#distressDataModal").modal("show");
                            }
                        },
                    });
                } else {
                    alert("Failed to get distress information!");
                }
            },
        });
    }
});
