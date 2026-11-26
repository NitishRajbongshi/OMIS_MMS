@extends('layouts.app')

@section('content')
    <main class="maintenance-dashboard">

        {{-- Page heading --}}
        <section class="mmis-hero">
            <div>
                <span class="mmis-eyebrow">
                    MAINTENANCE MANAGEMENT INFORMATION SYSTEM
                </span>

                <h1>{{ $pageTitle }}</h1>

                <p>
                    View and manage inspection records based on their assessment status.
                </p>
            </div>

            <a class="mmis-parent-link" href="{{ route('maintenance.landing') }}">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Dashboard</span>
            </a>
        </section>

        {{-- Inspection table --}}
        <section class="mmis-panel mmis-recent-panel">

            <div class="mmis-panel-heading">
                <div>
                    <h2>{{ $pageTitle }}</h2>

                    <p>
                        Total records:
                        <strong>{{ number_format($inspections->count()) }}</strong>
                    </p>
                </div>

                <span class="mmis-status {{ $status === 'assessed' ? 'complete' : 'pending' }}">
                    {{ ucfirst($status) }}
                </span>
            </div>

            <div class="mmis-table-wrap">

                <table id="inspectionStatusTable" class="mmis-table">

                    <thead>
                        <tr>
                            <th>Inspection ID</th>
                            <th>Inspection Code</th>
                            <th>Inspection Date</th>
                            <th>Asset Type</th>
                            <th>Asset ID</th>
                            <th>Location</th>
                            <th>MAS Score</th>
                            <th>MPS Score</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($inspections as $inspection)
                            <tr>
                                <td>{{ $inspection->id }}</td>

                                <td>{{ $inspection->insp_cd ?: '—' }}</td>

                                <td>
                                    {{ $inspection->insp_date ? \Carbon\Carbon::parse($inspection->insp_date)->format('d-m-Y') : '—' }}
                                </td>

                                <td>
                                    {{ $inspection->asset_type_name ?: $inspection->insp_asset_type ?: '—' }}
                                </td>

                                <td>{{ $inspection->insp_asset_id ?: '—' }}</td>

                                <td>{{ $inspection->insp_asset_location ?: '—' }}</td>

                                <td>
                                    {{ $inspection->mas_score !== null ? number_format((float) $inspection->mas_score, 2) : '—' }}
                                </td>

                                <td>
                                    {{ $inspection->mps_score !== null ? number_format((float) $inspection->mps_score, 2) : '—' }}
                                </td>

                                <td>
                                    <span class="mmis-status {{ $status === 'assessed' ? 'complete' : 'pending' }}">
                                        {{ $status === 'assessed' ? 'Assessed' : 'Pending' }}
                                    </span>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="mmis-empty">
                                    No inspection records found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </main>
@endsection
@push('styles')
    {{-- <link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css"> --}}
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#inspectionStatusTable').DataTable({
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                ordering: true,
                searching: true,
                paging: true,
                info: true,
                order: [
                    [2, 'desc']
                ],
                language: {
                    search: "Search inspections:",
                    emptyTable: "No inspection records available",
                    zeroRecords: "No matching inspection records found"
                }
            });

        });
    </script>
@endpush
