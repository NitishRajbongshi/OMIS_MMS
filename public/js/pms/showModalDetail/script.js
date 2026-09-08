function showModalDetail(id, type) {
    const showModal = document.getElementById("showModal");
    const modalValContainer = $("#modalValContainer");

    modalValContainer.empty();

    $.ajax({
        type: "GET",
        url:
            "/project-management/get-modal-detail/" +
            encodeURIComponent(id) +
            "/" +
            encodeURIComponent(type),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    const currentIndex = index + 1;

                    // -------- Heading Text Logic (clean version) --------
                    let titleMap = {
                        Culvert: "Culvert",
                        Bridge: "Bridge",
                        rtw: "Retain Wall",
                        pvm: "Pavements",
                    };

                    let headingText = titleMap[type] || "Item";

                    // Single item → no index
                    if (response.value.length > 1) {
                        headingText += " " + currentIndex;
                    }

                    // -------- Append Heading --------
                    modalValContainer.append(`
                        <div class="w-full mt-2">
                            <p class="text-primary text-bold border-bottom">
                                <i class="fa fa-caret-right mr-1"></i> ${headingText}
                            </p>
                        </div>
                    `);

                    // -------- Row Wrapper for fields --------
                    modalValContainer.append(
                        `<div class="row mt-1" id="row_${currentIndex}"></div>`,
                    );
                    let row = $("#row_" + currentIndex);

                    // -------- Fields --------
                    const fields = [
                        { label: "Sub asset Type", value: item.name },
                        { label: "Chainage", value: item.start_chainage },
                    ];

                    $.each(fields, function (i, field) {
                        row.append(`
                            <div class="col-sm-6 mb-2">
                                <label class="form-label">${field.label}</label>
                                <input type="text" class="form-control form-control-sm" value="${field.value}" disabled>
                            </div>
                        `);
                    });
                });

                if (showModal) showModal.style.display = "block";
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
        },
    });

    // ---- Modal Close ----
    const span = document.querySelector(".closeShowModal");
    if (span) {
        span.onclick = function () {
            if (showModal) showModal.style.display = "none";
        };
    }

    window.onclick = function (event) {
        if (showModal && event.target == showModal) {
            showModal.style.display = "none";
        }
    };
}


// ------------------------------------------------------
// FETCH SUB ITEMS FUNCTION
// ------------------------------------------------------

function toggleSubItems(item_cd, index, id) {
    const container = $("#subitems-" + index);
    const button = $("#btn-sub-" + index);

    // If already visible → hide it
    if (container.is(":visible")) {
        container.hide();
        button.text("Show Subitems");
        return;
    }

    // Load subitems only once
    if (container.attr("data-loaded") === "1") {
        container.show();
        button.text("Hide Subitems");
        return;
    }

    container.html(`<span class="text-muted">Loading...</span>`);

    $.ajax({
        type: "GET",
        url:
            "/project-management/get-sub-items/" +
            encodeURIComponent(item_cd) +
            "/" +
            encodeURIComponent(id),
        cache: false,
        success: function (response) {
            if (response.status === "success") {
                if (response.subitems.length === 0) {
                    container.html(
                        `<p class="small text-danger">No subitems found</p>`,
                    );
                } else {
                    let html = `<ul class="list-group list-group-sm">`;

                    $.each(response.subitems, function (i, sub) {
                        html += `
                            <li class="list-group-item p-1 small">
                                <b>${sub.name}</b> – Qty: ${sub.quantity ?? "-"}
                            </li>
                        `;
                    });

                    html += `</ul>`;
                    container.html(html);
                }

                container.attr("data-loaded", "1");
                container.show();
                button.text("Hide Subitems");
            }
        },
        error: function () {
            container.html(
                `<p class="text-danger small">Error loading subitems</p>`,
            );
        },
    });
}

// ----------------------------
// OPEN Upgradation Modal
// ----------------------------
function showUpgradationSubAssetsModal(id) {
    const modal = document.getElementById("viewUpgradationSubAssetsModal");
    modal.style.display = "block";

    loadUpgradationSubAssets(id);
}

// ----------------------------
// CLOSE Upgradation Modal
// ----------------------------
function hideUpgradationSubAssetsModal() {
    const modal = document.getElementById("viewUpgradationSubAssetsModal");
    modal.style.display = "none";
}

// Close button (×)
document
    .getElementById("closeUpgradationSubAssets")
    .addEventListener("click", hideUpgradationSubAssetsModal);

window.addEventListener("click", function (e) {
    const modal = document.getElementById("viewUpgradationSubAssetsModal");
    if (e.target === modal) {
        hideUpgradationSubAssetsModal();
    }
});

