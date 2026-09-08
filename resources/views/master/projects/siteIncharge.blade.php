@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Projects</li>
                        <li class="breadcrumb-item active">Site In-charge Office Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_office" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Office Code <span class="star">*</span></label>
                        <input type="text" name="office_cd" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Office Name <span class="star">*</span></label>
                        <input type="text" name="office_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Contact Person <span class="star">*</span></label>
                        <input type="text" name="contact_person_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Phone No <span class="star">*</span></label>
                        <input type="text" name="ph_no" class="form-control form-control-sm" maxlength="10" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Address Line 1</label>
                        <input type="text" name="address_line1" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">State <span class="star">*</span></label>
                        <select name="state_cd" class="form-control form-control-sm" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->state_code }}">{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">District <span class="star">*</span></label>
                        <select name="district_cd" class="form-control form-control-sm" required>
                            <option value="">Select District</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist->dist_code }}">{{ $dist->dist_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">PIN Code</label>
                        <input type="text" name="pin_code" class="form-control form-control-sm" maxlength="6">
                    </div>
                </div>
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Latitude / Longitude</label>
                        <div class="input-group">
                            <input type="number" step="any" name="latitude" class="form-control form-control-sm" placeholder="Lat">
                            <input type="number" step="any" name="longitude" class="form-control form-control-sm" placeholder="Lng">
                        </div>
                    </div>
                    <div class="col-md-2 mb-2 text-center">
                        <div class="custom-control custom-checkbox mb-2">
                            <input class="custom-control-input" type="checkbox" id="is_published" name="is_published" value="Y" checked>
                            <label for="is_published" class="custom-control-label text-xs">Published</label>
                        </div>
                    </div>
                    <div class="col-md-7 mb-2 text-right">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold">
                            <i class="fa fa-plus"></i> Save Office Details
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="officeTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th>Code</th>
                                <th>Office Name</th>
                                <th>Contact Person</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offices as $office)
                                <tr class="text-xs">
                                    <td>{{ $office->office_cd }}</td>
                                    <td>{{ $office->office_name }}</td>
                                    <td>{{ $office->contact_person_name }}</td>
                                    <td>{{ $office->ph_no }}</td>
                                    <td>{{ $office->dist_name }}, {{ $office->state_name }}</td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editOffice{{ $office->office_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editOffice{{ $office->office_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form class="update-office-form" method="PUT" action="{{ route('officeDetails.update', $office->office_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_office_cd" value="{{ $office->office_cd }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Edit Office: {{ $office->office_cd }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <!-- <div class="col-md-4">
                                                            <label>Office Code</label>
                                                            <input type="text" name="office_cd" class="form-control" value="{{ $office->office_cd }}" required>
                                                        </div> -->
                                                        <div class="col-md-8">
                                                            <label>Office Name</label>
                                                            <input type="text" name="office_name" class="form-control" value="{{ $office->office_name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-6">
                                                            <label>Contact Name</label>
                                                            <input type="text" name="contact_person_name" class="form-control" value="{{ $office->contact_person_name }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Phone</label>
                                                            <input type="text" name="ph_no" class="form-control" value="{{ $office->ph_no }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm">Update Details</button>
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#officeTable').DataTable({ responsive: true, autoWidth: false, order: [[0, "asc"]] });

    $('#add_office').on('submit', function(e) {
        e.preventDefault();
        handleAjax($(this), "{{ route('officeDetails.store') }}",$(this).attr('method'));
    });

    $('.update-office-form').on('submit', function(e) {
        e.preventDefault();
        handleAjax($(this), $(this).attr('action'),$(this).attr('method'));
    });

    function handleAjax(form, url, method) {
        $.ajax({
            type: method,
            url: url,
            data: form.serialize(),
            success: function(res) {
                Swal.fire({ 
                    icon: res.status, 
                    title: res.status.toUpperCase(), 
                    text: res.message, 
                    timer: 2000 
                }).then(() => { location.reload(); });
            }
        });
    }
});
</script>
@endpush