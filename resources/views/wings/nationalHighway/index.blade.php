@extends('layouts.app')
@section('content')
    <div class="content-header mb-4">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">View National Highways</li>
            </ol>
        </div>
    </div>
    <div id="loader">
        <img src="{{ asset('images/loader2.gif') }}" alt="Loading..." width="60px;">
    </div>
    <section class="content">
        <div class="container-fluid mainBody py-2" style="border-radius: .2rem;">
            <div class="d-flex justify-content-start flex-wrap text-xs">
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="px-2 wing_btn rounded-0 btn btn-sm text-light"
                            style="width: 8rem; border-top: 1px solid #417dbe; border-left: 1px solid #417dbe; border-right: 1px solid #417dbe; background-color: #417dbe;"
                            href={{ route('viewNHWings') }}>
                            National Highway
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="px-2 wing_btn rounded-0 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewNHCDWorks') }}>
                            CD Works
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="px-2 wing_btn rounded-0 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewNHBridge') }}>
                            Bridge
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="px-2 wing_btn rounded-0 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewNHPCI') }}>
                            PCI
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="px-2 wing_btn rounded-0 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewNHProtectionWall') }}>
                            Protection Wall
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="px-2 wing_btn rounded-0 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewNHSurfaceType') }}>
                            Surface Types
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="px-2 wing_btn rounded-0 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewNHHabitation') }}>
                            Habitation
                        </a>
                    </span>
                </div>
            </div>
            <h6 class="p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF NATIONAL HIGHWAYS UNDER NAGALAND P W D (NH).
                </span>
            </h6>
            <div class="border border-primary px-1 rounded loaderContainer">
                <div class="row justify-content-between align-item-center">
                    <form id="wing_nh" class="mt-2 mb-1">
                        @csrf
                        <div class="row justify-content-center align-items-start">
                            <div class="col-sm-12 col-md-11 row">
                                <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                    <div class="me-1">
                                        <label for="zone">Zone</label>
                                    </div>
                                    <select style="width: 100%;" class="custom_select text-uppercase text-xs" name="zone"
                                        id="zone">
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
                                    <select style="width:100%;" class="custom_select text-uppercase text-xs" name="circle"
                                        id="circle">
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
                                    <select style="width:100%;" class="custom_select text-uppercase text-xs" name="division"
                                        id="division">
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
                                    <select style="width:100%;" class="custom_select text-uppercase text-xs" name="type"
                                        id="type">
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
                                    <select style="width:100%;" class="custom_select text-uppercase text-xs" name="category"
                                        id="category">
                                        <option value="null">All Categories</option>
                                        @foreach ($roadCategories as $roadCategorie)
                                            @if ($roadCategorie->rd_catg_cd === 'NH')
                                                <option value={{ $roadCategorie->rd_catg_cd }}>
                                                    {{ $roadCategorie->rd_catg_descr }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                        <div class="me-1">
                                            <label for="owner">Owner</label>
                                        </div>
                                        <select style="width:100%;" class="custom_select text-uppercase text-xs"
                                            name="owner" id="owner">
                                            <option value="null">All Owners</option>
                                            @foreach ($roadOwners as $roadOwner)
                                                <option value={{ $roadOwner->owner_cd }}>
                                                    {{ $roadOwner->owner_name }}
                                                </option>
                                            @endforeach
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
                <div id="road_container" class="container-fluid mainBody border mb-1" style="border-radius: .3rem;">
                    <div class="d-flex justify-content-end py-1">
                        <i class="fas fa-caret-left" id="toggleBtn"></i>
                    </div>
                    <div class="container-fluid mt-3" id="tableContent" style="display: none;">
                        <div>
                            <h6 class="text-bold text-lg">Road Summary</h6>
                            <div class="row border-bottom border-top text-sm">
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
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row my-1 justify-content-end align-item-center">
                            <div class="col-sm-6 col-md-3 d-flex justify-content-end my-1">
                                <span class="mis-btn-road"></span>
                            </div>
                        </div>
                        <table class="table-responsive text-xs table table-bordered table-striped user_list"
                            id="nh_details_table">
                            <thead class="theader text-xs text-white" style="background-color:#417dbe">
                                <th class="text-center" style="min-width: 3rem;">Serial Number</th>
                                <th class="text-center" style="min-width: 6rem;">Road ID</th>
                                <th class="text-center" style="min-width: 6rem;">Road Category</th>
                                {{-- <th class="text-center">Road Number</th> --}}
                                <th class="text-center" style="min-width: 6rem;">Road Name</th>
                                <th class="text-center" style="min-width: 6rem;">Road Type</th>
                                <th class="text-center" style="min-width: 6rem;">Road Length</th>
                                <th class="text-center" style="min-width: 6rem;">District Name</th>
                                <th class="text-center" style="min-width: 6rem;">Road Owner Name</th>
                                <th class="text-center" style="min-width: 6rem;">Action</th>
                            </thead>

                            <tbody>

                                {{-- dynamic table body --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <label class="modal-title" id="mapTitle">Nagaland PWD Road Map, </label>
                        <label id="lblRoadInfo" name = "lblRoadInfo"></label>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <div id="road-sum-info" class="text-xs" style="display: flex; justify-content: space-around">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="roadAbstractModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Nagaland PWD Road Abstract</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="row justify-content-center align-item-center">
                        <div class="col-md-1 mb-1">

                        </div>
                        <div class="col-md-2 mb-1">
                            <label for="name" class="col-form-label">Road ID:</label>
                            <label name="modal_road_id" id ="modal_road_id" class="col-form-label"></label>
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="name" class="col-form-label">Road name:</label>
                            <label name="roadAbstractModal_road_name" id ="roadAbstractModal_road_name"
                                class="col-form-label"></label>
                        </div>
                        <div class="col-md-4 mb-1">
                            <label for="name" class="col-form-label">Division name:</label>
                            <label name="roadAbstractModal_div_name" id ="roadAbstractModal_div_name"
                                class="col-form-label"></label>
                        </div>
                    </div>
                    <div class="row justify-content-center align-item-center">
                        <table class="text-xs table table-bordered table-striped" id="road_asset_abstract_details_table"
                            style="width: 90%">
                            <thead class="theader" style="background-color:#6ea051" aria-colspan="3">
                                <th class="text-center" style="min-width: 3rem;" colspan="3">
                                    <h3>Roads Assets
                                        Abstract Details</h3>
                                </th>
                            </thead>
                            <thead class="theader text-xs" style="background-color:#417dbe">
                                <th class="text-center" style="min-width: 3rem;">Serial Number</th>
                                <th class="text-center" style="min-width: 6rem;">Asset Name</th>
                                <th class="text-center" style="min-width: 6rem;">Total Count</th>
                            </thead>
                            <tbody>
                                {{-- dynamic table body --}}
                            </tbody>
                        </table>
                        {{-- table-responsive text-xs table table-bordered table-striped  --}}
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
        a.wing_btn:hover {
            background-color: rgba(65, 125, 190, 0.719);
            color: white;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/wings/nationalHighway/script.js') }}" defer></script>
    {{-- <script src="{{ asset('js/wings/roads/script.js') }}" defer></script> --}}
    <script>
        const zoneDetails = @json($zoneDetails);
        const circleDetails = @json($circleDetails);
        const divisionDetails = @json($divisionDetails);
        const subDivisionDetails = @json($subDivisionDetails);
        $("#zone").on("change", function() {
            const selectedZone = $("#zone").val();
            // get the zone name and show in the report
            if (selectedZone == 'null') {
                $('.show_zone').html('All Zones');
            } else {
                $.each(zoneDetails, function(index, value) {
                    if ((value.zone_cd) == selectedZone) {
                        $('.show_zone').html(value.zone_name);
                    }
                });
            }
            $("#circle").empty().append('<option value="null">ALL CIRCLES</option>');
            $("#division").empty().append('<option value="null">ALL DIVISIONS</option>');
            $("#subDivision").empty().append('<option value="null">ALL SUBDIVISIONS</option>');
            if (selectedZone === 'null') {
                $.each(circleDetails, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#circle').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                    }
                });
                $.each(divisionDetails, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#division').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(subDivisionDetails, function(index, value) {
                    if ((value.dept_cd) == 3) {
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
            if (selectedCircle == 'null') {
                $('.show_circle').html('All Circles');
            } else {
                $.each(circleDetails, function(index, value) {
                    if ((value.circle_cd) == selectedCircle) {
                        $('.show_circle').html(value.circle_name);
                    }
                });
            }
            $("#division").empty().append('<option value="null">ALL DIVISIONS</option>');
            $("#subDivision").empty().append('<option value="null">ALL SUBDIVISIONS</option>');
            console.log(selectedCircle);
            if (selectedCircle === 'null') {
                const selectedZone = $("#zone").val();
                console.log("selected zone: ", selectedZone);
                $.each(divisionDetails, function(index, value) {
                    if ((value.dept_cd == 3) && (value.zone_cd == selectedZone)) {
                        $('#division').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(subDivisionDetails, function(index, value) {
                    if ((value.dept_cd == 3) && (value.zone_cd == selectedZone)) {
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
        $("#subDivision").on("change", function() {
            const selectedSubDivision = $("#subDivision").val();

            // get the circle name and show in the report
            if (selectedSubDivision == 'null') {
                $('.show_subdiv').html('All Divisions');
            } else {
                $.each(subDivisionDetails, function(index, value) {
                    if ((value.sub_div_cd) == selectedSubDivision) {
                        $('.show_subdiv').html(value.sub_div_name);
                    }
                });
            }
        })
        $("#division").on("change", function() {
            const selectedDivision = $("#division").val();
            // get the circle name and show in the report
            if (selectedDivision == 'null') {
                $('.show_division').html('All Divisions');
            } else {
                $.each(divisionDetails, function(index, value) {
                    if ((value.division_cd) == selectedDivision) {
                        $('.show_division').html(value.division_name);
                    }
                });
            }
            $("#subDivision").empty().append('<option value="null">ALL SUBDIVISIONS</option>');
            console.log(selectedDivision);
            if (selectedDivision === 'null') {
                const selectedZone = $("#zone").val();
                const selectedCircle = $("#circle").val();
                // if no circle is selected
                if (selectedCircle === 'null') {
                    $.each(subDivisionDetails, function(index, value) {
                        if ((value.dept_cd == 3) && (value.zone_cd == selectedZone)) {
                            $('#subDivision').append('<option value="' + value.sub_div_cd + '">' + value
                                .sub_div_name +
                                '</option>');
                        }
                    });
                } else {
                    $.each(subDivisionDetails, function(index, value) {
                        if ((value.dept_cd == 3) && (value.zone_cd == selectedZone) && (value.circle_cd ==
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
