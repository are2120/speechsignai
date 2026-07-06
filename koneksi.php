<?php
// Set session save path to a writable directory (for Vercel compatibility)
$sessionSavePath = sys_get_temp_dir() . '/speechsign_sessions';
if (!is_dir($sessionSavePath)) {
    mkdir($sessionSavePath, 0700, true);
}
session_save_path($sessionSavePath);
session_start();

/**
 * Parser file .env sederhana untuk development lokal
 * Kompatibel dengan PHP 7.4+
 */
function load_env($path) {
    if (!file_exists($path)) return;
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $trimmedLine = trim($line);
        if (strpos($trimmedLine, '#') === 0) continue;
        
        $parts = explode('=', $line, 2);
        if (count($parts) < 2) continue;
        
        $key = trim($parts[0]);
        $value = trim($parts[1]);
        
        if (!empty($key) && !getenv($key)) {
            putenv("$key=$value");
        }
    }
}

// Load file .env untuk development lokal
load_env(__DIR__ . '/.env');

// Konfigurasi database - mendukung Environment Variables Vercel dan lokal!
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'speechsign_ai';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

/**
 * Helper untuk mendapatkan base URL secara dinamis
 * Berjalan di localhost (subfolder) dan Vercel (root)
 */
function base_url($path = '') {
    // Deteksi jika di Vercel atau production
    $isVercel = getenv('VERCEL') === '1' || !empty(getenv('VERCEL_URL'));
    
    if ($isVercel) {
        $protocol = 'https';
        $host = getenv('VERCEL_URL') ?: $_SERVER['HTTP_HOST'];
        $base = $protocol . '://' . $host;
    } else {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $script_name = dirname($_SERVER['SCRIPT_NAME']);
        $base = rtrim("$protocol://$host$script_name", '/');
    }
    
    if ($path) {
        return $base . '/' . ltrim($path, '/');
    }
    
    return $base;
}

/**
 * Helper untuk asset URL
 */
function asset_url($path) {
    // Deteksi environment
    $isVercel = getenv('VERCEL') === '1' || !empty(getenv('VERCEL_URL')) || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'vercel.app') !== false);
    
    if ($isVercel) {
        // On Vercel, use absolute path from root
        return '/assets/' . ltrim($path, '/');
    } else {
        // Localhost, use base_url
        return base_url('assets/' . ltrim($path, '/'));
    }
}

function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: ' . base_url('login.php'));
        exit;
    }
}

function get_user() {
    global $conn;
    if (!is_logged_in()) return null;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>