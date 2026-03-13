# Omnexus - Platform E-Commerce Custom Clothing

Platform e-commerce untuk custom clothing dengan sistem autentikasi modern yang mendukung Google OAuth 2.0, profile management, dan dark mode.

## Fitur Utama

- Autentikasi Dual-Mode: Login dengan email/username atau Google OAuth
- Registration Otomatis: Pendaftar baru langsung bisa menggunakan akun
- Google OAuth 2.0: Integrasi dengan Google untuk login/register cepat
- Profile Management: Tab-based profile system (Profil, Keamanan, Alamat, Akun)
- Upload Foto Profil: Support profile photo dengan storage lokal
- Manajemen Alamat: Simpan multiple alamat pengiriman
- Ganti Password: Password change dengan validasi
- Dark/Light Mode: Toggle dark mode dengan persistent storage
- Shopping Cart: Icon cart di navbar

---

## Persyaratan

Pastikan sudah menginstall:

- [PHP](https://www.php.net/) >= 8.2
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) >= 18
- [MySQL](https://www.mysql.com/) >= 5.7
- [Git](https://git-scm.com/)

**Optional (untuk Windows):**
- [Laragon](https://laragon.org/) - Local development environment

---

## Setup & Instalasi

### 1. Clone Repository

```bash
git clone <url-repository> Omnexus
cd Omnexus
```

### 2. Install PHP & Node Dependencies

```bash
composer install
npm install
```

### 3. Setup File Environment

Salin `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

**Buat database MySQL baru:**
```sql
CREATE DATABASE PBL;
```

**Update `.env` dengan credentials database Anda:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PBL
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Database Migrations

```bash
php artisan migrate
```

Ini akan membuat semua tabel termasuk `users`, `sessions`, dan kolom tambahan untuk profile.

### 7. Setup Storage Link (untuk profile photos)

```bash
php artisan storage:link
```

---

## Setup Google OAuth

### A. Buat Google OAuth Credentials

1. **Buka [Google Cloud Console](https://console.cloud.google.com)**
   - Login dengan akun Google Anda

2. **Buat Project Baru** (jika belum punya)
   - Klik "Select a project" → "New Project"
   - Beri nama: `Omnexus`
   - Klik "Create"

3. **Enable Google+ API**
   - Cari "Google+ API" di search bar
   - Klik hasil → klik "Enable"

4. **Buat OAuth 2.0 Credentials**
   - Buka **APIs & Services** → **Credentials** (menu kiri)
   - Klik **Create Credentials** → **OAuth client ID**
   - Jika diminta, setup **OAuth Consent Screen**:
     - Pilih **External** → Continue
     - Isi App Name: `Omnexus`
     - Isi User Support Email: Email Anda sendiri
     - Klik "Save and Continue" sampai selesai

5. **Configure OAuth Client**
   - Application type: **Web application**
   - Name: `Omnexus Local` (atau production)
   - **Authorized redirect URIs**: Tambahkan:
     ```
     http://localhost:8000/auth/google/callback
     ```
     (Untuk production: `https://yourdomain.com/auth/google/callback`)
   - Klik **Create**

6. **Salin Credentials**
   - Modal akan muncul dengan **Client ID** dan **Client Secret**
   - Salin kedua nilai tersebut

### B. Update `.env` dengan Google Credentials

Buka file `.env` dan isi bagian Google:

```env
GOOGLE_CLIENT_ID=your_client_id_here
GOOGLE_CLIENT_SECRET=your_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Contoh:**
```env
GOOGLE_CLIENT_ID=959653117964-27v62nr2c55tocholuf35ug9s11c1hj1.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### C. Clear Cache

```bash
php artisan config:clear
```

---

## Menjalankan Project

### Development Mode

**Buka 2 terminal secara bersamaan:**

**Terminal 1 — Jalankan Laravel Server:**
```bash
php artisan serve
```
Server akan jalan di `http://localhost:8000`

**Terminal 2 — Jalankan Vite (CSS/JS bundler):**
```bash
npm run dev
```

Sekarang akses [http://localhost:8000](http://localhost:8000) di browser.

### Build untuk Production

```bash
npm run build
```

---

## Project Structure

```
Omnexus/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Auth/
│   │           ├── SocialAuthController.php      # Google OAuth handler
│   │           ├── LoginRequest.php              # Login validation (email/username)
│   │           └── ... (auth controllers lainnya)
│   ├── Models/
│   │   └── User.php                             # User model dengan google_id & profile fields
│   └── Providers/
│
├── config/
│   ├── services.php                             # Google OAuth configuration
│   └── ... (config lainnya)
│
├── database/
│   ├── migrations/
│   │   ├── ... (default migrations)
│   │   ├── 2026_03_13_000003_add_google_id_to_users_table.php
│   │   └── 2026_03_13_000004_add_profile_fields_to_users_table.php
│   └── seeders/
│
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.blade.php                  # Custom login page
│   │   │   └── register.blade.php               # Custom register page
│   │   ├── profile/
│   │   │   └── edit.blade.php                   # Profile dashboard dengan tabs
│   │   ├── beranda.blade.php                    # Homepage
│   │   └── ... (layouts & components)
│   ├── css/
│   │   └── app.css                              # Tailwind CSS
│   └── js/
│       ├── app.js                               # Main JS
│       └── bootstrap.js                         # Laravel bootstrap
│
├── routes/
│   ├── web.php                                  # Web routes (beranda, dashboard)
│   ├── auth.php                                 # Auth routes (login, register, OAuth)
│   └── ... (routes lainnya)
│
├── .env.example                                 # Environment template
├── composer.json                                # PHP dependencies
├── package.json                                 # Node dependencies
├── vite.config.js                               # Vite configuration
└── tailwind.config.js                           # Tailwind CSS config
```

---

## Troubleshooting

### Error: "404 Not Found" pada Google Callback

**Solusi:**
- Clear route cache: `php artisan route:clear`
- Pastikan `APP_URL` di `.env` sesuai (misal: `http://localhost:8000`)
- Restart `php artisan serve`

### Error: "InvalidStateException" dari Google

**Solusi:**
- Clear cache: `php artisan cache:clear && php artisan config:clear`
- Pastikan cookies diterima di browser (bukan private/incognito mode)
- Coba refresh halaman login

### Foto Profil tidak Muncul

**Solusi:**
- Pastikan sudah jalankan: `php artisan storage:link`
- Restart `php artisan serve`
- Upload foto baru di profile page

### Database Error

**Solusi:**
- Pastikan MySQL sudah running
- Konfigurasi DB di `.env` benar
- Jalankan: `php artisan migrate`

---

## Environment Variables Penting

| Variable | Default | Keterangan |
|----------|---------|-----------|
| `APP_NAME` | Interco | Nama aplikasi |
| `APP_URL` | http://localhost:8000 | URL aplikasi (ubah untuk production) |
| `DB_DATABASE` | PBL | Nama database MySQL |
| `GOOGLE_CLIENT_ID` | - | **REQUIRED** - dari Google Cloud Console |
| `GOOGLE_CLIENT_SECRET` | - | **REQUIRED** - dari Google Cloud Console |
| `SESSION_DRIVER` | cookie | Driver session (cookie atau database) |

---

## Tech Stack

- **Backend**: Laravel 12 (PHP Framework)
- **Frontend**: Tailwind CSS + Alpine.js
- **Database**: MySQL
- **Authentication**: Laravel Breeze (customized) + Laravel Socialite
- **OAuth Provider**: Google OAuth 2.0
- **Build Tool**: Vite
- **Package Manager**: Composer + npm

---

## License

Private project. Untuk informasi lebih lanjut, hubungi team lead.

---

## Pertanyaan atau Masalah?

Hubungi:
- Slack Channel: #omnexus-dev
- Email: dev-team@omnexus.id (jika ada)
