@extends('layouts.index')
@section('content')
    <main class="public-list-page">
        <section class="public-list-hero">
            <div class="public-list-brand">
                <img src="{{ asset('images/main_logo.png') }}" alt="Nagaland PWD logo">
                <div>
                    <span class="public-list-eyebrow">Citizen Asset Information</span>
                    <h1>Mechanical Vehicle Registry</h1>
                    <p>List of vehicle details under Nagaland PWD Mechanical.</p>
                </div>
            </div>
            <div class="public-list-actions">
                <button type="button" class="oamis-theme-toggle" data-theme-toggle aria-pressed="false">
                    <i class="fas fa-moon"></i><span>Dark</span>
                </button>
                <a href="{{ route('getWelcomeDashBoard') }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </section>
        <section class="public-list-card">
            <div class="public-list-title">
                <h2>Vehicle details</h2>
                <span>Mechanical</span>
            </div>
            <div class="public-list-table-wrap">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                        id="vehicle_details_table">
                        <thead class="theader text-white" style="background-color:#417DBE">
                            <th class="text-center">Sl No.</th>
                            <th class="text-center">Vehicle Code</th>
                            <th class="text-center">Vehicle Name</th>
                            <th class="text-center">Registration No.</th>
                            <th class="text-center">Chassis No.</th>
                            <th class="text-center">Engine No.</th>
                            <th class="text-center">Vehicle Type</th>
                            <th class="text-center">Seat Capacity</th>
                            <th class="text-center">Total Wheels</th>
                            <th class="text-center">Vehicle Maker</th>
                            <th class="text-center">Vehicle Model</th>
                            <th class="text-center">Fuel Type</th>
                            {{-- <th class="text-center">Purchased Date</th> --}}
                            {{-- <th class="text-center">Vehicle Cost</th> --}}
                            <th class="text-center">Vehicle Condition</th>
                            <th class="text-center">Laden Weight</th>
                            <th class="text-center">Unladen Weight</th>
                            <th class="text-center">Alloted To</th>
                            <th class="text-center">Alloted From</th>
                            <th class="text-center">Vehicle Remarks</th>
                        </thead>

                        <tbody>
                            <?php $i = 1; ?>

                            @foreach ($vehicleDetails as $item)
                                <tr class="text-center">
                                    <td>{{ $i }}</td>
                                    <td>
                                        {{ $item->vehicle_asset_cd }}
                                    </td>
                                    <td>
                                        {{ $item->vehicle_name }}
                                    </td>
                                    <td>
                                        {{ $item->vehicle_regn_no }}
                                    </td>
                                    <td>
                                        {{ $item->chassis_no }}
                                    </td>
                                    <td>
                                        {{ $item->engine_no }}
                                    </td>
                                    <td>
                                        {{ $item->veh_type_descr }}
                                    </td>
                                    <td>
                                        {{ $item->seating_capacity }}
                                    </td>
                                    <td>
                                        {{ $item->no_of_wheels }}
                                    </td>
                                    <td>
                                        {{ $item->maker_name }}
                                    </td>
                                    <td>
                                        {{ $item->model }}
                                    </td>
                                    <td>
                                        {{ $item->fuel_type_descr }}
                                    </td>
                                    {{-- <td>
                                        {{ $item->date_of_purchase }}
                                    </td>
                                    <td>
                                        {{ $item->purchase_cost }}
                                    </td> --}}
                                    <td>
                                        {{ $item->condition_descr }}
                                    </td>
                                    <td>
                                        {{ $item->laden_weight }}
                                    </td>
                                    <td>
                                        {{ $item->unladen_weight }}
                                    </td>
                                    <td>
                                        {{ $item->alloted_to }}
                                    </td>
                                    <td>
                                        {{ $item->alloted_from }}
                                    </td>
                                    <td>
                                        {{ $item->remarks }}
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </section>
    </main>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-list.css') }}">
@endpush
@push('scripts')
    <script>
        $(function() {
            $("#vehicle_details_table").DataTable();
        });
    </script>
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
@endpush
