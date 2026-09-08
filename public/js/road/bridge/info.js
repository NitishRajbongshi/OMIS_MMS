async function initMap(bridge_name, latitude, longitude) {
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
    const map = new Map(document.getElementById("map"), {
        center: { lat: 26.094757374299146, lng: 94.58979407214116 },
        zoom: 8,
        mapId: "3d95c646e2e4e33c",
    });

    // A marker with a with a URL pointing to a PNG.
    const beachFlagImg = document.createElement("img");

    beachFlagImg.src = "/images/marker_icons/bridge_icon3.png";
    beachFlagImg.style.width = "28px";
    beachFlagImg.style.height = "32px";

    const marker = new AdvancedMarkerElement({
        map,
        title: bridge_name,
        position: { lat: latitude, lng: longitude },
        content: beachFlagImg,
    });

    marker.addListener("click", function () {
        // Zoom in the map
        map.setZoom(15); // Adjust the zoom level as needed
        // Center the map on the marker's position
        map.panTo({ lat: latitude, lng: longitude });
    });
}

function showMap(btn) {
    let bridge_number = btn.getAttribute("bridge_number");
    let bridge_name = btn.getAttribute("bridge_name");
    let bridge_type = btn.getAttribute("bridge_type");
    let bridge_location = btn.getAttribute("bridge_location");
    const [lat, lon] = bridge_location.split(",");
    const latitude = parseFloat(lat);
    const longitude = parseFloat(lon);
    $('.alertMgs').hide();
    // incase coordnates not collected
    if (latitude == 0 && longitude == 0) {
        $('.alertMgs').show();
    }
    $('#mapModal').on('shown.bs.modal', function (e) {
        initMap(bridge_name, latitude, longitude);
        var tableHtml =
            '<div><table class="text-xs table table-striped"><tbody>' +
            '<tr> <td> Bridge No </td><td>' + bridge_number + '</td > </tr>' +
            '<tr> <td> Bridge Name </td><td>' + bridge_name + '</td > </tr>' +
            '<tr> <td> Bridge Type </td><td>' + bridge_type + '</td> </tr>' +
            '<tr> <td> Latitude </td><td>' + latitude + '</td> </tr>' +
            '<tr> <td> Longitude </td><td>' + longitude + '</td> </tr>' +
            '</tbody></table></div>';

        $('.building_details_container').html(tableHtml);
    }).modal('show');
}

function getWingWallValue(id) {
    var wingWallModal = document.getElementById("wingWallModal");
    var span = document.getElementsByClassName("closeWingWall")[0];

    $.ajax({
        type: "GET",
        url: "/asset-management/get-bridge-wing-wall-detail/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status == "success") {
                $("#wingWallValContainer").empty();
                $.each(response.value, function (index, item) {
                    var heading = $(
                        '<div class="col-12 mt-2 text-md">' +
                        '<p class="text-primary text-bold border bg-primary p-1"><i class="fa fa-caret-right mr-1" aria-hidden="true"></i>Wing wall : ' +
                        ++index +
                        "</p>" +
                        "</div>"
                    );
                    $("#wingWallValContainer").append(heading);

                    var wingWallType = $(
                        '<div class="col-12 text-md">' +
                        '<p class="text-bold">Wing Wall Type: ' +
                        item.wing_wall_type_descr +
                        "</p>" +
                        "</div>"
                    );
                    $("#wingWallValContainer").append(wingWallType);

                    var length = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Length : <span class="text-bold">' +
                        item.length +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#wingWallValContainer").append(length);

                    var topWidth = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Top Width : <span class="text-bold">' +
                        item.top_width +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#wingWallValContainer").append(topWidth);

                    var buttomWidth = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Bottom Width : <span class="text-bold">' +
                        item.bottom_width +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#wingWallValContainer").append(buttomWidth);

                    var height1 = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Height 1 : <span class="text-bold">' +
                        item.height1 +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#wingWallValContainer").append(height1);

                    var height2 = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Height 2 : <span class="text-bold">' +
                        item.height2 +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#wingWallValContainer").append(height2);

                    var slope = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Slop : <span class="text-bold">' +
                        item.slope +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#wingWallValContainer").append(slope);

                    if (item.angle != null) {
                        var angle = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Angle : <span class="text-bold">' +
                            item.angle +
                            ' Degrees</span></p>' +
                            '</div>'
                        );
                        $("#wingWallValContainer").append(angle);
                    }

                    if (item.radius != null) {
                        var radius = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Radius : <span class="text-bold">' +
                            item.radius +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#wingWallValContainer").append(radius);
                    }
                });
                if (response.value.length == 1) {
                    var info = $(
                        '<div class="col-12 text-md">' +
                        '<p class="text-secondary text-xs text-bold"><i class="fa fa-info-circle text-xs mr-1" aria-hidden="true"></i>Here, each wing wall has the same dimension.</p>' +
                        "</div>"
                    );
                    $("#wingWallValContainer").append(info);
                }
                wingWallModal.style.display = "block";
                span.onclick = function () {
                    wingWallModal.style.display = "none";
                };
                window.onclick = function (event) {
                    if (event.target == wingWallModal) {
                        wingWallModal.style.display = "none";
                    }
                };
            }
        },
    });
}

