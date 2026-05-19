# Tugas 5W+1H - Berdasarkan Project Omnexus

## 1. What

Apa nama dependency/package yang digunakan?

Jawaban:

- `laravel/socialite` (integrasi OAuth login Google)

---

## 2. Why

Mengapa package tersebut diperlukan?

Jawaban:

Package ini dipakai agar fitur login/register dengan akun Google bisa diimplementasikan lebih cepat, aman, dan mengikuti alur OAuth 2.0 tanpa membuat integrasi OAuth manual dari nol.

---

## 3. Who

Siapa yang akan menggunakan atau terdampak oleh package tersebut?

Jawaban:

- User aplikasi: bisa masuk lebih cepat menggunakan Google.
- Developer: lebih mudah mengelola autentikasi sosial.
- Admin/owner sistem: onboarding user jadi lebih praktis.

---

## 4. When

Kapan package tersebut digunakan dalam sistem?

Jawaban:

Digunakan saat user menekan tombol "Lanjutkan dengan Google" pada halaman login/register, lalu saat proses callback dari Google ke aplikasi.

---

## 5. Where

Di bagian mana package digunakan?

Jawaban:

- `app/Http/Controllers/Auth/SocialAuthController.php`
- `routes/auth.php` (route `auth/google/redirect` dan `auth/google/callback`)
- `config/services.php` (konfigurasi `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`)
- `resources/views/auth/login.blade.php` dan `resources/views/auth/register.blade.php` (tombol login/register Google)

---

## 6. How

Bagaimana cara kerja atau implementasi package tersebut?

Jawaban:

1. Package diinstall via Composer sebagai dependency backend Laravel.
2. Saat user klik login Google, aplikasi memanggil `Socialite::driver('google')->redirect()`.
3. Setelah user autentikasi di Google, callback diproses dengan `Socialite::driver('google')->user()`.
4. Sistem mencari user berdasarkan `google_id` atau email.
5. Jika user belum ada, sistem membuat akun baru; jika sudah ada tapi `google_id` kosong, sistem melakukan linking akun.
6. User di-login ke aplikasi dan diarahkan ke halaman utama (`beranda`).

---

## Format Ringkas Jawaban (5W+1H)

| 5W+1H | Penjelasan |
|---|---|
| What | `laravel/socialite` |
| Why | Menyediakan login/register Google OAuth 2.0 secara cepat dan aman |
| Who | User aplikasi, developer, admin/owner sistem |
| When | Saat user memilih login/register dengan Google dan pada proses callback |
| Where | Controller auth sosial, routes auth, config services, view login/register |
| How | Redirect ke Google, terima callback, cari/buat user, login user |
