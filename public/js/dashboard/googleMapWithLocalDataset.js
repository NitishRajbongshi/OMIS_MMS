// $(document).ready(function () {
$("#road_list").select2();
$("#division_list").select2();
$("#category_list").select2();

let lastClickedFeatureIds = 0;
let lastInteractedFeatureIds = [];
let markers = [];
let marker = null;
let map;
var data_layer;
var all_state_data;
let all_states_geojson_data = null;
var division_geojson_data = null;
let road_id = null;
var selected_road_lat = null;
var selected_road_lng = null;
let geojsonUrl = "/getAllStatesRoadsGeoJsonDataWithLazyLoading";
let c_lat = "26.094757374299146";
let c_lng = "94.58979407214116";
var strokeWeight = 0;
let strokeWeightRequired = true;
let selectedRoadIdsToDelete = [];
let selectedRoadIdsToHide = [];
// initMapLazyLoading();

initMap();

async function initMap() {
    const { Map } = await google.maps.importLibrary("maps");
    let startPosition = {
        lat: parseFloat(c_lat),
        lng: parseFloat(c_lng),
    };
    map = new Map(document.getElementById("map"), {
        center: startPosition,
        zoom: 9,
    });

    const loader = document.getElementById("loader");
    loader.style.display = "block"; // Show loader
    getRoadGoeJsonDataOfAllState();
}

