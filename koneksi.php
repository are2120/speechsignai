<?php
// Ensure no output before session_start()
ob_start();
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

$conn = @new mysqli($host, $user, $pass, $dbname);

/**
 * Helper untuk mendapatkan base URL secara dinamis
 * Berjalan di localhost (subfolder) dan Vercel (root)
 */
function base_url($path = '') {
    // Check if we're on Vercel
    if (getenv('VERCEL') === '1' || !empty(getenv('VERCEL_URL'))) {
        $protocol = 'https';
        $host = getenv('VERCEL_URL') ?: $_SERVER['HTTP_HOST'];
        $base = $protocol . '://' . $host;
    } else {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $script = $_SERVER['SCRIPT_NAME'];
        $dir = dirname($script);
        $base = rtrim("$protocol://$host$dir", '/');
    }
    
    return $base . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Helper untuk asset URL
 */
function asset_url($path) {
    return base_url('assets/' . ltrim($path, '/'));
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
    
    // If DB connection fails, return a default user from session
    if ($conn->connect_error) {
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? 'User',
            'email' => 'user@example.com',
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>