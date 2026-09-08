/**
 * Fetches the draft and redirects to the edit page.
 */
async function editWithLocal(projectCd, department) {
    try {
        const res = await fetch(
            `/project-management/project/load-db-draft/${projectCd}/${department}`,
        );

        if (!res.ok) throw new Error(`HTTP Error: ${res.status}`);

        const data = await res.json();

        if (data.status !== "success") {
            alert("Draft not found");
            return;
        }

        const editText = document.getElementById("editModeText");
        editText.innerText = `Project is under edit mode for Project Id: ${projectCd}`;
        editText.style.display = "block";

        document.getElementById("project_cd").value = projectCd;

        // ✅ Hide all tables
        ["draftSection", "draftSectionUpg", "draftSectionUpMaint"].forEach(
            (id) => {
                const table = document.getElementById(id);
                if (table) {
                    const wrapper = table.closest(".dataTables_wrapper");
                    if (wrapper) wrapper.style.display = "none";
                    else table.style.display = "none";
                }
            },
        );

        document.getElementById("PmsUpdateBtn").style.display = "inline-block";
        document.getElementById("PmsSaveBtn").style.display = "none";

        // ✅ Show form
        const form = document.getElementById("myForm");
        if (form) {
            form.style.display = "block";
            form.scrollIntoView({ behavior: "smooth" });
        }

        await loadDraftIntoForm(
            data.draft,
            data.images,
            data.documents,
            department,
        );
    } catch (err) {
        console.error("editWithLocal Error:", err);
        if (err.stack) {
            console.error(err.stack);
        }
        alert("Error fetching draft.");
    }
}

