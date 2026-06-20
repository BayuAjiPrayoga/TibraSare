# CONTEXT — Tibra Sare

> File ini di-paste ke awal setiap sesi coding baru bersama AI.
> Update setiap selesai satu phase atau ada perubahan signifikan.

---

# Project Summary

* **Nama**: Tibra Sare
* **Stack**: Laravel 12 + Laravel Blade + Tailwind CSS + Alpine.js + MySQL
* **Tujuan**:
  Mempermudah pengelolaan reservasi, kamar, dan tamu hotel melalui aplikasi web modern berbasis PWA yang responsif, dirancang khusus untuk deployment di shared hosting tanpa node.js runtime.

---

# Status Saat Ini

* **Phase aktif**: Phase 3 — Fitur Tambahan (UAS Requirements)

* **Sudah selesai**:

  * [x] Migrasi keseluruhan UI dari React/Inertia ke Laravel Blade murni
  * [x] Setup Tailwind CSS & Alpine.js
  * [x] Setup Authentication (Blade) & Role System
  * [x] Setup Google OAuth backend
  * [x] CRUD Master Data (Kamar, Kategori Kamar, Fasilitas, User, Tamu)
  * [x] Reservasi Flow (Public & Admin)
  * [x] Fitur Check-In & Check-Out
  * [x] QR Code Generator (Guest Ticket) & QR Scanner (Admin)
  * [x] Export Laporan PDF & Excel (via Vite client-side generation)
  * [x] Dashboard Analytics

* **Sedang dikerjakan**:

  * [ ] Perencanaan implementasi fitur tambahan (UAS requirements)
  * [ ] PWA Configuration

* **Belum dimulai**:

  * [ ] Upload Multiple Images (Room Gallery)
  * [ ] Activity Log / Audit Trail Enhancements
  * [ ] Setup real Email Notification (SMTP Configuration)
  * [ ] Deployment ke Shared Hosting

---

# Struktur Folder

```text
tibra-sare/
├── app/
│   ├── Models/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Requests/
│   │   └── Middleware/
│   ├── Helpers/
│   └── Policies/
│
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
│
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── components/ (Blade Components)
│   │   ├── layouts/
│   │   └── ... (Blade Views)
│   ├── js/ (Vite Entries for specific features e.g. reports.js)
│   └── css/
│
├── routes/
│   ├── web.php
│   └── auth.php
│
├── public/
│   ├── build/
│   ├── icons/
│   └── manifest.json
│
├── PRD.md
└── CONTEXT.md
```

---

# Keputusan Teknis Penting

* Menggunakan Laravel 12
* Menggunakan **Laravel Blade** murni (Migrasi dari React telah selesai)
* Menggunakan Tailwind CSS untuk styling
* Menggunakan Alpine.js untuk interaktivitas browser-side (dropdown, modal, toggle)
* Blade Components digunakan untuk modularitas UI (e.g. `<x-ui.button>`)
* Login Google menggunakan Laravel Socialite
* Export Excel & PDF menggunakan Vite compilation client-side untuk kompatibilitas shared hosting
* QR Scanner menggunakan html5-qrcode
* Tidak membutuhkan server Node.js di production (Zero Build Process Deployment)
* Semua fitur harus dapat berjalan mulus pada shared hosting standard.

---

# Konvensi Kode

## Backend

* Menggunakan Controller → Service → Model pattern jika kompleks
* Menggunakan Form Request Validation
* Semua Model wajib menggunakan fillable
* Custom Helper Functions berada di `app/Helpers/helpers.php`

### Naming Convention

Database: `snake_case`
Model / Controller: `PascalCase`
Migration: `snake_case`
Route Name: `dot.notation` (e.g. `reservations.index`)

---

## Frontend (Blade + Alpine)

* Ekstraksi UI berulang ke dalam `resources/views/components/`
* Penggunaan tag `<x-...>` untuk pemanggilan komponen
* Pengelolaan state client-side menggunakan `x-data` dari Alpine.js
* Hindari vanilla JS DOM manipulation (`document.getElementById`), gunakan fitur `$refs` atau `x-bind` Alpine.js jika memungkinkan.

---

# Instruksi untuk AI

* Selalu gunakan stack yang sudah ditentukan (Blade + Alpine + Tailwind)
* **JANGAN** pernah menawarkan solusi menggunakan React, Inertia, Vue, atau Livewire.
* **JANGAN** menyarankan package yang membutuhkan konfigurasi server khusus (seperti supervisor, queue worker daemon, atau websockets) karena target adalah shared hosting.
* Gunakan Blade Components untuk menjaga UI tetap DRY.
* Tanyakan terlebih dahulu jika ada kebutuhan yang ambigu.
* Prioritaskan maintainability dan kemudahan deployment.
