<?php
// Load DB connection (adjust path if db.php is in a different folder)
require_once __DIR__ . '/config.php';

// Safety check: ensure $pdo was created
if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    echo "Andmebaasi ühendus puudub. Kontrolli db.php seadeid ja faili asukohta.";
    exit;
    
}

// Fetch products
$stmt = $pdo->query("SELECT id_products , name, description, price FROM products ORDER BY id_products");
$products = $stmt->fetchAll();
?>

<?php require_once __DIR__ . '/nav.php';
?>

<main class="container">
    <h1>Hinnakiri</h1>

    <table class="price-table">
        <thead>
        <tr><th>Toode / Teenus</th><th>Kirjeldus</th><th>Hind (€)</th></tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?=htmlspecialchars($p['name'])?></td>
                <td><?=nl2br(htmlspecialchars($p['description']))?></td>
                <td class="price"><?=number_format($p['price'], 2, ',', ' ')?> €</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once __DIR__ . '/jalus.php'; ?>