@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-12 col-md-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Abstract</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="px-2">
        <div id="tabBtn">
            <ul class="d-flex">
                <li class="bg-primary">
                    <a class="d-block px-4 py-1 border border-secondary" href="#Algorithms">
                        General Abstract
                    </a>
                </li>
                <li class="bg-primary">
                    <a class="d-block px-4 py-1 border border-secondary" href="#Data_Structure">
                        Asset Summary
                    </a>
                </li>
            </ul>
            <div style="background-color: #FEFBFA;">
                <div class="p-2" id='Algorithms'>
                    <span class="text-bold">Choose Abstract Type</span>
                    <div class="form-check text-sm">
                        <input class="form-check-input" type="radio" name="generalAbstract" id="divisionAbstract"
                            value="0">
                        <label class="form-check-label" for="divisionAbstract">
                            Division Wise Abstract
                        </label>
                    </div>
                    <div class="form-check text-sm">
                        <input class="form-check-input" type="radio" name="generalAbstract" id="districtAbstract"
                            value="1">
                        <label class="form-check-label" for="districtAbstract">
                            District Wise Abstract
                        </label>
                    </div>
                    {{-- Dynamic div --}}
                    <div id="divisionWiseTable">
                        <!-- table content -->
                        <div class="container-fluid border mainBody py-3">
                            <div class="container-fluid mt-3">
                                <h6 class="text-center text-md">
                                    <span class="px-3 py-1" style="color: #417dbe; border: 1px solid #417dbe;">
                                        GENERAL ABSTRACT
                                    </span>
                                </h6>
                                <p class="text-secondary text-center">Statement Showing the Division-wise Number
                                    of Building(Both Residential and
                                    Non-Residential) under PWD (Housing)</p>
                                <span class="generalAbsDivContainer"></span>
                                <table class="table-responsive text-xs table table-bordered table-striped"
                                    id="divisionWiseAbstractTable">
                                    <thead class="theader text-white" style="background-color:#417DBE;">
                                        <th class="text-center" style="min-width: 4rem;">Serial No.</th>
                                        <th class="text-center" style="min-width: 10rem;">Name of E.E. Division
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">No. of Residential
                                            Buildings</th>
                                        <th class="text-center" style="min-width: 8rem;">Plinth Area in Sq. ft.
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">No. of Non-Residential
                                            Buildings</th>
                                        <th class="text-center" style="min-width: 8rem;">Plinth Area in Sq. ft.
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">Total No. of Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">Total Plinth Area in
                                            Sq. ft.</th>
                                    </thead>

                                    <tbody>
                                        <?php $i = 1;
                                        $count = 0; ?>
                                        @foreach ($generalAbstractDetails as $generalAbstractDetail)
                                            <tr>
                                                <td class="text-center">{{ $i }}</td>
                                                <td>
                                                    {{ $generalAbstractDetail->division_name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $generalAbstractDetail->residential_building_number }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($generalAbstractDetail->residential_plinth_area, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $generalAbstractDetail->non_residential_building_number }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($generalAbstractDetail->non_residential_plinth_area, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $generalAbstractDetail->total_building_number }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($generalAbstractDetail->total_plinth_area, 2) }}
                                                </td>
                                            </tr>
                                            <?php $i++;
                                            $count = $i; ?>
                                        @endforeach
                                        <tr>
                                            <td class="text-center text-bold text-md">{{ $count }}</td>
                                            <td class="text-bold text-md">Total</td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalGeneralAbstractDetail->total_residential_building_number }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ number_format($totalGeneralAbstractDetail->total_residential_plinth_area, 2) }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalGeneralAbstractDetail->total_non_residential_building_number }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ number_format($totalGeneralAbstractDetail->total_non_residential_plinth_area, 2) }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalGeneralAbstractDetail->total_building_number }}</td>
                                            <td class="text-center text-bold text-md">
                                                {{ number_format($totalGeneralAbstractDetail->total_plinth_area, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="districtWiseTable">
                        <!-- table content -->
                        <div class="container-fluid border mainBody py-3">
                            <div class="container-fluid mt-3">
                                <h6 class="text-center text-md">
                                    <span class="px-3 py-1" style="color: #417dbe; border: 1px solid #417dbe;">
                                        GENERAL ABSTRACT
                                    </span>
                                </h6>
                                <p class="text-secondary text-center">Statement Showing the District-wise Number
                                    of Building(Both Residential and
                                    Non-Residential) under PWD (Housing)</p>
                                <span class="generalAbsDistContainer"></span>
                                <table class="table-responsive text-xs table table-bordered table-striped"
                                    id="districtWiseAbstractTable">
                                    <thead class="theader text-white" style="background-color:#417DBE;">
                                        <th class="text-center" style="min-width: 4rem;">Serial No.</th>
                                        <th class="text-center" style="min-width: 8rem;">Name of District
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">No. of Residential
                                            Buildings</th>
                                        <th class="text-center" style="min-width: 8rem;">Plinth Area in Sq.
                                            ft.
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">No. of
                                            Non-Residential
                                            Buildings</th>
                                        <th class="text-center" style="min-width: 8rem;">Plinth Area in Sq.
                                            ft.</th>
                                        <th class="text-center" style="min-width: 8rem;">Total No. of Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">Total Plinth Area in
                                            Sq. ft.</th>
                                    </thead>

                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-2" id='Data_Structure'>
                    <span class="text-bold">Choose Building Type</span>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="buildingType" id="residential"
                            value="0">
                        <label class="form-check-label" for="residential">
                            Residential
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="buildingType" id="nonResidential"
                            value="1">
                        <label class="form-check-label" for="nonResidential">
                            Non Residential
                        </label>
                    </div>
                    {{-- Dynamic div --}}
                    <div id="residentialWiseTable">
                        <!-- table content -->
                        <div class="container-fluid border mainBody py-3">
                            <div class="container-fluid mt-3">
                                <h6 class="text-center text-md">
                                    <span class="px-3 py-1" style="color: #417dbe; border: 1px solid #417dbe;">
                                        RESIDENTIAL BUILDING
                                    </span>
                                </h6>
                                <p class="text-secondary text-center">Abstract Type of Building Inventory
                                    Register under PWD (Housing)</p>
                                <span class="residentialAbsDivContainer"></span>
                                <table class="table-responsive text-xs table table-bordered table-striped"
                                    id="residentialWiseAbstractTable">
                                    <thead class="theader text-white" style="background-color:#417DBE;">
                                        <th class="text-center" style="min-width: 4rem;">Serial No.</th>
                                        <th class="text-center" style="min-width: 8rem;">Type of Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">No. of RCC Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">No. of H/Type
                                            Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">No. of Semi Pucca
                                            Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">Total No. of Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">Total Plinth Area in
                                            Sq.
                                            ft.
                                        </th>
                                    </thead>

                                    <tbody>
                                        <?php $i = 1;
                                        $count = 0; ?>
                                        @foreach ($residentialDetails as $residentialDetail)
                                            <tr>
                                                <td class="text-center">{{ $i }}</td>
                                                <td>
                                                    {{ $residentialDetail->building_type_descr }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $residentialDetail->rcc }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $residentialDetail->hill_type }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $residentialDetail->semi_pucca }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $residentialDetail->total_building_number }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($residentialDetail->total_plinth_area, 2) }}
                                                </td>
                                            </tr>
                                            <?php $i++;
                                            $count = $i; ?>
                                        @endforeach
                                        <tr>
                                            <td class="text-center text-bold text-md">{{ $count }}</td>
                                            <td class="text-bold text-md">Total</td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalResidentialDetails->rcc }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalResidentialDetails->hill_type }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalResidentialDetails->semi_pucca }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalResidentialDetails->total_building_number }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ number_format($totalResidentialDetails->total_plinth_area, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="nonResidentialWiseTable">
                        <!-- table content -->
                        <div class="container-fluid border mainBody py-3">
                            <div class="container-fluid mt-3">
                                <h6 class="text-center text-md">
                                    <span class="px-3 py-1" style="color: #417dbe; border: 1px solid #417dbe;">
                                        NON RESIDENTIAL BUILDING
                                    </span>
                                </h6>
                                <p class="text-secondary text-center">Abstract of Building Inventory
                                    Register under PWD (Housing)</p>
                                <span class="nonResidentialAbsDistContainer"></span>
                                <table class="table-responsive text-xs table table-bordered table-striped"
                                    id="nonResidentialWiseAbstractTable">
                                    <thead class="theader text-white" style="background-color:#417DBE;">
                                        <th class="text-center" style="min-width: 4rem;">Serial No.</th>
                                        <th class="text-center" style="min-width: 8rem;">Type of Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">No. of RCC Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">No. of H/Type
                                            Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">No. of Semi Pucca
                                            Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">Total No. of Building
                                        </th>
                                        <th class="text-center" style="min-width: 8rem;">Total Plinth Area in
                                            Sq.
                                            ft.
                                        </th>
                                    </thead>

                                    <tbody>
                                        <?php $i = 1;
                                        $count = 0; ?>
                                        @foreach ($nonResidentialDetails as $nonResidentialDetail)
                                            <tr>
                                                <td class="text-center">{{ $i }}</td>
                                                <td>
                                                    {{ $nonResidentialDetail->building_type_descr }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $nonResidentialDetail->rcc }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $nonResidentialDetail->hill_type }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $nonResidentialDetail->semi_pucca }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $nonResidentialDetail->total_building_number }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($nonResidentialDetail->total_plinth_area, 2) }}
                                                </td>
                                            </tr>
                                            <?php $i++;
                                            $count = $i; ?>
                                        @endforeach
                                        <tr>
                                            <td class="text-center text-bold text-md">{{ $count }}</td>
                                            <td class="text-bold text-md">Total</td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalNonResidentialDetails->rcc }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalNonResidentialDetails->hill_type }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalNonResidentialDetails->semi_pucca }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ $totalNonResidentialDetails->total_building_number }}
                                            </td>
                                            <td class="text-center text-bold text-md">
                                                {{ number_format($totalNonResidentialDetails->total_plinth_area, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- </div> --}}
        {{-- </div> --}}
    </section>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">

    <style>
        #tabBtn ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #tabBtn li {
            display: inline-block;
        }

        #tabBtn a:hover {
            color: white;
            background: rgb(47, 102, 185);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#tabBtn").tabs({
                active: 0,
                // collapsible: true
            })
        });

        $("#divisionWiseTable, #districtWiseTable, #residentialWiseTable, #nonResidentialWiseTable").hide();
        $('input[name="generalAbstract"]').change(function() {
            $("#divisionWiseTable, #districtWiseTable").hide();
            if ($(this).val() === "0") {
                $("#divisionWiseTable").show();
            } else if ($(this).val() === "1") {
                $("#districtWiseTable").show();
            }
        });

        $('input[name="buildingType"]').change(function() {
            $("#residentialWiseTable, #nonResidentialWiseTable").hide();
            if ($(this).val() === "0") {
                $("#residentialWiseTable").show();
            } else if ($(this).val() === "1") {
                $("#nonResidentialWiseTable").show();
            }
        });

        $(function() {
            $("#divisionWiseAbstractTable")
                .DataTable({
                    buttons: ["csv", "excel"],
                })
                .buttons()
                .container()
                .appendTo(".generalAbsDivContainer");
        });
        $(function() {
            $("#districtWiseAbstractTable")
                .DataTable({
                    buttons: ["csv", "excel"],
                })
                .buttons()
                .container()
                .appendTo(".generalAbsDistContainer");
        });
        $(function() {
            $("#residentialWiseAbstractTable")
                .DataTable({
                    buttons: ["csv", "excel"],
                })
                .buttons()
                .container()
                .appendTo(".residentialAbsDivContainer");
        });
        $(function() {
            $("#nonResidentialWiseAbstractTable")
                .DataTable({
                    buttons: ["csv", "excel"],
                })
                .buttons()
                .container()
                .appendTo(".nonResidentialAbsDistContainer");
        });
    </script>
@endpush
