<?php
require 'api/koneksi.php';
require_login();

$user_id = $_SESSION['user_id'];
$recording_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM recordings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $recording_id, $user_id);
$stmt->execute();
$recording = $stmt->get_result()->fetch_assoc();

if (!$recording) {
    header('Location: ' . base_url('recordings.php'));
    exit;
}

$transcript = null;
$stmt = $conn->prepare("SELECT * FROM transcripts WHERE recording_id = ?");
$stmt->bind_param("i", $recording_id);
$stmt->execute();
$transcript = $stmt->get_result()->fetch_assoc();

$summary = null;
$stmt = $conn->prepare("SELECT * FROM summaries WHERE recording_id = ?");
$stmt->bind_param("i", $recording_id);
$stmt->execute();
$summary = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recording['title']); ?> - SpeechSign AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <button id="open-sidebar" class="sidebar-toggle">
                <svg width="1.5rem" height="1.5rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="page-title"><?php echo htmlspecialchars($recording['title']); ?></h1>
            <div></div>
        </header>

        <main class="content">
            <div class="grid" style="grid-template-columns: 1fr; gap: 1.5rem;">
                <div class="card">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1rem;">
                        <div>
                            <span class="status-badge status-<?php echo $recording['status']; ?>">
                                <?php echo ucfirst($recording['status']); ?>
                            </span>
                            <p style="margin-top: 0.5rem; color: #6b7280; font-size: 0.875rem;">
                                Dibuat: <?php echo date('d M Y H:i', strtotime($recording['created_at'])); ?>
                            </p>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?php echo base_url('recordings.php'); ?>" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                    
                    <?php if ($recording['description']): ?>
                        <p style="color: #4b5563; margin-bottom: 1rem;"><?php echo htmlspecialchars($recording['description']); ?></p>
                    <?php endif; ?>
                </div>

                <?php if ($transcript): ?>
                    <div class="card">
                        <h3 style="font-weight: 600; margin-bottom: 1rem;">Transkripsi</h3>
                        <div style="background: #f9fafb; padding: 1rem; border-radius: 0.5rem; white-space: pre-wrap;">
                            <?php echo htmlspecialchars($transcript['content']); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($summary): ?>
                    <div class="card">
                        <h3 style="font-weight: 600; margin-bottom: 1rem;">Ringkasan</h3>
                        <div style="background: #f9fafb; padding: 1rem; border-radius: 0.5rem; white-space: pre-wrap;">
                            <?php echo htmlspecialchars($summary['summary']); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="<?php echo asset_url('js/script.js'); ?>"></script>
</body>
</html>