async function loadDraftIntoForm(
    draft,
    images = [],
    documents = [],
    department,
) {
    const dom = {
        project_Type: document.getElementById("projectTypeSelect"),
        project_name: document.getElementById("project_name"),
        divisionSelect: document.getElementById("division_cd"),
        sub_division: document.getElementById("sub_division_cd"),
        project_start_date: document.getElementById("project_start_date"),
        project_end_date: document.getElementById("project_end_date"),
        project_awarded_to: document.getElementById("project_awarded_to"),
        est_proj_cost: document.getElementById("est_proj_cost"),
        defect_liability_period: document.getElementById(
            "defect_liability_period",
        ),
        work_order_amount: document.getElementById("work_order_amount"),
        is_published: document.getElementById("is_published"),
        tech_type_cd: document.getElementById("tech_type_cd"),

        //done by dipshikha-start
        work_order_no: document.getElementById("work_order_no"),
        work_order_issue_date: document.getElementById("work_order_issue_date"),
        scheme_cd: document.getElementById("scheme_cd"),
        //done by dipshikha-end
        slnewRdNew: document.getElementById("slnewRdNew"),
        rdLength_new: document.getElementById("rdLength"),
        road_category_new: document.getElementById("road_category_new"),
        road_owner_new: document.getElementById("road_owner_new"),
        road_type_new: document.getElementById("road_type_new"),

        rdoRoadWithSubAsset: document.getElementById("rdoRoadWithSubAsset"),
        rdoSubAsset: document.getElementById("rdoSubAsset"),
        new_road_name: document.getElementById("new_road_name"),
        road_length: document.getElementById("road_length"),
        road_category: document.getElementById("road_category"),
        road_owner: document.getElementById("road_owner"),
        road_type: document.getElementById("road_type"),

        buildingCategory: document.getElementById("buildingCategory"),
        maintBuildings: document.getElementById("maintBuildings"),
        buildingCategoryUpgradation: document.getElementById(
            "buildingCategoryUpgradation",
        ),
        upgBuildings: document.getElementById("upgBuildings"),
        building_type_upg: document.getElementById("building_type_upg"),
        owning_dept_upg: document.getElementById("owning_dept_upg"),
        buildingCategoryUpg: document.getElementById("buildingCategoryUpg"),
        quarter_no: document.getElementById("quarter_no"),
        rdo_yes: document.getElementById("rdo_yes"),
        rdo_no: document.getElementById("rdo_no"),
        asset_geo_location: document.getElementById("asset_geo_location"),
        asset_geo_location_lat: document.getElementById(
            "asset_geo_location_lat",
        ),
        asset_geo_location_lng: document.getElementById(
            "asset_geo_location_lng",
        ),
        residential: document.getElementById("residential"),
        nonResidential: document.getElementById("nonResidential"),
        rental: document.getElementById("rental"),
        building_location_cd: document.getElementById("building_location_cd"),
    };

    if (draft.projectTypeSelect && dom.project_Type) {
        dom.project_Type.value = draft.projectTypeSelect;
        dom.project_Type.dispatchEvent(new Event("change"));
        dom.project_Type.style.pointerEvents = "none";
    }

    if (draft.project_name && dom.project_name) {
        dom.project_name.value = draft.project_name;
    }

    if (draft.division_cd && dom.divisionSelect) {
        dom.divisionSelect.value = draft.division_cd;
    }

    if (draft.sub_division_cd && dom.sub_division) {
        loadSubDivisions(
            draft.division_cd,
            draft.sub_division_cd,
            function () {
                dom.sub_division.value = draft.sub_division_cd;
                dom.sub_division.dispatchEvent(new Event("change"));
            },
            false,
        );
    }

    if (draft.project_start_date && dom.project_start_date) {
        dom.project_start_date.value = formatDate(draft.project_start_date);
    }

    if (draft.project_end_date && dom.project_end_date) {
        dom.project_end_date.value = formatDate(draft.project_end_date);
    }

    if (draft.project_awarded_to && dom.project_awarded_to) {
        dom.project_awarded_to.value = draft.project_awarded_to;
    }

    if (draft.est_proj_cost && dom.est_proj_cost) {
        dom.est_proj_cost.value = draft.est_proj_cost;
    }

    if (draft.defect_liability_period && dom.defect_liability_period) {
        dom.defect_liability_period.value = draft.defect_liability_period;
    }

    if (draft.work_order_amount && dom.work_order_amount) {
        dom.work_order_amount.value = draft.work_order_amount;
    }

    //done by dipshikha-start
    if (draft.work_order_no && dom.work_order_no) {
        dom.work_order_no.value = draft.work_order_no;
    }

    if (draft.work_order_issue_date && dom.work_order_issue_date) {
        dom.work_order_issue_date.value = formatDate(
            draft.work_order_issue_date,
        );
    }

    if (draft.scheme_cd && dom.scheme_cd) {
        dom.scheme_cd.value = draft.scheme_cd;
        dom.scheme_cd.dispatchEvent(new Event("change"));
    }
    //done by dipshikha-end

    if (draft.is_published && dom.is_published) {
        dom.is_published.checked = draft.is_published === "Y";
    }

    if (dom.tech_type_cd) {
        dom.tech_type_cd.value = draft.tech_type_cd;
        dom.tech_type_cd.dispatchEvent(new Event("change"));
    }

    if (department === "15") {
        if (draft.projectTypeSelect === "UPG") {
            const upg = draft.upgradation || {};

            const vehicleIds = (upg.vehicles || []).map((v) =>
                String(v.vehicle_asset_cd ?? v),
            );

            const equipmentIds = (upg.equipments || []).map((e) =>
                String(e.euipment_cd ?? e),
            );

            const vehicleContainer = document.getElementById("vehicleListUpg");

            if (vehicleContainer) {
                const observer = new MutationObserver(() => {
                    const checkboxes = vehicleContainer.querySelectorAll(
                        'input[type="checkbox"]',
                    );

                    if (!checkboxes.length) return;

                    checkboxes.forEach((cb) => {
                        if (vehicleIds.includes(String(cb.value))) {
                            cb.checked = true;
                        }
                    });

                    observer.disconnect();
                });

                observer.observe(vehicleContainer, {
                    childList: true,
                    subtree: true,
                });
            }

            const equipmentContainer =
                document.getElementById("equipmentListUpg");

            if (equipmentContainer) {
                const observer2 = new MutationObserver(() => {
                    const checkboxes = equipmentContainer.querySelectorAll(
                        'input[type="checkbox"]',
                    );

                    if (!checkboxes.length) return;

                    checkboxes.forEach((cb) => {
                        if (equipmentIds.includes(String(cb.value))) {
                            cb.checked = true;
                        }
                    });

                    observer2.disconnect();
                });

                observer2.observe(equipmentContainer, {
                    childList: true,
                    subtree: true,
                });
            }
        }

        if (draft.projectTypeSelect === "MTN") {
            const mtn = draft.maintenance || {};

            const vehicleIds = (mtn.vehicles || []).map((v) =>
                String(v.vehicle_asset_cd ?? v),
            );

            const equipmentIds = (mtn.equipments || []).map((e) =>
                String(e.euipment_cd ?? e),
            );

            const vehicleContainer =
                document.getElementById("vehicleListMaint");

            if (vehicleContainer) {
                const observer = new MutationObserver(() => {
                    const checkboxes = vehicleContainer.querySelectorAll(
                        'input[type="checkbox"]',
                    );

                    if (!checkboxes.length) return;

                    checkboxes.forEach((cb) => {
                        if (vehicleIds.includes(String(cb.value))) {
                            cb.checked = true;
                        }
                    });

                    observer.disconnect();
                });

                observer.observe(vehicleContainer, {
                    childList: true,
                    subtree: true,
                });
            }

            const equipmentContainer =
                document.getElementById("equipmentListMaint");

            if (equipmentContainer) {
                const observer2 = new MutationObserver(() => {
                    const checkboxes = equipmentContainer.querySelectorAll(
                        'input[type="checkbox"]',
                    );

                    if (!checkboxes.length) return;

                    checkboxes.forEach((cb) => {
                        if (equipmentIds.includes(String(cb.value))) {
                            cb.checked = true;
                        }
                    });

                    observer2.disconnect();
                });

                observer2.observe(equipmentContainer, {
                    childList: true,
                    subtree: true,
                });
            }
        }
    }

    if (department === "6") {
        if (draft.projectTypeSelect === "NEW") {
            const val = String(draft.new_building_maintain_by_npwd);

            if (val === "Y") {
                dom.rdo_yes.checked = true;
                dom.rdo_no.checked = false;
            } else {
                dom.rdo_no.checked = true;
                dom.rdo_yes.checked = false;
            }

            if (draft.new_building_lat && draft.new_building_lng) {
                dom.asset_geo_location_lat.value = draft.new_building_lat;
                dom.asset_geo_location_lng.value = draft.new_building_lng;
                dom.asset_geo_location.value =
                    "[" +
                    draft.new_building_lat +
                    "," +
                    draft.new_building_lng +
                    "]";
            }

            const valCat = String(draft.new_building_class_cd);

            if (valCat === "0") {
                dom.residential.checked = true;
                dom.nonResidential.checked = false;
                dom.rental.checked = false;
            } else if (valCat === "1") {
                dom.nonResidential.checked = true;
                dom.residential.checked = false;
                dom.rental.checked = false;
            } else if (valCat === "2") {
                dom.rental.checked = true;
                dom.residential.checked = false;
                dom.nonResidential.checked = false;
            }

            $('input[name="building_class_cd"]:checked').trigger("change");

            if (draft.new_building_location_cd && dom.building_location_cd) {
                let val = String(draft.new_building_location_cd ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.building_location_cd.options];
                    let match = options.find((o) => o.value === val);

                    if (match) {
                        dom.building_location_cd.value = val;
                        dom.building_location_cd.dispatchEvent(
                            new Event("change"),
                        );
                        observer.disconnect();
                    }
                });

                observer.observe(dom.building_location_cd, {
                    childList: true,
                });
            }
        }

        if (draft.projectTypeSelect === "MTN") {
            const maint = draft.maintenance || {};

            if (maint.building_details && dom.buildingCategory) {
                let val = String(
                    maint.building_details.building_class_cd ?? "",
                );

                const observer = new MutationObserver(() => {
                    let options = [...dom.buildingCategory.options];
                    let match = options.find((o) => o.value === val);

                    if (match) {
                        dom.buildingCategory.value = val;
                        dom.buildingCategory.dispatchEvent(new Event("change"));
                        observer.disconnect();
                    }
                });

                observer.observe(dom.buildingCategory, {
                    childList: true,
                });
            }

            if (maint.building_details && dom.maintBuildings) {
                let val = String(
                    maint.building_details.building_system_cd ?? "",
                );

                const observer = new MutationObserver(() => {
                    let options = [...dom.maintBuildings.options];
                    let match = options.find((o) => o.value === val);

                    if (match) {
                        dom.maintBuildings.value = val;
                        dom.maintBuildings.dispatchEvent(new Event("change"));
                        observer.disconnect();
                    }
                });

                observer.observe(dom.maintBuildings, {
                    childList: true,
                });
            }
        }

        if (draft.projectTypeSelect === "UPG") {
            const upg = draft.upgradation || {};

            if (upg.building_details && dom.buildingCategoryUpgradation) {
                let val = String(
                    upg.building_details.actual_building_class_cd ?? "",
                );
                const observer = new MutationObserver(() => {
                    let options = [...dom.buildingCategoryUpgradation.options];
                    let match = options.find((o) => o.value === val);

                    if (match) {
                        dom.buildingCategoryUpgradation.value = val;
                        dom.buildingCategoryUpgradation.dispatchEvent(
                            new Event("change"),
                        );
                        observer.disconnect();
                    }
                });

                observer.observe(dom.buildingCategoryUpgradation, {
                    childList: true,
                });
            }

            if (upg.building_details && dom.upgBuildings) {
                let val = String(upg.building_details.building_id ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.upgBuildings.options];
                    let match = options.find((o) => o.value === val);

                    if (match) {
                        dom.upgBuildings.value = val;
                        dom.upgBuildings.dispatchEvent(new Event("change"));
                        observer.disconnect();
                    }
                });

                observer.observe(dom.upgBuildings, {
                    childList: true,
                });
            }

            if (
                upg.building_details.building_type_cd &&
                dom.building_type_upg
            ) {
                let val = String(upg.building_details.building_type_cd ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.building_type_upg.options];
                    let match = options.find((o) => o.value === val);

                    if (match) {
                        dom.building_type_upg.value = val;
                        observer.disconnect();
                    }
                });

                observer.observe(dom.building_type_upg, {
                    childList: true,
                });
            }

            if (upg.building_details.owning_dept_cd && dom.owning_dept_upg) {
                let val = String(upg.building_details.owning_dept_cd ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.owning_dept_upg.options];
                    let match = options.find((o) => o.value === val);

                    if (match) {
                        dom.owning_dept_upg.value = val;
                        observer.disconnect();
                    }
                });

                observer.observe(dom.owning_dept_upg, {
                    childList: true,
                });
            }

            if (
                upg.building_details.building_class_cd &&
                dom.buildingCategoryUpg
            ) {
                let val = String(upg.building_details.building_class_cd ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.buildingCategoryUpg.options];
                    let match = options.find((o) => o.value === val);

                    if (match) {
                        dom.buildingCategoryUpg.value = val;
                        dom.buildingCategoryUpg.dispatchEvent(
                            new Event("change"),
                        );
                        observer.disconnect();
                    }
                });

                observer.observe(dom.buildingCategoryUpg, {
                    childList: true,
                });
            }

            if (upg.building_details.building_class_cd === "0") {
                if (upg.building_details.quarter_no && dom.quarter_no) {
                    dom.quarter_no.value = upg.building_details.quarter_no;
                }
            } else {
                if (upg.building_details.building_name && dom.quarter_no) {
                    dom.quarter_no.value = upg.building_details.building_name;
                }
            }

            if (upg.building_details.is_maintained_by_npwd) {
                const val = String(upg.building_details.is_maintained_by_npwd);

                const yes = document.getElementById("rdo_yes_upg");
                const no = document.getElementById("rdo_no_upg");

                if (!yes || !no) return;

                if (val === "Y") {
                    yes.checked = true;
                    no.checked = false;
                } else {
                    no.checked = true;
                    yes.checked = false;
                }

                const lockRadio = (el, fixedValue) => {
                    Object.defineProperty(el, "checked", {
                        get: () => fixedValue,
                        set: () => {},
                        configurable: true,
                    });
                };

                if (val === "Y") {
                    lockRadio(yes, true);
                    lockRadio(no, false);
                } else {
                    lockRadio(yes, false);
                    lockRadio(no, true);
                }
            }
        }
    }

    //by dipshikha
    if (department === "14" || department === "3") {
        //end
        if (draft.projectTypeSelect === "NEW") {
            const newRoadName = (draft.new_road_name ?? "").toString().trim();

            if (newRoadName !== "") {
                if (dom.slnewRdNew) dom.slnewRdNew.value = newRoadName;

                if (dom.rdLength_new)
                    dom.rdLength_new.value = parseFloat(draft.rd_length) || 0;

                // 🔥 CRITICAL FIX: disable "new road" validation trigger
                const opt1 = document.getElementById("opt1");
                if (opt1) {
                    opt1.checked = false;
                }

                if (dom.road_category_new) {
                    dom.road_category_new.value = draft.road_category;
                }

                if (dom.road_owner_new) {
                    dom.road_owner_new.value = draft.road_owner;
                }

                if (dom.road_type_new) {
                    dom.road_type_new.value = draft.road_type;
                }
            }
        }

        if (draft.projectTypeSelect === "UPG") {
            const upg = draft.upgradation || {};

            const hasUpgradedRoads =
                Array.isArray(upg.upgraded_roads) &&
                upg.upgraded_roads.length > 0;

            if (hasUpgradedRoads) {
                if (dom.rdoRoadWithSubAsset) {
                    dom.rdoRoadWithSubAsset.checked = true;
                    dom.rdoRoadWithSubAsset.click();
                }
            } else {
                if (dom.rdoSubAsset) {
                    dom.rdoSubAsset.checked = true;
                    dom.rdoSubAsset.click();
                }
            }

            const tbody = document.querySelector("#refAssetTable tbody");

            if (!tbody) return;

            tbody.innerHTML = "";

            const rows = upg.upgraded_asset_dtls || [];

            rowIndex = 0;

            rows.forEach((item) => {
                rowIndex++;

                const tr = document.createElement("tr");
                const chainageColumns =
                    item.start_chainage != null &&
                    item.start_chainage !== "" &&
                    item.end_chainage != null &&
                    item.end_chainage !== ""
                        ? `
                    <td>
                        <input type="hidden" name="start_chainage[]" value="${item.start_chainage}">
                        ${item.start_chainage}
                    </td>

                    <td>
                        <input type="hidden" name="end_chainage[]" value="${item.end_chainage}">
                        ${item.end_chainage}
                    </td>
                `
                        : "";

                tr.innerHTML = `
                    <td>
                        ${item.parent_asset_id ?? ""}
                        <input type="hidden" name="roads[]" value="${item.parent_asset_id}">
                    </td>

					 ${chainageColumns}


                    <td>
                        ${(item.culverts || [])
                            .map(
                                (c) => `
                            <input type="hidden" name="culverts_${rowIndex}[]" value="${c}">
                            <span class="badge bg-info me-1">${c}</span>
                        `,
                            )
                            .join("")}
                    </td>

                    <td>
                        ${(item.bridges || [])
                            .map(
                                (b) => `
                            <input type="hidden" name="bridges_${rowIndex}[]" value="${b}">
                            <span class="badge bg-warning text-dark me-1">${b}</span>
                        `,
                            )
                            .join("")}
                    </td>

                    <td>
                        ${(item.retaining_walls || [])
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

                tbody.appendChild(tr);

                tr.querySelector(".deleteRowBtn").addEventListener(
                    "click",
                    () => {
                        tr.remove();
                        renumberRows();
                    },
                );
            });

            document.getElementById("rowCount").value = rowIndex;

            const newAsset = upg.new_asset;

            const newAssetsForm = document.getElementById("newAssetsForm");

            if (newAsset) {
                const roadName = newAsset.road_name || "";
                const roadLength = newAsset.road_length || "";
                if (roadName || roadLength) {
                    if (newAssetsForm) {
                        newAssetsForm.style.display = "block";
                    }

                    if (dom.new_road_name) {
                        dom.new_road_name.value = roadName;
                    }

                    if (dom.road_length) {
                        dom.road_length.value = roadLength;
                    }
                } else {
                    if (newAssetsForm) {
                        newAssetsForm.style.display = "none";
                    }
                }
                if (dom.road_category) {
                    dom.road_category.value = newAsset.road_category;
                }

                if (dom.road_owner) {
                    dom.road_owner.value = newAsset.road_owner;
                }

                if (dom.road_type) {
                    dom.road_type.value = newAsset.road_type;
                }
            } else {
                if (newAssetsForm) {
                    newAssetsForm.style.display = "none";
                }
            }

            calculateTotalRoadLength();

            if (upg.road_sequence && upg.road_sequence.length > 0) {
                const priorityCard = document.getElementById("priorityCard");
                const roadPriorityList =
                    document.getElementById("roadPriorityList");

                priorityCard.style.display = "block";
                roadPriorityList.innerHTML = "";

                upg.road_sequence
                    .sort((a, b) => a.sequence - b.sequence)
                    .forEach((item) => {
                        let displayRoadName = item.road_id;
                        let isNewRoad = false;

                        // New road
                        if (
                            upg.new_asset &&
                            upg.new_asset.road_id == item.road_id
                        ) {
                            displayRoadName = upg.new_asset.road_name;
                            isNewRoad = true;
                        }

                        const div = document.createElement("div");

                        div.className =
                            "list-group-item d-flex align-items-center";

                        if (isNewRoad) {
                            // important: match your input listener
                            div.id = "newRoadPriorityItem";
                        } else {
                            div.setAttribute("data-road", item.road_id);
                        }

                        div.innerHTML = `
                            <i class="fa fa-grip-vertical me-3 text-secondary"></i>

                            <span class="fw-bold flex-grow-1">
                                ${displayRoadName}
                            </span>

                            <input type="hidden"
                                name="priority_roads[]"
                                value="${isNewRoad ? item.road_id : item.road_id}">
                        `;

                        roadPriorityList.appendChild(div);
                    });

                updatePriorityCardVisibility();
            }
        }

        if (draft.projectTypeSelect === "MTN") {
            const mtn = draft.maintenance || {};
            const assets = mtn.assets || {};

            const tbody = document.querySelector("#mtnAssetTable tbody");

            if (!tbody) return;

            tbody.innerHTML = "";

            const tr = document.createElement("tr");

            mnt_rowIndex = 0;

            (assets.roads || []).forEach((road, i) => {
                mnt_rowIndex++;

                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <td>
                        <input type="hidden" name="roads_mnt${mnt_rowIndex}[]" value="${road}">
                        <span class="badge bg-info me-1">${road}</span>
                    </td>

                    <td>
                        ${(assets.culverts || [])
                            .map(
                                (c) => `
                            <input type="hidden" name="culverts_mnt_${mnt_rowIndex}[]" value="${c}">
                            <span class="badge bg-info me-1">${c}</span>
                        `,
                            )
                            .join("")}
                    </td>

                    <td>
                        ${(assets.bridges || [])
                            .map(
                                (b) => `
                            <input type="hidden" name="bridges_mnt_${mnt_rowIndex}[]" value="${b}">
                            <span class="badge bg-warning text-dark me-1">${b}</span>
                        `,
                            )
                            .join("")}
                    </td>

                    <td>
                        ${(assets.retaining_walls || [])
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

                tbody.appendChild(tr);

                tr.querySelector(".deleteRowBtn").addEventListener(
                    "click",
                    () => {
                        tr.remove();
                    },
                );
            });

            document.getElementById("mnt_rowCount").value = mnt_rowIndex;
        }

        loadKmlFile(draft.kml_file);
    }

    loadImages(images);
    loadDocuments(documents);
}

function renumberRows() {
    const rows = $("#refAssetTable tbody tr");

    rows.each(function (index) {
        const rowNo = index + 1;

        $(this)
            .find("input[name^='culverts_']")
            .each(function () {
                $(this).attr("name", "culverts_" + rowNo + "[]");
            });

        $(this)
            .find("input[name^='bridges_']")
            .each(function () {
                $(this).attr("name", "bridges_" + rowNo + "[]");
            });

        $(this)
            .find("input[name^='walls_']")
            .each(function () {
                $(this).attr("name", "walls_" + rowNo + "[]");
            });
    });

    $("#rowCount").val(rows.length);
}

function openDeleteModal(projectCd) {
    document.getElementById("delete_project_cd").value = projectCd;
    document.getElementById("delete_remarks").value = "";

    new bootstrap.Modal(document.getElementById("deleteDraftModal")).show();
}

function confirmDelete() {
    const projectCd = document.getElementById("delete_project_cd").value;
    const remarks = document.getElementById("delete_remarks").value;

    if (!remarks.trim()) {
        Swal.fire({
            icon: "warning",
            title: "Remarks Required",
            text: "Please enter remarks.",
        });
        return;
    }

    fetch(`/project-management/draft/delete/${projectCd}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            remarks: remarks,
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.status === 200) {
                Swal.fire({
                    title: "Success",
                    text: data.message,
                    icon: "success",
                    confirmButtonText: "Okay",
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    title: "Notice",
                    text: "Failed to delete",
                    icon: "info",
                    confirmButtonText: "Okay",
                });
            }
        })
        .catch((err) => {
            console.error(err);
            alert("Something went wrong");
        });
}

