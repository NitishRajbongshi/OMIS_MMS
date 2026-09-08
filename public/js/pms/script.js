//New File created by Pulak : 14-10-2025 - 18:28

// --- DOM Elements ---
const adNoOfAssetsBtn = document.getElementById("adNoOfAssetsBtn");
const slnewRd = document.getElementById("slnewRd");
const slnewRdNew = document.getElementById("slnewRdNew");
const rdLength = document.getElementById("rdLength");
const division_id = document.getElementById("division_cd");
const sub_division_id = document.getElementById("sub_division_cd");
const noOfAssetsTable = document.querySelector("#noOfAssetsTableBody");
const opt1 = document.getElementById("opt1");
// Asset Input IDs

// Container IDs for main form
const mainAssetContainers = {
    //
};


// --- State Variables ---
let startChainage_from = 0;
let startChainage_to = 0;

// --- Configuration ---
const assetConfig = [
    {
        key: "culvert",
        id: "noOfCls",
        div: "#new_culvert",
        label: "Culvert",
        name: "culverts",
        type: "Culvert",
    },
    {
        key: "bridge",
        id: "noOfBrs",
        div: "#new_bridge",
        label: "Bridge",
        name: "bridges",
        type: "Bridge",
    },
    {
        key: "rtw",
        id: "noOfrtws",
        div: "#new_rtw",
        label: "Retain Wall",
        name: "rtws",
        type: "Retaining Wall",
    },
];

// --- Utility Functions ---

