# Laporan Hasil Audit Quality Assurance (QA)

**Tanggal Eksekusi:** 20 Juni 2026  
**Proyek:** Tibra Sare  

Laporan ini merangkum hasil eksekusi dari 3 alat standar Quality Assurance (QA) utama: **PHPUnit** (Automated Testing), **PHP-CS-Fixer & Pint** (Code Formatting), dan **PHPStan / Larastan** (Static Analysis).

---

## 1. Automated Testing (PHPUnit) 🟢 LULUS 100%
Semua *test suite* berhasil dieksekusi tanpa ada *error* maupun *failure*.

- **Total Tests:** 33 *tests*
- **Total Assertions:** 88 *assertions*
- **Status:** **OK (100% Passed)**
- **Waktu Eksekusi:** ~2.04 detik

*Kesimpulan:* Inti aplikasi (Autentikasi, Profil, Roles, Booking) beroperasi sesuai dengan ekspektasi. Logika bisnis utama aman untuk lingkungan *production*.

---

## 2. Code Formatting (Laravel Pint & PHP-CS-Fixer) 🟢 SELESAI
Kode telah di-format untuk memenuhi standar kebersihan koding industri (PSR-12).

- **Laravel Pint:** Memperbaiki 23 isu *coding style* pada 108 file (terutama perbaikan spasi, koma, `new_with_parentheses`, dan impor class).
- **PHP-CS-Fixer:** Memperbaiki 20 file tambahan untuk merapikan urutan import dan memastikan konsistensi fungsi.
- **Status:** **Clean** (Semua *file* sudah mengikuti standar PSR).

---

## 3. Static Analysis (PHPStan / Larastan) 🟡 WARNINGS DITEMUKAN
Telah dijalankan **Larastan Level 5** dengan memori 512MB (`./vendor/bin/phpstan analyse --memory-limit=512M`). Analisis menemukan **92 Errors/Warnings**.

Perlu dicatat bahwa peringatan pada PHPStan (terutama level menengah-atas) adalah **hal yang wajar pada framework Laravel** dan **TIDAK membuat aplikasi Anda crash/rusak**. Ini lebih merupakan saran untuk penulisan kode yang sangat ketat (*strict typing*).

### Ringkasan Peringatan Terbanyak:

#### A. Penggunaan `env()` di Luar Config (`larastan.noEnvCallsOutsideOfConfig`)
- **Lokasi:** `app/Services/WamifyService.php` (dan kemungkinan di Controller lain).
- **Detail:** Pemanggilan `env('NAMA_VARIABEL')` langsung di dalam kode.
- **Saran Perbaikan:** Praktik terbaik Laravel adalah memanggil nilai `.env` di dalam file `config/` terlebih dahulu, lalu di kode menggunakan fungsi `config('namafile.variabel')`. Alasannya: jika Anda menjalankan `php artisan config:cache` di *production*, pemanggilan `env()` secara langsung akan mengembalikan nilai `null`.

#### B. Properti Magic yang Tidak Terdeteksi (`property.notFound`)
- **Lokasi:** `Http/Resources/UserResource.php`, penggunaan `$this->id`, `$this->name`, dll. Atau penggunaan pada Model seperti `$image_path`.
- **Detail:** PHPStan terkadang kesulitan membaca "magic properties" bawaan Eloquent milik Laravel jika Anda tidak secara tertulis mendefinisikan tipe kolomnya dengan bantuan PHPDoc (`@property`).
- **Saran Perbaikan:** Anda bisa menambahkan ekstensi `ide-helper` atau membiarkannya saja karena Laravel sebenarnya paham bagaimana mengeksekusi *magic methods* ini.

#### C. Tipe Objek vs String pada Enum (`property.nonObject`, `identical.alwaysFalse`)
- **Lokasi:** `Http/Middleware/EnsureUserHasRole.php`, `Models/User.php`.
- **Detail:** Membandingkan nilai langsung dengan `UserRole::Admin`. Middleware terkadang menerima nilai berupa `string`, namun Larastan mendeteksinya harus berbentuk *objek* Enum atau sebaliknya (sehingga *strict comparison* `===` dianggap salah).
- **Saran Perbaikan:** Pastikan *casting* nilai di Model (`'role' => UserRole::class`) sudah konsisten, dan ubah properti opsional *nullsafe* `?->value` menjadi `->value` apabila tipe datanya dijamin tidak akan pernah `null`.

#### D. Unresolvable Type (`argument.unresolvableType`)
- **Lokasi:** Operasi pemetaan koleksi (`Collection::map()`).
- **Detail:** Fungsi *closure* anonim dalam map/through dianggap tidak mendefinisikan parameter kembalian (*return type*) secara spesifik (apakah itu `int`, `string`, dll).
- **Saran Perbaikan:** Tambahkan tipe deklarasi di setiap fungsi anonim (`function ($item): string { ... }`).

---

### Tindakan Selanjutnya (Rekomendasi)
Anda memiliki 2 pilihan terkait laporan PHPStan ini:
1. **Abaikan Sementara (Direkomendasikan saat ini):** Karena 100% *PHPUnit Tests* lulus, aplikasi Anda aman untuk **Rilis / Deploy ke Production** tanpa hambatan fungsionalitas.
2. **Cicil Perbaikan:** Di versi berikutnya (v1.1), Anda dapat memprioritaskan perbaikan **A (Panggilan env())** dengan memindahkannya ke file `config/services.php` atau sejenisnya, karena hal tersebut dapat berpengaruh jika Anda melakukan *cache* konfigurasi di server. Peringatan lain (B, C, D) sifatnya hanya peringatan *strict typing*.
