@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Projects</li>
                        <li class="breadcrumb-item active">Item Units</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Measurement Unit</h5>
            <form id="add_unit_form" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Unit Code <span class="text-danger">*</span></label>
                        <input type="text" name="unit_cd" class="form-control form-control-sm" maxlength="30" placeholder="e.g., CUM" required>
                    </div>
                    <div class="col-md-5 mb-2">
                        <label class="text-xs">Description <span class="text-danger">*</span></label>
                        <input type="text" name="unit_descr" class="form-control form-control-sm" placeholder="e.g., Cubic Meter" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Published</label>
                        <select name="is_published" class="form-control form-control-sm">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus mr-1"></i> SAVE UNIT
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Measurement Units Master</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="unitsTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 20%;">Unit Code</th>
                                <th>Description</th>
                                <th class="text-center" style="width: 15%;">Published</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                                <tr class="text-xs">
                                    <td class="font-weight-bold">{{ $unit->unit_cd }}</td>
                                    <td>{{ $unit->unit_descr }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $unit->is_published == 'Y' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $unit->is_published == 'Y' ? 'PUBLISHED' : 'DRAFT' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editUnitModal{{ Str::slug($unit->unit_cd) }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editUnitModal{{ Str::slug($unit->unit_cd) }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-unit-form" method="PUT" action="{{ route('itemUnit.update', $unit->unit_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_cd" value="{{ $unit->unit_cd }}">
                                                <div class="modal-header bg-light py-2">
                                                    <h6 class="modal-title font-weight-bold">Update Unit Details</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <div class="form-group">
                                                        <label class="text-xs">Unit Code</label>
                                                        <input type="text" name="unit_cd" class="form-control form-control-sm" maxlength="30" value="{{ $unit->unit_cd }}" required>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label class="text-xs">Description</label>
                                                        <input type="text" name="unit_descr" class="form-control form-control-sm" value="{{ $unit->unit_descr }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Published Status</label>
                                                        <select name="is_published" class="form-control form-control-sm">
                                                            <option value="Y" {{ $unit->is_published == 'Y' ? 'selected' : '' }}>Yes</option>
                                                            <option value="N" {{ $unit->is_published == 'N' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer py-1">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success btn-sm">Update Unit</button>
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
        $('#unitsTable').DataTable({ responsive: true, order: [[0, "asc"]] });

        $('#add_unit_form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('itemUnit.store') }}",$(this).attr('method'));
        });

        $('.update-unit-form').on('submit', function(e) {
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