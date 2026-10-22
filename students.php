<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$page_title = 'Manage Students';

// Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM students WHERE id = $id");
    flash('success', 'Student deleted.');
    header('Location: students.php');
    exit;
}

// Search / filter
$q = trim($_GET['q'] ?? '');
$sql = "SELECT * FROM students WHERE 1=1";
if ($q !== '') {
    $sql .= " AND (full_name LIKE ? OR email LIKE ? OR course LIKE ? OR enrollment_no LIKE ?)";
    $like = "%$q%";
    $stmt = $conn->prepare($sql . " ORDER BY created_at DESC");
    $stmt->bind_param('ssss', $like, $like, $like, $like);
    $stmt->execute();
    $students = $stmt->get_result();
} else {
    $students = $conn->query($sql . " ORDER BY created_at DESC");
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Student Management</h2>
    <a href="student_form.php" class="btn btn-primary btn-sm">+ Add Student</a>
</div>

<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search name, email, course, enrollment..." value="<?= e($q) ?>">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </div>
</form>

<div class="table-responsive card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Year</th><th>Enrollment</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($s = $students->fetch_assoc()): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= e($s['full_name']) ?></td>
                <td><?= e($s['email']) ?></td>
                <td><?= e($s['course']) ?></td>
                <td><?= $s['year'] ?></td>
                <td><?= e($s['enrollment_no'] ?? '—') ?></td>
                <td><span class="badge bg-<?= $s['status']==='active'?'success':'secondary' ?>"><?= e($s['status']) ?></span></td>
                <td>
                    <a href="student_form.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <a href="?delete=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this student?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<p class="mt-3"><a href="index.php">&larr; Back to Dashboard</a></p>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
