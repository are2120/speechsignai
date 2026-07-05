# SpeechSign AI - PHP Native

Aplikasi untuk transkripsi suara, ringkasan otomatis, dan deteksi bahasa isyarat.

## 📋 Persyaratan Sistem
- PHP 7.4 atau lebih baru
- MySQL/MariaDB
- Web server (Apache/Nginx/XAMPP/WAMP)

---

## 🚀 Instalasi & Jalankan di Lokal

### 1. Import Database
1. Buka phpMyAdmin atau tool MySQL lainnya
2. Buat database baru bernama `speechsign_ai`
3. Import file `database.sql` ke database tersebut

### 2. Konfigurasi Koneksi
File `koneksi.php` sudah dikonfigurasi default untuk XAMPP:
```php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'speechsign_ai';
```
Edit sesuai konfigurasi database lokal Anda jika perlu.

### 3. Jalankan Aplikasi
Letakkan semua file di direktori `htdocs` (XAMPP) atau `www` (WAMP), lalu akses di browser:
`http://localhost/speechsign-ai/`

---

## 🌐 Cara Deploy ke Hosting (Realistis & Mudah!)
**Vercel tidak mensupport PHP native secara official.** Gunakan hosting PHP berikut (banyak yang FREE):

### Pilihan Hosting PHP Free / Murah
1. **InfinityFree** (Free) - [infinityfree.net](https://infinityfree.net/)
2. **000webhost** (Free) - [000webhost.com](https://www.000webhost.com/)
3. **Hostinger** (Murah) - [hostinger.com](https://www.hostinger.com/)

### Langkah Deploy
1. **Buat Akun & Database di Hosting**
   - Buat database baru di hosting
   - Import `database.sql` ke database hosting
   - Catat: hostname, username, password, nama database

2. **Edit Konfigurasi `koneksi.php`**
   Ubah sesuai kredensial database hosting Anda:
   ```php
   $host = 'hostname_database_hosting';
   $user = 'username_database';
   $pass = 'password_database';
   $dbname = 'nama_database';
   ```

3. **Upload Semua File ke Hosting**
   - Upload SEMUA file ke direktori `public_html` (atau `htdocs`) di hosting
   - File `index.php` harus berada di direktori root!

4. **Akses Aplikasi!**
   Buka domain hosting Anda → aplikasi Anda live! ✨

---

## 📂 Struktur File
```
.
├── assets/              # File statis (css, js, images)
│   ├── css/style.css
│   └── js/script.js
├── .htaccess            # Konfigurasi security & caching (untuk Apache)
├── .gitignore          # File yang tidak di-commit ke git
├── database.sql        # Schema database
├── koneksi.php       # Koneksi database & helper
├── index.php         # Halaman landing
├── login.php         # Login user
├── register.php      # Registrasi user
├── logout.php        # Logout
├── dashboard.php     # Dashboard utama
├── recordings.php   # Daftar rekaman
├── recordings-create.php # Buat rekaman baru
├── recordings-show.php  # Detail rekaman
├── sign-language.php # Deteksi bahasa isyarat
├── conversation.php # Komunikasi dua arah
├── profile.php        # Profile user
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
- Menggunakan Prepared Statement untuk mencegah SQL Injection
- Session PHP untuk autentikasi
- Sanitasi input dengan `clean_input()`
- Security headers via .htaccess
