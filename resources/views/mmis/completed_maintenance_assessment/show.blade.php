@extends('layouts.app')
@section('title', 'Completed Maintenance Assessment')
@section('content')
    <div class="container-fluid">
        {{-- ============================================================
        HEADER
        ============================================================= --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1">
                    Completed Maintenance Assessment
                </h4>
                <p class="text-muted mb-0">
                    {{ $assessmentDetail->insp_asset_id }}
                </p>
            </div>

            <a href="{{route('mmis.completed-maintenance-assessment.index')}}" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-arrow-left"></i>
                Completed Assessments
            </a>
        </div>
        {{-- ============================================================
        ASSET INFORMATION
        ============================================================= --}}
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <small class="text-muted">
                            Asset
                        </small>
                        <div class="fw-bold">
                            {{ $assessmentDetail->asset_type_name ?: $assessmentDetail->insp_asset_type }}
                        </div>
                        <div>
                            {{ $assessmentDetail->insp_asset_id }}
                        </div>
                        <small>
                            {{ $assessmentDetail->insp_asset_location ?: '—' }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <small class="text-muted">
                            Inspection
                        </small>
                        <div class="fw-bold">
                            {{ $assessmentDetail->insp_cd }}
                        </div>
                        <div>
                            {{ $assessmentDetail->insp_date ? \Illuminate\Support\Carbon::parse($assessmentDetail->insp_date)->format('d M Y') : '—' }}
                        </div>
                        <small>
                            Section:
                            {{ $assessmentDetail->start_section ?? '—' }}
                            –
                            {{ $assessmentDetail->end_section ?? '—' }}
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <small class="text-muted">
                            Assessment Completed
                        </small>
                        <div class="fw-bold text-success">
                            {{ $assessmentDetail->calculated_at ? \Illuminate\Support\Carbon::parse($assessmentDetail->calculated_at)->format('d M Y H:i') : '—' }}
                        </div>
                        <small>
                            Calculation Version: {{ $assessmentDetail->calculation_version }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        {{-- ============================================================
        SCORE SUMMARY
        ============================================================= --}}
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">
                            MAS
                        </small>
                        <div class="display-6 fw-bold text-success">
                            {{number_format($assessmentDetail->mas_score, 2)}}
                        </div>
                        <small>
                            Maintenance Assessment Score
                        </small>
                    </div>
                </div>
            </div>


            <!-- <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">
                            Condition Score
                        </small>
                        <div class="display-6 fw-bold text-primary">
                            {{number_format($assessmentDetail->condition_score,2)}}
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">
                            Criticality Score
                        </small>
                        <div class="display-6 fw-bold text-warning">
                            {{ number_format($assessmentDetail->criticality_score,2)}}
                        </div>
                    </div>
                </div>
            </div> -->


            <div class="col-md-3">

                <!-- <div class="card shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            MPS
                        </small>

                        <div class="display-6 fw-bold text-danger">
                            {{ number_format($assessmentDetail->mps_score,2) }}
                        </div>
                        <small>
                            Maintenance Priority Score
                        </small>
                    </div>
                </div> -->
            </div>
        </div>
        {{-- ============================================================
        PARAMETER DETAILS
        ============================================================= --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fa fa-list-alt text-primary me-1"></i>
                Assessment Parameter Details
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Parameter</th>
                                <th>Category</th>
                                <th class="text-center">
                                    Value
                                </th>
                                <th class="text-center">
                                    Maximum Range
                                </th>
                                <th class="text-center">
                                    Weightage
                                </th>
                                <th class="text-center">
                                    Normalized Score
                                </th>
                                <th class="text-center">
                                    Weighted Score
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parameterDetails as $detail)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $detail->parameter_name }}
                                        </strong>
                                        <div class="small text-muted">
                                            {{ $detail->parameter_code }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $detail->score_category }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->parameter_value }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->maximum_range }}
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->weightage }}
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($detail->normalized_score, 2) }}
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($detail->weighted_score, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No parameter details available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection