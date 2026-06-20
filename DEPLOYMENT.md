# Panduan Deployment ke Shared Hosting (cPanel)

Aplikasi **Tibra Sare Hotel** ini dirancang menggunakan arsitektur Laravel + Blade murni tanpa dependensi Node.js di sisi *production server*. Ini membuatnya sangat ramah untuk disebarkan ke lingkungan *Shared Hosting* seperti cPanel yang biasanya tidak memiliki akses terminal tingkat lanjut atau Node.js.

## 1. Persiapan Lokal (Local Environment)

Sebelum mengunggah file ke *hosting*, jalankan perintah berikut di komputer Anda untuk meng-*compile* aset *frontend* (CSS & JS) dan menyingkirkan file pengembangan:

```bash
# Pastikan semua library PHP terinstall (tanpa module dev)
composer install --optimize-autoloader --no-dev

# Compile aset frontend dengan Vite untuk Production
npm run build
```

Perintah `npm run build` akan menghasilkan folder `public/build`. Folder inilah yang nantinya digunakan oleh sistem (tidak butuh folder `node_modules`).

## 2. Struktur File cPanel

Hosting cPanel biasanya membagi folder root (`/home/username`) menjadi dua area utama:
- `public_html/` (Tempat file yang dapat diakses publik via browser)
- Folder di luar `public_html/` (Tempat file rahasia/inti sistem)

### Langkah Pemindahan:
1. Buat folder baru di luar `public_html` (misal: `/home/username/tibra-sare-core`).
2. Pindahkan **seluruh isi** project Laravel ke dalam `/home/username/tibra-sare-core`, **KECUALI** folder `public`.
3. Pindahkan **isi** dari folder `public` Laravel Anda ke dalam folder `/home/username/public_html` (atau folder domain Anda).

## 3. Konfigurasi Path `index.php`

Karena file `index.php` sekarang berada terpisah dari folder inti Laravel, Anda harus mengarahkannya kembali ke path yang benar. Buka file `/home/username/public_html/index.php` lalu edit dua baris berikut:

```php
// Cari baris ini:
require __DIR__.'/../vendor/autoload.php';
// Ubah menjadi:
require __DIR__.'/../tibra-sare-core/vendor/autoload.php';

// Cari baris ini:
$app = require_once __DIR__.'/../bootstrap/app.php';
// Ubah menjadi:
$app = require_once __DIR__.'/../tibra-sare-core/bootstrap/app.php';
```

## 4. Konfigurasi Server (`.env`)

Ubah file `/home/username/tibra-sare-core/.env` untuk menyesuaikan dengan database dan *environment hosting*:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-hotel-anda.com

# Konfigurasi Database (Ganti dengan kredensial dari cPanel MySQL Databases)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_db_cpanel
DB_USERNAME=user_db_cpanel
DB_PASSWORD=password_db_cpanel
```

## 5. Storage Link (Penting!)

Jika Anda menggunakan fitur *upload file* (misal: Multiple Image Gallery), folder `storage/app/public` harus bisa diakses dari web. Karena kita memisahkan folder `public`, Anda dapat membuat *Symlink* via PHP Script.

Buat file `symlink.php` di dalam `public_html/`:
```php
<?php
$targetFolder = '/home/username/tibra-sare-core/storage/app/public';
$linkFolder = '/home/username/public_html/storage';
symlink($targetFolder, $linkFolder);
echo 'Symlink process successfully completed';
```
Kunjungi `https://domain-hotel-anda.com/symlink.php`, jika berhasil, langsung hapus file `symlink.php` tersebut.

## Selesai! 🎉
Aplikasi kini siap berjalan sepenuhnya di *shared hosting* tanpa perlu Node.js, pm2, atau proses _background worker_ lainnya.
