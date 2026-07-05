<?php
require 'koneksi.php';
require_login();

$user = get_user();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_input($_POST['name']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $user_id);

    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name;
        $success = 'Profile berhasil diperbarui!';
        $user = get_user();
    } else {
        $error = 'Gagal memperbarui profile: ' . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - SpeechSign AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <button id="open-sidebar" class="sidebar-toggle">
                <svg width="1.5rem" height="1.5rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="page-title">Profile</h1>
            <div></div>
        </header>

        <main class="content">
            <div class="card" style="max-width: 600px;">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #8b5cf6); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <span style="color: white; font-size: 2.5rem; font-weight: 700;"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></span>
                    </div>
                    <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background: #e5e7eb; color: #4b5563;">
                        <?php echo ucfirst($user['role']); ?>
                    </span>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama</label>
                        <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background: #f3f4f6;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bergabung Sejak</label>
                        <input type="text" class="form-input" value="<?php echo date('d M Y', strtotime($user['created_at'])); ?>" disabled style="background: #f3f4f6;">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Perubahan</button>
                </form>

                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                    <a href="logout.php" class="btn btn-danger" style="width: 100%;">Keluar</a>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
