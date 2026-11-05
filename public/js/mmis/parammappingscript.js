
function getLocalDateString(date = new Date()) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
}


function isDateWithinValidity(validFrom, validTo) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (!validFrom || !validTo) {
        return false;
    }

    // Handles both YYYY-MM-DD and YYYY-MM-DD HH:mm:ss
    const from = new Date(validFrom.substring(0, 10) + "T00:00:00");
    const to = new Date(validTo.substring(0, 10) + "T00:00:00");

    return from <= today && to >= today;
}



// Department change
$("#department_id").on("change", function () {
    const department = $(this).val();

    $("#asset_id")
        .empty()
        .append($("<option>", {
            value: "",
            text: "Select Asset"
        }));

    $("#parameter_table_body").empty();
    $("#parameter_section").hide();
    $("#parameter_message").hide().empty();

    $("#valid_from").val("");
    $("#valid_to").val("");

    if (!department) {
        return;
    }

    $.ajax({
        url: "/maintenance-management/getAssetList",
        type: "GET",
        data: {
            department: department
        },

        success: function (data) {
            $.each(data, function (index, value) {
                $("#asset_id").append(
                    $("<option>", {
                        value: value.sub_asset_cd,
                        text: value.sub_assets_descr
                    })
                );
            });
        },

        error: function (xhr) {
            console.error(xhr.responseText);

            $("#parameter_message")
                .removeClass("alert-success")
                .addClass("alert-danger")
                .text("Failed to load assets.")
                .show();
        }
    });
});


// Asset change
$("#asset_id").on("change", function () {
    const department = $("#department_id").val();
    const asset = $(this).val();

    $("#parameter_table_body").empty();
    $("#parameter_section").hide();
    $("#parameter_message").hide().empty();

    $("#valid_from").val("");
    $("#valid_to").val("");

    if (!department || !asset) {
        return;
    }

    loadMappingParameters(department, asset);
});


// Load parameters and validity dates

function loadMappingParameters(department, asset) {

    $.ajax({
        url: "/maintenance-management/mapping-parameters",
        type: "GET",
        data: {
            department: department,
            asset: asset
        },

        success: function (response) {

            const tableBody = $("#parameter_table_body");
            tableBody.empty();

            const validFrom = response.valid_from
                ? response.valid_from.substring(0, 10)
                : "";

            const validTo = response.valid_to
                ? response.valid_to.substring(0, 10)
                : "";

            // Set validity dates
            $("#valid_from").val(validFrom);
            $("#valid_to").val(validTo);

            // Control editability from backend
            $("#valid_from").prop(
                "readonly",
                !response.valid_from_editable
            );

            $("#valid_to").prop(
                "readonly",
                !response.valid_to_editable
            );

            // Check current validity
            const periodActive = isDateWithinValidity(
                validFrom,
                validTo
            );

            const parameters = response.parameters || [];

            if (parameters.length === 0) {

                tableBody.append(
                    $("<tr>").append(
                        $("<td>", {
                            colspan: 2,
                            class: "text-center text-muted",
                            text: "No parameters found."
                        })
                    )
                );

                $("#parameter_section").show();
                return;
            }

            $.each(parameters, function (index, value) {

                const hasMapping =
                    value.mapping_id !== null &&
                    value.mapping_id !== undefined;

                const isWithinValidity = hasMapping &&
                    isDateWithinValidity(
                        value.valid_from,
                        value.valid_to
                    );

                const checkbox = $("<input>", {
                    type: "checkbox",
                    class: "form-check-input parameter-checkbox",
                    value: value.parameter_id
                });

                if (hasMapping) {
                    checkbox.prop("checked", true);
                }

                // Existing active mappings cannot be modified
                if (isWithinValidity) {
                    checkbox.prop("disabled", true);
                }

                const row = $("<tr>")
                    .append(
                        $("<td>", {
                            class: "text-center"
                        }).append(checkbox)
                    )
                    .append(
                        $("<td>").text(value.parameter_name)
                    );

                tableBody.append(row);
            });

            if (periodActive) {

                $("#parameter_message")
                    .removeClass("alert-danger alert-success")
                    .addClass("alert-info")
                    .text(
                        "Existing active parameters cannot be removed. " +
                        "You can select new parameters during this period."
                    )
                    .show();

            } else {

                $("#parameter_message").hide().empty();
            }

            $("#parameter_section").show();
        },

        error: function (xhr) {

            console.error(xhr.responseText);

            $("#parameter_message")
                .removeClass("alert-success alert-info")
                .addClass("alert-danger")
                .text("Failed to load parameters.")
                .show();
        }
    });
}


// Save mapping
$("#save_mapping").on("click", function () {
    const department = $("#department_id").val();
    const asset = $("#asset_id").val();
    const validFrom = $("#valid_from").val();
    const validTo = $("#valid_to").val();

    if (!department || !asset) {
        Swal.fire({
            icon: "warning",
            title: "Required",
            text: "Please select department and asset."
        });

        return;
    }

    if (!validFrom || !validTo || validTo < validFrom) {
        Swal.fire({
            icon: "warning",
            title: "Invalid Dates",
            text: "Please select valid dates. Valid To must be after Valid From."
        });

        return;
    }

    const parameterIds = [];

    $(".parameter-checkbox:checked").each(function () {
        parameterIds.push($(this).val());
    });

    $.ajax({
        url: "/maintenance-management/save-mapping",
        type: "POST",

        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            department: department,
            asset: asset,
            valid_from: $("#valid_from").val(),
            valid_to: $("#valid_to").val(),
            parameter_ids: parameterIds
        },

        success: function (response) {
            if (response.status === false) {
                Swal.fire({
                    icon: "warning",
                    title: "Cannot Save",
                    text: response.message || "The mapping could not be changed.",
                    confirmButtonText: "OK"
                });

                return;
            }

            Swal.fire({
                icon: "success",
                title: "Success",
                text: response.message ||
                    "Parameter mapping saved successfully.",
                confirmButtonText: "OK"
            }).then(function () {
                loadMappingParameters(department, asset);
            });
        },

        error: function (xhr) {
            console.error(xhr.responseText);

            const message = xhr.responseJSON &&
                xhr.responseJSON.message
                ? xhr.responseJSON.message
                : "Something went wrong while saving mapping.";

            Swal.fire({
                icon: "error",
                title: "Error",
                text: message
            });
        }
    });
});
