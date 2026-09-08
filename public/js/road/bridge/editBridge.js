if (bridgeId) {
        populateBridgeForm(bridgeId);
    }

function formatDate(dateStr) {
    if (!dateStr) return "";
    return dateStr.substring(0, 10); // gives '2026-04-21'
}
function populateBridgeForm(bridgeId) {

    $.ajax({
        url: `/asset-management/get-bridge-details/${bridgeId}`,
        method: "GET",
        success: function (response) {
            fillForm(response);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching bridge details:", error);
            alert("Failed to load bridge details. Please try again.");
        }
    });

    fetch(`/asset-management/load-bridge-files/${bridgeId}`)
    .then(res => res.json())
    .then(data => {
        loadExistingFiles(data);
    });
}

function fillForm(bridge) {
    const form = document.getElementById("cd_bridge_form");
    form.action = UPDATE_ROUTE_BASE + "/" + bridge.rd_bridge_cd;
    document.getElementById("form_method").value = "PUT";
    document.getElementById("bridge_id").value = bridge.rd_bridge_cd;
    document.getElementById("created_at_office_cd").value =
        bridge.created_at_office_cd;

    const editModeAlert = document.getElementById("editModeAlert");
    if (editModeAlert) {
        editModeAlert.innerHTML = `<i class="fa fa-info-circle"></i> The bridge is under edit mode for bridge id ${bridge.rd_bridge_cd}. Bridge Name: <strong>${bridge.bridge_name ?? 'N/A'}</strong>, Chainage: <strong>${bridge.chainage ?? 'N/A'}</strong>`;
    }
    // Basic text/number fields
    document.getElementById("rd_system_id").value = bridge.rd_system_id;
    document.getElementById("bridge_name").value = bridge.bridge_name ?? "";
    document.getElementById("bridge_type").value = bridge.bridge_type_cd ?? "";
    document.getElementById("chainage").value = bridge.chainage ?? "";
    document.getElementById("bridge_width").value = bridge.bridge_width ?? "";
    document.getElementById("river_name").value = bridge.river_name ?? "";
    document.getElementById("kerb_width").value = bridge.kerb_width ?? "";
    document.getElementById("kerb_height").value = bridge.kerb_height ?? "";
    document.getElementById("load_capacity").value = bridge.load_capacity ?? "";
    document.getElementById("no_of_piers").value = bridge.no_of_piers ?? 0;
    document.getElementById("deck_level").value = bridge.deck_level ?? "";
    document.getElementById("carriage").value = bridge.carriage_width ?? "";
    document.getElementById("guard_stone").value = bridge.guard_stone ?? "";
    document.getElementById("discharge").value = bridge.discharge ?? "";
    document.getElementById("source_depth").value = bridge.source_depth ?? "";
    document.getElementById("lowest_water_level").value =
        bridge.lowest_water_level ?? "";
    document.getElementById("highest_flood_level").value =
        bridge.highest_flood_level ?? "";
    document.getElementById("rfl").value = bridge.rfl ?? "";
    document.getElementById("last_inspection").value = formatDate(
        bridge.date_of_last_inspection,
    );
    document.getElementById("next_schedule_inspection").value = formatDate(
        bridge.next_schedule_inspection_date,
    );
    document.getElementById("remarks").value = bridge.bridge_remark ?? "";
    document.getElementById("apron_width").value =
        bridge.apron_width ??
        // Selects
    setSelectValue("bridge_type", bridge.bridge_type_cd);
    setSelectValue("construction_type", bridge.construction_type_cd);
    setSelectValue("superstructure_type", bridge.super_structure_type_cd);
    setSelectValue("handrail_type", bridge.handrail_type_cd);
    setSelectValue("deck_type", bridge.deck_type_cd);
    setSelectValue("expansion_joints", bridge.expansion_join_cd);
    setSelectValue("condition", bridge.bridge_condition);
    setSelectValue("span_no", bridge.no_of_span);
    setSelectValue("year_of_contruction", bridge.year_of_construction);
    setSelectValue("year_of_rehabilitation", bridge.year_of_rehabilitation);
    setSelectValue("safety_apron_type", bridge.safety_apron_type);

    // Trigger bridge_type change → reveals all common field sections
    document.getElementById("bridge_type").dispatchEvent(new Event("change"));

    // Radios
    setRadio("footpath", bridge.footh_path);
    setRadio("has_safety_apron", bridge.has_safety_apron);
    setRadio("bridge_abutment", bridge.has_abutment_wall);
    setRadio("wing_wall", bridge.has_wing_wall);
    setRadio("bridge_head_wall", bridge.has_head_wall);
    setRadio("bridge_retain_wall", bridge.has_retain_wall);

    // Trigger radio changes → shows/hides dependent containers
    triggerRadioChange("has_safety_apron");
    triggerRadioChange("bridge_abutment");
    triggerRadioChange("wing_wall");
    triggerRadioChange("bridge_head_wall");
    triggerRadioChange("bridge_retain_wall");

    // Switch button states
    document.getElementById("saveBtn").disabled = false;
    document.getElementById("saveBtnLabel").textContent = "Update";
    document.getElementById("cancelEditBtn").style.display = "";
	document.getElementById("reset_btn").style.display = "none";															

    document
        .getElementById("cd_bridge_form")
        .scrollIntoView({ behavior: "smooth" });

    let selected = bridge.span_dimension ?? "";
    let spans = bridge.spans ?? [];

    if (selected) {
        const radio = document.querySelector(
            'input[name="span_dimension"][value="' + selected + '"]',
        );

        if (radio) {
            radio.checked = true;
            radio.dispatchEvent(new Event("change"));
        }
    }

    //Fill span values
    if (selected === "Y") {
        // single span
        if (spans.length > 0) {
            document.getElementById("span_length").value = spans[0];
        }
    } else {
        // multiple spans
        spans.forEach((value, index) => {
            let i = index + 1;

            let input = document.getElementById(`span_length_${i}`);
            if (input) {
                input.value = value;
            }
        });
    }

    console.log("Bridge Piers", bridge.piers);

    let piers = bridge.piers ?? [];

    if (piers.length > 0) {
        let pier = piers[0]; // only first pier

        $("#piers_container").show();
        $("#no_of_piers").val(piers.length);

        // fill basic fields
        $("#pier_type_cd").val(pier.pier_type_cd).trigger("change");
        $("#pier_length").val(pier.pier_length);
        $("#pier_width").val(pier.pier_width);
        $("#pier_height").val(pier.pier_heigth);
        $("#pier_bearings").val(pier.bearing_type_cd).trigger("change");
        $("#pier_foundation_type").val(pier.foundation_type_cd);

        // trigger change if you already have listener
        $("#pier_foundation_type").trigger("change");

        // foundation type logic
        if (pier.foundation_type_cd === "0") {
            $("#PierPileFoundation").show();
            console.log(
                "Pier Pile foundation details",
                pier.pile_diameter,
                pier.pile_length,
                pier.pile_type_cd,
            );
            $("#pier_pile_diameter").val(pier.pile_diameter);
            $("#pier_pile_length").val(pier.pile_length);
            $("#pier_pile_type").val(pier.pile_type_cd).trigger("change");
        } else if (pier.foundation_type_cd === "1") {
            $("#PierWellFoundation").show();

            $("#pier_well_type").val(pier.well_type_cd).trigger("change");
            $("#pier_well_size").val(pier.well_size);
        } else if (pier.foundation_type_cd === "2") {
            $("#PierOpenFoundation").show();

            $("#pier_open_fundation_size").val(pier.open_foundation_size);
            $("#pier_open_fundation_depth").val(pier.open_foundation_depth);
        }
    }

    if (bridge.abutments.length > 0) {
        let abutment = bridge.abutments[0]; // same as pier (single UI)

        // select YES (has abutment)
        $('input[name="bridge_abutment"][value="Y"]')
            .prop("checked", true)
            .trigger("change");

        // show container
        $("#bridge_abutment_container").show();

        // basic fields
        $("#abutment_wall_type_cd")
            .val(abutment.abutment_wall_type_cd)
            .trigger("change");

        $("#abutment_wall_length").val(abutment.abutment_wall_length);
        $("#abutment_wall_width").val(abutment.abutment_wall_width);
        $("#abutment_wall_heigth").val(abutment.abutment_wall_heigth);

        $("#abutment_bearings").val(abutment.bearing_type_cd).trigger("change");

        $("#foundation_type")
            .val(abutment.foundation_type_cd)
            .trigger("change");

        // FOUNDATION LOGIC
        if (abutment.foundation_type_cd === "0") {
            $("#PileFoundation").show();

            $("#pile_diameter").val(abutment.pile_diameter);
            $("#pile_length").val(abutment.pile_length);
            $("#pile_type").val(abutment.pile_type_cd).trigger("change");
        } else if (abutment.foundation_type_cd === "1") {
            $("#WellFoundation").show();

            $("#well_type").val(abutment.well_type_cd).trigger("change");
            $("#well_size").val(abutment.well_size);
        } else if (abutment.foundation_type_cd === "2") {
            $("#OpenFoundation").show();

            $("#open_fundation_size").val(abutment.open_foundation_size);
            $("#open_fundation_depth").val(abutment.open_foundation_depth);
        }
    }

    let isSameWingWall = bridge.is_same_wing_wall;

    let wingWalls = bridge.wing_walls ?? [];

    if (wingWalls.length > 0) {
        // show main section
        $("#bridge_wing_wall").show();

        // set radio
        $('input[name="is_same_wing_wall"][value="' + isSameWingWall + '"]')
            .prop("checked", true)
            .trigger("change");
    }
    if (isSameWingWall === "Y") {
        let wall = wingWalls[0];

        $("#box_wing_wall_fields").show();

        $("#wing_wall_type_cd").val(wall.wing_wall_type_cd).trigger("change");
        $("#length").val(wall.length);
        $("#top_width").val(wall.top_width);
        $("#bottom_width").val(wall.bottom_width);
        $("#height1").val(wall.height1);
        $("#height2").val(wall.height2);
        $("#slope").val(wall.slope);
        $("#angle").val(wall.angle);
        $("#radius").val(wall.radius);
        
    } else {
        $("#box_wing_wall_fields_multiple").show();

        wingWalls.forEach((wall, index) => {
            let i = index + 1;

            // fill existing static blocks (you already have 1–4 in HTML)
            $(`#wing_wall_type_${i}`)
                .val(wall.wing_wall_type_cd)
                .trigger("change");
            $(`#length_${i}`).val(wall.length);
            $(`#top_width_${i}`).val(wall.top_width);
            $(`#bottom_width_${i}`).val(wall.bottom_width);
            $(`#height1_${i}`).val(wall.height1);
            $(`#height2_${i}`).val(wall.height2);
            $(`#slope_${i}`).val(wall.slope);
            $(`#angle_${i}`).val(wall.angle);
            $(`#radius_${i}`).val(wall.radius);
        });
    }

    let headWalls = bridge.head_walls ?? [];

    if (headWalls.length > 0) {
        // select YES (has head wall)
        $('input[name="bridge_head_wall"][value="Y"]')
            .prop("checked", true)
            .trigger("change");

        $("#bridge_head_wall_container").show();

        headWalls.forEach((wall, index) => {
            let i = index + 1; // 1 or 2

            // Fill fields
            $(`#head_wall_type_${i}`)
                .val(wall.head_wall_type_cd)
                .trigger("change");

            $(`#head_wall_length_${i}`).val(wall.head_wall_length);
            $(`#head_wall_width_${i}`).val(wall.head_wall_width);
            $(`#head_wall_height_${i}`).val(wall.head_wall_heigth);

            // stream type (already fixed in HTML, but still set)
            $(`#head_wall_stream_type_${i}`)
                .val(wall.head_wall_stream_type_cd)
                .trigger("change");
        });
    } else {
        // No head wall
        $('input[name="bridge_head_wall"][value="N"]').prop("checked", true);
    }

    let isSameRetainWall = bridge.is_same_retain_wall;
    let retainWalls = bridge.retain_walls ?? [];

    if (retainWalls.length > 0) {
        // has retain wall
        $('input[name="bridge_retain_wall"][value="Y"]')
            .prop("checked", true)
            .trigger("change");

        $("#bridge_retain_wall").show();

        // same / multiple logic
        $('input[name="is_same_retain_wall"][value="' + isSameRetainWall + '"]')
            .prop("checked", true)
            .trigger("change");

        if (isSameRetainWall === "Y") {
            fillSingleRetainWall(retainWalls[0]);
        } else {
            fillMultipleRetainWalls(retainWalls);
        }
    } else {
        // no retain wall
        $('input[name="bridge_retain_wall"][value="N"]').prop("checked", true);
    }
}

