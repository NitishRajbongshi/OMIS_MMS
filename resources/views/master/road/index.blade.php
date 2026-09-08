@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Roads</li>
                        <li class="breadcrumb-item active">Road Category Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_road_category" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3 col-sm-12">
                        <label class="text-xs">Category Code <span class="star">*</span></label>
                        <input type="text" name="rd_catg_cd" class="form-control form-control-sm" placeholder="e.g. SH, MDR" required>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <label class="text-xs">Category Description <span class="star">*</span></label>
                        <input type="text" name="rd_catg_descr" class="form-control form-control-sm" placeholder="e.g. State Highway" required>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <label class="text-xs">Short Code</label>
                        <input type="text" name="rd_catg_short_code" class="form-control form-control-sm" maxlength="5">
                    </div>
                    <div class="col-md-2 col-sm-12">
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
                    <h4 class="card-title text-sm text-bold text-uppercase">Road Category List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="roadCatTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 20%;">Code</th>
                                <th>Description</th>
                                <th style="width: 20%;">Short Code</th>
                                <th class="text-center" style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $cat)
                                <tr class="text-xs">
                                    <td>{{ $cat->rd_catg_cd }}</td>
                                    <td>{{ $cat->rd_catg_descr }}</td>
                                    <td>{{ $cat->rd_catg_short_code }}</td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#editModal{{ $cat->rd_catg_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $cat->rd_catg_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-cat-form" method="PUT" action="{{ route('roadCategory.update', $cat->rd_catg_cd) }}">
                                                @csrf
                                                <input type="hidden" name="rd_catg_cd" value="{{ $cat->rd_catg_cd }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Update Category: {{ $cat->rd_catg_cd }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        <input type="text" name="rd_catg_descr" class="form-control" value="{{ $cat->rd_catg_descr }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Short Code</label>
                                                        <input type="text" name="rd_catg_short_code" class="form-control" value="{{ $cat->rd_catg_short_code }}" maxlength="5">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
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
        $('#roadCatTable').DataTable();

        $('#add_road_category').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('roadCategory.store') }}",$(this).attr('method'));
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