<?php
require_once 'includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email and password are required.';
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, role, is_active FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $user['is_active'] && password_verify($password, $user['password'])) {
            login_user($user);
            // Update last_login
            $uid = $user['id'];
            $conn->query("UPDATE users SET last_login = NOW() WHERE id = $uid");
            // Audit
            $action = 'LOGIN';
            $details = 'User logged in';
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $stmt2 = $conn->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param('isss', $uid, $action, $details, $ip);
            $stmt2->execute();

            if ($user['role'] === 'admin') {
                header('Location: admin/index.php');
            } else {
                header('Location: dashboard.php');
            }
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

// Message from query string
$msg = $_GET['msg'] ?? '';
if ($msg === 'session_expired') $error = 'Your session has expired. Please login again.';
if ($msg === 'login_required') $error = 'Please login to continue.';

$page_title = 'Login';
require_once 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="mb-4 text-center">Login</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="text-center mt-3 mb-0">No account? <a href="register.php">Register</a></p>
                <hr>
                <p class="small text-muted mb-0"><strong>Demo accounts</strong> (after importing SQL and running setup):</p>
                <ul class="small text-muted">
                    <li>Admin: admin@studenthub.local / Admin@123</li>
                    <li>Student: student@studenthub.local / Student@123</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
