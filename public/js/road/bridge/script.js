$(document).ready(function () {
    $(
        "#year_of_contruction, #construction_type, #bridge_type, #year_of_rehabilitation, #span_no, #pier_foundation_type, #superstructure_type, #handrail_type, #pile_type, #well_type, #deck_type, #pier_pile_type, #pier_well_type, #abutment_wall_type_cd, #abutment_bearings, #pier_bearings, #foundation_type, #expansion_joints, #wing_wall_type_cd, #wing_wall_type_1, #wing_wall_type_2, #wing_wall_type_3, #wing_wall_type_4, #condition, #pier_type_cd, #head_wall_stream_type_1, #head_wall_stream_type_2, #head_wall_type_1, #head_wall_type_2, #retain_wall_type_cd, #retain_wall_type_cd_1, #retain_wall_type_cd_2, #retain_wall_type_cd_3, #retain_wall_type_cd_4, #safety_apron_type, #safety_apron_hand_rail_type"
    ).select2();
    $("#bridge_type").on("change", () => {
        $(".bridge_common_field_container").show();
        $("#asset_image_container").show();
        $("#asset_document_container").show();
    });

    // calculate the piers
    $("#span_no").on("change", function () {
        const selectedSpan = $(this).val();
        const pierValue = selectedSpan - 1;
        $("#piers_container").hide();
        $("#spanContainer").hide();
        if (selectedSpan) {
            $("#no_of_piers").val(pierValue);
            $("#spanContainer").show();
        }
        if (pierValue > 0) {
            $("#piers_container").show();
        }
    });

    // send data for finalization
    $("#freezeBtn").on("click", function () {
        const status = confirm("Are you sure?");
        if (status) {
            var selectedAsset = $(".selected-asset:checked")
                .map(function () {
                    return $(this).data("cdwork-cd");
                })
                .get();
            if (selectedAsset.length === 0) {
                showDashboardModal(
                    "Select at least one record to send for finalization!"
                );
            } else {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/send-bridge-details-finalization",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
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
    });

    // start- foundation type
    const wingWallFields = document.getElementById("PileFoundation");
    $("#foundation_type").on("change", () => {
        $("#PileFoundation").hide();
        $("#WellFoundation").hide();
        $("#OpenFoundation").hide();
        let foundType = $("#foundation_type option:selected").val();
        if (foundType == 0) {
            wingWallFields.style.display = "block";
        }
        if (foundType == 1) {
            $("#WellFoundation").show();
        }
        if (foundType == 2) {
            $("#OpenFoundation").show();
        }
    });
    // end- foundation type

    // start- Pier foundation type
    const pierFoundation = document.getElementById("PierPileFoundation");
    $("#pier_foundation_type").on("change", () => {
        $("#PierPileFoundation").hide();
        $("#PierWellFoundation").hide();
        $("#PierOpenFoundation").hide();
        let foundType = $("#pier_foundation_type option:selected").val();
        if (foundType == 0) {
            pierFoundation.style.display = "block";
        }
        if (foundType == 1) {
            $("#PierWellFoundation").show();
        }
        if (foundType == 2) {
            $("#PierOpenFoundation").show();
        }
    });
    // end- Pier foundation type

    // start- wing wall type
    $("#wing_wall_type_cd").on("change", function () {
        const wallTypeCd = $(this).val();
        console.log(wallTypeCd);
        $("#angleContainer").hide();
        $("#radiusContainer").hide();
        $("#angle").attr("disabled", "disabled");
        $("#radius").attr("disabled", "disabled");

        // Splayed wind wall
        if (wallTypeCd === "1" || wallTypeCd === "4") {
            $("#angleContainer").show();
            $("#angle").removeAttr("disabled");
        }

        if (wallTypeCd === "3") {
            $("#angle").removeAttr("disabled");
            $("#radius").removeAttr("disabled");
            $("#angleContainer").show();
            $("#radiusContainer").show();
        }
    });

    // wing wall type 1
    $("#wing_wall_type_1").on("change", function () {
        const wallTypeCd_1 = $(this).val();
        console.log(wallTypeCd_1);
        $("#angleContainer_1").hide();
        $("#radiusContainer_1").hide();
        $("#angle_1").attr("disabled", "disabled");
        $("#radius_1").attr("disabled", "disabled");

        if (wallTypeCd_1 === "1" || wallTypeCd_1 === "4") {
            $("#angleContainer_1").show();
            $("#angle_1").removeAttr("disabled");
        }

        if (wallTypeCd_1 === "3") {
            $("#angle_1").removeAttr("disabled");
            $("#radius_1").removeAttr("disabled");
            $("#angleContainer_1").show();
            $("#radiusContainer_1").show();
        }
    });

    // wing wall type 2
    $("#wing_wall_type_2").on("change", function () {
        const wallTypeCd_2 = $(this).val();
        console.log(wallTypeCd_2);
        $("#angleContainer_2").hide();
        $("#radiusContainer_2").hide();
        $("#angle_2").attr("disabled", "disabled");
        $("#radius_2").attr("disabled", "disabled");

        if (wallTypeCd_2 === "1" || wallTypeCd_2 === "4") {
            $("#angleContainer_2").show();
            $("#angle_2").removeAttr("disabled");
        }

        if (wallTypeCd_2 === "3") {
            $("#angle_2").removeAttr("disabled");
            $("#radius_2").removeAttr("disabled");
            $("#angleContainer_2").show();
            $("#radiusContainer_2").show();
        }
    });

    // wing wall type 3
    $("#wing_wall_type_3").on("change", function () {
        const wallTypeCd_3 = $(this).val();
        console.log(wallTypeCd_3);
        $("#angleContainer_3").hide();
        $("#radiusContainer_3").hide();
        $("#angle_3").attr("disabled", "disabled");
        $("#radius_3").attr("disabled", "disabled");

        if (wallTypeCd_3 === "1" || wallTypeCd_3 === "4") {
            $("#angleContainer_3").show();
            $("#angle_3").removeAttr("disabled");
        }

        if (wallTypeCd_3 === "3") {
            $("#angle_3").removeAttr("disabled");
            $("#radius_3").removeAttr("disabled");
            $("#angleContainer_3").show();
            $("#radiusContainer_3").show();
        }
    });

    // wing wall type 4
    $("#wing_wall_type_4").on("change", function () {
        const wallTypeCd_4 = $(this).val();
        console.log(wallTypeCd_4);
        $("#angleContainer_4").hide();
        $("#radiusContainer_4").hide();
        $("#angle_4").attr("disabled", "disabled");
        $("#radius_4").attr("disabled", "disabled");

        if (wallTypeCd_4 === "1" || wallTypeCd_4 === "4") {
            $("#angleContainer_4").show();
            $("#angle_4").removeAttr("disabled");
        }

        if (wallTypeCd_4 === "3") {
            $("#angle_4").removeAttr("disabled");
            $("#radius_4").removeAttr("disabled");
            $("#angleContainer_4").show();
            $("#radiusContainer_4").show();
        }
    });
    // end- wing wall type

    // start - year
    const currentYear = new Date().getFullYear();
    const $yearDropdown = $(".construnctionYearEdit");
    const $rehYearDropDown = $(".rehabilitationYearEdit");
    for (let year = currentYear; year >= 1950; year--) {
        const $option = $("<option>");
        $option.val(year);
        $option.html(year);
        $yearDropdown.append($option);
    }
    for (let year = currentYear; year >= 1950; year--) {
        const $option = $("<option>");
        $option.val(year);
        $option.html(year);
        $rehYearDropDown.append($option);
    }
    // end - year

    $("form.updateBridgeDetails").on("submit", function (e) {
        e.preventDefault();
        let location = "{{ route('bridge.store') }}";
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

    $("#chainage").on("input", () => {
        const rd_end_chainage = Number($("#road_end_chainage").val());
        const rd_srt_chainage = Number($("#road_start_chainage").val());
        const enteredChainage = Number($("#chainage").val());
        if (
            enteredChainage > rd_end_chainage ||
            enteredChainage < rd_srt_chainage
        ) {
            alert(
                `Chainage value should between ${rd_srt_chainage} - ${rd_end_chainage}`
            );
            $("#chainage").val("");
        }
    });
});

// start - Script to show year of rehabitation
var currentYear = new Date().getFullYear();
var yearDropdown = document.getElementById("year_of_rehabilitation");
for (var year = currentYear; year >= 1950; year--) {
    var option = document.createElement("option");
    option.value = year;
    option.text = year;
    yearDropdown.appendChild(option);
}
// end - Script to show year of rehabitation

// start - Script to show year of construction
var currentYear = new Date().getFullYear();
var yearDropdown = document.getElementById("year_of_contruction");
for (var year = currentYear; year >= 1950; year--) {
    var option = document.createElement("option");
    option.value = year;
    option.text = year;
    yearDropdown.appendChild(option);
}
// end - Script to show year of construction

// start - safty aprone
const safetyApronRadio = document.querySelectorAll(
    'input[name="has_safety_apron"]'
);
const apronContainer = document.getElementById("apron_container");
const apronWidthInput = document.getElementById("apron_width");
const appronTypeInput = document.getElementById("safety_apron_type");
const handRailTypeContainer = document.getElementById("hand_rail_container");
const handRailTypeInput = document.getElementById(
    "safety_apron_hand_rail_type"
);
safetyApronRadio.forEach((radio) => {
    radio.addEventListener("change", function () {
        if (this.value === "Y") {
            apronContainer.style.display = "flex";
            apronWidthInput.removeAttribute("disabled");
            appronTypeInput.removeAttribute("disabled");
        } else {
            apronContainer.style.display = "none";
            apronWidthInput.setAttribute("disabled", "disabled");
            appronTypeInput.setAttribute("disabled", "disabled");
        }
    });
});

appronTypeInput.addEventListener("change", function () {
    if (this.value === "1") {
        handRailTypeContainer.style.display = "block";
        handRailTypeInput.removeAttribute("disabled");
    } else {
        handRailTypeContainer.style.display = "none";
        handRailTypeInput.setAttribute("disabled", "disabled");
    }
});

document
    .getElementById("cd_bridge_form")
    .addEventListener("submit", function () {
        document.getElementById("apron_width").removeAttribute("disabled");
        document
            .getElementById("safety_apron_type")
            .removeAttribute("disabled");
        document
            .getElementById("safety_apron_hand_rail_type")
            .removeAttribute("disabled");
        document.getElementById("angle").removeAttribute("disabled");
        document.getElementById("radius").removeAttribute("disabled");

        for (let i = 1; i <= 4; i++) {
            document.getElementById(`angle_${i}`).removeAttribute("disabled");
            document.getElementById(`radius_${i}`).removeAttribute("disabled");
        }
    });
// end - safty aprone

// script for file handling

//start modification by Pulak 29-04-26
function showRemoveBtn(inputId) {
    const fileInput = document.getElementById(inputId);
    const removeButton = document.getElementById("removeBtn_" + inputId);

    if (!fileInput || fileInput.files.length === 0) return;

    // =========================
    // 🚫 CHECK EXISTING FILE
    // =========================
    const existingDeleteBtn = document.getElementById("deleteBtn_" + inputId);

    if (existingDeleteBtn) {
        alert("Please delete the existing file first before uploading a new one.");
        fileInput.value = ""; // reset input
        return;
    }

    const file = fileInput.files[0];
    const fileType = file.type;
    const fileSize = file.size;

    // =========================
    // VALIDATION
    // =========================
    if (inputId === "images") {
        const allowedTypes = ["image/jpeg", "image/jpg"];
        const maxSize = 1 * 1024 * 1024;

        if (!allowedTypes.includes(fileType)) {
            alert("Only JPG/JPEG images are allowed in Image section.");
            fileInput.value = "";
            removePreviewOrView(inputId);
            return;
        }

        if (fileSize > maxSize) {
            alert("Image size must be less than 1 MB.");
            fileInput.value = "";
            removePreviewOrView(inputId);
            return;
        }
    } else {
        const maxSize = 2 * 1024 * 1024;

        if (fileType !== "application/pdf") {
            alert("Only PDF files are allowed in Document section.");
            fileInput.value = "";
            removePreviewOrView(inputId);
            return;
        }

        if (fileSize > maxSize) {
            alert("PDF size must be less than 2 MB.");
            fileInput.value = "";
            removePreviewOrView(inputId);
            return;
        }
    }
    removePreviewOrView(inputId);

    // =========================
    // ADD NEW VIEW BUTTON
    // =========================
    removeButton.style.display = "inline-block";

    const fileURL = URL.createObjectURL(file);

    let viewBtn = document.createElement("button");
    viewBtn.type = "button";
    viewBtn.id = "viewBtn_" + inputId;
    viewBtn.innerText = "View";
    viewBtn.className = "border border-primary text-primary text-xs rounded-1";
    viewBtn.style.marginLeft = "5px";
    viewBtn.style.background = "#e6f0ff";

    viewBtn.onclick = function () {
        window.open(fileURL, "_blank");
    };

    fileInput.parentNode.appendChild(viewBtn);
}

function removeFile(inputId) {
    const fileInput = document.getElementById(inputId);
    const removeButton = document.getElementById("removeBtn_" + inputId);

    if (fileInput) fileInput.value = "";
    if (removeButton) removeButton.style.display = "none";

    removePreviewOrView(inputId);
}

function removePreviewOrView(inputId) {

    // remove single view button (for documents / new uploads)
    const viewBtn = document.getElementById("viewBtn_" + inputId);
    const deleteBtn = document.getElementById("deleteBtn_" + inputId);
    if (viewBtn) viewBtn.remove();
    if (deleteBtn) deleteBtn.remove();
}

//end modification by Pulak 29-04-26

// Script for span length manipulation
const spanLengthRadio = document.querySelectorAll(
    'input[name="span_dimension"]'
);
spanLengthRadio.forEach((radio) => {
    radio.addEventListener("change", function () {
        $("#single_span_field_container").hide();
        $("#multiple_span_field_container").hide();
        // get the no. of span
        const spanValue = $("#span_no").val();

        if (this.value === "Y") {
            $("#single_span_field_container").show();
        } else {
            var spanLengthContainer = $("#span_field_container");
            spanLengthContainer.empty();
            for (var i = 1; i <= spanValue; i++) {
                var inputField = `<div class="col-md-3">
                                    <label for="span_length_${i}">Span Length ${i} (Mtrs):<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000" id="span_length_${i}" oninput="restrictDecimalPoints(event)" class="form-control" name="span_length_${i}" value="{{ old('span_length_' + ${i}) }}">
                                  </div>`;
                spanLengthContainer.append(inputField);
            }
            $("#multiple_span_field_container").show();
        }
    });
});

// Script for wing wall
const bridgeWingWallRadio = document.querySelectorAll(
    'input[name="wing_wall"]'
);
const bridgeWingWall = document.getElementById("bridge_wing_wall");
const wingWallRadio = document.querySelectorAll(
    'input[name="is_same_wing_wall"]'
);
const wingWallFields = document.getElementById("box_wing_wall_fields");
const multipleWingWallFields = document.getElementById(
    "box_wing_wall_fields_multiple"
);

bridgeWingWallRadio.forEach((radio) => {
    radio.addEventListener("change", function () {
        wingWallFields.style.display = "none";
        multipleWingWallFields.style.display = "none";
        if (this.value === "Y") {
            bridgeWingWall.style.display = "block";
        } else {
            bridgeWingWall.style.display = "none";
        }
    });
});

wingWallRadio.forEach((radio) => {
    radio.addEventListener("change", function () {
        if (this.value === "Y") {
            wingWallFields.style.display = "block";
            multipleWingWallFields.style.display = "none";
            wingWallFields
                .querySelectorAll("input, select")
                .forEach((field) => field.removeAttribute("disabled"));
            multipleWingWallFields
                .querySelectorAll("input, select")
                .forEach((field) => field.setAttribute("disabled", "disabled"));
        } else {
            wingWallFields.style.display = "none";
            multipleWingWallFields.style.display = "block";
            wingWallFields
                .querySelectorAll("input, select")
                .forEach((field) => field.setAttribute("disabled", "disabled"));
            multipleWingWallFields
                .querySelectorAll("input, select")
                .forEach((field) => field.removeAttribute("disabled"));
        }
    });
});

// script for retain wall
const bridgeRetainWallRadio = document.querySelectorAll(
    'input[name="bridge_retain_wall"]'
);
const bridgeRetainWall = document.getElementById("bridge_retain_wall");
const retainWallRadio = document.querySelectorAll(
    'input[name="is_same_retain_wall"]'
);
const retainWallFields = document.getElementById("bridge_retain_wall_fields");
const multipleRetainWallFields = document.getElementById(
    "bridge_retain_wall_fields_multiple"
);

bridgeRetainWallRadio.forEach((radio) => {
    radio.addEventListener("change", function () {
        retainWallFields.style.display = "none";
        multipleRetainWallFields.style.display = "none";
        if (this.value === "Y") {
            bridgeRetainWall.style.display = "block";
        } else {
            bridgeRetainWall.style.display = "none";
        }
    });
});

retainWallRadio.forEach((radio) => {
    radio.addEventListener("change", function () {
        if (this.value === "Y") {
            retainWallFields.style.display = "block";
            multipleRetainWallFields.style.display = "none";
            retainWallFields
                .querySelectorAll("input, select")
                .forEach((field) => field.removeAttribute("disabled"));
            multipleRetainWallFields
                .querySelectorAll("input, select")
                .forEach((field) => field.setAttribute("disabled", "disabled"));
        } else {
            retainWallFields.style.display = "none";
            multipleRetainWallFields.style.display = "block";
            retainWallFields
                .querySelectorAll("input, select")
                .forEach((field) => field.setAttribute("disabled", "disabled"));
            multipleRetainWallFields
                .querySelectorAll("input, select")
                .forEach((field) => field.removeAttribute("disabled"));
        }
    });
});

// script for abutment
const bridgeAbutmentWallRadio = document.querySelectorAll(
    'input[name="bridge_abutment"]'
);
const bridgeAbutmentWall = document.getElementById("bridge_abutment_container");

bridgeAbutmentWallRadio.forEach((radio) => {
    radio.addEventListener("change", function () {
        bridgeAbutmentWall.style.display = "none";
        if (this.value === "Y") {
            bridgeAbutmentWall.style.display = "block";
            bridgeAbutmentWall
                .querySelectorAll("input, select")
                .forEach((field) => field.removeAttribute("disabled"));
        } else {
            bridgeAbutmentWall.style.display = "none";
            bridgeAbutmentWall
                .querySelectorAll("input, select")
                .forEach((field) => field.setAttribute("disabled", "disabled"));
        }
    });
});

// Script for head wall
const bridgeHeadWallRadio = document.querySelectorAll(
    'input[name="bridge_head_wall"]'
);
const bridgeHeadWallContainer = document.getElementById(
    "bridge_head_wall_container"
);

bridgeHeadWallRadio.forEach((radio) => {
    radio.addEventListener("change", function () {
        bridgeHeadWallContainer.style.display = "none";
        if (this.value === "Y") {
            bridgeHeadWallContainer.style.display = "block";
        } else {
            bridgeHeadWallContainer.style.display = "none";
        }
    });
});

//
$('select[name="foundation_type_edit"]').on("change", function () {
    const selectedValue = $(this).val();
    console.log(selectedValue);
    $(".PileFoundationEdit").hide();
    $(".WellFoundationEdit").hide();
    $(".OpenFoundationEdit").hide();
    if (selectedValue == 0) {
        $(".PileFoundationEdit").show();
    }
    if (selectedValue == 1) {
        $(".WellFoundationEdit").show();
    }
    if (selectedValue == 2) {
        $(".OpenFoundationEdit").show();
    }
});
//
//
$(function () {
    $("#bridge_details_table").DataTable();
});
//
//
document
    .getElementById("cd_bridge_form")
    .addEventListener("submit", function () {
        document.getElementsByName("chainage_to")[0].disabled = false;
    });
//

//modified by Pulak 29/04/2026
function loadExistingFiles(data) {

    // =========================
    // IMAGES (multiple)
    // =========================
    const imageInput = document.getElementById("images");

    if (imageInput && data.images.length > 0) {

        if (!imageInput) return;
        // remove old previews/buttons
        removePreviewOrView(imageInput);

        data.images.forEach(img => {

            let container = document.createElement("div");
            container.style.marginTop = "5px";

            // VIEW button
            let viewBtn = document.createElement("button");
            viewBtn.type = "button";
            viewBtn.id = "viewBtn_images";
            viewBtn.innerText = "View";
            viewBtn.className = "border border-primary text-primary text-xs rounded-1";
            viewBtn.style.marginRight = "5px";

            viewBtn.onclick = function () {
                window.open(img.image_url, "_blank");
            };

            // DELETE button
            let deleteBtn = document.createElement("button");
            deleteBtn.id = "deleteBtn_images";
            deleteBtn.type = "button";
            deleteBtn.innerText = "Delete";
            deleteBtn.className = "border border-danger text-danger text-xs rounded-1";

            deleteBtn.onclick = function () {
                removeExistingFile(img.id, "images");
            };

            container.appendChild(viewBtn);
            container.appendChild(deleteBtn);

            imageInput.parentNode.appendChild(container);
        });
    }

    // =========================
    // DOCUMENTS (fixed 4 fields)
    // =========================
    data.documents.forEach(doc => {

        let inputId = "";

        switch (doc.doc_catg) {
            case "WO":
                inputId = "workorder";
                break;
            case "DD":
                inputId = "design_doc";
                break;
            case "SO":
                inputId = "sanction_order";
                break;
            case "IR":
                inputId = "inspection_report";
                break;
        }

        if (!inputId) return;

        const fileInput = document.getElementById(inputId);
        if (!fileInput) return;

        // remove old buttons
        removePreviewOrView(inputId);

        // VIEW button
        let viewBtn = document.createElement("button");
        viewBtn.type = "button";
        viewBtn.id = "viewBtn_" + inputId;
        viewBtn.innerText = "View";
        viewBtn.className = "border border-primary text-primary text-xs rounded-1";
        viewBtn.style.marginLeft = "5px";

        viewBtn.onclick = function () {
            window.open(doc.file_url, "_blank");
        };

       // DELETE button
        let deleteBtn = document.createElement("button");
        deleteBtn.id = "deleteBtn_" + inputId;
        deleteBtn.type = "button";
        deleteBtn.innerText = "Delete";
        deleteBtn.className = "border border-danger text-danger text-xs rounded-1";
        deleteBtn.style.marginLeft = "5px";

        deleteBtn.onclick = function () {
            removeExistingFile(doc.id, inputId);
        };

        fileInput.parentNode.appendChild(viewBtn);
        fileInput.parentNode.appendChild(deleteBtn);
    });
}

function removeExistingFile(id, inputId) {
    if (!confirm("Delete this file?")) return;

    fetch('/asset-management/delete-bridge-file', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            id: id,
            type: inputId === "images" ? "image" : "document",
        })
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            alert("Deleted");

            removePreviewOrView(inputId); // remove UI
        } else {
            alert(res.message || "Delete failed");
        }
    });
}
// modification end by Pulak 29/04/2026
