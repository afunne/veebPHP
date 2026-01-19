<?php
// Tasks (yeah im ugu gaga i need to write ts it is that hard)
// 1) CLI: php set_hash.php username newpassword
//    php set_hash.php admin 1234
// 2) Web: upload this file, open in browser and submit the form (then delete file).
//
// Reminder to myself delete this file from the server immediately after use if problem occur.

require 'config.php';

if (!isset($pdo) || !$pdo) {
    echo "DB-ühendust ei leitud. Kontrollige config.php (peab olema olemas \$pdo).\n";
    exit(1);
}

function do_update(PDO $pdo, string $username, string $password): void {
    if ($username === '' || $password === '') {
        echo "Username and password must be non-empty.\n";
        return;
    }

    // Hash the new password
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if ($hash === false) {
        echo "password_hash() failed.\n";
        return;
    }

    try {
        // If user exists, update. Otherwise insert a new user (bootstrap).
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $upd = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $upd->execute([$hash, $row['id']]);
            echo "Updated password_hash for existing user '{$username}' (id={$row['id']}).\n";
        } else {
            $ins = $pdo->prepare('INSERT INTO users (username, password_hash, created_at) VALUES (?, ?, NOW())');
            $ins->execute([$username, $hash]);
            echo "Inserted new user '{$username}'.\n";
        }

        // Show brief verification using password_verify
        $stmt2 = $pdo->prepare('SELECT password_hash FROM users WHERE username = ? LIMIT 1');
        $stmt2->execute([$username]);
        $stored = $stmt2->fetchColumn();
        if ($stored) {
            $ok = password_verify($password, $stored) ? 'YES' : 'NO';
            echo "password_verify('{$password}', stored_hash) => {$ok}\n";
            echo "Stored hash length: " . strlen($stored) . "\n";
        }
    } catch (PDOException $e) {
        echo "DB error: " . $e->getMessage() . "\n";
    }
}

// CLI mode
if (php_sapi_name() === 'cli') {
    $argvUsername = $argv[1] ?? '';
    $argvPassword = $argv[2] ?? '';
    if ($argvUsername === '' || $argvPassword === '') {
        echo "Usage: php set_hash.php username newpassword\n";
        exit(1);
    }
    do_update($pdo, $argvUsername, $argvPassword);
    exit(0);
}

// Web mode (simple form)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    do_update($pdo, $username, $password);
    echo '<p><strong>Done. It will be removed after :P.</strong></p>';
    echo '<p><a href="javascript:history.back()">Back</a></p>';
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Set user password hash (one-off)</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; padding:20px; max-width:720px}
    label{display:block;margin:8px 0}
    input{width:100%; padding:8px; border:1px solid #ddd; border-radius:6px}
    .btn{margin-top:10px;padding:8px 12px;border-radius:6px;background:#c44;color:#fff;border:0;cursor:pointer}
    .warn{color:#900;margin-top:8px}
  </style>
</head>
<body>
  <h1>One-off: set/update user's password hash</h1>
  <p class="warn">Warning: This script is powerful. Delete it from the server after use.</p>
  <form method="post" action="set_hash.php">
    <label>Username<br><input name="username" required></label>
    <label>New password<br><input name="password" type="password" required></label>
    <button class="btn" type="submit">Set password (hash &amp; store)</button>
  </form>
  <p>Or run from CLI: <code>php set_hash.php username newpassword</code></p>
</body>
</html>