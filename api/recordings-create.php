<?php
require 'koneksi.php';
require_login();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = clean_input($_POST['title']);
    $description = clean_input($_POST['description']);
    $user_id = $_SESSION['user_id'];

    $file_path = null;
    $file_type = 'audio';
    $duration = isset($_POST['duration']) ? (int)$_POST['duration'] : null;

    $stmt = $conn->prepare("INSERT INTO recordings (user_id, title, description, file_path, file_type, duration, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("issssi", $user_id, $title, $description, $file_path, $file_type, $duration);

    if ($stmt->execute()) {
        $recording_id = $conn->insert_id;

        $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, 'create_recording', ?)");
        $stmt->bind_param("is", $user_id, $_SERVER['REMOTE_ADDR']);
        $stmt->execute();

        $success = 'Rekaman berhasil dibuat!';
    } else {
        $error = 'Gagal membuat rekaman: ' . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Baru - SpeechSign AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
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
            <h1 class="page-title">Rekam Baru</h1>
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

                <form method="POST">
                    <div class="form-group">
                        <label class="form-label" for="title">Judul</label>
                        <input type="text" id="title" name="title" class="form-input" required placeholder="Masukkan judul rekaman">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-input" rows="4" placeholder="Deskripsi (opsional)"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rekam Suara</label>
                        <div style="background: #f9fafb; padding: 1.5rem; border-radius: 0.75rem; text-align: center;">
                            <button type="button" id="record-btn" class="btn btn-primary" onclick="toggleRecording()">
                                <svg width="1.25rem" height="1.25rem" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                </svg>
                                Mulai Merekam
                            </button>
                            <input type="hidden" id="duration" name="duration" value="0">
                            <audio id="audio-preview" style="display: none; margin-top: 1rem; width: 100%;" controls></audio>
                            <p id="recording-status" style="margin-top: 1rem; color: #6b7280; font-size: 0.875rem;"></p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <a href="recordings.php" class="btn btn-secondary" style="flex: 1;">Batal</a>
                        <button type="submit" class="btn btn-primary" style="flex: 1;">Simpan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>
