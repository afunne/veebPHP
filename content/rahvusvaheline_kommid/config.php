<?php
$servername = "localhost";
$username = "husseintahmazov2";
$password = "1234";
$dbname = "husseintahmazov2";
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// Ühendus PDO-ga
$dsn = "mysql:host=$servername;dbname=$username;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    // Arendus: näita veateadet. Tootmises ära väljasta tundlikku infot.
    die("Andmebaasi ühendus ebaõnnestus: " . htmlspecialchars($e->getMessage()));
}
?>
