@extends('layouts.app')
@section('content')
    <div style="display: none;" id="selected_road_id"></div>
    <div class="">
        <div class="mx-1 border bg-light shadow row">
            {{-- <div class="col-sm-12 p-1 col-md-12">
                        <div class="mx-1 border bg-light row"> --}}
            <div class="p-1 col-md-3">
                <select id="division_list" style="width: 100%;" class="text-sm" name="division_list">
                    <option value=''>Select Division</option>
                    @foreach ($division_dtls as $item)
                        @if ($item->dept_cd == '14')
                            <option style="font-size: 0.2rem;" class="text-xs" value="{{ $item->division_cd }}">
                                {{ $item->division_name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="p-1 col-md-3">
                <select id="category_list" style="width: 100%;" class="text-xs" name="road_list">
                    <option value=''>Select Road Category</option>
                    @foreach ($catg_dtls as $item)
                        <option value="{{ $item->rd_catg_cd }}">
                            {{ $item->rd_catg_descr }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="p-1 col-md-3">
                <select id="road_list" style="width: 100%;" class="text-xs" name="road_list">
                    <option value=''>Select Road</option>
                    @foreach ($roadDetails as $item)
                        <option value="{{ $item->rd_system_id }}">
                            {{ $item->rd_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- </div>
                    </div> --}}
        </div>
        <div class="mx-1 border bg-light shadow row">
            <div class="col-sm-12 col-md-12 row">
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
            </div>
        </div>

        <div class="mx-1 border bg-light shadow row">
            <div class="col-sm-12 col-md-12 row">
                <div class="border my-1 py-1 col-12">
                    <form id="frmDeleteRoad">
                        <input type="hidden" name ='hdnRoadIdsToDelete' id='hdnRoadIdsToDelete' value='' />
                        <h6 class="py-1 border-bottom"><i class="fa fa-bars mr-1 text-xs"></i>Selected Roads To Delete From
                            Map: </h6>
                        <div class="scrollable-div">
                            <table class="table table-bordered text-xs" id="tblRoadListToDelete">
                                <th class="text-center">Road ID</th>
                                <th class="text-center">Road Name</th>
                                <th class="text-center">Hide / Unhide</th>
                                <th class="text-center">Remove Selection</th>
                            </table>
                        </div>
                        <button id="btnDeleteRdFrmMap" class="btn btn-success btn-sm rounded-0">
                            <i class="fa fa-save mr-1"></i> Delete
                        </button>
                        <button class="btn btn-danger btn-sm rounded-0">
                            <i class="fa fa-backward mr-1"></i>
                            <a class="text-white" href="{{ route('home') }} ">
                                Cancel</a>
                        </button>
                    </form>
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
    <script type="module" src="{{ asset('js/dashboard/googleMapWithLocalDataset.js') }}"></script>
    <style>
        .scrollable-div {
            height: 200px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 0.2rem;
            margin-bottom: 0.2rem;
        }
    </style>
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
    {{-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> --}}
    <link rel="stylesheet" type="text/css" src="{{ asset('css/MapStyle.css') }}">

    <script>
        var all_state_rd_details = @json($roadDetails);
        var division_dtls = @json($division_dtls);
        var all_state_cached_geojson_data = @json($allStateDataSetResult);
        var deleteRoadRequest = "Y";
    </script>

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
