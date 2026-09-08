@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Projects</li>
                        <li class="breadcrumb-item active">Funding Agencies</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_agency_form" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-2">
                        <label class="text-xs">Agency Code <span class="star">*</span></label>
                        <input type="text" name="agency_code" class="form-control form-control-sm" placeholder="Code" required>
                    </div>
                    <div class="col-md-4">
                        <label class="text-xs">Agency Name <span class="star">*</span></label>
                        <input type="text" name="agency_name" class="form-control form-control-sm" placeholder="Enter Agency Name" required>
                    </div>
                    <div class="col-md-3">
                        <label class="text-xs">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control form-control-sm" placeholder="Name">
                    </div>
                    <div class="col-md-3">
                        <label class="text-xs">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control form-control-sm" placeholder="email@example.com">
                    </div>
                </div>
                <div class="row mt-2 align-items-end">
                    <div class="col-md-2">
                        <label class="text-xs">Contact Phone</label>
                        <input type="tel" maxlength="10" minlength="10" pattern="[0-9]{10}" name="contact_phone" class="form-control form-control-sm" placeholder="Phone No.">
                    </div>
                    <div class="col-md-5">
                        <label class="text-xs">Address</label>
                        <input type="text" name="address" class="form-control form-control-sm" placeholder="Full Address">
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="hidden" name="is_published" value="N">
                            <input class="custom-control-input" type="checkbox" id="is_published" name="is_published" value="Y" checked>
                            <label for="is_published" class="custom-control-label text-xs">Publish?</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold w-100">
                            <i class="fa fa-plus"></i> Add Agency
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">Funding Agency List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="agencyTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 10%;">Code</th>
                                <th>Agency Name</th>
                                <th style="width: 20%;">Contact info</th>
                                <th style="width: 10%;" class="text-center">Status</th>
                                <th class="text-center" style="width: 8%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agencies as $index => $agency)
                                <tr class="text-xs">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $agency->agency_code }}</td>
                                    <td>{{ $agency->agency_name }}</td>
                                    <td>
                                        <div><strong>{{ $agency->contact_person }}</strong></div>
                                        <div class="text-muted">{{ $agency->contact_email }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $agency->is_published == 'Y' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $agency->is_published == 'Y' ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#editModal{{ $agency->funding_agency_id }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $agency->funding_agency_id }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form class="update-agency-form" method="POST" action="{{ route('fundingAgnecies.update', $agency->funding_agency_id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Edit Agency: {{ $agency->agency_code }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 form-group">
                                                            <label>Agency Name</label>
                                                            <input type="text" name="agency_name" class="form-control" value="{{ $agency->agency_name }}" required>
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label>Contact Person</label>
                                                            <input type="text" name="contact_person" class="form-control" value="{{ $agency->contact_person }}">
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label>Email</label>
                                                            <input type="email" name="contact_email" class="form-control" value="{{ $agency->contact_email }}">
                                                        </div>
                                                        <div class="col-md-6 form-group">
                                                            <label>Phone</label>
                                                            <input type="text" name="contact_phone" class="form-control" value="{{ $agency->contact_phone }}">
                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <label>Address</label>
                                                            <textarea name="address" class="form-control" rows="2">{{ $agency->address }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="hidden" name="is_published" value="N">
                                                        <input class="custom-control-input" type="checkbox" id="edit_pub_{{ $agency->funding_agency_id }}" name="is_published" value="Y" {{ $agency->is_published == 'Y' ? 'checked' : '' }}>
                                                        <label for="edit_pub_{{ $agency->funding_agency_id }}" class="custom-control-label">Is Published</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm">Update Agency</button>
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
        $('#agencyTable').DataTable();

        $('#add_agency_form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('fundingAgnecies.store') }}", "POST");
        });

        $('.update-agency-form').on('submit', function(e) {
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
                }
            });
        }
    });
</script>
@endpush