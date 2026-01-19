<?php
// logout.php — clear session and redirect to ?redirect=...
// Usage: logout.php?redirect=login.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Clear session data
$_SESSION = [];

// Remove session cookie if used
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// Destroy the session
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

// Safe redirect target (fallback to login.php)
$target = 'index.php';
if (!empty($_GET['redirect'])) {
    // Only allow internal redirects: no absolute URLs
    $r = $_GET['redirect'];
    if (strpos($r, '/') !== 0 && stripos($r, 'http') === false) {
        $target = $r;
    }
}

// Redirect
header('Location: ' . $target);
exit;