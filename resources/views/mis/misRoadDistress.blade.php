@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="#" class="mr-2">mis</a>/ Road Distress
        </div>
    </div>
    <!-- Main content -->
    <div class="border" style="border-radius: .3rem;">
        <div class="row justify-content-between align-item-center">
            <form id="frm_rd_distress" class="mt-2 mb-1" method="post" action="{{ route('filterRoadDistresses') }}">
                @csrf
                <div class="row justify-content-center align-items-start">
                    <div class="col-sm-12 col-md-11 row">
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="me-1">
                                <label for="zone">Zone</label>
                            </div>
                            <select style="width: 100%;" class="custom_select text-uppercase text-xs" name="zone"
                                id="zone">
                                <option value="A">All Zones</option>
                                @foreach ($zoneDetails as $zoneDetail)
                                    <option value={{ $zoneDetail->zone_cd }}>
                                        {{ $zoneDetail->zone_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="me-1">
                                <label for="circle">Circle</label>
                            </div>
                            <select style="width:100%;" class="custom_select text-uppercase text-xs" name="circle"
                                id="circle">
                                <option value="A">All Circles</option>
                                @foreach ($circleDetails as $circleDetail)
                                    <option value={{ $circleDetail->circle_cd }}>
                                        {{ $circleDetail->circle_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="me-1">
                                <label for="division">Division</label>
                            </div>
                            <select style="width:100%;" class="custom_select text-uppercase text-xs" name="division"
                                id="division">
                                <option value="A">All Divisions</option>
                                @foreach ($divisionDetails as $divisionDetail)
                                    <option value={{ $divisionDetail->division_name }}>
                                        {{ $divisionDetail->division_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-11 row">
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="me-1">
                                <label for="type">Distress Type</label>
                            </div>
                            <select style="width:50%;" class="custom_select text-uppercase text-xs" name="distressType"
                                id="distressType">
                                <option value="A">All</option>
                                @foreach ($distressTypes as $item)
                                    <option value={{ $item->distress_type_cd }}>
                                        {{ $item->distress_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="me-1">
                                <label for="category">Retore Status</label>
                            </div>
                            <select style="width:50%;" class="custom_select text-uppercase text-xs" name="restore_status"
                                id="restore_status">
                                <option value="A">All</option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-11 row">
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="me-1">
                                <label for="distres_from_date">Date from:</label>
                            </div>
                            <input style="width: 50%; height: 70%;" type="date" name="distres_from_date"
                                id="distres_from_date" class="form-control">
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <div class="me-1">
                                <label for="distres_to_date">Date To:</label>
                            </div>
                            <input style="width: 50%; height: 70%;" type="date" name="distres_to_date"
                                id="distres_to_date" class="form-control">

                        </div>

                        <div class="col-sm-12 col-md-1 d-flex justify-content-end">
                            <button type="submit" class="text-xs btn btn-xs btn-outline-primary">
                                View
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <section class="content" id="roadDistressDtlsSection" name="roadDistressDtlsSection">
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-md-2 mb-1">
                    <label for="name" class="col-form-label">Road Distress Report:</label>
                </div>
            </div>
            <span class="spanRoadDistressDetail text-center"></span>
            <table class="table-responsive table text-xs table-hover table-bordered table-striped user_list"
                id="tblRoadDistressDetail">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center">SL No.</th>
                    <th class="text-center">Road Name</th>
                    <th class="text-center">District</th>
                    <th class="text-center">Block Name</th>
                    <th class="text-center">Division Name</th>
                    <th class="text-center">Distress Type</th>
                    <th class="text-center">Distress From</th>
                    <th class="text-center">Distress To</th>
                    <th class="text-center">Distress Length(KM)</th>
                    <th class="text-center">Date of Occurance</th>
                    <th class="text-center">No of Days To Restore</th>
                    <th class="text-center">Restore Status</th>
                    <th class="text-center">Remark</th>

                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($distrs_dtls as $key)
                        @php

                            $date_occur = \Carbon\Carbon::parse($key->date_of_occurance);
                            $todays_date = \Carbon\Carbon::now();
                            $date_diff = $date_occur->diffInDays($todays_date);
                        @endphp
                        @if ($date_diff > $key->days_to_restore)
                            <tr class="table-danger">
                            @else
                            <tr class="table-primary">
                        @endif
                        <td class="text-center">{{ $i }}</td>
                        <td style="position: relative">
                            {{ $key->rd_name }}
                        </td>
                        <td style="position: relative">
                            {{ $key->district_name }}
                        </td>
                        <td style="position: relative">
                            {{ $key->block_name }}
                        </td>
                        <td style="position: relative">
                            {{ $key->division_name }}
                        </td>
                        <td style="position: relative">
                            {{ $key->distress_type_descr }}
                        </td>
                        <td style="position: relative">
                            {{ $key->start_landmark }}
                        </td>
                        <td style="position: relative">
                            {{ $key->end_landmark }}
                        </td>
                        <td style="position: relative">
                            {{ $key->distress_length_in_km }}
                        </td>
                        <td style="position: relative">
                            {{ $key->date_of_occurance }}
                        </td>
                        <td style="position: relative">
                            {{ $key->days_to_restore }}
                        </td>
                        <td style="position: relative">
                            Not Restored
                        </td>
                        <td style="position: relative">
                            {{ $key->distress_remarks }}
                        </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('#tblRoadDistressDetail').DataTable({
                "buttons": [{
                        extend: 'copy',
                        className: 'btn btn-primary glyphicon glyphicon-duplicate'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-primary glyphicon glyphicon-save-file'
                    }, ,
                    {
                        extend: 'excel',
                        className: 'btn btn-primary glyphicon glyphicon-save-file'
                    }, ,
                    {
                        extend: 'pdf',
                        className: 'btn btn-primary glyphicon glyphicon-save-file'
                    }
                ]
            }).buttons().container().appendTo('.spanRoadDistressDetail');
        });


        $('.modalClose').click(function() {
            //location.reload();
            $('.modal-body :input:not([readonly]), .modal-body textarea:not([readonly])').val('');
        });

        $('#modalDate').on('change', function() {
            var selectedDate = $(this).val();
            var dateObj = new Date(selectedDate);
            var year = dateObj.getFullYear();
            var month = ('0' + (dateObj.getMonth() + 1)).slice(-2);
            var day = ('0' + dateObj.getDate()).slice(-2);
            var formattedDate = year + '-' + month + '-' + day;
            $(this).val(formattedDate);
        });
    </script>

    <script>
        $(document).ready(function() {
            const zoneDetails = @json($zoneDetails);
            const circleDetails = @json($circleDetails);
            const divisionDetails = @json($divisionDetails);

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

                if (selectedZone === 'A') {
                    $.each(circleDetails, function(index, value) {
                        if ((value.dept_cd) == 14) {
                            $('#circle').append('<option value="' + value.circle_cd + '">' + value
                                .circle_name +
                                '</option>');
                        }
                    });
                    $.each(divisionDetails, function(index, value) {
                        if ((value.dept_cd) == 14) {
                            $('#division').append('<option value="' + value.division_cd + '">' +
                                value
                                .division_name +
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
                if (selectedCircle == 'A') {
                    $('.show_circle').html('All Circles');
                } else {
                    $.each(circleDetails, function(index, value) {
                        if ((value.circle_cd) == selectedCircle) {
                            $('.show_circle').html(value.circle_name);
                        }
                    });
                }

                $("#division").empty().append('<option value="null">ALL DIVISIONS</option>');
                if (selectedCircle === 'A') {
                    const selectedZone = $("#zone").val();
                    console.log("selected zone: ", selectedZone);
                    $.each(divisionDetails, function(index, value) {
                        if ((value.dept_cd == 14) && (value.zone_cd == selectedZone)) {
                            $('#division').append('<option value="' + value.division_cd + '">' +
                                value.division_name + '</option>');
                        }
                    });

                } else {
                    $.each(divisionDetails, function(index, value) {
                        if ((value.circle_cd) === selectedCircle)
                            $('#division').append('<option value="' + value.division_cd + '">' +
                                value.division_name + '</option>');
                    });
                }
            })

            $("#division").on("change", function() {
                const selectedDivision = $("#division").val();

                // get the circle name and show in the report
                if (selectedDivision == 'A') {
                    $('.show_division').html('All Divisions');
                } else {
                    $.each(divisionDetails, function(index, value) {
                        if ((value.division_cd) == selectedDivision) {
                            $('.show_division').html(value.division_name);
                        }
                    });
                }
                if (selectedDivision === 'A') {
                    const selectedZone = $("#zone").val();
                    const selectedCircle = $("#circle").val();
                    // if no circle is selected
                    if (selectedCircle === 'A') {
                        $.each(subDivisionDetails, function(index, value) {
                            if ((value.dept_cd == 14) && (value.zone_cd == selectedZone)) {
                                $('#subDivision').append('<option value="' + value.sub_div_cd +
                                    '">' + value
                                    .sub_div_name +
                                    '</option>');
                            }
                        });
                    } else {

                    }
                } else {

                }
            })
        });
    </script>
@endpush
