<?php
require_once 'includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$old = ['name'=>'','email'=>'','mobile'=>'','course'=>'','year'=>'1','gender'=>'Male'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $mobile   = trim($_POST['mobile'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    $course   = trim($_POST['course'] ?? '');
    $year     = (int)($_POST['year'] ?? 1);
    $gender   = $_POST['gender'] ?? 'Other';
    $terms    = isset($_POST['terms']);

    $old = compact('name','email','mobile','course','year','gender');

    if ($name === '' || strlen($name) < 3) $errors[] = 'Name must be at least 3 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (!preg_match('/^[6-9]\d{9}$/', $mobile)) $errors[] = 'Valid 10-digit Indian mobile required.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';
    if ($course === '') $errors[] = 'Course is required.';
    if (!$terms) $errors[] = 'You must accept the terms.';

    if (empty($errors)) {
        // Duplicate email check
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'student';
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $name, $email, $hash, $role);
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $enroll = strtoupper(substr($course,0,2)) . date('Y') . str_pad($user_id, 4, '0', STR_PAD_LEFT);
                $stmt2 = $conn->prepare("INSERT INTO students (user_id, full_name, email, mobile, course, year, gender, enrollment_no) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt2->bind_param('issssiss', $user_id, $name, $email, $mobile, $course, $year, $gender, $enroll);
                $stmt2->execute();

                // Audit
                $action = 'REGISTER';
                $details = "New student registered: $email";
                $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                $stmt3 = $conn->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
                $stmt3->bind_param('isss', $user_id, $action, $details, $ip);
                $stmt3->execute();

                flash('success', 'Registration successful! Please login.');
                header('Location: login.php');
                exit;
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    }
}

$page_title = 'Register';
require_once 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="mb-4 text-center">Student Registration</h2>
                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="POST" action="" novalidate id="regForm">
                    <div class="mb-3">
                        <label class="form-label" for="name">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= e($old['name']) ?>" required minlength="3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= e($old['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="mobile">Mobile *</label>
                        <input type="tel" class="form-control" id="mobile" name="mobile" value="<?= e($old['mobile']) ?>" pattern="[6-9][0-9]{9}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="password">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            <div class="form-text">Min 8 characters</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="confirm">Confirm Password *</label>
                            <input type="password" class="form-control" id="confirm" name="confirm" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="course">Course *</label>
                            <select class="form-select" id="course" name="course" required>
                                <option value="">Select</option>
                                <?php foreach (['Computer Engineering','Information Technology','CSE','AIML','CE','IT'] as $c): ?>
                                    <option value="<?= $c ?>" <?= $old['course']===$c?'selected':'' ?>><?= $c ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="year">Year</label>
                            <select class="form-select" id="year" name="year">
                                <?php for ($y=1;$y<=4;$y++): ?>
                                    <option value="<?= $y ?>" <?= $old['year']==$y?'selected':'' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="gender">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                                <?php foreach (['Male','Female','Other'] as $g): ?>
                                    <option value="<?= $g ?>" <?= $old['gender']===$g?'selected':'' ?>><?= $g ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">I accept the terms and conditions</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="text-center mt-3 mb-0">Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