async function fetchJson(url) {
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Network error: ${response.statusText}`);
    return response.json();
}

function getExistingRoad() {
    const refRoad = document.getElementById("refRoadSelect");
    const maintRoad = document.getElementById("maintRoads");
    return (slnewRd && slnewRd.value) || refRoad?.value || maintRoad?.value;
}




async function populateMaintenanceAssetsSubDiv(sub_division_cd,maintRoads,maintCulverts,maintBridges,maintWalls,maintPv) {
    if (!sub_division_cd) return;

    try {
        const data = await fetchJson(
            "/project-management/get-maintenance-assets-subdivision/" + sub_division_cd
        );


        const roadSelect = document.getElementById(maintRoads);
        roadSelect.innerHTML = `<option value="">-- NA --</option>`;

        data.roads.forEach((road) => {
            roadSelect.add(
                    new Option(
                        `${road.rd_name} (${road.rd_number})`,
                    road.rd_system_id
                )
                );
            });

        // ------------------ BRIDGES ------------------
        const bridgeSelect = document.getElementById(maintBridges);
        bridgeSelect.innerHTML = `<option value="">-- NA --</option>`;

        data.bridges.forEach((b) => {
            bridgeSelect.innerHTML += `
                <option value="${b.rd_bridge_cd}">
                    ${b.bridge_name}
                </option>`;
        });

        // ------------------ WALLS ------------------
        const wallSelect = document.getElementById(maintWalls);
        wallSelect.innerHTML = `<option value="">-- NA --</option>`;

        data.walls.forEach((w) => {
            wallSelect.innerHTML += `
                <option value="${w.protection_wall_cd}">
                    ${w.protection_wall_cd}
                </option>`;
        });

        // ------------------ CULVERTS ------------------
        const culvertSelect = document.getElementById(maintCulverts);
        culvertSelect.innerHTML = `<option value="">-- NA --</option>`;

        data.culverts.forEach((c) => {
            culvertSelect.innerHTML += `
                <option value="${c.rd_cdwork_cd}">
                    ${c.culvert_no}
                </option>`;
        });

    } catch (error) {
        console.error("Error loading assets:", error);
    }
}




async function populateRoadSelectSubDiv(sub_division_cd, selectId) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.options.length = 0;

    select.innerHTML = '<option value="">-- Select Road --</option>';
    if (!sub_division_cd) return;

    try {
        const data = await fetchJson(
            "/project-management/get-roads-subdivision/" + sub_division_cd,
        );
        select.options.length = 0;
        select.innerHTML = '<option value="">-- Select Road --</option>';
        if (Array.isArray(data) && data.length > 0) {
            data.forEach((road) => {
                select.add(
                    new Option(
                        `${road.rd_name} (${road.rd_number})`,
                        road.rd_number,
                    ),
                );
            });
        } else {
            select.innerHTML = '<option value="">No roads found</option>';
        }
    } catch (error) {
        console.error("Error loading roads:", error);
        select.innerHTML = '<option value="">Error loading roads</option>';
    }
}

async function populateRoadSelect(division_cd, selectId) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.innerHTML = '<option value="">-- Select Road --</option>';
    if (!division_cd) return;

    try {
        const data = await fetchJson("/project-management/get-roads/" + division_cd);
        if (Array.isArray(data) && data.length > 0) {
            data.forEach((road) => {
                select.add(
                    new Option(
                        `${road.rd_name} (${road.rd_number})`,
                        road.rd_number,
                    ),
                );
            });
        } else {
            select.innerHTML = '<option value="">No roads found</option>';
        }
    } catch (error) {
        console.error("Error loading roads:", error);
        select.innerHTML = '<option value="">Error loading roads</option>';
    }
}

function loadSubDivisions(selectedDivision, preselectedSubDivision = null, callback = null, lockDropdown = false) {
    if (!selectedDivision) return;

    $.ajax({
        url: "/asset-management/getSubDivisionList",
        type: "GET",
        data: { division: selectedDivision },
        success: function (data) {
            var dropdown = $("#sub_division_cd");
            dropdown.empty();
            dropdown.append('<option value="">Choose One</option>');

            $.each(data, function (index, value) {
                dropdown.append(
                    '<option value="' +
                        value.sub_div_cd +
                        '">' +
                        value.sub_div_name +
                        "</option>"
                );
            });

            if (preselectedSubDivision) {
                dropdown.val(preselectedSubDivision);
                if (lockDropdown) {
                    $("#sub_division_cd")
                        .val(preselectedSubDivision)
                        .prop("disabled", true);

                    if ($("#hidden_sub_division_cd").length === 0) {
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'hidden_sub_division_cd',
                            name: 'sub_division_cd',
                            value: preselectedSubDivision
                        }).appendTo('form');
                    }
                }
            }

            // Fire the callback after preselecting
            if (typeof callback === "function") callback();
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
    });
}


$("#division_cd").on("change", function () {
    const division_cd = $(this).val();
    loadSubDivisions(division_cd);
});

// Trigger on page load if division is preselected
$(document).ready(function () {
    const divisionSelect = document.getElementById("division_cd");
    const preselectedDivision = divisionSelect.value;
    const preselectedSubDivision = divisionSelect.dataset.userSubdivision;

    if (preselectedDivision) {
        loadSubDivisions(preselectedDivision, preselectedSubDivision, () => {
            if (preselectedSubDivision) {
                sub_division_id.value = preselectedSubDivision;
                sub_division_id.dispatchEvent(new Event("change"));
            }
       },
            true
        );
    }
});



// --- Event Listeners: Dropdowns ---

division_id.addEventListener("change", function () {
    const divCd = this.value;
    populateRoadSelect(divCd, "slnewRd");
});

sub_division_id.addEventListener("change", function () {
    const subdivCd = this.value;
    populateRoadSelectSubDiv(subdivCd, "refRoadSelect");
    populateMaintenanceAssetsSubDiv(subdivCd, "maintRoads","maintCulverts","maintBridges","maintWalls","maintPv");
});



function getChainageInputHtml(i, start, min, max, label, prefix, nameAttr) {
    return `
        <div class="myTooltip ${nameAttr ? "" : "col-md-3 mb-6"}">
            <div class="tooltiptext">
                <i class="fa fa-info-circle me-1"></i>
                Chainage should be between ${min} - ${max}
            </div>
            <label class="form-label ${nameAttr ? "form-label-xs" : "small"} mb-1">
                ${label} ${i} - Start Chainage:
                <span class="star text-danger">*</span>
            </label>
            <input
                type="number"
                step="0.001"
                name="${nameAttr || prefix + "_start[]"}"
                id="${nameAttr ? "" : prefix + i + "_start"}"
                min="${min}"
                max="${max}"
                class="form-control ${nameAttr ? "form-control-xs chainage-input" : "form-control-sm"}"
                value="${start}"
                placeholder="0.000"
                ${nameAttr ? `data-type="${label}" data-index="${i}" data-min="${min}" data-max="${max}"` : 'oninput="restrictDecimalPoints(event)"'}
            >
        </div>`;
}

// Generate fields for the MAIN form (before adding to table)
function generateChainageFields(count, containerId, labelName, prefix) {
    const container = document.getElementById(containerId);
    if (!container) return;
    container.innerHTML = "";

    let min = 0,
        max = 0;

    if (opt1.checked) {
        max = parseFloat(rdLength.value) || 0; // Dynamic based on manual input
        // Note: New Road ID (slnewRdNew) isn't used for range validation in original code logic for Opt1
    } else {
        return;
    }

    let html = "";
    for (let i = 1; i <= count; i++) {
        html += getChainageInputHtml(i, "", min, max, labelName, prefix, null);
    }
    container.innerHTML = html;
}

// Generate fields for the TABLE ROW (Sub-assets)
function generateSubAssetChainageFields(
    containerEl,
    noOfAsset,
    min,
    max,
    prefix,
    label,
    dataType,
    initialValues,
) {
    if (!containerEl) return;

    // Logic:
    // 1. If initialValues are passed (from Main Form), use them.
    // 2. Else, try to find existing inputs inside the container (preserving data when changing count inside the table).
    let sourceValues = [];

    if (initialValues && Array.isArray(initialValues)) {
        sourceValues = initialValues;
    } else {
        const existingInputs = containerEl.querySelectorAll("input");
        sourceValues = Array.from(existingInputs).map((input) => input.value);
    }

    let html = "";
    for (let i = 1; i <= noOfAsset; i++) {
        const val = sourceValues[i - 1] || "";
        // Note: nameAttr passed as `${label}_start[]` matches original logic
        html += getChainageInputHtml(
            i,
            val,
            min,
            max,
            dataType,
            prefix,
            `${label}_start[]`,
        );
    }
    containerEl.innerHTML = html;
}

// --- Validation Functions ---

function validateChainages(prefix, count, label, start_chainage, end_chainage) {
    let prevStart = null;

    // Handle both Main Form IDs (prefix + i + _start) and Table Class inputs
    // This function specifically targeted the Main Form in original code
    for (let i = 1; i <= count; i++) {
        const inputId = `${prefix}${i}_start`;
        const input = document.getElementById(inputId);
        if (!input) continue;

        const start = parseFloat(input.value);

        if (start < start_chainage || start > end_chainage) {
            alert(
                `${label} ${i} start chainage (${start}) is out of range (${start_chainage} – ${end_chainage}).`,
            );
            input.focus();
            return false;
        }

        if (prevStart !== null && start <= prevStart) {
            alert(
                `${label} ${i} start chainage (${start}) should be greater than ${label} ${i - 1} start chainage (${prevStart}).`,
            );
            input.focus();
            return false;
        }
        prevStart = start;
    }
    return true;
}

function attachChainageValidation(type, container) {
    const inputs = container.querySelectorAll(
        `.chainage-input[data-type="${type}"]`,
    );

    inputs.forEach((input) => {
        input.dataset.initialValue = input.value;

        input.addEventListener("focus", function () {
            this.dataset.initialValue = this.value;
        });

        input.addEventListener("blur", function () {
            const value = parseFloat(this.value);
            const min = parseFloat(this.dataset.min);
            const max = parseFloat(this.dataset.max);
            const index = parseInt(this.dataset.index);

            if (isNaN(value)) {
                this.setCustomValidity("");
                this.style.borderColor = "";
                return;
            }

            // Range check
            if (value < min || value > max) {
                this.setCustomValidity(
                    `Chainage ${index} must be between ${min} and ${max}.`,
                );
                this.reportValidity();
                this.style.borderColor = "red";
                this.value = this.dataset.initialValue;
                return;
            }

            // Sequential check
            if (index > 1) {
                const prevInput = container.querySelector(
                    `.chainage-input[data-type="${type}"][data-index="${index - 1}"]`,
                );
                if (prevInput) {
                    const prevValue = parseFloat(prevInput.value);
                    if (!isNaN(prevValue) && value <= prevValue) {
                        this.setCustomValidity(
                            `Chainage ${index} must be greater than Chainage ${index - 1}.`,
                        );
                        this.reportValidity();
                        this.style.borderColor = "red";
                        this.value = this.dataset.initialValue;
                        return;
                    }
                }
            }

            this.setCustomValidity("");
            this.style.borderColor = "green";
            this.dataset.initialValue = this.value;
        });
    });
}

// --- Main Form Event Listeners ---



function sFormValues(prefix, count) {
    const values = [];
    for (let i = 1; i <= count; i++) {
        // IDs in main form are formatted like: culvert1_start, bridge2_start
        const input = document.getElementById(`${prefix}${i}_start`);
        values.push(input ? input.value : "");
    }
    return values;
}

// --- ADD ROW LOGIC ---

adNoOfAssetsBtn.addEventListener("click", async () => {
    let roadNameVal = "";
    let roadLengthVal = 0;
    let minChain = 0;
    let maxChain = 0;
    let isNewRoad = false;

    // --- 1. Determine Context & Validate Road (Same as before) ---
    if (opt1.checked) {
        const roadInput = document.getElementById("slnewRdNew");
        roadNameVal = roadInput.value.trim();
        roadLengthVal = parseFloat(rdLength.value);

        if (!roadNameVal) return;
        if (isNaN(roadLengthVal) || roadLengthVal <= 0) {
            alert("Please enter a valid road length before adding assets.");
            return;
        }

        try {
            const data = await fetchJson(
                `/project-management/check-road-name?name=${encodeURIComponent(roadNameVal)}`,
            );
            if (data.exists) {
                roadInput.setCustomValidity("Road Name already exists");
                document.getElementById("roadNameError").textContent =
                    "Road name already exists.";
                roadInput.style.borderColor = "red";
                roadInput.focus();
                return;
            } else {
                roadInput.setCustomValidity("");
                document.getElementById("roadNameError").textContent = "";
                roadInput.style.borderColor = "";
            }
        } catch (error) {
            console.error("Error checking road name:", error);
            return;
        }

        minChain = 0;
        maxChain = roadLengthVal;
        isNewRoad = true;
    }

    // --- 3. Create Table Row ---
    const tr = document.createElement("tr");

    const nameInputHtml = isNewRoad
        ? `<input type="text" name="new_rd_name" id="new_rd_name" value="${roadNameVal}" class="form-control form-control-sm" style="width:80px;">
           <small id="newRoadNameError" style="color:red;"></small>`
        : `<input type="text" name="exist_road" id="exist_road" value="${roadNameVal}" hidden>${roadNameVal}`;

    const lengthInputHtml = isNewRoad
        ? `<input type="number" name="rd_length" id="rd_length" value="${roadLengthVal}" class="form-control form-control-sm" min="0" style="width:80px;">`
        : `<input type="number" name="rd_length" id="rd_length" value="${roadLengthVal}" hidden>${roadLengthVal}`;

    tr.innerHTML = `
        <td>${nameInputHtml}</td>
        <td>${lengthInputHtml}</td>
        <td>
            <input type="number" name="noOfCls" id="noOfCls" value="${counts.culvert}" class="form-control form-control-sm" min="0" style="width:80px;">
        </td>
        <td>
            <input type="number" name="noOfBrs" id="noOfBrs" value="${counts.bridge}" class="form-control form-control-sm" min="0" style="width:80px;">
        </td>
        <td>
            <input type="number" name="noOfrtws" id="noOfrtws" value="${counts.rtw}" class="form-control form-control-sm" min="0" style="width:80px;">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger deleteRowBtn">❌</button>
        </td>
    `;

    noOfAssetsTable.appendChild(tr);

    // --- 4. Initialize Sub-Asset Fields and Listeners inside the new Row ---
    const lengthInput = tr.querySelector("#rd_length");

    if (isNewRoad) {
        const rowRoadInput = tr.querySelector("#new_rd_name");
        const rowError = tr.querySelector("#newRoadNameError");

        rowRoadInput.addEventListener("input", function () {
            const name = this.value.trim();
            if (!name) return;
            fetch(`/project-management/check-road-name?name=${encodeURIComponent(name)}`)
                .then((r) => r.json())
                .then((data) => {
                    if (data.exists) {
                        this.setCustomValidity("Project Name already exist");
                        rowError.textContent = "Road name already exists.";
                        this.style.borderColor = "red";
                    } else {
                        this.setCustomValidity("");
                        rowError.textContent = "";
                        this.style.borderColor = "";
                    }
                })
                .catch((e) => console.error(e));
        });
    }

    assetConfig.forEach((cfg) => {
        const inputEl = tr.querySelector(`#${cfg.id}`);
        const divEl = tr.querySelector(cfg.div);

        // --- FIX IS HERE ---
        // 1. Get values from the Main Form Inputs
        const initialFormValues = getMainFormValues(
            cfg.key,
            parseInt(inputEl.value) || 0,
        );

        // 2. Pass them to the generator
        generateSubAssetChainageFields(
            divEl,
            parseInt(inputEl.value) || 0,
            minChain,
            maxChain,
            cfg.key,
            cfg.name,
            cfg.type,
            initialFormValues,
        );

        attachChainageValidation(cfg.type, divEl);

        // Change listener for count input inside the table (clears values or keeps existing TABLE values)
        inputEl.addEventListener("change", function () {
            const currentLen = isNewRoad
                ? parseInt(lengthInput.value) || 0
                : maxChain;
            // Note: We do NOT pass initialFormValues here, so it uses values currently in the table row
            generateSubAssetChainageFields(
                divEl,
                parseInt(this.value) || 0,
                minChain,
                currentLen,
                cfg.key,
                cfg.name,
                cfg.type,
            );
            attachChainageValidation(cfg.type, divEl);
        });
    });

    if (isNewRoad) {
        lengthInput.addEventListener("change", function () {
            const newLen = parseInt(this.value) || 0;
            assetConfig.forEach((cfg) => {
                const inputEl = tr.querySelector(`#${cfg.id}`);
                const divEl = tr.querySelector(cfg.div);
                generateSubAssetChainageFields(
                    divEl,
                    parseInt(inputEl.value) || 0,
                    0,
                    newLen,
                    cfg.key,
                    cfg.name,
                    cfg.type,
                );
                attachChainageValidation(cfg.type, divEl);
            });
        });
    }
});

