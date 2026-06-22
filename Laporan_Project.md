---
title: "Laporan Proyek: Sistem Informasi Reservasi Hotel Tibra Sare"
author: "Tim Developer"
date: "2026-06-23"
version: "2.0"
---

# Laporan Proyek: Tibra Sare
**Pengembangan Platform Reservasi dan Manajemen Penginapan Berbasis Web (Mobile-First)**

---

## 1. Informasi Proyek

| Item | Detail |
|---|---|
| **Nama Proyek** | Tibra Sare — Hotel & Resort Reservation System |
| **URL Produksi** | https://tibrasare.luhur.my.id |
| **Teknologi Utama** | Laravel 12, Tailwind CSS, MySQL, Alpine.js |
| **Integrasi Pihak Ketiga** | Xendit (Payment Gateway), Wamify (WhatsApp API) |
| **Autentikasi** | Laravel Breeze + Google OAuth (Socialite) |
| **Status** | ✅ Siap Produksi (Production Ready) |
| **Deployment** | Git-based deployment pada cPanel Shared Hosting |

---

## 2. Analisis Sistem

### 2.1 Latar Belakang
Sistem Informasi Reservasi "Tibra Sare" dibangun untuk memecahkan kendala pengelolaan reservasi manual dan memberikan pengalaman pemesanan yang mulus (*seamless*) bagi pelanggan secara *online*.

### 2.2 Tujuan Sistem
- Menyediakan *platform booking online* yang responsif (*Mobile-First*) dengan standar antarmuka UI/UX premium.
- Mengotomatiskan proses konfirmasi pembayaran menggunakan integrasi *Webhook* Xendit.
- Memberikan notifikasi instan kepada tamu melalui Email dan WhatsApp API (Wamify).
- Menyediakan *Dashboard* bagi Admin dan Resepsionis untuk manajemen kamar, kategori kamar, dan laporan pendapatan.
- Menyediakan fitur QR Code untuk proses Check-In dan Check-Out otomatis tanpa intervensi manual.

### 2.3 Arsitektur Sistem
Sistem menggunakan arsitektur **MVC (Model-View-Controller)** dengan fitur **PWA (Progressive Web App)** melalui implementasi *Service Worker* (`sw.js`). Strategi *Network-First* diterapkan untuk navigasi *guest* demi memastikan ketersediaan data *real-time*.

### 2.4 Aktor Sistem

| Aktor | Hak Akses |
|---|---|
| **Admin** | Manajemen kamar, kategori, fasilitas, pengguna, laporan, pengaturan, check-in/out, reservasi |
| **Resepsionis** | Check-in/out via QR Scanner, manajemen reservasi, data tamu |
| **Tamu (Guest)** | Registrasi, login (termasuk Google OAuth), booking kamar, pembayaran online, lihat QR Code, lihat riwayat |

---

## 3. Struktur Database

Database dirancang menggunakan model relasional untuk mencegah redundansi data dan memastikan integritas reservasi.

### 3.1 Entity Relationship

| Tabel | Deskripsi | Relasi |
|---|---|---|
| `users` | Kredensial akses staf & tamu (role: admin, receptionist, guest) | HasMany → reservations, activity_logs |
| `guests` | Data identitas tamu (Nama, Email, HP, NIK) | HasMany → reservations |
| `room_categories` | Kategori kamar (Saung Alit, Bumi Pasundan, Puri Parahyangan) + harga dasar | HasMany → rooms |
| `rooms` | Unit kamar fisik + status (available, occupied, reserved, maintenance) | BelongsTo → category, BelongsToMany → facilities |
| `facilities` | Data fasilitas (WiFi, AC, TV, dll.) | BelongsToMany → rooms |
| `room_facility` | Pivot table *Many-to-Many* rooms ↔ facilities | — |
| `room_images` | Galeri foto per kamar | BelongsTo → room |
| `reservations` | Transaksi inti: guest_id → room_id, tanggal, harga, payment_status, qr_code_path | BelongsTo → guest, room, creator |
| `activity_logs` | Audit trail tindakan staf | BelongsTo → user |
| `settings` | Konfigurasi dinamis hotel (key-value) | — |

### 3.2 Enum yang Digunakan

