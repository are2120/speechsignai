<?php
// WAJIB berada di baris pertama, sebelum ada output HTML atau spasi kosong
session_start();

require 'api/koneksi.php';

$error = '';

// Jika pengguna sudah login, langsung tendang ke dashboard (mencegah akses halaman login berulang)
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi koneksi database secara ketat, bukan dengan bypass login yang berbahaya
    if ($conn->connect_error) {
        $error = 'Sistem sedang mengalami gangguan koneksi database. Silakan coba lagi nanti.';
    } else {
        $email = clean_input($_POST['email']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verifikasi kecocokan hash password
            if (password_verify($password, $user['password'])) {
                // Set session data untuk autentikasi global
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                
                // Eksekusi pencatatan log aktivitas
                $stmt_log = $conn->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, 'login', ?)");
                $stmt_log->bind_param("is", $user['id'], $_SERVER['REMOTE_ADDR']);
                $stmt_log->execute();

                // Redirect ke dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Password salah';
            }
        } else {
            $error = 'Email tidak terdaftar';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SpeechSign AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo" style="justify-content: center; margin-bottom: 1rem;">
                    <div class="logo-icon">
                        <svg width="1.5rem" height="1.5rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                    </div>
                    <span class="logo-text">SpeechSign AI</span>
                </div>
                <h1 class="auth-title">Masuk ke Akun</h1>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk</button>
            </form>

            <div class="auth-link">
                <p>Belum punya akun? <a href="<?php echo base_url('register.php'); ?>">Daftar</a></p>
            </div>
        </div>
    </div>

    <script src="<?php echo asset_url('js/script.js'); ?>"></script>
</body>
</html>
