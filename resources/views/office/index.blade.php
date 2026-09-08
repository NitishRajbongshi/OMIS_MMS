@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-12 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Manage Office</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fa fa-check-circle mr-1"></i>Success!</strong>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i>Failed!</strong>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="container-fluid mainBody border py-2 px-3">
            <form id="add_office" action="{{ route('manageOffice') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <label for="office_name">Office Name <span class="text-danger">*</span></label>
                        <input type="text" id="office_name" class="form-control form-control-sm" name="office_name"
                            placeholder="Enter Office Name">

                        @error('office_name')
                            <div class="text-danger mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="department_id">Department <span class="text-danger">*</span></label>
                        <select id="department_id" class="custom-select form-control form-control-sm" name="department_id">
                            <option value="" disable selected hidden>Please Select</option>
                            @foreach ($dept_details as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                            @endforeach
                        </select>

                        @error('department_id')
                            <div class="text-danger mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="office_type_cd">Office Type<span class="text-danger">*</span></label>
                        <select id="office_type_cd" class="custom-select form-control form-control-sm"
                            name="office_type_cd">
                            <option value="" disable selected hidden>Please Select</option>
                            @foreach ($officeTypes as $officeType)
                                <option value="{{ $officeType->office_type_cd }}">
                                    {{ $officeType->office_type_desc }}</option>
                            @endforeach
                        </select>

                        @error('office_type_cd')
                            <div class="text-danger mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="row m-0 p-0" style="background:#f3f3f3">
                        <div class="col-md-4 col-sm-12 my-2" id="zone_cd_group" style="display:none;">
                            <label for="zone_cd">Zonal Office<span class="text-danger">*</span></label>
                            <select id="zone_cd" class="custom-select form-control form-control-sm" name="zone_cd">
                                <option value="" disable selected hidden>Choose One</option>
                                {{-- Dynamic content --}}
                            </select>

                            @error('zone_cd')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12  my-2" id="circle_cd_group" style="display:none;">
                            <label for="circle_cd">Circle Office<span class="text-danger">*</span></label>
                            <select id="circle_cd" class="custom-select form-control form-control-sm" name="circle_cd">
                                <option value="" disable selected hidden>Choose One</option>
                                {{-- Dynamic content --}}

                            </select>

                            @error('circle_cd')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12  my-2" id="division_cd_group" style="display:none;">
                            <label for="division_cd">Division Office<span class="text-danger">*</span></label>
                            <select id="division_cd" class="custom-select form-control form-control-sm" name="division_cd">
                                <option value="" disable selected hidden>Choose One</option>
                                {{-- Dynamic content --}}
                            </select>

                            @error('division_cd')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-sm-12  my-2" id="sub_division_cd_group" style="display:none;">
                            <label for="sub_division_cd">Sub Division Office<span class="text-danger">*</span></label>
                            <select id="sub_division_cd" class="custom-select form-control form-control-sm"
                                name="sub_division_cd">
                                <option value="" disable selected hidden>Choose One</option>
                                {{-- Dynamic content --}}
                            </select>

                            @error('sub_division_cd')
                                <div class="text-danger mt-2 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <label for="">Whether it is Parent Office <span class="text-danger">*</span></label><br>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="pOfficeYes" name="parent_office"
                                    value="Y">Yes
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="pOfficeNo" name="parent_office"
                                    value="N">No
                            </label>
                        </div><br>
                        @error('parent_office')
                            <div class="text-danger mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row hiddenDiv" style="display:none">
                    <div class="col-md-4 col-sm-12">
                        <label for="">Select Parent Office <span class="text-danger">*</span></label>
                        <select id="parent_office_id" class="custom-select form-control form-control-sm"
                            name="parent_office_id">
                            <option value="" disable selected hidden>Please Select</option>
                        </select>

                        @error('parent_office_id')
                            <div class="text-danger mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12 mt-3">
                        <button type="submit" class="btn btn-primary btn-sm rounded-0 mt-2 px-2 rounded-1">
                            <i class="fa fa-plus"></i>
                            Add Office Details
                        </button>
                    </div>
                </div><br>
            </form>
        </div>


        <table class="text-xs table table-bordered table-striped user_list" id="office_table">
            <thead class="theader text-white" style="background-color:#417DBE">
                <th class="text-center">Sl No.</th>
                <th class="text-center">Office Name</th>
                <th class="text-center">Department</th>
                <th class="text-center">Edit</th>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($office_details as $key)
                    <tr>
                        <td class="text-center">{{ $i }}</td>
                        <td class="text-start">{{ $key->office_name }}</td>
                        <td class="text-start">{{ $key->department_name }}</td>
                        <td class="text-center">
                            <a class="text-warning edit" data-toggle="modal" data-target="#officeEditModal"
                                data-office-id="{{ $key->id }}" data-office-name="{{ $key->office_name }}"
                                data-department-id="{{ $key->department_id }}">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
            </tbody>
        </table>
    </section>
    <!-- edit modal -->
    <div class="modal" id="officeEditModal">
        <div class="modal-dialog modal-md text-xs">
            <div class="modal-content">
                <form class="editOfficeForm" id="editOfficeForm" name="editOfficeForm" method="POST" action="#"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="edit_office_id" value="">
                    <div class="modal-header">
                        <p class="modal-title text-bold text-md" id="editUserModalLabel">
                            <i class="fas fa-edit mr-2"></i>
                            Update Office Details
                        </p>
                        <a type="button" data-dismiss="modal"><i class="fas fa-times"></i></a>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="edit_office_name" class="col-form-label">Office Name
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control form-control-sm" id="edit_office_name" name="office_name" rows="1"
                                    placeholder="" required></textarea>
                            </div>
                            <div class="col-12">
                                <label for="edit_dept" class="col-form-label">Department
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="edit_dept" class="form-select form-control form-control-sm"
                                    name="department_id">
                                    @foreach ($dept_details as $dept)
                                        <option value="{{ $dept->id }}">
                                            {{ $dept->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-xs text-sm mt-2 px-2"
                            onclick="editDraftData()">
                            <i class="fa fa-save"></i>
                            Update
                        </button>
                        <button class="btn btn-xs modalClose btn-danger text-sm mt-2 px-2" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal -->
@endsection
@push('styles')
    <link rel="stylesheet" href="css\common\selectOptionStyleSheet.css">
@endpush
@push('scripts')
    <script src="{{ asset('js/office/script.js') }}" defer></script>
@endpush
