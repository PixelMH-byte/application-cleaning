<?php
require_once '../db/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $assignment_id = $data['assignment_id'] ?? null;
    $qr_code = $data['qr_code'] ?? null;

    if (!$assignment_id || !$qr_code) {
        echo json_encode(['error' => 'Datos insuficientes.']);
        exit;
    }

    $database = new Database();
    $conn = $database->getConnection();

    // Verificar que el QR corresponde a la zona asignada
    $query = "
        SELECT wz.id, wz.status, wz.start_time, z.qr_code
        FROM worker_zones wz
        INNER JOIN zones_qr z ON wz.zone_id = z.id
        WHERE wz.id = :assignment_id AND z.qr_code = :qr_code
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':assignment_id', $assignment_id);
    $stmt->bindParam(':qr_code', $qr_code);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        echo json_encode(['error' => 'QR no válido o tarea no encontrada.']);
        exit;
    }

    // Actualizar estado según el estado actual
    if ($task['status'] === 'pendiente') {
        $query = "UPDATE worker_zones SET status = 'iniciado', start_time = NOW() WHERE id = :assignment_id";
    } elseif ($task['status'] === 'iniciado') {
        $query = "UPDATE worker_zones SET status = 'finalizado', end_time = NOW() WHERE id = :assignment_id";
    } else {
        echo json_encode(['error' => 'Tarea ya finalizada.']);
        exit;
    }

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':assignment_id', $assignment_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Estado de la tarea actualizado correctamente.']);
    } else {
        echo json_encode(['error' => 'Error al actualizar la tarea.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido.']);
}
?>
