<?php
require_once 'includes/auth.php';
$page_title = 'FAQ';
require_once 'includes/header.php';

$faqs = [
    ['q' => 'How do I register?', 'a' => 'Click Register in the top navigation, fill the form with valid details, and submit. You will then be able to login.'],
    ['q' => 'What are the demo credentials?', 'a' => 'After running setup_passwords.php: Admin – admin@studenthub.local / Admin@123 ; Student – student@studenthub.local / Student@123'],
    ['q' => 'How is my password stored?', 'a' => 'Passwords are hashed using PHP password_hash() (bcrypt) and never stored in plain text.'],
    ['q' => 'Can I upload event posters?', 'a' => 'Yes, admin users can upload JPG/PNG/WEBP posters (max 2 MB) when creating or editing events.'],
    ['q' => 'Is the site responsive?', 'a' => 'Yes. The UI uses Bootstrap 5 and works on mobile, tablet, and desktop.'],
    ['q' => 'How do I deploy locally?', 'a' => 'Import sql/studenthub.sql in phpMyAdmin, copy the project to htdocs, run setup_passwords.php once, then open http://localhost/StudentHub'],
];
?>
<h2 class="mb-4">Frequently Asked Questions</h2>
<div class="accordion" id="faqAccordion">
<?php foreach ($faqs as $i => $f): ?>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button <?= $i ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
                <?= e($f['q']) ?>
            </button>
        </h2>
        <div id="faq<?= $i ?>" class="accordion-collapse collapse <?= $i ? '' : 'show' ?>" data-bs-parent="#faqAccordion">
            <div class="accordion-body"><?= e($f['a']) ?></div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