function getHeadWallValue(id) {
    var headWallModal = document.getElementById("headWallModal");
    var span = document.getElementsByClassName("closeHeadWall")[0];

    $.ajax({
        type: "GET",
        url: "/asset-management/get-bridge-head-wall-detail/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status == "success") {
                $("#headWallValContainer").empty();
                $.each(response.value, function (index, item) {
                    var heading = $(
                        '<div class="col-12 mt-2 text-md">' +
                        '<p class="text-primary text-bold border bg-primary p-1"><i class="fa fa-caret-right mr-1" aria-hidden="true"></i>Stream: ' +
                        item.stream_descr +
                        "</p>" +
                        "</div>"
                    );
                    $("#headWallValContainer").append(heading);

                    var headWallType = $(
                        '<div class="col-12 text-md">' +
                        '<p class="text-bold">Head Wall Type: ' +
                        item.head_wall_descr +
                        "</p>" +
                        "</div>"
                    );
                    $("#headWallValContainer").append(headWallType);

                    var headWallLength = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Length : <span class="text-bold">' +
                        item.head_wall_length +
                        " Mtrs</span></p>" +
                        "</div>"
                    );
                    $("#headWallValContainer").append(headWallLength);

                    var headWallWidth = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Width : <span class="text-bold">' +
                        item.head_wall_width +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#headWallValContainer").append(headWallWidth);

                    var headWallHeight = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Height : <span class="text-bold">' +
                        item.head_wall_heigth +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#headWallValContainer").append(headWallHeight);
                });

                headWallModal.style.display = "block";
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
    var abutmentWallModal = document.getElementById("abutmentWallModal");
    var span = document.getElementsByClassName("closeAbutmentWall")[0];

    $.ajax({
        type: "GET",
        url: "/asset-management/get-abutment-wall-detail/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status == "success") {
                $("#abutmentWallValContainer").empty();
                $.each(response.value, function (index, item) {
                    var heading = $(
                        '<div class="col-12 mt-2 text-md">' +
                        '<p class="text-primary text-bold border bg-primary p-1"><i class="fa fa-caret-right mr-1" aria-hidden="true"></i>Abutment Wall Type: ' +
                        item.abutment_type_descr +
                        "</p>" +
                        "</div>"
                    );
                    $("#abutmentWallValContainer").append(heading);

                    var length = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Length : <span class="text-bold">' +
                        item.abutment_wall_length +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#abutmentWallValContainer").append(length);

                    var width = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Width : <span class="text-bold">' +
                        item.abutment_wall_width +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#abutmentWallValContainer").append(width);

                    var height = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Height : <span class="text-bold">' +
                        item.abutment_wall_heigth +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#abutmentWallValContainer").append(height);

                    var bearing = $(
                        '<div class="col-12">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Bearing Type: <span class="text-bold">' +
                        item.bearing_type_descr +
                        ' </span></p>' +
                        '</div>'
                    );
                    $("#abutmentWallValContainer").append(bearing);

                    var foundation = $(
                        '<div class="col-12">' +
                        '<p class="text-md border bg-success px-1">Foundation Type: <span class="text-bold">' +
                        item.foundation_descr +
                        ' </span></p>' +
                        '</div>'
                    );
                    $("#abutmentWallValContainer").append(foundation);

                    if (item.pile_diameter != null) {
                        var pileDiameter = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Pile Diameter: <span class="text-bold">' +
                            item.pile_diameter +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#abutmentWallValContainer").append(pileDiameter);
                    }
                    if (item.pile_length != null) {
                        var pileLength = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Pile Length: <span class="text-bold">' +
                            item.pile_length +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#abutmentWallValContainer").append(pileLength);
                    }
                    if (item.pile_type_descr != null) {
                        var pileType = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Pile Type: <span class="text-bold">' +
                            item.pile_type_descr +
                            ' </span></p>' +
                            '</div>'
                        );
                        $("#abutmentWallValContainer").append(pileType);
                    }

                    if (item.well_type_descr != null) {
                        var wellType = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Well Type: <span class="text-bold">' +
                            item.well_type_descr +
                            ' </span></p>' +
                            '</div>'
                        );
                        $("#abutmentWallValContainer").append(wellType);
                    }
                    if (item.well_size != null) {
                        var wellSize = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Well Size: <span class="text-bold">' +
                            item.well_size +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#abutmentWallValContainer").append(wellSize);
                    }

                    if (item.open_foundation_size != null) {
                        var openFoundationSize = $(
                            '<div class="col-sm-6 col-md-6">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Open Foundation Size: <span class="text-bold">' +
                            item.open_foundation_size +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#abutmentWallValContainer").append(openFoundationSize);
                    }
                    if (item.open_foundation_depth != null) {
                        var openFoundationDepth = $(
                            '<div class="col-sm-6 col-md-6">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Open Foundation Depth: <span class="text-bold">' +
                            item.open_foundation_depth +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#abutmentWallValContainer").append(openFoundationDepth);
                    }
                });
                abutmentWallModal.style.display = "block";

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

// function to show span details
function getSpanValue(id) {
    var spanDetailsModal = document.getElementById("spanDetailsModal");
    var span = document.getElementsByClassName("closeSpanModal")[0];

    $.ajax({
        type: "GET",
        url: "/asset-management/get-span-details/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            if (response.status == "success") {
                $("#spanDetailsContainer").empty();
                const spanCount = response.value.length;
                var heading = $(
                    '<div class="col-12 text-md">' +
                    '<p class="text-primary text-bold"><i class="fa fa-info-circle mr-1" aria-hidden="true"></i>Showing dimension for ' + spanCount + ' span(s).</p>' +
                    "</div>"
                );
                $("#spanDetailsContainer").append(heading);

                $.each(response.value, function (index, item) {
                    var div1 = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Span ' + item.span_sr_no + ' Length: <span class="text-bold">' +
                        item.span_length +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#spanDetailsContainer").append(div1);
                });

                spanDetailsModal.style.display = "block";

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

// function to show pier details
function getPierValue(id) {
    var pierDetailsModal = document.getElementById("pierDetailsModal");
    var span = document.getElementsByClassName("closePierModal")[0];

    $.ajax({
        type: "GET",
        url: "/asset-management/get-pier-details/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status == "success") {
                $("#pierDetailsContainer").empty();
                $.each(response.value, function (index, item) {
                    var heading = $(
                        '<div class="col-12 mt-2 text-md">' +
                        '<p class="text-primary text-bold border bg-primary p-1"><i class="fa fa-caret-right mr-1" aria-hidden="true"></i>Pier Type: ' +
                        item.pier_type_descr +
                        "</p>" +
                        "</div>"
                    );
                    $("#pierDetailsContainer").append(heading);

                    var length = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Length : <span class="text-bold">' +
                        item.pier_length +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#pierDetailsContainer").append(length);

                    var width = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Width : <span class="text-bold">' +
                        item.pier_width +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#pierDetailsContainer").append(width);

                    var height = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Height : <span class="text-bold">' +
                        item.pier_heigth +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#pierDetailsContainer").append(height);

                    var bearing = $(
                        '<div class="col-12">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Bearing Type: <span class="text-bold">' +
                        item.bearing_type_descr +
                        ' </span></p>' +
                        '</div>'
                    );
                    $("#pierDetailsContainer").append(bearing);

                    var foundation = $(
                        '<div class="col-12">' +
                        '<p class="text-md border bg-success px-1">Foundation Type: <span class="text-bold">' +
                        item.foundation_descr +
                        ' </span></p>' +
                        '</div>'
                    );
                    $("#pierDetailsContainer").append(foundation);

                    if (item.pile_diameter != null) {
                        var pileDiameter = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Pile Diameter: <span class="text-bold">' +
                            item.pile_diameter +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#pierDetailsContainer").append(pileDiameter);
                    }
                    if (item.pile_length != null) {
                        var pileLength = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Pile Length: <span class="text-bold">' +
                            item.pile_length +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#pierDetailsContainer").append(pileLength);
                    }
                    if (item.pile_type_descr != null) {
                        var pileType = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Pile Type: <span class="text-bold">' +
                            item.pile_type_descr +
                            ' </span></p>' +
                            '</div>'
                        );
                        $("#pierDetailsContainer").append(pileType);
                    }

                    if (item.well_type_descr != null) {
                        var wellType = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Well Type: <span class="text-bold">' +
                            item.well_type_descr +
                            ' </span></p>' +
                            '</div>'
                        );
                        $("#pierDetailsContainer").append(wellType);
                    }
                    if (item.well_size != null) {
                        var wellSize = $(
                            '<div class="col-sm-6 col-md-4">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Well Size: <span class="text-bold">' +
                            item.well_size +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#pierDetailsContainer").append(wellSize);
                    }

                    if (item.open_foundation_size != null) {
                        var openFoundationSize = $(
                            '<div class="col-sm-6 col-md-6">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Open Foundation Size: <span class="text-bold">' +
                            item.open_foundation_size +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#pierDetailsContainer").append(openFoundationSize);
                    }
                    if (item.open_foundation_depth != null) {
                        var openFoundationDepth = $(
                            '<div class="col-sm-6 col-md-6">' +
                            '<p class="text-md"><i class="fa fa-circle text-xs text-success mr-1" aria-hidden="true"></i>Open Foundation Depth: <span class="text-bold">' +
                            item.open_foundation_depth +
                            ' Mtrs</span></p>' +
                            '</div>'
                        );
                        $("#pierDetailsContainer").append(openFoundationDepth);
                    }
                });
                pierDetailsModal.style.display = "block";
                span.onclick = function () {
                    pierDetailsModal.style.display = "none";
                };
                window.onclick = function (event) {
                    if (event.target == pierDetailsModal) {
                        pierDetailsModal.style.display = "none";
                    }
                };
            }
        },
    });
}

function getRetainWallValue(id) {
    var retainWallModal = document.getElementById("retainWallModal");
    var span = document.getElementsByClassName("closeRetainWall")[0];

    $.ajax({
        type: "GET",
        url: "/asset-management/get-retain-wall-detail/" + id,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status == "success") {
                $("#retainWallValContainer").empty();
                $.each(response.value, function (index, item) {
                    var heading = $(
                        '<div class="col-12 mt-2 text-md">' +
                        '<p class="text-primary text-bold border bg-primary p-1"><i class="fa fa-caret-right mr-1" aria-hidden="true"></i>Retain wall : ' +
                        ++index +
                        "</p>" +
                        "</div>"
                    );
                    $("#retainWallValContainer").append(heading);

                    var type = $(
                        '<div class="col-12 text-md">' +
                        '<p class="text-bold">Retain Wall Type: ' +
                        item.reatain_wall_descr +
                        "</p>" +
                        "</div>"
                    );
                    $("#retainWallValContainer").append(type);

                    var length = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Length : <span class="text-bold">' +
                        item.retain_wall_length +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#retainWallValContainer").append(length);

                    var width = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Width : <span class="text-bold">' +
                        item.retain_wall_width +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#retainWallValContainer").append(width);

                    var height = $(
                        '<div class="col-sm-6 col-md-4">' +
                        '<p class="text-md"><i class="fa fa-circle text-xs text-primary mr-1" aria-hidden="true"></i>Height : <span class="text-bold">' +
                        item.retain_wall_heigth +
                        ' Mtrs</span></p>' +
                        '</div>'
                    );
                    $("#retainWallValContainer").append(height);
                });
                if (response.value.length == 1) {
                    var info = $(
                        '<div class="col-12 text-md">' +
                        '<p class="text-secondary text-xs text-bold"><i class="fa fa-info-circle text-xs mr-1" aria-hidden="true"></i>Here, each retain wall has the same dimension.</p>' +
                        "</div>"
                    );
                    $("#retainWallValContainer").append(info);
                }
                retainWallModal.style.display = "block";
                span.onclick = function () {
                    retainWallModal.style.display = "none";
                };
                window.onclick = function (event) {
                    if (event.target == retainWallModal) {
                        retainWallModal.style.display = "none";
                    }
                };
            }
        },
    });
}