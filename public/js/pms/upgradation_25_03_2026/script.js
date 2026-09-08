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


    if (culverts.length === 0 && bridges.length === 0 && walls.length === 0) {
        console.log("No assets selected → stopping row creation");
        alert("No assets selected.");
        return;
    }

    const startChainage = txt_start_chainage.value.trim();
    const endChainage = txt_end_chainage.value.trim();

    if (!road || !startChainage || !endChainage) {
        alert("Please select a road and enter chainages.");
        return;
    }

    // Unique index for each asset row
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
            <button type="button" class="btn btn-sm btn-danger deleteRowBtn">Delete</button>
        </td>
    `;

    refAssetTableBody.appendChild(tr);

    // Reset input fields
    refRoadSelect.selectedIndex = 0;
    refCulvertSelect.selectedIndex = -1;
    refBridgeSelect.selectedIndex = -1;
    refWallSelect.selectedIndex = -1;
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

const txt_start_chainage_mnt = document.getElementById(
    "txt_start_chainage_mnt",
);
const txt_end_chainage_mnt = document.getElementById("txt_end_chainage_mnt");
const roads = document.getElementById("maintRoads");
const maintCulverts = document.getElementById("maintCulverts");
const maintBridges = document.getElementById("maintBridges");
const maintWalls = document.getElementById("maintWalls");
let mnt_rowIndex = 0;
//Add to maintain the Assests
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
            <button type="button" class="btn btn-sm btn-danger deleteRowBtn">Delete</button>
        </td>
    `;

    document.querySelector("#mtnAssetTable tbody").appendChild(tr);

    // Reset fields
    txt_start_chainage_mnt.value = "";
    txt_end_chainage_mnt.value = "";
    roads.selectedIndex = 0;
    maintCulverts.selectedIndex = -1;
    maintBridges.selectedIndex = -1;
    maintWalls.selectedIndex = -1;

    // Delete row event
    tr.querySelector(".deleteRowBtn").addEventListener("click", () => {
        tr.remove();
    });
}

function updateMaintenanceData() {
    const roadId = document.getElementById("maintRoads").value;
    const start = document.getElementById("txt_start_chainage_mnt").value;
    const end = document.getElementById("txt_end_chainage_mnt").value;

    loadCulverts(roadId, start, end, "maintCulverts");
    loadBridges(roadId, start, end, "maintBridges");
    loadWalls(roadId, start, end, "maintWalls");
    loadPavements(roadId, start, end, "maintPv");
}

// Add event listeners in one line each
document
    .getElementById("maintRoads")
    .addEventListener("input", updateMaintenanceData);
document
    .getElementById("txt_start_chainage_mnt")
    .addEventListener("input", updateMaintenanceData);
document
    .getElementById("txt_end_chainage_mnt")
    .addEventListener("input", updateMaintenanceData);

function updateReferenceData() {
    const roadId = document.getElementById("refRoadSelect").value;
    const start = document.getElementById("txt_start_chainage").value;
    const end = document.getElementById("txt_end_chainage").value;

    loadCulverts(roadId, start, end, "refCulvertSelect");
    loadBridges(roadId, start, end, "refBridgeSelect");
    loadWalls(roadId, start, end, "refWallSelect");
    loadPavements(roadId, start, end, "refPVSelect");
}

document
    .getElementById("refRoadSelect")
    .addEventListener("input", updateReferenceData);
document
    .getElementById("txt_start_chainage")
    .addEventListener("input", updateReferenceData);
document
    .getElementById("txt_end_chainage")
    .addEventListener("input", updateReferenceData);

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
            culvertSelect.innerHTML += `<option value="${c.culvert_no}">${c.culvert_no}</option>`;
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
