$("#purchase_year, #equipment_condition_cd").select2();
// send data for finalization
$("#freezeBtn").on("click", function () {
    const status = confirm("Are you sure?");
    if (status) {
        var selectedAsset = $(".selected-asset:checked")
            .map(function () {
                return $(this).data("road-id");
            })
            .get();
        console.log(selectedAsset);
        if (selectedAsset.length === 0) {
            showDashboardModal(
                "Select atleast one equipment to send for finalization!"
            );
        } else {
            $.ajax({
                type: "GET",
                url: "/asset-management/send-equipment-details-finalization",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    assetList: selectedAsset,
                },
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
