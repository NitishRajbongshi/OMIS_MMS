$(document).ready(function () {
    $(
        "#wall_type_cd, #structure_type_cd, #year_of_construction, #year_of_renovation"
    ).select2();
    $("#asset_image_container").show();
    $("#asset_document_container").show();
    // Script to show year of construction
    var currentYear = new Date().getFullYear();
    var yearDropdown = document.getElementById("year_of_construction");
    for (var year = currentYear; year >= 1950; year--) {
        var option = document.createElement("option");
        option.value = year;
        option.text = year;
        yearDropdown.appendChild(option);
    }

    // Script to show year of renovation
    var currentYear = new Date().getFullYear();
    var yearDropdown = document.getElementById("year_of_renovation");
    for (var year = currentYear; year >= 1950; year--) {
        var option = document.createElement("option");
        option.value = year;
        option.text = year;
        yearDropdown.appendChild(option);
    }

    $(function () {
        $("#protectionWallTable")
            .DataTable({
                buttons: ["csv", "excel"],
            })
            .buttons()
            .container()
            .appendTo(".mis-btn-rd");
    });

    // send for finalization
    $("#freezeBtn").on("click", function () {
        const status = confirm("Are you sure?");
        if (status) {
            // const roadID = $("#road_system_id").val();
            var selectedAsset = $(".selected-asset:checked")
                .map(function () {
                    return $(this).data("protection-wall");
                })
                .get();
            if (selectedAsset.length === 0) {
                showDashboardModal(
                    "Select at least one record to send for finalization!"
                );
            } else {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/send-protection-wall-details-finalization",
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
});
//start modification by Pulak 02-05-26
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

    fetch('/asset-management/delete-protection-wall-file', {
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