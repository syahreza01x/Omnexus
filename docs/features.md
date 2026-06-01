# Feature Documentation

Berikut adalah dokumentasi fitur utama dari proyek Omnexus:

## Login dengan Google (OAuth)

**Tujuan fitur**
Memberikan kemudahan bagi pengguna agar dapat masuk ke dalam sistem dengan cepat dan aman tanpa perlu mengisi form registrasi panjang, melainkan menggunakan akun Google yang sudah mereka miliki.

**Aktor**
User aplikasi (Pelanggan)

**Alur fitur**
User klik tombol "Lanjutkan dengan Google" pada halaman login → Sistem mengalihkan user ke Google Consent Screen → User menyetujui akses → Google mengembalikan callback ke aplikasi dengan data user → Sistem memvalidasi dan menghubungkan akun / membuat akun baru → Dashboard (Beranda).

**Route / Controller terkait**
- Route: `GET /auth/google/redirect`, `GET /auth/google/callback`
- Controller: `SocialAuthController`

**Screenshot fitur**
![Login Google Placeholder](images/login-google-placeholder.png)

---

## Manajemen Profil (Tab-based)

**Tujuan fitur**
Memudahkan pengguna mengelola informasi data diri secara mandiri, seperti melihat profil, mengubah foto profil, mengatur daftar alamat pengiriman, dan keamanan (ganti password), di satu halaman yang tertata rapi.

**Aktor**
User aplikasi (Pelanggan)

**Alur fitur**
User masuk ke menu Profil dari Navbar → Sistem menampilkan dashboard profil berbasis tab → User beralih antar tab (Profil, Keamanan, Alamat, Akun) → User mengubah data (contoh: upload foto baru) → Klik simpan → Data tervalidasi dan berhasil diperbarui.

**Route / Controller terkait**
- Route: `GET /profile`, `PATCH /profile`, `DELETE /profile`
- View: `resources/views/profile/edit.blade.php`

**Screenshot fitur**
![Profil Dashboard Placeholder](images/profile-dashboard-placeholder.png)

---

*(Silakan tambahkan fitur-fitur lain dan perbarui gambar placeholder dengan path screenshot asli jika sudah tersedia)*
