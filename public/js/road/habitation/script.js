// $(document).ready(function () {
$('#district, #block, #village, #mla_constituency_cd, #mp_constituency_cd').select2();
$(document).on('change', '.district-select', function () {
    const district_cd = $(this).val();
    const form = $(this).closest('form');
    console.log("district_cd xxx :" + district_cd);
    $.ajax({
        url: '/asset-management/block/' + district_cd,
        type: 'GET',
        cache: false,
        success: function (response) {
            console.log("success");
            if (response.status == 'success') {
                const selectBlock = form.find('.block-select');
                selectBlock.empty();
                const selectVillage = form.find('.village-select');
                selectVillage.empty();
                selectBlock.append('<option value="">Choose One</option>');
                selectVillage.append('<option value="">Choose One</option>');
                $.each(response.result, function (index, block) {
                    selectBlock.append('<option class="text-uppercase" value="' + block.block_cd +
                        '">' + block.block_name + '</option>');
                });
                $('.block-select').prop('disabled', false);
            } else {
                alert("failed to fetch the Block list!");
            }
        }
    });
});

$(document).on('change', '.block-select', function () {
    // $('#block').on('input', () => {
    const block_cd = $(this).val();
    const form = $(this).closest('form');
    $.ajax({
        url: '/asset-management/village/' + block_cd,
        type: 'GET',
        cache: false,
        success: function (response) {
            if (response.status == 'success') {
                const select = form.find('.village-select');
                select.empty();
                select.append('<option value="">Choose One</option>');
                $.each(response.result, function (index, village) {
                    select.append('<option class="text-uppercase" value="' + village.village_code +
                        '">' + village.village_name + '</option>');
                });
                $('.village-select').prop('disabled', false);
            } else {
                alert("failed to fetch the village list!");
            }
        }
    });
});

// $("#village").on("change", function () {
$(document).on('change', '.village-select', function () {
    const selectedVillCode = $(this).val();
    const form = $(this).closest('form');
    // ajax call for get population
    $.ajax({
        url: "/asset-management/habitation-population/" + selectedVillCode,
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        cache: false,
        success: function (response) {
            console.log(response);
            if (response.status == 200) {
                console.log(response.result.census2011_village_code);
                $(".total_population").val(
                    response.result.total_population
                );
            }

            if (response.status == 204) {
                showDashboardModal(response.message);
                $(".total_population").val(null);
            }

            if (response.status == 401) {
                showDashboardModal(response.message);
                $(".total_population").val(null);
            }

            if (response.status == 500) {
                showDashboardModal(response.message);
                $(".total_population").val(null);
            }
        },
    });
});

// send data for finalization
$("#freezeBtn").on("click", function () {
    const status = confirm('Are you sure?');
    if (status) {
        var selectedAsset = $(".selected-asset:checked")
            .map(function () {
                return $(this).data("habitation");
            })
            .get();

        if (selectedAsset.length === 0) {
            showDashboardModal("Select at least one record to send for finalization!");
        } else {
            $.ajax({
                type: "GET",
                url: "/asset-management/send-habitation-details-finalization",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: { assetList: selectedAsset },
                cache: false,
                success: function (response) {
                    console.log(response);
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

//Facilities JQuery -- start
$('#facility_id').change(function () {
    let facilityId = $(this).val();

    $('#sub_facility_id option').hide();
    $('#sub_facility_id option:first').show();

    $('#sub_facility_id option[data-facility="' + facilityId + '"]').show();
    $('#sub_facility_id').val('');
});

// Add to table
$('#addFacilityBtn').click(function () {

    let facilityId = $('#facility_id').val();
    let facilityText = $('#facility_id option:selected').text();

    let subFacilityId = $('#sub_facility_id').val();
    let subFacilityText = $('#sub_facility_id option:selected').text();

    if (!facilityId || !subFacilityId) {
        alert('Please select both Facility and Sub Facility');
        return;
    }

    // Prevent duplicate
    if ($('#facilityTable tbody tr[data-sub="' + subFacilityId + '"]').length > 0) {
        alert('Already added');
        return;
    }

    let row = `
            <tr data-sub="${subFacilityId}">
                <td>
                    ${facilityText}
                    <input type="hidden" name="facility_ids[]" value="${facilityId}">
                </td>
                <td>
                    ${subFacilityText}
                    <input type="hidden" name="sub_facility_ids[]" value="${subFacilityId}">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm deleteRow">
                        Delete
                    </button>
                </td>
            </tr>
        `;

    $('#facilityTable tbody').append(row);
});

// Delete row
$(document).on('click', '.deleteRow', function () {
    $(this).closest('tr').remove();
});


$(document).on('click', '.add-facility-btn', function () {

    let modal = $(this).closest('.tab-pane');

    let facility = modal.find('.facility-select option:selected');
    let subfacility = modal.find('.subfacility-select option:selected');

    let facilityId = facility.val();
    let facilityName = facility.text();

    let subId = subfacility.val();
    let subName = subfacility.text();

    if (!facilityId || !subId) {
        alert("Select facility and sub facility");
        return;
    }

    let row = `
        <tr>

            <td>
                ${facilityName}
                <input type="hidden" name="facility_ids[]" value="${facilityId}">
            </td>

            <td>
                ${subName}
                <input type="hidden" name="sub_facility_ids[]" value="${subId}">
            </td>

            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
            </td>

        </tr>
    `;

    modal.find('.edit-facility-table tbody').append(row);

});


$(document).on('click', '.remove-row', function () {

    $(this).closest('tr').remove();

});
//Facilities JQuery -- end
// });

$(document).on('change', '.facility-select', function () {

    let facilityId = $(this).val();

    let modal = $(this).closest('.tab-pane');

    let subSelect = modal.find('.subfacility-select');

    subSelect.val("");

    subSelect.find('option').hide();

    subSelect.find('option:first').show();

    subSelect.find('option[data-facility="' + facilityId + '"]').show();

});

$(function () {
    $("#habitation_details_table").DataTable({}).buttons().container().appendTo(
        '#habitation_details_table_wrapper .col-md-11:eq(1)');
});