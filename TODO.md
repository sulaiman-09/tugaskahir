# TODO: Perbaiki Masalah Login - Tahapan Eksekusi

## Status Saat Ini
- Tabel users tidak ada (konfirmasi phpMyAdmin).
- Migrasi pending: divisions, news, settings_contents (tabel ada dari import, tapi belum ditandai).
- Tujuan: Tandai migrasi, buat users, seed data, clear cache, test – tanpa hapus data import.

## Tahapan Eksekusi

### Tahap 1: Tandai Migrasi Pending sebagai Ran
- [ ] Jalankan command Tinker untuk insert ke tabel migrations (divisions, news, settings_contents).
- Penjelasan: Fake-run supaya Laravel anggap selesai, hindari error "already exists".

### Tahap 2: Migrasi Tabel Users
- [ ] Jalankan `php artisan migrate --path=...create_users_table.php`.
- Penjelasan: Buat tabel users baru karena hilang.

### Tahap 3: Seed Data User
- [ ] Jalankan `php artisan db:seed --class=UserSeeder`.
- Penjelasan: Isi user default (admin, sales, dll.) untuk login.

### Tahap 4: Clear Cache
- [ ] Jalankan `php artisan config:clear`, `cache:clear`, `route:clear`.
- Penjelasan: Refresh config setelah ubah DB.

### Tahap 5: Verifikasi dan Test
- [ ] Cek `migrate:status`, start server, test login dan role access.
- Penjelasan: Konfirmasi sistem bekerja.

## Progress
- Semua [ ] – Mulai dari Tahap 1.

Terakhir Diperbarui: Sebelum Tahap 1
