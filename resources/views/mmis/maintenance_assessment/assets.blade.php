@extends('layouts.app')

@section('title', 'Maintenance Assessment Assets')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h4 class="mb-1">{{ $assetTypeName }} Assessment</h4><p class="text-muted mb-0">Each row represents the most recent inspection available for an asset.</p></div>
        <a href="{{ route('mmis.maintenance-assessment.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-arrow-left"></i> Asset Types</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- <div class="table-responsive"> -->
                <table class="table table-hover align-middle mb-0" id="pendingInspectionTable">
                    <thead class="table-light">
                        <tr>
                            <th>Asset ID</th>
                            <th>Location</th>
                            <th>Inspection Code</th>
                            <th>Inspection Date</th>
                            <th>Section</th>
                            <th>Overall <br> Condition</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td class="fw-semibold">{{ $asset->insp_asset_id }}</td>
                                <td>{{ $asset->insp_asset_location ?: '—' }}</td>
                                <td>{{ $asset->insp_cd }}</td>
                                <td>{{ $asset->insp_date ? \Illuminate\Support\Carbon::parse($asset->insp_date)->format('d M Y') : '—' }}</td>
                                <td>{{ $asset->start_section ?? '—' }} – {{ $asset->end_section ?? '—' }}</td>
                                <td>{{ $asset->cndtn_overall ?: '—' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('mmis.maintenance-assessment.show', $asset->inspection_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-clipboard-check"></i> Make Assessment
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-5">
                                    <i class="fa fa-check-circle fa-2x text-success mb-2"></i>
                                    <div class="fw-semibold">
                                        No pending maintenance assessments.
                                    </div>
                                    <small>
                                        All latest inspections for this asset type have been assessed.
                                    </small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            <!-- </div> -->
    </div>
</div>
@endsection

@push('styles')
<style>

    /* DataTable wrapper */
    #pendingInspectionTable_wrapper {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }


    /* DataTable top controls */
    #pendingInspectionTable_wrapper .dataTables_length,
    #pendingInspectionTable_wrapper .dataTables_filter {
        padding-top: 0;
        padding-bottom: 15px;
    }


    /* Search box */
    #pendingInspectionTable_wrapper .dataTables_filter input {
        width: 220px;
        max-width: 100%;
        height: 38px;
        margin-left: 8px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        box-sizing: border-box;
    }


    /* Show entries */
    #pendingInspectionTable_wrapper .dataTables_length select {
        width: 70px;
        height: 38px;
        padding: 6px 25px 6px 10px;
        border: 1px solid #ced4da;
        border-radius: 6px;
    }


    /* IMPORTANT:
       Force table to stay inside card */
    #pendingInspectionTable {
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        margin: 0 !important;
    }


    /* Header */
    #pendingInspectionTable thead th {
        white-space: normal !important;
        word-wrap: break-word;
        overflow-wrap: break-word;
        vertical-align: middle;
    }


    /* Body cells */
    #pendingInspectionTable tbody td {
        white-space: normal !important;
        word-wrap: break-word;
        overflow-wrap: break-word;
        vertical-align: middle;
    }


    /* Action column */
    #pendingInspectionTable tbody td:last-child {
        text-align: center;
    }


    /* Keep button inside its column */
    #pendingInspectionTable .btn {
        white-space: nowrap;
        max-width: 100%;
    }


    /* Bottom information */
    #pendingInspectionTable_wrapper .dataTables_info {
        padding-top: 12px;
    }


    /* Pagination */
    #pendingInspectionTable_wrapper .dataTables_paginate {
        padding-top: 8px;
    }


    /* Prevent DataTables from creating horizontal scrolling */
    #pendingInspectionTable_wrapper .dataTables_scroll {
        width: 100% !important;
        overflow: hidden !important;
    }


    #pendingInspectionTable_wrapper .dataTables_scrollBody {
        overflow-x: hidden !important;
    }

</style>
@endpush
@push('scripts')
<script>
    $(document).ready(function () {

        $('#pendingInspectionTable').DataTable({

            order: [
                [3, 'asc']
            ],

            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All']
            ],

            columnDefs: [
                {
                    orderable: false,
                    searchable: false,
                    targets: 6
                }
            ],

            language: {
                search: 'Search inspections:',
                lengthMenu: 'Show _MENU_ inspections',
                info: 'Showing _START_ to _END_ of _TOTAL_ inspections',
                infoEmpty: 'No inspections available',
                zeroRecords: 'No matching inspections found'
            }

        });

    });
</script>
@endpush