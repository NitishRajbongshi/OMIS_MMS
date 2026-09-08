@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Administrative</li>
                        <li class="breadcrumb-item active">Sub-Division Master</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">Add Sub-Division</h5>
            <form id="add_sub_div" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Sub-Div Code <span class="text-danger">*</span></label>
                        <input type="text" name="sub_div_cd" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="text-xs">Sub-Division Name <span class="text-danger">*</span></label>
                        <input type="text" name="sub_div_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Department <span class="text-danger">*</span></label>
                        <select name="dept_cd" class="form-control form-control-sm select2" required>
                            <option value="">Select Dept</option>
                            @foreach($departments as $dept)
                                @if($dept->department_name !== 'Secretariat')
                                    <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">District <span class="text-danger">*</span></label>
                        <select name="district_cd" class="form-control form-control-sm select2" required>
                            <option value="">Select District</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist->dist_code }}">{{ $dist->dist_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row bg-light p-2 border mb-2">
                    <div class="col-md-3 mb-2">
                        <label class="text-xs text-primary">Division <span class="text-danger">*</span></label>
                        <select name="div_cd" id="div_cd" class="form-control form-control-sm border-primary select2" required>
                            <option value="">Select Division</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->division_cd }}">{{ $div->division_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Circle (Auto)</label>
                        <input type="text" id="circle_display" class="form-control form-control-sm" readonly placeholder="Select Division first">
                        <input type="hidden" name="circle_cd" id="circle_cd">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">Zone (Auto)</label>
                        <input type="text" id="zone_display" class="form-control form-control-sm" readonly>
                        <input type="hidden" name="zone_cd" id="zone_cd">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="text-xs">State (Auto)</label>
                        <input type="text" id="state_display" class="form-control form-control-sm" readonly>
                        <input type="hidden" name="state_cd" id="state_cd">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                            <i class="fa fa-save mr-1"></i> SAVE SUB-DIVISION
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-4 text-sm">
            <div class='card rounded-0 shadow-sm'>
                <div class='card-body rounded-0'>
                    <table class="table table-bordered table-sm table-striped w-100" id="subDivTable">
                        <thead style="background-color:#e7effc;">
                            <tr>
                                <th>Code</th>
                                <th>Sub-Division</th>
                                <th>Division</th>
                                <th>Circle</th>
                                <th>Zone</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sub_divisions as $sd)
                                <tr class="text-xs">
                                    <td>{{ $sd->sub_div_cd }}</td>
                                    <td>{{ $sd->sub_div_name }}</td>
                                    <td>{{ $sd->division_name }}</td>
                                    <td>{{ $sd->circle_name ?? 'N/A' }}</td>
                                    <td>{{ $sd->zone_name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <button data-toggle="modal" data-target="#editSDModal{{ $sd->sub_div_cd }}" class="btn btn-sm text-warning">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="editSDModal{{ $sd->sub_div_cd }}" tabindex="-1" data-backdrop="static">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form class="update-sd-form" method="PUT" action="{{ route('subdivision.update', $sd->sub_div_cd) }}">
                                                @csrf
                                                <input type="hidden" name="old_sub_div_cd" value="{{ $sd->sub_div_cd }}">
                                                <div class="modal-header bg-light">
                                                    <h6 class="modal-title font-weight-bold">Edit Sub-Division</h6>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <label class="text-xs">Sub-Division Name</label>
                                                            <input type="text" name="sub_div_name" class="form-control form-control-sm" value="{{ $sd->sub_div_name }}" required>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success btn-sm">Update Details</button>
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
        $('#subDivTable').DataTable({ "responsive": true });

        // Initialize Select2
        $('select.select2').select2({ width: '100%', placeholder: 'Select an option', allowClear: true });

        // Logic to fetch Parent Hierarchy when Division changes
        $('#div_cd').on('change', function() {
            let divCd = $(this).val();
            if(divCd) {
                $.ajax({
                    url: "{{ url('/asset-management/get-division-hierarchy') }}/" + divCd,
                    type: "GET",
                    success: function(data) {
                        $('#circle_display').val(data.circle_name);
                        $('#circle_cd').val(data.circle_cd);
                        $('#zone_display').val(data.zone_name);
                        $('#zone_cd').val(data.zone_cd);
                        $('#state_display').val(data.state_name);
                        $('#state_cd').val(data.state_cd);
                    }
                });
            }
        });

        $('#add_sub_div, .update-sd-form').on('submit', function(e) {
        e.preventDefault();
        
        let form = $(this);
        // Determine the URL: If it's the add form, use the Add route, 
        // otherwise take the URL from the form's action attribute (Update route)
        let url = form.attr('id') === 'add_sub_div' ? "{{ route('subdivision.store') }}" : form.attr('action');

        $.ajax({
            type: form.attr('method'),
            url: url,
            data: form.serialize(),
            success: function(res) {
                Swal.fire({ 
                    icon: res.status, 
                    title: res.message, 
                    timer: 1500, 
                    showConfirmButton: false 
                }).then(() => { 
                    if(res.status === 'success') location.reload(); 
                });
            },
            error: function() {
                Swal.fire('Error', 'Something went wrong with the request', 'error');
            }
        });
    });
    });
</script>
@endpush