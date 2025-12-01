@extends('layouts.app')

@section('title', 'Dashboard - Life Media CMS')

@section('content')
    <div class="page-header dashboard-page">
        <h1 class="page-title">Dashboard</h1>
        <button class="refresh-btn" onclick="location.reload()"
            style="background: linear-gradient(90deg, #37393b, #333332); border-radius: 9999px; padding: 10px 18px; font-weight:600; gap:0;">
            Refresh
        </button>

    </div>

    <!-- Tailwind & ApexCharts (scoped to this page) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <section class="tailwind-section">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Customer Registration</p>
                    <p class="text-3xl font-bold">{{ $metrics['total_customers'] }}</p>
                    <p class="text-xs text-green-500 flex items-center mt-1">
                        <span>{{ $metrics['total_growth_note'] }}</span>
                    </p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0012 12a5.995 5.995 0 00-3-5.197M15 21a9 9 0 00-9-9">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Coverage Rate</p>
                    <p class="text-3xl font-bold">{{ number_format($metrics['coverage_rate'], 2) }}%</p>
                    <p class="text-xs text-red-500 flex items-center mt-1">
                        <span>{{ $metrics['coverage_note'] }}</span>
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">New Leads (Today)</p>
                    <p class="text-3xl font-bold">{{ $metrics['today_new'] }}</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <span>Target: {{ $metrics['today_target'] }}</span>
                    </p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Products</p>
                    <p class="text-3xl font-bold">{{ $metrics['active_products'] }}</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <span>{{ $metrics['active_products_note'] }}</span>
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Main Chart -->
        <div
            class="mt-8 bg-gradient-to-br from-white to-gray-50 p-8 rounded-xl shadow-lg chart-card border border-gray-100">
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Customer Growth Over Time</h3>
                    <p class="text-sm text-gray-500 mt-1">Track new customer acquisitions across different time periods</p>
                </div>
                <div id="growth-chart-filters" class="flex space-x-2 bg-gray-100 p-1.5 rounded-lg shadow-sm">
                    <button data-period="daily"
                        class="filter-btn px-4 py-2 text-sm font-semibold rounded-md transition-all duration-200 hover:bg-gray-200">Daily</button>
                    <button data-period="weekly"
                        class="filter-btn px-4 py-2 text-sm font-semibold rounded-md transition-all duration-200 hover:bg-gray-200">Weekly</button>
                    <button data-period="monthly"
                        class="filter-btn active px-4 py-2 text-sm font-semibold rounded-md transition-all duration-200">Monthly</button>
                    <button data-period="yearly"
                        class="filter-btn px-4 py-2 text-sm font-semibold rounded-md transition-all duration-200 hover:bg-gray-200">Yearly</button>
                </div>
            </div>
            <div id="customer-growth-chart" class="chart-container"></div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Peak</p>
                    <p class="text-xl font-bold text-red-500" id="growth-peak">-</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500">Average</p>
                    <p class="text-xl font-bold text-blue-500" id="growth-average">-</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-xl font-bold text-green-500" id="growth-total">-</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-500">Data Points</p>
                    <p class="text-xl font-bold text-purple-500" id="growth-points">-</p>
                </div>
            </div>
        </div>

        <!-- Secondary Analytics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            <div class="bg-white p-6 rounded-xl shadow-md chart-card">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Submissions by Hour</h3>
                <div id="submissions-by-hour-chart"></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md chart-card">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Product Popularity</h3>
                <div id="product-popularity-chart"></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md chart-card">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Coverage Distribution</h3>
                <div id="coverage-chart"></div>
            </div>
        </div>

        <!-- Top Subdistricts Table -->
        <div class="mt-8 bg-white p-6 rounded-xl shadow-md">
            <div class="flex flex-wrap gap-4 justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-700">Top Subdistrict</h3>
                <div class="flex items-center gap-2">
                    <label for="limit-select" class="text-sm font-medium text-gray-700">Show:</label>
                    <select id="limit-select" onchange="changeLimit(this.value)"
                        class="border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                        <option value="10" {{ $currentLimit == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ $currentLimit == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ $currentLimit == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $currentLimit == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $currentLimit == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Subdistrict</th>
                            <th scope="col" class="px-6 py-3">Total Registration</th>
                            <th scope="col" class="px-6 py-3">Covered</th>
                            <th scope="col" class="px-6 py-3">Uncovered</th>
                            <th scope="col" class="px-6 py-3">Coverage Rate (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSubdistricts as $index => $subdistrict)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">{{ $subdistrict['subdistrict'] }}</td>
                                <td class="px-6 py-4">{{ $subdistrict['total_registration'] }}</td>
                                <td class="px-6 py-4">{{ $subdistrict['covered'] }}</td>
                                <td class="px-6 py-4">{{ $subdistrict['uncovered'] }}</td>
                                <td class="px-6 py-4">{{ $subdistrict['coverage_rate'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500">No data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endpush

<script>
    function changeLimit(value) {
        const url = new URL(window.location);
        url.searchParams.set('limit', value);
        window.location.href = url.toString();
    }

    document.addEventListener("DOMContentLoaded", function() {
        const brandColors = ['#EF4444', '#3B82F6', '#10B981', '#F59E0B', '#6B7280'];

        const yearlyData = @json($charts['yearly']);
        const monthlyData = @json($charts['monthly']);
        const weeklyData = @json($charts['weekly']);
        const dailyData = @json($charts['daily']);

        // Create fallbacks for the detailed date labels where not provided by controller
        monthlyData.x_axis_labels = monthlyData.categories;
        yearlyData.x_axis_labels = yearlyData.categories;
        weeklyData.x_axis_labels = weeklyData.x_axis_labels || weeklyData.categories;
        dailyData.x_axis_labels = dailyData.x_axis_labels || dailyData.categories;

        let currentData = monthlyData;
        let currentXAxisLabels = monthlyData.x_axis_labels;

        // Helper function to calculate statistics
        function calculateStats(data) {
            if (!data || !data.series || !data.series[0] || !data.series[0].data) return null;
            const values = data.series[0].data;
            const peak = Math.max(...values);
            const total = values.reduce((a, b) => a + b, 0);
            const average = Math.round(total / values.length);
            const points = values.length;
            return {
                peak,
                total,
                average,
                points
            };
        }

        // Helper function to update statistics display
        function updateStats(data) {
            const stats = calculateStats(data);
            if (stats) {
                document.getElementById('growth-peak').textContent = stats.peak.toLocaleString();
                document.getElementById('growth-average').textContent = stats.average.toLocaleString();
                document.getElementById('growth-total').textContent = stats.total.toLocaleString();
                document.getElementById('growth-points').textContent = stats.points;
            }
        }

        var growthOptions = {
            series: monthlyData.series,
            chart: {
                height: 400,
                type: 'area',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true,
                    },
                    export: {
                        csv: {
                            filename: 'customer-growth.csv',
                        },
                        svg: {
                            filename: 'customer-growth.svg',
                        },
                        png: {
                            filename: 'customer-growth.png',
                        }
                    }
                },
                zoom: {
                    enabled: true,
                    type: 'x',
                    autoScaleYaxis: true
                },
                animations: {
                    enabled: true,
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 150
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                lineCap: 'round'
            },
            xaxis: {
                categories: monthlyData.categories,
                type: 'category',
                tickPlacement: 'between',
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 500,
                        colors: '#6b7280'
                    },
                    offsetY: 5
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 500,
                        colors: '#6b7280'
                    },
                    formatter: function(val) {
                        return Math.round(val).toLocaleString();
                    }
                }
            },
            grid: {
                borderColor: '#e5e7eb',
                strokeDashArray: 3,
                padding: {
                    bottom: 10,
                    top: 10
                }
            },
            tooltip: {
                enabled: true,
                theme: 'light',
                style: {
                    fontSize: '12px',
                    fontFamily: 'Inter, Arial, sans-serif'
                },
                custom: function({
                    series,
                    seriesIndex,
                    dataPointIndex,
                    w
                }) {
                    const dateLabel = currentXAxisLabels[dataPointIndex] || 'Unknown';
                    const value = series[seriesIndex][dataPointIndex];
                    return `
                    <div class="px-4 py-3 bg-white rounded-lg shadow-xl border border-gray-200" style="min-width: 180px;">
                        <div class="font-bold text-gray-900 text-lg">${value.toLocaleString()}</div>
                        <div class="text-sm text-gray-600 mt-1 font-medium">New Customers</div>
                        <div class="text-xs text-gray-500 mt-2 border-t border-gray-200 pt-2">${dateLabel}</div>
                    </div>
                `;
                }
            },
            colors: [brandColors[0]],
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '12px',
                fontWeight: 600,
                labels: {
                    colors: '#6b7280'
                }
            }
        };
        var growthChart = new ApexCharts(document.querySelector("#customer-growth-chart"), growthOptions);
        growthChart.render();
        updateStats(monthlyData);

        const filterButtons = document.querySelectorAll('#growth-chart-filters .filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                const period = button.dataset.period;
                let newData = {};
                switch (period) {
                    case 'yearly':
                        newData = yearlyData;
                        currentXAxisLabels = yearlyData.x_axis_labels;
                        break;
                    case 'monthly':
                        newData = monthlyData;
                        currentXAxisLabels = monthlyData.x_axis_labels;
                        break;
                    case 'weekly':
                        newData = weeklyData;
                        currentXAxisLabels = weeklyData.x_axis_labels;
                        break;
                    case 'daily':
                        newData = dailyData;
                        currentXAxisLabels = dailyData.x_axis_labels;
                        break;
                }
                currentData = newData;
                growthChart.updateOptions({
                    series: newData.series,
                    xaxis: {
                        categories: newData.categories
                    }
                });
                updateStats(newData);
            });
        });

        var submissionsOptions = {
            series: [{
                name: 'Submissions',
                data: @json($charts['hourly']['series'][0]['data'])
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '60%',
                    horizontal: false
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: @json($charts['hourly']['categories'])
            },
            colors: [brandColors[1]]
        };
        var submissionsChart = new ApexCharts(document.querySelector("#submissions-by-hour-chart"),
            submissionsOptions);
        submissionsChart.render();

        var productOptions = {
            series: @json($charts['products']['series']),
            chart: {
                type: 'donut',
                height: 300
            },
            labels: @json($charts['products']['labels']),
            legend: {
                position: 'bottom'
            },
            colors: brandColors
        };
        var productChart = new ApexCharts(document.querySelector("#product-popularity-chart"), productOptions);
        productChart.render();

        var coverageOptions = {
            series: @json($charts['coverage']['series']),
            chart: {
                type: 'donut',
                height: 300
            },
            labels: @json($charts['coverage']['labels']),
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Covered',
                                formatter: (w) => w.globals.series[0] + '%'
                            }
                        }
                    }
                }
            },
            colors: [brandColors[0], '#E5E7EB']
        };
        var coverageChart = new ApexCharts(document.querySelector("#coverage-chart"), coverageOptions);
        coverageChart.render();
    });
</script>@endsection
