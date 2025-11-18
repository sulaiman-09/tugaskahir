# Query Examples - Customer Growth Statistics

## 📊 Test API Endpoints

Gunakan Postman, curl, atau browser untuk test endpoint berikut:

---

## 1️⃣ YEARLY MODE (Semua Tahun)

```
GET http://localhost/stats/customer-growth?mode=yearly
```

**Response:**
```json
{
  "mode": "yearly",
  "labels": [2020, 2021, 2022, 2023, 2024],
  "values": [45, 123, 256, 512, 789],
  "total": 1725
}
```

**Penjelasan:**
- `labels`: Array tahun-tahun yang ada data customernya
- `values`: Total customer per tahun
- `total`: Grand total semua customer

---

## 2️⃣ MONTHLY MODE (Per Bulan dalam Tahun Tertentu)

```
GET http://localhost/stats/customer-growth?mode=monthly&year=2024
```

**Response:**
```json
{
  "mode": "monthly",
  "year": 2024,
  "labels": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
  "values": [15, 23, 18, 0, 31, 25, 28, 32, 26, 29, 35, 30],
  "total": 292
}
```

**Penjelasan:**
- Auto-fill: Jika bulan tidak ada data (Apr = 0), tetap ditampilkan
- Label format: 3-letter month name
- Total: Sum semua bulan

**Varian:**
```
GET http://localhost/stats/customer-growth?mode=monthly&year=2023
GET http://localhost/stats/customer-growth?mode=monthly&year=2022
```

---

## 3️⃣ WEEKLY MODE (Per Minggu dalam Tahun Tertentu)

```
GET http://localhost/stats/customer-growth?mode=weekly&year=2024
```

**Response:**
```json
{
  "mode": "weekly",
  "year": 2024,
  "labels": ["W01", "W02", "W03", ..., "W52"],
  "values": [8, 12, 10, 15, 9, ..., 11],
  "total": 520
}
```

**Penjelasan:**
- `labels`: W01 sampai W52 (atau W53 jika tahun punya 53 minggu)
- Auto-fill: Minggu tanpa data = 0
- Format label: W + nomor minggu (padded 2 digit)

**Varian:**
```
GET http://localhost/stats/customer-growth?mode=weekly&year=2023
```

---

## 4️⃣ DAILY MODE (Per Hari dalam Date Range)

### Default (30 hari terakhir)
```
GET http://localhost/stats/customer-growth?mode=daily
```

**Response:**
```json
{
  "mode": "daily",
  "start_date": "2024-11-18",
  "end_date": "2024-12-17",
  "labels": ["18 Nov", "19 Nov", "20 Nov", ..., "17 Dec"],
  "values": [5, 8, 3, 0, 6, 4, 7, ...],
  "total": 152
}
```

**Penjelasan:**
- Auto-fill: Tanggal tanpa data = 0
- Range: Otomatis 30 hari ke belakang dari hari ini
- Label format: DD MMM (e.g., "18 Nov")

### Custom Date Range
```
GET http://localhost/stats/customer-growth?mode=daily&start_date=2024-01-01&end_date=2024-01-31
```

**Response:**
```json
{
  "mode": "daily",
  "start_date": "2024-01-01",
  "end_date": "2024-01-31",
  "labels": ["01 Jan", "02 Jan", "03 Jan", ..., "31 Jan"],
  "values": [12, 15, 8, 10, 14, 9, 11, ...],
  "total": 310
}
```

### Edge Cases

**Range terbalik (start > end)** - Otomatis di-swap:
```
GET http://localhost/stats/customer-growth?mode=daily&start_date=2024-01-31&end_date=2024-01-01
```
→ Akan di-swap menjadi: start=2024-01-01, end=2024-01-31

**Invalid date format** - Error response:
```
GET http://localhost/stats/customer-growth?mode=daily&start_date=31-01-2024&end_date=01-02-2024
```

**Response:**
```json
{
  "mode": "daily",
  "error": "Invalid date format. Use Y-m-d",
  "labels": [],
  "values": []
}
```

---

## 🔧 Test dengan CURL

