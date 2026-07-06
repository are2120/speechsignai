<?php
require 'koneksi.php';

if (is_logged_in()) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (?, 'logout', ?)");
    $stmt->bind_param("is", $user_id, $_SERVER['REMOTE_ADDR']);
    $stmt->execute();
}

session_destroy();
header('Location: index.php');
exit;
