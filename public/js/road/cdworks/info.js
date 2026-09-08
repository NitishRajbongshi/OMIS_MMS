function getHeadWallValue(id) {
    var headWallModal = document.getElementById("headWallModal");
    var span = document.getElementsByClassName("closeHeadWall")[0];

    $.ajax({
        type: "GET",
        url: '/asset-management/get-head-wall-detail/' + id,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        success: function (response) {
            if (response.status == 'success') {
                $('#headWallValContainer').empty();
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
                }
                window.onclick = function (event) {
                    if (event.target == headWallModal) {
                        headWallModal.style.display = "none";
                    }
                }
            }
        }
    });
}

function getWingWallValue(id) {
    var wingWallModal = document.getElementById("wingWallModal");
    var span = document.getElementsByClassName("closeWingWall")[0];
    $.ajax({
        type: "GET",
        url: '/asset-management/get-wing-wall-detail/' + id,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        success: function(response) {
            if (response.status == 'success') {
                $('#wingWallValContainer').empty();
                $.each(response.value, function(index, item) {
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
                span.onclick = function() {
                    wingWallModal.style.display = "none";
                }
                window.onclick = function(event) {
                    if (event.target == wingWallModal) {
                        wingWallModal.style.display = "none";
                    }
                }
            }
        }
    });
}