<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chainage Details</title>
    <style>
        .mainBody {
            background-color: #FEFBFA;
            margin: 3px 10px 5px 10px;
            color: #11113B;
        }

        label {
            font-size: 12px;
        }

        legend {
            line-height: 30px;
            font-weight: 600;
            text-transform: uppercase;
            color: black;
            padding: 2px 2px 2px 2px;
            border: 2px solid #5677E7;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('layouts/header')
        @include('sweet::alert')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row text-sm">
                        <div class="col-sm-6 col-md-10">
                            <ol class="breadcrumb float-sm-left">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('manageRoad') }}">Manage Roads</a>
                                </li>
                                <li class="breadcrumb-item">View Chainage Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid mainBody border py-3">
                    @if (session('failed'))
                        <div class="alert alert-danger">
                            {{ session('failed') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('invalid'))
                        <div class="alert alert-warning text-white">
                            {{ session('invalid') }}
                        </div>
                    @endif

                    <x-road-info :roadChainage="$roadChainage" />
                    {{-- <div class="text-sm">
                        <fieldset class="border p-3 fl">
                            <legend class="w-auto px-2 mb-3" style="font-size:16px">Road Information</legend>
                            <div class="">
                                <span class="border p-2">Road Number: <span
                                        class="text-bold">{{ session('road_number') }}</span></span>
                                <span class="border p-2">Road Name: <span
                                        class="text-bold">{{ session('road_name') }}</span></span>
                            </div>
                            @if (session('warning'))
                                <div class="text-danger my-2">
                                    <p><span class="text-bold"><i class="fa fa-exclamation-circle " style="margin-right: 4px;" aria-hidden="true"></i></span>{{ session()->pull('warning') }}</p>
                                </div>
                            @endif
                        </fieldset>
                    </div> --}}
                    {{-- <form action="{{ route('road.store-crb-value') }}" method="post" autocomplete="off">
                        @csrf
                        <fieldset class="border p-3 fl">
                            <legend class="w-auto px-2" style="font-size:16px">CBR Value</legend>
                            <div class="row form-1-box">
                                <div class="col-md-3">
                                    <input type="hidden" id="road_system_id" class="form-control" name="road_system_id"
                                        value="{{ session('system_id') }}">
                                </div>
                            </div>
                            <div class="row form-1-box">
                                <div class="col-md-3">
                                    <label for="start_chainage">Start Chainage:<span class="star">*</span></label>
                                    <input type="text" id="start_chainage" class="form-control" name="start_chainage"
                                        value="{{ $totalSegmentValue->cbr_end_chainage ?? '0' }}" />
                                    @error('start_chainage')
                                        <div class="text-red-500 mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="end_chainage">End Chainage (in KMs):<span
                                            class="star">*</span></label>
                                    <input type="text" id="end_chainage" class="form-control" name="end_chainage" />
                                    @error('end_chainage')
                                        <div class="text-red-500 mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="segment_length">Segment Length:<span class="star">*</span></label>
                                    <input type="text" id="segment_length" class="form-control" name="segment_length"
                                        readonly />
                                    @error('segment_length')
                                        <div class="text-red-500 mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="cbr_value">CBR Value:<span class="star">*</span></label>
                                    <input type="text" id="cbr_value" class="form-control" name="cbr_value" />
                                    @error('cbr_value')
                                        <div class="text-red-500 mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-1-box">
                                <div class="col-md-3">
                                    <label for="total_length_of_the_road">Total Length of the Road:<span
                                            class="star">*</span></label>
                                    <input type="text" id="total_length_of_the_road" class="form-control"
                                        name="total_length_of_the_road" value="{{ session('road_length') }}" required
                                        readonly />
                                    @error('total_length_of_the_road')
                                        <div class="text-red-500 mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="total_entered_segment_length">Total Entered Segment Length:<span
                                            class="star">*</span></label>
                                    <input type="text" id="total_entered_segment_length" class="form-control"
                                        name="total_entered_segment_length"
                                        value="{{ $totalSegmentValue->tot_segment_length ?? '0' }}" required
                                        readonly />
                                    @error('total_entered_segment_length')
                                        <div class="text-red-500 mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label for="remaining_length">Remaining Length<span
                                            class="star">*</span></label>
                                    <input type="text" id="remaining_length" class="form-control"
                                        name="remaining_length" required readonly>
                                    @error('remaining_length')
                                        <div class="text-red-500 mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>
                        <button type="submit" class="btn btn-success btn-md rounded-0 mt-2" style="width:10%"><i
                                class="fa fa-save"></i> Submit</button>
                        <button class="btn btn-danger btn-md rounded-0 mt-2" style="width:10%"><i
                                class="fa fa-save"></i><a class="text-white" href="{{ route('manageRoad') }} ">
                                Cancel</a></button>
                    </form> --}}
                </div>

                <!-- table content -->
                <div class="container-fluid border mainBody py-3">
                    <div class="container-fluid mt-3">
                        <h6 class="text-center text-sm">
                            <span class="border text-blue-800 px-3 py-1">
                                Chainage Details
                            </span>
                        </h6>
                        <table class="table-responsive text-xs table table-bordered table-striped user_list"
                            id="cbr_value_table">
                            <thead class="theader text-white" style="background-color:#417DBE">
                                <th class="text-center">Serial No.</th>
                                <th class="text-center">Start Chainage Value (Km)</th>
                                <th class="text-center">End Chainage Value (Km)</th>
                                <th class="text-center">Remaining Chainage (Km)</th>
                                <th class="text-center">Zone Name</th>
                                <th class="text-center">Circle Name</th>
                                <th class="text-center">Division Name</th>
                                <th class="text-center">Sub Division Name</th>
                            </thead>

                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($CbrDetails as $key)
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td class="text-center">{{ $key->chainage_from }}</td>
                                        <td class="text-center">{{ $key->chainage_to }}</td>
                                        <td class="text-center">{{ $key->remaining_chainage_length }}</td>
                                        <td class="text-center">{{ $key->zone_name }}</td>
                                        <td class="text-center">{{ $key->circle_name }}</td>
                                        <td class="text-center">{{ $key->division_name }}</td>
                                        <td class="text-center">{{ $key->sub_div_name }}</td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>


    </div>

    @include('layouts/footer')

    <script type="text/javascript">
        $(function() {
            $("#cbr_value_table").DataTable({}).buttons().container().appendTo(
                '#cbr_value_table_wrapper .col-md-11:eq(1)');
        });

        $('.modalClose').click(function() {
            location.reload();
        });
    </script>

    {{-- Calculating The segment length --}}
    <script>
        const start_chainage = document.getElementById('start_chainage');
        const end_chainage = document.getElementById('end_chainage');
        const segment_length = document.getElementById('segment_length');

        const total_rd_length = document.getElementById('total_length_of_the_road');
        const total_segment_length = document.getElementById('total_entered_segment_length');
        const remaining_segment_length = document.getElementById('remaining_length');

        // get the road length and the total segment length
        const totalRoadLength = parseFloat(total_rd_length.value);
        const totalSegmentLength = parseFloat(total_segment_length.value);

        // display the remaining segment length
        const remainingSegLength = totalRoadLength - totalSegmentLength;
        remaining_segment_length.value = remainingSegLength.toFixed(3);

        function calculateSegmentLength() {
            const startChainageValue = parseFloat(start_chainage.value);
            const endChainageValue = parseFloat(end_chainage.value);

            if (!isNaN(startChainageValue) && !isNaN(endChainageValue)) {
                const segmentValue = endChainageValue - startChainageValue;
                segment_length.value = segmentValue;

                const newTotalSegmentLengthValue = totalSegmentLength + segmentValue;
                total_segment_length.value = newTotalSegmentLengthValue.toFixed(3);
                const remainingLength = totalRoadLength - newTotalSegmentLengthValue;
                if (remainingLength >= 0) {
                    remaining_segment_length.value = remainingLength.toFixed(3);
                } else {
                    remaining_segment_length.value = remainingLength.toFixed(3);
                    alert('Invalid! Remaining length can\'t be negative');
                }
            }
        }

        start_chainage.addEventListener('input', calculateSegmentLength);
        end_chainage.addEventListener('input', calculateSegmentLength);
    </script>
</body>

</html>
