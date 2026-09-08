@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">View Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <!-- table content -->
        <div class="container-fluid border mainBody">
            <div class="border mt-2" style="border-radius: .3rem;">
                <div class="row justify-content-between align-item-center">
                    <form method="GET" action="{{ route('viewUsers') }}" class="w-100 p-2">
                        <input type="hidden" name="filter_applied" value="1">
                        <p class="text-bold mb-2">Filter Users</p>
                        <div class="row text-xs">
                            <div class="col-md-3 mb-2">
                                <label for="view_filter_department">Department</label>
                                <select class="form-control form-control-sm select2" id="view_filter_department"
                                    name="filter_department">
                                    <option value="">All Departments</option>
                                    @foreach ($departmentDetails as $department)
                                        <option value="{{ $department->id }}"
                                            {{ (string) request('filter_department') === (string) $department->id ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="view_filter_status">Status</label>
                                <select class="form-control form-control-sm select2" id="view_filter_status"
                                    name="filter_status">
                                    <option value="">All Statuses</option>
                                    <option value="A" {{ request('filter_status') === 'A' ? 'selected' : '' }}>Active</option>
                                    <option value="D" {{ request('filter_status') === 'D' ? 'selected' : '' }}>Deactive</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="view_filter_office_type">Office Type</label>
                                <select class="form-control form-control-sm select2" id="view_filter_office_type"
                                    name="filter_office_type">
                                    <option value="">All Office Types</option>
                                    @foreach ($officeTypes as $officeType)
                                        <option value="{{ $officeType->office_type_cd }}"
                                            {{ (string) request('filter_office_type') === (string) $officeType->office_type_cd ? 'selected' : '' }}>
                                            {{ $officeType->office_type_desc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="view_filter_office">Office Name</label>
                                <select class="form-control form-control-sm select2" id="view_filter_office"
                                    name="filter_office">
                                    <option value="">All Offices</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="view_filter_designation">Designation</label>
                                <select class="form-control form-control-sm select2" id="view_filter_designation"
                                    name="filter_designation">
                                    <option value="">All Designations</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="view_filter_user_search">User Name or Email</label>
                                <input type="search" class="form-control form-control-sm"
                                    id="view_filter_user_search" name="filter_user_search"
                                    value="{{ request('filter_user_search') }}"
                                    placeholder="Enter user name or email">
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('viewUsers') }}" class="btn btn-secondary btn-sm">Reset</a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-search mr-1"></i>View Users
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="border mt-2 p-2" style="border-radius: .3rem;">
                <form id="viewUser" class="mb-0">
                    @csrf
                    <p class="text-bold mb-2">Choose the period to view users:</p>
                    <div class="row align-items-end text-xs">
                        <div class="col-sm-6 col-md-3 mb-2">
                            <label for="start_date">Start Date</label>
                            <input type="date" id="start_date" class="form-control form-control-sm"
                                name="start_date" required>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-2">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" class="form-control form-control-sm"
                                name="end_date" required>
                        </div>
                        <div class="col-sm-12 col-md-2 mb-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-calendar-alt mr-1"></i>View by Period
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="container-fluid mt-3">
                @if (!$filtersApplied)
                    <div class="alert alert-info text-sm" id="userFilterPrompt">
                        Select filter criteria, or leave all options as <strong>All</strong>, then click
                        <strong>View Users</strong> to load the user list.
                    </div>
                @endif
                <span class="mis-btn-office"></span>
                <table class="table-responsive text-xs table table-bordered table-striped user_list" id="user_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <tr>
                            <th class="text-center">Sl. No</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Gender
                                @if (session('users_office_type_cd') == 'ADM' || session('users_office_type_cd') == 'SO')
                                    <select name="searchByGender" id="searchByGender"
                                        style="padding: 0.15rem 0; width: 8rem;">
                                        <option value="A">All</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                @endif
                            </th>
                            <th class="text-center">
                                Department
                                @if (session('users_office_type_cd') == 'ADM' || session('users_office_type_cd') == 'SO')
                                    <select name="searchByDepartment" id="searchByDepartment"
                                        style="padding: 0.15rem 0; width: 8rem;">
                                        <option value="A">All</option>
                                        @foreach ($departmentDetails as $item)
                                            <option value={{ $item->department_name }}>
                                                {{ $item->department_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </th>
                            <th class="text-center">Office Type
                                @if (session('users_office_type_cd') == 'ADM' || session('users_office_type_cd') == 'SO')
                                    <select name="searchByOfficeType" id="searchByOfficeType"
                                        style="padding: 0.15rem 0; width: 8rem;">
                                        <option value="A">All</option>
                                        @foreach ($officeTypes as $item)
                                            <option value={{ $item->office_type_desc }}>
                                                {{ $item->office_type_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </th>
                            <th class="text-center">Office
                                @if (session('users_office_type_cd') == 'ADM' || session('users_office_type_cd') == 'SO')
                                    <select name="searchByOffice" id="searchByOffice"
                                        style="padding: 0.15rem 0; width: 8rem;">
                                        <option value="A">All</option>
                                        @foreach ($officeDetails as $item)
                                            <option value={{ $item->office_name }}>
                                                {{ $item->office_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </th>
                            <th class="text-center">Designation
                                @if (session('users_office_type_cd') == 'ADM' || session('users_office_type_cd') == 'SO')
                                    <select name="searchByDesignation" id="searchByDesignation"
                                        style="padding: 0.15rem 0; width: 8rem;">
                                        <option value="A">All</option>
                                        @foreach ($designationDetails as $item)
                                            <option value={{ $item->desg_name }}>
                                                {{ $item->desg_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </th>
                            <th class="text-center">Qualification
                                @if (session('users_office_type_cd') == 'ADM' || session('users_office_type_cd') == 'SO')
                                    <select name="searchByQualification" id="searchByQualification"
                                        style="padding: 0.15rem 0; width: 8rem;">
                                        <option value="A">All</option>
                                        @foreach ($qualificationDetails as $item)
                                            <option value={{ $item->details }}>
                                                {{ $item->details }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </th>
                            <th class="text-center">Joining Date</th>
                            <th class="text-center">Status
                                @if (session('users_office_type_cd') == 'ADM' || session('users_office_type_cd') == 'SO')
                                    <select name="searchByStatus" id="searchByStatus"
                                        style="padding: 0.15rem 0; width: 8rem;">
                                        <option value="A">All</option>
                                        <option value="Active">Active</option>
                                        <option value="Deactive">Deactive</option>
                                        @foreach ($statuChangeResons as $item)
                                            <option value={{ $item->reason }}>
                                                {{ $item->reason }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($userdetails as $key)
                            <tr>
                                <td style="min-width: 4rem;" class="text-center">{{ $i }}</td>
                                <td style="min-width: 8rem;">
                                    <a
                                        href="{{ URL::temporarySignedRoute('userMovement', now()->addMinutes(60), ['id' => $key->id, 'user' => session('userName')]) }}">
                                        {{ $key->name }}
                                    </a>
                                </td>
                                <td style="min-width: 8rem;">{{ $key->phoneno }}</td>
                                <td style="min-width: 6rem;">{{ $key->gender }}</td>
                                <td style="min-width: 8rem;">{{ $key->department_name }}</td>
                                <td style="min-width: 8rem;">{{ $key->office_type_desc }}</td>
                                <td style="min-width: 8rem;">{{ $key->office_name }}</td>
                                <td style="min-width: 8rem;">{{ $key->desg_name }}</td>
                                <td style="min-width: 8rem;">{{ $key->qualification_name }}</td>
                                <td style="min-width: 8rem;" class="text-center">
                                    {{ $key->since_current_position ? \Carbon\Carbon::parse($key->since_current_position)->format('d-m-Y') : '-' }}
                                </td>
                                <td style="min-width: 6rem;" class="text-center">
                                    @if ($key->activity_status == 'A')
                                        <a href="" data-toggle="modal" data-target="#activeModal{{ $key->id }}"
                                            class="text-success">Active</a>
                                    @else
                                        @if ($key->reason != '')
                                            <a href="" data-toggle="modal"
                                                data-target="#activeModal{{ $key->id }}"
                                                class="text-danger">{{ $key->reason }}</a>
                                        @else
                                            <a href="" data-toggle="modal"
                                                data-target="#activeModal{{ $key->id }}"
                                                class="text-danger">Deactive</a>
                                        @endif
                                    @endif
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
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">
    <style>
        #user_table thead select {
            appearance: auto !important;
            background-color: var(--oamis-field) !important;
            border: 1px solid var(--oamis-border) !important;
            border-radius: 10px !important;
            color: var(--oamis-ink) !important;
            display: block;
            font-size: 12px !important;
            font-weight: 700 !important;
            line-height: 1.3;
            margin: 7px auto 0;
            min-height: 32px !important;
            padding: 5px 8px !important;
            text-transform: none !important;
            width: 8.5rem !important;
        }

        #user_table thead select option {
            background-color: #ffffff !important;
            color: #0f172a !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            padding: 8px !important;
        }

        html[data-theme="dark"] #user_table thead select {
            background-color: #0f172a !important;
            border-color: var(--oamis-border) !important;
            color: #f8fafc !important;
        }

        html[data-theme="dark"] #user_table thead select option {
            background-color: #111827 !important;
            color: #f8fafc !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            const viewFilterOffices = @json($officeDetails);
            const viewFilterDesignations = @json($designationDetails);
            const selectedViewOffice = @json(request('filter_office'));
            const selectedViewDesignation = @json(request('filter_designation'));

            function loadViewFilterOffices(selectedOfficeId = null) {
                const departmentId = $('#view_filter_department').val();
                const officeTypeCode = $('#view_filter_office_type').val();
                const officeSelect = $('#view_filter_office');
                officeSelect.empty().append(new Option('All Offices', ''));

                if (!departmentId || !officeTypeCode) {
                    officeSelect.prop('disabled', true).trigger('change.select2');
                    return;
                }

                viewFilterOffices.forEach(function(office) {
                    if (String(office.department_id) === String(departmentId) &&
                        String(office.office_type_cd) === String(officeTypeCode)) {
                        officeSelect.append(new Option(office.office_name, office.id));
                    }
                });

                officeSelect.prop('disabled', false);
                if (selectedOfficeId) {
                    officeSelect.val(String(selectedOfficeId));
                }
                officeSelect.trigger('change.select2');
            }

            function loadViewFilterDesignations(selectedDesignationId = null) {
                const departmentId = $('#view_filter_department').val();
                const designationSelect = $('#view_filter_designation');
                designationSelect.empty().append(new Option('All Designations', ''));

                if (!departmentId) {
                    designationSelect.prop('disabled', true).trigger('change.select2');
                    return;
                }

                viewFilterDesignations.forEach(function(designation) {
                    if (String(designation.dept_cd) === String(departmentId)) {
                        designationSelect.append(new Option(designation.desg_name, designation.id));
                    }
                });

                designationSelect.prop('disabled', false);
                if (selectedDesignationId) {
                    designationSelect.val(String(selectedDesignationId));
                }
                designationSelect.trigger('change.select2');
            }

            loadViewFilterOffices(selectedViewOffice);
            loadViewFilterDesignations(selectedViewDesignation);

            $('#view_filter_department, #view_filter_status, #view_filter_office_type, #view_filter_office, #view_filter_designation')
                .select2({ width: '100%' });

            $('#view_filter_office_type').on('change', function() {
                loadViewFilterOffices();
            });

            $('#view_filter_department').on('change', function() {
                loadViewFilterDesignations();
                loadViewFilterOffices();
            });
        });
    </script>

    <script>
        $(function() {
            $("#user_table")
                .DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'csvHtml5',
                        header: false
                    }, {
                        extend: 'excelHtml5',
                        title: 'List of Users',
                        // header: false,
                        exportOptions: {
                            trim: true,
                            // stripHtml: true,
                            format: {
                                header: function(html, index, node) {
                                    if (index == 0)
                                        return 'Sr. No';
                                    if (index == 1)
                                        return 'Name';
                                    if (index == 2)
                                        return 'Phone No';
                                    if (index == 3)
                                        return 'Gender';
                                    if (index == 4)
                                        return 'Department';
                                    if (index == 5)
                                        return 'Office Type';
                                    if (index == 6)
                                        return 'Office';
                                    if (index == 7)
                                        return 'Designation';
                                    if (index == 8)
                                        return 'Qualification';
                                    if (index == 9)
                                        return 'Joining Date';
                                    if (index == 10)
                                        return 'Status';
                                }
                            }
                        }
                    }],
                })
                .buttons()
                .container()
                .appendTo(".mis-btn-office");
        });
        $(".modalClose").on("click", function() {
            location.reload();
        });

        $(function() {
            let filledName = null;
            let genderFieldName = null;
            let departmentFieldName = null;
            let officeTypeFieldName = null;
            let officeFieldName = null;
            let designationFieldName = null;
            let qualificationFieldName = null;
            let statusFieldName = null;
            let selectedDepartment = null;
            let selectedDepartmentCode = null;
            let selectedOfficeTypeCode = null;
            const officeDetails = @json($officeDetails);
            const deptType = document.getElementById("searchByDepartment");
            const userDesignation = document.getElementById("searchByDesignation");
            const userGender = document.getElementById("searchByGender");
            const userOfficeType = document.getElementById("searchByOfficeType");
            const userOffice = document.getElementById("searchByOffice");
            const userStatus = document.getElementById("searchByStatus");
            const userQualification = document.getElementById("searchByQualification");

            DataTable.ext.search.push(function(settings, data, dataIndex) {
                selectedDepartment = $("#searchByDepartment :selected").text();
                let selectedDesignation = $("#searchByDesignation :selected").text();
                let selectedGender = $("#searchByGender :selected").text();
                let selectedOfficeType = $("#searchByOfficeType :selected").text();
                let selectedOffice = $("#searchByOffice :selected").text();
                let selectedStatus = $("#searchByStatus :selected").text();
                let selectedQualification = $("#searchByQualification :selected").text();

                if (genderFieldName == "gender" || departmentFieldName == "department" ||
                    officeTypeFieldName == "officeType" || officeFieldName == "office") {
                    if (
                        ((isNaN(selectedGender) && selectedGender.trim() == data[3].trim()) || (isNaN(
                            selectedGender) && selectedGender.trim() == "All")) &&
                        ((isNaN(selectedDepartment) && selectedDepartment.trim() == data[4].trim()) ||
                            (isNaN(selectedDepartment) && selectedDepartment.trim() == "All")) &&
                        ((isNaN(selectedOfficeType) && selectedOfficeType.trim() == data[5].trim()) ||
                            (isNaN(selectedOfficeType) && selectedOfficeType.trim() == "All")) &&
                        (selectedOffice.trim() == data[6].trim() || selectedOffice.trim() == "All") &&
                        (selectedDesignation.trim() == data[7].trim() || selectedDesignation.trim() ==
                            "All") &&
                        (selectedQualification.trim() == data[8].trim() || selectedQualification.trim() ==
                            "All") &&
                        (selectedStatus.trim() == data[10].trim() || selectedStatus.trim() == "All")
                    ) {
                        return true;
                    }
                }

                if (filledName == null)
                    return true;
                return false;

            })

            const myDataTable = new DataTable('#user_table');
            myDataTable.draw();
            // const myDataTable = new DataTable('#user_table');
            // myDataTable.draw();

            userGender.addEventListener('change', function() {
                filledName = "gender";
                genderFieldName = "gender";
                // myDataTable.draw();
            });


            deptType.addEventListener('change', function() {
                filledName = "department";
                departmentFieldName = "department";
                if ($("#searchByDepartment :selected")
                    .text().trim() === "Roads and Bridges")
                    selectedDepartmentCode = 14;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "Housing Department")
                    selectedDepartmentCode = 6;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "Mechanical Department")
                    selectedDepartmentCode = 15;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "National Highway")
                    selectedDepartmentCode = 3;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "Secretariat")
                    selectedDepartmentCode = 16;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "NPWD")
                    selectedDepartmentCode = 18;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "Police Engineering Project")
                    selectedDepartmentCode = 19;
                if ($("#searchByDepartment :selected")
                    .text().trim() == "Planning And Design")
                    selectedDepartmentCode = 20;
                if (($("#searchByDepartment :selected")
                        .text()).trim() == "All")
                    selectedDepartmentCode = 0;
                $("#searchByOffice").empty().append('<option value="A">All</option>');
                $.each(officeDetails, function(index, value) {
                    if (value.department_id == selectedDepartmentCode && selectedDepartmentCode !=
                        0) {
                        $('#searchByOffice').append('<option value="' + value.office_name +
                            '">' +
                            value
                            .office_name +
                            '</option>');
                    }

                    if (selectedDepartmentCode == 0) {
                        $('#searchByOffice').append('<option value="' + value.office_name +
                            '">' +
                            value
                            .office_name +
                            '</option>');
                    }
                });
                myDataTable.draw();

            });


            userOfficeType.addEventListener('change', function() {
                filledName = "officeType";
                officeTypeFieldName = "officeType";

                if ($("#searchByOfficeType :selected")
                    .text().trim() === "System Admin")
                    selectedOfficeTypeCode = "ADM";
                if ($("#searchByOfficeType :selected")
                    .text().trim() === "Secretarial Office")
                    selectedOfficeTypeCode = "SO";
                if ($("#searchByOfficeType :selected")
                    .text().trim() === "Departmental Admin")
                    selectedOfficeTypeCode = "DA";
                if ($("#searchByOfficeType :selected")
                    .text().trim() === "Engineer in Chief Office")
                    selectedOfficeTypeCode = "ECO";
                if ($("#searchByOfficeType :selected")
                    .text().trim() === "Chief Engineers Office")
                    selectedOfficeTypeCode = "HQ";
                if ($("#searchByOfficeType :selected")
                    .text().trim() == "Zonal Office")
                    selectedOfficeTypeCode = "ZO";
                if ($("#searchByOfficeType :selected")
                    .text().trim() === "Circle Office")
                    selectedOfficeTypeCode = "CO";
                if ($("#searchByOfficeType :selected")
                    .text().trim() === "Division Office")
                    selectedOfficeTypeCode = "DO";
                if ($("#searchByOfficeType :selected")
                    .text().trim() == "Sub Division Office")
                    selectedOfficeTypeCode = "SDO";
                if (($("#searchByOfficeType :selected")
                        .text()).trim() == "All")
                    selectedOfficeTypeCode = "0";

                if ($("#searchByDepartment :selected")
                    .text().trim() === "Roads and Bridges")
                    selectedDepartmentCode = 14;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "Housing Department")
                    selectedDepartmentCode = 6;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "Mechanical Department")
                    selectedDepartmentCode = 15;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "National Highway")
                    selectedDepartmentCode = 3;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "Secretariat")
                    selectedDepartmentCode = 16;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "NPWD")
                    selectedDepartmentCode = 18;
                if ($("#searchByDepartment :selected")
                    .text().trim() === "Police Engineering Project")
                    selectedDepartmentCode = 19;
                if ($("#searchByDepartment :selected")
                    .text().trim() == "Planning And Design")
                    selectedDepartmentCode = 20;
                if (($("#searchByDepartment :selected")
                        .text()).trim() == "All")
                    selectedDepartmentCode = 0;

                console.log("11 selectedOfficeTypeCode : " + selectedOfficeTypeCode);
                console.log("22 selectedDepartmentCode : " + selectedDepartmentCode);
                $("#searchByOffice").empty().append('<option value="A">All</option>');
                if (selectedDepartmentCode != 0) {
                    $.each(officeDetails, function(index, value) {
                        if (selectedOfficeTypeCode !=
                            "0" && value.office_type_cd == selectedOfficeTypeCode &&
                            value.department_id == selectedDepartmentCode) {
                            console.log("rr1");
                            $('#searchByOffice').append('<option value="' + value.office_name +
                                '">' +
                                value
                                .office_name +
                                '</option>');
                        }

                        if (selectedOfficeTypeCode ==
                            "0" &&
                            value.department_id == selectedDepartmentCode) {
                            console.log("rr2");
                            $('#searchByOffice').append('<option value="' + value.office_name +
                                '">' +
                                value
                                .office_name +
                                '</option>');
                        }
                    });
                }

                if (selectedDepartmentCode == 0) {
                    if (selectedOfficeTypeCode !=
                        "0") {
                        $.each(officeDetails, function(index, value) {
                            if (value.office_type_cd == selectedOfficeTypeCode) {
                                console.log("rr3");
                                $('#searchByOffice').append('<option value="' + value.office_name +
                                    '">' +
                                    value.office_name +
                                    '</option>');
                            }
                        });
                    }

                    if (selectedOfficeTypeCode ==
                        "0") {
                        $.each(officeDetails, function(index, value) {
                            console.log("rr4");
                            $('#searchByOffice').append('<option value="' + value.office_name +
                                '">' +
                                value.office_name +
                                '</option>');
                        });
                    }
                }
                myDataTable.draw();

            });

            userOffice.addEventListener('change', function() {
                filledName = "office";
                officeFieldName = "office";
                // myDataTable.draw();
            });


            userDesignation.addEventListener('change', function() {
                filledName = "designation";
                designationFieldName = "designation";
                // myDataTable.draw();
            });





            userQualification.addEventListener('change', function() {
                filledName = "qualification";
                qualificationFieldName = "qualification";
                // myDataTable.draw();
            });

            userStatus.addEventListener('change', function() {
                filledName = "status";
                statusFieldName = "status";
                // myDataTable.draw();
            });
        });

        $(document).on("submit", "#viewUser", function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "/asset-management/user-period",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: $("#viewUser").serialize(),
                cache: false,
                success: function(response) {
                    console.log(response);
                    const table_id = $("#user_table");
                    if (response.status === 200) {
                        $('#userFilterPrompt').hide();
                        table_id.find("tbody").empty();
                        var userDataTable = new DataTable('#user_table');
                        if (response.result.length === 0) {
                            $('.road_count').html('00');
                            table_id
                                .find("tbody")
                                .html(
                                    '<tr><td colspan="18" class="text-center">No matching records found</td></tr>'
                                );
                            userDataTable.clear().draw();
                            // loader.hide();
                            // content.removeClass("blur-background");
                            // $("#tableContent").slideUp('slow');
                            // if ($('#toggleBtn').hasClass("fa-caret-down")) {
                            //     $("#toggleBtn").removeClass("fa-caret-down").addClass("fa-caret-left");
                            // }
                            // alert('No data available!');
                        } else {
                            // $('.road_count').html(response.result.length);
                            // table_id.find("tbody").empty();
                            // var rd_detail_data_table = new DataTable('#user_table');
                            // var tot_rd_len = 0;
                            userDataTable.clear().draw();
                            $.each(response.result, function(index, data) {
                                // tot_rd_len = tot_rd_len + parseFloat(data.road_length);
                                // tot_rd_len = parseFloat(tot_rd_len + 5.556).toFixed(3);
                                userDataTable.row.add([++index,
                                    data.name,
                                    data.phoneno,
                                    data.gender,
                                    data.department_name,
                                    data.office_type_desc,
                                    data.office_name,
                                    data.desg_name,
                                    data.qualification_name,
                                    data.since_current_position ? String(data.since_current_position)
                                        .substring(0, 10).split('-').reverse().join('-') : '-',
                                    (data.activity_status == 'D') ? 'Deactivated' :
                                    'Activate',
                                ]);
                            });
                            // loader.hide();
                            // content.removeClass("blur-background");
                            userDataTable.draw();
                            // $('.tot_rd_len').html(parseFloat(tot_rd_len).toFixed(3));

                            // $("#tableContent").slideDown('slow');
                            // if ($('#toggleBtn').hasClass("fa-caret-left")) {
                            //     $("#toggleBtn").removeClass("fa-caret-left").addClass("fa-caret-down");
                            // }
                        }
                    } else {
                        // loader.hide();
                        // content.removeClass("blur-background");
                        alert("failed to fetch the user details!");
                    }
                },
            });
        })
    </script>
@endpush
