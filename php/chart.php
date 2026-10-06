<?php
/**
 * Endpoint data untuk grafik — mengembalikan rata-rata progress per seksi
 */
header('Content-Type: application/json');

$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    echo json_encode(['error' => 'config.php tidak ditemukan']);
    exit;
}
require_once $configFile;

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER, DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Rata-rata progress per seksi
    $stmt = $pdo->query(
        "SELECT seksi, AVG(CAST(persentase AS DECIMAL(10,2))) AS avg_progress,
                COUNT(*) AS total_job,
                SUM(CASE WHEN CAST(persentase AS DECIMAL(10,2)) = 100 THEN 1 ELSE 0 END) AS done_job
         FROM wisuda
         WHERE seksi IS NOT NULL AND seksi != ''
         GROUP BY seksi
         ORDER BY seksi ASC"
    );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Overall average
    $stmtAvg = $pdo->query("SELECT AVG(CAST(persentase AS DECIMAL(10,2))) AS overall FROM wisuda");
    $overall = $stmtAvg->fetch(PDO::FETCH_ASSOC)['overall'] ?? 0;

    echo json_encode([
        'overall'   => round(floatval($overall), 2),
        'per_seksi' => array_map(function($r) {
            return [
                'seksi'        => $r['seksi'],
                'avg_progress' => round(floatval($r['avg_progress']), 2),
                'total_job'    => (int)$r['total_job'],
                'done_job'     => (int)$r['done_job'],
            ];
        }, $rows)
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
