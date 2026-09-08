// START: Google Map
let map = null;
let lat = null;
let lon = null;
let building_cd = null;
let bld_markers;
let markers_to_toggle = [];

const map_id = "3d95c646e2e4e33c";
async function initMap() {
    console.log("All approved building details: ", allApprovedBuildingDetails);
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

    const styleOptions = {
        strokeColor: "blue",
        strokeWeight: 2,
        strokeOpacity: 1,
        fillColor: "green",
        fillOpacity: 0.3,
    };

    const buildingIcon = document.createElement("div");
    buildingIcon.className = "building-marker";

    map = new Map(document.getElementById("map"), {
        zoom: 9,
        center: startPosition,
        mapId: map_id
    });

    var approved_marker_data = [];
    for (var i = 0; i < allApprovedBuildingDetails.length; i++) {
        var bld_lon = allApprovedBuildingDetails[i].lon;
        var bld_lat = allApprovedBuildingDetails[i].lat;
        if (bld_lon == null)
            bld_lon = 0;
        if (bld_lat == null)
            bld_lat = 0;

        var marker_json_data = {
            id: allApprovedBuildingDetails[i].building_system_cd,
            position: {
                lat: parseFloat(bld_lon),
                lng: parseFloat(bld_lat)
            },
            title: allApprovedBuildingDetails[i].bld_qtr_name
        };

        // createMarkers(allApprovedBuildingDetails);

        const approved_marker = new google.maps.marker.AdvancedMarkerElement({
            position: marker_json_data.position,
            map: map,
            // id: allApprovedBuildingDetails[i].building_system_cd,
            title: marker_json_data.title,
            title: `Click to get housing info`,
        });
        approved_marker.id = marker_json_data.id;
        approved_marker_data.push(approved_marker);

        approved_marker.addListener("click", () => {
            console.log('clicked on', approved_marker.id);
            map.setZoom(9);
            map.panTo(approved_marker.position);
            map.setCenter(approved_marker.position);
    
            showHousingInfo(approved_marker.id); // get housing info
        });
    }

    
}
// END: Google Map

// 
function showHousingInfo(id) {
    console.log(id);
    $.ajax({
        type: "GET",
        url: "/asset-management/get-housing/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response.status);
            if (response.status === 'failed') {
                $(".modal-title").html("Housing Information");
                $(".modal-body").html('Housing data not available!');
                $("#myModal").modal("show");
            }
            if (response.status === 'success') {
                console.log(response);
                var span = document.getElementsByClassName("closeWingWall")[0];
                const building = response.message;
                var tableHtml =
                    '<div><table class="table table-striped"><tbody>' +
                    '<tr> <td> Building Class</td><td>' + building['building_class_descr'] + '</td > </tr>' +
                    '<tr> <td> Building Type</td><td>' + building['building_type_descr'] + '</td > </tr>' +
                    '<tr> <td> Quarter Number </td><td>' + building['qtr_no'] + '</td > </tr>' +
                    '<tr> <td> Building Name </td><td>' + building['bld_qtr_name'] + '</td > </tr>' +
                    '<tr> <td> Department </td><td>' + building['owning_dpt_name'] + '</td > </tr>' +
                    // '<tr> <td> Building Category</td><td>' + building['building_catg_descr'] + '</td > </tr>' +
                    // '<tr> <td> Construction Year </td><td>' + building['construction_year'] + '</td > </tr>' +
                    // '<tr> <td> Plinth Area(Sr.ft) </td><td>' + building['plinth_area'] + '</td > </tr>' +
                    '</tbody></table></div>';
                $(".modal-title").html("Housing Information");
                $(".modal-body").html(tableHtml);
                $("#myModal").modal("show");

                span.onclick = function () {
                    $("#myModal").modal("hide");
                };
            }
        },
    });
}

function createMarkers(response) {
    markers.forEach((marker) => marker.setMap(null));
    markers = [];
    var transformedResponse = response.map(function (
        item,
        index
    ) {
        return {
            id: item.id,
            coords: {
                lat: parseFloat(item.lat),
                lng: parseFloat(item.lng),
            },
        };
    });

    markers = transformedResponse.map(({ id, coords }, index) => {
        const subAssetMarker =  new AdvancedMarkerElement({
            map: map,
            position: coords,
            id: id,
            // title: `Housing: ${index + 1}`,
            title: 'Click to get the housing info',
            animation: google.maps.Animation.DROP,
            icon: {
                url: "/images/marker_icons/home_1.png",
                scaledSize: new google.maps.Size(30, 30),
            },
        });

        subAssetMarker.addListener("click", () => {
            map.setZoom(9);
            map.panTo(coords);
            map.setCenter(coords);
            subAssetMarker.setAnimation(
                google.maps.Animation.BOUNCE
            );
            setTimeout(() => {
                subAssetMarker.setAnimation(null);
            }, 700);

            showHousingInfo(id); // get housing info
        });

        return subAssetMarker;
    });
}

$(document).on("submit", "#frmHousingForMap", function (e) {
    e.preventDefault();
    $.ajax({
        type: "GET",
        url: "/asset-management/housing-coordinates",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#frmHousingForMap").serialize(),
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status === 200) {
                // Show abstract data
                $("#roadAbstractSummaryBody").empty();
                $.each(response.abstract, function (index, data) {
                    var newRoadData =
                        "<tr>" +
                        "<td>" + data.building_type_descr + "</td>" +
                        "<td>" + parseFloat(data.plinth_area).toFixed(2) + "</td>" +
                        "<td>" + data.building_count + "</td>" +
                        "</tr>";
                    $("#roadAbstractSummaryBody").append(newRoadData);
                })
                createMarkers(response);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: '',
                    text: response["message"],
                    showConfirmButton: true,
                    timer: 5000
                }).then(() => {
                    location.reload();
                });
            }
        },
        error: function (error) {
            console.log(error);
        }

    });
});

// Call initMap function when the page has finished loading
window.addEventListener("load", () => {
    initMap();
});
