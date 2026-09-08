@extends('layouts.app')
@section('content')
    <div class="content-header mb-4">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">View Roads & Bridges</li>
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
                        <a class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewRoadWings') }}>
                            Roads
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewCDWorks') }}>
                            CD Works
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewBridge') }}>
                            Bridges
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewPCI') }}>
                            PCI
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="wing_btn rounded-0 px-2 btn btn-sm text-light"
                            style="width: 8rem; border-top: 1px solid #417dbe; border-left: 1px solid #417dbe; border-right: 1px solid #417dbe; background-color: #417dbe;"
                            href={{ route('viewProtectionWall') }}>
                            Protection Wall
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewSurfaceType') }}>
                            Surface Types
                        </a>
                    </span>
                </div>
                <div class="text-bold mr-1">
                    <span class="py-1">
                        <a class="wing_btn rounded-0 px-2 btn btn-sm border border-primary border-bottom-0"
                            style="width: 8rem;" href={{ route('viewHabitation') }}>
                            Habitation
                        </a>
                    </span>
                </div>
            </div>
            <h6 class="p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF PROTECTION WALL UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="border border-primary px-1 rounded loaderContainer">
                <div class="row justify-content-between align-item-center">
                    <form id="wing_road_protection_wall" class="mt-2 mb-1">
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
                                        <label for="road">Road</label>
                                    </div>
                                    <select style="width: 100%;" class="custom_select text-uppercase text-xs" name="road"
                                        id="road">
                                        <option value="">All Roads</option>
                                        @foreach ($roadDetails as $roadDetail)
                                            <option value={{ $roadDetail->rd_system_id }}>
                                                {{ $roadDetail->rd_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                    <div class="me-1">
                                        <label for="wall_type">Wall Type</label>
                                    </div>
                                    <select style="width: 100%;" class="custom_select text-uppercase text-xs"
                                        name="wall_type" id="wall_type">
                                        <option value="null">All Type</option>
                                        @foreach ($protectionWallTypes as $protectionWallType)
                                            <option value={{ $protectionWallType->wall_type_cd }}>
                                                {{ $protectionWallType->wall_type_descr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3 d-flex align-items-center">
                                    <div class="me-1">
                                        <label for="superstructure_type">Superstructure Type</label>
                                    </div>
                                    <select style="width: 100%;" class="custom_select text-uppercase text-xs"
                                        name="superstructure_type" id="superstructure_type">
                                        <option value="null">All Type</option>
                                        @foreach ($superStructureTypes as $superStructureType)
                                            <option value={{ $superStructureType->structure_type_cd }}>
                                                {{ $superStructureType->structure_type_descr }}
                                            </option>
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
                                            Total Asset Count: <span class="road_count text-bold"></span>
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
                            id="protection_wall_table">
                            <thead class="theader text-xs text-white" style="background-color:#417dbe">
                                <th class="text-center" style="min-width: 2rem;">Serial No.</th>
                                <th class="text-center" style="min-width: 5rem;">Road Name</th>
                                <th class="text-center" style="min-width: 5rem;">Protection Wall ID</th>
                                <th class="text-center" style="min-width: 5rem;">Chainage</th>
                                <th class="text-center" style="min-width: 5rem;">Wall Type</th>
                                <th class="text-center" style="min-width: 5rem;">Structure Type</th>
                                <th class="text-center" style="min-width: 5rem;">Bottom Width(Mtrs)</th>
                                <th class="text-center" style="min-width: 5rem;">Top Width(Mtrs)</th>
                                <th class="text-center" style="min-width: 5rem;">Length (Mtrs)</th>
                                <th class="text-center" style="min-width: 5rem;">Height (Mtrs)</th>
                                <th class="text-center" style="min-width: 5rem;">Construction Year</th>
                                <th class="text-center" style="min-width: 5rem;">Renovation Year</th>
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
        a.wing_btn:hover {
            background-color: rgba(65, 125, 190, 0.719);
            color: white;
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
        //         $('.show_subdiv').html('All Divisions');
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
            const division_name = $('#division').find(":selected").text().trim();
            getRoadByDivisionName(division_name);
            // get the circle name and show in the report
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

        function getRoadByDivisionName(divisionName) {
            $.ajax({
                type: "GET",
                url: '/asset-management/road-by-division-name',
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    division: divisionName
                },
                cache: false,
                success: function(response) {
                    $('#road').empty().append('<option value="">All Roads</option>');
                    if (response.status === 200) {
                        const data = response.result;
                        if (data.length == 0) {
                            alert('No road found!');
                        } else {
                            $.each(data, function(index, value) {
                                $('#road').append(
                                    '<option value="' + value.rd_system_id + '">' + value.rd_name +
                                    '</option>'
                                );
                            });
                        }
                    }
                    if (response.status === 204) {
                        alert(response.message);
                    }
                    if (response.status === 401) {
                        console.log(response.message);
                    }
                    if (response.status === 500) {
                        console.log(response.message);
                    }
                },
                error: function(error) {
                    console.log(error);
                },
            });
        }
    </script>
@endpush
