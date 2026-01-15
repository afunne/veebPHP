<?php
// Robustne admin_actions.php — laeb config, kontrollib $pdo ja toetab products.image_id kui olemas
require_once __DIR__ . '/config.php';

// Kontroll, et $pdo on olemas
if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    exit('Andmebaasi ühendus puudub. Kontrolli config.php seadeid.');
}

$action = $_POST['action'] ?? '';

function redirect_back($msg = null) {
    if ($msg) {
        header('Location: admin.php?msg=' . urlencode($msg));
    } else {
        header('Location: admin.php');
    }
    exit;
}

function table_has_column(PDO $pdo, string $table, string $column): bool {
    try {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
        $stmt->execute([$column]);
        return (bool)$stmt->fetch();
    } catch (PDOException $e) {
        return false;
    }
}

// Kas products.image_id on olemas?
$products_has_image_id = table_has_column($pdo, 'products', 'image_id');

if ($action === 'add_product') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $image_id = isset($_POST['image_id']) && $_POST['image_id'] !== '' ? (int)$_POST['image_id'] : null;

    if ($name === '' || $price <= 0) {
        redirect_back('Tühi nimi või vigane hind.');
    }

    if ($products_has_image_id) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $image_id]);
    } else {
        // fallback: ilma image_id
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $price]);
    }

    redirect_back('Toode lisatud.');
}

if ($action === 'update_product') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $image_id = isset($_POST['image_id']) && $_POST['image_id'] !== '' ? (int)$_POST['image_id'] : null;

    if ($id <= 0 || $name === '' || $price <= 0) {
        redirect_back('Vigased andmed.');
    }

    if ($products_has_image_id) {
        // proovime tavalist id-veergu, fallback id_products
        $sqlPrimary = "UPDATE products SET name = ?, description = ?, price = ?, image_id = ? WHERE id = ?";
        $paramsPrimary = [$name, $description, $price, $image_id, $id];
        $sqlAlt = "UPDATE products SET name = ?, description = ?, price = ?, image_id = ? WHERE id_products = ?";
        $paramsAlt = [$name, $description, $price, $image_id, $id];

        // proovime esmalt primary, kui ei mõjuta ridu, proovi alt
        try {
            $stmt = $pdo->prepare($sqlPrimary);
            $stmt->execute($paramsPrimary);
            if ($stmt->rowCount() === 0) {
                $stmt = $pdo->prepare($sqlAlt);
                $stmt->execute($paramsAlt);
            }
            redirect_back('Toode uuendatud.');
        } catch (PDOException $e) {
            // fallback ilma image_id (kui mingi DB ei tunne välja)
            try {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
                $stmt->execute([$name, $description, $price, $id]);
                redirect_back('Toode uuendatud (ilma pildi seoseta).');
            } catch (PDOException $e2) {
                redirect_back('Uuendamine ebaõnnestus.');
            }
        }
    } else {
        // products ei sisalda image_id
        try {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
            $stmt->execute([$name, $description, $price, $id]);
            redirect_back('Toode uuendatud.');
        } catch (PDOException $e) {
            // fallback id_products
            try {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id_products = ?");
                $stmt->execute([$name, $description, $price, $id]);
                redirect_back('Toode uuendatud.');
            } catch (PDOException $e2) {
                redirect_back('Uuendamine ebaõnnestus.');
            }
        }
    }
}

if ($action === 'delete_product') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        try_execute_with_alternatives: {
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$id]);
            } catch (PDOException $e) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM products WHERE id_products = ?");
                    $stmt->execute([$id]);
                } catch (PDOException $e2) {
                    // ignore
                }
            }
        }
    }
    redirect_back('Toode kustutatud.');
}

// --- pildi üleslaadimine ja pildi kustutamine jäävad samaks kui varasem implementatsioon ---
if ($action === 'upload_image') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        redirect_back('Pildi üleslaadimine ebaõnnestus.');
    }

    $file = $_FILES['image'];
    if ($file['size'] > 5 * 1024 * 1024) {
        redirect_back('Fail liiga suur (max 5MB).');
    }

    // Kontrolli MIME-tüüpi
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mime, $allowed, true)) {
        redirect_back('Lubatud vaid pildifailid (jpg, png, gif, webp).');
    }

    $data = file_get_contents($file['tmp_name']);
    $filename = basename($file['name']);
    $description = trim($_POST['description'] ?? '');

    $stmt = $pdo->prepare("INSERT INTO images (filename, mime_type, data, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$filename, $mime, $data, $description]);

    redirect_back('Pilt lisatud.');
}

if ($action === 'delete_image') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            try {
                $stmt = $pdo->prepare("DELETE FROM images WHERE id_images = ?");
                $stmt->execute([$id]);
            } catch (PDOException $e2) {
                // ignore
            }
        }

        // Kui soovid, võid lisada siia ka products.image_id -> NULL puhastuse,
        // kuid kui olemas väisvõti ON DELETE SET NULL, teeb DB seda ise.
        if ($products_has_image_id) {
            try {
                $stmt = $pdo->prepare("UPDATE products SET image_id = NULL WHERE image_id = ?");
                $stmt->execute([$id]);
            } catch (PDOException $e) {
                // ignore
            }
        }
    }
    redirect_back('Pilt kustutatud.');
}

// Kui tegevus tundmatu
redirect_back('Tundmatu tegevus.');