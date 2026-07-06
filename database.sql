-- =============================================
-- SpeechSign AI Database
-- Kompatibel dengan FreeSQLDatabase (MySQL Lama) & phpMyAdmin Terbaru
-- =============================================

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    email VARCHAR(191) NOT NULL,
    email_verified_at DATETIME NULL DEFAULT NULL,
    password VARCHAR(191) NOT NULL,
    remember_token VARCHAR(100) NULL DEFAULT NULL,
    role ENUM('user', 'premium', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tambahkan index untuk email secara terpisah (hindari error #1071)
CREATE INDEX idx_users_email ON users(email);

-- Tabel password_reset_tokens
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(191) NOT NULL,
    token VARCHAR(191) NOT NULL,
    created_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabel recordings
CREATE TABLE IF NOT EXISTS recordings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(191) NOT NULL,
    description TEXT NULL DEFAULT NULL,
    file_path VARCHAR(191) NULL DEFAULT NULL,
    file_type VARCHAR(50) NULL DEFAULT NULL,
    language VARCHAR(10) DEFAULT 'id',
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    duration INT NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabel transcripts
CREATE TABLE IF NOT EXISTS transcripts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recording_id INT NOT NULL,
    content LONGTEXT NULL DEFAULT NULL,
    segments TEXT NULL DEFAULT NULL, -- Ganti JSON jadi TEXT untuk kompatibilitas MySQL lama
    keywords TEXT NULL DEFAULT NULL, -- Ganti JSON jadi TEXT untuk kompatibilitas MySQL lama
    sentiment VARCHAR(50) NULL DEFAULT NULL,
    language VARCHAR(10) DEFAULT 'id',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (recording_id) REFERENCES recordings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabel summaries
CREATE TABLE IF NOT EXISTS summaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recording_id INT NOT NULL,
    summary TEXT NULL DEFAULT NULL,
    key_points TEXT NULL DEFAULT NULL,
    action_items TEXT NULL DEFAULT NULL,
    meeting_notes TEXT NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (recording_id) REFERENCES recordings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Tabel activity_logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(191) NOT NULL,
    details TEXT NULL DEFAULT NULL,
    ip_address VARCHAR(45) NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
