@extends('layouts.app')
@section('content')
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
                        <li class="breadcrumb-item">Show Surface Type Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <x-show-road-info />
        <x-show-road-nav-link />
        <div class="container-fluid mainBody py-3 text-sm">
            <!-- draft table content -->
            <h6 class="p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF FINALIZED SURFACE TYPE DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="surfaceDetailsDraft">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">Surface Code</th>
                        <th class="text-center">Surface Types</th>
                        <th class="text-center">Condition</th>
                        <th class="text-center">Surface Width</th>
                        <th class="text-center">Shoulder Width</th>
                        <th class="text-center">Start Chainage</th>
                        <th class="text-center">End Chainage</th>
                        <th class="text-center" style="min-width: 5rem;">Base-Layer Type</th>
                        <th class="text-center" style="min-width: 5rem;">Base-Layer Thickness</th>
                        <th class="text-center" style="min-width: 6rem;">SubBase-Layer Thickness</th>
                        <th class="text-center" style="min-width: 6rem;">SubBase-Layer Thickness</th>
                        <th class="text-center">Pavement Type</th>
                        <th class="text-center">Shoulder Type</th>
                        <th class="text-center">Land Slide</th>
                        <th class="text-center">Construction Year</th>
                        <th class="text-center">Base CBR</th>
                        <th class="text-center">Base PI</th>
                        <th class="text-center" style="min-width: 4rem;">Sub-Base CBR</th>
                        <th class="text-center" style="min-width: 4rem;">Sub-Base PI</th>
                        <th class="text-center">Maintenance Type</th>
                        <th class="text-center">Maintenance Date</th>
                        <th class="text-center">Drainage</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($surfaceTypeDetailsDraft as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->rd_surface_cd }}
                                </td>
                                <td>
                                    {{ $item->surface_descr }}
                                </td>
                                <td>
                                    {{ $item->surface_width }}
                                </td>
                                <td>
                                    {{ $item->shoulder_width }}
                                </td>
                                <td>
                                    {{ $item->rd_condition_descr }}
                                </td>
                                <td>
                                    {{ $item->start_chainage }}
                                </td>
                                <td>
                                    {{ $item->end_chainage }}
                                </td>
                                <td>
                                    {{ $item->base_layer_type_descr }}
                                </td>
                                <td>
                                    {{ $item->base_layer_thickness }}
                                </td>
                                <td>
                                    {{ $item->sub_base_layer_type_descr }}
                                </td>
                                <td>
                                    {{ $item->sub_base_layer_thickness }}
                                </td>
                                <td>
                                    {{ $item->pavement_type_descr }}
                                </td>
                                <td>
                                    {{ $item->shoulder_type_descr }}
                                </td>
                                <td>
                                    @if ($item->land_slide == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->construction_year }}
                                </td>
                                <td>
                                    {{ $item->base_cbr }}
                                </td>
                                <td>
                                    {{ $item->base_pi }}
                                </td>
                                <td>
                                    {{ $item->sub_base_cbr }}
                                </td>
                                <td>
                                    {{ $item->sub_base_pi }}
                                </td>
                                <td>
                                    {{ $item->maintenance_type_descr }}
                                </td>
                                <td>
                                    {{ $item->last_maintenance_date }}
                                </td>
                                <td>
                                    {{ $item->drainage_descr }}
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Freeze table data --}}
            <h6 class="mt-4 p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF FINALIZED SURFACE TYPE DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="surfaceDetailsFinal">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">Surface Code</th>
                        <th class="text-center">Surface Types</th>
                        <th class="text-center">Condition</th>
                        <th class="text-center">Surface Width</th>
                        <th class="text-center">Shoulder Width</th>
                        <th class="text-center">Start Chainage</th>
                        <th class="text-center">End Chainage</th>
                        <th class="text-center" style="min-width: 5rem;">Base-Layer Type</th>
                        <th class="text-center" style="min-width: 5rem;">Base-Layer Thickness</th>
                        <th class="text-center" style="min-width: 6rem;">SubBase-Layer Thickness</th>
                        <th class="text-center" style="min-width: 6rem;">SubBase-Layer Thickness</th>
                        <th class="text-center">Pavement Type</th>
                        <th class="text-center">Shoulder Type</th>
                        <th class="text-center">Land Slide</th>
                        <th class="text-center">Construction Year</th>
                        <th class="text-center">Base CBR</th>
                        <th class="text-center">Base PI</th>
                        <th class="text-center" style="min-width: 4rem;">Sub-Base CBR</th>
                        <th class="text-center" style="min-width: 4rem;">Sub-Base PI</th>
                        <th class="text-center">Maintenance Type</th>
                        <th class="text-center">Maintenance Date</th>
                        <th class="text-center">Drainage</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($surfaceTypeDetailsFinal as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->rd_surface_cd }}
                                </td>
                                <td>
                                    {{ $item->surface_descr }}
                                </td>
                                <td>
                                    {{ $item->surface_width }}
                                </td>
                                <td>
                                    {{ $item->shoulder_width }}
                                </td>
                                <td>
                                    {{ $item->rd_condition_descr }}
                                </td>
                                <td>
                                    {{ $item->start_chainage }}
                                </td>
                                <td>
                                    {{ $item->end_chainage }}
                                </td>
                                <td>
                                    {{ $item->base_layer_type_descr }}
                                </td>
                                <td>
                                    {{ $item->base_layer_thickness }}
                                </td>
                                <td>
                                    {{ $item->sub_base_layer_type_descr }}
                                </td>
                                <td>
                                    {{ $item->sub_base_layer_thickness }}
                                </td>
                                <td>
                                    {{ $item->pavement_type_descr }}
                                </td>
                                <td>
                                    {{ $item->shoulder_type_descr }}
                                </td>
                                <td>
                                    @if ($item->land_slide == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->construction_year }}
                                </td>
                                <td>
                                    {{ $item->base_cbr }}
                                </td>
                                <td>
                                    {{ $item->base_pi }}
                                </td>
                                <td>
                                    {{ $item->sub_base_cbr }}
                                </td>
                                <td>
                                    {{ $item->sub_base_pi }}
                                </td>
                                <td>
                                    {{ $item->maintenance_type_descr }}
                                </td>
                                <td>
                                    {{ $item->last_maintenance_date }}
                                </td>
                                <td>
                                    {{ $item->drainage_descr }}
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        // script for draft data table
        $(function() {
            $("#surfaceDetailsDraft").DataTable({}).buttons().container().appendTo(
                '#surfaceDetailsDraft_wrapper .col-md-11:eq(1)');
        });

        // script for draft data table
        $(function() {
            $("#surfaceDetailsFinal").DataTable({}).buttons().container().appendTo(
                '#surfaceDetailsFinal_wrapper .col-md-11:eq(1)');
        });
    </script>
@endpush
