# Installation Documentation

Panduan lengkap instalasi dan menjalankan proyek Omnexus di lingkungan lokal.

---

## 1. Persyaratan Sistem

| Software | Versi Minimum | Keterangan |
| --- | --- | --- |
| PHP | 8.2+ | |
| Composer | Terbaru | Package manager PHP |
| Node.js | 18+ | |
| MySQL | 5.7+ | |
| Git | Terbaru | |

Rekomendasi untuk Windows: gunakan [Laragon](https://laragon.org/) yang sudah menyertakan PHP, MySQL, dan server lokal tanpa konfigurasi tambahan.

---

## 2. Langkah Instalasi

### 1. Clone Repository

```bash
git clone <url-repository> Omnexus
cd Omnexus
```

### 2. Install Dependency

```bash
composer install
npm install
```

### 3. Setup Environment

Salin file `.env.example` menjadi `.env`:

```bash
# Linux / macOS
cp .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Setup Database

Buat database MySQL baru:

```sql
CREATE DATABASE Omnexus;
```

Sesuaikan konfigurasi di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Omnexus
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Setup Google OAuth

Buka [Google Cloud Console](https://console.cloud.google.com), buat project baru, aktifkan Google+ API, lalu buat credentials OAuth 2.0 (tipe: Web application).

Tambahkan Authorized Redirect URI:

```
http://127.0.0.1:8000/auth/google/callback
```

Isi nilai credentials ke file `.env`:

```env
GOOGLE_CLIENT_ID=client_id_anda.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=client_secret_anda
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

### 7. Jalankan Migration dan Setup Storage

```bash
php artisan migrate
php artisan storage:link
php artisan config:clear
```

Perintah `storage:link` diperlukan agar foto profil dan bukti pembayaran yang diupload bisa diakses secara publik.

---

## 3. Menjalankan Aplikasi

Buka dua terminal secara bersamaan:

**Terminal 1 — Laravel Server:**

```bash
php artisan serve
```

**Terminal 2 — Vite Dev Server:**

```bash
npm run dev
```

Aplikasi dapat diakses di `http://127.0.0.1:8000`.

Alternatif, jalankan semua sekaligus dengan satu perintah:

```bash
composer run dev
```

Perintah ini menggunakan `concurrently` untuk menjalankan Laravel server, Vite, queue listener, dan log viewer dalam satu terminal.

### Build untuk Production

```bash
npm run build
```

---

## 4. Environment Variables Penting

| Variable | Nilai Default | Keterangan |
| --- | --- | --- |
| `APP_NAME` | `Omnexus` | Nama aplikasi |
| `APP_ENV` | `local` | Environment (`local` atau `production`) |
| `APP_URL` | `http://127.0.0.1:8000` | URL dasar aplikasi |
| `DB_DATABASE` | `Omnexus` | Nama database MySQL |
| `DB_USERNAME` | `root` | Username database |
| `DB_PASSWORD` | *(kosong)* | Password database |
| `GOOGLE_CLIENT_ID` | — | Wajib diisi — dari Google Cloud Console |
| `GOOGLE_CLIENT_SECRET` | — | Wajib diisi — dari Google Cloud Console |
| `GOOGLE_REDIRECT_URI` | — | URL callback Google OAuth |
| `SESSION_DRIVER` | `cookie` | Driver session (`cookie` atau `database`) |

---

## 5. Troubleshooting

**Error "404 Not Found" pada Google Callback**

Jalankan `php artisan route:clear`, pastikan `APP_URL` di `.env` sesuai dengan URL yang digunakan, lalu restart server.

**Error "InvalidStateException" dari Google**

Jalankan `php artisan cache:clear && php artisan config:clear`. Pastikan cookies diterima di browser dan jangan gunakan mode incognito saat testing.

**Foto Profil atau Bukti Pembayaran Tidak Muncul**

Pastikan perintah `php artisan storage:link` sudah berhasil dijalankan, lalu restart server dan coba upload ulang.

**Database Error / Tabel Tidak Ditemukan**

Pastikan MySQL sudah berjalan, konfigurasi DB di `.env` sudah benar, lalu jalankan `php artisan migrate`.

**Halaman Putih / Error 500**

Jalankan:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```
