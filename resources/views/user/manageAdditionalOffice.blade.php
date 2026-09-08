@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        {{-- <li class="breadcrumb-item"><a href="{{ route('viewUsers') }}">View Users</a></li> --}}
                        <li class="breadcrumb-item">Manage Additional Office</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        @if (session('error'))
            <div class="text-xs alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-info" aria-hidden="true"></i>
                <strong>Failed!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="text-xs alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check" aria-hidden="true"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <p class="border bg-primary text-light p-2">
            Adding additional office for user: <span class="text-bold">{{ $searchUserName->name }}</span>
        </p>
        <div class="border container-fluid mainBody py-3">
            <form action="{{ route('manageUserAddOffice') }}" method="post" id="additional_office_form" autocomplete="off">
                @csrf
                <legend class="w-auto px-2 text-sm">Additional Office Details</legend>
                <div class="row form-1-box">
                    <input type="hidden" name="userid" id="userid" value="{{ $searchUserId }}">
                    <div class="col-md-3">
                        <label for="department">Department <span class="star">*</span></label>
                        <select id="department" class="custom-select form-control" name="department">
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
                        @error('department')
                            <div class="text-danger mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="designation">Designation <span class="star">*</span></label>
                        <select id="designation" class="custom-select form-control" name="designation">
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
                        <select id="office_type" class="custom-select form-control" name="office_type">
                            <option value="" disable selected hidden>Please Select</option>
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
                    <div class="col-md-3">
                        <label for="office_list">Office<span class="star">*</span></label>
                        <select id="office_list" class="custom-select form-control" name="office_list" disabled>
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
                        <input type="date" id="assign_date" class="form-control" name="assign_date">
                    </div>
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
                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-success btn-sm rounded-0 mt-2">
                        <i class="fa fa-save mr-2"></i>Submit
                    </button>
                    <a href="" class="btn btn-sm btn-danger rounded-0 mt-2 ml-3" id="reset">
                        <i class="fa fa-sync-alt mr-2"></i>Reset</a>
                </div>
            </form>
        </div>

        <div class="container-fluid border mainBody">
            <div class="mt-3">
                @if (empty($officeDetails['office_details']))
                    <p class="text-danger"><i class="fa-solid fa-notdef text-danger"></i> User does not have previous
                        records.</p>
                @else
                    <span class="mis-btn-office"></span>
                    <table class="table-responsive text-xs table table-bordered table-striped user_list" id="user_table">
                        <thead class="theader text-white" style="background-color:#417DBE">
                            <th class="text-center" style="min-width: 4rem;">Sl. No</th>
                            <th class="text-center" style="min-width: 8rem;">Department</th>
                            <th class="text-center" style="min-width: 8rem;">Designation</th>
                            <th class="text-center" style="min-width: 8rem;">Office Type</th>
                            <th class="text-center" style="min-width: 8rem;">Office</th>
                            <th class="text-center" style="min-width: 8rem;">From</th>
                            <th class="text-center" style="min-width: 8rem;">To</th>
                            <th class="text-center" style="min-width: 8rem;">Action</th>
                        </thead>

                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($officeDetails['office_details'] as $officeDetail)
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td>{{ $officeDetail['department_name'] }}</td>
                                    <td>{{ $officeDetail['designation_name'] }}</td>
                                    <td>{{ $officeDetail['office_type_name'] }}</td>
                                    <td>{{ $officeDetail['office_name'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($officeDetail['from'])->format('d-m-Y') }}</td>
                                    <td>{{ $officeDetail['to'] ? \Carbon\Carbon::parse($officeDetail['to'])->format('d-m-Y') : 'Present' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($officeDetail['status'] === 'A')
                                            <a href="#" class="btn btn-xs btn-danger changeStatusBtn"
                                                data-id="{{ $i }}">Deactive User</a>
                                        @else
                                            <p class="btn btn-xs btn-warning">Deactived</p>
                                        @endif
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>
@endsection
@push('styles')
@endpush

@push('scripts')
    <script>
        const designationDetails = @json($desgdetails);
        const officeDetails = @json($officedetails);
        $(document).ready(function() {
            $('#department, #designation, #office_type, #office_list').select2();

            $("#department").on("change", function() {
                $('#office_type').val('');
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
                }
            })

            $('#office_type').change(() => {
                let selectedOfficeType = $('#additional_office_form').find('#office_type').val();
                const selectedDepartmentType = $('#additional_office_form').find('#department').val();
                let officeList = $('#office_list');
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
                                    officeList.empty();
                                    officeList.append($('<option>').text('Please Select'));
                                    officeList.prop('disabled', true);
                                } else {
                                    officeList.empty();
                                    $.each(data.offices, function(key, value) {
                                        officeList.append($('<option>').text(value
                                                .office_name)
                                            .attr('value', value.id));
                                    });
                                    officeList.prop('disabled', false);
                                }
                            }
                        });
                    } else {
                        officeList.prop('disabled', true);
                    }
                } else {
                    alert('Please select department!');
                }
            })
        });

        $('.changeStatusBtn').on('click', function() {
            const location = '/asset-management/manage-additional-office'
            const index = $(this).data('id');
            const userid = $('#userid').val();
            if (index >= 0) {
                $.ajax({
                    url: '/asset-management/change-add-office-status',
                    method: 'GET',
                    data: {
                        index: index,
                        userid: userid
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status === 'success') {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'success',
                                    text: response.message,
                                    showConfirmButton: true,
                                    timer: 3000
                                })
                                .then(() => {
                                    window.location.replace(location);
                                });
                        } else {
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                    showConfirmButton: true,
                                    timer: 3000
                                })
                                .then(() => {
                                    window.location.replace(location);
                                });
                        }
                    }
                });
            }
        })
    </script>
@endpush
