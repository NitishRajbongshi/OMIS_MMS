@extends('layouts.app')

@section('content')
    <div class="content-header mb-1">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">Pending Modification Approval</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid mainBody">
        <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF PROJECTS PENDING MODIFICATION APPROVAL
            </span>
        </h6>

        <div class="container-fluid border py-2">
            <div class="table-responsive">
                <table class="text-xs table table-bordered table-striped rounded-0 user_list"
                    id="new_project_details_table">

                    <thead class="text-white" style="background-color:#417DBE">
                        <tr>
                            <th class="text-center">Sl No.</th>
                            <th class="text-center">Project Code</th>
                            <th class="text-center">Project Name</th>
                            <th class="text-center">Project Type</th>
                            <th class="text-center">Owner Department</th>
                            <th class="text-center">Division</th>
                            <th class="text-center">Sub Division</th>
                            <th class="text-center">Requested On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pendingProjects as $index => $draft)
                            @php
                                $changes = json_decode($draft->changes_applied ?? '{}', true);
                                $projectFields = $changes['project_fields'] ?? [];
                                $selectedWorkItems = $changes['work_item'] ?? [];
                                $others = json_decode($draft->others ?? '{}', true);
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
                                    {{ \Carbon\Carbon::parse($draft->requested_on)->format('d-m-Y') }}
                                </td>

                                <td class="text-center">
                                    <button
                                        class="approveBtn btn btn-outline-primary btn-xs text-xs fw-bold viewProjectBtn"
                                        style="width: 6rem;"
                                        onclick="window.location='{{ route('modification.details', ['request_cd' => $draft->request_id]) }}'">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <!--<tr>
                                <td colspan="10" class="text-center text-muted py-3">
                                    No pending modification approval requests found
                                </td>
                            </tr>-->
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="projectDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title" id="modalTitle">
                        Project Details
                    </h6>
                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="projectModalBody">
                </div>

            </div>
        </div>
    </div>

    <x-success-modal />
    <x-warning-modal />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
@endpush

@push('scripts')
    <script>
        $(function () {
            $('#new_project_details_table').DataTable();
        });
    </script>
@endpush