// Delete Row Handler
noOfAssetsTable.addEventListener("click", function (e) {
    if (e.target.classList.contains("deleteRowBtn")) {
        e.target.closest("tr").remove();
    }
});

projectTypeSelect.addEventListener("change", toggleSectionsBasedOnProjectType);
toggleSectionsBasedOnProjectType();

//Add Items of work
// --- Grab Elements ---
const qtyInput = document.getElementById("workQtyInput");
const startDateInput = document.getElementById("estimateStartDate");
const endDateInput = document.getElementById("estimateEndDate");
const addBtn = document.getElementById("addWorkItemBtn");
const tableBody = document.querySelector("#workItemsTable tbody");
const dropdownBtn = document.getElementById("predecessorDropdown");
const predecessorContainer = document.getElementById("predecessorOptions");

// --- Helper: Update Predecessor Dropdown ---
function updatePredecessorOptions() {
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    if (rows.length === 0) {
        predecessorContainer.innerHTML =
            "<p class='text-muted small'>No items available</p>";
        return;
    }

    // Generate HTML string map for better performance than appending nodes in loop
    predecessorContainer.innerHTML = rows
        .map((row) => {
            const val = row.querySelector("input[name='work_items[]']").value;
            const name = row.querySelector("td:first-child").innerText.trim();
            return `
            <label class="d-block">
                <input type="checkbox" value="${val}">
                <span class="ms-2">${name}</span>
            </label>`;
        })
        .join("");
}

