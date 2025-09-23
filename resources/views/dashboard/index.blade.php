@extends('layouts.app')

@section('title', 'Dashboard - Life Media CMS')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <button class="refresh-btn" onclick="location.reload()">
        🔄 Refresh
    </button>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Customer Registration</h3>
        <div class="stat-value">{{ $data['total_customers'] }}</div>
    </div>
    <div class="stat-card">
        <h3>Coverage Rate</h3>
        <div class="stat-value">{{ $data['coverage_rate'] }}%</div>
    </div>
</div>

<div class="chart-container">
    <h3 class="chart-header">Customer Growth Over Time</h3>
    <div class="chart-wrapper">
        <canvas id="customerChart"></canvas>
    </div>
    <div style="text-align: center; margin-top: 10px; color: #666; font-size: 14px;">
        Customer Lead Growth
    </div>
</div>

<div class="grid-2">
    <div class="chart-container">
        <h3 class="chart-header" style="color: #27ae60;">Regional Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="regionalChart"></canvas>
        </div>
    </div>
    
    <div class="chart-container">
        <h3 class="chart-header" style="color: #f39c12;">Customer Submissions by Hour</h3>
        <div class="chart-wrapper">
            <canvas id="hourlyChart"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Customer Growth Chart
    const customerCtx = document.getElementById('customerChart').getContext('2d');
    const customerData = @json($data['chart_data']);
    
    new Chart(customerCtx, {
        type: 'line',
        data: {
            labels: customerData.map(item => 'Day ' + item.day),
            datasets: [{
                label: 'Daily Customers',
                data: customerData.map(item => item.customers),
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    display: false
                },
                y: {
                    beginAtZero: true,
                    max: 15,
                    ticks: {
                        stepSize: 3
                    }
                }
            },
            elements: {
                point: {
                    radius: 1
                }
            }
        }
    });

    // Regional Distribution Chart
    const regionalCtx = document.getElementById('regionalChart').getContext('2d');
    const regionalData = @json($data['regional_data']);
    
    new Chart(regionalCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(regionalData),
            datasets: [{
                data: Object.values(regionalData),
                backgroundColor: [
                    '#3498db',
                    '#e74c3c',
                    '#f39c12',
                    '#27ae60'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Hourly Submissions Chart
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    const hourlyData = @json($data['hourly_submissions']);
    
    new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: hourlyData.map(item => item.hour + ':00'),
            datasets: [{
                label: 'Submissions',
                data: hourlyData.map(item => item.submissions),
                backgroundColor: '#f39c12',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxTicksLimit: 8
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection