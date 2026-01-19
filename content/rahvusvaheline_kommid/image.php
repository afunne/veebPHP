<?php
// image.php — serveerib BLOB-i või suunab brauseri välisele URL-ile
require_once __DIR__ . '/config.php';

if (!isset($pdo) || !$pdo) {
    http_response_code(500);
    exit('Andmebaasi ühendus puudub.');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(404);
    exit('Pilt puudub');
}

$row = false;
try {
    $stmt = $pdo->prepare("SELECT filename, mime_type, data FROM images WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
} catch (PDOException $e) {
    // ignore and try alternative column
}

if (!$row) {
    try {
        $stmt = $pdo->prepare("SELECT filename, mime_type, data FROM images WHERE id_images = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
    } catch (PDOException $e) {
        $row = false;
    }
}

if (!$row) {
    http_response_code(404);
    exit('Pilt puudub');
}

// Kui data tühi või NULL -> proovime treat'ida kui välis-URL
$data = $row['data'] ?? '';
$mime = $row['mime_type'] ?? '';
$filename = $row['filename'] ?? '';

if (empty($data)) {
    if (filter_var($filename, FILTER_VALIDATE_URL)) {
        // Suuname brauseri välisele pildile
        header('Location: ' . $filename);
        exit;
    }
    http_response_code(404);
    exit('Pilt ei ole saadaval');
}

// teenime BLOB-i
header('Content-Type: ' . $mime);
header('Content-Length: ' . strlen($data));
echo $data;