@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Roads</li>
                        <li class="breadcrumb-item active">Road Chainage Steps</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Chainage Step</h5>
            <form id="add_chainage_step" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Step ID (BigInt) <span class="text-danger">*</span></label>
                        <input type="number" name="step_id" class="form-control form-control-sm" placeholder="e.g., 1" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Office Type Code</label>
                        <input type="text" name="office_type_cd" class="form-control form-control-sm" maxlength="10" placeholder="e.g., DIV_OFF">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Chainage Name <span class="text-danger">*</span></label>
                        <input type="text" name="chainage_name" class="form-control form-control-sm" placeholder="e.g., Sub-Division Level" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus mr-1"></i> ADD STEP
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Chainage Steps List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="chainageStepTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 15%;">Step ID</th>
                                <th style="width: 20%;">Office Type</th>
                                <th>Chainage Name</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($steps as $step)
                                <tr class="text-xs">
                                    <td class="font-weight-bold">{{ $step->step_id }}</td>
                                    <td>{{ $step->office_type_cd }}</td>
                                    <td>{{ $step->chainage_name }}</td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editStepModal{{ $step->step_id }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editStepModal{{ $step->step_id }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-step-form" method="PUT" action="{{ route('chainageStep.update', $step->step_id) }}">
                                                @csrf
                                                <input type="hidden" name="old_step_id" value="{{ $step->step_id }}">
                                                <div class="modal-header bg-light">
                                                    <h6 class="modal-title font-weight-bold">Update Step</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <div class="form-group">
                                                        <label class="text-xs">Step ID</label>
                                                        <input type="number" name="step_id" class="form-control form-control-sm" value="{{ $step->step_id }}" required>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label class="text-xs">Office Type Code</label>
                                                        <input type="text" name="office_type_cd" class="form-control form-control-sm" maxlength="10" value="{{ $step->office_type_cd }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Chainage Name</label>
                                                        <input type="text" name="chainage_name" class="form-control form-control-sm" value="{{ $step->chainage_name }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
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
        $('#chainageStepTable').DataTable({ "responsive": true });

        $('#add_chainage_step').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('chainageStep.store') }}",$(this).attr('method'));
        });

        $('.update-step-form').on('submit', function(e) {
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
                        title: res.status ? res.status.toUpperCase() : 'SUCCESS', 
                        text: res.message, 
                        timer: 2000 
                    }).then(() => { location.reload(); });
                }
            });
        }
    });
</script>
@endpush