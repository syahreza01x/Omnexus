# Interco

## Persyaratan

Pastikan sudah menginstall:

- [PHP](https://www.php.net/) >= 8.2
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) >= 18
- [MySQL](https://www.mysql.com/)
- [Laragon](https://laragon.org/) (opsional, untuk kemudahan setup lokal di Windows)

## Cara Menjalankan Project

### 1. Clone repository

```bash
git clone <url-repository> PBL
cd PBL
```

### 2. Install dependency PHP

```bash
composer install
```

### 3. Install dependency Node.js

```bash
npm install
```

### 4. Setup file environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Konfigurasi database

Buat database MySQL dengan nama `PBL`, lalu sesuaikan konfigurasi di file `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PBL
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Jalankan migrasi database

```bash
php artisan migrate
```

### 8. Jalankan project

Buka **2 terminal** secara bersamaan:

**Terminal 1** — Jalankan server Laravel:

```bash
php artisan serve
```

**Terminal 2** — Jalankan Vite (frontend dev server):

```bash
npm run dev
```

Buka browser dan akses: [http://localhost:8000](http://localhost:8000)

## Build untuk Production

```bash
npm run build
```
