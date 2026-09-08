function showModalNewAssetDetail(projectId, table="draft") {
    const showModalNewAsset = document.getElementById("showModalNewAsset");
    const container = $("#modalValContainerNewAsset");

    container.empty();

    $.ajax({
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

        success: function (response) {
            if (response.status === "success") {

                let data = response.data;

                const renderBadges = (arr, cls) => {
                    if (!arr || !arr.length)
                        return `<span class="text-danger">NA</span>`;

                    return arr
                        .map(
                            (v) =>
                                 `<span class="badge ${cls} me-1 mb-1">${v}</span>`
                        )
                        .join("");
                };


                // =========================
                // EXISTING ASSETS
                // =========================
                if (data.roads && data.roads.length) {

                    container.append(`
                        <div class="col-12 mt-2">
                            <p class="text-rose-primary text-bold border-bottom">
                                Existing Assets
                            </p>
                        </div>

                        <div class="accordion" id="roadsAccordion"></div>
                    `);


                    const accordion = $("#roadsAccordion");


                    data.roads.forEach((r, index) => {

                        const collapseId = `roadCollapse${index}`;


                        accordion.append(`

                            <div class="accordion-item mb-2">

                                <h5 class="accordion-header mb-0">

                                    <button class="accordion-button collapsed py-1 px-2 fw-bold small"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#${collapseId}">

                                        Road ${index + 1} - ${r.road_name || "NA"}

                                    </button>

                                </h5>


                                <div id="${collapseId}"
                                     class="accordion-collapse collapse"
                                     data-bs-parent="#roadsAccordion">


                                    <div class="accordion-body">

                                        <div class="row">


                                            ${
                                                r.start_chainage != null &&
                                                r.start_chainage !== ''
                                                    ? `
                                                    <div class="col-md-6 mb-2">
                                                        <strong>Upgradation From Chainage:</strong>
                                                        ${r.start_chainage}
                                                    </div>
                                                    `
                                                    : ''
                                            }


                                            ${
                                                r.end_chainage != null &&
                                                r.end_chainage !== ''
                                                    ? `
                                                    <div class="col-md-6 mb-2">
                                                        <strong>Upgradation To Chainage:</strong>
                                                        ${r.end_chainage}
                                                    </div>
                                                    `
                                                    : ''
                                            }


                                            <div class="col-md-6 mb-2">
                                                <strong>Culverts:</strong><br>
                                                ${renderBadges(r.culverts, "bg-info")}
                                            </div>


                                            <div class="col-md-6 mb-2">
                                                <strong>Bridges:</strong><br>
                                                ${renderBadges(r.bridges, "bg-warning text-dark")}
                                            </div>


                                            <div class="col-md-6 mb-2">
                                                <strong>Walls:</strong><br>
                                                ${renderBadges(r.walls, "bg-secondary")}
                                            </div>
                                            

                                        </div>

                                    </div>

                                </div>

                            </div>

                        `);

                    });


                } else {

                    container.append(`
                        <div class="col-12 text-danger mb-2">
                            No Existing Assets Found
                        </div>
                    `);

                }



                // =========================
                // ROAD ALIGNMENT SEQUENCE
                // =========================

                if (data.road_sequence && data.road_sequence.length) {


                    container.append(`

                        <div class="col-12 mt-3">

                            <p class="text-rose-primary text-bold border-bottom">
                                Road Alignment Sequence
                            </p>


                            <div id="roadSequenceList"></div>

                        </div>

                    `);



                    data.road_sequence
                        .sort((a, b) => a.sequence - b.sequence)
                        .forEach((item) => {


                            let displayRoadName = item.road_id;
                            if (
                                data.new_road_name &&
                                data.new_road_id == item.road_id
                            ) {

                                displayRoadName = data.new_road_name;

                            }


                            $("#roadSequenceList").append(`

                                <div class="list-group-item d-flex align-items-center mb-1">


                                    <span class="badge text-white me-2"
                                          style="background-color: var(--primary);">

                                        ${item.sequence}

                                    </span>


                                    <strong>
                                        ${displayRoadName}
                                    </strong>


                                </div>

                            `);


                        });

                }




                // =========================
                // NEW ASSET
                // =========================

                if (data.new_road_name || data.new_road_length || data.road_category || data.road_type || data.road_owner) {


                    container.append(`


                        <div class="col-12 mt-3">

                            <p class="text-rose-primary text-bold border-bottom">
                                New Asset
                            </p>

                        </div>



						${data.new_road_name ? `						
                        <div class="col-sm-4 mb-2">
                            <label class="fw-bold text-rose-primary">
                                Road Name
                            </label>
                            <input class="form-control form-control-sm"
                                value="${data.new_road_name}"
                                disabled>
                        </div>
						` : ''}



                        <div class="col-sm-4 mb-2">

                            <label class="fw-bold text-rose-primary">
                                Road Category
                            </label>

                            <input class="form-control form-control-sm"
                                value="${data.road_category || ""}"
                                disabled>

                        </div>




                        <div class="col-sm-4 mb-2">

                            <label class="fw-bold text-rose-primary">
                                Road Type
                            </label>

                            <input class="form-control form-control-sm"
                                value="${data.road_type || ""}"
                                disabled>

                        </div>




                        ${data.new_road_length ? `
                        <div class="col-sm-4 mb-2">
                            <label class="fw-bold text-rose-primary">
                                Road Length (km)
                            </label>
                            <input class="form-control form-control-sm"
                                value="${data.new_road_length}"
                                disabled>
                        </div>
                        ` : ''}




                        <div class="col-sm-4 mb-2">

                            <label class="fw-bold text-rose-primary">
                                Road Owner
                            </label>

                            <input class="form-control form-control-sm"
                                value="${data.road_owner || ""}"
                                disabled>

                        </div>


                    `);

                }



                // SHOW MODAL

                showModalNewAsset.style.display = "block";


                document.getElementById("modalValContainerNewAsset").style.maxHeight = "70vh";


                document.getElementById(
                    "modalValContainerNewAsset"
                ).style.overflowY = "auto";

            }
        },
    });



    // CLOSE MODAL

    $(".closeShowModalNewAsset").click(function () {

        showModalNewAsset.style.display = "none";

    });

}
