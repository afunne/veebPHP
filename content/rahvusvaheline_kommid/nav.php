<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Magusad Maailm</title>
    <link rel="stylesheet" href="komm.css">
</head>
<body>
<?php
// nav.php — site header / navigation (keeps original layout but shows logout when admin is logged in)
// This file intentionally does NOT require auth.php to avoid forcing session_start() on public pages.
// It will show a logout link only when your protected pages have already included auth.php
// (so is_logged_in() / $_SESSION are available).

$showLogout = false;
$username = null;

// If auth.php was included earlier on this request, use is_logged_in(); otherwise don't try to start session here.
if (function_exists('is_logged_in') && is_logged_in()) {
    $showLogout = true;
    // username may be in session if auth.php set it
    $username = $_SESSION['username'] ?? null;
}
?>
<header class="site-header">
    <h1>Magusad Maailm</h1>
    <p class="tagline">ava oma meel, ava oma silmad!</p>
    <nav>
        <a href="index.php">Koduleht</a> |
        <a href="pricelist.php">Hinnakiri</a> |
        <a href="gallery.php">Galerii</a> |
        <a href="admin.php">Admin</a>
        <?php if ($showLogout): ?>
            | <span style="margin-left:.5rem">Tere, <?= htmlspecialchars($username ?? 'admin', ENT_QUOTES) ?></span>
            <a href="logout.php" style="margin-left:.5rem">Logi välja</a>
        <?php else: ?>
            <!-- Optionally show login link: <a href="login.php" style="margin-left:.5rem">Logi sisse</a> -->
        <?php endif; ?>
    </nav>
</header>