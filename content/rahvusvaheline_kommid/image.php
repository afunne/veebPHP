<?php
// image.php — serves a BLOB or redirects the browser to an external URL
// spolier, it partly doesnt work but i kept it will fix soon
require 'config.php';

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
    // ignore NOW!
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

// If data is empty tries to treat it as an external URL
// (PS: if anyone takes my work this is helping a lot)
$data = $row['data'] ?? '';
$mime = $row['mime_type'] ?? '';
$filename = $row['filename'] ?? '';

if (empty($data)) {
    if (filter_var($filename, FILTER_VALIDATE_URL)) {
        // We redirect the browser to an external image
        header('Location: ' . $filename);
        exit;
    }
    http_response_code(404);
    exit('Pilt ei ole saadaval');
}

// BLOB
header('Content-Type: ' . $mime);
header('Content-Length: ' . strlen($data));
echo $data;