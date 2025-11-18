# Customer Growth Statistics Chart - Dokumentasi Lengkap

## 📊 Ikhtisar Fitur

Sistem Customer Growth Analytics dengan ApexCharts yang menampilkan grafik modern, interaktif, dan responsif untuk tracking pertumbuhan customer berdasarkan berbagai periode waktu.

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────┐
│                     Frontend (Blade)                        │
│  - Filter Buttons (Yearly/Monthly/Weekly/Daily)            │
│  - Date Range Picker (Daily Mode)                          │
│  - ApexCharts Area Chart (Gradient + Smooth)               │
│  - Stats Footer (Total, Average, Peak)                     │
└─────────────────────────────────────────────────────────────┘
                           ↑ API Call
                              ↓
┌─────────────────────────────────────────────────────────────┐
│                  API Backend (Controller)                   │
│  Route: GET /stats/customer-growth                         │
│  - Mode Parameter: yearly|monthly|weekly|daily             │
│  - Year Parameter: untuk monthly/weekly mode               │
│  - Date Range: start_date & end_date (daily mode)          │
│  - Return: JSON { labels: [...], values: [...], total }    │
└─────────────────────────────────────────────────────────────┘
                           ↓ Query DB
                              ↓
┌─────────────────────────────────────────────────────────────┐
│                  Database (Customer Model)                  │
│  Table: customer_leads                                      │
│  Used Field: created_at (untuk grouping data)              │
└─────────────────────────────────────────────────────────────┘
```

---

## 📁 File yang Dibuat/Dimodifikasi

### 1. **Backend**

#### `app/Http/Controllers/StatsController.php` (NEW)
- **Fungsi**: Menangani logika grouping data customer
- **Methods**:
  - `customerGrowth()` - Main endpoint yang menangani request
  - `getYearlyData()` - Group by YEAR
  - `getMonthlyData()` - Group by MONTH (fill semua bulan)
  - `getWeeklyData()` - Group by WEEK
  - `getDailyData()` - Group by DATE (dengan date range)

#### `routes/web.php` (MODIFIED)
- Added: `Route::get('/stats/customer-growth', [StatsController::class, 'customerGrowth'])`
- Added: `Route::get('/stats/customer-growth-view', ...)->name('stats.customer-growth-view')`

### 2. **Frontend**

#### `resources/views/stats/customer-growth.blade.php` (NEW)
- **Komponen UI**:
  - Filter Button Group (Yearly/Monthly/Weekly/Daily)
  - Year Selector (untuk Monthly/Weekly mode)
  - Date Range Picker (untuk Daily mode)
  - ApexCharts Area Chart
  - Stats Footer (Total, Average, Peak Period)

---

## 🚀 Cara Menggunakan

### 1. **Akses Halaman Chart**

Buka browser dan akses:
```
http://localhost/stats/customer-growth-view
```

### 2. **Filter Data**

#### Mode Yearly
- Klik tombol **[Yearly]**
- Menampilkan total customer per tahun dari 2020 hingga sekarang
- Tidak perlu pilih year (otomatis semua tahun)

#### Mode Monthly
- Klik tombol **[Monthly]**
- Pilih tahun di dropdown **Year**
- Chart menampilkan 12 bulan (Jan-Dec) dengan automatic fill untuk bulan tanpa data

#### Mode Weekly
- Klik tombol **[Weekly]**
- Pilih tahun di dropdown **Year**
- Chart menampilkan minggu W01-W52/W53

#### Mode Daily
- Klik tombol **[Daily]**
- Date Range Picker muncul
- Set tanggal awal (Start Date) dan akhir (End Date)
- Klik tombol **[Apply]** untuk update chart
- Default: 30 hari terakhir (auto-fill untuk tanggal tanpa data)

---

## 📊 API Response Format

### Request Example:

```bash
# Mode Monthly untuk tahun 2024
GET /stats/customer-growth?mode=monthly&year=2024

# Mode Daily dengan date range
GET /stats/customer-growth?mode=daily&start_date=2024-01-01&end_date=2024-01-31

