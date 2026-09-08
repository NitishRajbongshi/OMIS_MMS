@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Projects</li>
                        <li class="breadcrumb-item active">Project Types</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_project_type" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Type Code <span class="star">*</span></label>
                        <input type="text" name="proj_type_cd" class="form-control form-control-sm" maxlength="5" placeholder="e.g. NEW" required>
                    </div>
                    <div class="col-md-5 mb-2">
                        <label class="text-xs">Description <span class="star">*</span></label>
                        <input type="text" name="proj_type_descr" class="form-control form-control-sm" placeholder="e.g. New Construction" required>
                    </div>
                    <div class="col-md-2 mb-2 text-center">
                        <div class="custom-control custom-checkbox mb-2">
                            <input class="custom-control-input" type="checkbox" id="is_published" name="is_published" value="Y" checked>
                            <label for="is_published" class="custom-control-label text-xs">Published</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold w-100">
                            <i class="fa fa-plus"></i> Add Project Type
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">Project Type Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="projTypeTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 15%;">Code</th>
                                <th>Description</th>
                                <th style="width: 15%;" class="text-center">Status</th>
                                <th class="text-center" style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($types as $type)
                                <tr class="text-xs">
                                    <td>{{ $type->proj_type_cd }}</td>
                                    <td>{{ $type->proj_type_descr }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $type->is_published == 'Y' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $type->is_published == 'Y' ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editTypeModal{{ $type->proj_type_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                            <div class="modal fade" id="editTypeModal{{ $type->proj_type_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-type-form" method="PUT" action="{{ route('projectType.update', $type->proj_type_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_proj_type_cd" value="{{ $type->proj_type_cd }}">
                                                
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Edit Project Type: {{ $type->proj_type_cd }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <div class="form-group">
                                                        <label class="text-xs">Type Code (New)</label>
                                                        <input type="text" name="proj_type_cd" class="form-control" value="{{ $type->proj_type_cd }}" maxlength="5" required>
                                                        <small class="text-muted">Warning: Changing the code may affect linked records.</small>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label class="text-xs">Description</label>
                                                        <input type="text" name="proj_type_descr" class="form-control" value="{{ $type->proj_type_descr }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox" id="edit_pub_{{ $type->proj_type_cd }}" name="is_published" value="Y" {{ $type->is_published == 'Y' ? 'checked' : '' }}>
                                                            <label for="edit_pub_{{ $type->proj_type_cd }}" class="custom-control-label">Is Published</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm">Update Records</button>
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
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
        $('#projTypeTable').DataTable();

        $('#add_project_type').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('projectType.store') }}",$(this).attr('method'));
        });

        $('.update-type-form').on('submit', function(e) {
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