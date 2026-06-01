# Dependency Documentation

Berikut adalah daftar dependency utama (pihak ketiga) yang ditambahkan ke dalam proyek di luar instalasi bawaan Laravel, beserta fungsinya:

| Package | Fungsi | Alasan | Versi | Risiko |
| --- | --- | --- | --- | --- |
| `laravel/socialite` | Mengurus OAuth (Google Login) | Menghemat waktu implementasi OAuth manual dan lebih terstandarisasi/aman. | ^5.25 | Rentan jika Google mengubah API autentikasinya (perlu pembaruan versi). |
| `laravel/breeze` | Scaffolding Autentikasi UI (Dev) | Memberikan struktur dasar UI Tailwind untuk login, register, dan manajemen profil dengan cepat. | ^2.3 | Sedikit overhead atau bentrok styling jika custom CSS tidak dikelola dengan baik. |

## Cara Install

Untuk menambahkan `laravel/socialite`:
```bash
composer require laravel/socialite
```

(Package dev seperti `laravel/breeze` biasanya diinstall melalui perintah `composer require laravel/breeze --dev` pada awal project setup).

## Dampak pada proyek

- **Menambah fitur**: Menyediakan fungsionalitas registrasi/login satu klik menggunakan platform sosial (Google).
- **Menambah ukuran dependency**: Vendor directory akan bertambah karena package terkait Socialite dan GuzzleHTTP ditarik otomatis.
- **Risiko update versi**: Apabila versi Laravel diperbarui ke mayor yang baru (misal Laravel 13 nantinya), package Socialite juga harus dipastikan kompatibel.
