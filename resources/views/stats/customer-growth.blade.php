@extends('layouts.app')

@section('title', 'Customer Growth Statistics')

@section('content')
    <div class="container-fluid py-4">
        
        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="h3 fw-bold mb-1">Customer Growth Analytics</h2>
                        <p class="text-muted small mb-0">Track customer acquisition trends over time</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded-4" style="background: #ffffff; overflow: hidden;">
                    
                    {{-- Card Header dengan Filter --}}
                    <div class="card-header bg-white border-bottom p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                {{-- Filter Buttons --}}
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary filter-btn" data-mode="yearly">
                                        <i class="fa fa-calendar me-1"></i> Yearly
                                    </button>
                                    <button type="button" class="btn btn-outline-primary filter-btn active" data-mode="monthly">
                                        <i class="fa fa-calendar me-1"></i> Monthly
                                    </button>
                                    <button type="button" class="btn btn-outline-primary filter-btn" data-mode="weekly">
                                        <i class="fa fa-calendar me-1"></i> Weekly
                                    </button>
                                    <button type="button" class="btn btn-outline-primary filter-btn" data-mode="daily">
                                        <i class="fa fa-calendar me-1"></i> Daily
                                    </button>
                                </div>
                            </div>

                            {{-- Year Selector untuk Monthly/Weekly --}}
                            <div class="col-md-4 text-end" id="yearSelector">
                                <label class="form-label mb-0 small fw-semibold">Year</label>
                                <select id="yearInput" class="form-select form-select-sm" style="width: 120px; display: inline-block;">
                                    @php
                                        $currentYear = now()->year;
                                        for ($year = 2020; $year <= $currentYear; $year++) {
                                            echo '<option value="' . $year . '" ' . ($year == $currentYear ? 'selected' : '') . '>' . $year . '</option>';
                                        }
                                    @endphp
                                </select>
                            </div>
                        </div>

                        {{-- Date Range Picker untuk Daily Mode --}}
                        <div id="dateRangeContainer" class="mt-3" style="display: none;">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Start Date</label>
                                    <input type="date" id="startDate" class="form-control form-control-sm" 
                                        value="{{ now()->subDays(29)->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">End Date</label>
                                    <input type="date" id="endDate" class="form-control form-control-sm"
                                        value="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary btn-sm w-100" id="applyDateRange">
                                        <i class="fa fa-check me-1"></i> Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Body dengan Chart --}}
                    <div class="card-body p-4">
                        {{-- Loading Spinner --}}
                        <div id="chartLoader" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2">Loading chart data...</p>
                        </div>

                        {{-- Chart Container --}}
                        <div id="chartContainer" style="display: none;">
                            <div id="customerGrowthChart" style="height: 400px;"></div>
                        </div>

                        {{-- Error Message --}}
                        <div id="chartError" class="alert alert-danger" style="display: none;"></div>
                    </div>

                    {{-- Card Footer dengan Info --}}
                    <div class="card-footer bg-light border-top p-4">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="mb-0">
                                    <p class="text-muted small mb-1">Total Customers</p>
                                    <h4 class="fw-bold text-primary" id="totalCustomers">0</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-0">
                                    <p class="text-muted small mb-1">Average per Period</p>
                                    <h4 class="fw-bold text-info" id="averagePerPeriod">0</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-0">
                                    <p class="text-muted small mb-1">Peak Period</p>
                                    <h4 class="fw-bold text-success" id="peakPeriod">-</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        {{-- ApexCharts Library --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.0/apexcharts.min.js"></script>

        <script>
            // ===== Konfigurasi =====
            const API_ENDPOINT = "{{ route('stats.customer-growth') }}";
            let currentMode = 'monthly';
            let chart = null;

            // ===== Inisialisasi Chart =====
            function initializeChart() {
                const options = {
                    series: [{
                        name: 'New Customers',
                        data: [],
                    }],
                    chart: {
                        type: 'area',
                        height: 400,
                        sparkline: { enabled: false },
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                selection: true,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: true,
                                reset: true
                            }
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
                    colors: ['#0d6efd'],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2.5
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
                    xaxis: {
                        categories: [],
                        labels: {
                            style: {
                                colors: '#6c757d',
                                fontSize: '12px'
                            },
                            rotateAlways: false,
                            rotate: 0
                        },
                        axisBorder: {
                            show: true,
                            color: '#dee2e6'
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: '#6c757d',
                                fontSize: '12px'
                            }
                        },
                        axisBorder: {
                            show: true,
                            color: '#dee2e6'
                        }
                    },
                    grid: {
                        borderColor: '#e9ecef',
                        strokeDashArray: 3,
                        xaxis: {
                            lines: {
                                show: false
                            }
                        }
                    },
                    tooltip: {
                        enabled: true,
                        theme: 'light',
                        x: {
                            show: true,
                            formatter: function(value, opts) {
                                return opts.w.globals.labels[opts.dataPointIndex];
                            }
                        },
                        y: {
                            formatter: function(value) {
                                return value + ' customers';
                            }
                        },
                        style: {
                            fontSize: '13px',
                            fontFamily: 'inherit'
                        }
                    },
                    responsive: [{
                        breakpoint: 1024,
                        options: {
                            xaxis: {
                                labels: {
                                    rotate: 45
                                }
                            }
                        }
                    }]
                };

                const chartElement = document.querySelector('#customerGrowthChart');
                chart = new ApexCharts(chartElement, options);
                chart.render();
            }

            // ===== Fetch Data dari API =====
            async function fetchData(mode, year = null, startDate = null, endDate = null) {
                const loader = document.getElementById('chartLoader');
                const container = document.getElementById('chartContainer');
                const errorDiv = document.getElementById('chartError');

                loader.style.display = 'block';
                container.style.display = 'none';
                errorDiv.style.display = 'none';

                try {
                    let url = `${API_ENDPOINT}?mode=${mode}`;

                    if (mode === 'yearly' || mode === 'monthly' || mode === 'weekly') {
                        url += `&year=${year || new Date().getFullYear()}`;
                    }

                    if (mode === 'daily') {
                        if (startDate) url += `&start_date=${startDate}`;
                        if (endDate) url += `&end_date=${endDate}`;
                    }

                    const response = await fetch(url);
                    const data = await response.json();

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    updateChart(data);
                    updateStats(data);

                    loader.style.display = 'none';
                    container.style.display = 'block';

                } catch (error) {
                    console.error('Error fetching data:', error);
                    loader.style.display = 'none';
                    errorDiv.style.display = 'block';
                    errorDiv.innerHTML = '<strong>Error:</strong> ' + error.message;
                }
            }

            // ===== Update Chart dengan Data Baru =====
            function updateChart(data) {
                if (chart) {
                    chart.updateOptions({
                        xaxis: {
                            categories: data.labels
                        }
                    });

                    chart.updateSeries([{
                        data: data.values
                    }]);
                }
            }

            // ===== Update Stats =====
            function updateStats(data) {
                const totalCustomers = document.getElementById('totalCustomers');
                const averagePerPeriod = document.getElementById('averagePerPeriod');
                const peakPeriod = document.getElementById('peakPeriod');

                const total = data.total || 0;
                const count = data.values.length || 1;
                const average = Math.round(total / count);
                
                const maxValue = Math.max(...data.values);
                const maxIndex = data.values.indexOf(maxValue);
                const peak = maxIndex >= 0 ? data.labels[maxIndex] : '-';

                totalCustomers.textContent = total.toLocaleString();
                averagePerPeriod.textContent = average.toLocaleString();
                peakPeriod.textContent = peak;
            }

            // ===== Event Listeners =====
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const mode = this.getAttribute('data-mode');
                    
                    // Update active button
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Show/hide year selector
                    const yearSelector = document.getElementById('yearSelector');
                    const dateRangeContainer = document.getElementById('dateRangeContainer');

                    if (mode === 'daily') {
                        yearSelector.style.display = 'none';
                        dateRangeContainer.style.display = 'block';
                    } else if (mode === 'yearly') {
                        yearSelector.style.display = 'none';
                        dateRangeContainer.style.display = 'none';
                    } else {
                        yearSelector.style.display = 'block';
                        dateRangeContainer.style.display = 'none';
                    }

                    currentMode = mode;
                    const year = document.getElementById('yearInput').value;
                    fetchData(mode, year);
                });
            });

            // Year selector change
            document.getElementById('yearInput').addEventListener('change', function() {
                const year = this.value;
                fetchData(currentMode, year);
            });

            // Apply date range untuk Daily mode
            document.getElementById('applyDateRange').addEventListener('click', function() {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                if (!startDate || !endDate) {
                    alert('Please select both start and end dates');
                    return;
                }

                fetchData(currentMode, null, startDate, endDate);
            });

            // ===== Load Initial Data =====
            document.addEventListener('DOMContentLoaded', function() {
                initializeChart();
                fetchData('monthly', new Date().getFullYear());
            });
        </script>

        <style>
            .btn-group .btn-outline-primary {
                border: 1px solid #0d6efd;
                color: #0d6efd;
                transition: all 0.3s ease;
            }

            .btn-group .btn-outline-primary:hover {
                background-color: #0d6efd;
                color: white;
            }

            .btn-group .btn-outline-primary.active {
                background-color: #0d6efd;
                color: white;
            }

            .card {
                transition: box-shadow 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            }

            /* Smooth transitions untuk chart */
            #customerGrowthChart {
                transition: all 0.3s ease;
            }
        </style>
    @endpush

@endsection
