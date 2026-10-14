$("#department_id").on("change", function () {

    const department_id = $(this).val();

    $("#parameter_table_body").empty();

    $("#parameter_section").hide();

    $("#weightage_message").empty().hide();

    $("#save_weightage").prop("disabled", true);

    $("#hist_remark_section").hide();

    $("#hist_remark").val("");

    loadAssets(department_id);

});

function loadAssets(selectedDepartment) {
    var dropdown = $("#asset_id");
    dropdown.empty();
    dropdown.append('<option value="">Select Asset</option>');

    if (!selectedDepartment) {
        return;
    }

    $.ajax({
        url: "/maintenance-management/getAssetList",
        type: "GET",
        data: { department: selectedDepartment },

        success: function (data) {
            $.each(data, function (index, value) {
                dropdown.append(
                    '<option value="' + value.sub_asset_cd + '">' +
                    value.sub_assets_descr +
                    '</option>'
                );
            });
        },

        error: function (xhr, status, error) {
            console.error(error);
        }
    });


    $("#asset_id").on("change", function () {

        const department = $("#department_id").val();
        const asset = $(this).val();

        $("#weightage_message").empty().hide();

        $("#save_weightage").prop("disabled", true);

        $("#hist_remark_section").hide();

        $("#hist_remark").val("");

        loadParameters(department, asset);
    });


    function loadParameters(selectedDepartment, selectedAsset) {

        const section = $("#parameter_section");
        const tableBody = $("#parameter_table_body");

        tableBody.empty();
        section.hide();

        if (!selectedDepartment || !selectedAsset) {
            return;
        }

        $.ajax({
            url: "/maintenance-management/parameters",
            type: "GET",
            data: {
                department: selectedDepartment,
                asset: selectedAsset
            },

            success: function (data) {

                tableBody.empty();

                if (data.length === 0) {
                    tableBody.append(
                        '<tr>' +
                            '<td colspan="2" class="text-center text-muted">' +
                                'No parameters found for this department and asset.' +
                            '</td>' +
                        '</tr>'
                    );

                    section.show();
                    return;
                }

                let existingData = false;

                $.each(data, function (index, value) {

                    if (value.weightage !== null && value.weightage !== "") {
                        existingData = true;
                    }

                    tableBody.append(
                        '<tr>' +

                            '<td>' +
                                value.parameter_name +
                            '</td>' +

                            '<td>' +
                                '<input type="number" ' +
                                    'class="form-control weightage-input" ' +
                                    'name="weightage[' + value.parameter_id + ']" ' +
                                    'value="' + (value.weightage ?? '') + '" ' +
                                    'min="0" ' +
                                    'max="100" ' +
                                    'step="1" ' +
                                    'data-parameter-id="' + value.parameter_id + '" ' +
                                    'data-parameter-set-id="' + value.parameter_set_id + '">' +
                            '</td>' +

                        '</tr>'
                    );
                });

                if (existingData) {
                    $("#hist_remark_section").show();
                } else {
                    $("#hist_remark_section").hide();
                    $("#hist_remark").val("");
                }

                section.show();
                $(".weightage-input").first().trigger("input");
            },

            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    }
}

    $(document).on("input", ".weightage-input", function () {

    let total = 0;

    $(".weightage-input").each(function () {

        let value = parseFloat($(this).val());

        if (!isNaN(value)) {
            total += value;
        }
    });

    total = Math.round(total * 100) / 100;

    const message = $("#weightage_message");
    const saveButton = $("#save_weightage");

    message.show();
    saveButton.prop("disabled", true);

    if (total < 100) {

        const remaining = 100 - total;

        message
            .removeClass("alert-success alert-danger")
            .addClass("alert alert-warning")
            .html(
                "<strong>Total: " + total + "%</strong> / 100% &nbsp; " +
                remaining + "% remaining"
            );

    } else if (total === 100) {

        message
            .removeClass("alert-warning alert-danger")
            .addClass("alert alert-success")
            .html(
                "<strong>Total: 100%</strong> / 100% &nbsp; " +
                "Weightage completed."
            );

        saveButton.prop("disabled", false);

    } else {

        const excess = total - 100;

        message
            .removeClass("alert-warning alert-success")
            .addClass("alert alert-danger")
            .html(
                "<strong>Total: " + total + "%</strong> / 100% &nbsp; " +
                excess + "% over the allowed total."
            );
    }
});

$("#save_weightage").on("click", function () {

    let department = $("#department_id").val();
    let asset = $("#asset_id").val();

    if ($("#hist_remark_section").is(":visible")) {

        let histRemark = $("#hist_remark").val().trim();

        if (histRemark === "") {

            Swal.fire({
                icon: "warning",
                title: "Reason Required",
                text: "Please enter reason before saving.",
                confirmButtonText: "OK"
            });

            $("#hist_remark").focus();
            return;
        }
    }

    let weightages = [];

    $(".weightage-input").each(function () {

        weightages.push({
            parameter_set_id: $(this).data("parameter-set-id"),
            parameter_id: $(this).data("parameter-id"),
            weightage: $(this).val() === "" ? 0 : $(this).val()
        });

    });

    $.ajax({
        url: "/maintenance-management/save",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            department: department,
            asset: asset,
            hist_remark: $("#hist_remark").val(),
            weightages: weightages
        },

        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Success",
                text: response.message,
                confirmButtonText: "OK"
            }).then(() => {
                location.reload();
            });
        },

        error: function (xhr) {

            console.error(xhr.responseText);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Something went wrong while saving the weightage.",
                confirmButtonText: "OK"
            });
        }
    });

});

$(document).on("keydown", ".weightage-input", function (e) {

    if (e.key === "." || e.key === ",") {
        e.preventDefault();
    }

});
