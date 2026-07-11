# Dependency Documentation

Daftar semua dependency yang digunakan dalam proyek Omnexus.

---

## PHP Dependencies (composer.json)

### Production

| Package | Versi | Fungsi |
| --- | --- | --- |
| `php` | `^8.2` | Bahasa pemrograman utama backend |
| `laravel/framework` | `^12.0` | Framework PHP utama (routing, ORM, middleware, dll) |
| `laravel/socialite` | `^5.25` | Autentikasi OAuth pihak ketiga (Google Login) |
| `laravel/tinker` | `^2.10.1` | REPL interaktif untuk debugging via CLI |

### Development

| Package | Versi | Fungsi |
| --- | --- | --- |
| `fakerphp/faker` | `^1.23` | Generate data palsu untuk testing dan database seeding |
| `laravel/breeze` | `^2.3` | Starter kit autentikasi (scaffolding login, register, profil) |
| `laravel/pail` | `^1.2.2` | Log viewer real-time di terminal |
| `laravel/pint` | `^1.24` | Code style fixer berbasis PHP-CS-Fixer |
| `laravel/sail` | `^1.41` | Docker development environment untuk Laravel |
| `mockery/mockery` | `^1.6` | Library mocking untuk unit testing |
| `nunomaduro/collision` | `^8.6` | Error handler yang lebih informatif di terminal |
| `phpunit/phpunit` | `^11.5.3` | Framework unit testing PHP |

---

## Node.js Dependencies (package.json)

Semua package Node berada di `devDependencies` karena hanya digunakan pada proses build frontend.

| Package | Versi | Fungsi |
| --- | --- | --- |
| `vite` | `^7.0.7` | Build tool dan dev server untuk aset frontend |
| `laravel-vite-plugin` | `^2.0.0` | Plugin Vite untuk integrasi dengan Laravel Blade |
| `tailwindcss` | `^3.1.0` | Framework CSS utility-first |
| `@tailwindcss/vite` | `^4.0.0` | Plugin Vite untuk Tailwind CSS |
| `@tailwindcss/forms` | `^0.5.2` | Plugin Tailwind untuk styling elemen form |
| `alpinejs` | `^3.4.2` | Framework JavaScript ringan untuk interaktivitas UI |
| `axios` | `^1.11.0` | HTTP client berbasis Promise untuk request AJAX |
| `autoprefixer` | `^10.4.2` | PostCSS plugin untuk menambah vendor prefix CSS otomatis |
| `postcss` | `^8.4.31` | Preprocessor CSS (digunakan bersama Tailwind) |
| `concurrently` | `^9.0.1` | Menjalankan beberapa command CLI secara bersamaan |

---

## Layanan Eksternal

| Layanan | Tujuan | Konfigurasi di `.env` |
| --- | --- | --- |
| Google OAuth 2.0 | Login dan register via akun Google | `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` |
| MySQL | Database utama aplikasi | `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` |

---

## PHP Extensions yang Diperlukan

Ekstensi berikut harus aktif (biasanya sudah aktif secara default di Laragon/XAMPP):

| Extension | Kegunaan |
| --- | --- |
| `ext-pdo` | Koneksi database |
| `ext-mbstring` | Manipulasi string multibyte |
| `ext-openssl` | Enkripsi dan keperluan OAuth |
| `ext-json` | Parsing JSON |
| `ext-tokenizer` | Digunakan oleh Laravel |
| `ext-fileinfo` | Validasi file upload (foto profil, bukti pembayaran) |

---

## Cara Install

```bash
# Install semua PHP dependency
composer install

# Install semua Node.js dependency
npm install
```

---

## Dampak pada Proyek

- **laravel/socialite** menyediakan fungsionalitas login satu klik via Google dan menarik package `GuzzleHTTP` secara otomatis sehingga ukuran vendor bertambah.
- **laravel/breeze** digunakan sebagai fondasi sistem autentikasi yang kemudian dikustomisasi. Merupakan dev dependency, tidak masuk ke production build.
- **alpinejs** menangani semua interaktivitas UI (dark mode toggle, tab profil, modal) tanpa membutuhkan framework berat seperti Vue atau React.
- Apabila Laravel diperbarui ke versi mayor baru, kompatibilitas semua package di atas perlu diverifikasi kembali.
