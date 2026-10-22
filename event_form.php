<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$page_title = 'Event Form';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = null;
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date  = $_POST['event_date'] ?? '';
    $location    = trim($_POST['location'] ?? '');
    $category    = trim($_POST['category'] ?? 'General');
    $status      = $_POST['status'] ?? 'open';
    $poster_name = $event['poster'] ?? null;

    if ($title === '') $errors[] = 'Title required.';
    if ($event_date === '') $errors[] = 'Date required.';

    // File upload validation
    if (!empty($_FILES['poster']['name'])) {
        $file = $_FILES['poster'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload error.';
        } elseif ($file['size'] > MAX_UPLOAD_SIZE) {
            $errors[] = 'File too large (max 2MB).';
        } elseif (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
            $errors[] = 'Invalid file type. Use JPG, PNG, WEBP or GIF.';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $poster_name = 'event_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
            if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $poster_name)) {
                $errors[] = 'Failed to save uploaded file.';
                $poster_name = $event['poster'] ?? null;
            } else {
                // remove old poster
                if ($event && $event['poster'] && file_exists(UPLOAD_DIR . $event['poster'])) {
                    unlink(UPLOAD_DIR . $event['poster']);
                }
            }
        }
    }

    if (empty($errors)) {
        $uid = current_user()['id'];
        if ($id && $event) {
            $stmt = $conn->prepare("UPDATE events SET title=?, description=?, event_date=?, location=?, category=?, poster=?, status=? WHERE id=?");
            $stmt->bind_param('sssssssi', $title, $description, $event_date, $location, $category, $poster_name, $status, $id);
            $stmt->execute();
            flash('success', 'Event updated.');
        } else {
            $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location, category, poster, status, created_by) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param('sssssssi', $title, $description, $event_date, $location, $category, $poster_name, $status, $uid);
            $stmt->execute();
            flash('success', 'Event created.');
        }
        // Audit
        $action = $id ? 'EVENT_UPDATE' : 'EVENT_CREATE';
        $details = ($id ? "Updated" : "Created") . " event: $title";
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $stmt2 = $conn->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?,?,?,?)");
        $stmt2->bind_param('isss', $uid, $action, $details, $ip);
        $stmt2->execute();

        header('Location: events.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<h2 class="mb-4"><?= $id ? 'Edit' : 'Add' ?> Event</h2>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
    <div class="mb-3">
        <label class="form-label">Title *</label>
        <input type="text" name="title" class="form-control" required value="<?= e($event['title'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"><?= e($event['description'] ?? '') ?></textarea>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Date *</label>
            <input type="date" name="event_date" class="form-control" required value="<?= e($event['event_date'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="<?= e($event['location'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="<?= e($event['category'] ?? 'General') ?>">
        </div>
    </div>
    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <?php foreach (['open','closed','cancelled'] as $st): ?>
                    <option value="<?= $st ?>" <?= ($event['status']??'open')===$st?'selected':'' ?>><?= ucfirst($st) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Poster (JPG/PNG, max 2MB)</label>
            <input type="file" name="poster" class="form-control" accept="image/*">
            <?php if (!empty($event['poster'])): ?>
                <div class="form-text">Current: <?= e($event['poster']) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Save Event</button>
        <a href="events.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
