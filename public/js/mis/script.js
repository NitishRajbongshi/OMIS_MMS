$("#dist_list").select2({
    theme: "classic",
});
$("#wing").select2({
    theme: "classic",
});
$("#zone_list").select2({
    theme: "classic",
});
$("#circle_list").select2({
    theme: "classic",
});
$("#division_list").select2({
    theme: "classic",
});
$("#subdivision_list").select2({
    theme: "classic",
});

$("#dist_list").on("change", function () {
    const district_cd = $(this).val();
    console.log(district_cd);
    if (district_cd != undefined) {
        $.ajax({
            url: "/asset-management/get-road-by-district/" + district_cd,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            cache: false,
            success: function (response) {
                console.log(response);
                const table_id = $("#road_details_table");
                if (response.status === 200) {
                    table_id.find("tbody").empty();
                    if (response.result.length === 0) {
                        // If no records found, display a message
                        table_id.find("tbody").html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
                    } else {
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
                                "<td>" +
                                data.rd_number +
                                "</td>" +
                                "<td>" +
                                data.rd_name +
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
                                '<td class="text-center">' +
                                '<a href="/road/show-cd-works/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-bridge-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-pci-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-surface-type-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-habitation-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/chainage/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                "</tr>";

                            table_id.find("tbody").append(newRoadData);
                        });
                    }
                } else {
                    alert("failed to fetch the road details!");
                }
            },
        });
    } else {
        alert("Failed to filter data!");
    }
});


$("#zone_list").on("change", function () {
    const zone_cd = $(this).val();
    console.log(zone_cd);
    if (zone_cd != undefined) {
        $.ajax({
            url: "/asset-management/get-road-by-zone/" + zone_cd,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            cache: false,
            success: function (response) {
                console.log(response);
                const table_id = $("#roadDetail");
                if (response.status === 200) {
                    table_id.find("tbody").empty();
                    if (response.result.length === 0) {
                        table_id.find("tbody").html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
                    } else {
                        $.each(response.result, function (index, data) {
                            $('.updateCol').hide();
                            $('.deleteCol').hide();
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
                                "<td>" +
                                data.rd_number +
                                "</td>" +
                                "<td>" +
                                data.rd_name +
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
                                '<td class="text-center">' +
                                '<a href="/road/show-cd-works/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-bridge-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-pci-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-surface-type-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-habitation-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/chainage/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                "</tr>";

                            table_id.find("tbody").append(newRoadData);
                        });
                    }
                } else {
                    alert("failed to fetch the road details!");
                }
            },
        });
    } else {
        alert("Failed to filter data!");
    }
});

$("#circle_list").on("change", function () {
    const circle_cd = $(this).val();
    console.log(circle_cd);
    if (circle_cd != undefined) {
        $.ajax({
            url: "/asset-management/get-road-by-circle/" + circle_cd,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            cache: false,
            success: function (response) {
                console.log(response);
                const table_id = $("#roadDetail");
                if (response.status === 200) {
                    table_id.find("tbody").empty();
                    if (response.result.length === 0) {
                        table_id.find("tbody").html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
                    } else {
                        $.each(response.result, function (index, data) {
                            $('.updateCol').hide();
                            $('.deleteCol').hide();
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
                                "<td>" +
                                data.rd_number +
                                "</td>" +
                                "<td>" +
                                data.rd_name +
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
                                '<td class="text-center">' +
                                '<a href="/road/show-cd-works/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-bridge-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-pci-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-surface-type-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-habitation-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/chainage/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                "</tr>";

                            table_id.find("tbody").append(newRoadData);
                        });
                    }
                } else {
                    alert("failed to fetch the road details!");
                }
            },
        });
    } else {
        alert("Failed to filter data!");
    }
});

$("#division_list").on("change", function () {
    const division_cd = $(this).val();
    console.log(division_cd);
    if (division_cd != undefined) {
        $.ajax({
            url: "/asset-management/get-road-by-division/" + division_cd,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            cache: false,
            success: function (response) {
                console.log(response);
                const table_id = $("#roadDetail");
                if (response.status === 200) {
                    table_id.find("tbody").empty();
                    if (response.result.length === 0) {
                        table_id.find("tbody").html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
                    } else {
                        $.each(response.result, function (index, data) {
                            $('.updateCol').hide();
                            $('.deleteCol').hide();
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
                                "<td>" +
                                data.rd_number +
                                "</td>" +
                                "<td>" +
                                data.rd_name +
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
                                '<td class="text-center">' +
                                '<a href="/road/show-cd-works/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-bridge-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-pci-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-surface-type-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-habitation-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/chainage/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                "</tr>";

                            table_id.find("tbody").append(newRoadData);
                        });
                    }
                } else {
                    alert("failed to fetch the road details!");
                }
            },
        });
    } else {
        alert("Failed to filter data!");
    }
});

$("#subdivision_list").on("change", function () {
    const subDivision_cd = $(this).val();
    console.log(subDivision_cd);
    if (subDivision_cd != undefined) {
        $.ajax({
            url: "/asset-management/get-road-by-subdivision/" + subDivision_cd,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            cache: false,
            success: function (response) {
                console.log(response);
                const table_id = $("#roadDetail");
                if (response.status === 200) {
                    table_id.find("tbody").empty();
                    if (response.result.length === 0) {
                        table_id.find("tbody").html(
                            '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                        );
                    } else {
                        $.each(response.result, function (index, data) {
                            $('.updateCol').hide();
                            $('.deleteCol').hide();
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
                                "<td>" +
                                data.rd_number +
                                "</td>" +
                                "<td>" +
                                data.rd_name +
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
                                '<td class="text-center">' +
                                '<a href="/road/show-cd-works/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-bridge-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-pci-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-surface-type-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show-habitation-data/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/chainage/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                '<td class="text-center">' +
                                '<a href="/road/show/' +
                                data.rd_system_id +
                                '"><i class="fa fa-eye"></i></a>' +
                                "</td>" +
                                "</tr>";

                            table_id.find("tbody").append(newRoadData);
                        });
                    }
                } else {
                    alert("failed to fetch the road details!");
                }
            },
        });
    } else {
        alert("Failed to filter data!");
    }
});