function getWingWallValue(id) {
    const wingWallModal = document.getElementById("wingWallModal");
    const wingWallValContainer = $("#wingWallValContainer");

    // Clear previous content
    wingWallValContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-bridge-wing-wall-detail/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    const currentIndex = index + 1;

                    // Create heading
                    const heading = `
                        <div class="col-12 mt-2 text-md">
                            <p class="text-primary text-bold border bg-primary p-1">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Wing wall: ${currentIndex}
                            </p>
                        </div>
                    `;
                    wingWallValContainer.append(heading);

                    // Wing wall type
                    const wingWallType = `
                        <div class="col-12 text-md">
                            <p class="text-bold">Wing Wall Type: ${item.wing_wall_type_descr}</p>
                        </div>
                    `;
                    wingWallValContainer.append(wingWallType);

                    // Function to generate dimension information
                    const createDimensionDiv = (label, value) => {
                        return `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                    ${label}: <span class="text-bold">${value} Mtrs</span>
                                </p>
                            </div>
                        `;
                    };

                    // Append dimension information
                    wingWallValContainer.append(
                        createDimensionDiv("Length", item.length)
                    );
                    wingWallValContainer.append(
                        createDimensionDiv("Top Width", item.top_width)
                    );
                    wingWallValContainer.append(
                        createDimensionDiv("Bottom Width", item.bottom_width)
                    );
                    wingWallValContainer.append(
                        createDimensionDiv("Height 1", item.height1)
                    );
                    wingWallValContainer.append(
                        createDimensionDiv("Height 2", item.height2)
                    );
                    wingWallValContainer.append(
                        createDimensionDiv("Slop", item.slope)
                    );

                    // Append angle if available
                    if (item.angle !== null) {
                        const angle = `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                    Angle: <span class="text-bold">${item.angle} Degrees</span>
                                </p>
                            </div>
                        `;
                        wingWallValContainer.append(angle);
                    }

                    // Append radius if available
                    if (item.radius !== null) {
                        const radius = `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                    Radius: <span class="text-bold">${item.radius} Mtrs</span>
                                </p>
                            </div>
                        `;
                        wingWallValContainer.append(radius);
                    }
                });

                // Add info if only one wing wall
                if (response.value.length === 1) {
                    const info = `
                        <div class="col-12 text-md">
                            <p class="text-secondary text-xs text-bold">
                                <i class="fa fa-info-circle text-xs mr-1" aria-hidden="true"></i>
                                Here, each wing wall has the same dimension.
                            </p>
                        </div>
                    `;
                    wingWallValContainer.append(info);
                }

                // Display Modal
                if (wingWallModal) {
                    wingWallModal.style.display = "block";
                }

                // Bind Events
                const span = document.querySelector(".closeWingWall");

                if (span) {
                    span.onclick = function () {
                        wingWallModal.style.display = "none";
                    };
                }

                window.onclick = function (event) {
                    if (event.target === wingWallModal) {
                        wingWallModal.style.display = "none";
                    }
                };
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
        },
    });
}

function getHeadWallValue(id) {
    const headWallModal = document.getElementById("headWallModal");
    const headWallValContainer = $("#headWallValContainer");

    // Clear previous content
    headWallValContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-bridge-head-wall-detail/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    // Heading
                    const heading = `
                        <div class="col-12 mt-2 text-md">
                            <p class="text-primary text-bold border bg-primary p-1">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Stream: ${item.stream_descr}
                            </p>
                        </div>
                    `;
                    headWallValContainer.append(heading);

                    // Head wall type
                    const headWallType = `
                        <div class="col-12 text-md">
                            <p class="text-bold">Head Wall Type: ${item.head_wall_descr}</p>
                        </div>
                    `;
                    headWallValContainer.append(headWallType);

                    // Function to create dimension divs
                    const createDimensionDiv = (label, value) => {
                        return `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                    ${label}: <span class="text-bold">${value} Mtrs</span>
                                </p>
                            </div>
                        `;
                    };

                    // Dimension information
                    headWallValContainer.append(
                        createDimensionDiv("Length", item.head_wall_length)
                    );
                    headWallValContainer.append(
                        createDimensionDiv("Width", item.head_wall_width)
                    );
                    headWallValContainer.append(
                        createDimensionDiv("Height", item.head_wall_heigth)
                    );
                });

                // Display Modal
                headWallModal.style.display = "block";

                // Event listeners for closing the modal
                const span = document.querySelector(".closeHeadWall");
                span.onclick = function () {
                    headWallModal.style.display = "none";
                };

                window.onclick = function (event) {
                    if (event.target == headWallModal) {
                        headWallModal.style.display = "none";
                    }
                };
            }
        },
    });
}