document.getElementById("PmsUpdateBtn").addEventListener("click", function (e) {
    document.getElementById("myForm").action =
        "/project-management/manage-project/update";
    const projectType = document.getElementById("projectTypeSelect").value;
    const departmentName = document.getElementById("owner_dept_cd").value;

    const dom = {
        project_name: document.getElementById("project_name"),
        division: document.getElementById("division_cd"),
        sub_division: document.getElementById("sub_division_cd"),
        start_date: document.getElementById("project_start_date"),
        end_date: document.getElementById("project_end_date"),
        project_awarded_to: document.getElementById("project_awarded_to"),
        cost: document.getElementById("est_proj_cost"),
        period: document.getElementById("defect_liability_period"),
        work_order: document.getElementById("work_order_amount"),

        //done by dipshikha-start
        work_order_no: document.getElementById("work_order_no"),
        work_order_issue_date: document.getElementById("work_order_issue_date"),
        scheme_cd: document.getElementById("scheme_cd"),
        tech_type_cd: document.getElementById("tech_type_cd"),
        //done by dipshikha-end
        road_name: document.getElementById("slnewRdNew"),
        road_length: document.getElementById("rdLength"),
        asset_geo_location_lat: document.getElementById(
            "asset_geo_location_lat",
        ),
        asset_geo_location_lng: document.getElementById(
            "asset_geo_location_lng",
        ),
        building_location_cd: document.getElementById("building_location_cd"),

        road_category_new: document.getElementById("road_category_new"),
        road_owner_new: document.getElementById("road_owner_new"),
        road_type_new: document.getElementById("road_type_new"),

        road_category: document.getElementById("road_category"),
        road_owner: document.getElementById("road_owner"),
        road_type: document.getElementById("road_type"),
    };

    const table = document.getElementById("refAssetTable");
    const mtnTable = document.getElementById("mtnAssetTable");

    const kmlFile = document.getElementById("road_kml_file");
    const kmlCard = document.getElementById("kmlCard");

    const rowCount = table ? table.querySelectorAll("tbody tr").length : 0;
    const mtnRowCount = mtnTable
        ? mtnTable.querySelectorAll("tbody tr").length
        : 0;

    const newAssetsForm = document.getElementById("newAssetsForm");

    let hasError = false;

    // 🔄 Remove old errors
    document.querySelectorAll(".dynamic-error").forEach((el) => el.remove());

    // 🔧 helper to show error
    const showError = (input, message) => {
        if (!input) return;

        const error = document.createElement("div");
        error.className = "text-danger dynamic-error";
        error.style.fontSize = "12px";
        error.textContent = message;

        input.style.borderColor = "red";

        // place after input
        input.parentNode.appendChild(error);
    };

    // Validation
    if (!dom.project_name.value.trim()) {
        showError(dom.project_name, "Project Name is required");
        hasError = true;
    }

    if (!dom.division.value) {
        showError(dom.division, "Division is required");
        hasError = true;
    }

    if (!dom.sub_division.value) {
        showError(dom.sub_division, "Sub Division is required");
        hasError = true;
    }

    if (!dom.start_date.value) {
        showError(dom.start_date, "Start Date is required");
        hasError = true;
    }

    if (!dom.end_date.value) {
        showError(dom.end_date, "End Date is required");
        hasError = true;
    }

    if (!dom.project_awarded_to.value) {
        showError(dom.project_awarded_to, "Project Awarded to is required");
        hasError = true;
    }

    if (!dom.cost.value) {
        showError(dom.cost, "Cost is required");
        hasError = true;
    }

    if (!dom.period.value) {
        showError(dom.period, "Period is required");
        hasError = true;
    }

    if (!dom.work_order.value) {
        showError(dom.work_order, "Work Order Amount is required");
        hasError = true;
    }

    //Done By Dipshikha -- start
    if (!dom.work_order_no.value) {
        showError(dom.work_order_no, "Work Order Number is required");
        hasError = true;
    }
    if (!dom.work_order_issue_date.value) {
        showError(
            dom.work_order_issue_date,
            "Work Order Issue Date is required",
        );
        hasError = true;
    }
    if (!dom.scheme_cd.value) {
        showError(dom.scheme_cd, "Scheme is required");
        hasError = true;
    }
    if (!dom.tech_type_cd.value) {
        showError(dom.tech_type_cd, "Technology Type is required");
        hasError = true;
    }
    //Done By Dipshikha -- end

    if (departmentName === "15") {
        if (projectType === "UPG") {
            const section = document.getElementById("upgradationSection");

            if (section && !section.classList.contains("d-none")) {
                document.getElementById("vehicle_type_cd_error_upg").innerText =
                    "";
                document.getElementById(
                    "equipment_type_cd_error_upg",
                ).innerText = "";

                const vehicles = document.querySelectorAll(
                    ".vehicle-checkbox:checked",
                );
                const equipments = document.querySelectorAll(
                    ".equipment-checkbox:checked",
                );

                if (vehicles.length === 0) {
                    document.getElementById(
                        "vehicle_type_cd_error_upg",
                    ).innerText = "Please select at least one vehicle";
                    hasError = true;
                }

                if (equipments.length === 0) {
                    document.getElementById(
                        "equipment_type_cd_error_upg",
                    ).innerText = "Please select at least one equipment";
                    hasError = true;
                }
            }
        }

        if (projectType === "MTN") {
            const section = document.getElementById("maintenanceSection");

            if (section && !section.classList.contains("d-none")) {
                document.getElementById("vehicle_type_cd_error").innerText = "";
                document.getElementById("equipment_type_cd_error").innerText =
                    "";

                const vehicles = document.querySelectorAll(
                    ".vehicle-checkbox:checked",
                );
                const equipments = document.querySelectorAll(
                    ".equipment-checkbox:checked",
                );

                if (vehicles.length === 0) {
                    document.getElementById("vehicle_type_cd_error").innerText =
                        "Please select at least one vehicle";
                    hasError = true;
                }

                if (equipments.length === 0) {
                    document.getElementById(
                        "equipment_type_cd_error",
                    ).innerText = "Please select at least one equipment";
                    hasError = true;
                }
            }
        }
    }

    //by dipshikha
    if (departmentName === "14" || departmentName === "3") {
        //end
        if (projectType === "NEW") {
            if (!dom.road_name.value.trim()) {
                showError(dom.road_name, "Road Name is required");
                hasError = true;
            }

            if (
                !dom.road_length.value ||
                parseFloat(dom.road_length.value) <= 0
            ) {
                showError(dom.road_length, "Enter valid length");
                hasError = true;
            }

            if (!dom.road_category_new.value.trim()) {
                showError(road_category_new, "Please select Road Category");
                hasError = true;
            }

            if (!dom.road_type_new.value.trim()) {
                showError(road_type_new, "Please select Road Type");
                hasError = true;
            }

            if (!dom.road_owner_new.value.trim()) {
                showError(road_owner_new, "Please select Road Owner");
                hasError = true;
            }
        }

        if (projectType === "UPG" && rowCount === 0) {
            Swal.fire({
                title: "Warning",
                text: "Please add at least one asset.",
                icon: "warning",
                confirmButtonText: "Okay",
            });
            hasError = true;
        }

        if (projectType === "MTN" && mtnRowCount === 0) {
            Swal.fire({
                title: "Warning",
                text: "Please add at least one asset.",
                icon: "warning",
                confirmButtonText: "Okay",
            });
            hasError = true;
        }

        if (projectType === "UPG") {
            const newRoadDetailsForm =
                document.getElementById("newRoadDetailsForm");

            if (
                newRoadDetailsForm &&
                newRoadDetailsForm.style.display !== "none"
            ) {
                if (!dom.road_category.value.trim()) {
                    showError(road_category, "Please select Road Category");
                    hasError = true;
                }

                if (!dom.road_type.value.trim()) {
                    showError(road_type, "Please select Road Type");
                    hasError = true;
                }

                if (!dom.road_owner.value.trim()) {
                    showError(road_owner_new, "Please select Road Owner");
                    hasError = true;
                }
            }
        }

        if (projectType === "UPG" || projectType === "NEW") {
            if (kmlFile && kmlCard.style.display !== "none") {
                const existingKml = document.querySelector(
                    'input[name="existing_kml_file"]',
                );

                // No new file and no existing database KML
                if (kmlFile.files.length === 0 && !existingKml) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "warning",
                        title: "KML File Required",
                        text: "Please upload a KML file for this road.",
                    });
                    return;
                }

                // Validate only newly selected KML
                if (kmlFile.files.length > 0) {
                    const file = kmlFile.files[0];
                    const extension = file.name.split(".").pop().toLowerCase();

                    if (extension !== "kml") {
                        e.preventDefault();
                        Swal.fire({
                            icon: "error",
                            title: "Invalid File",
                            text: "Only KML files are allowed.",
                        });
                        kmlFile.value = "";
                        return;
                    }
                }
            }
        }

        if (projectType === "UPG") {
            if (newAssetsForm && newAssetsForm.style.display !== "none") {
                const roadNameInput = newAssetsForm.querySelector(
                    'input[name="new_road_name"]',
                );
                const roadLengthInput = newAssetsForm.querySelector(
                    'input[name="road_length"]',
                );

                const roadNameEmpty = !roadNameInput?.value.trim();
                const roadLengthEmpty = !roadLengthInput?.value.trim();

                const roadLengthFilled = roadLengthInput?.value.trim() !== "";

                if (
                    roadNameEmpty ||
                    roadLengthEmpty ||
                    (roadLengthFilled && roadNameEmpty)
                ) {
                    if (roadNameEmpty) {
                        showError(roadNameInput, "Road Name is required");
                    }

                    if (roadLengthEmpty) {
                        showError(roadLengthInput, "Road Length is required");
                    }

                    hasError = true;
                }
            }
        }
    }

    if (departmentName === "6") {
        if (projectType === "NEW") {
            if (!dom.asset_geo_location_lat.value) {
                showError(dom.asset_geo_location_lat, "Latitude is required");
                hasError = true;
            }

            if (!dom.asset_geo_location_lng.value) {
                showError(dom.asset_geo_location_lng, "Longitude is required");
                hasError = true;
            }

            const buildingCategory = document.querySelector(
                'input[name="building_class_cd"]:checked',
            );
            if (!buildingCategory) {
                const firstRadio = document.querySelector(
                    'input[name="building_class_cd"]',
                );
                showError(firstRadio, "Building category is required");
                hasError = true;
            }

            if (!dom.building_location_cd.value) {
                showError(dom.building_location_cd, "Location is required");
                hasError = true;
            }
        }

        if (projectType === "MTN") {
            const category = document.getElementById("buildingCategory").value;
            const building = document.getElementById("maintBuildings").value;

            if (!category) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Building Category Required",
                    text: "Please select a Building Category.",
                });
                return;
            }

            if (!building) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Building Required",
                    text: "Please select a Building.",
                });
                return;
            }
        }

        if (projectType === "UPG") {
            const categoryUpg = document.getElementById(
                "buildingCategoryUpgradation",
            ).value;
            const buildingUpg = document.getElementById("upgBuildings").value;
            const section = document.getElementById("housingContainerOtherUpg");

            if (!categoryUpg) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Building Category Required",
                    text: "Please select a Building Category.",
                });
                return;
            }

            if (!buildingUpg) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Building Required",
                    text: "Please select a Building.",
                });
                return;
            }

            if (section && !section.classList.contains("d-none")) {
                const buildingType =
                    document.getElementById("building_type_upg");
                const dept = document.getElementById("owning_dept_upg");
                const quarter = document.getElementById("quarter_no");
                const buildingCategoryUpg = document.getElementById(
                    "buildingCategoryUpg",
                ).value;
                const quarterContainer =
                    document.getElementById("quarterContainer");

                // clear previous errors
                document.getElementById("building_type_cd_error").innerText =
                    "";
                document.getElementById("owning_dept_error").innerText = "";
                //by dipshikha
                document.getElementById("buildingCategoryUpg_error").innerText =
                    "";
                //end
                document.getElementById("quarter_no_error").innerText = "";

                if (!buildingType.value) {
                    document.getElementById(
                        "building_type_cd_error",
                    ).innerText = "Building Type is required";
                    hasError = true;
                }

                if (!dept.value) {
                    document.getElementById("owning_dept_error").innerText =
                        "Owning Department is required";
                    hasError = true;
                }

                if (!buildingCategoryUpg) {
                    document.getElementById(
                        "buildingCategoryUpg_error",
                    ).innerText = "Building Category is required";
                    //by dipshikha
                    hasError = true;
                    //end
                }

                if (!quarterContainer.classList.contains("d-none")) {
                    if (!quarter.value) {
                        document.getElementById("quarter_no_error").innerText =
                            "Field is required";
                        hasError = true;
                    }
                }
            }
        }
    }

    if (!hasError) {
        //by dipshikha
        if (dom.division.disabled) {
            dom.division.disabled = false;
        }
        //end
        document.getElementById("myForm").submit();
    } else {
        e.preventDefault();
    }
});