| Enum | Nilai |
|---|---|
| `ReservationStatus` | `reserved`, `checked_in`, `checked_out`, `cancelled` |
| `RoomStatus` | `available`, `occupied`, `maintenance`, `reserved` |
| `UserRole` | `admin`, `receptionist`, `guest` |

### 3.3 Migrasi Database

| File Migrasi | Keterangan |
|---|---|
| `create_users_table` | Tabel pengguna bawaan Laravel + kolom `role` |
| `create_cache_table` | Cache driver database |
| `create_jobs_table` | Queue driver database |
| `create_hotel_foundation_tables` | Tabel inti: rooms, room_categories, guests, reservations, facilities, room_facility, activity_logs |
| `add_image_path_to_room_categories` | Kolom gambar untuk kategori kamar |
| `create_room_images_table` | Tabel galeri foto kamar |
| `add_avatar_to_users_table` | Kolom foto profil pengguna |
| `add_payment_columns_to_reservations` | Kolom `payment_url`, `payment_status` untuk integrasi Xendit |
| `create_settings_table` | Tabel pengaturan dinamis |
| `add_qr_code_path_to_reservations` | Kolom `qr_code_path` untuk QR Code check-in |
| `create_telescope_entries_table` | Tabel debugging Telescope (development only) |

---

## 4. Implementasi

### 4.1 Fase Backend & API

| Komponen | File | Fungsi |
|---|---|---|
| Reservasi | `ReservationController` | CRUD reservasi + integrasi Xendit Invoice |
| Booking Tamu | `BookingController` | Formulir pemesanan publik per kategori kamar |
| Pembayaran | `PaymentCallbackController` | Webhook handler Xendit (PAID/SETTLED/EXPIRED) |
| Pembayaran | `XenditService` | Service class untuk membuat Invoice Xendit |
| Check-In | `CheckInController` | Proses check-in via QR Code (auto-submit) |
| Check-Out | `CheckOutController` | Proses check-out via QR Code (auto-submit) |
| Dashboard | `DashboardController` | Routing dinamis berdasarkan role (Admin vs Guest) |
| Laporan | `ReportController` | Laporan pendapatan + paginasi data |
| Autentikasi | `GoogleAuthController` | Login via Google OAuth dengan error handling |
| Notifikasi | `WamifyService` | Pengiriman pesan WhatsApp otomatis |
| Email | 5 Mailable classes | Konfirmasi reservasi, pembayaran, check-in, check-out, pembatalan |

### 4.2 Fase Frontend (UI/UX)

- Desain **Mobile-First** dengan Tailwind CSS dan komponen Blade yang modular.
- **Landing Page** responsif dengan hero section, showcase kamar, dan fitur PWA install banner.
- **Dashboard Tamu**: Menampilkan seluruh reservasi aktif dalam format *ticket card*, QR Code modal, dan riwayat perjalanan.
- **Dashboard Admin**: Statistik real-time (total kamar, kamar tersedia, terisi, total tamu), grafik pendapatan 6 bulan terakhir, dan log aktivitas terkini.
- **QR Code Scanner**: Halaman check-in/out dengan kamera perangkat, auto-submit tanpa modal konfirmasi.

### 4.3 Fase Integrasi

| Integrasi | Mekanisme | Detail |
|---|---|---|
| **Xendit** | Webhook POST → `/api/payment/xendit-callback` | Validasi token, update status PAID/EXPIRED, generate QR, kirim email |
| **Google OAuth** | Socialite + Stateless redirect | Error handling untuk `invalid_grant`, fallback ke halaman login |
| **Wamify** | REST API WhatsApp | Notifikasi check-in/check-out ke tamu |
| **PWA** | Service Worker (`sw.js`) + `manifest.json` | Installable app, Network-First caching |

### 4.4 Alur Bisnis Utama

```
Tamu Daftar/Login → Pilih Kamar → Isi Form Booking → Redirect ke Xendit
    → Bayar → Webhook PAID → Generate QR Code → Email Konfirmasi
    → Tamu Datang → Scan QR (Auto Check-In) → Menginap
    → Scan QR (Auto Check-Out) → Selesai
```

---

## 5. Screenshot Sistem

> *(Instruksi: Ganti path gambar di bawah dengan screenshot asli Anda sebelum laporan ini dicetak atau dipublikasikan)*

**1. Halaman Beranda (Landing Page)**
![Landing Page](public/images/screenshots/landing-page.jpg)
*Menampilkan hero section elegan dengan navigasi responsif dan PWA install banner.*

