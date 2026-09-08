function closeDiv() {
    var div = document.getElementById("mapClosableDiv");
    div.style.display = "none";

    var div1 = document.getElementById("map");
    div1.style.display = "none";
}

// (g => {
//     var h, a, k, p = "The Google Maps JavaScript API",
//         c = "google",
//         l = "importLibrary",
//         q = "__ib__",
//         m = document,
//         b = window;
//     b = b[c] || (b[c] = {});
//     var d = b.maps || (b.maps = {}),
//         r = new Set,
//         e = new URLSearchParams,
//         u = () => h || (h = new Promise(async (f, n) => {
//             await (a = m.createElement("script"));
//             e.set("libraries", [...r] + "");
//             for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
//             e.set("callback", c + ".maps." + q);
//             a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
//             d[q] = f;
//             a.onerror = () => h = n(Error(p + " could not load."));
//             a.nonce = m.querySelector("script[nonce]")?.nonce || "";
//             m.head.append(a)
//         }));
//     d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
//         d[l](f, ...n))
// })
// ({
//     key: "AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM",
//     v: "beta"
// });

// $(document).ready(function() {
$(
    "#building_location_cd, #building_type_cd, #bld_catg, #construction_year, #maintainedBy," +
        "#occupant_dept_cd, #buildingAccess, #owning_dept, #fencing_type"
).select2();

