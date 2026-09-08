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
                        <li class="breadcrumb-item">Show / PCI</li>
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
                    LIST OF DRAFT PCI DETAILS READY FOR FINALIZATION UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="pavement_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">PCI Code</th>
                        <th class="text-center">PCI Length</th>
                        <th class="text-center">PCI Chainage</th>
                        <th class="text-center">PCI Value</th>
                        <th class="text-center">Cracking percentages</th>
                        <th class="text-center">Ravelling percentages</th>
                        <th class="text-center">Pot Holes percentages</th>
                        <th class="text-center">Shoving percentages</th>
                        <th class="text-center">Patching percentages</th>
                        <th class="text-center">Settlement percentages</th>
                        <th class="text-center">Rut Depth</th>
                        <th class="text-center">M. Traffic/Day</th>
                        <th class="text-center">Traffice Light</th>
                        <th class="text-center">Remarks</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($pciDetailsDraft as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->pci_section_cd }}
                                </td>
                                <td>
                                    {{ $item->pci_section_length_in_meter }}
                                </td>
                                <td>
                                    {{ $item->chainage }}
                                </td>
                                <td>
                                    {{ $item->pci_value }}
                                </td>
                                <td>
                                    {{ $item->cracking_percent ? $item->cracking_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->ravelling_percent ? $item->ravelling_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->pot_holes_percent ? $item->pot_holes_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->shoving_percent ? $item->shoving_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->patching_percent ? $item->patching_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->settlement_depression_percent ? $item->settlement_depression_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->rut_depth ? $item->rut_depth : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->tot_motorized_traffic_per_day }}
                                </td>
                                <td>
                                    @if ($item->pv_traffic_light == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->pci_remarks }}
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h6 class="mt-4 p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF FINALIZED PCI DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="pavement_details_table_final">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">PCI Code</th>
                        <th class="text-center">PCI Length</th>
                        <th class="text-center">PCI Chainage</th>
                        <th class="text-center">PCI Value</th>
                        <th class="text-center">Cracking percentages</th>
                        <th class="text-center">Ravelling percentages</th>
                        <th class="text-center">Pot Holes percentages</th>
                        <th class="text-center">Shoving percentages</th>
                        <th class="text-center">Patching percentages</th>
                        <th class="text-center">Settlement percentages</th>
                        <th class="text-center">Rut Depth</th>
                        <th class="text-center">M. Traffic/Day</th>
                        <th class="text-center">Traffice Light</th>
                        <th class="text-center">Remarks</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>

                        @foreach ($pciDetailsFinal as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->pci_section_cd }}
                                </td>
                                <td>
                                    {{ $item->pci_section_length_in_meter }}
                                </td>
                                <td>
                                    {{ $item->chainage }}
                                </td>
                                <td>
                                    {{ $item->pci_value }}
                                </td>
                                <td>
                                    {{ $item->cracking_percent ? $item->cracking_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->ravelling_percent ? $item->ravelling_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->pot_holes_percent ? $item->pot_holes_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->shoving_percent ? $item->shoving_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->patching_percent ? $item->patching_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->settlement_depression_percent ? $item->settlement_depression_percent : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->rut_depth ? $item->rut_depth : 'NA' }}
                                </td>
                                <td>
                                    {{ $item->tot_motorized_traffic_per_day }}
                                </td>
                                <td>
                                    @if ($item->pv_traffic_light == 'Y')
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $item->pci_remarks }}
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
            $("#pavement_details_table").DataTable({}).buttons().container().appendTo(
                '#pavement_details_table_wrapper .col-md-11:eq(1)');
        });

        // script for draft data table
        $(function() {
            $("#pavement_details_table_final").DataTable({}).buttons().container().appendTo(
                '#pavement_details_table_final_wrapper .col-md-11:eq(1)');
        });
    </script>
@endpush
