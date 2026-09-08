function getFundingAgencyDetails(scheme_cd,work_order_amount, table = "draft") {
    $("#dataSectionForAgencyDetails").empty();
    $("#dataSectionForAgencyDetails").html(
        '<p class="text-muted small">Loading...</p>',
    );
    $("#showFundingAgencyDetails").show();

    $.ajax({
        type: "GET",
        url: "/project-management/showFundingAgencies",
        data: {
            scheme_cd: scheme_cd,
			work_order_amount: work_order_amount,
            table: table,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#dataSectionForAgencyDetails").empty();
            if (response.status === 200) {
                let data = response.result;
                if (data.length > 0) {
                    data.forEach(function (agency, index) {
                        let html = `
                        <div class="border col-12 mb-2 row pt-2">

                            <div class="col-md-12 mb-2 border-bottom pb-2">
                                <strong class="text-rose-primary">
                                    Funding Agency ${index + 1}
                                </strong>
                            </div>


                            <div class="col-6 col-md-4 mb-2">
                                <strong class="text-rose-primary">
                                    Agency Name:
                                </strong>
                                ${agency.agency_name ?? "N/A"}
                            </div>


                            <div class="col-6 col-md-4 mb-2">
                                <strong class="text-rose-primary">
                                    Funding Amount:
                                </strong>
                                ${agency.funding_amount ?? 0}
                            </div>


                            <div class="col-6 col-md-4 mb-2">
                                <strong class="text-rose-primary">
                                    Funding Percentage:
                                </strong>
                                ${agency.funding_percentage ?? 0}
                            </div>

                        </div>
                        `;
                        $("#dataSectionForAgencyDetails").append(html);
                    });
                } else {
                    $("#dataSectionForAgencyDetails").html(`
                        <div class="alert alert-warning">
                            No funding agencies found.
                        </div>
                    `);
                }
            } else {
                $("#dataSectionForAgencyDetails").html(`
                    <div class="alert alert-warning">
                        ${response.message}
                    </div>
                `);
            }
        },
        error: function () {
            $("#dataSectionForAgencyDetails").html(
                '<p class="text-danger small">Failed to load data.</p>',
            );
        },
    });

    $(".btn-close").on("click", () => {
        $("#showFundingAgencyDetails").hide();
    });
}
