// DataTables Initialization
$(function () {
    initializeDataTables();
});

function initializeDataTables() {
    $(
        "#road_details_table, #cdworks_details_table, #bridge_details_table, #habitation_details_table, #pci_details_table, #surfaceType_details_table, #protection_wall_table"
    )
        .DataTable({
            buttons: ["csv", "excel"],
            paging: true,
            ordering: true,
            info: true,
        })
        .buttons()
        .container()
        .appendTo(".mis-btn-road");
}

// Generic AJAX request handler
function fetchDataAndPopulateTable(formId, url, tableId, rowCallback) {
    const loader = $("#loader");
    const content = $(".loaderContainer");
    const table = $(tableId);

    content.addClass("blur-background");
    loader.show();

    $.ajax({
        type: "GET",
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $(formId).serialize(),
        cache: false,
        success: function (response) {
            loader.hide();
            content.removeClass("blur-background");

            if (response.status === 200) {
                console.log(response.result);
                if (response.result.length === 0) {
                    $(".road_count").html("00");
                    table
                        .find("tbody")
                        .html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
                    $("#tableContent").slideUp("slow");
                    toggleCaret(false);
                    $(".tot_rd_len").html("00.0");
                    showWarningAlert(response.message);
                } else {
                    $(".road_count").html(response.result.length);
                    var tot_rd_len = 0;
                    $.each(response.result, function (index, data) {
                        tot_rd_len = tot_rd_len + parseFloat(data.road_length);
                    });
                    $(".tot_rd_len").html(parseFloat(tot_rd_len).toFixed(3));
                    // Destroy existing DataTable if it exists
                    if ($.fn.DataTable.isDataTable(tableId)) {
                        $(tableId).DataTable().destroy();
                    }

                    table.find("tbody").empty();

                    // Build table rows using the rowCallback
                    let tableBodyHtml = "";
                    $.each(response.result, function (index, data) {
                        tableBodyHtml += rowCallback(index, data, response.facilities);
                    });
                    table.find("tbody").html(tableBodyHtml);

                    // Initialize DataTable after rows are added
                    $(tableId).DataTable({
                        buttons: ["csv", "excel"],
                        paging: true,
                        ordering: true,
                        info: true,
                    });

                    $("#tableContent").slideDown("slow");
                    toggleCaret(true);
                }
            } else {
                showErrorAlert(response.message);
            }
        },
        error: function (error) {
            loader.hide();
            content.removeClass("blur-background");
            console.error("AJAX error:", error);
            showErrorAlert("An error occurred while fetching data.");
        },
    });
}

// Helper functions for alerts and caret toggling
function showWarningAlert(message) {
    Swal.fire({
        icon: "warning",
        title: "Warning",
        text: message,
        showConfirmButton: true,
        timer: 10000,
    });
}

function showErrorAlert(message) {
    Swal.fire({
        icon: "error",
        title: "Error",
        text: message,
        showConfirmButton: true,
        timer: 10000,
    });
}

function toggleCaret(isDown) {
    const toggleBtn = $("#toggleBtn");
    if (isDown && toggleBtn.hasClass("fa-caret-left")) {
        toggleBtn.removeClass("fa-caret-left").addClass("fa-caret-down");
    } else if (!isDown && toggleBtn.hasClass("fa-caret-down")) {
        toggleBtn.removeClass("fa-caret-down").addClass("fa-caret-left");
    }
}

function updateRegions() {
    const selectedZone = $("#zone").val();
    const selectedCircle = $("#circle").val();
    const selectedDivision = $("#division").val();
    const selectedSubDivision = $("#subDivision").val();
    if (selectedZone == "null") {
        $(".show_zone").html("All Zones");
    } else {
        $.each(zoneDetails, function (index, value) {
            if (value.zone_cd == selectedZone) {
                $(".show_zone").html(value.zone_name);
            }
        });
    }
    if (selectedCircle == "null") {
        $(".show_circle").html("All Circles");
    } else {
        $.each(circleDetails, function (index, value) {
            if (value.circle_cd == selectedCircle) {
                $(".show_circle").html(value.circle_name);
            }
        });
    }
    if (selectedDivision == "null") {
        $(".show_division").html("All Divisions");
    } else {
        $.each(divisionDetails, function (index, value) {
            if (value.division_cd == selectedDivision) {
                $(".show_division").html(value.division_name);
            }
        });
    }
    if (selectedSubDivision == "null") {
        $(".show_subdiv").html("All Sub Divisions");
    } else {
        $.each(subDivisionDetails, function (index, value) {
            if (value.sub_div_cd == selectedSubDivision) {
                $(".show_subdiv").html(value.sub_div_name);
            }
        });
    }
}

