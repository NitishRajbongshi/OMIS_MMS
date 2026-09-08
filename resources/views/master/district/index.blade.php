@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Administrative</li>
                        <li class="breadcrumb-item active">LGD District Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_district" method="post">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3 col-sm-12">
                        <label>State <span class="star">*</span></label>
                        <select name="state_code" class="form-select form-select-sm" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->state_code }}">{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <label>District Code <span class="star">*</span></label>
                        <input type="text" name="dist_code" class="form-control form-control-sm" placeholder="Code" required>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label>District Name <span class="star">*</span></label>
                        <input type="text" name="dist_name" class="form-control form-control-sm" placeholder="Enter District Name" required>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <label>Short Code</label>
                        <input type="text" name="dist_short_code" class="form-control form-control-sm" maxlength="5">
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold">
                            <i class="fa fa-plus"></i> Add District
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">LGD District List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="districtTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th>LGD Code</th>
                                <th>District Name</th>
                                <th>State Code</th>
                                <th>Short Code</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($districts as $row)
                                <tr class="text-xs">
                                    <td>{{ $row->dist_code }}</td>
                                    <td>{{ $row->dist_name }}</td>
                                    <td>{{ $row->state_code }}</td>
                                    <td>{{ $row->dist_short_code }}</td>
                                    <td class="text-center">
                                        @if (session('updated') == 1)
                                            <a data-toggle="modal" data-target="#editModal{{ $row->dist_code }}" class="btn btn-sm text-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @else
                                            <i class="fas fa-ban text-danger"></i>
                                        @endif
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $row->dist_code }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-form" method="PUT" action="{{ route('districts.update',$row->dist_code) }}">
                                                @csrf
                                                <input type="hidden" name="dist_code" value="{{ $row->dist_code }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Edit District: {{ $row->dist_code }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>District Name</label>
                                                        <input type="text" name="dist_name" class="form-control" value="{{ $row->dist_name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Short Code</label>
                                                        <input type="text" name="dist_short_code" class="form-control" value="{{ $row->dist_short_code }}">
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
        $('#districtTable').DataTable();

        // Add AJAX
        $('#add_district').on('submit', function(e) {
            e.preventDefault();
            submitAjax($(this), "{{ route('districts.store') }}",$(this).attr('method'));
        });

        // Update AJAX
        $('.update-form').on('submit', function(e) {
            e.preventDefault();
            submitAjax($(this), $(this).attr('action'), $(this).attr('method'));
        });

        function submitAjax(form, url, method) {
            $.ajax({
                type: method,
                url: url,
                data: form.serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: response.status,
                        title: response.status.toUpperCase(),
                        text: response.message,
                        timer: 2000
                    }).then(() => { location.reload(); });
                }
            });
        }
    });
</script>
@endpush