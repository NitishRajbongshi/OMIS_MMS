@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Administrative</li>
                        <li class="breadcrumb-item active">Manage Zones</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_zone" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">State <span class="star">*</span></label>
                        <select name="state_cd" class="form-select form-select-sm" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->state_code }}">{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">District <span class="star">*</span></label>
                        <select name="district_cd" class="form-control form-control-sm select2" required>
                            <option value="">Select District</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist->dist_code }}">{{ $dist->dist_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Department <span class="star">*</span></label>
                        <select name="dept_cd" class="form-control form-control-sm select2" required>
                            <option value="">Select Dept</option>
                            @foreach($departments as $dept)
                            @if($dept->department_name !== 'Secretariat')
                                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="text-xs">Zone Code <span class="star">*</span></label>
                        <input type="text" name="zone_cd" class="form-control form-control-sm" placeholder="Zone Code" required>
                    </div>
                    <div class="col-md-6">
                        <label class="text-xs">Zone Name <span class="star">*</span></label>
                        <input type="text" name="zone_name" class="form-control form-control-sm" placeholder="Enter Zone Name" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold w-100">
                            <i class="fa fa-plus"></i> Add Zone
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">Zone Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="zoneTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th>Zone Code</th>
                                <th>Zone Name</th>
                                <th>District</th>
                                <th>Department</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($zones as $zone)
                                <tr class="text-xs">
                                    <td>{{ $zone->zone_cd }}</td>
                                    <td>{{ $zone->zone_name }}</td>
                                    <td>{{ $zone->dist_name }}</td>
                                    <td>{{ $zone->department_name }}</td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#editModal{{ $zone->zone_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $zone->zone_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-zone-form" method="PUT" action="{{ route('zone.update', $zone->zone_cd) }}">
                                                @csrf
                                                <input type="hidden" name="zone_cd" value="{{ $zone->zone_cd }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Update Zone: {{ $zone->zone_cd }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Zone Name</label>
                                                        <input type="text" name="zone_name" class="form-control" value="{{ $zone->zone_name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>District</label>
                                                        <select name="district_cd" class="form-control select2" required>
                                                            <option value="">Select District</option>
                                                            @foreach($districts as $dist)
                                                                <option value="{{ $dist->dist_code }}" {{ $dist->dist_code == $zone->district_cd ? 'selected' : '' }}>{{ $dist->dist_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Department</label>
                                                        <select name="dept_cd" class="form-control select2" required>
                                                            <option value="">Select Department</option>
                                                            @foreach($departments as $dept)
                                                            @if($dept->department_name !== 'Secretariat')
                                                                <option value="{{ $dept->id }}" {{ $dept->id == $zone->dept_cd ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
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
        $('#zoneTable').DataTable();

        // Initialize Select2
        $('select.select2').select2({ width: '100%', placeholder: 'Select an option', allowClear: true });

        // Re-initialize Select2 inside modals
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('select.select2').select2({ width: '100%', allowClear: true, dropdownParent: $(this) });
        });

        $('#add_zone').on('submit', function(e) {
            e.preventDefault();
            ajaxCall($(this), "{{ route('zone.store') }}",$(this).attr('method'));
        });

        $('.update-zone-form').on('submit', function(e) {
            e.preventDefault();
            ajaxCall($(this), $(this).attr('action'),$(this).attr('method'));
        });

        function ajaxCall(form, url, method) {
            $.ajax({
                type: method,
                url: url,
                data: form.serialize(),
                success: function(res) {
                    Swal.fire({ icon: res.status, title: res.message, timer: 2000 })
                    .then(() => { location.reload(); });
                }
            });
        }
    });
</script>
@endpush