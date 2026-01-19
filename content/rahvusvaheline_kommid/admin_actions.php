<?php
// admin_actions.php — full implementation for product and image CRUD
// Supports: add/update/delete product, upload image (file or URL), delete image.
// Handles return_to=edit to redirect back to edit.php after update.
require_once __DIR__ . '/config.php';

// Basic DB check
if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    exit('Andmebaasi ühenduse puudub. Kontrolli config.php seadeid.');
}

$action = $_POST['action'] ?? '';
$return_to = $_POST['return_to'] ?? '';

/**
 * Redirect helper that appends optional message as ?msg=
 */
function redirect_to(string $url, string $msg = null): void {
    if ($msg !== null && $msg !== '') {
        $sep = (strpos($url, '?') === false) ? '?' : '&';
        $url .= $sep . 'msg=' . urlencode($msg);
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Check if a table has a given column
 */
function table_has_column(PDO $pdo, string $table, string $column): bool {
    try {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $stmt->execute([$column]);
        return (bool)$stmt->fetch();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Execute primary SQL; if it affects 0 rows, try alternative SQL.
 * Returns true if any statement affected rows.
 */
function exec_with_fallback(PDO $pdo, string $sqlPrimary, array $paramsPrimary = [], string $sqlAlt = null, array $paramsAlt = []): bool {
    try {
        $stmt = $pdo->prepare($sqlPrimary);
        $stmt->execute($paramsPrimary);
        if ($stmt->rowCount() > 0) return true;
    } catch (PDOException $e) {
        // fall through to alternative if provided
    }

    if ($sqlAlt !== null) {
        try {
            $stmt = $pdo->prepare($sqlAlt);
            $stmt->execute($paramsAlt);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // both failed
        }
    }

    return false;
}

/**
 * Determine return URL after actions.
 * If return_to === 'edit' and id given, return edit.php?id=...
 * Otherwise return admin.php
 */
function determine_return_url(int $id = 0, string $return_to = ''): string {
    if ($return_to === 'edit' && $id > 0) {
        return 'edit.php?id=' . urlencode($id);
    }
    return 'admin.php';
}

// Detect if products table has image_id column
$products_has_image_id = table_has_column($pdo, 'products', 'image_id');

if ($action === 'add_product') {
    $name = trim((string)($_POST['name'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    // Round price to 2 decimals and prevent negatives
    $price = round((float)($_POST['price'] ?? 0), 2);
    $image_id = (isset($_POST['image_id']) && $_POST['image_id'] !== '') ? (int)$_POST['image_id'] : null;

    // Reject negative prices (allow 0 if desired; change to <=0 to forbid zeros)
    if ($name === '' || $price < 0) {
        redirect_to('admin.php', 'Tühi nimi või vigane hind.');
    }

    try {
        if ($products_has_image_id) {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $image_id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
            $stmt->execute([$name, $description, $price]);
        }
        redirect_to('admin.php', 'Toode lisatud.');
    } catch (PDOException $e) {
        redirect_to('admin.php', 'Toote lisamine ebaõnnestus: ' . $e->getMessage());
    }
}

if ($action === 'update_product') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim((string)($_POST['name'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    // Round price to 2 decimals, prevent negatives
    $price = round((float)($_POST['price'] ?? 0), 2);
    $image_id = (isset($_POST['image_id']) && $_POST['image_id'] !== '') ? (int)$_POST['image_id'] : null;

    $return_url = determine_return_url($id, $return_to);

    if ($id <= 0 || $name === '' || $price < 0) {
        redirect_to($return_url, 'Vigased andmed: kontrolli nime ja hinna.');
    }

    try {
        if ($products_has_image_id) {
            // Try update including image_id (id, then id_products)
            $ok = exec_with_fallback(
                $pdo,
                "UPDATE products SET name = ?, description = ?, price = ?, image_id = ? WHERE id = ?",
                [$name, $description, $price, $image_id, $id],
                "UPDATE products SET name = ?, description = ?, price = ?, image_id = ? WHERE id_products = ?",
                [$name, $description, $price, $image_id, $id]
            );
            if ($ok) {
                redirect_to($return_url, 'Toode uuendatud.');
            }

            // Try update without image_id
            $ok2 = exec_with_fallback(
                $pdo,
                "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?",
                [$name, $description, $price, $id],
                "UPDATE products SET name = ?, description = ?, price = ? WHERE id_products = ?",
                [$name, $description, $price, $id]
            );
            if ($ok2) {
                redirect_to($return_url, 'Toode uuendatud (ilma pildi seoseta).');
            }

            // nothing affected
            redirect_to($return_url, 'Uuendamine ei muutnud ridu — kontrolli id-väärtust.');
        } else {
            // No image_id column present — update by id or id_products
            $ok = exec_with_fallback(
                $pdo,
                "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?",
                [$name, $description, $price, $id],
                "UPDATE products SET name = ?, description = ?, price = ? WHERE id_products = ?",
                [$name, $description, $price, $id]
            );
            if ($ok) {
                redirect_to($return_url, 'Toode uuendatud.');
            }
            redirect_to($return_url, 'Uuendamine ei muutnud ridu — kontrolli id-väärtust.');
        }
    } catch (PDOException $e) {
        redirect_to($return_url, 'DB viga: ' . $e->getMessage());
    }
}

if ($action === 'delete_product') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        try {
            exec_with_fallback(
                $pdo,
                "DELETE FROM products WHERE id = ?",
                [$id],
                "DELETE FROM products WHERE id_products = ?",
                [$id]
            );
        } catch (PDOException $e) {
            // ignore
        }
    }
    redirect_to('admin.php', 'Toode kustutatud.');
}

// Image upload (file OR URL)
if ($action === 'upload_image') {
    // File has priority over URL
    $fileOk = isset($_FILES['image']) && isset($_FILES['image']['error']) && $_FILES['image']['error'] === UPLOAD_ERR_OK;
    $image_url = trim((string)($_POST['image_url'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));

    if ($fileOk) {
        $file = $_FILES['image'];
        if ($file['size'] > 5 * 1024 * 1024) {
            redirect_to('admin.php', 'Fail liiga suur (max 5MB).');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mime, $allowed, true)) {
            redirect_to('admin.php', 'Lubatud vaid pildifailid (jpg, png, gif, webp).');
        }

        $data = file_get_contents($file['tmp_name']);
        $filename = basename($file['name']);

        try {
            $stmt = $pdo->prepare("INSERT INTO images (filename, mime_type, data, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$filename, $mime, $data, $description]);
            redirect_to('admin.php', 'Pilt lisatud (fail).');
        } catch (PDOException $e) {
            redirect_to('admin.php', 'Pildi lisamine ebaõnnestus: ' . $e->getMessage());
        }
    }

    if ($image_url !== '') {
        if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
            redirect_to('admin.php', 'URL ei ole kehtiv.');
        }
        $scheme = parse_url($image_url, PHP_URL_SCHEME);
        if (!in_array(strtolower((string)$scheme), ['http', 'https'], true)) {
            redirect_to('admin.php', 'Lubatud ainult http või https URL-id.');
        }

        try {
            // store URL in filename, mime_type='external', data as empty string
            $stmt = $pdo->prepare("INSERT INTO images (filename, mime_type, data, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$image_url, 'external', '', $description]);
            redirect_to('admin.php', 'Pilt lisatud (URL).');
        } catch (PDOException $e) {
            redirect_to('admin.php', 'URL salvestamine ebaõnnestus: ' . $e->getMessage());
        }
    }

    redirect_to('admin.php', 'Ei faili ega URL-i üleslaadimiseks.');
}

if ($action === 'delete_image') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        try {
            exec_with_fallback(
                $pdo,
                "DELETE FROM images WHERE id = ?",
                [$id],
                "DELETE FROM images WHERE id_images = ?",
                [$id]
            );
        } catch (PDOException $e) {
            // ignore
        }

        // If products.image_id exists, clear references
        if (table_has_column($pdo, 'products', 'image_id')) {
            try {
                $stmt = $pdo->prepare("UPDATE products SET image_id = NULL WHERE image_id = ?");
                $stmt->execute([$id]);
            } catch (PDOException $e) {
                // ignore
            }
        }
    }
    redirect_to('admin.php', 'Pilt kustutatud.');
}

// Unknown action
redirect_to('admin.php', 'Tundmatu tegevus.');