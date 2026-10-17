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
    const modal = input.closest('.modal');

    const output = modal
        ? modal.querySelector('.edit-population-weightage')
        : document.querySelector('#population_weightage');

    if (!output) {
        console.log('Weightage output not found');
        return;
    }

    const population = input.value;

    if (population === '' || Number(population) < 0) {
        output.value = '';
        return;
    }

    const schemeId = modal
        ? (
            modal.querySelector('.scheme-select')?.value ||
            modal.querySelector('input[name="scheme_id"]')?.value
        )
        : document.querySelector('#scheme_id')?.value;

    if (!schemeId) {
        output.value = '';
        console.log('Scheme ID not found');
        return;
    }

    try {
        const response = await fetch(
            weightageUrl +
            '?population=' + encodeURIComponent(population) +
            '&scheme_id=' + encodeURIComponent(schemeId)
        );

        if (!response.ok) {
            throw new Error('HTTP error: ' + response.status);
        }

        const data = await response.json();

        output.value = data.weightage != null
            ? Number(data.weightage).toFixed(2)
            : 'No matching range';

    } catch (error) {
        output.value = '';
        console.error('Failed to fetch population weightage:', error);
    }
}

$(document).off('input', '.total_population');

$(document).on('input', '.total_population', function () {
    console.log('Population changed:', this.value);
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

    const facilityId = String($(this).val() || '');
    const container = this.closest('.modal-body') || document;
    const isEdit = !!this.closest('.modal-body');

    const subSelect = $(container).find(
        isEdit ? '.subfacility-select' : '#sub_facility_id'
    );

    const subWeightage = $(container).find(
        isEdit ? '.edit-sub-facility-weightage' : '#sub_facility_weightage'
    );

    const schemeId = isEdit
        ? String($(container).find('.scheme-select').val() || '')
        : String($('#scheme_id').val() || '');

    // Clear previous selection and weightage
    subSelect.val('');
    subWeightage.val('');

    // Hide all sub-facilities first
    subSelect.find('option').each(function () {

        const option = $(this);

        if (!option.val()) {
            option.prop('hidden', false);
            return;
        }

        const optionFacilityId = String(option.attr('data-facility') || '');
        const optionSchemeId = String(option.attr('data-scheme') || '');

        const matches = facilityId !== '' &&
            optionFacilityId === facilityId &&
            optionSchemeId === schemeId;

        option.prop('hidden', !matches);
    });

    subSelect.trigger('change');

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

    let facilityId = String($(this).val() || '');

    let modal = $(this).closest('.modal');

    let schemeId = String(modal.find('.scheme-select').val() || '');

    let subSelect = modal.find('.subfacility-select');

    subSelect.val('');

    subSelect.find('option').each(function () {

        let option = $(this);

        let optionFacilityId = String(option.attr('data-facility') || '');
        let optionSchemeId = String(option.attr('data-scheme') || '');

        if (!option.val()) {
            option.prop('hidden', false);
        } else if (
            optionFacilityId === facilityId &&
            optionSchemeId === schemeId
        ) {
            option.prop('hidden', false);
        } else {
            option.prop('hidden', true);
        }
    });

    subSelect.trigger('change');

    modal.find('.edit-sub-facility-weightage').val('');
});

$(function () {
    $("#habitation_details_table").DataTable({}).buttons().container().appendTo(
        '#habitation_details_table_wrapper .col-md-11:eq(1)');
});


$(document).ready(function () {

    const $scheme = $('#scheme_id');
    const $facility = $('#facility_id');
    const $subFacility = $('#sub_facility_id');

    const facilityOptions = $facility.find('option').clone();
    const subFacilityOptions = $subFacility.find('option').clone();

    $scheme.on('change', function () {
        const schemeId = String($(this).val() || '');

        $facility.empty().append('<option value="">Choose Facility</option>');
        $subFacility.empty().append('<option value="">Choose Sub Facility</option>');

        $('#facility_weightage').val('');
        $('#sub_facility_weightage').val('');

        $('#facilityTable tbody').empty();

        if (!schemeId) {
            $('#population_weightage').val('');
            return;
        }

        $facility.prop('disabled', !schemeId);
        $subFacility.prop('disabled', true);

        if (!schemeId) return;

        facilityOptions.each(function () {
            const option = $(this);

            if (option.val() && String(option.data('scheme')) === schemeId) {
                $facility.append(option.clone());
            }
        });

        $('.total_population').each(function () {
            if (this.value !== '') {
                updatePopulationWeightage(this);
            }
        });
    });

    $facility.on('change', function () {
        const facilityId = String($(this).val() || '');
        const schemeId = String($scheme.val() || '');

        $subFacility.empty().append('<option value="">Choose Sub Facility</option>');
        $('#facility_weightage').val('');
        $('#sub_facility_weightage').val('');

        if (!facilityId) {
            $subFacility.prop('disabled', true);
            return;
        }

        const selectedFacility = $(this).find('option:selected');
        $('#facility_weightage').val(selectedFacility.data('weightage') ?? '');

        subFacilityOptions.each(function () {
            const option = $(this);

            if (
                option.val() &&
                String(option.data('facility')) === facilityId &&
                String(option.data('scheme')) === schemeId
            ) {
                $subFacility.append(option.clone());
            }
        });

        $subFacility.prop('disabled', $subFacility.find('option').length <= 1);
    });

    $subFacility.on('change', function () {
        const weightage = $(this).find('option:selected').data('weightage');
        $('#sub_facility_weightage').val(weightage ?? '');
    });

});

$('#habitationForm').on('submit', function (e) {
    const form = this;
    const schemeId = $('#scheme_id').val();

    // 1. Check scheme
    if (!schemeId) {
        e.preventDefault();

        Swal.fire({
            icon: 'warning',
            title: 'Scheme Required',
            text: 'Please select a scheme name before saving.'
        });

        $('#scheme_id').focus();
        return;
    }

    // 2. Check mandatory habitation fields
    const requiredFields = [
        { selector: '.district-select', name: 'District' },
        { selector: '.block-select', name: 'Block' },
        { selector: '.village-select', name: 'Village' },
        { selector: '.mla-select', name: 'MLA Constituency' },
        { selector: '.mp-select', name: 'MP Constituency' },
        { selector: '.total_population', name: 'Population' }
    ];

    for (const field of requiredFields) {
        const input = $(form).find(field.selector).first();

        if (input.length && !input.val()) {
            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Habitation Details Required',
                text: 'Please enter ' + field.name + '.'
            }).then(() => input.focus());

            return;
        }
    }

    // 3. Warn if no facility details exist
    const facilityCount = $(form).find('#facilityTable tbody tr').length;

    if (facilityCount === 0) {
        e.preventDefault();

        Swal.fire({
            icon: 'warning',
            title: 'No Facility Details',
            text: 'No facility details have been added. Do you want to proceed without them?',
            showCancelButton: true,
            confirmButtonText: 'Yes, Save',
            cancelButtonText: 'Go Back'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
});

