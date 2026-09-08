@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid text-sm">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('manageRoad') }}">Manage Roads</a>
                </li>
                <li class="breadcrumb-item">Add Pavement</li>
            </ol>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody py-3">
            @if (session('failed'))
                <div class="text-sm alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Failed!</strong> {{ session('failed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <x-road-info :roadChainage="$roadChainage" />
            <x-road-tab-navigation />

            <form action="{{ route('createPavement') }}" method="post" autocomplete="off" id="pavementForm"
                enctype="multipart/form-data">
                @csrf
                <!-- saiful # 21-04-2026 # Start -->
                <input type="hidden" id="hdn_asset_plan_id" name="hdn_asset_plan_id"
                    value="{{ session('asset_plan_id') }}" />
                <!-- saiful # 21-04-2026 # End -->
                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2" style="font-size:14px">Pavement Section</legend><br>

                    {{-- Hidden fields --}}
                    <input type="hidden" id="road_system_id" name="road_system_id" value="{{ session('system_id') }}">
                    <input type="hidden" id="road_length" name="road_length" value="{{ session('road_length') }}">

                    {{-- Basic Common Fields --}}
                    <div class="row form-1-box border mt-2 pb-2">
                        <div class="col-md-3">
                            <label for="pavement_type">Pavement Type<span class="star">*</span></label>
                            <select class="form-control form-control-sm @error('pavement_type') is-invalid @enderror"
                                id="pavement_type" name="pavement_type">
                                <option value="">Choose one</option>
                                @foreach ($pavementTypes as $pavementType)
                                    <option value="{{ $pavementType->pavement_type_cd }}">
                                        {{ $pavementType->pavement_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pavement_type')
                                <div class="invalid-feedback text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="formation_width">Formation Width (Mtrs):</label>
                            <input type="number" step="0.001" placeholder="0.00" id="formation_width"
                                class="form-control form-control-sm @error('formation_width') is-invalid @enderror"
                                name="formation_width" value="{{ old('formation_width') }}"
                                oninput="restrictDecimalPoints(event)">

                            @error('formation_width')
                                <div class="invalid-feedback text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="carriage_width">Carriage Width (Mtrs):</label>
                            <input type="number" step="0.01" placeholder="0.00" id="carriage_width"
                                class="form-control form-control-sm @error('carriage_width') is-invalid @enderror"
                                name="carriage_width" value="{{ old('carriage_width') }}"
                                oninput="restrictDecimalPoints(event)">

                            @error('carriage_width')
                                <div class="invalid-feedback text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Chainage Section --}}
                    <div id="chainage_section" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Chainage Section</span>
                            </div>
                            <div class="col-md-3">
                                <label for="start_chainage">Start Chainage:</label>
                                <input type="number" step="0.01" placeholder="0.00" id="start_chainage"
                                    class="form-control form-control-sm @error('start_chainage') is-invalid @enderror"
                                    name="start_chainage" value="{{ old('start_chainage') }}"
                                    oninput="restrictDecimalPointsUptoThree(event)">

                                @error('start_chainage')
                                    <div class="invalid-feedback text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="end_chainage">End Chainage:</label>
                                <input type="number" step="0.01" placeholder="0.00" id="end_chainage"
                                    class="form-control form-control-sm @error('end_chainage') is-invalid @enderror"
                                    name="end_chainage" value="{{ old('end_chainage') }}"
                                    oninput="restrictDecimalPointsUptoThree(event)">

                                @error('end_chainage')
                                    <div class="invalid-feedback text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <x-get-coordinate.component />
                    {{-- Pavement Layer Section --}}
                    <div id="pavement_layer_section" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Shoulder Section</span>
                            </div>
                            <div class="col-md-3">
                                <label for="subbase_layer_type">Sub-Base Layer Type: <span class="star"></span></label>
                                <select
                                    class="form-control form-control-sm @error('subbase_layer_type') is-invalid @enderror"
                                    id="subbase_layer_type" name="subbase_layer_type">
                                    <option value="">Choose one</option>
                                    @foreach ($subBaseLayers as $subBaseLayer)
                                        <option value="{{ $subBaseLayer->sub_base_layer_type_cd }}">
                                            {{ $subBaseLayer->sub_base_layer_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subbase_layer_type')
                                    <div class="invalid-feedback text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="subbase_layer_thickness">Subbase Layer Thickness (MM):</label>
                                <input type="number" step="0.01" placeholder="0.00" id="subbase_layer_thickness"
                                    class="form-control form-control-sm @error('subbase_layer_thickness') is-invalid @enderror"
                                    name="subbase_layer_thickness" value="{{ old('subbase_layer_thickness') }}"
                                    oninput="restrictDecimalPoints(event)">

                                @error('subbase_layer_thickness')
                                    <div class="invalid-feedback text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="base_layer_type">Base Layer Type: <span class="star"></span></label>
                                <select class="form-control form-control-sm @error('base_layer_type') is-invalid @enderror"
                                    id="base_layer_type" name="base_layer_type">
                                    <option value="">Choose one</option>
                                    @foreach ($baseLayers as $baseLayer)
                                        <option value="{{ $baseLayer->base_layer_type_cd }}">
                                            {{ $baseLayer->base_layer_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('base_layer_type')
                                    <div class="invalid-feedback text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="base_layer_thickness">Base Layer Thickness (MM):</label>
                                <input type="number" step="0.01" placeholder="0.00" id="base_layer_thickness"
                                    class="form-control form-control-sm @error('base_layer_thickness') is-invalid @enderror"
                                    name="base_layer_thickness" value="{{ old('base_layer_thickness') }}"
                                    oninput="restrictDecimalPoints(event)">

                                @error('base_layer_thickness')
                                    <div class="invalid-feedback text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="surface_type">Surface Type: <span class="star"></span></label>
                                <select class="form-control form-control-sm @error('surface_type') is-invalid @enderror"
                                    id="surface_type" name="surface_type">
                                    <option value="">Choose one</option>
                                    @foreach ($surfaceTypes as $surfaceType)
                                        <option value="{{ $surfaceType->surface_cd }}">
                                            {{ $surfaceType->surface_descr }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('surface_type')
                                    <div class="invalid-feedback text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="surface_layer_thickness">Surface Layer Thickness (MM):</label>
                                <input type="number" step="0.01" placeholder="0.00" id="surface_layer_thickness"
                                    class="form-control form-control-sm @error('surface_layer_thickness') is-invalid @enderror"
                                    name="surface_layer_thickness" value="{{ old('surface_layer_thickness') }}"
                                    oninput="restrictDecimalPoints(event)">

                                @error('surface_layer_thickness')
                                    <div class="invalid-feedback text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="surface_condition">Surface Condition: <span class="star"></span></label>
                                <select
                                    class="form-control form-control-sm @error('surface_condition') is-invalid @enderror"
                                    id="surface_condition" name="surface_condition">
                                    <option value="">Choose one</option>
                                    @foreach ($surfaceConditions as $surfaceCondition)
                                        <option value="{{ $surfaceCondition->rd_condition_cd }}">
                                            {{ $surfaceCondition->rd_condition_descr }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('surface_condition')
                                    <div class="invalid-feedback text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="pavement_external_section" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Other Section</span>
                            </div>
                            <div class="col-md-3">
                                <label for="has_shoulder">
                                    Does the pavement have the shoulder?
                                </label><br>
                                <input type="radio" id="yes" name="has_shoulder" value="Y">
                                <label for="yes">Yes</label>
                                <input type="radio" id="no" name="has_shoulder" value="N" checked>
                                <label for="no">No</label>
                            </div>
                            <div class="col-md-3">
                                <label for="has_drainage">
                                    Does the pavement have the drainage?
                                </label><br>
                                <input type="radio" id="yes" name="has_drainage" value="Y">
                                <label for="yes">Yes</label>
                                <input type="radio" id="no" name="has_drainage" value="N" checked>
                                <label for="no">No</label>
                            </div>
                            <div class="col-md-3">
                                <label for="is_land_slide_prone">
                                    Does the pavement have the land slide?
                                </label><br>
                                <input type="radio" id="yes" name="is_land_slide_prone" value="Y">
                                <label for="yes">Yes</label>
                                <input type="radio" id="no" name="is_land_slide_prone" value="N" checked>
                                <label for="no">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="row form-1-box border mt-2 pb-2" id="pavement_common_section" style="display: none;">
                        <div class="col-md-3">
                            <label for="construction_year">Construction Year<span class="star"></span></label>
                            <select class="form-control form-control-sm @error('construction_year') is-invalid @enderror"
                                id="construction_year" name="construction_year">
                                <option value="">Choose one</option>
                                <?php for ($i = Carbon\Carbon::now()->year; $i >= 1950; $i--) { ?>
                                <option value="<?php    echo $i; ?>"><?php    echo $i; ?></option>
                                <?php } ?>
                            </select>
                            @error('construction_year')
                                <div class="invalid-feedback text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="construction_cost">Construction Cost (Lakh):</label>
                            <input type="number" step="0.001" placeholder="0.00" id="construction_cost"
                                class="form-control form-control-sm @error('construction_cost') is-invalid @enderror"
                                name="construction_cost" value="{{ old('construction_cost') }}"
                                oninput="restrictDecimalPoints(event)">

                            @error('construction_cost')
                                <div class="invalid-feedback text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="remark">Remarks:</label>
                            <textarea class="form-control form-control-sm text-sm" id="remark" name="remark" rows="2"
                                placeholder="Write pavement remarks..."></textarea>
                        </div>
                    </div>

                    {{-- Upload Image --}}
                    <x-asset-resources.asset-image />
                    {{-- End of upload image --}}
                    {{-- upload documents --}}
                    <x-asset-resources.asset-document />
                    {{-- End of upload documents --}}
                </fieldset>
                <div class="text-end">
                    <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2" id="saveBtn">
                        <i class="fa fa-save"></i> Save
                    </button>
                    <button type="reset" class="btn btn-info btn-sm rounded-0 mt-2">
                        <i class="fa fa-undo"></i> Reset
                    </button>
                    <a href="{{ route('manageRoad') }}" class="btn btn-secondary btn-sm rounded-0 mt-2">
                        <i class="fa fa-backward"></i> Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Table content -->
        <div class="container-fluid mainBody">
            <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT PAVEMENT DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border py-2">
                <table class="table-responsive text-xs table table-bordered table-striped" id="pavement_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <tr class="text-center">
                            <th>Serial Number</th>
                            <th>Pavement Code</th>
                            <th>Start Chainage</th>
                            <th>End Chainage</th>
                            <th>Pavement Type</th>
                            <th>Formation Width</th>
                            <th>Carriage Width</th>
                            <th style="min-width: 6rem;">Subbase Layer Type</th>
                            <th style="min-width: 6rem;">Subbase Layer Thickness</th>
                            <th style="min-width: 6rem;">Base Layer Type</th>
                            <th style="min-width: 6rem;">Base Layer Thickness</th>
                            <th style="min-width: 6rem;">Surface Layer Type</th>
                            <th style="min-width: 6rem;">Surface Layer Thickness</th>
                            <th>Surface Condition</th>
                            <th>Add Shoulder</th>
                            <th>Add Drainage</th>
                            <th>Add LandSlide</th>
                            <th>Construction Year</th>
                            <th>Construction Cost</th>
                            <th>Pavement Remarks</th>
                            <th>Edit Pavement</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php $i = 1; ?>
                        @foreach ($pavementDetails as $item)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $item->rd_pavement_cd }}</td>
                                <td>{{ $item->start_chainage }}</td>
                                <td>{{ $item->end_chainage }}</td>
                                <td>{{ $item->pavement_type_cd ?? 'N/A' }}</td>
                                <td>{{ $item->formation_width ?? 'N/A' }}</td>
                                <td>{{ $item->carriage_width ?? 'N/A' }}</td>
                                <td>{{ $item->sub_base_layer_type_cd ?? 'N/A' }}</td>
                                <td>{{ $item->sub_base_lyr_thickness ?? 'N/A' }}</td>
                                <td>{{ $item->base_layer_type_cd ?? 'N/A' }}</td>
                                <td>{{ $item->base_lyr_thickness ?? 'N/A' }}</td>
                                <td>{{ $item->surface_type_cd ?? 'N/A' }}</td>
                                <td>{{ $item->surface_lyr_thickness ?? 'N/A' }}</td>
                                <td>{{ $item->surface_condition ?? 'N/A' }}</td>
                                <td>
                                    @if ($item->has_shoulder == 'Y')
                                        <a href="{{ route('pavement.subsection', ['id' => $item->rd_pavement_cd, 'asset' => 0]) }}">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    @else
                                        <p>N/A</p>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->has_drainage == 'Y')
                                        <a href="{{ route('pavement.subsection', ['id' => $item->rd_pavement_cd, 'asset' => 1]) }}">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    @else
                                        <p>N/A</p>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->is_land_slide_prone == 'Y')
                                        <a href="{{ route('pavement.subsection', ['id' => $item->rd_pavement_cd, 'asset' => 2]) }}">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    @else
                                        <p>N/A</p>
                                    @endif
                                </td>
                                <td>{{ $item->year_of_construction ?? 'N/A' }}</td>
                                <td>{{ $item->construction_cost ?? 'N/A' }}</td>
                                <td>{{ $item->remarks ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <a class="text-primary edit" data-toggle="modal"
                                        data-target="#editModal{{ $item->rd_pavement_cd }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php    $i++; ?>
                            {{-- @empty
                            <tr>
                                <td colspan="5" class="text-center">No pavement details found</td>
                            </tr> --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @foreach ($pavementDetails as $item)
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $item->rd_pavement_cd }}" tabindex="-1">
            <div class="modal-dialog modal-lg text-xs">
                <form id="update_pavement_{{ $item->rd_pavement_cd }}" method="POST" action="#">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->rd_pavement_cd }}">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5><i class="fas fa-clipboard-list text-dark"></i> <strong class="text-md">Update Pavement
                                    Details</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{-- <div class="form-group">
                                <label for="edit_pavement_condition">Pavement Condition</label>
                                <select class="form-control form-control-sm" id="edit_pavement_condition"
                                    name="pavement_condition" required>
                                    @foreach ($pavementCondition as $condition)
                                    <option value="{{ $condition->pv_condition_cd }}" {{ $item->pv_condition_cd ==
                                        $condition->pv_condition_cd ? 'selected' : '' }}>
                                        {{ $condition->pv_condition_descr }}
                                    </option>
                                    @endforeach
                                </select>
                            </div> --}}
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-success">Update</button>
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script src="{{ asset('js/common/asset_coordinates_script.js') }}" defer></script>
    <script src="{{ asset('js/road/assets/removeSelectedFile/script.js') }}" defer></script>

    <script>
        $(document).ready(function () {
            $('#subbase_layer_type').select2();
            $('#pavement_details_table').DataTable({});
            // Form submission handling
            $('#pavementForm').on('submit', function () {
                $('#saveBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Saving...');
            });

            // Error handling for modals
            $('.modal').on('shown.bs.modal', function () {
                $(this).find('.is-invalid').first().focus();
            });

        });

        // show other layers for pavement form
        $('#pavement_type').on('change', () => {
            $('#chainage_section, #pavement_layer_section, #pavement_external_section, #pavement_common_section')
                .show();

            $("#asset_image_container").show();
            $("#asset_document_container").show();
        });

        function restrictDecimalPoints(event) {
            const input = event.target;
            const value = input.value;
            const decimalIndex = value.indexOf('.');
            if (decimalIndex !== -1 && value.length - decimalIndex > 3) {
                input.value = value.slice(0, decimalIndex + 3);
            }
        }

        function restrictDecimalPointsUptoThree(event) {
            const input = event.target;
            const value = input.value;
            const decimalIndex = value.indexOf('.');
            if (decimalIndex !== -1 && value.length - decimalIndex > 4) {
                input.value = value.slice(0, decimalIndex + 4);
            }
        }
    </script>
@endpush