function getAbutmentWallValue(id) {
    const abutmentWallModal = document.getElementById("abutmentWallModal");
    const abutmentWallValContainer = $("#abutmentWallValContainer");

    // Clear previous content
    abutmentWallValContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-abutment-wall-detail/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    // Abutment Type Heading
                    const heading = `
                        <div class="col-12 mt-2 text-md">
                            <p class="text-primary text-bold border bg-primary p-1">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Abutment Wall Type: ${item.abutment_type_descr}
                            </p>
                        </div>
                    `;
                    abutmentWallValContainer.append(heading);

                    // Function to create a dimension paragraph
                    const createDimensionDiv = (
                        label,
                        value,
                        colorClass = "text-primary"
                    ) => {
                        return `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs ${colorClass} mr-1" aria-hidden="true"></i>
                                    ${label}: <span class="text-bold">${value} Mtrs</span>
                                </p>
                            </div>
                        `;
                    };

                    // Abutment Dimensions
                    abutmentWallValContainer.append(
                        createDimensionDiv("Length", item.abutment_wall_length)
                    );
                    abutmentWallValContainer.append(
                        createDimensionDiv("Width", item.abutment_wall_width)
                    );
                    abutmentWallValContainer.append(
                        createDimensionDiv("Height", item.abutment_wall_heigth)
                    );

                    // Bearing Type
                    const bearing = `
                        <div class="col-12">
                            <p class="text-md">
                                <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                Bearing Type: <span class="text-bold">${item.bearing_type_descr}</span>
                            </p>
                        </div>
                    `;
                    abutmentWallValContainer.append(bearing);

                    // Foundation Type
                    const foundation = `
                        <div class="col-12">
                            <p class="text-md border bg-success px-1">
                                Foundation Type: <span class="text-bold">${item.foundation_descr}</span>
                            </p>
                        </div>
                    `;
                    abutmentWallValContainer.append(foundation);

                    // Optional Values
                    if (item.pile_diameter != null) {
                        abutmentWallValContainer.append(
                            createDimensionDiv(
                                "Pile Diameter",
                                item.pile_diameter,
                                "text-success"
                            )
                        );
                    }
                    if (item.pile_length != null) {
                        abutmentWallValContainer.append(
                            createDimensionDiv(
                                "Pile Length",
                                item.pile_length,
                                "text-success"
                            )
                        );
                    }
                    if (item.pile_type_descr != null) {
                        const pileType = `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>
                                    Pile Type: <span class="text-bold">${item.pile_type_descr}</span>
                                </p>
                            </div>
                        `;
                        abutmentWallValContainer.append(pileType);
                    }
                    if (item.well_type_descr != null) {
                        const wellType = `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>
                                    Well Type: <span class="text-bold">${item.well_type_descr}</span>
                                </p>
                            </div>
                        `;
                        abutmentWallValContainer.append(wellType);
                    }
                    if (item.well_size != null) {
                        abutmentWallValContainer.append(
                            createDimensionDiv(
                                "Well Size",
                                item.well_size,
                                "text-success"
                            )
                        );
                    }
                    if (item.open_foundation_size != null) {
                        abutmentWallValContainer.append(
                            createDimensionDiv(
                                "Open Foundation Size",
                                item.open_foundation_size,
                                "text-success"
                            )
                        );
                    }
                    if (item.open_foundation_depth != null) {
                        abutmentWallValContainer.append(
                            createDimensionDiv(
                                "Open Foundation Depth",
                                item.open_foundation_depth,
                                "text-success"
                            )
                        );
                    }
                });

                // Display Modal
                abutmentWallModal.style.display = "block";

                // Event listeners for closing the modal
                const span = document.querySelector(".closeAbutmentWall");
                span.onclick = function () {
                    abutmentWallModal.style.display = "none";
                };

                window.onclick = function (event) {
                    if (event.target == abutmentWallModal) {
                        abutmentWallModal.style.display = "none";
                    }
                };
            }
        },
    });
}

