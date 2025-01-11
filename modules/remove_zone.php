<?php
require_once '../db/Database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['worker_id'], $data['zone_id'])) {
    $worker_id = $data['worker_id'];
    $zone_id = $data['zone_id'];

    // Conexión a la base de datos
    $database = new Database();
    $conn = $database->getConnection();

    // Eliminar la asignación
    $query = "DELETE FROM worker_zones WHERE worker_id = :worker_id AND zone_id = :zone_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':worker_id', $worker_id);
    $stmt->bindParam(':zone_id', $zone_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Zona eliminada con éxito.']);
    } else {
        echo json_encode(['error' => 'Error al eliminar la zona.']);
    }
} else {
    echo json_encode(['error' => 'Datos incompletos.']);
}
?>