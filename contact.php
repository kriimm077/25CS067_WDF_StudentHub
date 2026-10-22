<?php
require_once 'includes/auth.php';
$page_title = 'Contact';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $msg = trim($_POST['message'] ?? '');
    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $msg) {
        // In a real app store to DB or send mail. Here we just show success.
        $message = 'Thank you! Your message has been received (demo).';
    } else {
        $message = 'Please fill all fields correctly.';
    }
}

require_once 'includes/header.php';
?>
<h2 class="mb-4">Contact Us</h2>
<?php if ($message): ?><div class="alert alert-info"><?= e($message) ?></div><?php endif; ?>
<div class="row">
    <div class="col-md-6">
        <form method="POST" class="card shadow-sm p-4">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm p-4 h-100">
            <h5>Campus Support</h5>
            <p class="mb-1"><i class="bi bi-envelope"></i> support@studenthub.local</p>
            <p class="mb-1"><i class="bi bi-telephone"></i> +91-00000-00000</p>
            <p class="mb-0"><i class="bi bi-geo-alt"></i> CHARUSAT Campus, Gujarat</p>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
