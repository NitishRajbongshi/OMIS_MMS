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
                            <span><a href="{{ route('building.view.index') }}"
                                    style="color: inherit; text-decoration: none;">Housing List</a></span>
                            <span>/</span>
                            <strong>Manage Locations</strong>
                        </div>
                    </div>
                </div>
                <x-common.alert-module />
            </div>
        </div>

        <!-- Main content -->
        <section class="content px-3">
            {{-- Page Header --}}
            <div class="command-heading mb-4">
                <div>
                    <h1>
                        <i class="fa fa-map-marker-alt text-primary me-2"></i>
                        Manage Locations
                    </h1>
                    <p>
                        Configure and view geographical building locations and administrative division mappings.
                    </p>
                </div>
            </div>

            {{-- Form Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>New Entry</span>
                        <h2>Add Location</h2>
                    </div>
                </header>
                <div class="p-3">
                    @include('building.location.partials._form', [
                        'formAction' => route('building-location.store'),
                        'isEdit' => false,
                        'location' => null,
                    ])
                </div>
            </article>

            {{-- List Panel --}}
            <article class="command-panel mb-4">
                <header>
                    <div>
                        <span>Registry</span>
                        <h2>List of Existing Locations</h2>
                    </div>
                </header>
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table table-hover w-100" id="location_table">
                            <thead>
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Location Name</th>
                                    <th>Residential/Non-Residential</th>
                                    <th>Division Name</th>
                                    <th>SubDivision Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($locationDetails as $key)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td><strong>{{ $key->location_name }}</strong></td>
                                        <td>{{ $key->building_class_descr }}</td>
                                        <td>{{ $key->division_name }}</td>
                                        <td>{{ $key->sub_div_name }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                {{-- Edit --}}
                                                <a href="{{ route('building-location.edit', [$key->location_cd]) }}"
                                                    class="btn btn-warning btn-xs text-dark" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                {{-- Delete --}}
                                                <form action="{{ route('building-location.destroy', [$key->location_cd]) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete the location?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-xs text-light" title="Delete">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
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
        .command-center {
            color: var(--oamis-ink);
        }

        .table th,
        .table td {
            font-size: 14px !important;
        }

        .gap-1 {
            gap: 0.25rem !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/location/script.js') }}" defer></script>
@endpush