// send data for finalization
$("#freezeBtn").on("click", function () {
    const status = confirm("Are you sure?");
    if (status) {
        var selectedAsset = $(".selected-asset:checked")
            .map(function () {
                return $(this).data("housing-id");
            })
            .get();

        if (selectedAsset.length === 0) {
            showDashboardModal(
                "Select atleast one housing data to send for finalization"
            );
        } else {
            $.ajax({
                type: "GET",
                url: "/asset-management/send-building-details-finalization",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: { assetList: selectedAsset },
                cache: false,
                success: function (response) {
                    if (response.status === 200) {
                        showSuccessModal(response.message);
                    }
                    if (response.status === 503) {
                        showDashboardModal(response.message);
                    }

                    if (response.status === 401) {
                        showDashboardModal(response.message);
                    }

                    if (response.status === 500) {
                        showDashboardModal(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });
        }
    }
});
// Show and hide housing data according to the location input
$('input[name="building_class_cd"]').on("change", function () {
    $("#housingContainer").show();
});

// fencing type
$('input[name="security_fencing"]').on("change", function () {
    var securityFencing = $(this).val();
    $("#div_fencing_type").hide();
    $("#fencing_type").hide();
    $("#fencing_type").prop("required", false);
    if (securityFencing === "Y") {
        $("#div_fencing_type").show();
        $("#fencing_type").show();
        $("#fencing_type").prop("required", true);
    }
});

// fencing type
$('input[name="repaired"]').on("change", function () {
    var securityFencing = $(this).val();
    $("#repairedCost").hide();
    $("#repairedScheme").hide();
    $("#repairedYear").hide();
    if (securityFencing === "Y") {
        $("#repairedCost").show();
        $("#repairedScheme").show();
        $("#repairedYear").show();
    }
});

$("#rdo_yes").on("click", function () {
    $("#maintained_by").val("Y");
});

$("#rdo_no").on("click", function () {
    $("#maintained_by").val("N");
});

// Manipulate dynamic data
$('input[name="building_class_cd"]').on("change", function () {
    var buildingClassCd = $(this).val();
    $("#quarter_no").val("");
    $("#building_name").val("");
    $("#occupant_name").val("");
    $("#occupant_dept_cd").val("");

    // ajax to get building type by building class
    $.ajax({
        url: "/asset-management/buildingType",
        type: "GET",
        data: { building_class_cd: buildingClassCd },
        success: function (data) {
            var dropdown = $("#building_type_cd");
            dropdown.empty();
            dropdown.append('<option value="">Choose one</option>');

            $.each(data, function (index, value) {
                dropdown.append(
                    '<option value="' +
                        value.building_type_cd +
                        '">' +
                        value.building_type_descr +
                        "</option>"
                );
            });
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
    });

    // ajax to get building location by building class
    $.ajax({
        url: "/asset-management/buildingLocation",
        type: "GET",
        data: { building_class_cd: buildingClassCd },
        success: function (data) {
            var dropdown = $("#building_location_cd");
            if (data != "null") {
                dropdown.empty();
                dropdown.append('<option value="">Choose one</option>');

                $.each(data, function (index, value) {
                    dropdown.append(
                        '<option value="' +
                            value.location_cd +
                            '">' +
                            value.location_name +
                            "</option>"
                    );
                });
            } else {
                dropdown.empty();
                dropdown.append('<option value="">Choose one</option>');
                alert("No location found!");
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
    });

    if ($(this).val() === "0") {
        $("#quarterNoInput, #occupantNameInput, #occupantDeptInput").show();
        $("#buildingNameInput").hide();
    }
    if (($(this).val() === "1") || ($(this).val() === "2")) {
        $("#quarterNoInput, #occupantNameInput, #occupantDeptInput").hide();
        $("#buildingNameInput").show();
    }
});

$("#housingForm").on("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission
    console.log("Building Form submitted!");

    // Perform the AJAX or other actions if needed
    this.submit(); // Submit the form normally
});

// Ajax request to submit the housing form data
function submitHousingForm() {
    var formData = $("#housingForm").serialize();
    $.ajax({
        type: "POST",
        url: "/add-housing",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: formData,
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status === 200) {
                $(
                    '#housingForm :input:not(:radio[name="building_class_cd"], :radio[name="has_water_supply"], :radio[name="has_electricity"], :radio[name="has_sanitary"], select[name="building_location_cd"])'
                ).val("");
                $(':radio[name="has_water_supply"]').prop("checked", false);
                $(':radio[name="has_electricity"]').prop("checked", false);
                $(':radio[name="has_sanitary"]').prop("checked", false);
                // $(':radio[name="has_sanitary"][value="N"]').prop('checked', true);
                $(
                    "#construction_year, #building_type_cd, #bld_catg, #maintainedBy, #occupant_dept_cd"
                ).trigger("change");
                $(".spanHide").empty(); // reset the error messages

                // manipulate the data table
                const table_id = $("#building_details_table");

                table_id.find("tbody").empty();
                // if (response.result.length === 0) {
                //     // If no records found, display a message
                //     table_id
                //         .find("tbody")
                //         .html(
                //             '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                //         );
                // } else {
                // $.each(response.result, function (index, data) {
                // var newRoadData =
                //     "<tr style='text-align: center;'>" +
                //     "<td>" +
                //     ++index +
                //     "</td>" +
                //     "<td>" +
                //     data.building_system_cd +
                //     "</td>" +
                //     "<td>" +
                //     (data.qtr_no === null ? 'NA' : data.qtr_no) +
                //     "</td>" +
                //     "<td>" +
                //     (data.bld_qtr_name === null ? 'NA' : data.bld_qtr_name) +
                //     "</td>" +
                //     "<td>" +
                //     data.building_type_descr +
                //     "</td>" +
                //     "<td>" +
                //     (data.is_maintained_by_npwd == 'Y' ? 'Yes' : 'No') +
                //     "</td>" +
                //     "<td>" +
                //     data.building_catg_descr +
                //     "</td>" +
                //     "<td>" +
                //     data.access_type_descr +
                //     "</td>" +
                //     "<td>" +
                //     (data.fenching_type_descr === null ? 'NA' : data.fenching_type_descr) +
                //     "</td>" +
                //     "<td>" +
                //     data.plinth_area +
                //     "</td>" +
                //     "<td>" +
                //     data.plot_area +
                //     "</td>" +
                //     "<td>" +
                //     data.construction_year +
                //     "</td>" +
                //     "<td>" +
                //     data.construction_cost +
                //     "</td>" +
                //     "<td>" +
                //     data.building_class_descr +
                //     "</td>" +
                //     "<td>" +
                //     (data.has_water_supply == 'Y' ? 'Yes' : 'No') +
                //     "</td>" +
                //     "<td>" +
                //     (data.has_electricity == 'Y' ? 'Yes' : 'No') +
                //     "</td>" +
                //     "<td>" +
                //     (data.has_sanitary == 'Y' ? 'Yes' : 'No') +
                //     "</td>" +
                //     "<td>" +
                //     (data.occupant_name === null ? 'NA' : data.occupant_name) +
                //     "</td>" +
                //     "<td>" +
                //     (data.department_name === null ? 'NA' : data.department_name) +
                //     "</td>" +
                //     "<td>" +
                //     (data.department_name === null ? 'NA' : data.department_name) +
                //     "</td>" +
                //     "<td>" +
                //     data.owning_dept_name +
                //     "</td>" +
                //     "<td>" +
                //     data.location_name +
                //     "</td>" +
                //     "<td>" +
                //     (data.remark === null ? '' : data.remark) +
                //     "</td>" +
                //     "<td>" +
                //     (data.reason_of_rejection === null ? '' : data.reason_of_rejection) +
                //     "</td>" +
                //     "<td class='text-center'>" +
                //     '<input type="checkbox" class="selected-asset" data-housing-id="' + data.building_system_cd + '" />' +
                //     "</td>" +
                //     '<th class="text-center">' +
                //     `<button type="submit" class="border-0 bg-transparent" onclick="return confirm('Some error occured, Please refresh the page!!')">` +
                //     '<i class="fa fa-trash text-xs text-danger"></i>' +
                //     '</button>' +
                //     '</th>' +
                //     "</tr>";

                // table_id.find("tbody").append(newRoadData);

                // });
                // }
                alert(response.message);
                location.reload(true);
            }
            if (response.status === 400) {
                console.log(response.error);
                var errors = response.error;

                $(".spanHide").empty();
                $.each(errors, function (field, messages) {
                    var errorHtml = "";
                    $.each(messages, function (index, message) {
                        errorHtml += message + "<br>";
                    });
                    $("#" + field + "_error").html(errorHtml);
                });
            }
            if (response.status === 500 || response.status === 409) {
                console.log(response.message);
            }
        },
        error: function (error) {
            // Handle errors
            console.log(error);
        },
    });
}

function editDraftData() {
    var formData = $("#editDraftBuildingForm").serialize();
    $.ajax({
        type: "POST",
        url: "/asset-management/editDraftBuildingData",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: formData,
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                alert(response.message);
                location.reload(true);
            }
            if (response.status === "failed") {
                alert(response.message);
            }
            if (response.status === 500 || response.status === 409) {
                console.log(response.message);
            }
        },
        error: function (error) {
            // Handle errors
            console.log(error);
        },
    });
}

$(".classSetGeoLocation").on("click", function () {
    $("#mapModal")
        .on("shown.bs.modal", function (e) {
            initMapForSetGeoLocation();
        })
        .modal("show");
});

let lat = 26.094757374299146;
let lng = 94.58979407214116;
async function initMapForSetGeoLocation() {
    const startPosition = {
        lat: 26.094757374299146,
        lng: 94.58979407214116,
    };
    const { Map } = await google.maps.importLibrary("maps");

    map = new Map(document.getElementById("map"), {
        center: startPosition,
        zoom: 9,
    });

    let marker = new google.maps.Marker({
        position: startPosition,
        map: map,
        draggable: true, // Make the marker draggable
    });

    // Update marker position on map drag
    google.maps.event.addListener(map, "dragend", function () {
        const center = map.getCenter();
        marker.setPosition(center);
        getGeoPosition(center);
    });

    // Update marker position on map click
    google.maps.event.addListener(map, "click", function (event) {
        marker.setPosition(event.latLng);
        getGeoPosition(event.latLng);
    });

    function getGeoPosition(latLng) {
        lat = latLng.lat();
        lng = latLng.lng();
        console.log(
            "Marker position saved: Latitude: " + lat + ", Longitude: " + lng
        );
        $("#asset_geo_location").val("[" + lat + "," + lng + "]");
        $("#asset_geo_location_lat").val(lat);
        $("#asset_geo_location_lng").val(lng);
    }
} // end of init map function

$("#btnSaveGeoLocation").on("click", function () {
    // $(document).on("click", "#btnSaveGeoLocation", function(e) {
    $("#asset_geo_location").val("[" + lat + "," + lng + "]");
    $("#asset_geo_location_lat").val(lat);
    $("#asset_geo_location_lng").val(lng);
    $("#mapModal").modal("hide");
});
// });
