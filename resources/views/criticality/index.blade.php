@extends('layouts.app')

@section('title', 'Criticality Index')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="mb-0">Criticality Index</h4>
                    </div>

                    <div class="card-body">
                        <div id="criticalityAlert" class="alert" style="display: none;" role="alert"></div>
                        <form id="criticalityIndexForm" method="POST" action="{{ route('criticality.store') }}">
                            @csrf

                            {{-- Asset Type --}}
                            <div class="mb-3">
                                <label for="asset_type" class="form-label">
                                    Asset Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="asset_type" id="asset_type" class="form-select">
                                    <option value="">
                                        -- Select Asset Type --
                                    </option>

                                    @foreach ($assetTypes as $assetType)
                                        <option value="{{ $assetType['code'] }}">
                                            {{ $assetType['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            {{-- Asset --}}
                            <div class="mb-3">
                                <label for="asset_id" class="form-label">
                                    Asset
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="asset_id" id="asset_id" class="form-select" disabled>
                                    <option value="">
                                        -- Select Asset Type First --
                                    </option>
                                </select>
                            </div>


                            {{-- Criticality Index --}}
                            <div class="mb-3">
                                <label for="criticality_index" class="form-label">
                                    Criticality Index
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number" name="criticality_index" id="criticality_index" class="form-control"
                                    step="0.01" disabled>

                                <div id="criticality_range" class="form-text" style="display: none;">
                                </div>

                                <div id="criticality_error" class="text-danger mt-1" style="display: none;">
                                </div>
                            </div>


                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    Submit
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="mb-0">
                            Existing Criticality Indexes
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="criticalityListAlert" class="alert" style="display: none;"></div>
                        <div class="table-responsive">
                            <div class="row mb-3">

                                {{-- Asset Type Filter --}}
                                <div class="col-md-4">

                                    <label for="filter_asset_type" class="form-label">
                                        Asset Type
                                    </label>

                                    <select id="filter_asset_type" class="form-select">

                                        <option value="">
                                            All Asset Types
                                        </option>

                                        @foreach ($assetTypes as $assetType)
                                            <option value="{{ $assetType['code'] }}">
                                                {{ $assetType['name'] }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>


                                {{-- Search --}}
                                <div class="col-md-5">

                                    <label for="criticality_search" class="form-label">
                                        Search Asset
                                    </label>

                                    <input type="text" id="criticality_search" class="form-control"
                                        placeholder="Search Asset ID or Name...">

                                </div>


                                {{-- Search Button --}}
                                <div class="col-md-3 d-flex align-items-end">

                                    <button type="button" id="searchCriticality" class="btn btn-primary me-2">
                                        Search
                                    </button>

                                    <button type="button" id="resetCriticality" class="btn btn-secondary">
                                        Reset
                                    </button>

                                </div>

                            </div>
                            <table class="table table-bordered table-striped" id="criticalityTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Asset Type</th>
                                        <th>Asset</th>
                                        <th>Criticality Index</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="criticalityTableBody">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Loading...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between align-items-center mt-3">

                                <div id="criticalityPaginationInfo" class="text-muted"></div>

                                <div id="criticalityPagination"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editCriticalityModal" tabindex="-1" aria-labelledby="editCriticalityModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="editCriticalityModalLabel">
                                Edit Criticality Index
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">

                            <div id="editCriticalityAlert" class="alert d-none"></div>

                            <input type="hidden" id="edit_criticality_id">

                            <div class="mb-3">
                                <label class="form-label">
                                    Asset Type
                                </label>

                                <input type="text" id="edit_asset_type" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Asset
                                </label>

                                <input type="text" id="edit_asset" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="edit_criticality_index" class="form-label">
                                    Criticality Index
                                </label>

                                <input type="number" id="edit_criticality_index" class="form-control" step="0.01">

                                <small id="edit_criticality_range" class="text-muted"></small>

                                <div id="edit_criticality_error" class="text-danger mt-1"></div>
                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="button" id="updateCriticality" class="btn btn-primary">
                                Update
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            let criticalityRange = {
                lower: null,
                upper: null
            };
            loadCriticalityList();

            function showAlert(type, message) {
                const $alert = $('#criticalityAlert');
                $alert
                    .removeClass('alert-success alert-danger alert-warning alert-info')
                    .addClass('alert-' + type)
                    .html(message)
                    .stop(true, true)
                    .fadeIn();
            }

            function hideAlert() {
                $('#criticalityAlert')
                    .stop(true, true)
                    .fadeOut();
            }

            function loadAssets(assetType) {

                const $asset = $('#asset_id');
                const $criticality = $('#criticality_index');
                const $submit = $('#submitBtn');


                /*
                 * Reset asset dropdown
                 */
                $asset
                    .prop('disabled', true)
                    .empty()
                    .append(
                        '<option value="">-- Loading Assets --</option>'
                    );


                /*
                 * Reset criticality
                 */
                $criticality
                    .val('')
                    .prop('disabled', true);


                /*
                 * Disable submit
                 */
                $submit
                    .prop('disabled', true);


                if (!assetType) {

                    $asset
                        .empty()
                        .append(
                            '<option value="">-- Select Asset Type First --</option>'
                        );

                    return;
                }


                $.ajax({

                    url: "{{ route('criticality.assets', ':assetType') }}"
                        .replace(':assetType', assetType),

                    type: 'GET',

                    success: function(response) {

                        $asset.empty();


                        if (
                            !response.success ||
                            !response.data ||
                            response.data.length === 0
                        ) {

                            $asset
                                .append(
                                    '<option value="">-- No Assets Available --</option>'
                                );

                            return;
                        }


                        /*
                         * Default option
                         */
                        $asset.append(
                            '<option value="">-- Select Asset --</option>'
                        );


                        /*
                         * Add assets
                         */
                        $.each(response.data, function(index, asset) {

                            $asset.append(
                                $('<option>', {
                                    value: asset.asset_id,
                                    text: asset.asset_label
                                })
                            );

                        });


                        $asset.prop('disabled', false);

                    },

                    error: function(xhr) {

                        $asset
                            .empty()
                            .append(
                                '<option value="">-- Failed to Load Assets --</option>'
                            );

                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ||
                            'Unable to load assets.'
                        );

                        console.error(xhr.responseJSON);
                    }

                });
            }

            function loadCriticalityList(page = 1) {

                const assetType =
                    $('#filter_asset_type').val();

                const search =
                    $('#criticality_search').val().trim();

                const $tbody =
                    $('#criticalityTableBody');


                $tbody.html(`
                    <tr>
                        <td colspan="5" class="text-center">
                            Loading...
                        </td>
                    </tr>
                `);

                $.ajax({
                    url: "{{ route('criticality.list') }}",
                    type: 'GET',
                    data: {
                        asset_type: assetType,
                        search: search,
                        page: page,
                        per_page: 10
                    },

                    success: function(response) {
                        $tbody.empty();
                        if (
                            !response.success ||
                            !response.data ||
                            response.data.length === 0
                        ) {
                            $tbody.html(`
                                <tr>
                                    <td
                                        colspan="5"
                                        class="text-center text-muted"
                                    >
                                        No Criticality Index records found.
                                    </td>
                                </tr>
                            `);

                            $('#criticalityPaginationInfo')
                                .text('');

                            $('#criticalityPagination')
                                .empty();

                            return;
                        }

                        $.each(
                            response.data,
                            function(index, record) {

                                const createdAt =
                                    record.created_at ?
                                    new Date(
                                        record.created_at
                                    ).toLocaleString() :
                                    '-';
                                $tbody.append(`
                                <tr>
                                    <td>
                                        ${index + 1}
                                    </td>

                                    <td>
                                        ${record.asset_type_name}
                                    </td>

                                    <td>
                                        ${record.asset_label}
                                    </td>

                                    <td>
                                        ${record.criticality_index}
                                    </td>

                                    <td>
                                        ${createdAt}
                                    </td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-primary edit-criticality"
                                            data-id="${record.id}"
                                            data-asset-type="${record.asset_type}"
                                            data-asset-type-name="${record.asset_type_name}"
                                            data-asset-label="${record.asset_label}"
                                            data-criticality-index="${record.criticality_index}"
                                        >
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            `);

                            }
                        );


                        /*
                         * Pagination information
                         */

                        const pagination =
                            response.pagination;


                        const from =
                            ((pagination.current_page - 1) *
                                pagination.per_page) + 1;


                        const to =
                            Math.min(
                                from + response.data.length - 1,
                                pagination.total
                            );


                        $('#criticalityPaginationInfo')
                            .text(
                                `Showing ${from} to ${to} ` +
                                `of ${pagination.total} records`
                            );


                        /*
                         * Pagination buttons
                         */

                        renderCriticalityPagination(
                            pagination
                        );

                    },


                    error: function(xhr) {

                        $tbody.html(`
                <tr>
                    <td
                        colspan="5"
                        class="text-center text-danger"
                    >
                        Unable to load records.
                    </td>
                </tr>
            `);

                        console.error(xhr.responseJSON);

                    }

                });
            }

            function renderCriticalityPagination(pagination) {

                const $pagination =
                    $('#criticalityPagination');

                $pagination.empty();


                if (pagination.last_page <= 1) {
                    return;
                }


                const $nav = $('<ul>', {
                    class: 'pagination mb-0'
                });


                /*
                 * Previous
                 */

                const previousDisabled =
                    pagination.current_page === 1 ?
                    'disabled' :
                    '';


                $nav.append(`
        <li class="page-item ${previousDisabled}">
            <a
                href="#"
                class="page-link criticality-page"
                data-page="${pagination.current_page - 1}"
            >
                Previous
            </a>
        </li>
    `);


                /*
                 * Page numbers
                 */

                for (
                    let page = 1; page <= pagination.last_page; page++
                ) {

                    const active =
                        page === pagination.current_page ?
                        'active' :
                        '';


                    $nav.append(`
            <li class="page-item ${active}">
                <a
                    href="#"
                    class="page-link criticality-page"
                    data-page="${page}"
                >
                    ${page}
                </a>
            </li>
        `);

                }


                /*
                 * Next
                 */

                const nextDisabled =
                    pagination.current_page === pagination.last_page ?
                    'disabled' :
                    '';


                $nav.append(`
        <li class="page-item ${nextDisabled}">
            <a
                href="#"
                class="page-link criticality-page"
                data-page="${pagination.current_page + 1}"
            >
                Next
            </a>
        </li>
    `);


                $pagination.append($nav);
            }

            $(document).on(
                'click',
                '.criticality-page',
                function(e) {

                    e.preventDefault();

                    const page =
                        parseInt($(this).data('page'));

                    if (page < 1) {
                        return;
                    }

                    loadCriticalityList(page);
                }
            );

            $('#searchCriticality').on('click', function() {

                loadCriticalityList(1);

            });

            $('#filter_asset_type').on('change', function() {

                loadCriticalityList(1);

            });

            $('#resetCriticality').on('click', function() {

                $('#filter_asset_type').val('');

                $('#criticality_search').val('');

                loadCriticalityList(1);

            });

            $.ajax({
                url: "{{ route('criticality.parameter') }}",
                type: 'GET',

                success: function(response) {

                    if (!response.success) {
                        console.error(response.message);
                        return;
                    }

                    criticalityRange.lower = parseFloat(
                        response.data.lower_range
                    );

                    criticalityRange.upper = parseFloat(
                        response.data.upper_range
                    );

                    $('#criticality_index')
                        .attr('min', criticalityRange.lower)
                        .attr('max', criticalityRange.upper);

                    $('#criticality_range').text(
                        'Allowed range: ' +
                        criticalityRange.lower.toFixed(2) +
                        ' - ' +
                        criticalityRange.upper.toFixed(2)
                    );
                },

                error: function(xhr) {
                    console.error(
                        'Unable to load Criticality Index range.',
                        xhr.responseJSON
                    );
                }
            });

            $(document).on('click', '.edit-criticality', function() {

                const id = $(this).data('id');
                const assetTypeName = $(this).data('asset-type-name');
                const assetLabel = $(this).data('asset-label');
                const criticalityIndex = $(this).data('criticality-index');

                $('#edit_criticality_id').val(id);
                $('#edit_asset_type').val(assetTypeName);
                $('#edit_asset').val(assetLabel);
                $('#edit_criticality_index').val(criticalityIndex);

                $('#edit_criticality_error').text('');

                $('#editCriticalityAlert')
                    .removeClass('alert-success alert-danger')
                    .addClass('d-none')
                    .text('');

                if (
                    criticalityRange.lower !== null &&
                    criticalityRange.upper !== null
                ) {
                    $('#edit_criticality_range').text(
                        `Allowed range: ${criticalityRange.lower} - ${criticalityRange.upper}`
                    );

                    $('#edit_criticality_index')
                        .attr('min', criticalityRange.lower)
                        .attr('max', criticalityRange.upper);
                }

                $('#editCriticalityModal').modal('show');
            });

            $('#asset_type').on('change', function() {
                hideAlert();
                const assetType = $(this).val();
                loadAssets(assetType);
            });

            $('#updateCriticality').on('click', function() {

                const id = $('#edit_criticality_id').val();

                const criticalityIndex =
                    $('#edit_criticality_index').val();

                $('#edit_criticality_error').text('');

                if (!criticalityIndex) {

                    $('#edit_criticality_error').text(
                        'Please enter the criticality index.'
                    );

                    return;
                }

                const value = parseFloat(criticalityIndex);

                if (isNaN(value)) {

                    $('#edit_criticality_error').text(
                        'Criticality index must be a valid number.'
                    );

                    return;
                }

                if (
                    value < criticalityRange.lower ||
                    value > criticalityRange.upper
                ) {

                    $('#edit_criticality_error').text(
                        `Criticality Index must be between ` +
                        `${criticalityRange.lower} and ` +
                        `${criticalityRange.upper}.`
                    );

                    return;
                }

                const button = $(this);

                button
                    .prop('disabled', true)
                    .text('Updating...');

                $.ajax({
                    url: `/criticality-index/${id}`,
                    type: 'PUT',

                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        criticality_index: value
                    },

                    success: function(response) {

                        $('#editCriticalityAlert')
                            .removeClass('d-none alert-danger')
                            .addClass('alert-success')
                            .text(response.message);

                        setTimeout(function() {

                            $('#editCriticalityModal').modal('hide');

                            loadCriticalityList();

                        }, 800);
                    },

                    error: function(xhr) {

                        if (
                            xhr.status === 422 &&
                            xhr.responseJSON &&
                            xhr.responseJSON.errors
                        ) {

                            const errors =
                                xhr.responseJSON.errors;

                            if (errors.criticality_index) {

                                $('#edit_criticality_error').text(
                                    errors.criticality_index[0]
                                );
                            }

                        } else {

                            $('#editCriticalityAlert')
                                .removeClass('d-none alert-success')
                                .addClass('alert-danger')
                                .text(
                                    xhr.responseJSON?.message ||
                                    'Failed to update Criticality Index.'
                                );
                        }
                    },

                    complete: function() {

                        button
                            .prop('disabled', false)
                            .text('Update');
                    }
                });
            });

            $('#asset_id').on('change', function() {

                const assetId = $(this).val();

                if (assetId) {

                    $('#criticality_index')
                        .prop('disabled', false);

                    $('#criticality_range').show();

                } else {

                    $('#criticality_index')
                        .val('')
                        .prop('disabled', true);

                    $('#criticality_range').hide();

                    $('#criticality_error')
                        .text('')
                        .hide();

                    $('#submitBtn').prop('disabled', true);
                }
            });

            $('#criticality_index').on('input', function() {
                const value = parseFloat($(this).val());
                const $error = $('#criticality_error');
                const $submit = $('#submitBtn');

                $error
                    .text('')
                    .hide();

                $submit.prop('disabled', true);

                if ($(this).val() === '') {
                    return;
                }

                if (isNaN(value)) {
                    $error
                        .text('Please enter a valid number.')
                        .show();
                    return;
                }

                if (
                    value < criticalityRange.lower ||
                    value > criticalityRange.upper
                ) {
                    $error
                        .text(
                            'Criticality Index must be between ' +
                            criticalityRange.lower.toFixed(2) +
                            ' and ' +
                            criticalityRange.upper.toFixed(2) +
                            '.'
                        )
                        .show();
                    return;
                }
                $submit.prop('disabled', false);
            });

            $('#criticalityIndexForm').on('submit', function(e) {
                e.preventDefault();
                hideAlert();

                const $form = $(this);
                const $submit = $('#submitBtn');
                const assetType = $('#asset_type').val();
                const assetId = $('#asset_id').val();
                const criticalityIndex = $('#criticality_index').val();

                if (!assetType || !assetId || !criticalityIndex) {
                    showAlert(
                        'warning',
                        'Please complete all required fields.'
                    );

                    return;
                }

                $submit
                    .prop('disabled', true)
                    .text('Saving...');


                $.ajax({

                    url: $form.attr('action'),

                    type: 'POST',

                    data: {
                        _token: $form.find('input[name="_token"]').val(),
                        asset_type: assetType,
                        asset_id: assetId,
                        criticality_index: criticalityIndex
                    },

                    success: function(response) {
                        if (!response.success) {
                            showAlert(
                                'danger',
                                response.message ||
                                'Unable to save Criticality Index.'
                            );

                            $submit
                                .prop('disabled', false)
                                .text('Submit');

                            return;
                        }

                        showAlert(
                            'success',
                            response.message
                        );

                        const selectedAssetType = $('#asset_type').val();

                        $('#criticality_index')
                            .val('')
                            .prop('disabled', true);


                        $('#criticality_range')
                            .hide();


                        $('#criticality_error')
                            .text('')
                            .hide();
                        loadAssets(selectedAssetType);
                        loadCriticalityList();
                        $submit
                            .prop('disabled', true)
                            .text('Submit');
                    },

                    error: function(xhr) {
                        $submit
                            .prop('disabled', false)
                            .text('Submit');
                        if (
                            xhr.status === 422 &&
                            xhr.responseJSON &&
                            xhr.responseJSON.errors
                        ) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.criticality_index) {
                                $('#criticality_error')
                                    .text(errors.criticality_index[0])
                                    .show();
                            }
                            if (errors.asset_id) {
                                showAlert(
                                    'danger',
                                    errors.asset_id[0]
                                );
                                return;
                            }

                            if (errors.asset_type) {
                                showAlert(
                                    'danger',
                                    errors.asset_type[0]
                                );
                                return;
                            }
                            return;
                        }
                        showAlert(
                            'danger',
                            xhr.responseJSON?.message ||
                            'Unable to save Criticality Index.'
                        );
                    }
                });
            });
        });
    </script>
@endpush