**2. Dashboard Tamu (Mobile View)**
![Guest Dashboard](public/images/screenshots/guest-dashboard.jpg)
*Menampilkan tiket reservasi aktif, QR Code, dan eksplorasi kamar.*

**3. Halaman Detail Kamar & Rekomendasi**
![Room Detail](public/images/screenshots/room-detail.jpg)
*Informasi kamar lengkap dengan horizontal slider rekomendasi kamar lain.*

**4. Proses Checkout & Integrasi Xendit**
![Checkout Process](public/images/screenshots/checkout.jpg)
*Formulir pemesanan yang terintegrasi langsung dengan E-Wallet dan VA Xendit.*

**5. Dashboard Admin**
![Admin Dashboard](public/images/screenshots/admin-dashboard.jpg)
*Panel statistik, grafik pendapatan, dan log aktivitas untuk staf hotel.*

**6. Halaman Laporan (Paginasi)**
![Report](public/images/screenshots/report.jpg)
*Tabel laporan reservasi dengan paginasi 15 data per halaman.*

**7. QR Code Scanner (Check-In/Check-Out)**
![QR Scanner](public/images/screenshots/qr-scanner.jpg)
*Halaman scanner QR yang langsung memproses check-in/out tanpa konfirmasi manual.*

**8. Invoice PDF**
![Invoice](public/images/screenshots/invoice.jpg)
*Invoice dengan logo, alamat resmi, dan status pembayaran dinamis (LUNAS/UNPAID).*

---

## 6. Quality Assurance (QA)

### 6.1 Ringkasan Strategi QA

Pengujian dilakukan secara berlapis (*multi-layered testing*) untuk memastikan kualitas kode dan stabilitas sistem di lingkungan produksi.

| Lapisan | Tool | Cakupan |
|---|---|---|
| Static Analysis | PHPStan / Larastan (Level 5) | Validasi tipe data, null-safety, logika |
| Code Style | Laravel Pint + PHP-CS-Fixer | Kepatuhan PSR-12 |
| Unit Testing | PHPUnit | Logika unit terisolasi |
| Feature Testing | PHPUnit + RefreshDatabase | HTTP request, database, middleware, auth |
| E2E Testing | Laravel Dusk (Browser) | Alur pengguna lengkap di browser nyata |
| Manual Testing | Browser + cPanel | Verifikasi UI, responsivitas, integrasi Xendit |

### 6.2 Static Code Analysis (PHPStan)

**Tool:** PHPStan / Larastan Level 5
**Perintah:** `./vendor/bin/phpstan analyse --memory-limit=512M`
**Hasil:** ✅ `[OK] No errors`

Temuan yang telah diperbaiki selama pengembangan:
- Peringatan *undefined property* pada `GuestRoomController` — diperbaiki dengan PHPDoc annotation.
- *Nullsafe operator* berlebihan — dieliminasi untuk konsistensi.
- Pemanggilan `env()` langsung di dalam kode — di-refaktor menjadi `config()` sesuai *best practice* Laravel.

### 6.3 Code Style & Formatting

**Tool:** Laravel Pint + PHP-CS-Fixer
**Perintah:** `./vendor/bin/pint` dan `./vendor/bin/php-cs-fixer fix`
**Hasil:** ✅ Seluruh kode mematuhi standar PSR-12

### 6.4 Feature Testing (PHPUnit)

Berikut adalah daftar *test suite* yang telah diimplementasikan:

#### A. Authentication Tests

| Test Class | Test Case | Status |
|---|---|---|
| `AuthenticationTest` | Login screen dapat ditampilkan | ✅ Pass |
| | User dapat login dengan kredensial valid | ✅ Pass |
| | User tidak bisa login dengan password salah | ✅ Pass |
| `RegistrationTest` | Halaman registrasi dapat ditampilkan | ✅ Pass |
| | User baru dapat mendaftar | ✅ Pass |
| `PasswordResetTest` | Halaman reset password dapat ditampilkan | ✅ Pass |
| | Link reset password dapat di-request | ✅ Pass |
| `PasswordUpdateTest` | Password dapat diperbarui | ✅ Pass |
| | Password lama harus benar untuk update | ✅ Pass |
| `PasswordConfirmationTest` | Halaman konfirmasi password dapat ditampilkan | ✅ Pass |
| | Password dapat dikonfirmasi | ✅ Pass |
| `EmailVerificationTest` | Halaman verifikasi email dapat ditampilkan | ✅ Pass |
| | Email dapat diverifikasi | ✅ Pass |

