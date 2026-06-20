# PRD — Tibra Sare Hotel

> Versi: 2.1 | Tanggal: 18/06/2026 | Status: Active

---

## 1. Overview

- **Product**: Tibra Sare Hotel
- **Tagline**: Smart Hotel Reservation & Guest Experience Platform
- **Target user**:
  - Admin Hotel
  - Resepsionis
  - Manajer Hotel
  - Tamu Hotel
- **Problem**: Banyak hotel dan penginapan masih menggunakan pencatatan reservasi manual atau sistem yang tidak terintegrasi sehingga menyebabkan kesalahan booking, kesulitan monitoring kamar, serta proses check-in dan check-out yang kurang efisien.
- **Value prop**: Mempermudah pengelolaan reservasi, kamar, dan tamu hotel melalui aplikasi web modern berbasis PWA yang responsif dan dapat digunakan layaknya aplikasi mobile.

---

## 2. Tech Stack

- **Backend**: Laravel 12
- **Admin Panel**: Custom Dashboard
- **Frontend**: Laravel Blade + Tailwind CSS + Alpine.js
- **Database**: MySQL
- **Auth**: Laravel Breeze (Blade) & Google OAuth Login
- **Payment**: Tidak ada (sesuai kebutuhan UAS)
- **Notifikasi**: Email Notification (Laravel Mail)
- **Storage**: Local Storage
- **PWA**: Laravel PWA (Installable Mobile App)
- **Charts**: ApexCharts (via CDN/Vite)
- **QR Code**: qrcode.js / html5-qrcode (via CDN/Vite)
- **Export**: jsPDF & SheetJS (via Vite)
- **Hosting**: Shared Hosting
- **Catatan constraint**:
  - Harus dapat berjalan di shared hosting.
  - Zero additional runtime dependencies (no Node.js server required for production).
  - Seluruh fitur dapat berjalan tanpa VPS.

---

## 3. Features

> ★ = MVP (wajib ada di Phase 1–2) | ☆ = Nice-to-have (Phase 3+)

### Authentication

- ★ User dapat login menggunakan email dan password
- ★ User dapat login menggunakan akun Google
- ★ User dapat logout
- ★ Admin dapat mengelola user
- ★ Admin dapat mengatur role user

### Manajemen Kamar

- ★ Admin dapat membuat kategori kamar
- ★ Admin dapat mengelola data kamar
- ★ Admin dapat mengatur harga kamar
- ★ Admin dapat mengubah status kamar
- ☆ Admin dapat mengunggah beberapa foto kamar (Multiple Images)

### Manajemen Tamu

- ★ Petugas dapat menambah data tamu
- ★ Petugas dapat mengubah data tamu
- ★ Petugas dapat mencari data tamu
- ★ Petugas dapat melihat riwayat kunjungan tamu

### Reservasi

- ★ Petugas dapat membuat reservasi
- ★ Petugas dapat memilih kamar yang tersedia
- ★ Sistem menghasilkan kode booking otomatis
- ★ Sistem menghitung lama menginap
- ★ Sistem menghitung total biaya menginap
- ☆ Sistem menghasilkan QR Code reservasi

### Check-In & Check-Out

- ★ Petugas dapat melakukan check-in
- ★ Petugas dapat melakukan check-out
- ★ Sistem mengubah status kamar otomatis
- ★ Sistem menyimpan histori tamu
- ☆ Scan QR Code untuk check-in / check-out

### Email Notification

- ★ Sistem mengirim email konfirmasi reservasi
- ★ Sistem mengirim email check-in berhasil
- ★ Sistem mengirim email check-out berhasil

### Dashboard

- ★ Melihat total kamar
- ★ Melihat kamar tersedia
- ★ Melihat kamar terisi
- ★ Melihat total tamu
- ★ Melihat total reservasi
- ★ Melihat grafik reservasi bulanan
- ★ Melihat grafik okupansi kamar

### Laporan

- ★ Melihat laporan reservasi
- ★ Melihat laporan check-in
- ★ Melihat laporan check-out
- ★ Export PDF
- ★ Export Excel

### Activity Log & Audit Trail

- ★ Sistem mencatat aktivitas pengguna (Activity Log)
- ★ Admin dapat melihat histori aktivitas
- ☆ Audit Trail komprehensif untuk data-data kritikal

### Progressive Web App (PWA)

- ★ Aplikasi dapat di-install di Android/iOS/Desktop
- ★ Mobile-first UI
- ★ Responsive Layout
- ★ Splash Screen
- ★ App Icon
- ☆ Offline cache halaman statis

---

## 4. Data Model

> Hanya kolom kunci — tidak perlu semua kolom, cukup yang penting untuk relasi dan logika bisnis.

| Table | Kolom Kunci | Relasi |
|-------|-------------|--------|
| users | id, name, email, role, google_id | hasMany → reservations |
| room_categories | id, name | hasMany → rooms |
| rooms | id, room_number, room_category_id, price, status | belongsTo → room_categories, hasMany → room_images |
| room_images | id, room_id, image_path | belongsTo → rooms |
| guests | id, identity_number, full_name, phone, email | hasMany → reservations |
| reservations | id, booking_code, guest_id, room_id, total_price, check_in_date, check_out_date, status | belongsTo → guests, belongsTo → rooms |
| activity_logs | id, user_id, action, description, properties | belongsTo → users |
| facilities | id, name | belongsToMany → rooms |
| facility_room | room_id, facility_id | pivot |

**Catatan khusus (Relasi & Soft Delete):**

- **One To Many**:
  - Room Category → Rooms
  - Rooms → Room Images
  - Guest → Reservations
  - User → Activity Logs
- **Many To Many**:
  - Rooms ↔ Facilities (pivot table: facility_room)
- **Soft Delete**:
  - rooms
  - guests
  - reservations

---

## 5. Phases

**Phase 1 — Foundation (Blade Migration)**

- Setup Laravel 12
- Migrasi dari React/Inertia ke Laravel Blade murni
- Setup Tailwind CSS & Alpine.js
- Setup Authentication (Breeze Blade)
- Setup Google Login
- Migration Database & Seeder

**Phase 2 — Core MVP**

- CRUD User & Master Data (Kategori Kamar, Kamar, Fasilitas)
- CRUD Tamu
- CRUD Reservasi
- Check-In & Check-Out dengan QR Scanner
- Dashboard & Laporan (PDF/Excel)

**Phase 3 — Fitur Tambahan (UAS Requirements)**

- Upload Multiple Images (Room Gallery)
- Activity Log & Audit Trail Enhancement
- Email Notification Configuration
- PWA Finalization

**Phase 4 — Deployment & Optimization**

- Shared Hosting Deployment Prep
- Zero Build Process Verification
- Final Testing

---

## Catatan Tambahan

- Desain menggunakan pendekatan Mobile First.
- Tampilan menyerupai aplikasi Android modern.
- Menggunakan Tailwind CSS dan Blade Components.
- Frontend logic menggunakan Alpine.js.
- Login Google, QR Code, Multiple Images, Activity Log, dan PWA digunakan sebagai nilai tambah UAS.
- Target akhir adalah sistem yang terlihat seperti produk startup hotel management modern, berjalan lancar di shared hosting.
