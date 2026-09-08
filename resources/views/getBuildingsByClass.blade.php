@extends('layouts.index')
@section('content')
    <main class="public-list-page">
        <section class="public-list-hero">
            <div class="public-list-brand">
                <img src="{{ asset('images/main_logo.png') }}" alt="Nagaland PWD logo">
                <div>
                    <span class="public-list-eyebrow">Citizen Asset Information</span>
                    <h1>Building Asset Registry</h1>
                    <p>Housing details of building class: {{ $cls_descr }}</p>
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
                <h2>Building details by class</h2>
                <span>{{ $cls_descr }}</span>
            </div>
            <div class="public-list-table-wrap">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="housing_details_table">
                    @if ($cls_cd == '0')
                        <thead class="theader text-xs text-white" style="background-color:#417dbe">
                            <th class="text-center">Serial Number</th>
                            <th class="text-center">Building ID</th>
                            <th class="text-center">Qtr No</th>
                            <th class="text-center">Buliding Type</th>
                            <th class="text-center">Construction Year</th>
                            <th class="text-center">
                                Division Name
                            </th>
                            <th class="text-center">
                                Sub Division Name
                            </th>
                            <th class="text-center">Building Category</th>
                            <th class="text-center">Occupant Name</th>
                            <th class="text-center">Department Name</th>
                            <th class="text-center">Location</th>
                        </thead>

                        <tbody>
                            <?php    $i = 1; ?>
                            @foreach ($bldDetails as $item)
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td>
                                        {{ $item->building_system_cd }}
                                    </td>
                                    <td>
                                        {{ $item->qtr_no }}
                                    </td>
                                    <td>
                                        {{ $item->building_type_descr }}
                                    </td>
                                    <td>
                                        {{ $item->construction_year }}
                                    </td>
                                    <td>
                                        {{ $item->division_name }}
                                    </td>
                                    <td>
                                        {{ $item->sub_div_name }}
                                    </td>
                                    <td>
                                        {{ $item->building_catg_descr }}
                                    </td>
                                    <td>
                                        {{ $item->occupant_name }}
                                    </td>
                                    <td>
                                        {{ $item->dept_descr }}
                                    </td>
                                    <td>
                                        {{ $item->location_name }}
                                    </td>
                                </tr>
                                <?php        $i++; ?>
                            @endforeach
                        </tbody>
                    @endif

                    @if ($cls_cd == '1')
                        <thead class="theader text-xs text-white" style="background-color:#417dbe">
                            <th class="text-center">Serial Number</th>
                            <th class="text-center">Building ID</th>
                            <th class="text-center">Building Name</th>
                            <th class="text-center">Buliding Type</th>
                            <th class="text-center">Construction Year</th>
                            <th class="text-center">
                                Division Name
                            </th>
                            <th class="text-center">
                                Sub Division Name
                            </th>
                            <th class="text-center">Building Category</th>
                            <th class="text-center">Department Name</th>
                            <th class="text-center">Location</th>
                        </thead>

                        <tbody>
                            <?php    $i = 1; ?>
                            @foreach ($bldDetails as $item)
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td>
                                        {{ $item->building_system_cd }}
                                    </td>
                                    <td>
                                        {{ $item->bld_qtr_name }}
                                    </td>
                                    <td>
                                        {{ $item->building_type_descr }}
                                    </td>
                                    <td>
                                        {{ $item->construction_year }}
                                    </td>
                                    <td>
                                        {{ $item->division_name }}
                                    </td>
                                    <td>
                                        {{ $item->sub_div_name }}
                                    </td>
                                    <td>
                                        {{ $item->building_catg_descr }}
                                    </td>

                                    <td>
                                        {{ $item->dept_descr }}
                                    </td>
                                    <td>
                                        {{ $item->location_name }}
                                    </td>

                                </tr>
                                <?php        $i++; ?>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        </section>
    </main>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public-list.css') }}">
@endpush
@push('scripts')
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
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <script src="dist/js/pages/dashboard.js"></script>
    <script type="text/javascript" src="dataTables/datatables.js"></script>
    <script type="text/javascript" src="dataTables/datatables.min.js"></script>

    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <script src="plugins/inputmask/jquery.inputmask.min.js"></script>
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <script src="plugins/dropzone/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>


    <script>
        $(function () {
            let filedName = null;
            const rd_tp = document.querySelector('#cust_serach_rd_type');
            const rd_divison = document.querySelector('#cust_serach_div');

            DataTable.ext.search.push(function (settings, data, dataIndex) {

                let selectedRdcatg = $("#cust_serach_rd_type :selected").text();
                let selectedRdDiv = $("#cust_serach_div :selected").text();
                if (filedName == "road_type") {
                    if (
                        (isNaN(selectedRdcatg) && selectedRdcatg.trim() == data[4].trim()) ||
                        (isNaN(selectedRdcatg) && selectedRdcatg.trim() == "All")) {
                        return true;
                    }
                }

                if (filedName == "road_divison") {
                    if ((isNaN(selectedRdDiv) && selectedRdDiv.trim() == data[6].trim()) ||
                        (isNaN(selectedRdDiv) && selectedRdDiv.trim() == "All")) {
                        return true;
                    }
                }
                if (filedName == null)
                    return true;
                return false;
            });

            const myDataTable = new DataTable('#housing_details_table');

            rd_tp.addEventListener('change', function () {
                filedName = "road_type";
                myDataTable.draw();
            });

            rd_divison.addEventListener('change', function () {
                filedName = "road_divison";
                myDataTable.draw();
            });
        });
        $(document).ready(function () {
            $('#housing_details_table').DataTable();
        });
    </script>
@endpush