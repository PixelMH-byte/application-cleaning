<?php
function logError($message) {
    $logFile = '../logs/errors.log'; // Ruta del archivo de logs
    $date = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$date] $message" . PHP_EOL, FILE_APPEND);
}

require_once '../db/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $assignment_id = $data['assignment_id'] ?? null;
    $qr_code_raw = $data['qr_code'] ?? null;

    logError("Datos recibidos: assignment_id = $assignment_id, qr_code = $qr_code_raw");

    if (!$assignment_id || !$qr_code_raw) {
        logError("Datos insuficientes.");
        echo json_encode(['error' => 'Datos insuficientes.']);
        exit;
    }

    $database = new Database();
    $conn = $database->getConnection();

    // Extraer QR del texto si tiene múltiples líneas
    $qr_code_lines = explode("\n", $qr_code_raw);
    $qr_code = null;

    foreach ($qr_code_lines as $line) {
        if (str_starts_with(trim($line), 'QR:')) {
            $qr_code = trim(str_replace('QR:', '', $line));
            break;
        }
    }

    logError("Código QR procesado: $qr_code");

    if (!$qr_code) {
        logError("Formato de QR no válido.");
        echo json_encode(['error' => 'Formato del QR no válido.']);
        exit;
    }

    // Verificar que el QR corresponde a la zona asignada
    $query = "
        SELECT wz.id, wz.status, wz.start_time, z.qr_code
        FROM worker_zones wz
        INNER JOIN zones_qr z ON wz.zone_id = z.id
        WHERE wz.id = :assignment_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':assignment_id', $assignment_id);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    logError("Resultado de la consulta: " . json_encode($task));

    if (!$task) {
        logError("Tarea no encontrada o código QR incorrecto.");
        echo json_encode(['error' => 'QR no válido o tarea no encontrada.']);
        exit;
    }

    if ($task['qr_code'] !== $qr_code) {
        logError("El código QR no coincide: esperado = " . $task['qr_code']);
        echo json_encode(['error' => 'El código QR no coincide con la tarea asignada.']);
        exit;
    }

    // Actualizar estado según el estado actual
    if ($task['status'] === 'pendiente') {
        $query = "UPDATE worker_zones SET status = 'iniciado', start_time = NOW() WHERE id = :assignment_id";
    } elseif ($task['status'] === 'iniciado') {
        $query = "UPDATE worker_zones SET status = 'finalizado', end_time = NOW() WHERE id = :assignment_id";
    } else {
        logError("La tarea ya está finalizada.");
        echo json_encode(['error' => 'La tarea ya está finalizada.']);
        exit;
    }

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':assignment_id', $assignment_id);

    if ($stmt->execute()) {
        logError("Tarea actualizada exitosamente.");
        echo json_encode(['success' => 'Estado de la tarea actualizado correctamente.']);
    } else {
        logError("Error al actualizar la tarea.");
        echo json_encode(['error' => 'Error al actualizar la tarea.']);
    }
} else {
    logError("Método no permitido.");
    echo json_encode(['error' => 'Método no permitido.']);
}

?>