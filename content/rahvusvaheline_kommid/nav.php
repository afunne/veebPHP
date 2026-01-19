<?php
   require 'config.php';
   require_once __DIR__ . '/auth.php';

$showLogout = false;
$username = null;
if (function_exists('is_logged_in') && is_logged_in()) {
    $showLogout = true;
    $username = $_SESSION['username'] ?? null;
}
?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Magusad Maailm</title>
    <link rel="stylesheet" href="komm.css">
</head>
<body>
<header class="site-header">
    <h1>Magusad Maailm</h1>
    <p class="tagline">ava oma meel, ava oma silmad!</p>
    <nav>
        <a href="index.php">Koduleht</a> |
        <a href="pricelist.php">Hinnakiri</a> |
        <a href="gallery.php">Galerii</a> |

        <?php if (function_exists('is_admin') && is_admin()): ?>
            <!-- Admin is logged in -->
            <a href="admin.php">Admin</a>
        <?php else: ?>
            <!-- No admin signed in (show login link instead) -->
            <a href="login.php">Log in</a>
        <?php endif; ?>

        <?php if ($showLogout): ?>
            | <span>Tere, <?= htmlspecialchars($username ?? '', ENT_QUOTES) ?></span>
            <a href="logout.php" style="margin-left:.5rem">Logi välja</a>
        <?php endif; ?>
    </nav>
</header>