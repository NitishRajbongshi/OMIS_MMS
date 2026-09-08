@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Administrative</li>
                        <li class="breadcrumb-item active">Manage Villages</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_village" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">State <span class="star">*</span></label>
                        <select name="state_code" class="form-select form-select-sm" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->state_code }}">{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">District <span class="star">*</span></label>
                        <select name="district_code" class="form-control form-control-sm select2" required>
                            <option value="">Select District</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist->dist_code }}">{{ $dist->dist_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Block <span class="star">*</span></label>
                        <select name="block_code" class="form-control form-control-sm select2" required>
                            <option value="">Select Block</option>
                            @foreach($blocks as $block)
                                <option value="{{ $block->block_cd }}">{{ $block->block_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Village Code <span class="star">*</span></label>
                        <input type="text" name="village_code" class="form-control form-control-sm" placeholder="Village Code" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Village Name <span class="star">*</span></label>
                        <input type="text" name="village_name" class="form-control form-control-sm" placeholder="Enter Village Name" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Census 2011 Code</label>
                        <input type="text" name="census2011_village_code" class="form-control form-control-sm" placeholder="000000">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold w-100">
                            <i class="fa fa-plus"></i> Add Village
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">Village Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="villageTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th>Village Code</th>
                                <th>Village Name</th>
                                <th>Block</th>
                                <th>District</th>
                                <th>Census Code</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($villages as $village)
                                <tr class="text-xs">
                                    <td>{{ $village->village_code }}</td>
                                    <td>{{ $village->village_name }}</td>
                                    <td>{{ $village->block_name }}</td>
                                    <td>{{ $village->district_name }}</td>
                                    <td>{{ $village->census2011_village_code }}</td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#editModal{{ $village->village_code }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $village->village_code }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-village-form" method="PUT" action="{{ route('village.update', $village->village_code) }}">
                                                @csrf
                                                <input type="hidden" name="village_code" value="{{ $village->village_code }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Update Village: {{ $village->village_name }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Village Name</label>
                                                        <input type="text" name="village_name" class="form-control" value="{{ $village->village_name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Census 2011 Code</label>
                                                        <input type="text" name="census2011_village_code" class="form-control" value="{{ $village->census2011_village_code }}">
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
        $('#villageTable').DataTable();

        // Initialize Select2
        $('select.select2').select2({ width: '100%', placeholder: 'Select an option', allowClear: true });

        $('#add_village').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('village.store') }}",$(this).attr('method'));
        });

        $('.update-village-form').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), $(this).attr('action'),$(this).attr('method'));
        });

        function handleAjax(form, url, method) {
            $.ajax({
                type: method,
                url: url,
                data: form.serialize(),
                success: function(res) {
                    Swal.fire({ icon: res.status, title: res.message, timer: 2000 })
                    .then(() => { location.reload(); });
                }
            });
        }
    });
</script>
@endpush