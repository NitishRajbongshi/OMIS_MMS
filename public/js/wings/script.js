$("#wing").on("change", function () {
    $wingValue = $(this).val();
    if ($wingValue === "al") {
        $("#road_container").show();
        $("#nh_container").show();
        $("#building_container").show();
        $("#vehicle_container").show();
        $("#equipment_container").show();
    }
    if ($wingValue === "rb") {
        $("#road_container").show();
        $("#nh_container").hide();
        $("#building_container").hide();
        $("#vehicle_container").hide();
        $("#equipment_container").hide();
    }
    if ($wingValue === "nh") {
        $("#road_container").hide();
        $("#nh_container").show();
        $("#building_container").hide();
        $("#vehicle_container").hide();
        $("#equipment_container").hide();
    }
    if ($wingValue === "bd") {
        $("#road_container").hide();
        $("#nh_container").hide();
        $("#building_container").show();
        $("#vehicle_container").hide();
        $("#equipment_container").hide();
    }
    if ($wingValue === "vh") {
        $("#road_container").hide();
        $("#nh_container").hide();
        $("#building_container").hide();
        $("#vehicle_container").show();
        $("#equipment_container").hide();
    }
    if ($wingValue === "eq") {
        $("#road_container").hide();
        $("#nh_container").hide();
        $("#building_container").hide();
        $("#vehicle_container").hide();
        $("#equipment_container").show();
    }
});

$(function () {
    $("#road_details_table")
        .DataTable({
            buttons: [
                // "copy",
                "csv",
                "excel",
                // ,"pdf"
            ],
        })
        .buttons()
        .container()
        .appendTo(".mis-btn-road");
});

$(function () {
    $("#nh_details_table")
        .DataTable({
            buttons: [
                // "copy",
                "csv",
                "excel",
                // ,"pdf"
            ],
        })
        .buttons()
        .container()
        .appendTo(".mis-btn-nh");
});

$(function () {
    $("#building_details_table")
        .DataTable({
            buttons: [
                // "copy",
                "csv",
                "excel",
                // ,"pdf"
            ],
        })
        .buttons()
        .container()
        .appendTo(".mis-btn-building");
});

$(function () {
    $("#equipment_details_table")
        .DataTable({
            buttons: [
                // "copy",
                "csv",
                "excel",
                // ,"pdf"
            ],
        })
        .buttons()
        .container()
        .appendTo(".mis-btn-equipment");
});

$(function () {
    $("#vehicle_details_table")
        .DataTable({
            buttons: [
                // "copy",
                "csv",
                "excel",
                // ,"pdf"
            ],
        })
        .buttons()
        .container()
        .appendTo(".mis-btn-vehicle");
});
