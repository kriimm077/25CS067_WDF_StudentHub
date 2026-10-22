<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$page_title = 'Student Form';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $mobile    = trim($_POST['mobile'] ?? '');
    $course    = trim($_POST['course'] ?? '');
    $year      = (int)($_POST['year'] ?? 1);
    $gender    = $_POST['gender'] ?? 'Other';
    $enrollment_no = trim($_POST['enrollment_no'] ?? '');
    $status    = $_POST['status'] ?? 'active';

    if ($full_name === '') $errors[] = 'Name required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';

    if (empty($errors)) {
        if ($id && $student) {
            $stmt = $conn->prepare("UPDATE students SET full_name=?, email=?, mobile=?, course=?, year=?, gender=?, enrollment_no=?, status=? WHERE id=?");
            $stmt->bind_param('ssssisssi', $full_name, $email, $mobile, $course, $year, $gender, $enrollment_no, $status, $id);
            $stmt->execute();
            flash('success', 'Student updated.');
        } else {
            $stmt = $conn->prepare("INSERT INTO students (full_name, email, mobile, course, year, gender, enrollment_no, status) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param('ssssisss', $full_name, $email, $mobile, $course, $year, $gender, $enrollment_no, $status);
            $stmt->execute();
            flash('success', 'Student added.');
        }
        header('Location: students.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<h2 class="mb-4"><?= $id ? 'Edit' : 'Add' ?> Student</h2>

<?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" class="card shadow-sm p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Full Name *</label>
            <input type="text" name="full_name" class="form-control" required value="<?= e($student['full_name'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" required value="<?= e($student['email'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Mobile</label>
            <input type="text" name="mobile" class="form-control" value="<?= e($student['mobile'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Course</label>
            <input type="text" name="course" class="form-control" value="<?= e($student['course'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Year</label>
            <select name="year" class="form-select">
                <?php for ($y=1;$y<=4;$y++): ?>
                    <option value="<?= $y ?>" <?= ($student['year']??1)==$y?'selected':'' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <?php foreach (['Male','Female','Other'] as $g): ?>
                    <option value="<?= $g ?>" <?= ($student['gender']??'')===$g?'selected':'' ?>><?= $g ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Enrollment No</label>
            <input type="text" name="enrollment_no" class="form-control" value="<?= e($student['enrollment_no'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" <?= ($student['status']??'')==='active'?'selected':'' ?>>Active</option>
                <option value="inactive" <?= ($student['status']??'')==='inactive'?'selected':'' ?>>Inactive</option>
            </select>
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="students.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
