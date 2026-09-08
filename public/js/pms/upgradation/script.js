//This function is not in use currently
function addUpgradeSubAssets() {
    // const road = document.getElementById('upgradeSubAssetRoad').value;

    const culverts = Array.from(
        document.getElementById("upgradeCulverts").selectedOptions,
    )
        .map((opt) => opt.text)
        .join(", ");
    const bridges = Array.from(
        document.getElementById("upgradeBridges").selectedOptions,
    )
        .map((opt) => opt.text)
        .join(", ");
    const walls = Array.from(
        document.getElementById("upgradeWalls").selectedOptions,
    )
        .map((opt) => opt.text)
        .join(", ");

    const row = document.createElement("tr");
    row.innerHTML = `
                    <td>${culverts}</td>
                    <td>${bridges}</td>
                    <td>${walls}</td>
                    <td><button class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Delete</button></td>
                    `;
    document.querySelector("#upgradeSubAssetTable tbody").appendChild(row);

    // Optional: Clear selections
    document.getElementById("upgradeCulverts").selectedIndex = -1;
    document.getElementById("upgradeBridges").selectedIndex = -1;
    document.getElementById("upgradeWalls").selectedIndex = -1;
}

// Asset of Upgradation
const addRefAssetBtn = document.getElementById("addRefAssetBtn");
const refRoadSelect = document.getElementById("refRoadSelect");
const refCulvertSelect = document.getElementById("refCulvertSelect");
const refBridgeSelect = document.getElementById("refBridgeSelect");
const refWallSelect = document.getElementById("refWallSelect");
const txt_start_chainage = document.getElementById("txt_start_chainage");
const txt_end_chainage = document.getElementById("txt_end_chainage");

const refAssetTableBody = document.querySelector("#refAssetTable tbody");

function addMaintenanceAssets() {
    const road = Array.from(maintRoads.selectedOptions).map((opt) => opt.value);
    const culverts = Array.from(maintCulverts.selectedOptions).map(
        (opt) => opt.value,
    );
    const bridges = Array.from(maintBridges.selectedOptions).map(
        (opt) => opt.value,
    );
    const walls = Array.from(maintWalls.selectedOptions).map(
        (opt) => opt.value,
    );

    if (
        road.length === 0 &&
        culverts.length === 0 &&
        bridges.length === 0 &&
        walls.length === 0
    ) {
        Swal.fire({
            icon: "error",
            title: "No Assets Selected",
            text: "Please select at least one asset."
        });
        return;
    }

    // Unique index for each asset row
    mnt_rowIndex++;
    document.getElementById("mnt_rowCount").value = mnt_rowIndex;

    const tr = document.createElement("tr");

    tr.innerHTML = `
        <td>
            ${road
                .map(
                    (r) => `
                <input type="hidden" name="roads_mnt${mnt_rowIndex}[]" value="${r}">
                <span class="badge bg-info me-1">${r}</span>
            `,
                )
                .join("")}
        </td>

        <td>
            ${culverts
                .map(
                    (c) => `
                <input type="hidden" name="culverts_mnt_${mnt_rowIndex}[]" value="${c}">
                <span class="badge bg-info me-1">${c}</span>
            `,
                )
                .join("")}
        </td>

        <td>
            ${bridges
                .map(
                    (b) => `
                <input type="hidden" name="bridges_mnt_${mnt_rowIndex}[]" value="${b}">
                <span class="badge bg-warning text-dark me-1">${b}</span>
            `,
                )
                .join("")}
        </td>

        <td>
            ${walls
                .map(
                    (w) => `
                <input type="hidden" name="walls_mnt_${mnt_rowIndex}[]" value="${w}">
                <span class="badge bg-secondary me-1">${w}</span>
            `,
                )
                .join("")}
        </td>

        <td>
            <button type="button" class="btn btn-sm btn-danger deleteRowBtn">Delete</button>
        </td>
    `;

    document.querySelector("#mtnAssetTable tbody").appendChild(tr);
    [
        "maintRoads",
        "maintCulverts",
        "maintBridges",
        "maintWalls",
    ].forEach((id) => {
        const el = document.getElementById(id);
        if (el) {
            el.selectedIndex = -1; // unselect all options
        }
    });

    tr.querySelector(".deleteRowBtn").addEventListener("click", () => {
        tr.remove();
    });
}

