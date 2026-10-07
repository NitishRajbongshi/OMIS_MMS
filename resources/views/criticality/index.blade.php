@extends('layouts.app')

@section('title', 'Criticality Index')

@section('content')
    <main class="command-center">
        <div class="content-header">
            <div class="container-fluid" style="position: relative;">
                <div class="row text-sm">
                    <div class="col-sm-12 col-md-10">
                        <div class="command-breadcrumb">
                            <i class="fas fa-house"></i>
                            <span><a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">Home</a></span>
                            <span>/</span>
                            <strong>Criticality Index</strong>
                        </div>
                    </div>
                </div>
                <x-common.alert-module />
            </div>
        </div>

        <!-- Main content -->
        <section class="content px-3">

            {{-- Page Header --}}
            <div class="command-heading mb-4">
                <div>
                    <h1>
                        <i class="fa fa-sliders-h text-primary me-2"></i>
                        Criticality Index
                    </h1>
                    <p>
                        Configure and manage asset criticality indexes.
                    </p>
                </div>
            </div>

            {{-- Form Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>New Entry</span>
                        <h2>Set Criticality Index</h2>
                    </div>
                </header>
                <div class="p-3">
                    <div id="criticalityAlert" class="alert" style="display: none;" role="alert"></div>

                    <form id="criticalityIndexForm" method="POST" action="{{ route('criticality.store') }}">
                        @csrf
                        <div class="row g-3">
                            {{-- Asset Type --}}
                            <div class="col-sm-6 col-md-4">
                                <label for="asset_type" class="form-label">
                                    Asset Type <span class="text-danger">*</span>
                                </label>
                                <select name="asset_type" id="asset_type" class="form-select form-select-sm">
                                    <option value="">-- Select Asset Type --</option>
                                    @foreach ($assetTypes as $assetType)
                                        <option value="{{ $assetType['code'] }}">
                                            {{ $assetType['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Asset --}}
                            <div class="col-sm-6 col-md-4">
                                <label for="asset_id" class="form-label">
                                    Asset <span class="text-danger">*</span>
                                </label>
                                <select name="asset_id" id="asset_id" class="form-select form-select-sm" disabled>
                                    <option value="">-- Select Asset Type First --</option>
                                </select>
                            </div>

                            {{-- Criticality Index --}}
                            <div class="col-sm-6 col-md-4">
                                <label for="criticality_index" class="form-label">
                                    Criticality Index <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="criticality_index" id="criticality_index"
                                    class="form-control form-control-sm" step="0.01"
                                    placeholder="Enter criticality index..." disabled>
                                <small id="criticality_range" class="form-text text-muted" style="display: none;"></small>
                                <div id="criticality_error" class="text-danger small mt-1" style="display: none;"></div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary btn-sm px-3" id="submitBtn" disabled>
                                    <i class="fa fa-save me-1"></i> Submit
                                </button>
                                <button type="reset" class="btn btn-light btn-sm px-3 border" id="formResetBtn">
                                    <i class="fa fa-undo me-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </article>

            {{-- List Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Registry</span>
                        <h2>Existing Criticality Indexes</h2>
                    </div>
                </header>
                <div class="p-3">
                    <div id="criticalityListAlert" class="alert" style="display: none;"></div>

                    <div class="row g-3 mb-3 align-items-end">
                        {{-- Asset Type Filter --}}
                        <div class="col-sm-6 col-md-4">
                            <label for="filter_asset_type" class="form-label">Asset Type</label>
                            <select id="filter_asset_type" class="form-select form-select-sm">
                                <option value="">All Asset Types</option>
                                @foreach ($assetTypes as $assetType)
                                    <option value="{{ $assetType['code'] }}">
                                        {{ $assetType['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Search --}}
                        <div class="col-sm-6 col-md-5">
                            <label for="criticality_search" class="form-label">Search Asset</label>
                            <input type="text" id="criticality_search" class="form-control form-control-sm"
                                placeholder="Search Asset ID or Name...">
                        </div>

                        {{-- Search Buttons --}}
                        <div class="col-sm-12 col-md-3 d-flex gap-2">
                            <button type="button" id="searchCriticality" class="btn btn-primary btn-sm flex-fill">
                                <i class="fa fa-search me-1"></i> Search
                            </button>
                            <button type="button" id="resetCriticality" class="btn btn-light btn-sm border flex-fill">
                                <i class="fa fa-undo me-1"></i> Reset
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover w-100" id="criticalityTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Asset Type</th>
                                    <th style="width: 35%;">Asset</th>
                                    <th style="width: 15%;">Criticality Index</th>
                                    <th style="width: 15%;">Created At</th>
                                    <th style="width: 10%;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="criticalityTableBody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        Loading...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <div id="criticalityPaginationInfo" class="text-muted small"></div>
                        <div id="criticalityPagination"></div>
                    </div>
                </div>
            </article>

            {{-- Edit Modal --}}
            <div class="modal fade" id="editCriticalityModal" tabindex="-1" aria-labelledby="editCriticalityModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="editCriticalityModalLabel">
                                <i class="fa fa-edit text-warning me-2"></i>Edit Criticality Index
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div id="editCriticalityAlert" class="alert d-none"></div>

                            <input type="hidden" id="edit_criticality_id">

                            <div class="mb-3">
                                <label class="form-label" for="edit_asset_type">
                                    Asset Type
                                </label>
                                <input type="text" id="edit_asset_type" class="form-control form-control-sm bg-light"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="edit_asset">
                                    Asset
                                </label>
                                <input type="text" id="edit_asset" class="form-control form-control-sm bg-light"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label for="edit_criticality_index" class="form-label">
                                    Criticality Index <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="edit_criticality_index" class="form-control form-control-sm"
                                    step="0.01" placeholder="Enter criticality index...">
                                <small id="edit_criticality_range" class="form-text text-muted"></small>
                                <div id="edit_criticality_error" class="text-danger small mt-1"></div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i> Cancel
                            </button>
                            <button type="button" id="updateCriticality" class="btn btn-primary btn-sm">
                                <i class="fa fa-save me-1"></i> Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/selectOptionStyleSheet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
    <style>
        .command-center {
            color: var(--oamis-ink);
        }

        .table th,
        .table td {
            font-size: 14px !important;
        }

        .badge {
            font-size: 11px !important;
            padding: 5px 9px !important;
        }

        .gap-1 {
            gap: 0.25rem !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#asset_id").select2();
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

                $('#criticality_range').hide();
                $('#criticality_error').text('').hide();

                /*
                 * Disable submit
                 */
                $submit.prop('disabled', true);

                if (!assetType) {
                    $asset
                        .empty()
                        .append(
                            '<option value="">-- Select Asset Type First --</option>'
                        );
                    return;
                }

                $.ajax({
                    url: "{{ route('criticality.assets', ':assetType') }}".replace(':assetType',
                        assetType),
                    type: 'GET',
                    success: function(response) {
                        $asset.empty();

                        if (!response.success || !response.data || response.data.length === 0) {
                            $asset.append(
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
                            xhr.responseJSON?.message || 'Unable to load assets.'
                        );

                        console.error(xhr.responseJSON);
                    }
                });
            }

            function loadCriticalityList(page = 1) {
                const assetType = $('#filter_asset_type').val();
                const search = $('#criticality_search').val().trim();
                const $tbody = $('#criticalityTableBody');

                $tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            <i class="fa fa-spinner fa-spin me-1"></i> Loading...
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

                        if (!response.success || !response.data || response.data.length === 0) {
                            $tbody.html(`
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        No Criticality Index records found.
                                    </td>
                                </tr>
                            `);

                            $('#criticalityPaginationInfo').text('');
                            $('#criticalityPagination').empty();
                            return;
                        }

                        const pagination = response.pagination;
                        const pageOffset = (pagination.current_page - 1) * pagination.per_page;

                        $.each(response.data, function(index, record) {
                            const createdAt = record.created_at ?
                                new Date(record.created_at).toLocaleString() :
                                '-';

                            $tbody.append(`
                                <tr>
                                    <td>${pageOffset + index + 1}</td>
                                    <td><strong>${record.asset_type_name}</strong></td>
                                    <td>${record.asset_label}</td>
                                    <td>${record.criticality_index}</td>
                                    <td>${createdAt}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-xs text-dark edit-criticality"
                                                data-id="${record.id}"
                                                data-asset-type="${record.asset_type}"
                                                data-asset-type-name="${record.asset_type_name}"
                                                data-asset-label="${record.asset_label}"
                                                data-criticality-index="${record.criticality_index}"
                                                title="Edit"
                                            >
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `);
                        });

                        /*
                         * Pagination information
                         */
                        const from = ((pagination.current_page - 1) * pagination.per_page) + 1;
                        const to = Math.min(from + response.data.length - 1, pagination.total);

                        $('#criticalityPaginationInfo').text(
                            `Showing ${from} to ${to} of ${pagination.total} records`
                        );

                        /*
                         * Pagination buttons
                         */
                        renderCriticalityPagination(pagination);
                    },
                    error: function(xhr) {
                        $tbody.html(`
                            <tr>
                                <td colspan="6" class="text-center text-danger py-3">
                                    Unable to load records.
                                </td>
                            </tr>
                        `);
                        console.error(xhr.responseJSON);
                    }
                });
            }

            function renderCriticalityPagination(pagination) {
                const $pagination = $('#criticalityPagination');
                $pagination.empty();

                if (pagination.last_page <= 1) {
                    return;
                }

                const $nav = $('<ul>', {
                    class: 'pagination pagination-sm mb-0'
                });

                /*
                 * Previous
                 */
                const previousDisabled = pagination.current_page === 1 ? 'disabled' : '';
                $nav.append(`
                    <li class="page-item ${previousDisabled}">
                        <a href="#" class="page-link criticality-page" data-page="${pagination.current_page - 1}">
                            &laquo;
                        </a>
                    </li>
                `);

                /*
                 * Page numbers
                 */
                for (let page = 1; page <= pagination.last_page; page++) {
                    const active = page === pagination.current_page ? 'active' : '';
                    $nav.append(`
                        <li class="page-item ${active}">
                            <a href="#" class="page-link criticality-page" data-page="${page}">
                                ${page}
                            </a>
                        </li>
                    `);
                }

                /*
                 * Next
                 */
                const nextDisabled = pagination.current_page === pagination.last_page ? 'disabled' : '';
                $nav.append(`
                    <li class="page-item ${nextDisabled}">
                        <a href="#" class="page-link criticality-page" data-page="${pagination.current_page + 1}">
                            &raquo;
                        </a>
                    </li>
                `);

                $pagination.append($nav);
            }

            $(document).on('click', '.criticality-page', function(e) {
                e.preventDefault();
                const page = parseInt($(this).data('page'));
                if (page < 1) return;
                loadCriticalityList(page);
            });

            $('#searchCriticality').on('click', function() {
                loadCriticalityList(1);
            });

            $('#criticality_search').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    loadCriticalityList(1);
                }
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

                    criticalityRange.lower = parseFloat(response.data.lower_range);
                    criticalityRange.upper = parseFloat(response.data.upper_range);

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

                if (criticalityRange.lower !== null && criticalityRange.upper !== null) {
                    $('#edit_criticality_range').text(
                        `Allowed range: ${criticalityRange.lower.toFixed(2)} - ${criticalityRange.upper.toFixed(2)}`
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

            $('#asset_id').on('change', function() {
                const assetId = $(this).val();

                if (assetId) {
                    $('#criticality_index').prop('disabled', false);
                    $('#criticality_range').show();
                } else {
                    $('#criticality_index')
                        .val('')
                        .prop('disabled', true);

                    $('#criticality_range').hide();
                    $('#criticality_error').text('').hide();
                    $('#submitBtn').prop('disabled', true);
                }
            });

            $('#criticality_index').on('input', function() {
                const value = parseFloat($(this).val());
                const $error = $('#criticality_error');
                const $submit = $('#submitBtn');

                $error.text('').hide();
                $submit.prop('disabled', true);

                if ($(this).val() === '') {
                    return;
                }

                if (isNaN(value)) {
                    $error.text('Please enter a valid number.').show();
                    return;
                }

                if (value < criticalityRange.lower || value > criticalityRange.upper) {
                    $error.text(
                        'Criticality Index must be between ' +
                        criticalityRange.lower.toFixed(2) +
                        ' and ' +
                        criticalityRange.upper.toFixed(2) +
                        '.'
                    ).show();
                    return;
                }
                $submit.prop('disabled', false);
            });

            $('#criticalityIndexForm').on('reset', function() {
                setTimeout(function() {
                    $('#asset_id')
                        .empty()
                        .append('<option value="">-- Select Asset Type First --</option>')
                        .prop('disabled', true);
                    $('#criticality_index').val('').prop('disabled', true);
                    $('#criticality_range').hide();
                    $('#criticality_error').text('').hide();
                    $('#submitBtn').prop('disabled', true);
                    hideAlert();
                }, 10);
            });

            $('#updateCriticality').on('click', function() {
                const id = $('#edit_criticality_id').val();
                const criticalityIndex = $('#edit_criticality_index').val();

                $('#edit_criticality_error').text('');

                if (!criticalityIndex) {
                    $('#edit_criticality_error').text('Please enter the criticality index.');
                    return;
                }

                const value = parseFloat(criticalityIndex);

                if (isNaN(value)) {
                    $('#edit_criticality_error').text('Criticality index must be a valid number.');
                    return;
                }

                if (value < criticalityRange.lower || value > criticalityRange.upper) {
                    $('#edit_criticality_error').text(
                        `Criticality Index must be between ${criticalityRange.lower.toFixed(2)} and ${criticalityRange.upper.toFixed(2)}.`
                    );
                    return;
                }

                const button = $(this);
                button.prop('disabled', true).text('Updating...');

                $.ajax({
                    url: `/asset-management/criticality-index/${id}`,
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
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.criticality_index) {
                                $('#edit_criticality_error').text(errors.criticality_index[0]);
                            }
                        } else {
                            $('#editCriticalityAlert')
                                .removeClass('d-none alert-success')
                                .addClass('alert-danger')
                                .text(xhr.responseJSON?.message ||
                                    'Failed to update Criticality Index.');
                        }
                    },
                    complete: function() {
                        button.prop('disabled', false).text('Update');
                    }
                });
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
                    showAlert('warning', 'Please complete all required fields.');
                    return;
                }

                $submit.prop('disabled', true).text('Saving...');

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
                            showAlert('danger', response.message ||
                                'Unable to save Criticality Index.');
                            $submit.prop('disabled', false).text('Submit');
                            return;
                        }

                        showAlert('success', response.message);

                        const selectedAssetType = $('#asset_type').val();
                        $('#criticality_index').val('').prop('disabled', true);
                        $('#criticality_range').hide();
                        $('#criticality_error').text('').hide();

                        loadAssets(selectedAssetType);
                        loadCriticalityList();

                        $submit.prop('disabled', true).text('Submit');
                    },
                    error: function(xhr) {
                        $submit.prop('disabled', false).text('Submit');

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.criticality_index) {
                                $('#criticality_error').text(errors.criticality_index[0])
                                    .show();
                            }
                            if (errors.asset_id) {
                                showAlert('danger', errors.asset_id[0]);
                                return;
                            }
                            if (errors.asset_type) {
                                showAlert('danger', errors.asset_type[0]);
                                return;
                            }
                            return;
                        }

                        showAlert('danger', xhr.responseJSON?.message ||
                            'Unable to save Criticality Index.');
                    }
                });
            });
        });
    </script>
@endpush