// Road Details Submission
$(document).on("submit", "#wing_road", function (e) {
    e.preventDefault();

    const rowCallback = function (index, data) {
        const roadNameButton =
            "<button class='btn btn-xs btn-link' " +
            "data_road_id='" +
            data.rd_system_id +
            "' " +
            "data_road_name='" +
            data.rd_name +
            "' " +
            "data_division_name='" +
            data.division_name +
            "' " +
            "onclick= 'showRoadAbstract(this)'>" +
            data.rd_name +
            "</button>";

        const showInMapButton =
            "<button class='btn btn-xs btn-outline-primary' " +
            "id='btnShowInMap" +
            data.rd_system_id +
            "' " +
            "data-road-id='" +
            data.rd_system_id +
            "' " +
            "data-road-name='" +
            data.rd_name +
            "' " +
            "data-division-name='" +
            data.division_name +
            "' " +
            "data-rd-catg-descr='" +
            data.rd_catg_descr +
            "' " +
            "data-rd-length='" +
            data.road_length +
            "' " +
            "data-center-lat='" +
            data.lat +
            "' " +
            "data-center-lng='" +
            data.lng +
            "' " +
            "onclick='showMap(this)';>" +
            "<i class='fa fa-eye mr-1'></i>Show In Map</button>";

        return `<tr>
                    <td>${++index}</td>
                    <td>${data.rd_system_id}</td>
                    <td>${roadNameButton}</td>
                    <td>${data.road_length}</td>
                    <td>${data.rd_catg_descr}</td>
                    <td>${data.rd_type_descr}</td>
                    <td>${showInMapButton}</td>
                </tr>`;
    };
    updateRegions();
    fetchDataAndPopulateTable(
        "#wing_road",
        "/asset-management/mis-road",
        "#road_details_table",
        rowCallback
    );
});

// CDWorks Details Submission
$(document).on("submit", "#wing_road_cdworks", function (e) {
    e.preventDefault();

    const rowCallback = function (index, data) {
        const viewButton =
            "<button class='btn btn-xs btn-outline-primary' " +
            "id='culvert_" +
            data.rd_cdwork_cd +
            "' " +
            "asset-id='" +
            data.rd_cdwork_cd +
            "' " +
            "asset-descr='" +
            data.cdwoerk_descr +
            "' " +
            "asset-type='0' " +
            "onclick='getAssetImg(this)';>" +
            "<i class='fa fa-eye mr-1'></i>View</button>";
        return `<tr>
                    <td>${++index}</td>
                    <td>${data.rd_name}</td>
                    <td>${data.rd_cdwork_cd}</td>
                    <td>${data.culvert_no}</td>
                    <td>${data.chainage}</td>
                    <td>${data.discharge}</td>
                    <td>${data.year_of_construction}</td>
                    <td>${data.year_of_rehabilitation}</td>
                    <td>${data.cd_condition}</td>
                    <td>${data.cdwoerk_descr}</td>
                    <td>${data.no_of_cell}</td>
                    <td>${data.length_span}</td>
                    <td>${data.width_each_cell}</td>
                    <td>${data.heigth_each_cell}</td>
                    <td>${data.cdwork_thickness_side_wall}</td>
                    <td>${data.cdwork_thickness_top_slab}</td>
                    <td>${data.cdwork_thickness_bottom_slab}</td>
                    <td>${data.span}</td>
                    <td>${data.slab_width}</td>
                    <td>${data.no_of_wing_wall}</td>
                    <td>${data.abutment_type_descr}</td>
                    <td>${data.abutment_height}</td>
                    <td>${data.bearing_type_descr}</td>
                    <td>${data.no_of_rows}</td>
                    <td>${data.height_of_earth_cushion}</td>
                    <td>${data.pipe_diameter}</td>
                    <td>${data.culvert_width}</td>
                    <td>${data.hume_pipe_descr}</td>
                    <td>${data.const_material_type_descr}</td>
                    <td>${data.cdwork_has_safety_apron}</td>
                    <td>${data.apron_type_descr}</td>
                    <td>${data.cdwork_safety_apron_outlet}</td>
                    <td>${data.cdwork_safety_apron_slab_thickness}</td>
                    <td>${data.cdwork_safety_apron_width}</td>
                    <td>${data.cdwork_safety_apron_length}</td>
                    <td>${data.catch_pit_availability}</td>
                    <td>${data.catch_pit_type_descr}</td>
                    <td>${data.catch_pit_width}</td>
                    <td>${data.catch_pit_breadth}</td>
                    <td>${data.catch_pit_heigth}</td>
                    <td>${data.catch_pit_thickness}</td>
                    <td>${data.cp_condition}</td>
                    <td>${data.cdwork_remark}</td>
                    <td>${viewButton}</td>
                </tr>`;
    };
    updateRegions();
    fetchDataAndPopulateTable(
        "#wing_road_cdworks",
        "/asset-management/mis-road-cdworks",
        "#cdworks_details_table",
        rowCallback
    );
});

