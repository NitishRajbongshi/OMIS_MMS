@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Roads</li>
                        <li class="breadcrumb-item active">Road Owner Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Road Owner</h5>
            <form id="add_road_owner" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Owner Code <span class="text-danger">*</span></label>
                        <input type="text" name="owner_cd" class="form-control form-control-sm" maxlength="10" placeholder="e.g., OWN001" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Short Code <span class="text-danger">*</span></label>
                        <input type="text" name="owner_short_code" class="form-control form-control-sm" maxlength="10" placeholder="e.g., PWD" required>
                    </div>
                    <div class="col-md-5 mb-2">
                        <label class="text-xs">Owner Name <span class="text-danger">*</span></label>
                        <input type="text" name="owner_name" class="form-control form-control-sm" placeholder="e.g., Public Works Department" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus mr-1"></i> ADD OWNER
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Road Owner List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="roadOwnerTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 15%;">Code</th>
                                <th style="width: 15%;">Short Code</th>
                                <th>Full Name</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($owners as $owner)
                                <tr class="text-xs">
                                    <td class="font-weight-bold">{{ $owner->owner_cd }}</td>
                                    <td>{{ $owner->owner_short_code }}</td>
                                    <td>{{ $owner->owner_name }}</td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editOwnerModal{{ $owner->owner_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editOwnerModal{{ $owner->owner_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-owner-form" method="PUT" action="{{ route('roadOwner.update', $owner->owner_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_owner_cd" value="{{ $owner->owner_cd }}">
                                                <div class="modal-header bg-light">
                                                    <h6 class="modal-title font-weight-bold">Update Road Owner</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <div class="form-group">
                                                        <label class="text-xs">Owner Code</label>
                                                        <input type="text" name="owner_cd" class="form-control form-control-sm" maxlength="10" value="{{ $owner->owner_cd }}" required>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label class="text-xs">Short Code</label>
                                                        <input type="text" name="owner_short_code" class="form-control form-control-sm" maxlength="10" value="{{ $owner->owner_short_code }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-xs">Owner Name</label>
                                                        <input type="text" name="owner_name" class="form-control form-control-sm" value="{{ $owner->owner_name }}" required>
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
        $('#roadOwnerTable').DataTable({ "responsive": true });

        $('#add_road_owner').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('roadOwner.store') }}",$(this).attr('method'));
        });

        $('.update-owner-form').on('submit', function(e) {
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
                        title: res.status ? res.status.toUpperCase() : 'SUCCESS', 
                        text: res.message, 
                        timer: 2000 
                    }).then(() => { location.reload(); });
                }
            });
        }
    });
</script>
@endpush