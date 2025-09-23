# Folder Images

Simpan gambar background untuk halaman login di folder ini.

## File yang diperlukan:
- `building-background.jpg` - Gambar background gedung dengan logo Life Media

## Format yang disarankan:
- Format: JPG atau PNG
- Resolusi: Minimal 1920x1080px untuk kualitas terbaik
- Ukuran file: Maksimal 2MB untuk performa optimal

## Cara menggunakan:
Gambar akan otomatis dimuat di halaman login menggunakan Laravel asset helper:
```php
{{ asset('images/building-background.jpg') }}
```

## Alternatif jika belum ada gambar:
Sementara menunggu gambar yang sesuai, Anda bisa menggunakan placeholder atau gambar dari internet dengan mengubah URL di file `login.blade.php`.