document.getElementById("refRoadSelect")?.addEventListener("change", () => {
    loadChainage("upg");
});

let rowIndex = 0;

addRefAssetBtn.addEventListener("click", () => {
    let val_start = parseFloat(txt_start_chainage.value);
    let val_end = parseFloat(txt_end_chainage.value);

    if (val_start < startChainage_from || val_start > startChainage_to) {
        Swal.fire({
            icon: "error",
            title: "Invalid Start Chainage",
            text: "Please enter a valid start chainage."
        }).then(() => {
            txt_start_chainage.focus();
        });
        return;
    }

    if (
        val_end < startChainage_from ||
        val_end > startChainage_to ||
        val_end <= val_start
    ) {
        Swal.fire({
            icon: "error",
            title: "Invalid End Chainage",
            text: "Please enter a valid end chainage."
        }).then(() => {
            txt_end_chainage.focus();
        });
        return;
    }

    const road = refRoadSelect.value;

    const culverts = Array.from(refCulvertSelect.selectedOptions)
        .map((opt) => opt.value)
        .filter((val) => val !== "");

    const bridges = Array.from(refBridgeSelect.selectedOptions)
        .map((opt) => opt.value)
        .filter((val) => val !== "");

    const walls = Array.from(refWallSelect.selectedOptions)
        .map((opt) => opt.value)
        .filter((val) => val !== "");

    if (
        document.querySelector('input[name="ref_asset"]:checked')?.value !==
            "exist" &&
        culverts.length === 0 &&
        bridges.length === 0 &&
        walls.length === 0
    ) {
        Swal.fire({
            icon: "error",
            title: "No Assets Selected",
            text: "Please select at least one asset."
        });
        return;
    }

    const startChainage = txt_start_chainage.value.trim();
    const endChainage = txt_end_chainage.value.trim();

    if (!road || !startChainage || !endChainage) {
        Swal.fire({
            icon: "warning",
            title: "Missing Required Fields",
            text: "Please select a road and enter both start and end chainages."
        });
        return;
    }

	const existingRoads = Array.from(
        document.querySelectorAll('#refAssetTable tbody input[name="roads[]"]')
    ).map(input => input.value);

    if (existingRoads.includes(road)) {
        Swal.fire({
            icon: "warning",
            title: "Duplicate Road",
            text: "This road has already been added."
        });
        return;
    }

    rowIndex++;

    document.getElementById("rowCount").value = rowIndex;

    const tr = document.createElement("tr");

	 const chainageColumns = document.getElementById("rdoRoadWithSubAsset").checked
    ? `
        <td>
            <input type="hidden" name="start_chainage[]" value="${startChainage}">
            ${startChainage}
        </td>

        <td>
            <input type="hidden" name="end_chainage[]" value="${endChainage}">
            ${endChainage}
        </td>
      `
    : '';

    tr.innerHTML = `
        <td>
            <input type="hidden" name="roads[]" value="${road}">
            ${road}
        </td>

		        ${chainageColumns}

        <td>
            ${culverts
                .map(
                    (c) => `
                <input type="hidden" name="culverts_${rowIndex}[]" value="${c}">
                <span class="badge bg-info me-1">${c}</span>
            `,
                )
                .join("")}
        </td>

        <td>
            ${bridges
                .map(
                    (b) => `
                <input type="hidden" name="bridges_${rowIndex}[]" value="${b}">
                <span class="badge bg-warning text-dark me-1">${b}</span>
            `,
                )
                .join("")}
        </td>

        <td>
            ${walls
                .map(
                    (w) => `
                <input type="hidden" name="walls_${rowIndex}[]" value="${w}">
                <span class="badge bg-secondary me-1">${w}</span>
            `,
                )
                .join("")}
        </td>
		<td>
            <button type="button" class="btn btn-sm btn-danger deleteRowBtn">Delete</button>
        </td>
    `;

    refAssetTableBody.appendChild(tr);

	if (document.getElementById("rdoRoadWithSubAsset").checked) {
        addRoadToPriorityList(road);
        updatePriorityCardVisibility();
		calculateTotalRoadLength();
    }


    refRoadSelect.selectedIndex = 0;
    [
        "refCulvertSelect",
        "refBridgeSelect",
        "refWallSelect",
    ].forEach((id) => {
        const el = document.getElementById(id);
        if (el) {
            el.selectedIndex = -1;
            el.value = "";
            el.innerHTML = '<option value="">-- NA --</option>';
        }
    });
    document.getElementById("txt_chainage_msg").textContent = "";
    txt_start_chainage.value = "";
    txt_end_chainage.value = "";
});

