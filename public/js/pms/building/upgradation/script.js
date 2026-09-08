const buildings = document.getElementById("maintBuildings");
const upgBuildings = document.getElementById("upgBuildings");
const buildingCategory = document.getElementById("buildingCategory");
const buildingCategoryUpgradation = document.getElementById(
    "buildingCategoryUpgradation",
);
const housingContainer = document.getElementById("housingContainer");
const housingContainerOther = document.getElementById("housingContainerOther");
const housingContainerUpg = document.getElementById("housingContainerUpg");
const housingContainerOtherUpg = document.getElementById(
    "housingContainerOtherUpg",
);
const quarterContainer = document.getElementById("quarterContainer");
const buildingTypeDropdown = document.getElementById("building_type_upg");
const deptDropdown = document.getElementById("owning_dept_upg");
const categoryDropdown = document.getElementById("buildingCategoryUpg");
const label = document.querySelector("label[for='quarter_no']");
const input = document.getElementById("quarter_no");

housingContainer.classList.add("d-none");
housingContainerOther.classList.add("d-none");

quarterContainer.classList.add("d-none");

housingContainerUpg.classList.add("d-none");
housingContainerOtherUpg.classList.add("d-none");

document.getElementById("rdo_yes_maint").onclick = () => false;
document.getElementById("rdo_no_maint").onclick = () => false;

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

async function populateMaintenanceBuildingSelectSubDiv(
    catCd,
    sub_division_cd,
    selectId,
) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.innerHTML = '<option value="">-- Select Building --</option>';
    if (!sub_division_cd) return;

    try {
        const data = await fetchJson(
            "/project-management/get-maintenance-buildings-subdivision/" +
                catCd +
                "/" +
                sub_division_cd,
        );
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
    }
}

async function populateUpgradationBuildingSelectSubDiv(
    catCd,
    sub_division_cd,
    selectId,
) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.innerHTML = '<option value="">-- Select Building --</option>';
    if (!sub_division_cd) return;

    try {
        const data = await fetchJson(
            "/project-management/get-upgradation-buildings-subdivision/" +
                catCd +
                "/" +
                sub_division_cd,
        );
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
    }
}

