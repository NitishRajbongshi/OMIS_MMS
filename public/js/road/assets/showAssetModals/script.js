function getWingWallValue(id) {
    const wingWallModal = document.getElementById("wingWallModal");
    const wingWallValContainer = $("#wingWallValContainer");

    // Clear previous content
    wingWallValContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-wing-wall-detail/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    const currentIndex = index + 1;
                    if (response.value.length == 1) {
                        const heading = `
                        <div class="w-full mt-2">
                            <p class="text-primary text-bold border-bottom">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Wing wall
                            </p>
                        </div>
                        `;
                        wingWallValContainer.append(heading);
                    } else {
                        const heading = `
                        <div class="w-full mt-2">
                            <p class="text-primary text-bold border-bottom">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Wing wall ${currentIndex}
                            </p>
                        </div>
                        `;
                        wingWallValContainer.append(heading);
                    }

                    const fields = [
                        {
                            label: "Wall Type",
                            value: item.wing_wall_type_descr,
                        },
                        {
                            label: "Length",
                            value: item.length,
                        },
                        {
                            label: "Top Width",
                            value: item.top_width,
                        },
                        {
                            label: "Bottom Width",
                            value: item.bottom_width,
                        },
                        {
                            label: "Height1",
                            value: item.height1,
                        },
                        {
                            label: "Height2",
                            value: item.height2,
                        },
                        {
                            label: "Slope",
                            value: item.slope,
                        },
                        {
                            label: "Angle",
                            value: item.angle,
                        },
                        {
                            label: "Radius",
                            value: item.radius,
                        },
                    ];

                    $.each(fields, function (i, field) {
                        const html = `
                            <div class="col-sm-6 col-md-3 mb-2">
                                <label class="form-label">${field.label}</label>
                                <input type="text" class="form-control form-control-sm" value="${field.value}" disabled>
                            </div>
                        `;
                        wingWallValContainer.append(html);
                    });

                    if (response.value.length == 1) {
                        var info = $(
                            '<div class="col-12 text-md">' +
                                '<p class="text-secondary text-xs text-bold"><i class="fa fa-info-circle text-xs mr-1" aria-hidden="true"></i>Here, each wing wall has the same dimension.</p>' +
                                "</div>"
                        );
                        wingWallValContainer.append(info);
                    }

                    if (wingWallModal) {
                        wingWallModal.style.display = "block";
                    }
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
        },
    });

    // Close modal functionality (Move outside AJAX)
    const span = document.querySelector(".closeWingWallModal"); // Use querySelector
    if (span) {
        span.onclick = function () {
            if (wingWallModal) {
                wingWallModal.style.display = "none";
            }
        };
    }

    window.onclick = function (event) {
        if (wingWallModal && event.target == wingWallModal) {
            wingWallModal.style.display = "none";
        }
    };
}

function getFinalizedWingWallValue(id) {
    const wingWallModal = document.getElementById("wingWallModalFinalized");
    const wingWallValContainer = $("#finalizedWingWallValContainer");

    // Clear previous content
    wingWallValContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-wing-wall-detail-finalized/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    const currentIndex = index + 1;
                    if (response.value.length == 1) {
                        const heading = `
                        <div class="w-full mt-2">
                            <p class="text-primary text-bold border-bottom">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Wing wall
                            </p>
                        </div>
                        `;
                        wingWallValContainer.append(heading);
                    } else {
                        const heading = `
                        <div class="w-full mt-2">
                            <p class="text-primary text-bold border-bottom">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Wing wall ${currentIndex}
                            </p>
                        </div>
                        `;
                        wingWallValContainer.append(heading);
                    }

                    const fields = [
                        {
                            label: "Wall Type",
                            value: item.wing_wall_type_descr,
                        },
                        {
                            label: "Length",
                            value: item.length,
                        },
                        {
                            label: "Top Width",
                            value: item.top_width,
                        },
                        {
                            label: "Bottom Width",
                            value: item.bottom_width,
                        },
                        {
                            label: "Height1",
                            value: item.height1,
                        },
                        {
                            label: "Height2",
                            value: item.height2,
                        },
                        {
                            label: "Slope",
                            value: item.slope,
                        },
                        {
                            label: "Angle",
                            value: item.angle,
                        },
                        {
                            label: "Radius",
                            value: item.radius,
                        },
                    ];

                    $.each(fields, function (i, field) {
                        const html = `
                            <div class="col-sm-6 col-md-3 mb-2">
                                <label class="form-label">${field.label}</label>
                                <input type="text" class="form-control form-control-sm" value="${field.value}" disabled>
                            </div>
                        `;
                        wingWallValContainer.append(html);
                    });

                    if (response.value.length == 1) {
                        var info = $(
                            '<div class="col-12 text-md">' +
                                '<p class="text-secondary text-xs text-bold"><i class="fa fa-info-circle text-xs mr-1" aria-hidden="true"></i>Here, each wing wall has the same dimension.</p>' +
                                "</div>"
                        );
                        wingWallValContainer.append(info);
                    }

                    if (wingWallModal) {
                        wingWallModal.style.display = "block";
                    }
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
        },
    });

    // Close modal functionality (Move outside AJAX)
    const span = document.querySelector(".closeWingWallModalFinalized");
    if (span) {
        span.onclick = function () {
            if (wingWallModal) {
                wingWallModal.style.display = "none";
            }
        };
    }

    window.onclick = function (event) {
        if (wingWallModal && event.target == wingWallModal) {
            wingWallModal.style.display = "none";
        }
    };
}