function getRoadGoeJsonDataOfAllState() {
    // all_state_data = all_state_cached_geojson_data.all_states_geojson_data;
    var all_state_data = null;
    fetch("/getAllStatesRoadsGeoJsonData")
        .then((response) => {
            console.log("HTTP Response Status:", response.status); // Debug status

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            return response.json();
        })
        .then((data) => {
            console.log("Full Response Data:", data);

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
                all_state_data = JSON.parse(decompressedData);

                var dt = new Date();
                var time =
                    dt.getHours() +
                    ":" +
                    dt.getMinutes() +
                    ":" +
                    dt.getSeconds();
                console.log("Retrieved Data from Server At : " + time);
                loadGeoJsonDataIntoMap(
                    all_state_data,
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

    // $.ajax({
    //     type: 'GET',
    //     url: "/getAllStatesRoadsGeoJsonData/",
    //     contentType: "application/json; charset=utf-8",
    //     crossDomain: true,
    //     dataType: "json",
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     cache: false,
    //     success: function (response, status, jqXHR) {
    //         if (response.status === true) {
    //             all_states_geojson_data = response.all_states_geojson_data;
    //             all_state_data = JSON.parse(all_states_geojson_data);
    //             loadGeoJsonDataIntoMap(JSON.parse(all_states_geojson_data), "26.094757374299146", "94.58979407214116");
    //         } else {

    //         }
    //     },
    //     error: function (error) {
    //         console.log(
    //             "Some Technical Issue!!Map Data Could Not Fetched From Server,Please Contact Administrator!!"
    //         );
    //         console.log(error);
    //         showDashboardModal(
    //             "Some Technical Issue!!Map Data Could Not Fetched From Server,Please Contact Administrator!!"
    //         );

    //     }
    // });
}

async function loadGeoJsonDataIntoMap(dataset, cntr_lat, cntr_lng) {
    const { Map } = await google.maps.importLibrary("maps");
    let startPosition = {
        lat: parseFloat(cntr_lat),
        lng: parseFloat(cntr_lng),
    };
    map = new Map(document.getElementById("map"), {
        center: startPosition,
        zoom: 9,
    });
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

    data_layer.setStyle(applyStyle(0, []));

    data_layer.addListener("click", handleClickOnDataSet);
    map.addListener("zoom_changed", handleZoomChangeEvent);
}

function applyStyleToSelectedCategory(
    catg_cd,
    catg_name,
    selected_div_name,
    all_state_data
) {
    data_layer.addGeoJson(all_state_data);
    return function (feature) {
        var roadCatg = feature.getProperty("road_category");
        var divNameFromGeoJson = feature.getProperty("division_name");
        var strokeColor;
        var fillColor;
        var visibility = true;
        // var strokeWeight;
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
        console.log(
            "roadCatg: " +
                roadCatg +
                " catg_cd : " +
                catg_cd +
                " selected_div_name : " +
                selected_div_name +
                " divNameFromGeoJson : " +
                divNameFromGeoJson
        );
        if (roadCatg == catg_cd && selected_div_name == divNameFromGeoJson) {
            visibility = true;
        } else {
            visibility = false;
            // data_layer.remove(feature);
        }

        return {
            strokeColor: strokeColor,
            fillColor: fillColor,
            strokeWeight: strokeWeight,
            strokeOpacity: 1.0,
            fillOpacity: 0.3,
            visible: visibility,
        };
    };
}

function applyStyleToSelectedRoads(arrRodIds, all_state_data) {
    data_layer.addGeoJson(all_state_data);
    return function (feature) {
        var roadCatg = feature.getProperty("road_category");
        var rd_id = feature.getProperty("road_id");
        var strokeColor;
        var fillColor;
        var visibility = true;
        // var strokeWeight;
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

        if (arrRodIds.includes(rd_id)) {
            visibility = false;
            data_layer.remove(feature);
        } else {
            visibility = true;
        }

        return {
            strokeColor: strokeColor,
            fillColor: fillColor,
            strokeWeight: strokeWeight,
            strokeOpacity: 1.0,
            fillOpacity: 0.3,
            visible: visibility,
        };
    };
}

function applyVisibilityOnSelectedRoad(rd_ids, isVisible) {
    // data_layer.addGeoJson(all_state_data);
    return function (feature) {
        var roadCatg = feature.getProperty("road_category");
        var rd_system_id = feature.getProperty("road_id");
        var strokeColor;
        var fillColor;
        var visibility = true;
        // var strokeWeight;
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

        if (rd_ids.includes(rd_system_id)) {
            visibility = false;
            if (isVisible) strokeWeight = 5.0;
        }
        // else {
        //     visibility = true;
        // }

        return {
            strokeColor: strokeColor,
            fillColor: fillColor,
            strokeWeight: strokeWeight,
            strokeOpacity: 1.0,
            fillOpacity: 0.3,
            visible: visibility,
        };
    };
}

function showSelectedRoadOnMap(rd_id) {
    // data_layer.addGeoJson(all_state_data);
    return function (feature) {
        var roadCatg = feature.getProperty("road_category");
        var rd_system_id = feature.getProperty("road_id");
        var strokeColor;
        var fillColor;
        var visibility = true;
        // var strokeWeight;
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

        if (rd_id == rd_system_id) {
            visibility = true;
        }

        return {
            strokeColor: strokeColor,
            fillColor: fillColor,
            strokeWeight: strokeWeight,
            strokeOpacity: 1.0,
            fillOpacity: 0.3,
            visible: visibility,
        };
    };
}

function calculateStrokeWeight(zoomLevel) {
    if (zoomLevel < 10) zoomLevel = 10;
    var strokeWeight = Math.pow(2, zoomLevel - 10);
    if (strokeWeight > 5.0) strokeWeight = 5.0;
    return strokeWeight; // Adjust this formula to suit your needs
}

function handleZoomChangeEvent(e) {
    console.log("Invoking zoom Change Event");
    console.log("road_id inside zoom: " + road_id);
    var zoomLevel = map.getZoom();
    strokeWeight = calculateStrokeWeight(zoomLevel);
    data_layer.setStyle(applyStyle(strokeWeight, lastClickedFeatureIds));
}
function handleClickOnDataSet(/* MouseEvent */ e) {
    var jsonString;
    var span = document.getElementsByClassName("closeRdInfoModal")[0];

    if (e.feature) {
        lastClickedFeatureIds = e.feature.getProperty("road_id");

        var rd_name = e.feature.getProperty("Name");
        var rd_id = e.feature.getProperty("road_id");
        var division_name = e.feature.getProperty("division_name");
        var road_length = e.feature.getProperty("road_length");
        var road_catg = e.feature.getProperty("road_category");

        if (lastInteractedFeatureIds.includes(rd_id)) {
            lastInteractedFeatureIds = lastInteractedFeatureIds.filter(
                (item) => item !== rd_id
            );
            deleteRowFromTable(rd_id);
        } else {
            // var retRes = {};
            if (deleteRoadRequest === "Y") {
                validateIfRoadCanBeDeleted(rd_id).then((resultJson) => {
                    const jsonObject = JSON.parse(resultJson);
                    console.log(
                        "returend status value: " + jsonObject.canDelete
                    );
                    if (jsonObject.canDelete == true) {
                        lastInteractedFeatureIds.push(rd_id);
                        addRowToTable(rd_id, rd_name);
                        $("#hdnRoadIdsToDelete").val(lastInteractedFeatureIds);
                        data_layer.setStyle(
                            applyStyle(4, lastInteractedFeatureIds)
                        );
                    } else {
                        var tableHtml =
                            '<div><table class="table table-bordered text-xs">' +
                            "<thead>" +
                            "<tr> <td colspan=2> Sorry Road Cannot be Deleted : " +
                            rd_name +
                            "<br>Found Some Sub Assets  </td></tr>" +
                            "</thead>" +
                            "<tbody>" +
                            "<tr> <td> No. Of Culverts  </td><td>" +
                            jsonObject.no_of_culvert +
                            "</td > </tr>" +
                            "<tr> <td> No. Of Bridges </td><td>" +
                            jsonObject.no_of_bridge +
                            "</td> </tr>" +
                            "<tr> <td> No. Of PCI </td><td>" +
                            jsonObject.no_of_pci +
                            "</td> </tr>" +
                            "<tr> <td> No. Of Surfaces </td><td>" +
                            jsonObject.no_of_surfaces +
                            "</td> </tr>" +
                            "<tr> <td> No. Of Habitations </td><td>" +
                            jsonObject.no_of_habitations +
                            "</td> </tr>" +
                            "</tbody></table></div>";

                        $(".modal-title").html("NL PWD ROAD INFORMATION");
                        $(".modal-body").html(tableHtml);
                        $("#road-sum-info").html(tableHtml);
                        $("#myModal").modal("show");

                        span.onclick = function () {
                            $("#myModal").modal("hide");
                        };
                    }
                });
            }
        }
        var tableHtml =
            '<div><table class="table table-striped"><tbody>' +
            "<tr> <td> Road Name </td><td>" +
            rd_name +
            "</td > </tr>" +
            "<tr> <td> Road ID </td><td>" +
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
        // data_layer.setStyle(applyStyle(4, lastClickedFeatureIds));
        data_layer.setStyle(applyStyle(4, lastInteractedFeatureIds));
    }

    if (deleteRoadRequest !== "Y") {
        // @ts-ignore
        $(".modal-title").html("NL PWD ROAD INFORMATION");
        $(".modal-body").html(tableHtml);
        $("#road-sum-info").html(tableHtml);
        $("#myModal").modal("show");

        span.onclick = function () {
            $("#myModal").modal("hide");
        };
    }
} //end of handleClickOnDataSet

async function validateIfRoadCanBeDeleted(rd_id) {
    let retJson = JSON.parse('{ "canDelete": true }');
    try {
        const response = await fetch(`/asset-management/getRoadsAssetsAbstractDetails/${rd_id}`);
        const data = await response.json();

        const no_of_culvert = data["totalCulvert"].total_culvert;
        const no_of_bridge = data["totalBridge"].total_bridge;
        const no_of_pci = data["totalPCI"].total_pci;
        const no_of_surfaces = data["totalSurfaceTypes"].total_surface_types;
        const no_of_habitations = data["totalHabitations"].total_habitation;

        if (
            no_of_culvert != 0 ||
            no_of_bridge != 0 ||
            no_of_pci != 0 ||
            no_of_surfaces != 0 ||
            no_of_habitations != 0
        ) {
            console.log("Found some sub assets");
            retJson.canDelete = false;
            retJson.no_of_culvert = no_of_culvert;
            retJson.no_of_bridge = no_of_bridge;
            retJson.no_of_pci = no_of_pci;
            retJson.no_of_surfaces = no_of_surfaces;
            retJson.no_of_habitations = no_of_habitations;
        } else {
            console.log("Not Found any sub asset");
            retJson.canDelete = true;
        }

        console.log("retJson : " + JSON.stringify(retJson));
    } catch (error) {
        retJson.canDelete = false;
        console.log(error);
    }
    return JSON.stringify(retJson);
}

function addRowToTable(rd_id, rd_name) {
    $("#tblRoadListToDelete").append(
        "<tr class='text-center'>" +
            "<td>" +
            rd_id +
            "</td>" +
            "<td>" +
            rd_name +
            "</td>" +
            "<td>" +
            "<input type='checkbox' " +
            "data_road_id='" +
            rd_id +
            "' " +
            "class='classHideUnHide' checked />" +
            "</td>" +
            "<td>" +
            "<input type='button' " +
            " data_road_id='" +
            rd_id +
            "' " +
            " class='classBtnRemoveRdFromDelete btn btn-xs btn-primary' value='Remove'/>" +
            "</td>" +
            "</tr>"
    );
    selectedRoadIdsToHide.push(rd_id);
}

function deleteRowFromTable(rd_id) {
    let table = document.getElementById("tblRoadListToDelete");
    const rows = table.getElementsByTagName("tr");
    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        for (let j = 0; j < cells.length; j++) {
            if (cells[j].innerText === rd_id) {
                table.deleteRow(i); // Remove the row
                selectedRoadIdsToHide = selectedRoadIdsToHide.filter(
                    (item) => item !== rd_id
                );
                break; // Stop the loop once the row is deleted
            }
        }
    }
}

function deleteAllRowFromTable() {
    let table = document.getElementById("tblRoadListToDelete");
    for (let i = 1; i < table.rows.length; i++) {
        table.deleteRow(i);
        continue;
    }
}

$("#tblRoadListToDelete").on(
    "click",
    ".classBtnRemoveRdFromDelete",
    function () {
        var rd_id = this.getAttribute("data_road_id");
        deleteRowFromTable(rd_id);
        selectedRoadIdsToHide.push(rd_id);

        data_layer.setStyle(showSelectedRoadOnMap(rd_id));
        return;
    }
);

$("#tblRoadListToDelete").on("click", ".classHideUnHide", function () {
    var rd_id = this.getAttribute("data_road_id");
    var visibility = $(this).is(":checked");
    if (visibility)
        selectedRoadIdsToHide = selectedRoadIdsToHide.filter(
            (item) => item !== rd_id
        );
    else {
        selectedRoadIdsToHide.push(rd_id);
    }
    data_layer.setStyle(
        applyVisibilityOnSelectedRoad(selectedRoadIdsToHide, visibility)
    );
    return;
});

function getFinalListOfRoadsToBeDeleted() {
    let arrRodId = [];
    let rd_id;
    let table = document.getElementById("tblRoadListToDelete");
    const rows = table.getElementsByTagName("tr");
    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        for (let j = 0; j < cells.length; j++) {
            rd_id = cells[j].innerText;
            arrRodId.push(rd_id);
        }
    }
    return arrRodId;
}

function applyStyle(zoomedStrokeWt, user_clicked_rd_id) {
    return function (feature) {
        var roadCatg = feature.getProperty("road_category");
        var strokeColor;
        var fillColor;
        // var strokeWeight;
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

        if (user_clicked_rd_id != "" || user_clicked_rd_id != null) {
            if (user_clicked_rd_id.includes(feature.getProperty("road_id"))) {
                strokeWeight = 5.0;
            }
        }

        return {
            strokeColor: strokeColor,
            fillColor: fillColor,
            strokeWeight: strokeWeight,
            strokeOpacity: 1.0,
            fillOpacity: 0.3,
            visible: true,
        };
    };
}

$("#road_list").on("change", function (e) {
    road_id = $(this).val();
    $("#selected_road_id").text(road_id);
    $.ajax({
        type: "GET",
        url: "/asset-management/get-lat-lng/" + road_id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            if (
                response.status === "success" &&
                response.lat != "" &&
                response.lng != ""
            ) {
                c_lat = parseFloat(response.lat);
                c_lng = parseFloat(response.lng);
                strokeWeightRequired = true;
                lastClickedFeatureIds = road_id;
                map.setCenter({ lat: c_lat, lng: c_lng });
                data_layer.setStyle(applyStyle(4, lastClickedFeatureIds));
            } else {
                loadGeoJsonDataIntoMap(all_state_data, c_lat, c_lng);
            }
        },
        error: function (xhr, status, error) {
            console.log(error);
        },
    });
    if (e.feature) {
        console.log("e has features");
    } else {
        console.log("Does Not have features");
        return;
    }
});

