# PEMIRA 2025  
Sistem Pemilihan Raya Primakara University

## Deskripsi Singkat
**PEMIRA 2025** adalah sistem e-voting berbasis web yang digunakan untuk pelaksanaan Pemilihan Raya (PEMIRA) di Primakara University. Aplikasi ini memungkinkan mahasiswa memberikan suara secara digital untuk memilih Ketua dan Wakil Ketua dari berbagai organisasi kemahasiswaan, seperti:

- DPM (Dewan Perwakilan Mahasiswa)
- BEM (Badan Eksekutif Mahasiswa)
- HIMA (Himpunan Mahasiswa)

Sistem dikembangkan menggunakan **Laravel** sebagai backend dan **Inertia.js + Vue.js** sebagai frontend untuk menghadirkan pengalaman aplikasi yang modern, cepat, dan responsif.

---

## Fitur Utama
- Autentikasi mahasiswa
- Manajemen organisasi (DPM, BEM, HIMA)
- Manajemen kandidat
- Proses voting aman dan terenkripsi
- Dashboard rekapitulasi suara
- Role-based access control (Admin, Panitia, Pemilih)
- Log aktivitas dan tracking

---

## Teknologi yang Digunakan
- Laravel 11+
- Inertia.js
- Vue.js 3 (Composition API)
- Tailwind CSS
- MySQL / MariaDB
- Vite

---

## Persyaratan Sistem
- PHP 8.2+
- Composer 2+
- Node.js 18+
- MySQL / MariaDB
- Git

---

## Instalasi & Setup

### 1. Clone Repository
```bash
git clone https://github.com/your-repo/pemira-2025.git
cd pemira-2025
```

### 2. Install Dependency PHP
```bash
composer install
```

### 3. Copy File Environtment
```bash
cp .env.example .env
```

### 4. Generate Key Laravel
```bash
php artisan key:generate
```

### 5. Konfigurasi Database
```bash
DB_DATABASE=pemira2025
DB_USERNAME=root
DB_PASSWORD=yourpassword

GOOGLE_CLIENT_ID=""
GOOGLE_CLIENT_SECRET=""
```

### 6. Migrasi Database
```bash
php artisan migrate
```

### 7. Seeding Database 
```bash
php artisan db:seed
```

### 8. Storage Link
```bash
php artisan storage:link
```

### 9. Install Dependency Frontend
```bash
npm install
```

### 10. Jalankan Vite
```bash
npm run dev
```

### 11. Jalankan Server Laravel
```bash
php artisan serve
```

---

## Kontributor

- PEMIRA 2025
- Primakara University