document.querySelectorAll("input, select").forEach((el) => {
    el.addEventListener("input", () => {
        el.style.borderColor = "";
        const error = el.parentNode.querySelector(".dynamic-error");
        if (error) error.remove();
    });
});

const formatDate = (dateStr) => {
    return dateStr ? dateStr.split(" ")[0] : "";
};

function ensureDbImageContainer() {
    let container = document.getElementById("dbImagesPreview");

    if (!container) {
        const uploadSection =
            document.getElementById("imagesPreview").parentElement;

        container = document.createElement("div");
        container.id = "dbImagesPreview";
        container.className = "row mt-2";

        uploadSection.insertBefore(
            container,
            document.getElementById("imagesPreview"),
        );
    }

    return container;
}

function ensureDbDocumentContainer() {
    let container = document.getElementById("dbDocumentsPreview");

    if (!container) {
        const uploadSection = document.getElementById(
            "asset_document_container",
        );

        container = document.createElement("div");
        container.id = "dbDocumentsPreview";
        container.className = "mt-2";

        uploadSection.appendChild(container);
    }

    return container;
}

function loadImages(images = []) {
    const preview = ensureDbImageContainer();
    preview.innerHTML = "";

    images.forEach((img) => {
        const col = document.createElement("div");
        col.className = "col-md-3 position-relative mb-2";

        col.innerHTML = `
            <img src="${img.image_url}"
                style="
                    width: 100%;
                    height: 100px;
                    object-fit: contain;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    display: block;
                " />

            <button type="button"
                class="btn btn-sm btn-danger position-absolute remove-db-image"
                data-id="${img.id}"
                style="
                    top: 5px;
                    right: 5px;
                    width: 22px;
                    height: 22px;
                    padding: 0;
                    line-height: 18px;
                    border-radius: 0;
                ">
                ×
            </button>

            <input type="hidden" name="existing_images[]" value="${img.id}">
        `;

        preview.appendChild(col);
    });
}

