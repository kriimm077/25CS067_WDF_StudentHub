<?php
require_once 'includes/auth.php';
require_login();
$user = current_user();
$page_title = 'My Profile';

$stmt = $conn->prepare("SELECT * FROM students WHERE user_id = ? LIMIT 1");
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $student) {
    $mobile = trim($_POST['mobile'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $year   = (int)($_POST['year'] ?? 1);
    $gender = $_POST['gender'] ?? 'Other';

    $stmt = $conn->prepare("UPDATE students SET mobile=?, course=?, year=?, gender=? WHERE id=?");
    $stmt->bind_param('ssisi', $mobile, $course, $year, $gender, $student['id']);
    if ($stmt->execute()) {
        flash('success', 'Profile updated successfully.');
        header('Location: profile.php');
        exit;
    }
    flash('error', 'Update failed.');
}

require_once 'includes/header.php';
?>

<h2 class="mb-4">My Profile</h2>

<?php if (!$student): ?>
    <div class="alert alert-warning">No student profile linked. Contact admin.</div>
<?php else: ?>
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Account Info</h5>
                <p><strong>Name:</strong> <?= e($user['name']) ?></p>
                <p><strong>Email:</strong> <?= e($user['email']) ?></p>
                <p><strong>Role:</strong> <?= e(ucfirst($user['role'])) ?></p>
                <p><strong>Enrollment:</strong> <?= e($student['enrollment_no'] ?? '—') ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Update Details</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-control" value="<?= e($student['mobile'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <input type="text" name="course" class="form-control" value="<?= e($student['course']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select">
                            <?php for ($y=1;$y<=4;$y++): ?>
                                <option value="<?= $y ?>" <?= $student['year']==$y?'selected':'' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <?php foreach (['Male','Female','Other'] as $g): ?>
                                <option value="<?= $g ?>" <?= $student['gender']===$g?'selected':'' ?>><?= $g ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
