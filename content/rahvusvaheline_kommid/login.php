<?php
require 'config.php';
require_once __DIR__ . '/auth.php';

// If already logged in, redirect based on role (admins -> admin.php, others -> index.php)
if (is_logged_in()) {
    if (is_admin()) {
        header('Location: admin.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$redirect = $_GET['redirect'] ?? 'index.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $redirect = $_POST['redirect'] ?? $redirect;

    if ($username === '' || $password === '') {
        $msg = 'Täida kasutajanimi ja parool.';
    } else {
        $user = authenticate($pdo, $username, $password);
        if ($user !== false) {
            // Ensure session is started, regenerate id to prevent fixation, then set session data
            if (session_status() === PHP_SESSION_NONE) session_start();
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'] ? 1 : 0;

            // Choose a safe redirect target:
            if ($_SESSION['is_admin']) {
                // Admins go to admin area
                $target = 'admin.php';
            } else {
                // Non-admins must not be sent to admin.php; prefer redirect param unless it's admin.php
                if ($redirect === 'admin.php' || stripos($redirect, 'admin.php') !== false) {
                    $target = 'index.php';
                } else {
                    $target = $redirect ?: 'index.php';
                }
            }

            header('Location: ' . $target);
            exit;
        } else {
            $msg = 'Vale kasutajanimi või parool.';
        }
    }
}
?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Admin login</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="komm.css">
</head>
<body>
<?php require 'nav.php'; ?>
<main class="container">
    <div class="login-card card">
        <h1>Logi sisse</h1>
        <?php if ($msg): ?>
            <script>alert(<?= json_encode($msg, JSON_UNESCAPED_UNICODE) ?>);</script>
        <?php endif; ?>
        <form method="post" action="login.php">
            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect, ENT_QUOTES) ?>">
            <label>Kasutajanimi<br><input type="text" name="username" required></label>
            <label>Parool<br><input type="password" name="password" required></label>
            <div class="form-actions">
                <button class="btn" type="submit">Logi sisse</button>
            </div>
            <p>Märkus: selles failis on kontroll admin / 1234 jaoks.
                opilane / 54321
                See on ainult testimiseks. </p>
        </form>
    </div>
</main>
<?php require 'jalus.php'; ?>
</body>
</html>