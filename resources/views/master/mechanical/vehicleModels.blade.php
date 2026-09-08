@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Master</li>
                        <li class="breadcrumb-item">Mechanical</li>
                        <li class="breadcrumb-item active">Vehicle Models</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Vehicle Model</h5>
            <form id="add_model" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Maker <span class="text-danger">*</span></label>
                        <select name="maker_cd" class="form-control form-control-sm select2" required>
                            <option value="">-- Select Maker --</option>
                            @foreach($makers as $maker)
                                <option value="{{ $maker->maker_cd }}">{{ $maker->maker_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Model Code <span class="text-danger">*</span></label>
                        <input type="text" name="model_cd" class="form-control form-control-sm" maxlength="5" placeholder="e.g., ACE" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Model Name <span class="text-danger">*</span></label>
                        <input type="text" name="model_name" class="form-control form-control-sm" placeholder="e.g., Tata Ace Gold" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus mr-1"></i> ADD MODEL
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Vehicle Model Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="modelTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th>Maker</th>
                                <th style="width: 20%;">Model Code</th>
                                <th>Model Name</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($models as $model)
                                <tr class="text-xs">
                                    <td>{{ $model->maker_name }}</td>
                                    <td class="font-weight-bold">{{ $model->model_cd }}</td>
                                    <td>{{ $model->model_name }}</td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editModelModal{{ $model->model_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModelModal{{ $model->model_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-model-form" method="PUT" action="{{ route('vehicleModels.update', $model->model_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_model_cd" value="{{ $model->model_cd }}">
                                                <div class="modal-header bg-light">
                                                    <h6 class="modal-title font-weight-bold">Update Model</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="text-xs">Maker</label>
                                                        <select name="maker_cd" class="form-control form-control-sm" required>
                                                            @foreach($makers as $maker)
                                                                <option value="{{ $maker->maker_cd }}" {{ $maker->maker_cd == $model->maker_cd ? 'selected' : '' }}>
                                                                    {{ $maker->maker_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label class="text-xs">Model Code</label>
                                                        <input type="text" name="model_cd" class="form-control form-control-sm" maxlength="5" value="{{ $model->model_cd }}" required>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label class="text-xs">Model Name</label>
                                                        <input type="text" name="model_name" class="form-control form-control-sm" value="{{ $model->model_name }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success btn-sm">Save Changes</button>
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
        $('#modelTable').DataTable({ "responsive": true });

        $('#add_model').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('vehicleModels.store') }}", $(this).attr('method'));
        });

        $('.update-model-form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), $(this).attr('action'), $(this).attr('method'));
        });

        function handleAjax(form, url, method) {
            $.ajax({
                type: method,
                url: url,
                data: form.serialize(),
                success: function(res) {
                    Swal.fire({ icon: res.status, title: res.status.toUpperCase(), text: res.message, timer: 2000 })
                    .then(() => { location.reload(); });
                }
            });
        }
    });
</script>
@endpush