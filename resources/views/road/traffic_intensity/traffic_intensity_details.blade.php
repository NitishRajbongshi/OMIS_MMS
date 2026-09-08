<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <style>
        .mainBody {
            background-color: #FEFBFA;
            margin: 3px 10px 5px 10px;
            box-shadow: 2px 2px 2px 2px grey;
            color: #11113B;
        }

        .row {
            padding-left: 2%;
            padding-right: 2%;
            padding-top: 1%;
        }

        label {
            font-size: 14px;
        }

        .fl {
            border: 2px solid blue !important;
        }

        legend {
            line-height: 30px;
            font-weight: 600;
            text-transform: uppercase;
            color: black;
            padding: 2px 2px 2px 2px;
            border: 2px solid #5677E7;
        }

        .star {
            color: red;
        }

        input[type="checkbox"] {
            width: 15px;
            height: 15px;
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
                    <div class="row mb-2 py-2">
                        <div class="col-sm-6 col-md-10">
                            <ol class="breadcrumb float-sm-left">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">Road</a>
                                </li>
                                <li class="breadcrumb-item">Add Traffic Intensity</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid mainBody py-3">
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
                    <div class="my-4">
                        <fieldset class="border p-3 fl">
                            <legend class="w-auto px-2" style="font-size:16px">Road Information</legend>
                            <div class="">
                                <span class="border p-2">Road Number: <span
                                        class="text-bold">{{ session('road_number') }}</span></span>
                                <span class="border p-2">Road Name: <span
                                        class="text-bold">{{ session('road_name') }}</span></span>
                            </div>
                        </fieldset>
                    </div>

                    <form action="{{ route('road.store-traffic-intensity') }}" method="post" autocomplete="off">
                        @csrf
                        <fieldset class="border p-3 fl">

                            <legend class="w-auto px-2" style="font-size:16px">Traffic Intensity</legend>
                            {{-- Hidden field --}}
                            <div class="row form-1-box">
                                <div class="col-md-3">
                                    <input type="hidden" id="road_system_id" class="form-control" name="road_system_id"
                                        value="{{ session('system_id') }}">
                                </div>
                            </div>
                            <div class="row form-1-box">
                                <div class="col-md-3">
                                    <label for="year">Year <span class="star">*</span></label>
                                    <select id="year" name="year" class="form-control">
                                    </select>
                                    @error('year')
                                        <div class="text-red-500 mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="total_motorized_traffic_per_day">Total Motorized Traffic / Day <span
                                            class="star">*</span></label>
                                    <input type="text" id="total_motorized_traffic_per_day" class="form-control"
                                        name="total_motorized_traffic_per_day" />
                                    @error('total_motorized_traffic_per_day')
                                        <div class="text-red-500 mt-2 text-sm">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="commercial_vehicle_traffic_per_day">Commercial Vehicle Traffic / Day
                                        <span class="star">*</span></label>
                                    <input type="text" id="commercial_vehicle_traffic_per_day" class="form-control"
                                        name="commercial_vehicle_traffic_per_day" />
                                    @error('commercial_vehicle_traffic_per_day')
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
                    </form>
                </div>

                <div class='card mt-4'>
                    <div class="card-header text-dark" style="background-color:#C8C8C8">
                        <h3 class="card-title" style="font-weight: bold; text-transform:uppercase;">CD Work Details
                        </h3>
                    </div>
                    <div class='card-body text-xs'>
                        <table class="table table-bordered table-striped user_list w-100" id="traffic_intensity_table">
                            <thead class="theader" style="background-color:#C8C8C8">
                                <th class="text-center">SLNo.</th>
                                <th class="text-center">Intensity Year</th>
                                <th class="text-center">Motorized Traffic / Day</th>
                                <th class="text-center">Commertial Vehical Traffic / Day</th>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($trafficIntensityDetails as $key)
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td class="text-center">{{ $key->intensity_year }}</td>
                                        <td class="text-center">{{ $key->tot_motorized_traffic_per_day }}</td>
                                        <td class="text-center">{{ $key->tot_comm_veh_traffic_per_day }}</td>
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
            $("#traffic_intensity_table").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#traffic_intensity_table_wrapper .col-md-6:eq(1)');

        });

        $('.modalClose').click(function() {
            location.reload();
        });
    </script>

    <script>
        const dateRangeSelect = document.getElementById('year');

        // Function to generate the options for the dropdown
        function generateDateRangeOptions() {
            dateRangeSelect.innerHTML = ''; // Clear existing options

            for (let year = 2022; year >= 2010; year--) {
                const startYear = year;
                const endYear = year + 1;

                const option = document.createElement('option');
                option.value = `${startYear}-${endYear}`;
                option.textContent = `${startYear}-${endYear}`;

                dateRangeSelect.appendChild(option);
            }
        }

        // Call the function to generate initial options
        generateDateRangeOptions();
    </script>

</body>

</html>
