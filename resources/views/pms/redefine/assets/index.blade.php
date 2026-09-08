@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left text-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">PMS</li>
                        <li class="breadcrumb-item active">Redefine Asset Project Upgradation</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mainBody">
        <h6 class="p-2 mt-3 border border-primary text-light bg-primary">
            <span class="text-sm text-uppercase">
                Redefine Road Asset Details
            </span>
        </h6>

        <div class="container-fluid border py-3">
            @if(session('success'))
                <div class="alert alert-success text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div id="roadDraftResponse" class="alert text-sm d-none"></div>

            @if(!empty($copyMessage))
                <div class="alert {{ !empty($copyStatus) ? 'alert-success' : 'alert-warning' }} text-sm">
                    {{ $copyMessage }}
                </div>
            @endif

            @if(!empty($roadDraftDetails))
                <form id="roadDraftEditForm" method="POST" action="{{ route('update.road') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" id="edit_road_id" value="{{ $roadDraftDetails->rd_system_id }}">
                    <input type="text" name="hdn_project_cd" id="project_cd" value="{{ $projectCd }}">
                    <input type="text" name="hdn_asset_plan_id" id="asset_plan_id" value="{{ $assetPlanId }}">

                    <div class="row text-xs">
                        <div class="form-group col-md-3">
                            <label>Road System ID</label>
                            <input type="text" class="form-control form-control-sm"
                                value="{{ $roadDraftDetails->rd_system_id }}" readonly>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Road Number</label>
                            <input type="text" class="form-control form-control-sm" value="{{ $roadDraftDetails->rd_number }}"
                                readonly>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <label for="edit_road_name">Road Name<span class="star">*</span></label>
                            <input type="text" class="form-control" id="edit_road_name" name="road_name"
                                value="{{ $roadDraftDetails->rd_name }}">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="edit_road_category">Road Category <span class="star">*</span></label>
                            <select class="form-control form-select" id="edit_road_category" name="road_category">
                                @foreach ($roadCategories as $roadCategory)
                                    <option value="{{ $roadCategory->rd_catg_cd }}">
                                        {{ $roadCategory->rd_catg_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="edit_road_type">Road Type <span class="star">*</span></label>
                            <select class="form-control" id="edit_road_type" name="road_type">
                                @foreach ($roadTypes as $roadType)
                                    <option value="{{ $roadType->rd_type_cd }}">
                                        {{ $roadType->rd_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="edit_road_length">Road Length (Km) <span class="star">*</span></label>
                            <input type="number" step="0.001" id="edit_road_length" class="form-control" name="road_length"
                                placeholder="0.000" value="{{ $roadDraftDetails->road_length }}">
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label for="edit_road_owner">Road Owner <span class="star">*</span></label>
                            <select class="form-control" id="edit_road_owner" name="road_owner">
                                @foreach ($roadOwners as $roadOwner)
                                    <option value="{{ $roadOwner->owner_cd }}">
                                        {{ $roadOwner->owner_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-4">
                            <label for="edit_road_kml_file">
                                Upload KML File for this Road:
                            </label>
                            <input type="file" class="text-xs text-success" id="edit_road_kml_file" name="edit_road_kml_file">
                        </div>
                    </div>

                    <div class="text-right mt-2">
                        <button type="submit" class="btn btn-sm btn-primary">
                            Update Road Draft
                        </button>
                    </div>
                </form>
            @else
                <div class="alert alert-warning text-sm mb-0">
                    No road draft details found for this asset.
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#roadDraftEditForm').on('submit', function (e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const responseBox = $('#roadDraftResponse');

                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        responseBox
                            .removeClass('d-none alert-success alert-danger alert-warning')
                            .addClass('alert-warning')
                            .text('Updating draft data...');
                    },
                    success: function (response) {
                        const isSuccess = response.status === 'success';

                        responseBox
                            .removeClass('alert-warning alert-success alert-danger')
                            .addClass(isSuccess ? 'alert-success' : 'alert-danger')
                            .text(response.message || (isSuccess ? 'Draft data updated successfully!' : 'Failed to update draft data.'));
                    },
                    error: function (xhr) {
                        let errorMessage = 'Failed to update draft data.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        responseBox
                            .removeClass('d-none alert-warning alert-success')
                            .addClass('alert-danger')
                            .text(errorMessage);
                    }
                });
            });
        });
    </script>
@endpush