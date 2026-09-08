@extends('layouts.app')

@section('content')
    @php
        $formatCurrency = function ($amount) {
            $amount = (float) $amount;

            if (abs($amount) >= 10000000) {
                return '&#8377;' . number_format($amount / 10000000, 2) . ' Cr';
            }

            if (abs($amount) >= 100000) {
                return '&#8377;' . number_format($amount / 100000, 2) . ' Lakh';
            }

            return '&#8377;' . number_format($amount, 2);
        };
    @endphp
    <main class="pms-landing">
        <section class="pms-hero">
            <div>
                <span class="pms-eyebrow">Project Management System</span>
                <h1>Project Dashboard</h1>
                <p>Portfolio performance, progress and completion trends at a glance.</p>
            </div>
            <div class="pms-hero-actions">
                <a class="pms-parent-link" href="{{ route('manage-project') }}"><i class="fas fa-plus"></i><span>Create
                        Project</span></a>
                <a class="pms-parent-link" href="{{ route('project.verified.list') }}"><i
                        class="fas fa-list-check"></i><span>Project List</span></a>
            </div>
        </section>

        <section class="pms-kpis" aria-label="Project key performance indicators">
            <article class="pms-kpi pms-kpi-blue"><span class="pms-kpi-icon"><i class="fas fa-diagram-project"></i></span>
                <div><small>Total
                        Projects</small><strong>{{ number_format($dashboard['total_projects']) }}</strong><span>{{ $dashboard['completed_projects'] }}
                        completed</span></div>
            </article>
            <article class="pms-kpi pms-kpi-green"><span class="pms-kpi-icon"><i
                        class="fas fa-indian-rupee-sign"></i></span>
                <div><small>Total Work Order
                        Value</small><strong>{!! $formatCurrency($dashboard['total_budget']) !!}</strong><span>{!! $formatCurrency($dashboard['total_paid']) !!}
                        paid</span></div>
            </article>
            <article class="pms-kpi pms-kpi-orange"><span class="pms-kpi-icon"><i class="fas fa-chart-line"></i></span>
                <div><small>Average Physical
                        Progress</small><strong>{{ $dashboard['physical_progress'] }}%</strong><span>Across published
                        projects</span></div>
            </article>
            <article class="pms-kpi pms-kpi-red"><span class="pms-kpi-icon"><i
                        class="fas fa-triangle-exclamation"></i></span>
                <div><small>Delayed
                        Projects</small><strong>{{ number_format($dashboard['delayed_projects']) }}</strong><span>Past
                        scheduled end date</span></div>
            </article>
        </section>

        <section class="pms-dashboard-grid">
            <article class="pms-panel">
                <div class="pms-panel-heading">
                    <div>
                        <h2>Project Status</h2>
                        <p>Current portfolio distribution</p>
                    </div><span class="pms-panel-total">{{ $dashboard['total_projects'] }} Total</span>
                </div>
                <div class="pms-chart-wrap pms-chart-pie"><canvas id="projectStatusChart"></canvas></div>
            </article>

            <article class="pms-panel">
                <div class="pms-panel-heading">
                    <div>
                        <h2>Overall Progress</h2>
                        <p>Physical achievement and financial utilization</p>
                    </div>
                </div>
                <div class="pms-chart-wrap"><canvas id="progressChart"></canvas></div>
                <div class="pms-progress-summary"><span><i class="pms-dot pms-dot-physical"></i>Physical
                        <strong>{{ $dashboard['physical_progress'] }}%</strong></span><span><i
                            class="pms-dot pms-dot-financial"></i>Financial
                        <strong>{{ $dashboard['financial_progress'] }}%</strong></span></div>
            </article>

            <article class="pms-panel pms-panel-wide">
                <div class="pms-panel-heading">
                    <div>
                        <h2>Monthly Project Completion</h2>
                        <p>Projects completed during the last 12 months</p>
                    </div><a href="{{ route('project.pms-completion-report') }}">View completion reports <i
                            class="fas fa-arrow-right"></i></a>
                </div>
                <div class="pms-chart-wrap pms-chart-line"><canvas id="completionChart"></canvas></div>
            </article>

            <article class="pms-panel pms-panel-wide">
                <div class="pms-panel-heading">
                    <div>
                        <h2>Contractor-wise Physical Progress</h2>
                        <p>Average physical progress across projects awarded to each contractor</p>
                    </div><span class="pms-panel-total">{{ $contractorProgress->count() }} Contractors</span>
                </div>
                @if ($contractorProgress->isNotEmpty())
                    <div class="pms-chart-wrap pms-chart-contractors"
                        style="--contractor-count: {{ $contractorProgress->count() }}"><canvas
                            id="contractorProgressChart"></canvas></div>
                @else
                    <div class="pms-empty-chart"><i class="fas fa-chart-bar"></i><span>No contractor project progress is
                            available.</span></div>
                @endif
            </article>
        </section>
    </main>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pms-landing.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var statusCounts = @json(array_values($dashboard['status_counts']));
            var statusLabels = @json(array_keys($dashboard['status_counts']));
            var monthData = @json($monthlyCompletions);
            var contractorData = @json($contractorProgress);
            var commonLegend = { position: 'bottom', labels: { boxWidth: 11, padding: 18, fontColor: '#526170' } };

            new Chart(document.getElementById('projectStatusChart'), {
                type: 'doughnut',
                data: { labels: statusLabels, datasets: [{ data: statusCounts, backgroundColor: ['#2f9e67', '#3976c5', '#dc5a5a', '#aeb8c2'], borderWidth: 0 }] },
                options: { responsive: true, maintainAspectRatio: false, cutoutPercentage: 68, legend: commonLegend, tooltips: { callbacks: { label: function (item, data) { var value = data.datasets[0].data[item.index] || 0; var total = statusCounts.reduce(function (sum, current) { return sum + current; }, 0); var percent = total ? Math.round((value / total) * 100) : 0; return ' ' + data.labels[item.index] + ': ' + value + ' (' + percent + '%)'; } } } }
            });

            new Chart(document.getElementById('progressChart'), {
                type: 'bar',
                data: { labels: ['Physical Progress', 'Financial Progress'], datasets: [{ data: [{{ $dashboard['physical_progress'] }}, {{ $dashboard['financial_progress'] }}], backgroundColor: ['#3976c5', '#d9902f'], borderRadius: 4 }] },
                options: { responsive: true, maintainAspectRatio: false, legend: { display: false }, scales: { yAxes: [{ ticks: { beginAtZero: true, max: 100, callback: function (value) { return value + '%'; } }, gridLines: { color: '#edf1f5' } }], xAxes: [{ gridLines: { display: false } }] }, tooltips: { callbacks: { label: function (item) { return item.yLabel + '%'; } } } }
            });

            new Chart(document.getElementById('completionChart'), {
                type: 'line',
                data: { labels: monthData.map(function (row) { return row.label; }), datasets: [{ label: 'Completed Projects', data: monthData.map(function (row) { return row.count; }), borderColor: '#3976c5', backgroundColor: 'rgba(57,118,197,.10)', pointBackgroundColor: '#3976c5', pointRadius: 4, borderWidth: 3, fill: true, lineTension: .28 }] },
                options: { responsive: true, maintainAspectRatio: false, legend: { display: false }, scales: { yAxes: [{ ticks: { beginAtZero: true, precision: 0 }, gridLines: { color: '#edf1f5' } }], xAxes: [{ gridLines: { display: false } }] } }
            });

            var contractorCanvas = document.getElementById('contractorProgressChart');
            if (contractorCanvas) {
                new Chart(contractorCanvas, {
                    type: 'horizontalBar',
                    data: {
                        labels: contractorData.map(function (row) { return row.name; }),
                        datasets: [{
                            label: 'Physical Progress',
                            data: contractorData.map(function (row) { return row.progress; }),
                            backgroundColor: '#3976c5',
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: { display: false },
                        scales: {
                            xAxes: [{ ticks: { beginAtZero: true, max: 100, callback: function (value) { return value + '%'; } }, gridLines: { color: '#edf1f5' } }],
                            yAxes: [{ gridLines: { display: false }, ticks: { autoSkip: false } }]
                        },
                        tooltips: { callbacks: { label: function (item) { var row = contractorData[item.index]; return ' ' + item.xLabel + '% (' + row.projects + ' project' + (row.projects === 1 ? '' : 's') + ')'; } } }
                    }
                });
            }
        });
    </script>
@endpush