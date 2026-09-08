@extends('layouts.app')
@section('content')
    <main class="command-center">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row text-sm">
                    <div class="col-sm-12">
                        <div class="command-breadcrumb">
                            <i class="fas fa-house"></i>
                            <span><a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">Home</a></span>
                            <span>/</span>
                            <span><a href="{{ url('project-management/manage-project?mode=create') }}"
                                    style="color: inherit; text-decoration: none;">Create Project</a></span>
                            <span>/</span>
                            <span><a href="{{ route('pms.work-item.index', $project) }}"
                                    style="color: inherit; text-decoration: none;">Item of Work</a></span>
                            <span>/</span>
                            <strong>Edit</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content px-3">
            {{-- Page Header --}}
            <div class="command-heading mb-4">
                <div>
                    <h1>
                        <i class="fa fa-edit text-primary me-2"></i>
                        Edit Item of Work
                    </h1>
                    <p>
                        Modify details for the selected work item.
                    </p>
                </div>
                <div class="command-actions">
                    <a href="{{ route('pms.work-item.index', $project) }}" class="btn btn-secondary btn-sm text-light">
                        <i class="fa fa-arrow-left me-1"></i> Back to IOW List
                    </a>
                </div>
            </div>

            {{-- Form Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Modify Details</span>
                        <h2>Edit Item of Work</h2>
                    </div>
                </header>
                <div class="p-3">
                    @include('pms.IOW.partials._form', [
                        'formAction' => route('pms.work-item.update', [$project, $itemOfWork]),
                        'isEdit' => true,
                        'currentItem' => $itemOfWork,
                    ])
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
        .command-center {
            color: var(--oamis-ink);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            var checkedSubItemCds = @json(old('sub_item_cd', $checkedSubItemCds ?? []));
            checkedSubItemCds = checkedSubItemCds.map(Number);

            // Pre-fill unit on page load for edit mode
            var unit = $('#item_cd').find('option:selected').data('unit');
            $('#unit').val(unit);

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
                            if (response.status === 'success' && response.subitems && response.subitems.length > 0) {
                                $.each(response.subitems, function(index, subitem) {
                                    var isChecked = checkedSubItemCds.includes(Number(subitem.sub_item_cd)) ? 'checked' : '';
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
