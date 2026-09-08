@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Master</li>
                        <li class="breadcrumb-item">Project</li>
                        <li class="breadcrumb-item active">Items of Work</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Item of Work</h5>
            <form id="add_item_work_form" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="item_name" class="form-control form-control-sm" maxlength="50" placeholder="e.g., PCC for Foundation" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Unit <span class="text-danger">*</span></label>
                        <select name="unit_cd" class="form-control form-control-sm select2" required>
                            <option value="">-- Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->unit_cd }}">{{ $unit->unit_cd }}</option>
                            @endforeach
                        </select>
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
                    <div class="col-md-1 mb-2">
                        <label class="text-xs">Status</label>
                        <select name="is_published" class="form-select form-select-sm">
                            <option value="Y">Active</option>
                            <option value="N">Draft</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus mr-1"></i> SAVE ITEM
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Items of Work Master</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="itemWorkTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 8%;">CD</th>
                                <th>Item Name</th>
                                <th style="width: 10%;">Unit</th>
                                <th>Department</th>
                                <th class="text-center" style="width: 10%;">Status</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr class="text-xs">
                                    <td>{{ $item->item_cd }}</td>
                                    <td class="font-weight-bold">{{ $item->item_name }}</td>
                                    <td>{{ $item->unit_cd }}</td>
                                    <td>{{ $item->department_name }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $item->is_published == 'Y' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $item->is_published == 'Y' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editItemModal{{ $item->item_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editItemModal{{ $item->item_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-item-form" method="PUT" action="{{ route('itemOfWorkMaster.update', $item->item_cd) }}">
                                                @csrf
                                                <input type="hidden" name="item_cd" value="{{ $item->item_cd }}">
                                                <div class="modal-header bg-light py-2">
                                                    <h6 class="modal-title font-weight-bold">Update Item of Work</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="text-xs">Item Name</label>
                                                        <input type="text" name="item_name" class="form-control form-control-sm" value="{{ $item->item_name }}" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 form-group">
                                                            <label class="text-xs">Unit</label>
                                                            <select name="unit_cd" class="form-control form-control-sm select2" required>
                                                                @foreach($units as $unit)
                                                                    <option value="{{ $unit->unit_cd }}" {{ $item->unit_cd == $unit->unit_cd ? 'selected' : '' }}>{{ $unit->unit_cd }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label class="text-xs">Department</label>
                                                            <select name="dept_cd" class="form-control form-control-sm select2" required>
                                                                @foreach($departments as $dept)
                                                                    @if($dept->department_name !== 'Secretariat')
                                                                        <option value="{{ $dept->id }}" {{ $item->dept_cd == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Status</label>
                                                        <select name="is_published" class="form-select form-select-sm">
                                                            <option value="Y" {{ $item->is_published == 'Y' ? 'selected' : '' }}>Active</option>
                                                            <option value="N" {{ $item->is_published == 'N' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer py-1">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success btn-sm">Update Item</button>
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
        $('#itemWorkTable').DataTable({ responsive: true });

        // Initialize Select2
        $('select.select2').select2({ width: '100%', placeholder: 'Select an option', allowClear: true });

        // Re-initialize Select2 inside modals
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('select.select2').select2({ width: '100%', allowClear: true, dropdownParent: $(this) });
        });

        $('#add_item_work_form, .update-item-form').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let btn = form.find('button[type="submit"]');

            $.ajax({
                type: form.attr('id') === 'add_item_work_form' ? 'POST' : 'PUT',
                url: form.attr('id') === 'add_item_work_form' ? "{{ route('itemOfWorkMaster.store') }}" : form.attr('action'),
                data: form.serialize(),
                beforeSend: () => btn.prop('disabled', true),
                success: (res) => {
                    Swal.fire({
                        icon: res.status === 'success' ? 'success' : 'error',
                        title: res.status === 'success' ? 'Saved' : 'Error',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => { if(res.status === 'success') location.reload(); });
                },
                error: () => Swal.fire('Error', 'Internal DB Error', 'error'),
                complete: () => btn.prop('disabled', false)
            });
        });
    });
</script>
@endpush