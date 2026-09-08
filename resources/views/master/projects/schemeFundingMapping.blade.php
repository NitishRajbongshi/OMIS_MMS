@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Projects</li>
                        <li class="breadcrumb-item active">Scheme Funding Mapping</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_mapping_form" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="text-xs">Select Scheme <span class="star">*</span></label>
                        <select name="scheme_id" class="form-control form-control-sm select2" required>
                            <option value="">-- Choose Scheme --</option>
                            @foreach($schemes as $s)
                                <option value="{{ $s->scheme_id }}">{{ $s->scheme_name }} ({{ $s->scheme_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="text-xs">Funding Agency <span class="star">*</span></label>
                        <select name="funding_agency_id" class="form-control form-control-sm select2" required>
                            <option value="">-- Choose Agency --</option>
                            @foreach($agencies as $a)
                                <option value="{{ $a->funding_agency_id }}">{{ $a->agency_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-md-2">
                        <label class="text-xs">Amount</label>
                        <input type="number" step="0.01" name="funding_amount" class="form-control form-control-sm" placeholder="0.00">
                    </div> --}}
                    <div class="col-md-2">
                        <label class="text-xs">Percentage (%)</label>
                        <input type="number" step="0.01" name="funding_percentage" class="form-control form-control-sm" placeholder="0.00">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm fw-bold w-100">
                            <i class="fa fa-link"></i> Map Agency
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">Funding Allocation List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="mappingTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th>Scheme</th>
                                <th>Funding Agency</th>
                                {{-- <th class="text-right">Amount</th> --}}
                                <th class="text-center">Share (%)</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mappings as $map)
                                <tr class="text-xs">
                                    <td>{{ $map->scheme_name }}</td>
                                    <td>{{ $map->agency_name }}</td>
                                    {{-- <td class="text-right">{{ number_format($map->funding_amount, 2) }}</td> --}}
                                    <td class="text-center">{{ $map->funding_percentage }}%</td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#editMap{{ $map->id }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editMap{{ $map->id }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-mapping-form" method="POST" action="{{ route('mapping.update', $map->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Edit Allocation</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-muted text-xs">Scheme: <strong>{{ $map->scheme_name }}</strong><br>Agency: <strong>{{ $map->agency_name }}</strong></p>
                                                    {{-- <div class="form-group">
                                                        <label>Funding Amount</label>
                                                        <input type="number" step="0.01" name="funding_amount" class="form-control" value="{{ $map->funding_amount }}">
                                                    </div> --}}
                                                    <div class="form-group">
                                                        <label>Funding Percentage</label>
                                                        <input type="number" step="0.01" name="funding_percentage" class="form-control" value="{{ $map->funding_percentage }}">
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
        $('#mappingTable').DataTable();

        $('#add_mapping_form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('mapping.store') }}", "POST");
        });

        $('.update-mapping-form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), $(this).attr('action'), "POST");
        });

        function handleAjax(form, url, method) {
            $.ajax({
                type: method,
                url: url,
                data: form.serialize(),
                success: function(res) {
                    Swal.fire({ icon: res.status, title: res.status.toUpperCase(), text: res.message, timer: 2000 })
                    .then(() => { if(res.status === 'success') location.reload(); });
                }
            });
        }
    });
</script>
@endpush