# GitHub Actions / CI Documentation

## Workflow yang digunakan

**Continuous Integration (CI)**
CI workflow digunakan untuk memeriksa validitas kode secara otomatis setiap kali ada perubahan yang masuk ke repositori. Workflow ini menginstal semua dependency proyek (Composer & NPM), menyalin konfigurasi environment, men-generate application key, menyiapkan SQLite in-memory database, dan menjalankan automated test bawaan proyek.

## Lokasi file
`.github/workflows/ci.yml` *(Akan dibuat/Direncanakan)*

## Trigger
Saat ada event `push` atau `pull_request` ke branch `main`.

## Tahapan workflow (Direncanakan)
1. **Checkout code:** Mengambil source code dari repositori (menggunakan action `checkout@v4`).
2. **Setup PHP:** Menyiapkan versi PHP yang digunakan (misal PHP 8.2) dan ekstensi yang dibutuhkan.
3. **Setup Node:** Menyiapkan Node.js untuk asset bundling.
4. **Composer install:** Mengunduh dan menginstal library/package vendor.
5. **NPM install & build:** Menginstal dan mem-build asset frontend (Tailwind/Vite).
6. **Environment Setup:** `cp .env.example .env` dan `php artisan key:generate`.
7. **Run test:** Menjalankan perintah `php artisan test` untuk memastikan semua fitur berjalan lancar.

## Hasil workflow
*(Belum ada badge/screenshot, rencananya berupa status badge "build passing" di README)*
