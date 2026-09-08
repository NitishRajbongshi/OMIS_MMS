@extends('layouts.app')

@section('content')
    @php
        $newProjects = collect($query ?? []);
        $revertedProjects = collect($reverted_projects ?? []);
        $rejectedProjects = collect($rejected_projects ?? []);
        $desgCd = $userDesgCd;

    @endphp

    <div class="content-header">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left text-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Project Completion Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid mainBody">
            <h6 class="p-2 border border-primary text-light bg-primary text-uppercase text-sm">
                Project Completion Report
            </h6>

            <div class="card completion-report-card">
                <div class="card-header border-bottom-0 pb-0">
                    <ul class="nav nav-tabs completion-report-tabs" id="completionReportTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="new-tab" data-bs-toggle="tab"
                                data-bs-target="#new-pane" type="button" role="tab" aria-controls="new-pane"
                                aria-selected="true">
                                New
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reverted-tab" data-bs-toggle="tab"
                                data-bs-target="#reverted-pane" type="button" role="tab"
                                aria-controls="reverted-pane" aria-selected="false">
                                Reverted
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab"
                                data-bs-target="#rejected-pane" type="button" role="tab"
                                aria-controls="rejected-pane" aria-selected="false">
                                Rejected
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="completionReportTabsContent">
                        <div class="tab-pane fade show active" id="new-pane" role="tabpanel"
                            aria-labelledby="new-tab">
                            @include('pms.report.partials.projectCompletionTable', [
                                'projects' => $newProjects,
                                'desgCd' => $desgCd,
                                'tableId' => 'newCompletionProjectsTable',
                                'emptyMessage' => 'No new completed project found.',
                            ])
                        </div>

                        <div class="tab-pane fade" id="reverted-pane" role="tabpanel"
                            aria-labelledby="reverted-tab">
                            @include('pms.report.partials.projectCompletionTable', [
                                'projects' => $revertedProjects,
                                'desgCd' => $desgCd,
                                'tableId' => 'revertedCompletionProjectsTable',
                                'emptyMessage' => 'No reverted completion report found.',
                            ])
                        </div>

                        <div class="tab-pane fade" id="rejected-pane" role="tabpanel"
                            aria-labelledby="rejected-tab">
                            @include('pms.report.partials.projectCompletionTable', [
                                'projects' => $rejectedProjects,
                                'desgCd' => $desgCd,
                                'tableId' => 'rejectedCompletionProjectsTable',
                                'emptyMessage' => 'No rejected completion report found.',
                            ])
                        </div>
                    </div>
                </div>
            </div>

            @include('pms.report.partials.projectCompletionDetailsModal', ['desgCd' => $desgCd,])
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .completion-report-card {
            border-top: 3px solid #417dbe;
        }

        .completion-report-tabs .nav-link {
            color: #495057;
            font-size: .875rem;
            font-weight: 600;
        }

        .completion-report-tabs .nav-link.active {
            color: #417dbe;
        }

        .completion-project-table th,
        .completion-project-table td {
            vertical-align: middle;
        }

        .completion-progress {
            min-width: 115px;
        }

        .completion-progress .progress {
            height: 7px;
            margin-top: 5px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.completion-project-table').each(function () {
                if ($.fn.DataTable && !$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        pageLength: 25,
                        ordering: true,
                        autoWidth: false,
                        columnDefs: [{
                            orderable: false,
                            targets: 0
                        }]
                    });
                }
            });

            document.querySelectorAll('#completionReportTabs button[data-bs-toggle="tab"]').forEach(function (tab) {
                tab.addEventListener('shown.bs.tab', function () {
                    if ($.fn.DataTable) {
                        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                    }
                });
            });
        });
    </script>
@endpush
