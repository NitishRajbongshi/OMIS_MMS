// Finalization Script: script responsible for send selected projects for finalization
// Coded by: Nitish
$("#freezeBtnForNewProjectRoad, #freezeBtnForNewProjectHousing, #freezeBtnForNewProjectMechanical").on("click", function () {

    Swal.fire({
        title: "Are you sure?",
        text: "Click OK to proceed!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "OK",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33"
    }).then((result) => {
        if (result.isConfirmed) {
            sendForFinalization();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
                title: "Cancelled",
                text: "Finalization has been cancelled.",
                icon: "info",
                confirmButtonText: "OK"
            });
        }
    });

});





function sendForFinalization() {
    var selectedAsset = $(".selected-asset:checked")
        .map(function () {
            return $(this).data("project-cd");
        })
        .get();
    if (selectedAsset.length === 0) {
        showDashboardModal(
            "Select at least one record to send for finalization!",
        );
    } else {
        $.ajax({
            type: "GET",
            url: "/project-management/send-pms-details-finalization",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
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
