<?php
// admin.php — full admin page that reliably detects products PK column and uses it for edit links
require 'config.php';
require 'auth.php';
require_login(true);

if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    echo "Andmebaasi ühendus puudub. Kontrolli config.php seadeid."; // for the debugging
    exit;
}

function get_primary_key_column(PDO $pdo, string $table): ?string {
    try {
        $stmt = $pdo->prepare("SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['Column_name'])) {
            return $row['Column_name'];
        }
    } catch (PDOException $e) {
        //ignores <_<
    }

    // Fallback common names
    $candidates = ['id', 'id_products', 'product_id', 'pk'];
    foreach ($candidates as $c) {
        try {
            $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
            $stmt->execute([$c]);
            if ($stmt->fetch()) return $c;
        } catch (PDOException $e) {
            // ignore
        }
    }

    return null;
}

//Selects products and return array of rows
function select_products_with_pk(PDO $pdo): array {
    $pk = get_primary_key_column($pdo, 'products') ?? null;

    // Tries to do queries depending on pk :3
    if ($pk) {
        try {
            $sql = "SELECT `$pk` AS pk, name, price, description, " .
                   "CASE WHEN (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products' AND COLUMN_NAME = 'image_id') > 0 THEN image_id ELSE NULL END AS image_id " .
                   "FROM products ORDER BY `$pk`";
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rows !== false) return $rows;
        } catch (PDOException $e) {
            // ignore and try fallback
        }
    }

    // if it fails I am using ts
    $tryQueries = [
        "SELECT id AS pk, name, price, description, image_id FROM products ORDER BY id",
        "SELECT id_products AS pk, name, price, description, image_id FROM products ORDER BY id_products",
        // generic fallback: select everything and map primary-like fields if present
        "SELECT * FROM products LIMIT 100"
    ];

    foreach ($tryQueries as $q) {
        try {
            $stmt = $pdo->query($q);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($rows !== false && count($rows) > 0) {
                // Ensure each row has 'pk' key
                foreach ($rows as &$r) {
                    if (!isset($r['pk'])) {
                        if (isset($r['id'])) $r['pk'] = $r['id'];
                        elseif (isset($r['id_products'])) $r['pk'] = $r['id_products'];
                        elseif (isset($r['product_id'])) $r['pk'] = $r['product_id'];
                        else $r['pk'] = null;
                    }
                }
                return $rows;
            }
        } catch (PDOException $e) {
            // ignores <_<
        }
    }

    return [];
}

$products = select_products_with_pk($pdo);

