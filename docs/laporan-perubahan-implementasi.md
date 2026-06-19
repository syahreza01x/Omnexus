# Laporan Perubahan dan Implementasi

Penjelasan lengkap berdasarkan perubahan yang lakukan:

## Komponen yang Berdampak

| Komponen        | File                | Perubahan                                                                      |
| :-------------- | :------------------ | :----------------------------------------------------------------------------- |
| Halaman Beranda | `beranda.blade.php` | Redesign total — glassmorphism, animasi scroll, dark mode, hero section        |
| Navbar / Header | `beranda.blade.php` | Ubah `position: sticky` → `fixed`, tambah glassmorphism, light mode handling   |
| Cart Drawer     | `beranda.blade.php` | Perbaikan z-index (`z-50` → `z-[200]`), avatar tidak lagi menghalangi tombol X |
| Product Modal   | `beranda.blade.php` | Tambah auth guard — guest diarahkan ke login, bukan langsung ke form cart      |
| Routing Cart    | `routes/web.php`    | Semua cart route dilindungi middleware `auth`                                  |

## Risiko

**1. Risiko Tampilan (UI Regression)**
Perubahan desain besar-besaran pada `beranda.blade.php` berisiko merusak tata letak yang sudah ada. Misalnya ketika navbar diubah dari `sticky` ke `fixed`, section-section di bawahnya bisa tertutup jika `padding-top` tidak disesuaikan. Ini terbukti terjadi — dan diperbaiki dengan menambahkan `padding-top: 64px` pada `.hero-section`.

**2. Risiko Kompatibilitas Browser**
Fitur seperti `backdrop-filter: blur()` untuk efek glassmorphism tidak didukung semua browser lama. Pada browser yang tidak support, navbar akan terlihat transparan penuh tanpa blur — fungsional, tapi kurang estetis.

**3. Risiko Z-Index Conflict**
Navbar `position: fixed` dengan `z-index: 100` bertabrakan dengan cart drawer `z-index: 50`. Akibatnya avatar profil user "menembus" cart dan tombol X tidak bisa diklik. Solusinya: naikkan z-index cart ke `z-[200]`.

**4. Risiko Keamanan (Security)**
Cart routes sebelumnya tidak dilindungi middleware `auth` di sisi server. Artinya siapapun bisa mengirim request POST ke `/cart` secara manual (misal pakai Postman) tanpa login. Ini adalah celah keamanan kecil yang sudah diperbaiki.

**5. Risiko UX — State Tidak Konsisten**
Jika dark mode disimpan di `localStorage` tapi warna icon navbar tidak mengikuti, user akan melihat icon putih di atas background putih (tidak terlihat). Solusinya: binding Alpine.js diubah dari hanya cek `navScrolled` menjadi cek `(navScrolled || !darkMode)`.

## Hasil Implementasi

**1. Redesign Halaman Beranda**
Landing page sekarang memiliki tampilan modern dengan:

- Hero section gelap (`#0a0a0f`) dengan efek noise grain dan orb gradient beranimasi
- Scroll-triggered animations menggunakan `IntersectionObserver`
- Card produk dengan hover effect dan glassmorphism
- Dark/Light mode toggle yang persisten via `localStorage`
- Typography premium (Playfair Display + Inter dari Google Fonts)

**2. Header Bug Fix**
Header yang sebelumnya tidak terlihat saat berada di posisi paling atas (karena teks putih di atas background putih) kini selalu terlihat:

- Di light mode: navbar selalu putih/glass sejak awal load
- Di dark mode: navbar transparan di atas hero gelap, lalu berubah menjadi glass saat scroll

**3. Cart Auth Guard**

- Guest tidak melihat icon cart di navbar sama sekali
- Jika guest membuka modal produk, tombol "Masukkan ke Keranjang" digantikan oleh tombol "Masuk untuk Memesan" yang mengarahkan ke halaman login
- Di sisi server, semua cart routes (`/cart` POST/PATCH/DELETE) sudah dilindungi `middleware('auth')` di `web.php`

**4. Cart Drawer Fix**
Tombol X (tutup) pada cart drawer kini bisa diklik karena cart berada di z-index lebih tinggi dari navbar.

## Refleksi

**Yang berjalan baik:**
Pendekatan iteratif — mulai dari redesign visual, lalu fix bug satu per satu — terbukti efektif. Setiap bug yang muncul (header tidak terlihat, avatar menghalangi tombol X, icon putih di light mode) bisa ditelusuri akarnya karena perubahan dilakukan bertahap dan terstruktur.

**Yang bisa diperbaiki:**

- Tidak ada unit/feature test — perubahan di `web.php` dan `beranda.blade.php` dilakukan manual tanpa automated test, sehingga regresi hanya terdeteksi dari observasi visual di browser.
- CSS masih inline — banyak style ditulis inline (`style="..."`) di dalam Blade, yang seharusnya dipindahkan ke class CSS agar lebih mudah di-maintain.
- Dark mode tidak menggunakan Tailwind variant sepenuhnya — sebagian besar dark mode handling dilakukan via CSS custom properties dan Alpine.js binding, bukan Tailwind `dark:` variant. Ini membuat kode lebih panjang dari seharusnya.

**Pelajaran utama:**
Saat mengubah `position: sticky` menjadi `fixed`, selalu pertimbangkan dampaknya ke elemen di bawahnya. Dan dalam pengembangan web yang melibatkan auth, proteksi harus dilakukan di dua lapisan: frontend (UI) dan backend (middleware) — karena frontend saja tidak cukup untuk mencegah akses tidak sah.
