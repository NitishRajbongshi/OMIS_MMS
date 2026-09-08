@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ url('project-management/manage-project?mode=create') }}">Create Project</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pms.work-item.index', $project) }}">Item of Work</a>
                        </li>
                        <li class="breadcrumb-item">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div
            style="background: #f5f5f5; border-radius: 3px; padding: 12px; border: 1px solid #99b3cc; font-family: 'Segoe UI', Arial, sans-serif;">
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 2px solid #003366;">
                <h3 style="margin: 0; font-size: 14px; color: #003366; font-weight: 600; text-transform: uppercase;">
                    Edit Item of Work
                </h3>
            </div>

            @include('pms.IOW.partials._form', [
                'formAction' => route('pms.work-item.update', [$project, $itemOfWork]),
                'isEdit' => true,
                'currentItem' => $itemOfWork,
            ])
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Pre-fill unit on page load for edit mode
            var unit = $('#item_cd').find('option:selected').data('unit');
            $('#unit').val(unit);

            $('#item_cd').change(function() {
                var unit = $(this).find('option:selected').data('unit');
                $('#unit').val(unit);
            });
        });
    </script>
@endpush
