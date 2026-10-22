<?php
/**
 * Simple JSON API for events (Practical 13)
 * GET  /api/events.php          → list
 * GET  /api/events.php?id=1     → single
 * POST /api/events.php          → create (admin, JSON body)
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../includes/auth.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $conn->prepare("SELECT id, title, description, event_date, location, category, status, poster FROM events WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) {
            echo json_encode(['success' => true, 'data' => $row]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Event not found']);
        }
    } else {
        $q = trim($_GET['q'] ?? '');
        $sql = "SELECT id, title, description, event_date, location, category, status, poster FROM events WHERE 1=1";
        if ($q !== '') {
            $sql .= " AND (title LIKE ? OR category LIKE ?)";
            $like = "%$q%";
            $stmt = $conn->prepare($sql . " ORDER BY event_date ASC");
            $stmt->bind_param('ss', $like, $like);
            $stmt->execute();
            $res = $stmt->get_result();
        } else {
            $res = $conn->query($sql . " ORDER BY event_date ASC");
        }
        $data = [];
        while ($row = $res->fetch_assoc()) $data[] = $row;
        echo json_encode(['success' => true, 'count' => count($data), 'data' => $data]);
    }
    exit;
}

if ($method === 'POST') {
    // Require admin for create
    if (!is_logged_in() || current_user()['role'] !== 'admin') {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $title = trim($input['title'] ?? '');
    $event_date = $input['event_date'] ?? '';
    $description = trim($input['description'] ?? '');
    $location = trim($input['location'] ?? '');
    $category = trim($input['category'] ?? 'General');
    $status = $input['status'] ?? 'open';

    if ($title === '' || $event_date === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'title and event_date required']);
        exit;
    }
    $uid = current_user()['id'];
    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location, category, status, created_by) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssssi', $title, $description, $event_date, $location, $category, $status, $uid);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id, 'message' => 'Event created']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed']);
