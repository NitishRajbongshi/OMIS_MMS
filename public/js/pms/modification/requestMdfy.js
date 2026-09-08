const housingContainer = document.getElementById("housingContainer");
const housingContainerOther = document.getElementById("housingContainerOther");
const housingContainerUpg = document.getElementById("housingContainerUpg");
const housingContainerOtherUpg = document.getElementById("housingContainerOtherUpg",);
const buildings = document.getElementById("maintBuildings");
const upgBuildings = document.getElementById("upgBuildings");
const buildingCategory = document.getElementById("buildingCategory");
const buildingCategoryUpgradation = document.getElementById("buildingCategoryUpgradation");
const buildingTypeDropdown = document.getElementById("building_type_upg");
const deptDropdown = document.getElementById("owning_dept_upg");
const categoryDropdown = document.getElementById("buildingCategoryUpg");
const labelUpg = document.querySelector("label[for='quarter_no']");
const inputUpg = document.getElementById("quarter_no");

housingContainer.classList.add("d-none");
housingContainerOther.classList.add("d-none");

quarterContainer.classList.add("d-none");

housingContainerUpg.classList.add("d-none");
housingContainerOtherUpg.classList.add("d-none");

document.getElementById("rdo_yes_maint").onclick = () => false;
document.getElementById("rdo_no_maint").onclick = () => false;

$(document).on('click', '.rejectBtn', function () {
    let requestId = $(this).data('id');
    $('#reject_request_id').val(requestId);
});

$(document).on('click', '.approveBtn', function () {
    let requestId = $(this).data('id');
    $('#accept_request_id').val(requestId);
});

$('#rejectModificationForm').on('submit', function(e) {
    e.preventDefault();


    let requestId = $('#reject_request_id').val();
    let reason = $('textarea[name="reject_reason"]').val().trim();

    if (reason === '') {
        showDashboardModal('Reason is required.');
        return;
    }

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
            if (response.status === 200) {
                $('#rejectModificationModal').modal('hide');
                $('#rejectModificationForm')[0].reset();
                $('#reject_request_id').val('');
                showSuccessModal(response.message);
            } else {
                showDashboardModal(response.message);
            }
        },
        error: function() {
            showDashboardModal('Something went wrong.');
        }
    });
});

$('#acceptModificationForm').on('submit', function(e) {
    e.preventDefault();

    let requestId = $('#accept_request_id').val();
    let reason = $('textarea[name="approve_remark"]').val().trim();

    if (reason === '') {
        showDashboardModal('Remark is required.');
        return;
    }

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function(response) {
            if (response.status === 200) {
                $('#acceptModificationModal').modal('hide');
                $('#acceptModificationForm')[0].reset();
                $('#accept_request_id').val('');
                showSuccessModal(response.message);
            } else {
                showDashboardModal(response.message);
            }
        },
        error: function() {
            showDashboardModal('Something went wrong.');
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
        new bootstrap.Tooltip(el);
    });
});
async function loadDataIntoForm(draft, changesData, workItems, itemOfWorks, documents = [], department) {

    const editText = document.getElementById("editModeText");
    editText.innerText = `Project is under edit mode for Project Id: ${draft.project_cd}`;

    const rawFields = changesData?.project_fields || [];
    const editableFields = rawFields.filter(f =>
        f !== "creation_of_new_asset" &&
        f !== "redefine_existing_asset"
    );
    const isNewAsset = rawFields.includes("creation_of_new_asset");
    const isRedefineAsset = rawFields.includes("redefine_existing_asset");

    const dom = {
        projectTypeSelect: document.getElementById("projectTypeSelect"),
        project_name: document.getElementById("project_name"),
        divisionSelect: document.getElementById("division_cd"),
        sub_division_cd: document.getElementById("sub_division_cd"),
        owner_dept_cd: document.getElementById("owner_dept_cd"),
        project_start_date: document.getElementById("project_start_date"),
        project_end_date: document.getElementById("project_end_date"),
        est_proj_cost: document.getElementById("est_proj_cost"),
        defect_liability_period: document.getElementById("defect_liability_period"),
        work_order_amount: document.getElementById("work_order_amount"),
        addConstructor: document.getElementById("add-constructor"),
		//by dipshikha
        work_order_no: document.getElementById("work_order_no"),
        work_order_issue_date: document.getElementById("work_order_issue_date"),
        scheme_cd: document.getElementById("scheme_cd"),
        //end

        // road section
        slnewRdNew: document.getElementById("slnewRdNew"),
        rdLength_new: document.getElementById("rdLength"),


        // upgradation
        rdoRoadWithSubAsset: document.getElementById("rdoRoadWithSubAsset"),
        rdoSubAsset: document.getElementById("rdoSubAsset"),
        new_road_name: document.getElementById("new_road_name"),
        road_length: document.getElementById("road_length"),
        new_culverts: document.getElementById("new_culverts"),
        new_bridges: document.getElementById("new_bridges"),
        new_retaining_walls: document.getElementById("new_retaining_walls"),
        new_pavements: document.getElementById("new_pavements"),

        rdo_yes: document.getElementById("rdo_yes"),
        rdo_no: document.getElementById("rdo_no"),
        asset_geo_location: document.getElementById("asset_geo_location"),
        asset_geo_location_lat: document.getElementById("asset_geo_location_lat"),
        asset_geo_location_lng: document.getElementById("asset_geo_location_lng"),
        residential: document.getElementById("residential"),
        nonResidential: document.getElementById("nonResidential"),
        rental: document.getElementById("rental"),
        building_location_cd: document.getElementById("building_location_cd"),

        buildingCategory: document.getElementById("buildingCategory"),
        maintBuildings: document.getElementById("maintBuildings"),
        buildingCategoryUpgradation: document.getElementById("buildingCategoryUpgradation"),
        upgBuildings: document.getElementById("upgBuildings"),
        building_type_upg: document.getElementById("building_type_upg"),
        owning_dept_upg: document.getElementById("owning_dept_upg"),
        buildingCategoryUpg: document.getElementById("buildingCategoryUpg"),
        quarter_no: document.getElementById("quarter_no"),
        rdo_yes: document.getElementById("rdo_yes"),
        rdo_no: document.getElementById("rdo_no"),
        asset_geo_location: document.getElementById("asset_geo_location"),
        asset_geo_location_lat: document.getElementById("asset_geo_location_lat"),
        asset_geo_location_lng: document.getElementById("asset_geo_location_lng"),
        residential: document.getElementById("residential"),
        nonResidential: document.getElementById("nonResidential"),
        rental: document.getElementById("rental"),
        building_location_cd: document.getElementById("building_location_cd"),
    };

    if (dom.projectTypeSelect && draft.project_type_cd) {
        dom.projectTypeSelect.innerHTML = `
            <option value="${draft.project_type_cd}" selected>
                ${draft.proj_type_descr}
            </option>
        `;
        dom.projectTypeSelect.readOnly = true;
        dom.projectTypeSelect.dispatchEvent(new Event("change"));
    }

    if (dom.project_name) dom.project_name.textContent = draft.project_name || "";
    if (dom.owner_dept_cd) {
        dom.owner_dept_cd.value = draft.owner_dept_cd || "";
        dom.owner_dept_cd.readOnly = true;
    }

    if (draft.division_cd && dom.divisionSelect) {
        dom.divisionSelect.value = draft.division_cd;
        dom.divisionSelect.disabled = true;
    }

    if (draft.sub_division_cd && dom.sub_division_cd) {
        const select = dom.sub_division_cd;
        select.dataset.selected = draft.sub_division_cd;

        loadSubDivisions(draft.division_cd,draft.sub_division_cd,
            function () {
                dom.sub_division_cd.value = draft.sub_division_cd;
                dom.sub_division_cd.dispatchEvent(new Event("change"));

                if (String(department) === "6") {
                    if (String(draft.project_type_cd).trim().toUpperCase() === "MTN") {
                        populateMaintenanceBuildingCategory("buildingCategory");
                    }

                    if (String(draft.project_type_cd).trim().toUpperCase() === "UPG") {
                        populateMaintenanceBuildingCategory("buildingCategoryUpgradation");
                    }
                }
                if (String(department) === "15"){
                    if (String(draft.project_type_cd).trim().toUpperCase() === "MTN") {
                        populateVehicleListMaint(draft.sub_division_cd, "vehicleListMaint");
                        populateEquipmentListMaint(draft.sub_division_cd,"equipmentListMaint");
                    }

                    if (String(draft.project_type_cd).trim().toUpperCase() === "UPG") {
                        populateVehicleList(draft.sub_division_cd, "vehicleListUpg");
                        populateEquipmentList(draft.sub_division_cd,"equipmentListUpg");
                    }
                }
            },true
        );

        const observer = new MutationObserver(() => {

            const saved = dom.sub_division_cd.dataset.selected;

            if (saved && dom.sub_division_cd.value !== saved) {
                dom.sub_division_cd.value = saved;
                 dom.sub_division_cd.dispatchEvent(new Event("change"));
                  observer.disconnect();
                   dom.sub_division_cd.readOnly = true;
            }
        });

        observer.observe(dom.sub_division_cd, {
            childList: true,
            subtree: true
        });
    }


    if (dom.project_start_date)
        dom.project_start_date.value = formatDate(draft.project_start_date);

    if (dom.project_end_date)
        dom.project_end_date.textContent = formatDate(draft.project_end_date);

    if (dom.est_proj_cost)
        dom.est_proj_cost.textContent = draft.est_proj_cost || "";

    if (dom.defect_liability_period)
        dom.defect_liability_period.textContent = draft.defect_liability_period || "";

    if (dom.work_order_amount)
        dom.work_order_amount.textContent = draft.work_order_amount || "";

	//by dipshikha
    if (dom.work_order_no)
        dom.work_order_no.textContent = draft.work_order_no || "";

    if (dom.work_order_issue_date)
        dom.work_order_issue_date.textContent = formatDate(draft.work_order_issue_date) || "";

    if (draft.scheme_cd && dom.scheme_cd) {
        dom.scheme_cd.value = draft.scheme_cd;
        dom.scheme_cd.dispatchEvent(new Event("change"));
    }

    const others = draft.others;

    //by dipshikha
    if (String(department) === "14" || String(department) === "3") {
        //end

        if (String(draft.project_type_cd).trim().toUpperCase() === "NEW") {
            $("#divNewHousing").hide();
            $("#divNewRd").show();

            const newRoadName = (others.new_road_name ?? "").toString().trim();

            if (newRoadName !== "") {

                if (dom.slnewRdNew) dom.slnewRdNew.textContent = newRoadName;

                if (dom.rdLength_new)
                    dom.rdLength_new.textContent = parseFloat(others.new_road_length) || 0;

                const opt1 = document.getElementById("opt1");
                if (opt1) {
                    opt1.checked = false;
                }
            }
        }


        if (String(draft.project_type_cd).trim().toUpperCase() === "UPG") {
            $("#divUpgHousing").hide();
            $("#divUpgMech").hide();
            $("#divUpgRd").show();

            const upg = others.upgradation || {};

            const hasUpgradedRoads = Array.isArray(upg.upgraded_roads) && upg.upgraded_roads.length > 0;

            if (hasUpgradedRoads) {
                if (dom.rdoRoadWithSubAsset) {
                    dom.rdoRoadWithSubAsset.checked = true;
                    dom.rdoSubAsset.disabled = true;
                    dom.rdoRoadWithSubAsset.click();
                }
            } else {
                if (dom.rdoSubAsset) {
                    dom.rdoSubAsset.checked = true;
                    dom.rdoRoadWithSubAsset.disabled = true;
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
                item.start_chainage !== '' &&
                item.end_chainage != null &&
                item.end_chainage !== ''
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
                : '';

                tr.innerHTML = `
                    <td>
                        ${item.parent_asset_id ?? ""}
                        <input type="hidden" name="roads[]" value="${item.parent_asset_id}">
                    </td>

                    ${chainageColumns}

                    <td>
                        ${(item.culverts || []).map(c => `
                            <input type="hidden" name="culverts_${rowIndex}[]" value="${c}">
                            <span class="badge bg-info me-1">${c}</span>
                        `).join("")}
                    </td>

                    <td>
                        ${(item.bridges || []).map(b => `
                            <input type="hidden" name="bridges_${rowIndex}[]" value="${b}">
                            <span class="badge bg-warning text-dark me-1">${b}</span>
                        `).join("")}
                    </td>

                    <td>
                        ${(item.retaining_walls || []).map(w => `
                            <input type="hidden" name="walls_${rowIndex}[]" value="${w}">
                            <span class="badge bg-secondary me-1">${w}</span>
                        `).join("")}
                    </td>

                    <td>
                        <button type="button" class="btn btn-sm btn-danger deleteRowBtn">Delete</button>
                    </td>
                `;

                tbody.appendChild(tr);

                tr.querySelector(".deleteRowBtn").addEventListener("click", () => {
                    tr.remove();
                    renumberRows();
                });
            });

            document.getElementById("rowCount").value = rowIndex;

            const newAsset = upg.new_asset;

            const newAssetsForm = document.getElementById("newAssetsForm");
            const roadName = newAsset.road_name || "";
            const roadLength = newAsset.road_length || "";

            if (newAsset) {
                if (roadName || roadLength) {
                    if (newAssetsForm) {
                        newAssetsForm.style.display = "block";
                    }

                    if (dom.new_road_name) {
                        dom.new_road_name.textContent = roadName;
                    }

                    if (dom.road_length) {
                        dom.road_length.textContent = roadLength;
                    }
                } else {
                    if (newAssetsForm) {
                        newAssetsForm.style.display = "none";
                    }
                }
            } else {
                if (newAssetsForm) {
                    newAssetsForm.style.display = "none";
                }
            }

            if (roadName == null || roadName.trim() === "") {
                $("#newRoadNameCol").addClass("d-none").removeClass("d-flex");
                $("#new_road_nameDiv").removeClass("d-none");
            }

            if (roadLength == null || Number(roadLength) === 0) {
                $("#roadLengthCol").addClass("d-none").removeClass("d-flex");
                $("#road_length_div").removeClass("d-none");
            }

            calculateTotalRoadLength();

            if (upg.road_sequence && upg.road_sequence.length > 0) {

                const priorityCard = document.getElementById("priorityCard");
                const roadPriorityList = document.getElementById("roadPriorityList");

                priorityCard.style.display = "block";
                roadPriorityList.innerHTML = "";

                upg.road_sequence
                    .sort((a, b) => a.sequence - b.sequence)
                    .forEach((item) => {

                        let displayRoadName = item.road_id;
                        let isNewRoad = false;

                        // New road
                        if (upg.new_asset && upg.new_asset.road_id == item.road_id) {
                            displayRoadName = upg.new_asset.road_name;
                            isNewRoad = true;
                        }

                        const div = document.createElement("div");

                        div.className = "list-group-item d-flex align-items-center";

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

        if (String(draft.project_type_cd).trim().toUpperCase() === "MTN") {
            $("#divMtnMech").hide();
            $("#divMtnHousing").hide();
            $("#divMtnRd").show();


            const mtn = others.maintenance || {};
            const assetList = mtn.asset_dtls?.asset_list || [];
            const subAssets = mtn.sub_asset_dtls || [];
            const rowBase = assetList.length ? assetList : ["--"];

            const tbody = document.querySelector("#mtnAssetTable tbody");
            if (!tbody) return;

            tbody.innerHTML = "";

            mnt_rowIndex = 0;

            rowBase.forEach((road) => {

                mnt_rowIndex++;

                const culverts = subAssets.find(s => s.sub_asset_type_cd === 0)?.sub_asset_list || [];
                const bridges  = subAssets.find(s => s.sub_asset_type_cd === 1)?.sub_asset_list || [];
                const retaining_walls = subAssets.find(s => s.sub_asset_type_cd === 16)?.sub_asset_list || [];

                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <td>
                        <input type="hidden" name="roads_mnt${mnt_rowIndex}[]" value="${road === '--' ? '' : road}">
                        <span class="badge bg-info me-1">${road === '--' ? 'N/A' : road}</span>
                    </td>

                    <td>
                        ${(culverts || []).map(c => `
                            <input type="hidden" name="culverts_mnt_${mnt_rowIndex}[]" value="${c}">
                            <span class="badge bg-info me-1">${c}</span>
                        `).join("")}
                    </td>

                    <td>
                        ${(bridges || []).map(b => `
                            <input type="hidden" name="bridges_mnt_${mnt_rowIndex}[]" value="${b}">
                            <span class="badge bg-warning text-dark me-1">${b}</span>
                        `).join("")}
                    </td>

                    <td>
                        ${(retaining_walls || []).map(w => `
                            <input type="hidden" name="walls_mnt_${mnt_rowIndex}[]" value="${w}">
                            <span class="badge bg-secondary me-1">${w}</span>
                        `).join("")}
                    </td>

                    <td>
                        <button type="button" class="btn btn-sm btn-danger deleteRowBtn">Delete</button>
                    </td>
                `;

                tbody.appendChild(tr);


                tr.querySelector(".deleteRowBtn").addEventListener("click", () => {
                    tr.remove();
                });
            });

            document.getElementById("mnt_rowCount").value = mnt_rowIndex;

        }
    }

    if (String(department) === "6") {
        if (String(draft.project_type_cd).trim().toUpperCase() === "NEW") {
            $("#divNewHousing").show();
            $("#divNewRd").hide();
            $("#kmlCard").hide();
        }

        if (String(draft.project_type_cd).trim().toUpperCase() === "MTN") {
            $("#divMtnHousing").show();
            $("#divMtnRd").hide();
            $("#divMtnMech").hide();
            $("#kmlCard").hide();

            const maint = draft.others.maintenance_data || {};

            if (maint.building_details && dom.buildingCategory) {
                let val = String(maint.building_details.building_class_cd ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.buildingCategory.options];
                    let match = options.find(o => o.value === val);

                    if (match) {
                        dom.buildingCategory.value = val;
                        dom.buildingCategory.dispatchEvent(new Event("change"));
                        observer.disconnect();
                    }
                });

                observer.observe(dom.buildingCategory, {
                    childList: true
                });
            }

            if (maint.building_details && dom.maintBuildings) {
                let val = String(maint.building_details.building_system_cd ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.maintBuildings.options];
                    let match = options.find(o => o.value === val);

                    if (match) {
                        dom.maintBuildings.value = val;
                        dom.maintBuildings.dispatchEvent(new Event("change"));
                        observer.disconnect();
                    }
                });

                observer.observe(dom.maintBuildings, {
                    childList: true
                });
            }
        }

        if (String(draft.project_type_cd).trim().toUpperCase() === "UPG") {
            $("#divUpgHousing").show();
            $("#divUpgMech").hide();
            $("#divUpgRd").hide();
            $("#kmlCard").hide();

            const upg = draft.others.upgradation_data || {};

            if (upg.building_details && dom.buildingCategoryUpgradation) {
                let val = String(upg.building_details.actual_building_class_cd ?? "");
                const observer = new MutationObserver(() => {
                    let options = [...dom.buildingCategoryUpgradation.options];
                    let match = options.find(o => o.value === val);

                    if (match) {
                        dom.buildingCategoryUpgradation.value = val;
                        dom.buildingCategoryUpgradation.dispatchEvent(new Event("change"));
                        observer.disconnect();
                    }
                });

                observer.observe(dom.buildingCategoryUpgradation, {
                    childList: true
                });
            }

            if (upg.building_details && dom.upgBuildings) {
                let val = String(upg.building_details.building_id ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.upgBuildings.options];
                    let match = options.find(o => o.value === val);

                    if (match) {
                        dom.upgBuildings.value = val;
                        dom.upgBuildings.dispatchEvent(new Event("change"));
                        observer.disconnect();
                    }
                });

                observer.observe(dom.upgBuildings, {
                    childList: true
                });
            }


            if (upg.building_details.building_type_cd && dom.building_type_upg) {
                let val = String(upg.building_details.building_type_cd ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.building_type_upg.options];
                    let match = options.find(o => o.value === val);

                    if (match) {
                        dom.building_type_upg.value = val;
                        observer.disconnect();
                    }
                });

                observer.observe(dom.building_type_upg, {
                    childList: true
                });
            }

            if (upg.building_details.owning_dept_cd && dom.owning_dept_upg) {
                let val = String(upg.building_details.owning_dept_cd ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.owning_dept_upg.options];
                    let match = options.find(o => o.value === val);

                    if (match) {
                        dom.owning_dept_upg.value = val;
                        observer.disconnect();
                    }
                });

                observer.observe(dom.owning_dept_upg, {
                    childList: true
                });
            }

            if (upg.building_details.building_class_cd && dom.buildingCategoryUpg) {
                let val = String(upg.building_details.building_class_cd ?? "");

                const observer = new MutationObserver(() => {
                    let options = [...dom.buildingCategoryUpg.options];
                    let match = options.find(o => o.value === val);

                    if (match) {
                        dom.buildingCategoryUpg.value = val;
                        dom.buildingCategoryUpg.dispatchEvent(new Event("change"));
                        observer.disconnect();
                    }
                });

                observer.observe(dom.buildingCategoryUpg, {
                    childList: true
                });
            }

            if(upg.building_details.building_class_cd === '0'){
                if (upg.building_details.quarter_no && dom.quarter_no) {
                    dom.quarter_no.value = upg.building_details.quarter_no;
                }
            }else{
                if (upg.building_details.building_name && dom.quarter_no) {
                    dom.quarter_no.value = upg.building_details.building_name;
                }
            }

            if (upg.building_details.is_maintained_by_npwd) {

                const val = String(upg.building_details.is_maintained_by_npwd);

                const yes = document.getElementById('rdo_yes_upg');
                const no = document.getElementById('rdo_no_upg');

                if (!yes || !no) return;

                if (val === 'Y') {
                    yes.checked = true;
                    no.checked = false;
                } else {
                    no.checked = true;
                    yes.checked = false;
                }

                const lockRadio = (el, fixedValue) => {
                    Object.defineProperty(el, "checked", {
                        get: () => fixedValue,
                        set: () => {

                        },
                        configurable: true
                    });
                };

                if (val === 'Y') {
                    lockRadio(yes, true);
                    lockRadio(no, false);
                } else {
                    lockRadio(yes, false);
                    lockRadio(no, true);
                }
            }
        }
    }

    if (String(department) === "15") {
        if (String(draft.project_type_cd).trim().toUpperCase() === "UPG") {
            $("#divUpgHousing").hide();
            $("#divUpgMech").show();
            $("#divUpgRd").hide();
            $("#kmlCard").hide();

            const upg = draft.others.upgradation || {};

            const vehicleIds = (upg.vehicles || []).map(v =>
                String(v.vehicle_asset_cd ?? v)
            );

            const equipmentIds = (upg.equipments || []).map(e =>
                String(e.euipment_cd ?? e)
            );

            const vehicleContainer = document.getElementById("vehicleListUpg");

            if (vehicleContainer) {
                const observer = new MutationObserver(() => {
                    const checkboxes = vehicleContainer.querySelectorAll('input[type="checkbox"]');

                    if (!checkboxes.length) return;

                    checkboxes.forEach(cb => {
                        if (vehicleIds.includes(String(cb.value))) {
                            cb.checked = true;
                        }
                    });

                    observer.disconnect();
                });

                observer.observe(vehicleContainer, {
                    childList: true,
                    subtree: true
                });
            }


            const equipmentContainer = document.getElementById("equipmentListUpg");

            if (equipmentContainer) {
                const observer2 = new MutationObserver(() => {
                    const checkboxes = equipmentContainer.querySelectorAll('input[type="checkbox"]');

                    if (!checkboxes.length) return;

                    checkboxes.forEach(cb => {
                        if (equipmentIds.includes(String(cb.value))) {
                            cb.checked = true;
                        }
                    });

                    observer2.disconnect();
                });

                observer2.observe(equipmentContainer, {
                    childList: true,
                    subtree: true
                });
            }
        }
        if (String(draft.project_type_cd).trim().toUpperCase() === "MTN") {
            $("#divMtnHousing").hide();
            $("#divMtnMech").show();
            $("#divMtnRd").hide();
            $("#kmlCard").hide();

            const mtn = draft.others.maintenance || {};

            const vehicleIds = (mtn.vehicles || []).map(v =>
                String(v.vehicle_asset_cd ?? v)
            );

            const equipmentIds = (mtn.equipments || []).map(e =>
                String(e.euipment_cd ?? e)
            );

            const vehicleContainer = document.getElementById("vehicleListMaint");

            if (vehicleContainer) {
                const observer = new MutationObserver(() => {
                    const checkboxes = vehicleContainer.querySelectorAll('input[type="checkbox"]');

                    if (!checkboxes.length) return;

                    checkboxes.forEach(cb => {
                        if (vehicleIds.includes(String(cb.value))) {
                            cb.checked = true;
                        }
                    });

                    observer.disconnect();
                });

                observer.observe(vehicleContainer, {
                    childList: true,
                    subtree: true
                });
            }


            const equipmentContainer = document.getElementById("equipmentListMaint");

            if (equipmentContainer) {
                const observer2 = new MutationObserver(() => {
                    const checkboxes = equipmentContainer.querySelectorAll('input[type="checkbox"]');

                    if (!checkboxes.length) return;

                    checkboxes.forEach(cb => {
                        if (equipmentIds.includes(String(cb.value))) {
                            cb.checked = true;
                        }
                    });

                    observer2.disconnect();
                });

                observer2.observe(equipmentContainer, {
                    childList: true,
                    subtree: true
                });
            }
        }
    }

    loadDocuments(documents);
    loadKmlFile(draft.kml_file);
    if(workItems){
        loadExistingItems(workItems,itemOfWorks);
    }
}

function renumberRows() {

    const rows = $('#refAssetTable tbody tr');

    rows.each(function(index) {

        const rowNo = index + 1;

        $(this).find("input[name^='culverts_']").each(function () {
            $(this).attr('name', 'culverts_' + rowNo + '[]');
        });

        $(this).find("input[name^='bridges_']").each(function () {
            $(this).attr('name', 'bridges_' + rowNo + '[]');
        });

        $(this).find("input[name^='walls_']").each(function () {
            $(this).attr('name', 'walls_' + rowNo + '[]');
        });

    });

    $('#rowCount').val(rows.length);
}

const formatDate = (dateStr) => {
    return dateStr ? dateStr.split(" ")[0] : "";
};

function ensureDbDocumentContainer() {
    let container = document.getElementById("dbDocumentsPreview");

    if (!container) {
        const uploadSection = document.getElementById("asset_document_container");

        container = document.createElement("div");
        container.id = "dbDocumentsPreview";
        container.className = "mt-2";

        uploadSection.appendChild(container);
    }

    return container;
}

function loadDocuments(documents = []) {
    const container = ensureDbDocumentContainer();
    container.innerHTML = "";

    loadedDocuments = documents; // keep full state

    const docMapReverse = {
        SO: "workOrder",
        WO: "projectPlan",
        WP: "drpDocument",
        PA: "designDoc"
    };

    existingDocTypes = documents
        .map(doc => doc.doc_catg)
        .map(code => docMapReverse[code])
        .filter(Boolean);


    const fileInputs = document.querySelectorAll("#asset_document_container input[type='file']");

    fileInputs.forEach(input => {
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

            msg.innerText = "Already uploaded. Remove existing document to upload new one.";

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

    documents.forEach(doc => {
        const div = document.createElement("div");
        div.className = "d-flex align-items-center border p-2 mb-2 position-relative";

        div.innerHTML = `
            <a href="${doc.file_url}" target="_blank" class="me-3">
                <i class="fa fa-file-pdf text-danger"></i>
                ${doc.label ?? 'Document'}
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

document.addEventListener("click", function (e) {

    if (e.target.classList.contains("remove-db-doc")) {

        const docId = e.target.getAttribute("data-id");
        const docType = e.target.getAttribute("data-type");

        const parent = e.target.closest(".d-flex");
        if (parent) parent.remove();

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

        loadedDocuments = loadedDocuments.filter(d => d.id != docId);

        loadDocuments(loadedDocuments);
    }
});

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

        msg.innerText = "Already uploaded. Remove existing KML file to upload a new one.";

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




document.querySelectorAll("input, select").forEach(el => {
    el.addEventListener("input", () => {
        el.style.borderColor = "";
        const error = el.parentNode.querySelector(".dynamic-error");
        if (error) error.remove();
    });
});

let newWorkItems = [];
let workItemIndex = 0;
let editingItemId = null;
let currentWorkItem = null;
let currentPlans = [];
let existingPredecessors = [];
let deletedWorkItems = [];

function loadExistingItems(workItems,itemOfWorks) {

    existingPredecessors = itemOfWorks;

    workItems.forEach(function(item) {

        let subItems = item.sub_items && item.sub_items.length
            ? item.sub_items.join(", ")
            : "-";

        let workPlanCount = item.work_plans ? item.work_plans.length : 0;

        let row = `
        <tr data-item='${JSON.stringify(item)}' data-existing="true" data-wid="${item.wid_id}">
            <td>${item.name}</td>
            <td>${subItems}</td>
            <td>${item.unit}</td>
            <td>${item.qty}</td>
            <td>
                ${
                    workPlanCount
                    ? `<button type="button" class="btn btn-sm btn-outline-primary viewPlans">
                            View Plans (${workPlanCount})
                    </button>`
                    : "-"
                }
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm deleteRow">Delete</button>
            </td>
        </tr>

        <tr class="planRow" style="display:none;">
            <td colspan="6">
                <div class="planContainer"></div>
            </td>
        </tr>`;

        $("#workItemTable tbody").append(row);
    });
}

$(document).on("click", ".viewPlans", function () {

    let tr = $(this).closest("tr");
    let planRow = tr.next(".planRow");
    let item = tr.data("item");

    if (planRow.is(":visible")) {
        planRow.hide();
        return;
    }

    let html = `
        <table class="table table-bordered table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Predecessor</th>
                </tr>
            </thead>
            <tbody>`;

    item.work_plans.forEach((plan, index) => {
        html += `
            <tr>
                <td>${index + 1}</td>
                <td>${plan.plan_start_date}</td>
                <td>${plan.plan_end_date}</td>
                <td>${plan.precedence_item_name ?? "No Predecessor"}</td>
            </tr>`;
    });

    html += `
            </tbody>
        </table>`;

    planRow.find(".planContainer").html(html);
    planRow.show();
});


$('#item_cd')?.on('change', function () {

    const selectedOption = $(this).find(':selected');
    const subItems = selectedOption.data('sub-items') || [];

    $('#sub_items_checkboxes').empty();

    if (subItems.length > 0) {

        $('#sub_items_container').show();

        subItems.forEach(function (item) {

            $('#sub_items_checkboxes').append(`
                <div class="form-check me-3">
                    <input class="form-check-input"
                           type="checkbox"
                           name="sub_items[]"
                           value="${item.sub_item_cd}"
                           id="sub_${item.sub_item_cd}">

                    <label class="form-check-label" for="sub_${item.sub_item_cd}">
                        ${item.sub_item_name}
                    </label>
                </div>
            `);
        });

    } else {

        $('#sub_items_container').hide();

    }

    // Set Unit
    $('#unit').val(selectedOption.data('unit') || '');
});


$('#item_cd')?.trigger('change');

$("#addWorkItemBtn").click(function () {

    // Clear previous errors
    $("#item_cd_error").text("");
    $("#quantity_error").text("");

    let itemSelect = $("#item_cd");
    let itemCd = itemSelect.val();
    let unit = $("#unit").val();
    let qty = $("#quantity").val();

    let hasError = false;

    if (!itemCd) {
        $("#item_cd_error").text("Please select an item of work.");
        hasError = true;
    }

    if (!qty || parseFloat(qty) <= 0) {
        $("#quantity_error").text("Please enter a valid quantity.");
        hasError = true;
    }

    if (hasError) {
        return;
    }

    let itemName = itemSelect.find(":selected").text();

    // Selected sub items
    let subItems = [];

    $("input[name='sub_items[]']:checked").each(function () {
        subItems.push($(this).next("label").text().trim());
    });

    let item = {
        id: workItemIndex++,
        item_cd: itemCd,
        name: itemName,
        unit: unit,
        qty: qty,
        sub_items: subItems,
        work_plans: [],
        isExisting: false
    };

    if (editingItemId === null) {

        newWorkItems.push(item);
        appendNewWorkItem(item);

    } else {

        item.id = editingItemId;

        let index = newWorkItems.findIndex(x => x.id == editingItemId);

        item.work_plans = newWorkItems[index].work_plans;

        newWorkItems[index] = item;

        updateWorkItemRow(item);

        editingItemId = null;

        $("#addWorkItemBtn").text("Add");
    }

    clearWorkItemForm();
});

$("#item_cd").on("change", function () {
    $("#item_cd_error").text("");
});

$("#quantity").on("input", function () {
    $("#quantity_error").text("");
});

function appendNewWorkItem(item) {

    let subItems = item.sub_items.length
        ? item.sub_items.join(", ")
        : "-";

    let row = `
    <tr data-id="${item.id}">
        <td>${item.name}</td>
        <td>${subItems}</td>
        <td>${item.unit}</td>
        <td>${item.qty}</td>

        <td>
            <button type="button"
                    class="btn btn-success btn-sm addWorkPlan">
                Add Work Plan
            </button>
        </td>

        <td>

            <button type="button"
                    class="btn btn-warning btn-sm editRow">
                Edit
            </button>

            <button type="button"
                    class="btn btn-danger btn-sm deleteRow">
                Delete
            </button>

        </td>
    </tr>`;

    $("#workItemTable tbody").append(row);
}

function clearWorkItemForm() {

    $("#item_cd").val("").trigger("change");

    $("#quantity").val("");

    $("#sub_items_checkboxes").empty();

    $("#sub_items_container").hide();

    $("#unit").val("");
}

$(document).on("click", ".editRow", function () {

    let id = $(this).closest("tr").data("id");

    let item = newWorkItems.find(x => x.id == id);

    if (!item) return;

    editingItemId = id;

    $("#addWorkItemBtn").text("Update");

    $("#item_cd").val(item.item_cd).trigger("change");

    $("#quantity").val(item.qty);

    // Wait until sub-items are rendered
    setTimeout(function () {

        $("input[name='sub_items[]']").prop("checked", false);

        item.sub_items.forEach(function (subItemName) {

            $("input[name='sub_items[]']").each(function () {

                if ($(this).next("label").text().trim() === subItemName) {

                    $(this).prop("checked", true);

                }

            });

        });

    }, 100);

});

function updateWorkItemRow(item) {

    let subItems = item.sub_items.length
        ? item.sub_items.join(", ")
        : "-";

    let workPlanText = item.work_plans.length
        ? `Work Plans (${item.work_plans.length})`
        : "Add Work Plan";

    let row = `
        <td>${item.name}</td>
        <td>${subItems}</td>
        <td>${item.unit}</td>
        <td>${item.qty}</td>

        <td>

            <button type="button"
                    class="btn btn-success btn-sm addWorkPlan">
                ${workPlanText}
            </button>

        </td>

        <td>

            <button type="button"
                    class="btn btn-warning btn-sm editRow">
                Edit
            </button>

            <button type="button"
                    class="btn btn-danger btn-sm deleteRow">
                Delete
            </button>

        </td>
    `;

    $("#workItemTable tbody tr[data-id='" + item.id + "']").html(row);
}

$(document).on("click", ".addWorkPlan", function () {

    let id = $(this).closest("tr").data("id");

    currentWorkItem = newWorkItems.find(x => x.id == id);

    currentPlans = [...currentWorkItem.work_plans];

    $("#currentWorkItemId").val(id);

    $("#planTableBody").empty();

    loadPlans();
    loadPredecessorDropdown(currentWorkItem.item_cd);
    $("#workPlanModal").modal("show");
});

function loadPredecessorDropdown(currentItemCd) {
    $("#precedenceItem").empty().append(
        '<option value="">No Predecessor</option>'
    );

    existingPredecessors.forEach(function(item) {
        if (String(item.item_cd) === String(currentItemCd)) {
            return;
        }

        $("#precedenceItem").append(`
            <option value="${item.item_cd}">
                ${item.item_name}
            </option>
        `);
    });
}

$("#addPlanRow").click(function () {

    // Clear previous errors
    $("#planStart_error").text("");
    $("#planEnd_error").text("");

    let start = $("#planStart").val();
    let end = $("#planEnd").val();

    let hasError = false;

    if (start === "") {
        $("#planStart_error").text("Please select start date.");
        hasError = true;
    }

    if (end === "") {
        $("#planEnd_error").text("Please select end date.");
        hasError = true;
    }

    if (!hasError && new Date(start) > new Date(end)) {
        $("#planEnd_error").text("End date must be greater than or equal to start date.");
        hasError = true;
    }

    if (hasError) {
        return;
    }

    let predId = $("#precedenceItem").val();
    let predName = $("#precedenceItem option:selected").text();

    currentPlans.push({
        plan_start_date: start,
        plan_end_date: end,
        wid_precedence_item_cd: predId,
        precedence_item_name: predId === "" ? null : predName
    });

    loadPlans();

    $("#planStart").val("");
    $("#planEnd").val("");
    $("#precedenceItem").val("");

});

$("#planStart").on("change", function () {
    $("#planStart_error").text("");
});

$("#planEnd").on("change", function () {
    $("#planEnd_error").text("");
});

function loadPlans(){

    $("#planTableBody").empty();

    currentPlans.forEach(function(plan,index){

        $("#planTableBody").append(`

            <tr>

                <td>${index+1}</td>

                <td>${plan.plan_start_date}</td>

                <td>${plan.plan_end_date}</td>

                <td>${plan.precedence_item_name ?? "No Predecessor"}</td>

                <td>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm deletePlan"
                        data-index="${index}">

                        Delete

                    </button>

                </td>

            </tr>

        `);

    });

}

$(document).on("click",".deletePlan",function(){

    let index=$(this).data("index");

    currentPlans.splice(index,1);

    loadPlans();

});

$("#savePlans").click(function(){

    let id=$("#currentWorkItemId").val();

    let item=newWorkItems.find(x=>x.id==id);

    item.work_plans=currentPlans;

    updateWorkItemRow(item);

    $("#workPlanModal").modal("hide");

});

$(document).on("click", ".deleteRow", function () {

    if (!confirm("Delete this work item?")) {
        return;
    }

    let row = $(this).closest("tr");

    if (row.data("existing")) {

        deletedWorkItems.push(row.data("wid"));

        row.next(".planRow").remove();
        row.remove();

        return;
    }

    let id = row.data("id");

    // Remove from array
    newWorkItems = newWorkItems.filter(item => item.id != id);

    // Remove row
    row.remove();
});

document.getElementById("PmsUpdateBtn").addEventListener("click", function (e) {
    $("#work_items_json").val(JSON.stringify({
        added: newWorkItems,
        deleted: deletedWorkItems
    }));

    const projectType = document.getElementById("projectTypeSelect").value;
    const departmentName = document.getElementById("owner_dept_cd").value;

    const dom = {
        project_name: document.getElementById("project_name_nv"),
        end_date: document.getElementById("project_end_date_nv"),
        project_awarded_to: document.getElementById("project_awarded_to"),
        cost: document.getElementById("est_proj_cost_nv"),
        period: document.getElementById("defect_liability_period_nv"),
        work_order: document.getElementById("work_order_amount_nv"),
        work_order_no: document.getElementById("work_order_no_nv"),
        work_order_issue_date: document.getElementById("work_order_issue_date_nv"),
        scheme_cd: document.getElementById("scheme_cd"),

        road_name: document.getElementById("slnewRdNew_nv"),
        road_length: document.getElementById("rdLength_nv"),
        road_category_new: document.getElementById("road_category_new"),
        road_owner_new: document.getElementById("road_owner_new"),
        road_type_new: document.getElementById("road_type_new"),

        road_category: document.getElementById("road_category"),
        road_owner: document.getElementById("road_owner"),
        road_type: document.getElementById("road_type"),

        asset_geo_location_lat: document.getElementById("asset_geo_location_lat"),
        asset_geo_location_lng: document.getElementById("asset_geo_location_lng"),
        building_location_cd: document.getElementById("building_location_cd"),
    };

    const table = document.getElementById('refAssetTable');
    const mtnTable = document.getElementById('mtnAssetTable');

    const kmlFile = document.getElementById('road_kml_file');
    const kmlCard = document.getElementById('kmlCard');

    const rowCount = table ? table.querySelectorAll('tbody tr').length : 0;
    const mtnRowCount = mtnTable ? mtnTable.querySelectorAll('tbody tr').length : 0;

    const newAssetsForm = document.getElementById('newAssetsForm');

    let hasError = false;

    // 🔄 Remove old errors
    document.querySelectorAll(".dynamic-error").forEach(el => el.remove());

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
    if ($(dom.project_name).is(":visible") && !dom.project_name.value.trim()) {
        showError(dom.project_name, "Project Name is required");
        hasError = true;
    }

    if ($(dom.end_date).is(":visible") && !dom.end_date.value) {
        showError(dom.end_date, "End Date is required");
        hasError = true;
    }

    if ($(dom.project_awarded_to).is(":visible") && !dom.project_awarded_to.value) {
        showError(dom.project_awarded_to, "Project Awarded to is required");
        hasError = true;
    }

    if ($(dom.cost).is(":visible") && !dom.cost.value) {
        showError(dom.cost, "Cost is required");
        hasError = true;
    }

    if ($(dom.period).is(":visible") && !dom.period.value) {
        showError(dom.period, "Period is required");
        hasError = true;
    }

    if ($(dom.work_order).is(":visible") && !dom.work_order.value) {
        showError(dom.work_order, "Work Order Amount is required");
        hasError = true;
    }

    if ($(dom.work_order_no).is(":visible") && !dom.work_order_no.value) {
        showError(dom.work_order_no, "Work Order Number is required");
        hasError = true;
    }

    if ($(dom.work_order_issue_date).is(":visible") && !dom.work_order_issue_date.value) {
        showError(dom.work_order_issue_date, "Work Order Issue Date is required");
        hasError = true;
    }

    if ($(dom.scheme_cd).is(":visible") && !dom.scheme_cd.value) {
        showError(dom.scheme_cd, "Scheme is required");
        hasError = true;
    }
    //Done By Dipshikha -- end


    //by dipshikha
    if(departmentName === "14" || departmentName === "3"){
        //end
        if (projectType === "NEW") {

            if ($(dom.road_name).is(":visible") && !dom.road_name.value.trim()) {
                showError(dom.road_name, "Road Name is required");
                hasError = true;
            }

            if ($(dom.road_length).is(":visible") &&
                (!dom.road_length.value || parseFloat(dom.road_length.value) <= 0)) {
                showError(dom.road_length, "Enter valid length");
                hasError = true;
            }

            if ($(dom.road_category_new).is(":visible") && !dom.road_category_new.value.trim()) {
                showError(dom.road_category_new, "Please select Road Category");
                hasError = true;
            }

            if ($(dom.road_type_new).is(":visible") && !dom.road_type_new.value.trim()) {
                showError(dom.road_type_new, "Please select Road Type");
                hasError = true;
            }

            if ($(dom.road_owner_new).is(":visible") && !dom.road_owner_new.value.trim()) {
                showError(dom.road_owner_new, "Please select Road Owner");
                hasError = true;
            }
        }

        if (projectType === 'UPG' && rowCount === 0) {
            Swal.fire({
                title: 'Warning',
                text: 'Please add at least one asset.',
                icon: 'warning',
                confirmButtonText: 'Okay'
            });
            hasError = true;
        }

        if (projectType === 'MTN' && mtnRowCount === 0) {
            Swal.fire({
                title: 'Warning',
                text: 'Please add at least one asset.',
                icon: 'warning',
                confirmButtonText: 'Okay'
            });
            hasError = true;
        }

        if (projectType === 'UPG') {

            const newRoadDetailsForm = document.getElementById("newRoadDetailsForm");

            if (newRoadDetailsForm && newRoadDetailsForm.style.display !== 'none') {

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

        if(projectType === 'UPG' || projectType === 'NEW'){
            if (kmlFile && kmlCard.style.display !== 'none') {

                const existingKml = document.querySelector('input[name="existing_kml_file"]');

                // No new file and no existing database KML
                if (kmlFile.files.length === 0 && !existingKml) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "warning",
                        title: "KML File Required",
                        text: "Please upload a KML file for this road."
                    });
                    return;
                }

                // Validate only newly selected KML
                if (kmlFile.files.length > 0) {

                    const file = kmlFile.files[0];
                    const extension = file.name.split('.').pop().toLowerCase();

                    if (extension !== 'kml') {
                        e.preventDefault();
                        Swal.fire({
                            icon: "error",
                            title: "Invalid File",
                            text: "Only KML files are allowed."
                        });
                        kmlFile.value = '';
                        return;
                    }
                }
            }
        }

        if (projectType === 'UPG') {

            if (newAssetsForm && newAssetsForm.style.display !== 'none') {

                const roadNameInput = newAssetsForm.querySelector('input[name="new_road_name_nv"]');
                const roadLengthInput = newAssetsForm.querySelector('input[name="road_length_nv"]');

                const roadNameEmpty = !roadNameInput?.value.trim();
                const roadLengthEmpty = !roadLengthInput?.value.trim();

                const roadLengthFilled = roadLengthInput?.value.trim() !== '';


                if ((roadNameEmpty || roadLengthEmpty) || (roadLengthFilled || roadNameEmpty)) {

                    if ($(roadNameInput).is(":visible")){
                        if (roadNameEmpty) {
                            showError(roadNameInput, "Road Name is required");
                            hasError = true;
                        }
                    }

                    if ($(roadLengthInput).is(":visible")){
                        if (roadLengthEmpty) {
                            showError(roadLengthInput, "Road Length is required");
                            hasError = true;
                        }
                    }
                }
            }
        }
    }

    if(departmentName === "6"){
        if (projectType === 'NEW') {

            const maintained = document.querySelector('input[name="rdo_maintained_by"]:checked');

            if ($("#maintainedByDiv").is(":visible") && !maintained) {
                const maintainedBy = document.querySelector('input[name="rdo_maintained_by"]');
                showError(maintainedBy, "Please select whether the building is maintained by NPWD");
                hasError = true;
            }

            if ($(dom.asset_geo_location_lat).is(":visible") && !dom.asset_geo_location_lat.value) {
                showError(dom.asset_geo_location_lat, "Latitude is required");
                hasError = true;
            }

            if ($(dom.asset_geo_location_lng).is(":visible") && !dom.asset_geo_location_lng.value) {
                showError(dom.asset_geo_location_lng, "Longitude is required");
                hasError = true;
            }

            const categoryEditChecked = $('input.toggleEdit[data-target="#buildingCategoryDiv"]').is(':checked');
            const locationEdit = $('input.toggleEdit[data-target="#buildingLocationDiv"]');

            if (categoryEditChecked) {

                // Building Category validation
                const buildingCategory = document.querySelector('input[name="building_class_cd"]:checked');

                if (!buildingCategory) {
                    const firstRadio = document.querySelector('input[name="building_class_cd"]');
                    showError(firstRadio, "Building category is required");
                    hasError = true;
                }

                // If Location edit is not enabled, enable it automatically
                if (!locationEdit.is(':checked')) {

                    locationEdit.prop('checked', true);

                    $("#buildingLocationDiv").removeClass("d-none");

                    $("#building_location_cd_error").text(
                        "Please select location after changing the building category."
                    );

                    hasError = true;
                }
            }

            if ($(dom.building_location_cd).is(":visible") && !dom.building_location_cd.value) {
                showError(dom.building_location_cd, "Location is required");
                hasError = true;
            }
        }

        if (projectType === 'MTN') {

            const category = document.getElementById('buildingCategory').value;
            const building = document.getElementById('maintBuildings').value;

            if (!category) {
                showError(document.getElementById('buildingCategory'), "Please select Building Category.");
                hasError = true;
            }

            if (!building) {
                showError(document.getElementById('maintBuildings'), "Please select Building.");
                hasError = true;
            }
        }

        if (projectType === 'UPG') {

            const categoryUpg = document.getElementById('buildingCategoryUpgradation').value;
            const buildingUpg = document.getElementById('upgBuildings').value;
            const section = document.getElementById("housingContainerOtherUpg");

            if (!categoryUpg) {
                showError(document.getElementById('buildingCategoryUpgradation'), "Please select Building Category.");
                hasError = true;
            }

            if (!buildingUpg) {
                showError(document.getElementById('upgBuildings'), "Please select Building.");
                hasError = true;
            }

            if (section && !section.classList.contains("d-none")) {

                const buildingType = document.getElementById("building_type_upg");
                const dept = document.getElementById("owning_dept_upg");
                const quarter = document.getElementById("quarter_no");
                const buildingCategoryUpg = document.getElementById("buildingCategoryUpg").value;
                const quarterContainer = document.getElementById("quarterContainer");

                // clear previous errors
                document.getElementById("building_type_cd_error").innerText = "";
                document.getElementById("owning_dept_error").innerText = "";
				//by dipshikha
                document.getElementById("buildingCategoryUpg_error").innerText = "";
                //end
                document.getElementById("quarter_no_error").innerText = "";

                if (!buildingType.value) {
                    document.getElementById("building_type_cd_error").innerText = "Building Type is required";
                    hasError = true;
                }

                if (!dept.value) {
                    document.getElementById("owning_dept_error").innerText = "Owning Department is required";
                    hasError = true;
                }

                if (!buildingCategoryUpg) {
                    document.getElementById("buildingCategoryUpg_error").innerText = "Building Category is required";
					//by dipshikha
                    hasError = true;
                    //end
                }

                if (!quarterContainer.classList.contains("d-none")) {
                    if (!quarter.value) {
                        document.getElementById("quarter_no_error").innerText = "Field is required";
                        hasError = true;
                    }
                }
            }
        }
    }

    if(departmentName === "15"){
        if(projectType === "UPG"){

            const section = document.getElementById("upgradationSection");

            if (section && !section.classList.contains("d-none")) {

                document.getElementById("vehicle_type_cd_error_upg").innerText = "";
                document.getElementById("equipment_type_cd_error_upg").innerText = "";

                const vehicles = document.querySelectorAll(".vehicle-checkbox:checked");
                const equipments = document.querySelectorAll(".equipment-checkbox:checked");

                if (vehicles.length === 0) {
                    document.getElementById("vehicle_type_cd_error_upg").innerText ="Please select at least one vehicle";
                    hasError = true;
                }

                if (equipments.length === 0) {
                    document.getElementById("equipment_type_cd_error_upg").innerText ="Please select at least one equipment";
                    hasError = true;
                }
            }
        }

        if (projectType === "MTN"){
            const section = document.getElementById("maintenanceSection");

            if (section && !section.classList.contains("d-none")) {

                document.getElementById("vehicle_type_cd_error").innerText = "";
                document.getElementById("equipment_type_cd_error").innerText = "";

                const vehicles = document.querySelectorAll(".vehicle-checkbox:checked");
                const equipments = document.querySelectorAll(".equipment-checkbox:checked");

                if (vehicles.length === 0) {
                    document.getElementById("vehicle_type_cd_error").innerText = "Please select at least one vehicle";
                    hasError = true;
                }

                if (equipments.length === 0) {
                    document.getElementById("equipment_type_cd_error").innerText = "Please select at least one equipment";
                    hasError = true;
                }
            }
        }
    }

    let file = $('#supporting_document')[0].files[0];
    let reason = $('textarea[name="reason"]').val().trim();


    if (reason === '') {
        showDashboardModal('Modification Reason is required.');
        hasError = true;
    }else if (!file) {
        showDashboardModal('Modification Order document is required.');
        hasError = true;
    } else if (file.type !== 'application/pdf') {
        showDashboardModal('Only PDF file allowed.');
        hasError = true;
    }else if (file.size > 2 * 1024 * 1024) {
        showDashboardModal('PDF must be under 2MB.');
        hasError = true;
    }

    if (!hasError) {
        document.getElementById("myForm").submit();
    } else {
        e.preventDefault();
    }
});

$('input.toggleEdit[data-target="#buildingCategoryDiv"]').on('change', function () {

    if (!this.checked) {

        // Hide Building Category
        $("#buildingCategoryDiv").addClass("d-none");
        $("#building_class_cd_error").text("");

        // Hide Location
        $('input.toggleEdit[data-target="#buildingLocationDiv"]')
            .prop('checked', false);

        $("#buildingLocationDiv").addClass("d-none");
        $("#building_location_cd_error").text("");
        $("#building_location_cd").val("");
    }
});

async function populateMaintenanceBuildingCategory(selectId) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.disabled = true;
    select.innerHTML = '<option value="">Loading categories...</option>';

    try {
        const data = await fetchJson("/project-management/get-buildings-category");
        select.innerHTML = "";

        select.innerHTML = '<option value="">-- Select Category --</option>';

        if (Array.isArray(data) && data.length > 0) {
            data.forEach((categories) => {
                select.add(
                    new Option(
                        categories.building_class_descr,
                        categories.building_class_cd,
                    ),
                );
            });
        } else {
            select.innerHTML = '<option value="">No categories found</option>';
        }
    } catch (error) {
        console.error("Error loading categories:", error);
        select.innerHTML = '<option value="">Error loading categories</option>';
    }finally {
        select.disabled = false;
    }
}


async function loadBuildingDetails(buildingCd, type) {
    if (!buildingCd) {
        if (type === "maint") {
            housingContainer.classList.add("d-none");
            housingContainerOther.classList.add("d-none");
        }

        if (type === "upg") {
            housingContainerUpg.classList.add("d-none");
            housingContainerOtherUpg.classList.add("d-none");
            quarterContainer.classList.add("d-none");
        }

        return;
    }

    try {
        const data = await fetchJson(`/project-management/get-building-details/${buildingCd}`);

        if (type === "maint") {
            housingContainer.classList.remove("d-none");
            housingContainerOther.classList.remove("d-none");
            if (data.building.is_maintained_by_npwd === "Y") {
                document.getElementById("rdo_yes_maint").checked = true;
            } else if (data.building.is_maintained_by_npwd === "N") {
                document.getElementById("rdo_no_maint").checked = true;
            }
            document.getElementById("asset_geo_location_lat_maint").value =
                data.building.lat ?? "";
            document.getElementById("asset_geo_location_lng_maint").value =
                data.building.lon ?? "";
            document.getElementById("building_location_id").value =
                data.building.location_name ?? "";
            document.getElementById("building_type").value =
                data.building.building_type_descr ?? "";

            document.getElementById("owning_dept_maint").value =
                data.building.dept_name ?? "";
        }

        if (type === "upg") {
            housingContainerUpg.classList.remove("d-none");
            housingContainerOtherUpg.classList.remove("d-none");

            if (data.building.is_maintained_by_npwd === "Y") {
                document.getElementById("rdo_yes_upg").checked = true;
            } else if (data.building.is_maintained_by_npwd === "N") {
                document.getElementById("rdo_no_upg").checked = true;
            }

            document.getElementById("asset_geo_location_lat_upg").value =
                data.building.lat ?? "";
            document.getElementById("asset_geo_location_lng_upg").value =
                data.building.lon ?? "";
            document.getElementById("building_location_id_upg").value =
                data.building.location_name ?? "";

            buildingTypeDropdown.innerHTML =
                '<option value="">--Select Building Type--</option>';
            deptDropdown.innerHTML =
                '<option value="">--Select Owning Department--</option>';
            categoryDropdown.innerHTML =
                '<option value="">--Select Category--</option>';
            const initialClass = String(data.building.building_class_cd ?? "");

            data.buildingTypes.forEach((type) => {
                let option = document.createElement("option");
                option.value = type.building_type_cd;
                option.text = type.building_type_descr;

                if (type.building_type_cd == data.building.building_type_cd) {
                    option.selected = true;
                }

                buildingTypeDropdown.appendChild(option);
            });

            data.departments.forEach((dept) => {
                let option = document.createElement("option");
                option.value = dept.id;
                option.text = dept.dept_name;

                if (dept.id == data.building.asset_owning_dept_cd) {
                    option.selected = true;
                }

                deptDropdown.appendChild(option);
            });

            data.categories.forEach((cat) => {
                let option = document.createElement("option");
                option.value = cat.building_class_cd;
                option.text = cat.building_class_descr;

                if (
                    String(cat.building_class_cd) ===
                    String(data.building.building_class_cd)
                ) {
                    option.selected = true;
                }

                categoryDropdown.appendChild(option);
            });

            categoryDropdown.onchange = function () {
                const selected = String(this.value);
                if (!selected || selected === "" || selected === initialClass) {
                    quarterContainer.classList.add("d-none");
                    inputUpg.value = "";
                    return;
                }

                quarterContainer.classList.remove("d-none");

                if (selected === "0") {
                    labelUpg.innerHTML =
                        'Quarter No <span class="star text-danger">*</span>';
                    inputUpg.placeholder = "Enter Quarter No";
                } else {
                    labelUpg.innerHTML =
                        'Building Name <span class="star text-danger">*</span>';
                    inputUpg.placeholder = "Enter Building Name";
                }
            };
        }
    } catch (error) {
        console.error("Error loading building details:", error);
        if (type === "maint") {
            housingContainer.classList.add("d-none");
            housingContainerOther.classList.add("d-none");
        }

        if (type === "upg") {
            housingContainerUpg.classList.add("d-none");
            housingContainerOtherUpg.classList.add("d-none");
        }
    }
}
async function populateMaintenanceBuildingSelectSubDiv(
    catCd,
    sub_division_cd,
    selectId,
) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.disabled = true;
    select.innerHTML = '<option value="">Loading Building...</option>';

    if (!sub_division_cd) return;

    try {
        const data = await fetchJson(
            "/project-management/get-maintenance-buildings-subdivision/" +
                catCd +
                "/" +
                sub_division_cd,
        );
        select.innerHTML = "";
        select.innerHTML = '<option value="">-- Select Building --</option>';
        if (Array.isArray(data) && data.length > 0) {
            data.forEach((building) => {
                select.add(
                    new Option(
                        `${building.building_name} (${building.building_system_cd})`,
                        building.building_system_cd,
                    ),
                );
            });
        } else {
            select.innerHTML = '<option value="">No buildings found</option>';
        }
    } catch (error) {
        console.error("Error loading buildings:", error);
        select.innerHTML = '<option value="">Error loading buildings</option>';
    }finally {
        select.disabled = false;
    }
}

async function populateUpgradationBuildingSelectSubDiv(
    catCd,
    sub_division_cd,
    selectId,
) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.disabled = true;
    select.innerHTML = '<option value="">Loading Building...</option>';
    if (!sub_division_cd) return;

    try {
        const data = await fetchJson(
            "/project-management/get-upgradation-buildings-subdivision/" +
                catCd +
                "/" +
                sub_division_cd,
        );
        select.innerHTML = "";
        select.innerHTML = '<option value="">-- Select Building --</option>';
        if (Array.isArray(data) && data.length > 0) {
            data.forEach((building) => {
                select.add(
                    new Option(
                        `${building.building_name} (${building.building_system_cd})`,
                        building.building_system_cd,
                    ),
                );
            });
        } else {
            select.innerHTML = '<option value="">No buildings found</option>';
        }
    } catch (error) {
        console.error("Error loading buildings:", error);
        select.innerHTML = '<option value="">Error loading buildings</option>';
    }finally {
        select.disabled = false;
    }
}

async function fetchJson(url) {
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Network error: ${response.statusText}`);
    return response.json();
}

buildingCategory.addEventListener("change", function () {
    const catCd = this.value;
    const subDivId = document.getElementById("sub_division_cd").value;
    housingContainer.classList.add("d-none");
    housingContainerOther.classList.add("d-none");

    populateMaintenanceBuildingSelectSubDiv(catCd, subDivId, "maintBuildings");
});

buildingCategoryUpgradation.addEventListener("change", function () {
    const catCd = this.value;
    const subDivId = document.getElementById("sub_division_cd").value;
    housingContainerUpg.classList.add("d-none");
    housingContainerOtherUpg.classList.add("d-none");
    quarterContainer.classList.add("d-none");

    populateUpgradationBuildingSelectSubDiv(catCd, subDivId, "upgBuildings");
});

buildings.addEventListener("change", () => {
    const buildingCd = document.getElementById("maintBuildings").value;
    loadBuildingDetails(buildingCd, "maint");
});

upgBuildings.addEventListener("change", () => {
    const buildingCd = document.getElementById("upgBuildings").value;
    loadBuildingDetails(buildingCd, "upg");
});

async function populateVehicleList(subDivId,containerName) {
    const container = document.getElementById(containerName);
    container.innerHTML = "Loading...";

    try {
        const data = await fetchJson(`/project-management/get-vehicles/${subDivId}`);
        const vehicles = data.vehicles;

        if (Array.isArray(vehicles) && vehicles.length > 0) {
            container.innerHTML = "";

            vehicles.forEach(vehicle => {
                const div = document.createElement("div");
                div.className = "d-flex justify-content-between align-items-center mb-1";

                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <input type="checkbox" name="vehicle_type_cd[]" value="${vehicle.vehicle_asset_cd}" class="vehicle-checkbox">
                            <span class="item-name">${vehicle.vehicle_name}</span>
                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary view-vehicle-btn"
                            data-id="${vehicle.vehicle_asset_cd}"
                            title="View Vehicle Details"
                        >
                             👁️
                        </button>

                    </div>
                `;

                container.appendChild(div);
            });

        } else {
            container.innerHTML = "No vehicles found";
        }

    } catch (error) {
        console.error(error);
        container.innerHTML = "Error loading vehicles";
    }
}

async function populateVehicleListMaint(subDivId,containerName) {
    const container = document.getElementById(containerName);
    container.innerHTML = "Loading...";

    try {
        const data = await fetchJson(`/project-management/get-maintenance-vehicles/${subDivId}`);
        const vehicles = data.vehicles;

        if (Array.isArray(vehicles) && vehicles.length > 0) {
            container.innerHTML = "";

            vehicles.forEach(vehicle => {
                const div = document.createElement("div");
                div.className = "d-flex justify-content-between align-items-center mb-1";

                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <input type="checkbox" name="vehicle_type_cd[]" value="${vehicle.vehicle_asset_cd}" class="vehicle-checkbox">
                            <span class="item-name">${vehicle.vehicle_name}</span>
                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary view-vehicle-btn"
                            data-id="${vehicle.vehicle_asset_cd}"
                            title="View Vehicle Details"
                        >
                             👁️
                        </button>

                    </div>
                `;

                container.appendChild(div);
            });

        } else {
            container.innerHTML = "No vehicles found";
        }

    } catch (error) {
        console.error(error);
        container.innerHTML = "Error loading vehicles";
    }
}

async function populateEquipmentList(subDivId,containerName) {
    const container = document.getElementById(containerName);
    container.innerHTML = "Loading...";

    try {
        const data = await fetchJson(`/project-management/get-equipments/${subDivId}`);
        const equipments = data.equipments;

        if (Array.isArray(equipments) && equipments.length > 0) {
            container.innerHTML = "";

            equipments.forEach(equipments => {
                const div = document.createElement("div");
                div.className = "d-flex justify-content-between align-items-center mb-1";

                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <input type="checkbox" name="equipment_type_cd[]" value="${equipments.euipment_cd}" class="equipment-checkbox">
                            <span class="item-name">${equipments.equipment_name}</span>
                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary view-equipment-btn"
                            data-id="${equipments.euipment_cd}"
                            title="View Equipment Details"
                        >
                             👁️
                        </button>

                    </div>
                `;

                container.appendChild(div);
            });

        } else {
            container.innerHTML = "No equipments found";
        }

    } catch (error) {
        console.error(error);
        container.innerHTML = "Error loading equipments";
    }
}

async function populateEquipmentListMaint(subDivId,containerName) {
    const container = document.getElementById(containerName);
    container.innerHTML = "Loading...";

    try {
        const data = await fetchJson(`/project-management/get-maintenance-equipments/${subDivId}`);
        const equipments = data.equipments;

        if (Array.isArray(equipments) && equipments.length > 0) {
            container.innerHTML = "";

            equipments.forEach(equipments => {
                const div = document.createElement("div");
                div.className = "d-flex justify-content-between align-items-center mb-1";

                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <input type="checkbox" name="equipment_type_cd[]" value="${equipments.euipment_cd}" class="equipment-checkbox">
                            <span class="item-name">${equipments.equipment_name}</span>
                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary view-equipment-btn"
                            data-id="${equipments.euipment_cd}"
                            title="View Equipment Details"
                        >
                             👁️
                        </button>

                    </div>
                `;

                container.appendChild(div);
            });

        } else {
            container.innerHTML = "No equipments found";
        }

    } catch (error) {
        console.error(error);
        container.innerHTML = "Error loading equipments";
    }
}

$(document).on("click", ".view-vehicle-btn", function () {
    const vehicleId = $(this).data("id");
    viewSingleRoad(vehicleId);
});

$(document).on("click", ".view-equipment-btn", function () {
    const equpmentId = $(this).data("id");
    viewSingleEquipment(equpmentId);
});

async function viewSingleRoad(vehicleId) {

    const showModalVehicle = document.getElementById("showModalNewAsset");
    const container = $("#modalValContainerNewAsset");

    container.html("Loading...");

    try {
        const response = await $.ajax({
            type: "GET",
            url: "/project-management/get-vehicle-details/" + encodeURIComponent(vehicleId),
        });

        let data = response.vehiclesDetails;

        if (!data) {
            container.html("No vehicle details found");
            return;
        }

        container.html(`

            <div class="col-12 mb-2">
                <b>Vehicle Name:</b> ${data.vehicle_name ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Registration No:</b> ${data.vehicle_regn_no ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Chassis No:</b> ${data.chassis_no ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Engine No:</b> ${data.engine_no ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Vehicle Type:</b> ${data.veh_type_descr ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Seating Capacity:</b> ${data.seating_capacity ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Wheel Count:</b> ${data.no_of_wheels ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Maker:</b> ${data.maker_name ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Model:</b> ${data.model ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Fuel Type:</b> ${data.fuel_type_descr ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Purchase Date:</b> ${data.date_of_purchase ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Purchase Cost:</b> ${data.purchase_cost ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Condition:</b> ${data.condition_descr ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Laden Weight:</b> ${data.laden_weight ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Unladen Weight:</b> ${data.unladen_weight ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Alloted To:</b> ${data.alloted_to ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Alloted from:</b> ${data.alloted_from ?? "NA"}
            </div>

        `);

        showModalVehicle.style.display = "block";

    } catch (error) {
        console.error("Error fetching vehicle details:", error);
        container.html("Error loading data");
    }

    $(".closeShowModalNewAsset").click(() => {
        showModalVehicle.style.display = "none";
    });
}

async function viewSingleEquipment(equipmentId) {

    const showModalEquipment = document.getElementById("showModalNewAsset");
    const container = $("#modalValContainerNewAsset");

    container.html("Loading...");

    try {
        const response = await $.ajax({
            type: "GET",
            url: "/project-management/get-equipment-details/" + encodeURIComponent(equipmentId),
        });

        let data = response.equipmentsDetails;

        if (!data) {
            container.html("No equipment details found");
            return;
        }

        container.html(`

            <div class="col-12 mb-2">
                <b>Equipment Name:</b> ${data.equipment_name ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Serial No:</b> ${data.serial_number ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Model No:</b> ${data.model_no ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Purchase Year:</b> ${data.purchase_year ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Purchase Cost:</b> ${data.purchase_cost ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Condition:</b> ${data.condition_descr ?? "NA"}
            </div>

            <div class="col-12 mb-2">
                <b>Is under warranty:</b> ${data.is_under_waranty ?? "NA"}
            </div>

        `);

        showModalEquipment.style.display = "block";

    } catch (error) {
        console.error("Error fetching equipment details:", error);
        container.html("Error loading data");
    }

    $(".closeShowModalNewAsset").click(() => {
        showModalEquipment.style.display = "none";
    });
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
                $("#sub_division_cd").val(preselectedSubDivision).prop("disabled", true);
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

function setupSearch(inputId, listId) {
    const input = document.getElementById(inputId);

    input?.addEventListener("keyup", function () {

        const search = this.value.toLowerCase().trim();
        const items = document.querySelectorAll(`#${listId} .d-flex`);

        items.forEach(item => {
            const nameEl = item.querySelector(".item-name");
            if (!nameEl) return;

            const name = nameEl.innerText.toLowerCase();

            if (search === "" || name.includes(search)) {
                item.classList.remove("hidden-item");
            } else {
                item.classList.add("hidden-item");
            }
        });

    });
}

// apply
// Maintenance
setupSearch("vehicleSearchMaint", "vehicleListMaint");
setupSearch("equipmentSearchMaint", "equipmentListMaint");

// Upgradation
setupSearch("vehicleSearchUpg", "vehicleListUpg");
setupSearch("equipmentSearchUpg", "equipmentListUpg");
