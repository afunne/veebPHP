<?php
// edit.php — edit a single product in its own window/tab
require_once __DIR__ . '/config.php';

// Basic DB check
if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    echo "Andmebaasi ühendus puudub. Kontrolli config.php seadeid.";
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(404);
    echo "Toode puudub.";
    exit;
}

// Try to load product using standard id, fallback to id_products
$product = null;
try {
    $stmt = $pdo->prepare("SELECT id, name, description, price, image_id FROM products WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if (!$product) {
        $stmt = $pdo->prepare("SELECT id_products AS id, name, description, price, image_id FROM products WHERE id_products = ? LIMIT 1");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
    }
} catch (PDOException $e) {
    // ignore — $product will remain null
}

// Load images for select list (try both id and id_images)
$images = [];
try {
    $images = $pdo->query("SELECT id, filename FROM images ORDER BY created_at DESC")->fetchAll();
} catch (PDOException $e) {
    try {
        $images = $pdo->query("SELECT id_images AS id, filename FROM images ORDER BY created_at DESC")->fetchAll();
    } catch (PDOException $e2) {
        $images = [];
    }
}
?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Muuda toodet — Muudased</title>
    <link rel="stylesheet" href="komm.css">
</head>
<body>
<?php require_once __DIR__ . '/nav.php'; ?>

<main class="container">
    <h1>Muuda toodet</h1>

    <form action="admin_actions.php" method="post" class="card">
        <input type="hidden" name="action" value="update_product">
        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id'], ENT_QUOTES) ?>">

        <label>Nimetus<br>
            <input type="text" name="name" required value="<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>">
        </label>

        <label>Kirjeldus<br>
            <textarea name="description"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
        </label>

        <label>Hind (€)<br>
            <input type="number" step="0.01" name="price" required value="<?= htmlspecialchars($product['price'], ENT_QUOTES) ?>">
        </label>

        <label>Pilt (seosta tootega)<br>
            <select name="image_id">
                <option value="">— Puudub —</option>
                <?php foreach ($images as $img): ?>
                    <?php $sel = (isset($product['image_id']) && $product['image_id'] == $img['id']) ? 'selected' : ''; ?>
                    <option value="<?= htmlspecialchars($img['id'], ENT_QUOTES) ?>" <?= $sel ?>><?= htmlspecialchars($img['filename']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <div class="form-actions">
            <button type="submit" class="btn">Salvesta</button>
            <a class="btn-muted" href="admin.php" target="_blank">Ava admin leht</a>
            <button type="button" class="btn-muted" onclick="window.close();">Sulge aken</button>
        </div>
    </form>

</main>

<?php require_once __DIR__ . '/jalus.php'; ?>