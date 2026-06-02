# Installation Documentation

## 1. Persyaratan Sistem

- **PHP** >= 8.2
- **Composer** (versi terbaru disarankan)
- **Node.js** >= 18
- **MySQL** >= 5.7
- **Git**
- *Optional (untuk Windows)*: Laragon sebagai Local Development Environment

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
cp .env.example .env
```

### 4. Setup Database
Buat database MySQL baru dengan nama, misalnya `PBL`, lalu sesuaikan konfigurasi di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PBL
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Setup Google OAuth (Wajib jika fitur Google Login digunakan)
Buka Google Cloud Console, buat kredensial OAuth 2.0 untuk aplikasi Web, lalu tambahkan Redirect URI: `http://localhost:8000/auth/google/callback`.
Isi nilai tersebut di `.env`:
```env
GOOGLE_CLIENT_ID=client_id_anda
GOOGLE_CLIENT_SECRET=client_secret_anda
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 6. Generate Key & Migrasi Database
Jalankan perintah berikut untuk menginisialisasi sistem:
```bash
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan config:clear
```
*(Catatan: `storage:link` diperlukan agar foto profil bisa diakses publik)*

### 7. Menjalankan Aplikasi
Buka dua terminal secara bersamaan:

**Terminal 1 (Backend - Laravel):**
```bash
php artisan serve
```

**Terminal 2 (Frontend - Vite):**
```bash
npm run dev
```

Aplikasi dapat diakses melalui browser pada `http://localhost:8000`.

## 3. Troubleshooting

- **Error: "404 Not Found" pada Google Callback:**
  Jalankan `php artisan route:clear`, pastikan `APP_URL` di `.env` sudah benar (`http://localhost:8000`), lalu restart server.
  
- **Error: "InvalidStateException" dari Google:**
  Jalankan `php artisan cache:clear && php artisan config:clear`, dan pastikan Anda menerima cookies di browser.

- **Foto Profil tidak Muncul:**
  Pastikan perintah `php artisan storage:link` sudah berhasil dijalankan, dan coba restart server `serve`.
