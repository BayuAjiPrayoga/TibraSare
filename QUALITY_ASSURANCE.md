# Quality Assurance (QA) & Panduan Testing

Dokumen ini berisi panduan untuk menjaga kualitas kode, melakukan *debugging*, dan menjalankan otomatisasi pengujian (*automated testing*) pada aplikasi **Tibra Sare**. Seluruh perangkat QA ini telah dikonfigurasi untuk memudahkan pengembangan.

---

## 1. Testing (Pengujian)

Aplikasi ini menggunakan **PHPUnit** bawaan Laravel untuk menjalankan pengujian secara otomatis. Testing mencakup:
- **Unit Testing**: Menguji logika fungsi terpisah.
- **Feature Testing**: Menguji fungsionalitas HTTP, Database, dan Workflow (seperti Autentikasi dan Pemesanan).

### Cara Menjalankan Test
Untuk menjalankan seluruh *test suite*, gunakan perintah berikut di terminal:
```bash
php artisan test
```

> **Catatan Penting:** Sebagian besar pengujian *Feature* menggunakan *trait* `RefreshDatabase`. Ini berarti database memori (SQLite) akan di-reset setiap kali test berjalan. Pastikan migrasi Anda selalu *up-to-date*.

---

## 2. Debugging

Untuk mempermudah pelacakan *bug* selama masa *development*, kami menggunakan **Laravel Telescope**.

### Laravel Telescope
Telescope merekam berbagai aktivitas secara detail, antara lain:
- Permintaan HTTP (*HTTP Requests*)
- Kueri Database beserta waktu eksekusinya
- *Exceptions* dan *Errors*
- Email yang terkirim
- *Jobs* dan *Events*

**Cara Mengakses:**
1. Pastikan server lokal berjalan (`php artisan serve`).
2. Buka browser dan arahkan ke: `http://localhost:8000/telescope`.

> **Perhatian:** Telescope sengaja diinstal di ranah pengembangan (`--dev`). Di *environment production*, Anda harus memastikan `APP_ENV=production` dan Telescope akan dinonaktifkan secara otomatis untuk menghemat sumber daya server.

---

## 3. Code Quality Tools (Standardisasi & Analisis Kode)

Untuk menjaga *codebase* tetap bersih, konsisten, dan mematuhi standar **PSR-12**, aplikasi ini mengintegrasikan tiga *tool* utama:

### A. Laravel Pint
*Tool* bawaan Laravel yang sangat cepat untuk memformat kode (*code style formatter*).
**Cara Menjalankan:**
```bash
./vendor/bin/pint
```

### B. PHP-CS-Fixer
Berguna sebagai pelengkap Pint dengan konfigurasi kustomisasi *ruleset* lebih lanjut (terletak di file `.php-cs-fixer.dist.php`).
**Cara Menjalankan:**
```bash
./vendor/bin/php-cs-fixer fix
```

### C. PHPStan / Larastan
Berfungsi untuk melakukan **Static Code Analysis** atau pengecekan *error* pada tipe data dan logika tanpa harus mengeksekusi (*run*) kodenya. Konfigurasi terdapat pada `phpstan.neon` dengan batasan ketat Level 5.
**Cara Menjalankan:**
```bash
./vendor/bin/phpstan analyse --memory-limit=512M
```
*(Parameter memory limit digunakan karena proses analisis statik membutuhkan memori PHP yang cukup besar).*

---

Dengan mengikuti pedoman di atas sebelum melakukan *commit* atau *deploy*, aplikasi Tibra Sare akan lebih stabil, bebas *bug* kritis, dan mudah dipelihara oleh sesama *developer*.