function calculateTotalRoadLength() {
    let total = 0;

    document.querySelectorAll('#refAssetTable tbody tr').forEach(row => {
        const startInput = row.querySelector('input[name="start_chainage[]"]');
        const endInput = row.querySelector('input[name="end_chainage[]"]');

        if (startInput && endInput) {
            const start = parseFloat(startInput.value) || 0;
            const end = parseFloat(endInput.value) || 0;

            total += (end - start);
        }
    });

    const roadLengthElement = document.getElementById("road_length");
    const roadLengthNv = parseFloat(document.getElementById("road_length_nv")?.value) || 0;

    let roadLength = 0;

    // Use original road length only when no edited value is entered
    if (roadLengthNv === 0) {
        roadLength = parseFloat(
            roadLengthElement?.value ?? roadLengthElement?.textContent ?? 0
        ) || 0;
    }

    total += roadLength + roadLengthNv;

    document.getElementById('total_road_length').value = total.toFixed(2);
}

function updatePriorityCardVisibility() {

    const count = document.getElementById("roadPriorityList").children.length;

    document.getElementById("priorityCard").style.display =
        count > 1 ? "block" : "none";
}

function addRoadToPriorityList(road = null) {

    if (!road) {
        road = document.getElementById("new_road_name")?.textContent.trim() || document.getElementById("new_road_name_nv")?.value.trim();
    }

    if (!road) return;

    const exists = Array.from(
        document.querySelectorAll("#roadPriorityList [data-road]")
    ).some(el => el.dataset.road === road);

    if (exists) return;

    const item = document.createElement("div");

    item.className = "list-group-item d-flex align-items-center";

    item.dataset.road = road;

    item.innerHTML = `
        <i class="fa fa-grip-vertical me-3 text-secondary"></i>

        <span class="fw-bold flex-grow-1">
            ${road}
        </span>

        <input type="hidden"
               name="priority_roads[]"
               value="${road}">
    `;

    document.getElementById("roadPriorityList").appendChild(item);

    document.getElementById("priorityCard").style.display = "block";

}

document.getElementById("new_road_name").addEventListener("input", function () {

    const road = this.value.trim();

    let item = document.getElementById("newRoadPriorityItem");

    if (!road) {
        if (item) item.remove();
        updatePriorityCardVisibility();
        return;
    }

    if (!item) {
        item = document.createElement("div");
        item.id = "newRoadPriorityItem";
        item.className = "list-group-item d-flex align-items-center";

        document.getElementById("roadPriorityList").appendChild(item);
    }

    item.innerHTML = `
        <i class="fa fa-grip-vertical me-3 text-secondary"></i>

        <span class="fw-bold flex-grow-1">
            ${road}
        </span>

        <input type="hidden"
               name="priority_roads[]"
               value="${road}">
    `;

    document.getElementById("priorityCard").style.display = "block";

    updatePriorityCardVisibility();
});

document.getElementById("new_road_name_nv")?.addEventListener("input", function () {

    const road = this.value.trim();

    let item = document.getElementById("newRoadPriorityItem");

    if (!road) {
        if (item) item.remove();
        updatePriorityCardVisibility();
        return;
    }

    if (!item) {
        item = document.createElement("div");
        item.id = "newRoadPriorityItem";
        item.className = "list-group-item d-flex align-items-center";

        document.getElementById("roadPriorityList").appendChild(item);
    }

    item.innerHTML = `
        <i class="fa fa-grip-vertical me-3 text-secondary"></i>

        <span class="fw-bold flex-grow-1">
            ${road}
        </span>

        <input type="hidden"
               name="priority_roads[]"
               value="${road}">
    `;

    document.getElementById("priorityCard").style.display = "block";

    updatePriorityCardVisibility();
});


document.getElementById("closeNewAssetsForm").addEventListener("click", function () {

    const newRoadItem = document.getElementById("newRoadPriorityItem");

    if (newRoadItem) {
        newRoadItem.remove();
    }

    updatePriorityCardVisibility();
	calculateTotalRoadLength();

});

new Sortable(
    document.getElementById("roadPriorityList"),
    {
        animation: 150,
        ghostClass: "bg-light"
    }
);


refAssetTableBody.addEventListener("click", (e) => {
    if (e.target.classList.contains("deleteRowBtn")) {

        const road = e.target
            .closest("tr")
            .querySelector('input[name="roads[]"]').value;

        const priorityItem = document.querySelector(
            `#roadPriorityList [data-road="${road}"]`
        );

        if (priorityItem) {
            priorityItem.remove();
        }

        updatePriorityCardVisibility();

        e.target.closest("tr").remove();
		calculateTotalRoadLength();
        rowIndex--;
        if (rowIndex < 0) rowIndex = 0;

        document.getElementById("rowCount").value = rowIndex;

    }
});

document.querySelectorAll('input[name="ref_asset"]').forEach((radio) => {
    radio.addEventListener("change", () => {
        // ✅ Clear table body
        refAssetTableBody.innerHTML = "";

        // ✅ Reset row index
        rowIndex = 0;
        document.getElementById("rowCount").value = 0;

        document.getElementById("roadPriorityList").innerHTML = "";

        document.getElementById("new_road_name").value = "";
        document.getElementById("road_length").value = "";

        document.getElementById("road_category").selectedIndex = 0;
        document.getElementById("road_type").selectedIndex = 0;
        document.getElementById("road_owner").selectedIndex = 0;

        // Hide priority card
        document.getElementById("priorityCard").style.display = "none";

        const kmlFile = document.getElementById("road_kml_file");
        if (kmlFile) {
            kmlFile.value = "";
        }

        const removeBtn = document.getElementById("removeBtn_road_kml_file");
        if (removeBtn) {
            removeBtn.style.display = "none";
        }

        // ✅ Optional: clear inputs
        txt_start_chainage.value = "";
        txt_end_chainage.value = "";

        // ✅ Optional: reset selects
        refRoadSelect.selectedIndex = 0;

        [
            "refCulvertSelect",
            "refBridgeSelect",
            "refWallSelect",
        ].forEach((id) => {
            const el = document.getElementById(id);
            if (el) {
                el.selectedIndex = -1;
                el.innerHTML = '<option value="">-- NA --</option>';
            }
        });
    });
});

let mnt_rowIndex = 0;

async function loadChainage(type) {
    const existRoad = getExistingRoad();
    if (!existRoad) return;

    try {
        const data = await fetchJson(`/project-management/get-road-length/${existRoad}`);
        startChainage_from = parseFloat(data.chainage_from);
        startChainage_to = parseFloat(data.chainage_to);

        const msg = `Chainage should be between ${startChainage_from} - ${startChainage_to}`;

        if (type === "upg") {
            const msgEl = document.getElementById("txt_chainage_msg");
            if (msgEl) msgEl.textContent = msg;

            const startInput = document.getElementById("txt_start_chainage");
            const endInput = document.getElementById("txt_end_chainage");

            if (startInput) startInput.value = startChainage_from.toFixed(3);
            if (endInput) endInput.value = startChainage_to.toFixed(3);

            updateReferenceData();
        }
    } catch (error) {
        console.error("Error fetching road length:", error);
    }
}

function safeUpdateMaintenanceData(type) {
    if (type === "upg") {
        const roadId = document.getElementById("refRoadSelect").value;
        const startInput = document.getElementById("txt_start_chainage");
        const endInput = document.getElementById("txt_end_chainage");

        const start = parseFloat(startInput.value);
        const end = parseFloat(endInput.value);

        const assetSelects = [
            "refCulvertSelect",
            "refBridgeSelect",
            "refWallSelect",
        ];

        assetSelects.forEach((id) => {
            const sel = document.getElementById(id);
            if (sel) {
                sel.innerHTML = '<option value="">-- NA --</option>';
            }
        });

        if (!roadId) {
            return;
        }
        if (isNaN(start) || isNaN(end)) {
            return;
        }
        if (start > end) {
            return;
        }

        updateReferenceData();
    }
}

