<div class="modal fade completion-progress-modal" id="itemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <small class="text-uppercase text-primary font-weight-bold">Project Drill-Down</small>
                    <h5 id="modalTitle" class="modal-title">Project Progress Details</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <ul class="nav nav-tabs completion-detail-tabs" id="progressTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="physical-tab" data-bs-toggle="tab"
                            data-bs-target="#physicalProgress" type="button" role="tab" aria-controls="physicalProgress"
                            aria-selected="true">
                            <i class="fas fa-tasks mr-1"></i> Physical Progress
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="financial-tab" data-bs-toggle="tab"
                            data-bs-target="#financialProgress" type="button" role="tab"
                            aria-controls="financialProgress" aria-selected="false">
                            <i class="fas fa-indian-rupee-sign mr-1"></i> Financial Progress
                        </button>
                    </li>
                </ul>

                <div class="tab-content completion-detail-tab-content">
                    <div class="tab-pane fade show active" id="physicalProgress" role="tabpanel"
                        aria-labelledby="physical-tab">
                        <div class="completion-physical-heading">
                            <h6>Item-Wise Physical Progress</h6>
                            <button type="button" id="viewPreviousProgress" class="btn btn-light btn-sm">
                                <i class="fas fa-clock-rotate-left mr-1"></i>
                                View Previous Progress
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-sm mb-0">
                                <thead class="text-white" style="background-color: #417DBE;">
                                    <tr>
                                        <th>Item Of Work</th>
                                        <th>Total Qty</th>
                                        <th>Qty Done</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody id="itemTableBody"></tbody>
                            </table>
                        </div>
                        <div id="previousProgressContainer" class="completion-history-container mt-3"></div>
                    </div>

                    <div class="tab-pane fade" id="financialProgress" role="tabpanel" aria-labelledby="financial-tab">
                        <h6>Financial Progress</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-sm mb-0">
                                <thead class="text-white" style="background-color: #417DBE;">
                                    <tr>
                                        <th>Date</th>
                                        <th>Payment Amount</th>
                                        <th>Reference No.</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody id="financialTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="completion-remarks-history mt-4">
                    <button type="button" id="viewOfficialsRemarks" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-comments mr-1"></i>
                        View Officials' Remarks
                    </button>
                    <div id="officialsRemarksContainer" class="mt-3"></div>
                </div>

                <form id="completionActionForm" class="completion-action-form mt-4" method="POST"
                    action="{{ route('project.pms-completion-report.movement.store') }}">
                    @csrf
                    <input type="hidden" id="completion_project_cd" name="project_cd">

                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="completion_date" class="form-label font-weight-bold">Actual Project Completion
                                Date</label>
                            <input type="date" id="completion_date" name="completion_date" class="form-control"
                                placeholder="Select completion date" required
                                @if (!in_array((int) $desgCd, [8, 11, 16, 21], true)) readonly @endif>
                        </div>
                        <div class="col-md-4">
                            <label for="completion_remarks" class="form-label font-weight-bold">Remarks</label>
                            <textarea id="completion_remarks" name="remarks" class="form-control" rows="2"
                                placeholder="Enter remarks"></textarea>
                        </div>
                        <div class="col-md-2">
                            <label for="completion_action" class="form-label font-weight-bold">Action</label>
                            <select id="completion_action" name="action_type" class="form-select" required>
                                <option value="">Select Action</option>
                                @if (in_array((int) $desgCd, [8, 11, 16, 21], true))
                                    <option value="1">Forward</option>
                                @elseif (in_array((int) $desgCd, [6, 12, 17, 22, 4, 13, 18, 23], true))
                                    <option value="1">Forward</option>
                                    <option value="2">Revert</option>
                                @elseif (in_array((int) $desgCd, [2, 15, 20, 25], true))
                                    <option value="3">Approve</option>
                                    <option value="2">Revert</option>
                                    <option value="4">Reject</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2 mt-3 mt-md-0">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-paper-plane mr-1"></i> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .completion-progress-modal .modal-dialog {
            max-width: min(1180px, calc(100vw - 32px));
        }

        .completion-detail-tabs {
            border-bottom: 1px solid #c8d7e6;
        }

        .completion-detail-tabs .nav-link {
            color: #2f4f6f;
            font-size: 14px;
            font-weight: 700;
        }

        .completion-detail-tabs .nav-link.active {
            background-color: #417DBE;
            border-color: #417DBE;
            color: #fff;
        }

        .completion-detail-tab-content {
            border: 1px solid #c8d7e6;
            border-top: 0;
            padding: 18px;
        }

        .completion-detail-tab-content h6 {
            font-weight: 700;
            margin-bottom: 0;
        }

        .completion-physical-heading {
            align-items: center;
            display: flex;
            gap: 12px;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        #viewPreviousProgress {
            font-size: 13px;
            font-weight: 700;
        }

        .completion-history-container:empty {
            display: none;
        }

        #officialsRemarksContainer:empty {
            display: none;
        }

        .completion-history-container .accordion-button {
            font-size: 13px;
            font-weight: 700;
        }

        .completion-financial-summary td {
            background: var(--oamis-soft, #f7f9fc) !important;
            font-weight: 700;
        }

        .completion-action-form {
            background: var(--oamis-soft, #f7f9fc);
            border: 1px solid var(--oamis-border, #dce3ea);
            border-radius: 12px;
            padding: 18px;
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
    </style>
@endpush

@push('scripts')
    <script>
        $(document).on('click', '.viewItems', function () {
            const projectCd = $(this).data('project');
            const projectName = $(this).data('name');
            const completionDate = $(this).attr('data-completion-date') || '';

            $('#completion_project_cd').val(projectCd);
            $('#modalTitle').text('Project Progress Details - ' + projectName);
            $('#completion_date').val(completionDate);
            $('#completion_action').val('');
            $('#completion_remarks').val('');
            $('#previousProgressContainer').empty();
            $('#officialsRemarksContainer').empty();
            $('#itemTableBody').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
            $('#financialTableBody').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
            $('#itemModal').modal('show');

            $.get("{{ url('/project-management/pms-item-wise-progress') }}/" + projectCd)
                .done(function (rows) {
                    let html = '';
                    $.each(rows, function (index, row) {
                        html += `<tr>
                                        <td>${row.item_name ?? ''}</td>
                                        <td>${row.quantity ?? 0} ${row.iow_unit ?? ''}</td>
                                        <td>${row.quantity_done ?? 0} ${row.iow_unit ?? ''}</td>
                                        <td>${row.progress_percent ?? 0}%</td>
                                    </tr>`;
                    });
                    $('#itemTableBody').html(html || '<tr><td colspan="4" class="text-center">No Data Found</td></tr>');
                });

            $.get("{{ url('/project-management/get-financial-progress') }}/" + projectCd)
                .done(function (rows) {
                    let html = '';
                    let totalWorkOrderAmount = 0;
                    let totalPayment = 0;
                    let remainingAmount = 0;

                    $.each(rows, function (index, row) {
                        totalWorkOrderAmount = parseFloat(row.work_order_amount) || 0;
                        totalPayment += parseFloat(row.payment_amount) || 0;
                        remainingAmount = parseFloat(row.remaining_amount) || 0;

                        html += `<tr>
                                        <td>${row.payment_date ?? ''}</td>
                                        <td>${row.payment_amount ?? 0}</td>
                                        <td>${row.payment_reference ?? ''}</td>
                                        <td>${row.remarks ?? ''}</td>
                                    </tr>`;
                    });

                    if (rows.length > 0) {
                        const paymentBase = totalPayment + remainingAmount;
                        const paymentProgress = paymentBase > 0 ? (totalPayment / paymentBase) * 100 : 0;

                        html += `<tr class="completion-financial-summary">
                                        <td colspan="3" class="text-right">Total Work Order Amount</td>
                                        <td>${totalWorkOrderAmount.toFixed(2)}</td>
                                    </tr>
                                    <tr class="completion-financial-summary">
                                        <td colspan="3" class="text-right">Total Payment</td>
                                        <td>${totalPayment.toFixed(2)}</td>
                                    </tr>
                                    <tr class="completion-financial-summary">
                                        <td colspan="3" class="text-right">Remaining Amount</td>
                                        <td>${remainingAmount.toFixed(2)}</td>
                                    </tr>
                                    <tr class="completion-financial-summary">
                                        <td colspan="3" class="text-right">Payment Progress</td>
                                        <td>${paymentProgress.toFixed(2)}%</td>
                                    </tr>`;
                    }

                    $('#financialTableBody').html(
                        html || '<tr><td colspan="4" class="text-center">No Financial Data Found</td></tr>'
                    );
                });
        });

        $('#viewPreviousProgress').on('click', function () {
            const projectCd = $('#completion_project_cd').val();

            if (!projectCd) {
                return;
            }

            $('#previousProgressContainer').html(
                '<div class="text-center text-muted py-3">Loading previous progress...</div>'
            );

            $.get("{{ url('/project-management/get-previous-progress') }}/" + projectCd)
                .done(function (response) {
                    const groups = response.data ?? {};
                    let html = '';

                    Object.keys(groups).forEach(function (key) {
                        const group = groups[key];
                        const header = group[0] ?? {};
                        const collapseId = 'previousProgress_' + key.toString().replace(/[^a-zA-Z0-9_-]/g, '_');
                        const isApproved = header.status === 'A';

                        html += `<div class="accordion mb-2">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                                                    <i class="fas fa-calendar-check mr-2"></i>
                                                    <span>${header.progress_date ?? 'Previous Progress'}</span>
                                                    <span class="badge ${isApproved ? 'bg-success' : 'bg-danger'} ml-2">
                                                        ${isApproved ? 'Approved' : 'Rejected'}
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="${collapseId}" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped text-sm mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Item</th>
                                                                    <th>Qty Done</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>`;

                        group.forEach(function (row) {
                            html += `<tr>
                                            <td>${row.item_name ?? ''}</td>
                                            <td>${row.quantity_done ?? 0} ${row.iow_unit ?? ''}</td>
                                        </tr>`;
                        });

                        html += `</tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                    });

                    $('#previousProgressContainer').html(
                        html || '<div class="text-center text-muted py-3">No previous progress found.</div>'
                    );
                })
                .fail(function () {
                    $('#previousProgressContainer').html(
                        '<div class="alert alert-danger mb-0">Unable to load previous progress.</div>'
                    );
                });
        });

        $('#viewOfficialsRemarks').on('click', function () {
            const projectCd = $('#completion_project_cd').val();

            if (!projectCd) {
                return;
            }

            const escapeHtml = function (value) {
                return $('<div>').text(value ?? '').html();
            };

            $('#officialsRemarksContainer').html(
                '<div class="text-center text-muted py-3">Loading officials remarks...</div>'
            );

            $.get("{{ url('/project-management/pms-completion-report') }}/" + projectCd + '/remarks')
                .done(function (rows) {
                    let html = '';

                    $.each(rows, function (index, row) {
                        html += `<tr>
                                        <td>${index + 1}</td>
                                        <td>${escapeHtml(row.official_name || '-')}</td>
                                        <td>${escapeHtml(row.designation_name || '-')}</td>
                                        <td>${escapeHtml(row.movement_status || '-')}</td>
                                        <td>${escapeHtml(row.officials_remark || '-')}</td>
                                        <td>${escapeHtml(row.movement_date || '-')}</td>
                                    </tr>`;
                    });

                    if (!html) {
                        $('#officialsRemarksContainer').html(
                            '<div class="alert alert-info mb-0">No officials remarks found.</div>'
                        );
                        return;
                    }

                    $('#officialsRemarksContainer').html(`
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-sm mb-0">
                                    <thead class="text-white" style="background-color: #417DBE;">
                                        <tr>
                                            <th>Sl No.</th>
                                            <th>Official</th>
                                            <th>Designation</th>
                                            <th>Movement Status</th>
                                            <th>Remarks</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>${html}</tbody>
                                </table>
                            </div>
                        `);
                })
                .fail(function () {
                    $('#officialsRemarksContainer').html(
                        '<div class="alert alert-danger mb-0">Unable to load officials remarks.</div>'
                    );
                });
        });

    </script>
@endpush
