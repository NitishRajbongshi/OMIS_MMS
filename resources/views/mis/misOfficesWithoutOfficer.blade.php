@extends('layouts.app')
@section('content')
    <section class="content mis-offices-page" id="officesWitoutOfficerSection" name="officesWitoutOfficerSection">
        <div class="container-fluid">
            <div class="mis-offices-breadcrumb">
                <a href="{{ route('home') }}">Dashboard</a>
                <span>/</span>
                <span>MIS</span>
                <span>/</span>
                <span>Offices Without Officers</span>
            </div>

            <div class="mis-offices-hero">
                <div>
                    <span class="mis-offices-eyebrow">MIS Oversight</span>
                    <h1>Offices Without Officers</h1>
                    <p>Track offices that currently do not have assigned officer records and filter them by administrative hierarchy.</p>
                </div>
                <div class="mis-offices-stat">
                    <span>Total Offices Listed</span>
                    <strong>{{ count($listOffices) }}</strong>
                </div>
            </div>

            <div class="mis-offices-card">
                <div class="mis-offices-card-header">
                    <div>
                        <span>Office Assignment Register</span>
                        <strong>Unassigned Office List</strong>
                    </div>
                    <span class="spanOfficesWitoutOfficer text-center"></span>
                </div>

                <div class="table-responsive mis-offices-table-wrap">
                    <table class="table text-xs table-hover table-bordered table-striped user_list mis-offices-table"
                        id="tblOfficesWitoutOfficer">
                        <thead class="theader">
                            <tr>
                                <th class="text-center">SL No.</th>
                                <th class="text-center">Office Name</th>
                                <th class="text-center">
                                    <span class="mis-offices-th-label">Department</span>
                                    <select class="custom_select text-uppercase text-xs mis-offices-filter" name="selDept"
                                        id="selDept">
                                        <option value="0">ALL</option>
                                        @foreach ($deptdetails as $deptdetail)
                                            <option value={{ $deptdetail->id }}>
                                                {{ $deptdetail->department_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th class="text-center">Office Type</th>
                                <th class="text-center">
                                    <span class="mis-offices-th-label">Zone</span>
                                    <select class="custom_select text-uppercase text-xs mis-offices-filter" name="selZone"
                                        id="selZone">
                                        <option value="A">ALL</option>
                                        @foreach ($zoneDetails as $zoneDetail)
                                            <option value={{ $zoneDetail->zone_cd }}>
                                                {{ $zoneDetail->zone_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th class="text-center">
                                    <span class="mis-offices-th-label">Circle</span>
                                    <select class="custom_select text-uppercase text-xs mis-offices-filter" name="selCircle"
                                        id="selCircle">
                                        <option value="A">ALL</option>
                                        @foreach ($circleDetails as $circleDetail)
                                            <option value={{ $circleDetail->circle_cd }}>
                                                {{ $circleDetail->circle_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th class="text-center">
                                    <span class="mis-offices-th-label">Division</span>
                                    <select class="custom_select text-uppercase text-xs mis-offices-filter" name="selDivision"
                                        id="selDivision">
                                        <option value="A">ALL</option>
                                        @foreach ($divisionDetails as $divisionDetail)
                                            <option value={{ $divisionDetail->division_name }}>
                                                {{ $divisionDetail->division_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th class="text-center">
                                    <span class="mis-offices-th-label">Sub Division</span>
                                    <select class="custom_select text-uppercase text-xs mis-offices-filter" name="selSubDivision"
                                        id="selSubDivision">
                                        <option value="A">ALL</option>
                                        @foreach ($subDivisionDetails as $subDivisionItems)
                                            <option value={{ $subDivisionItems->sub_div_cd }}>
                                                {{ $subDivisionItems->sub_div_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($listOffices as $key)
                                <?php $has_additional_office_charge = false; ?>
                                @foreach ($listOfUserWithAdditionalCharge as $addlOfs)
                                    @if ($addlOfs->id === $key->id)
                                        <?php $has_additional_office_charge = true; ?>
                                    @endif
                                @endforeach
                                @if ($has_additional_office_charge == false)
                                    <tr class="mis-office-row">
                                        <td class="text-center">{{ $i }}</td>
                                        <td>{{ $key->office_name }}</td>
                                        <td>{{ $key->department_name }}</td>
                                        <td>{{ $key->office_type_desc }}</td>
                                        <td>{{ $key->zone_name }}</td>
                                        <td>{{ $key->circle_name }}</td>
                                        <td>{{ $key->division_name }}</td>
                                        <td>{{ $key->sub_div_name }}</td>
                                    </tr>
                                @endif
                                <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('styles')
    <style>
        .mis-offices-page {
            color: var(--oamis-ink);
            font-family: var(--oamis-font);
            padding-bottom: 28px;
        }

        .mis-offices-breadcrumb {
            align-items: center;
            color: var(--oamis-muted);
            display: flex;
            flex-wrap: wrap;
            font-size: 13px;
            font-weight: 700;
            gap: 8px;
            margin-bottom: 14px;
        }

        .mis-offices-breadcrumb a {
            color: var(--oamis-primary);
        }

        .mis-offices-hero {
            align-items: center;
            background:
                linear-gradient(135deg, rgba(11, 107, 74, .12), transparent 50%),
                var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 22px;
            box-shadow: var(--oamis-shadow);
            display: flex;
            gap: 18px;
            justify-content: space-between;
            margin-bottom: 18px;
            padding: 22px 24px;
        }

        .mis-offices-eyebrow,
        .mis-offices-card-header span:not(.spanOfficesWitoutOfficer) {
            color: var(--oamis-primary);
            display: block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .mis-offices-hero h1 {
            color: var(--oamis-ink);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.03em;
            margin: 4px 0 6px;
        }

        .mis-offices-hero p {
            color: var(--oamis-muted);
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            max-width: 760px;
        }

        .mis-offices-stat {
            background: rgba(11, 107, 74, .08);
            border: 1px solid rgba(11, 107, 74, .18);
            border-radius: 18px;
            min-width: 190px;
            padding: 14px 16px;
            text-align: right;
        }

        .mis-offices-stat span,
        .mis-offices-stat strong {
            display: block;
        }

        .mis-offices-stat span {
            color: var(--oamis-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .mis-offices-stat strong {
            color: var(--oamis-primary);
            font-size: 26px;
            font-weight: 800;
            line-height: 1.1;
            margin-top: 4px;
        }

        .mis-offices-card {
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 22px;
            box-shadow: var(--oamis-shadow);
            overflow: hidden;
        }

        .mis-offices-card-header {
            align-items: center;
            background: var(--oamis-card);
            border-bottom: 1px solid var(--oamis-border);
            display: flex;
            gap: 16px;
            justify-content: space-between;
            padding: 16px 18px;
        }

        .mis-offices-card-header > div {
            border-left: 4px solid var(--oamis-primary);
            padding-left: 12px;
        }

        .mis-offices-card-header > div > span {
            color: var(--oamis-primary) !important;
        }

        .mis-offices-card-header > div > strong {
            color: var(--oamis-ink) !important;
        }

        .mis-offices-card-header strong {
            display: block;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .mis-offices-card-header .dt-buttons {
            background: transparent !important;
            color: var(--oamis-ink) !important;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
            margin: 0;
        }

        .mis-offices-card-header .dt-buttons .dt-button,
        .mis-offices-card-header .dt-buttons button,
        .mis-offices-card-header .dt-buttons a,
        .mis-offices-card-header .dt-buttons .btn {
            background: rgba(11, 107, 74, .1) !important;
            border: 1px solid rgba(11, 107, 74, .24) !important;
            border-radius: 999px !important;
            box-shadow: none !important;
            color: var(--oamis-primary) !important;
            font-family: var(--oamis-font) !important;
            font-size: 13px !important;
            font-weight: 800 !important;
            line-height: 1.2 !important;
            min-height: 36px;
            padding: 9px 14px !important;
            text-transform: uppercase;
        }

        .mis-offices-card-header .dt-buttons .dt-button span,
        .mis-offices-card-header .dt-buttons button span,
        .mis-offices-card-header .dt-buttons a span,
        .mis-offices-card-header .dt-buttons .btn span {
            color: var(--oamis-primary) !important;
        }

        .mis-offices-card-header .dt-buttons .dt-button:hover,
        .mis-offices-card-header .dt-buttons button:hover,
        .mis-offices-card-header .dt-buttons a:hover,
        .mis-offices-card-header .dt-buttons .btn:hover {
            background: #0b6b4a !important;
            border-color: #0b6b4a !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .mis-offices-card-header .dt-buttons .dt-button:hover span,
        .mis-offices-card-header .dt-buttons button:hover span,
        .mis-offices-card-header .dt-buttons a:hover span,
        .mis-offices-card-header .dt-buttons .btn:hover span {
            color: #ffffff !important;
        }

        .mis-offices-table-wrap {
            border: 0 !important;
            border-radius: 0 !important;
        }

        .mis-offices-table {
            color: var(--oamis-ink) !important;
            margin-bottom: 0 !important;
            white-space: nowrap;
        }

        .mis-offices-table thead th {
            min-width: 120px;
            vertical-align: top;
        }

        .mis-offices-th-label {
            color: var(--oamis-table-head-text) !important;
            display: block;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .05em;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .mis-offices-filter {
            background-color: #ffffff !important;
            border: 1px solid var(--oamis-border) !important;
            border-radius: 12px !important;
            color: #0f172a !important;
            font-family: var(--oamis-font) !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            min-height: 38px;
            width: 100% !important;
        }

        .mis-offices-filter option {
            background: #ffffff !important;
            color: #0f172a !important;
            font-family: var(--oamis-font) !important;
            font-weight: 700;
        }

        .mis-offices-table tbody tr,
        .mis-offices-table tbody td {
            background-color: var(--oamis-card) !important;
            color: var(--oamis-ink) !important;
            font-size: 14px;
            font-weight: 600;
        }

        .mis-offices-table.table-striped tbody tr:nth-of-type(odd) td {
            background-color: color-mix(in srgb, var(--oamis-soft) 50%, var(--oamis-card)) !important;
        }

        .mis-offices-table.table-hover tbody tr:hover td {
            background-color: rgba(11, 107, 74, .08) !important;
            color: var(--oamis-ink) !important;
        }

        html[data-theme="dark"] .mis-offices-hero,
        html[data-theme="dark"] .mis-offices-card,
        html[data-theme="dark"] .mis-offices-card-header,
        html[data-theme="dark"] .mis-offices-table tbody tr,
        html[data-theme="dark"] .mis-offices-table tbody td {
            background-color: var(--oamis-card) !important;
            color: var(--oamis-ink) !important;
        }

        html[data-theme="dark"] .mis-offices-table.table-striped tbody tr:nth-of-type(odd) td {
            background-color: color-mix(in srgb, var(--oamis-soft) 56%, var(--oamis-card)) !important;
        }

        html[data-theme="dark"] .mis-offices-card-header .dt-buttons .dt-button,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons button,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons a,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons .btn {
            background: rgba(52, 211, 153, .14) !important;
            border-color: rgba(52, 211, 153, .28) !important;
            color: #6ee7b7 !important;
        }

        html[data-theme="dark"] .mis-offices-card-header .dt-buttons .dt-button span,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons button span,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons a span,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons .btn span {
            color: #6ee7b7 !important;
        }

        html[data-theme="dark"] .mis-offices-card-header .dt-buttons .dt-button:hover,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons button:hover,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons a:hover,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons .btn:hover {
            background: #0b6b4a !important;
            border-color: #0b6b4a !important;
            color: #ffffff !important;
        }

        html[data-theme="dark"] .mis-offices-card-header .dt-buttons .dt-button:hover span,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons button:hover span,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons a:hover span,
        html[data-theme="dark"] .mis-offices-card-header .dt-buttons .btn:hover span {
            color: #ffffff !important;
        }

        html[data-theme="dark"] .mis-offices-filter {
            background: #0f172a !important;
            border-color: #233044 !important;
            color: #f8fafc !important;
        }

        html[data-theme="dark"] .mis-offices-filter option {
            background: #111827 !important;
            color: #f8fafc !important;
        }

        @media (max-width: 991.98px) {
            .mis-offices-hero,
            .mis-offices-card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .mis-offices-stat {
                text-align: left;
                width: 100%;
            }
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        const zoneDetails = @json($zoneDetails);
        const circleDetails = @json($circleDetails);
        const divisionDetails = @json($divisionDetails);
        const subDivisionDetails = @json($subDivisionDetails);
        $(function() {
            const myDataTable = $('#tblOfficesWitoutOfficer').DataTable({
                dom: 'Bfrtip',
                "buttons": [{
                        extend: 'csv',
                        title: 'List of Offices With No User',
                        exportOptions: {
                            trim: true,
                            format: {
                                header: function(html, index, node) {
                                    if (index == 0)
                                        return 'Sr. No';
                                    if (index == 1)
                                        return 'Office Name';
                                    if (index == 2)
                                        return 'Department';
                                    if (index == 3)
                                        return 'Office Type';
                                    if (index == 4)
                                        return 'Zone';
                                    if (index == 5)
                                        return 'Circle';
                                    if (index == 6)
                                        return 'Division';
                                    if (index == 7)
                                        return 'Sub Division';
                                }
                            }
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'List of Offices With No User',
                        exportOptions: {
                            trim: true,
                            format: {
                                header: function(html, index, node) {
                                    if (index == 0)
                                        return 'Sr. No';
                                    if (index == 1)
                                        return 'Office Name';
                                    if (index == 2)
                                        return 'Department';
                                    if (index == 3)
                                        return 'Office Type';
                                    if (index == 4)
                                        return 'Zone';
                                    if (index == 5)
                                        return 'Circle';
                                    if (index == 6)
                                        return 'Division';
                                    if (index == 7)
                                        return 'Sub Division';
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'List of Offices With No User',
                        className: 'btn btn-primary glyphicon glyphicon-save-file',
                        exportOptions: {
                            trim: true,
                            format: {
                                header: function(html, index, node) {
                                    if (index == 0)
                                        return 'Sr. No';
                                    if (index == 1)
                                        return 'Office Name';
                                    if (index == 2)
                                        return 'Department';
                                    if (index == 3)
                                        return 'Office Type';
                                    if (index == 4)
                                        return 'Zone';
                                    if (index == 5)
                                        return 'Circle';
                                    if (index == 6)
                                        return 'Division';
                                    if (index == 7)
                                        return 'Sub Division';
                                }
                            }
                        }
                    }
                ]
            });

            myDataTable.buttons().container().appendTo('.spanOfficesWitoutOfficer');





            let filledName = null;
            let departmentFieldName = null;
            let zoneFieldName = null;
            let circleFieldName = null;
            let divisionFieldName = null;
            let subDivisionFieldName = null;
            const deptType = document.getElementById("selDept");
            const zoneElt = document.getElementById("selZone");
            const circleElt = document.getElementById("selCircle");
            const divElt = document.getElementById("selDivision");
            const subDivElt = document.getElementById("selSubDivision");
            let selectedDepartmentCode = 0;
            let selectedZoneCode = "A";
            let selectedCircleCode = "A";
            let selectedDivisionCode = "A";
            let selectedSubDivisionCode = "A";


            DataTable.ext.search.push(function(settings, data, dataIndex) {
                let selectedDepartment = $("#selDept :selected").text();
                let selectedZone = $("#selZone :selected").text();
                let selectedCircle = $("#selCircle :selected").text();
                let selectedDivision = $("#selDivision :selected").text();
                let selectedSubDivision = $("#selSubDivision :selected").text();

                if (departmentFieldName == "department" || zoneFieldName == "zone" || circleFieldName ==
                    "circle" || divisionFieldName == "division" || subDivisionFieldName == "subdivision") {
                    if (
                        (selectedDepartment.trim() == data[2].trim() || selectedDepartment.trim() ==
                            "ALL") &&
                        (selectedZone.trim() == data[4].trim() || selectedZone.trim() == "ALL") &&
                        (selectedCircle.trim() == data[5].trim() || selectedCircle.trim() ==
                            "ALL") &&
                        (selectedDivision.trim() == data[6].trim() || selectedDivision.trim() == "ALL") &&
                        (selectedSubDivision.trim() == data[7].trim() || selectedSubDivision.trim() ==
                            "ALL")
                    ) {
                        return true;
                    }
                }

                if (filledName == null)
                    return true;
                return false;

            })

            myDataTable.draw();

            deptType.addEventListener('change', function() {
                filledName = "department";
                departmentFieldName = "department";

                selectedDepartmentCode = $("#selDept :selected").val().trim();

                $("#selZone").empty().append('<option value="A">ALL</option>');
                $("#selCircle").empty().append('<option value="A">ALL</option>');
                $("#selDivision").empty().append('<option value="A">ALL</option>');
                $("#selSubDivision").empty().append('<option value="A">ALL</option>');

                $.each(zoneDetails, function(index, value) {
                    if (value.dept_cd == selectedDepartmentCode && selectedDepartmentCode !=
                        0) {
                        $('#selZone').append('<option value="' + value.zone_cd + '">' +
                            value.zone_name + '</option>');
                    }

                    if (selectedDepartmentCode == 0) {
                        $('#selZone').append('<option value="' + value.zone_cd + '">' +
                            value.zone_name + '</option>');
                    }
                });

                $.each(circleDetails, function(index, value) {
                    if (value.dept_cd == selectedDepartmentCode && selectedDepartmentCode !=
                        0) {
                        $('#selCircle').append('<option value="' + value.circle_cd + '">' +
                            value.circle_name + '</option>');
                    }
                    if (selectedDepartmentCode == 0) {
                        $('#selCircle').append('<option value="' + value.circle_cd + '">' +
                            value.circle_name + '</option>');
                    }
                });

                $.each(divisionDetails, function(index, value) {
                    if (value.dept_cd == selectedDepartmentCode && selectedDepartmentCode !=
                        0) {
                        $('#selDivision').append('<option value="' + value.division_cd + '">' +
                            value.division_name + '</option>');
                    }
                    if (selectedDepartmentCode == 0) {
                        $('#selDivision').append('<option value="' + value.division_cd + '">' +
                            value.division_name + '</option>');
                    }
                });

                $.each(subDivisionDetails, function(index, value) {
                    if (value.dept_cd == selectedDepartmentCode && selectedDepartmentCode !=
                        0) {
                        $('#selSubDivision').append('<option value="' + value.sub_div_cd + '">' +
                            value.sub_div_name + '</option>');
                    }
                    if (selectedDepartmentCode == 0) {
                        $('#selSubDivision').append('<option value="' + value.sub_div_cd + '">' +
                            value.sub_div_name + '</option>');
                    }
                });

                myDataTable.draw();
            });

            zoneElt.addEventListener('change', function() {
                filledName = "zone";
                zoneFieldName = "zone";

                selectedZoneCode = $("#selZone :selected").val().trim();

                $("#selCircle").empty().append('<option value="A">ALL</option>');
                $("#selDivision").empty().append('<option value="A">ALL</option>');
                $("#selSubDivision").empty().append('<option value="A">ALL</option>');

                $.each(circleDetails, function(index, value) {
                    if (value.zone_cd == selectedZoneCode && selectedZoneCode !=
                        "A") {
                        $('#selCircle').append('<option value="' + value.circle_cd + '">' +
                            value.circle_name + '</option>');
                    }
                    if (selectedZoneCode == "A") {
                        $('#selCircle').append('<option value="' + value.circle_cd + '">' +
                            value.circle_name + '</option>');
                    }
                });

                $.each(divisionDetails, function(index, value) {
                    if (value.zone_cd == selectedZoneCode && selectedZoneCode !=
                        "A") {
                        $('#selDivision').append('<option value="' + value.division_cd + '">' +
                            value.division_name + '</option>');
                    }
                    if (selectedZoneCode == "A") {
                        $('#selDivision').append('<option value="' + value.division_cd + '">' +
                            value.division_name + '</option>');
                    }
                });

                $.each(subDivisionDetails, function(index, value) {
                    if (value.zone_cd == selectedZoneCode && selectedZoneCode !=
                        "A") {
                        $('#selSubDivision').append('<option value="' + value.sub_div_cd + '">' +
                            value.sub_div_name + '</option>');
                    }
                    if (selectedZoneCode == "A") {
                        $('#selSubDivision').append('<option value="' + value.sub_div_cd + '">' +
                            value.sub_div_name + '</option>');
                    }
                });

                myDataTable.draw();
            });

            circleElt.addEventListener('change', function() {
                filledName = "circle";
                circleFieldName = "circle";

                selectedCircleCode = $("#selCircle :selected").val().trim();

                $("#selDivision").empty().append('<option value="A">ALL</option>');
                $("#selSubDivision").empty().append('<option value="A">ALL</option>');

                $.each(divisionDetails, function(index, value) {
                    if (value.zone_cd == selectedCircleCode && selectedCircleCode !=
                        "A") {
                        $('#selDivision').append('<option value="' + value.division_cd + '">' +
                            value.division_name + '</option>');
                    }
                    if (selectedCircleCode == "A") {
                        $('#selDivision').append('<option value="' + value.division_cd + '">' +
                            value.division_name + '</option>');
                    }
                });

                $.each(subDivisionDetails, function(index, value) {
                    if (value.zone_cd == selectedCircleCode && selectedCircleCode !=
                        "A") {
                        $('#selSubDivision').append('<option value="' + value.sub_div_cd + '">' +
                            value.sub_div_name + '</option>');
                    }
                    if (selectedCircleCode == "A") {
                        $('#selSubDivision').append('<option value="' + value.sub_div_cd + '">' +
                            value.sub_div_name + '</option>');
                    }
                });

                myDataTable.draw();
            });


            divElt.addEventListener('change', function() {
                filledName = "division";
                divisionFieldName = "division";

                selectedDivisionCode = $("#selDivision :selected").val().trim();

                $("#selSubDivision").empty().append('<option value="A">ALL</option>');

                $.each(subDivisionDetails, function(index, value) {
                    if (value.zone_cd == selectedDivisionCode && selectedDivisionCode !=
                        "A") {
                        $('#selSubDivision').append('<option value="' + value.sub_div_cd + '">' +
                            value.sub_div_name + '</option>');
                    }
                    if (selectedDivisionCode == "A") {
                        $('#selSubDivision').append('<option value="' + value.sub_div_cd + '">' +
                            value.sub_div_name + '</option>');
                    }
                });

                myDataTable.draw();
            });
        });
    </script>

    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
