<?php
// gallery.php
require_once __DIR__ . '/config.php';

// Kontroll, et $pdo on olemas
if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    echo "Andmebaasi ühendus puudub. Kontrolli config.php seadeid.";
    exit;
}

// Proovime esmalt tavapärast veergu "id", kui ebaõnnestub, proovime "id_images"
try {
    $stmt = $pdo->query("SELECT id, filename, description, created_at FROM images ORDER BY created_at DESC");
} catch (PDOException $e) {
    // Kui esimene päring ebaõnnestus, proovime alternatiivset veergu
    $stmt = $pdo->query("SELECT id_images AS id, filename, description, created_at FROM images ORDER BY created_at DESC");
}

$images = $stmt->fetchAll();
?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Galerii — Roosi & Co</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php require_once __DIR__ . '/nav.php'; ?>

<main class="container">
    <h1>Galerii</h1>
    <p>All pildid on salvestatud andmebaasi. Vajuta pildil, et avada suurem vaade (sõltub brauserist).</p>
    <div class="gallery-grid">
        <?php foreach ($images as $img): ?>
            <figure class="gallery-item">
                <a href="image.php?id=<?=urlencode($img['id'])?>" target="_blank">
                    <img src="image.php?id=<?=urlencode($img['id'])?>" alt="<?=htmlspecialchars($img['description'] ?? $img['filename'])?>">
                </a>
                <figcaption>
                    <?=htmlspecialchars($img['description'] ?? $img['filename'])?><br>
                    <small><?=htmlspecialchars($img['created_at'])?></small>
                </figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once __DIR__ . '/jalus.php'; ?>