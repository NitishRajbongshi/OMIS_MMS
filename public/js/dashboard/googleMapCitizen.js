$(document).ready(function () {
    $("#road_list").select2();

    let datasetLayer;
    let lastClickedFeatureIds = [];
    let lastInteractedFeatureIds = [];
    let markers = [];

    async function initMap() {
        var distress_dtls_array = <?php echo json_encode($distressDetails); ?>;
        const { Map } = await google.maps.importLibrary("maps");
     
            console.log(distress_dtls_array);
     
        const startPosition = {
            lat: 26.094757374299146,
            lng: 94.58979407214116,
        };
        const styleId = "Nagaland";
        const mapId = "3d95c646e2e4e33c";
        const datasetId = "78f1fe3e-e77d-4780-a45b-d76c2903391c";

        const styleOptions = {
            strokeColor: "blue",
            strokeWeight: 2,
            strokeOpacity: 1,
            fillColor: "green",
            fillOpacity: 0.3,
        };

        const map = new Map(document.getElementById("map"), {
            zoom: 9,
            center: startPosition,
            mapId: mapId,
            mapTypeControl: false,
            icon: { url: google.maps.SymbolPath.BICYCLE },
        });

        const marker = new google.maps.Marker({
            position: startPosition,
            map: map,
            title: "Nagaland",
            icon: {
                url: '/images/marker_icons/marker_1.png',
                // url: '/images/marker_icons/Red_circle.gif',
                scaledSize: new google.maps.Size(30, 30)
            }
        });

        const infowindow = new google.maps.InfoWindow({
            content: "Nagaland",
        });

        datasetLayer = map.getDatasetFeatureLayer(datasetId);
        console.log(datasetLayer);

        datasetLayer.style = applyStyle;
        // datasetLayer.setStyle(styleOptions);

        datasetLayer.addListener("click", handleClick);
        datasetLayer.addListener("mousemove", handleMouseMove);

        map.data.addListener("mouseover", function (event) {
            var title = event.feature.getProperty("OBJECTID");
            console.log("Title: " + title);
        });
        //   // Map event listener.
        map.addListener("mousemove", () => {
            // If the map gets a mousemove, that means there are no feature layers
            // with listeners registered under the mouse, so we clear the last
            // interacted feature ids.
            console.log("Inside Map mouseMove");
            // if (lastInteractedFeatureIds.length > 0)
            if (lastInteractedFeatureIds?.length) {
                lastInteractedFeatureIds = [];
                datasetLayer.style = applyStyle;
            }
        });

        const attributionDiv = document.createElement("div");
        const attributionControl = createAttribution(map);

        attributionDiv.appendChild(attributionControl);
        map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(
            attributionDiv
        );

        $("#road_list").on("change", function () {
            const road_id = $(this).val();
            $("#selected_road_id").text(road_id);

            // var divId = $("#selected_road_id").text();
            // alert("Read id from div: " + divId);

            $.ajax({
                type: "GET",
                url: "/asset-management/get-lat-lng/" + road_id,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                cache: false,
                success: function (response) {
                    console.log(response);
                    if (response.lat === 0 || response.lng === 0) {
                        // if coordinates are not available
                        showDashboardModal(
                            "Coordinates not found for this road!"
                        );
                        // alert('Coordinate not found for this road!');
                    } else {
                        let dynamicPosition = response;
                        console.log(dynamicPosition);

                        marker.setPosition(dynamicPosition);
                        marker.setTitle("Click to zoom");
                        map.setZoom(13);
                        map.setCenter(dynamicPosition);
                        map.panTo(dynamicPosition);
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                        setTimeout(() => {
                            marker.setAnimation(null);
                        }, 700);

                        const infowindow = new google.maps.InfoWindow({
                            content: "Click to zoom",
                        });

                        marker.addListener("click", () => {
                            map.setZoom(15);
                            map.panTo(dynamicPosition);
                            marker.setAnimation(google.maps.Animation.BOUNCE);
                            setTimeout(() => {
                                marker.setAnimation(null);
                            }, 700);
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });
        });

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
                            url: '/images/marker_icons/culvert_1.png',
                            scaledSize: new google.maps.Size(30, 30)
                        }
                    });

                    // Add click event listener to each marker
                    subAssetMarker.addListener("click", () => {
                        map.setZoom(16);
                        map.panTo(coords);
                        map.setCenter(coords);
                        // Add animation to the marker
                        subAssetMarker.setAnimation(
                            google.maps.Animation.BOUNCE
                        );
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
            // alert("Read id from div: " + selectedRoadId);
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
                            // if (response.status === "success") {
                            //     const coordinatesList = response.message;
                            //     let ajaxResponse = response.message;
                            //     // Transform the response into the desired format
                            //     var transformedResponse = ajaxResponse.map(
                            //         function (item, index) {
                            //             return {
                            //                 id: item.id,
                            //                 coords: {
                            //                     lat: parseFloat(
                            //                         item.coords.lat
                            //                     ),
                            //                     lng: parseFloat(
                            //                         item.coords.lng
                            //                     ),
                            //                 },
                            //             };
                            //         }
                            //     );

                            //     // Create markers for each set of coordinates
                            //     const markers = transformedResponse.map(
                            //         ({ id, coords }, index) => {
                            //             const subAssetMarker =
                            //                 new google.maps.Marker({
                            //                     map: map,
                            //                     position: coords,
                            //                     id: id,
                            //                     title: `Marker ${index + 1}`,
                            //                     animation:
                            //                         google.maps.Animation.DROP,
                            //                 });

                            //             // Add click event listener to each marker
                            //             subAssetMarker.addListener(
                            //                 "click",
                            //                 () => {
                            //                     map.setZoom(16);
                            //                     map.panTo(coords);
                            //                     map.setCenter(coords);
                            //                     // Add animation to the marker
                            //                     subAssetMarker.setAnimation(
                            //                         google.maps.Animation.BOUNCE
                            //                     );
                            //                     setTimeout(() => {
                            //                         subAssetMarker.setAnimation(
                            //                             null
                            //                         );
                            //                     }, 700);

                            //                     // Display information or perform other actions if needed
                            //                     infowindow.setContent(
                            //                         `Click to zoom - Marker ${
                            //                             index + 1
                            //                         }`
                            //                     );
                            //                     infowindow.open(
                            //                         map,
                            //                         subAssetMarker
                            //                     );

                            //                     console.log("Marker ID:", id);
                            //                 }
                            //             );

                            //             return subAssetMarker;
                            //     }
                            //     );
                            // }
                            // if (response.status === "failed") {
                            //     console.log(response.message);
                            // }
                        },
                    });
                }
            } else {
                console.log("Radio button unchecked");
            }
        });
    }

    function showDashboardModal(message) {
        var dashboardModal = document.getElementById("dashboardModal");
        var span = document.getElementsByClassName("closeWingWall")[0];
        $("#dashboardModalSubContent").html(`<p>${message}</p>`);
        dashboardModal.style.display = "block";
        span.onclick = function () {
            dashboardModal.style.display = "none";
        };

        window.onclick = function (event) {
            if (event.target == dashboardModal) {
                dashboardModal.style.display = "none";
            }
        };
    }

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
                    showDashboardModal("Culvert Details not available!");
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
                    // $("#status_message").html(
                    //     "<span id='text-primary'><i class='fa fa-check text-xs mr-1' aria-hidden='true'></i>Culvert Details Fetch Successfully</span>"
                    // );
                }
            },
        });
    }

    function handleClick(/* MouseEvent */ e) {
        var jsonString;
        if (e.features) {
            var obj = new Object();

            lastClickedFeatureIds = e.features.map(
                (f) => f.datasetAttributes["OBJECTID"]
            );
            var rd_name = e.features.map(
                (f) => f.datasetAttributes["Road_Name_"]
            );
            var rd_id = e.features.map((f) => f.datasetAttributes["Road_ID_"]);
            var dist_name = e.features.map(
                (f) => f.datasetAttributes["District_N"]
            );
            var block_name = e.features.map(
                (f) => f.datasetAttributes["Block_Name"]
            );
            var road_num = e.features.map(
                (f) => f.datasetAttributes["road_num"]
            );
            var road_length = e.features.map(
                (f) => f.datasetAttributes["Length_"]
            );

            jsonString =
                "<p>" +
                "<i class='fa fa-circle text-xs mr-1' aria-hidden='true'></i><strong>Object Id: </strong> " +
                lastClickedFeatureIds[0] +
                "<br>" +
                "<i class='fa fa-circle text-xs mr-1' aria-hidden='true'></i><strong>Road Name: </strong>" +
                rd_name[0] +
                "<br>" +
                "<i class='fa fa-circle text-xs mr-1' aria-hidden='true'></i><strong>Road Id: </strong>" +
                rd_id[0] +
                "<br>" +
                "<i class='fa fa-circle text-xs mr-1' aria-hidden='true'></i><strong>District: </strong>" +
                dist_name[0] +
                "<br>" +
                "<i class='fa fa-circle text-xs mr-1' aria-hidden='true'></i><strong>Block: </strong>" +
                block_name[0] +
                "<br>" +
                "<i class='fa fa-circle text-xs mr-1' aria-hidden='true'></i><strong>Road No: </strong>" +
                road_num[0] +
                "<br>" +
                "<i class='fa fa-circle text-xs mr-1' aria-hidden='true'></i><strong>Road Length: </strong>" +
                road_length[0] +
                "</p>";
        }

        // @ts-ignore
        datasetLayer.style = applyStyle;
        $(".modal-title").html(
            "<i class='fa fa-map mr-1 text-xs'></i>NL PWD ROAD INFORMATION"
        );
        $(".modal-body").html(jsonString);
        $("#road-sum-info").html(jsonString);
        $("#myModal").modal("show");
    }

    function handleMouseMove(/* MouseEvent */ e) {
        if (e.features) {
            // console.log("Mouse Move function***************" + e.features);
            // console.log("rrrr: " + e.features.map((f) => f.datasetAttributes["OBJECTID"],));
            lastInteractedFeatureIds = e.features.map(
                (f) => f.datasetAttributes["OBJECTID"]
            );
            // lastInteractedFeatureIds = e.feature.getProperty("OBJECTID");
            datasetLayer.style = applyStyle;
        }
        // else
        // {
        //   console.log("Not a e.features")
        // }

        // @ts-ignore
    }

    const styleDefault = {
        strokeColor: "green",
        strokeWeight: 2.0,
        strokeOpacity: 1.0,
        fillColor: "green",
        fillOpacity: 0.3,
    };
    const styleClicked = {
        ...styleDefault,
        strokeColor: "blue",
        fillColor: "blue",
        fillOpacity: 0.5,
    };
    const styleMouseMove = {
        ...styleDefault,
        strokeWeight: 5.0,
    };

    function applyStyle(/* FeatureStyleFunctionOptions */ params) {
        // console.log("Inside Apply Style");
        const datasetFeature = params.feature;
        // console.log(datasetFeature);
        // Note, 'OBJECTID' is an attribute in this dataset.
        //@ts-ignore

        if (
            lastClickedFeatureIds.includes(
                datasetFeature.datasetAttributes["OBJECTID"]
            )
        ) {
            return styleClicked;
        }

        //@ts-ignore
        if (
            lastInteractedFeatureIds.includes(
                datasetFeature.datasetAttributes["OBJECTID"]
            )
        ) {
            return styleMouseMove;
        }
        return styleDefault;
    }

    // [END maps_dds_datasets_polygon_click_stylefunction]

    function createAttribution(map) {
        const attributionLabel = document.createElement("div");

        // Define CSS styles.
        attributionLabel.style.backgroundColor = "#fff";
        attributionLabel.style.opacity = "0.7";
        attributionLabel.style.fontFamily = "Roboto,Arial,sans-serif";
        attributionLabel.style.fontSize = "5px";
        attributionLabel.style.padding = "2px";
        attributionLabel.style.margin = "2px";
        attributionLabel.textContent = "Data source: Nagaland PWD Road Data";
        return attributionLabel;
    }

    initMap();
    // window.initMap = initMap;
});