$("#division_list").on("change", function () {
    const div_id = $(this).val();
    var division_name = $("#division_list").find(":selected").text().trim();
    console.log("division_list : " + division_name);
    console.log("division_cd : " + div_id);
    var data = { division_cd: div_id };

    if (div_id == "") {
        $("#road_list").empty().append('<option value="">Select Road</option>');
        $.each(all_state_rd_details, function (index, value) {
            $("#road_list").append(
                '<option value="' +
                    value.rd_system_id +
                    '">' +
                    value.rd_name +
                    "</option>"
            );
        });
        loadGeoJsonDataIntoMap(JSON.parse(all_state_data), c_lat, c_lng);
        return;
    }
    // var response = getDivisionsRoadsGeoJsonData(div_id);
    // console.log(response);
    // if (response.status === true) {
    //     var div_lat = response.div_lat;
    //     var div_lon = response.div_lon;
    //     division_geojson_data = response.division_geojson_data;
    //     loadGeoJsonDataIntoMap(JSON.parse(division_geojson_data), div_lat, div_lon);
    // } else {

    // }
    $.ajax({
        type: "GET",
        url: "/getDivisionsRoadsGeoJsonData/" + div_id,
        contentType: "application/json; charset=utf-8",
        crossDomain: true,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response, status, jqXHR) {
            if (response.status === true) {
                var div_lat = response.div_lat;
                var div_lon = response.div_lon;
                division_geojson_data = response.division_geojson_data;
                console.log("11111");
                loadGeoJsonDataIntoMap(
                    JSON.parse(division_geojson_data),
                    div_lat,
                    div_lon
                );
                console.log("22222");
            } else {
            }
        },
        error: function (error) {
            console.log(
                "Some Technical Issue!!Map Data Could Not Fetched From Server,Please Contact Administrator!!"
            );
            showDashboardModal(
                "Some Technical Issue!!Map Data Could Not Fetched From Server,Please Contact Administrator!!"
            );
        },
    });

    var get_url = "/asset-management/road-by-division-and-chainage";
    if (rd_tp == "NH") get_url = "/asset-management/nh-by-division-name";

    console.log("get_url : " + get_url);
    console.log("rd_tp : " + rd_tp);
    $.ajax({
        type: "GET",
        url: get_url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: { division: div_id },
        cache: false,
        success: function (response) {
            $("#road_list")
                .empty()
                .append('<option value="">Select Road</option>');
            if (response.status === 200) {
                const data = response.result;
                if (data.length == 0) {
                    // alert('No road found!');
                    console.log("No road found!");
                } else {
                    $.each(data, function (index, value) {
                        $("#road_list").append(
                            '<option value="' +
                                value.rd_system_id +
                                '">' +
                                value.rd_name +
                                "</option>"
                        );
                    });
                    getRoadAbstactByDivision(division_name);
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

function getDivisionsRoadsGeoJsonData(div_cd) {
    var retRes = { status: false };
    $.ajax({
        type: "GET",
        url: "/getDivisionsRoadsGeoJsonData/" + div_cd,
        contentType: "application/json; charset=utf-8",
        crossDomain: true,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response, status, jqXHR) {
            retRes = response;
        },
        error: function (error) {
            console.log(
                "Some Technical Issue!!Map Data Could Not Fetched From Server,Please Contact Administrator!!"
            );
            showDashboardModal(
                "Some Technical Issue!!Map Data Could Not Fetched From Server,Please Contact Administrator!!"
            );
        },
    });

    return retRes;
}

$("#category_list").on("change", function () {
    const catg_cd = $(this).val();
    var div_name = $("#division_list").find(":selected").text().trim();
    var div_code = $("#division_list").val();
    var rd_catg_name = $("#category_list").find(":selected").text().trim();
    var data = { rd_catg_cd: catg_cd };

    if (div_code != null || div_code != "") {
        $.ajax({
            type: "GET",
            url: "/asset-management/road-by-division-name",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: { division: div_name },
            cache: false,
            success: function (response) {
                console.log("div_name : " + div_name);
                $("#road_list")
                    .empty()
                    .append('<option value="">Select Road</option>');
                if (response.status === 200) {
                    const data = response.result;
                    if (data.length == 0) {
                        // alert('No Road Found!');
                    } else {
                        if (catg_cd == "") {
                            $.each(data, function (index, value) {
                                $("#road_list").append(
                                    '<option value="' +
                                        value.rd_system_id +
                                        '">' +
                                        value.rd_name +
                                        "</option>"
                                );
                            });
                        } else {
                            $.each(data, function (index, value) {
                                if (rd_catg_name == value.rd_catg_descr) {
                                    $("#road_list").append(
                                        '<option value="' +
                                            value.rd_system_id +
                                            '">' +
                                            value.rd_name +
                                            "</option>"
                                    );
                                }
                            });
                        }
                    }
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    } else {
        $("#road_list").empty().append('<option value="">Select Road</option>');
        $.each(all_state_rd_details, function (index, value) {
            $("#road_list").append(
                '<option value="' +
                    value.rd_system_id +
                    '">' +
                    value.rd_name +
                    "</option>"
            );
        });
    }

    if (catg_cd == "") {
        if (div_code != null || div_code != "") {
            loadGeoJsonDataIntoMap(
                JSON.parse(division_geojson_data),
                c_lat,
                c_lng
            );
            data_layer.setStyle(
                applyStyleToSelectedCategory(
                    catg_cd,
                    rd_catg_name,
                    div_name,
                    JSON.parse(division_geojson_data)
                )
            );
            return;
        } else {
            loadGeoJsonDataIntoMap(JSON.parse(all_state_data), c_lat, c_lng);
            data_layer.setStyle(
                applyStyleToSelectedCategory(
                    catg_cd,
                    rd_catg_name,
                    null,
                    JSON.parse(all_state_data)
                )
            );
            return;
        }
    } else {
        div_name = $("#division_list").find(":selected").text().trim();
        var div_code = $("#division_list").val();

        if (div_code != null || div_code != "") {
            data_layer.setStyle(
                applyStyleToSelectedCategory(
                    catg_cd,
                    rd_catg_name,
                    div_name,
                    JSON.parse(division_geojson_data)
                )
            );
            return;
        } else {
            data_layer.setStyle(
                applyStyleToSelectedCategory(
                    catg_cd,
                    rd_catg_name,
                    null,
                    JSON.parse(all_state_data)
                )
            );
            return;
        }
    }
});

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

// [END maps_dds_datasets_polygon_click_stylefunction]

// Function to create markers based on the response
function createMarkers(response) {
    // Clear existing markers from the map
    markers.forEach((marker) => marker.setMap(null));
    markers = []; // Reset the markers array

    if (response.status === "success") {
        console.log(response.message);
        // Transform the response into the desired format
        var transformedResponse = response.message.map(function (item, index) {
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
                infowindow.setContent(`Click to zoom - Culvert ${index + 1}`);
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
    } else {
        // alert("Select a road");
        showDashboardModal("Select a road from the list first!");
    }
});

function getRoadAbstactByDivision(divisionName) {
    $.ajax({
        type: "GET",
        url: "/asset-management/abstract-road-and-bridge-division",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: { division: divisionName },
        cache: false,
        success: function (response) {
            if (response.status === "success") {
                $("#roadAbstractSummaryBody").empty();
                $.each(response.message, function (index, data) {
                    var newRoadData =
                        "<tr>" +
                        "<td>" +
                        data.rd_catg_descr +
                        "</td>" +
                        "<td>" +
                        data.road_count +
                        "</td>" +
                        "<td>" +
                        data.road_length +
                        "</td>" +
                        "</tr>";
                    $("#roadAbstractSummaryBody").append(newRoadData);
                    console.log("data.rd_catg_descr : " + data.rd_catg_descr);
                });
            }
        },
        error: function (xhr, status, error) {
            console.log(error);
        },
    });
}

function confirmDeletion() {
    confirmationInput = document.getElementById("confirmationInput").value;
    alert(confirmationInput);
}

// $('#frmDeleteRoad').on('click', '#btnDeleteRdFrmMap', function () {
$(document).on("submit", "#frmDeleteRoad", function (e) {
    e.preventDefault();
    selectedRoadIdsToDelete = getFinalListOfRoadsToBeDeleted();
    const isConfirmed = confirm(
        "Are you sure you want to delete these records?"
    );
    if (isConfirmed) {
        $.ajax({
            type: "GET",
            url: "/asset-management/delete-road-frm-map",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: $("#frmDeleteRoad").serialize(),
            cache: false,
            success: function (response) {
                console.log(response);
                if (response.status === 200) {
                    Swal.fire({
                        icon: "success",
                        title: "",
                        text: response["message"],
                        showConfirmButton: true,
                        timer: 5000,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "",
                        text: response["message"],
                        showConfirmButton: true,
                        timer: 5000,
                    });
                }

                // deleteAllRowFromTable();
                $("#tblRoadListToDelete tr").empty();
                lastInteractedFeatureIds = [];
                data_layer.setStyle(
                    applyStyleToSelectedRoads(
                        selectedRoadIdsToDelete,
                        JSON.parse(all_state_data)
                    )
                );
                const updatedAllStateData = deleteRoadByIdInAllStateData(
                    all_state_data,
                    selectedRoadIdsToDelete
                );
                all_state_data = updatedAllStateData;
            },
            error: function (error) {
                console.log(error);
                Swal.fire({
                    icon: "error",
                    title: "",
                    text: response["message"],
                    showConfirmButton: true,
                    timer: 5000,
                });
            },
        });
    } else {
        return;
    }
});

function deleteRoadByIdInAllStateData(geoJsonData, roadIdToDelete) {
    if (geoJsonData.type === "FeatureCollection") {
        const roadIdsSet = new Set(roadIdToDelete);
        geoJsonData.features = geoJsonData.features.filter((feature) => {
            return !roadIdsSet.has(feature.properties?.road_id);
        });
    }
    return geoJsonData;
}