function fillSingleRetainWall(wall) {
    $("#bridge_retain_wall_fields").show();

    $("#retain_wall_type_cd").val(wall.retain_wall_type_cd).trigger("change");

    $("#retain_wall_length").val(wall.retain_wall_length);
    $("#retain_wall_width").val(wall.retain_wall_width);
    $("#retain_wall_heigth").val(wall.retain_wall_heigth);
}

function fillMultipleRetainWalls(walls) {
    $("#bridge_retain_wall_fields_multiple").show();

    walls.forEach((wall, index) => {
        let i = index + 1;

        $(`#retain_wall_type_cd_${i}`)
            .val(wall.retain_wall_type_cd)
            .trigger("change");

        $(`#retain_wall_length_${i}`).val(wall.retain_wall_length);
        $(`#retain_wall_width_${i}`).val(wall.retain_wall_width);
        $(`#retain_wall_heigth_${i}`).val(wall.retain_wall_heigth);
    });
}

function setSelectValue(id, value) {
    const el = document.getElementById(id);
    if (el && value !== null && value !== undefined) el.value = value;
    document
        .getElementById(id)
        .dispatchEvent(new Event("change", { bubbles: true }));
}

function setRadio(name, value) {
    document
        .querySelectorAll(`input[name="${name}"]`)
        .forEach((r) => (r.checked = r.value === value));
}

function triggerRadioChange(name) {
    const checked = document.querySelector(`input[name="${name}"]:checked`);
    if (checked) checked.dispatchEvent(new Event("change", { bubbles: true }));
}
