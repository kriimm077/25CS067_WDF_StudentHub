<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$page_title = 'Admin Dashboard';

$stats = [
    'users'    => $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'],
    'students' => $conn->query("SELECT COUNT(*) AS c FROM students")->fetch_assoc()['c'],
    'events'   => $conn->query("SELECT COUNT(*) AS c FROM events")->fetch_assoc()['c'],
    'regs'     => $conn->query("SELECT COUNT(*) AS c FROM registrations")->fetch_assoc()['c'],
];

$recent = $conn->query("SELECT a.*, u.name FROM audit_logs a LEFT JOIN users u ON a.user_id=u.id ORDER BY a.created_at DESC LIMIT 10");

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Admin Dashboard</h2>
    <div class="btn-group">
        <a href="students.php" class="btn btn-outline-primary btn-sm">Students</a>
        <a href="events.php" class="btn btn-outline-primary btn-sm">Events</a>
        <a href="../index.php" class="btn btn-outline-secondary btn-sm">Site</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h3 class="text-primary"><?= $stats['users'] ?></h3>
                <p class="mb-0 small text-muted">Users</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h3 class="text-success"><?= $stats['students'] ?></h3>
                <p class="mb-0 small text-muted">Students</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h3 class="text-warning"><?= $stats['events'] ?></h3>
                <p class="mb-0 small text-muted">Events</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h3 class="text-info"><?= $stats['regs'] ?></h3>
                <p class="mb-0 small text-muted">Registrations</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">Recent Audit Log</div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead><tr><th>Time</th><th>User</th><th>Action</th><th>Details</th><th>IP</th></tr></thead>
            <tbody>
            <?php while ($row = $recent->fetch_assoc()): ?>
                <tr>
                    <td class="small"><?= e($row['created_at']) ?></td>
                    <td><?= e($row['name'] ?? '—') ?></td>
                    <td><span class="badge bg-secondary"><?= e($row['action']) ?></span></td>
                    <td class="small"><?= e($row['details'] ?? '') ?></td>
                    <td class="small"><?= e($row['ip_address'] ?? '') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
