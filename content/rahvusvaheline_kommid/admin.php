<?php
// admin.php — haldusleht (uuendatud: products.image_id valik + pisipilt)
require_once __DIR__ . '/config.php';

// Kontroll, et $pdo on olemas
if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    echo "Andmebaasi ühendus puudub. Kontrolli config.php seadeid.";
    exit;
}

// Abifunktsioon: proovi valida veeru nimi (id või id_products)
function selectProducts(PDO $pdo) {
    try {
        return $pdo->query("SELECT id, name, price, image_id FROM products ORDER BY id")->fetchAll();
    } catch (PDOException $e) {
        // proovime alternatiivi id_products ja image_id võib eksisteerida või mitte
        try {
            return $pdo->query("SELECT id_products AS id, name, price, image_id FROM products ORDER BY id_products")->fetchAll();
        } catch (PDOException $e2) {
            // kui ka see ei toimi, proovime ilma image_id välja
            try {
                return $pdo->query("SELECT id, name, price FROM products ORDER BY id")->fetchAll();
            } catch (PDOException $e3) {
                return [];
            }
        }
    }
}

// Lae tooted ja pildid halduspaneeli jaoks (kasutame fallback'e)
$products = selectProducts($pdo);

try {
    $images = $pdo->query("SELECT id, filename, created_at FROM images ORDER BY created_at DESC")->fetchAll();
} catch (PDOException $e) {
    // fallback: id_images
    try {
        $images = $pdo->query("SELECT id_images AS id, filename, created_at FROM images ORDER BY created_at DESC")->fetchAll();
    } catch (PDOException $e2) {
        $images = [];
    }
}

// Kui redigeerime, lae toote andmed (inkl. image_id kui olemas)
$editProduct = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("SELECT id, name, description, price, image_id FROM products WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            $editProduct = $stmt->fetch();
            if (!$editProduct) {
                $stmt = $pdo->prepare("SELECT id_products AS id, name, description, price, image_id FROM products WHERE id_products = ? LIMIT 1");
                $stmt->execute([$id]);
                $editProduct = $stmt->fetch();
            }
        } catch (PDOException $e) {
            // ignore, $editProduct jääb null
        }
    }
}
?>
<?php require_once __DIR__ . '/nav.php'; ?>

<main class="container admin">
    <h1>Admin - Halduse leht</h1>

    <?php if (!empty($_GET['msg'])): ?>
        <div class="flash flash--ok"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <section>
        <h2>Toodete haldus</h2>

        <!-- Lisa / uuenda toode -->
        <form action="admin_actions.php" method="post" class="card">
            <input type="hidden" name="action" value="<?= $editProduct ? 'update_product' : 'add_product' ?>">
            <?php if ($editProduct): ?>
                <input type="hidden" name="id" value="<?=htmlspecialchars($editProduct['id'], ENT_QUOTES)?>">
            <?php endif; ?>
            <label>Nimetus<br><input type="text" name="name" required value="<?= $editProduct ? htmlspecialchars($editProduct['name'], ENT_QUOTES) : '' ?>"></label>
            <label>Kirjeldus<br><textarea name="description"><?= $editProduct ? htmlspecialchars($editProduct['description']) : '' ?></textarea></label>
            <label>Hind (€)<br><input type="number" step="0.01" name="price" required value="<?= $editProduct ? htmlspecialchars($editProduct['price'], ENT_QUOTES) : '' ?>"></label>

            <!-- Pildi valik (kui pilte olemas) -->
            <label>Pilt (seosta tootega)<br>
                <select name="image_id">
                    <option value="">— Puudub —</option>
                    <?php foreach ($images as $img): ?>
                        <?php $sel = ($editProduct && isset($editProduct['image_id']) && $editProduct['image_id'] == $img['id']) ? 'selected' : ''; ?>
                        <option value="<?=htmlspecialchars($img['id'])?>" <?= $sel ?>><?=htmlspecialchars($img['filename'])?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <div class="form-actions">
                <button type="submit"><?= $editProduct ? 'Uuenda toodet' : 'Lisa toode' ?></button>
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
                <tr>
                    <td>
                        <?php if (!empty($p['image_id'])): ?>
                            <img src="image.php?id=<?=urlencode($p['image_id'])?>" style="height:60px;object-fit:cover" alt="">
                        <?php else: ?>
                            <span style="color:var(--muted);font-size:.9rem">—</span>
                        <?php endif; ?>
                    </td>
                    <td><?=htmlspecialchars($p['id'])?></td>
                    <td><?=htmlspecialchars($p['name'])?></td>
                    <td><?=isset($p['price']) ? number_format($p['price'], 2, ',', ' ') . ' €' : ''?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?=urlencode($p['id'])?>" target="_blank" rel="noopener">Muuda</a> |
                        <form action="admin_actions.php" method="post" style="display:inline" onsubmit="return confirm('Kustutada toode?');">
                            <input type="hidden" name="action" value="delete_product">
                            <input type="hidden" name="id" value="<?=htmlspecialchars($p['id'])?>">
                            <button type="submit" class="linklike">Kustuta</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Pildigalerii haldus</h2>
        <form action="admin_actions.php" method="post" enctype="multipart/form-data" class="card">
            <input type="hidden" name="action" value="upload_image">
            <label>Vali pilt (max 5MB)<br><input type="file" name="image" accept="image/*" required></label>
            <label>Kirjeldus<br><input type="text" name="description"></label>
            <div class="form-actions"><button type="submit">Laadi üles</button></div>
        </form>

        <h3>Laaditud pildid</h3>
        <table class="admin-table">
            <thead><tr><th>Preview</th><th>Fail</th><th>Laaditud</th><th>Tegevus</th></tr></thead>
            <tbody>
            <?php foreach ($images as $img): ?>
                <tr>
                    <td><img src="image.php?id=<?=urlencode($img['id'])?>" style="height:60px;object-fit:cover" alt=""></td>
                    <td><?=htmlspecialchars($img['filename'])?></td>
                    <td><?=htmlspecialchars($img['created_at'])?></td>
                    <td class="actions">
                        <form action="admin_actions.php" method="post" style="display:inline" onsubmit="return confirm('Kustutada pilt?');">
                            <input type="hidden" name="action" value="delete_image">
                            <input type="hidden" name="id" value="<?=htmlspecialchars($img['id'])?>">
                            <button type="submit" class="linklike">Kustuta</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once __DIR__ . '/jalus.php'; ?>