@extends('layouts.app')
@section('content')
    <div style="display: none;" id="selected_road_id"></div>
    <div class="">
        <input type="hidden" id="hdnGetAllStatesRoadJsonDataUrl" name="hdnGetAllStatesRoadJsonDataUrl"
            value="{{ config('customconfigpath.GET_ALL_STATES_GEOJSON_DATA_OF_ROAD_API') }}" />
        <input type="hidden" id="hdnGetDivisionRoadJsonDataUrl" name="hdnGetDivisionRoadJsonDataUrl"
            value="{{ config('customconfigpath.GET_DIVISIONS_GEOJSON_DATA_OF_ROAD_API') }}" />
        <input type="hidden" id="hdnGetSingleRoadJsonDataUrl" name="hdnGetSingleRoadJsonDataUrl"
            value="{{ config('customconfigpath.GET_GEOJSON_DATA_OF_SINGLE_ROAD_API') }}" />
        <div class="mx-1 border bg-light shadow row">
            <div class="col-12 p-1">
                <div class="border p-1 d-flex flex-wrap">
                    <div class="col-sm-12 col-md-4">
                        <h6 class="py-1 border-bottom"><i class="fa fa-bars mr-1 text-sm"></i>Division Details
                        </h6>
                        <select id="division_list" style="width: 100%;" class="text-sm" name="division_list">
                            <option value=''>Select Division</option>
                            @foreach ($division_dtls as $item)
                                @if ($item->dept_cd == '3')
                                    <option style="font-size: 0.2rem;" class="text-xs" value="{{ $item->division_cd }}">
                                        {{ $item->division_name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <h6 class="py-1 border-bottom"><i class="fa fa-bars mr-1 text-xs"></i>Road Details</h6>
                        <select id="road_list" style="width: 100%;" class="text-xs" name="road_list">
                            <option value=''>Select Road</option>
                            @foreach ($roadDetails as $roadDetails)
                                <option value="{{ $roadDetails->rd_system_id }}">
                                    {{ $roadDetails->rd_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <h6 class="py-1 border-bottom"><i class="fa fa-bars mr-1 text-xs"></i>Asset Details</h6>
                        <div class="ps-2 d-flex flex-wrap gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="options" id="radio1"
                                    value="culvert">
                                <label class="form-check-label" for="radio1">Culvert</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="options" id="radio2" value="bridge">
                                <label class="form-check-label" for="radio2">Bridge</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: none;" id="selectedRoadName" data-id=""></div>

            <div class="col-sm-12 col-md-9 row">
                <div class="border my-1 py-1 col-12">
                    <div id="world-map"></div>
                    <div class="" id="map"></div>
                    <div id="loader" class="loader">Please Wait Till Road Data Loading...</div>
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="modal-title text-md fw-bold text-uppercase"></p>
                                    <button class="closeRdInfoModal btn btn-xs">
                                        <i class="fa fa-close"></i>
                                    </button>
                                </div>
                                <div id="modal-body" class="modal-body text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border my-1 py-2 col-12" id="sub-asset-sum-container">
                    <h6 class="py-1 border-bottom"><i class="fa fa-bars mr-1 text-xs"></i>Other Details</h6>
                    <h6 style="text-xs" id="sub_asset_header"></h6>
                    <div id="status_message"></div>
                </div>
            </div>
            <div class="col-sm-12 p-1 col-md-3">
                <div class="p-1">
                    <h6 class="py-1 border-bottom"><i class="fa fa-bars mr-1 text-xs"></i>Road Summary</h6>
                    {{-- <div id="road-sum-info" class="text-xs"></div> --}}
                    <div id="tabBtn" style="max-height: 65vh; overflow-y: auto;">
                        <table class="table-responsive text-xs table table-bordered table-striped"
                            id="divisionWiseAbstractTable">
                            <thead class="theader text-white" style="background-color:#417DBE;">
                                <th class="text-center">
                                    Types
                                </th>
                                <th class="text-center">
                                    Numbers
                                </th>
                                <th class="text-center">
                                    Length(km)
                                </th>
                            </thead>

                            <tbody id="roadAbstractSummaryBody">
                                @foreach ($roadAbstractDetails as $roadDetail)
                                    <tr>
                                        <td>
                                            {{ $roadDetail->rd_catg_descr }}
                                        </td>
                                        <td class="text-center">
                                            {{ $roadDetail->road_count }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($roadDetail->road_length, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="dashboardModal" class="dashboardModal">
        <div class="dashboardModalContent">
            <div class="headContent">
                <p class="text-sm text-bold text-danger">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                    Warning
                </p>
                <span class="closeWingWall">&times;</span>
            </div>
            <div class="row text-xs" id="dashboardModalSubContent">
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/style.css') }}">
@endpush
@push('scripts')
    <script>
        (g => {
            var h, a, k, p = "The Google Maps JavaScript API",
                c = "google",
                l = "importLibrary",
                q = "__ib__",
                m = document,
                b = window;
            b = b[c] || (b[c] = {});
            var d = b.maps || (b.maps = {}),
                r = new Set,
                e = new URLSearchParams,
                u = () => h || (h = new Promise(async (f, n) => {
                    await (a = m.createElement("script"));
                    e.set("libraries", [...r] + "");
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                    e.set("callback", c + ".maps." + q);
                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                    d[q] = f;
                    a.onerror = () => h = n(Error(p + " could not load."));
                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                    m.head.append(a)
                }));
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
                d[l](f, ...n))
        })
        ({
            key: "AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM",
            v: "beta"
        });
    </script>
    <!-- Include jVectorMap library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/jquery-jvectormap.min.css"
        type="text/css" media="screen" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/jquery-jvectormap.min.js"></script>

    <!-- Include jVectorMap World map data -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/maps/jquery-jvectormap-world-mill.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link rel="stylesheet" type="text/css" src="{{ asset('css/MapStyle.css') }}">
    <script>
        var all_state_cached_geojson_data = @json($allStateDataSetResult);
        var deleteRoadRequest = "N";
        var rd_tp = "NH";
    </script>
    <script type="module" src="{{ asset('js/dashboard/googleMapWithLocalDataset.js') }}"></script>
    <style>
        /* Style the loader */
        .loader {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            display: none;
            /* Initially hidden */
            font-size: 24px;
            color: #ee0b0be0;
        }
    </style>
@endpush
