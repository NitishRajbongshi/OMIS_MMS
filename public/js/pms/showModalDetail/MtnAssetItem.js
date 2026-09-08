function showModalMaintenanceDetail(projectId, table = "draft") {

    const showModalMaintenance = document.getElementById(
        "showModalMaintenance",
    );

    const container = $("#modalValContainerMaintenance");

    container.empty();

    $.ajax({
        type: "GET",
        url:
            "/project-management/get-maintenance-detail/" +
            encodeURIComponent(projectId),
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            table: table
        },

        success: function (response) {

            if (response.status === "success") {

                let data = response.data;

                console.log(data);


                const renderBadges = (arr, cls) => {

                    if (!arr || !arr.length)
                        return `<span class="text-danger">NA</span>`;

                    return arr
                        .map(
                            (v) =>
                                `<span class="badge ${cls} me-1 mb-1">${v}</span>`,
                        )
                        .join("");
                };


                container.append(`

                    <div class="col-12 mb-2">
                        <strong>Roads:</strong><br>
                        ${renderBadges(data.road_names, "theme-primary")}
                    </div>


                    <div class="col-12 mb-2">
                        <strong>Culverts:</strong><br>
                        ${renderBadges(data.culverts, "bg-info")}
                    </div>


                    <div class="col-12 mb-2">
                        <strong>Bridges:</strong><br>
                        ${renderBadges(data.bridges, "bg-warning text-dark")}
                    </div>


                    <div class="col-12 mb-2">
                        <strong>Walls:</strong><br>
                        ${renderBadges(data.walls, "bg-secondary")}
                    </div>

                `);


                showModalMaintenance.style.display = "block";
            }
        },
    });


    $(".closeShowModalMaintenance").click(() => {

        showModalMaintenance.style.display = "none";

    });
}
