<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}


function authenticate(PDO $pdo, string $username, string $password) {
    try {
        $stmt = $pdo->prepare('SELECT id, username, password_hash, is_admin FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && !empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
            return [
                'id' => $user['id'],
                'username' => $user['username'],
                'is_admin' => (int)($user['is_admin'] ?? 0),
            ];
        }
    } catch (PDOException $e) {
        // Optionally log error
    }
    return false;
}

function is_logged_in(): bool {
    return session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['user_id']);
}

function is_admin(): bool {
    return is_logged_in() && !empty($_SESSION['is_admin']);
}

function require_login(bool $adminOnly = false): void {
    // If not logged in -> redirect to login page
    if (!is_logged_in()) {
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
        echo '</head><body>Redirecting to <a href="' . $escaped . '">' . $escaped . '</a></body></html>';
        exit;
    }

    // If logged in but adminOnly required and user is NOT admin -> redirect elsewhere (index.php) to avoid loop
    if ($adminOnly && !is_admin()) {
        $target = 'index.php'; // or a "403.php" page if you prefer
        if (!headers_sent()) {
            header('Location: ' . $target);
            exit;
        }
        $escaped = htmlspecialchars($target, ENT_QUOTES | ENT_SUBSTITUTE);
        echo '<!doctype html><html><head>';
        echo '<meta http-equiv="refresh" content="0;url=' . $escaped . '">';
        echo '<script>window.location.href = ' . json_encode($target, JSON_UNESCAPED_SLASHES) . ';</script>';
        echo '</head><body>Redirecting to <a href="' . $escaped . '">' . $escaped . '</a></body></html>';
        exit;
    }

    // else: logged in and (if adminOnly) user is admin — OK
}

// Return current user info from session or null.
function current_user(): ?array {
    if (!is_logged_in()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? null,
        'is_admin' => !empty($_SESSION['is_admin']) ? 1 : 0,
    ];
}

// clears session
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