@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Buildings</li>
                        <li class="breadcrumb-item active">Building Type Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Building Type</h5>
            <form id="add_building_type" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Type Code <span class="text-danger">*</span></label>
                        <input type="text" name="building_type_cd" class="form-control form-control-sm" maxlength="5" placeholder="OFFIC" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Description <span class="text-danger">*</span></label>
                        <input type="text" name="building_type_descr" class="form-control form-control-sm" placeholder="Office Building" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Building Class <span class="text-danger">*</span></label>
                        <select name="building_class_cd" class="form-select form-select-sm" required>
                            <option value="">Select Class</option>
                            @foreach($buildingClasses as $class)
                                <option value="{{ $class->building_class_cd }}">{{ $class->building_class_descr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus mr-1"></i> ADD BUILDING TYPE
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Building Type Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="buildingTypeTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 15%;">Type Code</th>
                                <th>Description</th>
                                <th>Linked Class</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buildingTypes as $type)
                                <tr class="text-xs">
                                    <td class="font-weight-bold text-primary">{{ $type->building_type_cd }}</td>
                                    <td>{{ $type->building_type_descr }}</td>
                                    <td>{{ $type->building_class_descr }}</td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editTypeModal{{ $type->building_type_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editTypeModal{{ $type->building_type_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-type-form" method="PUT" action="{{ route('buildingTypes.update', $type->building_type_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_building_type_cd" value="{{ $type->building_type_cd }}">
                                                <div class="modal-header bg-light">
                                                    <h6 class="modal-title font-weight-bold">Update Building Type</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <!-- <div class="col-md-12 mb-2">
                                                            <label class="text-xs">Type Code</label>
                                                            <input type="text" name="building_type_cd" class="form-control form-control-sm" value="{{ $type->building_type_cd }}" required>
                                                        </div>   -->
                                                        <div class="col-md-12 mb-2">
                                                            <label class="text-xs">Description</label>
                                                            <input type="text" name="building_type_descr" class="form-control form-control-sm" value="{{ $type->building_type_descr }}" required>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label class="text-xs">Building Class</label>
                                                            <select name="building_class_cd" class="form-select form-select-sm" required>
                                                                @foreach($buildingClasses as $class)
                                                                    <option value="{{ $class->building_class_cd }}" {{ $type->building_class_cd == $class->building_class_cd ? 'selected' : '' }}>
                                                                        {{ $class->building_class_descr }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
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
        $('#buildingTypeTable').DataTable({ "responsive": true });

        $('#add_building_type').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('buildingTypes.store') }}", $(this).attr('method'));
        });
        $('.update-type-form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), $(this).attr('action'), $(this).attr('method'));
        });
        function handleAjax(form, url, method) {
            $.ajax({
                type: method, url: url, data: form.serialize(),
                success: function(res) {
                    Swal.fire({ icon: res.status, title: res.status.toUpperCase(), text: res.message, timer: 2000 })
                    .then(() => { location.reload(); });
                }
            });
        }
    });
</script>
@endpush