// Bridge Details Submission
$(document).on("submit", "#wing_road_bridge", function (e) {
    e.preventDefault();

    const rowCallback = function (index, data) {
        return `<tr>
                    <td>${++index}</td>
                    <td>${data.rd_name}</td>
                    <td>${data.rd_bridge_cd}</td>
                    <td>${data.bridge_name}</td>
                    <td>${data.chainage}</td>
                    <td>${data.bridge_type_descr}</td>
                    <td>${data.bridge_width}</td>
                    <td>${data.river_name}</td>
                    <td>${data.construction_type_descr}</td>
                    <td>${data.no_of_span}</td>
                    <td>${data.kerb_width}</td>
                    <td>${data.kerb_height}</td>
                    <td>${data.load_capacity}</td>
                    <td>${data.no_of_piers}</td>
                    <td>${data.st_type_descr}</td>
                    <td>${data.hand_rail_type_descr}</td>
                    <td>${data.deck_type_descr}</td>
                    <td>${data.expn_joint_descr}</td>
                    <td>${data.deck_level}</td>
                    <td>${data.carriage_width}</td>
                    <td>${data.guard_stone}</td>
                    <td>${data.discharge}</td>
                    <td>${data.source_depth}</td>
                    <td>${data.lowest_water_level}</td>
                    <td>${data.highest_flood_level}</td>
                    <td>${data.rfl}</td>
                    <td>${data.year_of_rehabilitation}</td>
                    <td>${data.year_of_construction}</td>
                    <td>${data.date_of_last_inspection}</td>
                    <td>${data.rd_condition_descr}</td>
                    <td>${data.next_schedule_inspection_date}</td>
                    <td>${data.footh_path}</td>
                    <td>${data.bridge_remark}</td>
                </tr>`;
    };
    updateRegions();
    fetchDataAndPopulateTable(
        "#wing_road_bridge",
        "/asset-management/mis-road-bridge",
        "#bridge_details_table",
        rowCallback
    );
});

// PCI Details Submission
$(document).on("submit", "#wing_road_pci", function (e) {
    e.preventDefault();

    const rowCallback = function (index, data) {
        return `<tr>
                    <td>${++index}</td>
                    <td>${data.rd_name}</td>
                    <td>${data.pci_section_cd}</td>
                    <td>${data.pci_section_length_in_meter}</td>
                    <td>${data.chainage}</td>
                    <td>${data.cracking_percent}</td>
                    <td>${data.ravelling_percent}</td>
                    <td>${data.pot_holes_percent}</td>
                    <td>${data.shoving_percent}</td>
                    <td>${data.patching_percent}</td>
                    <td>${data.settlement_depression_percent}</td>
                    <td>${data.rut_depth}</td>
                    <td>${data.tot_motorized_traffic_per_day}</td>
                    <td>${data.pv_traffic_light}</td>
                    <td>${data.pci_value}</td>
                    <td>${data.pci_remarks}</td>
                </tr>`;
    };
    updateRegions();
    fetchDataAndPopulateTable(
        "#wing_road_pci",
        "/asset-management/mis-road-pci",
        "#pci_details_table",
        rowCallback
    );
});