#### B. Google OAuth Tests

| Test Class | Test Case | Status |
|---|---|---|
| `GoogleRedirectTest` | Redirect ke login jika config Google kosong | ✅ Pass |
| | Route legacy `auth.google` tersedia | ✅ Pass |

#### C. Role-Based Access Control (RBAC) Tests

| Test Class | Test Case | Status |
|---|---|---|
| `RoleMiddlewareTest` | Admin dapat mengakses route admin-only | ✅ Pass |
| | Resepsionis **tidak bisa** mengakses route admin-only (403) | ✅ Pass |

#### D. Hotel Foundation Tests

| Test Class | Test Case | Status |
|---|---|---|
| `HotelFoundationTest` | Seluruh model dapat di-*persist* dengan relasi yang benar | ✅ Pass |
| | Soft deletes aktif untuk rooms, guests, dan reservations | ✅ Pass |

#### E. Profile Tests

| Test Class | Test Case | Status |
|---|---|---|
| `ProfileTest` | Halaman profil dapat ditampilkan | ✅ Pass |
| | Informasi profil dapat diperbarui | ✅ Pass |
| | Email verification status di-reset saat email berubah | ✅ Pass |
| | User dapat menghapus akunnya | ✅ Pass |
| | Password harus benar untuk menghapus akun | ✅ Pass |

**Total Feature Tests:** 20+ test cases
**Perintah:** `php artisan test`

### 6.5 End-to-End Testing (Laravel Dusk)

Pengujian browser otomatis menggunakan Laravel Dusk untuk mensimulasikan interaksi pengguna nyata.

| Test Class | Skenario | Status |
|---|---|---|
| `GuestNavigationTest` | Navigasi halaman publik, landing page, kamar | ✅ Pass |
| `AuthFlowTest` | Login, registrasi, logout dari browser | ✅ Pass |
| `BookingFlowTest` | Alur pemesanan lengkap hingga redirect Xendit | ✅ Pass |

**Catatan Teknis:**
- Skrip Dusk dirancang agar tidak menimbulkan konflik *Mixed-Content* pada environment lokal dengan terowongan Cloudflare.
- Booking flow test mengakomodasi redirect ke domain eksternal Xendit (`checkout.xendit.co`).

### 6.6 Security Testing

| Aspek | Implementasi | Status |
|---|---|---|
| **CSRF Protection** | Aktif di seluruh form, dikecualikan hanya untuk Xendit webhook | ✅ |
| **Xendit Webhook Token** | Validasi `x-callback-token` header pada setiap callback | ✅ |
| **Role Middleware** | Custom `EnsureUserHasRole` middleware untuk proteksi route admin | ✅ |
| **Soft Deletes** | Data kritis (rooms, guests, reservations) tidak dihapus permanen | ✅ |
| **Password Hashing** | Bcrypt dengan 12 rounds | ✅ |
| **Google OAuth Stateless** | Mencegah session fixation pada SSO | ✅ |
| **Error Handling** | Try-catch pada Google callback untuk mencegah error 500 | ✅ |
| **Idempotency** | Webhook handler mencegah proses ganda jika Xendit mengirim ulang | ✅ |

### 6.7 Performance & PWA Testing

| Aspek | Detail | Status |
|---|---|---|
| **Service Worker** | Versi v3 dengan strategi Network-First | ✅ |
| **PWA Manifest** | `manifest.json` dengan icon, theme color, display standalone | ✅ |
| **Install Banner** | Auto-detect `beforeinstallprompt` event | ✅ |
| **Cache Busting** | Upgrade versi SW mencegah cache usang | ✅ |
| **Vite Build** | Asset CSS/JS di-bundle dan di-commit ke `public/build` untuk shared hosting | ✅ |

### 6.8 Daftar Bug yang Ditemukan dan Diperbaiki

