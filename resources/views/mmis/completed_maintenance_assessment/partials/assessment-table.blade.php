
{{-- Summary --}}
@php
    $assetTabs = [
        '10' => 'Roads',
        '0'  => 'Culverts',
        '1'  => 'Bridges',
        '12' => 'Protection Walls',
    ];

    $knownTypes = array_keys($assetTabs);

    $otherAssessments = $assessments->filter(
        fn ($item) => !in_array((string) $item->insp_asset_type, $knownTypes, true)
    );

    $tabCounts = [];
    foreach ($assetTabs as $type => $label) {
        $tabCounts[$type] = $assessments->filter(
            fn ($item) => (string) $item->insp_asset_type === $type
        )->count();
    }

    $tabCounts['other'] = $otherAssessments->count();
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small">Completed Assessments</div>
                <div class="fs-2 fw-bold text-success">
                    {{ $assessments->count() }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small">High Priority</div>
                <div class="fs-2 fw-bold text-danger">
                    {{ $assessments->filter(fn ($a) => (float) ($a->mps_score ?? 0) >= 70)->count() }}
                </div>
                <small class="text-muted">MPS ≥ 70</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="text-muted small">Medium Priority</div>
                <div class="fs-2 fw-bold text-warning">
                    {{ $assessments->filter(fn ($a) => (float) ($a->mps_score ?? 0) >= 40 && (float) ($a->mps_score ?? 0) < 70)->count() }}
                </div>
                <small class="text-muted">MPS 40–69.99</small>
            </div>
        </div>
    </div>
</div>

{{-- Asset Type Tabs --}}
<ul class="nav nav-tabs mb-0" id="assessmentTabs" role="tablist">
    @foreach($assetTabs as $type => $label)
        <li class="nav-item" role="presentation">
            <button
                class="nav-link {{ $loop->first ? 'active' : '' }}"
                id="tab-{{ $type }}"
                data-bs-toggle="tab"
                data-bs-target="#pane-{{ $type }}"
                type="button"
                role="tab">
                {{ $label }}
                <span class="badge bg-secondary ms-1">
                    {{ $tabCounts[$type] }}
                </span>
            </button>
        </li>
    @endforeach

    <li class="nav-item" role="presentation">
        <button
            class="nav-link"
            id="tab-other"
            data-bs-toggle="tab"
            data-bs-target="#pane-other"
            type="button"
            role="tab">
            Other Assets
            <span class="badge bg-secondary ms-1">
                {{ $tabCounts['other'] }}
            </span>
        </button>
    </li>
</ul>

<div class="tab-content border border-top-0 rounded-bottom shadow-sm bg-white"
     id="assessmentTabContent">

    @foreach($assetTabs as $type => $label)
        @php
            $tabAssessments = $assessments->filter(
                fn ($item) => (string) $item->insp_asset_type === $type
            );
        @endphp

        <div
            class="tab-pane fade {{ $loop->first ? 'show active' : '' }} p-3"
            id="pane-{{ $type }}"
            role="tabpanel"
            aria-labelledby="tab-{{ $type }}">

            @include(
                'mmis.completed_maintenance_assessment.partials.assessment-table',
                ['tabAssessments' => $tabAssessments]
            )
        </div>
    @endforeach

    <div class="tab-pane fade p-3"
         id="pane-other"
         role="tabpanel"
         aria-labelledby="tab-other">

        @php $tabAssessments = $otherAssessments; @endphp

        @include(
            'mmis.completed_maintenance_assessment.partials.assessment-table',
            ['tabAssessments' => $tabAssessments]
        )
    </div>
</div>
