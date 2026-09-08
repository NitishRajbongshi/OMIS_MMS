$(document).ready(function () {
    $("#road_list").select2();
    $("#division_list").select2();

    let datasetLayer;
    let lastClickedFeatureIds = [];
    let lastInteractedFeatureIds = [];
    let markers = [];
    let marker = null;
    let map = null;
    async function initMap(datasetId) {
        const { Map } = await google.maps.importLibrary("maps");

        const startPosition = {
            lat: 26.094757374299146,
            lng: 94.58979407214116,
        };
        const styleId = "Nagaland";
        const mapId = "3d95c646e2e4e33c";
        const styleOptions = {
            strokeColor: "blue",
            strokeWeight: 2,
            strokeOpacity: 1,
            fillColor: "green",
            fillOpacity: 0.3,
        };

        map = new Map(document.getElementById("map"), {
            zoom: 9,
            center: startPosition,
            mapId: mapId,
            mapTypeControl: false,
            icon: { url: google.maps.SymbolPath.BICYCLE },
        });

        marker = new google.maps.Marker({
            position: startPosition,
            // map: map,
            // title: "Nagaland",
            // icon: {
            //     url: "/images/marker_icons/marker_1.png",
            //     scaledSize: new google.maps.Size(30, 30),
            // },
        });

        const infowindow = new google.maps.InfoWindow({
            content: "Nagaland",
        });

        // datasetLayer = map.getDatasetFeatureLayer(datasetId);
        // console.log(datasetLayer);

        // datasetLayer.style = applyStyle;
        // // datasetLayer.setStyle(styleOptions);

        // datasetLayer.addListener("click", handleClick);
        // datasetLayer.addListener("mousemove", handleMouseMove);

        // map.data.addListener("mouseover", function (event) {
        //     var title = event.feature.getProperty("road_id");
        //     console.log("Title: " + title);
        // });
        // //   // Map event listener.
        // map.addListener("mousemove", () => {
        //     // If the map gets a mousemove, that means there are no feature layers
        //     // with listeners registered under the mouse, so we clear the last
        //     // interacted feature ids.
        //     console.log("Inside Map mouseMove");
        //     // if (lastInteractedFeatureIds.length > 0)
        //     if (lastInteractedFeatureIds?.length) {
        //         lastInteractedFeatureIds = [];
        //         datasetLayer.style = applyStyle;
        //     }
        // });

        // const attributionDiv = document.createElement("div");
        // const attributionControl = createAttribution(map);

        // attributionDiv.appendChild(attributionControl);
        // map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(
        //     attributionDiv
        // );






    }// end of initMap

    function handleMouseMove(/* MouseEvent */ e) {
        if (e.features) {
            // console.log("Mouse Move function***************" + e.features);
            // console.log("rrrr: " + e.features.map((f) => f.datasetAttributes["road_id"],));
            lastInteractedFeatureIds = e.features.map(
                (f) => f.datasetAttributes["road_id"]
            );
            // lastInteractedFeatureIds = e.feature.getProperty("road_id");
            datasetLayer.style = applyStyle;
        }

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
        var span = document.getElementsByClassName("closeRdInfoModal")[0];
        if (e.features) {
            var obj = new Object();

            lastClickedFeatureIds = e.features.map(
                (f) => f.datasetAttributes["road_id"]
            );
            var rd_name = e.features.map(
                (f) => f.datasetAttributes["Name"]
            );
            var rd_id = e.features.map((f) => f.datasetAttributes["road_id"]);
            // var dist_name = e.features.map(
            //     (f) => f.datasetAttributes["District_N"]
            // );
            // var block_name = e.features.map(
            //     (f) => f.datasetAttributes["Block_Name"]
            // );
            var division_name = e.features.map(
                (f) => f.datasetAttributes["DIVISION"]
            );
            var road_num = e.features.map(
                (f) => f.datasetAttributes["road_id"]
            );
            var road_length = e.features.map(
                (f) => f.datasetAttributes["Road_len"]
            );
            var road_catg = e.features.map(
                (f) => f.datasetAttributes["Category"]
            );

            var tableHtml =
                '<div><table class="table table-striped"><tbody>' +
                '<tr> <td> Road Name </td><td>' + rd_name[0] + '</td > </tr>' +
                '<tr> <td> Road ID </td><td>' + rd_id[0] + '</td> </tr>' +
                '<tr> <td> Road Length </td><td>' + road_length[0] + '</td> </tr>' +
                '<tr> <td> Division Name </td><td>' + division_name[0] + '</td> </tr>' +
                '<tr> <td> Road Category </td><td>' + road_catg[0] + '</td> </tr>' +
                '</tbody></table></div>';
        }

        // @ts-ignore
        datasetLayer.style = applyStyle;
        $(".modal-title").html("NL PWD ROAD INFORMATION");
        $(".modal-body").html(tableHtml);
        // $("#road-sum-info").html(tableHtml);
        $("#myModal").modal("show");

        span.onclick = function () {
            $("#myModal").modal("hide");
        };
    }





    function applyStyle(/* FeatureStyleFunctionOptions */ params) {
        // console.log("Inside Apply Style");
        const datasetFeature = params.feature;
        // console.log(datasetFeature);
        // Note, 'road_id' is an attribute in this dataset.
        //@ts-ignore
        // console.log("xxxxxxx : " + datasetFeature.datasetAttributes["road_id"]);
        if (
            lastClickedFeatureIds.includes(
                datasetFeature.datasetAttributes["road_id"]
            )
        ) {
            return styleClicked;
        }

        //@ts-ignore
        if (
            lastInteractedFeatureIds.includes(
                datasetFeature.datasetAttributes["road_id"]
            )
        ) {
            if (datasetFeature.datasetAttributes["Category"] == "NH") {
                return styleMouseMoveNH;
            }
            if (datasetFeature.datasetAttributes["Category"] == "SH") {
                return styleMouseMoveSH;
            }
            if (datasetFeature.datasetAttributes["Category"] == "MDR") {
                return styleMouseMoveMDR;
            }

            if (datasetFeature.datasetAttributes["Category"] == "ODR") {
                return styleMouseMoveODR;
            }
            if (datasetFeature.datasetAttributes["Category"] == "VR") {
                return styleMouseMoveVR;
            }

            if (datasetFeature.datasetAttributes["Category"] == "ALR") {
                return styleMouseMoveALR;
            }
            if (datasetFeature.datasetAttributes["Category"] == "UR") {
                return styleMouseMoveUR;
            }

            if (datasetFeature.datasetAttributes["Category"] == "RD") {
                return styleMouseMoveRD;
            }

            if (datasetFeature.datasetAttributes["Category"] == "INTER") {
                return styleMouseMoveINTER;
            }
            return styleMouseMove;
        }

        if (datasetFeature.datasetAttributes["Category"] == "NH") {
            return styleNH;
        }
        if (datasetFeature.datasetAttributes["Category"] == "SH") {
            return styleSH;
        }
        if (datasetFeature.datasetAttributes["Category"] == "MDR") {
            return styleMDR;
        }
        if (datasetFeature.datasetAttributes["Category"] == "ODR") {
            return styleODR;
        }
        if (datasetFeature.datasetAttributes["Category"] == "VR") {
            return styleVR;
        }
        if (datasetFeature.datasetAttributes["Category"] == "ALR") {
            return styleALR;
        }
        if (datasetFeature.datasetAttributes["Category"] == "UR") {
            return styleUR;
        }
        if (datasetFeature.datasetAttributes["Category"] == "RD") {
            return styleRD;
        }
        if (datasetFeature.datasetAttributes["Category"] == "INTER") {
            return styleINTER;
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
            showDashboardModal(response.message);
        }
    }
    // Handle radio button click event
    $('input[type="radio"]').on("change", function () {
        var selectedRoadId = $("#selected_road_id").text();
        if (selectedRoadId) {
            if ($(this).is(":checked")) {
                var selectedValue = $(this).val();
                if (selectedValue === "culvert") {
                    $.ajax({
                        type: "GET",
                        url: "/asset-management/get-culvert-lat-lng/" + selectedRoadId,
                        headers: {
                            "X-CSRF-TOKEN": $(
                                'meta[name="csrf-token"]'
                            ).attr("content"),
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
        } else {
            // alert("Select a road");
            showDashboardModal("Select a road from the list first!");
        }
    });
    $("#road_list").on("change", function (e) {
        // lastInteractedFeatureIds = e.features.map(
        //     (f) => f.datasetAttributes["road_id"]
        // );


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
                    console.log("dynamicPosition: " + dynamicPosition);
                    // let myLatlng = new google.maps.LatLng(response);
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

                    lastClickedFeatureIds = [];
                    lastInteractedFeatureIds.push(road_id)
                    // lastClickedFeatureIds.push(road_id);
                    datasetLayer.style = applyStyle;
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });
    });

    $("#division_list").on("change", function () {
        var division_name = $('#division_list').find(":selected").text().trim();
        $.ajax({
            type: "GET",
            url: '/asset-management/road-by-division-name',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            data: { division: division_name },
            cache: false,
            success: function (response) {
                $('#road_list').empty().append('<option value="">Select Road</option>');
                if (response.status === 200) {
                    const data = response.result;
                    console.log(data);
                    if (data.length == 0) {
                        alert('No road found!');
                    } else {
                        $.each(data, function (index, value) {
                            $('#road_list').append(
                                '<option value="' + value.rd_system_id + '">' + value.rd_name + '</option>'
                            );
                        });
                    }
                }
                if (response.status === 204) {
                    alert(response.message);
                }
                if (response.status === 401) {
                    console.log(response.message);
                }
                if (response.status === 500) {
                    console.log(response.message);
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    });

    function getRoadAbstactByDivision(divisionName) {
        // console.log(divisionName);
        $.ajax({
            type: "GET",
            url: "/asset-management/abstract-road-and-bridge-division",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                ),
            },
            data: { division: divisionName },
            cache: false,
            success: function (response) {
                if (response.status === 'success') {
                    $("#roadAbstractSummaryBody").empty();
                    $.each(response.message, function (index, data) {
                        var newRoadData =
                            "<tr>" +
                            "<td>" + data.rd_catg_descr + "</td>" +
                            "<td>" + data.road_count + "</td>" +
                            "<td>" + data.road_length + "</td>" +
                            "</tr>";
                        $("#roadAbstractSummaryBody").append(newRoadData);
                    })
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });
    }


    $("#division_list").on("change", function () {
        const div_id = $(this).val();
        var division_name_selected = $('#division_list').find(":selected").text().trim();
        // console.log("division_name_selected : " + division_name_selected);
        getRoadAbstactByDivision(division_name_selected);
        var datasetId = "e334fa46-6103-4435-9c05-5a159bb045a9";
        if (div_id == 0) // PHEK Division
            datasetId = "7349c6f5-b0df-48fa-a9d0-b6e78974773d";
        if (div_id == 1) // Dimapur Division
            datasetId = "9397fb8f-c40f-48e2-b970-2a5c8c766d63";
        if (div_id == 2) // Peren Division
            datasetId = "537be8d2-bc6a-40e7-bd54-487522a5003c";
        if (div_id == 3) //Pfutsuro Division
            datasetId = "f50bd915-c614-489e-950c-14328d4d924e";
        if (div_id == 4) //Rlc Division
            datasetId = "e334fa46-6103-4435-9c05-5a159bb045a9";
        if (div_id == 5) //Kohima South Division
            datasetId = "f2cd6917-8713-48d2-9f45-b99dd06bfd6a";
        if (div_id == 6) //Wokha Division
            datasetId = "47e657d0-09f3-43e1-9aa3-9837ab9dbdba";
        if (div_id == 7) //Tseminyu Division
            datasetId = "9a8d58ed-bab1-4ff1-ad2b-ec3c66e7c526";
        if (div_id == 8) //Baghty Division
            datasetId = "9f2dde78-77c3-4e1e-8c4b-852de3677712";
        if (div_id == 9) // Construction
            datasetId = "49ae4566-886d-4340-9afe-37cfe1eb7413";
        if (div_id == 10) //Mon
            datasetId = "39b6d2df-7f62-4df6-8c82-9557c64d4cfe";
        if (div_id == 11) //Aboi Division
            datasetId = "233f083d-0cd0-41db-bdb2-1a1396a703d8";
        if (div_id == 12) //Naginimora Division
            datasetId = "7cceda3d-d251-45b6-bafa-a443e4f5e437";
        if (div_id == 13) //Mokokchung Division
            datasetId = "d29b00c4-80fe-4b72-ad09-4f845ec203bd";
        if (div_id == 14) //Mangkolemba Division 
            datasetId = "f0c43853-80aa-4042-bfb8-c919a56dd6a6";
        if (div_id == 15) //Changtongya
            datasetId = "0cb29928-0432-4106-8d1c-aa9dafb10d91";
        if (div_id == 16) //Tuli 
            datasetId = "442a3bc8-19e6-4c1b-ae6b-b1c194eb69cd";
        if (div_id == 17) //Zunheboto
            datasetId = "6612b3b4-06a0-4c69-93c4-fdd9d0bf3050";
        if (div_id == 18) //Atoizu Division
            datasetId = "1e5d22a6-ba23-41b1-b484-e6c9059c428b";
        if (div_id == 19) // Aghunato 
            datasetId = "a5a0dbf7-c7ef-446f-a74a-8d573502716f";
        if (div_id == 20) //Pughoboto
            datasetId = "d1c50bc6-7359-4b6c-b35b-d308d57c8fb6";
        if (div_id == 21) //Tuensang Division
            datasetId = "ab7086ee-11cd-491a-b0b0-ab10f38ce68b";
        if (div_id == 22) //Longleng Division
            datasetId = "40fbdb2d-ad0c-48f3-944c-c6820f60a33e";
        if (div_id == 23) //Kiphire Division
            datasetId = "5550c431-8e94-4d0f-8ba1-9cbe493b8627";
        if (div_id == 24) //Noklak Division
            datasetId = "a826ed0f-8346-4d96-8820-ee3553ce1866";

        initMap(datasetId);
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

    const styleMouseMoveNH = {
        ...styleNH,
        strokeWeight: 5.0,
    };
    const styleMouseMoveSH = {
        ...styleSH,
        strokeWeight: 5.0,
    };
    const styleMouseMoveMDR = {
        ...styleMDR,
        strokeWeight: 5.0,
    };
    const styleMouseMoveODR = {
        ...styleODR,
        strokeWeight: 5.0,
    };

    const styleMouseMoveVR = {
        ...styleVR,
        strokeWeight: 5.0,
    };

    const styleMouseMoveALR = {
        ...styleALR,
        strokeWeight: 5.0,
    };

    const styleMouseMoveUR = {
        ...styleUR,
        strokeWeight: 5.0,
    };

    const styleMouseMoveRD = {
        ...styleRD,
        strokeWeight: 5.0,
    };

    const styleMouseMoveINTER = {
        ...styleINTER,
        strokeWeight: 5.0,
    };
    const styleMouseMove = {
        ...styleDefault,
        strokeWeight: 5.0,
    };
    initMap("e334fa46-6103-4435-9c05-5a159bb045a9");
    // window.initMap = initMap;
});
