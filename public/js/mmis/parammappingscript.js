$("#department_id").on("change", function () {

    const department = $(this).val();

    $("#asset_id").empty().append('<option value="">Select Asset</option>');

    $("#parameter_table_body").empty();
    $("#parameter_section").hide();
    $("#parameter_message").hide().empty();

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
                    '<option value="' + value.sub_asset_cd + '">' +
                        value.sub_assets_descr +
                    '</option>'
                );

            });

        },

        error: function (xhr) {
            console.error(xhr.responseText);
        }
    });

});


$("#asset_id").on("change", function () {

    const department = $("#department_id").val();
    const asset = $(this).val();

    $("#parameter_table_body").empty();
    $("#parameter_section").hide();
    $("#parameter_message").hide().empty();

    if (!department || !asset) {
        return;
    }

    loadMappingParameters(department, asset);

});


function loadMappingParameters(department, asset)
{
    $.ajax({
        url: "/maintenance-management/mapping-parameters",
        type: "GET",

        data: {
            department: department,
            asset: asset
        },

        success: function (data) {

            const tableBody = $("#parameter_table_body");

            tableBody.empty();

            if (data.length === 0) {

                tableBody.append(
                    '<tr>' +
                        '<td colspan="2" class="text-center text-muted">' +
                            'No parameters found.' +
                        '</td>' +
                    '</tr>'
                );

                $("#parameter_section").show();

                return;
            }

            $.each(data, function (index, value) {

                const checked =
                    value.mapping_id !== null
                        ? "checked"
                        : "";

                tableBody.append(

                    '<tr>' +

                        '<td class="text-center">' +

                            '<input type="checkbox" ' +
                                'class="form-check-input parameter-checkbox" ' +
                                'value="' + value.parameter_id + '" ' +
                                checked +
                            '>' +

                        '</td>' +

                        '<td>' +
                            value.parameter_name +
                        '</td>' +

                    '</tr>'
                );

            });

            $("#parameter_section").show();
        },

        error: function (xhr) {

            console.error(xhr.responseText);

            $("#parameter_message")
                .removeClass("alert-success")
                .addClass("alert-danger")
                .text("Failed to load parameters.")
                .show();
        }
    });
}


$("#save_mapping").on("click", function () {

    const department = $("#department_id").val();
    const asset = $("#asset_id").val();

    if (!department || !asset) {

        Swal.fire({
            icon: "warning",
            title: "Required",
            text: "Please select department and asset."
        });

        return;
    }

    let parameterIds = [];

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
            parameter_ids: parameterIds
        },

        success: function (response) {

            if (response.status === false) {
                Swal.fire({
                    icon: "warning",
                    title: "Cannot Remove",
                    text: response.message,
                    confirmButtonText: "OK"
                });

                return;
            }

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
                text: "Something went wrong while saving mapping."
            });
        }
    });

});
