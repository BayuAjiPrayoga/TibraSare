# Tutor Google OAuth - Tibra Sare

Panduan ini dipakai untuk konfigurasi manual Google Login. Bagian kode backend sudah tersedia melalui Laravel Socialite:

- Route redirect: `/auth/google/redirect`
- Route callback: `/auth/google/callback`
- Controller: `App\Http\Controllers\Auth\GoogleAuthController`
- Service: `App\Services\Auth\GoogleAuthService`
- Kolom user: `google_id`

## 1. Siapkan URL Aplikasi

Untuk local development:

```env
APP_URL=http://localhost:8000
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

Jika memakai domain hosting:

```env
APP_URL=https://domain-anda.com
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

Setelah mengubah `.env`, jalankan:

```bash
php artisan config:clear
```

## 2. Buat OAuth Client di Google Cloud Console

1. Buka Google Cloud Console.
2. Buat project baru atau pilih project yang sudah ada.
3. Masuk ke menu **APIs & Services**.
4. Buka **OAuth consent screen**.
5. Pilih user type sesuai kebutuhan:
   - **External** untuk akun Gmail umum.
   - **Internal** hanya jika memakai Google Workspace organisasi.
6. Isi nama aplikasi, email support, dan developer contact.
7. Tambahkan scope dasar:
   - `openid`
   - `email`
   - `profile`
8. Simpan consent screen.

## 3. Buat Credential OAuth

1. Masuk ke **APIs & Services > Credentials**.
2. Klik **Create Credentials**.
3. Pilih **OAuth client ID**.
4. Pilih application type **Web application**.
5. Isi nama, contoh: `Tibra Sare Local`.
6. Tambahkan **Authorized JavaScript origins**:

```text
http://localhost:8000
```

Untuk hosting:

```text
https://domain-anda.com
```

7. Tambahkan **Authorized redirect URIs**:

```text
http://localhost:8000/auth/google/callback
```

Untuk hosting:

```text
https://domain-anda.com/auth/google/callback
```

8. Klik **Create**.
9. Salin **Client ID** dan **Client Secret**.

## 4. Isi `.env`

```env
GOOGLE_CLIENT_ID=isi_client_id_dari_google
GOOGLE_CLIENT_SECRET=isi_client_secret_dari_google
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

Lalu jalankan:

```bash
php artisan config:clear
php artisan route:clear
```

## 5. Jalankan Aplikasi Lokal

```bash
php artisan serve
npm run dev
```

Buka:

```text
http://localhost:8000/auth/google/redirect
```

Jika berhasil, browser akan diarahkan ke halaman login Google, lalu kembali ke:

```text
http://localhost:8000/dashboard
```

## 6. Verifikasi Database

Cek apakah user Google masuk ke database:

```bash
mysql -h 127.0.0.1 -P 3306 -u root -D tibra_sare -e "SELECT id, name, email, role, google_id FROM users;"
```

User baru dari Google akan otomatis mendapat role:

```text
receptionist
```

Jika email sudah ada, sistem akan menautkan akun tersebut ke `google_id` tanpa mengganti role yang sudah ada.

## 7. Troubleshooting

Jika muncul:

```text
Missing required parameter: client_id
Error 400: invalid_request
```

Artinya Laravel mengirim request Google tanpa `GOOGLE_CLIENT_ID`. Perbaiki `.env`:

```env
APP_URL=http://localhost:8000
GOOGLE_CLIENT_ID=isi_client_id_dari_google
GOOGLE_CLIENT_SECRET=isi_client_secret_dari_google
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

Lalu bersihkan cache:

```bash
php artisan optimize:clear
```

Cek apakah Laravel sudah membaca config:

```bash
php artisan tinker --execute="dump(config('services.google.client_id')); dump(config('services.google.redirect'));"
```

Output pertama tidak boleh kosong. Output redirect untuk local harus:

```text
http://localhost:8000/auth/google/callback
```

Jika muncul `redirect_uri_mismatch`, pastikan value di Google Cloud Console sama persis dengan `.env`:

```text
http://localhost:8000/auth/google/callback
```

Perbedaan `http` vs `https`, port, slash akhir, atau domain akan dianggap berbeda oleh Google.

Jika muncul `invalid_client`, periksa:

- `GOOGLE_CLIENT_ID`
- `GOOGLE_CLIENT_SECRET`
- `php artisan config:clear` sudah dijalankan

Jika login berhasil tetapi kembali ke login page, periksa:

- `SESSION_DRIVER=database`
- Tabel `sessions` sudah ada
- `APP_KEY` sudah dibuat dengan `php artisan key:generate`

## 8. Batas Automation Test

Automated test bisa memverifikasi route, service, database, role, dan linking user Google dengan mock Socialite user.

Yang tetap harus manual:

- Pembuatan OAuth Client di Google Cloud Console.
- Persetujuan OAuth consent screen.
- Tes redirect browser dari Google sungguhan.
- Validasi domain production dan HTTPS.
