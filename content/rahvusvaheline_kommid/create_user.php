<?php
// create_user.php — improved: first user is admin (bootstrap). Subsequent creation requires a logged-in admin to create other admins.
// soon  to be deleted :3

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
    echo "<p>Viga andmebaasi päringus: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

$needsAuth = $userCount > 0;
if ($needsAuth) {
    require_once __DIR__ . '/auth.php';
    require_login(true); // require admin to create new users (and only admins can set is_admin)
}

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
                // determine is_admin bit:
                if ($userCount === 0) {
                    // first user -> make admin
                    $makeAdmin = 1;
                } else {
                    // subsequent: only current admin can set is_admin via checkbox
                    $makeAdmin = 0;
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    if (!empty($_SESSION['is_admin']) && !empty($_POST['is_admin'])) {
                        $makeAdmin = 1;
                    }
                }

                // prevent password reuse check omitted for brevity (you had that before)
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins = $pdo->prepare('INSERT INTO users (username, password_hash, is_admin, created_at) VALUES (?, ?, ?, NOW())');
                $ins->execute([$username, $hash, $makeAdmin]);
                $msg = 'Kasutaja loodud edukalt. Eemalda create_user.php pärast kasutamist.';
                $success = true;
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
</head>
<body>
<?php require_once __DIR__ . '/nav.php'; ?>
<main class="container">
    <h1><?= $userCount === 0 ? 'Loo esimene admin kasutaja' : 'Loo uus kasutaja' ?></h1>

    <?php if ($msg): ?>
        <div><?= htmlspecialchars($msg, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
        <form method="post" action="create_user.php">
            <label>Kasutajanimi<br><input name="username" required maxlength="100"></label>
            <label>Parool (vähemalt 8 märki)<br><input type="password" name="password" required minlength="8"></label>
            <label>Korrake parooli<br><input type="password" name="password_confirm" required minlength="8"></label>

            <?php if ($userCount > 0): ?>
                <?php
                // show is_admin checkbox only to current admin if they are logged in
                $showAdminCheckbox = false;
                if (session_status() === PHP_SESSION_NONE) session_start();
                if (!empty($_SESSION['is_admin'])) $showAdminCheckbox = true;
                ?>
                <?php if ($showAdminCheckbox): ?>
                    <label>Teisalda admin õigused? <input type="checkbox" name="is_admin" value="1"></label>
                <?php endif; ?>
            <?php endif; ?>

            <button type="submit">Loo kasutaja</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Logi sisse</a> või eemalda see fail serverist.</p>
    <?php endif; ?>
</main>
</body>
</html>