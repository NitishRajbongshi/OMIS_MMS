<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover text-sm completion-project-table" id="{{ $tableId }}">
        <thead class="text-white" style="background-color: #417DBE;">
            <tr>
                <th class="text-center" style="width: 55px;">Sl No.</th>
                <th class="text-center">Project Code</th>
                <th>Project Name</th>
                <th class="text-center">Project Type</th>
                <th class="text-center">Division</th>
                <th class="text-center">Sub-Division</th>
                <th class="text-center">Start Date</th>
                <th class="text-center">End Date</th>
                <th class="text-center">Progress</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($projects as $index => $project)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $project->project_cd ?? 'N/A' }}</td>
                    <td>{{ $project->project_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $project->proj_type_descr ?? 'N/A' }}</td>
                    <td class="text-center">{{ $project->division_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $project->sub_div_name ?? 'N/A' }}</td>
                    <td class="text-center">
                        {{ !empty($project->project_start_date) ? \Carbon\Carbon::parse($project->project_start_date)->format('d-M-Y') : 'N/A' }}
                    </td>
                    <td class="text-center">
                        {{ !empty($project->project_end_date) ? \Carbon\Carbon::parse($project->project_end_date)->format('d-M-Y') : 'N/A' }}
                    </td>
                    <td class="text-center completion-progress">
                        @php
                            $progress = max(0, min(100, (float) ($project->progress_percent ?? 0)));
                        @endphp
                        <strong>{{ number_format($progress, 2) }}%</strong>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%;"
                                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-center pms-action-col">
                        <button type="button" class="btn btn-primary btn-sm viewItems"
                            data-project="{{ $project->project_cd }}" data-name="{{ $project->project_name }}"
                            data-completion-date="{{ !empty($project->prj_completion_date) ? \Carbon\Carbon::parse($project->prj_completion_date)->format('Y-m-d') : '' }}">
                            <i class="fas fa-eye"></i>
                            <span>View Details</span>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="py-4 text-center text-muted">{{ $emptyMessage }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@push('styles')
    <style>
        .pms-progress-page,
        .pms-progress-page *,
        .pms-progress-modal,
        .pms-progress-modal * {
            font-family: var(--oamis-font, "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif) !important;
        }

        .pms-progress-page {
            color: var(--oamis-ink);
        }

        .pms-page-breadcrumb {
            margin-bottom: 14px;
        }

        .pms-progress-hero {
            align-items: center;
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 24px;
            box-shadow: var(--oamis-shadow);
            display: flex;
            gap: 18px;
            justify-content: space-between;
            margin-bottom: 16px;
            padding: 24px;
        }

        .pms-eyebrow {
            color: var(--oamis-primary);
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .1em;
            margin-bottom: 7px;
            text-transform: uppercase;
        }

        .pms-progress-hero h1,
        .pms-card-header h2,
        .pms-modal-section-head h6 {
            color: var(--oamis-ink);
            font-weight: 700;
            margin: 0;
        }

        .pms-progress-hero h1 {
            font-size: 28px;
            letter-spacing: -.03em;
        }

        .pms-progress-hero p,
        .pms-modal-section-head p,
        .pms-summary-card small {
            color: var(--oamis-muted);
            margin: 7px 0 0;
        }

        .pms-hero-chip,
        .pms-code-pill,
        .pms-progress-pill {
            align-items: center;
            border-radius: 999px;
            display: inline-flex;
            font-weight: 700;
            gap: 7px;
            white-space: nowrap;
        }

        .pms-hero-chip {
            background: rgba(11, 107, 74, .1);
            color: var(--oamis-primary);
            font-size: 13px;
            padding: 9px 13px;
        }

        .pms-summary-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 16px;
        }

        .pms-summary-card {
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            box-shadow: var(--oamis-shadow);
            padding: 17px 18px;
        }

        .pms-summary-card span {
            color: var(--oamis-muted);
            display: block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .pms-summary-card strong {
            color: var(--oamis-ink);
            display: block;
            font-size: 24px;
            font-weight: 700;
            margin-top: 7px;
        }

        .pms-report-card {
            background: var(--oamis-card) !important;
            border: 1px solid var(--oamis-border) !important;
            border-radius: 24px !important;
            box-shadow: var(--oamis-shadow);
            margin-top: 0;
            overflow: hidden;
            padding: 0 !important;
        }

        .pms-card-header {
            align-items: center;
            border-bottom: 1px solid var(--oamis-border);
            display: flex;
            gap: 16px;
            justify-content: space-between;
            padding: 20px 22px;
        }

        .pms-card-header h2 {
            font-size: 19px;
        }

        .pms-table-tools {
            align-items: center;
            color: var(--oamis-muted);
            display: flex;
            flex-wrap: wrap;
            font-size: 12px;
            font-weight: 700;
            gap: 10px;
        }

        .pms-table-tools span {
            background: var(--oamis-soft);
            border: 1px solid var(--oamis-border);
            border-radius: 999px;
            padding: 7px 10px;
        }

        .pms-table-wrap {
            overflow-x: auto;
            padding: 16px;
        }

        .projectTable {
            margin-bottom: 0 !important;
            width: 100% !important;
        }

        .projectTable th,
        .projectTable td {
            font-size: 14px !important;
            vertical-align: middle !important;
        }

        .pms-project-name {
            font-weight: 700;
            min-width: 260px;
        }

        .pms-code-pill {
            background: var(--oamis-soft);
            color: var(--oamis-ink);
            font-size: 12px;
            padding: 6px 9px;
        }

        .pms-progress-pill {
            background: rgba(21, 128, 61, .12);
            color: var(--oamis-green);
            font-size: 12px;
            padding: 6px 10px;
        }

        .pms-action-col {
            min-width: 138px !important;
            white-space: nowrap !important;
        }

        .viewItems {
            align-items: center;
            display: inline-flex !important;
            font-size: 13px !important;
            gap: 7px;
            justify-content: center;
            min-width: 118px;
            white-space: nowrap;
        }

        .pms-progress-modal .modal-dialog {
            max-width: min(1180px, calc(100vw - 32px));
        }

        .pms-progress-modal .modal-content {
            border-radius: 30px !important;
        }

        .pms-progress-modal .modal-header {
            align-items: flex-start;
            padding: 22px 24px;
        }

        .pms-progress-modal .modal-title {
            color: var(--oamis-ink);
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -.02em;
            margin: 0;
        }

        .pms-progress-tabs {
            background: var(--oamis-soft);
            border: 1px solid var(--oamis-border);
            border-radius: 999px;
            display: inline-flex;
            gap: 6px;
            padding: 6px;
        }

        .pms-progress-tabs .nav-link {
            align-items: center;
            border: 0 !important;
            border-radius: 999px !important;
            color: var(--oamis-muted) !important;
            display: inline-flex;
            font-size: 13px !important;
            font-weight: 700;
            gap: 8px;
            padding: 10px 14px;
        }

        .pms-progress-tabs .nav-link.active {
            background: #0b6b4a !important;
            box-shadow: 0 10px 24px rgba(11, 107, 74, .22);
            color: #fff !important;
        }

        .pms-progress-tab-content {
            margin-top: 18px;
        }

        .pms-modal-section-head {
            align-items: center;
            display: flex;
            gap: 14px;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .pms-modal-section-head h6 {
            font-size: 16px;
        }

        .pms-modal-table {
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            padding: 0;
        }

        .pms-modal-table table {
            margin: 0 !important;
        }

        .custom-accordion-btn {
            align-items: center;
            border-radius: 999px !important;
            display: inline-flex;
            font-size: 13px !important;
            font-weight: 700;
            gap: 8px;
            padding: 9px 13px;
            transition: background-color .2s ease, color .2s ease, transform .2s ease;
        }

        .custom-accordion-btn:hover {
            transform: translateY(-1px);
        }

        .pms-summary-row td {
            background: color-mix(in srgb, var(--oamis-soft) 70%, var(--oamis-card)) !important;
            color: var(--oamis-ink) !important;
            font-weight: 700;
        }

        .pms-empty-state {
            color: var(--oamis-muted) !important;
            font-weight: 700;
            padding: 26px !important;
        }

        .pms-history-panel {
            margin-top: 14px;
        }

        .pms-history-panel:empty {
            display: none;
        }

        .pms-history-panel .accordion-item {
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            overflow: hidden;
        }

        .pms-history-panel .accordion-button {
            background: var(--oamis-soft) !important;
            color: var(--oamis-ink) !important;
            font-size: 13px !important;
            font-weight: 700;
            gap: 8px;
            padding: 12px 14px !important;
        }

        .pms-history-panel .accordion-body {
            background: var(--oamis-card);
            color: var(--oamis-ink);
            padding: 14px;
        }

        html[data-theme="dark"] .pms-hero-chip,
        html[data-theme="dark"] .pms-progress-pill {
            background: rgba(52, 211, 153, .13);
            color: #6ee7b7;
        }

        html[data-theme="dark"] .pms-summary-row td {
            background: #162235 !important;
            color: #f8fafc !important;
        }

        @media (max-width: 991px) {
            .pms-progress-hero,
            .pms-card-header,
            .pms-modal-section-head {
                align-items: flex-start;
                flex-direction: column;
            }

            .pms-summary-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575px) {
            .pms-progress-hero,
            .pms-card-header,
            .pms-table-wrap {
                padding: 14px;
            }

            .pms-progress-tabs {
                border-radius: 18px;
                display: flex;
                width: 100%;
            }

            .pms-progress-tabs .nav-link {
                justify-content: center;
                width: 100%;
            }
        }
    </style>
@endpush
