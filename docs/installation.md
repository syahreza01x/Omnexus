# Installation Documentation

## 1. Persyaratan Sistem

| Software | Versi Minimum | Catatan |
| :--- | :--- | :--- |
| **PHP** | >= 8.2 | Aktifkan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `fileinfo`, `bcmath` |
| **Composer** | >= 2.x | [getcomposer.org](https://getcomposer.org) |
| **Node.js** | >= 18 | [nodejs.org](https://nodejs.org) |
| **MySQL** | >= 5.7 | Bisa pakai Laragon, XAMPP, atau MySQL langsung |
| **Git** | Terbaru | [git-scm.com](https://git-scm.com) |

> **Rekomendasi untuk Windows:** Gunakan **Laragon** karena sudah menyertakan PHP, MySQL, dan Composer secara terpadu.

---

## 2. Langkah Instalasi

### Langkah 1 — Clone Repository
```bash
git clone <url-repository> Omnexus
cd Omnexus
```

### Langkah 2 — Install Dependency
```bash
composer install
npm install
```

### Langkah 3 — Setup File Environment
Salin file `.env.example` menjadi `.env`:
```bash
# Linux / Mac
cp .env.example .env

# Windows (Command Prompt)
copy .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env
```

### Langkah 4 — Setup Database
Buat database MySQL baru, lalu sesuaikan konfigurasi di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PBL
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 5 — Isi API Keys (Wajib)
Edit file `.env` dan isi nilai berikut:

**a. Google OAuth** (untuk fitur Login dengan Google)
- Buka [Google Cloud Console](https://console.cloud.google.com)
- Buat/minta kredensial OAuth 2.0 dari ketua tim
- Tambahkan Redirect URI: `http://127.0.0.1:8000/auth/google/callback`
```env
GOOGLE_CLIENT_ID=isi_disini
GOOGLE_CLIENT_SECRET=isi_disini
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

**b. Gemini API Key** (untuk fitur Chatbot AI)
- Buka [Google AI Studio](https://aistudio.google.com/app/apikey)
- Buat API Key baru (gratis)
```env
GEMINI_API_KEY=isi_disini
```

### Langkah 6 — Generate Key & Jalankan Migrasi
```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan config:clear
```

> **Catatan:** `--seed` akan mengisi data awal (FAQ, dll). `storage:link` diperlukan agar foto profil bisa diakses.

### Langkah 7 — Jalankan Aplikasi
Buka **dua terminal** secara bersamaan:

**Terminal 1 — Backend (Laravel):**
```bash
php artisan serve
```

**Terminal 2 — Frontend (Vite / CSS):**
```bash
npm run dev
```

Akses aplikasi di browser: **http://127.0.0.1:8000**

---

## 3. Membuat Akun Super Admin

Setelah migrasi selesai, buat akun admin melalui Tinker:
```bash
php artisan tinker
```
Lalu jalankan:
```php
\App\Models\User::create([
    'name'     => 'superadmin',
    'email'    => 'admin@interco.com',
    'password' => bcrypt('password_anda'),
    'role'     => 'super_admin',
]);
```

---

## 4. Troubleshooting

**Error: `Session::has` atau error setelah `SESSION_ENCRYPT=true`**
> Sesi lama tidak kompatibel setelah enkripsi diaktifkan. Hapus file sesi lama:
```bash
php artisan cache:clear
php artisan config:clear
# Hapus file di storage/framework/sessions/ secara manual jika perlu
```

**Error: "404 Not Found" pada Google Callback**
> Jalankan `php artisan route:clear`, pastikan `APP_URL` di `.env` sudah benar (`http://127.0.0.1:8000`), lalu restart server.

**Error: "InvalidStateException" dari Google**
> Jalankan `php artisan cache:clear` dan `php artisan config:clear`. Pastikan cookies diizinkan di browser Anda.

**Foto Profil tidak Muncul**
> Pastikan perintah `php artisan storage:link` sudah berhasil. Coba restart server jika perlu.

**Error: "Class not found" setelah pull**
> Jalankan `composer dump-autoload` untuk memperbarui autoloader setelah ada file PHP baru.

**Tampilan CSS tidak berubah setelah pull**
> Pastikan `npm run dev` sedang berjalan. Jika perlu, jalankan `npm run build` untuk build ulang aset.

---

## 5. Catatan untuk Anggota Tim

- **Jangan commit file `.env`** — file ini sudah ada di `.gitignore` dan berisi rahasia pribadi
- Setelah setiap `git pull`, selalu cek apakah ada migrasi baru dengan: `php artisan migrate`
- Jika ada perubahan `composer.json`, jalankan: `composer install`
- Jika ada perubahan `package.json`, jalankan: `npm install`