async function fetchJson(url) {
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Network error: ${response.statusText}`);
    return response.json();
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
                    input.value = "";
                    return;
                }

                quarterContainer.classList.remove("d-none");

                if (selected === "0") {
                    label.innerHTML =
                        'Quarter No <span class="star text-danger">*</span>';
                    input.placeholder = "Enter Quarter No";
                } else {
                    label.innerHTML =
                        'Building Name <span class="star text-danger">*</span>';
                    input.placeholder = "Enter Building Name";
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

async function showModalMaintenanceDetailBuilding(projectId, table = "draft") {
    const showModalMaintenance = document.getElementById(
        "showModalMaintenance",
    );
    const container = $("#modalValContainerMaintenance");

    container.empty();

    try {
        const response = await $.ajax({
            type: "GET",
            url:
                "/project-management/get-maintenance-detail/" +
                encodeURIComponent(projectId),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                table: table,
            },
        });

        if (response.status === "success") {
            let data = response.data;

            const building_id = data.building_id;
            const dataBuilding = await fetchJson(
                `/project-management/get-building-details/${building_id}`,
            );

            const label =
                dataBuilding.building.building_class_cd == 0
                    ? "Quarter No"
                    : "Building Name";

            container.append(`

                <div class="col-12 mb-2">
                    <b>Building Category:</b> ${dataBuilding.building.building_class_descr ?? "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>${label}:</b> ${dataBuilding.building.building_name || "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>Is Maintained by NPWD?</b><br>
                    <input type="radio" name="npwd" ${dataBuilding.building.is_maintained_by_npwd === "Y" ? "checked" : ""} onclick="return false"> Yes
                    <input type="radio" name="npwd" ${dataBuilding.building.is_maintained_by_npwd === "N" ? "checked" : ""} onclick="return false"> No
                </div>

                <div class="col-12 mb-2">
                    <b>Latitude:</b> ${dataBuilding.building.lat ?? "NA"}
                </div>

                <div class="col-12 mb-2">
                     <b>Longitude:</b> ${dataBuilding.building.lon ?? "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>Location:</b> ${dataBuilding.building.location_name ?? "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>Building Type:</b> ${dataBuilding.building.building_type_descr ?? "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>Owning Department:</b> ${dataBuilding.building.dept_name ?? "NA"}
                </div>

            `);

            showModalMaintenance.style.display = "block";
        }
    } catch (error) {
        console.error("Error fetching maintenance/building details:", error);
    }

    $(".closeShowModalMaintenance").click(() => {
        showModalMaintenance.style.display = "none";
    });
}

async function showModalNewAssetDetailBuilding(projectId, table = "draft") {
    const showModalNewAsset = document.getElementById("showModalNewAsset");
    const container = $("#modalValContainerNewAsset");

    container.empty();

    try {
        const response = await $.ajax({
            type: "GET",
            url:
                "/project-management/get-upgradation-detail/" +
                encodeURIComponent(projectId),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                table: table,
            },
        });

        if (response.status === "success") {
            let data = response.data;

            const label =
                data.building_class_cd == 0 ? "Quarter No" : "Building Name";
            const value =
                data.building_class_cd == 0
                    ? data.quarter_no
                    : data.building_name;

            container.append(`

                <div class="col-12 mb-2">
                    <b>Building Category:</b> ${data.building_class_descr ?? "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>${label}:</b> ${value || "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>Is Maintained by NPWD?</b><br>
                    <input type="radio" name="npwd" ${data.is_maintained_by_npwd === "Y" ? "checked" : ""} onclick="return false"> Yes
                    <input type="radio" name="npwd" ${data.is_maintained_by_npwd === "N" ? "checked" : ""} onclick="return false"> No
                </div>

                <div class="col-12 mb-2">
                    <b>Latitude:</b> ${data.lat ?? "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>Longitude:</b> ${data.lon ?? "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>Location:</b> ${data.location_name ?? "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>Building Type:</b> ${data.building_type_descr ?? "NA"}
                </div>

                <div class="col-12 mb-2">
                    <b>Owning Department:</b> ${data.dept_name ?? "NA"}
                </div>

            `);

            showModalNewAsset.style.display = "block";
        }
    } catch (error) {
        console.error("Error fetching upgradation/building details:", error);
    }

    $(".closeShowModalNewAsset").click(() => {
        showModalNewAsset.style.display = "none";
    });
}

$("#division_cd").on("change", function () {
    const division_cd = $(this).val();
    loadSubDivisions(division_cd);
});

$(document).ready(function () {
    const divisionSelect = document.getElementById("division_cd");
    const preselectedDivision = divisionSelect.value;
    const preselectedSubDivision = divisionSelect.dataset.userSubdivision;

    if (preselectedDivision) {
        loadSubDivisions(preselectedDivision, preselectedSubDivision, () => {
            if (preselectedSubDivision) {
                document.getElementById("sub_division_cd").value =
                    preselectedSubDivision;
                document
                    .getElementById("sub_division_cd")
                    .dispatchEvent(new Event("change"));
            }
        },
            true
        );
    }
});

document
    .getElementById("sub_division_cd")
    .addEventListener("change", function () {
        populateMaintenanceBuildingCategory("buildingCategory");
        populateMaintenanceBuildingCategory("buildingCategoryUpgradation");
    });

async function populateMaintenanceBuildingCategory(selectId) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.innerHTML = '<option value="">-- Select Category --</option>';

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
    }
}

function loadSubDivisions(selectedDivision,preselectedSubDivision = null,callback = null, lockDropdown = false) {
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
                        "</option>",
                );
            });

            if (preselectedSubDivision) {
                dropdown.val(preselectedSubDivision);
				if (lockDropdown) {				   
                $("#sub_division_cd")
                    .val(preselectedSubDivision)
                    .prop("disabled", true);
                if ($("#hidden_sub_division_cd").length === 0) {
                    $("<input>")
                        .attr({
                            type: "hidden",
                            id: "hidden_sub_division_cd",
                            name: "sub_division_cd",
                            value: preselectedSubDivision,
                        })
                        .appendTo("form");
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
