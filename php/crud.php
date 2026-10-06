<?php
/**
 * CRUD handler untuk tabel wisuda
 * Menggantikan DataTables Editor (berbayar) dengan implementasi PDO murni
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Load konfigurasi database dari file terpisah (tidak di-commit ke Git)
$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    http_response_code(500);
    echo json_encode(['error' => 'File config.php tidak ditemukan. Salin config.example.php menjadi config.php dan isi kredensialnya.']);
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
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Pastikan tabel ada
$pdo->exec("CREATE TABLE IF NOT EXISTS `wisuda` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `seksi` varchar(255) DEFAULT NULL,
    `job` varchar(255) DEFAULT NULL,
    `pic` varchar(255) DEFAULT NULL,
    `persentase` varchar(255) DEFAULT NULL,
    `ket` TEXT DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$action = $_POST['action'] ?? $_GET['action'] ?? 'read';

switch ($action) {

    // ── READ: dipakai DataTables sebagai ajax source ──────────────────────────
    case 'read':
        $stmt = $pdo->query("SELECT id, seksi, job, pic, persentase, ket FROM wisuda ORDER BY seksi ASC");
        $rows = $stmt->fetchAll();
        // Format sesuai yang diharapkan DataTables (field "data")
        echo json_encode(['data' => $rows]);
        break;

    // ── CREATE ────────────────────────────────────────────────────────────────
    case 'create':
        $seksi     = trim($_POST['seksi'] ?? '');
        $job       = trim($_POST['job'] ?? '');
        $pic       = trim($_POST['pic'] ?? '');
        $persentase = trim(str_replace('%', '', $_POST['persentase'] ?? '0'));
        $ket       = trim($_POST['ket'] ?? '');

        // Validasi
        if (!is_numeric($persentase) || $persentase < 0 || $persentase > 100) {
            echo json_encode(['error' => 'Persentase harus berupa angka 0–100']);
            exit;
        }

        $stmt = $pdo->prepare(
            "INSERT INTO wisuda (seksi, job, pic, persentase, ket) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$seksi, $job, $pic, $persentase, $ket]);
        $newId = $pdo->lastInsertId();

        $row = $pdo->query("SELECT id, seksi, job, pic, persentase, ket FROM wisuda WHERE id = $newId")->fetch();
        echo json_encode(['data' => [$row]]);
        break;

    // ── UPDATE ────────────────────────────────────────────────────────────────
    case 'edit':
        $id        = (int)($_POST['id'] ?? 0);
        $seksi     = trim($_POST['seksi'] ?? '');
        $job       = trim($_POST['job'] ?? '');
        $pic       = trim($_POST['pic'] ?? '');
        $persentase = trim(str_replace('%', '', $_POST['persentase'] ?? '0'));
        $ket       = trim($_POST['ket'] ?? '');

        if ($id <= 0) {
            echo json_encode(['error' => 'ID tidak valid']);
            exit;
        }
        if (!is_numeric($persentase) || $persentase < 0 || $persentase > 100) {
            echo json_encode(['error' => 'Persentase harus berupa angka 0–100']);
            exit;
        }

        $stmt = $pdo->prepare(
            "UPDATE wisuda SET seksi=?, job=?, pic=?, persentase=?, ket=? WHERE id=?"
        );
        $stmt->execute([$seksi, $job, $pic, $persentase, $ket, $id]);

        $row = $pdo->query("SELECT id, seksi, job, pic, persentase, ket FROM wisuda WHERE id = $id")->fetch();
        echo json_encode(['data' => [$row]]);
        break;

    // ── DELETE ────────────────────────────────────────────────────────────────
    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['error' => 'ID tidak valid']);
            exit;
        }
        $pdo->prepare("DELETE FROM wisuda WHERE id=?")->execute([$id]);
        echo json_encode(['data' => []]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Action tidak dikenal']);
        break;
}
