# SpeechSign AI - PHP Native

Aplikasi untuk transkripsi suara, ringkasan otomatis, dan deteksi bahasa isyarat.

## 📋 Persyaratan Sistem
- PHP 7.4 atau lebih baru
- MySQL/MariaDB (remote untuk production)
- Web server (Apache/Nginx/XAMPP/WAMP) untuk local
- Vercel akun (untuk deploy)

---

## 🚀 Instalasi Lokal

### 1. Import Database
1. Buka phpMyAdmin atau tool MySQL lainnya
2. Buat database baru bernama `speechsign_ai`
3. Import file `database.sql` ke database tersebut

### 2. Konfigurasi Lokal
Untuk development lokal:
- File `koneksi.php` sudah dikonfigurasi default (cocok untuk XAMPP)
- Bisa edit manual jika diperlukan

### 3. Jalankan Aplikasi
Letakkan semua file di direktori `htdocs` (XAMPP), lalu akses di browser: `http://localhost/speechsign-ai/`

---

## 🌐 Deploy ke Vercel

### 1. Siapkan Database Remote (Penting!)
Vercel tidak menyediakan database native. Gunakan layanan database MySQL remote:
- **PlanetScale** (free tier): [planetscale.com](https://planetscale.com)
- **Railway** (free tier): [railway.app](https://railway.app)
- **AWS RDS**
- **Supabase (PostgreSQL, tapi bisa dipakai dengan driver MySQL)**

Setelah database remote siap:
1. Import `database.sql` ke database remote
2. Catat kredensial: `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`

### 2. Siapkan Proyek di Vercel
1. **Push proyek ke GitHub/GitLab/Bitbucket**
2. Buka [vercel.com](https://vercel.com), login, dan klik **New Project**
3. Pilih repositori proyek Anda
4. Klik **Deploy** (Vercel otomatis mendeteksi PHP dari `composer.json` dan `vercel.json`)

### 3. Tambahkan Environment Variables di Vercel
Setelah deploy selesai:
1. Buka dashboard proyek → **Settings** → **Environment Variables**
2. Tambahkan variabel berikut:
   - `DB_HOST`: hostname database remote Anda
   - `DB_USER`: username database
   - `DB_PASS`: password database
   - `DB_NAME`: nama database (misal `speechsign_ai`)
3. Klik **Save** → **Redeploy** untuk menerapkan perubahan

### 4. Akses Aplikasi!
Aplikasi Anda siap diakses di URL yang diberikan Vercel! ✨

---

## 📂 Struktur File
```
.
├── public/             # Semua file web untuk Vercel
│   ├── assets/         # File statis (css, js, images)
│   │   ├── css/style.css
│   │   └── js/script.js
│   ├── koneksi.php     # Koneksi database & helper
│   ├── index.php       # Halaman landing
│   ├── login.php       # Login user
│   ├── register.php    # Registrasi user
│   ├── logout.php      # Logout
│   ├── dashboard.php   # Dashboard utama
│   ├── recordings.php # Daftar rekaman
│   ├── recordings-create.php # Buat rekaman baru
│   ├── recordings-show.php  # Detail rekaman
│   ├── sign-language.php # Deteksi bahasa isyarat
│   ├── conversation.php # Komunikasi dua arah
│   └── profile.php     # Profile user
├── .htaccess           # Konfigurasi security & caching (untuk Apache)
├── .gitignore         # File yang tidak di-commit
├── .env.example       # Template environment variables
├── composer.json      # Konfigurasi PHP untuk Vercel
├── vercel.json        # Konfigurasi routing & build Vercel
└── database.sql       # Schema database
```

---

## ✨ Fitur Utama
1. Autentikasi User (Login & Register)
2. Dashboard & Statistik
3. Perekaman Suara
4. Deteksi Bahasa Isyarat (MediaPipe)
5. Speech to Text & Text to Speech
6. Manajemen Profile

---

## 🛡️ Keamanan
- Menggunakan Prepared Statement untuk mencegah SQL Injection
- Session PHP untuk autentikasi
- Sanitasi input dengan `clean_input()`
- Security headers via .htaccess
- Environment Variables untuk kredensial database
