@extends('layouts.app')

@section('content')

<div class="d-flex flex-column" style="min-height: 100vh;">

    <div>

        <div class="content-header mb-1">
            <div class="container-fluid">
                <ol class="breadcrumb float-sm-left text-sm">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('project.request.list') }}">
                            Request for Modification
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Modification History
                    </li>
                </ol>
            </div>
        </div>

        <div class="container mt-4">

            <h4 class="mb-3 text-primary">
                Modification History : {{ $project->project_cd }}
            </h4>

            <div class="card mb-3">
                <div class="card-body">
                    <b>Project :</b> {{ $project->project_name }}
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="project_history_table" class="table table-bordered table-striped text-sm mb-0">

                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Requested On</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                    <th>Approved By</th>
                                    <th>Approved On</th>
                                    <th>Reject Reason</th>
                                    <th>Rejected By</th>
                                    <th>Rejected On</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($history as $key => $row)

                                    <tr>

                                        <td>{{ $key + 1 }}</td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A') }}
                                        </td>

                                        <td>{{ $row->reason }}</td>

                                        <td>

                                            @if($row->status_cd == 0)

                                                <span class="badge bg-warning">
                                                    Pending
                                                </span>

                                            @elseif($row->status_cd == 1)

                                                <span class="badge bg-primary">
                                                    Approved - In Progress
                                                </span>

                                            @elseif($row->status_cd == 2)

                                                <span class="badge bg-success">
                                                    Completed
                                                </span>

                                            @elseif($row->status_cd == 4)

                                                <span class="badge bg-danger">
                                                    Rejected
                                                </span>

                                            @endif

                                            @if($key == 0)

                                                <span class="badge bg-dark ms-1">
                                                    Latest
                                                </span>

                                            @endif

                                        </td>

                                        <td>{{ $row->remarks ?? 'NA' }}</td>

                                        <td>{{ $row->approved_by_name ?? 'NA' }}</td>

                                        <td>
                                            {{ $row->approved_on
                                                ? \Carbon\Carbon::parse($row->approved_on)->format('d-m-Y h:i A')
                                                : 'NA' }}
                                        </td>

                                        <td>{{ $row->reject_reason ?? 'NA' }}</td>

                                        <td>{{ $row->rejected_by_name ?? 'NA' }}</td>

                                        <td>
                                            {{ $row->rejected_on
                                                ? \Carbon\Carbon::parse($row->rejected_on)->format('d-m-Y h:i A')
                                                : 'NA' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="10" class="text-center text-danger py-5">
                                            No modification history found
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <div class="mt-auto"></div>

</div>

@endsection

@push('scripts')
<script>
    $(function() {
        $("#project_history_table").DataTable();
    });
</script>
@endpush
