@extends('layouts.app')
@section('content')
        <div class="content-header mb-4 view-roads-page">
            <div class="container-fluid">
                <ol class="breadcrumb float-sm-left text-sm">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item"> Roads & Bridges</li>
                </ol>
            </div>
        </div>
        <div id="loader">
            <img src="{{ asset('images/loader2.gif') }}" alt="Loading..." width="60px;">
        </div>
        <section class="content view-roads-page">
            <div class="container-fluid mainBody py-2">
                <div class="road-page-hero">
                    <div>
                        <span class="road-page-eyebrow">Roads & Bridges Wing</span>
                        <h1>Road Asset Registry</h1>
                        <p>Filter, review, summarize, and inspect road assets across Nagaland PWD jurisdictions.</p>
                    </div>
                    <span class="road-page-chip"><i class="fas fa-road"></i> State Roads</span>
                </div>

                <div class="d-flex justify-content-start flex-wrap text-xs road-wing-tabs">
                    <div class="text-bold mr-1">
                        <span class="py-1">
                            <a id="cd_work_link" class="wing_btn px-2 btn btn-sm text-light rounded-0"
                                style="width: 8rem; border-top: 1px solid #417dbe; border-left: 1px solid #417dbe; border-right: 1px solid #417dbe; background-color: #417dbe;"
                                href={{ route('viewRoadWings') }}>
                                Road
                            </a>
                        </span>
                    </div>
                    <div class="text-bold mr-1">
                        <span class="py-1">
                            <a id="cd_work_link"
                                class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                                style="width: 8rem;" href={{ route('viewCDWorks') }}>
                                CD Works
                            </a>
                        </span>
                    </div>
                    <div class="text-bold mr-1">
                        <span class="py-1">
                            <a id="cd_work_link"
                                class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                                style="width: 8rem;" href={{ route('viewBridge') }}>
                                Bridge
                            </a>
                        </span>
                    </div>
                    <div class="text-bold mr-1">
                        <span class="py-1">
                            <a id="cd_work_link"
                                class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                                style="width: 8rem;" href={{ route('viewPCI') }}>
                                PCI
                            </a>
                        </span>
                    </div>
                    <div class="text-bold mr-1">
                        <span class="py-1">
                            <a class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                                style="width: 8rem;" href={{ route('viewProtectionWall') }}>
                                Protection Wall
                            </a>
                        </span>
                    </div>
                    <div class="text-bold mr-1">
                        <span class="py-1">
                            <a id="cd_work_link"
                                class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                                style="width: 8rem;" href={{ route('viewSurfaceType') }}>
                                Surface Types
                            </a>
                        </span>
                    </div>

                    <div class="text-bold mr-1">
                        <span class="py-1">
                            <a id="cd_work_link"
                                class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                                style="width: 8rem;" href={{ route('viewHabitation') }}>
                                Habitation
                            </a>
                        </span>
                    </div>
                </div>

                <h6 class="p-2 border border-primary text-light bg-primary">
                    <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                        LIST OF ROADS UNDER NAGALAND P W D.
                    </span>
                </h6>
                <div class="border border-primary px-1 rounded loaderContainer road-list-card">
                    {{-- Road and Bridges --}}
                    <div class="row justify-content-between align-item-center road-filter-card">
                        <form id="wing_road" class="mt-2 mb-1">
                            @csrf
                            <div class="row justify-content-center align-items-start">
                                <div class="col-sm-12 col-md-11 row">
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="zone">Zone</label>
                                        </div>
                                        <select style="width: 100%;" class="custom_select text-uppercase text-xs"
                                            name="zone" id="zone">
                                            <option value="null">All Zones</option>
                                            @foreach ($zoneDetails as $zoneDetail)
                                                <option value={{ $zoneDetail->zone_cd }}>
                                                    {{ $zoneDetail->zone_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="circle">Circle</label>
                                        </div>
                                        <select style="width:100%;" class="custom_select text-uppercase text-xs"
                                            name="circle" id="circle">
                                            <option value="null">All Circles</option>
                                            @foreach ($circleDetails as $circleDetail)
                                                <option value={{ $circleDetail->circle_cd }}>
                                                    {{ $circleDetail->circle_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="division">Division</label>
                                        </div>
                                        <select style="width:100%;" class="custom_select text-uppercase text-xs"
                                            name="division" id="division">
                                            <option value="null">All Divisions</option>
                                            @foreach ($divisionDetails as $divisionDetail)
                                                <option value={{ $divisionDetail->division_cd }}>
                                                    {{ $divisionDetail->division_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="subDivision">SubDivision </label>
                                        </div>
                                        <select style="width:100%;" class="custom_select text-uppercase text-xs"
                                            name="subDivision" id="subDivision">
                                            <option value="null">All SubDivisions</option>
                                            @foreach ($subDivisionDetails as $subDivisionDetail)
                                                <option value={{ $subDivisionDetail->sub_div_cd }}>
                                                    {{ $subDivisionDetail->sub_div_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="type">Type</label>
                                        </div>
                                        <select style="width:100%;" class="custom_select text-uppercase text-xs"
                                            name="type" id="type">
                                            <option value="null">All Types</option>
                                            @foreach ($roadTypes as $roadType)
                                                <option value={{ $roadType->rd_type_cd }}>
                                                    {{ $roadType->rd_type_descr }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="category">Category</label>
                                        </div>
                                        <select style="width:100%;" class="custom_select text-uppercase text-xs"
                                            name="category" id="category">
                                            <option value="null">All Categories</option>
                                            @foreach ($roadCategories as $roadCategorie)
                                                @if ($roadCategorie->rd_catg_cd != 'NH')
                                                    <option value={{ $roadCategorie->rd_catg_cd }}>
                                                        {{ $roadCategorie->rd_catg_descr }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-1 d-flex justify-content-end">
                                    <button type="submit" class="text-xs btn btn-xs btn-outline-primary">
                                        View
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="road_container" class="container-fluid mainBody border mb-1 road-results-card" style="border-radius: .3rem;">
                        <div class="d-flex justify-content-end py-1">
                            <i class="fas fa-caret-left" id="toggleBtn"></i>
                        </div>
                        <div class="container-fluid mt-3" id="tableContent" style="display: none;">
                            <div>
                                <input type="hidden" id="hdnGetAllStatesRoadJsonDataUrl"
                                    name="hdnGetAllStatesRoadJsonDataUrl"
                                    value="{{ config('customconfigpath.GET_ALL_STATES_GEOJSON_DATA_OF_ROAD_API') }}" />
                                <input type="hidden" id="hdnGetDivisionRoadJsonDataUrl"
                                    name="hdnGetDivisionRoadJsonDataUrl"
                                    value="{{ config('customconfigpath.GET_DIVISIONS_GEOJSON_DATA_OF_ROAD_API') }}" />
                                <input type="hidden" id="hdnGetSingleRoadJsonDataUrl" name="hdnGetSingleRoadJsonDataUrl"
                                    value="{{ config('customconfigpath.GET_GEOJSON_DATA_OF_SINGLE_ROAD_API') }}" />
                                <h6 class="text-bold text-lg road-summary-title">Road Summary</h6>
                                <div class="row border-bottom border-top text-sm road-summary-grid">
                                    <div class="col-12 col-md-6">
                                        <ul>
                                            <li>
                                                Total Road Count: <span class="road_count text-bold"></span>
                                            </li>
                                            <li>
                                                Zone: <span class="show_zone text-bold">All Zones</span>
                                            </li>
                                            <li>
                                                Circle: <span class="show_circle text-bold">All Circles</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <ul>
                                            <li>
                                                Division: <span class="show_division text-bold">All Divisions</span>
                                            </li>
                                            <li>
                                                Sub_division: <span class="show_subdiv text-bold">All Sub Divisions</span>
                                            </li>
                                            <li>
                                                Total Road Length (KM): <span class="tot_rd_len text-bold">0</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-1 justify-content-end align-item-center">
                                <div class="col-sm-6 col-md-3 d-flex justify-content-end my-1">
                                    <span class="mis-btn-road"></span>
                                </div>
                            </div>
                            <table class="table-responsive text-xs table table-bordered table-striped user_list road-registry-table"
                                id="road_details_table">
                                <thead class="theader text-xs text-white" style="background-color:#417dbe">
                                    <th class="text-center" style="min-width: 4rem;">Sl. No</th>
                                    <th class="text-center" style="min-width: 5rem;">Road ID</th>
                                    <th class="text-center" style="min-width: 10rem;">Road Name</th>
                                    <th class="text-center" style="min-width: 5rem;">Length (km)</th>
                                    <th class="text-center" style="min-width: 8rem;">Road Category</th>
                                    <th class="text-center" style="min-width: 8rem;">Road Type</th>
                                    <th class="text-center" style="min-width: 5.5rem;">Action</th>
                                </thead>

                                <tbody class="text-center">
                                    {{-- dynamic table body --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <label class="modal-title" id="mapTitle">Nagaland PWD Road Map, </label>
                                    <label id="lblRoadInfo" name = "lblRoadInfo"></label>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="row justify-content-center align-item-center">
                                    <div class="col-md-10 mb-1">
                                        <div class="modal-body modal-dialog-centered" id='map'
                                            style='width: 100%; height: 500px;'>
                                            <form action="#" id="frm_map_modal">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-1">
                                        <div id="road-sum-info" class="text-xs"
                                            style="display: flex; justify-content: space-around">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Show road abstract modal --}}
                    <x-reports.roads.abstract-modal />
                    <x-reports.roads.culvert-abstract-modal />
                </div>
            </div>
        </section>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader/style.css') }}">
    <style>
        .view-roads-page,
        .view-roads-page * {
            font-family: var(--oamis-font, "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif) !important;
        }

        .view-roads-page .mainBody {
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .road-page-hero {
            align-items: center;
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 24px;
            box-shadow: var(--oamis-shadow);
            color: var(--oamis-ink);
            display: flex;
            gap: 16px;
            justify-content: space-between;
            margin-bottom: 16px;
            padding: 22px 24px;
        }

        .road-page-eyebrow {
            color: var(--oamis-primary);
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .1em;
            margin-bottom: 7px;
            text-transform: uppercase;
        }

        .road-page-hero h1 {
            color: var(--oamis-ink);
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -.03em;
            margin: 0;
        }

        .road-page-hero p {
            color: var(--oamis-muted);
            font-size: 14px;
            margin: 7px 0 0;
        }

        .road-page-chip {
            align-items: center;
            background: rgba(11, 107, 74, .1);
            border: 1px solid rgba(11, 107, 74, .2);
            border-radius: 999px;
            color: var(--oamis-primary);
            display: inline-flex;
            font-size: 13px;
            font-weight: 700;
            gap: 8px;
            padding: 9px 13px;
            white-space: nowrap;
        }

        .road-wing-tabs {
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            box-shadow: var(--oamis-shadow);
            gap: 8px;
            margin-bottom: 16px;
            padding: 10px;
        }

        .road-wing-tabs .text-bold,
        .road-wing-tabs span {
            margin: 0 !important;
            padding: 0 !important;
        }

        .road-wing-tabs .wing_btn {
            align-items: center;
            background: var(--oamis-soft) !important;
            border: 1px solid var(--oamis-border) !important;
            border-radius: 999px !important;
            color: var(--oamis-muted) !important;
            display: inline-flex;
            font-size: 13px !important;
            font-weight: 700 !important;
            justify-content: center;
            min-height: 36px;
            min-width: 120px;
            padding: 8px 12px !important;
            width: auto !important;
        }

        .road-wing-tabs .wing_btn.text-light,
        .road-wing-tabs .wing_btn[style*="background-color"] {
            background: #0b6b4a !important;
            border-color: #0b6b4a !important;
            color: #fff !important;
        }

        a.wing_btn:hover {
            background-color: rgba(11, 107, 74, .1) !important;
            color: var(--oamis-primary) !important;
            transform: translateY(-1px);
        }

        .road-list-card {
            background: var(--oamis-card) !important;
            border: 1px solid var(--oamis-border) !important;
            border-radius: 22px !important;
            box-shadow: var(--oamis-shadow);
            overflow: hidden;
            padding: 0 !important;
        }

        .road-filter-card {
            background: var(--oamis-card);
            border-bottom: 1px solid var(--oamis-border);
            margin: 0 !important;
            padding: 16px;
        }

        #wing_road {
            width: 100%;
        }

        #wing_road .row {
            row-gap: 12px;
        }

        #wing_road .d-flex.align-items-center {
            align-items: flex-start !important;
            flex-direction: column;
            gap: 5px;
        }

        #wing_road label {
            color: var(--oamis-muted);
            font-size: 12px !important;
            font-weight: 700;
            letter-spacing: .04em;
            margin: 0;
            text-transform: uppercase;
        }

        #wing_road select {
            min-width: 100%;
        }

        .road-results-card {
            background: var(--oamis-card) !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            margin: 0 !important;
            padding: 0 16px 16px !important;
        }

        #road_container > .d-flex {
            color: var(--oamis-muted);
            padding: 12px 0 !important;
        }

        #toggleBtn {
            align-items: center;
            background: var(--oamis-soft);
            border: 1px solid var(--oamis-border);
            border-radius: 999px;
            cursor: pointer;
            display: inline-flex;
            height: 32px;
            justify-content: center;
            transition: background-color .2s ease, color .2s ease, transform .2s ease;
            width: 32px;
        }

        #toggleBtn:hover {
            color: var(--oamis-primary);
            transform: translateY(-1px);
        }

        #tableContent {
            margin-top: 0 !important;
            padding: 0 !important;
        }

        .road-summary-title {
            color: var(--oamis-ink);
            font-size: 17px !important;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .road-summary-grid {
            background: var(--oamis-soft);
            border: 1px solid var(--oamis-border) !important;
            border-radius: 18px;
            color: var(--oamis-ink);
            margin: 0 !important;
            padding: 13px 8px 8px;
        }

        .road-summary-grid ul {
            margin-bottom: 0;
            padding-left: 18px;
        }

        .road-summary-grid li {
            color: var(--oamis-muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .road-summary-grid span {
            color: var(--oamis-ink);
        }

        .road-registry-table {
            border-collapse: separate !important;
            border-spacing: 0;
            display: table !important;
            margin-bottom: 0 !important;
            min-width: 960px;
            width: 100% !important;
        }

        .road-registry-table tbody td {
            font-size: 13px !important;
            padding: 12px !important;
            vertical-align: middle !important;
        }

        .road-registry-table tbody tr:hover td {
            background: rgba(11, 107, 74, .06) !important;
        }

        #mapModal .modal-dialog {
            max-width: min(1180px, calc(100vw - 28px));
        }

        #mapModal #map {
            border: 1px solid var(--oamis-border);
            border-radius: 20px;
            overflow: hidden;
        }

        #road-sum-info {
            background: var(--oamis-soft);
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            color: var(--oamis-ink);
            padding: 12px;
        }

        html[data-theme="dark"] .road-page-chip {
            background: rgba(52, 211, 153, .13);
            border-color: rgba(52, 211, 153, .28);
            color: #6ee7b7;
        }

        html[data-theme="dark"] .road-summary-grid,
        html[data-theme="dark"] #road-sum-info {
            background: var(--oamis-soft);
        }

        @media (max-width: 767px) {
            .road-page-hero {
                align-items: flex-start;
                flex-direction: column;
                padding: 18px;
            }

            .road-wing-tabs {
                overflow-x: auto;
                flex-wrap: nowrap !important;
            }

            .road-results-card {
                overflow-x: auto;
                padding: 0 12px 12px !important;
            }
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/wings/roads/script.js') }}" defer></script>
    <script>
        const zoneDetails = @json($zoneDetails);
        const circleDetails = @json($circleDetails);
        const divisionDetails = @json($divisionDetails);
        const subDivisionDetails = @json($subDivisionDetails);
        $("#zone").on("change", function() {
            const selectedZone = $("#zone").val();
            // // get the zone name and show in the report
            // if (selectedZone == 'null') {
            //     $('.show_zone').html('All Zones');
            // } else {
            //     $.each(zoneDetails, function(index, value) {
            //         if ((value.zone_cd) == selectedZone) {
            //             $('.show_zone').html(value.zone_name);
            //         }
            //     });
            // }

            $("#circle").empty().append('<option value="null">ALL CIRCLES</option>');
            $("#division").empty().append('<option value="null">ALL DIVISIONS</option>');
            $("#subDivision").empty().append('<option value="null">ALL SUBDIVISIONS</option>');
            if (selectedZone === 'null') {
                $.each(circleDetails, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#circle').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                    }
                });
                $.each(divisionDetails, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#division').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(subDivisionDetails, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#subDivision').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });
            } else {
                $.each(circleDetails, function(index, value) {
                    if ((value.zone_cd) === selectedZone)
                        $('#circle').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                });
            }
        })
        $("#circle").on("change", function() {
            const selectedCircle = $("#circle").val();

            // get the circle name and show in the report
            // if (selectedCircle == 'null') {
            //     $('.show_circle').html('All Circles');
            // } else {
            //     $.each(circleDetails, function(index, value) {
            //         if ((value.circle_cd) == selectedCircle) {
            //             $('.show_circle').html(value.circle_name);
            //         }
            //     });
            // }

            $("#division").empty().append('<option value="null">ALL DIVISIONS</option>');
            $("#subDivision").empty().append('<option value="null">ALL SUBDIVISIONS</option>');
            console.log(selectedCircle);
            if (selectedCircle === 'null') {
                const selectedZone = $("#zone").val();
                console.log("selected zone: ", selectedZone);
                $.each(divisionDetails, function(index, value) {
                    if ((value.dept_cd == 14) && (value.zone_cd == selectedZone)) {
                        $('#division').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(subDivisionDetails, function(index, value) {
                    if ((value.dept_cd == 14) && (value.zone_cd == selectedZone)) {
                        $('#subDivision').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });
            } else {
                $.each(divisionDetails, function(index, value) {
                    if ((value.circle_cd) === selectedCircle)
                        $('#division').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                });
            }
        })

        // $("#subDivision").on("change", function() {
        //     const selectedSubDivision = $("#subDivision").val();

        //     // get the circle name and show in the report
        //     if (selectedSubDivision == 'null') {
        //         $('.show_subdiv').html('All Sub Divisions');
        //     } else {
        //         $.each(subDivisionDetails, function(index, value) {
        //             if ((value.sub_div_cd) == selectedSubDivision) {
        //                 $('.show_subdiv').html(value.sub_div_name);
        //             }
        //         });
        //     }
        // })

        $("#division").on("change", function() {
            const selectedDivision = $("#division").val();

            // // get the circle name and show in the report
            // if (selectedDivision == 'null') {
            //     $('.show_division').html('All Divisions');
            // } else {
            //     $.each(divisionDetails, function(index, value) {
            //         if ((value.division_cd) == selectedDivision) {
            //             $('.show_division').html(value.division_name);
            //         }
            //     });
            // }
            $("#subDivision").empty().append('<option value="null">ALL SUBDIVISIONS</option>');
            console.log(selectedDivision);
            if (selectedDivision === 'null') {
                const selectedZone = $("#zone").val();
                const selectedCircle = $("#circle").val();
                // if no circle is selected
                if (selectedCircle === 'null') {
                    $.each(subDivisionDetails, function(index, value) {
                        if ((value.dept_cd == 14) && (value.zone_cd == selectedZone)) {
                            $('#subDivision').append('<option value="' + value.sub_div_cd + '">' + value
                                .sub_div_name +
                                '</option>');
                        }
                    });
                } else {
                    $.each(subDivisionDetails, function(index, value) {
                        if ((value.dept_cd == 14) && (value.zone_cd == selectedZone) && (value.circle_cd ==
                                selectedCircle)) {
                            $('#subDivision').append('<option value="' + value.sub_div_cd + '">' + value
                                .sub_div_name +
                                '</option>');
                        }
                    });
                }
            } else {
                $.each(subDivisionDetails, function(index, value) {
                    if ((value.div_cd) == (selectedDivision))
                        $('#subDivision').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                });
            }
        })

        $(document).ready(function() {
            $("#toggleBtn").click(function() {
                $("#tableContent").slideToggle('slow');
                $(this).toggleClass("fa-caret-left fa-caret-down");
            });
        });
    </script>
@endpush
