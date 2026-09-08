@extends('layouts.index')

@section('content')
    <?php $brdcstMsg = Cache::get('global_message'); ?>

    <main class="public-oamis">
        <nav class="public-nav">
            <a class="public-brand" href="{{ route('getWelcomeDashBoard') }}">
                <img src="{{ asset('images/main_logo.png') }}" alt="Nagaland PWD logo">
                <span>
                    <strong>OMIS Nagaland PWD</strong>
                    <small>Online Asset Management & Information System</small>
                </span>
            </a>
            <div class="public-nav-actions">
                <button type="button" class="oamis-theme-toggle" data-theme-toggle aria-pressed="false">
                    <i class="fas fa-moon"></i><span>Dark</span>
                </button>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-primary"><i class="fa fa-home mr-1"></i>Home</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary"><i class="fa fa-sign-in mr-1"></i>Login</a>
                    @endauth
                @endif
            </div>
        </nav>

        @if ($brdcstMsg)
            <div class="public-alert">
                <i class="fa fa-bullhorn"></i>
                <span>{{ $brdcstMsg }}</span>
            </div>
        @endif

        <section class="road-alert-marquee" aria-label="Road blockage alerts">
            <div class="marquee-label">
                <i class="fa fa-triangle-exclamation"></i>
                <span>Road Blockage Alerts</span>
            </div>
            <div class="marquee-track">
                <div class="marquee-content">
                    @if ($distressDetails->count() > 0)
                        @foreach ($distressDetails as $distressDetail)
                            <span>{{ $distressDetail->rd_name }}</span>
                        @endforeach
                    @else
                        <span>No blockage found</span>
                    @endif
                </div>
            </div>
        </section>

        <section class="public-grid">
            <article class="public-card public-message">
                <span class="eyebrow">Minister's Message</span>
                <h2>Digital governance for infrastructure delivery</h2>
                <p>
                    The Online Management and Information System is a transformative digital
                    initiative developed by Nagaland Public Works Department for transparent,
                    accountable and citizen-centric infrastructure management.
                </p>
                <p>
                    Citizens can access real-time road and infrastructure updates, supporting
                    faster decisions, better planning, and stronger trust between government and
                    communities.
                </p>
                <footer>
                    <strong>Shri. G. Kaito Aye</strong>
                    <span>Minister PWD (Roads & Bridges), Government of Nagaland</span>
                </footer>
            </article>

            <article class="public-card public-map" id="state-map">
                <div class="section-header">
                    <div>
                        <span class="eyebrow">State Infrastructure Map</span>
                        <h2>Road Network of Nagaland</h2>
                    </div>
                    <span class="map-note">Click active markers for blockage details</span>
                </div>
                <input id="hidden_distress_dtls" name="hidden_distress_dtls" type="hidden" value="{{ $distressDetails }}" />
                <div id="map"></div>
                <!-- <div style="width: 100%; height: 100vh;">
                    <iframe src="http://npwdomisgis.nagaland.gov.in/portal/apps/dashboards/00b560723c184292b876473016ddc57f"
                        width="100%" height="100%" frameborder="0" allowfullscreen>
                    </iframe>
                </div> -->
                <div id="loader" class="loader">Please wait while road data loads...</div>
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p class="modal-title text-md fw-bold text-uppercase mb-0"></p>
                            </div>
                            <div id="modal-body" class="modal-body"></div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="distressDataModal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p class="modal-title text-md fw-bold text-uppercase mb-0"></p>
                            </div>
                            <div id="modal-body" class="modal-body"></div>
                        </div>
                    </div>
                </div>
            </article>

            <article class="public-card public-about">
                <span class="eyebrow">About OMIS</span>
                <h2>Integrated asset information for planning and decisions</h2>
                <p>
                    OMIS helps transform manual infrastructure records into a data-driven
                    operating platform for planning, budgeting, monitoring and public service
                    delivery across Nagaland PWD.
                </p>
                <div class="insight-list">
                    <span><i class="fa fa-circle-check"></i>GIS-enabled public visibility</span>
                    <span><i class="fa fa-circle-check"></i>Road, building and mechanical asset registries</span>
                    <span><i class="fa fa-circle-check"></i>Citizen-facing infrastructure information</span>
                </div>
            </article>
        </section>

        <section class="public-kpis" id="asset-summary">
            <a class="public-kpi" href="#state-map">
                <i class="fa fa-road"></i>
                <span>Total Roads</span>
                <strong>{{ $totalRoadCount }}</strong>
            </a>
            <a class="public-kpi" href="#building-assets">
                <i class="fa fa-building"></i>
                <span>Buildings</span>
                <strong>{{ $totalBuildingCount }}</strong>
            </a>
            <a class="public-kpi" href="{{ route('getNHRoadByCatg') }}" target="_blank">
                <i class="fa fa-route"></i>
                <span>National Highways</span>
                <strong>{{ $totalNHCount }}</strong>
            </a>
            <a class="public-kpi" href="{{ route('citizen.equipment') }}" target="_blank">
                <i class="fa fa-gears"></i>
                <span>Mechanical Assets</span>
                <strong>{{ $totalEquipmentCount + $totalVehicleCount }}</strong>
            </a>
        </section>

        <section class="asset-breakdown">
            <div class="section-header">
                <div>
                    <span class="eyebrow">Asset Registry</span>
                    <h2>Infrastructure asset summary</h2>
                </div>
            </div>
            <div class="asset-breakdown-grid">
                <article class="asset-summary">
                    <h3><i class="fa fa-road"></i>Roads</h3>
                    <p>Total No. of Roads: <strong>{{ $totalRoadCount }}</strong></p>
                    @foreach ($roadCategoryCounts as $roadCategoryCount)
                        <a href="{{ route('citizen.getRoadByCatg', ['cd' => $roadCategoryCount->rd_category_cd]) }}"
                            target="_blank">
                            {{ $roadCategoryCount->rd_catg_descr }}: {{ $roadCategoryCount->count }}
                        </a>
                    @endforeach
                </article>
                <article class="asset-summary" id="building-assets">
                    <h3><i class="fa fa-building"></i>Buildings</h3>
                    <p>Total No. of Buildings: <strong>{{ $totalBuildingCount }}</strong></p>
                    @foreach ($buildingClassCounts as $item)
                        <a href="{{ route('getHousingByClass', ['cd' => $item->building_class_cd]) }}" target="_blank">
                            {{ $item->building_class_descr }} Buildings: {{ $item->count }}
                        </a>
                    @endforeach
                </article>
                <article class="asset-summary">
                    <h3><i class="fa fa-route"></i>National Highway</h3>
                    <a href="{{ route('getNHRoadByCatg') }}" target="_blank">
                        Total No. of National Highways: {{ $totalNHCount }}
                    </a>
                </article>
                <article class="asset-summary">
                    <h3><i class="fa fa-gears"></i>Mechanical</h3>
                    <p>Total Machinery: <strong>{{ $totalEquipmentCount + $totalVehicleCount }}</strong></p>
                    <a href="{{ route('citizen.equipment') }}" target="_blank">Equipment: {{ $totalEquipmentCount }}</a>
                    <a href="{{ route('citizen.vehicle') }}" target="_blank">Vehicles: {{ $totalVehicleCount }}</a>
                </article>
            </div>
        </section>
    </main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-home.css') }}">
@endpush

@push('scripts')
    <!-- <script>
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
            </script> -->
    <script>
        var distress_dtls_array = @json($distressDetails);
        var all_state_cached_geojson_data = @json($allStateDataSetResult);
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/jquery-jvectormap.min.css"
        type="text/css" media="screen" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/jquery-jvectormap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jvectormap/2.0.5/maps/jquery-jvectormap-world-mill.js"></script>
    <link rel="stylesheet" type="text/css" src="{{ asset('css/MapStyle.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pako/2.1.0/pako.min.js"></script>
    <!-- <script type="module" src="{{ asset('js/dashboard/googleMapCitizenWithLocalDataset.js') }}"></script> -->
    <link rel="stylesheet" href="https://js.arcgis.com/4.34/esri/themes/light/main.css">
    <script src="https://js.arcgis.com/4.34/"></script>
    <script type="module" src="{{ asset('js/dashboard/arcGISMapCitizen.js') }}"></script>


@endpush