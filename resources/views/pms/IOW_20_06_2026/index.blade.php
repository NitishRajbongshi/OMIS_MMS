@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid" style="position: relative;">
            <div class="row text-sm">
                <div class="col-sm-12 col-md-10">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('project-management/manage-project?mode=create') }}">Create Project</a>
                        </li>
                        <li class="breadcrumb-item">Add Item of Work</li>
                    </ol>
                </div>
            </div>
            {{-- alert section --}}
            <div id="alertContainer" style="text-align: right">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert"
                        style="position: absolute; top: 0; right: 0; z-index: 1;">
                        <strong> <i class="fa fa-check-circle mr-1"></i> Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close btn-xs" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-inline-block" role="alert"
                        style="position: absolute; top: 1px; right: 2px; z-index: 1;">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <section class="content">
        {{-- Card to show selected project details --}}
        <div id="flip"
            style="cursor: pointer; padding: 6px 12px; background: #003366; color: white; border-radius: 3px; font-size: 13px; font-weight: 500; display: inline-block; margin-bottom: 8px; border-left: 3px solid #ff9933;">
            <i class="fas fa-chevron-down" style="margin-right: 5px; font-size: 11px;"></i>View Selected Project Details
        </div>
        <div id="panel"
            style="display: none; background: #f5f5f5; border-radius: 3px; padding: 10px; margin-top: 5px; border: 1px solid #99b3cc;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;">
                <div style="background: #fff; padding: 6px 8px; border-left: 3px solid #003366;">
                    <span style="font-size: 9px; color: #555; display: block;">PROJECT NAME</span>
                    <span style="font-size: 11px; font-weight: 500;">{{ $project->project_name ?? '—' }}</span>
                </div>

                <div style="background: #fff; padding: 6px 8px; border-left: 3px solid #003366;">
                    <span style="font-size: 9px; color: #555; display: block;">TYPE</span>
                    <span style="font-size: 11px; font-weight: 500;">{{ $project->project_type ?? '—' }}</span>
                </div>

                <div style="background: #fff; padding: 6px 8px; border-left: 3px solid #003366;">
                    <span style="font-size: 9px; color: #555; display: block;">OWNER DEPT</span>
                    <span style="font-size: 11px; font-weight: 500;">{{ $project->owner_department ?? '—' }}</span>
                </div>

                <div style="background: #fff; padding: 6px 8px; border-left: 3px solid #003366;">
                    <span style="font-size: 9px; color: #555; display: block;">DIVISION</span>
                    <span style="font-size: 11px; font-weight: 500;">{{ $project->division_name ?? '—' }}</span>
                </div>

                <div style="background: #fff; padding: 6px 8px; border-left: 3px solid #003366;">
                    <span style="font-size: 9px; color: #555; display: block;">SUB DIVISION</span>
                    <span style="font-size: 11px; font-weight: 500;">{{ $project->sub_div_name ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- create form --}}
        <div
            style="background: #f5f5f5; border-radius: 3px; padding: 12px; border: 1px solid #99b3cc; font-family: 'Segoe UI', Arial, sans-serif;">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 2px solid #003366;">
                <h3 style="margin: 0; font-size: 14px; color: #003366; font-weight: 600; text-transform: uppercase;">
                    Add Item of Work
                </h3>
            </div>

            @include('pms.IOW.partials._form', [
                'formAction' => route('pms.work-item.store', $project->project_cd),
                'isEdit' => false,
                'currentItem' => null,
            ])
        </div>

        {{-- Data table --}}
        <table class="text-xs table table-bordered table-striped user_list" id="office_table">
            <thead class="theader text-white" style="background-color:#003366">
                <th>Item of Work</th>
                <th>Item Quantity</th>
                <th>Item Unit</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>
            </thead>
            <tbody>
                @foreach ($projectIowDetails as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ $item->est_start_date }}</td>
                        <td>{{ $item->est_end_date }}</td>
                        <td>
                            {{-- Edit --}}
                            <a href="{{ route('pms.work-item.edit', [$project->project_cd, $item->id]) }}" class="inline-block">
                                <button
                                    class="text-xs outline-0 px-2 btn btn-xs btn-outline-secondary inline fw-bold rounded-0">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('pms.work-item.destroy', [$project->project_cd, $item->id]) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger px-2 fw-bold rounded-0">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection
@push('styles')
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#flip").click(function() {
                $("#panel").slideToggle("slow");
            });

            // script to get unit of selected item of work and set it in unit input field
            $('#item_cd').change(function() {
                var unit = $(this).find('option:selected').data('unit');
                $('#unit').val(unit);
            });
        });
    </script>
@endpush
