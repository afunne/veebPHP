<?php
// create_user.php — improved: requires existing admin for subsequent creations
// and prevents creating a new admin with a password already used by any existing admin.
//
// Usage: open in browser, fill username + password.
// If there are zero users in the DB, the first user can be created without login (bootstrap).
// After creating required accounts, remove this file from the server.

require_once __DIR__ . '/config.php';

if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    echo "Andmebaasi ühendus puudub. Kontrolli config.php seadeid.";
    exit;
}

// Count existing users
try {
    $stmt = $pdo->query("SELECT COUNT(*) AS cnt FROM users");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $userCount = isset($row['cnt']) ? (int)$row['cnt'] : 0;
} catch (PDOException $e) {
    // If table doesn't exist or query fails, show helpful message
    echo "<p>Viga andmebaasi päringus: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Veendu, et tabel <code>users</code> on loodud. Näide SQL-st on create_users_table.sql</p>";
    exit;
}

// If there are existing users, require admin login to create a new admin
$needsAuth = $userCount > 0;

if ($needsAuth) {
    require_once __DIR__ . '/auth.php';
    // require_login() will redirect to login.php if not logged in
    require_login();
    // Optional: we could check current_user role; for now every user is admin
}

// Handle form POST
$msg = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $password_confirm = (string)($_POST['password_confirm'] ?? '');

    if ($username === '' || $password === '') {
        $msg = 'Täida kasutajanimi ja parool.';
    } elseif ($password !== $password_confirm) {
        $msg = 'Paroolid ei kattu.';
    } elseif (strlen($password) < 8) {
        $msg = 'Parool peab olema vähemalt 8 märki pikk.';
    } else {
        try {
            // Check username uniqueness
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $msg = 'Selline kasutajanimi on juba olemas. Vali teine.';
            } else {
                // Prevent reuse of existing passwords:
                // Fetch all password_hash values and verify the plaintext new password
                $stmt = $pdo->query('SELECT password_hash FROM users');
                $hashes = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

                $passwordUsed = false;
                foreach ($hashes as $h) {
                    if ($h && password_verify($password, $h)) {
                        $passwordUsed = true;
                        break;
                    }
                }

                if ($passwordUsed) {
                    $msg = 'Antud parool on juba mõne olemasoleva admini poolt kasutusel. Palun vali teine parool.';
                } else {
                    // All checks passed: insert user
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $ins = $pdo->prepare('INSERT INTO users (username, password_hash, created_at) VALUES (?, ?, NOW())');
                    $ins->execute([$username, $hash]);
                    $msg = 'Kasutaja loodud edukalt. Eemalda create_user.php pärast kasutamist.';
                    $success = true;
                }
            }
        } catch (PDOException $e) {
            $msg = 'DB viga: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!doctype html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title>Loo admin kasutaja</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="styles.css">
  <style>
    .create-card{max-width:520px;margin:36px auto;padding:18px;border:1px solid #eee;border-radius:8px;background:#fff}
    .create-card label{display:block;margin-bottom:10px}
    .create-card input{width:100%;padding:8px;border:1px solid #ddd;border-radius:6px}
    .create-card .btn{margin-top:10px}
    .flash{margin-bottom:12px;padding:10px;border-radius:8px}
    .flash--err{background:#ffecec;color:#900;border:1px solid #f2c2c2}
    .flash--ok{background:#e8f8f2;color:#13654a;border:1px solid #cfeee2}
    small.note{display:block;color:var(--muted,#666);margin-top:8px}
  </style>
</head>
<body>
  <?php require_once __DIR__ . '/nav.php'; ?>
  <main class="container">
    <div class="create-card card">
      <h1><?= $userCount === 0 ? 'Loo esimene admin kasutaja' : 'Loo uus admin kasutaja' ?></h1>

      <?php if ($msg): ?>
        <div class="flash <?= $success ? 'flash--ok' : 'flash--err' ?>"><?= htmlspecialchars($msg, ENT_QUOTES) ?></div>
      <?php endif; ?>

      <?php if ($needsAuth): ?>
        <p>Sa pead olema sisse logitud admin, et luua uut admini.</p>
        <p>Praegu sisseloginud: <strong><?= htmlspecialchars($_SESSION['username'] ?? '—', ENT_QUOTES) ?></strong></p>
      <?php else: ?>
        <p>See loob süsteemi esimese admin kasutaja. Pärast loomist eemalda see leht serverist.</p>
      <?php endif; ?>

      <?php if (!$success): ?>
        <form method="post" action="create_user.php">
          <label>Kasutajanimi<br>
            <input name="username" required maxlength="100" pattern=".{3,}" title="Vähemalt 3 märki">
          </label>

          <label>Parool (vähemalt 8 märki)<br>
            <input type="password" name="password" required minlength="8">
          </label>

          <label>Korrake parooli<br>
            <input type="password" name="password_confirm" required minlength="8">
          </label>

          <div class="form-actions">
            <button class="btn" type="submit">Loo kasutaja</button>
          </div>
        </form>
        <small class="note">Pärast kasutaja loomist eemalda create_user.php fail serverist. Sama parooli korduvkasutus teiste adminidega on keelatud.</small>
      <?php else: ?>
        <p><a href="login.php">Logi sisse</a> või eemalda see fail serverist.</p>
      <?php endif; ?>
    </div>
  </main>
  <?php require_once __DIR__ . '/jalus.php'; ?>
</body>
</html>