// Protection Wall Details Submission
$(document).on("submit", "#wing_road_protection_wall", function (e) {
    e.preventDefault();

    const rowCallback = function (index, data) {
        return `<tr>
                    <td>${++index}</td>
                    <td>${data.rd_name}</td>
                    <td>${data.protection_wall_cd}</td>
                    <td>${data.chainage}</td>
                    <td>${data.wall_type_descr}</td>
                    <td>${data.structure_type_descr}</td>
                    <td>${data.bottom_width}</td>
                    <td>${data.top_width}</td>
                    <td>${data.length}</td>
                    <td>${data.height}</td>
                    <td>${data.year_of_construction}</td>
                    <td>${data.year_of_renovation}</td>
                </tr>`;
    };
    updateRegions();
    fetchDataAndPopulateTable(
        "#wing_road_protection_wall",
        "/asset-management/mis-road-protection-wall",
        "#protection_wall_table",
        rowCallback
    );
});

// Surface Type Details Submission
$(document).on("submit", "#wing_road_surfaceType", function (e) {
    e.preventDefault();

    const rowCallback = function (index, data) {
        return `<tr>
                    <td>${++index}</td>
                    <td>${data.rd_name}</td>
                    <td>${data.rd_surface_cd}</td>
                    <td>${data.surface_descr}</td>
                    <td>${data.surface_width}</td>
                    <td>${data.shoulder_width}</td>
                    <td>${data.rd_condition_descr}</td>
                    <td>${data.start_chainage}</td>
                    <td>${data.end_chainage}</td>
                    <td>${data.base_layer_type_descr}</td>
                    <td>${data.base_layer_thickness}</td>
                    <td>${data.sub_base_layer_type_descr}</td>
                    <td>${data.sub_base_layer_thickness}</td>
                    <td>${data.pavement_type_descr}</td>
                    <td>${data.shoulder_type_descr}</td>
                    <td>${data.land_slide}</td>
                    <td>${data.construction_year}</td>
                    <td>${data.base_cbr}</td>
                    <td>${data.base_pi}</td>
                    <td>${data.sub_base_cbr}</td>
                    <td>${data.sub_base_pi}</td>
                    <td>${data.maintenance_type_descr}</td>
                    <td>${data.last_maintenance_date}</td>
                    <td>${data.drainage_descr}</td>
                </tr>`;
    };
    updateRegions();
    fetchDataAndPopulateTable(
        "#wing_road_surfaceType",
        "/asset-management/mis-road-surfaceType",
        "#surfaceType_details_table",
        rowCallback
    );
});

// Habitation Details Submission
$(document).on("submit", "#wing_road_habitation", function (e) {
    e.preventDefault();
    const rowCallback = function (index, data, facilities) {
        let facilityHtml = "";
        let collapseId = "fac_" + data.habitation_cd;
        if (facilities[data.habitation_cd]) {
            let grouped = {};
            // group sub facilities by facility name
            $.each(facilities[data.habitation_cd], function (_, item) {
                if (!grouped[item.facility_name]) {
                    grouped[item.facility_name] = [];
                }
                grouped[item.facility_name].push(item.sub_facility_name);
            });
            // build inner table rows
            let innerRows = "";
            $.each(grouped, function (facility, subs) {
                innerRows += `<tr>
                            <td>${facility}</td>
                            <td>${subs.join(", ")}</td>
                          </tr>`;
            });

            facilityHtml = `
            <button class="btn btn-sm btn-primary toggleFacility"
                    data-target="#${collapseId}">
                View
            </button>

            <div id="${collapseId}" style="display:none;margin-top:5px;">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Facility</th>
                            <th>Sub Facilities</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${innerRows}
                    </tbody>
                </table>
            </div>
        `;

        } else {
            facilityHtml = `<span class="text-muted">No facilities</span>`;
        }

        return `<tr>
                    <td>${++index}</td>
                    <td>${data.rd_name}</td>
                    <td>${data.habitation_cd}</td>
                    <td>${data.district}</td>
                    <td>${data.block}</td>
                    <td>${data.village}</td>
                    <td>${data.mla}</td>
                    <td>${data.mp}</td>
                    <td>${data.total_population}</td>
                    <td>${facilityHtml}</td>
                    <td>${data.remarks}</td>
                </tr>`;
    };
    updateRegions();
    fetchDataAndPopulateTable(
        "#wing_road_habitation",
        "/asset-management/mis-road-habitation",
        "#habitation_details_table",
        rowCallback
    );
});

