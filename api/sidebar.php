<?php
require_once 'koneksi.php';
$user = get_user();
if (!$user) {
    header('Location: login.php');
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <a href="dashboard.php" class="logo">
            <div class="logo-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                </svg>
            </div>
            <span class="logo-text">SpeechSign AI</span>
        </a>
        <button id="close-sidebar" class="sidebar-toggle">
            <svg width="1.25rem" height="1.25rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="recordings.php" class="nav-link <?php echo $current_page === 'recordings.php' || $current_page === 'recordings-create.php' || $current_page === 'recordings-show.php' ? 'active' : ''; ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
            </svg>
            <span>Rekaman</span>
        </a>

        <a href="sign-language.php" class="nav-link <?php echo $current_page === 'sign-language.php' || $current_page === 'sign-language-learn.php' ? 'active' : ''; ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path>
            </svg>
            <span>Bahasa Isyarat</span>
        </a>

        <a href="conversation.php" class="nav-link <?php echo $current_page === 'conversation.php' ? 'active' : ''; ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <span>Komunikasi</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
            </div>
            <div class="user-details">
                <p><?php echo htmlspecialchars($user['name']); ?></p>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="profile.php" class="btn btn-secondary" style="flex: 1; padding: 0.5rem 1rem; font-size: 0.875rem;">Profile</a>
            <a href="logout.php" class="btn btn-danger" style="flex: 1; padding: 0.5rem 1rem; font-size: 0.875rem;">Keluar</a>
        </div>
    </div>
</aside>
<div id="sidebar-overlay" class="sidebar-overlay"></div>
