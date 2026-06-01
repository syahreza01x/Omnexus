# Omnexus - Sistem Informasi E-Commerce Custom Clothing

## 1. Deskripsi Proyek
- **Tujuan Aplikasi:** Platform e-commerce untuk pemesanan custom clothing dengan sistem autentikasi modern yang mendukung Google OAuth 2.0 dan manajemen profil yang lengkap.
- **Masalah yang Diselesaikan:** Menyediakan sistem registrasi dan login yang cepat (tanpa hambatan) serta memudahkan pengelolaan pesanan custom pakaian secara digital.
- **Target Pengguna:** Pelanggan yang ingin memesan custom clothing secara online, serta admin/owner sistem yang mengelola pesanan.

## 2. Fitur Utama
- **Autentikasi Dual-Mode**: Login dengan email/username atau Google OAuth.
- **Registration Otomatis**: Pendaftar baru via Google langsung bisa menggunakan akun tanpa repot.
- **Profile Management**: Sistem tab untuk mengelola Profil, Keamanan, Alamat, dan Akun, beserta upload foto.
- **Manajemen Alamat & Ganti Password**: Memudahkan user menyimpan berbagai alamat pengiriman dan menjaga keamanan akun.
- **Shopping Cart & Dark/Light Mode**: Icon cart interaktif di navbar dan toggle mode tampilan yang persisten.

## 3. Teknologi yang Digunakan
- Laravel 12 (PHP Framework)
- MySQL
- Tailwind CSS + Alpine.js
- Laravel Breeze + Laravel Socialite (Google OAuth 2.0)
- Vite + Composer + npm

## 4. Instalasi Singkat
```bash
git clone <url-repository> Omnexus
cd Omnexus
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run dev
php artisan serve
```

## 5. Screenshot Proyek
- Halaman Login: ![Login](docs/images/login-placeholder.png)
- Dashboard/Beranda: ![Dashboard](docs/images/dashboard-placeholder.png)
- Fitur Utama (Profil): ![Profil](docs/images/profile-placeholder.png)
*(Silakan tambahkan folder docs/images/ dan ganti link screenshot di atas dengan gambar asli)*

## 6. Tim Pengembang
- Anggota 1
- Anggota 2
- Anggota 3
- Anggota 4
*(Silakan ganti dengan nama anggota Kelompok 4 sebenarnya)*

---

**Dokumentasi Tambahan:**
- [Dokumentasi Instalasi Detail](docs/installation.md)
- [Dokumentasi Fitur](docs/features.md)
- [Dokumentasi Dependency](docs/dependency.md)
- [Dokumentasi Refactoring](docs/refactoring.md)
- [Dokumentasi GitHub Actions](docs/github-actions.md)
- [Changelog](CHANGELOG.md)
