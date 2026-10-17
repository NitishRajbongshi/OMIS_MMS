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


const weightageUrl = '/asset-management/get-population-weightage';

async function updatePopulationWeightage(input) {
    const population = input.value;
    const container = input.closest('.modal-body') || document;

    const output = container.querySelector(
        input.closest('.modal-body')
            ? '.edit-population-weightage'
            : '#population_weightage'
    );

    if (!output) return;

    if (population === '' || Number(population) < 0) {
        output.value = '';
        return;
    }

    try {
        const response = await fetch(
            weightageUrl + '?population=' + encodeURIComponent(population)
        );

        if (!response.ok) {
            throw new Error('HTTP error: ' + response.status);
        }

        const data = await response.json();

        output.value = data.weightage !== null &&
            data.weightage !== undefined
            ? Number(data.weightage).toFixed(2)
            : 'No matching range';

    } catch (error) {
        output.value = '';
        console.error('Failed to fetch population weightage:', error);
    }
}

// Manual population entry
$(document).on('input', '.total_population', function () {
    updatePopulationWeightage(this);
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
                const populationInput = form.find('.total_population');

                populationInput.val(response.result.total_population);

                // Calculate weightage for automatically fetched population
                if (populationInput.length) {
                    updatePopulationWeightage(populationInput[0]);
                }
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

const facilityUrl = '/asset-management/get-facility-weightage';

async function updateFacilityWeightage(selectElement) {

    const facilityId = $(selectElement).val();
    const container = selectElement.closest('.modal-body') || document;

    const output = container.querySelector(
        selectElement.closest('.modal-body')
            ? '.edit-facility-weightage'
            : '#facility_weightage'
    );

    if (!output) return;

    if (!facilityId) {
        output.value = '';
        return;
    }

    try {
        const response = await fetch(
            facilityUrl + '?facility_id=' + encodeURIComponent(facilityId)
        );

        if (!response.ok) {
            throw new Error('HTTP error: ' + response.status);
        }

        const data = await response.json();

        output.value = data.weightage != null
            ? Number(data.weightage).toFixed(2)
            : '';

    } catch (error) {
        output.value = '';
        console.error('Failed to fetch facility weightage:', error);
    }
}

//Facilities JQuery -- start
$(document).on('change', '#facility_id, .facility-select', function () {

    const facilityId = $(this).val();
    const container = this.closest('.modal-body') || document;

    const isEdit = !!this.closest('.modal-body');

    const subSelect = $(container).find(
        isEdit ? '.subfacility-select' : '#sub_facility_id'
    );

    const subWeightage = $(container).find(
        isEdit ? '.edit-sub-facility-weightage' : '#sub_facility_weightage'
    );

    // Clear previous sub-facility and its weightage
    subSelect.val('');
    subWeightage.val('');

    // Filter sub-facilities
    subSelect.find('option').hide();
    subSelect.find('option:first').show();

    if (facilityId) {
        subSelect.find('option[data-facility="' + facilityId + '"]').show();
    }

    // Update selected facility weightage
    updateFacilityWeightage(this);
});


const subFacilityUrl = '/asset-management/get-sub-facility-weightage';

async function updateSubFacilityWeightage(selectElement) {
    const subFacilityId = $(selectElement).val();
    const container = selectElement.closest('.modal-body') || document;

    const output = container.querySelector(
    selectElement.closest('.modal-body')
        ? '.edit-sub-facility-weightage'
        : '#sub_facility_weightage'
);

    if (!output) return;

    if (!subFacilityId) {
        output.value = '';
        return;
    }

    try {
        const response = await fetch(
            subFacilityUrl + '?sub_facility_id=' +
            encodeURIComponent(subFacilityId)
        );

        if (!response.ok) {
            throw new Error('HTTP error: ' + response.status);
        }

        const data = await response.json();

        output.value = data.weightage != null
            ? Number(data.weightage).toFixed(2)
            : '';
    } catch (error) {
        output.value = '';
        console.error('Failed to fetch sub-facility weightage:', error);
    }
}

$(document).on('change', '#sub_facility_id, .subfacility-select', function () {
    updateSubFacilityWeightage(this);
});


// Add to table
$('#addFacilityBtn').click(async function () {

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

    const [facilityResponse, subResponse] = await Promise.all([
            fetch(facilityUrl + '?facility_id=' + encodeURIComponent(facilityId)),
            fetch(subFacilityUrl + '?sub_facility_id=' + encodeURIComponent(subFacilityId))
    ]);

    if (!facilityResponse.ok || !subResponse.ok) {
        throw new Error('Failed to fetch weightages');
    }

    const facilityData = await facilityResponse.json();
    const subData = await subResponse.json();

    const facilityWeightage = Number(facilityData.weightage || 0).toFixed(2);
    const subFacilityWeightage = Number(subData.weightage || 0).toFixed(2);

    let row = `
            <tr data-sub="${subFacilityId}">
                <td>
                    ${facilityText}
                    <input type="hidden" name="facility_ids[]" value="${facilityId}">
                </td>
                <td>
                    ${facilityWeightage}
                </td>
                <td>
                    ${subFacilityText}
                    <input type="hidden" name="sub_facility_ids[]" value="${subFacilityId}">
                </td>
                <td>
                    ${subFacilityWeightage}
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
    let facilityWeightage = modal.find('.edit-facility-weightage').val();

    let subId = subfacility.val();
    let subName = subfacility.text();
    let subWeightage = modal.find('.edit-sub-facility-weightage').val();

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
                ${facilityWeightage}
            </td>

            <td>
                ${subName}
                <input type="hidden" name="sub_facility_ids[]" value="${subId}">
            </td>

            <td>
                ${subWeightage}
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

