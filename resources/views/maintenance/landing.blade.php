@extends('layouts.app')

@section('content')
<main class="maintenance-dashboard">
    <section class="mmis-hero">
        <div>
            <span class="mmis-eyebrow">MAINTENANCE MANAGEMENT INFORMATION SYSTEM</span>
            <h1>Maintenance Dashboard</h1>
            <p>Monitor inspections, asset condition and maintenance priority across your infrastructure portfolio.</p>
        </div>
        <a class="mmis-parent-link" href="{{ route('portal.landing') }}">
            <i class="fas fa-layer-group"></i>
            <span>OMIS Parent Portal</span>
        </a>
    </section>

    <section class="mmis-kpis" aria-label="Maintenance dashboard summary">
        <article class="mmis-kpi">
            <span class="mmis-kpi-icon blue"><i class="fas fa-clipboard-list"></i></span>
            <div><small>Total Inspections</small><strong>{{ number_format($dashboard['total_inspections'])
                    }}</strong><span>Recorded inspection records</span></div>
        </article>
        <article class="mmis-kpi">
            <span class="mmis-kpi-icon green"><i class="fas fa-check-circle"></i></span>
            <div><small>Assessed Inspections</small><strong>{{ number_format($dashboard['assessed_inspections'])
                    }}</strong><span>With an active calculated score</span></div>
        </article>
        <article class="mmis-kpi">
            <span class="mmis-kpi-icon amber"><i class="fas fa-gauge-high"></i></span>
            <div><small>Average MAS</small><strong>{{ number_format($dashboard['average_mas'], 2)
                    }}</strong><span>Maintenance Assessment Score</span></div>
        </article>
        <!-- <article class="mmis-kpi">
            <span class="mmis-kpi-icon red"><i class="fas fa-triangle-exclamation"></i></span>
            <div><small>Average MPS</small><strong>{{ number_format($dashboard['average_mps'], 2) }}</strong><span>Maintenance Priority Score</span></div>
        </article> -->
    </section>

    <section class="mmis-chart-grid">
        <article class="mmis-panel">
            <div class="mmis-panel-heading">
                <div>
                    <h2>Assessment Status</h2>
                    <p>Inspections with and without an active score</p>
                </div>
                <span class="mmis-total-pill">{{ number_format($dashboard['total_inspections']) }} total</span>
            </div>
            @php
            $statusTotal = max(array_sum($assessmentStatus), 1);
            $assessedPercent = round(($assessmentStatus['Assessed'] / $statusTotal) * 100, 1);
            @endphp
            <div class="mmis-donut-wrap">
                <div class="mmis-donut" style="--assessed: {{ $assessedPercent }}%">
                    <div class="mmis-donut-center"><strong>{{ $assessedPercent }}%</strong><span>Assessed</span></div>
                </div>
                <div class="mmis-legend">
                    <span><i class="legend-dot assessed"></i>Assessed <b>{{ number_format($assessmentStatus['Assessed'])
                            }}</b></span>
                    <span><i class="legend-dot pending"></i>Pending assessment <b>{{
                            number_format($assessmentStatus['Pending Assessment']) }}</b></span>
                </div>
            </div>
        </article>

        <article class="mmis-panel">
            <div class="mmis-panel-heading">
                <div>
                    <h2>Average Assessment Scores</h2>
                    <p>Average values from active assessment scores</p>
                </div>
            </div>
            <div class="mmis-score-bars">
                <div class="mmis-score-row">
                    <div><span>MAS</span><strong>{{ number_format($dashboard['average_mas'], 2) }}</strong></div>
                    <div class="mmis-progress-track"><span class="mas-bar"
                            style="width: {{ max(0, min(100, $dashboard['average_mas'])) }}%"></span></div>
                </div>
                <!-- <div class="mmis-score-row">
                    <div><span>MPS</span><strong>{{ number_format($dashboard['average_mps'], 2) }}</strong></div>
                    <div class="mmis-progress-track"><span class="mps-bar"
                            style="width: {{ max(0, min(100, $dashboard['average_mps'])) }}%"></span></div>
                </div>
                <div class="mmis-score-row">
                    <div><span>Condition Score</span><strong>{{ number_format($dashboard['average_condition_score'], 2)
                            }}</strong></div>
                    <div class="mmis-progress-track"><span class="condition-bar"
                            style="width: {{ max(0, min(100, $dashboard['average_condition_score'])) }}%"></span></div>
                </div>
                <div class="mmis-score-row">
                    <div><span>Criticality Score</span><strong>{{ number_format($dashboard['average_criticality_score'],
                            2) }}</strong></div>
                    <div class="mmis-progress-track"><span class="criticality-bar"
                            style="width: {{ max(0, min(100, $dashboard['average_criticality_score'])) }}%"></span>
                    </div>
                </div> -->
            </div>
            <p class="mmis-chart-note">Bars are capped visually at 100.</p>
        </article>
    </section>

    <section class="mmis-panel mmis-monthly-panel">
        <div class="mmis-panel-heading">
            <div>
                <h2>Monthly Inspection Trend</h2>
                <p>Inspection records created over the last 12 months</p>
            </div>
        </div>
        <div class="mmis-monthly-chart">
            @php $maxMonthly = max(1, (int) $monthlyInspections->max('count')); @endphp
            @foreach ($monthlyInspections as $month)
            <div class="mmis-month-column" title="{{ $month['label'] }}: {{ $month['count'] }} inspections">
                <span class="mmis-month-value">{{ $month['count'] }}</span>
                <div class="mmis-month-track"><span style="height: {{ ($month['count'] / $maxMonthly) * 100 }}%"></span>
                </div>
                <small>{{ $month['label'] }}</small>
            </div>
            @endforeach
        </div>
    </section>

    <section class="mmis-lower-grid">
        <article class="mmis-panel">
            <div class="mmis-panel-heading">
                <div>
                    <h2>Asset Category Distribution</h2>
                    <p>Inspections grouped by asset type</p>
                </div>
            </div>
            @if ($assetTypeBreakdown->isEmpty())
            <p class="mmis-empty">No inspection records are available yet.</p>
            @else
            <div class="mmis-category-list">
                @php $maxCategory = max(1, (int) $assetTypeBreakdown->max('total')); @endphp
                @foreach ($assetTypeBreakdown as $category)
                <div class="mmis-category-row">
                    <div><span>{{ $category->asset_type }}</span><strong>{{ number_format($category->total) }}</strong>
                    </div>
                    <div class="mmis-progress-track"><span
                            style="width: {{ ($category->total / $maxCategory) * 100 }}%"></span></div>
                </div>
                @endforeach
            </div>
            @endif
        </article>

        <article class="mmis-panel mmis-actions-panel">
            <div class="mmis-panel-heading">
                <div>
                    <h2>Quick Actions</h2>
                    <p>Go directly to common MMIS tasks</p>
                </div>
            </div>
            @foreach ($maintenanceLinks as $link)
            <a class="mmis-action-link" href="{{ $link['route'] }}">
                <span class="mmis-action-icon"><i class="{{ $link['icon'] }}"></i></span>
                <span><strong>{{ $link['title'] }}</strong><small>{{ $link['subtitle'] }}</small></span>
                <i class="fas fa-arrow-right mmis-action-arrow"></i>
            </a>
            @endforeach
        </article>
    </section>

    <section class="mmis-panel mmis-recent-panel">
        <div class="mmis-panel-heading">
            <div>
                <h2>Recent Inspections</h2>
                <p>Latest inspection records and calculated scores</p>
            </div>
            <a class="mmis-text-link" href="{{ url('/maintenance-management/completed-maintenance-assessments') }}">Open
                assessments <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="mmis-table-wrap">
            <table class="mmis-table">
                <thead>
                    <tr>
                        <th>Inspection No.</th>
                        <th>Date</th>
                        <th>Asset Type</th>
                        <th>Asset ID</th>
                        <th>Location</th>
                        <th>MAS</th>
                        <!-- <th>MPS</th> -->
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentInspections as $inspection)
                    <tr>
                        <td>{{ $inspection->insp_cd }}</td>
                        <td>{{ $inspection->insp_date ? \Carbon\Carbon::parse($inspection->insp_date)->format('d-m-Y') :
                            '—' }}</td>
                        <td>{{ $inspection->asset_type_name ?: $inspection->insp_asset_type ?: '—' }}</td>
                        <td>{{ $inspection->insp_asset_id ?: '—' }}</td>
                        <td>{{ $inspection->insp_asset_location ?: '—' }}</td>
                        <td>{{ $inspection->mas_score !== null ? number_format((float) $inspection->mas_score, 2) : '—'
                            }}</td>
                        <!-- <td>{{ $inspection->mps_score !== null ? number_format((float) $inspection->mps_score, 2) : '—'
                            }}</td> -->
                        <td><span class="mmis-status {{ $inspection->mas_score !== null ? 'complete' : 'pending' }}">{{
                                $inspection->mas_score !== null ? 'Assessed' : 'Pending' }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="mmis-empty">No inspection records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/maintenance-landing.css') }}">
@endpush