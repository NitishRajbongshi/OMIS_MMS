$('#chainage, #start_chainage').on('blur', function() {
    const selectedValue = $(this).val();
    const roadId = $("#road_system_id").val();
    $('#coordinates_geting_info').show();
    $('#asset_location_container').hide();
    $('#lat').val('0');
    $('#lng').val('0');
    if (selectedValue !== '') {
        $('#asset_location_container').show();
        // call ajax request to get the cooradinate for the culvert
        $.ajax({
            url: '/asset-management/get-coordinates',
            method: 'GET',
            data: {
                chainage: selectedValue,
                road_id: roadId
            },
            success: function (response) {
                console.log(response);
                if(response.status == 200) {
                    let coordinates = response.location;
                    let [lat, lng] = coordinates.split(',');
                    $('#lat').val(lat);
                    $('#lng').val(lng);
                    $('#coordinates_geting_info').hide();

                    // Enable save button after success
                    $('#saveBtn').prop('disabled', false);
                } else {
                    $('#coordinates_geting_info').hide();
                    $('#asset_location_container').hide();
                    alert('Error fetching coordinates for the asset. Please try again.');

                    // Keep button disabled on error
                    $('#saveBtn').prop('disabled', true);
                }
            },
            error: function (xhr) {
                $('#coordinates_geting_info').hide();
                $('#asset_location_container').hide();
                alert('Error fetching coordinates for the asset. Please try again.');
                console.error('Error fetching coordinates:', xhr.responseText);

                // Keep button disabled on error
                $('#saveBtn').prop('disabled', true);
            }
        });
    }
});
