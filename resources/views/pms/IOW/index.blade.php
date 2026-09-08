@extends('layouts.app')
@section('content')
    <main class="command-center">
        <div class="content-header">
            <div class="container-fluid" style="position: relative;">
                <div class="row text-sm">
                    <div class="col-sm-12 col-md-10">
                        <div class="command-breadcrumb">
                            <i class="fas fa-house"></i>
                            <span><a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">Home</a></span>
                            <span>/</span>
                            <span><a href="{{ url('project-management/manage-project?mode=create') }}"
                                    style="color: inherit; text-decoration: none;">Create Project</a></span>
                            <span>/</span>
                            <strong>Add Item of Work</strong>
                        </div>
                    </div>
                </div>
                {{-- alert section --}}
                <div id="alertContainer" style="text-align: right">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert"
                            style="position: absolute; top: 0; right: 0; z-index: 100;">
                            <strong> <i class="fa fa-check-circle mr-1"></i> Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close btn-xs" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-inline-block" role="alert"
                            style="position: absolute; top: 1px; right: 2px; z-index: 100;">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <section class="content px-3">
            {{-- Page Header --}}
            <div class="command-heading mb-4">
                <div>
                    <h1>
                        Item of Work
                    </h1>
                    <p>
                        Manage and assign work items, and quantites for project construction.
                    </p>
                </div>
                <div class="command-actions">
                    <a href="{{ url('project-management/manage-project?mode=create') }}"
                        class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to Create Project
                    </a>
                </div>
            </div>

            {{-- Card to show selected project details --}}
            <article class="command-panel mb-4">
                <header id="flip" class="pointer-cursor">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <span>Project Overview</span>
                            <h2>Selected Project Details <span style="color: #0d6efd;">(Click to know more)</span></h2>
                        </div>
                        <div class="text-muted"><i class="fas fa-chevron-down" id="flip-icon"></i></div>
                    </div>
                </header>
                <div id="panel" style="display: none;" class="p-3">
                    <div class="row g-3 text-sm">
                        <div class="col-sm-12 col-md-4">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">PROJECT NAME</div>
                                <div class="fw-bold">{{ $project->project_name ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">TYPE</div>
                                <div class="fw-bold">{{ $project->project_type ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">OWNER DEPT</div>
                                <div class="fw-bold">{{ $project->owner_department ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">DIVISION</div>
                                <div class="fw-bold">{{ $project->division_name ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="detail-item">
                                <div class="text-muted small mb-1">SUB DIVISION</div>
                                <div class="fw-bold">{{ $project->sub_div_name ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            {{-- create form --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>New Entry</span>
                        <h2>Add Item of Work</h2>
                    </div>
                </header>
                <div class="p-3">
                    @include('pms.IOW.partials._form', [
                        'formAction' => route('pms.work-item.store', $project->project_cd),
                        'isEdit' => false,
                        'currentItem' => null,
                    ])
                </div>
            </article>

            {{-- Data table --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Registry</span>
                        <h2>Items of Work List</h2>
                    </div>
                </header>
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table table-hover w-100 user_list" id="office_table">
                            <thead>
                                <tr>
                                    <th>Item of Work</th>
                                    <th>Item Quantity</th>
                                    <th>Item Unit</th>
                                    {{-- <th>Start Date</th> --}}
                                    {{-- <th>End Date</th> --}}
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($projectIowDetails as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->item_name }}</strong>
                                            @if (isset($item->sub_items) && $item->sub_items->count() > 0)
                                                <div class="mt-1 d-flex flex-wrap gap-1">
                                                    @foreach ($item->sub_items as $sub)
                                                        <span class="sub-item-badge">
                                                            <i class="fa-solid fa-circle-notch text-primary me-1"
                                                                style="font-size: 8px;"></i>
                                                            {{ $sub->sub_item_name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-primary">{{ $item->quantity }}</td>
                                        <td><span
                                                class="badge bg-secondary text-light">{{ $item->unit ?? $item->unit_cd }}</span>
                                        </td>
                                        {{-- <td>{{ $item->est_start_date ? \Carbon\Carbon::parse($item->est_start_date)->format('d M Y') : '—' }}
                                        </td>
                                        <td>{{ $item->est_end_date ? \Carbon\Carbon::parse($item->est_end_date)->format('d M Y') : '—' }}
                                        </td> --}}
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                {{-- work plan --}}
                                                <a href="{{ route('pms.work-plan.index', [$project->project_cd, $item->id]) }}"
                                                    class="btn btn-success btn-xs text-dark" title="Work Plans">
                                                    <i class="fas fa-calendar"></i> Work Plans
                                                </a>

                                                {{-- Edit --}}
                                                <a href="{{ route('pms.work-item.edit', [$project->project_cd, $item->id]) }}"
                                                    class="btn btn-warning btn-xs text-dark" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                {{-- Delete --}}
                                                <form
                                                    action="{{ route('pms.work-item.destroy', [$project->project_cd, $item->id]) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this item?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs text-light"
                                                        title="Delete">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No items of work found for this
                                            project.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </article>
        </section>
    </main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wings/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/selectOptionStyleSheet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
    <style>
        .detail-item {
            padding: 10px 14px;
            background: var(--oamis-soft);
            border: 1px solid var(--oamis-border);
            border-radius: 8px;
            height: 100%;
        }

        .detail-item .text-muted {
            color: var(--oamis-muted) !important;
        }

        .detail-item .fw-bold {
            color: var(--oamis-ink);
        }

        .command-center {
            color: var(--oamis-ink);
        }

        .table th,
        .table td {
            font-size: 14px !important;
        }

        .badge {
            font-size: 11px !important;
            padding: 5px 9px !important;
        }

        .gap-1 {
            gap: 0.25rem !important;
        }

        .pointer-cursor {
            cursor: pointer;
        }

        .sub-item-badge {
            font-size: 11px;
            font-weight: 500;
            padding: 2px 8px;
            background-color: rgba(11, 107, 74, 0.08);
            border: 1px solid rgba(11, 107, 74, 0.2);
            color: var(--oamis-primary, #0b6b4a);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            var checkedSubItemCds = @json(old('sub_item_cd', []));
            checkedSubItemCds = checkedSubItemCds.map(Number);

            $("#flip").click(function() {
                $("#panel").slideToggle("slow", function() {
                    let isVisible = $("#panel").is(":visible");
                    if (isVisible) {
                        $("#flip-icon").removeClass("fa-chevron-down").addClass("fa-chevron-up");
                    } else {
                        $("#flip-icon").removeClass("fa-chevron-up").addClass("fa-chevron-down");
                    }
                });
            });

            // script to get unit of selected item of work and set it in unit input field
            $('#item_cd').change(function() {
                var unit = $(this).find('option:selected').data('unit');
                $('#unit').val(unit);
            });

            function fetchSubItems(itemId) {
                var $container = $('#sub_items_container');
                var $checkboxes = $('#sub_items_checkboxes');

                $checkboxes.empty();
                $container.hide();

                if (itemId) {
                    var url = "{{ route('pms.work-item.sub-items', ':id') }}";
                    url = url.replace(':id', itemId);

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success' && response.subitems && response.subitems
                                .length > 0) {
                                $.each(response.subitems, function(index, subitem) {
                                    var isChecked = checkedSubItemCds.includes(Number(subitem
                                        .sub_item_cd)) ? 'checked' : '';
                                    var checkboxHtml = `
                                        <div class="form-check sub-item-card me-2 mb-2">
                                            <input class="form-check-input" type="checkbox" name="sub_item_cd[]" id="sub_item_${subitem.sub_item_cd}" value="${subitem.sub_item_cd}" ${isChecked}>
                                            <label class="form-check-label" for="sub_item_${subitem.sub_item_cd}">
                                                ${subitem.sub_item_name}
                                            </label>
                                        </div>
                                    `;
                                    $checkboxes.append(checkboxHtml);
                                });
                                $container.show();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Failed to fetch sub-items:', error);
                        }
                    });
                }
            }

            // On change event
            $('#item_cd').change(function() {
                fetchSubItems($(this).val());
            });

            // On load event for old input / prefilled values
            var initialItem = $('#item_cd').val();
            if (initialItem) {
                fetchSubItems(initialItem);
            }
        });
    </script>
@endpush
