@extends('layouts.app')

@section('title', 'Completed Maintenance Assessments')

@section('content')

    <div class="container-fluid">

        {{-- ============================================================
        PAGE HEADER
        ============================================================= --}}

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h4 class="mb-1">
                    Completed Maintenance Assessments
                </h4>

                <p class="text-muted mb-0">
                    View all completed maintenance assessments.
                </p>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('mmis.maintenance-assessment.index') }}" class="btn btn-sm btn-outline-primary">

                    <i class="fa fa-clipboard-check"></i>
                    Pending Assessments

                </a>

                <a href="{{ route('maintenance.landing') }}" class="btn btn-sm btn-outline-secondary">

                    <i class="fa fa-arrow-left"></i>
                    Maintenance Portal

                </a>

            </div>

        </div>


        {{-- ============================================================
        ERROR
        ============================================================= --}}
        @if(session('error'))

            <div class="alert alert-danger">
                {{ session('error') }}
            </div>

        @endif
        {{-- ============================================================
        SUMMARY
        ============================================================= --}}

        <div class="row mb-3">

            <div class="col-md-3">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Total Completed Assessments
                        </small>

                        <div class="display-6 fw-bold text-success">
                            {{ $assessments->count() }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
        ASSESSMENT TABLE
        ============================================================= --}}

        <div class="card shadow-sm">

            <div class="card-header bg-white fw-bold">

                <i class="fa fa-check-circle text-success me-1"></i>

                Completed Assessments

            </div>


            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Asset ID</th>
                                <th>Asset Type</th>
                                <th>Location</th>
                                <th>Inspection</th>
                                <th>Inspection Date</th>
                                <th class="text-center">
                                    MAS
                                </th>
                                <!-- <th class="text-center">
                                    MPS
                                </th> -->
                                <th>Assessment Date</th>
                                <th class="text-center">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($assessments as $assessment)

                                                <tr>
                                                    {{-- Asset ID --}}
                                                    <td class="fw-semibold">
                                                        {{ $assessment->insp_asset_id }}
                                                    </td>
                                                    {{-- Asset Type --}}
                                                    <td>
                                                        {{ $assessment->asset_type_name ?: $assessment->insp_asset_type }}
                                                    </td>
                                                    {{-- Location --}}
                                                    <td>
                                                        {{ $assessment->insp_asset_location ?: '—' }}
                                                    </td>
                                                    {{-- Inspection --}}
                                                    <td>
                                                        {{ $assessment->insp_cd }}
                                                    </td>
                                                    {{-- Inspection Date --}}
                                                    <td>
                                                        {{
                                $assessment->insp_date
                                ? \Illuminate\Support\Carbon::parse(
                                    $assessment->insp_date
                                )->format('d M Y')
                                : '—'
                                                            }}

                                                    </td>


                                                    {{-- MAS --}}
                                                    <td class="text-center">
                                                        <span class="badge bg-success">
                                                            {{ number_format($assessment->mas_score, 2) }}
                                                        </span>
                                                    </td>


                                                    {{-- MPS --}}
                                                    <!-- <td class="text-center">

                                                            <span class="badge bg-primary">

                                                                {{ number_format(
                                                                    $assessment->mps_score,
                                                                    2
                                                                ) }}

                                                            </span>

                                                        </td> -->


                                                    {{-- Assessment Date --}}
                                                    <td>

                                                        {{
                                $assessment->calculated_at
                                ? \Illuminate\Support\Carbon::parse(
                                    $assessment->calculated_at
                                )->format('d M Y H:i')
                                : '—'
                                                            }}

                                                    </td>


                                                    {{-- Action --}}
                                                    <td class="text-center">

                                                        <a href="{{
                                route(
                                    'mmis.completed-maintenance-assessment.show',
                                    $assessment->assessment_id
                                )
                                                            }}" class="btn btn-sm btn-outline-primary">

                                                            <i class="fa fa-eye"></i>

                                                            View

                                                        </a>

                                                    </td>

                                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center text-muted py-5">

                                        <i class="fa fa-info-circle fa-2x mb-2"></i>

                                        <div>
                                            No completed maintenance assessments found.
                                        </div>

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