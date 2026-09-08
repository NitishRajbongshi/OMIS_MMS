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
const refPVSelect = document.getElementById("refPVSelect");
const txt_start_chainage = document.getElementById("txt_start_chainage");
const txt_end_chainage = document.getElementById("txt_end_chainage");

const refAssetTableBody = document.querySelector("#refAssetTable tbody");


document.getElementById("refRoadSelect")?.addEventListener("change", () => {
    loadChainage('upg');
});

document.getElementById("maintRoads")?.addEventListener("change", () => {
    loadChainage('maint');
});

let rowIndex = 0;

addRefAssetBtn.addEventListener("click", () => {
    let val_start = parseFloat(txt_start_chainage.value);
    let val_end = parseFloat(txt_end_chainage.value);

    if (val_start < startChainage_from || val_start > startChainage_to) {
        alert("Enter a valid start chainage");
        txt_start_chainage.focus();
        return;
    }

    if (
        val_end < startChainage_from ||
        val_end > startChainage_to ||
        val_end <= val_start
    ) {
        alert("Enter a valid end chainage");
        txt_end_chainage.focus();
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

    const pavements = Array.from(refPVSelect.selectedOptions)
        .map((opt) => opt.value)
        .filter((val) => val !== "");



    if (culverts.length === 0 && bridges.length === 0 && walls.length === 0 && pavements.length === 0) {
        alert("No assets selected.");
        return;
    }

    const startChainage = txt_start_chainage.value.trim();
    const endChainage = txt_end_chainage.value.trim();

    if (!road || !startChainage || !endChainage) {
        alert("Please select a road and enter chainages.");
        return;
    }

    rowIndex++;

    document.getElementById("rowCount").value = rowIndex;

    const tr = document.createElement("tr");

    tr.innerHTML = `
        <td>
            <input type="hidden" name="roads[]" value="${road}">
            ${road}
        </td>

        <td>
            <input type="hidden" name="start_chainage[]" value="${startChainage}">
            ${startChainage}
        </td>

        <td>
            <input type="hidden" name="end_chainage[]" value="${endChainage}">
            ${endChainage}
        </td>

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
            ${pavements
                .map(
                    (p) => `
                <input type="hidden" name="pavements_${rowIndex}[]" value="${p}">
                <span class="badge bg-secondary me-1">${p}</span>
            `,
                )
                .join("")}
        </td>

        <td>
            <button type="button" class="btn btn-sm btn-danger deleteRowBtn">Delete</button>
        </td>
    `;

    refAssetTableBody.appendChild(tr);

    refRoadSelect.selectedIndex = 0;
    ["refCulvertSelect", "refBridgeSelect", "refWallSelect", "refPVSelect"].forEach(id => {
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

refAssetTableBody.addEventListener("click", (e) => {
    if (e.target.classList.contains("deleteRowBtn")) {
        e.target.closest("tr").remove();
        rowIndex--;
        if (rowIndex < 0) rowIndex = 0;

        document.getElementById("rowCount").value = rowIndex;
    }
});

const txt_start_chainage_mnt = document.getElementById("txt_start_chainage_mnt",);
const txt_end_chainage_mnt = document.getElementById("txt_end_chainage_mnt");
const roads = document.getElementById("maintRoads");
const maintCulverts = document.getElementById("maintCulverts");
const maintBridges = document.getElementById("maintBridges");
const maintWalls = document.getElementById("maintWalls");
const maintPv = document.getElementById("maintPv");
let mnt_rowIndex = 0;

function addMaintenanceAssets() {
    let val_start = parseFloat(txt_start_chainage_mnt.value);
    let val_end = parseFloat(txt_end_chainage_mnt.value);

    // Validate start chainage
    if (val_start < startChainage_from || val_start > startChainage_to) {
        alert("Enter a valid start chainage");
        txt_start_chainage_mnt.focus();
        return;
    }

    // Validate end chainage
    if (
        val_end < startChainage_from ||
        val_end > startChainage_to ||
        val_end <= val_start
    ) {
        alert("Enter a valid end chainage");
        txt_end_chainage_mnt.focus();
        return;
    }

    const road = roads.value;

    const culverts = Array.from(maintCulverts.selectedOptions).map(
        (opt) => opt.value,
    );
    const bridges = Array.from(maintBridges.selectedOptions).map(
        (opt) => opt.value,
    );
    const walls = Array.from(maintWalls.selectedOptions).map(
        (opt) => opt.value,
    );

    const pavements = Array.from(maintPv.selectedOptions).map(
        (opt) => opt.value,
    );


    if (culverts.length === 0 && bridges.length === 0 && walls.length === 0 && pavements.length === 0) {
        alert("No assets selected.");
        return;
    }

    const startChainage = txt_start_chainage_mnt.value.trim();
    const endChainage = txt_end_chainage_mnt.value.trim();

    if (!road || !startChainage || !endChainage) {
        alert("Please select a road and enter chainages.");
        return;
    }

    // Unique index for each asset row
    mnt_rowIndex++;
    document.getElementById("mnt_rowCount").value = mnt_rowIndex;

    const tr = document.createElement("tr");

    tr.innerHTML = `
        <td>
            <input type="hidden" name="roads_mnt[]" value="${road}">
            ${road}
        </td>

        <td>
            <input type="hidden" name="start_chainage_mnt[]" value="${startChainage}">
            ${startChainage}
        </td>

        <td>
            <input type="hidden" name="end_chainage_mnt[]" value="${endChainage}">
            ${endChainage}
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
            ${pavements
                .map(
                    (p) => `
                <input type="hidden" name="pavements_mnt_${mnt_rowIndex}[]" value="${p}">
                <span class="badge bg-secondary me-1">${p}</span>
            `,
                )
                .join("")}
        </td>

        <td>
            <button type="button" class="btn btn-sm btn-danger deleteRowBtn">Delete</button>
        </td>
    `;

    document.querySelector("#mtnAssetTable tbody").appendChild(tr);

    // Reset fields
    txt_start_chainage_mnt.value = "";
    txt_end_chainage_mnt.value = "";
    roads.selectedIndex = 0;

    ["maintCulverts", "maintBridges", "maintWalls", "maintPv"].forEach(id => {
    const el = document.getElementById(id);
        if (el) {
            el.selectedIndex = -1;
            el.value = "";
            el.innerHTML = '<option value="">-- NA --</option>';
        }
    });
    document.getElementById("txt_chainage_msg_mnt").textContent = "";

    // Delete row event
    tr.querySelector(".deleteRowBtn").addEventListener("click", () => {
        tr.remove();
    });
}

async function loadChainage(type) {
    const existRoad = getExistingRoad();
    if (!existRoad) return;

    try {
        const data = await fetchJson(`/get-road-length/${existRoad}`);
        startChainage_from = parseFloat(data.chainage_from);
        startChainage_to = parseFloat(data.chainage_to);

        const msg = `Chainage should be between ${startChainage_from} - ${startChainage_to}`;


        if (type === 'upg') {
            const msgEl = document.getElementById("txt_chainage_msg");
            if (msgEl) msgEl.textContent = msg;

            const startInput = document.getElementById("txt_start_chainage");
            const endInput = document.getElementById("txt_end_chainage");

            if (startInput) startInput.value = startChainage_from.toFixed(3);
            if (endInput) endInput.value = startChainage_to.toFixed(3);

            updateReferenceData();
        } else if (type === 'maint') {
            const msgElmnt = document.getElementById("txt_chainage_msg_mnt");
            if (msgElmnt) msgElmnt.textContent = msg;
            const startInput = document.getElementById("txt_start_chainage_mnt");
            const endInput = document.getElementById("txt_end_chainage_mnt");

            if (startInput) startInput.value = startChainage_from.toFixed(3);
            if (endInput) endInput.value = startChainage_to.toFixed(3);

            updateMaintenanceData();
        }

    } catch (error) {
        console.error("Error fetching road length:", error);
    }
}


function safeUpdateMaintenanceData(type) {
    if (type === 'upg') {
        const roadId = document.getElementById("refRoadSelect").value;
        const startInput = document.getElementById("txt_start_chainage");
        const endInput = document.getElementById("txt_end_chainage");

        const start = parseFloat(startInput.value);
        const end = parseFloat(endInput.value);

        const assetSelects = ["refCulvertSelect", "refBridgeSelect", "refWallSelect", "refPVSelect"];

        assetSelects.forEach(id => {
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

    } else if (type === 'mnt') {
        const roadId = document.getElementById("maintRoads").value;
        const startInput = document.getElementById("txt_start_chainage_mnt");
        const endInput = document.getElementById("txt_end_chainage_mnt");

        const start = parseFloat(startInput.value);
        const end = parseFloat(endInput.value);

        const assetSelects = ["maintCulverts", "maintBridges", "maintWalls", "maintPv"];

        assetSelects.forEach(id => {
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

        updateMaintenanceData();
    }
}


document.getElementById("maintRoads").addEventListener("input", () => safeUpdateMaintenanceData('mnt'));
document.getElementById("txt_start_chainage_mnt").addEventListener("input", () => safeUpdateMaintenanceData('mnt'));
document.getElementById("txt_end_chainage_mnt").addEventListener("input", () => safeUpdateMaintenanceData('mnt'));

document.getElementById("refRoadSelect").addEventListener("input", () => safeUpdateMaintenanceData('upg'));
document.getElementById("txt_start_chainage").addEventListener("input", () => safeUpdateMaintenanceData('upg'));
document.getElementById("txt_end_chainage").addEventListener("input", () => safeUpdateMaintenanceData('upg'));

function updateMaintenanceData() {
    const roadId = document.getElementById("maintRoads").value;
    const start = document.getElementById("txt_start_chainage_mnt").value;
    const end = document.getElementById("txt_end_chainage_mnt").value;

    loadCulverts(roadId, start, end, "maintCulverts");
    loadBridges(roadId, start, end, "maintBridges");
    loadWalls(roadId, start, end, "maintWalls");
    loadPavements(roadId, start, end, "maintPv");
}

function updateReferenceData() {
    const roadId = document.getElementById("refRoadSelect").value;
    const start = document.getElementById("txt_start_chainage").value;
    const end = document.getElementById("txt_end_chainage").value;

    loadCulverts(roadId, start, end, "refCulvertSelect");
    loadBridges(roadId, start, end, "refBridgeSelect");
    loadWalls(roadId, start, end, "refWallSelect");
    loadPavements(roadId, start, end, "refPVSelect");
}



async function loadCulverts(roadId, start, end, container) {
    if (!roadId || !start || !end) return;

    const projectType = document.getElementById("projectTypeSelect").value;
    let apiUrl = projectType === 'MTN'
        ? `/get-maintenance-culverts?roadId=${roadId}&start=${start}&end=${end}`
        : `/get-culverts?roadId=${roadId}&start=${start}&end=${end}`;

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

    const projectType = document.getElementById("projectTypeSelect").value;
    let apiUrl = projectType === 'MTN'
        ? `/get-maintenance-bridges?roadId=${roadId}&start=${start}&end=${end}`
        : `/get-bridges?roadId=${roadId}&start=${start}&end=${end}`;


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

    const projectType = document.getElementById("projectTypeSelect").value;
    let apiUrl = projectType === 'MTN'
        ? `/get-maintenance-walls?roadId=${roadId}&start=${start}&end=${end}`
        : `/get-walls?roadId=${roadId}&start=${start}&end=${end}`;

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

async function loadPavements(roadId, start, end, container) {
    if (!roadId || !start || !end) return;

    const projectType = document.getElementById("projectTypeSelect").value;
    let apiUrl = projectType === 'MTN'
        ? `/get-maintenance-pavements?roadId=${roadId}&start=${start}&end=${end}`
        : `/get-pavements?roadId=${roadId}&start=${start}&end=${end}`;

    try {
        const res = await fetch(apiUrl);
        if (!res.ok) throw new Error("Network response was not ok");
        const data = await res.json();

        const pvSelect = document.getElementById(container);
        pvSelect.innerHTML = `<option value="">-- NA --</option>`;
        data.forEach((p) => {
            pvSelect.innerHTML += `<option value="${p.rd_pavement_cd}">${p.rd_pavement_cd}</option>`;
        });
    } catch (err) {
        console.error("Error loading pavements:", err);
    }
}


function restrictInput(event) {
    const input = event.target;
    let value = input.value;
    let cursorPos = input.selectionStart;

    // Remove invalid characters but allow digits and dot
    let newValue = '';
    let dotCount = 0;

    for (let i = 0; i < value.length; i++) {
        const char = value[i];
        if (char >= '0' && char <= '9') {
            newValue += char;
        } else if (char === '.' && dotCount === 0) {
            newValue += '.';
            dotCount++;
        } else {
            if (i < cursorPos) cursorPos--;
        }
    }

    // Restrict decimals to 3
    if (newValue.includes('.')) {
        const [intPart, decPart] = newValue.split('.');
        newValue = intPart + '.' + decPart.slice(0, 3);
    }

    // Only assign if different to avoid cursor jump
    if (newValue !== input.value) {
        input.value = newValue;
        // Correct cursor if necessary
        if (cursorPos > newValue.length) cursorPos = newValue.length;
        input.setSelectionRange(cursorPos, cursorPos);
    }
}
