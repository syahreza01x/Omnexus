# Feature Documentation

Dokumentasi fitur lengkap dari proyek Omnexus.

---

## Autentikasi dan Keamanan

### Login dan Register

**Tujuan fitur**
Memberikan akses masuk ke sistem dengan dua metode: formulir email/username tradisional, dan login cepat melalui akun Google.

**Aktor**
User (Pelanggan)

**Alur fitur**
User membuka halaman login, memilih metode login (formulir atau Google), mengisi kredensial, sistem memvalidasi, lalu mengarahkan ke beranda.

**Route / Controller terkait**
- Route: `GET /login`, `POST /login`, `GET /register`, `POST /register`
- Controller: `Auth/AuthenticatedSessionController`, `Auth/RegisteredUserController`

---

### Google OAuth 2.0

**Tujuan fitur**
Memungkinkan user masuk atau mendaftar menggunakan akun Google tanpa mengisi formulir registrasi manual.

**Aktor**
User (Pelanggan)

**Alur fitur**
User klik "Lanjutkan dengan Google" pada halaman login → diarahkan ke Google Consent Screen → user menyetujui akses → Google mengirim callback ke aplikasi → sistem menghubungkan akun yang sudah ada atau membuat akun baru secara otomatis → beranda.

**Route / Controller terkait**
- Route: `GET /auth/google/redirect`, `GET /auth/google/callback`
- Controller: `Auth/SocialAuthController`

---

## Manajemen Profil

**Tujuan fitur**
Memudahkan user mengelola data diri secara mandiri dalam satu halaman yang tertata dengan sistem tab.

**Aktor**
User (Pelanggan)

**Tab yang tersedia**

| Tab | Isi |
| --- | --- |
| Profil | Edit nama, username, bio, dan upload foto profil |
| Keamanan | Ganti email dan ganti password |
| Alamat | Tambah, edit, hapus, dan set alamat default pengiriman |
| Akun | Hapus akun secara permanen |

**Alur fitur**
User buka menu Profil dari navbar → memilih tab yang diinginkan → mengubah data → klik simpan → data tervalidasi dan diperbarui.

**Route / Controller terkait**
- Route: `GET /profile`, `PATCH /profile`, `PATCH /profile/basic`, `PATCH /profile/security/email`, `DELETE /profile`
- Route alamat: `POST /addresses`, `PATCH /addresses/{address}`, `POST /addresses/{address}/set-default`, `DELETE /addresses/{address}`
- Controller: `ProfileController`
- View: `resources/views/profile/edit.blade.php`

---

## Katalog Produk

**Tujuan fitur**
Menampilkan produk custom clothing yang tersedia kepada pengunjung.

**Aktor**
User (Pelanggan), Tamu

**Alur fitur**
User membuka beranda → sistem menampilkan daftar produk aktif terbaru → user melihat detail produk (nama, gambar, harga, stok).

**Route / Controller terkait**
- Route: `GET /`
- Model: `Product`
- View: `resources/views/beranda.blade.php`

---

## Keranjang Belanja

**Tujuan fitur**
Memungkinkan user menampung produk yang ingin dibeli sebelum melakukan checkout.

**Aktor**
User (Pelanggan)

**Alur fitur**
User klik tombol "Tambah ke Keranjang" pada produk → produk masuk ke session keranjang → user dapat mengubah jumlah atau menghapus produk → lanjut ke checkout.

**Catatan teknis**
Data keranjang disimpan di session, bukan database. Keranjang otomatis dikosongkan setelah checkout berhasil.

**Route / Controller terkait**
- Route: `POST /cart`, `PATCH /cart/{product}`, `DELETE /cart/{product}`, `POST /cart/clear`
- Controller: `CartController`

---

## Checkout dan Pembayaran

**Tujuan fitur**
Memproses pemesanan dari isi keranjang menjadi transaksi resmi, termasuk pemilihan alamat pengiriman dan upload bukti pembayaran.

**Aktor**
User (Pelanggan)

**Alur fitur**
User buka halaman checkout → sistem menampilkan ringkasan pesanan dan total harga → user memilih alamat pengiriman → user konfirmasi pesanan → transaksi dibuat dengan status `pending` → user mengupload bukti transfer → status diperbarui oleh admin.

**Route / Controller terkait**
- Route: `GET /checkout`, `POST /checkout`
- Controller: `CheckoutController`
- View: `resources/views/checkout.blade.php`

---

## Riwayat Pesanan

**Tujuan fitur**
Memungkinkan user melihat semua pesanan yang pernah dibuat beserta statusnya.

**Aktor**
User (Pelanggan)

**Status transaksi yang tersedia**
`pending`, `processing`, `shipped`, `completed`, `cancelled`

**Route / Controller terkait**
- Route: `GET /orders`, `GET /orders/{transaction}`, `POST /orders/{transaction}/proof`
- Controller: `OrderController`
- View: `resources/views/orders/`

---

## Live Chat

**Tujuan fitur**
Menyediakan saluran komunikasi langsung antara user dan tim customer service.

**Aktor**
User (Pelanggan), Admin

**Alur fitur**
User buka halaman chat → mengirim pesan → pesan tersimpan di database → admin membaca dan membalas dari panel admin → user melihat balasan.

**Route / Controller terkait**
- Route: `GET /chat`, `POST /chat`
- Controller: `ChatController`, `Admin/AdminChatController`
- Model: `Message`

---

## Panel Admin

**Tujuan fitur**
Memberikan akses pengelolaan produk, pesanan, stok, dan chat kepada admin.

**Aktor**
Admin

**Sub-fitur**

| Fitur | Keterangan |
| --- | --- |
| Manajemen Produk | Tambah, edit, hapus, aktifkan/nonaktifkan produk |
| Manajemen Pesanan | Lihat semua pesanan, verifikasi bukti bayar, ubah status |
| Manajemen Stok | Pantau stok produk, catat perubahan stok masuk/keluar via `StockLog` |
| Chat Admin | Balas pesan dari semua user |

**Controller terkait**
- `Admin/AdminWebController`
- `Admin/AdminWarehouseController`
- `Admin/AdminChatController`

---

## Panel Super Admin

**Tujuan fitur**
Memberikan kontrol penuh atas akun admin dan hak akses sistem.

**Aktor**
Super Admin

**Fitur tambahan di atas Admin**
- Manajemen akun admin (buat, edit, hapus)
- Kontrol role dan hak akses user

**Controller terkait**
- `Admin/SuperAdminController`

---

## Dark / Light Mode

**Tujuan fitur**
Memungkinkan user memilih tema tampilan sesuai preferensi.

**Cara kerja**
Preferensi tema disimpan di `localStorage` browser sehingga tetap tersimpan antar sesi tanpa perlu login. Semua halaman mendukung kedua tema.
