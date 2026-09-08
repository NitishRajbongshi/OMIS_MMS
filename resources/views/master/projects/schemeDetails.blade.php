@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Projects</li>
                        <li class="breadcrumb-item active">Scheme Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_scheme_form" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-2">
                        <label class="text-xs">Scheme Code <span class="star">*</span></label>
                        <input type="text" name="scheme_code" class="form-control form-control-sm" placeholder="Code" required>
                    </div>
                    <div class="col-md-4">
                        <label class="text-xs">Scheme Name <span class="star">*</span></label>
                        <input type="text" name="scheme_name" class="form-control form-control-sm" placeholder="Enter Scheme Name" required>
                    </div>
                    <div class="col-md-2">
                        <label class="text-xs">Start Date</label>
                        <input type="date" name="start_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="text-xs">End Date</label>
                        <input type="date" name="end_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="text-xs">Total Budget</label>
                        <input type="number" step="0.01" name="total_budget" class="form-control form-control-sm" placeholder="0.00">
                    </div>
                </div>
                <div class="row mt-2 align-items-end">
                    <div class="col-md-7">
                        <label class="text-xs">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="1" placeholder="Brief description..."></textarea>
                    </div>
                    <!-- <div class="col-md-2 text-center">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="hidden" name="is_published" value="N">
                            <input class="custom-control-input" type="checkbox" id="is_published" name="is_published" value="Y" checked>
                            <label for="is_published" class="custom-control-label text-xs">Publish?</label>
                        </div>
                    </div> -->
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold w-100">
                            <i class="fa fa-save"></i> Save Scheme
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">Scheme Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="schemeTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 10%;">Code</th>
                                <th>Scheme Name</th>
                                <th style="width: 15%;">Budget</th>
								<th style="width: 15%;">Estimated Start Date</th>
                                <th style="width: 15%;">Estimated End Date</th>
                                <th style="width: 10%;" class="text-center">Status</th>
                                <th class="text-center" style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schemes as $index => $scheme)
                                <tr class="text-xs">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $scheme->scheme_code }}</td>
                                    <td>{{ $scheme->scheme_name }}</td>
                                    <td>{{ number_format($scheme->total_budget, 2) }}</td>
									<td>{{ date('d-m-Y', strtotime($scheme->start_date)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($scheme->end_date)) }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $scheme->is_published == 'Y' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $scheme->is_published == 'Y' ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#editModal{{ $scheme->scheme_id }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $scheme->scheme_id }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form class="update-scheme-form" method="POST" action="{{ route('scheme.update', $scheme->scheme_id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Edit Scheme: {{ $scheme->scheme_code }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 form-group">
                                                            <label>Scheme Name</label>
                                                            <input type="text" name="scheme_name" class="form-control" value="{{ $scheme->scheme_name }}" required>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label>Total Budget</label>
                                                            <input type="number" step="0.01" name="total_budget" class="form-control" value="{{ $scheme->total_budget }}">
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <label>Description</label>
                                                            <textarea name="description" class="form-control" rows="2">{{ $scheme->description }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="hidden" name="is_published" value="N">
                                                        <input class="custom-control-input" type="checkbox" id="edit_pub_{{ $scheme->scheme_id }}" name="is_published" value="Y" {{ $scheme->is_published == 'Y' ? 'checked' : '' }}>
                                                        <label for="edit_pub_{{ $scheme->scheme_id }}" class="custom-control-label">Is Published</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm">Update Scheme</button>
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
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
        $('#schemeTable').DataTable();

        $('#add_scheme_form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('scheme.store') }}", "POST");
        });

        $('.update-scheme-form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), $(this).attr('action'), "POST");
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
                    }).then(() => { if(res.status === 'success') location.reload(); });
                },
                error: function(err) {
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            });
        }
    });
</script>
@endpush