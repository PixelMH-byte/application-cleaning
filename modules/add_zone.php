<?php
require_once '../db/Database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['worker_id'], $data['zone_id'])) {
    $worker_id = $data['worker_id'];
    $zone_id = $data['zone_id'];

    // Conexión a la base de datos
    $database = new Database();
    $conn = $database->getConnection();

    // Insertar la nueva asignación
    $query = "INSERT INTO worker_zones (worker_id, zone_id) VALUES (:worker_id, :zone_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':worker_id', $worker_id);
    $stmt->bindParam(':zone_id', $zone_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Zona asignada con éxito.']);
    } else {
        echo json_encode(['error' => 'Error al asignar la zona.']);
    }
} else {
    echo json_encode(['error' => 'Datos incompletos.']);
}
?>