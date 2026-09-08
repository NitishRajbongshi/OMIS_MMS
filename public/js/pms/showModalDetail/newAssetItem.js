function getNewWorksAssetDetails(projectId, table = "draft") {
    $("#dataSectionForNewWorks").empty();
    $("#dataSectionForNewWorks").html(
        '<p class="text-muted small">Loading...</p>',
    );
    $("#showAssetForNewWorks").show();

    $.ajax({
        type: "GET",
        url:
            "/project-management/other-details/new-works/" +
            encodeURIComponent(projectId),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            table: table,
        },
        success: function (response) {
            $("#dataSectionForNewWorks").empty();
            if (response.status === "success") {
                let data = response.data;

                let html = `
                <div class="border col-12 mb-2 row pt-2">

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Road ID:</strong>
                        ${data.new_road_id ?? "N/A"}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Project Type:</strong>
                        ${data.project_type ?? "N/A"}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Road Name:</strong>
                        ${data.new_road_name ?? "N/A"}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Road Category:</strong>
                        ${data.road_category_name ?? 0}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Road Type:</strong>
                        ${data.road_type_name ?? 0}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Road Length:</strong>
                        ${data.new_road_length ?? "N/A"}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Road Owner:</strong>
                        ${data.road_owner_name ?? 0}
                    </div>

                </div>
                `;

                $("#dataSectionForNewWorks").html(html);
            }
        },
        error: function () {
            $("#dataSectionForNewWorks").html(
                '<p class="text-danger small">Failed to load data.</p>',
            );
        },
    });

    $(".btn-close").on("click", () => {
        $("#showAssetForNewWorks").hide();
    });
}

function getNewWorksAssetDetailsHousing(projectId, table = "draft") {
    $("#dataSectionForNewWorks").empty();
    $("#dataSectionForNewWorks").html(
        '<p class="text-muted small">Loading...</p>',
    );
    $("#showAssetForNewWorks").show();

    $.ajax({
        type: "GET",
        url:
            "/project-management/other-details/new-works/" +
            encodeURIComponent(projectId),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            table: table,
        },
        success: function (response) {
            $("#dataSectionForNewWorks").empty();
            if (response.status === "success") {
                let data = response.data;

                let html = `
                <div class="border col-12 mb-2 row pt-2">

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">New Building ID:</strong>
                        ${data.new_building_id ?? "N/A"}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Project Type:</strong>
                        ${data.project_type ?? "N/A"}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Building Position:</strong>
                        [${data.new_building_lat ?? "N/A"}, ${data.new_building_lng ?? "N/A"}]
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Maintain by NP:</strong>
                        ${data.new_building_maintain_by_npwd == "Y" ? "Yes" : "No"}
                    </div>

					<div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Building Name:</strong>
                        ${data.bld_qtr_name ?? "N/A"}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Quarter No:</strong>
                        ${data.qtr_no ?? "N/A"}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Building Type:</strong>
                        ${data.building_type_name ?? "N/A"}
                    </div>

                    <div class="col-md-6 mb-2">
                        <strong class="text-rose-primary">Owning Department:</strong>
                        ${data.owning_dept_name ?? "N/A"}
                    </div>
                </div>  
                `;

                $("#dataSectionForNewWorks").html(html);
            }
        },
        error: function () {
            $("#dataSectionForNewWorks").html(
                '<p class="text-danger small">Failed to load data.</p>',
            );
        },
    });

    $(".btn-close").on("click", () => {
        $("#showAssetForNewWorks").hide();
    });
}