# Mode Yearly (all years)
GET /stats/customer-growth?mode=yearly
```

### Response JSON Example:

```json
{
  "mode": "monthly",
  "year": 2024,
  "labels": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
  "values": [15, 23, 18, 31, 25, 28, 32, 26, 29, 35, 30, 28],
  "total": 320
}
```

---

## 🎨 Fitur Chart

### 1. **Tipe Chart**: Area Chart
- Menampilkan kurva smooth dengan gradient fill
- Mudah dibaca dan modern

### 2. **Styling**
- **Warna**: Blue gradient (#0d6efd)
- **Opacity**: Gradient dari 45% ke 5%
- **Stroke**: Smooth curve, width 2.5px
- **Grid**: Light border (#e9ecef) dengan dotted style

### 3. **Interaktivitas**
- **Toolbar**: Download, zoom, pan, reset
- **Tooltip**: Menunjukkan jumlah customer & label periode
- **Responsive**: Auto adjust pada breakpoint 1024px
- **Animation**: Smooth entrance & dynamic updates

### 4. **Responsive Design**
- Desktop: Full width dengan x-axis labels normal
- Tablet/Mobile: Labels rotated 45° untuk readability

---

## 🔧 Customization

### Ubah Warna Chart

Di file `customer-growth.blade.php`, cari section `colors`:

```javascript
colors: ['#0d6efd'],  // Ubah ke warna lain, e.g. '#28a745' (green)
```

### Ubah Tipe Chart

Ganti tipe di `chart` options:

```javascript
chart: {
    type: 'area',  // Ubah ke: 'line', 'bar', 'histogram', dll
```

### Ubah Tinggi Chart

```javascript
chart: {
    type: 'area',
    height: 400,  // Ubah ke height yang diinginkan
```

### Tambah Series Data Baru

Jika ingin menampilkan multiple lines, ubah `series`:

```javascript
series: [
    {
        name: 'New Customers',
        data: [],
    },
    {
        name: 'Active Customers',
        data: [],
    }
],
```

Dan update API untuk return multiple value arrays.

---

## ⚙️ Database Requirements

Pastikan tabel `customer_leads` memiliki field `created_at` (timestamp).

### Check struktur table:

```sql
DESC customer_leads;
```

Harus ada field:
- `id` (primary key)
- `created_at` (timestamp) ← **Digunakan untuk grouping**
- Semua field lainnya (name, email, phone, dll)

---

## 📈 Data Grouping Logic

### Yearly
```sql
SELECT YEAR(created_at) as period, COUNT(*) as count
FROM customer_leads
GROUP BY YEAR(created_at)
```

### Monthly
```sql
SELECT MONTH(created_at) as period, COUNT(*) as count
FROM customer_leads
WHERE YEAR(created_at) = 2024
GROUP BY MONTH(created_at)
-- Auto-fill: Jika bulan tidak ada, nilai = 0
```

### Weekly
```sql
SELECT WEEK(created_at) as period, COUNT(*) as count
FROM customer_leads
WHERE YEAR(created_at) = 2024
GROUP BY WEEK(created_at)
```

### Daily
```sql
SELECT DATE(created_at) as period, COUNT(*) as count
FROM customer_leads
WHERE created_at BETWEEN '2024-01-01' AND '2024-01-31'
GROUP BY DATE(created_at)
-- Auto-fill: Semua tanggal dalam range, jika tidak ada data = 0
```

---

## 🐛 Troubleshooting

### 1. Chart tidak muncul
**Solution**: 
- Pastikan ApexCharts CDN loaded (cek di Network tab)
- Check browser console untuk error message

### 2. Data tidak muncul
**Solution**:
- Pastikan ada data customer di database
- Cek API response di Network tab (pastikan return JSON valid)

### 3. Label tanggal menumpuk
**Solution**:
- Sudah handled otomatis di responsive settings
- Jika masih jelek, tambah breakpoint baru di `responsive[]`

### 4. Tooltip tidak muncul
**Solution**:
- Pastikan `tooltip: { enabled: true }` di options

---

## 📱 Mobile Responsiveness

Chart sudah responsive dengan breakpoint:
- **Desktop** (>1024px): Labels horizontal
- **Tablet/Mobile** (<1024px): Labels rotated 45°

Untuk custom breakpoint, edit di options:

```javascript
responsive: [{
    breakpoint: 768,  // Custom width
    options: {
        xaxis: {
            labels: { rotate: 45 }
        }
    }
}]
```

---

## 🔐 Security Notes

1. **Input Validation**: Sudah di-validate di controller
   - Mode: hanya accept 'yearly|monthly|weekly|daily'
   - Date: format Y-m-d dengan Carbon parsing
   
2. **SQL Injection**: Aman, menggunakan Eloquent ORM

3. **Auth**: Route sudah protected dengan `middleware('auth')`

---

## 🚀 Performance Tips

1. **Database Index**: Tambah index di field `created_at`
   ```sql
   CREATE INDEX idx_created_at ON customer_leads(created_at);
   ```

2. **Caching** (Optional): Cache hasil API untuk periode tertentu
   ```php
   return Cache::remember('customer-growth-'.$mode, 3600, function() {
       // Query logic
   });
   ```

3. **Pagination**: Jika dataset sangat besar, pertimbangkan pagination di API

---

## 📞 Support

Jika ada masalah atau ingin menambah fitur:
1. Check console untuk error messages
2. Inspect Network tab untuk API response
3. Check ApexCharts documentation: https://apexcharts.com

---

## 📋 Checklist Implementation

- [x] Backend Controller dengan 4 mode grouping
- [x] Frontend Blade dengan ApexCharts
- [x] Filter buttons (Yearly/Monthly/Weekly/Daily)
- [x] Date range picker untuk Daily mode
- [x] Stats footer (Total, Average, Peak)
- [x] Responsive design
- [x] Tooltip & animation
- [x] Error handling
- [x] Database grouping queries

---

**Status**: ✅ **Ready to Use**
**Last Updated**: 2024
