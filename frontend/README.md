# Omnexus Frontend

Frontend dipisah dari Laravel dan memakai React + Vite.

## Menjalankan frontend

1. Install dependency:
   npm install
2. Salin env:
   cp .env.example .env
3. Jalankan:
   npm run dev

## Environment

- VITE_API_BASE_URL: URL backend Laravel API (default: http://localhost:8000)

## Catatan migrasi

- Asset gambar dipindah ke `public/images`.
- Asset frontend Laravel lama dipindah ke `src/legacy` sebagai referensi migrasi komponen.
