@extends('layouts.app')
@section('content')
    <div class="content-header mb-4">
        <div class="container-fluid">
            <div class="flex justify-between items-center">
                <div class="text-sm">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('manageRoad') }}">Manage Roads</a>
                        </li>
                        <li class="breadcrumb-item">Assign Chainage</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        @if (session('failed'))
            <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-info" aria-hidden="true"></i>
                <strong>Failed!</strong> {{ session('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check" aria-hidden="true"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="container-fluid mainBody py-2 px-3">
            <form id="hq_chainage" action="{{ route('HqChainage') }}" method="post">
                @csrf
                <div class="row p-2">
                    <div class="col-12 border row container mx-auto">
                        <p class="fw-bold border-bottom text-primary py-2 text-sm">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            Select a Road to start Chainage
                        </p>

                        <div class="col-12 col-md-6">
                            <label for="road_list_dropdown">Select Road <span class="text-danger">*</span></label>
                            <select id="road_list_dropdown" style="width: 100%" class="custom-select form-control"
                                name="road_list_dropdown">
                                <option value=''>Select Road</option>
                                @foreach ($roadDetails as $roadDetails)
                                    <option value="{{ $roadDetails->rd_system_id }}">
                                        {{ $roadDetails->rd_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('road_list_dropdown')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6" style="margin-top: 33px">
                            <button id='but_read' type="button" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-eye mr-1 text-xs"></i>
                                View Road Details
                            </button>
                            {{-- <input type='button'  value='View Road Details' > --}}
                        </div>
                        <div class="col-12 mb-2">
                            <div id='result'>
                            </div>
                        </div>
                        <div class="col-12 row mb-2" id="road-details-chainage" style="display: none;">
                            <div class="col-md-3">
                                <label for="road_number">Road Number <span class="star">*</span></label>
                                <input type="text" id="road_number" class="form-control" name="road_number" required
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="road_name">Road Name<span class="star">*</span></label>
                                <input type="text" id="road_name" class="form-control" name="road_name" required
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="road_length">Road Length <span class="star">*</span></label>
                                <input type="text" id="road_length" class="form-control" name="road_length" required
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="road_type">Road Type <span class="star">*</span></label>
                                <input type="text" id="road_type" class="form-control" name="road_type" required
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="district">District <span class="star">*</span></label>
                                <input type="text" id="district" class="form-control" name="district" required readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="block">Block <span class="star">*</span></label>
                                <input type="text" id="block" class="form-control" name="block" required readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <fieldset class="p-3 border">
                    <legend class="w-auto px-2" style="font-size:14px">Create Chainage</legend>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="chainage_level">Chainage Upto <span class="star">*</span></label>
                            <select name="chainage_level" class="form-control" id="chainage_level">
                                <option value="">Choose one</option>
                                <option value="ZO">Zonal Level</option>
                                <option value="CO">Circle Level</option>
                                <option value="DO">Division Level</option>
                                <option value="SDO">Sub Division Level</option>
                            </select>

                            @error('chainage_level')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-3" id="zone_container" style="display:none;">
                            <label for="zone_cd">Zone Name <span class="star">*</span></label>
                            <select name="zone_cd" class="form-control" id="zone_cd">
                                <option value="">Choose one</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->zone_cd }}">
                                        {{ $zone->zone_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3" id="circle_container" style="display:none;">
                            <label for="circle_cd">Circle Name <span class="star">*</span></label>
                            <select name="circle_cd" class="form-control" id="circle_cd">
                                <option value="">Choose one</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="division_container" style="display:none;">
                            <label for="division_cd">Division Name <span class="star">*</span></label>
                            <select name="division_cd" class="form-control" id="division_cd">
                                <option value="">Choose one</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="sub_division_container" style="display:none;">
                            <label for="sub_division_cd">Sub Division Name <span class="star">*</span></label>
                            <select name="sub_division_cd" class="form-control" id="sub_division_cd">
                                <option value="">Choose one</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2"><i class="fa fa-save"></i>
                    Save</button>
                <button type="reset" class="btn btn-info btn-sm rounded-0 mt-2">
                    <i class="fa fa-undo" aria-hidden="true"></i>
                    Reset
                </button>
                <button class="btn btn-secondary btn-sm rounded-0 mt-2"><i class="fa fa-backward"></i><a
                        class="text-white" href="{{ route('manageRoad') }} ">
                        Cancel</a></button>
                {{-- <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2 px-2 rounded-1">
                                    <i class="fa fa-save"></i>
                                    Save
                                </button>
                            </div>
                        </div> --}}

            </form>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        // function to display field level wise
        function showHide(param) {
            console.log(param);
            $("#zone_container").hide();
            $("#circle_container").hide();
            $("#division_container").hide();
            $("#sub_division_container").hide();
            if (param === 'ZO') {
                $('#zone_container').show();
            }
            if (param === 'CO') {
                $('#zone_container').show();
                $("#circle_container").show();
            }
            if (param === 'DO') {
                $('#zone_container').show();
                $("#circle_container").show();
                $("#division_container").show();
            }
            if (param === 'SDO') {
                $('#zone_container').show();
                $("#circle_container").show();
                $("#division_container").show();
                $("#sub_division_container").show();
            }
        }

        $(document).ready(function() {
            $("#road_list_dropdown").select2();
            $('#road-details-chainage').hide();

            $('#but_read').click(function() {
                var roadName = $('#road_list_dropdown option:selected').text();
                var roadId = $('#road_list_dropdown').val();
                console.log(roadId);
                if (roadId === '') {
                    $('#road-details-chainage').hide();
                    $('#result').empty();
                    alert("Select a road!");
                } else {
                    // ajax call to road details
                    $.ajax({
                        type: 'GET',
                        url: '/asset-management/get-road-by-id/' + roadId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        cache: false,
                        success: function(response) {
                            // console.log(response);
                            if (response.status === 'success') {
                                $('#road_id').val(response.result.rd_system_id);
                                $('#road_number').val(response.result.rd_number);
                                $('#road_name').val(response.result.rd_name);
                                $('#road_length').val(response.result.road_length);
                                $('#road_type').val(response.result.road_type);
                                $('#district').val(response.result.district_name);
                                $('#block').val(response.result.block_name);

                                // message to confirm that the road has selected
                                $('#result').html(
                                    "<p class='text-primary text-xs'><i class='fa fa-check-circle mr-1' aria-hidden='true'></i>Road Selected Successfully</p>"
                                );

                                $('#road-details-chainage').show();
                            }
                            if (response.status === 'failed') {
                                alert(response.message);
                            }
                        }
                    })
                }
            });

            $('#chainage_level').on('change', function() {
                // console.log('changed');
                const level = $(this).val();
                showHide(level);
            })
        });

        // get circle
        $('#zone_cd').on('change', function() {
            const zone_cd = $(this).val();
            $.ajax({
                type: 'GET',
                url: '/asset-management/get-circle/' + zone_cd,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        const selectCircle = $('#circle_cd');
                        selectCircle.empty();
                        selectCircle.append('<option value="">Choose one</option>');
                        $.each(response.result, function(index, circle) {
                            selectCircle.append('<option value="' + circle.circle_cd +
                                '">' + circle.circle_name + '</option>');
                        });
                        $('#block').prop('disabled', false);
                    }
                    if (response.status === 'failed') {
                        alert(response.message);
                    }
                }
            });
        })

        // get division
        $('#circle_cd').on('change', function() {
            const circle_cd = $(this).val();
            $.ajax({
                type: 'GET',
                url: '/asset-management/get-division/' + circle_cd,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        const selectDivision = $('#division_cd');
                        selectDivision.empty();
                        selectDivision.append('<option value="">Choose one</option>');
                        $.each(response.result, function(index, division) {
                            selectDivision.append('<option value="' + division.division_cd +
                                '">' + division.division_name + '</option>');
                        });
                        $('#block').prop('disabled', false);
                    }
                    if (response.status === 'failed') {
                        alert(response.message);
                    }
                }
            });
        })

        // get sub division
        $('#division_cd').on('change', function() {
            const division_cd = $(this).val();
            $.ajax({
                type: 'GET',
                url: '/asset-management/get-sub-division/' + division_cd,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        const selectSubDivision = $('#sub_division_cd');
                        selectSubDivision.empty();
                        selectSubDivision.append('<option value="">Choose one</option>');
                        $.each(response.result, function(index, subDivision) {
                            selectSubDivision.append('<option value="' + subDivision
                                .sub_div_cd +
                                '">' + subDivision.sub_div_name + '</option>');
                        });
                        $('#block').prop('disabled', false);
                    }
                    if (response.status === 'failed') {
                        alert(response.message);
                    }
                }
            });
        })
    </script>
@endpush