// Load images (robust: id or id_images)
function select_images(PDO $pdo): array {
    try {
        $stmt = $pdo->query("SELECT id, filename, created_at FROM images ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        try {
            $stmt = $pdo->query("SELECT id_images AS id, filename, created_at FROM images ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e2) {
            return [];
        }
    }
}

$images = select_images($pdo);

// If editing from the admin page, try to load edit product using PK detection:
$editProduct = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    if ($editId > 0) {
        // discover pk column
        $pkcol = get_primary_key_column($pdo, 'products');
        if ($pkcol) {
            try {
                $stmt = $pdo->prepare("SELECT `$pkcol` AS pk, name, description, price, " .
                    "(CASE WHEN (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products' AND COLUMN_NAME = 'image_id') > 0 THEN image_id ELSE NULL END) AS image_id " .
                    "FROM products WHERE `$pkcol` = ? LIMIT 1");
                $stmt->execute([$editId]);
                $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // ignores <_<
            }
        } else {
            // last-resort: try id and id_products
            try {
                $stmt = $pdo->prepare("SELECT id AS pk, name, description, price, image_id FROM products WHERE id = ? LIMIT 1");
                $stmt->execute([$editId]);
                $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$editProduct) {
                    $stmt = $pdo->prepare("SELECT id_products AS pk, name, description, price, image_id FROM products WHERE id_products = ? LIMIT 1");
                    $stmt->execute([$editId]);
                    $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            } catch (PDOException $e) {
                // ignores <_<
            }
        }
    }
}
?>
<!doctype html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title>Admin — Roosi & Co</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php require 'nav.php'; ?>

  <main class="container admin">
    <h1>Admin - Halduse leht</h1>

    <?php if (!empty($_GET['msg'])): ?>
      <div class="flash flash--ok"><?= htmlspecialchars($_GET['msg'], ENT_QUOTES) ?></div>
    <?php endif; ?>

    <section>
      <h2>Toodete haldus</h2>

      <form action="admin_actions.php" method="post" class="card">
        <input type="hidden" name="action" value="<?= $editProduct ? 'update_product' : 'add_product' ?>">
        <?php if ($editProduct): ?>
          <input type="hidden" name="id" value="<?= htmlspecialchars($editProduct['pk'], ENT_QUOTES) ?>">
        <?php endif; ?>

        <label>Nimetus<br>
          <input type="text" name="name" required value="<?= $editProduct ? htmlspecialchars($editProduct['name'] ?? '', ENT_QUOTES) : '' ?>">
        </label>

        <label>Kirjeldus<br>
          <textarea name="description"><?= $editProduct ? htmlspecialchars($editProduct['description'] ?? '', ENT_QUOTES) : '' ?></textarea>
        </label>

        <label>Hind (€)<br>
        <input type="number"
              name="price"
              step="0.01"
              min="0"
              required
              oninput="if(this.value !== '' && parseFloat(this.value) < 0) this.value = '0.00';"
              value="<?= $editProduct ? htmlspecialchars($editProduct['price'], ENT_QUOTES) : '' ?>">
        </label>

        <label>Pilt (seosta tootega)<br>
          <select name="image_id">
            <option value="">— Puudub —</option>
            <?php foreach ($images as $img): ?>
              <?php $sel = ($editProduct && isset($editProduct['image_id']) && $editProduct['image_id'] == $img['id']) ? 'selected' : ''; ?>
              <option value="<?= htmlspecialchars($img['id'], ENT_QUOTES) ?>" <?= $sel ?>><?= htmlspecialchars($img['filename'], ENT_QUOTES) ?></option>
            <?php endforeach; ?>
          </select>
        </label>

        <div class="form-actions">
          <button type="submit" class="btn"><?= $editProduct ? 'Uuenda toodet' : 'Lisa toode' ?></button>
          <?php if ($editProduct): ?>
            <a class="btn-muted" href="admin.php">Lõpeta redigeerimine</a>
          <?php endif; ?>
        </div>
      </form>

      <h3>Olemasolevad tooted</h3>
      <table class="admin-table">
        <thead><tr><th>Preview</th><th>ID</th><th>Nimetus</th><th>Hind</th><th>Tegevus</th></tr></thead>
        <tbody>
          <?php foreach ($products as $p): ?>
            <?php $pk = $p['pk'] ?? ($p['id'] ?? ($p['id_products'] ?? null)); ?>
            <tr>
              <td>
                <?php if (!empty($p['image_id'])): ?>
                  <img src="image.php?id=<?= urlencode($p['image_id']) ?>" style="height:60px;object-fit:cover" alt="">
                <?php else: ?>
                  <span style="color:var(--muted);font-size:.9rem">—</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($pk, ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($p['name'] ?? '', ENT_QUOTES) ?></td>
              <td><?= isset($p['price']) ? number_format((float)$p['price'], 2, ',', ' ') . ' €' : '' ?></td>
              <td class="actions">
                <?php if ($pk !== null): ?>
                  <a href="edit.php?id=<?= urlencode($pk) ?>" target="_blank" rel="noopener">Muuda</a> |
                  <form action="admin_actions.php" method="post" style="display:inline" onsubmit="return confirm('Kustutada toode?');">
                    <input type="hidden" name="action" value="delete_product">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($pk, ENT_QUOTES) ?>">
                    <button type="submit" class="linklike">Kustuta</button>
                  </form>
                <?php else: ?>
                  <span style="color:var(--muted)">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <section>
      <h2>Pildigalerii haldus</h2>
      <!-- image upload form (file OR URL) unchanged -->
      <form action="admin_actions.php" method="post" enctype="multipart/form-data" class="card">
        <input type="hidden" name="action" value="upload_image">
        <label>Vali pilt (max 5MB)<br><input type="file" name="image" accept="image/*"></label>
        <label>Või sisesta pildi URL (http(s))<br><input type="url" name="image_url" placeholder="https://example.com/pilt.jpg"></label>
        <small>Kui mõlemad on täidetud, võetakse üleslaetud fail (file) prioriteedina.</small>
        <label>Kirjeldus<br><input type="text" name="description"></label>
        <div class="form-actions"><button type="submit" class="btn">Laadi üles / Salvesta URL</button></div>
      </form>

      <h3>Laaditud pildid</h3>
      <table class="admin-table">
        <thead><tr><th>Preview</th><th>Fail</th><th>Laaditud</th><th>Tegevus</th></tr></thead>
        <tbody>
          <?php foreach ($images as $img): ?>
          <tr>
            <td><img src="image.php?id=<?= urlencode($img['id']) ?>" style="height:60px;object-fit:cover" alt=""></td>
            <td><?= htmlspecialchars($img['filename'], ENT_QUOTES) ?></td>
            <td><?= htmlspecialchars($img['created_at'] ?? '', ENT_QUOTES) ?></td>
            <td class="actions">
              <form action="admin_actions.php" method="post" style="display:inline" onsubmit="return confirm('Kustutada pilt?');">
                <input type="hidden" name="action" value="delete_image">
                <input type="hidden" name="id" value="<?= htmlspecialchars($img['id'], ENT_QUOTES) ?>">
                <button type="submit" class="linklike">Kustuta</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

  </main>

  <?php require 'jalus.php';?>
</body>
</html>