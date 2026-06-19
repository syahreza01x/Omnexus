# Changelog

Semua catatan perubahan pada proyek ini akan didokumentasikan di file ini.

## Planned - Version 1.1.0

### Changed
- Revisi seluruh bagian desain seperti ikon dan layout antarmuka untuk meningkatkan UI/UX tanpa menghilangkan struktur desain awal.

### Impacted Modules
- View
- Styling (Tailwind/CSS)


## v1.0.0

### Added
- Fitur login dan register menggunakan akun Google (Google OAuth 2.0).
- Fitur Profile Management berbasis tab.
- Fungsionalitas upload foto profil, ganti password, dan simpan daftar alamat pengiriman.
- Tampilan Dark/Light mode switcher pada antarmuka pengguna.
- Halaman dokumentasi teknis yang komprehensif di dalam folder `docs/`.

### Dependency
- add `laravel/socialite` untuk autentikasi pihak ketiga.
- add Tailwind CSS + Alpine.js sebagai framework styling dan interaksi komponen.

### Refactor
- Memisahkan logika autentikasi Google ke dalam `SocialAuthController` agar tidak mengganggu sistem autentikasi bawaan.
