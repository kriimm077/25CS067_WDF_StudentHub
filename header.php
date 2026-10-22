<?php
if (!isset($page_title)) $page_title = 'StudentHub';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> | StudentHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Student<span class="text-warning">Hub</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <?php if ($user): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <?php if ($user['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="admin/index.php">Admin</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <button id="darkModeToggle" class="btn btn-sm btn-outline-light" type="button" aria-label="Toggle dark mode">🌙</button>
                <?php if ($user): ?>
                    <span class="text-white small d-none d-md-inline">Hi, <?= e($user['name']) ?></span>
                    <a href="profile.php" class="btn btn-sm btn-outline-light">Profile</a>
                    <a href="logout.php" class="btn btn-sm btn-warning">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm btn-outline-light">Login</a>
                    <a href="register.php" class="btn btn-sm btn-warning">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
<main class="container py-4">
<?php
$success = flash('success');
$error   = flash('error');
if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= e($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif;
if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?= e($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
