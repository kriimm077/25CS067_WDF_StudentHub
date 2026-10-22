<?php
/**
 * Database connection using MySQLi (prepared statements)
 */
require_once __DIR__ . '/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

/**
 * Helper: execute a prepared statement and return result or affected rows
 */
function db_query(string $sql, string $types = '', array $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    if ($types && $params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt;
}
