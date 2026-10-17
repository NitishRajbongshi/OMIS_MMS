{{-- Save as: resources/views/mmis/inspection/index.blade.php --}}
@extends('layouts.app')

@section('content')

<style>
    /* Keep the column widths set in <th> */
    #inspectionTable {
        table-layout: fixed;
    }

    #inspectionTable td {
        vertical-align: top;
    }

    /* Wrap long text inside the column */
    #inspectionTable td.wrap-text {
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: anywhere;
    }

    /* Sub table area is hidden until the button is clicked */
    .sub-content {
        display: none;
        margin-top: 8px;
    }

    .sub-table th,
    .sub-table td {
        font-size: 13px;
        padding: 4px 8px;
    }

    /* Small arrow on the button that turns when expanded */
    .toggle-btn .arrow {
        display: inline-block;
        transition: transform 0.2s;
    }
    .toggle-btn.open .arrow {
        transform: rotate(90deg);
    }
</style>

<div class="container-fluid py-4">
    <h4 class="mb-3">Inspections</h4>

    {{-- Choose the asset type: the table below reloads by AJAX (no page reload) --}}
    <div class="row g-2 align-items-center mb-3">
        <div class="col-auto">
            <label for="asset_type" class="col-form-label fw-bold">Asset Type</label>
        </div>
        <div class="col-auto">
            <select id="asset_type" class="form-select form-select-sm">
                @foreach ($assetTypes as $type)
                    <option value="{{ $type->sub_asset_cd }}"
                        {{ (string) $selectedType === (string) $type->sub_asset_cd ? 'selected' : '' }}>
                        {{ $type->sub_assets_descr }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Empty table: DataTables fills it by AJAX --}}
    <table id="inspectionTable" class="table table-bordered bg-white" style="width:100%">
        <thead class="table-dark">
            <tr>
                <th style="width:15%">Asset Type</th>
                <th style="width:15%">Asset ID</th>
                <th style="width:25%">Road Name</th>
                <th style="width:45%">Inspection</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        // Make text safe before putting it into HTML
        function esc(text) {
            return $('<div>').text(text == null ? '' : text).html();
        }

        // Start DataTable. The rows come from the server by AJAX.
        var table = $('#inspectionTable').DataTable({
            pageLength: 10,
            autoWidth: false,
            processing: true,   // shows "Processing..." while loading
            ajax: {
                url: "{{ route('mmis.inspection.assets') }}",
                // send the selected asset type with every request
                data: function (d) {
                    d.asset_type = $('#asset_type').val();
                }
            },
            columns: [
                { data: 'asset_type', className: 'wrap-text', render: $.fn.dataTable.render.text() },
                { data: 'asset_id',   className: 'wrap-text', render: $.fn.dataTable.render.text() },
                { data: 'asset_name', className: 'wrap-text', render: $.fn.dataTable.render.text() },
                {
                    // Button + empty box for the sub table
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return '<button type="button" class="btn btn-sm btn-primary toggle-btn" data-url="' + esc(row.latest_url) + '">'
                             + '<span class="arrow">&#9654;</span> Inspections (' + esc(row.total) + ')'
                             + '</button>'
                             + '<div class="sub-content"></div>';
                    }
                }
            ]
        });

        // When the asset type changes: reload only the table data
        $('#asset_type').on('change', function () {
            table.ajax.reload();
        });

        // Build the sub table from the data sent by the server
        function buildTable(res) {
            var html = '<table class="table table-sm table-bordered mb-0 sub-table">';
            html += '<thead class="table-light"><tr>';
            html += '<th>Inspection ID</th><th>Inspection Date</th><th style="width:140px">Actions</th>';
            html += '</tr></thead><tbody>';

            $.each(res.items, function (i, item) {
                html += '<tr>';
                html += '<td>' + esc(item.insp_cd) + '</td>';
                html += '<td>' + esc(item.insp_date) + '</td>';
                html += '<td>';
                html += '<a href="' + esc(item.show_url) + '" class="btn btn-sm btn-outline-success py-0">View all details</a> ';
                html += '<a href="' + esc(item.pdf_url) + '" class="btn btn-sm btn-outline-secondary mt-1"><i class="fa fa-file-pdf"></i> Generate Report</a>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody></table>';

            // More than 3 inspections: link to the full list page
            if (res.total > 3) {
                html += '<div class="mt-2">';
                html += '<a href="' + esc(res.list_url) + '" class="btn btn-sm btn-link p-0">View all inspections (' + esc(res.total) + ') &raquo;</a>';
                html += '</div>';
            }

            return html;
        }

        // Click on "Inspections (N)" button
        // (attached to the table so it keeps working after paging, searching and reloading)
        $('#inspectionTable tbody').on('click', '.toggle-btn', function () {
            var btn = $(this);
            var box = btn.siblings('.sub-content');

            // already open: close it
            if (btn.hasClass('open')) {
                btn.removeClass('open');
                box.slideUp(200);
                return;
            }

            // open it
            btn.addClass('open');
            box.slideDown(200);

            // already loaded before: nothing more to do
            if (box.data('loaded')) {
                return;
            }

            // load the latest 3 inspections from the server (only the first time)
            box.html('<div class="text-muted small">Loading...</div>');

            $.get(btn.data('url'))
                .done(function (res) {
                    box.html(buildTable(res));
                    box.data('loaded', true);
                })
                .fail(function () {
                    box.html('<div class="text-danger small">Could not load inspections. Click the button twice to try again.</div>');
                });
        });

    });
</script>
@endpush