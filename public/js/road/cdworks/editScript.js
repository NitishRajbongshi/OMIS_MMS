if(cdWorkData){
    populateCDWorkForm(cdWorkData);
}

function populateCDWorkForm(culvert_id) {

    $.ajax({
        url: `/asset-management/get-cd-work-details/${culvert_id}`, 
        method: "GET",
        success: function (response) {
            fillCDWorkForm(response);
        },
        error: function (xhr, status, error) {  
            console.error("Error fetching bridge details:", error);
            alert("Failed to load bridge details. Please try again.");
        }
    });

    fetch(`/asset-management/load-culvert-files/${culvert_id}`) //modified by Pulak 02-05-26
    .then(res => res.json())
    .then(data => {
        loadExistingFiles(data);
    });
}
function fillCDWorkForm(cd) {

    document.getElementById("culvert_no").value = cd.culvert_no ?? "";

    const editModeAlert = document.getElementById("editModeAlert");
    if (editModeAlert) {
        editModeAlert.innerHTML = `<i class="fa fa-info-circle"></i> The culvert is under edit mode for culvert id <strong>${cd.rd_cdwork_cd}</strong>. Culvert No: <strong>${cd.culvert_no ?? 'N/A'}</strong>, Chainage: <strong>${cd.chainage ?? 'N/A'}</strong>`;
    }

    // =========================
    // ✅ BASIC FIELDS
    // =========================
    console.log("Cd work details",cd);
    document.getElementById("chainage").value = cd.chainage ?? "";
   // setSelectValue("culvert_type", cd.culvert_type_cd);
    document.getElementById("culvert_type").value = cd.culvert_type_cd ?? "";
    document.getElementById("discharge").value = cd.discharge ?? "";
    
    setSelectValue("year_of_contruction", cd.year_of_construction ?? "");
    setSelectValue("year_of_rehabilitation", cd.year_of_rehabilitation ?? "");
    setSelectValue("condition", cd.cdwork_condition ?? "");
    console.log("Construction material",cd.const_material_type_cd);
    setSelectValue("slab_construction_material", cd.const_material_type_cd ?? "");


  
    // =========================
    // ✅ TRIGGER UI FIRST
    // =========================
   $('#saveBtn').prop('disabled', false);
   document.getElementById("culvert_type").dispatchEvent(new Event("change"));
   

    // ========================= 
    // ✅ BOX / ARC CULVERT
    // =========================
    document.getElementById("cell_no").value = cd.no_of_cell ?? "";
    document.getElementById("length_span_bxc").value = cd.length_span ?? "";
    document.getElementById("each_cell_width").value = cd.width_each_cell ?? "";
    document.getElementById("each_cell_height").value = cd.heigth_each_cell ?? "";

    document.getElementById("thickness_of_side_wall").value = cd.cdwork_thickness_side_wall ?? "";
    document.getElementById("thickness_of_top_slab").value = cd.cdwork_thickness_top_slab ?? "";
    document.getElementById("thickness_of_bottom_slab").value = cd.cdwork_thickness_bottom_slab ?? "";

    if(cd.cdwork_has_wing_wall){
    let boxWingWalls = cd.wing_wall ?? []; 
    if(cd.wing_wall_count === "Y" && cd.culvert_type_cd === 'BXC')
    {
        let wall = boxWingWalls[0] || {};
        setRadio("wing_wall",cd.cdwork_has_wing_wall);
        triggerRadioChange("wing_wall");
        setRadio("is_same_wing_wall","Y")
        triggerRadioChange("is_same_wing_wall")
        //$("#wing_wall_container").show();
        setSelectValue("wing_wall_type_box", wall.wing_wall_type_cd ?? "");
        setSelectValue("length_box", wall.length ?? "");
        document.getElementById("top_width_box").value = wall.top_width ?? "";
        document.getElementById("bottom_width_box").value = wall.bottom_width ?? "";
        document.getElementById("height1_box").value = wall.height1 ?? "";
        document.getElementById("height2_box").value = wall.height2 ?? "";
        document.getElementById("slope_box").value = wall.slope ?? "";
        document.getElementById("angle_box").value = wall.angle ?? "";
        document.getElementById("radius_box").value = wall.radius ?? "";

    }
    else if(cd.wing_wall_count === "N") 
    {
        boxWingWalls.forEach((wall, index) => {
            let i = index + 1;

            setRadio("wing_wall",cd.cdwork_has_wing_wall);
            triggerRadioChange("wing_wall");
            setRadio("is_same_wing_wall","N")
            triggerRadioChange("is_same_wing_wall")
            $(`#box_wing_wall_type_${i}`)
                .val(wall.wing_wall_type_cd)
                .trigger("change");
            $(`#length_box_${i}`).val(wall.length);
            $(`#top_width_box_${i}`).val(wall.top_width);
            $(`#bottom_width_box_${i}`).val(wall.bottom_width);
            $(`#height1_box_${i}`).val(wall.height1);
            $(`#height2_box_${i}`).val(wall.height2);
            $(`#slope_box_${i}`).val(wall.slope);
            $(`#box_angle_${i}`).val(wall.angle);
            $(`#box_radius_${i}`).val(wall.radius);
        });
    } 
    }
    // =========================
    // ✅ HUME PIPE
    // =========================
    document.getElementById("no_of_rows").value = cd.no_of_rows ?? "";
    document.getElementById("pipe_diameter").value = cd.pipe_diameter ?? "";
    document.getElementById("culvert_width").value = cd.culvert_width ?? "";
    document.getElementById("pipe_specification").value = cd.pipe_specification ?? "";
    document.getElementById("cd_cussion").value = cd.height_of_earth_cushion ?? "";

    if(cd.culvert_type_cd === 'HPC'){
      setRadio("head_wall",cd.cdwork_has_head_wall);
      triggerRadioChange("head_wall");  
      
      let headWall = cd.head_wall ?? [];
      if(headWall.length > 0){
          headWall.forEach((wall, index) => {
            let i = index + 1;
            setSelectValue("hume_head_wall_type_"+i, wall.head_wall_type_cd ?? "");
            document.getElementById("hume_head_wall_length_"+i).value = wall.head_wall_length ?? "";
            document.getElementById("hume_head_wall_width_"+i).value = wall.top_width ?? "";
            document.getElementById("hume_head_wall_height_"+i).value = wall.head_wall_heigth ?? "";
        });
      }
    }

    // =========================
    // ✅ SLAB CULVERT
    // =========================
    document.getElementById("span").value = cd.span ?? "";
    document.getElementById("no_of_wing_wall").value = cd.no_of_wing_wall ?? "";
    setSelectValue("abutment_type_slb", cd.abutment_type_cd ?? "");
    document.getElementById("abutment_height_slb").value = cd.abutment_height ?? "";
    document.getElementById("slab_width_slb").value = cd.slab_width ?? "";

    if(cd.bearing_type_cd )
    {
    setRadio("has_bearing", "Y");
    triggerRadioChange("has_bearing")
    }
    else
    {
    setRadio("has_bearing", "N");
    triggerRadioChange("has_bearing")
    }
    setSelectValue("bearing_type", cd.bearing_type_cd ?? "");

    // =========================
    // ✅ RADIO BUTTONS
    // =========================

    let wingWalls = cd.wing_wall ?? []; 
    if(cd.cdwork_has_wing_wall === 'Y' && cd.culvert_type_cd === 'SLB'){
    if(cd.wing_wall_count === "Y")
    { 

        let wall = wingWalls[0] || {};
        setRadio("slab_wing_wall",cd.cdwork_has_wing_wall)
        triggerRadioChange("slab_wing_wall")
        setRadio("is_same_wing_wall_slab_vented","Y")
        triggerRadioChange("is_same_wing_wall_slab_vented")
        //$("#wing_wall_container").show();
        setSelectValue("wing_wall_type_slab_vented", wall.wing_wall_type_cd ?? "");
        setSelectValue("length", wall.length ?? "");
        document.getElementById("top_width").value = wall.top_width ?? "";
        document.getElementById("bottom_width").value = wall.bottom_width ?? "";
        document.getElementById("height1").value = wall.height1 ?? "";
        document.getElementById("height2").value = wall.height2 ?? "";
        document.getElementById("slope").value = wall.slope ?? "";
        document.getElementById("angleSlabVented").value = wall.angle ?? "";
        document.getElementById("radiusSlabVented").value = wall.radius ?? "";

    }
    else if(cd.wing_wall_count === "N")
    {
        wingWalls.forEach((wall, index) => {
            let i = index + 1;

            // fill existing static blocks (you already have 1–4 in HTML)
            setRadio("slab_wing_wall",cd.cdwork_has_wing_wall)
            triggerRadioChange("slab_wing_wall")
            setRadio("is_same_wing_wall_slab_vented","N")
            triggerRadioChange("is_same_wing_wall_slab_vented")
            $(`#slab_vented_wing_wall_type_${i}`)
                .val(wall.wing_wall_type_cd)
                .trigger("change");
            $(`#length_${i}`).val(wall.length);
            $(`#top_width_${i}`).val(wall.top_width);
            $(`#bottom_width_${i}`).val(wall.bottom_width);
            $(`#height1_${i}`).val(wall.height1);
            $(`#height2_${i}`).val(wall.height2);
            $(`#slope_${i}`).val(wall.slope);
             $(`#slab_vented_angle_${i}`).val(wall.angle);
             $(`#slab_vented_radius_${i}`).val(wall.radius);
        });
    }
    }
     
    setRadio("safety_apron",cd.cdwork_has_safety_apron);
    triggerRadioChange("safety_apron")
    
    setSelectValue("cdwork_safety_apron_type",cd.cdwork_safety_apron_type)
    document.getElementById("cdwork_safety_apron_outlet").value = cd.cdwork_safety_apron_outlet;
    document.getElementById("cdwork_safety_apron_slab_thickness").value= cd.cdwork_safety_apron_slab_thickness;
    document.getElementById("cdwork_safety_apron_width").value = cd.cdwork_safety_apron_width;
    document.getElementById("cdwork_safety_apron_length").value = cd.cdwork_safety_apron_length;
    
    setRadio("catch_pit_availability",cd.catch_pit_availability);
    triggerRadioChange("catch_pit_availability")
    setSelectValue("catch_pit_type_cd",cd.catch_pit_type_cd);
    document.getElementById("catch_pit_width").value=cd.catch_pit_width;
    document.getElementById("catch_pit_breadth").value=cd.catch_pit_breadth;
    document.getElementById("catch_pit_heigth").value=cd.catch_pit_heigth;
    document.getElementById("catch_pit_thickness").value=cd.catch_pit_thickness;
    setSelectValue("catch_pit_condition",cd.catch_pit_condition);
    document.getElementById("cdwork_remark").value=cd.cdwork_remark;
    
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


}