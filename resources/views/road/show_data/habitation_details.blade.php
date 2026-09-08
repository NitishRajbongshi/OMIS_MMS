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
                        <li class="breadcrumb-item">Show Habitation Details</li>
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
                    LIST OF DRAFT HABITATION DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="pavement_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial Number</th>
                        <th class="text-center">Habitation Code</th>
                        <th class="text-center">District Name</th>
                        <th class="text-center">Block Name</th>
                        <th class="text-center">Village Name</th>
                        <th class="text-center">MLA Constituency</th>
                        <th class="text-center">MP Constituency</th>
                        <th class="text-center">Population Count</th>
                        <th class="text-center">Facilities</th>
                        <th class="text-center">Habitation Remarks</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($habitationDetailsDraft as $habitationDetail)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $habitationDetail->habitation_cd }}
                                </td>
                                <td>
                                    {{ $habitationDetail->district_name }}
                                </td>
                                <td>
                                    {{ $habitationDetail->block_name }}
                                </td>
                                <td>
                                    {{ $habitationDetail->village_name }}
                                </td>
                                <td>
                                    {{ $habitationDetail->mla_constituency }}
                                </td>
                                <td>
                                    {{ $habitationDetail->mp_constituency }}
                                </td>
                                <td>
                                    {{ $habitationDetail->total_population }}
                                </td>
                                <td>
                                    @if(isset($habitationFacilitiesDraft[$habitationDetail->habitation_cd]))

                                        @php
                                            $groupedFacilities = collect($habitationFacilitiesDraft[$habitationDetail->habitation_cd])
                                                ->groupBy('facility_name');
                                        @endphp

                                        <!-- Expand Button -->
                                        <a class="text-primary" data-toggle="collapse"
                                            href="#facility{{ $habitationDetail->habitation_cd }}" role="button">

                                            <i class="fas fa-plus-circle"></i> View
                                        </a>
                                        <div class="collapse mt-2" id="facility{{ $habitationDetail->habitation_cd }}">

                                            <table class="table table-bordered table-sm mb-0 text-xs">
                                                <thead>
                                                    <tr>
                                                        <th style="width:40%">Facility</th>
                                                        <th style="width:60%">Sub Facilities</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($groupedFacilities as $facilityName => $subs)

                                                        <tr>
                                                            <td class="text-left">
                                                                {{ $facilityName }}
                                                            </td>

                                                            <td class="text-left">

                                                                <ul class="mb-0 ps-3">

                                                                    @foreach($subs as $sub)

                                                                        <li>{{ $sub->sub_facility_name }}</li>

                                                                    @endforeach

                                                                </ul>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-muted text-center">
                                            No Facilities Added
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    {{ $habitationDetail->remarks }}
                                </td>
                            </tr>
                            <?php    $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h6 class="mt-4 p-2 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    LIST OF FINALIZED HABITATION DETAILS UNDER NAGALAND P W D.
                </span>
            </h6>
            <div class="container-fluid border border-primary mainBody py-3">
                <table class="table-responsive text-xs table table-bordered table-striped user_list"
                    id="pavement_details_table_final">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Serial Number</th>
                        <th class="text-center">Habitation Code</th>
                        <th class="text-center">District Name</th>
                        <th class="text-center">Block Name</th>
                        <th class="text-center">Village Name</th>
                        <th class="text-center">MLA Constituency</th>
                        <th class="text-center">MP Constituency</th>
                        <th class="text-center">Population Count</th>
                        <th class="text-center">Facilities</th>
                        <th class="text-center">Habitation Remarks</th>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($habitationDetailsFinal as $habitationDetail)
                            <tr class="text-center">
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $habitationDetail->habitation_cd }}
                                </td>
                                <td>
                                    {{ $habitationDetail->district_name }}
                                </td>
                                <td>
                                    {{ $habitationDetail->block_name }}
                                </td>
                                <td>
                                    {{ $habitationDetail->village_name }}
                                </td>
                                <td>
                                    {{ $habitationDetail->mla_constituency }}
                                </td>
                                <td>
                                    {{ $habitationDetail->mp_constituency }}
                                </td>
                                <td>
                                    {{ $habitationDetail->total_population }}
                                </td>
                                <td>
                                    @if(isset($habitationFacilities[$habitationDetail->habitation_cd]))

                                        @php
                                            $groupedFacilities = collect($habitationFacilities[$habitationDetail->habitation_cd])
                                                ->groupBy('facility_name');
                                        @endphp

                                        <!-- Expand Button -->
                                        <a class="text-primary" data-toggle="collapse"
                                            href="#facility{{ $habitationDetail->habitation_cd }}" role="button">

                                            <i class="fas fa-plus-circle"></i> View
                                        </a>
                                        <div class="collapse mt-2" id="facility{{ $habitationDetail->habitation_cd }}">

                                            <table class="table table-bordered table-sm mb-0 text-xs">
                                                <thead>
                                                    <tr>
                                                        <th style="width:40%">Facility</th>
                                                        <th style="width:60%">Sub Facilities</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($groupedFacilities as $facilityName => $subs)

                                                        <tr>
                                                            <td class="text-left">
                                                                {{ $facilityName }}
                                                            </td>

                                                            <td class="text-left">

                                                                <ul class="mb-0 ps-3">

                                                                    @foreach($subs as $sub)

                                                                        <li>{{ $sub->sub_facility_name }}</li>

                                                                    @endforeach

                                                                </ul>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-muted text-center">
                                            No Facilities Added
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    {{ $habitationDetail->remarks }}
                                </td>
                            </tr>
                            <?php    $i++; ?>
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
        $(function () {
            $("#pavement_details_table").DataTable({}).buttons().container().appendTo(
                '#pavement_details_table_wrapper .col-md-11:eq(1)');
        });

        // script for draft data table
        $(function () {
            $("#pavement_details_table_final").DataTable({}).buttons().container().appendTo(
                '#pavement_details_table_final_wrapper .col-md-11:eq(1)');
        });
    </script>
@endpush