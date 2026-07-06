<?php
require 'koneksi.php';
require_login();

$user_id = $_SESSION['user_id'];

$stats = [
    'total_recordings' => 0,
    'total_transcripts' => 0,
    'total_duration' => 0,
    'activity_this_week' => 0
];

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM recordings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stats['total_recordings'] = $result->fetch_assoc()['count'];

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM transcripts t JOIN recordings r ON t.recording_id = r.id WHERE r.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stats['total_transcripts'] = $result->fetch_assoc()['count'];

$stmt = $conn->prepare("SELECT COALESCE(SUM(duration), 0) as total FROM recordings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stats['total_duration'] = $result->fetch_assoc()['total'];

$week_ago = date('Y-m-d H:i:s', strtotime('-7 days'));
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM activity_logs WHERE user_id = ? AND created_at >= ?");
$stmt->bind_param("is", $user_id, $week_ago);
$stmt->execute();
$result = $stmt->get_result();
$stats['activity_this_week'] = $result->fetch_assoc()['count'];

$stmt = $conn->prepare("SELECT * FROM recordings WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_recordings = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SpeechSign AI</title>
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
            <h1 class="page-title">Dashboard</h1>
            <div></div>
        </header>

        <main class="content">
            <div class="grid grid-4" style="margin-bottom: 1.5rem;">
                <div class="stats-card">
                    <div>
                        <p class="stats-label">Total Rekaman</p>
                        <p class="stats-value"><?php echo $stats['total_recordings']; ?></p>
                    </div>
                    <div class="stats-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                    </div>
                </div>

                <div class="stats-card">
                    <div>
                        <p class="stats-label">Total Transkripsi</p>
                        <p class="stats-value"><?php echo $stats['total_transcripts']; ?></p>
                    </div>
                    <div class="stats-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="stats-card">
                    <div>
                        <p class="stats-label">Total Durasi</p>
                        <p class="stats-value"><?php echo gmdate('H:i:s', $stats['total_duration']); ?></p>
                    </div>
                    <div class="stats-icon orange">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="stats-card">
                    <div>
                        <p class="stats-label">Aktivitas Minggu Ini</p>
                        <p class="stats-value"><?php echo $stats['activity_this_week']; ?></p>
                    </div>
                    <div class="stats-icon pink">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid" style="grid-template-columns: 1fr;">
                <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                    <div class="card">
                        <h3 style="font-weight: 600; font-size: 1.125rem; margin-bottom: 1rem;">Aksi Cepat</h3>
                        <div class="quick-actions">
                            <a href="<?php echo base_url('recordings-create.php'); ?>" class="quick-action blue">
                                <div class="quick-action-icon blue">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                    </svg>
                                </div>
                                <div class="quick-action-text">
                                    <p>Rekam Baru</p>
                                    <p>Mulai merekam suara</p>
                                </div>
                            </a>
                            <a href="<?php echo base_url('sign-language.php'); ?>" class="quick-action purple">
                                <div class="quick-action-icon purple">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path>
                                    </svg>
                                </div>
                                <div class="quick-action-text">
                                    <p>Bahasa Isyarat</p>
                                    <p>Deteksi bahasa isyarat</p>
                                </div>
                            </a>
                            <a href="<?php echo base_url('conversation.php'); ?>" class="quick-action green">
                                <div class="quick-action-icon green">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <div class="quick-action-text">
                                    <p>Komunikasi</p>
                                    <p>Komunikasi dua arah</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                            <h3 style="font-weight: 600; font-size: 1.125rem;">Rekaman Terbaru</h3>
                            <a href="<?php echo base_url('recordings.php'); ?>" style="color: #3b82f6; text-decoration: none; font-weight: 500; font-size: 0.875rem;">Lihat Semua</a>
                        </div>
                        
                        <?php if ($recent_recordings->num_rows > 0): ?>
                            <div class="recording-list">
                                <?php while ($recording = $recent_recordings->fetch_assoc()): ?>
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
                                                <p><?php echo date('d M Y H:i', strtotime($recording['created_at'])); ?> · <?php echo $recording['duration'] ? gmdate('i:s', $recording['duration']) : '-'; ?></p>
                                            </div>
                                        </div>
                                        <div class="recording-meta">
                                            <span class="status-badge status-<?php echo $recording['status']; ?>">
                                                <?php echo ucfirst($recording['status']); ?>
                                            </span>
                                            <div class="recording-actions">
                                                <a href="<?php echo base_url('recordings-show.php?id=' . $recording['id']); ?>">
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
                                <a href="<?php echo base_url('recordings-create.php'); ?>" style="color: #3b82f6; text-decoration: none; font-weight: 500;">Buat rekaman pertama Anda</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="<?php echo asset_url('js/script.js'); ?>"></script>
</body>
</html>
