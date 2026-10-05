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

            $('#asset_type').on('change', function() {

                const assetType = $(this).val();

                const $asset = $('#asset_id');
                const $criticality = $('#criticality_index');
                const $submit = $('#submitBtn');

                // Reset
                $asset
                    .prop('disabled', true)
                    .empty()
                    .append('<option value="">-- Loading Assets --</option>');

                $criticality
                    .val('')
                    .prop('disabled', true);

                $submit.prop('disabled', true);

                if (!assetType) {
                    $asset
                        .empty()
                        .append('<option value="">-- Select Asset Type First --</option>');

                    return;
                }

                $.ajax({
                    url: "{{ route('criticality.assets', ':assetType') }}"
                        .replace(':assetType', assetType),

                    type: 'GET',

                    success: function(response) {

                        $asset.empty();

                        if (!response.success || response.data.length === 0) {

                            $asset
                                .append(
                                    '<option value="">-- No Assets Available --</option>'
                                );

                            return;
                        }

                        $asset.append(
                            '<option value="">-- Select Asset --</option>'
                        );

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

                        console.error(xhr.responseJSON);
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

                const $form = $(this);
                const $submit = $('#submitBtn');

                const assetType = $('#asset_type').val();
                const assetId = $('#asset_id').val();
                const criticalityIndex = $('#criticality_index').val();

                if (!assetType || !assetId || !criticalityIndex) {
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

                        if (response.success) {

                            alert(response.message);

                            /*
                             * Reset form
                             */

                            $form[0].reset();

                            $('#asset_id')
                                .empty()
                                .append(
                                    '<option value="">-- Select Asset Type First --</option>'
                                )
                                .prop('disabled', true);

                            $('#criticality_index')
                                .val('')
                                .prop('disabled', true);

                            $('#criticality_range')
                                .hide();

                            $('#criticality_error')
                                .text('')
                                .hide();

                            $submit
                                .prop('disabled', true)
                                .text('Submit');
                        }
                    },

                    error: function(xhr) {

                        $submit
                            .prop('disabled', false)
                            .text('Submit');

                        console.error(xhr.responseJSON);

                        /*
                         * Laravel validation errors
                         */

                        if (xhr.status === 422 && xhr.responseJSON?.errors) {

                            const errors = xhr.responseJSON.errors;

                            if (errors.criticality_index) {

                                $('#criticality_error')
                                    .text(errors.criticality_index[0])
                                    .show();
                            }

                            if (errors.asset_id) {

                                alert(errors.asset_id[0]);
                            }

                            return;
                        }


                        /*
                         * General error
                         */

                        alert(
                            xhr.responseJSON?.message ||
                            'Unable to save Criticality Index.'
                        );
                    }

                });

            });
        });
    </script>
@endpush
