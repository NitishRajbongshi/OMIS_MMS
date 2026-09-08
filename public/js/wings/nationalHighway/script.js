$(function () {
    $(
        "#nh_details_table, #nh_habitation_details_table, #nh_cdworks_details_table, #nh_bridge_details_table, #nh_pci_details_table, #nh_surfaceType_details_table"
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
});

// get road details after filteration
$(document).on("submit", "#wing_nh", function (e) {
    console.log("clicked");
    e.preventDefault();
    var loader = $("#loader");
    var content = $(".loaderContainer");
    content.addClass("blur-background");
    loader.show();
    $.ajax({
        type: "GET",
        url: "/asset-management/mis-nh",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#wing_nh").serialize(),
        cache: false,
        success: function (response) {
            const table_id = $("#nh_details_table");
            if (response.status === 200) {
                table_id.find("tbody").empty();
                if (response.result.length === 0) {
                    $('.road_count').html('00');
                    table_id
                        .find("tbody")
                        .html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
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
                    $('.road_count').html(response.result.length);
                    $.each(response.result, function (index, data) {
                        var newRoadData =
                            "<tr>" +
                            "<td>" +
                            ++index +
                            "</td>" +
                            "<td>" +
                            data.rd_system_id +
                            "</td>" +
                            "<td>" +
                            data.rd_catg_descr +
                            "</td>" +
                            // "<td>" +
                            // data.rd_number +
                            // "</td>" +
                            "<td>" +
                            "<button class='btn btn-xs btn-link' " +
                            "data_road_id='" + data.rd_system_id + "' " +
                            "data_road_name='" + data.rd_name + "' " +
                            "data_division_name='" + data.division_name + "' " +
                            "onclick= 'showRoadAbstract(this)'>" +
                            data.rd_name +
                            "</button>" +
                            "</td>" +
                            "<td>" +
                            data.rd_type_descr +
                            "</td>" +
                            "<td>" +
                            data.road_length +
                            "</td>" +
                            "<td>" +
                            data.district_name +
                            "</td>" +
                            "<td>" +
                            data.owner_name +
                            "</td>" +
                            "<td>" +
                            "<button class='btn btn-xs btn-outline-primary' " +
                            "id='btnShowInMap" + data.rd_system_id + "' " +
                            "data-road-id='" + data.rd_system_id + "' " +
                            "data-road-name='" + data.rd_name + "' " +
                            "data-division-name='" + data.division_name + "' " +
                            "data-rd-catg-descr='" + data.rd_catg_descr + "' " +
                            "data-rd-length='" + data.road_length + "' " +
                            "data-center-lat='" + data.lat + "' " +
                            "data-center-lng='" + data.lng + "' " +
                            "onclick='showMap(this)';>" +
                            "<i class='fa fa-eye mr-1'></i>Show In Map</button>"
                        "</td>" +
                            "</tr>";
                        table_id.find("tbody").append(newRoadData);
                    });
                    loader.hide();
                    content.removeClass("blur-background");
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

// get CDWork after filteration
$(document).on("submit", "#wing_nh_cdworks", function (e) {
    e.preventDefault();
    var loader = $("#loader");
    var content = $(".loaderContainer");
    content.addClass("blur-background");
    loader.show();
    $.ajax({
        type: "GET",
        url: "/asset-management/mis-nh-cdworks",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#wing_nh_cdworks").serialize(),
        cache: false,
        success: function (response) {
            const table_id = $("#nh_cdworks_details_table");
            if (response.status === 200) {
                table_id.find("tbody").empty();
                if (response.result.length === 0) {
                    $('.road_count').html('00');
                    table_id
                        .find("tbody")
                        .html(
                            '<tr><td colspan="43" class="text-center">No matching records found</td></tr>'
                        );
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
                    var cdworks_details_datatable = new DataTable('#nh_cdworks_details_table');
                    cdworks_details_datatable.clear().draw();
                    $('.road_count').html(response.result.length);
                    $.each(response.result, function (index, data) {
                        var newRoadData = [
                            ++index,
                            data.rd_name,
                            data.rd_cdwork_cd,
                            data.culvert_no,
                            data.chainage,
                            data.discharge,
                            data.year_of_construction,
                            data.year_of_rehabilitation,
                            data.cd_condition,
                            data.cdwoerk_descr,
                            data.no_of_cell,
                            data.length_span,
                            data.width_each_cell,
                            data.heigth_each_cell,
                            data.cdwork_thickness_side_wall,
                            data.cdwork_thickness_top_slab,
                            data.cdwork_thickness_bottom_slab,
                            data.span,
                            data.slab_width,
                            data.no_of_wing_wall,
                            data.abutment_type_descr,
                            data.abutment_height,
                            data.bearing_type_descr,
                            data.no_of_rows,
                            data.height_of_earth_cushion,
                            data.pipe_diameter,
                            data.culvert_width,
                            data.hume_pipe_descr,
                            data.const_material_type_descr,
                            data.cdwork_has_safety_apron,
                            data.apron_type_descr,
                            data.cdwork_safety_apron_outlet,
                            data.cdwork_safety_apron_slab_thickness,
                            data.cdwork_safety_apron_width,
                            data.cdwork_safety_apron_length,
                            data.catch_pit_availability,
                            data.catch_pit_type_descr,
                            data.catch_pit_width,
                            data.catch_pit_breadth,
                            data.catch_pit_heigth,
                            data.catch_pit_thickness,
                            data.cp_condition,
                            data.cdwork_remark
                        ];
                        cdworks_details_datatable.row.add(newCDWorkData);
                    });
                    loader.hide();
                    content.removeClass("blur-background");
                    $("#tableContent").slideDown('slow');
                    cdworks_details_datatable.draw();
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

// get Bridge after filteration
$(document).on("submit", "#wing_nh_bridge", function (e) {
    console.log("clicked");
    e.preventDefault();
    var loader = $("#loader");
    var content = $(".loaderContainer");
    content.addClass("blur-background");
    loader.show();
    $.ajax({
        type: "GET",
        url: "/asset-management/mis-nh-bridge",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#wing_nh_bridge").serialize(),
        cache: false,
        success: function (response) {
            const table_id = $("#nh_bridge_details_table");
            if (response.status === 200) {
                table_id.find("tbody").empty();
                if (response.result.length === 0) {
                    $('.road_count').html('00');
                    table_id
                        .find("tbody")
                        .html(
                            '<tr><td colspan="33" class="text-center">No matching records found</td></tr>'
                        );
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
                    $('.road_count').html(response.result.length);
                    var bridge_details_datatable = new DataTable('#nh_bridge_details_table').draw();
                    bridge_details_datatable.clear().draw();
                    $.each(response.result, function (index, data) {
                        var newBridgeData = [
                            ++index,
                            data.rd_name,
                            data.rd_bridge_cd,
                            data.bridge_name,
                            data.chainage,
                            data.bridge_type_descr,
                            data.bridge_width,
                            data.river_name,
                            data.construction_type_descr,
                            data.no_of_span,
                            data.kerb_width,
                            data.kerb_height,
                            data.load_capacity,
                            data.no_of_piers,
                            data.st_type_descr,
                            data.hand_rail_type_descr,
                            data.deck_type_descr,
                            data.expn_joint_descr,
                            data.deck_level,
                            data.carriage_width,
                            data.guard_stone,
                            data.discharge,
                            data.source_depth,
                            data.lowest_water_level,
                            data.highest_flood_level,
                            data.rfl,
                            data.year_of_rehabilitation,
                            data.year_of_construction,
                            data.date_of_last_inspection,
                            data.rd_condition_descr,
                            data.next_schedule_inspection_date,
                            data.footh_path,
                            data.bridge_remark,
                        ];
                        bridge_details_datatable.row.add(newBridgeData);
                    });
                    loader.hide();
                    content.removeClass("blur-background");
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

// get PCI after filteration
$(document).on("submit", "#wing_nh_pci", function (e) {
    console.log("clicked");
    e.preventDefault();
    var loader = $("#loader");
    var content = $(".loaderContainer");
    content.addClass("blur-background");
    loader.show();
    $.ajax({
        type: "GET",
        url: "/asset-management/mis-nh-pci",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#wing_nh_pci").serialize(),
        cache: false,
        success: function (response) {
            console.log(response);
            const table_id = $("#nh_pci_details_table");
            if (response.status === 200) {
                table_id.find("tbody").empty();
                if (response.result.length === 0) {
                    $('.road_count').html('00');
                    table_id
                        .find("tbody")
                        .html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
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
                    $('.road_count').html(response.result.length);
                    var pci_details_datatable = new DataTable('#nh_pci_details_table').draw();
                    pci_details_datatable.clear().draw();
                    $.each(response.result, function (index, data) {
                        var newPCIData = [
                            ++index,
                            data.rd_name,
                            data.pci_section_cd,
                            data.pci_section_length_in_meter,
                            data.chainage,
                            data.cracking_percent,
                            data.ravelling_percent,
                            data.pot_holes_percent,
                            data.shoving_percent,
                            data.patching_percent,
                            data.settlement_depression_percent,
                            data.rut_depth,
                            data.tot_motorized_traffic_per_day,
                            data.pv_traffic_light,
                            data.pci_value,
                            data.pci_remarks];
                        pci_details_datatable.row.add(newPCIData);
                    });
                    loader.hide();
                    content.removeClass("blur-background");
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

// get Protection wall after filteration
$(document).on("submit", "#wing_nh_protection_wall", function (e) {
    e.preventDefault();
    console.log('called!');
    var loader = $("#loader");
    var content = $(".loaderContainer");
    content.addClass("blur-background");
    loader.show();
    $.ajax({
        type: "GET",
        url: "/asset-management/mis-nh-protection-wall",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#wing_road_protection_wall").serialize(),
        cache: false,
        success: function (response) {
            console.log(response);
            const table_id = $("#protection_wall_table");
            if (response.status === 200) {
                table_id.find("tbody").empty();
                if (response.result.length === 0) {
                    $('.road_count').html('00');
                    table_id
                        .find("tbody")
                        .html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
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
                    $('.road_count').html(response.result.length);
                    var protection_wall_details_datatable = new DataTable('#protection_wall_table').draw();
                    protection_wall_details_datatable.clear().draw();
                    $.each(response.result, function (index, data) {
                        var newPCIData =
                            [++index,
                            data.protection_wall_cd,
                            data.chainage,
                            data.wall_type_descr,
                            data.structure_type_descr,
                            data.bottom_width,
                            data.top_width,
                            data.length,
                            data.height,
                            data.year_of_construction,
                            data.year_of_renovation];
                        protection_wall_details_datatable.row.add(newPCIData);
                    });
                    loader.hide();
                    content.removeClass("blur-background");
                    protection_wall_details_datatable.draw();
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

// get PCI after filteration
$(document).on("submit", "#wing_nh_surfaceType", function (e) {
    console.log("clicked");
    e.preventDefault();
    var loader = $("#loader");
    var content = $(".loaderContainer");
    content.addClass("blur-background");
    loader.show();
    $.ajax({
        type: "GET",
        url: "/asset-management/mis-nh-surfaceType",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#wing_nh_surfaceType").serialize(),
        cache: false,
        success: function (response) {
            console.log(response);
            const table_id = $("#nh_surfaceType_details_table");
            if (response.status === 200) {
                table_id.find("tbody").empty();
                if (response.result.length === 0) {
                    $('.road_count').html('00');
                    table_id
                        .find("tbody")
                        .html(
                            '<tr><td colspan="23" class="text-center">No matching records found</td></tr>'
                        );
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
                    $('.road_count').html(response.result.length);
                    var surfaceType_details_datatable = new DataTable('#nh_surfaceType_details_table');
                    surfaceType_details_datatable.clear().draw();
                    $.each(response.result, function (index, data) {
                        var newSurfaceTypeData = [
                            ++index,
                            data.rd_name,
                            data.rd_surface_cd,
                            data.surface_descr,
                            data.surface_width,
                            data.shoulder_width,
                            data.rd_condition_descr,
                            data.start_chainage,
                            data.end_chainage,
                            data.base_layer_type_descr,
                            data.base_layer_thickness,
                            data.sub_base_layer_type_descr,
                            data.sub_base_layer_thickness,
                            data.pavement_type_descr,
                            data.shoulder_type_descr,
                            data.land_slide,
                            data.construction_year,
                            data.base_cbr,
                            data.base_pi,
                            data.sub_base_cbr,
                            data.sub_base_pi,
                            data.maintenance_type_descr,
                            data.last_maintenance_date,
                            data.drainage_descr];
                        surfaceType_details_datatable.row.add(newSurfaceTypeData);
                    });
                    loader.hide();
                    content.removeClass("blur-background");
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

// get habitation after filteration
$(document).on("submit", "#wing_nh_habitation", function (e) {
    console.log("clicked");
    e.preventDefault();
    var loader = $("#loader");
    var content = $(".loaderContainer");
    content.addClass("blur-background");
    loader.show();
    $.ajax({
        type: "GET",
        url: "/asset-management/mis-nh-habitation",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#wing_nh_habitation").serialize(),
        cache: false,
        success: function (response) {
            console.log(response);
            const table_id = $("#nh_habitation_details_table");
            if (response.status === 200) {
                table_id.find("tbody").empty();
                if (response.result.length === 0) {
                    $('.road_count').html('00');
                    table_id
                        .find("tbody")
                        .html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
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
                    $('.road_count').html(response.result.length);
                    var habitation_details_datatable = new DataTable('#nh_habitation_details_table');
                    habitation_details_datatable.clear().draw();
                    $.each(response.result, function (index, data) {
                        var newHabitationData = [
                            ++index,
                            data.rd_name,
                            data.habitation_cd,
                            data.district,
                            data.village,
                            data.mla,
                            data.mp,
                            data.total_population,
                            data.remarks];
                        habitation_details_datatable.row.add(newHabitationData);
                    });
                    loader.hide();
                    content.removeClass("blur-background");
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

function showRoadAbstract(btn) {
    $('#roadAbstractModal').on('shown.bs.modal', function (e) {
        let rd_id = btn.getAttribute("data_road_id");
        let rd_name = btn.getAttribute("data_road_name");
        let div_name = btn.getAttribute("data_division_name");
        $.ajax({
            url: '/asset-management/getRoadsAssetsAbstractDetails/' + rd_id,
            type: 'GET',
            cache: false,
            success: function (response) {
                side
                $('#roadAbstractModal_road_name').text(rd_name);
                $('#modal_road_id').text(rd_id);
                $('#roadAbstractModal_div_name').text(div_name);

                const table_id = $("#road_asset_abstract_details_table");

                var roadAssetAbstractDetails = "<tr>" +
                    "<td> 1. </td>" +
                    "<td> CD Work </td>" +
                    "<td>" +
                    "<button class='btn btn-link' " +
                    "cd_work_data_road_id='" + rd_id + "' " +
                    "cd_work_data_road_name='" + rd_name + "' " +
                    "cd_work_data_division_name='" + div_name + "' " +
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
                    "data_road_id='" + rd_id + "' " +
                    "data_road_name='" + rd_name + "' " +
                    "data_division_name='" + div_name + "' " +
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
                    "data_road_id='" + rd_id + "' " +
                    "data_road_name='" + rd_name + "' " +
                    "data_division_name='" + div_name + "' " +
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
                    "data_road_id='" + rd_id + "' " +
                    "data_road_name='" + rd_name + "' " +
                    "data_division_name='" + div_name + "' " +
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
                    "data_road_id='" + rd_id + "' " +
                    "data_road_name='" + rd_name + "' " +
                    "data_division_name='" + div_name + "' " +
                    "onclick= 'showHabitaionDetails(this)'>" +
                    response["totalHabitations"].total_habitation +
                    "</button>" +
                    "</td>" +
                    "</tr>"

                table_id.find("tbody").empty();
                table_id.find("tbody").append(roadAssetAbstractDetails);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }).modal('show');
}

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
        type: 'GET',
        // url: $('#hdnGetAllStatesRoadJsonDataUrl').val(),
        url: "/getAllStatesRoadsGeoJsonData/",
        contentType: "application/json; charset=utf-8",
        crossDomain: true,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
        }
    });
});

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
    d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(
        () => d[l](f, ...n))
})({
    key: "AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM",
    v: "beta",
    // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
    // Add other bootstrap parameters as needed, using camel case.
});

function showMap(btn) {
    rd_id = btn.getAttribute("data-road-id");
    let rd_name = btn.getAttribute("data-road-name");
    let div_name = btn.getAttribute("data-division-name");
    let rd_catg = btn.getAttribute("data-rd-catg-descr");
    let rd_lngth = btn.getAttribute("data-rd-length");
    center_lat = btn.getAttribute("data-center-lat");
    center_lng = btn.getAttribute("data-center-lng");
    if (div_name == "")
        div_name = "NA";

    $("#lblRoadInfo").text(" Road Id : " + rd_id + ", Road Name: " + rd_name + ", Division: " + div_name);
    initMap(all_states_geojson_data, rd_id, center_lat, center_lng);
    var tableHtml =
        '<table class="table table-striped bordered"><tbody>' +
        '<tr> <td> Road Name </td><td>' + rd_name + '</td > </tr>' +
        '<tr> <td> Road ID </td><td>' + rd_id + '</td> </tr>' +
        '<tr> <td> Road Length </td><td>' + rd_lngth + '</td> </tr>' +
        '<tr> <td> Division Name </td><td>' + div_name + '</td> </tr>' +
        '<tr> <td> Road Category </td><td>' + rd_catg + '</td> </tr>' +
        '</tbody></table>';
    $("#road-sum-info").html(tableHtml);


    $('#mapModal').on('shown.bs.modal', function (e) {

    }).modal('show');
} // end of showMap function

async function initMap(dataset, selected_rd_id, r_lat, r_lng) {
    console.log(dataset);

    if (r_lat === null || isNaN(r_lat))
        r_lat = 26.094757374299146;
    if (r_lng === null || isNaN(r_lng))
        r_lng = 94.58979407214116;


    let startPosition;
    const {
        Map
    } = await google.maps.importLibrary("maps");
    try {
        startPosition = {
            lat: parseFloat(r_lat),
            lng: parseFloat(r_lng),

        };
    }
    catch (e) {
        startPosition = {
            lat: 26.094757374299146,
            lng: 94.58979407214116,
        };
        console.log(e);
    }


    map = new Map(document.getElementById('map'), {
        center: startPosition,
        zoom: 9
    });

    try {
        data_layer = new google.maps.Data({
            map: map
        });

        data_layer.addGeoJson(dataset);
    }
    catch (e) {
        console.log(e);
    }

    data_layer.setStyle(function (feature) {
        var roadCatg = feature.getProperty('road_category');
        var strokeColor;
        var fillColor;
        var strokeWeight;
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

        if (feature.getProperty('road_id') == selected_rd_id) {
            strokeWeight = 5.0;
        }

        return {
            strokeColor: strokeColor,
            fillColor: fillColor,
            strokeWeight: strokeWeight,
            strokeOpacity: 1.0,
            fillOpacity: 0.3
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
    map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(
        attributionDiv
    );

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
function handleClick( /* MouseEvent */ e) {
    var jsonString;
    if (e.features) {
        var obj = new Object();

        lastClickedFeatureIds = e.features.map(
            (f) => f.datasetAttributes["road_id"]
        );
        var rd_name = e.features.map(
            (f) => f.datasetAttributes["Name"]
        );
        var rd_id = e.features.map((f) => f.datasetAttributes["road_id"]);
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
            '<table class="table table-striped bordered"><tbody>' +
            '<tr> <td> Road Name </td><td>' + rd_name[0] + '</td > </tr>' +
            '<tr> <td> Road ID </td><td>' + rd_id[0] + '</td> </tr>' +
            '<tr> <td> Road Length </td><td>' + road_length[0] + '</td> </tr>' +
            '<tr> <td> Division Name </td><td>' + division_name[0] + '</td> </tr>' +
            '<tr> <td> Road Category </td><td>' + road_catg[0] + '</td> </tr>' +
            '</tbody></table>';
    }

    // @ts-ignore
    datasetLayer.style = applyStyle;

    $("#road-sum-info").html(tableHtml);

}

function handleMouseMove( /* MouseEvent */ e) {
    if (e.features) {
        lastInteractedFeatureIds = e.features.map(
            (f) => f.datasetAttributes["road_id"]
        );
        datasetLayer.style = applyStyle;
    }
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
        var rd_name = e.feature.getProperty('Name');

        var rd_id = e.feature.getProperty("road_id");
        var division_name = e.feature.getProperty("division_name");
        var road_num = e.feature.getProperty("road_id");
        var road_length = e.feature.getProperty("road_length");
        var road_catg = e.feature.getProperty("road_category");
        var tableHtml =
            '<table class="table table-striped bordered"><tbody>' +
            '<tr> <td> Road Name </td><td>' + rd_name + '</td > </tr>' +
            '<tr> <td> Road ID </td><td>' + rd_id + '</td> </tr>' +
            '<tr> <td> Road Length </td><td>' + road_length + '</td> </tr>' +
            '<tr> <td> Division Name </td><td>' + division_name + '</td> </tr>' +
            '<tr> <td> Road Category </td><td>' + road_catg + '</td> </tr>' +
            '</tbody></table>';
        data_layer.setStyle(function (feature) {

            var roadCatg = feature.getProperty('road_category');
            var strokeColor;
            var fillColor;
            var strokeWeight;
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
            if (feature.getProperty('road_id') == lastClickedFeatureIds) {
                strokeWeight = 4.0;
            }
            return {
                strokeColor: strokeColor,
                fillColor: fillColor,
                strokeWeight: strokeWeight,
                strokeOpacity: 1.0,
                fillOpacity: 0.3
            };
        });

        $("#road-sum-info").html(tableHtml);
    }
}//end of handleClickOnDataSet

function applyStyle(/* FeatureStyleFunctionOptions */ params, /* MouseEvent */ e) {
    const datasetFeature = e.feature;
    //@ts-ignore
    if (
        lastInteractedFeatureIds.includes(
            datasetFeature.getProperty("road_id")
        )
    ) {

        return styleClicked;
    }
    return styleDefault;
}
// Map -- End