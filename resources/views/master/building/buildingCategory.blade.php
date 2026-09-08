@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Buildings</li>
                        <li class="breadcrumb-item active">Building Categories</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Building Category</h5>
            <form id="add_building_cat" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Category Code <span class="text-danger">*</span></label>
                        <input type="text" name="building_catg_cd" class="form-control form-control-sm" maxlength="10" placeholder="e.g., RESI" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-xs">Description <span class="text-danger">*</span></label>
                        <input type="text" name="building_catg_descr" class="form-control form-control-sm" placeholder="e.g., Residential Buildings" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus mr-1"></i> ADD CATEGORY
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Building Category List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="buildingCatTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 25%;">Category Code</th>
                                <th>Description</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $cat)
                                <tr class="text-xs">
                                    <td class="font-weight-bold">{{ $cat->building_catg_cd }}</td>
                                    <td>{{ $cat->building_catg_descr }}</td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#editCatModal{{ $cat->building_catg_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editCatModal{{ $cat->building_catg_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-cat-form" method="PUT" action="{{ route('buildingCategory.update', $cat->building_catg_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_building_catg_cd" value="{{ $cat->building_catg_cd }}">
                                                <div class="modal-header bg-light">
                                                    <h6 class="modal-title font-weight-bold">Update Category</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <div class="form-group">
                                                        <label class="text-xs">Category Code</label>
                                                        <input type="text" name="building_catg_cd" class="form-control form-control-sm" maxlength="10" value="{{ $cat->building_catg_cd }}" required>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label class="text-xs">Description</label>
                                                        <input type="text" name="building_catg_descr" class="form-control form-control-sm" value="{{ $cat->building_catg_descr }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success btn-sm">Update</button>
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
        $('#buildingCatTable').DataTable({ "responsive": true });

        $('#add_building_cat').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('buildingCategory.store') }}", $(this).attr('method'));
        });
        $('.update-cat-form').on('submit', function(e) {
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