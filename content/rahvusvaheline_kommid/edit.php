<?php
// edit.php — edits a single product in its own window/tab, PK detection
require 'config.php';

if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    echo "Andmebaasi ühendus puudub. Kontrolli config.php seadeid.";
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(404);
    echo "Toode puudub."; // for debugging (I had them too much)
    exit;
}

// Determites the primary key column for products (same helper as admin.php)
function get_primary_key_column(PDO $pdo, string $table): ?string {
    try {
        $stmt = $pdo->prepare("SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['Column_name'])) return $row['Column_name'];
    } catch (PDOException $e) {
        // ignore
    }
    $candidates = ['id', 'id_products', 'product_id', 'pk'];
    foreach ($candidates as $c) {
        try {
            $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
            $stmt->execute([$c]);
            if ($stmt->fetch()) return $c;
        } catch (PDOException $e) {
            // ignore 030
        }
    }
    return null;
}

$pkcol = get_primary_key_column($pdo, 'products');

$product = null;
if ($pkcol) {
    try {
        $stmt = $pdo->prepare("SELECT `$pkcol` AS pk, name, description, price, " .
            "(CASE WHEN (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products' AND COLUMN_NAME = 'image_id') > 0 THEN image_id ELSE NULL END) AS image_id " .
            "FROM products WHERE `$pkcol` = ? LIMIT 1");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // ignore and fallback below
    }
}

if (!$product) {
    // fallback attempts (id, id_products)
    try {
        $stmt = $pdo->prepare("SELECT id AS pk, name, description, price, image_id FROM products WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // ignore uwu
    }
}
if (!$product) {
    try {
        $stmt = $pdo->prepare("SELECT id_products AS pk, name, description, price, image_id FROM products WHERE id_products = ? LIMIT 1");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // ignore
    }
}

if (!$product) {
    http_response_code(404);
    echo "Toode puudub.";
    exit;
}

// Load images for select list
$images = [];
try {
    $images = $pdo->query("SELECT id, filename FROM images ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    try {
        $images = $pdo->query("SELECT id_images AS id, filename FROM images ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e2) {
        $images = [];
    }
}
?>
<!doctype html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title>Muuda toodet — Magusad maailm</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="edit.css"> <!-- custom CSS for edit.php -->
</head>
<body class="edit-page">
  <?php require 'nav.php'; ?>

  <main class="container">
    <h1>Muuda toodet</h1>

    <?php if (!empty($_GET['msg'])): ?>
      <div class="flash flash--ok"><?= htmlspecialchars($_GET['msg'], ENT_QUOTES) ?></div>
    <?php endif; ?>

    <form action="admin_actions.php" method="post" class="card">
      <input type="hidden" name="action" value="update_product">
      <input type="hidden" name="id" value="<?= htmlspecialchars($product['pk'], ENT_QUOTES) ?>">
      <input type="hidden" name="return_to" value="edit">

      <label>Nimetus<br>
        <input type="text" name="name" required value="<?= htmlspecialchars($product['name'] ?? '', ENT_QUOTES) ?>">
      </label>

      <label>Kirjeldus<br>
        <textarea name="description"><?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES) ?></textarea>
      </label>

      <label>Hind (€)<br>
        <input type="number"
              name="price"
              step="0.01"
              min="0"
              required
              oninput="if(this.value !== '' && parseFloat(this.value) < 0) this.value = '0.00';"
              value="<?= htmlspecialchars($product['price'] ?? '', ENT_QUOTES) ?>">
      </label>

      <label>Pilt (seosta tootega)<br>
        <select name="image_id">
          <option value="">— Puudub —</option>
          <?php foreach ($images as $img): ?>
            <?php $sel = (isset($product['image_id']) && $product['image_id'] == $img['id']) ? 'selected' : ''; ?>
            <option value="<?= htmlspecialchars($img['id'], ENT_QUOTES) ?>" <?= $sel ?>><?= htmlspecialchars($img['filename'], ENT_QUOTES) ?></option>
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

  <?php require 'jalus.php'; ?>
</body>
</html>