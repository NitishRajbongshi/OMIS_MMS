@extends('layouts.app')

@section('content')
<main class="command-center">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-12 col-md-10">
                    <div class="command-breadcrumb">
                        <i class="fas fa-house"></i>
                        <span><a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">Home</a></span>
                        <span>/</span>
                        <span><a href="{{ route('manage.housing.index') }}" style="color: inherit; text-decoration: none;">Manage Housing</a></span>
                        <span>/</span>
                        <strong>Housing List</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="command-heading px-3">
        <div>
            <h1>List of Buildings</h1>
            <p>Statewide operational overview of registered buildings under {{ session('department') ?: 'Nagaland Public Works Department' }}</p>
        </div>
    </section>

    <!-- Main content -->
    <section class="content px-3">
        <x-common.alert-module />

        <article class="command-panel mb-4">
            <header>
                <div>
                    <span>Government Buildings</span>
                    <h2>Statewide Registry</h2>
                </div>
            </header>
            <div class="table-responsive p-3">
                <table class="table table-striped table-bordered w-100 text-xs" id="buildings_table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Building Code</th>
                            <th class="text-center">Quarter Number</th>
                            <th class="text-center">Building Name</th>
                            <th class="text-center">Building Type</th>
                            <th class="text-center">Building Class</th>
                            <th class="text-center">Division</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buildings as $index => $building)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-0 text-dark"
                                        style="background-color: #d6e8fd; letter-spacing: 0.5px">
                                        {{ $building->building_system_cd }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $building->qtr_no ? $building->qtr_no : '—' }}</td>
                                <td>{{ $building->bld_qtr_name ? $building->bld_qtr_name : '—' }}</td>
                                <td>{{ $building->buildingType?->building_type_descr ?? '—' }}</td>
                                <td>{{ $building->buildingClass?->building_class_descr ?? '—' }}</td>
                                <td>{{ $building->division?->division_name ?? '—' }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- View full building details --}}
                                        <a href="{{ route('manage.housing.show', $building->building_system_cd) }}"
                                            class="btn btn-info btn-xs text-light" title="View Details">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                        {{-- Go to building units --}}
                                        <a href="{{ route('building.unit.index', $building->building_system_cd) }}"
                                            class="btn btn-primary btn-xs text-light" title="Building Units">
                                            <i class="fa fa-cubes"></i> Units
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No buildings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/command-center.css') }}">
@endpush

@push('scripts')
    {{-- data table for the building table --}}
    <script>
        $(document).ready(function() {
            $('#buildings_table').DataTable({
                "pageLength": 25
            });
        });
    </script>
@endpush