```bash
# Test Yearly
curl "http://localhost/stats/customer-growth?mode=yearly"

# Test Monthly
curl "http://localhost/stats/customer-growth?mode=monthly&year=2024"

# Test Weekly
curl "http://localhost/stats/customer-growth?mode=weekly&year=2024"

# Test Daily (default 30 hari)
curl "http://localhost/stats/customer-growth?mode=daily"

# Test Daily (custom range)
curl "http://localhost/stats/customer-growth?mode=daily&start_date=2024-01-01&end_date=2024-01-31"
```

---

## 🧪 Test dengan PHP Artisan Tinker

```bash
# Buka tinker
php artisan tinker

# Test Yearly
>>> \App\Models\Customer::selectRaw('YEAR(created_at) as period, COUNT(*) as count')->groupByRaw('YEAR(created_at)')->get();

# Test Monthly untuk 2024
>>> \App\Models\Customer::whereYear('created_at', 2024)->selectRaw('MONTH(created_at) as period, COUNT(*) as count')->groupByRaw('MONTH(created_at)')->get();

# Test Weekly untuk 2024
>>> \App\Models\Customer::whereYear('created_at', 2024)->selectRaw('WEEK(created_at) as period, COUNT(*) as count')->groupByRaw('WEEK(created_at)')->get();

# Test Daily untuk range
>>> \App\Models\Customer::whereBetween('created_at', ['2024-01-01', '2024-01-31'])->selectRaw('DATE(created_at) as period, COUNT(*) as count')->groupByRaw('DATE(created_at)')->get();
```

---

## 📊 Sample Data untuk Testing

Jika ingin test dengan sample data:

```php
// Di controller atau seeder
use App\Models\Customer;
use Carbon\Carbon;

// Create 100 sample customers spread across dates
for ($i = 0; $i < 100; $i++) {
    Customer::create([
        'customer_name' => 'Customer ' . $i,
        'customer_phone' => '081234567890',
        'email' => 'customer' . $i . '@example.com',
        'address' => 'Sample Address',
        'province' => 'Sample Province',
        'city' => 'Sample City',
        'created_at' => Carbon::now()->subDays(rand(0, 365)),
    ]);
}
```

---

## ✅ Validasi Response

Setiap response seharusnya memiliki struktur:

```json
{
  "mode": "yearly|monthly|weekly|daily",
  "labels": [...],      // Array string
  "values": [...],      // Array integer
  "total": 0,           // Integer (sum dari values)
  "year": 0,            // Optional (untuk yearly/monthly/weekly)
  "start_date": "",     // Optional (untuk daily)
  "end_date": ""        // Optional (untuk daily)
}
```

---

## 🐛 Debug Tips

### 1. Enable Query Logging (Laravel)
```php
// Di AppServiceProvider atau controller
\DB::enableQueryLog();

// Setelah eksekusi query
dd(\DB::getQueryLog());
```

### 2. Check Data di Database
```sql
-- Total customers
SELECT COUNT(*) FROM customer_leads;

-- Customers per year
SELECT YEAR(created_at), COUNT(*) 
FROM customer_leads 
GROUP BY YEAR(created_at);

-- Customers per month (2024)
SELECT MONTH(created_at), COUNT(*) 
FROM customer_leads 
WHERE YEAR(created_at) = 2024
GROUP BY MONTH(created_at);
```

### 3. Inspect Frontend Request
- Buka DevTools (F12)
- Network tab
- Cari request ke `/stats/customer-growth`
- Lihat response JSON di Preview/Response tab

---

## 🚨 Common Errors & Solutions

### Error: "No data returned"
**Cause**: Database tidak punya data customer
**Solution**: Insert sample data dulu (lihat bagian "Sample Data untuk Testing")

### Error: "Invalid date format"
**Cause**: Format tanggal tidak Y-m-d
**Solution**: Gunakan format `YYYY-MM-DD`, e.g., `2024-01-31`

### Error: "year parameter invalid"
**Cause**: Tahun tidak valid atau di-pass sebagai string
**Solution**: Pastikan year adalah integer dan valid (2020-sekarang)

### Error: CORS issue
**Cause**: Request dari origin berbeda
**Solution**: Pastikan API dan Frontend di domain yang sama

---

**Dokumentasi Query Examples | Ready to Test ✅**
