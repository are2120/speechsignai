<?php require 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpeechSign AI - Transkripsi & Bahasa Isyarat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>">
</head>
<body>
    <section class="hero">
        <div class="hero-content">
            <div class="logo" style="justify-content: center; margin-bottom: 1.5rem;">
                <div class="logo-icon" style="width: 4rem; height: 4rem;">
                    <svg width="2rem" height="2rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="hero-title">SpeechSign AI</h1>
            <p class="hero-subtitle">Platform AI untuk transkripsi suara, ringkasan otomatis, dan deteksi bahasa isyarat real-time</p>
            <div class="hero-actions">
                <?php if (is_logged_in()): ?>
                    <a href="<?php echo base_url('dashboard.php'); ?>" class="btn btn-primary">Masuk ke Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo base_url('register.php'); ?>" class="btn btn-primary">Daftar Gratis</a>
                    <a href="<?php echo base_url('login.php'); ?>" class="btn btn-secondary">Masuk</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="features">
        <h2 class="section-title">Fitur Unggulan</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon blue">
                    <svg width="2rem" height="2rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                    </svg>
                </div>
                <h3 class="feature-title">Transkripsi Suara</h3>
                <p class="feature-text">Ubah rekaman suara menjadi teks secara otomatis dengan akurasi tinggi</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon purple">
                    <svg width="2rem" height="2rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path>
                    </svg>
                </div>
                <h3 class="feature-title">Deteksi Bahasa Isyarat</h3>
                <p class="feature-text">Deteksi gerakan tangan untuk mengenali bahasa isyarat secara real-time</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon green">
                    <svg width="2rem" height="2rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="feature-title">Komunikasi Dua Arah</h3>
                <p class="feature-text">Facilitasi komunikasi antara pengguna dengan kemampuan berbicara dan tuli</p>
            </div>
        </div>
    </section>

    <script src="<?php echo asset_url('js/script.js'); ?>"></script>
</body>
</html>
