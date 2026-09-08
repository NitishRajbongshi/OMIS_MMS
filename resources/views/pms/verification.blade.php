@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
    <div class="content-header ">
        <div class="container-fluid text-sm">
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item">PMS</li>
            </ol>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid mainBody py-3">
            <div id="alertContainer">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-secondary btn-sm" id="listDraft">Draft list</button>
        </div>
        <div class="container-fluid mainBody">
            <form action="{{ isset($project) ? route('project.update', $project->project_cd) : route('project.store') }}"
                method="POST">
                @csrf
                @if(isset($project))
                    @method('PUT')
                    @php
                        $others = json_decode($project->others ?? '{}', true);
                    @endphp
                @endif

                <!-- Tracking the draft Id to delete -->
                <input type="hidden" name="draft_id" value="">
                <!-- Project Information -->
                <div class="card mb-2">
                    <div class="card-header text-light fw-bold text-uppercase">Project Information</div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-sm-3">
                                <label class="form-label">Project Type <span class="text-danger">*</span></label>
                                <select name="projectTypeSelect" id="projectTypeSelect" class="form-select" required>
                                    <option value="">Select</option>

                                    @foreach($projectTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('projectTypeSelect', $others['project_type'] ?? '') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                        </div>
                    </div>
                </div>
    </section>
    </div>


@endsection