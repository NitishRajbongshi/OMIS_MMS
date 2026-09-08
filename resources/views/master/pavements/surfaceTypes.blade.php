@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Master</li>
                        <li class="breadcrumb-item">Pavements</li>
                        <li class="breadcrumb-item active">Surface Types</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Surface Type</h5>
            <form id="add_surface_type" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Surface Code <span class="text-danger">*</span></label>
                        <input type="text" name="surface_cd" class="form-control form-control-sm" maxlength="10" placeholder="e.g., BC" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Description <span class="text-danger">*</span></label>
                        <input type="text" name="surface_descr" class="form-control form-control-sm" placeholder="e.g., Bituminous Concrete" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Pavement Category <span class="text-danger">*</span></label>
                        <select name="pavement_type_cd" class="form-control form-control-sm select2" required>
                            <option value="">-- Select Pavement --</option>
                            @foreach($pavementTypes as $pt)
                                <option value="{{ $pt->pavement_type_cd }}">{{ $pt->pavement_type_descr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-layer-group mr-1"></i> SAVE SURFACE
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Surface Type Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="surfaceTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Pavement Type</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($surfaces as $row)
                                <tr class="text-xs">
                                    <td class="font-weight-bold">{{ $row->surface_cd }}</td>
                                    <td>{{ $row->surface_descr }}</td>
                                    <td><span class="badge badge-info">{{ $row->pavement_type_cd }}</span></td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editModal{{ $row->surface_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $row->surface_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-surface-form" method="PUT" action="{{ route('surfaceType.update', $row->surface_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_cd" value="{{ $row->surface_cd }}">
                                                <div class="modal-header bg-light py-2">
                                                    <h6 class="modal-title font-weight-bold">Update Surface Type</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <div class="form-group">
                                                        <label class="text-xs">Surface Code</label>
                                                        <input type="text" name="surface_cd" class="form-control form-control-sm" maxlength="10" value="{{ $row->surface_cd }}" required>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label class="text-xs">Description</label>
                                                        <input type="text" name="surface_descr" class="form-control form-control-sm" value="{{ $row->surface_descr }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Pavement Category</label>
                                                        <select name="pavement_type_cd" class="form-control form-control-sm select2" required>
                                                            @foreach($pavementTypes as $pt)
                                                                <option value="{{ $pt->pavement_type_cd }}" {{ $row->pavement_type_cd == $pt->pavement_type_cd ? 'selected' : '' }}>
                                                                    {{ $pt->pavement_type_descr }}
                                                                </option>
                                                            @endforeach
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
        $('#surfaceTable').DataTable({ responsive: true, order: [[0, "asc"]] });

        // Initialize Select2
        $('select.select2').select2({ width: '100%', placeholder: '-- Select Pavement --', allowClear: true });

        $('#add_surface_type').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('surfaceType.store') }}",$(this).attr('method'));
        });

        $('.update-surface-form').on('submit', function(e) {
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