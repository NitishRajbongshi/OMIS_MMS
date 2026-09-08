function showDashboardModal(message) {
    var dashboardModal = document.getElementById("dashboardModal");
    var span = document.getElementsByClassName("closeWingWall")[0];
    $("#dashboardModalSubContent").html(`<p>${message}</p>`);
    dashboardModal.style.display = "block";
    span.onclick = function () {
        dashboardModal.style.display = "none";
        // location.reload();
    };

    window.onclick = function (event) {
        if (event.target == dashboardModal) {
            dashboardModal.style.display = "none";
            // location.reload();
        }
    };
}

function showSuccessModal(message) {
    var successModal = document.getElementById("successModal");
    var span = document.getElementById("closeSuccessBtn");
    $("#successModalSubContent").html(`<p>${message}</p>`);
    successModal.style.display = "block";
    span.onclick = function () {
        successModal.style.display = "none";
        location.reload();
    };

    window.onclick = function (event) {
        if (event.target == successModal) {
            successModal.style.display = "none";
            location.reload();
        }
    };
}