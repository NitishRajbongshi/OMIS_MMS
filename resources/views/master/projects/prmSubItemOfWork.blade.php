@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Projects</li>
                        <li class="breadcrumb-item active">Sub-Items of Work</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_sub_item" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Department <span class="star">*</span></label>
                        <select name="dept_cd" class="form-control form-control-sm" required>
                            <option value="">Select Dept</option>
                            @foreach($departments as $dept)
                                @if($dept->department_name !== 'Secretariat')
                                    <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Main Item of Work <span class="star">*</span></label>
                        <select name="item_cd" class="form-control form-control-sm" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->item_cd }}">{{ $item->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Sub-Item Name <span class="star">*</span></label>
                        <input type="text" name="sub_item_name" class="form-control form-control-sm" placeholder="Enter Sub-Item Name" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Published</label>
                        <select name="is_published" class="form-control form-control-sm">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold">
                            <i class="fa fa-plus"></i> Add Sub-Item
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">Sub-Item List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="subItemTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th>ID</th>
                                <th>Sub-Item Name</th>
                                <th>Main Item</th>
                                <th>Department</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subItems as $sub)
                                <tr class="text-xs">
                                    <td>{{ $sub->sub_item_cd }}</td>
                                    <td>{{ $sub->sub_item_name }}</td>
                                    <td>{{ $sub->item_name }}</td>
                                    <td>{{ $sub->department_name }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $sub->is_published == 'Y' ? 'badge-success' : 'badge-danger' }}">
                                            {{ $sub->is_published == 'Y' ? 'Published' : 'Unpublished' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editSubModal{{ $sub->sub_item_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editSubModal{{ $sub->sub_item_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-sub-form" method="PUT" action="{{ route('subItemOfWork.update', $sub->sub_item_cd) }}">
                                                @csrf
                                                <input type="hidden" name="sub_item_cd" value="{{ $sub->sub_item_cd }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Edit Sub-Item</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Department</label>
                                                        <select name="dept_cd" class="form-control">
                                                            @foreach($departments as $dept)
                                                                @if($dept->department_name !== 'Secretariat')
                                                                    <option value="{{ $dept->id }}" {{ $sub->dept_cd == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Main Item</label>
                                                        <select name="item_cd" class="form-control">
                                                            @foreach($items as $it)
                                                                <option value="{{ $it->item_cd }}" {{ $sub->item_cd == $it->item_cd ? 'selected' : '' }}>{{ $it->item_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Sub-Item Name</label>
                                                        <input type="text" name="sub_item_name" class="form-control" value="{{ $sub->sub_item_name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select name="is_published" class="form-control">
                                                            <option value="Y" {{ $sub->is_published == 'Y' ? 'selected' : '' }}>Published</option>
                                                            <option value="N" {{ $sub->is_published == 'N' ? 'selected' : '' }}>Unpublished</option>
                                                        </select>
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
        $('#subItemTable').DataTable();

        $('#add_sub_item').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('subItemOfWork.store') }}",$(this).attr('method'));
        });

        $('.update-sub-form').on('submit', function(e) {
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