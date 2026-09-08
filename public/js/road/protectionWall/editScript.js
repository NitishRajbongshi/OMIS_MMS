if(protection_wall_id){
    editProtectionWall(protection_wall_id)
}
function editProtectionWall(id) {
    populateProtectionWallForm(id);
}

function populateProtectionWallForm(id) {

    // 🔹 Fetch main data
    $.ajax({
        url: `/asset-management/get-protection-wall/${id}`,   // create this route
        method: "GET",
        success: function (response) {
            fillProtectionWallForm(response);
        },
        error: function () {
            alert("Failed to load protection wall details");
        }
    });

    //🔹 Fetch files (optional like your culvert)
    fetch(`/asset-management/load-protection-wall-files/${id}`)
        .then(res => res.json())
        .then(data => {
            loadExistingFiles(data); // reuse your existing function
        });
}


function fillProtectionWallForm(data) {

    // =========================
    // FILL INPUTS
    // =========================
    document.getElementById("chainage").value = data.chainage ?? "";
    document.getElementById("wall_type_cd").value = data.wall_type_cd ?? "";
    document.getElementById("structure_type_cd").value = data.structure_type_cd ?? "";
    document.getElementById("bottom_width").value = data.bottom_width ?? "";
    document.getElementById("top_width").value = data.top_width ?? "";
    document.getElementById("length").value = data.length ?? "";
    document.getElementById("height").value = data.height ?? "";
    document.getElementById("year_of_construction").value = data.year_of_construction ?? "";
    document.getElementById("year_of_renovation").value = data.year_of_renovation ?? "";
    document.getElementById("remarks").value = data.remarks ?? "";

    // Set dropdown values (call AFTER inputs are filled)
    setSelectValue("wall_type_cd", data.wall_type_cd);
    setSelectValue("structure_type_cd", data.structure_type_cd);
    setSelectValue("year_of_construction", data.year_of_construction);
    setSelectValue("year_of_renovation", data.year_of_renovation);

    function setSelectValue(id, value) {
        const el = document.getElementById(id);
        if (el && value !== null && value !== undefined) el.value = value;
        document
            .getElementById(id)
            .dispatchEvent(new Event("change", { bubbles: true }));
    }
    // =========================
    // ✅ ALERT / EDIT MODE INFO
    // =========================
    const alertBox = document.getElementById("editModeAlert");
    if (alertBox) {
        alertBox.innerHTML = `
            <i class="fa fa-info-circle"></i>
            Edit Mode: Protection Wall ID <strong>${data.protection_wall_cd}</strong>,
            Chainage: <strong>${data.chainage ?? 'N/A'}</strong>
        `;
    }

    // Scroll to form
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

