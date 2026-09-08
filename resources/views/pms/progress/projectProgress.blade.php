@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <ol class="breadcrumb float-sm-left text-sm">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pms.project.list') }}">Project List</a></li>
            <li class="breadcrumb-item active">Project Progress</li>
        </ol>
    </div>
</div>

<section class="content mt-2">
<br>
    {{-- PROJECT SUMMARY --}}
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="section-title">Project Summary</h6>
            <div class="row">
                @foreach([
                    'Project Name'  => $project->project_name,
                    'Division'      => $project->division_name,
                    'Sub Division'  => $project->sub_div_name,
                    'Department'    => $project->department_name,
                    'Start Date'    => $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d-M-Y') : 'N/A',
                    'End Date'      => $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d-M-Y') : 'N/A',
                ] as $label => $value)
                <div class="col-md-3 col-6 mb-2">
                    <small class="text-muted d-block">{{ $label }}</small>
                    <strong class="text-dark" style="font-size:13px;">{{ $value }}</strong>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- PHYSICAL PROGRESS --}}
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="section-title">Physical Progress (Item Wise)</h6>

            @php $items = $project->work_item_details ?? []; @endphp

            @if(count($items) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead class="thead-custom">
                        <tr>
                            <th width="4%">#</th>
                            <th width="26%">Item Name</th>
                            <th width="12%">Est. Qty</th>
                            <th width="12%">Completed Qty</th>
                            <th width="12%">Remaining Qty</th>
                            <th width="22%">Progress Qty</th>
                            <th width="12%">History</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            @php
                                $est  = (float)($item['quantity'] ?? 0);
                                $done = (float)($item['quantity_cumulative_progess'] ?? 0);
                                $rem  = max($est - $done, 0);
                                $completed = $done >= $est && $est > 0;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="text-primary fw-bold" style="font-size:12px;">
                                        {{ $item['item_name'] }}
                                    </span>
                                    @if($completed)
                                        <span class="badge badge-success ml-1">Completed</span>
                                    @endif
                                </td>
                                <td>{{ $est }} {{ $item['unit_cd'] }}</td>
                                <td>{{ $done }} {{ $item['unit_cd'] }}</td>
                                <td class="text-danger font-weight-bold">{{ $rem }} {{ $item['unit_cd'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input
                                            type="number"
                                            class="form-control form-control-sm progress-input"
                                            placeholder="Enter Qty"
                                            min="0"
                                            max="{{ $rem }}"
                                            step="{{ strtoupper($item['unit_cd']) === 'NOS' ? '1' : '0.01' }}"
                                            data-item-id="{{ $item['item_id'] }}"
                                            data-item-cd="{{ $item['item_cd'] }}"
                                            data-boq-item-id="{{ $item['boq_item_id'] }}"
                                            data-estimated="{{ $est }}"
                                            data-completed="{{ $done }}"
                                            data-remaining="{{ $rem }}"
                                            data-unit="{{ strtoupper($item['unit_cd']) }}"
                                            {{ $completed ? 'disabled' : '' }}
                                            style="max-width:120px; text-align:center;"
                                        >
                                        <span class="ml-2 text-primary font-weight-bold" style="font-size:12px; min-width:30px;">
                                            {{ $item['unit_cd'] }}
                                        </span>
                                    </div>
                                    <small class="text-danger validation-msg" style="display:none; font-size:10px;"></small>
                                </td>
                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary btn-history"
                                        data-item-cd="{{ $item['item_id'] }}"
                                        data-item-name="{{ $item['item_name'] }}"
                                        data-item-unit="{{ $item['unit_name'] ?? $item['unit_cd'] }}"
                                        data-item-unit-cd="{{ $item['unit_cd'] }}"
                                        data-total-qty="{{ $est }}"
                                        title="View History">
                                        <i class="fas fa-history"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="alert alert-warning mb-0">No work items found.</div>
            @endif
        </div>
    </div>

    {{-- STATUS & REMARKS --}}
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="section-title">Status &amp; Remarks</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Current Status</label>
                        <select name="current_status" id="current_status" class="form-control form-control-sm">
                            <option value="on_track">On Track</option>
                            <option value="delayed">Delayed</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Remarks</label>
                        <textarea class="form-control form-control-sm" id="remarks" rows="3" placeholder="Enter remarks"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PHOTOS --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="section-title mb-0">Project Progress Photos</h6>
                <button type="button" class="btn btn-sm btn-primary" id="btnTakePhoto">
                    <i class="fas fa-camera mr-1"></i>Upload Photo
                </button>
            </div>

            {{-- Notice --}}
            <div class="alert mb-3 py-2 px-3 d-flex align-items-start"
                style="background:#fff8e1; border:1px solid #ffe082; border-left:4px solid #f9a825; border-radius:4px; font-size:12px; color:#5d4037;">
                <i class="fas fa-info-circle mr-2 mt-1" style="color:#f9a825; flex-shrink:0;"></i>
                <div>
                    <strong>Photo upload requirements:</strong>
                    Only <strong>JPG, JPEG</strong> and <strong>PNG</strong> files are accepted.
                    Each photo must not exceed <strong>1 MB</strong> in size.
                    Invalid files will be skipped automatically.
                </div>
            </div>

            <input type="file" id="photoInput" accept=".jpg,.jpeg,.png,image/jpeg,image/png" multiple hidden>
            <div id="photoErrorBox" class="alert alert-danger py-2 px-3 d-none" style="font-size:12px;"></div>
            <div class="row" id="photoPreviewContainer"></div>
        </div>
    </div>

    {{-- SUBMIT --}}
        <div class="card-body text-right">
            <button type="button" class="btn btn-success" id="btnSubmit">
                <i class="fas fa-paper-plane mr-1"></i>Submit Progress
            </button>
        </div>

    {{-- HISTORY MODAL --}}
    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">

                <div class="modal-header" style="background:#1f3c88; padding:10px 16px;">
                    <div>
                        <h6 class="modal-title text-white mb-0" id="historyModalLabel">Progress History</h6>
                        <small class="text-white-50" id="historyItemName"></small>
                    </div>
                    <button type="button" class="close text-white" id="historyModalClose" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-3">

                    {{-- Item info strip --}}
                    <div id="historyInfoStrip" class="d-none mb-3 px-3 py-2 rounded"
                        style="background:#eaf0fb; border-left:4px solid #1f3c88;">
                        <div class="d-flex justify-content-between">
                            <span style="font-size:12px; color:#555;">
                                Unit: <strong id="historyUnit" style="color:#1f3c88;"></strong>
                            </span>
                            <span style="font-size:12px; color:#555;">
                                Total Qty: <strong id="historyTotalQty" style="color:#1f3c88;"></strong>
                            </span>
                        </div>
                    </div>

                    {{-- Loading --}}
                    <div id="historyLoading" class="text-center py-4 d-none">
                        <i class="fas fa-spinner fa-spin fa-lg" style="color:#1f3c88;"></i>
                        <p class="mt-2 text-muted" style="font-size:13px;">Loading history...</p>
                    </div>

                    {{-- Empty state --}}
                    <div id="historyEmpty" class="text-center py-4 d-none">
                        <i class="fas fa-inbox fa-2x text-muted"></i>
                        <p class="mt-2 text-muted" style="font-size:13px;">No submission history found.</p>
                    </div>

                    {{-- Table --}}
                    <div id="historyTableWrap" class="d-none table-responsive">
                        <table class="table table-bordered table-sm mb-0" style="font-size:12px;">
                            <thead style="background:#1f3c88; color:#fff;">
                                <tr>
                                    <th width="5%"  class="text-center">#</th>
                                    <th width="35%">Date</th>
                                    <th width="25%" class="text-right">Qty</th>
                                    <th width="35%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody"></tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" id="historyModalCloseFooter">Close</button>
                </div>

            </div>
        </div>
    </div>

</section>

@endsection


@push('styles')
<style>
.section-title {
    color: #003f87;
    font-weight: 700;
    font-size: 14px;
    margin-bottom: 12px;
}

.thead-custom {
    background: #437fc0;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    text-align: center;
}

.thead-custom th {
    vertical-align: middle;
    border-color: #3a6ea8;
}

.table-sm td {
    font-size: 11px;
    vertical-align: middle;
}

.photo-thumb {
    position: relative;
    margin-bottom: 12px;
}

.photo-thumb img {
    width: 100%;
    height: 110px;
    object-fit: cover;
    border: 1px solid #ccc;
    border-radius: 3px;
}

.photo-thumb .btn-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #dc3545;
    color: #fff;
    border: none;
    font-size: 11px;
    line-height: 1;
    cursor: pointer;
    padding: 0;
}

/* History modal */
#historyModal .modal-header .close { opacity: 1; }

.badge-approved  { background:#2e7d32; color:#fff; }
.badge-submitted { background:#e65100; color:#fff; }
.badge-rejected  { background:#c62828; color:#fff; }

.history-badge {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
}
</style>
@endpush


@push('scripts')
<script>
let capturedFiles = [];

    $(document).ready(function () {

    // ── Inline Validation ──────────────────────────────────────────
    $(document).on('input', '.progress-input', function () {
        const $input    = $(this);
        const val       = $input.val();
        const entered   = parseFloat(val);
        const estimated = parseFloat($input.data('estimated')) || 0;
        const remaining = parseFloat($input.data('remaining')) || 0;
        const isNOS     = $input.data('unit') === 'NOS';
        const $msg      = $input.closest('td').find('.validation-msg');

        $msg.hide().text('');

        if (val === '' || isNaN(entered)) return;

        if (entered < 0) {
            $msg.show().text('Quantity cannot be negative.');
            $input.val('');
            return;
        }

        if (isNOS && !Number.isInteger(entered)) {
            $msg.show().text('Only whole numbers allowed for NOS unit.');
            $input.val(Math.floor(entered) || '');
            return;
        }

        if (entered > estimated) {
            $msg.show().text('Cannot exceed estimated quantity (' + estimated + ').');
            $input.val('');
            return;
        }

        if (entered > remaining) {
            $msg.show().text('Maximum allowed is ' + remaining + '.');
            $input.val('');
        }
    });

    // ── Photo Upload ───────────────────────────────────────────────
    const ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png'];
    const MAX_SIZE_MB   = 1;
    const MAX_SIZE_BYTES = MAX_SIZE_MB * 1024 * 1024;

    $('#btnTakePhoto').on('click', function () {
        $('#photoInput').trigger('click');
    });

    $('#photoInput').on('change', function () {
        const files    = Array.from(this.files);
        const rejected = [];

        $('#photoErrorBox').addClass('d-none').html('');

        files.forEach(function (file) {
            const ext      = file.name.split('.').pop().toLowerCase();
            const typeOk   = ALLOWED_TYPES.includes(file.type);
            const sizeOk   = file.size <= MAX_SIZE_BYTES;
            const sizeMB   = (file.size / 1024 / 1024).toFixed(2);

            if (!typeOk) {
                rejected.push(`<strong>${file.name}</strong> — invalid type (only JPG, JPEG, PNG allowed).`);
                return;
            }
            if (!sizeOk) {
                rejected.push(`<strong>${file.name}</strong> — file too large (${sizeMB} MB, max 1 MB).`);
                return;
            }

            // Valid file — add preview
            const idx = capturedFiles.length;
            capturedFiles.push(file);

            const reader = new FileReader();
            reader.onload = function (e) {
                const html = `
                    <div class="col-md-3 col-6 photo-thumb" id="photo-${idx}">
                        <img src="${e.target.result}" alt="preview">
                        <button type="button" class="btn-remove" data-index="${idx}" title="Remove">×</button>
                        <small class="d-block text-muted mt-1" style="font-size:10px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">
                            ${file.name}
                        </small>
                    </div>`;
                $('#photoPreviewContainer').append(html);
            };
            reader.readAsDataURL(file);
        });

        // Show rejected errors
        if (rejected.length > 0) {
            $('#photoErrorBox')
                .removeClass('d-none')
                .html(
                    '<i class="fas fa-exclamation-triangle mr-1"></i>'
                    + '<strong>Some files were not added:</strong><ul class="mb-0 mt-1 pl-3">'
                    + rejected.map(r => `<li>${r}</li>`).join('')
                    + '</ul>'
                );
        }

        $(this).val('');
    });

    $(document).on('click', '.btn-remove', function () {
        const idx = $(this).data('index');
        capturedFiles[idx] = null;
        $('#photo-' + idx).remove();
    });

    // ── History Modal Close ────────────────────────────────────────
    function closeHistoryModal() {
        $('#historyModal').modal('hide');
    }
    $('#historyModalClose, #historyModalCloseFooter').on('click', closeHistoryModal);

    // Fallback: close on backdrop click in case AdminLTE interferes
    $('#historyModal').on('click', function (e) {
        if ($(e.target).is('#historyModal')) closeHistoryModal();
    });

    // ESC key
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') closeHistoryModal();
    });


    $(document).on('click', '.btn-history', function () {
        const $btn      = $(this);
        const itemCd    = $btn.data('item-cd');
        const itemName  = $btn.data('item-name');
        const itemUnit  = $btn.data('item-unit');
        const itemUnitCd= $btn.data('item-unit-cd');
        const totalQty  = $btn.data('total-qty');

        // Reset modal state
        $('#historyModalLabel').text('Progress History');
        $('#historyItemName').text(itemName);
        $('#historyInfoStrip').addClass('d-none');
        $('#historyLoading').addClass('d-none');
        $('#historyEmpty').addClass('d-none');
        $('#historyTableWrap').addClass('d-none');
        $('#historyTableBody').empty();

        $('#historyModal').modal('show');
        $('#historyLoading').removeClass('d-none');

        $.ajax({
            url: "{{ route('pms.progress.history') }}",
            type: 'GET',
            data: {
                proj_cd: "{{ $project->project_cd }}",
                item_cd: itemCd
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#historyLoading').addClass('d-none');

                const history = res?.progress_history ?? [];

                // Info strip
                $('#historyUnit').text(itemUnit || itemUnitCd);
                $('#historyTotalQty').text(totalQty + ' ' + itemUnitCd);
                $('#historyInfoStrip').removeClass('d-none');

                if (history.length === 0) {
                    $('#historyEmpty').removeClass('d-none');
                    return;
                }

                // Build rows
                history.forEach(function (entry, idx) {
                    const status = entry.submission_status ?? '';
                    let badgeClass, badgeLabel;

                    if (status === 'Approved') {
                        badgeClass = 'badge-approved';
                        badgeLabel = '✔ Approved';
                    } else if (status === 'Submitted') {
                        badgeClass = 'badge-submitted';
                        badgeLabel = '⏳ Submitted';
                    } else {
                        badgeClass = 'badge-rejected';
                        badgeLabel = '✘ Rejected';
                    }

                    const rowBg = idx % 2 === 0 ? '#f4f8ff' : '#fff';

                    const row = `
                        <tr style="background:${rowBg};">
                            <td class="text-center">${idx + 1}</td>
                            <td>${entry.project_submission_date ? entry.project_submission_date.split(' ')[0].split('-').reverse().join('-') : '-'}</td>
                            <td class="text-right font-weight-bold" style="color:#1f3c88;">
                                ${entry.submitted_quantity ?? '-'} ${itemUnitCd}
                            </td>
                            <td class="text-center">
                                <span class="history-badge ${badgeClass}">${badgeLabel}</span>
                            </td>
                        </tr>`;
                    $('#historyTableBody').append(row);
                });

                $('#historyTableWrap').removeClass('d-none');
            },
            error: function (xhr) {
                $('#historyLoading').addClass('d-none');
                $('#historyEmpty').removeClass('d-none').find('p').text('Failed to load history.');
                console.error(xhr);
            }
        });
    });


    $('#btnSubmit').on('click', async function () {
        const $btn = $(this);
        try {
            const payload = await buildProgressPayload();

            $.ajax({
                url: "{{ route('pms.progress.submit') }}",
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(payload),
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                beforeSend: function () {
                    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Submitting...');
                },
                success: function (res) {
                    if (res.status === true) {
                        alert('Progress submitted successfully.');
                        setTimeout(function () {
                            window.location.href = "{{ route('pms.project.list') }}";
                        }, 1500);
                    } else {
                        console.error(res);
                        alert('Submission failed. Please try again.');
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    alert('Error: ' + (xhr.responseText || 'Something went wrong.'));
                },
                complete: function () {
                    $btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-1"></i>Submit Progress');
                }
            });

        } catch (err) {
            console.error(err);
            alert('Error preparing submission payload.');
        }
    });

});

// ── Payload Builder ────────────────────────────────────────────────
async function buildProgressPayload() {
    const images = await convertImagesToBase64();

    const physical_progress = [];
    $('.progress-input').each(function () {
        const val = parseFloat($(this).val());
        if (!isNaN(val) && val > 0) {
            physical_progress.push({
                item_id:        $(this).data('item-id'),
                item_cd:        $(this).data('item-cd'),
                progress_qty:   val,
                boq_item_id:    $(this).data('boq-item-id'),
                boq_progress_qty: 0
            });
        }
    });

    return {
        proj_cd:          "{{ $project->project_cd }}",
        status:           $('#current_status').val(),
        remarks:          $('#remarks').val(),
        uid:              "{{ auth()->id() }}",
        images,
        physical_progress
    };
}

// ── Image Helpers ──────────────────────────────────────────────────
async function convertImagesToBase64() {
    const validFiles = capturedFiles.filter(Boolean);
    const results = [];
    for (const file of validFiles) {
        results.push({ img: await fileToBase64(file) });
    }
    return results;
}

function fileToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload  = () => resolve(reader.result.split(',')[1]);
        reader.onerror = reject;
    });
}
</script>
@endpush
