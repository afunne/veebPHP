<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

if (is_logged_in()) {
    header('Location: admin.php');
    exit;
}

$redirect = $_GET['redirect'] ?? 'admin.php';
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
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ' . ($redirect ?: 'admin.php'));
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
  <link rel="stylesheet" href="styles.css">
  <style>
    /* small inline styles to ensure form looks okay */
    .login-card{max-width:420px;margin:48px auto;padding:18px;border:1px solid #eee;border-radius:8px;background:#fff}
    .login-card label{display:block;margin-bottom:10px}
    .login-card input{width:100%;padding:8px;border:1px solid #ddd;border-radius:6px}
    .login-card .btn{margin-top:8px}
  </style>
</head>
<body>
  <?php require_once __DIR__ . '/nav.php'; ?>

  <main class="container">
    <div class="login-card card">
      <h1>Admin sisselogimine</h1>

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
      </form>

      <p style="margin-top:12px;color:#666;font-size:.95rem;">
        Märkus: selles failis on DEV-kontroll <strong>admin / 1234</strong> jaoks.
        See on ainult testimiseks.
      </p>
    </div>
  </main>

  <?php require_once __DIR__ . '/jalus.php'; ?>
</body>
</html>