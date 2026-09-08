@extends('layouts.app')
@section('content')
    <div class="">
        <form id="frmHousingForMap" class="">
            @csrf
            <div class="pb-2 border bg-light shadow d-flex flex-wrap justify-content-between align-items-end">
                <div class="row col-12 col-md-11">
                    <div class="col-md-1">
                        <h6 class="py-1 text-sm border-bottom">Division
                        </h6>
                        <select id="bld_division" name="bld_division"style="width: 100%;" class="text-sm">
                            <option value='A'>All</option>
                            @foreach ($division_dtls as $item)
                                <option style="font-size: 0.2rem;" class="custom_select text-uppercase text-xs"
                                    value="{{ $item->division_cd }}">
                                    {{ $item->division_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <h6 class="py-1 text-sm border-bottom">Sub Division
                        </h6>
                        <select id="bld_sub_division" name="bld_sub_division"style="width: 100%;" class="text-sm">
                            <option value='A'>All</option>
                            @foreach ($sub_division_dtls as $item)
                                <option style="font-size: 0.2rem;" class="custom_select text-uppercase text-xs"
                                    value="{{ $item->sub_div_cd }}">
                                    {{ $item->sub_div_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <h6 class="py-1 text-sm border-bottom">Building Class
                        </h6>
                        <select id="bld_class" name="bld_class"style="width: 100%;"
                            class="custom_select text-uppercase text-xs">
                            <option value='A'>All</option>
                            @foreach ($bld_class_master as $item)
                                <option style="font-size: 0.2rem;" class="text-xs" value="{{ $item->building_class_cd }}">
                                    {{ $item->building_class_descr }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1">
                        <h6 class="py-1 border-bottom text-sm">Location</h6>
                        <select id="bld_loc" name="bld_loc"style="width: 100%;"
                            class="custom_select text-uppercase text-xs">
                            <option value='A'>All</option>
                            @foreach ($bld_loc_master as $item)
                                <option style="font-size: 0.2rem;" class="text-xs" value="{{ $item->location_cd }}">
                                    {{ $item->location_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <h6 class="py-1 border-bottom text-sm">Building Category</h6>
                        <select id="bld_catg" name="bld_catg"style="width: 100%;"
                            class="custom_select text-uppercase text-xs">
                            <option value='A'>All </option>
                            @foreach ($bld_catg_master as $item)
                                <option style="font-size: 0.2rem;" class="text-xs" value="{{ $item->building_catg_cd }}">
                                    {{ $item->building_catg_descr }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <h6 class="py-1 border-bottom text-sm">Building Type</h6>
                        <select id="bld_type" name="bld_type"style="width: 100%;"
                            class="custom_select text-uppercase text-xs">
                            <option value='A'>All </option>
                            @foreach ($bld_type_master as $item)
                                <option style="font-size: 0.2rem;" class="text-xs" value="{{ $item->building_type_cd }}">
                                    {{ $item->building_type_descr }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-1">
                    <button type="submit" class="text-xs btn btn-xs btn-outline-primary">
                        View
                    </button>
                </div>
            </div>
        </form>
        <div class="mx-1 border bg-light shadow row">
            <div class="col-12 col-md-9">
                <div class="border my-1 py-1 col-12">
                    <div id="world-map"></div>
                    <div class="" id="map"></div>
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header d-flex justify-content-between align-items-end">
                                    <p class="modal-title text-md fw-bold text-uppercase"></p>
                                    <button class="closeWingWall btn btn-xs">
                                        <i class="fa fa-close"></i>
                                    </button>
                                </div>
                                <div id="modal-body" class="modal-body text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="border my-1 py-2 col-12" id="sub-asset-sum-container">
                        <h6 class="py-1 border-bottom"><i class="fa fa-bars mr-1 text-xs"></i>Other Details</h6>
                        <h6 style="text-xs" id="sub_asset_header"></h6>
                        <div id="status_message"></div>
                    </div> --}}
            </div>
            <div class="col-12 col-md-3">
                <h6 class="py-1 border-bottom"><i class="fa fa-bars mr-1 text-xs"></i>Building Summary</h6>
                {{-- <div id="road-sum-info" class="text-xs"></div> --}}
                <div id="tabBtn" style="max-height: 65vh; overflow-y: auto;">
                    <table class="table-responsive text-xs table table-bordered table-striped"
                        id="divisionWiseAbstractTable">
                        <thead class="theader text-white" style="background-color:#417DBE;">
                            <th class="text-center">
                                Building Type
                            </th>
                            <th class="text-center">
                                Plinth Area(sq.ft)
                            </th>
                            <th class="text-center">
                                Total Building
                            </th>
                        </thead>

                        <tbody id="roadAbstractSummaryBody">
                            @foreach ($buildingSummaries as $buildingSummary)
                                <tr>
                                    <td>
                                        {{ $buildingSummary->building_type_descr }}
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($buildingSummary->plinth_area, 2) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $buildingSummary->building_count }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        #map {
            height: 70vh;
        }
    </style>
    <link rel="stylesheet" type="text/css" src="{{ asset('css/MapStyle.css') }}">
@endpush

@push('scripts')
    <script>
        var allApprovedBuildingDetails = @json($allApprovedBuildingDetails);
    </script>
    <script type="module" src="{{ asset('js/dashboard/housingGoogleMap.js') }}"></script>

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

    <script>
        const divisionDetails = @json($division_dtls);
        const subDivisionDetails = @json($sub_division_dtls);
        const bldClassMaster = @json($bld_class_master);
        const bldLocMaster = @json($bld_loc_master);
        const bldCatgMaster = @json($bld_catg_master);
        const bldTypeMaster = @json($bld_type_master);
        console.log(bldLocMaster);
        $("#bld_division").on("change", function() {
            const selectedDivision = $("#bld_division").val();

            $("#bld_sub_division").empty().append('<option value="A">ALL</option>');

            if (selectedDivision === 'A') {
                $.each(subDivisionDetails, function(index, value) {
                    $('#bld_sub_division').append('<option value="' + value.sub_div_cd + '">' + value
                        .sub_div_name +
                        '</option>');
                });

            } else {
                $.each(subDivisionDetails, function(index, value) {
                    if ((value.div_cd) == (selectedDivision))
                        $('#bld_sub_division').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                });
            }
        })

        $("#bld_class").on("change", function() {
            const selectedBldClass = $("#bld_class").val();
            $("#bld_loc").empty().append('<option value="A">ALL</option>');

            if (selectedBldClass === 'A') {
                $.each(bldLocMaster, function(index, value) {
                    $('#bld_loc').append('<option value="' + value.location_cd + '">' +
                        value.location_name + '</option>');
                });

            } else {
                $.each(bldLocMaster, function(index, value) {
                    if ((value.building_class_cd) == (selectedBldClass))
                        $('#bld_loc').append('<option value="' + value.location_cd + '">' +
                            value.location_name + '</option>');
                });
            }
        });
    </script>
@endpush
