<?php
// Database connection
$host = 'localhost';      // Your database host
$dbname = 'poltekgt_aset'; // Your database name
$user = 'poltekgt_ridwan';   // Your database username
$pass = 'Bismillah14!';   // Your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to calculate the average salary (replace 'your_table' and 'persentase' with actual names)
    $query = "SELECT AVG(persentase) AS avg_progress FROM wisuda";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch the average salary
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Output the average salary (formatted as a float)
    echo floatval($result['avg_progress']);
} catch (PDOException $e) {
    // Handle connection errors
    echo 'Database error: ' . $e->getMessage();
}
