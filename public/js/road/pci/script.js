$(document).ready(function () {
    // send data for finalization
    $("#freezeBtn").on("click", function () {
        const status = confirm('Are you sure?');
        if (status) {
            var selectedAsset = $(".selected-asset:checked")
                .map(function () {
                    return $(this).data("pci");
                })
                .get();

            if (selectedAsset.length === 0) {
                showDashboardModal("Select at least one record to send for finalization!");
            } else {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/send-pci-details-finalization",
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

    // calculate the chainage
    const prevChainage = $("#preChainage").val();
    const prevSecLength = $("#preSectionLength").val();
    const startChainage = $("#start_chainage").val();
    const curChainage =
        (parseFloat(prevChainage) * 1000 + parseFloat(prevSecLength)) / 1000;
    console.log(prevChainage);
    console.log(prevSecLength);
    if (prevChainage === "null" || prevSecLength === "null") {
        console.log("start chainage", startChainage);
        $("#chainage").val(startChainage);
    } else {
        console.log(curChainage);
        $("#chainage").val(curChainage);
    }

    $("#to_chainage").on("input", () => {
        let length = parseInt($("#road_length").val());
        console.log(length);
        let to_chainage = parseInt($("#to_chainage").val());
        console.log(to_chainage);
        if (to_chainage > length) {
            alert("Please enter a valid chainage");
            $("#to_chainage").val("");
        }
    });

    //
    $("form.updatePavementDetails").on("submit", function (e) {
        e.preventDefault();
        let location = "{{ route('road.add-pavement') }}";
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
                if (response.status == "success") {
                    Swal.fire({
                        icon: "success",
                        title: "success",
                        text: response.message,
                        showConfirmButton: true,
                        timer: 3000,
                    }).then(() => {
                        window.location.replace(location);
                    });
                } else if (response.status === "failed") {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.message,
                        showConfirmButton: true,
                        timer: 3000,
                    }).then(() => {
                        window.location.replace(location);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something Went Wrong!",
                        showConfirmButton: true,
                        timer: 3000,
                    }).then(() => {
                        window.location.replace(location);
                    });
                }
            },
        });
    });

    // script for retain wall
    const pciMethodRadio = document.querySelectorAll('input[name="pci_method"]');
    const chainageSection = document.getElementById("chanage_section");
    const pciParamSection = document.getElementById("pci_param_section");
    const pciInput = document.getElementById("pci_value");
    const otherSection = document.getElementById("other_section");
    const imageSection = document.getElementById("asset_image_container");
    const documentSection = document.getElementById("asset_document_container");

    pciMethodRadio.forEach((radio) => {
        radio.addEventListener("change", function () {
            chainageSection.style.display = "none";
            pciParamSection.style.display = "none";
            pciInput.style.display = "none";
            otherSection.style.display = "none";
            imageSection.style.display = "none";
            documentSection.style.display = "none";
            if (this.value === "M") {
                chainageSection.style.display = "flex";
                pciInput.style.display = "block";
                otherSection.style.display = "flex";
                imageSection.style.display = "flex";
                documentSection.style.display = "flex";
            } if (this.value === "P") {
                chainageSection.style.display = "flex";
                pciParamSection.style.display = "flex";
                otherSection.style.display = "flex";
                imageSection.style.display = "flex";
                documentSection.style.display = "flex";
            }
        });
    });
});

$(function () {
    $("#pavement_details_table")
        .DataTable({})
        .buttons()
        .container()
        .appendTo("#pavement_details_table_wrapper .col-md-11:eq(1)");
});
