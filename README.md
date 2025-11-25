# Quick Start — Web CMS Compro Life Media

Panduan singkat untuk menjalankan proyek ini secara lokal.

## Prasyarat
- PHP 8.0+ (sesuaikan dengan requirement di composer.json)
- Composer
- Node.js + npm (atau pnpm/yarn)
- MySQL / MariaDB (atau database lain yang didukung)
- Git
- (Opsional) Docker & Docker Compose

## Langkah instalasi lokal

1. Clone repository
```bash
git clone https://github.com/Magang-VAS/Web-CMS-Compro-Life-Media.git
cd Web-CMS-Compro-Life-Media
```

2. Install dependensi PHP
```bash
composer install
```

3. Salin file environment dan konfigurasi
```bash
cp .env.example .env
```
- Edit `.env` dan isi konfigurasi database, mail, dan layanan lain:
  - DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
  - MAIL_MAILER, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_ADDRESS
  - CACHE_DRIVER / QUEUE_CONNECTION jika dipakai

4. Generate application key
```bash
php artisan key:generate
```

5. Jalankan migrasi dan seeding (jika ada)
```bash
php artisan migrate
php artisan db:seed
```

6. Link storage (untuk menyajikan file upload)
```bash
php artisan storage:link
```

7. Install dependensi frontend dan build assets
- Jika proyek menggunakan Vite:
```bash
npm install
npm run dev   # untuk development
npm run build # untuk production
```
- Jika menggunakan Laravel Mix:
```bash
npm install
npm run dev
npm run prod
```

8. Jalankan server lokal
```bash
php artisan serve
# atau buka http://localhost:8000
```

## Alternatif: Docker (opsional)
Jika ingin menggunakan Docker, siapkan Dockerfile / docker-compose.yml (jika belum ada, saya bisa bantu membuatkan). Contoh alur singkat:
- Build image PHP + ext + Composer
- Service: app (PHP), db (MySQL), redis (opsional), nginx (opsional)
- Jalankan migrate & seed via artisan dalam container

## Catatan penting
- Konvensi CSS: Tailwind hanya pakai prefix `tw-`; Bootstrap tanpa prefix. Jangan mencampur kelas keduanya pada satu elemen tanpa alasan.
- Simpan gambar background login di `public/images` (lihat `public/images/README.md`)
- Pastikan hak akses folder `storage/` dan `bootstrap/cache/` sudah benar.

## Troubleshooting singkat
- 500 error setelah migrasi: cek `.env` dan permission folder `storage`.
- Assets tidak berubah: bersihkan cache view dan config:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```
