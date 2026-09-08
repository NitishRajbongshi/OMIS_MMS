@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Projects</li>
                        <li class="breadcrumb-item active">Contractor Categories</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_contractor_cat" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2 col-sm-12">
                        <label class="text-xs">Category Code <span class="star">*</span></label>
                        <input type="text" name="category_cd" class="form-control form-control-sm" maxlength="5" placeholder="Code" required>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label class="text-xs">Category Description <span class="star">*</span></label>
                        <input type="text" name="category_descr" class="form-control form-control-sm" placeholder="Enter Description" required>
                    </div>
                    <div class="col-md-2 col-sm-12 text-center">
                        <div class="custom-control custom-checkbox mb-2">
                            <input class="custom-control-input" type="checkbox" id="is_published" name="is_published" value="Y" checked>
                            <label for="is_published" class="custom-control-label text-xs">Publish?</label>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold w-100">
                            <i class="fa fa-plus"></i> Add Category
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">Contractor Category List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="contractorCatTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 15%;">SI No.</th>
                                <th>Description</th>
                                <th style="width: 15%;" class="text-center">Published</th>
                                <th style="width: 20%;">Last Updated</th>
                                <th class="text-center" style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as  $index => $cat)
                                <tr class="text-xs">
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $cat->category_descr }}</td>
                                    <td class="text-center">
                                        @if($cat->is_published == 'Y')
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $cat->updated_at ? date('d-m-Y H:i', strtotime($cat->updated_at)) : 'N/A' }}</td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#editModal{{ $cat->category_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $cat->category_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-cat-form" method="PUT" action="{{ route('contractorCategory.update', $cat->category_cd) }}">
                                                @csrf
                                                <input type="hidden" name="category_cd" value="{{ $cat->category_cd }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Edit Category: {{ $cat->category_cd }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <input type="text" name="category_descr" class="form-control" value="{{ $cat->category_descr }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox" id="edit_pub_{{ $cat->category_cd }}" name="is_published" value="Y" {{ $cat->is_published == 'Y' ? 'checked' : '' }}>
                                                            <label for="edit_pub_{{ $cat->category_cd }}" class="custom-control-label">Is Published</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
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
        $('#contractorCatTable').DataTable();

        $('#add_contractor_cat').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('contractorCategory.store') }}",$(this).attr('method'));
        });

        $('.update-cat-form').on('submit', function(e) {
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