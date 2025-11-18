# Dashboard Integration Guide

## 📌 Tambah ke Menu Utama

### Option 1: Add Menu Item (Jika Menggunakan Menu System)

Edit sidebar atau menu Anda (biasanya di `resources/views/layouts/sidebar.blade.php`):

```blade
<li class="nav-item">
    <a class="nav-link" href="{{ route('stats.customer-growth-view') }}">
        <i class="fas fa-chart-line me-2"></i>
        <span>Customer Growth</span>
    </a>
</li>
```

---

## 📊 Embed Chart di Dashboard

### Option 1: Standalone Widget

Di halaman dashboard utama, tambahkan:

```blade
<div class="row">
    <div class="col-md-12">
        <iframe src="{{ route('stats.customer-growth-view') }}" 
                style="width: 100%; height: 600px; border: none; border-radius: 8px;">
        </iframe>
    </div>
</div>
```

### Option 2: Direct Chart Embed

Gunakan component blade atau include view:

```blade
@include('stats.customer-growth-chart')
```

Buat file `resources/views/stats/customer-growth-chart.blade.php` berisi:

```blade
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-4">
        <h5 class="mb-0">Customer Growth</h5>
    </div>
    <div class="card-body p-4">
        <div id="chartContainer">
            <div id="customerGrowthChart" style="height: 400px;"></div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.0/apexcharts.min.js"></script>
    <script>
        // Include script dari customer-growth.blade.php
        // atau refactor menjadi shared component
    </script>
@endpush
```

---

## 🎯 Dashboard Stats Cards

Tambahkan stats cards di dashboard:

```blade
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 small">Total Customers</p>
                        <h4 class="mb-0" id="dashboardTotal">-</h4>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 small">This Month</p>
                        <h4 class="mb-0" id="dashboardMonthly">-</h4>
                    </div>
                    <i class="fas fa-calendar fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 small">Growth Rate</p>
                        <h4 class="mb-0" id="dashboardGrowth">-</h4>
                    </div>
                    <i class="fas fa-arrow-up fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 small">Peak Period</p>
                        <h4 class="mb-0 small" id="dashboardPeak">-</h4>
                    </div>
                    <i class="fas fa-chart-line fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
```

### Fetch Data untuk Cards:

```javascript
<script>
    async function loadDashboardStats() {
        try {
            const response = await fetch("{{ route('stats.customer-growth') }}?mode=monthly&year=" + new Date().getFullYear());
            const data = await response.json();

            document.getElementById('dashboardTotal').textContent = data.total.toLocaleString();
            document.getElementById('dashboardMonthly').textContent = data.values[new Date().getMonth()] || 0;
            
            // Calculate growth
            const current = data.values[new Date().getMonth()] || 0;
            const previous = new Date().getMonth() > 0 ? (data.values[new Date().getMonth() - 1] || 0) : 0;
            const growth = previous > 0 ? Math.round(((current - previous) / previous) * 100) : 0;
            document.getElementById('dashboardGrowth').textContent = growth + '%';

            const maxIdx = data.values.indexOf(Math.max(...data.values));
            document.getElementById('dashboardPeak').textContent = data.labels[maxIdx];
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    // Load on page load
    document.addEventListener('DOMContentLoaded', loadDashboardStats);
</script>
```

---

## 🔄 Auto-Refresh Chart

Tambahkan auto-refresh untuk data real-time (optional):

```javascript
// Set interval untuk refresh setiap 5 menit
setInterval(function() {
    fetchData(currentMode);
}, 5 * 60 * 1000);  // 5 minutes
```

---

## 📊 Add to Analytics Menu

Jika ada menu khusus untuk Analytics, tambahkan route:

```blade
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
        <i class="fas fa-analytics me-2"></i> Analytics
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('stats.customer-growth-view') }}">
            <i class="fas fa-chart-line me-2"></i> Customer Growth
        </a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Other Reports</a></li>
    </ul>
</li>
```

