<?php
/**
 * StudentHub Configuration
 * Update DB credentials for your XAMPP/local environment
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // default XAMPP password is empty
define('DB_NAME', 'studenthub');

define('SITE_NAME', 'StudentHub');
define('SITE_URL', 'http://localhost/StudentHub'); // change if using virtual host

// Session timeout in seconds (30 minutes)
define('SESSION_TIMEOUT', 1800);

// Upload settings
define('UPLOAD_DIR', __DIR__ . '/../uploads/posters/');
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024); // 2 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// Start session securely
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Strict');
    session_start();
}