| # | Bug | Penyebab | Solusi | Commit |
|---|---|---|---|---|
| 1 | Telescope crash di production | Class tidak ditemukan karena `--dev` dependency | Conditional loading di `AppServiceProvider` | `eb880b9` |
| 2 | Seeder gagal saat dijalankan ulang | Duplikasi data unique constraint | Ubah `create` → `updateOrCreate` | `62d5117` |
| 3 | CSS/JS tidak muncul di shared hosting | `public/build` tidak ada di repository | Commit folder build ke git | `529716a` |
| 4 | QR Scan memerlukan klik manual | Modal konfirmasi menghalangi auto-submit | Hapus modal, langsung submit | `b428872` |
| 5 | Google login error 500 (`invalid_grant`) | Exception tidak di-catch saat token expired | Tambah try-catch + redirect ke login | `5fb886a` |
| 6 | Invoice selalu menampilkan "UNPAID" | Status hardcoded, tidak membaca `payment_status` | Buat status dinamis berdasarkan data | `410ce64` |
| 7 | Email konfirmasi dikirim 2x dari webhook | Xendit mengirim webhook berulang | Tambah idempotency check | `01bf608` |
| 8 | Badge "Sedang Menginap" terpotong | CSS `whitespace-wrap` pada layar kecil | Tambah `whitespace-nowrap` + `inline-block` | `d77d817` |
| 9 | Alamat di landing page tidak sesuai | Data placeholder belum diganti | Update ke alamat resmi | `d77d817` |
| 10 | Faker not found saat seeding di production | `fakerphp/faker` adalah dev dependency | Ganti dengan array manual + fungsi PHP native | `1afdd98` |
| 11 | Seeder error `identity_type` column | Kolom tidak ada di tabel `guests` | Hapus kolom dari seeder | `20fef6d` |
| 12 | Laporan tidak ada paginasi | `take(50)` tanpa pagination links | Ubah ke `paginate(15)` + render links | `98368c9` |
| 13 | Dashboard tamu hanya tampil 1 reservasi | `->take(1)` membatasi output | Hapus limit, tampilkan semua aktif | `98368c9` |
| 14 | Foto profil tidak muncul di production | Symlink `public/storage` rusak | Re-create symlink via `storage:link` | Manual fix |

### 6.9 Cara Menjalankan Seluruh QA

```bash
# 1. Static Analysis
./vendor/bin/phpstan analyse --memory-limit=512M

# 2. Code Style
./vendor/bin/pint

# 3. Feature Tests
php artisan test

# 4. E2E Browser Tests (memerlukan ChromeDriver)
php artisan dusk

# 5. Debugging (Development Only)
# Buka: http://localhost:8000/telescope
```

---

## 7. Deployment

### 7.1 Infrastruktur

| Komponen | Detail |
|---|---|
| **Hosting** | cPanel Shared Hosting |
| **Domain** | tibrasare.luhur.my.id |
| **Database** | MySQL (ofbeemoa_tibrasare) |
| **Email** | tibrasare@luhur.my.id (SMTP via mail.luhur.my.id:465 SSL) |
| **Version Control** | Git-based deployment (push → pull) |

### 7.2 Konfigurasi Environment Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tibrasare.luhur.my.id

MAIL_MAILER=smtp
MAIL_HOST=mail.luhur.my.id
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="tibrasare@luhur.my.id"

# Xendit
XENDIT_SECRET_KEY=<secret>
XENDIT_WEBHOOK_TOKEN=<token>
```

### 7.3 Langkah Deploy

```bash
# Di server cPanel:
git pull
php artisan migrate --force
php artisan optimize:clear
php artisan storage:link   # Jika symlink belum ada
```

---

## 8. Kesimpulan

Sistem Informasi Reservasi "Tibra Sare" **telah berhasil diluncurkan ke tahap Produksi** di domain `tibrasare.luhur.my.id`. Sistem ini memadukan:

- **Desain Mobile-First** yang premium dan responsif.
- **Otomasi Backend** lengkap: pembayaran Xendit, QR Code check-in/out, notifikasi email & WhatsApp.
- **Quality Assurance** berlapis: static analysis (PHPStan Level 5), 20+ feature tests, 3 E2E browser test suites, dan security hardening.
- **14 bug** telah ditemukan dan diperbaiki selama siklus pengembangan dan UAT.

Sistem siap digunakan oleh tim operasional hotel untuk mengelola reservasi, pembayaran, dan check-in/out secara efisien.

---

*Dokumen ini terakhir diperbarui pada 23 Juni 2026.*
