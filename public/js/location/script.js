$(document).ready(function () {
    $("#division_cd, #sub_division_cd, #building_class_cd").select2();

    // Reusable function to load sub-divisions
    function loadSubDivisions(division_cd, selectedValue = null) {
        if (!division_cd) return;

        $.ajax({
            url: "/asset-management/getSubDivisionList",
            type: "GET",
            data: { division: division_cd },
            success: function (data) {
                var dropdown = $("#sub_division_cd");
                dropdown.empty();
                dropdown.append('<option value="">Choose One</option>');

                $.each(data, function (index, value) {
                    dropdown.append(
                        '<option value="' +
                            value.sub_div_cd +
                            '">' +
                            value.sub_div_name +
                            "</option>",
                    );
                });

                // If a pre-selected value exists (edit mode), set it
                if (selectedValue) {
                    dropdown.val(selectedValue).trigger("change"); // trigger("change") updates select2
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    }

    // On manual division change
    $("#division_cd").on("change", function () {
        loadSubDivisions($(this).val());
    });

    // On page load — handles edit mode pre-selection
    const initialDivision = $("#division_cd").val();
    const initialSubDivision = $("#sub_division_cd").data("selected");

    if (initialDivision) {
        loadSubDivisions(initialDivision, initialSubDivision);
    }

    $(function () {
        $("#location_table").DataTable();
    });
});
