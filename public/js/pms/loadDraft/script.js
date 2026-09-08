const listDraft = document.getElementById("listDraft");
if (listDraft) {
    listDraft.addEventListener("click", async function () {
        try {
            const res = await fetch("/project-management/get-draft-list");
            const data = await res.json();

            const list = document.getElementById("draftList");
            list.innerHTML = "";

            if (data.status === "success" && data.drafts.length > 0) {
                data.drafts.forEach((draft) => {
                    const li = document.createElement("li");
                    li.classList.add(
                        "list-group-item",
                        "d-flex",
                        "justify-content-between",
                        "align-items-center",
                    );
                    li.innerHTML = `
                                    <div>
                                        <strong>${draft.project_name}</strong><br>
                                        <small>Saved on: ${draft.saved_at}</small>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-primary me-2" onclick="loadDraft('${draft.key}')">Load</button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteDraft('${draft.key}', this)">Delete</button>
                                    </div>
                                        `;
                    list.appendChild(li);
                });
            } else {
                list.innerHTML = `<li class="list-group-item text-center text-muted">No drafts available</li>`;
            }

            new bootstrap.Modal(
                document.getElementById("partialProjectsModal"),
            ).show();
        } catch (error) {
            console.error("Error fetching drafts:", error);
        }
    });
}

