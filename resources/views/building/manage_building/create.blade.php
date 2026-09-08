@extends('layouts.app')
@section('content')
<main class="command-center">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-12 col-md-6">
                    <div class="command-breadcrumb">
                        <i class="fas fa-house"></i>
                        <span><a href="{{ route('dashboard.housing') }}" style="color: inherit; text-decoration: none;">Dashboard</a></span>
                        <span>/</span>
                        <span><a href="{{ route('manage.housing.index') }}" style="color: inherit; text-decoration: none;">Manage Housing</a></span>
                        <span>/</span>
                        <strong>Add New Building</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="command-heading px-3">
        <div>
            <h1>Add New Building</h1>
            <p>Add government building details under {{ session('department') ?: 'Nagaland P.W.D (Housing)' }}</p>
        </div>
    </section>

    <section class="content px-3">
        <x-building-flash-message />
        
        <article class="command-panel mb-4">
            <header>
                <div>
                    <span>Form</span>
                    <h2>Add Housing Details</h2>
                </div>
            </header>
            <div class="p-3">
                <p class="text-sm text-secondary mb-3">
                    <span class="fa fa-info-circle text-primary"></span>
                    All fields marked with <span class="star text-danger text-md text-bold">*</span> is mandatory
                </p>

                @include('building.manage_building.partials._form', [
                    'mode' => 'create',
                    'action' => route('manage.housing.store'),
                ])
            </div>
        </article>

        {{-- Show Draft table data --}}
        <article class="command-panel mb-4">
            <header class="d-flex align-items-center justify-content-between">
                <div>
                    <span>Drafted Buildings</span>
                    <h2>List of Drafted Government Buildings</h2>
                </div>
                <div>
                    <button id="freezeBtn" class="btn btn-sm btn-info text-bold">
                        <i class="fa fa-check-circle mr-1" aria-hidden="true"></i>
                        Send data for finalization
                    </button>
                </div>
            </header>
            <div class="table-responsive p-3">
                <table class="table table-striped table-bordered text-xs user_list w-100" id="building_details_table">
                    <thead>
                        <tr>
                            <th class="text-center">Building ID</th>
                            <th class="text-center">Quarter Number</th>
                            <th class="text-center">Building Name</th>
                            <th class="text-center">Building Type</th>
                            <th class="text-center">Maintained by NPWD?</th>
                            <th class="text-center">Residential/Non-Residential</th>
                            <th class="text-center">Owning Department</th>
                            <th class="text-center">Rejection Reason</th>
                            <th class="text-center">Select</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buildingDetails as $item)
                            <tr>
                                <td class="text-center">{{ $item->building_system_cd }}</td>
                                <td class="text-center">{{ $item->qtr_no ?? '__' }}</td>
                                <td>{{ $item->bld_qtr_name ?? '__' }}</td>
                                <td>{{ $item->buildingType?->building_type_descr ?? '__' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->is_maintained_by_npwd == 'Y' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $item->is_maintained_by_npwd == 'Y' ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>{{ $item->buildingClass?->building_class_descr ?? '__' }}</td>
                                <td>{{ $item->owningDept?->dept_name ?? '__' }}</td>
                                <td>{{ $item->reason_of_rejection ?? '__' }}</td>
                                <td class="text-center">
                                    <input type="checkbox" class="selected-asset"
                                        data-housing-id="{{ $item->building_system_cd }}" />
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('manage.housing.edit', $item->building_system_cd) }}"
                                            class="btn btn-info btn-xs text-light" title="Edit">
                                            <i class="fa fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('manage.housing.destroy', $item->building_system_cd) }}"
                                            method="post" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this building?')">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" name="building_id"
                                                value="{{ $item->building_system_cd }}">
                                            <button type="submit" class="btn btn-danger btn-xs" title="Delete">
                                                <i class="fa fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No drafted building details found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    <x-building.coordinates.set-coordinate />
    <x-success-modal />
    <x-warning-modal />
</main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/selectOptionStyleSheet.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/modal/script.js') }}" defer></script>
    <script src="{{ asset('js/building/script.js') }}" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnj1P_w0UVJGnX0vNSPMe5QS__d_E0goM"></script>
    <script>
        $(function() {
            $("#building_details_table").DataTable({}).buttons().container().appendTo(
                '#building_details_table_wrapper .col-md-11:eq(1)');
        });

        const bld_class_master = @json($buildingClasses);
        const bld_type_master = @json($buildingTypes);
    </script>
@endpush
