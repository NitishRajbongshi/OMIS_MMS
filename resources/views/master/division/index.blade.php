@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Administrative</li>
                        <li class="breadcrumb-item active">Manage Divisions</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_division" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-2 mb-2">
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
                        <select name="district_cd" class="form-control form-control-sm select2" required>
                            <option value="">Select District</option>
                            @foreach($districts as $dist)
                                    <option value="{{ $dist->dist_code }}">{{ $dist->dist_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Zone <span class="star">*</span></label>
                        <select name="zone_cd" class="form-control form-control-sm select2" required>
                            <option value="">Select Zone</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->zone_cd }}">{{ $zone->zone_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Circle <span class="star">*</span></label>
                        <select name="circle_cd" class="form-control form-control-sm select2" required>
                            <option value="">Select Circle</option>
                            @foreach($circles as $circle)
                                <option value="{{ $circle->circle_cd }}">{{ $circle->circle_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Dept <span class="star">*</span></label>
                        <select name="dept_cd" class="form-control form-control-sm select2" required>
                            <option value="">Select Dept</option>
                            @foreach($departments as $dept)
                                @if($dept->department_name !== 'Secretariat')
                                    <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 mb-2">
                        <label class="text-xs">Div Code <span class="star">*</span></label>
                        <input type="text" name="division_cd" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Division Name <span class="star">*</span></label>
                        <input type="text" name="division_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-1 mb-2">
                        <label class="text-xs">Short Code</label>
                        <input type="text" name="div_short_code" class="form-control form-control-sm" maxlength="5">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Latitude</label>
                        <input type="number" step="any" name="lat" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Longitude</label>
                        <input type="number" step="any" name="lng" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold w-100">
                            <i class="fa fa-plus"></i> Add Division
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">Division Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="divTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th>Code</th>
                                <th>Division Name</th>
                                <th>Circle</th>
                                <th>Short Code</th>
                                <th>Lat/Lng</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($divisions as $div)
                                <tr class="text-xs">
                                    <td>{{ $div->division_cd }}</td>
                                    <td>{{ $div->division_name }}</td>
                                    <td>{{ $div->circle_name }}</td>
                                    <td>{{ $div->div_short_code }}</td>
                                    <td>{{ $div->lat }}, {{ $div->lng }}</td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#editModal{{ $div->division_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $div->division_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-div-form" method="PUT" action="{{ route('division.update', $div->division_cd) }}">
                                                @csrf
                                                <input type="hidden" name="division_cd" value="{{ $div->division_cd }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Update Division: {{ $div->division_cd }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Division Name</label>
                                                        <input type="text" name="division_name" class="form-control" value="{{ $div->division_name }}" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label>Lat</label>
                                                            <input type="number" step="any" name="lat" class="form-control" value="{{ $div->lat }}">
                                                        </div>
                                                        <div class="col-6">
                                                            <label>Lng</label>
                                                            <input type="number" step="any" name="lng" class="form-control" value="{{ $div->lng }}">
                                                        </div>
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
        $('#divTable').DataTable();

        // Initialize Select2 on all add-form dropdowns
        $('select.select2').select2({ width: '100%', placeholder: 'Select an option', allowClear: true });

        $('#add_division').on('submit', function(e) {
            e.preventDefault();
            ajaxRequest($(this), "{{ route('division.store') }}",$(this).attr('method'));
        });

        $('.update-div-form').on('submit', function(e) {
            e.preventDefault();
            ajaxRequest($(this), $(this).attr('action'),$(this).attr('method'));
        });

        function ajaxRequest(form, url, method) {
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