function getSpanValue(id) {
    const spanDetailsModal = document.getElementById("spanDetailsModal");
    const spanDetailsContainer = $("#spanDetailsContainer");

    // Clear previous content
    spanDetailsContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-span-details/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                const spanCount = response.value.length;

                // Display the number of spans
                const heading = `
                    <div class="col-12 text-md">
                        <p class="text-primary text-bold">
                            <i class="fa fa-info-circle mr-1" aria-hidden="true"></i>
                            Showing dimension for ${spanCount} span(s).
                        </p>
                    </div>
                `;
                spanDetailsContainer.append(heading);

                // Add each span to the page
                $.each(response.value, function (index, item) {
                    const spanDiv = `
                        <div class="col-sm-6 col-md-4">
                            <p class="text-md">
                                <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                Span ${item.span_sr_no} Length:
                                <span class="text-bold">${item.span_length} Mtrs</span>
                            </p>
                        </div>
                    `;
                    spanDetailsContainer.append(spanDiv);
                });

                // Show the modal
                spanDetailsModal.style.display = "block";

                // Close modal event handlers
                const span = document.querySelector(".closeSpanModal");
                span.onclick = function () {
                    spanDetailsModal.style.display = "none";
                };

                window.onclick = function (event) {
                    if (event.target == spanDetailsModal) {
                        spanDetailsModal.style.display = "none";
                    }
                };
            }
        },
    });
}

