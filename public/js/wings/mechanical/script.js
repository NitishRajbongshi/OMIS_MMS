$(function () {
    $("#mechanical_equipment_details_table, #mechanical_vehicle_details_table")
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
$(document).on("submit", "#wing_mechanical_equipment", function (e) {
    console.log("clicked on here");
    e.preventDefault();
    var loader = $("#loader");
    var content = $(".loaderContainer");
    content.addClass("blur-background");
    loader.show();
    $.ajax({
        type: "GET",
        url: "/asset-management/mis-mechanical-equipment",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#wing_mechanical_equipment").serialize(),
        cache: false,
        success: function (response) {
            const table_id = $("#mechanical_equipment_details_table");
            if (response.status === 200) {
                table_id.find("tbody").empty();
                var equipment_data_table = new DataTable('#mechanical_equipment_details_table');
                if (response.result.length === 0) {
                    table_id
                        .find("tbody")
                        .html(
                            '<tr><td colspan="10" class="text-center">No matching records found</td></tr>'
                        );
                    equipment_data_table.clear().draw();
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
                    equipment_data_table.clear().draw();
                    $.each(response.result, function (index, data) {
                        equipment_data_table.row.add([
                            ++index,
                            data.euipment_cd,
                            data.equipment_name,
                            data.serial_number,
                            data.model_no,
                            data.purchase_year,
                            data.purchase_cost,
                            data.condition_descr,
                            data.is_under_waranty,
                            data.equipment_remarks
                        ]);
                    });

                    loader.hide();
                    content.removeClass("blur-background");
                    equipment_data_table.draw();
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
$(document).on("submit", "#wing_mechanical_vehicle", function (e) {
    console.log("clicked on here");
    e.preventDefault();
    var loader = $("#loader");
    var content = $(".loaderContainer");
    content.addClass("blur-background");
    loader.show();
    $.ajax({
        type: "GET",
        url: "/asset-management/mis-mechanical-vehicle",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: $("#wing_mechanical_vehicle").serialize(),
        cache: false,
        success: function (response) {
            console.log(response);
            const table_id = $("#mechanical_vehicle_details_table");
            if (response.status === 200) {
                table_id.find("tbody").empty();
                var vehicle_data_table = new DataTable('#mechanical_vehicle_details_table');
                if (response.result.length === 0) {
                    table_id
                        .find("tbody")
                        .html(
                            '<tr><td colspan="22" class="text-center">No matching records found</td></tr>'
                        );
                    vehicle_data_table.clear().draw();
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
                    vehicle_data_table.clear().draw();
                    $.each(response.result, function (index, data) {
                        vehicle_data_table.row.add([
                            ++index,
                            data.vehicle_asset_cd,
                            data.vehicle_name,
                            data.vehicle_regn_no,
                            data.chassis_no,
                            data.engine_no,
                            data.veh_type_descr,
                            data.seating_capacity,
                            data.no_of_wheels,
                            data.maker_name,
                            data.model,
                            data.fuel_type_descr,
                            data.date_of_purchase,
                            data.purchase_cost,
                            data.condition_descr,
                            data.laden_weight,
                            data.unladen_weight,
                            data.alloted_to,
                            data.alloted_from,
                            data.remarks
                        ]);
                    });
                    loader.hide();
                    content.removeClass("blur-background");
                    vehicle_data_table.draw();
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
