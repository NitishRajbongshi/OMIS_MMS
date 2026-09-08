$("#btnGetRoadnBridges").on("clickxxxx", function () {
    var loader = $("#loader");
    loader.show();
    $.ajax({
        url: "/asset-management/list_roads_n_bridges_to_unlock",
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data:{"zone_cd" : $("#zone_rnb").val() , "circle_cd" : $("#circle_rnb").val(), 
            "division_cd" :  $("#division_rnb").val(), "sub_division_cd" : $("#subDivision_rnb").val(), 
        "rd_catg_rnb" :  $("#rd_catg_rnb").val()},
        // cache: false,
        success: function (response) {
            console.log(response);
            const table_id = $("#roadDetail");
            loader.hide();
            if (response.status === 200) {

                table_id.find("tbody").empty();
                if (response.result.length === 0) {
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
                            '<form class="frm_unlock_road" id="frm_unlock_road" method="post" action="/asset-management/viewAssetToUnlock">'+
                            '<input type="hidden" name="asset_type_cd" id="asset_type_cd" value="10">'+
                            '<input type="hidden" name="asset_cd" id="asset_cd" value="' + data.rd_system_id + '">'+
                            '<button type="submit"  class="btn btn-primary btn-sm submitEditBtn"' +
                            'style="border-radius:5px"><i class="fa fa-lock"></i> Unlock</button>'
                            '</form>'
                            "</td>" +
                            "</tr>";

                        table_id.find("tbody").append(newRoadData);
                    });
                    const myDataTable = new DataTable('#roadDetail');
                    myDataTable.draw();
                }
            } else {
                alert("failed to fetch the road details!");
            }
        },
    });
   
});