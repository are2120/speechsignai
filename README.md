# SpeechSign AI - PHP Native

Aplikasi untuk transkripsi suara, ringkasan otomatis, dan deteksi bahasa isyarat.

## 📋 Persyaratan Sistem
- PHP 7.4 atau lebih baru
- MySQL/MariaDB
- Web server (Apache/Nginx/XAMPP/WAMP)

## 🚀 Cara Instalasi & Menjalankan

### 1. Import Database
1. Buka phpMyAdmin atau tool MySQL lainnya
2. Buat database baru bernama `speechsign_ai`
3. Import file `database.sql` ke database tersebut

### 2. Konfigurasi Koneksi Database
Edit file `koneksi.php` jika diperlukan (default cocok untuk XAMPP):
```php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'speechsign_ai';
```

### 3. Letakkan File di Web Server
Letakkan semua file di direktori web server (misal `htdocs` untuk XAMPP).

### 4. Akses Aplikasi
Buka browser dan akses: `http://localhost/nama-folder-proyek/`

## 📂 Struktur File
```
.
├── assets/              # File statis (css, js, images)
│   ├── css/style.css
│   └── js/script.js
├── .htaccess            # Konfigurasi security & caching
├── database.sql         # Schema database
├── koneksi.php          # Koneksi database & helper
├── index.php            # Halaman landing
├── login.php            # Login user
├── register.php         # Registrasi user
├── logout.php           # Logout
├── dashboard.php        # Dashboard utama
├── recordings.php       # Daftar rekaman
├── recordings-create.php # Buat rekaman baru
├── recordings-show.php  # Detail rekaman
├── sign-language.php    # Deteksi bahasa isyarat
├── conversation.php     # Komunikasi dua arah
└── profile.php          # Profile user
```

## ✨ Fitur Utama
1. Autentikasi User (Login & Register)
2. Dashboard & Statistik
3. Perekaman Suara
4. Deteksi Bahasa Isyarat (MediaPipe)
5. Speech to Text & Text to Speech
6. Manajemen Profile

## 🛡️ Keamanan
- Menggunakan Prepared Statement untuk mencegah SQL Injection
- Session PHP untuk autentikasi
- Sanitasi input dengan `clean_input()`
- Security headers via .htaccess
