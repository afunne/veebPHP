<?php
require_once __DIR__ . '/auth.php'; // auth.php peab pakkuma validate_csrf_token()

if (session_status() === PHP_SESSION_NONE) session_start();

$token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($token)) {
    // Vorm oli vale -> suuna tagasi või kuva viga
    header('Location: index.php');
    exit;
} // yet to make function but it is not needed

// perform logout
perform_logout();

// Redirect
header('Location: index.php');
exit;
