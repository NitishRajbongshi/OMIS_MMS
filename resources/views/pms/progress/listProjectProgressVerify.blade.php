@extends('layouts.app')
@section('content')
    @php
        $financialProjects = collect($financial_project_list ?? []);
        $totalWorkOrder = $financialProjects->sum(fn($project) => (float) ($project->work_order_amount ?? 0));
        $totalPayment = $financialProjects->sum(fn($project) => (float) ($project->total_payment ?? 0));
        $totalBalance = $financialProjects->sum(fn($project) => (float) ($project->balance ?? 0));
        $paymentPercent = $totalWorkOrder > 0 ? ($totalPayment / $totalWorkOrder) * 100 : 0;
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left text-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Verify Project Progress</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="container-fluid mainBody">
            <h6 class="p-2 mt-5 border border-primary text-light bg-primary">
                <span class="border border-primary text-light bg-primary text-sm text-uppercase">
                    VERIFY PROGRESS OF PROJECTS UNDER NAGALAND P W D.
                </span>
            </h6>
            @if (session('warning'))
                <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa fa-warn" aria-hidden="true"></i>
                    <strong>Rejected!</strong> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    <strong>Approved!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <ul class="nav nav-tabs pms-progress-tabs" id="verifyProgressTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="physical-progress-tab" data-bs-toggle="tab"
                        data-bs-target="#physical-progress-pane" type="button" role="tab"
                        aria-controls="physical-progress-pane" aria-selected="true">
                        Physical Progress
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="financial-progress-tab" data-bs-toggle="tab"
                        data-bs-target="#financial-progress-pane" type="button" role="tab"
                        aria-controls="financial-progress-pane" aria-selected="false">
                        Financial Progress
                    </button>
                </li>
            </ul>

            <div class="tab-content pms-progress-tab-content" id="verifyProgressTabsContent">
                <div class="tab-pane fade show active" id="physical-progress-pane" role="tabpanel"
                    aria-labelledby="physical-progress-tab">
                    <div class="container-fluid border py-2">
                        <table class="table-responsive text-xs table table-bordered table-striped projectTable"
                            id="project_details_table">
                            <thead class="theader text-white" style="background-color:#417DBE">
                                <th class="text-center" style="min-width: 3rem;">Sl No.</th>
                                <th class="text-center" style="min-width: 4rem;">Progress CD</th>
                                <th class="text-center" style="min-width: 8rem;">Division</th>
                                <th class="text-center" style="min-width: 8rem;">Sub-Division</th>
                                <th class="text-center" style="min-width: 5rem;">Project Code</th>
                                <th class="text-center" style="min-width: 5rem;">Project Name</th>
                                <th class="text-center" style="min-width: 5rem;">Project Type</th>
                                <th class="text-center" style="min-width: 5rem;">Start Date</th>
                                <th class="text-center" style="min-width: 5rem;">End Date</th>
                                <th class="text-center" style="min-width: 4rem;">Date of Submission</th>
                                <th class="text-center" style="min-width: 4rem;">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($project_list as $index => $prj)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $prj->progress_id }}</td>
                                        <td class="text-center">{{ $prj->division_name }}</td>
                                        <td class="text-center">{{ $prj->sub_div_name }}</td>
                                        <td class="text-center">{{ $prj->project_cd }}</td>
                                        <td class="text-center">{{ $prj->project_name }}</td>
                                        <td class="text-center">{{ $prj->proj_type_descr }}</td>
                                        <td class="text-center">{{ $prj->project_start_date ? \Carbon\Carbon::parse($prj->project_start_date)->format('d-m-Y') : '-' }}</td>
                                        <td class="text-center">{{ $prj->project_end_date ? \Carbon\Carbon::parse($prj->project_end_date)->format('d-m-Y') : '-' }}</td>
                                        <td class="text-center">{{ $prj->progress_date ? \Carbon\Carbon::parse($prj->progress_date)->format('d-m-Y') : '-' }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm viewItems"
                                                data-project="{{ $prj->project_cd }}"
                                                data-progress-cd="{{ $prj->progress_id }}"
                                                data-name="{{ $prj->project_name }}"
                                                data-submitted-on="{{ $prj->created_at }}">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="financial-progress-pane" role="tabpanel"
                    aria-labelledby="financial-progress-tab">
                    <div class="pms-financial-inline-page">
                        <section class="pms-financial-hero">
                            <div>
                                <span class="pms-financial-eyebrow">Project Monitoring</span>
                                <h1>Financial Progress Register</h1>
                                <p>
                                    Review work order value, payment movement, outstanding balance, and record new
                                    payment updates for Nagaland PWD projects.
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
                                <strong>{{ $financialProjects->count() }}</strong>
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
                                <small>{{ $financialProjects->count() }} record(s)</small>
                            </div>

                            <div class="table-responsive pms-financial-table-wrap">
                                <table class="table table-bordered table-striped table-hover financialProjectTable pms-financial-table"
                                    id="financial_project_details_table">
                                    <thead class="theader">
                                        <tr>
                                            <th class="text-center" style="min-width: 1rem;">Sl<br>No.</th>
                                            <th class="text-center" style="min-width: 4rem;">Project <br>Code</th>
                                            <th class="text-center" style="min-width: 4rem;">Project <br>Name</th>
                                            <th class="text-center" style="min-width: 4rem;">Division</th>
                                            <th class="text-center" style="min-width: 4rem;">Sub <br>Division</th>
                                            <th class="text-center" style="min-width: 4rem;">Project <br>Cost</th>
                                            <th class="text-center" style="min-width: 4rem;">Total <br>Payment</th>
                                            <th class="text-center" style="min-width: 4rem;">Balance</th>
                                            <th class="text-center" style="min-width: 4rem;">Last <br>Payment <br>Date</th>
                                            <th class="text-center" style="min-width: 4rem;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($financialProjects as $index => $prj)
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
                                                    <div class="d-flex justify-content-center align-items-center" style="gap: 6px;">
                                                        <a href="{{ route('pms.progress.create.financial', $prj->project_cd) }}"
                                                            class="btn btn-primary btn-sm pms-financial-action">
                                                            <i class="fas fa-receipt mr-1"></i> Record Payment
                                                        </a>
                                                        <button type="button" class="btn btn-outline-primary btn-sm verify-payment-history-btn"
                                                            title="View payment history"
                                                            data-project-name="{{ $prj->project_name }}"
                                                            data-url="{{ route('pms.progress.get.financial', $prj->project_cd) }}">
                                                            <i class="fas fa-history"></i>
                                                        </button>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="verifyPaymentHistoryModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyPaymentHistoryTitle">Payment History Trail</h5>
                        <button type="button" class="close verify-payment-history-close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm mb-0">
                                <thead><tr>
                                    <th>Date</th><th>Type</th><th>Bill No.</th><th>Against Bill</th>
                                    <th class="text-right">Bill Amount</th><th class="text-right">Payment Amount</th>
                                    <th class="text-right">Withheld Amount</th><th>Reference</th><th>Remarks</th>
                                </tr></thead>
                                <tbody id="verify_payment_history_body"></tbody>
                            </table>
                        </div>
                        <div id="verify_payment_history_empty" class="text-center text-muted py-4 d-none">No payment history found.</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary verify-payment-history-close">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <form id="approvalForm" method="POST">
            @csrf
            <div class="modal fade" id="itemModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 id="modalTitle">Project Progress Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col col-md-3">
                                    <label for="lbl_project_cd" class="form-label" id="lbl_project_cd">Project CD:</label>
                                    <input type="hidden" id="txt_project_cd" name="txt_project_cd" value="">
                                </div>
                                <div class="col col-md-3">
                                    <label for="lbl_progress_cd" class="form-label" id="lbl_progress_cd">Progress
                                        CD:</label>
                                    <input type="hidden" id="txt_progress_cd" name="txt_progress_cd" value="">
                                </div>
                                <div class="col col-md-4">
                                    <label for="txt_submission_date" class="form-label" id="txt_submission_date">Submitted
                                        On:</label>
                                </div>
                            </div>
                            <table class="table-responsive table table-bordered table-striped text-xs">
                                <thead class="theader text-white" style="background-color:#417DBE">
                                    <tr>
                                        <th class="text-center">Item Of Work</th>
                                        <th class="text-center">Total Qty</th>
                                        <th class="text-center">Total Approved Qty</th>
                                        <th class="text-center">Qty Done</th>
                                    </tr>
                                </thead>
                                <tbody id="itemTableBody"></tbody>
                            </table>


                            <div class="mt-3">
                                <label>Action:</label>
                                <select id="action_type" name="action_type" class="form-select">
                                    <option value="">Select</option>
                                    <option value="A">Approve</option>
                                    <option value="R">Reject</option>
                                </select>
                            </div>

                            <div id="imageContainer"></div>
                            <!-- Reject Reason (Hidden Initially) -->
                            <div id="rejectDiv" style="display:none;" class="mt-3">
                                <label>Reject Reason:</label>
                                <textarea name="reject_reason" id="reject_reason" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="modal-footer">
                                <!-- <label>View Previous Progresses:</label> -->
                                <button type="submit" class="btn btn-success">
                                    Submit
                                </button>

                            </div>
                            <div class="modal-footer">
                                <button onclick="loadPreviousProgress()"
                                    class="accordion-button collapsed custom-accordion-btn" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#previousProgressContainer"><span
                                        class="me-2">📊</span><span>View
                                        Previous Progresses</span>
                                </button>
                            </div>
                            <div id="previousProgressContainer"></div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
        </section>
@endsection
    @push('scripts')
        <style>
            .custom-accordion-btn {
                padding: 6px 12px;
                font-size: 14px;
                font-weight: 500;
                background-color: #bfd9f3;
                border-radius: 6px;
                transition: all 0.2s ease;
            }

            .custom-accordion-btn:hover {
                background-color: #337cc5;
            }

            .custom-accordion-btn .icon {
                margin-left: auto;
                font-size: 12px;
                transition: transform 0.2s ease;
            }

            /* rotate icon when open */
            .custom-accordion-btn:not(.collapsed) .icon {
                transform: rotate(180deg);
            }

            .pms-progress-tabs {
                border-bottom: 1px solid #c8d7e6;
                gap: 4px;
                margin-top: 18px;
            }

            .pms-progress-tabs .nav-link {
                color: #2f4f6f;
                font-size: 14px;
                font-weight: 700;
                min-height: 42px;
            }

            .pms-progress-tabs .nav-link.active {
                color: var(--oamis-table-head-text);
                background-color: var(--oamis-table-head-bg);
                border-color: var(--oamis-table-head-bg) var(--oamis-table-head-bg) #ffffff;
            }

            .pms-progress-tab-content {
                background: #ffffff;
                border: 1px solid #c8d7e6;
                border-top: 0;
                padding: 12px;
            }

            .pms-financial-inline-page {
                color: var(--oamis-ink);
                padding-bottom: 8px;
            }

            .pms-financial-hero {
                align-items: center;
                background:
                    linear-gradient(135deg, rgba(11, 107, 74, .12), transparent 50%),
                    var(--oamis-card);
                border: 1px solid var(--oamis-border);
                border-radius: 18px;
                box-shadow: var(--oamis-shadow);
                display: flex;
                gap: 18px;
                justify-content: space-between;
                margin-bottom: 18px;
                padding: 20px 22px;
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
                letter-spacing: 0;
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
                border-radius: 14px;
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
                border-radius: 14px;
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
                border-radius: 18px;
                box-shadow: var(--oamis-shadow);
                overflow: hidden;
            }

            .pms-financial-card-header {
                align-items: center;
                background: var(--oamis-card);
                border-bottom: 1px solid var(--oamis-border);
                display: flex;
                gap: 14px;
                justify-content: space-between;
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
                letter-spacing: 0;
            }

            .pms-financial-card-header small {
                color: var(--oamis-muted);
                font-size: 12px;
                font-weight: 800;
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

            .pms-financial-empty {
                color: var(--oamis-muted) !important;
                font-weight: 700;
                padding: 28px 16px !important;
            }

            .verify-payment-history-btn { min-height: 36px; min-width: 38px; }
            #verifyPaymentHistoryModal .modal-title { font-size: 18px; font-weight: 700; }
            #verifyPaymentHistoryModal th,
            #verifyPaymentHistoryModal td,
            #verifyPaymentHistoryModal .modal-footer .btn,
            #verifyPaymentHistoryModal #verify_payment_history_empty { font-size: 14px; }
            #verifyPaymentHistoryModal th { padding: 10px 8px; }
            #verifyPaymentHistoryModal td { padding: 9px 8px; }

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
        {{-- blade-formatter-disable --}}
        <script>
            let selectedProjectCd = null;
            $(document).ready(function () {
                $('.projectTable').DataTable({
                    "pageLength": 25,
                    "ordering": true
                });

                $('.financialProjectTable').DataTable({
                    "pageLength": 25,
                    "ordering": true,
                    columnDefs: [
                        {
                            orderable: false,
                            targets: 9
                        }
                    ]
                });

                $(document).on('click', '.verify-payment-history-btn', function () {
                    let body = $('#verify_payment_history_body');
                    let empty = $('#verify_payment_history_empty');
                    $('#verifyPaymentHistoryTitle').text('Payment History Trail - ' + $(this).data('project-name'));
                    body.html('<tr><td colspan="9" class="text-center text-muted py-3">Loading payment history...</td></tr>');
                    empty.addClass('d-none');
                    $('#verifyPaymentHistoryModal').modal('show');

                    $.get($(this).data('url')).done(function (payments) {
                        body.empty();
                        if (!payments.length) { empty.removeClass('d-none'); return; }
                        payments.forEach(function (payment) {
                            let row = $('<tr>');
                            row.append($('<td>').text(payment.payment_date || '-'));
                            row.append($('<td>').text(payment.bill_type === 'W' ? 'Withheld' : 'Running'));
                            row.append($('<td>').text(payment.bill_no || '-'));
                            row.append($('<td>').text(payment.against_bill_no || '-'));
                            row.append($('<td>', { class: 'text-right' }).text(formatPaymentAmount(payment.bill_amount)));
                            row.append($('<td>', { class: 'text-right' }).text(formatPaymentAmount(payment.payment_amount)));
                            row.append($('<td>', { class: 'text-right' }).text(formatPaymentAmount(payment.withheld_amount)));
                            row.append($('<td>').text(payment.payment_reference || '-'));
                            row.append($('<td>').text(payment.remarks || '-'));
                            body.append(row);
                        });
                    }).fail(function () {
                        body.html('<tr><td colspan="9" class="text-center text-danger py-3">Unable to load payment history.</td></tr>');
                    });
                });

                $('.verify-payment-history-close').click(function () {
                    $('#verifyPaymentHistoryModal').modal('hide');
                });

                function formatPaymentAmount(amount) {
                    return Number(amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }

                $('#action_type').change(function () {
                    if ($(this).val() === 'R') {
                        $('#rejectDiv').show();
                        $('#reject_reason').prop('required', true);
                    } else {
                        $('#rejectDiv').hide();
                        $('#reject_reason').prop('required', false);
                    }
                });

                $('#approvalForm').submit(function (e) {
                    let action = $('#action_type').val();
                    if (action === 'R' && $('#reject_reason').val().trim() === '') {
                        e.preventDefault();
                        return;
                    }
                    if (action === '') {
                        e.preventDefault();
                        return;
                    }
                    // Set route dynamically

                    $(this).attr('action', '/project-management/approve-progress');

                });
            });

            $(document).on("click", ".viewItems", function () {
                let project_cd = $(this).data("project");
                selectedProjectCd = $(this).data("project");
                let progress_cd = $(this).data("progress-cd");
                let project_name = $(this).data("name");
                let date_submitted = $(this).data("submitted-on");
                $("#modalTitle").text("Project Progress Details- " + project_name);
                $("#lbl_project_cd").text("Project CD: " + project_cd);
                $("#lbl_progress_cd").text("Progress CD: " + progress_cd);
                $("#txt_project_cd").val(project_cd);
                $("#txt_progress_cd").val(progress_cd);
                $("#txt_submission_date").text("Submitted On: " + date_submitted);
                console.log("date_submitted : " + date_submitted);
                var itemProgressUrl = "{{ url('/project-management/pms-view-progress-submitted') }}";
                $.ajax({
                    url: itemProgressUrl + "/" + project_cd + "/" + progress_cd,
                    type: "GET",

                    success: function (res) {
                        let data = res.data;
                        let images = res.images;
                        let html = "";

                        if (data.length === 0) {
                            html = "<tr><td colspan='5' class='text-center'>No Data Found</td></tr>";
                        } else {
                            $.each(data, function (i, row) {
                                html += `<tr>
                                            <td>${row.item_name ?? ''}</td>
                                            <td>${row.quantity ?? 0} ${row.iow_unit}</td>
                                            <td>${row.total_approved_qty ?? 0} ${row.iow_unit}</td>
                                            <td>${row.quantity_done ?? 0} ${row.iow_unit}</td>
                                            </tr>`;
                            });
                        }

                        let imageHtml = '';

                        if (images.length > 0) {
                            images.forEach(function (img) {
                                imageHtml += `<a href="/project-management/view-file/${img}" target="_blank">
                                                <img src="/project-management/view-file/${img}"
                                                style="width:100px;height:100px;margin:5px;border-radius:5px;">
                                                </a>`;
                            });
                        } else {
                            imageHtml = '<p>No images available</p>';
                        }

                        $("#itemTableBody").html(html);
                        $('#imageContainer').html(imageHtml);
                        $("#itemModal").modal("show");
                    }
                });
            });

            function loadPreviousProgress() {
                if (!selectedProjectCd) {
                    alert("Project not selected");
                    return;
                }
                fetch(`/project-management/get-previous-progress/` + selectedProjectCd)
                    .then(res => res.json())
                    .then(data => renderPreviousProgressModal(data));
            }

            function renderPreviousProgressModal(response) {
                let data = response.data;
                let html = '';
                console.log(data);
                Object.keys(data).forEach((key, index) => {
                    let group = data[key];
                    console.log(group);
                    let header = group[0];
                    let badgeClass = header.status === 'A' ? 'bg-success' : 'bg-danger';

                    html += `<div class="accordion mb-2" id="accordion_${key}">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_${key}">
                                        <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse_${key}"
                                            style="padding: 4px 10px;font-size: 13px;">
                                            📅 ${header.progress_date} 
                                            &nbsp; | &nbsp;
                                            <span class="badge ${badgeClass}">
                                                ${header.status === 'A' ? 'Approved' : 'Rejected'}
                                            </span>
                                        </button>
                                    </h2>

                                    <div id="collapse_${key}" 
                                            class="accordion-collapse collapse" 
                                            data-bs-parent="#accordion_${key}">
                                        <div class="accordion-body">
                                            <table class="table table-bordered table-striped text-xs">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Qty Done</th>
                                                    </tr>
                                                </thead>
                                    <tbody>`;

                    group.forEach(row => {
                        html += `<tr>
                                        <td>${row.item_name ?? ''}</td>
                                        <td>${row.quantity_done ?? 0} ${row.iow_unit}</td>
                                    </tr>`;
                    });

                    html += `</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>`;
                });

                // ✅ Inject into SAME modal body
                document.getElementById('previousProgressContainer').innerHTML = html;
            }
        </script>
    @endpush