function loadDocuments(documents = []) {
    const container = ensureDbDocumentContainer();
    container.innerHTML = "";

    loadedDocuments = documents; // keep full state

    const docMapReverse = {
        SO: "workOrder",
        WO: "projectPlan",
        WP: "drpDocument",
        PA: "designDoc",
    };

    existingDocTypes = documents
        .map((doc) => doc.doc_catg)
        .map((code) => docMapReverse[code])
        .filter(Boolean);

    const fileInputs = document.querySelectorAll(
        "#asset_document_container input[type='file']",
    );

    fileInputs.forEach((input) => {
        const docType = input.name;

        const msgId = "msg_" + docType;

        if (existingDocTypes.includes(docType)) {
            input.disabled = true;
            input.classList.add("bg-light");

            let msg = document.getElementById(msgId);
            if (!msg) {
                msg = document.createElement("small");
                msg.id = msgId;
                msg.className = "text-danger d-block mt-1";
                input.parentNode.appendChild(msg);
            }

            msg.innerText =
                "Already uploaded. Remove existing document to upload new one.";
        } else {
            input.disabled = false;
            input.classList.remove("bg-light");

            const msg = document.getElementById(msgId);
            if (msg) msg.remove();
        }
    });

    renderDocumentList(documents);
}

function renderDocumentList(documents) {
    const container = ensureDbDocumentContainer();
    container.innerHTML = "";

    documents.forEach((doc) => {
        const div = document.createElement("div");
        div.className =
            "d-flex align-items-center border p-2 mb-2 position-relative";

        div.innerHTML = `
            <a href="${doc.file_url}" target="_blank" class="me-3">
                <i class="fa fa-file-pdf text-danger"></i>
                ${doc.label ?? "Document"}
            </a>

            <button type="button"
                class="btn btn-sm btn-danger remove-db-doc"
                data-id="${doc.id}"
                data-type="${doc.doc_catg}"
                style="position:absolute; right:5px; top:5px; width:22px; height:22px; padding:0;">
                ×
            </button>

            <input type="hidden" name="existing_documents[]" value="${doc.id}">
        `;

        container.appendChild(div);
    });
}

