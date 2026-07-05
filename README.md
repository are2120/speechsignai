# SpeechSign AI - PHP Native for Vercel

Aplikasi untuk transkripsi suara, ringkasan otomatis, dan deteksi bahasa isyarat. **Sudah 100% compatible Vercel!**

---

## 📋 Persyaratan Sistem
- PHP 7.4 atau lebih baru
- MySQL/MariaDB (remote untuk Vercel)
- Web server (Apache/Nginx/XAMPP/WAMP) untuk lokal

---

## 📝 Environment Variables

### Lokal (Development)
Untuk menjalankan di lokal, buat file `.env` (copy dari `.env.example`):
1. Copy file `.env.example` menjadi `.env`
2. Isi sesuai konfigurasi database lokal Anda:
   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=speechsign_ai
   ```

### Vercel (Production)
Di Vercel, tambahkan Environment Variables di **Settings > Environment Variables:
1. `DB_HOST`: Host database remote Anda (contoh dari PlanetScale/Railway)
2. `DB_USER`: Username database
3. `DB_PASS`: Password database
4. `DB_NAME`: Nama database

---

## 🚀 Cara Deploy ke Vercel (LENGKAP & PERMANEN!)

### 1. Siapkan Database Remote
Vercel tidak menyediakan database native. Buat database di platform berikut (GRATIS!):
- **PlanetScale** (Free) - [planetscale.com](https://planetscale.com/)
- **Railway** (Free) - [railway.app](https://railway.app/)
- **Supabase** (Free) - [supabase.com](https://supabase.com/)

Setelah database siap:
1. Import `database.sql` ke database remote tersebut
2. Catat kredensial database Anda: `host`, `username`, `password`, `database name`

### 2. Deploy ke Vercel via GitHub
1. **Push proyek ke GitHub** (pastikan semua file termasuk di commit!)
2. Buka [vercel.com](https://vercel.com/), login, lalu klik **Add New > Project**
3. Pilih repositori Anda, lalu klik **Deploy**
4. Tunggu deploy selesai (walaupun tanpa environment variables dulu tidak apa-apa)

### 3. Tambahkan Environment Variables di Vercel
1. Di dashboard proyek Vercel, klik **Settings > Environment Variables**
2. Tambahkan variabel berikut (satu per satu):
   - `DB_HOST`: Isi dengan hostname database remote Anda
   - `DB_USER`: Username database Anda
   - `DB_PASS`: Password database Anda
   - `DB_NAME`: Nama database Anda
3. Klik **Save**, lalu **Redeploy** proyek!

### 4. Akses Aplikasi!
Aplikasi Anda sudah LIVE di domain Vercel! ✨

---

## 🏠 Jalankan di Lokal (Untuk Development)

1. **Import Database**: Buat database `speechsign_ai` di lokal lalu import `database.sql`
2. **Jalankan Web Server**: Letakkan semua file di `htdocs` (XAMPP) / `www` (WAMP)
3. **Akses**: Buka browser → `http://localhost/speechsign-ai/`

---

## 📂 Struktur Proyek (Sesuai Standar Vercel)
```
Speech-signAI/
├── api/                    <-- Semua file PHP & Logic disini!
│   ├── index.php           <-- Router utama untuk Vercel
│   ├── koneksi.php         <-- Koneksi database (support Env Vars)
│   ├── index.php           <-- Landing page
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   └── ... (semua file PHP lain)
├── public/                 <-- Semua file STATIC (css, js, dll)
│   └── assets/
│       ├── css/style.css
│       └── js/script.js
├── vercel.json             <-- KONFIGURASI VERCEL (PENTING!)
├── composer.json           <-- Untuk deteksi PHP di Vercel
├── database.sql            <-- Schema database
└── README.md
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
- Menggunakan Prepared Statement (anti SQL Injection)
- Session PHP untuk autentikasi
- Sanitasi input dengan `clean_input()`
- Security headers via `.htaccess`

---

## 📝 Daftar Perubahan yang Dilakukan
Berikut penjelasan detail setiap perubahan untuk Vercel:

1. **Membuat struktur folder `api/` dan `public/`**:
   - `api/`: Tempat semua file PHP dan logic (dijalankan sebagai Vercel Functions)
   - `public/`: Tempat semua file static (CSS, JS, gambar) agar bisa diakses langsung oleh browser

2. **Membuat `api/index.php` (Router Utama)**:
   - Ini adalah entry point untuk SEMUA request ke Vercel
   - Memetakan URL ke file PHP yang sesuai di folder `api/`

3. **Membuat `vercel.json` yang Valid**:
   - Menggunakan runtime `vercel-php@0.7.4` (runtime community yang populer dan stabil)
   - Mengkonfigurasi routing agar file static diakses dari `/assets/`
   - Tidak menggunakan property `match` yang sudah deprecated!

4. **Perbaiki Semua Path Assets**:
   - Menambahkan `/` di depan semua path assets (contoh: `assets/css/style.css` → `/assets/css/style.css`)
   - Agar assets bisa diakses dari direktori mana saja di Vercel

5. **Perbaiki `koneksi.php`**:
   - Mendukung Environment Variables untuk Vercel
   - Fallback ke konfigurasi lokal untuk development

6. **Membuat `composer.json`**:
   - Agar Vercel mendeteksi bahwa ini adalah proyek PHP
