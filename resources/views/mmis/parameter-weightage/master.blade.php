@extends('layouts.app')

@section('title', 'Maintenance Parameter Weightage')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Maintenance Parameter Weightage</h4>
        </div>
        <a href="{{ route('maintenance.landing') }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-arrow-left"></i> Maintenance Portal</a>
    </div>

    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Maintenance Parameter</h5>
            <form id="add_param_form" method="POST">
                @csrf
                <div class="row">
                    <!-- First row: 3 fields -->
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Parameter Name<span class="text-danger">*</span></label>
                        <input type="text" name="param_name" class="form-control form-control-sm" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Parameter Code<span class="text-danger">*</span></label>
                        <input type="text" name="param_code" class="form-control form-control-sm"
                            style="text-transform: uppercase;"
                            oninput="this.value = this.value.toUpperCase()" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Parameter Type<span class="text-danger">*</span></label>
                        <select name="param_type" class="form-control form-control-sm" required>
                            <option value="">Select Type</option>
                            <option value="RANGE">RANGE</option>
                            <option value="LIST">LIST</option>
                            <option value="NUMERIC">NUMERIC</option>
                            <option value="TEXT">TEXT</option>
                            <option value="BOOLEAN">BOOLEAN</option>
                        </select>
                    </div>

                    <!-- Second row: 3 items -->
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Parameter Category<span class="text-danger">*</span></label>
                        <select name="param_category" class="form-control form-control-sm" required>
                            <option value="">Select Category</option>
                            <option value="CONDITION">CONDITION</option>
                            <option value="CRITICALITY">CRITICALITY</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Parameter Description<span class="text-danger">*</span></label>
                        <textarea name="param_desc" class="form-control form-control-sm" rows="2" required></textarea>
                    </div>

                    <div class="col-md-4 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus-circle mr-1"></i> Save Parameter
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Maintenance Parameters Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="paramTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Parameter Name</th>
                                <th class="text-center">Parameter Code</th>
                                <th class="text-center">Parameter Type</th>
                                <th class="text-center">Parameter Categoty</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($parameters as $parameter)
                                <tr class="text-xs">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="font-weight-bold text-center">{{ $parameter->parameter_name }}</td>
                                    <td class="text-center">{{ $parameter->parameter_code }}</td>
                                    <td class="text-center">{{ $parameter->parameter_type }}</td>
                                    <td class="text-center">{{ $parameter->parameter_category }}</td>
                                    <td class="text-center">{{ $parameter->description }}</td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editParamModal{{ $parameter->id }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('mmis.parameter-weightage.deleteParameter', $parameter->id) }}"
                                            method="POST" class="d-inline delete-param-form">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm text-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editParamModal{{ $parameter->id }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-param-form" method="PUT" action="{{ route('mmis.parameter-weightage.updateParameter', $parameter->id) }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $parameter->id }}">
                                                <div class="modal-header bg-light py-2">
                                                    <h6 class="modal-title font-weight-bold">Update Parameter</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="text-xs">Parameter Name<span class="text-danger">*</span></label>
                                                        <input type="text" name="param_name" class="form-control form-control-sm" value="{{ $parameter->parameter_name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Parameter Code</label>
                                                        <input type="text" name="param_code" class="form-control form-control-sm" value="{{ $parameter->parameter_code }}" readonly style="text-transform: uppercase;" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="text-xs">Parameter Type<span class="text-danger">*</span></label>
                                                        <select name="param_type" class="form-control form-control-sm" required>
                                                            <option value="">Select Type</option>
                                                            <option value="RANGE" {{ $parameter->parameter_type == 'RANGE' ? 'selected' : '' }}>RANGE</option>
                                                            <option value="LIST" {{ $parameter->parameter_type == 'LIST' ? 'selected' : '' }}>LIST</option>
                                                            <option value="NUMERIC" {{ $parameter->parameter_type == 'NUMERIC' ? 'selected' : '' }}>NUMERIC</option>
                                                            <option value="TEXT" {{ $parameter->parameter_type == 'TEXT' ? 'selected' : '' }}>TEXT</option>
                                                            <option value="BOOLEAN" {{ $parameter->parameter_type == 'BOOLEAN' ? 'selected' : '' }}>BOOLEAN</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="text-xs">Parameter Category<span class="text-danger">*</span></label>
                                                        <select name="param_category" class="form-control form-control-sm" required>
                                                            <option value="">Select Category</option>
                                                            <option value="CONDITION" {{ $parameter->parameter_category == 'CONDITION' ? 'selected' : '' }}>CONDITION</option>
                                                            <option value="CRITICALITY" {{ $parameter->parameter_category == 'CRITICALITY' ? 'selected' : '' }}>CRITICALITY</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="text-xs">Description<span class="text-danger">*</span></label>
                                                        <textarea name="param_desc" class="form-control form-control-sm" rows="2" required>{{ $parameter->description }}</textarea>
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
            $('#paramTable').DataTable({ responsive: true });

            $('#add_param_form').on('submit', function(e) {
                e.preventDefault();
                handleAjax($(this), "{{ route('mmis.parameter-weightage.saveParameter') }}",$(this).attr('method'));
            });

            $('.update-param-form').on('submit', function(e) {
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
                        }).then(() => { location.reload(); });
                    },
                    error: function(xhr) {
                        let message = 'Something went wrong. Please try again.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }
                });
            }

            $(document).on('submit', '.delete-param-form', function(e) {
                e.preventDefault();

                let form = $(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This parameter will be deleted if it is not mapped to any department or asset.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: form.attr('action'),
                            data: form.serialize(),
                            success: function(res) {
                                Swal.fire({
                                    icon: res.status,
                                    title: res.status.toUpperCase(),
                                    text: res.message
                                }).then(() => {
                                    if (res.status === 'success') {
                                        location.reload();
                                    }
                                });
                            },
                            error: function(xhr) {
                                let message = 'Something went wrong. Please try again.';

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: message
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
