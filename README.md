<p align="center">
  <img src="public/images/IconTS.png" width="120" alt="Tibra Sare Logo">
</p>

<h1 align="center">Tibra Sare</h1>
<p align="center"><strong>Hotel & Resort Reservation System</strong></p>
<p align="center">Platform reservasi dan manajemen penginapan berbasis web dengan pendekatan Mobile-First, integrasi Payment Gateway, dan otomasi Check-In/Out via QR Code.</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/MySQL-8-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Xendit-Payment-003CFF?style=for-the-badge" alt="Xendit">
  <img src="https://img.shields.io/badge/PWA-Enabled-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white" alt="PWA">
</p>

<p align="center">
  <a href="https://tibrasare.luhur.my.id">🌐 Live Demo</a> •
  <a href="#-screenshot-aplikasi">📸 Screenshots</a> •
  <a href="#-instalasi">⚙️ Instalasi</a> •
  <a href="#-teknologi">🛠 Teknologi</a>
</p>

---

## 📋 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur Utama](#-fitur-utama)
- [Teknologi](#-teknologi)
- [Arsitektur Sistem](#-arsitektur-sistem)
- [Screenshot Aplikasi](#-screenshot-aplikasi)
- [Instalasi](#-instalasi)
- [Konfigurasi Environment](#-konfigurasi-environment)
- [Penggunaan](#-penggunaan)
- [Testing & QA](#-testing--qa)
- [Deployment](#-deployment)
- [Struktur Proyek](#-struktur-proyek)
- [Tim Pengembang](#-tim-pengembang)
- [Lisensi](#-lisensi)

---

## 🏨 Tentang Proyek

**Tibra Sare** adalah sistem informasi reservasi hotel full-stack yang dirancang dengan pendekatan **Mobile-First** untuk memberikan pengalaman pemesanan yang mulus bagi tamu sekaligus menyediakan panel manajemen yang kaya fitur bagi staf hotel.

Sistem ini mengotomatiskan seluruh siklus hidup reservasi — mulai dari pemesanan kamar, pembayaran online, konfirmasi otomatis, hingga proses check-in dan check-out menggunakan QR Code — sehingga meminimalkan intervensi manual dan meningkatkan efisiensi operasional hotel.

### Permasalahan yang Diselesaikan

| Masalah | Solusi |
|---|---|
| Reservasi manual via telepon/walk-in | Booking online 24/7 dengan payment gateway |
| Konfirmasi pembayaran lambat | Webhook Xendit real-time + notifikasi otomatis |
| Check-in/out memerlukan antrian | Scan QR Code instan tanpa antri |
| Monitoring pendapatan tidak transparan | Dashboard admin dengan grafik pendapatan real-time |
| Informasi tidak sampai ke tamu | Email + WhatsApp notifikasi otomatis |

---

## ✨ Fitur Utama

### 🧑‍💼 Panel Admin (Desktop)

- **Dashboard** — Statistik real-time (kamar tersedia, terisi, total tamu, reservasi aktif) + grafik pendapatan 6 bulan
- **Manajemen Kamar** — CRUD kamar dengan galeri foto, harga, dan status
- **Manajemen Kategori Kamar** — Pengelompokan kamar dengan fasilitas per kategori
- **Manajemen Fasilitas** — CRUD fasilitas hotel (WiFi, AC, TV, dll.)
- **Manajemen Pengguna** — CRUD staf dengan role (Admin / Resepsionis)
- **Reservasi** — Daftar seluruh reservasi + pembuatan reservasi manual
- **Laporan** — Tabel laporan dengan paginasi + export PDF/Excel
- **Activity Log** — Audit trail seluruh tindakan staf
- **Pengaturan** — Konfigurasi dinamis hotel (nama, alamat, kontak)

### 👨‍💻 Panel Resepsionis (Desktop)

- **Dashboard** — Ringkasan operasional harian
- **Check-In** — Scan QR Code tamu → auto check-in
- **Check-Out** — Scan QR Code tamu → auto check-out
- **Reservasi** — Pembuatan reservasi dan pencarian data tamu

### 📱 Portal Tamu (Mobile-First)

- **Landing Page** — Hero section, showcase kamar, info fasilitas, PWA install banner
- **Registrasi & Login** — Akun manual + Google OAuth
- **Eksplorasi Kamar** — Galeri kategori kamar + detail lengkap
- **Booking Online** — Form pemesanan → redirect ke Xendit payment
- **Dashboard Tamu** — Tiket reservasi aktif, QR Code, riwayat perjalanan
- **Notifikasi** — Email konfirmasi + WhatsApp notifikasi (check-in/out)
- **Profil** — Update nama, email, foto profil
- **PWA** — Installable sebagai aplikasi mobile

---

## 🛠 Teknologi

### Core Stack

| Teknologi | Versi | Fungsi |
|---|---|---|
| **Laravel** | 12 | Backend framework (MVC) |
| **Tailwind CSS** | 3 | Utility-first CSS framework |
| **Alpine.js** | 3 | Reactive JavaScript framework |
| **MySQL** | 8 | Database relasional |
| **Vite** | 6 | Frontend build tool |
| **Blade** | — | Template engine |

### Integrasi Pihak Ketiga

| Layanan | Fungsi |
|---|---|
| **Xendit** | Payment Gateway (E-Wallet, VA, QRIS, Kartu Kredit) |
| **Wamify** | WhatsApp Gateway API untuk notifikasi tamu |
| **Google OAuth** | Login dengan akun Google (Socialite) |

### DevOps & QA Tools

| Tool | Fungsi |
|---|---|
| **PHPStan / Larastan** | Static code analysis (Level 5) |
| **Laravel Pint** | Code style formatter (PSR-12) |
| **PHP-CS-Fixer** | Advanced code style rules |
| **PHPUnit** | Unit & Feature testing |
| **Laravel Dusk** | End-to-end browser testing |
| **Laravel Telescope** | Debugging & monitoring (dev) |

---

## 🏗 Arsitektur Sistem

![Arsitektur dan Proses Bisnis](public/SS%20APP/ARSITEKTUR%20DAN%20PROSES%20BISNIS.png)

---

## 📸 Screenshot Aplikasi

### 🖥️ Desktop — Admin & Resepsionis

#### Autentikasi

| Halaman Login | Halaman Registrasi |
|:---:|:---:|
| ![Login](public/SS%20APP/LOGIN.png) | ![Registrasi](public/SS%20APP/REGISTRASI.png) |

#### Dashboard

| Dashboard Admin | Dashboard Resepsionis |
|:---:|:---:|
| ![Dashboard Admin](public/SS%20APP/DASHBOARD%20ADMIN.png) | ![Dashboard Resepsionis](public/SS%20APP/DASHBOARD%20RESEPSIONIS.png) |
| *Statistik kamar, grafik pendapatan, log aktivitas* | *Ringkasan operasional harian* |

#### Manajemen Data

| Manajemen Kamar | Kategori Kamar | Fasilitas |
|:---:|:---:|:---:|
| ![Kamar](public/SS%20APP/MANAGEMEN%20KAMAR.png) | ![Kategori](public/SS%20APP/MANAGEMENT%20KATEGORI%20KAMAR.png) | ![Fasilitas](public/SS%20APP/MANAGEMENT%20FASILITAS.png) |

#### Operasional

| Reservasi | Laporan Pendapatan |
|:---:|:---:|
| ![Reservasi](public/SS%20APP/RESERVASI.png) | ![Laporan](public/SS%20APP/LAPORAN.png) |

| QR Scanner Check-In/Out | Halaman Check-In |
|:---:|:---:|
| ![QR Scanner](public/SS%20APP/SCAN%20QR%20CODE%20CEK%20%20IN-OUT.png) | ![Check-In](public/SS%20APP/CEK%20IN.png) |

| Activity Log | Manajemen Pengguna |
|:---:|:---:|
| ![Activity Log](public/SS%20APP/ACTIVITY%20LOG.png) | ![Pengguna](public/SS%20APP/PENGGUNA.png) |

| Pengaturan | Menu Navigasi |
|:---:|:---:|
| ![Pengaturan](public/SS%20APP/PENGATURAN%20ADMIN.png) | ![Menu](public/SS%20APP/MENU%20TAMU.png) |

---

### 📱 Mobile — Portal Tamu

#### Landing Page

| Desktop | Mobile |
|:---:|:---:|
| ![Landing Desktop](public/SS%20APP/LANDING%20PAGE.png) | ![Landing Mobile](public/SS%20APP/LANDING%20PAGE%20MOBILE%20TAMU.png) |

#### Eksplorasi & Detail Kamar

| Daftar Kamar | Detail Kamar |
|:---:|:---:|
| ![Kamar](public/SS%20APP/KAMAR%20TAMU.png) | ![Detail](public/SS%20APP/DETAIL%20KAMAR%20TAMU.png) |

#### Booking & Pembayaran

| Form Pemesanan | Payment Gateway | Pembayaran Berhasil |
|:---:|:---:|:---:|
| ![Pesan](public/SS%20APP/PESAN%20KAMAR%20TAMU.png) | ![Payment](public/SS%20APP/PAYMENT%20GATEWAY%20TAMU.png) | ![Berhasil](public/SS%20APP/PEMBAYARAN%20BERHASIL%20TAMU.png) |

#### Dashboard & Pengaturan Tamu

| Dashboard Tamu | Pengaturan Profil |
|:---:|:---:|
| ![Dashboard](public/SS%20APP/DASHBOARD%20TAMU.png) | ![Pengaturan](public/SS%20APP/PENGATURAN%20TAMU.png) |

#### Notifikasi

| Email Konfirmasi | WhatsApp Gateway |
|:---:|:---:|
| ![Email](public/SS%20APP/EMAIL%20KONFIRMASI.jpeg) | ![WhatsApp](public/SS%20APP/WHATSAPP%20GATEWAY.jpeg) |

---

## ⚙️ Instalasi

### Prasyarat

- PHP >= 8.4
- Composer >= 2.x
- Node.js >= 20.x
- MySQL >= 8.0
- Git

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/BayuAjiPrayoga/TibraSare.git
cd TibraSare

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di file .env (lihat bagian di bawah)

# 7. Jalankan migrasi database
php artisan migrate

# 8. Jalankan seeder data awal
php artisan db:seed

# 9. Buat symlink storage
php artisan storage:link

# 10. Build frontend assets
npm run build

# 11. Jalankan development server
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

### Akun Default

| Role | Email | Password |
|---|---|---|
| Admin | `admin@tibrasare.test` | `password` |
| Resepsionis | `receptionist@tibrasare.test` | `password` |

---

## 🔧 Konfigurasi Environment

Salin `.env.example` ke `.env` dan sesuaikan nilai berikut:

```env
# Aplikasi
APP_NAME="Tibra Sare"
APP_URL=http://localhost:8000

# Database
DB_DATABASE=tibra_sare
DB_USERNAME=root
DB_PASSWORD=

# Email (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=mail.luhur.my.id
MAIL_PORT=465
MAIL_USERNAME="tibrasare@luhur.my.id"
MAIL_PASSWORD="your-email-password"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="tibrasare@luhur.my.id"
MAIL_FROM_NAME="${APP_NAME}"

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# Xendit Payment Gateway
XENDIT_SECRET_KEY=your-xendit-secret-key
XENDIT_WEBHOOK_TOKEN=your-xendit-webhook-token

# WhatsApp Gateway (Wamify)
WAMIFY_SESSION_ID=your-wamify-session-id

# WiFi Kamar
WIFI_SSID="Tibra Sare Guest"
WIFI_PASSWORD="Tibrasare123"
```

---

## 🚀 Penggunaan

### Development

```bash
# Jalankan server + frontend watcher secara bersamaan
php artisan serve &
npm run dev
```

### Dummy Data (Opsional)

```bash
# Generate 1 tahun data dummy reservasi (ratusan record)
php artisan db:seed --class=DummyData1YearSeeder
```

### Xendit Webhook

Setelah mendapatkan akun Xendit, atur Webhook URL di dashboard Xendit:

```
URL: https://your-domain.com/api/payment/xendit-callback
```

---

## 🧪 Testing & QA

### Static Analysis

```bash
# PHPStan Level 5
./vendor/bin/phpstan analyse --memory-limit=512M
```

### Code Style

```bash
# Laravel Pint (PSR-12)
./vendor/bin/pint
```

### Feature Tests

```bash
# Jalankan seluruh test suite
php artisan test
```

**Test Suites yang tersedia:**

| Test Suite | Jumlah | Cakupan |
|---|---|---|
| Authentication Tests | 12 | Login, register, password reset, email verification |
| Google OAuth Tests | 2 | Redirect, config validation |
| RBAC Middleware Tests | 2 | Admin-only access control |
| Hotel Foundation Tests | 2 | Model relationships, soft deletes |
| Profile Tests | 5 | CRUD profil, delete account |

### E2E Browser Tests

```bash
# Memerlukan ChromeDriver
php artisan dusk
```

| Test Suite | Skenario |
|---|---|
| `GuestNavigationTest` | Navigasi halaman publik |
| `AuthFlowTest` | Login & registrasi via browser |
| `BookingFlowTest` | Alur booking lengkap hingga Xendit |

### Debugging (Development)

```bash
# Akses Laravel Telescope
http://localhost:8000/telescope
```

---

## 🌐 Deployment

### cPanel Shared Hosting

```bash
# Di server cPanel:
git pull
php artisan migrate --force
php artisan optimize:clear
php artisan storage:link
```

> 📖 Panduan lengkap tersedia di [Panduan_Deploy.md](Panduan_Deploy.md)

---

## 📁 Struktur Proyek

```
tibra-sare/
├── app/
│   ├── Enums/                  # ReservationStatus, RoomStatus, UserRole
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/           # Google OAuth, Login, Register
│   │   │   ├── BookingController.php
│   │   │   ├── CheckInController.php
│   │   │   ├── CheckOutController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── PaymentCallbackController.php
│   │   │   ├── ReportController.php
│   │   │   └── ...
│   │   └── Middleware/         # EnsureUserHasRole
│   ├── Mail/                   # 5 Mailable classes
│   ├── Models/                 # 9 Eloquent models
│   └── Services/               # XenditService, WamifyService, GoogleAuthService
├── database/
│   ├── migrations/             # 11 migration files
│   └── seeders/                # DatabaseSeeder, SundaTibraSareSeeder, DummyData1YearSeeder
├── resources/views/
│   ├── components/             # Blade UI components
│   ├── dashboard/              # Admin dashboard
│   ├── guest/                  # Guest dashboard
│   ├── public/                 # Landing page
│   ├── pdf/                    # Invoice template
│   └── ...
├── public/
│   ├── images/                 # Logo, hero, assets
│   ├── build/                  # Compiled Vite assets
│   ├── manifest.json           # PWA manifest
│   └── sw.js                   # Service Worker
├── tests/
│   ├── Feature/                # PHPUnit feature tests
│   ├── Unit/                   # PHPUnit unit tests
│   └── Browser/                # Laravel Dusk E2E tests
├── Laporan_Project.md          # Laporan proyek lengkap
├── QUALITY_ASSURANCE.md        # Panduan QA
├── Panduan_Deploy.md           # Panduan deployment
└── README.md                   # Dokumentasi ini
```

---

## 👥 Tim Pengembang

| Nama | Role |
|---|---|
| **Bayu Aji Prayoga** | Full-Stack Developer |

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademik dan operasional Hotel Tibra Sare.

---

<p align="center">
  <sub>Dibangun dengan penuh rasa syukur dan bangga menggunakan Laravel 12 • Tailwind CSS • Alpine.js</sub>
</p>