function loadKmlFile(kmlFile) {
    loadedKmlFile = kmlFile;

    const fileInput = document.getElementById("road_kml_file");

    // Create container dynamically if it doesn't exist
    let container = document.getElementById("existingKmlContainer");

    if (!container) {
        container = document.createElement("div");
        container.id = "existingKmlContainer";
        container.className = "mt-2";

        fileInput.parentElement.appendChild(container);
    }

    container.innerHTML = "";

    if (kmlFile) {
        const msgId = "msg_road_kml_file";

        let msg = document.getElementById(msgId);

        if (!msg) {
            msg = document.createElement("small");
            msg.id = msgId;
            msg.className = "text-danger d-block mt-1";
            fileInput.parentNode.appendChild(msg);
        }

        msg.innerText =
            "Already uploaded. Remove existing KML file to upload a new one.";

        fileInput.disabled = true;
        fileInput.classList.add("bg-light");

        const fileName = kmlFile.file_path.split(/[\\/]/).pop();

        container.innerHTML = `
            <div class="d-flex align-items-center border p-2 mb-2 position-relative">
                <span class="me-3">
                    <i class="fa fa-file text-success"></i>
                    ${fileName}
                </span>

                <button type="button"
                    class="btn btn-sm btn-danger remove-db-kml"
                    data-id="${kmlFile.id}"
                    style="position:absolute; right:5px; top:5px; width:22px; height:22px; padding:0;">
                    ×
                </button>

                <input type="hidden" name="existing_kml_file" value="${kmlFile.id}">
            </div>
        `;
    } else {
        fileInput.disabled = false;
        fileInput.classList.remove("bg-light");
        const msg = document.getElementById("msg_road_kml_file");
        if (msg) {
            msg.remove();
        }
    }
}

