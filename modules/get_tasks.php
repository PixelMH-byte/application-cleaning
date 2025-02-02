<?php
require_once '../db/Database.php';

header('Content-Type: application/json');

$database = new Database();
$conn = $database->getConnection();

try {
    // Obtener las zonas desde la tabla zones_qr
    $query = "SELECT id, name FROM zones_qr ORDER BY name ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($zones);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener las zonas: ' . $e->getMessage()]);
}
?>
