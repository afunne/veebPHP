<?php
// auth.php — session helpers + authenticate(username,password)
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

function is_logged_in(): bool {
    return session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['user_id']);
}

// Authenticate using DB users table first; if not found and fallback configured in config.php,
// verify against fallback hashed password. Returns user array on success, false on failure.
function authenticate(PDO $pdo, string $username, string $password) {
    // 1) Try DB lookup
    try {
        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && !empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
            return [
                'id' => $user['id'],
                'username' => $user['username'],
            ];
        }
    } catch (PDOException $e) {
        // DB may be missing or inaccessible — fall through to fallback if configured
    }

    // 2) Optional fallback defined in config.php
    if (isset($GLOBALS['ADMIN_FALLBACK']) && is_array($GLOBALS['ADMIN_FALLBACK'])) {
        $fb = $GLOBALS['ADMIN_FALLBACK'];
        if (!empty($fb['username']) && !empty($fb['password_hash']) && hash_equals((string)$fb['username'], $username)) {
            if (password_verify($password, $fb['password_hash'])) {
                // Synthetic user id to represent fallback login
                return [
                    'id' => 'fallback_' . $fb['username'],
                    'username' => $fb['username'],
                ];
            }
        }
    }

    return false;
}

// require_login() and other helpers unchanged...
function require_login(): void {
    if (is_logged_in()) return;
    $loginUrl = 'login.php';
    $requested = $_SERVER['REQUEST_URI'] ?? null;
    if ($requested) $loginUrl .= '?redirect=' . urlencode($requested);
    if (!headers_sent()) {
        header('Location: ' . $loginUrl);
        exit;
    }
    $escaped = htmlspecialchars($loginUrl, ENT_QUOTES | ENT_SUBSTITUTE);
    echo '<!doctype html><html><head>';
    echo '<meta http-equiv="refresh" content="0;url=' . $escaped . '">';
    echo '<script>window.location.href = ' . json_encode($loginUrl, JSON_UNESCAPED_SLASHES) . ';</script>';
    echo '</head><body>';
    echo 'Redirecting to <a href="' . $escaped . '">' . $escaped . '</a>';
    echo '</body></html>';
    exit;
}

function perform_logout(): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }
}