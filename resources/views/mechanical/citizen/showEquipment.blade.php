@extends('layouts.index')
@section('content')
    <main class="public-list-page">
        <section class="public-list-hero">
            <div class="public-list-brand">
                <img src="{{ asset('images/main_logo.png') }}" alt="Nagaland PWD logo">
                <div>
                    <span class="public-list-eyebrow">Citizen Asset Information</span>
                    <h1>Mechanical Equipment Registry</h1>
                    <p>List of equipment details under Nagaland PWD Mechanical.</p>
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
                <h2>Equipment details</h2>
                <span>Mechanical</span>
            </div>
            <div class="public-list-table-wrap">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="equipment_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Sl No.</th>
                            <th class="text-center">Equipment Code</th>
                            <th class="text-center">Equipment Name</th>
                            <th class="text-center">Serial Number</th>
                            <th class="text-center">Model Number</th>
                            {{-- <th class="text-center">Purchased year</th> --}}
                            {{-- <th class="text-center">Purchased Cost</th> --}}
                            <th class="text-center">Equipment Condition</th>
                            <th class="text-center">Under Warranty?</th>
                            <th class="text-center">Equipment Remarks</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($equipmentDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->euipment_cd }}
                                </td>
                                <td>
                                    {{ $item->equipment_name }}
                                </td>
                                <td>
                                    {{ $item->serial_number }}
                                </td>
                                <td>
                                    {{ $item->model_no }}
                                </td>
                                {{-- <td>
                                    {{ $item->purchase_year }}
                                </td>
                                <td>
                                    {{ $item->purchase_cost }}
                                </td> --}}
                                <td>
                                    {{ $item->condition_descr }}
                                </td>
                                <td>
                                    @if ($item->is_under_waranty == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->equipment_remarks }}
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
            $("#equipment_details_table").DataTable();
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