// -------------------------------------------------------------
// Modal Functionalities and Google Maps API related functions
// -------------------------------------------------------------
$(document).on("click", ".toggleFacility", function () {

    let target = $(this).data("target");

    $(target).slideToggle(200);

    if ($(this).text() === "View") {
        $(this).text("Hide");
    } else {
        $(this).text("View");
    }

});
function showRoadAbstract(btn) {
    $("#roadAbstractModal")
        .on("shown.bs.modal", function (e) {
            let rd_id = btn.getAttribute("data_road_id");
            let rd_name = btn.getAttribute("data_road_name");
            let div_name = btn.getAttribute("data_division_name");
            $.ajax({
                url: "/asset-management/getRoadsAssetsAbstractDetails/" + rd_id,
                type: "GET",
                cache: false,
                success: function (response) {
                    $("#roadAbstractModal_road_name").text(rd_name);
                    $("#modal_road_id").text(rd_id);
                    $("#roadAbstractModal_div_name").text(div_name);

                    const table_id = $("#road_asset_abstract_details_table");

                    var roadAssetAbstractDetails =
                        "<tr>" +
                        "<td> 1. </td>" +
                        "<td> CD Work </td>" +
                        "<td>" +
                        "<button class='btn btn-link' " +
                        "cd_work_data_road_id='" +
                        rd_id +
                        "' " +
                        "cd_work_data_road_name='" +
                        rd_name +
                        "' " +
                        "cd_work_data_division_name='" +
                        div_name +
                        "' " +
                        "onclick= 'showCulvertDetails(this)'>" +
                        response["totalCulvert"].total_culvert +
                        "</button>" +
                        " </td>" +
                        "</tr>" +
                        "<tr>" +
                        "<td> 2. </td>" +
                        "<td> Bridge </td>" +
                        "<td>" +
                        "<button class='btn btn-link' " +
                        "data_road_id='" +
                        rd_id +
                        "' " +
                        "data_road_name='" +
                        rd_name +
                        "' " +
                        "data_division_name='" +
                        div_name +
                        "' " +
                        "onclick= 'showRoadDetails(this)'>" +
                        response["totalBridge"].total_bridge +
                        "</button>" +
                        " </td>" +
                        "</tr>" +
                        "<tr>" +
                        "<td> 3. </td>" +
                        "<td> PCI </td>" +
                        "<td>" +
                        "<button class='btn btn-link' " +
                        "data_road_id='" +
                        rd_id +
                        "' " +
                        "data_road_name='" +
                        rd_name +
                        "' " +
                        "data_division_name='" +
                        div_name +
                        "' " +
                        "onclick= 'showPCIDetails(this)'>" +
                        response["totalPCI"].total_pci +
                        "</button>" +
                        "</td>" +
                        "</tr>" +
                        "<tr>" +
                        "<td> 4. </td>" +
                        "<td> Surface Types </td>" +
                        "<td>" +
                        "<button class='btn btn-link' " +
                        "data_road_id='" +
                        rd_id +
                        "' " +
                        "data_road_name='" +
                        rd_name +
                        "' " +
                        "data_division_name='" +
                        div_name +
                        "' " +
                        "onclick= 'showSurfaceTypeDetails(this)'>" +
                        response["totalSurfaceTypes"].total_surface_types +
                        "</button>" +
                        "</td>" +
                        "</tr>" +
                        "<tr>" +
                        "<td> 5. </td>" +
                        "<td> Habitations </td>" +
                        "<td>" +
                        "<button class='btn btn-link' " +
                        "data_road_id='" +
                        rd_id +
                        "' " +
                        "data_road_name='" +
                        rd_name +
                        "' " +
                        "data_division_name='" +
                        div_name +
                        "' " +
                        "onclick= 'showHabitaionDetails(this)'>" +
                        response["totalHabitations"].total_habitation +
                        "</button>" +
                        "</td>" +
                        "</tr>";

                    table_id.find("tbody").empty();
                    table_id.find("tbody").append(roadAssetAbstractDetails);
                },
                error: function (error) {
                    console.log(error);
                },
            });
        })
        .modal("show");
}

