<?php
require_once 'includes/auth.php';
require_login();
$user = current_user();
$page_title = 'Dashboard';

// Stats for student
$student = null;
$stmt = $conn->prepare("SELECT * FROM students WHERE user_id = ? LIMIT 1");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$reg_count = 0;
if ($student) {
    $sid = $student['id'];
    $r = $conn->query("SELECT COUNT(*) AS c FROM registrations WHERE student_id = $sid");
    $reg_count = $r->fetch_assoc()['c'] ?? 0;
}

$event_count = $conn->query("SELECT COUNT(*) AS c FROM events WHERE status='open'")->fetch_assoc()['c'] ?? 0;

require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Welcome, <?= e($user['name']) ?>!</h2>
    <span class="badge bg-<?= $user['role']==='admin'?'danger':'primary' ?>"><?= e(ucfirst($user['role'])) ?></span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <h6 class="card-subtitle opacity-75">My Event Registrations</h6>
                <h2 class="mb-0"><?= $reg_count ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <h6 class="card-subtitle opacity-75">Open Events</h6>
                <h2 class="mb-0"><?= $event_count ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <h6 class="card-subtitle opacity-75">Profile Status</h6>
                <h2 class="mb-0"><?= $student ? 'Complete' : 'Incomplete' ?></h2>
            </div>
        </div>
    </div>
</div>

<?php if ($student): ?>
<div class="card shadow-sm mb-4">
    <div class="card-header">My Profile Summary</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Enrollment:</strong> <?= e($student['enrollment_no'] ?? 'N/A') ?></p>
                <p><strong>Course:</strong> <?= e($student['course']) ?></p>
                <p><strong>Year:</strong> <?= e($student['year']) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Email:</strong> <?= e($student['email']) ?></p>
                <p><strong>Mobile:</strong> <?= e($student['mobile'] ?? '—') ?></p>
                <p><strong>Gender:</strong> <?= e($student['gender']) ?></p>
            </div>
        </div>
        <a href="profile.php" class="btn btn-sm btn-outline-primary">Edit Profile</a>
    </div>
</div>
<?php endif; ?>

<div class="d-flex gap-2 flex-wrap">
    <a href="events.php" class="btn btn-primary">Browse Events</a>
    <a href="profile.php" class="btn btn-outline-secondary">My Profile</a>
    <?php if ($user['role'] === 'admin'): ?>
        <a href="admin/index.php" class="btn btn-danger">Admin Panel</a>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