function getPierValue(id) {
    const pierDetailsModal = document.getElementById("pierDetailsModal");
    const pierDetailsContainer = $("#pierDetailsContainer");

    // Clear previous content
    pierDetailsContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-pier-details/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    // -----------------------------------------------------------------
                    // Reusable Function to Create HTML
                    // -----------------------------------------------------------------
                    const createDiv = (text, isSuccess = false) => {
                        const iconColor = isSuccess
                            ? "text-success"
                            : "text-primary";
                        const divClass = isSuccess
                            ? "col-sm-6 col-md-6"
                            : "col-sm-6 col-md-4"; //Adjust width if it is well foundation section
                        return `
                            <div class="${divClass}">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs ${iconColor} mr-1" aria-hidden="true"></i>
                                    ${text}
                                </p>
                            </div>
                        `;
                    };

                    // -----------------------------------------------------------------
                    // Append Data to the Content
                    // -----------------------------------------------------------------
                    pierDetailsContainer.append(`
                        <div class="col-12 mt-2 text-md">
                            <p class="text-primary text-bold border bg-primary p-1">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Pier Type: ${item.pier_type_descr}
                            </p>
                        </div>
                    `);

                    // Dimensions
                    pierDetailsContainer.append(
                        createDiv(
                            `Length : <span class="text-bold">${item.pier_length} Mtrs</span>`
                        )
                    );
                    pierDetailsContainer.append(
                        createDiv(
                            `Width : <span class="text-bold">${item.pier_width} Mtrs</span>`
                        )
                    );
                    pierDetailsContainer.append(
                        createDiv(
                            `Height : <span class="text-bold">${item.pier_heigth} Mtrs</span>`
                        )
                    );

                    // Bearing Information
                    pierDetailsContainer.append(`
                        <div class="col-12">
                            <p class="text-md">
                                <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                Bearing Type: <span class="text-bold">${item.bearing_type_descr}</span>
                            </p>
                        </div>
                    `);

                    // Foundation Information
                    pierDetailsContainer.append(`
                        <div class="col-12">
                            <p class="text-md border bg-success px-1">
                                Foundation Type: <span class="text-bold">${item.foundation_descr}</span>
                            </p>
                        </div>
                    `);

                    // Optional values
                    if (item.pile_diameter != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Pile Diameter: <span class="text-bold">${item.pile_diameter} Mtrs</span>`,
                                true
                            )
                        );
                    }
                    if (item.pile_length != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Pile Length: <span class="text-bold">${item.pile_length} Mtrs</span>`,
                                true
                            )
                        );
                    }
                    if (item.pile_type_descr != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Pile Type: <span class="text-bold">${item.pile_type_descr} </span>`,
                                true
                            )
                        );
                    }
                    if (item.well_type_descr != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Well Type: <span class="text-bold">${item.well_type_descr} </span>`,
                                true
                            )
                        );
                    }
                    if (item.well_size != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Well Size: <span class="text-bold">${item.well_size} Mtrs</span>`,
                                true
                            )
                        );
                    }
                    if (item.open_foundation_size != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Open Foundation Size: <span class="text-bold">${item.open_foundation_size} Mtrs</span>`,
                                true
                            )
                        );
                    }
                    if (item.open_foundation_depth != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Open Foundation Depth: <span class="text-bold">${item.open_foundation_depth} Mtrs</span>`,
                                true
                            )
                        );
                    }
                });

                // Show Modal
                pierDetailsModal.style.display = "block";

                // -----------------------------------------------------------------
                // Modal Event Listeners
                // -----------------------------------------------------------------
                const span = document.querySelector(".closePierModal");

                // Function to Close the Modal
                const closeModal = () => {
                    pierDetailsModal.style.display = "none";
                };
                span.onclick = closeModal;
                window.onclick = (event) => {
                    if (event.target == pierDetailsModal) {
                        closeModal();
                    }
                };
            }
        },
    });
}

function getRetainWallValue(id) {
    const retainWallModal = document.getElementById("retainWallModal");
    const retainWallValContainer = $("#retainWallValContainer");

    // Clear previous content
    retainWallValContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-retain-wall-detail/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    // Retain Wall Header
                    const heading = `
                        <div class="col-12 mt-2 text-md">
                            <p class="text-primary text-bold border bg-primary p-1">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Retain wall: ${index + 1}
                            </p>
                        </div>
                    `;
                    retainWallValContainer.append(heading);

                    // Information of the retain wall
                    const wallInfo = `
                        <div class="col-12 text-md">
                            <p class="text-bold">Retain Wall Type: ${item.reatain_wall_descr}</p>
                        </div>
                    `;
                    retainWallValContainer.append(wallInfo);

                    // Reusable function to creat dimension data
                    const createDimensionDiv = (labelText, valueText) => {
                        return `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                    ${labelText}:
                                    <span class="text-bold">${valueText} Mtrs</span>
                                </p>
                            </div>
                        `;
                    };

                    // Dimensions of the retain wall
                    retainWallValContainer.append(
                        createDimensionDiv("Length", item.retain_wall_length)
                    );
                    retainWallValContainer.append(
                        createDimensionDiv("Width", item.retain_wall_width)
                    );
                    retainWallValContainer.append(
                        createDimensionDiv("Height", item.retain_wall_heigth)
                    );
                });

                // Display an info banner, if the length of the array is one
                if (response.value.length === 1) {
                    const info = `
                        <div class="col-12 text-md">
                            <p class="text-secondary text-xs text-bold">
                                <i class="fa fa-info-circle text-xs mr-1" aria-hidden="true"></i>
                                Here, each retain wall has the same dimension.
                            </p>
                        </div>
                    `;
                    retainWallValContainer.append(info);
                }

                // Display Modal
                retainWallModal.style.display = "block";

                // Set the onclick events for closing the modal
                const span = document.querySelector(".closeRetainWall");
                span.onclick = () => {
                    retainWallModal.style.display = "none";
                };
                window.onclick = (event) => {
                    if (event.target == retainWallModal) {
                        retainWallModal.style.display = "none";
                    }
                };
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
        },
    });
}

function getFinalizedWingWallValue(id) {
    const wingWallModal = document.getElementById("wingWallModalFinalized");
    const wingWallValContainer = $("#finalizedWingWallValContainer");

    // Clear previous content
    wingWallValContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-bridge-wing-wall-detail-finalized/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    const currentIndex = index + 1;

                    // Create heading
                    const heading = `
                        <div class="col-12 mt-2 text-md">
                            <p class="text-primary text-bold border bg-primary p-1">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Wing wall: ${currentIndex}
                            </p>
                        </div>
                    `;
                    wingWallValContainer.append(heading);

                    // Wing wall type
                    const wingWallType = `
                        <div class="col-12 text-md">
                            <p class="text-bold">Wing Wall Type: ${item.wing_wall_type_descr}</p>
                        </div>
                    `;
                    wingWallValContainer.append(wingWallType);

                    // Function to generate dimension information
                    const createDimensionDiv = (label, value) => {
                        return `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                    ${label}: <span class="text-bold">${value} Mtrs</span>
                                </p>
                            </div>
                        `;
                    };

                    // Append dimension information
                    wingWallValContainer.append(
                        createDimensionDiv("Length", item.length)
                    );
                    wingWallValContainer.append(
                        createDimensionDiv("Top Width", item.top_width)
                    );
                    wingWallValContainer.append(
                        createDimensionDiv("Bottom Width", item.bottom_width)
                    );
                    wingWallValContainer.append(
                        createDimensionDiv("Height 1", item.height1)
                    );
                    wingWallValContainer.append(
                        createDimensionDiv("Height 2", item.height2)
                    );
                    wingWallValContainer.append(
                        createDimensionDiv("Slop", item.slope)
                    );

                    // Append angle if available
                    if (item.angle !== null) {
                        const angle = `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                    Angle: <span class="text-bold">${item.angle} Degrees</span>
                                </p>
                            </div>
                        `;
                        wingWallValContainer.append(angle);
                    }

                    // Append radius if available
                    if (item.radius !== null) {
                        const radius = `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                    Radius: <span class="text-bold">${item.radius} Mtrs</span>
                                </p>
                            </div>
                        `;
                        wingWallValContainer.append(radius);
                    }
                });

                // Add info if only one wing wall
                if (response.value.length === 1) {
                    const info = `
                        <div class="col-12 text-md">
                            <p class="text-secondary text-xs text-bold">
                                <i class="fa fa-info-circle text-xs mr-1" aria-hidden="true"></i>
                                Here, each wing wall has the same dimension.
                            </p>
                        </div>
                    `;
                    wingWallValContainer.append(info);
                }

                // Display Modal
                if (wingWallModal) {
                    wingWallModal.style.display = "block";
                }

                // Bind Events
                const span = document.querySelector(".closeFinalizedWingWall");

                if (span) {
                    span.onclick = function () {
                        wingWallModal.style.display = "none";
                    };
                }

                window.onclick = function (event) {
                    if (event.target === wingWallModal) {
                        wingWallModal.style.display = "none";
                    }
                };
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
        },
    });
}

function getFinalizedHeadWallValue(id) {
    const headWallModal = document.getElementById("headWallModalFinalized");
    const headWallValContainer = $("#finalizedHeadWallValContainer");

    // Clear previous content
    headWallValContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-bridge-head-wall-detail-finalized/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    // Heading
                    const heading = `
                        <div class="col-12 mt-2 text-md">
                            <p class="text-primary text-bold border bg-primary p-1">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Stream: ${item.stream_descr}
                            </p>
                        </div>
                    `;
                    headWallValContainer.append(heading);

                    // Head wall type
                    const headWallType = `
                        <div class="col-12 text-md">
                            <p class="text-bold">Head Wall Type: ${item.head_wall_descr}</p>
                        </div>
                    `;
                    headWallValContainer.append(headWallType);

                    // Function to create dimension divs
                    const createDimensionDiv = (label, value) => {
                        return `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                    ${label}: <span class="text-bold">${value} Mtrs</span>
                                </p>
                            </div>
                        `;
                    };

                    // Dimension information
                    headWallValContainer.append(
                        createDimensionDiv("Length", item.head_wall_length)
                    );
                    headWallValContainer.append(
                        createDimensionDiv("Width", item.head_wall_width)
                    );
                    headWallValContainer.append(
                        createDimensionDiv("Height", item.head_wall_heigth)
                    );
                });

                // Display Modal
                headWallModal.style.display = "block";

                // Event listeners for closing the modal
                const span = document.querySelector(".closeFinalizedHeadWall");
                span.onclick = function () {
                    headWallModal.style.display = "none";
                };

                window.onclick = function (event) {
                    if (event.target == headWallModal) {
                        headWallModal.style.display = "none";
                    }
                };
            }
        },
    });
}

function getFinalizedAbutmentWallValue(id) {
    const abutmentWallModal = document.getElementById("abutmentWallModalFinalized");
    const abutmentWallValContainer = $("#finalizedAbutmentWallValContainer");

    // Clear previous content
    abutmentWallValContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-abutment-wall-detail-finalized/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    // Abutment Type Heading
                    const heading = `
                        <div class="col-12 mt-2 text-md">
                            <p class="text-primary text-bold border bg-primary p-1">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Abutment Wall Type: ${item.abutment_type_descr}
                            </p>
                        </div>
                    `;
                    abutmentWallValContainer.append(heading);

                    // Function to create a dimension paragraph
                    const createDimensionDiv = (
                        label,
                        value,
                        colorClass = "text-primary"
                    ) => {
                        return `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs ${colorClass} mr-1" aria-hidden="true"></i>
                                    ${label}: <span class="text-bold">${value} Mtrs</span>
                                </p>
                            </div>
                        `;
                    };

                    // Abutment Dimensions
                    abutmentWallValContainer.append(
                        createDimensionDiv("Length", item.abutment_wall_length)
                    );
                    abutmentWallValContainer.append(
                        createDimensionDiv("Width", item.abutment_wall_width)
                    );
                    abutmentWallValContainer.append(
                        createDimensionDiv("Height", item.abutment_wall_heigth)
                    );

                    // Bearing Type
                    const bearing = `
                        <div class="col-12">
                            <p class="text-md">
                                <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                Bearing Type: <span class="text-bold">${item.bearing_type_descr}</span>
                            </p>
                        </div>
                    `;
                    abutmentWallValContainer.append(bearing);

                    // Foundation Type
                    const foundation = `
                        <div class="col-12">
                            <p class="text-md border bg-success px-1">
                                Foundation Type: <span class="text-bold">${item.foundation_descr}</span>
                            </p>
                        </div>
                    `;
                    abutmentWallValContainer.append(foundation);

                    // Optional Values
                    if (item.pile_diameter != null) {
                        abutmentWallValContainer.append(
                            createDimensionDiv(
                                "Pile Diameter",
                                item.pile_diameter,
                                "text-success"
                            )
                        );
                    }
                    if (item.pile_length != null) {
                        abutmentWallValContainer.append(
                            createDimensionDiv(
                                "Pile Length",
                                item.pile_length,
                                "text-success"
                            )
                        );
                    }
                    if (item.pile_type_descr != null) {
                        const pileType = `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>
                                    Pile Type: <span class="text-bold">${item.pile_type_descr}</span>
                                </p>
                            </div>
                        `;
                        abutmentWallValContainer.append(pileType);
                    }
                    if (item.well_type_descr != null) {
                        const wellType = `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>
                                    Well Type: <span class="text-bold">${item.well_type_descr}</span>
                                </p>
                            </div>
                        `;
                        abutmentWallValContainer.append(wellType);
                    }
                    if (item.well_size != null) {
                        abutmentWallValContainer.append(
                            createDimensionDiv(
                                "Well Size",
                                item.well_size,
                                "text-success"
                            )
                        );
                    }
                    if (item.open_foundation_size != null) {
                        abutmentWallValContainer.append(
                            createDimensionDiv(
                                "Open Foundation Size",
                                item.open_foundation_size,
                                "text-success"
                            )
                        );
                    }
                    if (item.open_foundation_depth != null) {
                        abutmentWallValContainer.append(
                            createDimensionDiv(
                                "Open Foundation Depth",
                                item.open_foundation_depth,
                                "text-success"
                            )
                        );
                    }
                });

                // Display Modal
                abutmentWallModal.style.display = "block";

                // Event listeners for closing the modal
                const span = document.querySelector(".closeFinalizedAbutmentWall");
                span.onclick = function () {
                    abutmentWallModal.style.display = "none";
                };

                window.onclick = function (event) {
                    if (event.target == abutmentWallModal) {
                        abutmentWallModal.style.display = "none";
                    }
                };
            }
        },
    });
}

function getFinalizedSpanValue(id) {
    const spanDetailsModal = document.getElementById("spanDetailsModalFinalized");
    const spanDetailsContainer = $("#finalizedSpanDetailsContainer");

    // Clear previous content
    spanDetailsContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-span-details-finalized/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                const spanCount = response.value.length;

                // Display the number of spans
                const heading = `
                    <div class="col-12 text-md">
                        <p class="text-primary text-bold">
                            <i class="fa fa-info-circle mr-1" aria-hidden="true"></i>
                            Showing dimension for ${spanCount} span(s).
                        </p>
                    </div>
                `;
                spanDetailsContainer.append(heading);

                // Add each span to the page
                $.each(response.value, function (index, item) {
                    const spanDiv = `
                        <div class="col-sm-6 col-md-4">
                            <p class="text-md">
                                <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                Span ${item.span_sr_no} Length:
                                <span class="text-bold">${item.span_length} Mtrs</span>
                            </p>
                        </div>
                    `;
                    spanDetailsContainer.append(spanDiv);
                });

                // Show the modal
                spanDetailsModal.style.display = "block";

                // Close modal event handlers
                const span = document.querySelector(".closeFinalizedSpanModal");
                span.onclick = function () {
                    spanDetailsModal.style.display = "none";
                };

                window.onclick = function (event) {
                    if (event.target == spanDetailsModal) {
                        spanDetailsModal.style.display = "none";
                    }
                };
            }
        },
    });
}

function getFinalizedPierValue(id) {
    const pierDetailsModal = document.getElementById("pierDetailsModalFinalized");
    const pierDetailsContainer = $("#finalizedPierDetailsContainer");

    // Clear previous content
    pierDetailsContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-pier-details-finalized/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    // -----------------------------------------------------------------
                    // Reusable Function to Create HTML
                    // -----------------------------------------------------------------
                    const createDiv = (text, isSuccess = false) => {
                        const iconColor = isSuccess
                            ? "text-success"
                            : "text-primary";
                        const divClass = isSuccess
                            ? "col-sm-6 col-md-6"
                            : "col-sm-6 col-md-4"; //Adjust width if it is well foundation section
                        return `
                            <div class="${divClass}">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs ${iconColor} mr-1" aria-hidden="true"></i>
                                    ${text}
                                </p>
                            </div>
                        `;
                    };

                    // -----------------------------------------------------------------
                    // Append Data to the Content
                    // -----------------------------------------------------------------
                    pierDetailsContainer.append(`
                        <div class="col-12 mt-2 text-md">
                            <p class="text-primary text-bold border bg-primary p-1">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Pier Type: ${item.pier_type_descr}
                            </p>
                        </div>
                    `);

                    // Dimensions
                    pierDetailsContainer.append(
                        createDiv(
                            `Length : <span class="text-bold">${item.pier_length} Mtrs</span>`
                        )
                    );
                    pierDetailsContainer.append(
                        createDiv(
                            `Width : <span class="text-bold">${item.pier_width} Mtrs</span>`
                        )
                    );
                    pierDetailsContainer.append(
                        createDiv(
                            `Height : <span class="text-bold">${item.pier_heigth} Mtrs</span>`
                        )
                    );

                    // Bearing Information
                    pierDetailsContainer.append(`
                        <div class="col-12">
                            <p class="text-md">
                                <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                Bearing Type: <span class="text-bold">${item.bearing_type_descr}</span>
                            </p>
                        </div>
                    `);

                    // Foundation Information
                    pierDetailsContainer.append(`
                        <div class="col-12">
                            <p class="text-md border bg-success px-1">
                                Foundation Type: <span class="text-bold">${item.foundation_descr}</span>
                            </p>
                        </div>
                    `);

                    // Optional values
                    if (item.pile_diameter != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Pile Diameter: <span class="text-bold">${item.pile_diameter} Mtrs</span>`,
                                true
                            )
                        );
                    }
                    if (item.pile_length != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Pile Length: <span class="text-bold">${item.pile_length} Mtrs</span>`,
                                true
                            )
                        );
                    }
                    if (item.pile_type_descr != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Pile Type: <span class="text-bold">${item.pile_type_descr} </span>`,
                                true
                            )
                        );
                    }
                    if (item.well_type_descr != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Well Type: <span class="text-bold">${item.well_type_descr} </span>`,
                                true
                            )
                        );
                    }
                    if (item.well_size != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Well Size: <span class="text-bold">${item.well_size} Mtrs</span>`,
                                true
                            )
                        );
                    }
                    if (item.open_foundation_size != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Open Foundation Size: <span class="text-bold">${item.open_foundation_size} Mtrs</span>`,
                                true
                            )
                        );
                    }
                    if (item.open_foundation_depth != null) {
                        pierDetailsContainer.append(
                            createDiv(
                                `Open Foundation Depth: <span class="text-bold">${item.open_foundation_depth} Mtrs</span>`,
                                true
                            )
                        );
                    }
                });

                // Show Modal
                pierDetailsModal.style.display = "block";

                // -----------------------------------------------------------------
                // Modal Event Listeners
                // -----------------------------------------------------------------
                const span = document.querySelector(".closeFinalizedPierModal");

                // Function to Close the Modal
                const closeModal = () => {
                    pierDetailsModal.style.display = "none";
                };
                span.onclick = closeModal;
                window.onclick = (event) => {
                    if (event.target == pierDetailsModal) {
                        closeModal();
                    }
                };
            }
        },
    });
}

function getFinalizedRetainWallValue(id) {
    const retainWallModal = document.getElementById("retainWallModalFinalized");
    const retainWallValContainer = $("#finalizedRetainWallValContainer");

    // Clear previous content
    retainWallValContainer.empty();

    $.ajax({
        type: "GET",
        url: "/asset-management/get-retain-wall-detail-finalized/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                $.each(response.value, function (index, item) {
                    // Retain Wall Header
                    const heading = `
                        <div class="col-12 mt-2 text-md">
                            <p class="text-primary text-bold border bg-primary p-1">
                                <i class="fa fa-caret-right mr-1" aria-hidden="true"></i>
                                Retain wall: ${index + 1}
                            </p>
                        </div>
                    `;
                    retainWallValContainer.append(heading);

                    // Information of the retain wall
                    const wallInfo = `
                        <div class="col-12 text-md">
                            <p class="text-bold">Retain Wall Type: ${item.reatain_wall_descr}</p>
                        </div>
                    `;
                    retainWallValContainer.append(wallInfo);

                    // Reusable function to creat dimension data
                    const createDimensionDiv = (labelText, valueText) => {
                        return `
                            <div class="col-sm-6 col-md-4">
                                <p class="text-md">
                                    <i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>
                                    ${labelText}:
                                    <span class="text-bold">${valueText} Mtrs</span>
                                </p>
                            </div>
                        `;
                    };

                    // Dimensions of the retain wall
                    retainWallValContainer.append(
                        createDimensionDiv("Length", item.retain_wall_length)
                    );
                    retainWallValContainer.append(
                        createDimensionDiv("Width", item.retain_wall_width)
                    );
                    retainWallValContainer.append(
                        createDimensionDiv("Height", item.retain_wall_heigth)
                    );
                });

                // Display an info banner, if the length of the array is one
                if (response.value.length === 1) {
                    const info = `
                        <div class="col-12 text-md">
                            <p class="text-secondary text-xs text-bold">
                                <i class="fa fa-info-circle text-xs mr-1" aria-hidden="true"></i>
                                Here, each retain wall has the same dimension.
                            </p>
                        </div>
                    `;
                    retainWallValContainer.append(info);
                }

                // Display Modal
                retainWallModal.style.display = "block";

                // Set the onclick events for closing the modal
                const span = document.querySelector(".closeFinalizedRetainWall");
                span.onclick = () => {
                    retainWallModal.style.display = "none";
                };
                window.onclick = (event) => {
                    if (event.target == retainWallModal) {
                        retainWallModal.style.display = "none";
                    }
                };
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
        },
    });
}