function showCulvertDetails(btn) {
    $("#culvertDetailsForARoadModal")
        .on("shown.bs.modal", function (e) {
            let rd_id = btn.getAttribute("cd_work_data_road_id");
            let rd_name = btn.getAttribute("cd_work_data_road_name");
            let div_name = btn.getAttribute("cd_work_data_division_name");
            $.ajax({
                url: "/asset-management/getCDWorkDetailsOfARoad/" + rd_id,
                type: "GET",
                cache: false,
                success: function (response) {
                    $("#culvertDetails_road_name").text(rd_name);
                    $("#culvertDetails_modal_road_id").text(rd_id);
                    $("#culvertDetails_div_name").text(div_name);

                    const table_id = $("#cd_work_details_table");
                    if (response.status === "success") {
                        table_id.find("tbody").empty();
                        $.each(response.result, function (index, data) {
                            var newCulvertData =
                                "<tr>" +
                                "<td>" +
                                ++index +
                                "</td>" +
                                "<td>" +
                                data.culvert_no +
                                "</td>" +
                                "<td>" +
                                data.chainage +
                                "</td>" +
                                "<td>" +
                                data.discharge +
                                "</td>" +
                                "<td>" +
                                data.year_of_construction +
                                "</td>" +
                                "<td>" +
                                data.year_of_rehabilitation +
                                "</td>" +
                                "<td>" +
                                data.rd_condition_descr +
                                "</td>" +
                                "<td>" +
                                data.cdwoerk_descr +
                                "</td>" +
                                "<td>" +
                                data.cussion +
                                "</td>" +
                                "<td>" +
                                data.cdwork_size +
                                "</td>" +
                                "<td>" +
                                data.cdwork_width +
                                "</td>" +
                                "<td>" +
                                data.cdwork_height +
                                "</td>" +
                                "<td>" +
                                data.cdwork_length +
                                "</td>" +
                                "<td>" +
                                data.cdwork_outlet +
                                "</td>" +
                                "<td>" +
                                data.cdwork_no_of_vents +
                                "</td>" +
                                "<td>" +
                                data.cdwork_thickness_side_wall +
                                "</td>" +
                                "<td>" +
                                data.cdwork_thickness_top_slab +
                                "</td>" +
                                "<td>" +
                                data.cdwork_thickness_bottom_slab +
                                "</td>" +
                                "<td>" +
                                data.cdwork_has_safety_apron +
                                "</td>" +
                                "<td>" +
                                data.cdwork_apron_width +
                                "</td>" +
                                "<td>" +
                                data.discharge +
                                "</td>" +
                                "<td>" +
                                data.span +
                                "</td>" +
                                "<td>" +
                                data.carriage_way +
                                "</td>" +
                                "<td>" +
                                data.no_of_rows +
                                "</td>" +
                                "<td>" +
                                data.pipe_diameter +
                                "</td>" +
                                "<td>" +
                                data.pipe_length +
                                "</td>" +
                                "<td>" +
                                data.pipe_specification +
                                "</td>" +
                                "<td>" +
                                data.slab_length +
                                "</td>" +
                                "<td>" +
                                data.slab_width +
                                "</td>" +
                                "<td>" +
                                data.vent_height +
                                "</td>" +
                                "<td>" +
                                data.no_of_wing_wall +
                                "</td>" +
                                "<td>" +
                                data.no_of_cell +
                                "</td>" +
                                "<td>" +
                                data.width_each_cell +
                                "</td>" +
                                "<td>" +
                                data.heigth_each_cell +
                                "</td>" +
                                "<td>" +
                                data.height_of_earth_cushion +
                                "</td>" +
                                "<td>" +
                                data.culvert_location +
                                "</td>" +
                                "<td>" +
                                data.no_of_opening +
                                "</td>" +
                                "<td>" +
                                data.outlet_type_cd +
                                "</td>" +
                                "<td>" +
                                data.catch_pit_availability +
                                "</td>" +
                                "<td>" +
                                data.catch_pit_type_cd +
                                "</td>" +
                                "<td>" +
                                data.catch_pit_size +
                                "</td>" +
                                "<td>" +
                                data.catch_pit_condition +
                                "</td>" +
                                "<td>" +
                                data.catch_toe_wall_size +
                                "</td>" +
                                "<td>" +
                                data.cdwork_remark +
                                "</td>" +
                                "<td>" +
                                data.cdwork_has_wing_wall +
                                "</td>" +
                                "<td>" +
                                data.cdwork_has_head_wall +
                                "</td>" +
                                "</tr>";

                            table_id.find("tbody").append(newCulvertData);
                        });
                    }
                },
                error: function (error) {
                    console.log(error);
                },
            });
        })
        .modal("show");
}

// -------------------------------------------------------------
// Google Maps API related functions
// -------------------------------------------------------------

// Map -- Start
let datasetLayer;
let lastClickedFeatureIds = [];
let lastInteractedFeatureIds = [];
let markers = [];
let map;
var all_states_geojson_data;
let rd_id;
let center_lat;
let center_lng;

