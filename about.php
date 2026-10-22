<?php
require_once 'includes/auth.php';
$page_title = 'About';
require_once 'includes/header.php';
?>
<h2>About StudentHub</h2>
<p class="lead">StudentHub is a full-stack campus portal developed as the semester project for <strong>Web Development Frameworks (ITUE203)</strong> at CHARUSAT.</p>
<p>It integrates semantic HTML5, responsive CSS (Bootstrap), client-side JavaScript, PHP server-side processing, MySQL database, secure authentication, role-based access, CRUD modules, file uploads, and AJAX-ready APIs.</p>
<div class="row g-3 mt-3">
    <div class="col-md-6">
        <div class="card h-100"><div class="card-body">
            <h5>Features</h5>
            <ul>
                <li>Secure registration & login (password_hash / password_verify)</li>
                <li>Session management with timeout</li>
                <li>Role-based access (Student / Admin)</li>
                <li>Student & Event CRUD with search/filter</li>
                <li>Poster upload with validation</li>
                <li>Admin dashboard & audit logs</li>
            </ul>
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card h-100"><div class="card-body">
            <h5>Tech Stack</h5>
            <ul>
                <li>HTML5, CSS3, Bootstrap 5</li>
                <li>JavaScript (ES6+)</li>
                <li>PHP 8 + MySQLi (prepared statements)</li>
                <li>MySQL / MariaDB</li>
                <li>XAMPP local deployment</li>
                <li>GitHub version control</li>
            </ul>
        </div></div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
