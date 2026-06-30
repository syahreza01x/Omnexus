# Omnexus — Platform E-Commerce Custom Clothing

**Omnexus** adalah platform e-commerce yang dikhususkan untuk pemesanan custom clothing. Platform ini menyediakan pengalaman belanja yang lengkap, mulai dari browsing produk, manajemen keranjang belanja, proses checkout, konfirmasi pembayaran, hingga sistem chat langsung dengan tim.

---

## Fitur Utama

| Fitur | Keterangan |
| --- | --- |
| Autentikasi Dual-Mode | Login dengan email/username atau Google OAuth 2.0 |
| Shopping Cart | Tambah, ubah, hapus, dan clear produk di keranjang |
| Checkout dan Pesanan | Proses checkout dengan pilihan alamat pengiriman |
| Upload Bukti Pembayaran | Upload bukti transfer untuk konfirmasi pesanan |
| Riwayat Pesanan | Lihat semua pesanan dan status transaksi |
| Live Chat | Chat langsung dengan tim customer service |
| Profile Management | Tab-based: Profil, Keamanan, Alamat, Akun |
| Upload Foto Profil | Support profile photo dengan storage lokal |
| Manajemen Alamat | Simpan dan kelola multiple alamat pengiriman |
| Dark / Light Mode | Toggle tema dengan persistent local storage |
| Admin Panel | Manajemen produk, pesanan, stok, dan chat oleh admin |
| Super Admin | Manajemen admin dan hak akses sistem |

---

## Tech Stack

| Layer | Teknologi |
| --- | --- |
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Blade + Tailwind CSS v3 + Alpine.js |
| Database | MySQL 5.7+ |
| Autentikasi | Laravel Breeze (kustom) + Laravel Socialite |
| OAuth Provider | Google OAuth 2.0 |
| Build Tool | Vite 7 |
| HTTP Client | Axios |
| Package Manager | Composer + npm |

---

## Persyaratan

- [PHP](https://www.php.net/) >= 8.2
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) >= 18
- [MySQL](https://www.mysql.com/) >= 5.7
- [Git](https://git-scm.com/)

**Optional (untuk Windows):** [Laragon](https://laragon.org/) — Local development environment yang sudah include PHP, MySQL, dan server lokal.

---

## Instalasi Cepat

Lihat panduan lengkap di [docs/installation.md](./docs/installation.md).

```bash
git clone <url-repository> Omnexus
cd Omnexus
composer install && npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
```

Lalu jalankan:

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

Akses di: http://127.0.0.1:8000

---

## Struktur Project

```
Omnexus/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/
│   │       │   ├── AdminChatController.php      # Chat management (admin)
│   │       │   ├── AdminWarehouseController.php # Manajemen stok dan gudang
│   │       │   ├── AdminWebController.php       # Manajemen produk dan pesanan
│   │       │   └── SuperAdminController.php     # Manajemen user dan admin
│   │       ├── Auth/
│   │       │   └── SocialAuthController.php     # Google OAuth handler
│   │       ├── CartController.php               # Keranjang belanja
│   │       ├── CheckoutController.php           # Proses checkout
│   │       ├── OrderController.php              # Pesanan dan upload bukti bayar
│   │       ├── ChatController.php               # Live chat user
│   │       └── ProfileController.php            # Manajemen profil
│   └── Models/
│       ├── User.php
│       ├── Product.php
│       ├── Transaction.php
│       ├── TransactionItem.php
│       ├── Address.php
│       ├── Message.php
│       └── StockLog.php
│
├── database/
│   └── migrations/
│
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── auth/
│   │   ├── chat/
│   │   ├── orders/
│   │   ├── profile/
│   │   ├── beranda.blade.php
│   │   └── checkout.blade.php
│   ├── css/app.css
│   └── js/app.js
│
├── routes/
│   ├── web.php
│   ├── admin.php
│   └── auth.php
│
└── docs/
    ├── installation.md
    ├── features.md
    ├── dependency.md
    └── ...
```

---

## Dokumentasi

| File | Isi |
| --- | --- |
| [docs/installation.md](./docs/installation.md) | Panduan instalasi dan konfigurasi lengkap |
| [docs/features.md](./docs/features.md) | Daftar fitur lengkap beserta penjelasannya |
| [docs/dependency.md](./docs/dependency.md) | Semua dependency PHP dan Node.js |
| [CHANGELOG.md](./CHANGELOG.md) | Catatan perubahan tiap versi |

---

## Kontak Tim

- Slack: `#omnexus-dev`
- Email: `dev-team@omnexus.id`

---

## Lisensi

Private project — hak cipta dilindungi. Untuk informasi lebih lanjut, hubungi team lead.
