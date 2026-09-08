@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Master</li>
                        <li class="breadcrumb-item">Mechanical</li>
                        <li class="breadcrumb-item active">Vehicle Makers</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Vehicle Maker</h5>
            <form id="add_maker" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Maker Code <span class="text-danger">*</span></label>
                        <input type="text" name="maker_cd" class="form-control form-control-sm" maxlength="5" placeholder="e.g., TATA" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="text-xs">Maker Name <span class="text-danger">*</span></label>
                        <input type="text" name="maker_name" class="form-control form-control-sm" placeholder="e.g., Tata Motors" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus mr-1"></i> ADD MAKER
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">Vehicle Maker List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="makerTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 25%;">Code</th>
                                <th>Maker Name</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($makers as $maker)
                                <tr class="text-xs">
                                    <td class="font-weight-bold">{{ $maker->maker_cd }}</td>
                                    <td>{{ $maker->maker_name }}</td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editMakerModal{{ $maker->maker_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editMakerModal{{ $maker->maker_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-maker-form" method="PUT" action="{{ route('vehicleMaker.update', $maker->maker_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_maker_cd" value="{{ $maker->maker_cd }}">
                                                <div class="modal-header bg-light">
                                                    <h6 class="modal-title font-weight-bold">Update Maker</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- <div class="form-group">
                                                        <label class="text-xs">Maker Code</label>
                                                        <input type="text" name="maker_cd" class="form-control form-control-sm" maxlength="5" value="{{ $maker->maker_cd }}" required>
                                                    </div> -->
                                                    <div class="form-group">
                                                        <label class="text-xs">Maker Name</label>
                                                        <input type="text" name="maker_name" class="form-control form-control-sm" value="{{ $maker->maker_name }}" required>
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
        $('#makerTable').DataTable({ "responsive": true });

        $('#add_maker').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('vehicleMaker.store') }}", $(this).attr('method'));
        });

        $('.update-maker-form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), $(this).attr('action'), $(this).attr('method'));
        });

        function handleAjax(form, url, method) {
            $.ajax({
                type: method,
                url: url,
                data: form.serialize(),
                success: function(res) {
                    Swal.fire({ icon: res.status, title: res.status.toUpperCase(), text: res.message, timer: 2000 })
                    .then(() => { location.reload(); });
                }
            });
        }
    });
</script>
@endpush