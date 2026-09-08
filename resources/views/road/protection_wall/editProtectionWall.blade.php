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
                <li class="breadcrumb-item">Add Protection Wall</li>
            </ol>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody py-3">
            @if (session('failed'))
                <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
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

            {{-- modified by Pulak 02-05-26 --}}
            @if ($protection_wall_id)
                <div class="alert alert-info" id="editModeAlert">
                </div>
            @endif
            {{-- modified by Pulak 02-05-26 --}}

            <form action="{{ route('updateProtectionWall', $protection_wall_id) }}" id="protection_wall_form" method="POST"
                autocomplete="off" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- saiful # 21-04-2026 # Start -->
                <input type="hidden" id="hdn_asset_plan_id" name="hdn_asset_plan_id" value="{{ $assetPlanId}}" />
                <input type="hidden" id="hdn_redefine_asset_from_project" name="hdn_redefine_asset_from_project"
                    value="{{ $redefineAssetFromProject }}" />
                <!-- saiful # 21-04-2026 # End -->
                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2" style="font-size:14px">Protection Wall Section</legend>
                    <div style="line-height: 2px;" class="my-2">
                        <p class="text-info text-sm">Completion of fields indicated by an asterisk (
                            <span class="text-danger text-bold">*</span> ) is mandatory.
                        </p><br>
                        <p class="text-info text-sm">Decimal inputs are accepted with a precision of up to three decimal
                            places. </p>
                    </div>
                    {{-- Hidden field --}}
                    <div class="row form-1-box">
                        <div class="col-md-3">

                            <input type="hidden" id="road_system_id" class="form-control form-control-sm"
                                name="road_system_id" value="{{ session('system_id') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="road_length" class="form-control form-control-sm" name="road_length"
                                value="{{ session('road_length') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="start_chainage" class="form-control form-control-sm"
                                name="start_chainage" value="{{ $roadChainage->chainage_from }}">
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="end_chainage" class="form-control form-control-sm" name="end_chainage"
                                value="{{ $roadChainage->chainage_to }}">
                        </div>
                        <div>
                            <input type="hidden" id="preSectionLength" class="form-control form-control-sm"
                                name="preSectionLength" value="{{ $pciPrevValue->pci_section_length_in_meter ?? 'null' }}">
                        </div>
                        <div>
                            <input type="hidden" id="preChainage" class="form-control form-control-sm" name="preChainage"
                                value="{{ $pciPrevValue->chainage ?? 'null' }}">
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="chainage">Chainage (Kms):<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" placeholder="0.000" id="chainage"
                                class="form-control form-control-sm" name="chainage" oninput="restrictDecimalPoints(event)">
                        </div>
                        <div class="col-md-3">
                            <label for="wall_type_cd">Protection Wall Type:</label>
                            <select class="form-control form-control-sm" id="wall_type_cd" name="wall_type_cd">
                                <option value="">Choose one</option>
                                @foreach ($protectionWallTypes as $protectionWallTypes)
                                    <option value="{{ $protectionWallTypes->wall_type_cd }}">
                                        {{ $protectionWallTypes->wall_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="structure_type_cd">Protection Structure Type:</label>
                            <select class="form-control form-control-sm" id="structure_type_cd" name="structure_type_cd">
                                <option value="">Choose one</option>
                                @foreach ($superStructureTypes as $superStructureType)
                                    <option value="{{ $superStructureType->structure_type_cd }}">
                                        {{ $superStructureType->structure_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="bottom_width">Bottom Width (Mtrs):<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" placeholder="0.000" id="bottom_width"
                                class="form-control form-control-sm" name="bottom_width"
                                oninput="restrictDecimalPoints(event)">
                        </div>
                        <div class="col-md-3">
                            <label for="top_width">Top Width (Mtrs):<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" placeholder="0.000" id="top_width"
                                class="form-control form-control-sm" name="top_width"
                                oninput="restrictDecimalPoints(event)">
                        </div>
                        <div class="col-md-3">
                            <label for="length">Length (Mtrs):<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" placeholder="0.000" id="length"
                                class="form-control form-control-sm" name="length" oninput="restrictDecimalPoints(event)">
                        </div>
                        <div class="col-md-3">
                            <label for="height">Height (Mtrs):<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" placeholder="0.000" id="height"
                                class="form-control form-control-sm" name="height" oninput="restrictDecimalPoints(event)">
                        </div>
                        <div class="col-md-3">
                            <label for="year_of_construction">Year of Construction:</label>
                            <select class="form-control form-control-sm" id="year_of_construction"
                                name="year_of_construction">
                                <option value="">Please Select</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="year_of_renovation">Year of Renovation:</label>
                            <select class="form-control form-control-sm" id="year_of_renovation" name="year_of_renovation">
                                <option value="">Please Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-12">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control form-control-sm text-sm" id="remarks" name="remarks" rows="2"
                                placeholder="Write PCI remarks..."></textarea>
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
                    <button type="submit" id="saveBtn" class="btn btn-success btn-sm rounded-0 mt-2">
                        <i class="fa fa-save"></i> <span id="saveBtnLabel">Update</span>
                    </button>
                    <button type="button" id="cancelEditBtn" class="btn btn-warning btn-sm rounded-0 mt-2">
                        <i class="fa fa-times"></i><a class="text-white"
                            href="{{ route('road.protection.wall', $road_system_id) }}"> Cancel Edit</a>
                    </button>
                    <button class="btn btn-secondary btn-sm rounded-0 mt-2"><i class="fa fa-backward"></i><a
                            class="text-white" href="{{ route('manageRoad') }} ">
                            Cancel</a>
                    </button>
                </div>
            </form>
        </div>

        <!-- table content -->
        <div class="container-fluid mainBody">
            <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT PROTECTION WALL DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border py-2">
                <div class="d-flex text-sm justify-content-end mb-2">
                    <button id="freezeBtn" class="btn btn-sm btn-info rounded-1 text-bold">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Send for finalization
                    </button>
                </div>
                <table class="table-responsive text-xs table table-bordered table-striped" id="protectionWallTable">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">Protection-Wall Code</th>
                        <!-- Saiful # 22-04-2026 # Start -->
                        <th class="text-center">Project CD</th>
                        <!-- Saiful # 22-04-2026 # End -->
                        <th class="text-center">Chainage (Kms)</th>
                        <th class="text-center">Protection-Wall Type</th>
                        <th class="text-center">WallStructure Type</th>
                        <th class="text-center">Bottom Width(Mtrs)</th>
                        <th class="text-center">Top Width(Mtrs)</th>
                        <th class="text-center">Length (Mtrs)</th>
                        <th class="text-center">Height (Mtrs)</th>
                        <th class="text-center">Construction Year</th>
                        <th class="text-center">Renovation Year</th>
                        <th class="text-center">Rejection Reason</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Select</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($protectionWallDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->protection_wall_cd ?? 'NA' }}
                                </td>
                                <!-- Saiful # 22-04-2026 # Start -->
                                <td>
                                    {{ $item->project_cd ?? 'NA' }}
                                </td>
                                <!-- Saiful # 22-04-2026 # End -->
                                <td>
                                    {{ $item->chainage ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->wall_type_descr ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->structure_type_descr ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->bottom_width ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->top_width ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->length ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->height ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->year_of_construction ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->year_of_renovation ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->reason_of_rejection ?? 'NA' }}
                                </td>
                                <td>
                                    {{ $item->remarks ?? 'NA' }}
                                </td>
                                <td>
                                    <a class="text-primary edit"
                                        href="{{ route('editProtectionWall', $item->protection_wall_cd) }}"><i
                                            class="fas fa-edit"></i></a>
                                </td>
                                <td>
                                    <input type="checkbox" class="selected-asset"
                                        data-protection-wall="{{ $item->protection_wall_cd }}" />
                                </td>
                            </tr>
                            <?php    $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <x-success-modal />
    <x-warning-modal />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <style>
        /* Target the Select2 container */
        .select2-container .select2-selection--single {
            font-size: 13px;
        }

        /* Target the dropdown options */
        .select2-results__option {
            font-size: 13px;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/road/protectionWall/script.js') }}" defer></script>
    <script>
        function restrictDecimalPoints(event) {
            const input = event.target;
            const value = input.value;
            const decimalIndex = value.indexOf('.');
            if (decimalIndex !== -1 && value.length - decimalIndex > 4) {
                input.value = value.slice(0, decimalIndex + 4);
            }
        }
        const protection_wall_id = "{{ $protection_wall_id }}";
        console.log(protection_wall_id);
    </script>
    <script src="{{ asset('js/road/protectionWall/editScript.js') }}" defer></script>
@endpush