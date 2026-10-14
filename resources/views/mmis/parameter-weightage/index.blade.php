@extends('layouts.app')

@section('title', 'Maintenance Parameter Weightage')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Maintenance Parameter Weightage</h4>
        </div>
        <a href="{{ route('maintenance.landing') }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-arrow-left"></i> Maintenance Portal</a>
    </div>

    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="department_id" class="form-label">
                        Department
                    </label>

                    <select name="department_id" id="department_id" class="form-select">
                        <option value="">Select Department</option>

                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="asset_id" class="form-label">
                        Asset
                    </label>
                    <select name="asset_id" id="asset_id" class="form-select">
                        <option value="">Select Asset</option>
                        {{-- Dynamic content --}}
                    </select>
                </div>
            </div>
            <div id="parameter_section" class="mt-4" style="display: none;">
                <h5 class="mb-3">Parameters</h5>
                <div id="weightage_message" class="mt-3" style="display: none;"></div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th width="70%">Parameter</th>
                                <th width="30%">Weightage</th>
                            </tr>
                        </thead>
                        <tbody id="parameter_table_body">
                        </tbody>
                    </table>
                </div>

                <div id="hist_remark_section" class="mt-3" style="display: none;">
                    <label for="hist_remark" class="form-label">
                        Reason for Changes
                    </label>
                    <textarea
                        name="hist_remark"
                        id="hist_remark"
                        class="form-control"
                        rows="2"
                        maxlength="255"
                        placeholder="Enter reason"
                    ></textarea>
                </div>
                <div class="mt-3 text-end">
                    <button type="button" id="save_weightage" class="btn btn-primary" disabled>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/mmis/paramweightagescript.js') }}" defer></script>
@endpush