$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/getAllStatesRoadsGeoJsonData/",
        contentType: "application/json; charset=utf-8",
        crossDomain: true,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response, status, jqXHR) {
            if (response.status === true) {
                let compressedArray = Uint8Array.from(atob(response.all_states_geojson_data), c => c.charCodeAt(0));
                let decompressedData = pako.inflate(compressedArray, { to: 'string' });
                all_states_geojson_data = JSON.parse(decompressedData);
                // all_states_geojson_data = JSON.parse(
                //     response.all_states_geojson_data
                // );
            } else {
            }
        },
        error: function (error) {
            console.log(
                "Some Technical Issue!!Map Data Could Not Fetched From Server,Please Contact Administrator!!"
            );
        },
    });
});

((g) => {
    var h,
        a,
        k,
        p = "The Google Maps JavaScript API",
        c = "google",
        l = "importLibrary",
        q = "__ib__",
        m = document,
        b = window;
    b = b[c] || (b[c] = {});
    var d = b.maps || (b.maps = {}),
        r = new Set(),
        e = new URLSearchParams(),
        u = () =>
            h ||
            (h = new Promise(async (f, n) => {
                await (a = m.createElement("script"));
                e.set("libraries", [...r] + "");
                for (k in g)
                    e.set(
                        k.replace(/[A-Z]/g, (t) => "_" + t[0].toLowerCase()),
                        g[k]
                    );
                e.set("callback", c + ".maps." + q);
                a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                d[q] = f;
                a.onerror = () => (h = n(Error(p + " could not load.")));
                a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                m.head.append(a);
            }));
    d[l]
        ? console.warn(p + " only loads once. Ignoring:", g)
        : (d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)));
})({
    key: "AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM",
    v: "beta",
    // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
    // Add other bootstrap parameters as needed, using camel case.
});

function getAssetImg(btn) {
    let asset_id = btn.getAttribute("asset-id");
    let asset_desc = btn.getAttribute("asset-descr");
    let asset_type = btn.getAttribute("asset-type");
    // calling a ajax request
    $.ajax({
        url: "/asset-management/view-culvert-images/" + asset_id + "/" + asset_type,
        type: "GET",
        cache: false,
        success: function (response) {
            console.log(response); // Log the response to the console

            // Check if the response contains image URLs
            if (response.img_url_list && response.img_url_list.length > 0) {
                // Loop through each image URL in the response
                response.img_url_list.forEach(function (image) {
                    // Append the image to the #imageContainer
                    $("#imageContainer").append(
                        '<div class="col-sm-6 col-md-4">' +
                        '<img src="' +
                        image.img_url +
                        '" alt="img" width="100%">' +
                        "</div>"
                    );
                });
            } else {
                $("#imageContainer").append(
                    '<p class="text-sm text-secondary">No Image found!</p>'
                );
                console.log("No images found in the response.");
            }
        },
        error: function (xhr) {
            console.error("AJAX Error:", xhr); // Log any errors
        },
    });
    // $("#imageContainer").append('<div class="col-sm-6 col-md-4"><img src="https://images.unsplash.com/photo-1471899236350-e3016bf1e69e?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Zmxvd2VyfGVufDB8fDB8fHww" alt="img" width="100%" ></div>');
    $("#mapModal")
        .on("shown.bs.modal", function (e) { })
        .modal("show");
}

function showMap(btn) {
    rd_id = btn.getAttribute("data-road-id");
    let rd_name = btn.getAttribute("data-road-name");
    let div_name = btn.getAttribute("data-division-name");
    let rd_catg = btn.getAttribute("data-rd-catg-descr");
    let rd_lngth = btn.getAttribute("data-rd-length");
    center_lat = btn.getAttribute("data-center-lat");
    center_lng = btn.getAttribute("data-center-lng");
    if (div_name == "") div_name = "NA";

    $("#lblRoadInfo").text(
        " Road Id : " +
        rd_id +
        ", Road Name: " +
        rd_name +
        ", Division: " +
        div_name
    );
    initMap(all_states_geojson_data, rd_id, center_lat, center_lng);
    var tableHtml =
        '<table class="table table-striped bordered"><tbody>' +
        "<tr> <td> Road Name </td><td>" +
        rd_name +
        "</td > </tr>" +
        "<tr> <td> Road ID </td><td>" +
        rd_id +
        "</td> </tr>" +
        "<tr> <td> Road Length </td><td>" +
        rd_lngth +
        "</td> </tr>" +
        "<tr> <td> Division Name </td><td>" +
        div_name +
        "</td> </tr>" +
        "<tr> <td> Road Category </td><td>" +
        rd_catg +
        "</td> </tr>" +
        "</tbody></table>";
    $("#road-sum-info").html(tableHtml);

    $("#mapModal")
        .on("shown.bs.modal", function (e) { })
        .modal("show");
} // end of showMap function