function getHeadWallValue(id) {
    const headWallModal = document.getElementById("headWallModal");
    const headWallValContainer = $("#headWallValContainer");

    headWallValContainer.empty(); // Clear previous content

    $.ajax({
        type: "GET",
        url: "/asset-management/get-head-wall-detail/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    const heading = `
                        <div class="w-full mt-2 text-sm">
                            <p class="text-primary text-bold border-bottom">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Stream: ${item.stream_descr}
                            </p>
                        </div>
                    `;
                    headWallValContainer.append(heading);

                    const fields = [
                        {
                            label: "Wall Type",
                            value: item.head_wall_descr,
                        },
                        {
                            label: "Length",
                            value: item.head_wall_length,
                        },
                        {
                            label: "Width",
                            value: item.top_width,
                        },
                        {
                            label: "Height",
                            value: item.head_wall_heigth,
                        },
                    ];

                    let html = "";
                    $.each(fields, function (i, field) {
                        html += `
                            <div class="col-sm-6 col-md-3 mb-2">
                                <label class="form-label">${field.label}</label>
                                <input type="text" class="form-control form-control-sm" value="${field.value}" disabled>
                            </div>
                        `;
                    });
                    headWallValContainer.append(
                        `<div class="row">${html}</div>`
                    );

                    if (headWallModal) {
                        headWallModal.style.display = "block";
                    }
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
        },
    });

    // Move modal closing logic outside AJAX for single registration
    const span = document.querySelector(".closeHeadWall");
    if (span) {
        span.onclick = function () {
            if (headWallModal) {
                headWallModal.style.display = "none";
            }
        };
    }

    window.onclick = function (event) {
        if (headWallModal && event.target == headWallModal) {
            headWallModal.style.display = "none";
        }
    };
}

function getFinalizedHeadWallValue(id) {
    const headWallModal = document.getElementById("headWallModalFinalized");
    const headWallValContainer = $("#finalizedHeadWallValContainer");

    headWallValContainer.empty(); // Clear previous content

    $.ajax({
        type: "GET",
        url: "/asset-management/get-head-wall-detail-finalized/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    const heading = `
                        <div class="w-full mt-2 text-sm">
                            <p class="text-primary text-bold border-bottom">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Stream: ${item.stream_descr}
                            </p>
                        </div>
                    `;
                    headWallValContainer.append(heading);

                    const fields = [
                        {
                            label: "Wall Type",
                            value: item.head_wall_descr,
                        },
                        {
                            label: "Length",
                            value: item.head_wall_length,
                        },
                        {
                            label: "Width",
                            value: item.head_wall_width,
                        },
                        {
                            label: "Height",
                            value: item.head_wall_heigth,
                        },
                    ];

                    let html = "";
                    $.each(fields, function (i, field) {
                        html += `
                            <div class="col-sm-6 col-md-3 mb-2">
                                <label class="form-label">${field.label}</label>
                                <input type="text" class="form-control form-control-sm" value="${field.value}" disabled>
                            </div>
                        `;
                    });
                    headWallValContainer.append(
                        `<div class="row">${html}</div>`
                    );

                    if (headWallModal) {
                        headWallModal.style.display = "block";
                    }
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
        },
    });

    // Move modal closing logic outside AJAX for single registration
    const span = document.querySelector(".closeHeadWallFinalized");
    if (span) {
        span.onclick = function () {
            if (headWallModal) {
                headWallModal.style.display = "none";
            }
        };
    }

    window.onclick = function (event) {
        if (headWallModal && event.target == headWallModal) {
            headWallModal.style.display = "none";
        }
    };
}
