@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
    <div class="content-header mb-1">
        <div class="container-fluid">
            <ol class="breadcrumb float-sm-left text-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">Modification Approved Projects</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid mainBody">
        <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF NEW MODIFICATION APPROVED PROJECTS
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
                        @forelse ($projectDetailsNew as $index => $draft)
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
                                        class="btn btn-xs btn-outline-primary fw-bold"
                                        onclick="window.location='{{ route('approved.project.edit', ['project_cd' => $draft->project_cd,'request_id' => $draft->request_id]) }}'">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">
                                    No modification approved project found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF UPGRADATION APPROVED PROJECTS
            </span>
        </h6>
        <div class="container-fluid border py-2">
            <div class="table-responsive">
                <table class="text-xs table table-bordered table-striped rounded-0 user_list"
                    id="upg_project_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Sl No.</th>
                        <th class="text-center">Project Code</th>
                        <th class="text-center">Project Name</th>
                        <th class="text-center">Project Type</th>
                        <th class="text-center">Owner Department</th>
                        <th class="text-center">Division</th>
                        <th class="text-center">Sub Division</th>
                        <th class="text-center">Requested On</th>
                        <th class="text-center">Action</th>
                    </thead>
                    <tbody>
                        @forelse ($projectDetailsUpgrade as $index => $draft)
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
                                        class="btn btn-xs btn-outline-primary fw-bold"
                                          onclick="window.location='{{ route('approved.project.edit', ['project_cd' => $draft->project_cd,'request_id' => $draft->request_id]) }}'">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">
                                    No modification approved project found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
            <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                LIST OF MAINTENANCE APPROVED PROJECTS
            </span>
        </h6>
        <div class="container-fluid border py-2">
            <div class="table-responsive">
                <table class="text-xs table table-bordered table-striped rounded-0 user_list"
                    id="mtn_project_details_table">
                    <thead class="theader text-white" style="background-color:#417DBE">
                        <th class="text-center">Sl No.</th>
                        <th class="text-center">Project Code</th>
                        <th class="text-center">Project Name</th>
                        <th class="text-center">Project Type</th>
                        <th class="text-center">Owner Department</th>
                        <th class="text-center">Division</th>
                        <th class="text-center">Sub Division</th>
                        <th class="text-center">Requested On</th>
                        <th class="text-center">Action</th>
                    </thead>
                    <tbody>
                        @forelse ($projectDetailsMaintenance as $index => $draft)
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
                                        class="btn btn-xs btn-outline-primary fw-bold"
                                         onclick="window.location='{{ route('approved.project.edit', ['project_cd' => $draft->project_cd,'request_id' => $draft->request_id]) }}'">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">
                                    No modification approved project found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
