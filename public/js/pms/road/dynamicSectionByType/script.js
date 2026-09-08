// script to display different div section according to the change of Project types
//To display the div

$("#projectTypeSelect").on("change", function () {
    toggleSectionsBasedOnProjectType();
});

function toggleSectionsBasedOnProjectType() {
    const selected = $("#projectTypeSelect").val();

    // Hide everything first to reset
    $("#upgradationSection, #maintenanceSection, #addRefAssetNewBtn, #newAssetSubAssetCountSection, #kmlCard").hide();

    // Show only what is needed
     if (selected === "NEW") {
        $("#newAssetSubAssetCountSection, #kmlCard").show();
    } else if (selected === "UPG") {
        $("#upgradationSection, #addRefAssetNewBtn, #kmlCard").show();
    } else if (selected === "MTN") {
        $("#maintenanceSection").show();
    } else {
        $(
            "#upgradationSection, #maintenanceSection, #addRefAssetNewBtn, #newAssetSubAssetCountSection",
        ).hide();
    }
}

// button to close the sub asset section for upgradation
$("#closeNewAssetsForm").on("click", function () {
    $("#newAssetsForm").hide();
    $("#newAssetsForm").find("input").val("");
});

$("#addRefAssetNewBtn").on("click", function () {
    $("#newAssetsForm").show();
});

$("#rdoSubAsset").on("click", function () {
    $("#addRefAssetNewBtn").hide();
    $("#newAssetsForm").hide();
    $("#priorityCard").hide();
    $("#kmlCard").hide();
    $("#newRoadDetailsForm").hide();
    $("#startChainageLabel").text("Start Chainage (KM)");
    $("#endChainageLabel").text("End Chainage (KM)");
    $("#refAssetTable th:nth-child(2), #refAssetTable td:nth-child(2)").hide();
    $("#refAssetTable th:nth-child(3), #refAssetTable td:nth-child(3)").hide();
});

$("#rdoRoadWithSubAsset").on("click", function () {
    $("#addRefAssetNewBtn").show();
    $("#newAssetsForm").hide();
    $("#newRoadDetailsForm").show();
    $("#kmlCard").show();
    $("#startChainageLabel").text("Upgradation From Chainage (KM)");
    $("#endChainageLabel").text("Upgradation To Chainage (KM)");
    $("#refAssetTable th:nth-child(2), #refAssetTable td:nth-child(2)").show();
    $("#refAssetTable th:nth-child(3), #refAssetTable td:nth-child(3)").show();
});
