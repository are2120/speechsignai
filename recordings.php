<?php
require 'koneksi.php';
require_login();

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM recordings WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recordings = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekaman - SpeechSign AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
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
            <h1 class="page-title">Rekaman</h1>
            <a href="<?php echo base_url('recordings-create.php'); ?>" class="btn btn-primary">+ Rekam Baru</a>
        </header>

        <main class="content">
            <div class="card">
                <?php if ($recordings->num_rows > 0): ?>
                    <div class="recording-list">
                        <?php while ($recording = $recordings->fetch_assoc()): ?>
                            <div class="recording-item">
                                <div class="recording-info">
                                    <div class="recording-icon <?php echo $recording['file_type'] === 'video' ? 'video' : 'audio'; ?>">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <?php if ($recording['file_type'] === 'video'): ?>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            <?php else: ?>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                            <?php endif; ?>
                                        </svg>
                                    </div>
                                    <div class="recording-details">
                                        <p><?php echo htmlspecialchars($recording['title']); ?></p>
                                        <p><?php echo htmlspecialchars($recording['description'] ?: 'Tidak ada deskripsi'); ?></p>
                                        <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;"><?php echo date('d M Y H:i', strtotime($recording['created_at'])); ?></p>
                                    </div>
                                </div>
                                <div class="recording-meta">
                                    <span class="status-badge status-<?php echo $recording['status']; ?>">
                                        <?php echo ucfirst($recording['status']); ?>
                                    </span>
                                    <div class="recording-actions">
                                        <a href="<?php echo base_url('recordings-show.php?id=' . $recording['id']); ?>" style="padding: 0.5rem;">
                                            <svg width="1.25rem" height="1.25rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                        </div>
                        <p class="empty-text">Belum ada rekaman</p>
                        <a href="<?php echo base_url('recordings-create.php'); ?>" class="btn btn-primary" style="margin-top: 1rem;">Buat Rekaman Pertama</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="<?php echo asset_url('js/script.js'); ?>"></script>
</body>
</html>
