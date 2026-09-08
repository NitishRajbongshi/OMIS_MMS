$(document).ready(function () {
    $('#surface_type_cd, #surface_condition, #sub_base_layer_type, #base_layer_type, #pavment_type, #shoulder_type, #land_slide, #construction_year, #maintenance_type, #drainage').select2();
    // send data for finalization
    $("#freezeBtn").on("click", function () {
        const status = confirm('Are you sure?');
        if (status) {
            var selectedAsset = $(".selected-asset:checked")
                .map(function () {
                    return $(this).data("surface-type");
                })
                .get();

            if (selectedAsset.length === 0) {
                showDashboardModal("Select at least one record to send for finalization!");
            } else {
                $.ajax({
                    type: "GET",
                    url: "/asset-management/send-surface-type-details-finalization",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: { assetList: selectedAsset },
                    cache: false,
                    success: function (response) {
                        if (response.status === 200) {
                            showSuccessModal(response.message);
                        }
                        if (response.status === 503) {
                            showDashboardModal(response.message);
                        }

                        if (response.status === 401) {
                            showDashboardModal(response.message);
                        }

                        if (response.status === 500) {
                            showDashboardModal(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            }
        }
    });

    $('#to_chainage').on('input', () => {
        let length = parseInt($('#road_length').val());
        let to_chainage = parseInt($("#to_chainage").val());
        if (to_chainage > length) {
            alert("Please enter a valid chainage");
            $('#to_chainage').val('');
        }
    })

    $('#drainage').on('change', function () {
        const drainageValue = $(this).val();
        $('#drainage_container').hide();
        $('#line_drainage').hide();
        $('#line_drainage_left').hide();
        $('#line_drainage_right').hide();
        if (drainageValue === '0') {
            $('#drainage_container').show();
        }
    })

    $('#land_slide').on('change', () => {
        const landSlide = $('#land_slide').val();
        $('#chainage_container').hide();
        $('#to_chainage').removeAttr('required');
        $('#to_chainage').prop('disabled', false);
        if (landSlide == 'Y') {
            $('#chainage_container').show();
            $('#to_chainage').attr('required');
            $('#to_chainage').prop('disabled', false);
        } else {
            $('#chainage_container').hide();
            $('#to_chainage').removeAttr('required');
            $('#to_chainage').prop('disabled', false);
        }
    })

    $('form.updateSurfaceTypeDetails').on("submit", function (e) {
        e.preventDefault();
        let location = "{{ route('road.add-surface-type') }}";
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            cache: false,
            success: function (response) {
                console.log(response);
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: response.message,
                        showConfirmButton: true,
                        timer: 3000
                    })
                        .then(() => {
                            window.location.replace(location)
                        });
                } else if (response.status === 'failed') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        showConfirmButton: true,
                        timer: 3000
                    })
                        .then(() => {
                            window.location.replace(location)
                        });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something Went Wrong!',
                        showConfirmButton: true,
                        timer: 3000
                    })
                        .then(() => {
                            window.location.replace(location)
                        });
                }
            }
        });
    });

    const retainWallRadio = document.querySelectorAll(
        'input[name="line_drainage_side"]'
    );

    retainWallRadio.forEach((radio) => {
        radio.addEventListener("change", function () {
            const lineDrainageSideStatus = $(this).val();
            $('#line_drainage').hide();
            $('#line_drainage_left').hide();
            $('#line_drainage_right').hide();
            if (lineDrainageSideStatus === 'Y') {
                $('#line_drainage').hide();
                $('#line_drainage_left').show();
                $('#line_drainage_right').show();
            } else {
                $('#line_drainage').show();
                $('#line_drainage_left').hide();
                $('#line_drainage_right').hide();
            }
        });
    });
});