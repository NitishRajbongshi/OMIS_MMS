@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-10 text-sm">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Control Maker Checker Workflow</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title fw-bold text-center text-md">Maker Checker Workflow Configuration</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-light text-sm">
                            <th style="width: 70%">Asset and Sub-Asset</th>
                            <th style="width: 30%">Maker Checker Enabled</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($subAssets as $asset)
                            <tr>
                                <td>{{ $asset->sub_assets_descr }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-switch" type="checkbox"
                                            data-id="{{ $asset->sub_asset_cd }}" id="toggle_{{ $asset->sub_asset_cd }}"
                                            {{ $asset->maker_checker_enabled == 'Y' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="toggle_{{ $asset->sub_asset_cd }}">
                                            {{ $asset->maker_checker_enabled == 'Y' ? 'Enabled' : 'Disabled' }}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
@push('styles')
    <style>
        .card {
            border: 1px solid #ddd;
            box-shadow: none;
            border-radius: 0;
        }

        .card-header {
            background-color: #f1f1f1;
            border-bottom: 1px solid #ddd;
            padding: 10px 15px;
        }

        .table td,
        .table th {
            padding: 12px 15px;
            vertical-align: middle;
            border: 1px solid #ddd;
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.2em;
            margin-left: 0;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .form-switch .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .form-check-label {
            margin-left: 60px;
            font-weight: normal;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.toggle-switch').change(function() {
                const toggleSwitch = $(this);
                const subAssetCd = toggleSwitch.data('id');
                const isEnabled = toggleSwitch.is(':checked');
                const statusValue = isEnabled ? 'Y' : 'N';
                const label = $(`label[for="toggle_${subAssetCd}"]`);

                $.ajax({
                    url: '{{ route('updateMakerChecker') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        sub_asset_cd: subAssetCd,
                        maker_checker_enabled: statusValue
                    },
                    success: function(response) {
                        if (response.status !== 'success') {
                            toggleSwitch.prop('checked', !isEnabled);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                showConfirmButton: true,
                                width: '380px'
                            });
                            return;
                        } else {
                            label.text(isEnabled ? 'Enabled' : 'Disabled');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500,
                                width: '380px'
                            });
                        }
                    },
                    error: function(xhr) {
                        toggleSwitch.prop('checked', !isEnabled);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while updating the status.',
                            showConfirmButton: true,
                            width: '380px'
                        });
                    }
                });
            });
        });
    </script>
@endpush
