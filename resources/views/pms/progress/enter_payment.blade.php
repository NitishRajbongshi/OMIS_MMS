@extends('layouts.app')
@section('content')
    <div class="content-header pms-payment-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-10">
                    <ol class="breadcrumb float-sm-left text-sm">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('pms.progress.financial') }}">List Projects</a>
                        </li>

                        <li class="breadcrumb-item">Financial Progress</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="card mb-2 pms-payment-card">
            <div class="pms-payment-title">
                <span>Financial Progress</span>
                <strong>Capture Financial Progress Under Nagaland PWD</strong>
            </div>
            @if (session('failed'))
                <div class="text-sm alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa fa-info" aria-hidden="true"></i>
                    <strong>Failed!</strong> {{ session('failed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="text-sm alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-sm-3">
                        <label class="form-label">Project Name:</label>
                        <input type="text" id="txt_proj_name" class="form-control form-control-sm"
                            value="{{ $project->project_name }}" readonly>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Project CD:</label>
                        <input type="text" id="txt_proj_cd" class="form-control form-control-sm"
                            value="{{ $project->project_cd }}" readonly>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Division Name:</label>
                        <input type="text" id="txt_div_name" class="form-control form-control-sm"
                            value="{{ $project->division_name }}" readonly>
                    </div>

                    <div class="col-sm-3">
                        <label class="form-label">Sub Division Name:</label>
                        <input type="text" id="txt_sub_div_name" class="form-control form-control-sm"
                            value="{{ $project->sub_div_name }}" readonly>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-sm-3">
                        <label class="form-label">Project Cost:</label>
                        <input type="text" id="txt_total_project_cost" class="form-control form-control-sm"
                            value="{{ $project->work_order_amount }}" readonly>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Last Payment Date:</label>
                        <input type="text" id="txt_last_payment_date" class="form-control form-control-sm"
                            value="{{ $project->last_payment_date }}" readonly>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Last Payment Amount:</label>
                        <input type="text" id="txt_last_payment_amount" class="form-control form-control-sm"
                            value="{{ $project->last_payment_amount }}" readonly>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label">Balance Amount:</label>
                        <input type="text" id="txt_balance_amount" class="form-control form-control-sm"
                            value="{{ $project->balance }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                <form method="POST" action="{{ route('pms.progress.store.financial') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="project_cd" value="{{ $project->project_cd }}">

                    <div class="form-group">
                        <div class="row g-2">
                            <div class="col-sm-3">
                                <label>Bill Type</label>
                                <select id="sel_bill_type" name="sel_bill_type" class="form-control" required>
                                    <option value="R">Running Bill</option>
                                    <option value="W">Withheld Bill</option>
                                </select>
                            </div>
                            <div class="col-sm-3" id="div_running_bill_no">
                                <label>Bill No</label>
                                <input type="number" id="running_bill_no" class="form-control"
                                    value="{{ $nextBillNo }}" readonly>
                            </div>
                            <div class="col-sm-3" id="div_against_bill" style="display:none;">
                                <label>Against Running Bill No</label>
                                <select name="against_bill_no[]" id="against_bill_no"
                                    class="form-control bill-checkbox-select" multiple>
                                    @foreach($runningBills as $bill)
                                        <option value="{{ $bill->bill_no }}"
                                            data-bill-amount="{{ $bill->bill_amount }}"
                                            data-original-withheld="{{ $bill->withheld_amount }}"
                                            data-remaining-withheld="{{ $bill->remaining_withheld_amount }}">
                                            {{ $bill->bill_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3" id="div_bill_amount">
                                <label>Bill Amount</label>
                                <input type="number" name="bill_amount" id="bill_amount" class="form-control"
                                    max="{{ $project->remaining_bill_amount }}"
                                    data-remaining-bill-amount="{{ $project->remaining_bill_amount }}" required>
                                <small id="remaining_bill_hint" class="text-muted">
                                    Remaining billable: {{ number_format($project->remaining_bill_amount, 2) }}
                                </small>
                            </div>
                            <div class="col-sm-3" id="div_withheld_amount">
                                <label>Withheld Amount</label>
                                <input type="number" name="withheld_amount" id="withheld_amount" class="form-control"
                                    readonly required>
                                <input type="hidden" id="available_withheld_amount">
                            </div>
                            <div class="col-sm-3" id="div_payment_amount">
                                <label id="payment_amount_label">Payment Amount</label>
                                <input type="number" name="payment_amount" id="payment_amount" class="form-control"
                                    required>
                            </div>
                            <div class="col-sm-3 running-composite-field">
                                <label>Release Withheld From Bill</label>
                                <select name="release_against_bill_no[]" id="release_against_bill_no"
                                    class="form-control bill-checkbox-select" multiple>
                                    @foreach($runningBills as $bill)
                                        <option value="{{ $bill->bill_no }}"
                                            data-remaining-withheld="{{ $bill->remaining_withheld_amount }}">
                                            Bill {{ $bill->bill_no }} (Balance: {{ number_format($bill->remaining_withheld_amount, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3 running-composite-field">
                                <label>Withheld Release Amount</label>
                                <input type="number" name="release_withheld_amount" id="release_withheld_amount"
                                    class="form-control" min="0" step="0.01" readonly>
                            </div>
                            <div class="col-sm-3 running-composite-field">
                                <label>Total Payment Amount</label>
                                <input type="number" id="total_invoice_payment" class="form-control" readonly>
                            </div>
                            <div class="col-sm-3">
                                <label>Payment Reference</label>
                                <input type="text" name="payment_reference" id="payment_reference" class="form-control"
                                    required>
                            </div>
                            <div class="col-sm-3">
                                <label>Payment Date</label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                            </div>
                            <div class="col-sm-3 d-flex align-items-end">
                                <button type="button" id="btn_payment_history" class="btn btn-outline-primary w-100"
                                    data-url="{{ route('pms.progress.get.financial', $project->project_cd) }}">
                                    <i class="fas fa-history mr-1"></i> Payment History
                                </button>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control form-control-sm text-sm" id="remarks" name="remarks" rows="2"
                                    placeholder="Write Payment remarks...">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <div class="text-danger text-xs">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-1-box border mt-2" style="margin: 0 1.5px;" id="asset_document_container">
                            <div class="col-md-12 pt-2">
                                <fieldset class="">
                                    <legend class="w-auto px-2" style="font-size:13px ">
                                        Upload Documents
                                    </legend>
                                    <div class="p-2">
                                        <div>
                                            <p class="text-sm text-info text-underline">
                                                <strong>
                                                    <i class="fa fa-info-circle mr-1 text-xs"></i>Important:
                                                </strong>
                                            </p>
                                            <ul class="text-xs text-secondary">
                                                <li>
                                                    <strong>
                                                        File Size Limit:
                                                    </strong>
                                                    The maximum allowed file size is 2 MB.
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="row form-1-box my-1">
                                            <div class="col-md-4">
                                                <label for="paymentDoc">1. Upload Document (PDF):</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="file" class="text-xs text-success" id="paymentDoc"
                                                    name="paymentDoc">
                                                <button type="button" id="removeBtn_paymentDoc"
                                                    class="outline-0 border border-danger text-danger text-xs rounded-0"
                                                    style="background:rgb(252, 217, 217); display:none;"
                                                    onclick="removeFile('paymentDoc')">
                                                    <i class="fa fa-trash mr-1 text-xs"></i>
                                                    Remove
                                                </button>
                                                @error('paymentDoc')
                                                    <div class="text-danger text-xs">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <button type="submit" id="btn_save_payment" class="btn btn-success mt-2">Save Payment</button>
                </form>
            </div>

        </div>

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
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Bill No.</th>
                                        <th>Against Bill</th>
                                        <th class="text-right">Bill Amount</th>
                                        <th class="text-right">Payment Amount</th>
                                        <th class="text-right">Withheld Amount</th>
                                        <th>Reference</th>
                                        <th>Remarks</th>
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
    </section>
@endsection
@push('styles')
    <style>
        .pms-payment-page,
        .pms-payment-page * {
            font-family: var(--oamis-font) !important;
        }

        .pms-payment-card {
            background: var(--oamis-card) !important;
            border: 1px solid var(--oamis-border) !important;
            border-radius: 18px !important;
            box-shadow: var(--oamis-shadow) !important;
            overflow: hidden;
        }

        .pms-payment-title {
            background: var(--oamis-card);
            border-bottom: 1px solid var(--oamis-border);
            padding: 16px 18px 16px 22px;
            position: relative;
        }

        .pms-payment-title::before {
            background: var(--oamis-primary);
            border-radius: 999px;
            bottom: 16px;
            content: "";
            left: 12px;
            position: absolute;
            top: 16px;
            width: 4px;
        }

        .pms-payment-title span {
            color: var(--oamis-primary) !important;
            display: block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .pms-payment-title strong {
            color: var(--oamis-ink) !important;
            display: block;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -.02em;
            margin-top: 3px;
            text-transform: uppercase;
        }

        html[data-theme="dark"] .pms-payment-card,
        html[data-theme="dark"] .pms-payment-title {
            background: var(--oamis-card) !important;
            color: var(--oamis-ink) !important;
        }

        .bill-checkbox-option {
            align-items: center;
            display: flex;
            gap: 8px;
        }

        .bill-checkbox-option input {
            height: 16px;
            margin: 0;
            pointer-events: none;
            width: 16px;
        }

        #paymentHistoryModal .modal-title {
            font-size: 18px;
            font-weight: 700;
        }

        #paymentHistoryModal table {
            font-size: 14px;
        }

        #paymentHistoryModal th {
            font-size: 14px;
            font-weight: 700;
            padding: 10px 8px;
        }

        #paymentHistoryModal td {
            font-size: 14px;
            padding: 9px 8px;
        }

        #paymentHistoryModal .modal-footer .btn,
        #paymentHistoryModal #payment_history_empty {
            font-size: 14px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function () {
            function renderBillCheckbox(option) {
                if (!option.id) {
                    return option.text;
                }

                return $('<span>', { class: 'bill-checkbox-option' })
                    .append($('<input>', {
                        type: 'checkbox',
                        tabindex: -1,
                        checked: option.element.selected
                    }))
                    .append($('<span>').text(option.text));
            }

            $('.bill-checkbox-select').select2({
                theme: 'bootstrap4',
                width: '100%',
                closeOnSelect: false,
                placeholder: 'Select Running Bill(s)',
                templateResult: renderBillCheckbox
            });

            $('.bill-checkbox-select').on('select2:select select2:unselect', function () {
                setTimeout(function () {
                    $('.select2-results__option[aria-selected]').each(function () {
                        $(this).find('input[type="checkbox"]')
                            .prop('checked', $(this).attr('aria-selected') === 'true');
                    });
                }, 0);
            });

            $('.projectTable').DataTable({
                "pageLength": 25,
                "ordering": true
            });


            $('#sel_bill_type').change(function () {

                if ($(this).val() === 'W') {

                    let runningBillCount = $('#against_bill_no option').length;

                    if (runningBillCount <= 0) {
                        alert('No Running Bill exists with withheld amount for this project.');
                        $(this).val('R');
                        $('#div_against_bill').hide();
                        return;
                    }

                    $('#div_against_bill').show();
                    $('#div_running_bill_no').hide();
                    $('#bill_amount').val('').prop('readonly', true);
                    $('#bill_amount').removeAttr('max');
                    $('#payment_amount').val('').removeAttr('max');
                    $('#withheld_amount').val('');
                    $('#available_withheld_amount').val('');
                    $('.running-composite-field').hide();
                    $('#payment_amount_label').text('Payment Amount');
                    $('#btn_save_payment').prop('disabled', false);
                    $('#release_against_bill_no').val(null).trigger('change');
                    $('#release_withheld_amount').val('');
                    $('#total_invoice_payment').val('');
                    setAmountFieldOrder('W');

                } else {
                    $('#div_against_bill').hide();
                    $('#div_running_bill_no').show();
                    $('#against_bill_no').val(null).trigger('change');
                    $('#bill_amount').val('');
                    $('#bill_amount').attr('max', $('#bill_amount').data('remaining-bill-amount'));
                    $('#payment_amount').val('').removeAttr('max');
                    $('#withheld_amount').val('');
                    $('#available_withheld_amount').val('');
                    $('.running-composite-field').show();
                    $('#payment_amount_label').text('Payment Amount');
                    setAmountFieldOrder('R');
                    applyRunningBillAvailability();
                }
            });

            function applyRunningBillAvailability() {
                let remainingBillable = parseFloat($('#bill_amount').data('remaining-bill-amount')) || 0;
                let isRunningBill = $('#sel_bill_type').val() === 'R';

                $('#bill_amount').prop('readonly', isRunningBill && remainingBillable <= 0);
                $('#btn_save_payment').prop('disabled', isRunningBill && remainingBillable <= 0);
            }

            $('#bill_amount').on('input', function () {
                if ($('#sel_bill_type').val() !== 'R') {
                    return;
                }

                let remainingBillable = parseFloat($(this).data('remaining-bill-amount')) || 0;
                let billAmount = parseFloat($(this).val()) || 0;

                if (billAmount > remainingBillable) {
                    alert('Bill Amount cannot exceed the remaining billable amount of ' + remainingBillable.toFixed(2));
                    $(this).val('');
                    $('#payment_amount').val('');
                    $('#withheld_amount').val('');
                }
            });

            function setAmountFieldOrder(billType) {
                let billAmountField = $('#div_bill_amount');
                let paymentAmountField = $('#div_payment_amount');
                let withheldAmountField = $('#div_withheld_amount');

                if (billType === 'W') {
                    billAmountField.after(withheldAmountField);
                    withheldAmountField.after(paymentAmountField);
                } else {
                    billAmountField.after(paymentAmountField);
                    paymentAmountField.after(withheldAmountField);
                }
            }

            setAmountFieldOrder($('#sel_bill_type').val());
            applyRunningBillAvailability();

            $('#release_against_bill_no').change(function () {
                let available = selectedWithheldTotal($(this));
                let hasSelection = $(this).find(':selected').length > 0;
                $('#release_withheld_amount')
                    .val(hasSelection ? available.toFixed(2) : '')
                    .attr('max', available || '');
                calculateInvoiceTotal();
                calculateWithheld();
            });

            $('#release_withheld_amount').on('keyup change', function () {
                let available = selectedWithheldTotal($('#release_against_bill_no'));
                let releaseAmount = parseFloat($(this).val()) || 0;

                if (releaseAmount > available) {
                    alert('Withheld Release Amount cannot exceed the available balance');
                    $(this).val('');
                }
                calculateInvoiceTotal();
            });

            function calculateInvoiceTotal() {
                let currentBillPayment = parseFloat($('#payment_amount').val()) || 0;
                let withheldRelease = parseFloat($('#release_withheld_amount').val()) || 0;
                $('#total_invoice_payment').val((currentBillPayment + withheldRelease).toFixed(2));
            }

            $('#against_bill_no').change(function () {
                let selectedBills = $(this).find(':selected');
                let billAmount = 0;
                let remainingWithheld = 0;

                selectedBills.each(function () {
                    billAmount += parseFloat($(this).data('bill-amount')) || 0;
                    remainingWithheld += parseFloat($(this).data('remaining-withheld')) || 0;
                });

                $('#bill_amount').val(selectedBills.length ? billAmount.toFixed(2) : '');
                $('#withheld_amount').val(
                    selectedBills.length ? remainingWithheld.toFixed(2) : ''
                );
                $('#available_withheld_amount').val(
                    selectedBills.length ? remainingWithheld : ''
                );
                $('#payment_amount')
                    .val('')
                    .attr('max', selectedBills.length ? remainingWithheld : '');
            });

            function selectedWithheldTotal(select) {
                let total = 0;
                select.find(':selected').each(function () {
                    total += parseFloat($(this).data('remaining-withheld')) || 0;
                });
                return total;
            }

            $('#btn_payment_history').click(function () {
                let historyBody = $('#payment_history_body');
                let emptyState = $('#payment_history_empty');

                historyBody.empty().append(
                    $('<tr>').append(
                        $('<td>', {
                            colspan: 9,
                            class: 'text-center text-muted py-3',
                            text: 'Loading payment history...'
                        })
                    )
                );
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
                            let billType = payment.bill_type === 'W' ? 'Withheld' : 'Running';
                            let row = $('<tr>');

                            row.append($('<td>').text(payment.payment_date || '-'));
                            row.append($('<td>').text(billType));
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
                        historyBody.empty().append(
                            $('<tr>').append(
                                $('<td>', {
                                    colspan: 9,
                                    class: 'text-center text-danger py-3',
                                    text: 'Unable to load payment history.'
                                })
                            )
                        );
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

            function calculateWithheld() {

                let billType = $('#sel_bill_type').val();

                let billAmount = parseFloat($('#bill_amount').val()) || 0;
                let paymentAmount = parseFloat($('#payment_amount').val()) || 0;
                let paymentLimit = billType === 'W'
                    ? parseFloat($('#available_withheld_amount').val()) || 0
                    : billAmount;

                if (paymentAmount > paymentLimit) {

                    alert(billType === 'W'
                        ? 'Payment Amount cannot be greater than the remaining Withheld Amount'
                        : 'Payment Amount cannot exceed Bill Amount');

                    $('#payment_amount').val('');

                    if (billType === 'R') {
                        $('#withheld_amount').val('');
                    } else {
                        $('#withheld_amount').val(paymentLimit.toFixed(2));
                    }

                    return;
                }

                if (billType == 'R') {
                    let withheldAmount = billAmount - paymentAmount;

                    $('#withheld_amount').val(withheldAmount.toFixed(2));
                    calculateInvoiceTotal();
                } else if (billType == 'W') {
                    let remainingWithheldAmount = paymentLimit - paymentAmount;
                    $('#withheld_amount').val(remainingWithheldAmount.toFixed(2));
                }
            }

            $('#payment_amount').on('keyup change', calculateWithheld);
            $('#bill_amount').on('keyup change', calculateWithheld);
            $('#sel_bill_type').on('change', calculateWithheld);
            $('#release_withheld_amount').on('keyup change', calculateWithheld);
        });
    </script>
@endpush
