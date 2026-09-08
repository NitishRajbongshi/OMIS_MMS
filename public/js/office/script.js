$(document).ready(function () {
    $(
        "#department_id, #office_type_cd, #zone_cd, #circle_cd, #division_cd, #sub_division_cd, #parent_office_id"
    ).select2();
    // edit office details 
    $('#officeEditModal').on('show.bs.modal ', function (event) {
        var button = $(event.relatedTarget); // get the button property
        let office_id = button.data('office-id');
        let office_name = button.data('office-name');
        let dept_id = button.data('department-id');
        $("#edit_office_id").val(office_id);
        $("#edit_office_name").val(office_name);
        $("#edit_dept").val(dept_id);
    });

    // ajax to get zone list by department
    $("#department_id").on("change", function () {
        const departmentCd = $(this).val();
        $.ajax({
            url: "/asset-management/getZoneList",
            type: "GET",
            data: { department: departmentCd },
            success: function (data) {
                console.log(data);
                var dropdown = $("#zone_cd");
                dropdown.empty();
                dropdown.append('<option value="">Choose One</option>');

                $.each(data, function (index, value) {
                    dropdown.append(
                        '<option value="' +
                        value.zone_cd +
                        '">' +
                        value.zone_name +
                        "</option>"
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    });
    // ajax to get circle list by zone
    $("#zone_cd").on("change", function () {
        const zone_cd = $(this).val();
        $.ajax({
            url: "/asset-management/getCircleList",
            type: "GET",
            data: { zone: zone_cd },
            success: function (data) {
                console.log(data);
                var dropdown = $("#circle_cd");
                dropdown.empty();
                dropdown.append('<option value="">Choose One</option>');

                $.each(data, function (index, value) {
                    dropdown.append(
                        '<option value="' +
                        value.circle_cd +
                        '">' +
                        value.circle_name +
                        "</option>"
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    });
    // ajax to get division list by circle
    $("#circle_cd").on("change", function () {
        const circle_cd = $(this).val();
        console.log(circle_cd)
        $.ajax({
            url: "/asset-management/getDivisionList",
            type: "GET",
            data: { circle: circle_cd },
            success: function (data) {
                console.log(data);
                var dropdown = $("#division_cd");
                dropdown.empty();
                dropdown.append('<option value="">Choose One</option>');

                $.each(data, function (index, value) {
                    dropdown.append(
                        '<option value="' +
                        value.division_cd +
                        '">' +
                        value.division_name +
                        "</option>"
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    });
    // ajax to get sub-division list by division
    $("#division_cd").on("change", function () {
        const division_cd = $(this).val();
        console.log(division_cd);
        $.ajax({
            url: "/asset-management/getSubDivisionList",
            type: "GET",
            data: { division: division_cd },
            success: function (data) {
                console.log(data);
                var dropdown = $("#sub_division_cd");
                dropdown.empty();
                dropdown.append('<option value="">Choose One</option>');

                $.each(data, function (index, value) {
                    dropdown.append(
                        '<option value="' +
                        value.sub_div_cd +
                        '">' +
                        value.sub_div_name +
                        "</option>"
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    });
    $("#office_type_cd").on("change", function () {
        console.log("calling");
        var selectedValue = $(this).val();
        console.log(selectedValue);
        // Hide all input fields and dropdowns
        $("#zone_cd_group").hide();
        $("#circle_cd_group").hide();
        $("#division_cd_group").hide();
        $("#sub_division_cd_group").hide();

        // Show the relevant input fields based on the selected office_type_cd
        if (selectedValue === "ZO") {
            $("#zone_cd_group").show();
        } else if (selectedValue === "CO") {
            $("#zone_cd_group").show();
            $("#circle_cd_group").show();
        } else if (selectedValue === "DO") {
            $("#zone_cd_group").show();
            $("#circle_cd_group").show();
            $("#division_cd_group").show();
        } else if (selectedValue === "SDO") {
            $("#zone_cd_group").show();
            $("#circle_cd_group").show();
            $("#division_cd_group").show();
            $("#sub_division_cd_group").show();
        }
    });

    $(function () {
        $("#office_table")
            .DataTable({
                buttons: ["csv", "excel"],
            })
            .buttons()
            .container()
            .appendTo(".mis-btn-office");
    });

    $("#pOfficeNo").on("click", function () {
        $(".hiddenDiv").show();
    });
    $("#pOfficeYes").on("click", function () {
        $(".hiddenDiv").hide();
    });
    // Handle dropdown change event and make an AJAX request
    $("#office_type_cd").on("change", function () {
        var selectedValue = $(this).val();
        $.ajax({
            type: "GET",
            url: "/asset-management/get-parent-offices/" + selectedValue,
            success: function (data) {
                console.log(data);

                // Clear the current options in the dropdown
                $("#parent_office_id").empty();

                // Iterate through the response array and create options
                data.forEach(function (item) {
                    // Assuming 'id' is the value and 'office_name' is the label
                    $("#parent_office_id").append(
                        $("<option>", {
                            value: item.id,
                            text:
                                item.office_name +
                                (item.branch ? " - " + item.branch : ""),
                        })
                    );
                });
            },
        });
    });

    const radio = $('input[name="parent_office"]');
    const inputField = $("#parent_office_id");
    radio.on("change", function () {
        if ($(this).val() === "N") {
            inputField.prop("required", true);

            // var selectedValue = $('#office_type_cd').val();
            // console.log(selectedValue);
            // $.ajax({
            //     type: 'GET',
            //     url: '/get-parent-offices/' + selectedValue,
            //     success: function(data) {
            //         console.log(data);
            //         // $('#officeList').html(data);
            //     }
            // });
        } else {
            inputField.prop("required", false);
            inputField.val("");
        }
    });

    $("form.update-office-form").on("submit", function (e) {
        e.preventDefault();

        var form = $(this);
        var formData = form.serialize();

        $.ajax({
            type: "POST",
            url: form.attr("action"),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            cache: false,
            success: function (response) {
                console.log(response);
                if (response.message == "success") {
                    Swal.fire({
                        icon: "success",
                        title: "success",
                        text: "Data Updated Successfully",
                        showConfirmButton: true,
                        timer: 3000,
                    });
                    //window.location.replace(location)
                    location.reload();
                } else if (response.message == "validationFails") {
                    console.log("validation fails");
                } else if (response.message == "duplicate") {
                    console.log("duplicate");
                    alert("This Office Name already exist");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something went wrong",
                        showConfirmButton: true,
                        timer: 3000,
                    });
                }
            },
        });
    });
});

function editDraftData() {
    console.log('clicked');
    var formData = $("#editOfficeForm").serialize();
    $.ajax({
        type: "POST",
        url: "/asset-management/update-office",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: formData,
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: 'success',
                    text: response.message,
                    showConfirmButton: true,
                    timer: 3000
                }).then(() => {
                    location.reload(true);
                });
            }
            if (response.status === "failed") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                    showConfirmButton: true,
                    timer: 3000
                }).then(() => {
                    location.reload(true);
                });
            }
            if (response.status === 500 || response.status === 409) {
                console.log(response.message);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                    showConfirmButton: true,
                    timer: 3000
                }).then(() => {
                    location.reload(true);
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
                timer: 3000
            }).then(() => {
                location.reload(true);
            });
        },
    });
}
