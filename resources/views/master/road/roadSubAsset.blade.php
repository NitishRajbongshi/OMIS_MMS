@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Roads</li>
                        <li class="breadcrumb-item active">Road Sub-Assets</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Road Sub-Asset</h5>
            <form id="add_sub_asset" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Sub-Asset Code <span class="text-danger">*</span></label>
                        <input type="text" name="sub_asset_cd" class="form-control form-control-sm" maxlength="5" placeholder="SIGNB" required>
                    </div>
                    <div class="col-md-5 mb-2">
                        <label class="text-xs">Description <span class="text-danger">*</span></label>
                        <input type="text" name="sub_assets_descr" class="form-control form-control-sm" placeholder="e.g., Road Signboards" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Maker-Checker <span class="text-danger">*</span></label>
                        <select name="maker_checker_enabled" class="form-control form-control-sm" required>
                            <option value="Y">Enabled (Y)</option>
                            <option value="N">Disabled (N)</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus mr-1"></i> ADD SUB-ASSET
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Sub-Asset Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="subAssetTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 15%;">Code</th>
                                <th>Description</th>
                                <th style="width: 15%;">Maker-Checker</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subAssets as $asset)
                                <tr class="text-xs">
                                    <td class="font-weight-bold">{{ $asset->sub_asset_cd }}</td>
                                    <td>{{ $asset->sub_assets_descr }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $asset->maker_checker_enabled == 'Y' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $asset->maker_checker_enabled == 'Y' ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editSubAssetModal{{ $asset->sub_asset_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editSubAssetModal{{ $asset->sub_asset_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-sub-asset-form" method="PUT" action="{{ route('roadSubAsset.update', $asset->sub_asset_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_sub_asset_cd" value="{{ $asset->sub_asset_cd }}">
                                                <div class="modal-header bg-light">
                                                    <h6 class="modal-title font-weight-bold">Update Sub-Asset</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <div class="form-group">
                                                        <label class="text-xs">Sub-Asset Code</label>
                                                        <input type="text" name="sub_asset_cd" class="form-control form-control-sm" maxlength="5" value="{{ $asset->sub_asset_cd }}" required>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label class="text-xs">Description</label>
                                                        <input type="text" name="sub_assets_descr" class="form-control form-control-sm" value="{{ $asset->sub_assets_descr }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Maker-Checker Status</label>
                                                        <select name="maker_checker_enabled" class="form-control form-control-sm" required>
                                                            <option value="Y" {{ $asset->maker_checker_enabled == 'Y' ? 'selected' : '' }}>Enabled (Y)</option>
                                                            <option value="N" {{ $asset->maker_checker_enabled == 'N' ? 'selected' : '' }}>Disabled (N)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success btn-sm">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#subAssetTable').DataTable({ "responsive": true });

        $('#add_sub_asset').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('roadSubAsset.store') }}",$(this).attr('method'));
        });

        $('.update-sub-asset-form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), $(this).attr('action'),$(this).attr('method'));
        });

        function handleAjax(form, url, method) {
            $.ajax({
                type: method,
                url: url,
                data: form.serialize(),
                success: function(res) {
                    Swal.fire({ 
                        icon: res.status, 
                        title: res.status ? res.status.toUpperCase() : 'SUCCESS', 
                        text: res.message, 
                        timer: 2000 
                    }).then(() => { location.reload(); });
                }
            });
        }
    });
</script>
@endpush