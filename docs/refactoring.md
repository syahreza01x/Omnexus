# Refactoring Documentation

## Pemisahan Controller Autentikasi Sosial (Service Extraction)

**Sebelum (Masalah):**
Logika autentikasi dan callback Google disatukan di dalam controller autentikasi standar (misalnya `LoginController` atau `RegisteredUserController`) sehingga menyebabkan controller tersebut menjadi terlalu besar, padat (fat controller), dan mengotori logika autentikasi default berbasis email/password.

**Perubahan:**
Logika khusus untuk OAuth Google dipisahkan ke controller tersendiri bernama `SocialAuthController`.

**Alasan:**
- Memudahkan proses pemeliharaan (maintenance) kode. Jika ada bug di bagian Google login, developer dapat fokus langsung pada file `SocialAuthController` tanpa khawatir merusak form login tradisional.
- Menerapkan prinsip *Single Responsibility Principle* (SRP) di mana satu controller hanya bertanggung jawab mengelola satu spesifik flow.

**Dampak:**
- Kode lebih modular, ringkas, dan mudah dibaca.
- Penamaan routing menjadi lebih rapi (`/auth/google/redirect`).