async function initMap(dataset, selected_rd_id, r_lat, r_lng) {
    if (r_lat === null || isNaN(r_lat)) r_lat = 26.094757374299146;
    if (r_lng === null || isNaN(r_lng)) r_lng = 94.58979407214116;

    let startPosition;
    const { Map } = await google.maps.importLibrary("maps");
    try {
        startPosition = {
            lat: parseFloat(r_lat),
            lng: parseFloat(r_lng),
        };
    } catch (e) {
        startPosition = {
            lat: 26.094757374299146,
            lng: 94.58979407214116,
        };
    }

    map = new Map(document.getElementById("map"), {
        center: startPosition,
        zoom: 9,
    });

    try {
        data_layer = new google.maps.Data({
            map: map,
        });

        data_layer.addGeoJson(dataset);
    } catch (e) {
        console.log(e);
    }

    data_layer.setStyle(function (feature) {
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
                strokeColor = "#005500";
                fillColor = "#005500";
                strokeWeight = 1.7;
                break;
            case "MDR":
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

        if (feature.getProperty("road_id") == selected_rd_id) {
            strokeWeight = 5.0;
        }

        return {
            strokeColor: strokeColor,
            fillColor: fillColor,
            strokeWeight: strokeWeight,
            strokeOpacity: 1.0,
            fillOpacity: 0.3,
        };
    });

    data_layer.addListener("click", handleClickOnDataSet);
    data_layer.addListener("mousemove", handleMouseMoveOnDataSet);

    //   // Map event listener.
    map.addListener("mousemove", () => {
        // If the map gets a mousemove, that means there are no feature layers
        // with listeners registered under the mouse, so we clear the last
        // interacted feature ids.
        console.log("Inside Map mouseMove");

        // if (lastInteractedFeatureIds.length > 0)
        if (lastInteractedFeatureIds?.length) {
            lastInteractedFeatureIds = [];
        }
    });

    const attributionDiv = document.createElement("div");
    const attributionControl = createAttribution(map);

    attributionDiv.appendChild(attributionControl);
    map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(attributionDiv);

    map.setZoom(Math.max(map.getZoom(), 12));
}
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

function handleMouseMoveOnDataSet(/* MouseEvent */ e) {
    if (e.feature) {
        lastInteractedFeatureIds = e.feature.getProperty("road_id");
        // datasetLayer.style = applyStyle;
    }
} // end of handleMouseMoveOnDataSet
function handleClickOnDataSet(/* MouseEvent */ e) {
    var jsonString;
    var span = document.getElementsByClassName("closeRdInfoModal")[0];

    if (e.feature) {
        lastClickedFeatureIds = e.feature.getProperty("road_id");
        var rd_name = e.feature.getProperty("Name");

        var rd_id = e.feature.getProperty("road_id");
        var division_name = e.feature.getProperty("division_name");
        var road_num = e.feature.getProperty("road_id");
        var road_length = e.feature.getProperty("road_length");
        var road_catg = e.feature.getProperty("road_category");
        var tableHtml =
            '<table class="table table-striped bordered"><tbody>' +
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
            "</tbody></table>";
        data_layer.setStyle(function (feature) {
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
                    strokeColor = "#005500";
                    fillColor = "#005500";
                    strokeWeight = 1.7;
                    break;
                case "MDR":
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
            if (feature.getProperty("road_id") == lastClickedFeatureIds) {
                strokeWeight = 4.0;
            }
            return {
                strokeColor: strokeColor,
                fillColor: fillColor,
                strokeWeight: strokeWeight,
                strokeOpacity: 1.0,
                fillOpacity: 0.3,
            };
        });

        $("#road-sum-info").html(tableHtml);
    }
} //end of handleClickOnDataSet

function applyStyle(
    /* FeatureStyleFunctionOptions */ params,
    /* MouseEvent */ e
) {
    const datasetFeature = e.feature;
    //@ts-ignore
    if (
        lastInteractedFeatureIds.includes(datasetFeature.getProperty("road_id"))
    ) {
        return styleClicked;
    }
    return styleDefault;
}
// Map -- End
