@extends('layouts.app')
@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

    <div class="content-header mb-1">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">Verified Project List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid mainBody">
        <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF ALL PROJECT DETAILS APPROVED AND FINALIZED UNDER GOVT. OF NAGALAND
            </span>
        </h6>
        <div class="duration-notice mb-3">
            <div class="duration-notice-icon">
                <i class="fas fa-clock"></i>
            </div>

            <div class="duration-notice-content">
                <div class="duration-notice-title">
                    PROJECT DURATION FILTER
                </div>

                <div class="duration-notice-text">
                    Only projects for which <strong>half of the scheduled project duration
                    has elapsed</strong> are displayed in this list, calculated based on
                    the <strong>Project Start Date</strong> and <strong>Project End Date</strong>.
                </div>
            </div>
        </div>
        <div class="container-fluid border py-2">
            <table class="table-responsive text-xs table table-bordered table-striped rounded-0 user_list"
                id="new_project_details_table">
                <thead class="theader text-white" style="background-color:#417DBE">
                    <th class="text-center" style="min-width: 3rem;">Sl No.</th>
                    <th class="text-center" style="min-width: 6rem;">Project Code</th>
                    <th class="text-center" style="min-width: 8rem;">Project Name</th>
                    <th class="text-center" style="min-width: 5rem;">Project Type</th>
                    <th class="text-center" style="min-width: 6rem;">Owner Department</th>
                    <th class="text-center" style="min-width: 6rem;">Division</th>
                    <th class="text-center" style="min-width: 6rem;">Sub Division</th>
                    <th class="text-center" style="min-width: 8rem;">Check Status</th>
                    <th class="text-center" style="min-width: 8rem;">Action</th>
                </thead>
                <tbody>
                    @foreach ($projects as $index => $draft)
                        @php
                            $other = json_decode($draft->others ?? '{}', true);
                            $status = $draft->latest_mod_status;
                            $disableReason = '';
                            $canEdit = true;

                            if (is_null($status)) {
                                $statusText = 'No Request';
                                $statusClass = 'secondary';
                                $canEdit = true;
                            } elseif ($status == 0) {
                                $statusText = 'Pending';
                                $statusClass = 'warning';
                                $canEdit = false;
                                $disableReason = 'Modification request is pending approval';
                            } elseif ($status == 1) {
                                $statusText = 'Approved - In Progress';
                                $statusClass = 'primary';
                                $canEdit = false;
                                $disableReason = 'Modification request has been approved and work is in progress';
                            } elseif ($status == 2) {
                                $statusText = 'Completed';
                                $statusClass = 'success';
                                $canEdit = true;
                            } elseif ($status == 4) {
                                $statusText = 'Rejected';
                                $statusClass = 'danger';
                                $canEdit = true;
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $draft->project_cd }}</td>
                            <td class="text-center">{{ $draft->project_name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $draft->project_type ?? 'N/A' }}</td>
                            <td class="text-center">{{ $draft->owner_department ?? 'N/A' }}</td>
                            <td class="text-center">{{ $draft->division_name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $draft->sub_div_name ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a
                                    href="{{ route('project.modification.history', $draft->project_cd) }}"
                                    class="btn btn-outline-info btn-xs text-xs fw-bold"
                                    title="View modification status">
                                    <i class="fas fa-eye me-1"></i>
                                    View Status
                                </a>
                            </td>
                            <td class="text-center">
                                <div style="margin-bottom: 0.1rem;">

                                    @if(!$canEdit)
                                        <span
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="{{ $disableReason }}"
                                            style="display:inline-block;">
                                            <button
                                                class="approveBtn btn btn-outline-primary btn-xs text-xs fw-bold requestEditBtn"
                                                disabled
                                                style="width: 6rem;"
                                                onclick="window.location='{{ route('approved.project.edit', ['project_cd' => $draft->project_cd]) }}'">
                                                Request for modification
                                            </button>
                                        </span>
                                    @else
                                        <button
                                            class="approveBtn btn btn-outline-primary btn-xs text-xs fw-bold requestEditBtn"
                                            style="width: 6rem;"
                                            onclick="window.location='{{ route('approved.project.edit', ['project_cd' => $draft->project_cd]) }}'">
                                            Request for modification
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <x-success-modal />
    <x-warning-modal />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pms/common/modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <style>
        :root {
            --primary: var(--oamis-primary, #0b6b4a);
            --secondary: color-mix(in srgb, var(--oamis-primary, #0b6b4a) 14%, #ffffff);
            --accent: color-mix(in srgb, var(--oamis-primary, #0b6b4a) 14%, #ffffff);
        }

        .text-rose-primary {
            color: var(--primary) !important;
        }

        .badge-rose-primary {
            background-color: var(--secondary);
            color: var(--primary);
            border: 1px solid color-mix(in srgb, var(--primary) 25%, white);
            font-weight: 600;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
        }
        .duration-notice {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            background: #fff8e1;
            border: 1px solid #f0c36d;
            border-left: 6px solid #f39c12;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .duration-notice-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f39c12;
            color: #fff;
            border-radius: 50%;
            font-size: 19px;
        }

        .duration-notice-content {
            line-height: 1.4;
        }

        .duration-notice-title {
            font-size: 14px;
            font-weight: 700;
            color: #9a5b00;
            margin-bottom: 3px;
            letter-spacing: 0.3px;
        }

        .duration-notice-text {
            font-size: 14px;
            color: #4a4a4a;
        }

        .duration-notice-text strong {
            color: #7a4a00;
            font-weight: 700;
        }
    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script>
        $(function() {
            $("#new_project_details_table").DataTable();
        });
    </script>
@endpush
