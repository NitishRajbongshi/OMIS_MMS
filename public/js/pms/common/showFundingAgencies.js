//donr by dipshikha

function loadFundingAgencies() {

    $("#fundingAgencyContainer").empty();

    let scheme_cd;

    if ($("#scheme_div").hasClass("d-none")) {
        // Edit OFF → use original scheme
        scheme_cd = $("#scheme_text").data("scheme-id");
    } else {
        // Edit ON → use selected scheme
        scheme_cd = $("#scheme_cd").val();
    }

    // Check if scheme not selected
    if (
        !scheme_cd ||
        scheme_cd === "" ||
        scheme_cd === "Select Scheme"
    ) {
        return;
    }

    $("#fundingAgencyContainer").html(
        '<div>Loading Details...</div>'
    );


    const workOrderElement = $("#work_order_amount");

    let workOrderAmount;

    if ($("#work_order_amount_div").is(":visible")) {
        workOrderAmount = parseFloat($("input[name='work_order_amount_nv']").val()) || 0;
    } else {
        workOrderAmount = parseFloat(workOrderElement.val() || workOrderElement.text()) || 0;
    }

    $.ajax({
        method: "GET",
        url: "/project-management/showFundingAgencies",

        data: {
            scheme_cd: scheme_cd,
        },

        success: function (response) {

            $("#fundingAgencyContainer").empty();

            if (response.status === 200) {

                if (response.result.length > 0) {

                    response.result.forEach(function (agency, index) {

                        let percentage =
                            parseFloat(agency.funding_percentage) || 0;

                        let fundingAmount =
                            (workOrderAmount * percentage) / 100;

                        let html = `
                            <div class="card my-1 border">

                                <div class="card-body">

                                    <p class="fw-bold border-bottom pb-2">
                                        Funding Agency ${index + 1}
                                    </p>

                                    <div class="row">

                                        <div class="col-6 col-md-3 mb-1">
                                            <label class="fw-bold">
                                                Agency Name
                                            </label>

                                            <input type="text"
                                                class="form-control form-control-sm"
                                                value="${agency.agency_name}"
                                                readonly>
                                        </div>

                                        <div class="col-6 col-md-3 mb-1">
                                            <label class="fw-bold">
                                                Funding Percentage
                                            </label>

                                            <input type="text"
                                                class="form-control form-control-sm"
                                                value="${percentage}%"
                                                readonly>
                                        </div>

                                        <div class="col-6 col-md-3 mb-1">
                                            <label class="fw-bold">
                                                Funding Amount
                                            </label>

                                            <input type="text"
                                                class="form-control form-control-sm"
                                                value="${fundingAmount.toFixed(2)}"
                                                readonly>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        `;

                        $("#fundingAgencyContainer").append(html);
                    });

                } else {

                    $("#fundingAgencyContainer").html(`
                        <div class="alert alert-warning">
                            No funding agencies found.
                        </div>
                    `);
                }

            } else {

                $("#fundingAgencyContainer").html(`
                    <div class="alert alert-warning">
                        ${response.message}
                    </div>
                `);
            }
        }
    });
}

$("#scheme_cd").on("change", function () {
    loadFundingAgencies();
});

$("#work_order_amount").on("input", function () {
    loadFundingAgencies();
});

$(".toggleEdit").on("change", function () {

    let target = $(this).data("target");

    $(target).toggleClass("d-none", !this.checked);

    if (target === "#scheme_div" && !this.checked) {
        loadFundingAgencies();
    }
    if (target === "#work_order_amount_div" && !this.checked) {
        loadFundingAgencies();
    }
});

$("input[name='work_order_amount_nv']").on("input", function () {
    loadFundingAgencies();
});
