# 🚀 Quick Start - Customer Growth Analytics

## ⚡ 5 Menit Setup

### Step 1: Verifikasi Routes
```bash
php artisan route:list | grep stats
```

Harus muncul:
```
stats/customer-growth .................. stats.customer-growth
stats/customer-growth-view ............. stats.customer-growth-view
```

### Step 2: Akses Halaman
Buka browser:
```
http://localhost/stats/customer-growth-view
```

### Step 3: Filter Data

| Filter | Aksi |
|--------|------|
| [Yearly] | Click → Chart otomatis update (semua tahun) |
| [Monthly] | Click → Pilih tahun → Chart update |
| [Weekly] | Click → Pilih tahun → Chart update |
| [Daily] | Click → Set tanggal → Klik [Apply] |

### Step 4: Cek API Response (Optional)
```bash
# Browser atau curl
curl "http://localhost/stats/customer-growth?mode=monthly&year=2024"
```

---

## 📁 Files Created

```
app/Http/Controllers/StatsController.php         [API Backend]
resources/views/stats/customer-growth.blade.php  [UI + Chart]
routes/web.php                                   [Modified - Routes added]

QUICK_START.md                                   [This file]
IMPLEMENTATION_SUMMARY.md                        [Overview]
CUSTOMER_GROWTH_CHART_DOCS.md                    [Full Docs]
CUSTOMER_GROWTH_QUERY_EXAMPLES.md                [Test Examples]
APEXCHARTS_CUSTOMIZATION.md                      [Advanced Customization]
```

---

## ✅ Checklist

- [ ] Buka `http://localhost/stats/customer-growth-view`
- [ ] Klik filter Yearly
- [ ] Klik filter Monthly + pilih tahun
- [ ] Klik filter Weekly + pilih tahun
- [ ] Klik filter Daily + set date range
- [ ] Lihat chart update dengan smooth animation
- [ ] Check footer stats (Total, Average, Peak)
- [ ] Test API dengan postman: `GET /stats/customer-growth?mode=monthly&year=2024`

---

## 🔍 Verify Data

Pastikan ada customer data di database:

```bash
# Dengan MySQL CLI
mysql> SELECT COUNT(*) FROM customer_leads;

# Atau dengan Laravel Tinker
php artisan tinker
>>> \App\Models\Customer::count()
```

Jika kosong, insert sample data dulu.

---

## 🎨 Customize (Optional)

### Change Chart Color
Edit `resources/views/stats/customer-growth.blade.php`:
```javascript
colors: ['#28a745'],  // Ubah ke warna lain (green)
```

### Change Chart Type
```javascript
chart: {
    type: 'line',  // Ubah dari 'area'
```

### Change Chart Height
```javascript
chart: {
    height: 500,  // Ubah dari 400
```

---

## 🐛 Troubleshooting

### Error: Route not found
```bash
php artisan route:cache
php artisan route:clear
```

### Error: Chart tidak muncul
1. Buka DevTools (F12)
2. Check Console untuk error message
3. Check Network tab → pastikan ApexCharts CDN loaded

### Error: No data shown
1. Check database punya data customer
2. Check API response (Network tab)
3. Format tanggal harus Y-m-d (YYYY-MM-DD)

---

## 📖 Documentation

Untuk penjelasan lebih detail, baca:

1. **IMPLEMENTATION_SUMMARY.md** - Ringkasan implementasi & struktur
2. **CUSTOMER_GROWTH_CHART_DOCS.md** - Dokumentasi lengkap sistem
3. **CUSTOMER_GROWTH_QUERY_EXAMPLES.md** - Test examples & API calls
4. **APEXCHARTS_CUSTOMIZATION.md** - Advanced customization tips

---

## 🚀 Next Steps

**Setelah verify berjalan:**

1. Customize UI sesuai kebutuhan
2. Add ke dashboard/menu utama
3. Setup scheduled reports (optional)
4. Configure caching untuk performa (optional)

---

**Ready to go! 🎉**

Akses sekarang: http://localhost/stats/customer-growth-view