document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-db-image")) {
        const imageId = e.target.getAttribute("data-id");

        // remove from UI instantly
        const parent = e.target.closest(".col-md-3");
        if (parent) parent.remove();

        // store deleted ids
        let input = document.getElementById("deleted_images");

        if (!input) {
            input = document.createElement("input");
            input.type = "hidden";
            input.id = "deleted_images";
            input.name = "deleted_images";
            input.value = "[]";
            document.querySelector("form").appendChild(input);
        }

        let deleted = JSON.parse(input.value || "[]");

        if (!deleted.includes(imageId)) {
            deleted.push(imageId);
        }

        input.value = JSON.stringify(deleted);
    }
});

document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-db-doc")) {
        const docId = e.target.getAttribute("data-id");
        const docType = e.target.getAttribute("data-type");

        const parent = e.target.closest(".d-flex");
        if (parent) parent.remove();

        // update deleted list
        let input = document.getElementById("deleted_documents");

        if (!input) {
            input = document.createElement("input");
            input.type = "hidden";
            input.id = "deleted_documents";
            input.name = "deleted_documents";
            input.value = "[]";
            document.querySelector("form").appendChild(input);
        }

        let deleted = JSON.parse(input.value || "[]");

        if (!deleted.includes(docId)) {
            deleted.push(docId);
        }

        input.value = JSON.stringify(deleted);

        // ✅ IMPORTANT: remove from state
        loadedDocuments = loadedDocuments.filter((d) => d.id != docId);

        // 🔥 re-render everything
        loadDocuments(loadedDocuments);
    }
});

document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-db-kml")) {
        const kmlId = e.target.dataset.id;

        let input = document.getElementById("deleted_kml");

        if (!input) {
            input = document.createElement("input");
            input.type = "hidden";
            input.id = "deleted_kml";
            input.name = "deleted_kml";
            document.querySelector("form").appendChild(input);
        }

        input.value = kmlId;

        loadedKmlFile = null;

        loadKmlFile(null);
    }
});
