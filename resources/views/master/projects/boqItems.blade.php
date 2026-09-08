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
                        <li class="breadcrumb-item active">BOQ Items</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add BOQ Item</h5>
            <form id="add_boq_item_form" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="boq_item_name" class="form-control form-control-sm" maxlength="50" placeholder="e.g., Earthwork in Excavation" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Unit <span class="text-danger">*</span></label>
                        <select name="unit_cd" class="form-control form-control-sm select2" required>
                            <option value="">-- Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->unit_cd }}">{{ $unit->unit_cd }} ({{ $unit->unit_descr }})</option>
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
                            <option value="Y">Published</option>
                            <option value="N">Draft</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-save mr-1"></i> SAVE ITEM
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">BOQ Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="boqTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 8%;">ID</th>
                                <th>Item Name</th>
                                <th style="width: 10%;">Unit</th>
                                <th>Department</th>
                                <th class="text-center" style="width: 10%;">Status</th>
                                <th class="text-center" style="width: 80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($boqItems as $item)
                                <tr class="text-xs">
                                    <td>{{ $item->boq_item_id }}</td>
                                    <td class="font-weight-bold">{{ $item->boq_item_name }}</td>
                                    <td><span class="badge badge-light border">{{ $item->unit_cd }}</span></td>
                                    <td>{{ $item->department_name }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $item->is_published == 'Y' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $item->is_published == 'Y' ? 'Active' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editBOQModal{{ $item->boq_item_id }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editBOQModal{{ $item->boq_item_id }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form class="update-boq-form" method="PUT" action="{{ route('boqItem.update', $item->boq_item_id) }}">
                                                @csrf
                                                <input type="hidden" name="boq_item_id" value="{{ $item->boq_item_id }}">
                                                <div class="modal-header bg-light py-2">
                                                    <h6 class="modal-title font-weight-bold">Update BOQ Item</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12 form-group">
                                                            <label class="text-xs">Item Description</label>
                                                            <input type="text" name="boq_item_name" class="form-control form-control-sm" value="{{ $item->boq_item_name }}" required>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label class="text-xs">Measurement Unit</label>
                                                             <select name="unit_cd" class="form-control form-control-sm select2" required>
                                                                @foreach($units as $unit)
                                                                    <option value="{{ $unit->unit_cd }}" {{ $item->unit_cd == $unit->unit_cd ? 'selected' : '' }}>
                                                                        {{ $unit->unit_cd }} ({{ $unit->unit_descr }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label class="text-xs">Department</label>
                                                             <select name="dept_cd" class="form-control form-control-sm select2" required>
                                                                @foreach($departments as $dept)
                                                                    @if($dept->department_name !== 'Secretariat')
                                                                        <option value="{{ $dept->id }}" {{ $item->dept_cd == $dept->id ? 'selected' : '' }}>
                                                                            {{ $dept->department_name }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label class="text-xs">Status</label>
                                                            <select name="is_published" class="form-select form-select-sm">
                                                                <option value="Y" {{ $item->is_published == 'Y' ? 'selected' : '' }}>Published</option>
                                                                <option value="N" {{ $item->is_published == 'N' ? 'selected' : '' }}>Draft</option>
                                                            </select>
                                                        </div>
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
        $('#boqTable').DataTable({ responsive: true });

        // Initialize Select2 for add form
        $('select.select2').select2({ width: '100%', placeholder: 'Select an option', allowClear: true });

        // Re-initialize Select2 inside modals when they open
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('select.select2').select2({ width: '100%', allowClear: true, dropdownParent: $(this) });
        });

        $('#add_boq_item_form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('boqItem.store') }}",$(this).attr('method'));
        });

        $('.update-boq-form').on('submit', function(e) {
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