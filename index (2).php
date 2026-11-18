<?php
date_default_timezone_set('Africa/Addis_Ababa');

$studentEmail = 'annah@gmail.com';
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

$errors = [];
$sent = isset($_GET['sent']);
$form = ['name' => '', 'email' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['name'] = trim($_POST['name'] ?? '');
    $form['email'] = trim($_POST['email'] ?? '');
    $form['message'] = trim($_POST['message'] ?? '');

    if ($form['name'] === '' || mb_strlen($form['name']) > 80) {
        $errors[] = 'Please write your name, up to 80 characters.';
    }

    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($form['email']) > 120) {
        $errors[] = 'Please write a valid email address.';
    }

    if ($form['message'] === '' || mb_strlen($form['message']) > 1000) {
        $errors[] = 'Please write a message, up to 1000 characters.';
    }

    if (!$errors) {
        if ($pdo) {
            $statement = $pdo->prepare('INSERT INTO messages (name, email, message) VALUES (?, ?, ?)');
            $statement->execute([$form['name'], $form['email'], $form['message']]);
            header('Location: index.php?sent=1#contact');
            exit;
        }

        $errors[] = 'Messages cannot be saved while the database is offline.';
    }
}

$count = $pdo ? (int) $pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn() : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#090d18">
    <meta name="description" content="Anna's student portfolio, learning web development and PHP/MySQL database concepts in Gondar, Ethiopia.">
    <title>Anna | Student Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="container nav">
        <a class="brand" href="#home">anna<span>.</span></a>
        <nav id="navigation">
            <a href="#home" class="active">Home</a>
            <a href="#about">About</a>
            <a href="#skills">Skills</a>
            <a href="#projects">Projects</a>
            <a href="#contact">Contact</a>
            <a href="messages.php">Inbox</a>
        </nav>
        <a class="nav-cta" href="#contact">Let's connect &#8599;</a>
        <button class="menu-btn" id="menuBtn" aria-label="Open navigation" aria-expanded="false">&#9776;</button>
    </div>
</header>

