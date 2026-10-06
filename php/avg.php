<?php
// Load konfigurasi database dari file terpisah (tidak di-commit ke Git)
$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    echo 'Error: File config.php tidak ditemukan.';
    exit;
}
require_once $configFile;

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT AVG(persentase) AS avg_progress FROM wisuda";
    $stmt  = $pdo->prepare($query);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo floatval($result['avg_progress']);

} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}
