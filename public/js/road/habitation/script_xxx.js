$(document).ready(function () {
    $('#district, #block, #village, #mla_constituency_cd, #mp_constituency_cd').select2();
    $('#district').on('change', () => {
        const district_cd = $('#district').val();
        $.ajax({
            url: '/asset-management/block/' + district_cd,
            type: 'GET',
            cache: false,
            success: function (response) {
                if (response.status == 'success') {
                    const selectBlock = $('#block');
                    selectBlock.empty();
                    const selectVillage = $('#village');
                    selectVillage.empty();
                    selectBlock.append('<option value="">Choose One</option>');
                    selectVillage.append('<option value="">Choose One</option>');
                    $.each(response.result, function (index, block) {
                        selectBlock.append('<option class="text-uppercase" value="' + block.block_cd +
                            '">' + block.block_name + '</option>');
                    });
                    $('#block').prop('disabled', false);
                } else {
                    alert("failed to fetch the Block list!");
                }
            }
        });
    });

    $('#block').on('input', () => {
        const block_cd = $('#block').val();
        $.ajax({
            url: '/asset-management/village/' + block_cd,
            type: 'GET',
            cache: false,
            success: function (response) {
                if (response.status == 'success') {
                    const select = $('#village');
                    select.empty();
                    select.append('<option value="">Choose One</option>');
                    $.each(response.result, function (index, village) {
                        select.append('<option class="text-uppercase" value="' + village.village_code +
                            '">' + village.village_name + '</option>');
                    });
                    $('#village').prop('disabled', false);
                } else {
                    alert("failed to fetch the village list!");
                }
            }
        });
    });

    $("#village").on("change", function () {
        const selectedVillCode = $(this).val();

        // ajax call for get population
        $.ajax({
            url: "/asset-management/habitation-population/" + selectedVillCode,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            cache: false,
            success: function (response) {
                console.log(response);
                if (response.status == 200) {
                    console.log(response.result.census2011_village_code);
                    $("#total_population").val(
                        response.result.census2011_village_code
                    );
                }

                if (response.status == 204) {
                    showDashboardModal(response.message);
                    $("#total_population").val(null);
                }

                if (response.status == 401) {
                    showDashboardModal(response.message);
                    $("#total_population").val(null);
                }

                if (response.status == 500) {
                    showDashboardModal(response.message);
                    $("#total_population").val(null);
                }
            },
        });
    });

    // send data for finalization
    $("#freezeBtn").on("click", function () {
        const status = confirm('Are you sure?');
        if (status) {
            var selectedAsset = $(".selected-asset:checked")
                .map(function () {
                    return $(this).data("habitation");
                })
                .get();

            if (selectedAsset.length === 0) {
                showDashboardModal("Select at least one record to send for finalization!");
            } else {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/send-habitation-details-finalization",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: { assetList: selectedAsset },
                    cache: false,
                    success: function (response) {
                        console.log(response);
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
});

$(function () {
    $("#habitation_details_table").DataTable({}).buttons().container().appendTo(
        '#habitation_details_table_wrapper .col-md-11:eq(1)');
});