<main>
    <section class="container hero" id="home">
        <div class="reveal">
            <div class="eyebrow"><span class="dot"></span> Student &bull; Future creator</div>
            <h1>Hello, I'm<br><span class="gradient">Anna.</span></h1>
            <p class="hero-copy">A curious Grade 12 student from Gondar, Ethiopia, building my knowledge in technology and web development. I believe every big achievement starts with a small step.</p>
            <div class="hero-actions">
                <a class="btn primary" href="#projects">Explore my work <span>&#8599;</span></a>
                <a class="btn secondary" href="#contact">Get in touch</a>
            </div>
            <div class="location">&#8982; <span>Gondar, Ethiopia</span><span>&bull;</span><span>Preparing for the Grade 12 exit exam</span></div>
        </div>
        <div class="portrait-wrap reveal">
            <div class="portrait-glow"></div>
            <div class="portrait-frame"><img src="profile.jpg" alt="Anna's profile picture"></div>
            <span class="orbit-dot one"></span><span class="orbit-dot two"></span>
            <div class="portrait-label">&#10022; Always learning</div>
            <div class="floating-tag"><strong>Future web creator</strong>Curiosity into creativity</div>
        </div>
    </section>

    <section id="about">
        <div class="container">
            <div class="section-head reveal">
                <div class="section-kicker">01 / About me</div>
                <h2>A little about my journey.</h2>
                <p>I'm at the beginning of my journey, staying curious, learning new things, and working toward a bright future.</p>
            </div>
            <div class="about-grid">
                <article class="card reveal">
                    <h3>Who I am</h3>
                    <p>My name is Anna. I am a Grade 12 student living in Gondar, Ethiopia, and I am currently preparing for the national exit examination. I enjoy learning, exploring ideas, and discovering how technology can solve everyday problems.</p>
                </article>
                <article class="card reveal">
                    <h3>My current focus</h3>
                    <p>I am learning the foundations of web development and database concepts. My goal is to keep improving step by step, practice what I learn, and create useful digital projects.</p>
                    <div class="facts">
                        <div class="fact"><small>Based in</small><strong>Gondar, Ethiopia</strong></div>
                        <div class="fact"><small>Current level</small><strong>Grade 12 student</strong></div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section id="skills">
        <div class="container">
            <div class="section-head reveal">
                <div class="section-kicker">02 / Skills</div>
                <h2>Things I'm learning.</h2>
                <p>These are learning areas I am exploring and practicing. I will keep adding to this list as I grow.</p>
            </div>
            <div class="skill-grid">
                <article class="card skill reveal"><div class="skill-icon">HTML</div><div><h3>HTML</h3><small>Building page structure</small></div></article>
                <article class="card skill reveal"><div class="skill-icon">CSS</div><div><h3>CSS</h3><small>Layouts and styling</small></div></article>
                <article class="card skill reveal"><div class="skill-icon">PHP</div><div><h3>PHP</h3><small>Server-side basics</small></div></article>
                <article class="card skill reveal"><div class="skill-icon">SQL</div><div><h3>MySQL</h3><small>Database fundamentals</small></div></article>
            </div>
        </div>
    </section>

    <section id="projects">
        <div class="container">
            <div class="section-head reveal">
                <div class="section-kicker">03 / Projects</div>
                <h2>Learning through projects.</h2>
                <p>A small collection of practice work and topics I am developing my understanding of.</p>
            </div>
            <div class="project-grid">
                <article class="card reveal">
                    <div class="project-top"><span class="project-number">PROJECT 01</span><span class="project-mark">&#9636;</span></div>
                    <h3>PHP &amp; MySQL Database Study</h3>
                    <p>A learning project based on my database notes. It covers connecting PHP to MySQL, creating databases and tables, and understanding how information is stored.</p>
                    <div class="tags"><span class="tag">PHP</span><span class="tag">MySQL</span><span class="tag">Database</span></div>
                </article>
                <article class="card reveal">
                    <div class="project-top"><span class="project-number">PROJECT 02</span><span class="project-mark">&#8984;</span></div>
                    <h3>CRUD Concepts Practice</h3>
                    <p>Practicing the basic database operations: adding records, viewing information, updating existing data, and deleting selected records.</p>
                    <div class="tags"><span class="tag">SQL</span><span class="tag">INSERT</span><span class="tag">SELECT</span><span class="tag">UPDATE</span><span class="tag">DELETE</span></div>
                </article>
            </div>
        </div>
    </section>

    <section id="contact">
        <div class="container">
            <div class="contact-box reveal">
                <div class="section-kicker">04 / CONTACT</div>
                <h2>Have a good idea?<span>Let's connect.</span></h2>
                <p>I'd love to hear from you. Send me a message and let's start a conversation.</p>
                <a class="contact-email" href="mailto:<?= e($studentEmail) ?>">
                    <span class="email-icon">&#9993;</span>
                    <span><small>EMAIL ME AT</small><strong><?= e($studentEmail) ?></strong></span>
                    <span class="email-arrow">&#8599;</span>
                </a>

                <?php if ($dbError): ?>
                    <div class="notice bad"><?= e($dbError) ?></div>
                <?php endif; ?>

                <?php if ($sent): ?>
                    <div class="notice ok">Thank you! Your message is saved. <a href="messages.php">Open the inbox</a>.</div>
                <?php endif; ?>

                <?php if ($errors): ?>
                    <div class="notice bad">
                        <strong>Please check the form.</strong>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form class="contact-form" method="post" action="index.php#contact">
                    <label class="field">Your name
                        <input type="text" name="name" placeholder="Enter your name" value="<?= e($form['name']) ?>" required maxlength="80">
                    </label>
                    <label class="field">Your email
                        <input type="email" name="email" placeholder="you@example.com" value="<?= e($form['email']) ?>" required maxlength="120">
                    </label>
                    <label class="field full">Your message
                        <textarea name="message" placeholder="Write your message..." required maxlength="1000"><?= e($form['message']) ?></textarea>
                    </label>
                    <button class="btn primary form-submit" type="submit">Send message &#8599;</button>
                    <p class="form-note muted"><?= $count ?> message<?= $count === 1 ? '' : 's' ?> saved. <a href="messages.php">Open the inbox &#8594;</a></p>
                </form>
            </div>
        </div>
    </section>
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
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach((element) => observer.observe(element));
const sections = [...document.querySelectorAll('main section[id]')];
const navLinks = [...nav.querySelectorAll('a')];
window.addEventListener('scroll', () => {
    let current = 'home';
    sections.forEach((section) => {
        if (window.scrollY >= section.offsetTop - 150) current = section.id;
    });
    navLinks.forEach((link) => link.classList.toggle('active', link.getAttribute('href') === '#' + current));
});
</script>
</body>
</html>
