<?php
require_once 'includes/auth.php';

if (is_logged_in()) {
    $uid = $_SESSION['user_id'];
    $action = 'LOGOUT';
    $details = 'User logged out';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('isss', $uid, $action, $details, $ip);
    $stmt->execute();
}

logout_user();
flash('success', 'You have been logged out successfully.');
header('Location: login.php');
exit;
