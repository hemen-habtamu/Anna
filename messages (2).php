<?php
date_default_timezone_set('Africa/Addis_Ababa');

$dbHost = '127.0.0.1';
$dbName = 'anna_portfolio';
$dbUser = 'root';
$dbPass = '';

$pdo = null;
$dbError = '';

try {
    $pdo = new PDO("mysql:host=$dbHost;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(80) NOT NULL,
        email VARCHAR(120) NOT NULL,
        message TEXT NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (PDOException $exception) {
    $pdo = null;
    $dbError = 'The database is not connected. Start MySQL in XAMPP, then refresh this page.';
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$messages = [];

if ($pdo) {
    $messages = $pdo->query('SELECT id, name, email, message, created_at FROM messages ORDER BY id DESC')->fetchAll();
}

$count = count($messages);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#090d18">
    <title>Inbox | Anna</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="container nav">
        <a class="brand" href="index.php">anna<span>.</span></a>
        <nav id="navigation">
            <a href="index.php#home">Home</a>
            <a href="index.php#about">About</a>
            <a href="index.php#skills">Skills</a>
            <a href="index.php#projects">Projects</a>
            <a href="index.php#contact">Contact</a>
            <a href="messages.php" class="active">Inbox</a>
        </nav>
        <a class="nav-cta" href="index.php#contact">Let's connect &#8599;</a>
        <button class="menu-btn" id="menuBtn" aria-label="Open navigation" aria-expanded="false">&#9776;</button>
    </div>
</header>

<main class="container inbox-page">
    <div class="inbox-head">
        <div class="section-kicker">Saved messages</div>
        <h1>Inbox</h1>
        <p>Every hello sent from the contact form, saved in MySQL and shown newest first.</p>
        <span class="count-pill"><?= $count ?> message<?= $count === 1 ? '' : 's' ?></span>
    </div>

    <?php if ($dbError): ?>
        <div class="notice bad"><?= e($dbError) ?></div>
    <?php endif; ?>

    <?php if (!$messages && !$dbError): ?>
        <div class="empty-inbox">
            <h2>No messages yet</h2>
            <p>Be the first to say hi from the portfolio page.</p>
            <a class="btn primary" href="index.php#contact">Send a message <span>&#8599;</span></a>
        </div>
    <?php endif; ?>

    <?php if ($messages): ?>
        <div class="msg-list">
            <?php foreach ($messages as $message): ?>
                <article class="msg-card">
                    <div class="msg-avatar"><?= e(mb_substr($message['name'], 0, 1)) ?></div>
                    <div class="msg-body">
                        <div class="msg-head">
                            <h2><?= e($message['name']) ?></h2>
                            <time><?= date('d M Y, H:i', strtotime($message['created_at'])) ?></time>
                        </div>
                        <a class="msg-email" href="mailto:<?= e($message['email']) ?>"><?= e($message['email']) ?></a>
                        <p><?= e($message['message']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer>
    <div class="container footer-inner">
        <span>&copy; <?= date('Y') ?> Anna. Made with curiosity.</span>
        <span>Gondar, Ethiopia &#10022;</span>
    </div>
</footer>

<script>
const menuBtn = document.getElementById('menuBtn');
const nav = document.getElementById('navigation');
menuBtn.addEventListener('click', () => {
    const opened = nav.classList.toggle('open');
    menuBtn.setAttribute('aria-expanded', String(opened));
    menuBtn.textContent = opened ? '\u00d7' : '\u2630';
});
nav.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
    nav.classList.remove('open');
    menuBtn.setAttribute('aria-expanded', 'false');
    menuBtn.textContent = '\u2630';
}));
</script>
</body>
</html>