async function deleteDraft(draftId, button) {
    if (!draftId) return alert("No draft to delete.");
    if (!confirm("Are you sure you want to delete this draft?")) return;

    try {
        const res = await fetch(`/project-management/delete-draft/${draftId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
        });
        const data = await res.json();

        if (data.status === "success") {
            alert("Draft deleted successfully!");
            button.closest("li").remove(); // remove from list instantly
        }
    } catch (error) {
        console.error("Error deleting draft:", error);
    }
}

let currentDraftId = null;

// Helper: Get CSRF Token
const getCsrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

// Helper: Collect Structure Data
// Updated to handle both Main Form (culvert_start[]) and Table (culverts_start[]) naming conventions if needed
// But primarily targets the Table data based on your logic
const collectStructure = (label) => {
    // Label expected: 'culverts', 'bridges', etc.
    const inputs = document.querySelectorAll(`input[name="${label}_start[]"]`);
    if (inputs.length === 0) return [];

    return Array.from(inputs).map((input, index) => ({
        start: input.value || "",
        index: index + 1,
    }));
};

// Helper: Collect Asset Table Data
const collectAssetTableData = (
    tableSelector,
    roadInputName,
    startName,
    endName,
    prefix,
) => {
    const tableBody = document.querySelector(`${tableSelector} tbody`);
    if (!tableBody) return [];

    return Array.from(tableBody.querySelectorAll("tr")).map((row, i) => {
        const rowIndex = i + 1;

        // Helper to get array of values from inputs
        const getValues = (type) =>
            Array.from(
                row.querySelectorAll(
                    `input[name="${type}_${prefix}${rowIndex}[]"]`,
                ),
            ).map((el) => el.value);

        return {
            road:
                row.querySelector(`input[name="${roadInputName}[]"]`)?.value ||
                "",
            start_chainage:
                row.querySelector(`input[name="${startName}[]"]`)?.value || "",
            end_chainage:
                row.querySelector(`input[name="${endName}[]"]`)?.value || "",
            culverts: getValues("culverts"),
            bridges: getValues("bridges"),
            walls: getValues("walls"),
        };
    });
};

// --- SAVE DRAFT ---
// document
//     .getElementById("saveDraft")
//     .addEventListener("click", async function () {
//         const form = document.querySelector("#myForm");
//         if (!form) return;

//         const formData = new FormData(form);
//         const draftData = Object.fromEntries(formData);

//         // Include existing draft_id if editing
//         if (currentDraftId) draftData.draft_id = currentDraftId;

//         // Collect structure data (Culverts, Bridges, etc.)
//         // Note: This collects data from the TABLE rows because the names match (e.g., culverts_start[])
//         ["culverts", "bridges", "rtws", "pvms"].forEach((type) => {
//             draftData[type] = collectStructure(type);
//         });

//         // Collect Work Items
//         draftData.workItems = Array.from(
//             document.querySelectorAll("#workItemsTable tbody tr"),
//         ).reduce((acc, row) => {
//             const item = row
//                 .querySelector('input[name="work_items[]"]')
//                 ?.value.trim();
//             const qty = row
//                 .querySelector('input[name="work_qtys[]"]')
//                 ?.value.trim();

//             if (item && qty) {
//                 acc.push({
//                     item,
//                     qty,
//                     startDate:
//                         row.querySelector('input[name="estimateStartDate[]"]')
//                             ?.value || "",
//                     endDate:
//                         row.querySelector('input[name="estimateEndDate[]"]')
//                             ?.value || "",
//                     predecessors: Array.from(
//                         row.querySelectorAll(
//                             'input[name^="predecessorSelect"]',
//                         ),
//                     )
//                         .map((p) => p.value)
//                         .filter((v) => v !== ""),
//                 });
//             }
//             return acc;
//         }, []);

//         // Collect Assets
//         draftData.assets = collectAssetTableData(
//             "#refAssetTable",
//             "roads",
//             "start_chainage",
//             "end_chainage",
//             "",
//         );
//         draftData.assetsMnt = collectAssetTableData(
//             "#mtnAssetTable",
//             "roads_mnt",
//             "start_chainage_mnt",
//             "end_chainage_mnt",
//             "mnt_",
//         );

//         // Safely access global variable
//         draftData.subItemsData =
//             typeof subItemsData !== "undefined" ? subItemsData : {};

//         console.log("Saving draft:", draftData);

//         try {
//             const response = await fetch("/save-draft", {
//                 method: "POST",
//                 headers: {
//                     "Content-Type": "application/json",
//                     "X-CSRF-TOKEN": getCsrfToken(),
//                 },
//                 body: JSON.stringify(draftData),
//             });

//             const data = await response.json();

//             if (data.status === "success") {
//                 currentDraftId = data.draftId;
//                 const draftInput = document.querySelector(
//                     'input[name="draft_id"]',
//                 );
//                 if (draftInput) draftInput.value = currentDraftId;
//                 showAlert("success", data.message);
//             } else {
//                 showAlert("error", data.message);
//             }
//         } catch (err) {
//             console.error("Error saving draft:", err);
//             showAlert("error", "Failed to communicate with server.");
//         }
//     });

// --- ALERT UTILS ---
function showAlert(type, message) {
    const alertContainer = document.getElementById("alertContainer");
    if (!alertContainer) return;

    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show mt-2" role="alert">
            <strong>${type === "success" ? "Success!" : "Error!"}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

    const alertDiv = alertContainer.querySelector(".alert");
    alertDiv.focus();
    alertDiv.scrollIntoView({ behavior: "smooth", block: "start" });

    setTimeout(() => alertDiv?.remove(), 4000);
}

// --- LOAD DRAFT ---
async function loadDraft(draftId) {
    try {
        const res = await fetch(`/project-management/load-draft/${draftId}`);
        const data = await res.json();

        if (data.status !== "success") return alert("No saved draft found.");

        const draft = data.draft;
        console.log("Loaded Draft:", draft);
        currentDraftId = draftId;

        // 1. Fill simple inputs
        Object.keys(draft).forEach((key) => {
            if (
                [
                    "_token",
                    "culverts",
                    "bridges",
                    "rtws",
                    "pvms",
                    "assets",
                    "assetsMnt",
                    "workItems",
                    "subItemsData",
                ].includes(key)
            )
                return;

            const input =
                document.querySelector(`[name="${key}"]`) ||
                document.getElementById(key);
            if (input) {
                if (input.type === "checkbox" || input.type === "radio") {
                    input.checked = input.value == draft[key];
                } else {
                    input.value = draft[key];
                }
            }
        });

        // 2. Division & Roads
        if (draft.division_cd) {
            const divSelect = document.getElementById("division_cd");
            const subDvision = document.getElementById("sub_division_cd");

            if (divSelect) {
                divSelect.value = draft.division_cd;
                // Wait for roads to load before setting values
                divSelect.dispatchEvent(new Event("change"));
                await Promise.all([
                    populateRoadSelect(draft.division_cd, "slnewRd"),
                    populateRoadSelect(draft.division_cd, "maintRoads"),
                    populateRoadSelect(draft.division_cd, "refRoadSelect"),
                ]);
                if (subDvision) subDvision.value = draft.sub_division_cd || "";
            }
        }

        // 3. Project Type Logic
        const projectTypeSelect = document.getElementById("projectTypeSelect");
        if (projectTypeSelect) {
            projectTypeSelect.value = draft.projectTypeSelect || "";
            if (typeof toggleSectionsBasedOnProjectType === "function") {
                toggleSectionsBasedOnProjectType();
            }
        }
        const officeSelect = document.getElementById("site_incharge_office_cd");
        if (officeSelect) officeSelect.dispatchEvent(new Event("change"));

        // 4. Road Type Logic
        const opt1 = document.getElementById("opt1");
        if (draft.rdo_type == "1") opt1.checked = true;

        if (typeof toggleDisplayEvent === "function") toggleDisplayEvent();

        if (opt1.checked && document.getElementById("slnewRdNew")) {
            document.getElementById("slnewRdNew").value =
                draft.new_rd_name || draft.slnewRdNew || "";
        }

        // 5. Work Items & Sub Items
        if (Array.isArray(draft.workItems)) {
            const addBtn = document.getElementById("addWorkItemBtn");
            const workSelect = document.getElementById("workItemSelect");
            const qtyInput = document.getElementById("workQtyInput");

            draft.workItems.forEach((item) => {
                if (workSelect) workSelect.value = item.item;
                if (qtyInput) qtyInput.value = item.qty;

                document
                    .querySelectorAll(
                        "#predecessorOptions input[type='checkbox']",
                    )
                    .forEach((cb) => {
                        cb.checked = item.predecessors.includes(cb.value);
                    });

                const estStart = document.getElementById("estimateStartDate");
                const estEnd = document.getElementById("estimateEndDate");
                if (estStart) estStart.value = item.startDate;
                if (estEnd) estEnd.value = item.endDate;

                if (addBtn) addBtn.dispatchEvent(new Event("click"));
            });
        }

        // Restore Global subItemsData

        subItemsData = draft.subItemsData || {};

        // Update hidden input
        document.getElementById("subItems").value =
            JSON.stringify(subItemsData);

        // Restore Action buttons for every row that has sub-items
        Object.keys(subItemsData).forEach((itemKey) => {
            const row = document
                .querySelector(
                    `#workItemsTable tbody tr td:first-child input[value="${itemKey}"]`,
                )
                ?.closest("tr");

            if (!row) return;

            row.querySelector("td:nth-child(6)").innerHTML = `
                    <button type="button" class="text-primary border-0 bg-transparent outline-0 viewSubBtn">
                        <i class="fas fa-eye"></i>
                    </button>

                    <button type="button" class="btn btn-outline-primary btn-sm editItemBtn">
                        + Edit item
                    </button>
                `;
        });

        // 6 & 7. Restore Assets (Generic Function for Reference & Maintenance)
        const restoreAssetGroup = async (assets, config) => {
            if (!Array.isArray(assets)) return;

            const {
                roadSelectId,
                startId,
                endId,
                btnId,
                culvertId,
                bridgeId,
                wallId,
                isMaint,
            } = config;
            const roadSelect = document.getElementById(roadSelectId);
            const startInput = document.getElementById(startId);
            const endInput = document.getElementById(endId);
            const addBtn = document.getElementById(btnId);

            for (const asset of assets) {
                roadSelect.value = asset.road;
                startInput.value = asset.start_chainage;
                endInput.value = asset.end_chainage;

                roadSelect.dispatchEvent(new Event("change")); // Trigger chainage load

                if (roadSelect.value) {
                    // Load dependent dropdowns
                    await Promise.all([
                        loadCulverts(
                            roadSelect.value,
                            asset.start_chainage,
                            asset.end_chainage,
                            culvertId,
                        ),
                        loadBridges(
                            roadSelect.value,
                            asset.start_chainage,
                            asset.end_chainage,
                            bridgeId,
                        ),
                        loadWalls(
                            roadSelect.value,
                            asset.start_chainage,
                            asset.end_chainage,
                            wallId,
                        ),
                    ]);
                }

                // Select options helper
                const setSelection = (selectId, values) => {
                    const el = document.getElementById(selectId);
                    if (el)
                        Array.from(el.options).forEach(
                            (opt) =>
                                (opt.selected = values.includes(opt.value)),
                        );
                };

                setSelection(culvertId, asset.culverts);
                setSelection(bridgeId, asset.bridges);
                setSelection(wallId, asset.walls);

                if (isMaint) addMaintenanceAssets();
                else addBtn.dispatchEvent(new Event("click"));
            }
        };

        // Process Reference Assets
        await restoreAssetGroup(draft.assets, {
            roadSelectId: "refRoadSelect",
            startId: "txt_start_chainage",
            endId: "txt_end_chainage",
            btnId: "addRefAssetBtn",
            culvertId: "refCulvertSelect",
            bridgeId: "refBridgeSelect",
            wallId: "refWallSelect",
            isMaint: false,
        });

        // Process Maintenance Assets
        await restoreAssetGroup(draft.assetsMnt, {
            roadSelectId: "maintRoads",
            startId: "txt_start_chainage_mnt",
            endId: "txt_end_chainage_mnt",
            btnId: null,
            culvertId: "maintCulverts",
            bridgeId: "maintBridges",
            wallId: "maintWalls",
            isMaint: true,
        });

        // ============================================================
        // 8. Structure Sections (OPTIMIZED & SYNCHRONOUS)
        // ============================================================
        if (draft.rd_length) {
            // A. Set the Main Form Counts (visible inputs)
            if (draft.noOfCls)
                document.getElementById("noOfCl").value = draft.noOfCls;
            if (draft.noOfBrs)
                document.getElementById("noOfBr").value = draft.noOfBrs;
            if (draft.noOfrtws)
                document.getElementById("noOfrtw").value = draft.noOfrtws;
            if (draft.noOfPvms)
                document.getElementById("noOfPvm").value = draft.noOfPvms;

            document.getElementById("rdLength").value = draft.rd_length;

            // B. Trigger Change Events on Main Form Inputs
            // This forces the "generateChainageFields" logic to run and create the inputs in the DOM immediately
            ["noOfCl", "noOfBr", "noOfrtw", "noOfPvm"].forEach((id) => {
                document.getElementById(id).dispatchEvent(new Event("change"));
            });

            // C. Fill the Main Form Inputs with Draft Data
            // We fill the specific IDs (e.g., culvert1_start) so the "Add" button listener can find them
            const fillMainInputs = (prefix, dataArray) => {
                if (!Array.isArray(dataArray)) return;
                dataArray.forEach((item, index) => {
                    // Handle object {start: "10"} or raw value "10"
                    const val =
                        typeof item === "object" && item.start
                            ? item.start
                            : item;
                    const inputId = `${prefix}${index + 1}_start`;
                    const input = document.getElementById(inputId);
                    if (input) input.value = val;
                });
            };

            fillMainInputs("culvert", draft.culverts);
            fillMainInputs("bridge", draft.bridges);
            fillMainInputs("rtw", draft.rtws);
            fillMainInputs("pvm", draft.pvms);

            // D. Click the Add Button
            // The listener executes immediately, finds the values we just set, and builds the row.
            const addBtn = document.getElementById("adNoOfAssetsBtn");
            if (addBtn) addBtn.click();

            showAlert("success", "Draft loaded successfully!");
        } else {
            showAlert("success", "Draft loaded successfully!");
        }
    } catch (err) {
        console.error("Error loading draft:", err);
        showAlert("error", "Error loading draft.");
    }
}