document
    .getElementById("refRoadSelect")
    .addEventListener("input", () => safeUpdateMaintenanceData("upg"));
document
    .getElementById("txt_start_chainage")
    .addEventListener("input", () => safeUpdateMaintenanceData("upg"));
document
    .getElementById("txt_end_chainage")
    .addEventListener("input", () => safeUpdateMaintenanceData("upg"));

function updateReferenceData() {
    const roadId = document.getElementById("refRoadSelect").value;
    const start = document.getElementById("txt_start_chainage").value;
    const end = document.getElementById("txt_end_chainage").value;

    loadCulverts(roadId, start, end, "refCulvertSelect");
    loadBridges(roadId, start, end, "refBridgeSelect");
    loadWalls(roadId, start, end, "refWallSelect");
}

async function loadCulverts(roadId, start, end, container) {
    if (!roadId || !start || !end) return;

    let apiUrl = `/project-management/get-culverts?roadId=${roadId}&start=${start}&end=${end}`;

    try {
        const res = await fetch(apiUrl);
        if (!res.ok) throw new Error("Network response was not ok");
        const data = await res.json();

        const culvertSelect = document.getElementById(container);
        culvertSelect.innerHTML = `<option value="">-- NA --</option>`;
        data.forEach((c) => {
            culvertSelect.innerHTML += `<option value="${c.rd_cdwork_cd}">${c.culvert_no}</option>`;
        });
    } catch (err) {
        console.error("Error loading culverts:", err);
    }
}

async function loadBridges(roadId, start, end, container) {
    if (!roadId || !start || !end) return;

    let apiUrl = `/project-management/get-bridges?roadId=${roadId}&start=${start}&end=${end}`;

    try {
        const res = await fetch(apiUrl);
        if (!res.ok) throw new Error("Network response was not ok");
        const data = await res.json();

        const bridgeSelect = document.getElementById(container);
        bridgeSelect.innerHTML = `<option value="">-- NA --</option>`;
        data.forEach((b) => {
            bridgeSelect.innerHTML += `<option value="${b.rd_bridge_cd}">${b.bridge_name}</option>`;
        });
    } catch (err) {
        console.error("Error loading bridges:", err);
    }
}

async function loadWalls(roadId, start, end, container) {
    if (!roadId || !start || !end) return;

    let apiUrl = `/project-management/get-walls?roadId=${roadId}&start=${start}&end=${end}`;

    try {
        const res = await fetch(apiUrl);
        if (!res.ok) throw new Error("Network response was not ok");
        const data = await res.json();

        const wallSelect = document.getElementById(container);
        wallSelect.innerHTML = `<option value="">-- NA --</option>`;
        data.forEach((w) => {
            wallSelect.innerHTML += `<option value="${w.protection_wall_cd}">${w.protection_wall_cd}</option>`;
        });
    } catch (err) {
        console.error("Error loading walls:", err);
    }
}

function restrictInput(event) {
    const input = event.target;
    let value = input.value;
    let cursorPos = input.selectionStart;

    // Remove invalid characters but allow digits and dot
    let newValue = "";
    let dotCount = 0;

    for (let i = 0; i < value.length; i++) {
        const char = value[i];
        if (char >= "0" && char <= "9") {
            newValue += char;
        } else if (char === "." && dotCount === 0) {
            newValue += ".";
            dotCount++;
        } else {
            if (i < cursorPos) cursorPos--;
        }
    }

    // Restrict decimals to 3
    if (newValue.includes(".")) {
        const [intPart, decPart] = newValue.split(".");
        newValue = intPart + "." + decPart.slice(0, 3);
    }

    // Only assign if different to avoid cursor jump
    if (newValue !== input.value) {
        input.value = newValue;
        // Correct cursor if necessary
        if (cursorPos > newValue.length) cursorPos = newValue.length;
        input.setSelectionRange(cursorPos, cursorPos);
    }
}

document.getElementById("road_length").addEventListener("input", function () {
    calculateTotalRoadLength();
});

document.getElementById("road_length_nv")?.addEventListener("input", function () {
    calculateTotalRoadLength();
});