// --- Helper: Generate Row HTML ---
function createRowHtml(
    val,
    name,
    qty,
    startDate,
    endDate,
    preds,
    predVals,
    rowIndex,
) {
    // Generate hidden inputs for predecessors
    const predInputs =
        predVals.length > 0
            ? predVals
                  .map(
                      (v) =>
                          `<input type="hidden" name="predecessorSelect[${rowIndex}][]" value="${v}">`,
                  )
                  .join("")
            : `<input type="hidden" name="predecessorSelect[${rowIndex}][]" value="">`;

    return `
        <tr>
            <td>
                <input type="hidden" name="work_items[]" value="${val}">
                ${name}
            </td>
            <td>
                <input type="number" class="form-control form-control-sm" min="0" name="work_qtys[]" value="${qty}">
            </td>
            <td>
                ${preds.join(", ") || "N/A"}
                ${predInputs}
            </td>
            <td>
                <input type="date" name="estimateStartDate[]" value="${startDate}">
            </td>
            <td>
                <input type="date" name="estimateEndDate[]" value="${endDate}">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-primary btn-sm subItemBtn">+ Sub Items</button>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger deleteRowBtn" data-val="${val}">❌</button>
            </td>
        </tr>`;
}

// --- Helper: Reset Input Form ---
function resetForm() {
    qtyInput.value = "";
    startDateInput.value = "";
    endDateInput.value = "";
    workSelect.selectedIndex = 0;
    dropdownBtn.textContent = "Select Predecessor";

    // Uncheck all predecessor checkboxes
    predecessorContainer
        .querySelectorAll("input[type='checkbox']")
        .forEach((cb) => (cb.checked = false));
}
