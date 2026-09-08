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
                            <strong>Work Plan</strong>
                        </div>
                    </div>
                </div>

                {{-- Flash alerts --}}
                <div id="alertContainer" style="text-align: right">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert"
                            style="position: absolute; top: 0; right: 0; z-index: 100;">
                            <strong><i class="fa fa-check-circle me-1"></i> Success!</strong> {{ session('success') }}
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
                    <h1>Work Plan</h1>
                    <p>Manage and assign work plan details for the project.</p>
                </div>
                <div class="command-actions">
                    <a href="{{ url('project-management/manage-project?mode=create') }}"
                        class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to Create Project
                    </a>
                </div>
            </div>

            {{-- Project Overview --}}
            <article class="command-panel mb-4">
                <header id="flip" class="pointer-cursor">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <span>Project Overview</span>
                            <h2>Selected Project Details</h2>
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

            {{-- Create form --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>New Entry</span>
                        <h2>Add Work Plan Details</h2>
                    </div>
                </header>
                <div class="p-3">
                    @include('pms.workPlan.partials._form', [
                        'formAction' => route('pms.work-plan.store', [$project->project_cd, $workItem->id]),
                        'isEdit' => false,
                        'currentItem' => null,
                    ])
                </div>
            </article>

            {{-- Data Table --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Records</span>
                        <h2>Work Plan Entries</h2>
                    </div>
                </header>
                <div class="p-3">
                    @if ($workPlans->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-alt fa-2x mb-2 d-block opacity-50"></i>
                            No work plan entries found. Add one using the form above.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Estimated Start Date</th>
                                        <th>Estimated End Date</th>
                                        <th>Work Plan Predecessor</th>
                                        <th class="text-center" style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($workPlans as $index => $plan)
                                        <tr>
                                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($plan->plan_start_date)->format('d M Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($plan->plan_end_date)->format('d M Y') }}</td>
                                            <td>{{ $plan->item_name ?? 'No predecessor' }}</td>
                                            <td class="text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    {{-- Edit: opens modal --}}
                                                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                                        title="Edit" data-id="{{ $plan->id }}"
                                                        data-precedence="{{ $plan->wid_precedence_item_cd }}"
                                                        data-start="{{ \Carbon\Carbon::parse($plan->plan_start_date)->format('Y-m-d') }}"
                                                        data-end="{{ \Carbon\Carbon::parse($plan->plan_end_date)->format('Y-m-d') }}"
                                                        data-url="{{ route('pms.work-plan.update', [$project->project_cd, $workItem->id, $plan->id]) }}">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>

                                                    {{-- Delete --}}
                                                    <form method="POST"
                                                        action="{{ route('pms.work-plan.destroy', [$project->project_cd, $workItem->id, $plan->id]) }}"
                                                        onsubmit="return confirm('Delete this work plan entry?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </article>

        </section>
    </main>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Work Plan Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4">
                                <label class="form-label" for="edit_wid_precedence_item_cd">
                                    Item Predecesor <span class="text-danger">*</span>
                                </label>
                                <select name="wid_precedence_item_cd" id="edit_wid_precedence_item_cd"
                                    class="form-select form-select-sm">
                                    <option value="">No predecessor</option>
                                    @foreach ($listOfPredessor as $predecessor)
                                        <option value="{{ $predecessor->item_cd }}">
                                            {{ $predecessor->item_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <label class="form-label" for="edit_plan_start_date">
                                    Start Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="plan_start_date" id="edit_plan_start_date"
                                    class="form-control form-control-sm" required />
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <label class="form-label" for="edit_plan_end_date">
                                    End Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="plan_end_date" id="edit_plan_end_date"
                                    class="form-control form-control-sm" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            <i class="fa fa-save me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
    </style>
@endpush

@push('scripts')
    <script>
        // ── Project overview flip ────────────────────────────────────────────
        document.getElementById('flip').addEventListener('click', function() {
            const panel = document.getElementById('panel');
            const icon = document.getElementById('flip-icon');
            const open = panel.style.display !== 'none';
            panel.style.display = open ? 'none' : 'block';
            icon.classList.toggle('fa-chevron-down', open);
            icon.classList.toggle('fa-chevron-up', !open);
        });

        // ── Edit modal ───────────────────────────────────────────────────────
        document.querySelectorAll('.btn-edit').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('edit_wid_precedence_item_cd').value = this.dataset.precedence;
                document.getElementById('edit_plan_start_date').value = this.dataset.start;
                document.getElementById('edit_plan_end_date').value = this.dataset.end;
                document.getElementById('editForm').action = this.dataset.url;

                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
        });
    </script>
@endpush
