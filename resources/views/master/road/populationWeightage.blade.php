@extends('layouts.app')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row text-sm">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Master</li>
                        <li class="breadcrumb-item">Roads</li>
                        <li class="breadcrumb-item active">Population Weightage</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">

        <!-- ADD POPULATION WEIGHTAGE -->
        <div class="container-fluid mainBody px-3 py-2 border bg-white shadow-sm">
            <h5 class="text-sm font-weight-bold mb-3 text-uppercase text-primary">
                Add Population Weightage
            </h5>

            <form id="add_population_weightage_form" method="POST">
                @csrf
                <div class="row align-items-end">

                    <!-- LOWER POPULATION -->
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">
                            Lower Population <span class="text-danger">*</span>
                        </label>

                        <input type="number" name="lower_population" class="form-control form-control-sm" placeholder="e.g., 0" min="0" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                    </div>

                    <!-- HIGHER POPULATION -->
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">
                            Higher Population <span class="text-danger">*</span>
                        </label>

                       <input type="number" name="higher_population" class="form-control form-control-sm"
                            placeholder="e.g., 500"
                            min="0"
                            step="1"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <!-- WEIGHTAGE -->
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">
                            Weightage <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="weightage"
                               class="form-control form-control-sm"
                               placeholder="e.g., 10"
                               min="0"
                               max="100"
                               step="0.01"
                               onkeydown="if(event.key === '-' || event.key === 'e' || event.key === '+') event.preventDefault();"
                               required>
                    </div>

                    <!-- SCHEME -->
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">
                            Scheme <span class="text-danger">*</span>
                        </label>

                        <select name="scheme_id" class="form-control form-control-sm" required>
                            <option value="">Select Scheme</option>
                            @foreach ($schemes as $scheme)
                                <option value="{{ $scheme->scheme_id }}">{{ $scheme->scheme_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- STATUS -->
                    <div class="col-md-2 mb-2">
                        <label class="text-xs">Status</label>

                        <select name="is_published"
                                class="form-select form-select-sm">
                            <option value="Y">Published</option>
                            <option value="N">Draft</option>
                        </select>
                    </div>

                    <!-- SAVE -->
                    <div class="col-md-2 mb-2">
                        <button type="submit"
                                class="btn btn-primary btn-sm btn-block fw-bold shadow-sm">
                            <i class="fa fa-plus-circle mr-1"></i>
                            SAVE
                        </button>
                    </div>

                </div>
            </form>

        </div>


        <!-- MASTER LIST -->
        <div class="mt-4 text-sm">

            <div class="card rounded-0 shadow-sm">

                <div class="card-header rounded-0"
                     style="background-color:rgb(214, 232, 253)">

                    <h4 class="card-title text-sm text-bold text-uppercase text-dark">
                        Population Weightage Master List
                    </h4>

                </div>

                <div class="card-body rounded-0">

                    <table class="table table-bordered table-sm table-striped w-100"
                           id="populationWeightageTable">

                        <thead style="background-color:#e7effc;">
                            <tr>

                                <th style="width: 8%;">
                                    ID
                                </th>

                                <th>
                                    Scheme
                                </th>

                                <th class="text-center">
                                    Lower Population
                                </th>

                                <th class="text-center">
                                    Higher Population
                                </th>

                                <th class="text-center">
                                    Weightage
                                </th>

                                <th class="text-center" style="width: 15%;">
                                    Status
                                </th>

                                <th class="text-center" style="width: 100px;">
                                    Action
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($populationWeightages as $population)

                                <tr class="text-xs">

                                    <td>
                                        {{ $population->id }}
                                    </td>

                                    <td>
                                        {{ $population->scheme_name }}
                                    </td>

                                    <td class="text-center font-weight-bold">
                                        {{ $population->lower_population }}
                                    </td>

                                    <td class="text-center font-weight-bold">
                                        {{ $population->higher_population }}
                                    </td>

                                    <td class="text-center">
                                        {{ $population->weightage }}
                                    </td>

                                    <td class="text-center">

                                        <span class="badge
                                            {{ $population->is_published == 'Y'
                                                ? 'badge-success'
                                                : 'badge-secondary' }}">

                                            {{ $population->is_published == 'Y'
                                                ? 'PUBLISHED'
                                                : 'DRAFT' }}

                                        </span>

                                    </td>

                                    <td class="text-center">

                                        <button
                                            data-toggle="modal"
                                            data-target="#editPopulationModal{{ $population->id }}"
                                            class="btn btn-sm text-warning">

                                            <i class="fas fa-edit"></i>

                                        </button>

                                    </td>

                                </tr>


                                <!-- EDIT MODAL -->

                                <div class="modal fade"
                                     id="editPopulationModal{{ $population->id }}"
                                     tabindex="-1"
                                     data-backdrop="static">

                                    <div class="modal-dialog">

                                        <div class="modal-content">

                                            <form class="update-population-form"
                                                  method="PUT"
                                                  action="{{ route('populationWeightage.update', $population->id) }}">

                                                @csrf

                                                <input type="hidden"
                                                       name="id"
                                                       value="{{ $population->id }}">


                                                <div class="modal-header bg-light py-2">

                                                    <h6 class="modal-title font-weight-bold">
                                                        Update Population Weightage
                                                    </h6>

                                                    <button type="button"
                                                            class="close"
                                                            data-dismiss="modal">
                                                        &times;
                                                    </button>

                                                </div>


                                                <div class="modal-body">

                                                    <!-- LOWER POPULATION -->

                                                    <div class="form-group">

                                                        <label class="text-xs">
                                                            Lower Population
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <input type="number"
                                                               name="lower_population"
                                                               class="form-control form-control-sm"
                                                               value="{{ $population->lower_population }}"
                                                                min="0"
                                                                step="1"
                                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                               required>

                                                    </div>


                                                    <!-- HIGHER POPULATION -->

                                                    <div class="form-group">

                                                        <label class="text-xs">
                                                            Higher Population
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <input type="number"
                                                               name="higher_population"
                                                               class="form-control form-control-sm"
                                                               value="{{ $population->higher_population }}"
                                                               min="0"
                                                               step="1"
                                                               onkeydown="if(event.key === '-' || event.key === 'e' || event.key === '+') event.preventDefault();">

                                                    </div>


                                                    <!-- WEIGHTAGE -->

                                                    <div class="form-group">

                                                        <label class="text-xs">
                                                            Weightage
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <input type="number"
                                                               name="weightage"
                                                               class="form-control form-control-sm"
                                                               value="{{ $population->weightage }}"
                                                               min="0" max="100" step="0.01" onkeydown="if(event.key === '-' || event.key === 'e' || event.key === '+') event.preventDefault();"
                                                               required>

                                                    </div>

                                                    <!-- SCHEME -->

                                                    <div class="form-group">

                                                        <label class="text-xs">
                                                            Scheme
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <select name="scheme_id" class="form-control form-control-sm" required>
                                                            <option value="">Select Scheme</option>
                                                            @foreach ($schemes as $scheme)
                                                                <option value="{{ $scheme->scheme_id }}" {{ $population->scheme_id == $scheme->scheme_id ? 'selected' : '' }}>
                                                                    {{ $scheme->scheme_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                    </div>


                                                    <!-- STATUS -->

                                                    <div class="form-group">

                                                        <label class="text-xs">
                                                            Status
                                                        </label>

                                                        <select name="is_published"
                                                                class="form-select form-select-sm">

                                                            <option value="Y"
                                                                {{ $population->is_published == 'Y' ? 'selected' : '' }}>
                                                                Published
                                                            </option>

                                                            <option value="N"
                                                                {{ $population->is_published == 'N' ? 'selected' : '' }}>
                                                                Draft
                                                            </option>

                                                        </select>

                                                    </div>

                                                </div>


                                                <div class="modal-footer py-1">

                                                    <button type="button"
                                                            class="btn btn-secondary btn-sm"
                                                            data-dismiss="modal">
                                                        Close
                                                    </button>

                                                    <button type="submit"
                                                            class="btn btn-success btn-sm">
                                                        Update Changes
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </section>

@endsection


@push('scripts')

<script>

$(document).ready(function() {

    $('#populationWeightageTable').DataTable({
        responsive: true
    });


    // ADD

    $('#add_population_weightage_form').on('submit', function(e) {

        e.preventDefault();

        handleAjax(
            $(this),
            "{{ route('populationWeightage.store') }}",
            $(this).attr('method')
        );

    });


    // UPDATE

    $('.update-population-form').on('submit', function(e) {

        e.preventDefault();

        handleAjax(
            $(this),
            $(this).attr('action'),
            $(this).attr('method')
        );

    });


    function handleAjax(form, url, method) {

        $.ajax({

            type: method,
            url: url,
            data: form.serialize(),

            success: function(res) {

                Swal.fire({

                    icon: res.status,
                    title: res.status.toUpperCase(),
                    text: res.message,
                    timer: res.status === 'success' ? 2000 : null,
                    showConfirmButton: true

                }).then(function() {

                    if (res.status === 'success') {
                        location.reload();
                    }

                });

            }

        });

    }

});

</script>

@endpush
