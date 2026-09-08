@extends('layouts.app')

@section('content')
    @php
        $projects = collect($project_list);
        $totalWorkOrder = $projects->sum(fn($project) => (float) ($project->work_order_amount ?? 0));
        $totalPayment = $projects->sum(fn($project) => (float) ($project->total_payment ?? 0));
        $totalBalance = $projects->sum(fn($project) => (float) ($project->balance ?? 0));
        $paymentPercent = $totalWorkOrder > 0 ? ($totalPayment / $totalWorkOrder) * 100 : 0;
    @endphp

    <div class="content-header pms-financial-page">
        <div class="container-fluid">
            <div class="pms-financial-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Financial Project Progress</span>
            </div>

            <section class="pms-financial-hero">
                <div>
                    <span class="pms-financial-eyebrow">Project Monitoring</span>
                    <h1>Financial Progress Register</h1>
                    <p>
                        Review work order value, payment movement, outstanding balance, and record new payment
                        updates for Nagaland PWD projects.
                    </p>
                </div>
                <div class="pms-financial-hero-stat">
                    <span>Payment Utilization</span>
                    <strong>{{ number_format($paymentPercent, 1) }}%</strong>
                </div>
            </section>

            <section class="pms-financial-summary">
                <article>
                    <span>Total Projects</span>
                    <strong>{{ $projects->count() }}</strong>
                    <small>Awaiting full payment closure</small>
                </article>
                <article>
                    <span>Work Order Amount</span>
                    <strong>{{ number_format($totalWorkOrder, 2) }}</strong>
                    <small>Total sanctioned payable amount</small>
                </article>
                <article>
                    <span>Total Payment</span>
                    <strong>{{ number_format($totalPayment, 2) }}</strong>
                    <small>Recorded project payments</small>
                </article>
                <article>
                    <span>Balance</span>
                    <strong>{{ number_format($totalBalance, 2) }}</strong>
                    <small>Remaining payment liability</small>
                </article>
            </section>

            <section class="pms-financial-card">
                <div class="pms-financial-card-header">
                    <div>
                        <span>Financial Progress</span>
                        <strong>Projects Available for Payment Entry</strong>
                    </div>
                    <small>{{ $projects->count() }} record(s)</small>
                </div>

                <div class="table-responsive pms-financial-table-wrap">
                    <table class="table table-bordered table-striped table-hover projectTable pms-financial-table"
                        id="project_details_table">
                        <thead class="theader">
                            <tr>
                                <th class="text-center" style="min-width: 1rem;">Sl<br>No.</th>
                                <th class="text-center" style="min-width: 5rem;">Project <br>Code</th>
                                <th class="text-center" style="min-width: 8rem;">Project <br>Name</th>
                                <th class="text-center" style="min-width: 6rem;">Division</th>
                                <th class="text-center" style="min-width: 6rem;">Sub <br>Division</th>
                                <th class="text-center" style="min-width: 4rem;">Project <br>Cost</th>
                                <th class="text-center" style="min-width: 4rem;">Total <br> Payment</th>
                                <th class="text-center" style="min-width: 4rem;">Balance</th>
                                <th class="text-center" style="min-width: 4rem;">Last <br> Payment <br>Date</th>
                                <th class="text-center" style="min-width: 4rem;">Payment <br>Details</th>
                                <th class="text-center" style="min-width: 4rem;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $index => $prj)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        <span class="pms-financial-code">{{ $prj->project_cd }}</span>
                                    </td>
                                    <td>{{ $prj->project_name }}</td>
                                    <td class="text-center">{{ $prj->division_name }}</td>
                                    <td class="text-center">{{ $prj->sub_div_name }}</td>
                                    <td class="text-right">{{ number_format((float) ($prj->work_order_amount ?? 0), 2) }}</td>
                                    <td class="text-right">{{ number_format((float) ($prj->total_payment ?? 0), 2) }}</td>
                                    <td class="text-right">
                                        <span class="pms-financial-balance">
                                            {{ number_format((float) ($prj->balance ?? 0), 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $prj->last_payment_date ? \Carbon\Carbon::parse($prj->last_payment_date)->format('d-M-Y') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center" style="gap: 6px;">                                            <button type="button" class="btn btn-outline-primary btn-sm payment-history-btn"
                                                title="View payment history"
                                                data-project-name="{{ $prj->project_name }}"
                                                data-url="{{ route('pms.progress.get.financial', $prj->project_cd) }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center" style="gap: 6px;">
                                            <a href="{{ route('pms.progress.create.financial', $prj->project_cd) }}"
                                                class="btn btn-primary btn-sm">
                                                Record Payment
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center pms-financial-empty">
                                        No project is currently pending financial payment entry.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="modal fade" id="paymentHistoryModal" tabindex="-1" role="dialog"
                aria-labelledby="paymentHistoryModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentHistoryModalLabel">Payment History Trail</h5>
                            <button type="button" class="close payment-history-close" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th><th>Type</th><th>Bill No.</th><th>Against Bill</th>
                                            <th class="text-right">Bill Amount</th>
                                            <th class="text-right">Payment Amount</th>
                                            <th class="text-right">Withheld Amount</th>
                                            <th>Reference</th><th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="payment_history_body"></tbody>
                                </table>
                            </div>
                            <div id="payment_history_empty" class="text-center text-muted py-4 d-none">
                                No payment history found.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary payment-history-close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pms-financial-page,
        .pms-financial-page * {
            font-family: var(--oamis-font) !important;
        }

        .pms-financial-page {
            color: var(--oamis-ink);
            padding-bottom: 28px;
        }

        .pms-financial-breadcrumb {
            align-items: center;
            color: var(--oamis-muted);
            display: flex;
            flex-wrap: wrap;
            font-size: 13px;
            font-weight: 700;
            gap: 8px;
            margin-bottom: 14px;
        }

        .pms-financial-breadcrumb a {
            color: var(--oamis-primary);
        }

        .pms-financial-hero {
            align-items: center;
            background:
                linear-gradient(135deg, rgba(11, 107, 74, .12), transparent 50%),
                var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 22px;
            box-shadow: var(--oamis-shadow);
            display: flex;
            gap: 18px;
            justify-content: space-between;
            margin-bottom: 18px;
            padding: 22px 24px;
        }

        .pms-financial-eyebrow,
        .pms-financial-card-header span {
            color: var(--oamis-primary);
            display: block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .pms-financial-hero h1 {
            color: var(--oamis-ink);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.03em;
            margin: 4px 0 6px;
        }

        .pms-financial-hero p {
            color: var(--oamis-muted);
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            max-width: 780px;
        }

        .pms-financial-hero-stat {
            background: rgba(11, 107, 74, .08);
            border: 1px solid rgba(11, 107, 74, .18);
            border-radius: 18px;
            min-width: 190px;
            padding: 14px 16px;
            text-align: right;
        }

        .pms-financial-hero-stat span,
        .pms-financial-hero-stat strong {
            display: block;
        }

        .pms-financial-hero-stat span {
            color: var(--oamis-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .pms-financial-hero-stat strong {
            color: var(--oamis-primary);
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
            margin-top: 4px;
        }

        .pms-financial-summary {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 18px;
        }

        .pms-financial-summary article {
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 18px;
            box-shadow: var(--oamis-shadow);
            padding: 16px 17px;
        }

        .pms-financial-summary span {
            color: var(--oamis-muted);
            display: block;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .pms-financial-summary strong {
            color: var(--oamis-ink);
            display: block;
            font-size: 22px;
            font-weight: 800;
            margin-top: 6px;
        }

        .pms-financial-summary small {
            color: var(--oamis-muted);
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-top: 4px;
        }

        .pms-financial-card {
            background: var(--oamis-card);
            border: 1px solid var(--oamis-border);
            border-radius: 22px;
            box-shadow: var(--oamis-shadow);
            overflow: hidden;
        }

        .pms-financial-card-header {
            align-items: center;
            background: var(--oamis-card);
            border-bottom: 1px solid var(--oamis-border);
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding: 16px 18px;
        }

        .pms-financial-card-header > div {
            border-left: 4px solid var(--oamis-primary);
            padding-left: 12px;
        }

        .pms-financial-card-header strong {
            color: var(--oamis-ink);
            display: block;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .pms-financial-card-header small {
            color: var(--oamis-muted);
            font-size: 12px;
            font-weight: 800;
        }

        .pms-financial-table-wrap {
            border: 0 !important;
            border-radius: 0 !important;
        }

        .pms-financial-table {
            margin-bottom: 0 !important;
            white-space: nowrap;
        }

        .pms-financial-table td {
            color: var(--oamis-ink) !important;
            font-size: 14px;
            font-weight: 600;
            vertical-align: middle;
        }

        .pms-financial-code {
            background: rgba(11, 107, 74, .09);
            border-radius: 999px;
            color: var(--oamis-primary);
            display: inline-flex;
            font-weight: 800;
            padding: 5px 10px;
        }

        .pms-financial-balance {
            color: var(--oamis-warning);
            font-weight: 800;
        }

        .pms-financial-action {
            background: #0b6b4a !important;
            border-color: #0b6b4a !important;
            border-radius: 999px !important;
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 800 !important;
            min-height: 36px;
            padding: 9px 15px !important;
        }

        .pms-financial-action i {
            color: #ffffff !important;
        }

        .pms-financial-action:hover,
        .pms-financial-action:focus {
            background: #07583d !important;
            border-color: #07583d !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .payment-history-btn {
            min-height: 36px;
            min-width: 38px;
        }

        #paymentHistoryModal .modal-title {
            font-size: 18px;
            font-weight: 700;
        }

        #paymentHistoryModal th,
        #paymentHistoryModal td,
        #paymentHistoryModal .modal-footer .btn,
        #paymentHistoryModal #payment_history_empty {
            font-size: 14px;
        }

        #paymentHistoryModal th { padding: 10px 8px; }
        #paymentHistoryModal td { padding: 9px 8px; }

        .pms-financial-empty {
            color: var(--oamis-muted) !important;
            font-weight: 700;
            padding: 28px 16px !important;
        }

        html[data-theme="dark"] .pms-financial-hero,
        html[data-theme="dark"] .pms-financial-summary article,
        html[data-theme="dark"] .pms-financial-card,
        html[data-theme="dark"] .pms-financial-card-header {
            background-color: var(--oamis-card) !important;
            color: var(--oamis-ink) !important;
        }

        html[data-theme="dark"] .pms-financial-code {
            background: rgba(52, 211, 153, .14);
            color: #6ee7b7;
        }

        @media (max-width: 1199.98px) {
            .pms-financial-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .pms-financial-hero,
            .pms-financial-card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .pms-financial-hero-stat {
                text-align: left;
                width: 100%;
            }

            .pms-financial-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.projectTable').DataTable({
                pageLength: 25,
                ordering: true,
                columnDefs: [
                    {
                        orderable: false,
                        targets: 9
                    }
                ]
            });

            $(document).on('click', '.payment-history-btn', function () {
                let historyBody = $('#payment_history_body');
                let emptyState = $('#payment_history_empty');
                let projectName = $(this).data('project-name');

                $('#paymentHistoryModalLabel').text('Payment History Trail - ' + projectName);
                historyBody.html('<tr><td colspan="9" class="text-center text-muted py-3">Loading payment history...</td></tr>');
                emptyState.addClass('d-none');
                $('#paymentHistoryModal').modal('show');

                $.get($(this).data('url'))
                    .done(function (payments) {
                        historyBody.empty();
                        if (!payments.length) {
                            emptyState.removeClass('d-none');
                            return;
                        }

                        payments.forEach(function (payment) {
                            let row = $('<tr>');
                            row.append($('<td>').text(payment.payment_date || '-'));
                            row.append($('<td>').text(payment.bill_type === 'W' ? 'Withheld' : 'Running'));
                            row.append($('<td>').text(payment.bill_no || '-'));
                            row.append($('<td>').text(payment.against_bill_no || '-'));
                            row.append($('<td>', { class: 'text-right' }).text(formatAmount(payment.bill_amount)));
                            row.append($('<td>', { class: 'text-right' }).text(formatAmount(payment.payment_amount)));
                            row.append($('<td>', { class: 'text-right' }).text(formatAmount(payment.withheld_amount)));
                            row.append($('<td>').text(payment.payment_reference || '-'));
                            row.append($('<td>').text(payment.remarks || '-'));
                            historyBody.append(row);
                        });
                    })
                    .fail(function () {
                        historyBody.html('<tr><td colspan="9" class="text-center text-danger py-3">Unable to load payment history.</td></tr>');
                    });
            });

            $('.payment-history-close').click(function () {
                $('#paymentHistoryModal').modal('hide');
            });

            function formatAmount(amount) {
                return Number(amount || 0).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        });
    </script>
@endpush
