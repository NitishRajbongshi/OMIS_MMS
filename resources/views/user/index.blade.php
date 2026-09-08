@extends('layouts.app')
@section('content')
    <style>
        .colHead {
            background: #C2C0C0;
            padding: 2px;
            font-weight: 600;
        }
    </style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Manage Users</li>
                    </ol>
                </div>
                <div class="col-md-2 col-sm-6">
                    <a href="{{ route('createUser') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus"></i> Add New User</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="card mb-3">
            <div class="card-header text-dark" style="background-color:#C8C8C8">
                <h3 class="card-title text-bold">Filter Users</h3>
            </div>
            <div class="card-body pb-2">
                <form method="GET" action="{{ route('manageUser') }}">
                    <input type="hidden" name="filter_applied" value="1">
                    <div class="row text-xs">
                        <div class="col-md-3 mb-2">
                            <label for="filter_department">Department</label>
                            <select class="form-control form-control-sm select2" id="filter_department" name="filter_department">
                                <option value="">All Departments</option>
                                @foreach ($deptdetails as $department)
                                    <option value="{{ $department->id }}"
                                        {{ (string) request('filter_department') === (string) $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="filter_status">Status</label>
                            <select class="form-control form-control-sm select2" id="filter_status" name="filter_status">
                                <option value="">All Statuses</option>
                                <option value="A" {{ request('filter_status') === 'A' ? 'selected' : '' }}>Active</option>
                                <option value="D" {{ request('filter_status') === 'D' ? 'selected' : '' }}>Deactive</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="filter_office_type">Office Type</label>
                            <select class="form-control form-control-sm select2" id="filter_office_type" name="filter_office_type">
                                <option value="">All Office Types</option>
                                @foreach ($officeTypeDetails as $officeType)
                                    <option value="{{ $officeType->office_type_cd }}"
                                        {{ (string) request('filter_office_type') === (string) $officeType->office_type_cd ? 'selected' : '' }}>
                                        {{ $officeType->office_type_desc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="filter_office">Office Name</label>
                            <select class="form-control form-control-sm select2" id="filter_office" name="filter_office">
                                <option value="">All Offices</option>
                                @foreach ($officedetails as $office)
                                    <option value="{{ $office->id }}"
                                        {{ (string) request('filter_office') === (string) $office->id ? 'selected' : '' }}>
                                        {{ $office->office_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="filter_designation">Designation</label>
                            <select class="form-control form-control-sm select2" id="filter_designation" name="filter_designation">
                                <option value="">All Designations</option>
                                @foreach ($desgdetails as $designation)
                                    <option value="{{ $designation->id }}"
                                        {{ (string) request('filter_designation') === (string) $designation->id ? 'selected' : '' }}>
                                        {{ $designation->desg_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="filter_user_search">User Name or Email</label>
                            <input type="search" class="form-control form-control-sm" id="filter_user_search"
                                name="filter_user_search" value="{{ request('filter_user_search') }}"
                                placeholder="Enter user name or email">
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('manageUser') }}" class="btn btn-secondary btn-sm">Reset</a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search mr-1"></i>View Users
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header text-dark" style="background-color:#C8C8C8">
                <h3 class="card-title text-bold">List of Users
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if (!$filtersApplied)
                    <div class="alert alert-info text-sm mb-3">
                        Select filter criteria, or leave all options as <strong>All</strong>, then click
                        <strong>View Users</strong> to load the user list.
                    </div>
                @endif
                <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped text-xs text-nowrap w-100">
                    <thead class="theader" style="background-color:#C8C8C8">
                        <tr>
                            <th class="text-center">Sl. No</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Designation</th>
                            <th class="text-center">Roles</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Menu Access</th>
                            <th class="text-center">View</th>
                            <th class="text-center">Edit</th>
                            <th class="text-center">Office Name</th>
                            <th class="text-center">Zone</th>
                            <th class="text-center">Circle</th>
                            <th class="text-center">Division</th>
                            <th class="text-center">Sub Division</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($userdetails as $key)
                            @if ($key->user_role_id != '1' && $key->user_role_id != '2')
                                <tr>
                                    <th class="text-center">{{ $i }}</th>
                                    <th>{{ $key->name }}</th>
                                    <th>{{ $key->email }}</th>
                                    <th>{{ $key->desg_name ?? '-' }}</th>
                                    <th class="text-center">
                                        <a href="" data-toggle="modal" data-target="#assignUserRoleModal"
                                            data-role-ids="{{ $key->role_ids }}" data-user-name="{{ $key->name }}"
                                            data-user-email="{{ $key->email }}" data-user-id="{{ $key->id }}"
                                            class="btn btn-xs btn-primary text-white">View/Assign</a>
                                    </th>
                                    <th class="text-center">
                                        @if ($key->activity_status == 'A')
                                            <p class="text-primary">Active</p>
                                        @else
                                            <p class="text-danger">Deactive</p>
                                        @endif
                                    </th>
                                    <th class="text-center">
                                        <a href="" data-toggle="modal" data-target="#editUserMenuAccessModal"
                                            data-menu-ids="{{ $key->menu_ids }}" data-user-name1="{{ $key->name }}"
                                            data-user-email1="{{ $key->email }}" data-user-id1="{{ $key->id }}"
                                            class="btn btn-xs btn-primary text-white">View/Edit</a>
                                    </th>
                                    <th class="text-center">
                                        @if (session('viewed') == 1)
                                            <a class="text-primary viewall" data-toggle="modal" data-user-name="{{ $key->name }}"
                                                data-user-phone-no="{{ $key->phoneno }}" data-user-email="{{ $key->email }}"
                                                data-uder-gender="{{ $key->gender }}" data-user-address1="{{ $key->address1 }}"
                                                data-user-address2="{{ $key->address2 }}" data-user-pin="{{ $key->pin }}"
                                                data-user-dist-name="{{ $key->dist_name }}"
                                                data-user-state-name="{{ $key->state_name }}"
                                                data-user-country-name="{{ $key->country }}" data-user-desg-name="{{ $key->desg_name }}"
                                                data-user-dept-name="{{ $key->department_name }}"
                                                data-user-office-name="{{ $key->office_name }}"
                                                data-user-post-name="{{ $key->post_name }}"
                                                data-user-activity-status="{{ $key->activity_status == 'A' ? 'Activated' : 'Deactived' }}"
                                                data-user-role-names="{{ $key->rolename == null ? 'Not Assigned Yet' : $key->rolename }}"
                                                data-target="#viewUserModel"><i class="fas fa-eye"></i></a>
                                        @else
                                            <a class=""><i class="fas fa-eye-slash"></i></a>
                                        @endif
                                    </th>
                                    <th class="text-center">
                                        @if (session('updated') == 1)
                                            <a class="text-warning edit" data-toggle="modal" data-user-id="{{ $key->id }}"
                                                data-user-name="{{ $key->name }}" data-user-phone-no="{{ $key->phoneno }}"
                                                data-user-addr1="{{ $key->address1 }}" data-user-adrr2="{{ $key->address2 }}"
                                                data-user-dist-cd="{{ $key->district }}" data-user-dist-name="{{ $key->dist_name }}"
                                                data-user-pin="{{ $key->pin }}" data-user-gender="{{ $key->gender }}"
                                                data-user-dept-cd="{{ $key->department }}"
                                                data-user-dept-name="{{ $key->department_name }}"
                                                data-user-desg-cd="{{ $key->designation }}" data-user-desg-name="{{ $key->desg_name }}"
                                                data-user-office-type-cd="{{ $key->office_type_cd }}"
                                                data-user-office-type-descr="{{ $key->office_type_desc }}"
                                                data-user-office-cd="{{ $key->office }}" data-user-office-name="{{ $key->office_name }}"
                                                data-user-status="{{ $key->activity_status }}" data-target="#editUserDetailsModal"><i
                                                    class="fas fa-edit"></i></a>
                                        @else
                                            <span class="text-danger text-bold"><i class="fas fa-ban"></i></span>
                                        @endif
                                    </th>
                                    <th>{{ $key->office_name ?? '-' }}</th>
                                    <th>{{ $key->zone_name ?? '-' }}</th>
                                    <th>{{ $key->circle_name ?? '-' }}</th>
                                    <th>{{ $key->division_name ?? '-' }}</th>
                                    <th>{{ $key->sub_div_name ?? '-' }}</th>
                                </tr>
                                <?php        $i++; ?>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </section>

    <!-- The Assign Role Modal -->
    <div class="modal fade" id="assignUserRoleModal">
        <div class="modal-dialog modal-lg">
            <form id="updateUserRole" name="updateUserRole">
                {{-- enctype="multipart/form-data" --}}
                {{-- "{{ route('assignUserRole') }}" --}}
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title text-bold" id="editUserRoleModalTitle">
                            <i class="fas fa-edit mr-2"></i>
                            Assign role for
                        </p>
                        <a type="button" data-dismiss="modal"><i class="fas fa-times"></i></a>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="hdnUserId" name="hdnUserId" value="" />
                        <div class="row text-xs">

                            <div class="col-md-5 p-2">
                                <label>Available Roles :</label>
                                {{-- <div class="select2-purple"> --}}
                                    <select class="select2 px-2" name="selAvailableUserRoles" id="selAvailableUserRoles"
                                        data-placeholder="Select a Role" data-dropdown-css-class="select2-purple"
                                        style="width: 100%; height: 350px;" multiple>

                                    </select>
                                    {{--
                                </div> --}}
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="button-section mt-5">
                                    <div>
                                        <input type="button" style="height:20px;font-size: 10px; text-align: center;"
                                            id="right" value=">"
                                            onclick='moveItems("#selAvailableUserRoles", "#selAssignedUsersRoles" );' />
                                    </div>
                                    <div>
                                        <input type="button" style="height:20px;font-size: 10px; text-align: center;"
                                            id="left" value="<"
                                            onclick='moveItems("#selAssignedUsersRoles", "#selAvailableUserRoles");' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 p-2">
                                <label>Assigned Roles</label>
                                <select multiple="multiple" id='selAssignedUsersRoles' name="selAssignedUsersRoles"
                                    style="width: 100%; height: 350px;">
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <input type="hidden" name="inserted_by" value="{{ Auth::user()->id }}"> -->
                    <div class="modal-footer">
                        <button id="btnUpdateUserRole" class="btn btn-sm btn-success assignRoleBtn">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            Assign
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" data-dismiss="modal">
                            <i class="fa fa-times mr-1" aria-hidden="true"></i>
                            Close
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Assign Role Modal -->

    <!-- The View User Details Modal -->
    <div class="modal" id="viewUserModel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <p class="modal-title text-bold" id="editUserModalLabel">
                        <i class="fas fa-eye mr-2"></i>
                        User Details
                    </p>
                    <a type="button" data-dismiss="modal"><i class="fas fa-times"></i></a>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row px-2">
                        <div class="col-md-12 colHead">PERSONAL INFORMATION
                        </div>
                        <div class="col-md-6 text-normal"><span style="font-weight:600">Name
                                : </span>
                            <label id="txtUserName">
                            </label>
                        </div>
                        <div class="col-md-6"><span style="font-weight:600">Contact No. :
                            </span><label id="txtPhoneNo">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">E-Mail
                                ID :
                            </span><label id="txtEmail">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">Gender
                                :
                            </span><label id="txtGender">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">Address 1 :
                            </span><label id="txtAddr1">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">Address 2 :
                            </span><label id="txtAddr2">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">District :
                            </span><label id="txtdistName">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">PIN :
                            </span><label id="txtPinNo">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">State
                                :
                            </span><label id="txtStateName">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">Country :
                            </span><label id="txtCountryName">
                            </label></div>
                    </div><br>
                    <div class="row px-2">
                        <div class="col-md-12 colHead">OFFICIAL INFORMATION
                        </div>
                        <div class="col-md-6"><span style="font-weight:600">Designation :
                            </span><label id="txtDesgName">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">Department :
                            </span><label id="txtDeptName">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">Office
                                :
                            </span><label id="txtOfficeName">
                            </label></div>
                        <div class="col-md-6"><span style="font-weight:600">Post :
                            </span><label id="txtPostName">
                            </label></div>
                    </div>
                    <div class="row px-2 mt-2">
                        <div class="col-md-12 colHead">ACTIVITY STATUS</div>
                        <div class="col-md-6"><span style="font-weight:600">Current Activity Status
                                : </span>
                            <label id="txtActivityStatus">
                            </label>
                        </div>

                    </div>

                    <div class="row px-2 mt-2">
                        <div class="col-md-12 colHead">CURRENT ROLE</div>
                        <div class="col-md-6"><span style="font-weight:600">Role :
                            </span>
                            <label id="txtRoles">
                            </label>
                        </div>

                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button class="btn btn-sm btn-outline-secondary" data-dismiss="modal">
                        <i class="fa fa-times mr-1" aria-hidden="true"></i>
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End View Users Details Modal -->

    <!-- User menu edit Modal Start-->
    <div class="modal fade" id="editUserMenuAccessModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="editUserModalLabel"><i class="fas fa-edit mr-2"></i>Edit User
                        Menu Access
                    </p>
                    <a type="button" data-dismiss="modal"><i class="fas fa-times"></i></a>
                </div>
                <div class="modal-body">
                    <div class="row text-xs">
                        <div class="col-md-5 p-2">
                            <label>User Email:</label>
                            <lable id="selectedUserEmail" name="selectedUserEmail"></lable>
                        </div>
                    </div>
                    <div class="row text-xs">
                        <div class="col-md-5 p-2">
                            <label>Available Menus</label>
                            <div class="menuItems">
                                <select class="w-full" multiple="multiple" id='availableMenu'
                                    style="width: 100% ;height:350px;">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="button-section mt-5">
                                <div>
                                    <input type="button" style="height:20px;font-size: 10px; text-align: center;" id="right"
                                        value=">" onclick='moveMenuItems("#availableMenu", "#userMenu" );' />
                                </div>
                                <div>
                                    <input type="button" style="height:20px;font-size: 10px; text-align: center;" id="left"
                                        value="<" onclick='moveMenuItems("#userMenu", "#availableMenu");' />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 p-2">
                            <label>Assigned Menus</label>
                            <select multiple="multiple" id='userMenu' style="width: 100% ;height:350px;">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-xs" id="saveUserMenuChanges">
                        <i class="fa fa-check mr-1"></i>Save
                        Changes</button>
                    <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">
                        <i class="fa fa-times mr-1" aria-hidden="true"></i>
                        Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- User menu edit Modal End-->

    <!-- Edit User Details Modal Start -->
    <div class="modal" id="editUserDetailsModal">
        <div class="modal-dialog modal-lg text-xs">
            <form class="update-user-form" id="update_user" method="POST">
                @csrf
                <input type="hidden" id="id" name="id" value="">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <p class="modal-title text-bold text-md" id="editUserModalLabel">
                            <i class="fas fa-edit mr-2"></i>
                            Update User Data
                        </p>
                        <a type="button" data-dismiss="modal"><i class="fas fa-times"></i></a>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row text-xs">
                            <div class="col-sm-12 col-md-4">
                                <label for="">Name <span class="star">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="" required>
                                <span class="text-danger" id="name_error"></span>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="">Contact No. <span class="star">*</span></label>
                                <input type="number" class="form-control modal_phoneno" id="phoneno" name="phoneno" value=""
                                    required>
                                <span class="text-danger" id="phoneno_error"></span>
                                <span class="text-danger phoneno_validation_error" style="display:none">Phone Number must
                                    be 10 digits</span>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="">Address 1 <span class="star">*</span></label>
                                <input type="text" class="form-control" id="address1" name="address1" value="" required>
                                <span class="text-danger" id="address1_error"></span>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="">Address 2 <span class="star">*</span></label>
                                <input type="text" class="form-control" id="address2" name="address2" value="" required>
                                <span class="text-danger" id="address2_error"></span>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="district">District <span class="star">*</span></label>

                                <select class="form-control" id="district" name="district">
                                </select>
                                <span class="text-danger" id="district_error"></span>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="">PIN <span class="star">*</span></label>
                                <input type="number" class="form-control modal_pin" id="pin" name="pin" value="" required>
                                <span class="text-danger" id="pin_error"></span>
                                <span class="text-danger pin_validation_error" style="display:none">PIN must be 6
                                    digits</span>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="">Gender <span class="star">*</span></label>
                                <select id="gender" class="form-control custom-select" name="gender" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="department">Department
                                    <span class="star">*</span></label>
                                <select id="department" class="form-control custom-select" name="department" required>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="designation">Designation
                                    <span class="star">*</span></label>
                                <select id="designation" class="form-control" name="designation" required>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="office_type_cd">Office
                                    Type<span class="star">*</span></label>
                                <select id="office_type_cd" class="form-control custom-select" name="office_type_cd"
                                    required>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4" id="division_section" style="display: none;">
                                <label for="division">Circle:</label>
                                <select id="division" class="custom-select form-control" name="division">
                                    <option value="" selected>Please Select</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4" id="subdivision_section" style="display: none;">
                                <label for="subDivision">Division:</label>
                                <select id="subDivision" class="custom-select form-control" name="subDivision">
                                    <option value="" selected>Please Select</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="office">Office <span class="star">*</span></label>
                                <select id="office" class="form-control custom-select" name="office" required>

                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="status">
                                    User Status
                                    <span class="star">*</span>
                                </label>
                                <select id="status" class="form-control custom-select" name="status" required>
                                    <option value="A">Active</option>
                                    <option value="D">Deactive</option>
                                </select>
                            </div>

                            <div class="col-sm-12 col-md-4" style="display:none;" id="reason_container">
                                <label for="reason">
                                    Reason
                                    <span class="star">*</span>
                                </label>
                                <select id="reason" class="form-control custom-select" name="reason">
                                    <option value="">Select Reason</option>
                                    @foreach ($reasons as $reason)
                                        <option value="{{ $reason->id }}">
                                            {{ $reason->reason }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-12 col-md-4" style="display:none;" id="assign_to_container">
                                <label for="assign_to">
                                    Date of Modification
                                    <span class="star">*</span>
                                </label>
                                <input type="date" name="assign_to" id="assign_to" class="form-control">
                            </div>
                            <div class="col-sm-12 col-md-4" style="display:none;" id="assign_from_container">
                                <label for="assign_from">
                                    Date of assignment
                                    <span class="star">*</span>
                                </label>
                                <input type="date" name="assign_from" id="assign_from" class="form-control">
                            </div>

                            <div class="col-12" style="display:none;" id="remarks_container">
                                <label for="remarks">
                                    Remarks
                                    <span class="star">*</span>
                                </label>
                                <input type="text" name="remarks" id="remarks" class="form-control"
                                    placeholder="Give a valid remarks">
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <a id="linkToAssignAdditionalOfficeDetails" href="#" class="btn btn-sm btn-primary">
                                Additional Office Details
                            </a>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success modalUpBtn"><i
                                class="fa fa-check mr-2"></i>Update</button>
                        <a class="btn btn-sm modalClose btn-danger" data-dismiss="modal"><i
                                class="fa fa-times mr-2"></i>CLOSE</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Edit User Details Modal -->
@endsection
@push('scripts')
    <script>
        $(function () {
            $("#user_details_table").DataTable();
        });
    </script>

    <script>
        function moveItems(origin, dest) {
            $(origin).find(':selected').appendTo(dest);
        }

        function moveMenuItems(origin, dest) {
            const selectedMenuIds = $(origin).find(':selected').map(function () {
                return Number($(this).val());
            }).get();

            selectedMenuIds.forEach(function (menuId) {
                if (dest === '#userMenu') {
                    assignedMenuIds.push(menuId);
                } else {
                    assignedMenuIds = assignedMenuIds.filter(function (assignedMenuId) {
                        return assignedMenuId !== menuId;
                    });
                }
            });

            renderMenuAccessLists();
        }

        let assignedMenuIds = [];

        $(document).ready(function () {
            const arrCurrenUserRoles = @json($arrCurrenUserRoles);
            const logged_in_user_data = @json($user);
            const dist_master_data = @json($districtDetails);
            const dept_master_data = @json($deptdetails);
            const roleDetails = @json($roledetails);
            const menu_details = @json($menudetails);
            const menuCategoryNames = {
                0: 'Asset Management',
                1: 'Project Management',
                2: 'Maintenance Management'
            };

            function appendGroupedMenus(selectId, menus) {
                const groupedMenus = {};

                menus.forEach(function (menu) {
                    const categoryCode = Number(menu.portal_catg_cd);
                    const categoryName = menuCategoryNames[categoryCode] || 'Other Menus';

                    if (!groupedMenus[categoryName]) {
                        groupedMenus[categoryName] = [];
                    }
                    groupedMenus[categoryName].push(menu);
                });

                Object.keys(groupedMenus).forEach(function (categoryName) {
                    const optgroup = $('<optgroup>').attr('label', categoryName);

                    groupedMenus[categoryName].forEach(function (menu) {
                        optgroup.append($('<option>', {
                            value: menu.id,
                            text: menu.menu_name
                        }));
                    });

                    $(selectId).append(optgroup);
                });
            }

            window.renderMenuAccessLists = function () {
                $('#availableMenu').empty();
                $('#userMenu').empty();

                appendGroupedMenus('#availableMenu', menu_details.filter(function (menu) {
                    return !assignedMenuIds.includes(Number(menu.id));
                }));
                appendGroupedMenus('#userMenu', menu_details.filter(function (menu) {
                    return assignedMenuIds.includes(Number(menu.id));
                }));
            };
            const desgDetails = @json($desgdetails);
            const officeTypeDetails = @json($officeTypeDetails);
            const officeDetails = @json($officedetails);
            const degignationOfficeTypeMappings = @json($degnOfficeTypeMappings);
            const circleDetails = @json($circles);
            const divisionDetails = @json($divisions);
            const subDivisionDetails = @json($subDivisions);

            const selectedFilterOffice = @json(request('filter_office'));
            const selectedFilterDesignation = @json(request('filter_designation'));

            function loadFilterOffices(selectedOfficeId = null) {
                const departmentId = $('#filter_department').val();
                const officeTypeCode = $('#filter_office_type').val();
                const officeSelect = $('#filter_office');
                officeSelect.empty().append(new Option('All Offices', ''));

                if (!departmentId || !officeTypeCode) {
                    officeSelect.prop('disabled', true).trigger('change.select2');
                    return;
                }

                officeDetails.forEach(function (office) {
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

            function loadFilterDesignations(selectedDesignationId = null) {
                const departmentId = $('#filter_department').val();
                const designationSelect = $('#filter_designation');
                designationSelect.empty().append(new Option('All Designations', ''));

                if (!departmentId) {
                    designationSelect.prop('disabled', true).trigger('change.select2');
                    return;
                }

                desgDetails.forEach(function (designation) {
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

            loadFilterOffices(selectedFilterOffice);
            loadFilterDesignations(selectedFilterDesignation);

            $('#filter_department, #filter_status, #filter_office_type, #filter_office, #filter_designation').select2({
                width: '100%'
            });

            $('#filter_office_type').on('change', function () {
                loadFilterOffices();
            });

            $('#filter_department').on('change', function () {
                loadFilterDesignations();
                loadFilterOffices();
            });

            $('#editUserDetailsModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var user_id = button.data('user-id');

                var user_name = button.data('user-name');
                var user_ph_no = button.data('user-phone-no');
                var user_addr1 = button.data('user-addr1');
                var user_addr2 = button.data('user-adrr2');
                var user_dist_cd = button.data('user-dist-cd');
                var user_dist_name = button.data('user-dist-name');
                var user_pin = button.data('user-pin');
                var user_gender = button.data('user-gender');
                var user_dept_cd = button.data('user-dept-cd');
                var user_dep_name = button.data('user-dept-name');
                var user_desg_cd = button.data('user-desg-cd');
                var user_desg_name = button.data('user-desg-name');
                var user_ofs_type_cd = button.data('user-office-type-cd');
                var user_ofs_type_descr = button.data('user-office-type-descr');
                var user_ofs_cd = button.data('user-office-cd');
                var user_ofs_name = button.data('user-office-name');
                var user_status = button.data('user-status');


                $('#district').empty();
                $('#department').empty();
                $('#designation').empty();
                $('#office_type_cd').empty();
                $('#office').empty();

                $('#linkToAssignAdditionalOfficeDetails').attr('href', '/asset-management/manage-user-additional-office/' +
                    user_id);
                $('#id').val(user_id);
                $('#name').val(user_name);
                $('#phoneno').val(user_ph_no);
                $('#address1').val(user_addr1);
                $('#address2').val(user_addr1);
                $('#pin').val(user_pin);
                $('#status').val(user_status);


                $.each(dist_master_data, function (index, option) {
                    $('#district').append(new Option(option.dist_name, option.dist_code));
                });
                $('#district').val(user_dist_cd);
                if (user_gender == 'Male' || user_gender == 'Female' || user_gender == 'Others')
                    $('#gender').val(user_gender);
                else
                    $('#gender').val("Male");

                if (arrCurrenUserRoles.includes(1))
                    $.each(dept_master_data, function (index, option) {
                        $('#department').append(new Option(option.department_name, option.id));
                    });
                else
                    $('#department').append(new Option(user_dep_name, user_dept_cd));

                $('#department').val(user_dept_cd);


                $.each(desgDetails, function (index, option) {
                    if (option.dept_cd == user_dept_cd)
                        $('#designation').append(new Option(option.desg_name, option.id));
                });
                $('#designation').val(user_desg_cd);

                $.each(officeDetails, function (index, option) {
                    if (option.department_id == user_dept_cd)
                        $('#office').append(new Option(option.office_name, option.id));
                });
                $('#office').val(user_ofs_cd);


                $.each(officeTypeDetails, function (index, option) {
                    $('#office_type_cd').append(new Option(option.office_type_desc, option
                        .office_type_cd));
                });
                $('#office_type_cd').val(user_ofs_type_cd);
            });


            $('#viewUserModel').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var user_name = button.data('user-name');
                var user_ph_no = button.data('user-phone-no');
                var user_email = button.data('user-email');
                var user_gender = button.data('uder-gender');
                var user_addr1 = button.data('user-address1');
                var user_addr2 = button.data('user-address2');
                var user_pin = button.data('user-pin');
                var user_dist = button.data('user-dist-name');
                var user_state = button.data('user-state-name');
                var user_country = button.data('user-country-name');
                var user_desg = button.data('user-desg-name');
                var user_dept = button.data('user-dept-name');
                var user_office = button.data('user-office-name');
                var user_post = button.data('user-post-name');
                var user_activity = button.data('user-activity-status');
                var user_roles = button.data('user-role-names');
                $('#txtUserName').text(user_name);
                $('#txtPhoneNo').text(user_ph_no);
                $('#txtEmail').text(user_email);
                $('#txtGender').text(user_gender);
                $('#txtAddr1').text(user_addr1);
                $('#txtAddr2').text(user_addr2);
                $('#txtPinNo').text(user_pin);
                $('#txtdistName').text(user_dist);
                $('#txtStateName').text(user_state);
                $('#txtCountryName').text(user_country);
                $('#txtDesgName').text(user_desg);
                $('#txtDeptName').text(user_dept);
                $('#txtOfficeName').text(user_office);
                $('#txtPostName').text(user_post);
                $('#txtActivityStatus').text(user_activity);
                $('#txtRoles').text(user_roles);
            });


            $('#assignUserRoleModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var userName = button.data('user-name');
                var roleIds = button.data('role-ids');
                var selectedUserId = button.data('user-id');
                $('#btnUpdateUserRole').attr('data-user-id', selectedUserId);
                $('#editUserRoleModalTitle').text("Assign role for : " + userName);

                var arrAssignedRoles = [];
                console.log("roleIds : ", roleIds);
                if (Array.isArray(roleIds)) {
                    arrAssignedRoles = roleIds.filter(function (roleId) {
                        return roleId !== null;
                    }).map(Number);
                } else if (roleIds) {
                    arrAssignedRoles = String(roleIds)
                        .replace(/^\{|\}$/g, '')
                        .split(',')
                        .filter(function (roleId) {
                            return roleId && roleId.toUpperCase() !== 'NULL';
                        })
                        .map(Number);
                }

                $('#selAvailableUserRoles').empty();
                $('#selAssignedUsersRoles').empty();
                $.each(roleDetails, function (index, value) {
                    if (value.roletype === 'U' || value.roletype === 'DA')
                        if (!arrAssignedRoles.includes(value.id))
                            $('#selAvailableUserRoles').append('<option value="' + value.id + '">' +
                                value.rolename + '</option>');

                    if (value.roletype === 'U' || value.roletype === 'DA')
                        if (arrAssignedRoles.includes(value.id))
                            $('#selAssignedUsersRoles').append('<option value="' + value.id + '">' +
                                value.rolename + '</option>');
                });

            });

            $(document).on('click', '#btnUpdateUserRole', function (e) {
                e.preventDefault();
                let selectedRoleIdArr = null;
                selectedRoleIdArr = $('#selAssignedUsersRoles option').map(function () {
                    return $(this).val();
                }).get();
                var user_id = $(this).attr("data-user-id");
                data = {
                    "user_id": user_id,
                    "role_id": selectedRoleIdArr
                }
                $.ajax({
                    url: "/asset-management/assignUserRole", // URL to send the request
                    type: "POST", // Request method (GET, POST, etc.)
                    data: data,
                    success: function (response) {
                        // Update the content of your view without reloading
                        if (response.status == true) {
                            const updatedRoleIds = '{' + selectedRoleIdArr.join(',') + '}';
                            const roleButton = $('[data-target="#assignUserRoleModal"][data-user-id="' + user_id + '"]');
                            roleButton.attr('data-role-ids', updatedRoleIds);
                            roleButton.data('role-ids', updatedRoleIds);

                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: "Role Assigned Updated Successfully ",
                                showConfirmButton: true,
                                timer: 5000
                            }).then(() => {

                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'error',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 5000
                            }).then(() => {

                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        // Handle the error (e.g., display an error message)
                    }
                });
            });

            $('#editUserMenuAccessModal').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget);
                var userId = button.data('user-id1');
                var userName = button.data('user-name1');
                var menuIds = button.data('menu-ids');
                var selectedUserEmail = button.data('user-email1');
                console.log("menuIds");
                console.log(menuIds);
                $('#saveUserMenuChanges').attr('data-user-id', userId);
                $('#editUserModalLabel').text("Assign Menu Access for : " + userName);
                $('#selectedUserEmail').text(selectedUserEmail);
                var arrAssignedMenus = [];

                if (Array.isArray(menuIds)) {
                    arrAssignedMenus = menuIds.filter(function (menuId) {
                        return menuId !== null;
                    }).map(Number);
                } else if (menuIds !== null && menuIds !== undefined && menuIds !== '') {
                    arrAssignedMenus = String(menuIds)
                        .replace(/^\{|\}$/g, '')
                        .split(',')
                        .filter(function (menuId) {
                            return menuId && menuId.toUpperCase() !== 'NULL';
                        })
                        .map(Number);
                }

                assignedMenuIds = arrAssignedMenus;
                renderMenuAccessLists();


            });

            $('#saveUserMenuChanges').click(function (e) {
                e.preventDefault();
                var userId = $(this).attr("data-user-id");
                var allOptions = null;
                allOptions = $('#userMenu option').map(function () {
                    return $(this).val();
                }).get();
                $.ajax({
                    type: "POST",
                    url: "/asset-management/edit-user-menu-access",
                    data: {
                        userId: userId,
                        selectedOptions: allOptions
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            const updatedMenuIds = '{' + allOptions.join(',') + '}';
                            const menuButton = $('[data-target="#editUserMenuAccessModal"][data-user-id1="' + userId + '"]');
                            menuButton.attr('data-menu-ids', updatedMenuIds);
                            menuButton.data('menu-ids', updatedMenuIds);

                            // editUserMenuAccessModal 
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: 'Menu Updated Successfully',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                // window.location.replace(location)
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                // window.location.replace(location)
                            });
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            // $('.edit-user-menu-access-button').on('click', function() {
            //     var userId = $(this).data('user-id');
            //     let location = "{{ route('manageUser') }}";
            //     $.ajax({
            //         type: "GET",
            //         url: "user-menu-access",
            //         data: {
            //             id: userId
            //         },
            //         success: function(response) {
            //             console.log(response);

            //             response.menuDetails.forEach((menu) => {
            //                 var option = $('<option>', {
            //                     value: menu.id,
            //                     text: menu.menu_name
            //                 });
            //                 $('#availableMenu').append(option);
            //             })

            //             response.userMenuDetails.forEach((userMenu) => {
            //                 var option = $('<option>', {
            //                     value: userMenu.menuid,
            //                     text: userMenu.menu_name
            //                 });
            //                 $('#userMenu').append(option);
            //             })
            //             $('#editUserMenuAccessModal').modal('show');
            //         },
            //         error: function(error) {
            //             // Handle any errors here
            //         }
            //     });
            //     $('#editUserMenuAccessModal').modal('show');


            // });
            $('#btnRight').click(function (e) {
                var selectedOpts = $('#availableMenu option:selected');
                if (selectedOpts.length == 0) {
                    alert("Nothing to move.");
                    e.preventDefault();
                }

                $('#userMenu').append($(selectedOpts).clone());
                $(selectedOpts).remove();
                e.preventDefault();
            });

            $('#btnLeft').click(function (e) {
                var selectedOpts = $('#userMenu option:selected');
                if (selectedOpts.length == 0) {
                    alert("Nothing to move.");
                    e.preventDefault();
                }

                $('#availableMenu').append($(selectedOpts).clone());
                $(selectedOpts).remove();
                e.preventDefault();
            });

            // $('#closeMenu').click(() => {
            //     location.reload();
            // })

            $('form.active-user-form').on("submit", function (e) {
                e.preventDefault();

                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.message == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: 'Status Updated Successfully',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                // window.location.replace(location)
                            });
                        } else if (response.message == 'validationFails') {
                            console.log('validation fails');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                // window.location.replace(location)
                            });
                        }
                    }
                });
            });

            $('form.update-user-form').on("submit", function (e) {
                e.preventDefault();
                let location = "{{ route('manageUser') }}";
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: "{{ route('updateUser') }}",
                    // url: form.attr('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // data: $('#update_user').serialize(),
                    data: formData,
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response.response == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                // window.location.replace(location)
                            });
                        }
                        if (response.response === 'failed') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                // window.location.replace(location)
                            });
                        }
                    }
                });
            });

            // edit user

            $('[name = "department"]').on('change', function () {
                const dataID = $(this).data('id');
                let departmentId = $(this).val();
                departmentId = parseInt(departmentId);
                console.log(typeof (departmentId));
                $('#assign_from_container').show();
                $('#remarks_container').show();
                $('#reason_container').show();
                $('#assign_from').prop('required', true);
                $('#reason').prop('required', true);
                $('#remarks').prop('required', true);

                $('#designation').empty().append('<option value="null">Select One</option>');
                $('#office_type_cd').empty().append('<option value="null">Select One</option>');
                $('#office').empty().append('<option value="null">Select One</option>');

                $.each(desgDetails, function (index, value) {
                    if (value.dept_cd === departmentId)
                        $('#designation').append('<option value="' + value.id + '">' +
                            value.desg_name + '</option>');
                });
            })

            $('[name = "designation"]').on('change', function () {
                const dataID = $(this).data('id');
                let selectedDegn = $(this).val();
                selectedDegn = parseInt(selectedDegn);

                $('#assign_from_container').show();
                $('#remarks_container').show();
                $('#reason_container').show();
                $('#assign_from').prop('required', true);
                $('#reason').prop('required', true);
                $('#remarks').prop('required', true);

                $('#office_type_cd').empty().append('<option value="null">Select One</option>');
                $('#office').empty().append('<option value="null">Select One</option>');

                $.each(degignationOfficeTypeMappings, function (index, value) {
                    if (value.desg_cd == selectedDegn)
                        $('#office_type_cd').append('<option value="' + value
                            .office_type_cd +
                            '">' + value.office_type_desc + '</option>');
                });
            })

            $('[name = "office_type_cd"]').on('change', function () {
                $('#division_section').hide();
                $('#subdivision_section').hide();
                $('#assign_from_container').show();
                $('#remarks_container').show();
                $('#reason_container').show();
                let selectedOfficeType = $(this).val();
                let selectedDepartment = $("#department").val();
                let officeList = $('#office');
                selectedDepartment = parseInt(selectedDepartment);

                if (selectedDepartment) {
                    if (selectedOfficeType) {
                        if ((selectedOfficeType != 'DO') && (selectedOfficeType != 'SDO')) {
                            $.ajax({
                                url: '/asset-management/getOfficeList',
                                method: 'GET',
                                data: {
                                    office_type: selectedOfficeType,
                                    department_type: selectedDepartment
                                },
                                success: function (data) {
                                    if (data.status === 404) {
                                        officeList.empty();
                                        officeList.append($('<option>').text('Please Select'));
                                        officeList.prop('disabled', true);
                                    } else {
                                        officeList.empty();
                                        $.each(data.offices, function (key, value) {
                                            officeList.append($('<option>').text(value
                                                .office_name)
                                                .attr('value', value.id));
                                        });
                                        officeList.prop('disabled', false);
                                    }
                                }
                            });
                        }
                        if (selectedOfficeType == 'DO') {
                            $('#division_section').show();
                            $('#division').empty();
                            $('#division').append('<option value="">Choose One</option>');
                            $.each(circleDetails, function (index, value) {
                                if (value.dept_cd == selectedDepartment) {
                                    $('#division').append('<option value="' + value.circle_cd +
                                        '">' +
                                        value
                                            .circle_name +
                                        '</option>');
                                }
                            });
                            $('#division').change(() => {
                                const selectedDivision = $('#update_user').find('#division').val();
                                $.ajax({
                                    url: '/asset-management/getOfficeList',
                                    method: 'GET',
                                    data: {
                                        office_type: selectedOfficeType,
                                        department_type: selectedDepartment
                                    },
                                    success: function (data) {
                                        if (data.status === 404) {
                                            alert(data.message);
                                            officeList.empty();
                                            officeList.append($('<option>').text(
                                                'Please Select'));
                                            officeList.prop('disabled', true);
                                        } else {
                                            officeList.empty();
                                            $.each(data.offices, function (key, value) {
                                                if (value.circle_cd ===
                                                    selectedDivision) {
                                                    officeList.append($(
                                                        '<option>')
                                                        .text(value
                                                            .office_name)
                                                        .attr('value', value
                                                            .id));
                                                }
                                            });
                                            officeList.prop('disabled', false);
                                        }
                                    }
                                });
                            })
                        }
                        if (selectedOfficeType == 'SDO') {
                            $('#subdivision_section').show();
                            $('#subDivision').empty();
                            $('#subDivision').append('<option value="">Choose One</option>');
                            $.each(divisionDetails, function (index, value) {
                                if (value.dept_cd == selectedDepartment) {
                                    $('#subDivision').append('<option value="' + value.division_cd +
                                        '">' +
                                        value
                                            .division_name +
                                        '</option>');
                                }
                            });
                            $('#subDivision').change(() => {
                                const selectedSubDivision = $('#update_user').find('#subDivision')
                                    .val();
                                $.ajax({
                                    url: '/asset-management/getOfficeList',
                                    method: 'GET',
                                    data: {
                                        office_type: selectedOfficeType,
                                        department_type: selectedDepartment
                                    },
                                    success: function (data) {
                                        if (data.status === 404) {
                                            alert(data.message);
                                            officeList.empty();
                                            officeList.append($('<option>').text(
                                                'Please Select'));
                                            officeList.prop('disabled', true);
                                        } else {
                                            officeList.empty();

                                            $.each(data.offices, function (key, value) {
                                                if (value.division_cd ===
                                                    selectedSubDivision) {
                                                    officeList.append($(
                                                        '<option>')
                                                        .text(value
                                                            .office_name)
                                                        .attr('value', value
                                                            .id));
                                                }
                                            });
                                            officeList.prop('disabled', false);
                                        }
                                    }
                                });
                            })
                        }

                    } else {
                        officeList.prop('disabled', true);
                    }
                } else {
                    alert('No department selected!!');
                }
            })

            $('[name="status"]').on('change', function () {
                console.log('change status');
                const statusVal = $(this).val();
                const statusID = $(this).data('id');
                $('#assign_from_container' + statusID).hide();
                $('#assign_from_container' + statusID).hide();
                $('#reason_container' + statusID).hide();
                $('#remarks_container' + statusID).hide();
                $('#assign_from_' + statusID).prop('required', false);
                $('#assign_to' + statusID).prop('required', false);
                $('#reason' + statusID).prop('required', false);
                $('#remarks' + statusID).prop('required', false);
                if (statusVal === 'A') {
                    $('#assign_from_container' + statusID).show();
                    $('#assign_from_' + statusID).prop('required', true);
                }
                if (statusVal === 'D') {
                    $('#assign_from_container' + statusID).show();
                    $('#assign_to' + statusID).prop('required', true);
                    $('#reason_container' + statusID).show();
                    $('#reason' + statusID).prop('required', true);
                    $('#remarks_container' + statusID).show();
                    $('#remarks' + statusID).prop('required', true);
                }
            })

            $('[name="reason"]').on('change', function () {
                const reason = $(this).find('option:selected').text().trim();
                const dataID = $(this).data('id');
                if (reason != 'Others') {
                    $('#remarks').val(reason);
                }
            })
        });
    </script>
    <script>
        $(document).ready(function () {

        });
    </script>
    <script>
        $(document).ready(function () {
            // Set up the CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Add an event listener to the "Add" button
            $("#add").click(function () {
                // Select all checked checkboxes in the left side (available menus)
                var selectedCheckboxes = $("#menuItems input[type='checkbox']:checked");

                // Iterate over selected checkboxes and move their corresponding menu items
                selectedCheckboxes.each(function () {
                    var menuItemText = $(this).siblings("label").text();

                    // Create a new container for the assigned menu item
                    var container = $("<div>").addClass("assigned-menu-item");

                    // Create a checkbox for the assigned menu item
                    var checkbox = $("<input>")
                        .attr("type", "checkbox")
                        .prop("checked", true); // Ensure the checkbox is checked on the right side

                    // Create a label for the assigned menu item
                    var label = $("<label>").text(menuItemText);

                    // Append the checkbox and label to the container
                    container.append(checkbox, label);

                    // Append the container to the DOM under the form
                    $("#assigned-menus").append(container);

                    // Uncheck the checkbox on the left side
                    $(this).prop("checked", false);
                });
            });

            // Rest of your code for handling individual checkbox changes...
        });
    </script>


    <script type="text/javascript">
        $('.user_list').DataTable();
        //reset
        $('#reset').click(function () {
            $('#add_user')[0].reset();
        });
    </script>

    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": false,
                "lengthChange": true,
                "autoWidth": false,
                // "buttons": ["copy", "csv", "excel", "pdf"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(1)');

        });
    </script>

    <script>
        $('.modal_phoneno').on("blur", function (e) {
            e.preventDefault();
            var ph = $(this).val();
            if (ph.length < 10 || ph.length > 10) {
                console.log('not correct');
                $('.phoneno_validation_error').show();
            } else {
                console.log('correct');
                $('.phoneno_validation_error').hide();
            }
        });

        $('.modal_pin').on("blur", function (e) {
            e.preventDefault();
            var pin = $(this).val();
            if (pin.length < 6 || pin.length > 6) {
                console.log('not correct');
                $('.pin_validation_error').show();
            } else {
                console.log('correct');
                $('.pin_validation_error').hide();
            }
        });

        // $('.modalClose').click(function() {
        //     location.reload();
        // });
    </script>
@endpush
