$(function () {
    $("#building_details_table")
        .DataTable({
            buttons: ["csv", "excel"],
            paging: true,
            ordering: true,
            info: true,
        })
        .buttons()
        .container()
        .appendTo(".mis-btn-road");
});

// get habitation after filteration
$(document).on("submit", "#wing_building", function (e) {
    console.log("clicked");
    e.preventDefault();
    var loader = $("#loader");
    var content = $(".loaderContainer");
    content.addClass("blur-background");
    loader.show();
    $.ajax({
        type: "GET",
        url: "/asset-management/mis-building",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#wing_building").serialize(),
        cache: false,
        success: function (response) {
            console.log(response);
            const table_id = $("#building_details_table");
            if (response.status === 200) {
                table_id.find("tbody").empty();
                var housingDataTable = new DataTable('#building_details_table');
                if (response.result.length === 0) {
                    table_id
                        .find("tbody")
                        .html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
                    housingDataTable.clear().draw();
                    loader.hide();
                    content.removeClass("blur-background");
                    $("#tableContent").slideUp('slow');
                    if ($('#toggleBtn').hasClass("fa-caret-down")) {
                        $("#toggleBtn").removeClass("fa-caret-down").addClass("fa-caret-left");
                    }
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: response.message,
                        showConfirmButton: true,
                        timer: 10000
                    }).then(() => {
                        // location.reload(true);
                    });
                } else {
                    // $('.road_count').html(response.result.length);
                    // table_id.find("tbody").empty();
                    // var housingDataTable = new DataTable('#building_details_table');
                    // var tot_rd_len = 0;
                    housingDataTable.clear().draw();
                    $.each(response.result, function (index, data) {
                        var bld_bld_qtr_name_no = '';
                        if (data.building_class_cd == "0")
                            bld_bld_qtr_name_no = "Quarter No: " + data.qtr_no;
                        else
                            bld_bld_qtr_name_no = "Building Name: " + data.bld_qtr_name;
                        housingDataTable.row.add([
                            ++index,
                            data.building_system_cd,
                            data.qtr_no,
                            data.bld_qtr_name,
                            data.building_type_descr,
                            data.is_maintained_by_npwd,
                            data.building_class_descr,
                            data.owning_dept_name,
                            // data.building_catg_descr,
                            // data.plinth_area,
                            // data.construction_year,
                            // data.construction_cost,
                            // data.has_water_supply,
                            // data.has_electricity,
                            // data.has_sanitary,
                            // data.dept_name,
                            // data.dept_name,
                            // data.location_name,
                            // data.remark,
                            "<button class='btn btn-sm btn-outline-primary' " +
                            "data_bld_sys_id='" + data.building_system_cd + "' " +
                            "data_bld_bld_qtr_name='" + bld_bld_qtr_name_no + "' " +
                            "data_bld_lat='" + data.lat + "' " +
                            "data_bld_lon='" + data.lon + "' " +
                            "data_loc_name='" + data.location_name + "' " +
                            "id='btnShowInMap' onclick='showMap(this)'>" +
                            "<i class='fa fa-eye mr-1'></i>Show</button>",
                            '<a class="btn btn-xs text-primary border border-primary" href="/asset-images?asset_type=5&asset_cd=' +
                            data.building_system_cd + '" target="_blank">View Image</a>'
                        ]);
                    });
                    loader.hide();
                    content.removeClass("blur-background");
                    housingDataTable.draw();
                    $("#tableContent").slideDown('slow');
                    if ($('#toggleBtn').hasClass("fa-caret-left")) {
                        $("#toggleBtn").removeClass("fa-caret-left").addClass("fa-caret-down");
                    }
                }
            } else {
                loader.hide();
                content.removeClass("blur-background");
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                    showConfirmButton: true,
                    timer: 10000
                }).then(() => {
                    // location.reload(true);
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
                timer: 10000
            }).then(() => {
                // location.reload(true);
            });
        },
    });
});

async function initMap(bld_lat, bld_lon, bld_bld_name_qtr_no, bld_loc_name) {
    const map_id = "3d95c646e2e4e33c";
    const {
        Map
    } = await google.maps.importLibrary("maps");

    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    const startPosition = {
        lat: 26.094757374299146,
        lng: 94.58979407214116,
    };

    const bld_marker_position = {
        lat: parseFloat(bld_lon),
        lng: parseFloat(bld_lat),
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

    let map = new Map(document.getElementById("map"), {
        zoom: 9,
        center: bld_marker_position,
        mapId: map_id
        // // mapId: mapId,
        // mapTypeControl: false,
        // icon: {
        //     url: google.maps.SymbolPath.BICYCLE
        // },
    });

    // const marker = new google.maps.Marker({
    //     position: startPosition,
    //     map: map,
    //     title: "Nagaland",
    //     icon: {
    //         url: "/images/marker_icons/marker_1.png",
    //         scaledSize: new google.maps.Size(30, 30),
    //     },
    // });

    bld_markers = new AdvancedMarkerElement({
        position: bld_marker_position,
        map: map,
        title: bld_bld_name_qtr_no,
        content: buildingIcon //this is new way to set own marker icon in AdvancedMarkerElement
    });

    bld_markers.addListener("click", function () {
        alert(bld_bld_name_qtr_no);
    });
}

function showMap(btn) {
    let bld_sys_id = btn.getAttribute("data_bld_sys_id");
    let bld_bld_name_qtr_no = btn.getAttribute("data_bld_bld_qtr_name");
    let bld_loc_name = btn.getAttribute("data_loc_name");
    let bld_lat = btn.getAttribute("data_bld_lat");
    let bld_lon = btn.getAttribute("data_bld_lon");

    $('#mapModal').on('shown.bs.modal', function (e) {
        $('#modal_bld_name_qtr_no').prop("value", bld_bld_name_qtr_no);
        $('#modal_bld_loc').prop("value", bld_loc_name);
        initMap(bld_lat, bld_lon, bld_bld_name_qtr_no, bld_loc_name);
    }).modal('show');

    this.getBuildingDetails(bld_sys_id);
}

function getBuildingDetails(buildingID) {
    $.ajax({
        type: "GET",
        url: '/asset-management/get-housing/' + buildingID,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        success: function (response) {
            if (response.status === 'success') {
                const data = response.message;
                var tableHtml =
                    '<div><table class="text-xs table table-striped"><tbody>' +
                    '<tr> <td> Quarter No </td><td>' + data.qtr_no + '</td > </tr>' +
                    '<tr> <td> Quarter Name </td><td>' + data.bld_qtr_name + '</td> </tr>' +
                    '<tr> <td> Type </td><td>' + data.building_type_descr + '</td> </tr>' +
                    '<tr> <td> Category </td><td>' + data.building_catg_descr + '</td> </tr>' +
                    '<tr> <td> Class </td><td>' + data.building_class_descr + '</td> </tr>' +
                    '<tr> <td> Plinth Area(Sq.Ft) </td><td>' + data.plinth_area + '</td> </tr>' +
                    '<tr> <td> Construction Year </td><td>' + data.construction_year + '</td> </tr>' +
                    '</tbody></table></div>';

                $('.building_details_container').html(tableHtml);
            } else {
                console.log(response.message);
            }
        }
    });
}