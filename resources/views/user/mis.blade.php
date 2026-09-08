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
                        <li class="breadcrumb-item">User Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="px-2">
        <div class="container-fluid border mainBody py-3">
            <div class="container-fluid mt-3">
                <h6 class="text-center text-md">
                    <span class="px-3 py-1 text-uppercase" style="color: #417dbe; border: 1px solid #417dbe;">
                        Officers Summary
                    </span>
                </h6>
                <span class="generalAbsDivContainer"></span>
                <table class="text-xs table table-bordered table-striped" id="divisionWiseAbstractTable">
                    <thead class="theader text-white" style="background-color:#417DBE;">
                        <th class="text-center" style="min-width: 2rem;">Serial No.</th>
                        <th class="text-center" style="min-width: 5rem;">Designation Information
                        </th>
                        <th class="text-center" style="min-width: 3rem;">No.s of Users</th>
                    </thead>

                    <tbody>
                        <?php $i = 1;
                        $count = 0; ?>
                        @foreach ($userCountByDesignations as $userCountByDesignation)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td>
                                    {{ $userCountByDesignation->desg_name }}
                                    {{-- <a
                                            href="{{ URL::temporarySignedRoute('designationUserHistory', now()->addMinutes(60), ['id' => $userCountByDesignation->designation, 'user' => session('userName')]) }}">
                                            {{ $userCountByDesignation->desg_name }}
                                        </a> --}}
                                </td>
                                <td class="text-center">
                                    <a
                                        href="{{ URL::temporarySignedRoute('userHistory', now()->addMinutes(60), ['id' => $userCountByDesignation->designation, 'user' => session('userName')]) }}">
                                        {{ $userCountByDesignation->no_of_users }}
                                    </a>
                                </td>
                            </tr>
                            <?php $i++;
                            $count = $i; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <div id="tabBtn">
                <ul class="d-flex">
                    <li class="bg-primary">
                        <a class="d-block px-4 py-1 border border-secondary" href="#Algorithms">
                            General Summary
                        </a>
                    </li>
                    <li class="bg-primary">
                        <a class="d-block px-4 py-1 border border-secondary" href="#Data_Structure">
                            Role Based Summary
                        </a>
                    </li>
                </ul>
                <div style="background-color: #FEFBFA;">
                    <div class="p-2" id='Algorithms'> --}}
        {{-- <span class="text-bold">Choose Summary Type</span>
                        <div class="form-check text-sm">
                            <input class="form-check-input" type="radio" name="generalAbstract" id="divisionAbstract"
                                value="0" checked>
                            <label class="form-check-label" for="divisionAbstract">
                                Officers Summary
                            </label>
                        </div> --}}
        {{-- <div class="form-check text-sm">
                            <input class="form-check-input" type="radio" name="generalAbstract" id="districtAbstract"
                                value="1">
                            <label class="form-check-label" for="districtAbstract">
                                Offices Summary
                            </label>
                        </div> --}}
        {{-- Dynamic div --}}
        {{-- <div id="divisionWiseTable">
                            <div class="container-fluid border mainBody py-3">
                                <div class="container-fluid mt-3">
                                    <h6 class="text-center text-md">
                                        <span class="px-3 py-1 text-uppercase"
                                            style="color: #417dbe; border: 1px solid #417dbe;">
                                            Officers Summary
                                        </span>
                                    </h6>
                                    <span class="generalAbsDivContainer"></span>
                                    <table class="text-xs table table-bordered table-striped"
                                        id="divisionWiseAbstractTable">
                                        <thead class="theader text-white" style="background-color:#417DBE;">
                                            <th class="text-center" style="min-width: 2rem;">Serial No.</th>
                                            <th class="text-center" style="min-width: 5rem;">Designation Information
                                            </th>
                                            <th class="text-center" style="min-width: 3rem;">No.s of Users</th>
                                        </thead>

                                        <tbody>
                                            <?php $i = 1;
                                            $count = 0; ?>
                                            @foreach ($userCountByDesignations as $userCountByDesignation)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>
                                                        <a
                                                            href="{{ URL::temporarySignedRoute('designationUserHistory', now()->addMinutes(60), ['id' => $userCountByDesignation->designation, 'user' => session('userName')]) }}">
                                                            {{ $userCountByDesignation->desg_name }}
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a
                                                            href="{{ URL::temporarySignedRoute('userHistory', now()->addMinutes(60), ['id' => $userCountByDesignation->designation, 'user' => session('userName')]) }}">
                                                            {{ $userCountByDesignation->no_of_users }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                                $count = $i; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> --}}
        {{-- <div id="districtWiseTable" style="display: none;" >
                            <div class="container-fluid border mainBody py-3">
                                <div class="container-fluid mt-3">
                                    <h6 class="text-center text-md">
                                        <span class="px-3 py-1 text-uppercase"
                                            style="color: #417dbe; border: 1px solid #417dbe;">
                                            Offices Summary
                                        </span>
                                    </h6>
                                    <p class="text-secondary text-center">Statement Showing the Office-wise Number
                                        of Users</p>
                                    <span class="generalAbsDistContainer"></span>
                                    <table class="text-xs table table-bordered table-striped"
                                        id="districtWiseAbstractTable">
                                        <thead class="theader text-white" style="background-color:#417DBE;">
                                            <th class="text-center" style="min-width: 2rem;">Serial No.</th>
                                            <th class="text-center" style="min-width: 5rem;">Information Details
                                            </th>
                                            <th class="text-center" style="min-width: 3rem;">No.s of Users</th>
                                        </thead>

                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($userCountByOffices as $userCountByOffice)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>
                                                        {{ $userCountByOffice->office_name }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $userCountByOffice->no_of_users }}
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> --}}
        {{-- </div>

                    <div class="p-2" id='Data_Structure'>
                        <span class="text-bold">Choose Summary Type</span>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="buildingType" id="residential"
                                value="0">
                            <label class="form-check-label" for="residential">
                                Office Wise Summary
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="buildingType" id="nonResidential"
                                value="1">
                            <label class="form-check-label" for="nonResidential">
                                Designations Wise Summary
                            </label>
                        </div>
                        <div id="residentialWiseTable" style="display: none;">
                            <div class="container-fluid border mainBody py-3">
                                <div class="container-fluid mt-3">
                                    <h6 class="text-center text-md">
                                        <span class="px-3 py-1 text-uppercase"
                                            style="color: #417dbe; border: 1px solid #417dbe;">
                                            Office Wise Summary
                                        </span>
                                    </h6>
                                    <p class="text-secondary text-center">Statement Showing the Office-wise Number
                                        of Users</p>
                                    <span class="residentialAbsDivContainer"></span>
                                    <table class="text-xs table table-bordered table-striped"
                                        id="residentialWiseAbstractTable">
                                        <thead class="theader text-white" style="background-color:#417DBE;">
                                            <th class="text-center" style="min-width: 2rem;">Serial No.</th>
                                            <th class="text-center" style="min-width: 8rem;">Office Name
                                            </th>
                                            <th class="text-center" style="min-width: 8rem;">Designation Name
                                            </th>
                                            <th class="text-center" style="min-width: 3rem;">No.s of Users
                                            </th>
                                        </thead>

                                        <tbody>
                                            <?php $i = 1;
                                            $count = 0; ?>
                                            @foreach ($userInOffices as $userInOffice)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>
                                                        {{ $userInOffice->office_name }}
                                                    </td>
                                                    <td>
                                                        {{ $userInOffice->desg_name }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $userInOffice->user_count }}
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                                $count = $i; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="nonResidentialWiseTable" style="display: none;">
                            <div class="container-fluid border mainBody py-3">
                                <div class="container-fluid mt-3">
                                    <h6 class="text-center text-md">
                                        <span class="px-3 py-1 text-uppercase"
                                            style="color: #417dbe; border: 1px solid #417dbe;">
                                            Designation Wise Summary
                                        </span>
                                    </h6>
                                    <p class="text-secondary text-center">Statement Showing the Designation-wise Number
                                        of Users</p>
                                    <span class="nonResidentialAbsDistContainer"></span>
                                    <table class="text-xs table table-bordered table-striped"
                                        id="nonResidentialWiseAbstractTable">
                                        <thead class="theader text-white" style="background-color:#417DBE;">
                                            <th class="text-center" style="min-width: 2rem;">Serial No.</th>
                                            <th class="text-center" style="min-width: 8rem;">Office Name
                                            </th>
                                            <th class="text-center" style="min-width: 8rem;">Designation Name
                                            </th>
                                            <th class="text-center" style="min-width: 3rem;">No.s of Users
                                            </th>
                                        </thead>

                                        <tbody>
                                            <?php $i = 1;
                                            $count = 0; ?>
                                            @foreach ($userInDesignations as $userInDesignation)
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>
                                                        {{ $userInDesignation->office_name }}
                                                    </td>
                                                    <td>
                                                        {{ $userInDesignation->desg_name }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $userInDesignation->user_count }}
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                                $count = $i; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        {{-- </div> --}}
        {{-- </div> --}}
    </section>
@endsection
@push('styles')
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

        $("#districtWiseTable, #residentialWiseTable, #nonResidentialWiseTable").hide();
        // $("#divisionWiseTable, #districtWiseTable, #residentialWiseTable, #nonResidentialWiseTable").hide();
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
