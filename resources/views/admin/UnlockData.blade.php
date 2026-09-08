@extends('layouts.app')
@section('content')
    @php
        $zn_dtls = $zoneDetails;
        $crcl_dtls = $circleDetails;
        $dv_dtls = $divisionDetails;
        $sb_dv_dtls = $subDivisionDetails;
    @endphp
    <div class="container-fluid">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="{{ '#' }}" class="mr-2">Dashboard</a>/Unlock Data
        </div>
    </div>
    <section class="content" style="border-radius: .2rem;">
        <div class="container-fluid mainBody py-2" style="border-radius: .2rem;">
            <div class="border px-1 rounded loaderContainer">

                <div class="border" style="border-radius: .3rem;">
                    <ul class="nav nav-pills nav-tabs nav-fill">
                        <li class="nav-item active">
                            <a href="#rnb" data-toggle="tab" class="nav-link active" aria-current="page">Roads &
                                Bridges</a>
                        </li>
                        <li class="nav-item">
                            <a href="#nh" data-toggle="tab" class="nav-link">National Highway</a>
                        </li>
                        <li class="nav-item">
                            <a href="#housing" data-toggle="tab" class="nav-link">Housing</a>
                        </li>
                        <li class="nav-item">
                            <a href="#mech" data-toggle="tab" class="nav-link">Mechanicals</a>
                        </li>
                    </ul>
                    <div class="tab-content clearfix">
                        <div class="tab-pane active" id="rnb">
                            <input type="hidden" name="hdnZone" id="hdnZone" value="{{ $zoneDetails }}">
                            <form class="frm_unloack_for_rnb" id="frm_unloack_for_rnb" method="POST" action="">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="zone">Zone </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase" name="zone_rnb"
                                            id="zone_rnb">
                                            <option value="A">All</option>
                                            @foreach ($zoneDetails as $zoneDetail)
                                                @if ($zoneDetail->dept_cd == 14)
                                                    <option value={{ $zoneDetail->zone_cd }}>
                                                        {{ $zoneDetail->zone_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-sm-2">

                                        <label for="circle">Circle</label>

                                        <select style="width:10rem;" class="custom_select text-uppercase" name="circle_rnb"
                                            id="circle_rnb">
                                            <option value="A">All</option>
                                            @foreach ($circleDetails as $circleDetail)
                                                @if ($circleDetail->dept_cd == 14)
                                                    <option value={{ $circleDetail->circle_cd }}>
                                                        {{ $circleDetail->circle_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-sm-2">

                                        <label for="division">Division</label>

                                        <select style="width:10rem;" class="custom_select text-uppercase"
                                            name="division_rnb" id="division_rnb">
                                            <option value="A">All</option>
                                            @foreach ($divisionDetails as $divisionDetail)
                                                @if ($divisionDetail->dept_cd == 14)
                                                    <option value={{ $divisionDetail->division_cd }}>
                                                        {{ $divisionDetail->division_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-sm-2">

                                        <label for="subDivision">Sub-Division </label>

                                        <select style="width:10rem;" class="custom_select text-uppercase for-control"
                                            name="subDivision_rnb" id="subDivision_rnb">
                                            <option value="A">All</option>
                                            @foreach ($subDivisionDetails as $subDivisionDetail)
                                                @if ($subDivisionDetail->dept_cd == 14)
                                                    <option value={{ $subDivisionDetail->sub_div_cd }}>
                                                        {{ $subDivisionDetail->sub_div_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <br>
                                        <button type="submit" class="text-xs btn btn-sm btn-outline-primary">
                                            View
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="nh">
                            <form class="frm_unloack_for_nh" id="frm_unloack_for_nh" method="POST" action="">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="zone">Zone </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase" name="zone_nh"
                                            id="zone_nh">
                                            <option value="A">All</option>
                                            @foreach ($zoneDetails as $zoneDetail)
                                                @if ($zoneDetail->dept_cd == 3)
                                                    <option value={{ $zoneDetail->zone_cd }}>
                                                        {{ $zoneDetail->zone_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-sm-2">
                                        <label for="circle">Circle </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase" name="circle_nh"
                                            id="circle_nh">
                                            <option value="A">All</option>
                                            @foreach ($circleDetails as $circleDetail)
                                                @if ($circleDetail->dept_cd == 3)
                                                    <option value={{ $circleDetail->circle_cd }}>
                                                        {{ $circleDetail->circle_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-sm-2">

                                        <label for="division">Division </label>

                                        <select style="width:10rem;" class="custom_select text-uppercase" name="division_nh"
                                            id="division_nh">
                                            <option value="A">All</option>
                                            @foreach ($divisionDetails as $divisionDetail)
                                                @if ($divisionDetail->dept_cd == 3)
                                                    <option value={{ $divisionDetail->division_cd }}>
                                                        {{ $divisionDetail->division_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-sm-2">

                                        <label for="subDivision">Sub-Division </label>

                                        <select style="width:10rem;" class="custom_select text-uppercase for-control"
                                            name="subDivision_nh" id="subDivision_nh">
                                            <option value="A">All</option>
                                            @foreach ($subDivisionDetails as $subDivisionDetail)
                                                @if ($subDivisionDetail->dept_cd == 3)
                                                    <option value={{ $subDivisionDetail->sub_div_cd }}>
                                                        {{ $subDivisionDetail->sub_div_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <br>
                                        <button type="submit" class="text-xs btn btn-sm btn-outline-primary">
                                            View
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="housing">
                            <form class="frm_unloack_for_housing" id="frm_unloack_for_housing" method="POST"
                                action="">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="zone">Zone </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase"
                                            name="zone_housing" id="zone_housing">
                                            <option value="A">All</option>
                                            @foreach ($zoneDetails as $zoneDetail)
                                                @if ($zoneDetail->dept_cd == 6)
                                                    <option value={{ $zoneDetail->zone_cd }}>
                                                        {{ $zoneDetail->zone_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="circle">Circle </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase"
                                            name="circle_housing" id="circle_housing">
                                            <option value="A">All</option>
                                            @foreach ($circleDetails as $circleDetail)
                                                @if ($circleDetail->dept_cd == 6)
                                                    <option value={{ $circleDetail->circle_cd }}>
                                                        {{ $circleDetail->circle_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="division">Division </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase"
                                            name="division_housing" id="division_housing">
                                            <option value="A">All</option>
                                            @foreach ($divisionDetails as $divisionDetail)
                                                @if ($divisionDetail->dept_cd == 6)
                                                    <option value={{ $divisionDetail->division_cd }}>
                                                        {{ $divisionDetail->division_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="subDivision">Sub-Division </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase for-control"
                                            name="subDivision_housing" id="subDivision_housing">
                                            <option value="A">All</option>
                                            @foreach ($subDivisionDetails as $subDivisionDetail)
                                                @if ($subDivisionDetail->dept_cd == 6)
                                                    <option value={{ $subDivisionDetail->sub_div_cd }}>
                                                        {{ $subDivisionDetail->sub_div_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <br>
                                        <button type="submit" class="text-xs btn btn-sm btn-outline-primary">
                                            View
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="mech">
                            <form class="frm_unloack_for_mech" id="frm_unloack_for_mech" method="POST" action="">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="zone">Zone </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase"
                                            name="zone_mech" id="zone_mech">
                                            <option value="A">All</option>
                                            @foreach ($zoneDetails as $zoneDetail)
                                                @if ($zoneDetail->dept_cd == 15)
                                                    <option value={{ $zoneDetail->zone_cd }}>
                                                        {{ $zoneDetail->zone_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="circle">Circle </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase"
                                            name="circle_mech" id="circle_mech">
                                            <option value="A">All</option>
                                            @foreach ($circleDetails as $circleDetail)
                                                @if ($circleDetail->dept_cd == 15)
                                                    <option value={{ $circleDetail->circle_cd }}>
                                                        {{ $circleDetail->circle_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="division">Division </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase"
                                            name="division_mech" id="division_mech">
                                            <option value="A">All</option>
                                            @foreach ($divisionDetails as $divisionDetail)
                                                @if ($divisionDetail->dept_cd == 15)
                                                    <option value={{ $divisionDetail->division_cd }}>
                                                        {{ $divisionDetail->division_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="subDivision">Sub-Division </label>
                                        <select style="width:10rem;" class="custom_select text-uppercase for-control"
                                            name="subDivision_mech" id="subDivision_mech">
                                            <option value="A">All</option>
                                            @foreach ($subDivisionDetails as $subDivisionDetail)
                                                @if ($subDivisionDetail->dept_cd == 15)
                                                    <option value={{ $subDivisionDetail->sub_div_cd }}>
                                                        {{ $subDivisionDetail->sub_div_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <br>
                                        <button type="submit" class="text-xs btn btn-sm btn-outline-primary">
                                            View
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        var zn_dtls = <?php echo json_encode($zn_dtls); ?>;
        var crcl_dtls = <?php echo json_encode($crcl_dtls); ?>;
        var dv_dtls = <?php echo json_encode($dv_dtls); ?>;
        var sub_dv_dtls = <?php echo json_encode($sb_dv_dtls); ?>;

        $("#zone_rnb").on("change", function() {
            var zn_cd = $("#zone_rnb").val();

            $("#circle_rnb").empty();
            $('#circle_rnb').append('<option value="A">All</option>');

            $("#division_rnb").empty();
            $('#division_rnb').append('<option value="A">All</option>');

            $("#subDivision_rnb").empty();
            $('#subDivision_rnb').append('<option value="A">All</option>');

            if (zn_cd == "A") {

                $.each(crcl_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#circle_rnb').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                    }
                });
                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#division_rnb').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#subDivision_rnb').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.zone_cd) == (zn_cd))
                        $('#circle_rnb').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                });


                $("#division_rnb").empty();
                $('#division_rnb').append('<option value="A">All</option>');

                $("#subDivision_rnb").empty();
                $('#subDivision_rnb').append('<option value="A">All</option>');
            }
        });

        $("#circle_rnb").on("change", function() {
            var crcl_cd = $("#circle_rnb").val();
            $("#division_rnb").empty();
            $('#division_rnb').append('<option value="A">All</option>');

            $("#subDivision_rnb").empty();
            $('#subDivision_rnb').append('<option value="A">All</option>');
            if (crcl_cd == "A") {
                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#division_rnb').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#subDivision_rnb').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });
            } else {
                $.each(dv_dtls, function(index, value) {
                    if ((value.circle_cd) == (crcl_cd))
                        $('#division_rnb').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                });

            }
        });

        $("#division_rnb").on("change", function() {
            var div_cd = $("#division_rnb").val();
            $("#subDivision_rnb").empty();
            $('#subDivision_rnb').append('<option value="A">All</option>');

            if (div_cd == "A") {

                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 14) {
                        $('#subDivision_rnb').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });
            } else {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.div_cd) == (div_cd))
                        $('#subDivision_rnb').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                });

                // $("#subDivision_rnb").empty();
                // $('#subDivision_rnb').append('<option value="A">All</option>');
            }
        });


        $("#zone_nh").on("change", function() {
            var zn_cd = $("#zone_nh").val();

            $("#circle_nh").empty();
            $('#circle_nh').append('<option value="A">All</option>');

            $("#division_nh").empty();
            $('#division_nh').append('<option value="A">All</option>');

            $("#subDivision_nh").empty();
            $('#subDivision_nh').append('<option value="A">All</option>');

            if (zn_cd == "A") {

                $.each(crcl_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#circle_nh').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                    }
                });
                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#division_nh').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#subDivision_nh').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(crcl_dtls, function(index, value) {
                    console.log(value.zone_cd);
                    if ((value.zone_cd) == (zn_cd))
                        $('#circle_nh').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                });



            }
        });


        $("#circle_nh").on("change", function() {
            var crcl_cd = $("#circle_nh").val();
            $("#division_nh").empty();
            $('#division_nh').append('<option value="A">All</option>');

            $("#subDivision_nh").empty();
            $('#subDivision_nh').append('<option value="A">All</option>');

            if (crcl_cd == "A") {


                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#division_nh').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#subDivision_nh').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(dv_dtls, function(index, value) {
                    if ((value.circle_cd) == (crcl_cd))
                        $('#division_nh').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                });
            }
        });

        $("#division_nh").on("change", function() {
            var div_cd = $("#division_nh").val();
            $("#subDivision_nh").empty();
            $('#subDivision_nh').append('<option value="A">All</option>');
            if (div_cd == "A") {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 3) {
                        $('#subDivision_nh').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.div_cd) == (div_cd))
                        $('#subDivision_nh').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                });
            }
        });


        $("#zone_housing").on("change", function() {
            var zn_cd = $("#zone_housing").val();

            $("#circle_housing").empty();
            $('#circle_housing').append('<option value="A">All</option>');

            $("#division_housing").empty();
            $('#division_housing').append('<option value="A">All</option>');

            $("#subDivision_housing").empty();
            $('#subDivision_housing').append('<option value="A">All</option>');

            if (zn_cd == "A") {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#circle_housing').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                    }
                });
                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#division_housing').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#subDivision_housing').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.zone_cd) == (zn_cd))
                        $('#circle_housing').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                });
            }
        });


        $("#circle_housing").on("change", function() {
            var crcl_cd = $("#circle_housing").val();
            $("#division_housing").empty();
            $('#division_housing').append('<option value="A">All</option>');

            $("#subDivision_housing").empty();
            $('#subDivision_housing').append('<option value="A">All</option>');
            if (crcl_cd == "A") {

                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#division_housing').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#subDivision_housing').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(dv_dtls, function(index, value) {
                    if ((value.circle_cd) == (crcl_cd))
                        $('#division_housing').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                });
            }
        });

        $("#division_housing").on("change", function() {
            var div_cd = $("#division_housing").val();
            $("#subDivision_housing").empty();
            $('#subDivision_housing').append('<option value="A">All</option>');
            if (div_cd == "A") {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 6) {
                        $('#subDivision_housing').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.div_cd) == (div_cd))
                        $('#subDivision_housing').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                });
            }
        });


        $("#zone_mech").on("change", function() {
            var zn_cd = $("#zone_mech").val();

            $("#circle_mech").empty();
            $('#circle_mech').append('<option value="A">All</option>');

            $("#division_mech").empty();
            $('#division_mech').append('<option value="A">All</option>');

            $("#subDivision_mech").empty();
            $('#subDivision_mech').append('<option value="A">All</option>');

            if (zn_cd == "A") {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#circle_mech').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                    }
                });
                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#division_mech').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#subDivision_mech').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(crcl_dtls, function(index, value) {
                    if ((value.zone_cd) == (zn_cd))
                        $('#circle_mech').append('<option value="' + value.circle_cd + '">' + value
                            .circle_name +
                            '</option>');
                });
            }
        });


        $("#circle_mech").on("change", function() {
            var crcl_cd = $("#circle_mech").val();
            $("#division_mech").empty();
            $('#division_mech').append('<option value="A">All</option>');

            $("#subDivision_mech").empty();
            $('#subDivision_mech').append('<option value="A">All</option>');

            if (crcl_cd == "A") {

                $.each(dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#division_mech').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                    }
                });
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#subDivision_mech').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(dv_dtls, function(index, value) {
                    if ((value.circle_cd) == (crcl_cd))
                        $('#division_mech').append('<option value="' + value.division_cd + '">' + value
                            .division_name +
                            '</option>');
                });
            }
        });

        $("#division_mech").on("change", function() {
            var div_cd = $("#division_mech").val();
            $("#subDivision_mech").empty();
            $('#subDivision_mech').append('<option value="A">All</option>');
            if (div_cd == "A") {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.dept_cd) == 15) {
                        $('#subDivision_mech').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                    }
                });

            } else {
                $.each(sub_dv_dtls, function(index, value) {
                    if ((value.div_cd) == (div_cd))
                        $('#subDivision_mech').append('<option value="' + value.sub_div_cd + '">' + value
                            .sub_div_name +
                            '</option>');
                });
            }
        });
    </script>
@endpush
