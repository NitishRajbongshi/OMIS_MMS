@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('manageRoad') }}">Manage Roads</a>
                </li>
                <li class="breadcrumb-item">Add Bridge</li>
            </ol>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody py-3">
            @if (session('failed'))
                <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa fa-info" aria-hidden="true"></i>
                    <strong>Failed!</strong> {{ session('failed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('success'))
                <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <x-road-info :roadChainage="$roadChainage" />
            <x-road-tab-navigation />
            <!--modified by Pulak-- 29-04-2026-->
            @if ($bridge_id)
                <div class="alert alert-info" id="editModeAlert">
                </div>
            @endif
            <!--modification end by Pulak-- 29-04-2026-->
            <form action="{{ route('bridge.store') }}" id="cd_bridge_form" method="post" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <!-- saiful # 21-04-2026 # Start -->
                <input type="hidden" id="hdn_asset_plan_id" name="hdn_asset_plan_id" value="{{ $assetPlanId}}" />
                <input type="hidden" id="hdn_redefine_asset_from_project" name="hdn_redefine_asset_from_project"
                    value="{{ $redefineAssetFromProject }}" />
                <!-- saiful # 21-04-2026 # End -->

                <!--modified by Pulak-- 29-04-2026-->
                <input type="hidden" name="_method" id="form_method" value="POST">
                <input type="hidden" name="bridge_id" id="bridge_id" value="">
                <input type="hidden" name="rd_system_id" id="rd_system_id" value="">
                <input type="hidden" name="created_at_office_cd" id="created_at_office_cd" value="">
                <!--modification end by Pulak-- 29-04-2026-->
                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2" style="font-size:14px; margin-bottom: 15px;">Bridge Details</legend>
                    <div style="line-height: 2px;">
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
                            <input type="hidden" id="road_end_chainage" class="form-control form-control-sm"
                                name="road_end_chainage" value="{{ $roadChainage->chainage_to }}">
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="road_start_chainage" class="form-control form-control-sm"
                                name="road_start_chainage" value="{{ $roadChainage->chainage_from }}">
                        </div>
                    </div>

                    {{-- Default field --}}
                    <div class="row form-1-box">
                        <div class="col-md-4">
                            <label for="bridge_name">Bridge Name:<span class="star"></span></label>
                            <input type="text" id="bridge_name" class="form-control form-control-sm" name="bridge_name"
                                value="{{ old('bridge_name') }}" placeholder="Bridge Name">
                        </div>
                        <div class="col-md-3 myTooltip">
                            <label for="chainage">Chainage:<span class="star"></span></label>
                            <div class="tooltiptext">
                                <i class="fa fa-info-circle me-1"></i>
                                Chainage should between {{ $roadChainage->chainage_from }}
                                - {{ $roadChainage->chainage_to }}
                            </div>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="chainage" class="form-control form-control-sm" name="chainage"
                                value="{{ old('chainage') }}" />
                        </div>
                        <div class="col-md-3">
                            <label for="bridge_type">Bridge Type:<span class="star"></span></label>
                            <select class="form-control form-control-sm" id="bridge_type" name="bridge_type">
                                <option value="">Choose one</option>
                                @foreach ($bridgeTypes as $item)
                                    <option value="{{ $item->bridge_type_cd }}">
                                        {{ $item->bridge_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <x-get-coordinate.component />
                    {{-- Common fields --}}
                    <div class="row form-1-box bridge_common_field_container" style="display: none;">
                        <div class="col-md-3">
                            <label for="bridge_width">Bridge Width (Mtrs):<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="bridge_width" class="form-control form-control-sm" name="bridge_width"
                                value="{{ old('bridge_width') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="river_name">River Name:<span class="star"></span></label>
                            <input type="text" id="river_name" class="form-control form-control-sm" name="river_name"
                                value="{{ old('river_name') }}" placeholder="River Name">
                        </div>
                        <div class="col-md-3">
                            <label for="construction_type">Construction Type:<span class="star"></span></label>
                            <select class="form-control form-control-sm" id="construction_type" name="construction_type">
                                <option value="">Choose one</option>
                                @foreach ($constructionTypes as $item)
                                    <option value="{{ $item->construction_type_cd }}">
                                        {{ $item->construction_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="span_no">No of Span:<span class="star"></span></label>
                            <select class="form-control form-control-sm" id="span_no" name="span_no">
                                <option value="">Choose one</option>
                                <?php for ($i = 1; $i <= 8; $i++) { ?>
                                <option value="<?php    echo $i; ?>"><?php    echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    {{-- Span container --}}
                    <div class="row form-1-box mt-2" id="spanContainer" style="display: none; background-color: #efeeee;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1">
                            <span class="text-bold text-sm">Bridge Span Details</span>
                        </div>
                        <div class="py-1">
                            <label for="span_dimension" class="text-danger">
                                Do all the spans for this bridge have the same length?
                                <span class="star"></span></label>
                            <input type="radio" id="yes" name="span_dimension" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="span_dimension" value="N">
                            <label for="no">No</label>
                        </div>
                    </div>
                    <div class="row form-1-box">
                        {{-- <div class="col-md-12" style="background-color: #efeeee;" id="span_container">
                        </div> --}}
                        <div id="single_span_field_container" style="display: none;" class="col-md-12">
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-md-3">
                                    <label for="span_length">Span Length (Mtrs):<span class="star"></span></label>
                                    <input type="number" min='0' step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="span_length"
                                        class="form-control form-control-sm" name="span_length"
                                        value="{{ old('span_length') }}">
                                </div>
                            </div>
                        </div>

                        <div id="multiple_span_field_container" style="display: none;" class="col-md-12">
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;" data-section="1"
                                id="span_field_container">
                                <div class="col-md-3">
                                    <label for="span_length_1">Span Length 1 (Mtrs):<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="span_length_1"
                                        class="form-control form-control-sm" name="span_length_1"
                                        value="{{ old('span_length_1') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End of Span container --}}

                    <div class="row form-1-box bridge_common_field_container" style="display: none;">
                        <div class="col-md-3">
                            <label for="kerb_width">Kerb Width (Mtrs):<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="kerb_width" class="form-control form-control-sm" name="kerb_width"
                                value="{{ old('kerb_width') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="kerb_height">Kerb Height (Mtrs):<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="kerb_height" class="form-control form-control-sm" name="kerb_height"
                                value="{{ old('kerb_height') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="load_capacity">Load Capacity (Tones):<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="load_capacity" class="form-control form-control-sm" name="load_capacity"
                                value="{{ old('load_capacity') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="no_of_piers">No of Pier:<span class="star"></span></label>
                            <input type="number" step="0.001" id="no_of_piers" value="0"
                                class="form-control form-control-sm" name="no_of_piers" value="{{ old('no_of_piers') }}"
                                readonly>
                        </div>
                    </div>

                    {{-- Piers container --}}
                    <div class="row form-1-box mt-2" id="piers_container" style="display: none; background-color: #efeeee;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Bridge Piers Details</span>
                        </div>
                        <div class="col-md-12">
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-md-3">
                                    <label for="pier_type_cd">Type of Pier:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="pier_type_cd" name="pier_type_cd">
                                        <option value="">Choose one</option>
                                        @foreach ($pierTypes as $pierType)
                                            <option value="{{ $pierType->id }}">
                                                {{ $pierType->pier_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="pier_length">Pier Length (Mtrs):<span class="star"></span></label>
                                    <input type="number" min='0' step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="pier_length"
                                        class="form-control form-control-sm" name="pier_length"
                                        value="{{ old('pier_length') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="pier_width">Pier Width (Mtrs):<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="pier_width"
                                        class="form-control form-control-sm" name="pier_width"
                                        value="{{ old('pier_width') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="pier_height">Pier Height (Mtrs):<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="pier_height"
                                        class="form-control form-control-sm" name="pier_height"
                                        value="{{ old('pier_height') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="pier_bearings">Bearing Types:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="pier_bearings" name="pier_bearings">
                                        <option value="">Choose one</option>
                                        @foreach ($bearingTypes as $item)
                                            <option value="{{ $item->bearing_type_cd }}">
                                                {{ $item->bearing_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="pier_foundation_type">Foundation Type:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="pier_foundation_type"
                                        name="pier_foundation_type">
                                        <option value="">Choose one</option>
                                        @foreach ($foundationTypes as $item)
                                            <option value="{{ $item->foundation_cd }}">
                                                {{ $item->foundation_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End of Piers container --}}

                    {{-- Start: Piers Foundation Type Section --}}
                    <div id="PierPileFoundation" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Pile Foundation</span>
                            </div>
                            <div class="col-md-3">
                                <label for="pier_pile_diameter">Diameter of Pile:<span class="star"></span></label>
                                <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                    id="pier_pile_diameter" class="form-control form-control-sm" name="pier_pile_diameter">
                            </div>
                            <div class="col-md-3">
                                <label for="pier_pile_length">Length of Pile (Mtrs):<span class="star"></span></label>
                                <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                    id="pier_pile_length" class="form-control form-control-sm" name="pier_pile_length" />
                            </div>
                            <div class="col-md-3">
                                <label for="pier_pile_type">Pile Type:<span class="star"></span></label>
                                <select class="form-control form-control-sm" id="pier_pile_type" name="pier_pile_type">
                                    <option value="">Choose one</option>
                                    @foreach ($pileTypes as $item)
                                        <option value="{{ $item->pile_type_cd }}">
                                            {{ $item->pile_type_descr }}
                                        </option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="PierWellFoundation" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Well Foundation</span>
                            </div>
                            <div class="col-md-3">
                                <label for="pier_well_type">Well Type:<span class="star"></span></label>
                                <select class="form-control form-control-sm" id="pier_well_type" name="pier_well_type">
                                    <option value="">Choose one</option>
                                    @foreach ($wellTypes as $item)
                                        <option value="{{ $item->well_type_cd }}">
                                            {{ $item->well_type_descr }}
                                        </option>

                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="pier_well_size">Size of Well (Mtrs):<span class="star"></span></label>
                                <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                    id="pier_well_size" class="form-control form-control-sm" name="pier_well_size">
                            </div>
                        </div>
                    </div>
                    <div id="PierOpenFoundation" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Open Foundation</span>
                            </div>
                            <div class="col-md-3">
                                <label for="pier_open_fundation_size">Size of Foundation (Mtrs):<span
                                        class="star"></span></label>
                                <input type="text" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                    id="pier_open_fundation_size" class="form-control form-control-sm"
                                    name="pier_open_fundation_size">
                            </div>

                            <div class="col-md-3">
                                <label for="pier_open_fundation_depth">Depth of Foundation (Mtrs):<span
                                        class="star"></span></label>
                                <input type="text" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                    id="pier_open_fundation_depth" class="form-control form-control-sm"
                                    name="pier_open_fundation_depth">
                            </div>
                        </div>
                    </div>
                    {{-- End: Piers Foundation Type Section --}}

                    <div class="row form-1-box bridge_common_field_container" style="display: none;">
                        <div class="col-md-3">
                            <label for="superstructure_type">Super Structure Type:<span class="star"></span></label>
                            <select class="form-control form-control-sm" id="superstructure_type"
                                name="superstructure_type">
                                <option value="">Choose one</option>
                                @foreach ($superStructureType as $item)
                                    <option value="{{ $item->st_type_cd }}">
                                        {{ $item->st_type_descr }}
                                    </option>

                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="handrail_type">Handrail Type <span class="star"></span></label>
                            <select class="form-control form-control-sm" id="handrail_type" name="handrail_type">
                                <option value="">Choose one</option>
                                @foreach ($handrailTypes as $item)
                                    <option value="{{ $item->hand_rail_type_cd }}">
                                        {{ $item->hand_rail_type_descr }}
                                    </option>

                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="deck_type">Deck Type <span class="star"></span></label>
                            <select class="form-control form-control-sm" id="deck_type" name="deck_type">
                                <option value="">Choose one</option>
                                @foreach ($deckTypes as $item)
                                    <option value="{{ $item->deck_type_cd }}">
                                        {{ $item->deck_type_descr }}
                                    </option>

                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- Start of abutment section --}}
                    <div class="row form-1-box mt-2 bridge_common_field_container"
                        style="display: none; background-color: #efeeee;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Bridge Abutment Details</span>
                        </div>
                        <div class="col-md-12 pl-2 py-2">
                            <label for="bridge_abutment">Does the bridge have an abutment wall? <span
                                    class="star"></span></label>
                            <input type="radio" id="yes" name="bridge_abutment" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="bridge_abutment" value="N" checked>
                            <label for="no">No</label>
                        </div>
                        <div id="bridge_abutment_container" style="display: none; width: 100%;">
                            <div class="row pb-2">
                                <div class="col-md-3">
                                    <label for="abutment_wall_type_cd">Abutment Type:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="abutment_wall_type_cd"
                                        name="abutment_wall_type_cd">
                                        <option value="">Choose one</option>
                                        @foreach ($abutmentTypes as $item)
                                            <option value="{{ $item->abutment_type_cd }}">
                                                {{ $item->abutment_type_descr }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="abutment_wall_length">Length (Mtrs):<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="abutment_wall_length"
                                        class="form-control form-control-sm" name="abutment_wall_length"
                                        value="{{ old('abutment_wall_length') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="abutment_wall_width">Width (Mtrs):<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="abutment_wall_width"
                                        class="form-control form-control-sm" name="abutment_wall_width"
                                        value="{{ old('abutment_wall_width') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="abutment_wall_heigth">Height (Mtrs):<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="abutment_wall_heigth"
                                        class="form-control form-control-sm" name="abutment_wall_heigth"
                                        value="{{ old('abutment_wall_heigth') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="abutment_bearings">Bearing Types:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="abutment_bearings"
                                        name="abutment_bearings">
                                        <option value="">Choose one</option>
                                        @foreach ($bearingTypes as $item)
                                            <option value="{{ $item->bearing_type_cd }}">
                                                {{ $item->bearing_type_descr }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="foundation_type">Foundation Type:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="foundation_type"
                                        name="foundation_type">
                                        <option value="">Choose one</option>
                                        @foreach ($foundationTypes as $item)
                                            <option value="{{ $item->foundation_cd }}">
                                                {{ $item->foundation_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End of abutment section --}}

                    {{-- Start: Abutment Foundation Type Section --}}
                    <div id="PileFoundation" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Pile Foundation</span>
                            </div>
                            <div class="col-md-3">
                                <label for="pile_diameter">Diameter of Pile:<span class="star"></span></label>
                                <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                    id="pile_diameter" class="form-control form-control-sm" name="pile_diameter">
                            </div>
                            <div class="col-md-3">
                                <label for="pile_length">Length of Pile (Mtrs):<span class="star"></span></label>
                                <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                    id="pile_length" class="form-control form-control-sm" name="pile_length" />
                            </div>
                            <div class="col-md-3">
                                <label for="pile_type">Pile Type:<span class="star"></span></label>
                                <select class="form-control form-control-sm" id="pile_type" name="pile_type">
                                    <option value="">Choose one</option>
                                    @foreach ($pileTypes as $item)
                                        <option value="{{ $item->pile_type_cd }}">
                                            {{ $item->pile_type_descr }}
                                        </option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="WellFoundation" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Well Foundation</span>
                            </div>
                            <div class="col-md-3">
                                <label for="well_type">Well Type:<span class="star"></span></label>
                                <select class="form-control form-control-sm" id="well_type" name="well_type">
                                    <option value="">Choose one</option>
                                    @foreach ($wellTypes as $item)
                                        <option value="{{ $item->well_type_cd }}">
                                            {{ $item->well_type_descr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="well_size">Size of Well (Mtrs):<span class="star"></span></label>
                                <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                    id="well_size" class="form-control form-control-sm" name="well_size">
                            </div>
                        </div>
                    </div>
                    <div id="OpenFoundation" style="display: none;">
                        <div class="row form-1-box border mt-2 pb-2" style="background-color: #efeeee;">
                            <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                                <span class="text-bold text-sm">Open Foundation</span>
                            </div>
                            <div class="col-md-3">
                                <label for="open_fundation_size">Size of Foundation (Mtrs):<span
                                        class="star"></span></label>
                                <input type="text" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                    id="open_fundation_size" class="form-control form-control-sm"
                                    name="open_fundation_size">
                            </div>

                            <div class="col-md-3">
                                <label for="open_fundation_depth">Depth of Foundation (Mtrs):<span
                                        class="star"></span></label>
                                <input type="text" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                    id="open_fundation_depth" class="form-control form-control-sm"
                                    name="open_fundation_depth">
                            </div>
                        </div>
                    </div>
                    {{-- End: Abutment Foundation Type Section --}}

                    <div class="row form-1-box bridge_common_field_container" style="display: none;">
                        <div class="col-md-3">
                            <label for="expansion_joints">Expansion Joints:<span class="star"></span></label>
                            <select class="form-control form-control-sm" id="expansion_joints" name="expansion_joints">
                                <option value="">Choose one</option>
                                @foreach ($expJoints as $item)
                                    <option value="{{ $item->expn_joint_cd }}">
                                        {{ $item->expn_joint_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="deck_level">Deck Level:<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" id="deck_level"
                                oninput="restrictDecimalPoints(event)" class="form-control form-control-sm"
                                name="deck_level" value="{{ old('deck_level') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="carriage">Carriage Width (Mtrs):<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="carriage" class="form-control form-control-sm" name="carriage"
                                value="{{ old('carriage') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="guard_stone">Guard Stone:<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="guard_stone" class="form-control form-control-sm" name="guard_stone"
                                value="{{ old('guard_stone') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="discharge">Discharge:</label>
                            <input type="number" step="0.001" placeholder="0.000" id="discharge"
                                oninput="restrictDecimalPoints(event)" class="form-control form-control-sm" name="discharge"
                                value="{{ old('discharge') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="source_depth">Source Depth (Mtrs):</label>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="source_depth" class="form-control form-control-sm" name="source_depth"
                                value="{{ old('source_depth') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="lowest_water_level">Lowest Water Level:</label>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="lowest_water_level" class="form-control form-control-sm" name="lowest_water_level"
                                value="{{ old('lowest_water_level') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="highest_flood_level">Highest Flood Level:</label>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="highest_flood_level" class="form-control form-control-sm" name="highest_flood_level"
                                value="{{ old('highest_flood_level') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="rfl">FRL:<span class="star"></span></label>
                            <input type="number" step="0.001" placeholder="0.000" oninput="restrictDecimalPoints(event)"
                                id="rfl" class="form-control form-control-sm" name="rfl" value="{{ old('rfl') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="year_of_rehabilitation">Rehabilitation Year:</label>
                            <select class="form-control form-control-sm" id="year_of_rehabilitation"
                                name="year_of_rehabilitation">
                                <option value="" disable selected hidden>Select year</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="year_of_contruction">Construction Year:</label>
                            <select class="form-control form-control-sm" id="year_of_contruction"
                                name="year_of_contruction">
                                <option value="" disable selected hidden>Select year</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="last_inspection">Last Inspection:<span class="star"></span></label>
                            <input type="date" id="last_inspection" class="form-control form-control-sm"
                                name="last_inspection" value="{{ old('last_inspection') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="condition">Condition:<span class="star"></span></label>
                            <select class="form-control form-control-sm" id="condition" name="condition">
                                <option value="">Choose one</option>
                                @foreach ($bridgeConditions as $item)
                                    <option value="{{ $item->rd_condition_cd }}">
                                        {{ $item->rd_condition_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="next_schedule_inspection">Next Schedule Inspection:<span
                                    class="star"></span></label>
                            <input type="date" id="next_schedule_inspection" class="form-control form-control-sm"
                                name="next_schedule_inspection" value="{{ old('next_schedule_inspection') }}">
                        </div>
                        <div class="col-md-3 px-2">
                            <label for="footpath">Does the bridge have a footpath?<span class="star"></span></label><br>
                            <input type="radio" id="yes" name="footpath" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="footpath" value="N" checked>
                            <label for="no">No</label>
                        </div>
                    </div>

                    {{-- Start of wing wall section --}}
                    <div class="row form-1-box mt-2 bridge_common_field_container"
                        style="display: none; background-color: #efeeee;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Bridge Wing Wall</span>
                        </div>
                        <div class="col-md-3 px-2">
                            <label for="wing_wall">Does the bridge have a wing wall? <span class="star"></span></label><br>
                            <input type="radio" id="yes" name="wing_wall" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="wing_wall" value="N" checked>
                            <label for="no">No</label>
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-12" style="background-color: #efeeee; display:none;" id="bridge_wing_wall">
                            <div class="">
                                <label for="is_same_wing_wall" class="text-danger">
                                    Are all the wing walls the same dimension?
                                    <span class="star"></span></label>
                                <input type="radio" id="yes" name="is_same_wing_wall" value="Y">
                                <label for="yes">Yes</label>
                                <input type="radio" id="no" name="is_same_wing_wall" value="N">
                                <label for="no">No</label>
                            </div>
                        </div>
                        <div id="box_wing_wall_fields" style="display: none;" class="col-md-12">
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-md-3">
                                    <label for="wing_wall_type_cd">Wing Wall Type <span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="wing_wall_type_cd"
                                        name="wing_wall_type_cd">
                                        <option value="">Choose one</option>
                                        @foreach ($wingWallTypes as $wingWallType)
                                            <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                {{ $wingWallType->wing_wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="length">Length (Mtrs)<span class="star"></span></label>
                                    <input type="number" min='0' step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="length"
                                        class="form-control form-control-sm" name="length" value="{{ old('length') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="top_width">Top Width (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="top_width"
                                        class="form-control form-control-sm" name="top_width"
                                        value="{{ old('top_width') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="bottom_width">Bottom Width (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="bottom_width"
                                        class="form-control form-control-sm" name="bottom_width"
                                        value="{{ old('bottom_width') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="height1">Height 1 (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="height1"
                                        class="form-control form-control-sm" name="height1" value="{{ old('height1') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="height2">Height 2 (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="height2"
                                        class="form-control form-control-sm" name="height2" value="{{ old('height2') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="slope">Slope (Mtrs)<span class="star"></span></label>
                                    <input type="number" min='0' step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="slope"
                                        class="form-control form-control-sm" name="slope" value="{{ old('slope') }}">
                                </div>
                                <div class="col-md-3" id="angleContainer" style="display: none;">
                                    <label for="angle">Angle (Mtrs)<span class="star"></span></label>
                                    <input type="number" min='0' step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="angle"
                                        class="form-control form-control-sm" name="angle" value="{{ old('angle') }}">
                                </div>
                                <div class="col-md-3" style="display: none;" id="radiusContainer">
                                    <label for="radius">Radius (Mtrs)<span class="star"></span></label>
                                    <input type="number" min='0' step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="radius"
                                        class="form-control form-control-sm" name="radius" value="{{ old('radius') }}">
                                </div>
                            </div>
                        </div>

                        <div id="box_wing_wall_fields_multiple" style="display: none;" class="col-md-12">
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;" data-section="1">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Wing wall 1
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="wing_wall_type_1">Wing Wall Type <span class="star"></span></label>
                                    <select class="form-control form-control-sm wing-wall-type" id="wing_wall_type_1"
                                        name="wing_wall_type_1" data-section="1">
                                        <option value="">Choose one</option>
                                        @foreach ($wingWallTypes as $wingWallType)
                                            <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                {{ $wingWallType->wing_wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="length_1">Length (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="length_1"
                                        class="form-control form-control-sm" name="length_1" value="{{ old('length_1') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="top_width_1">Top Width (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="top_width_1"
                                        class="form-control form-control-sm" name="top_width_1"
                                        value="{{ old('top_width_1') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="bottom_width_1">Bottom Width (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="bottom_width_1"
                                        class="form-control form-control-sm" name="bottom_width_1"
                                        value="{{ old('bottom_width_1') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="height1_1">Height 1 (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="height1_1"
                                        class="form-control form-control-sm" name="height1_1"
                                        value="{{ old('height1_1') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="height2_1">Height 2 (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="height2_1"
                                        class="form-control form-control-sm" name="height2_1"
                                        value="{{ old('height2_1') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="slope_1">Slope (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="slope_1"
                                        class="form-control form-control-sm" name="slope_1" value="{{ old('slope_1') }}">
                                </div>
                                <div class="col-md-3" id="angleContainer_1" style="display: none;">
                                    <label for="angle_1">Angle (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="angle_1"
                                        class="form-control form-control-sm" value="{{ old('angle_1') }}" data-section="1"
                                        name="angle_1">
                                </div>
                                <div class="col-md-3" id="radiusContainer_1" style="display: none;">
                                    <label for="radius_1">Radius (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="radius_1"
                                        class="form-control form-control-sm" data-section="1" name="radius_1"
                                        value="{{ old('radius_1') }}">
                                </div>
                            </div>
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;" data-section="2">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Wing wall 2
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="wing_wall_type_2">Wing Wall Type <span class="star"></span></label>
                                    <select class="form-control form-control-sm wing-wall-type" id="wing_wall_type_2"
                                        name="wing_wall_type_2" data-section="2">
                                        <option value="">Choose one</option>
                                        @foreach ($wingWallTypes as $wingWallType)
                                            <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                {{ $wingWallType->wing_wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="length_2">Length (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="length_2"
                                        class="form-control form-control-sm" name="length_2" value="{{ old('length_2') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="top_width_2">Top Width (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="top_width_2"
                                        class="form-control form-control-sm" name="top_width_2"
                                        value="{{ old('top_width_2') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="bottom_width_2">Bottom Width (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="bottom_width_2"
                                        class="form-control form-control-sm" name="bottom_width_2"
                                        value="{{ old('bottom_width_2') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="height1_2">Height 1 (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="height1_2"
                                        class="form-control form-control-sm" name="height1_2"
                                        value="{{ old('height1_2') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="height2_2">Height 2 (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="height2_2"
                                        class="form-control form-control-sm" name="height2_2"
                                        value="{{ old('height2_2') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="slope_2">Slope (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="slope_2"
                                        class="form-control form-control-sm" name="slope_2" value="{{ old('slope_2') }}">
                                </div>
                                <div class="col-md-3" id="angleContainer_2" style="display: none;">
                                    <label for="angle_2">Angle (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="angle_2"
                                        class="form-control form-control-sm" value="{{ old('angle_2') }}" data-section="2"
                                        name="angle_2">
                                </div>
                                <div class="col-md-3" id="radiusContainer_2" style="display: none;">
                                    <label for="radius_2">Radius (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="radius_2"
                                        class="form-control form-control-sm" data-section="2" name="radius_2"
                                        value="{{ old('radius_2') }}">
                                </div>
                            </div>
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;" data-section="3">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Wing wall 3
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="wing_wall_type_3">Wing Wall Type <span class="star"></span></label>
                                    <select class="form-control form-control-sm wing-wall-type" id="wing_wall_type_3"
                                        name="wing_wall_type_3" data-section="3">
                                        <option value="">Choose one</option>
                                        @foreach ($wingWallTypes as $wingWallType)
                                            <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                {{ $wingWallType->wing_wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="length_3">Length (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="length_3"
                                        class="form-control form-control-sm" name="length_3" value="{{ old('length_3') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="top_width_3">Top Width (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="top_width_3"
                                        class="form-control form-control-sm" name="top_width_3"
                                        value="{{ old('top_width_3') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="bottom_width_3">Bottom Width (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="bottom_width_3"
                                        class="form-control form-control-sm" name="bottom_width_3"
                                        value="{{ old('bottom_width_3') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="height1_3">Height 1 (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="height1_3"
                                        class="form-control form-control-sm" name="height1_3"
                                        value="{{ old('height1_3') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="height2_3">Height 2 (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="height2_3"
                                        class="form-control form-control-sm" name="height2_3"
                                        value="{{ old('height2_3') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="slope_3">Slope (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="slope_3"
                                        class="form-control form-control-sm" name="slope_3" value="{{ old('slope_3') }}">
                                </div>
                                <div class="col-md-3" id="angleContainer_3" style="display: none;">
                                    <label for="angle_3">Angle (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="angle_3"
                                        class="form-control form-control-sm" value="{{ old('angle_3') }}" data-section="3"
                                        name="angle_3">
                                </div>
                                <div class="col-md-3" id="radiusContainer_3" style="display: none;">
                                    <label for="radius_3">Radius (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="radius_3"
                                        class="form-control form-control-sm" data-section="3" name="radius_3"
                                        value="{{ old('radius_3') }}">
                                </div>
                            </div>
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;" data-section="4">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Wing wall 4
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="wing_wall_type_4">Wing Wall Type <span class="star"></span></label>
                                    <select class="form-control form-control-sm wing-wall-type" id="wing_wall_type_4"
                                        name="wing_wall_type_4" data-section="4">
                                        <option value="">Choose one</option>
                                        @foreach ($wingWallTypes as $wingWallType)
                                            <option value="{{ $wingWallType->wing_wall_type_cd }}">
                                                {{ $wingWallType->wing_wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="length_4">Length (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="length_4"
                                        class="form-control form-control-sm" name="length_4" value="{{ old('length_4') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="top_width_4">Top Width (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="top_width_4"
                                        class="form-control form-control-sm" name="top_width_4"
                                        value="{{ old('top_width_4') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="bottom_width_4">Bottom Width (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="bottom_width_4"
                                        class="form-control form-control-sm" name="bottom_width_4"
                                        value="{{ old('bottom_width_4') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="height1_4">Height 1 (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="height1_4"
                                        class="form-control form-control-sm" name="height1_4"
                                        value="{{ old('height1_4') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="height2_4">Height 2 (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="height2_4"
                                        class="form-control form-control-sm" name="height2_4"
                                        value="{{ old('height2_4') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="slope_4">Slope (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="slope_4"
                                        class="form-control form-control-sm" name="slope_4" value="{{ old('slope_4') }}">
                                </div>
                                <div class="col-md-3" id="angleContainer_4" style="display: none;">
                                    <label for="angle_4">Angle (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="angle_4"
                                        class="form-control form-control-sm" value="{{ old('angle_4') }}" data-section="4"
                                        name="angle_4">
                                </div>
                                <div class="col-md-3" id="radiusContainer_4" style="display: none;">
                                    <label for="radius_4">Radius (Mtrs)<span class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="radius_4"
                                        class="form-control form-control-sm" data-section="4" name="radius_4"
                                        value="{{ old('radius_4') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End of wing wall section --}}

                    {{-- Start of Head wall section --}}
                    <div class="row form-1-box mt-2 bridge_common_field_container"
                        style="display: none; background-color: #efeeee;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Bridge Head Wall</span>
                        </div>
                        <div class="col-md-3 px-2">
                            <label for="bridge_head_wall">Does the bridge have a Head Wall?<span
                                    class="star"></span></label><br>
                            <input type="radio" id="yes" name="bridge_head_wall" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="bridge_head_wall" value="N" checked>
                            <label for="no">No</label>
                        </div>
                        <div id="bridge_head_wall_container" style="display: none; width: 100%;">
                            <div class="row pb-2" style="">
                                {{-- Head wall: Upstream --}}
                                <div class="col-md-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Head Wall 1
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="head_wall_stream_type_1">Head Wall Stream Type:<span
                                            class="star"></span></label>
                                    <select class="form-control form-control-sm" id="head_wall_stream_type_1"
                                        name="head_wall_stream_type_1">
                                        <option value="U">Upstream</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="head_wall_type_1">Head Wall Type:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="head_wall_type_1"
                                        name="head_wall_type_1">
                                        <option value="">Choose one
                                        </option>
                                        @foreach ($headWalls as $headWall)
                                            <option value="{{ $headWall->head_wall_cd }}">
                                                {{ $headWall->head_wall_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="head_wall_length_1">Length (Mtrs):<span class="star"></span></label>
                                    <input type="number" step="0.001" min="0" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="head_wall_length_1"
                                        class="form-control form-control-sm" name="head_wall_length_1" />
                                </div>

                                <div class="col-md-3">
                                    <label for="head_wall_width_1">Top Width (Mtrs):<span class="star"></span></label>
                                    <input type="number" step="0.001" min="0" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="head_wall_width_1"
                                        class="form-control form-control-sm" name="head_wall_width_1" />
                                </div>
                                <div class="col-md-3">
                                    <label for="head_wall_height_1">Height (Mtrs):<span class="star"></span></label>
                                    <input type="number" step="0.001" min="0" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="head_wall_height_1"
                                        class="form-control form-control-sm" name="head_wall_height_1" />
                                </div>

                                {{-- Head wall: Downstream --}}
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Head Wall 2
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="head_wall_stream_type_2">Head Wall Stream Type:<span
                                            class="star"></span></label>
                                    <select class="form-control form-control-sm" id="head_wall_stream_type_2"
                                        name="head_wall_stream_type_2">
                                        <option value="D">Downstream</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="head_wall_type_2">Head Walll Type:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="head_wall_type_2"
                                        name="head_wall_type_2">
                                        <option value="">Choose one
                                        </option>
                                        @foreach ($headWalls as $headWall)
                                            <option value="{{ $headWall->head_wall_cd }}">
                                                {{ $headWall->head_wall_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="head_wall_length_2">
                                        Length (Mtrs):
                                        <span class="star"></span>
                                    </label>
                                    <input type="number" step="0.001" min="0" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="head_wall_length_2"
                                        class="form-control form-control-sm" name="head_wall_length_2" />
                                </div>
                                <div class="col-md-3">
                                    <label for="head_wall_width_2">
                                        Top Width (Mtrs):
                                        <span class="star"></span>
                                    </label>
                                    <input type="number" step="0.001" min="0" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="head_wall_width_2"
                                        class="form-control form-control-sm" name="head_wall_width_2" />
                                </div>
                                <div class="col-md-3">
                                    <label for="head_wall_height_2">
                                        Height (Mtrs):
                                        <span class="star"></span>
                                    </label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" min="0" id="head_wall_height_2"
                                        class="form-control form-control-sm" name="head_wall_height_2" />
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End of head wall section --}}

                    {{-- Start of retain wall section --}}
                    <div class="row form-1-box mt-2 bridge_common_field_container"
                        style="display: none; background-color: #efeeee;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Bridge Retain Wall</span>
                        </div>
                        <div class="col-md-3 px-2">
                            <label for="bridge_retain_wall">Does the bridge have a Retain Wall? <span
                                    class="star"></span></label><br>
                            <input type="radio" id="yes" name="bridge_retain_wall" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="bridge_retain_wall" value="N" checked>
                            <label for="no">No</label>
                        </div>
                    </div>

                    {{-- Start of retain wall section --}}
                    <div class="row form-1-box">
                        <div class="col-md-12" style="background-color: #efeeee; display:none;" id="bridge_retain_wall">
                            <div class="">
                                <label for="is_same_retain_wall" class="text-danger">
                                    Are all the retain walls the same dimension?
                                    <span class="star"></span></label>
                                <input type="radio" id="yes" name="is_same_retain_wall" value="Y">
                                <label for="yes">Yes</label>
                                <input type="radio" id="no" name="is_same_retain_wall" value="N">
                                <label for="no">No</label>
                            </div>
                        </div>
                        <div id="bridge_retain_wall_fields" style="display: none;" class="col-md-12">
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-md-3">
                                    <label for="retain_wall_type_cd">Retain Wall Type:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="retain_wall_type_cd"
                                        name="retain_wall_type_cd">
                                        <option value="">Choose one</option>
                                        @foreach ($retainWalls as $retainWall)
                                            <option value="{{ $retainWall->retain_type_cd }}">
                                                {{ $retainWall->reatain_wall_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_length">Retain Wall Length (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_length"
                                        class="form-control form-control-sm" name="retain_wall_length"
                                        value="{{ old('retain_wall_length') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_width">Retain Wall Width (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_width"
                                        class="form-control form-control-sm" name="retain_wall_width"
                                        value="{{ old('retain_wall_width') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_heigth">Retain Wall Height (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_heigth"
                                        class="form-control form-control-sm" name="retain_wall_heigth"
                                        value="{{ old('retain_wall_heigth') }}">
                                </div>
                            </div>
                        </div>

                        <div id="bridge_retain_wall_fields_multiple" style="display: none;" class="col-md-12">
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Retain wall 1
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="retain_wall_type_cd_1">Retain Wall Type:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="retain_wall_type_cd_1"
                                        name="retain_wall_type_cd_1">
                                        <option value="">Choose one</option>
                                        @foreach ($retainWalls as $retainWall)
                                            <option value="{{ $retainWall->retain_type_cd }}">
                                                {{ $retainWall->reatain_wall_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_length_1">Retain Wall Length (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_length_1"
                                        class="form-control form-control-sm" name="retain_wall_length_1"
                                        value="{{ old('retain_wall_length_1') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_width_1">Retain Wall Width (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_width_1"
                                        class="form-control form-control-sm" name="retain_wall_width_1"
                                        value="{{ old('retain_wall_width_1') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_heigth_1">Retain Wall Height (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_heigth_1"
                                        class="form-control form-control-sm" name="retain_wall_heigth_1"
                                        value="{{ old('retain_wall_heigth_1') }}">
                                </div>
                            </div>
                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Retain wall 2
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="retain_wall_type_cd_2">Retain Wall Type:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="retain_wall_type_cd_2"
                                        name="retain_wall_type_cd_2">
                                        <option value="">Choose one</option>
                                        @foreach ($retainWalls as $retainWall)
                                            <option value="{{ $retainWall->retain_type_cd }}">
                                                {{ $retainWall->reatain_wall_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_length_2">Retain Wall Length (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_length_2"
                                        class="form-control form-control-sm" name="retain_wall_length_2"
                                        value="{{ old('retain_wall_length_2') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_width_2">Retain Wall Width (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_width_2"
                                        class="form-control form-control-sm" name="retain_wall_width_2"
                                        value="{{ old('retain_wall_width_2') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_heigth_2">Retain Wall Height (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_heigth_2"
                                        class="form-control form-control-sm" name="retain_wall_heigth_2"
                                        value="{{ old('retain_wall_heigth_2') }}">
                                </div>
                            </div>

                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Retain wall 3
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="retain_wall_type_cd_3">Retain Wall Type:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="retain_wall_type_cd_3"
                                        name="retain_wall_type_cd_3">
                                        <option value="">Choose one</option>
                                        @foreach ($retainWalls as $retainWall)
                                            <option value="{{ $retainWall->retain_type_cd }}">
                                                {{ $retainWall->reatain_wall_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_length_3">Retain Wall Length (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_length_3"
                                        class="form-control form-control-sm" name="retain_wall_length_3"
                                        value="{{ old('retain_wall_length_3') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_width_3">Retain Wall Width (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_width_3"
                                        class="form-control form-control-sm" name="retain_wall_width_3"
                                        value="{{ old('retain_wall_width_3') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_heigth_3">Retain Wall Height (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_heigth_3"
                                        class="form-control form-control-sm" name="retain_wall_heigth_3"
                                        value="{{ old('retain_wall_heigth_3') }}">
                                </div>
                            </div>

                            <div class="row form-1-box pb-2" style="background-color: #efeeee;">
                                <div class="col-12">
                                    <span class="text-xs text-danger fw-bold">
                                        <i class="fa fa-caret-right mr-1"></i>
                                        Retain wall 4
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <label for="retain_wall_type_cd_4">Retain Wall Type:<span class="star"></span></label>
                                    <select class="form-control form-control-sm" id="retain_wall_type_cd_4"
                                        name="retain_wall_type_cd_4">
                                        <option value="">Choose one</option>
                                        @foreach ($retainWalls as $retainWall)
                                            <option value="{{ $retainWall->retain_type_cd }}">
                                                {{ $retainWall->reatain_wall_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_length_4">Retain Wall Length (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_length_4"
                                        class="form-control form-control-sm" name="retain_wall_length_4"
                                        value="{{ old('retain_wall_length_4') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="retain_wall_width_4">Retain Wall Width (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_width_4"
                                        class="form-control form-control-sm" name="retain_wall_width_4"
                                        value="{{ old('retain_wall_width_4') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="retain_wall_heigth_4">Retain Wall Height (Mtrs):<span
                                            class="star"></span></label>
                                    <input type="number" step="0.001" placeholder="0.000"
                                        oninput="restrictDecimalPoints(event)" id="retain_wall_heigth_4"
                                        class="form-control form-control-sm" name="retain_wall_heigth_4"
                                        value="{{ old('retain_wall_heigth_4') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End of retain wall section --}}

                    {{-- Safety apron section --}}
                    <div class="row form-1-box mt-2 bridge_common_field_container"
                        style="display: none; background-color: #efeeee;">
                        <div style="border-bottom: 1px solid rgb(199, 199, 199);" class="py-1 mb-1">
                            <span class="text-bold text-sm">Bridge Safety Apron</span>
                        </div>
                        <div class="col-md-3 px-2">
                            <label for="has_safety_apron">Does the bridge have a Safety Apron?<span
                                    class="star"></span></label><br>
                            <input type="radio" id="yes" name="has_safety_apron" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="has_safety_apron" value="N" checked>
                            <label for="no">No</label>
                        </div>
                    </div>

                    <div class="row form-1-box pb-2" id="apron_container" style="background-color: #efeeee; display: none;">
                        <div class="col-md-3">
                            <label for="apron_width">Apron Width (Mtrs):<span class="star"></span></label>
                            <input type="number" min="0" step="0.001" placeholder="0.000"
                                oninput="restrictDecimalPoints(event)" id="apron_width" class="form-control form-control-sm"
                                name="apron_width" value="{{ old('apron_width') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="safety_apron_type">Apron Type:<span class="star"></span></label>
                            <select class="form-control form-control-sm" id="safety_apron_type" name="safety_apron_type">
                                <option value="">Choose one</option>
                                @foreach ($saftyApronTypes as $saftyApronType)
                                    <option value="{{ $saftyApronType->apron_type_cd }}">
                                        {{ $saftyApronType->apron_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3" id="hand_rail_container" style="display: none;">
                            <label for="safety_apron_hand_rail_type">Hand Railing Type:<span class="star"></span></label>
                            <select class="form-control form-control-sm" id="safety_apron_hand_rail_type"
                                name="safety_apron_hand_rail_type">
                                <option value="">Choose one</option>
                                @foreach ($handRailTypes as $handRailType)
                                    <option value="{{ $handRailType->hand_rail_type_cd }}">
                                        {{ $handRailType->hand_rail_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- End of safety apron --}}
                    <div class="row form-1-box bridge_common_field_container" style="display: none;">
                        <div class="col-md-12">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control form-control-sm text-sm" id="remarks" name="remarks" rows="2"
                                placeholder="Write bridge remarks...">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                    {{-- Upload Image --}}
                    <x-asset-resources.asset-image />
                    {{-- End of upload image --}}
                    {{-- upload documents --}}
                    <x-asset-resources.asset-document />
                    {{-- End of upload documents --}}
                </fieldset>
                <!--modified by Pulak-- 29-04-2026-->
                <div class="text-end">
                    <button type="submit" id="saveBtn" class="btn btn-success btn-sm rounded-0 mt-2" disabled>
                        <i class="fa fa-save"></i>
                        <span id="saveBtnLabel">Save</span>
                    </button>
                    <button type="button" id="cancelEditBtn" class="btn btn-warning btn-sm rounded-0 mt-2"
                        style="display:none;" onclick="resetToAddMode()">
                        <i class="fa fa-times"></i><a class="text-white"
                            href="{{ route('road.cd-bridge-details', $road_system_id) }}"> Cancel Edit</a>
                    </button>
                    <button type="reset" id="reset_btn" class="btn btn-info btn-sm rounded-0 mt-2">
                        <i class="fa fa-undo" aria-hidden="true"></i>
                        Reset
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm rounded-0 mt-2">
                        <i class="fa fa-backward"></i>
                        <a class="text-white" href="{{ route('manageRoad') }}">Cancel</a>
                    </button>
                    <!--modification end by Pulak-- 29-04-2026-->
                </div>
            </form>
        </div>

        <!-- table content -->
        <div class="container-fluid mainBody">
            <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF DRAFT BRIDGE DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border py-2">
                <div class="d-flex text-sm justify-content-end mb-2">
                    <button id="freezeBtn" class="btn btn-sm btn-info rounded-1 text-bold">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Send for finalization
                    </button>
                </div>
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="bridge_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center" style="min-width: 4rem;">Sl No.</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Code</th>
                        <!-- Saiful # 29-04-2026 # Start -->
                        <th class="text-center" style="min-width: 8rem;">Project Code</th>
                        <!-- Saiful # 29-04-2026 # End -->
                        <th class="text-center" style="min-width: 8rem;">Bridge Name</th>
                        <th class="text-center" style="min-width: 8rem;">Chainage (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Type</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Width (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">River Name</th>
                        <th class="text-center" style="min-width: 8rem;">Construction Type</th>
                        <th class="text-center" style="min-width: 8rem;">No. of Span</th>
                        <th class="text-center" style="min-width: 8rem;">Kerb width (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Kerb Height (Mtrs)</th>
                        <th class="text-center" style="min-width: 8rem;">Load Capacity</th>
                        <th class="text-center" style="min-width: 8rem;">No. of Pears</th>
                        <th class="text-center" style="min-width: 8rem;">Super Structure Type</th>
                        <th class="text-center" style="min-width: 8rem;">Handrail Type</th>
                        <th class="text-center" style="min-width: 8rem;">Deck Type</th>
                        <th class="text-center" style="min-width: 8rem;">Expansion Joints</th>
                        <th class="text-center" style="min-width: 8rem;">Deck Level</th>
                        <th class="text-center" style="min-width: 8rem;">Carriage Width</th>
                        <th class="text-center" style="min-width: 8rem;">Guard Stone</th>
                        <th class="text-center" style="min-width: 8rem;">Discharge</th>
                        <th class="text-center" style="min-width: 8rem;">Source Depth</th>
                        <th class="text-center" style="min-width: 8rem;">Lowest Water level</th>
                        <th class="text-center" style="min-width: 8rem;">Highest Flood Level</th>
                        <th class="text-center" style="min-width: 4rem;">FRL</th>
                        <th class="text-center" style="min-width: 8rem;">Rehabilitation Year</th>
                        <th class="text-center" style="min-width: 8rem;">Construction Year</th>
                        <th class="text-center" style="min-width: 8rem;">Last Inspection</th>
                        <th class="text-center" style="min-width: 8rem;">Bridge Condition</th>
                        <th class="text-center" style="min-width: 8rem;">Expectation Date</th>
                        <th class="text-center" style="min-width: 4rem;">Footpath</th>
                        <th class="text-center" style="min-width: 8rem;">Safety Apron</th>
                        <th class="text-center" style="min-width: 8rem;">Apron type</th>
                        <th class="text-center" style="min-width: 8rem;">Apron width</th>
                        <th class="text-center" style="min-width: 8rem;">Rejection Reason</th>
                        <th class="text-center" style="min-width: 8rem;">Remarks</th>
                        <th class="text-center" style="min-width: 4rem;">Wing Wall</th>
                        <th class="text-center" style="min-width: 4rem;">Head Wall</th>
                        <th class="text-center" style="min-width: 4rem;">Abutment</th>
                        <th class="text-center" style="min-width: 4rem;">Retain Wall</th>
                        <th class="text-center" style="min-width: 6rem;">Span Details</th>
                        <th class="text-center" style="min-width: 4rem;">Pier Details</th>
                        <th class="text-center" style="min-width: 4rem;">Edit</th>
                        <th style="min-width: 3rem;" class="text-center">Select</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($cd_bridge_details as $item)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">
                                    {{ $item->rd_bridge_cd }}
                                </td>
                                <!-- Saifu # 29-04-2026 # Start -->
                                <td class="text-center">
                                    {{ $item->project_cd }}
                                </td>
                                <!-- Saiful # 29-04-2026 # End -->
                                <td class="text-center">
                                    {{ $item->bridge_name }}
                                </td>
                                <td class="text-center">
                                    {{ $item->chainage }}
                                </td>
                                <td class="text-center">
                                    {{ $item->bridge_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->bridge_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->river_name }}
                                </td>
                                <td class="text-center">
                                    {{ $item->construction_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_span }}
                                </td>
                                <td class="text-center">
                                    {{ $item->kerb_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->kerb_height }}
                                </td>
                                <td class="text-center">
                                    {{ $item->load_capacity }}
                                </td>
                                <td class="text-center">
                                    {{ $item->no_of_piers }}
                                </td>
                                <td class="text-center">
                                    {{ $item->st_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->hand_rail_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->deck_type_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->expn_joint_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->deck_level }}
                                </td>
                                <td class="text-center">
                                    {{ $item->carriage_width }}
                                </td>
                                <td class="text-center">
                                    {{ $item->guard_stone }}
                                </td>
                                <td class="text-center">
                                    {{ $item->source_depth }}
                                </td>
                                <td class="text-center">
                                    {{ $item->discharge }}
                                </td>
                                <td class="text-center">
                                    {{ $item->lowest_water_level }}
                                </td>
                                <td class="text-center">
                                    {{ $item->highest_flood_level }}
                                </td>
                                <td class="text-center">
                                    {{ $item->rfl }}
                                </td>
                                <td class="text-center">
                                    {{ $item->year_of_rehabilitation }}
                                </td>
                                <td class="text-center">
                                    {{ $item->year_of_construction }}
                                </td>
                                <td class="text-center">
                                    {{ $item->date_of_last_inspection }}
                                </td>
                                <td class="text-center">
                                    {{ $item->rd_condition_descr }}
                                </td>
                                <td class="text-center">
                                    {{ $item->next_schedule_inspection_date }}
                                </td>
                                <td class="text-center">
                                    @if ($item->footh_path == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->has_safety_apron == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $item->safety_apron_type ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->apron_width ?? 'NA' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->reason_of_rejection ?? 'No Remarks' }}
                                </td>
                                <td class="text-center">
                                    {{ $item->bridge_remark }}
                                </td>
                                <td class="text-center">
                                    @if ($item->has_wing_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#wingWallModal{{ $item->rd_bridge_cd }}"
                                            onclick="getWingWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_head_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#headWallModal{{ $item->rd_bridge_cd }}"
                                            onclick="getHeadWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_abutment_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#abutmentWallModal{{ $item->rd_bridge_cd }}"
                                            onclick="getAbutmentWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_retain_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#retainWallModal{{ $item->rd_bridge_cd }}"
                                            onclick="getRetainWallValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->has_retain_wall == 'Y')
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#spanDetailsModal{{ $item->rd_bridge_cd }}"
                                            onclick="getSpanValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <!-- new code start by Pulak -->
                                    @if ($item->no_of_piers > 0)
                                        <!-- new code end by Pulak -->
                                        <button class="text-primary border-0 bg-transparent outline-0" data-toggle="modal"
                                            data-target="#pierDetailsModal{{ $item->rd_bridge_cd }}"
                                            onclick="getPierValue('{{ $item->rd_bridge_cd }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span class="text-danger text-bold">NA</span>
                                    @endif
                                </td>

                                <!--modified by Pulak-- 29-04-2026-->
                                <td class="text-center">
                                    <button type="button" class="text-primary border-0 bg-transparent"
                                        onclick="redirectToIndex('{{ $item->rd_bridge_cd }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <!--modified by Pulak-- 29-04-2026-->
                                <td class="text-center">
                                    <input type="checkbox" class="selected-asset" data-cdwork-cd="{{ $item->rd_bridge_cd }}" />
                                </td>
                                <!--modification end by Pulak-- 29-04-2026-->
                            </tr>
                            <?php    $i++;?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <x-success-modal />
    <x-warning-modal />
    <!-- The Modal for wing wall -->
    <div id="wingWallModal" class="wingWallModal">
        <!-- Modal content -->
        <div class="wingWallModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Wing Wall Details
                </p>
                <div>
                    <span class="closeWingWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="wingWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for head wall-->
    <div id="headWallModal" class="headWallModal">
        <!-- Modal content -->
        <div class="headWallModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Head wall Details
                </p>
                <div>
                    <span class="closeHeadWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="headWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for Abutment wall-->
    <div id="abutmentWallModal" class="abutmentWallModal">
        <!-- Modal content -->
        <div class="abutmentWallModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Abutment Wall Details
                </p>
                <div>
                    <span class="closeAbutmentWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="abutmentWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for Retain wall-->
    <div id="retainWallModal" class="retainWallModal">
        <!-- Modal content -->
        <div class="retainWallModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Retain Wall Details
                </p>
                <div>
                    <span class="closeRetainWall">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="retainWallValContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for Span Details-->
    <div id="spanDetailsModal" class="spanDetailsModal">
        <!-- Modal content -->
        <div class="spanDetailsModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    Bridge Span Details
                </p>
                <div>
                    <span class="closeSpanModal">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="spanDetailsContainer">
            </div>
        </div>
    </div>

    <!-- The Modal for Piers Details-->
    <div id="pierDetailsModal" class="pierDetailsModal">
        <!-- Modal content -->
        <div class="pierDetailsModalContent">
            <div class="d-flex flex-wrap justify-content-between align-item-center border-bottom">
                <p class="text-md text-bold text-primary">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    List of Pier Details
                </p>
                <div>
                    <span class="closePierModal">&times;</span>
                </div>
            </div>
            <div class="row text-xs pt-2" id="pierDetailsContainer">
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/road/bridge/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/selectOptionStyleSheet.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('js/road/bridge/script.js') }}" defer></script>
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/common/restrict_decimal_points.js') }}" defer></script>
    <script src="{{ asset('js/common/asset_coordinates_script.js') }}" defer></script>
    <script src="{{ asset('js/road/assets/removeSelectedFile/script.js') }}" defer></script>
    <script src="{{ asset('js/road/assets/showBridgeAssetModals/script.js') }}" defer></script>
    <script src="{{ asset('js/road/bridge/editBridge.js') }}" defer></script>
    {{-- modified by Pulak-- 27-04-2026 --}}
    <script>
        const STORE_ROUTE = "{{ route('bridge.store') }}";
        const UPDATE_ROUTE_BASE = "{{ url('/asset-management/update-bridge-details') }}";
        function redirectToIndex(bridgeId) {
            window.location.href = `/asset-management/edit-cd-bridge-details/${bridgeId}`;
        }
        const bridgeId = @json($bridge_id ?? null);
        console.log('Bridge ID from session:', bridgeId);
    </script>
    {{-- end of modified code by Pulak-- 27-04-2026 --}}
@endpush