---

## 🌐 Widget untuk Homepage (Public)

Jika ingin tampilkan chart di homepage publik (visitor bisa lihat):

```blade
<!-- resources/views/pages/analytics.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-5">Our Growth Story</h1>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body p-5">
                        <div id="publicChart" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.0/apexcharts.min.js"></script>
        <script>
            // Load chart data
            fetch("{{ route('stats.customer-growth') }}?mode=yearly")
                .then(res => res.json())
                .then(data => {
                    const options = {
                        series: [{ name: 'Customers', data: data.values }],
                        chart: { type: 'area', height: 500 },
                        xaxis: { categories: data.labels }
                    };
                    new ApexCharts(document.querySelector('#publicChart'), options).render();
                });
        </script>
    @endpush
@endsection
```

Route untuk public view:

```php
Route::get('/analytics', function() {
    return view('pages.analytics');
})->name('analytics');
```

---

## 📱 Mobile Dashboard Widget

Untuk mobile-responsive dashboard:

```blade
<div class="row">
    <div class="col-12 col-md-6 col-lg-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Customer Growth</h6>
            </div>
            <div class="card-body" style="overflow-x: auto;">
                <div id="mobileChart" style="height: 300px; min-width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Responsive chart untuk mobile
    function initMobileChart() {
        fetch("{{ route('stats.customer-growth') }}?mode=monthly&year=" + new Date().getFullYear())
            .then(res => res.json())
            .then(data => {
                const options = {
                    series: [{ name: 'Customers', data: data.values }],
                    chart: {
                        type: 'bar',  // Bar lebih cocok untuk mobile
                        height: 300
                    },
                    xaxis: { categories: data.labels }
                };
                new ApexCharts(document.querySelector('#mobileChart'), options).render();
            });
    }

    document.addEventListener('DOMContentLoaded', initMobileChart);
</script>
```

---

## 🔐 Permission Check

Jika ada role-based access control:

```blade
@can('view-stats')
    <a href="{{ route('stats.customer-growth-view') }}" class="btn btn-primary">
        View Analytics
    </a>
@endcan
```

Edit route untuk add permission middleware:

```php
Route::get('/stats/customer-growth-view', function() {
    return view('stats.customer-growth');
})->middleware(['auth', 'can:view-stats'])->name('stats.customer-growth-view');
```

---

## 🎯 Breadcrumb Navigation

Tambahkan breadcrumb untuk navigasi yang lebih baik:

```blade
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#">Analytics</a>
        </li>
        <li class="breadcrumb-item active">Customer Growth</li>
    </ol>
</nav>
```

---

## 📊 Export Report Button

Tambahkan button untuk export laporan:

```blade
<div class="d-flex gap-2 mb-3">
    <button type="button" class="btn btn-sm btn-primary" onclick="exportChartImage()">
        <i class="fas fa-download me-1"></i> Download Chart
    </button>
    <button type="button" class="btn btn-sm btn-success" onclick="exportData()">
        <i class="fas fa-file-csv me-1"></i> Export CSV
    </button>
</div>

<script>
    function exportChartImage() {
        chart.dataURI().then(({ imgURI, blob }) => {
            const link = document.createElement('a');
            link.href = imgURI;
            link.download = 'customer-growth.png';
            link.click();
        });
    }

    function exportData() {
        // CSV export logic
    }
</script>
```

---

## 🔄 Integration Checklist

- [ ] Add menu item di sidebar
- [ ] Test akses route `/stats/customer-growth-view`
- [ ] Verify chart muncul dan load data
- [ ] Test semua filter buttons
- [ ] Test date range picker
- [ ] Check responsive di mobile
- [ ] Setup permissions (if needed)
- [ ] Add stats cards di dashboard
- [ ] Setup auto-refresh (if needed)

---

**Integration selesai! 🎉**
