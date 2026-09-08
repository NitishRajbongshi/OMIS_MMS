@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Administrative</li>
                        <li class="breadcrumb-item active">Manage Blocks</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2">
            <form id="add_block" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-2 col-sm-12">
                        <label class="text-xs">State <span class="star">*</span></label>
                        <select name="state_cd" class="form-control form-control-sm" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->state_code }}">{{ $state->state_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="text-xs">District <span class="star">*</span></label>
                        <select name="district_cd" class="form-control form-control-sm" required>
                            <option value="">Select District</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist->dist_code }}">{{ $dist->dist_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <label class="text-xs">Block Code <span class="star">*</span></label>
                        <input type="text" name="block_cd" class="form-control form-control-sm" placeholder="Block Code" required>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <label class="text-xs">Block Name <span class="star">*</span></label>
                        <input type="text" name="block_name" class="form-control form-control-sm" placeholder="Enter Block Name" required>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <button type="submit" class="btn btn-primary btn-sm rounded-1 fw-bold w-100">
                            <i class="fa fa-plus"></i> Add Block
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0'>
                <div class="card-header rounded-0" style="background-color:rgb(214, 232, 253)">
                    <h4 class="card-title text-sm text-bold text-uppercase">Block Master List</h4>
                </div>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-striped w-100" id="blockTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th style="width: 15%;">Block Code</th>
                                <th>Block Name</th>
                                <th>District</th>
                                <th>State</th>
                                <th class="text-center" style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blocks as $block)
                                <tr class="text-xs">
                                    <td>{{ $block->block_cd }}</td>
                                    <td>{{ $block->block_name }}</td>
                                    <td>{{ $block->dist_name }}</td>
                                    <td>{{ $block->state_name }}</td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-target="#editModal{{ str_replace('/', '_', $block->block_cd) }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ str_replace('/', '_', $block->block_cd) }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="update-block-form" method="PUT" action="{{ route('block.update', $block->block_cd) }}">
                                                @csrf
                                                <input type="hidden" name="block_cd" value="{{ $block->block_cd }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Update Block: {{ $block->block_name }}</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Block Name</label>
                                                        <input type="text" name="block_name" class="form-control" value="{{ $block->block_name }}" required>
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
        $('#blockTable').DataTable();

        $('#add_block').on('submit', function(e) {
            e.preventDefault();
            handleAjax($(this), "{{ route('block.store') }}",$(this).attr('method'));
        });

        $('.update-block-form').on('submit', function(e) {
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