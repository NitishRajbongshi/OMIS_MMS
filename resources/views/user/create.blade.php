@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid text-sm">
            <div class="row px-2">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('manageUser') }}">Manage Users</a>
                    </li>
                    <li class="breadcrumb-item">Add User</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid mainBody">
            @if (session('failed'))
                <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    <strong>Failed!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('createUser') }}" method="post" id="user_form" autocomplete="off">
                @csrf
                <legend class="w-auto px-2 mt-2" style="font-size:14px">User Information</legend>
                <fieldset class="border p-3 fl">

                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="name">Full Name <span class="star">*</span></label>
                            <input type="text" id="name" class="form-control form-control-sm" name="name"
                                placeholder="Full Name" value="{{ old('name') }}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('name')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="email">Email ID <span class="star">*</span></label>
                            <input type="text" id="email" class="form-control form-control-sm" name="email"
                                placeholder="Email ID" value="{{ old('email') }}"
                                oninput="this.value = this.value.toLowerCase()">
                            @error('email')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="phoneno">Phone No. <span class="star">*</span></label>
                            <input type="tel" id="phoneno" class="form-control form-control-sm" name="phoneno"
                                maxlength="10" minlength="10" pattern="[0-9]{10}" value="{{ old('phoneno') }}"
                                placeholder="Phone No.">
                            @error('phoneno')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border p-3 fl">
                    <legend class="w-auto px-2 text-sm">Personal Information</legend>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="address1">Address line 1 <span class="star">*</span></label>
                            <input type="text" id="address1" class="form-control form-control-sm" name="address1"
                                value="{{ old('address1') }}" placeholder="Address line 1">
                            @error('address1')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="address2">Address line 2 <span class="star">*</span></label>
                            <input type="text" id="address2" class="form-control form-control-sm" name="address2"
                                value="{{ old('address2') }}" placeholder="Address line 2">
                            @error('address2')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="country">Country <span class="star">*</span></label>
                            <select class="form-control form-control-sm" name="country" id="country">
                                <option value="India" selected>India</option>
                            </select>
                            @error('country')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="state">State <span class="star">*</span></label>
                            <select class="form-control form-control-sm" id="state" name="state">
                                {{-- <option value="">Choose one</option> --}}
                                @foreach ($states as $state)
                                    <option value="{{ $state->state_code }}" class="text-uppercase">
                                        {{ $state->state_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="district">District <span class="star">*</span></label>
                            <select class="form-control form-control-sm" id="district" name="district">
                                <option value="">Choose one</option>
                                @foreach ($districtDetails as $item)
                                    <option value="{{ $item->dist_code }}">
                                        {{ $item->dist_name }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- <label for="district">District <span class="star">*</span></label> --}}
                            {{-- <input type="text" id="district" class="form-control form-control-sm" name="district"> --}}
                            {{-- <span class="text-danger" id="district_error"></span> --}}

                            @error('district')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="pin">PIN <span class="star">*</span></label>
                            <input type="tel" id="pin" class="form-control form-control-sm" name="pin"
                                maxlength="6" minlength="6" pattern="[0-9]{6}" placeholder="6 Digits PIN"
                                value="{{ old('pin') }}">
                            {{-- <span class="text-danger" id="pin_error"></span> --}}

                            @error('pin')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="gender">Gender <span class="star">*</span></label>
                            <select id="gender" class="form-control form-control-sm" name="gender">
                                <option value="" disable selected hidden>Please Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Others">Others</option>
                            </select>
                            @error('gender')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </fieldset>


                <fieldset class="border p-3 mt-3 fl">
                    <legend class="w-auto px-2 text-sm">Office Information</legend>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="department">Department <span class="star">*</span></label>
                            <select id="department" class="form-control form-control-sm" name="department">
                                <option value="" disable selected hidden>Please Select</option>
                                @if ($user->user_role_id === 1)
                                    @foreach ($deptdetails as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->department_name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="{{ $user->department }}">
                                        {{ $userdetails->department_name }}
                                    </option>
                                @endif
                            </select>
                            @error('department')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="designation">Designation <span class="star">*</span></label>
                            <select id="designation" class="form-control form-control-sm" name="designation">
                                <option value="" disable selected hidden>Please Select</option>
                            </select>
                            @error('designation')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="office_type">Office Type <span class="star">*</span></label>
                            <select id="office_type" class="form-control form-control-sm" name="office_type">
                                <option value="" selected>Please Select</option>
                                @foreach ($officeTypes as $item)
                                    <option value="{{ $item->office_type_cd }}">{{ $item->office_type_desc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('office_type')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3" id="division_section" style="display: none;">
                            <label for="division">Circle:</label>
                            <select id="division" class="form-control form-control-sm" name="division">
                                <option value="" selected>Please Select</option>
                                {{-- @foreach ($divisions as $item)
                                    <option value="{{ $item->division_cd }}">{{ $item->division_name }}
                                    </option>
                                    @endforeach --}}
                            </select>
                        </div>
                        <div class="col-md-3" id="subdivision_section" style="display: none;">
                            <label for="subDivision">Division:</label>
                            <select id="subDivision" class="form-control form-control-sm" name="subDivision">
                                <option value="" selected>Please Select</option>
                                {{-- @foreach ($subDivisions as $item)
                                    <option value="{{ $item->sub_div_cd }}">{{ $item->sub_div_name }}
                                    </option>
                                    @endforeach --}}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="office_list">Office<span class="star">*</span></label>
                            <select id="office_list" class="form-control form-control-sm" name="office_list" disabled>
                                <option value="" disable selected hidden>Please Select</option>
                            </select>

                            @error('office_list')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="assign_date">Date of Current Assignment<span class="star">*</span></label>
                            <input type="date" id="assign_date" class="form-control form-control-sm"
                                name="assign_date">
                        </div>

                        {{-- <div class="col-md-3">
                                <label for="qtr_no">Quarter No. </label>
                                <select id="qtr_no" class="form-control form-control-sm" name="qtr_no">
                                    <option value="" disable selected hidden>Please Select</option>
                                    @foreach ($quarterDetails as $item)
                                    <option value="{{ $item->qtr_no }}">{{ $item->qtr_no }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('qtr_no')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div> --}}

                        <div class="col-md-3">
                            <label for="data_entry">Data Entry Permission: <span class="star">*</span></label> <br>
                            <input type="radio" id="yes" name="data_entry" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="data_entry" value="N" checked>
                            <label for="no">No</label>

                            @error('data_entry')
                                <div class="text-danger text-xs">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!-- <div class="pt-3" id="CE_office_details" style="display: none;">
                                                                                                                                                                            <legend class="w-auto px-2 text-sm">Other Office Information</legend>
                                                                                                                                                                            <div class="row form-1-box">
                                                                                                                                                                                <div class="col-md-3">
                                                                                                                                                                                    <label for="other_office">Other Office: <span class="star">*</span></label>
                                                                                                                                                                                    <select id="other_office" class="form-control form-control-sm" name="other_office">
                                                                                                                                                                                        <option value="" selected>Please Select</option>
                                                                                                                                                                                    </select>
                                                                                                                                                                                    <span class="text-danger text-xs" id="other_office_error">This field is
                                                                                                                                                                                        required</span>
                                                                                                                                                                                </div>
                                                                                                                                                                            </div>
                                                                                                                                                                        </div> -->
                    {{-- <div class="form-1-box mt-3">
                            <label for="has_additional_office">User have additional office details: <span
                                    class="star">*</span></label>
                            <input type="radio" id="yes" name="has_additional_office" value="Y">
                            <label for="yes">Yes</label>
                            <input type="radio" id="no" name="has_additional_office" value="N" checked>
                            <label for="no">No</label>

                            @error('has_additional_office')
                            <div class="text-danger text-xs">
                                {{ $message }}
                            </div>
                            @enderror
                        </div> --}}
                </fieldset>

                {{-- <fieldset class="border p-3 mt-3 fl todos_labels" id="additional_office_info"
                        style="display: none;">
                        <legend class="w-auto px-2 text-sm">Additional Office Information</legend>
                        <div class="row form-1-box additionalOfficeInfoSection">
                            <div class="col-md-3">
                                <label for="additional_department">Department <span class="star">*</span></label>
                                <select id="additional_department" class="form-control form-control-sm"
                                    name="additional_department">
                                    <option value="" disable selected hidden>Please Select</option>
                                    @if ($user->user_role_id === 1)
                                    @foreach ($deptdetails as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->department_name }}
                                    </option>
                                    @endforeach
                                    @else
                                    <option value="{{ $user->department }}">
                                        {{ $userdetails->department_name }}</option>
                                    @endif
                                </select>
                                @error('additional_department')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="additional_designation">Designation <span class="star">*</span></label>
                                <select id="additional_designation" class="form-control form-control-sm"
                                    name="additional_designation">
                                    <option value="" disable selected hidden>Please Select</option>
                                </select>
                                @error('additional_designation')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="additional_office_type">Office Type <span class="star">*</span></label>
                                <select id="additional_office_type" class="form-control form-control-sm"
                                    name="additional_office_type">
                                    <option value="" disable selected hidden>Please Select</option>
                                    @foreach ($officeTypes as $item)
                                    <option value="{{ $item->office_type_cd }}">{{ $item->office_type_desc }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('additional_office_type')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="additional_office_list">Office<span class="star">*</span></label>
                                <select id="additional_office_list" class="form-control form-control-sm"
                                    name="additional_office_list" disabled>
                                    <option value="" disable selected hidden>Please Select</option>
                                </select>

                                @error('additional_office_list')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="additional_assign_date">Date of Current Assignment<span
                                        class="star">*</span></label>
                                <input type="date" id="additional_assign_date" class="form-control form-control-sm"
                                    name="additional_assign_date">
                            </div>

                            <div class="col-md-3">
                                <label for="add_data_entry">Data Entry Permission: <span class="star">*</span></label>
                                <br>
                                <input type="radio" id="yes" name="add_data_entry" value="Y">
                                <label for="yes">Yes</label>
                                <input type="radio" id="no" name="add_data_entry" value="N" checked>
                                <label for="no">No</label>
                            </div>
                        </div>

                        <div class="repeatable"></div>
                        <div class="form-group" style="text-align:end;">
                            <input type="button" value="Add More Office" class="btn bg-primary text-light btn-sm add">
                        </div>

                        <script type="text/template" id="todos_labels">
                                        <div class="row form-1-box additionalOfficeInfoSection border-top border-dark mt-3 pt-3" data-id="{?}">
                                            <div class="col-md-3">
                                                <label for="additional_department_{?}">Department <span class="star">*</span></label>
                                                <select id="additional_department_{?}" class="form-control form-control-sm"
                                                    name="todos_labels[{?}][additional_department]" data-id="{?}" required>
                                                    <option value="" disable selected hidden>Please Select</option>
                                                    @if ($user->user_role_id === 1)
                                                        @foreach ($deptdetails as $dept)
                                                            <option value="{{ $dept->id }}">{{ $dept->department_name }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option value="{{ $user->department }}">
                                                            {{ $userdetails->department_name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="additional_designation_{?}">Designation <span class="star">*</span></label>
                                                <select id="additional_designation_{?}" class="form-control form-control-sm"
                                                    name="todos_labels[{?}][additional_designation]" data-id="{?}" required>
                                                    <option value="" disable selected hidden>Please Select</option>
                                                    @foreach ($desgdetails as $desg)
                                                        <option value="{{ $desg->id }}">{{ $desg->desg_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="additional_office_type_{?}">Office Type <span class="star">*</span></label>
                                                <select id="additional_office_type_{?}" class="form-control form-control-sm"
                                                    name="todos_labels[{?}][additional_office_type]" data-id="{?}" required>
                                                    <option value="" disable selected hidden>Please Select</option>
                                                    @foreach ($officeTypes as $item)
                                                        <option value="{{ $item->office_type_cd }}">{{ $item->office_type_desc }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="additional_office_list_{?}">Office<span class="star">*</span></label>
                                                <select id="additional_office_list_{?}" class="form-control form-control-sm"
                                                    name="todos_labels[{?}][additional_office_list]" data-id="{?}" required>
                                                    <option value="" disable selected hidden>Please Select</option>
                                                    @foreach ($officedetails as $item)
                                                        <option value="{{ $item->id }}">{{ $item->office_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="additional_assign_date_{?}">Date of Current Assignment<span
                                                        class="star">*</span></label>
                                                <input type="date" id="additional_assign_date_{?}" class="form-control form-control-sm"
                                                    name="todos_labels[{?}][additional_assign_date]" data-id="{?}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Data Entry Permission<span class="star">*</span></label><br>
                                                <label for="radio_yes_{?}">Yes</label>
                                                <input type="radio" id="radio_yes_{?}" name="todos_labels[{?}][additional_data_entry]" value="Y" required>
                                                <label for="radio_no_{?}">No</label>
                                                <input type="radio" id="radio_no_{?}" name="todos_labels[{?}][additional_data_entry]" value="N" checked>
                                            </div>
                                            <div class="col-md-3">
                                                <label for=""></label><br>
                                                  <input type="button" class="delete" value="Remove" />
                                            </div>
                                        </div>
                                        </script>
                    </fieldset> --}}

                <fieldset class="border p-3 mt-3">
                    <legend class="w-auto px-2 text-sm">User Vehicle Information</legend>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="vehicle_regn_no">Registration Number:</label>
                            <input type="text" id="vehicle_regn_no" class="form-control form-control-sm"
                                name="vehicle_regn_no" placeholder="Vehicle Registration No."
                                value="{{ old('vehicle_regn_no') }}">
                            @error('vehicle_regn_no')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="chassis_no">Chassis Number: </label>
                            <input type="text" id="chassis_no" class="form-control form-control-sm" name="chassis_no"
                                placeholder="Vehicle Chassis No." value="{{ old('chassis_no') }}">
                            @error('chassis_no')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="engine_no">Engine Number: </label>
                            <input type="text" id="engine_no" class="form-control form-control-sm" name="engine_no"
                                placeholder="Vehicle Engine No." value="{{ old('engine_no') }}">
                            @error('engine_no')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="vehicle_type">Vehicle Type </label>
                            <select class="form-control form-control-sm" id="vehicle_type" name="vehicle_type">
                                <option value="">Choose one</option>
                                @foreach ($vehTypes as $vehType)
                                    <option value="{{ $vehType->veh_type_cd }}">
                                        {{ $vehType->veh_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_type')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="model">Vehicle model</label>
                            <input type="text" id="model" class="form-control form-control-sm" name="model"
                                placeholder="Vehicle Model" value="{{ old('model') }}">
                            @error('model')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="maker">Vehicle Maker</label>
                            <select class="form-control form-control-sm" id="maker" name="maker">
                                <option value="">Choose one</option>
                                @foreach ($vehMakers as $vehMaker)
                                    <option value="{{ $vehMaker->maker_cd }}">
                                        {{ $vehMaker->maker_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('maker')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="fuel_type">Fuel Type </label>
                            <select class="form-control form-control-sm" id="fuel_type" name="fuel_type">
                                <option value="">Choose one</option>
                                @foreach ($fuelTypes as $fuelType)
                                    <option value="{{ $fuelType->fuel_type_cd }}">
                                        {{ $fuelType->fuel_type_descr }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fuel_type')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border p-3 mt-3 fl">
                    <legend class="w-auto px-2 text-sm">Educational Information</legend>
                    <div class="row form-1-box">
                        <div class="col-md-3">
                            <label for="qualification">Highest Qualification <span class="star">*</span></label>
                            <select id="qualification" class="form-control form-control-sm" name="qualification">
                                <option value="">Please Select</option>
                                @foreach ($qualificationDetails as $qualificationDetail)
                                    <option value="{{ $qualificationDetail->qualificationid }}">
                                        {{ $qualificationDetail->details }} ({{ $qualificationDetail->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('qualification')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </fieldset>

                {{-- <fieldset class="border p-3 mt-3">
                        <legend class="w-auto px-2 text-sm">Menu access</legend>
                        <div class="row form-1-box px-3">
                            <div class="col-md-12 form-check">
                                <label class="form-check-label" for="selectAll">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                    Select All
                                </label>
                            </div>
                        </div>
                        <div class="row form-1-box px-3" id="div_menu_user_roles">
                            @foreach ($menuDetails as $menuDetail)
                            <div class="col-md-3 form-check">
                                <label class="form-check-label" for="{{ $menuDetail->menu_name }}">
                                    <input type="checkbox" class="form-check-input" id="{{ $menuDetail->menu_name }}"
                                        name="{{ $menuDetail->menu_name }}" value="{{ $menuDetail->id }}">
                                    {{ $menuDetail->menu_name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </fieldset> --}}
                <br>
                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2">
                        <i class="fa fa-save mr-2"></i>Submit
                    </button>
                    <a href="" class="btn btn-sm btn-danger rounded-0 mt-2 ml-3" id="reset">
                        <i class="fa fa-sync-alt mr-2"></i>Reset</a>
                </div><br>
            </form>
        </div>
    </section>
@endsection
@push('styles')
    <link rel="stylesheet" href="css\common\selectOptionStyleSheet.css">
@endpush
@push('scripts')
    <script src="{{ asset('js/jquery.repeatable.js') }}" defer></script>
    <script>
        $(function() {
            $(".todos_labels .repeatable").repeatable({
                addTrigger: ".todos_labels .add",
                deleteTrigger: ".todos_labels .delete",
                template: "#todos_labels",
                startWith: 1,
                max: 5
            });
        });
    </script>
    <script>
        const designationDetails = @json($desgdetails);
        const degignationOfficeTypeMappings = @json($degnOfficeTypeMappings);
        const officeDetails = @json($officedetails);
        const menuDetails = @json($menuDetails);
        const circleDetails = @json($circles);
        const divisionDetails = @json($divisions);
        const subDivisionDetails = @json($subDivisions);
        $(document).ready(function() {
            $("#department").on("change", function() {
                $("#office_type option").prop("selected", function() {
                    return this.defaultSelected;
                });

                $("#designation").empty().append('<option value="">Please Select</option>');
                // $("#office_type").empty().append('<option value="">Please Select</option>');
                $("#office_list").empty().append('<option value="">Please Select</option>');
                const selectedDept = $("#department").val();
                if (selectedDept) {
                    $.each(designationDetails, function(index, value) {
                        if (value.dept_cd == selectedDept)
                            $('#designation').append('<option value="' + value.id + '">' + value
                                .desg_name +
                                '</option>');
                    });

                    $("#div_menu_user_roles").empty();
                    var container = $('#div_menu_user_roles');
                    if (selectedDept == 16 || selectedDept == 18) {
                        var menuContent = "<div class='col-md-3 form-check'>" +
                            "<label class='form-check-label'>" +
                            "<input type='checkbox' class='form-check-input' id='14'" +
                            "name='14' value='14'>" +
                            "View MIS and Dashboard" +
                            "</label>" +
                            "</div>";
                        $('#div_menu_user_roles').append(menuContent);
                    } else {
                        $.each(menuDetails, function(index, value) {
                            if (value.dept_cd == selectedDept || value.dept_cd == null) {

                                var menuContent = "<div class='col-md-3 form-check'>" +
                                    "<label class='form-check-label'>" +
                                    "<input type='checkbox' class='form-check-input' id=" + value
                                    .menu_name +
                                    "name=" + value.menu_name + "value=" + value.id + ">" +
                                    value.menu_name +
                                    "</label>" +
                                    "</div>";
                                $('#div_menu_user_roles').append(menuContent);
                            }
                        });
                    }

                } else {
                    $("#div_menu_user_roles").empty();
                    var container = $('#div_menu_user_roles');
                    $.each(menuDetails, function(index, value) {

                        var menuContent = "<div class='col-md-3 form-check'>" +
                            "<label class='form-check-label'>" +
                            "<input type='checkbox' class='form-check-input' id=" + value
                            .menu_name +
                            "name=" + value.menu_name + "value=" + value.id + ">" +
                            value.menu_name +
                            "</label>" +
                            "</div>";
                        $('#div_menu_user_roles').append(menuContent);
                    });
                }
            })

            $("#designation").on("change", function() {
                $('#CE_office_details').hide();
                // $('#other_office').prop('required', false);
                // $("#office_type").empty().append('<option value="">Please Select</option>');
                // $("#office_list").empty().append('<option value="">Please Select</option>');
                // console.log('changed');
                const selectedDegn = $("#designation").val();
                // if (selectedDegn) {
                //     $.each(degignationOfficeTypeMappings, function(index, value) {
                //         if (value.desg_cd == selectedDegn)
                //             $('#office_type').append('<option value="' + value.office_type_cd +
                //                 '">' + value
                //                 .office_type_desc +
                //                 '</option>');
                //     });
                // }

                // if (selectedDegn === '3') {
                //     $('#CE_office_details').show();
                //     $('#other_office').prop('required', true);
                //     $.each(officeDetails, function(index, value) {
                //         if ((value.department_id == '14') && (value.office_type_cd == 'HQ'))
                //             $('#other_office').append('<option value="' + value.id +
                //                 '">' + value.office_name +
                //                 '</option>');
                //     });
                // }
            })

            $('#state, #district, #gender, #department, #designation, #post,  #office_list, #qualification, #qtr_no, #vehicle_type, #maker, #fuel_type, #country')
                .select2();

            $('#office_type').change(() => {
                let selectedOfficeType = $('#user_form').find('#office_type').val();
                const selectedDepartmentType = $('#user_form').find('#department').val();
                $('#division_section').hide();
                $('#subdivision_section').hide();
                let officeList = $('#office_list');
                if (selectedDepartmentType) {
                    if (selectedOfficeType) {
                        if ((selectedOfficeType != 'DO') && (selectedOfficeType != 'SDO')) {
                            // ajax call to get office list
                            $.ajax({
                                url: '/asset-management/getOfficeList',
                                method: 'GET',
                                data: {
                                    office_type: selectedOfficeType,
                                    department_type: selectedDepartmentType
                                },
                                success: function(data) {
                                    if (data.status === 404) {
                                        alert(data.message);
                                        officeList.empty(); // clear previous option list
                                        officeList.append($('<option>').text('Please Select'));
                                        officeList.prop('disabled', true);
                                    } else {
                                        officeList.empty(); // clear previous option list

                                        $.each(data.offices, function(key, value) {
                                            officeList.append($('<option>').text(value
                                                    .office_name)
                                                .attr('value', value.id));
                                        });
                                        // clear the disabled option
                                        officeList.prop('disabled', false);
                                    }
                                }
                            });
                        }
                        if (selectedOfficeType == 'DO') {
                            $('#division_section').show();
                            $('#division').empty();
                            $('#division').append('<option value="">Choose One</option>');
                            $.each(circleDetails, function(index, value) {
                                if (value.dept_cd == selectedDepartmentType) {
                                    $('#division').append('<option value="' + value.circle_cd +
                                        '">' +
                                        value
                                        .circle_name +
                                        '</option>');
                                }
                            });
                            $('#division').change(() => {
                                const selectedDivision = $('#user_form').find('#division').val();
                                console.log('selected division: ', selectedDivision);
                                $.ajax({
                                    url: '/asset-management/getOfficeList',
                                    method: 'GET',
                                    data: {
                                        office_type: selectedOfficeType,
                                        department_type: selectedDepartmentType
                                    },
                                    success: function(data) {
                                        if (data.status === 404) {
                                            alert(data.message);
                                            officeList
                                                .empty(); // clear previous option list
                                            officeList.append($('<option>').text(
                                                'Please Select'));
                                            officeList.prop('disabled', true);
                                        } else {
                                            officeList
                                                .empty(); // clear previous option list
                                            $.each(data.offices, function(key, value) {
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
                                            // clear the disabled option
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
                            $.each(divisionDetails, function(index, value) {
                                if (value.dept_cd == selectedDepartmentType) {
                                    $('#subDivision').append('<option value="' + value.division_cd +
                                        '">' +
                                        value
                                        .division_name +
                                        '</option>');
                                }
                            });
                            $('#subDivision').change(() => {
                                const selectedSubDivision = $('#user_form').find('#subDivision')
                                    .val();
                                console.log('selected sub division: ', selectedSubDivision);
                                $.ajax({
                                    url: '/asset-management/getOfficeList',
                                    method: 'GET',
                                    data: {
                                        office_type: selectedOfficeType,
                                        department_type: selectedDepartmentType
                                    },
                                    success: function(data) {
                                        if (data.status === 404) {
                                            alert(data.message);
                                            officeList
                                                .empty(); // clear previous option list
                                            officeList.append($('<option>').text(
                                                'Please Select'));
                                            officeList.prop('disabled', true);
                                        } else {
                                            console.log(data);
                                            officeList
                                                .empty(); // clear previous option list

                                            $.each(data.offices, function(key, value) {
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
                                            // clear the disabled option
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
                    // officeList.append($('<option>').text('Please Select'));
                    alert('Please select department!');
                }
            })

            $('#selectAll').on('click', function() {
                if ($(this).prop('checked')) {
                    $('input[type="checkbox"]').not(this).prop('checked', true);
                } else {
                    $('input[type="checkbox"]').not(this).prop('checked', false);
                }
            })

            $("#additional_department").on("change", function() {
                $("#additional_designation").empty().append('<option value="">Please Select</option>');
                // $("#additional_office_type").empty().append('<option value="">Please Select</option>');
                $("#additional_office_list").empty().append('<option value="">Please Select</option>');
                const selectedDept = $("#additional_department").val();
                if (selectedDept) {
                    $.each(designationDetails, function(index, value) {
                        if (value.dept_cd == selectedDept)
                            $('#additional_designation').append('<option value="' + value.id +
                                '">' + value
                                .desg_name +
                                '</option>');
                    });
                }
            })

            $("#additional_designation").on("change", function() {
                // $("#additional_office_type").empty().append('<option value="">Please Select</option>');
                // $("#additional_office_list").empty().append('<option value="">Please Select</option>');
                // console.log('changed');
                const selectedDegn = $("#additional_designation").val();
                // if (selectedDegn) {
                //     $.each(degignationOfficeTypeMappings, function(index, value) {
                //         if (value.desg_cd == selectedDegn)
                //             $('#additional_office_type').append('<option value="' + value
                //                 .office_type_cd +
                //                 '">' + value
                //                 .office_type_desc +
                //                 '</option>');
                //     });
                // }
            })

            $('#additional_department, #additional_designation, #additional_office_type, #additional_office_list, #division, #subDivision')
                .select2();
            $('#additional_department').on('change', function() {
                $('#additional_office_type').val('');
            })

            const radioButtons = document.getElementsByName('has_additional_office');
            radioButtons.forEach(button => {
                button.addEventListener('change', function() {
                    $('#additional_office_info').hide();
                    $('#additional_department, #additional_designation, #additional_office_type, #additional_assign_date')
                        .prop('required', false);
                    const selectedValue = this.value;
                    if (selectedValue === 'Y') {
                        $('#additional_office_info').show();
                        $('#additional_department, #additional_designation, #additional_office_type, #additional_assign_date')
                            .prop('required', true);
                    }
                });
            });

            $('#additional_office_type').change(() => {
                let selectedOfficeType = $('#user_form').find('#additional_office_type').val();
                const selectedDepartmentType = $('#user_form').find('#additional_department').val();
                let officeList = $('#additional_office_list');
                if (selectedDepartmentType) {
                    if (selectedOfficeType) {
                        $.ajax({
                            url: '/asset-management/getOfficeList',
                            method: 'GET',
                            data: {
                                office_type: selectedOfficeType,
                                department_type: selectedDepartmentType
                            },
                            success: function(data) {
                                if (data.status === 404) {
                                    alert(data.message);
                                    officeList.empty(); // clear previous option list
                                    officeList.append($('<option>').text('Please Select'));
                                    officeList.prop('disabled', true);
                                } else {
                                    officeList.empty(); // clear previous option list

                                    $.each(data.offices, function(key, value) {
                                        officeList.append($('<option>').text(value
                                                .office_name)
                                            .attr('value', value.id));
                                    });
                                    // clear the disabled option
                                    officeList.prop('disabled', false);
                                }
                            }
                        });
                    } else {
                        officeList.prop('disabled', true);
                    }
                } else {
                    // officeList.append($('<option>').text('Please Select'));
                    alert('Please select department!');
                }
            })
        })
    </script>
@endpush
