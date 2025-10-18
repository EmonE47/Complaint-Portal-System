@extends('layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('css/si-dashboard.css') }}" rel="stylesheet" />

<div class="sidebar">
    <div class="sidebar-header">
        <h2>Police GD System</h2>
        <p>Sub-Inspector Dashboard</p>
    </div>
    <ul class="sidebar-menu">
        <li><a href="{{ route('si.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="{{ route('si.cases') }}"><i class="fas fa-clipboard-list"></i> My Cases</a></li>
        <li><a href="#" class="active" data-section="reports"><i class="fas fa-chart-bar"></i> Reports</a></li>
        <li><a href="{{ route('si.profile') }}"><i class="fas fa-user"></i> Profile</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="header">
        <h1>Reports</h1>
        <div class="user-info">
            <div class="user-avatar">SI</div>
            <span>Sub-Inspector</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</button>
            </form>
        </div>
    </div>

    <!-- Reports Section -->
    <div id="reports" class="section active">
        <div class="reports-grid">
            <!-- Case Statistics Cards -->
            <div class="report-card">
                <div class="report-card-header">
                    <h3>Case Statistics</h3>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">{{ $totalCases }}</div>
                        <div class="stat-label">Total Cases</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $pendingCases }}</div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $investigatingCases }}</div>
                        <div class="stat-label">Investigating</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $resolvedCases }}</div>
                        <div class="stat-label">Resolved</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $closedCases }}</div>
                        <div class="stat-label">Closed</div>
                    </div>
                </div>
            </div>

            <!-- Monthly Case Trends -->
            <div class="report-card">
                <div class="report-card-header">
                    <h3>Monthly Case Trends</h3>
                </div>
                <div class="chart-container">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Case Types Distribution -->
            <div class="report-card">
                <div class="report-card-header">
                    <h3>Case Types Distribution</h3>
                </div>
                <div class="chart-container">
                    <canvas id="caseTypesChart"></canvas>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="report-card">
                <div class="report-card-header">
                    <h3>Performance Metrics</h3>
                </div>
                <div class="metrics-list">
                    <div class="metric-item">
                        <div class="metric-label">Resolution Rate</div>
                        <div class="metric-value">{{ $totalCases > 0 ? round(($resolvedCases / $totalCases) * 100, 1) : 0 }}%</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Average Cases per Month</div>
                        <div class="metric-value">{{ $monthlyCases->avg('count') ?: 0 }}</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Active Cases</div>
                        <div class="metric-value">{{ $pendingCases + $investigatingCases }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Cases Chart
    // const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    // const monthlyData = @json($monthlyCases->pluck('count')->toArray());
    // const monthlyLabels = @json($monthlyCases->map(function(item){
    //     return item.month . '/' . item.year;
    // })->toArray());

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Cases Assigned',
                data: monthlyData,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Case Types Chart
    const caseTypesCtx = document.getElementById('caseTypesChart').getContext('2d');
    const caseTypesData = @json($caseTypes->values()->toArray());
    const caseTypesLabels = @json($caseTypes->keys()->map(function(type) {
        return type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
    })->toArray());

    new Chart(caseTypesCtx, {
        type: 'doughnut',
        data: {
            labels: caseTypesLabels,
            datasets: [{
                data: caseTypesData,
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#dc3545',
                    '#ffc107',
                    '#6c757d',
                    '#17a2b8'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection
