@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Master</li>
                        <li class="breadcrumb-item">Projects</li>
                        <li class="breadcrumb-item active">Workplan Activities</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Workplan Activity</h5>
            <form id="add_activity_form" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Activity Name <span class="text-danger">*</span></label>
                        <input type="text" name="wp_name" class="form-control form-control-sm" maxlength="50" placeholder="e.g., Excavation Works" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Department <span class="text-danger">*</span></label>
                        <select name="dept_cd" class="form-control form-control-sm select2" required>
                            <option value="">-- Select Dept --</option>
                            @foreach($departments as $dept)
                                @if($dept->department_name !== 'Secretariat')
                                    <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Status</label>
                        <select name="is_published" class="form-control form-control-sm">
                            <option value="Y">Published</option>
                            <option value="N">Draft</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-tasks mr-1"></i> SAVE ACTIVITY
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Workplan Activities List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="activityTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th>Activity Name</th>
                                <th>Department</th>
                                <th class="text-center" style="width: 10%;">Status</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activities as $act)
                                <tr class="text-xs">
                                    <td>{{ $act->wp_cd }}</td>
                                    <td class="font-weight-bold">{{ $act->wp_name }}</td>
                                    <td>{{ $act->department_name }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $act->is_published == 'Y' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $act->is_published == 'Y' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editActModal{{ $act->wp_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editActModal{{ $act->wp_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-activity-form" method="PUT" action="{{ route('workplanActivity.update', $act->wp_cd) }}">
                                                @csrf
                                                <input type="hidden" name="wp_cd" value="{{ $act->wp_cd }}">
                                                <div class="modal-header bg-light py-2">
                                                    <h6 class="modal-title font-weight-bold">Edit Activity</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="text-xs">Activity Name</label>
                                                        <input type="text" name="wp_name" class="form-control form-control-sm" value="{{ $act->wp_name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Department</label>
                                                        <select name="dept_cd" class="form-control form-control-sm" required>
                                                            @foreach($departments as $dept)
                                                                @if($dept->department_name !== 'Secretariat')
                                                                    <option value="{{ $dept->id }}" {{ $act->dept_cd == $dept->id ? 'selected' : '' }}>
                                                                        {{ $dept->department_name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Status</label>
                                                        <select name="is_published" class="form-control form-control-sm">
                                                            <option value="Y" {{ $act->is_published == 'Y' ? 'selected' : '' }}>Published</option>
                                                            <option value="N" {{ $act->is_published == 'N' ? 'selected' : '' }}>Draft</option>
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
        $('#activityTable').DataTable({ responsive: true });

        $('#add_activity_form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('workplanActivity.store') }}",$(this).attr('method'));
        });

        $('.update-activity-form').on('submit', function(e) {
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