// ----------------------------
// OPEN Maintenance Modal
// ----------------------------
function showMaintenanceSubAssetsModal(id) {
    const modal = document.getElementById("viewMaintenanceSubAssetsModal");
    modal.style.display = "block";
    loadMaintenanceSubAssets(id);
}

// ----------------------------
// CLOSE Maintenance Modal
// ----------------------------
function hideMaintenanceSubAssetsModal() {
    const modal = document.getElementById("viewMaintenanceSubAssetsModal");
    modal.style.display = "none";
}

// Close button (×)
document
    .getElementById("closeMaintenanceSubAssets")
    .addEventListener("click", hideMaintenanceSubAssetsModal);

// Optional: Click outside to close
window.addEventListener("click", function (e) {
    const modal = document.getElementById("viewMaintenanceSubAssetsModal");
    if (e.target === modal) {
        hideMaintenanceSubAssetsModal();
    }
});

function loadUpgradationSubAssets(projectId) {
    $.ajax({
        url: "/project-management/get-upgradation-subassets/" + projectId,
        type: "GET",
        success: function (response) {
            const tbody = $("#viewUpgradationSubAssetsTable tbody");
            tbody.empty();

            if (!response.status || !response.data) {
                tbody.append(
                    `<tr><td colspan="7" class="text-center text-danger">No Data Found</td></tr>`,
                );
                return;
            }

            const data = response.data;

            const roads = data.upgrade_road || [];
            const startC = data.upg_start_chainage || [];
            const endC = data.upg_end_chainage || [];

            const walls = data.walls || {};
            const bridges = data.bridges || {};
            const culverts = data.culverts || {};

            if (roads.length === 0) {
                tbody.append(
                    `<tr><td colspan="7" class="text-center text-danger">No Upgradation Data Found</td></tr>`,
                );
                return;
            }

            roads.forEach((road, index) => {
                const rowNo = index + 1;

                // ⭐ Show actual values, not count
                const wallsList = walls[rowNo] ? walls[rowNo].join(", ") : "";
                const bridgesList = bridges[rowNo]
                    ? bridges[rowNo].join(", ")
                    : "";
                const culvertsList = culverts[rowNo]
                    ? culverts[rowNo].join(", ")
                    : "";

                tbody.append(`
                    <tr>
                        <td>${rowNo}</td>
                        <td>${road}</td>
                        <td>${startC[index] || ""}</td>
                        <td>${endC[index] || ""}</td>
                        <td>${culvertsList}</td>
                        <td>${bridgesList}</td>
                        <td>${wallsList}</td>
                    </tr>
                `);
            });
        },
        error: function () {
            alert("Failed to load Upgradation Sub-Assets!");
        },
    });
}

function loadMaintenanceSubAssets(projectId) {
    $.ajax({
        url: "/project-management/get-maintenance-subassets/" + projectId,
        type: "GET",
        success: function (response) {
            const tbody = $("#viewMaintenanceSubAssetsTable tbody");
            tbody.empty();

            if (!response.status || !response.data) {
                tbody.append(
                    `<tr><td colspan="7" class="text-center text-danger">No Data Found</td></tr>`,
                );
                return;
            }

            const data = response.data;

            const roads = data.mnt_road || [];
            const startC = data.mnt_start_chainage || [];
            const endC = data.mnt_end_chainage || [];

            const walls = data.mnt_walls || {};
            const bridges = data.mnt_bridges || {};
            const culverts = data.mnt_culverts || {};

            if (roads.length === 0) {
                tbody.append(
                    `<tr><td colspan="7" class="text-center text-danger">No Maintenance Data Found</td></tr>`,
                );
                return;
            }

            roads.forEach((road, index) => {
                const rowNo = index + 1;

                // ⭐ Show values instead of count
                const wallsList = walls[rowNo] ? walls[rowNo].join(", ") : "";
                const bridgesList = bridges[rowNo]
                    ? bridges[rowNo].join(", ")
                    : "";
                const culvertsList = culverts[rowNo]
                    ? culverts[rowNo].join(", ")
                    : "";

                tbody.append(`
                    <tr>
                        <td>${rowNo}</td>
                        <td>${road}</td>
                        <td>${startC[index] || ""}</td>
                        <td>${endC[index] || ""}</td>
                        <td>${culvertsList}</td>
                        <td>${bridgesList}</td>
                        <td>${wallsList}</td>
                    </tr>
                `);
            });
        },
        error: function () {
            alert("Failed to load Maintenance Sub-Assets!");
        },
    });
}
