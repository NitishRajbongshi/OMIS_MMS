@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('manageRoad') }}">Manage Roads</a>
                        </li>
                        <li class="breadcrumb-item">Show / Protection Wall</li>
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
                    LIST OF DRAFT PROTECTION WALL DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <!-- table content -->
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped "
                    id="protection_wall_draft_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">Protection-Wall Code</th>
                        <th class="text-center">Chainage (Kms)</th>
                        <th class="text-center">Protection-Wall Type</th>
                        <th class="text-center">WallStructure Type</th>
                        <th class="text-center">Bottom Width(Mtrs)</th>
                        <th class="text-center">Top Width(Mtrs)</th>
                        <th class="text-center">Length (Mtrs)</th>
                        <th class="text-center">Height (Mtrs)</th>
                        <th class="text-center">Construction Year</th>
                        <th class="text-center">Renovation Year</th>
                        <th class="text-center">Remarks</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($draftDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->protection_wall_cd }}
                                </td>
                                <td>
                                    {{ $item->chainage }}
                                </td>
                                <td>
                                    {{ $item->wall_type_descr }}
                                </td>
                                <td>
                                    {{ $item->structure_type_descr }}
                                </td>
                                <td>
                                    {{ $item->bottom_width }}
                                </td>
                                <td>
                                    {{ $item->top_width }}
                                </td>
                                <td>
                                    {{ $item->length }}
                                </td>
                                <td>
                                    {{ $item->height }}
                                </td>
                                <td>
                                    {{ $item->year_of_construction }}
                                </td>
                                <td>
                                    {{ $item->year_of_renovation }}
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

            <!-- freezed table content -->
            <h6 class="mt-4 p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF FINALIZED PROTECTION WALL DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped "
                    id="protection_wall_final_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial No.</th>
                        <th class="text-center">Protection-Wall Code</th>
                        <th class="text-center">Chainage (Kms)</th>
                        <th class="text-center">Protection-Wall Type</th>
                        <th class="text-center">WallStructure Type</th>
                        <th class="text-center">Bottom Width(Mtrs)</th>
                        <th class="text-center">Top Width(Mtrs)</th>
                        <th class="text-center">Length (Mtrs)</th>
                        <th class="text-center">Height (Mtrs)</th>
                        <th class="text-center">Construction Year</th>
                        <th class="text-center">Renovation Year</th>
                        <th class="text-center">Remarks</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($finalizedDetails as $item)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $item->protection_wall_cd }}
                                </td>
                                <td>
                                    {{ $item->chainage }}
                                </td>
                                <td>
                                    {{ $item->wall_type_descr }}
                                </td>
                                <td>
                                    {{ $item->structure_type_descr }}
                                </td>
                                <td>
                                    {{ $item->bottom_width }}
                                </td>
                                <td>
                                    {{ $item->top_width }}
                                </td>
                                <td>
                                    {{ $item->length }}
                                </td>
                                <td>
                                    {{ $item->height }}
                                </td>
                                <td>
                                    {{ $item->year_of_construction }}
                                </td>
                                <td>
                                    {{ $item->year_of_renovation }}
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
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        // script for draft data table
        $(function() {
            $("#protection_wall_draft_details_table").DataTable({}).buttons().container().appendTo(
                '#protection_wall_draft_details_table_wrapper .col-md-11:eq(1)');
        });

        // script for draft data table
        $(function() {
            $("#protection_wall_final_details_table").DataTable({}).buttons().container().appendTo(
                '#protection_wall_final_details_table_wrapper .col-md-11:eq(1)');
        });
    </script>
@endpush
