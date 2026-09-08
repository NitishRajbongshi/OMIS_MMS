@extends('layouts.app')
@section('content')
    <div class="content-header mb-4">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">View Housing</li>
            </ol>
        </div>
    </div>

    <div id="loader">
        <img src="{{ asset('images/loader2.gif') }}" alt="Loading..." width="60px;">
    </div>

    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diseaseWiseTitle">Nagaland PWD Housing Map</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- <div class="px-3 row justify-content-center align-item-center">
                        <div class="col-md-6">
                            <label for="name" class="col-form-label">Building Name/ Qtr No:</label>
                            <input type="text" name="modal_bld_name_qtr_no" id ="modal_bld_name_qtr_no" value=""
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="col-form-label">Localtion:</label>
                            <input type="text" name="modal_bld_loc" id ="modal_bld_loc" value="" readonly>
                        </div>
                    </div> --}}
                <div class="p-3 row justify-content-center align-item-center">
                    <div class="col-12 col-md-9">
                        <div class="modal-body modal-dialog-centered" id='map' style='width: 100%; height: 500px;'>
                            {{-- <form action="#" id="frm_map_modal">
                                    @csrf
                                </form> --}}
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div>
                            <h6 class="border-bottom pb-2">Building Details</h6>
                        </div>
                        <div class="building_details_container">

                        </div>
                        {{-- <div id="road-sum-info" class="text-xs" style="display: flex; justify-content: space-around">
                            </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody py-2" style="border-radius: .2rem;">
            <h6 class="p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF BUILDINGS UNDER NAGALAND P W D (HOUSING).
                </span>
            </h6>
            <div class="border border-primary px-1 rounded loaderContainer">
                <div class="" style="border-radius: .3rem;">
                    <div class="row justify-content-between align-items-start">
                        <form id="wing_building" class="mt-2 mb-1">
                            @csrf
                            <div class="row justify-content-center align-items-start">
                                <div class="col-sm-12 col-md-11 row">
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="division">Division:</label>
                                        </div>
                                        <select style="width: 100%;" class="custom_select text-uppercase text-xs"
                                            name="division" id="division">
                                            <option value="null">All</option>
                                            @foreach ($divisions as $division)
                                                <option value={{ $division->division_cd }}>
                                                    {{ $division->division_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="d-flex mr-2 justify-content-start align-item-center ">
                                                <div class="mr-1">
                                                    <label for="subDivision">Sub Division</label>
                                                </div>
                                                <div class="mr-1">
                                                    <select class="custom_select text-uppercase text-xs" name="subDivision" id="subDivision"
                                                        style="width: 9rem;">
                                                        <option value="null">All</option>
                                                        @foreach ($subDivisions as $subDivision)
                                                            <option value={{ $subDivision->sub_div_cd }}>
                                                                {{ $subDivision->sub_div_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> --}}
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="type">Type:</label>
                                        </div>
                                        <select style="width: 100%;" class="custom_select text-uppercase text-xs"
                                            name="type" id="type">
                                            <option value="null">All</option>
                                            @foreach ($buildingTypes as $buildingType)
                                                <option value={{ $buildingType->building_type_cd }}>
                                                    {{ $buildingType->building_type_descr }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                            <div class="me-1">
                                                <label for="category">Category </label>
                                            </div>
                                            <select class="custom_select text-uppercase text-xs" name="category" id="category"
                                                style="width: 100%;">
                                                <option value="null">All</option>
                                                @foreach ($buildingCategories as $buildingCategory)
                                                    <option value="{{ $buildingCategory->building_catg_cd }}">
                                                        {{ $buildingCategory->building_catg_descr }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="class">Class:</label>
                                        </div>
                                        <select class="custom_select text-uppercase text-xs" name="class" id="class"
                                            style="width: 100%;">
                                            <option value="null">All</option>
                                            @foreach ($buildingClasses as $buildingClass)
                                                <option value="{{ $buildingClass->building_class_cd }}">
                                                    {{ $buildingClass->building_class_descr }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="department">Department:</label>
                                        </div>
                                        <select class="custom_select text-uppercase text-xs" name="department"
                                            id="department" style="width: 100%;">
                                            <option value="null">All</option>
                                            @foreach ($departmentDetails as $departmentDetail)
                                                <option value="{{ $departmentDetail->id }}">
                                                    {{ $departmentDetail->dept_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="maintain">Maintained by NPWD</label>
                                        </div>
                                        <select class="custom_select text-uppercase text-xs" name="maintain" id="maintain"
                                            style="width: 100%;">
                                            <option value="null">All</option>
                                            <option value="Y">YES</option>
                                            <option value="N">NO</option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                            <div class="me-1">
                                                <label for="water">Water</label>
                                            </div>
                                            <select class="custom_select text-uppercase text-xs" name="water" id="water"
                                                style="width: 100%;">
                                                <option value="null">All</option>
                                                <option value="Y">YES</option>
                                                <option value="N">NO</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                            <div class="me-1">
                                                <label for="electricity">Electricity</label>
                                            </div>
                                            <select class="custom_select text-uppercase text-xs" name="electricity" id="electricity"
                                                style="width: 100%;">
                                                <option value="null">All</option>
                                                <option value="Y">YES</option>
                                                <option value="N">NO</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                            <div class="me-1">
                                                <label for="sanitary">Sanitary</label>
                                            </div>
                                            <select class="custom_select text-uppercase text-xs" name="sanitary" id="sanitary"
                                                style="width: 100%;">
                                                <option value="null">All</option>
                                                <option value="Y">YES</option>
                                                <option value="N">NO</option>
                                            </select>
                                        </div> --}}
                                    {{-- <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                            <div class="me-1">
                                                <label for="construction_year">Construction Year </label>
                                            </div>
                                            <select class="custom_select text-uppercase text-xs" name="construction_year" id="construction_year"
                                                style="width: 100%;">
                                                <option value="null">All</option>
                                                <?php for ($i = Carbon\Carbon::now()->year; $i >= 1950; $i--) { ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div> --}}
                                </div>
                                <div class="col-sm-12 col-md-1 d-flex justify-content-end">
                                    <button type="submit" class="text-xs btn btn-xs btn-outline-primary">
                                        View
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="road_container" class="container-fluid mainBody border mb-1" style="border-radius: .3rem;">
                    <div class="d-flex justify-content-end py-1">
                        <i class="fas fa-caret-left" id="toggleBtn"></i>
                    </div>
                    <div class="container-fluid mt-3" id="tableContent" style="display: none;">
                        <div class="row my-2 justify-content-end align-item-center">
                            <div class="col-sm-6 col-md-3 d-flex justify-content-end mb-1">
                                <span class="mis-btn-road"></span>
                            </div>
                        </div>
                        <table class="table-responsive text-xs table table-bordered table-striped user_list"
                            id="building_details_table">
                            <thead class="theader text-xs text-white" style="background-color:#417dbe">
                                <th class="text-center" style="min-width: 2rem;">Serial No.</th>
                                <th class="text-center" style="min-width: 5rem;">Building ID</th>
                                <th class="text-center" style="min-width: 5rem;">Quarter Number</th>
                                <th class="text-center" style="min-width: 5rem;">Building Name</th>
                                <th class="text-center" style="min-width: 5rem;">Building Type</th>
                                <th class="text-center" style="min-width: 5rem;">Maintained By NPWD?</th>
                                <th class="text-center" style="min-width: 5rem;">Residential/Non-residential</th>
                                <th class="text-center" style="min-width: 5rem;">Owning Department</th>
                                <th class="text-center" style="min-width: 5rem;">Show In Map</th>
                                <th class="text-center" style="min-width: 5rem;">View Images</th>
                                {{-- <th class="text-center">Building Category</th>
                                    <th class="text-center">Plinth Areas(Sq.ft)</th>
                                    <th class="text-center">Construction Year</th>
                                    <th class="text-center">Construction Cost</th>
                                    <th class="text-center">Water (Yes/No)</th>
                                    <th class="text-center">Electricity (Yes/No)</th>
                                    <th class="text-center">Sanitary (Yes/No)</th>
                                    <th class="text-center">Present Occupant</th>
                                    <th class="text-center">Location</th>
                                    <th class="text-center">Remark</th> --}}
                            </thead>
                            <tbody class="text-center">
                                {{-- dynamic table body --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loader/style.css') }}">
    <style>
        .building-marker {
            background-image: url("/images/marker_icons/home_1.png");
            background-size: cover;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            /*border: 2px solid #000;*/
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/wings/building/script.js') }}" defer></script>

    <script>
        $(document).ready(function() {
            $("#toggleBtn").click(function() {
                $("#tableContent").slideToggle('slow');
                $(this).toggleClass("fa-caret-left fa-caret-down");
            });
        });
    </script>

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
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(
                () =>
                d[l](f, ...n))
        })
        ({
            key: "AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM",
            v: "beta"
        });
    </script>
@endpush
