@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Master</li>
                        <li class="breadcrumb-item">Road</li>
                        <li class="breadcrumb-item active">Habitation Sub-Facilities</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Habitation Sub-Facility</h5>
            <form id="add_sub_facility_form" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Parent Facility <span class="text-danger">*</span></label>
                        <select name="facility_id" class="form-control form-control-sm select2" required>
                            <option value="">-- Select Facility --</option>
                            @foreach($facilities as $fac)
                                <option value="{{ $fac->id }}">{{ $fac->facility_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Sub-Facility Name <span class="text-danger">*</span></label>
                        <input type="text" name="sub_facility_name" class="form-control form-control-sm" placeholder="e.g., Senior Secondary School" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Status</label>
                        <select name="is_published" class="form-select form-select-sm">
                            <option value="Y">Published</option>
                            <option value="N">Draft</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus-circle mr-1"></i> SAVE SUB-FACILITY
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Sub-Facilities Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="subFacilityTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 8%;">ID</th>
                                <th>Sub-Facility Name</th>
                                <th>Parent Facility</th>
                                <th class="text-center" style="width: 12%;">Status</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subFacilities as $sub)
                                <tr class="text-xs">
                                    <td>{{ $sub->id }}</td>
                                    <td class="font-weight-bold">{{ $sub->sub_facility_name }}</td>
                                    <td><span class="text-muted">{{ $sub->facility_name }}</span></td>
                                    <td class="text-center">
                                        <span class="badge {{ $sub->is_published == 'Y' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $sub->is_published == 'Y' ? 'PUBLISHED' : 'DRAFT' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editSubModal{{ $sub->id }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editSubModal{{ $sub->id }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-sub-form" method="PUT" action="{{ route('habitationSubFacilities.update', $sub->id) }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $sub->id }}">
                                                <div class="modal-header bg-light py-2">
                                                    <h6 class="modal-title font-weight-bold">Update Sub-Facility</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="text-xs">Parent Facility</label>
                                                        <select name="facility_id" class="form-control form-control-sm" required>
                                                            @foreach($facilities as $fac)
                                                                <option value="{{ $fac->id }}" {{ $sub->facility_id == $fac->id ? 'selected' : '' }}>
                                                                    {{ $fac->facility_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Sub-Facility Name</label>
                                                        <input type="text" name="sub_facility_name" class="form-control form-control-sm" value="{{ $sub->sub_facility_name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Status</label>
                                                        <select name="is_published" class="form-select form-select-sm">
                                                            <option value="Y" {{ $sub->is_published == 'Y' ? 'selected' : '' }}>Published</option>
                                                            <option value="N" {{ $sub->is_published == 'N' ? 'selected' : '' }}>Draft</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer py-1">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success btn-sm">Update Changes</button>
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
        $('#subFacilityTable').DataTable({ responsive: true });

        $('#add_sub_facility_form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('habitationSubFacilities.store') }}",$(this).attr('method'));
        });

        $('.update-sub-form').on('submit', function(e) {
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