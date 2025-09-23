# Assets Guide - Life Media CMS

## Struktur Folder Assets

```
public/
├── images/
│   ├── Latarblkng Login.png          # Background login page (SUDAH ADA)
│   ├── logo-life-media.png           # Logo Life Media (SILAKAN TAMBAHKAN)
│   └── logo-life-media-white.png     # Logo putih untuk background gelap
```

## File yang Diperlukan

### 1. Background Login ✅ (Sudah Ada)
- **File**: `Latarblkng Login.png`
- **Lokasi**: `public/images/`
- **Deskripsi**: Background dengan gedung dan logo Life Media besar

### 2. Logo Life Media (Perlu Ditambahkan)
- **File**: `logo-life-media.png`
- **Lokasi**: `public/images/`
- **Format**: PNG dengan background transparan
- **Ukuran**: Minimal 200x80px
- **Deskripsi**: Logo untuk form login

### 3. Logo Putih (Opsional)
- **File**: `logo-life-media-white.png`
- **Lokasi**: `public/images/`
- **Format**: PNG dengan background transparan
- **Ukuran**: Minimal 200x80px
- **Deskripsi**: Logo putih untuk background gelap

## Cara Menggunakan Logo

### Di File `login.blade.php`:

```html
<!-- Untuk menggunakan logo file -->
<div class="form-logo">
    <img src="{{ asset('images/logo-life-media.png') }}" alt="Life Media Logo">
</div>

<!-- Atau menggunakan logo CSS (saat ini) -->
<div class="form-logo">
    <div class="form-logo-icon"></div>
    <div class="form-logo-text">Life media</div>
</div>
```

## Cara Upload Logo

1. **Siapkan logo dalam format PNG dengan background transparan**
2. **Upload ke folder**: `public/images/`
3. **Rename menjadi**: `logo-life-media.png`
4. **Uncomment baris img di login.blade.php**:
   ```html
   <img src="{{ asset('images/logo-life-media.png') }}" alt="Life Media Logo">
   ```
5. **Comment baris logo CSS**:
   ```html
   <!-- <div class="form-logo-icon"></div>
   <div class="form-logo-text">Life media</div> -->
   ```

## Tips Desain

- Logo sebaiknya memiliki rasio yang sesuai dengan design Figma
- Gunakan format PNG dengan background transparan
- Pastikan logo terlihat jelas pada background putih semi-transparan
- Ukuran logo tidak terlalu besar agar tidak mengganggu layout form

## Testing

Setelah upload logo, refresh halaman login untuk melihat hasilnya. Jika logo tidak muncul, cek:
1. Path file sudah benar
2. File format PNG
3. Permission folder `public/images/` bisa diakses
4. Cache browser sudah di-clear
