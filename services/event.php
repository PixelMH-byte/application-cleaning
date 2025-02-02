<?php
require_once '../db/Database.php';

function logMessage($message) {
    $logFile = '../logs/event_log.txt';
    $date = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$date] $message" . PHP_EOL, FILE_APPEND);
}

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    $queryBackup = "
        INSERT INTO task_history (assignment_id, worker_id, zone_id, status, start_time, end_time)
        SELECT wz.id, wz.worker_id, z.id AS zone_id, wz.status, wz.start_time, wz.end_time
        FROM worker_zones wz
        INNER JOIN zones_qr z ON wz.zone_id = z.id
    ";

    $stmt = $conn->prepare($queryBackup);
    if ($stmt->execute()) {
        logMessage("✅ Datos de tareas respaldados en task_history.");
    } else {
        logMessage("❌ Error al respaldar los datos.");
    }

    $queryReset = "UPDATE worker_zones SET status = 'pendiente', start_time = NULL, end_time = NULL";
    $stmt = $conn->prepare($queryReset);
    
    if ($stmt->execute()) {
        logMessage("✅ Estados de tareas restablecidos en worker_zones.");
        echo json_encode(["success" => "Tareas reiniciadas correctamente."]);
    } else {
        logMessage("❌ Error al restablecer los estados.");
        echo json_encode(["error" => "Error al restablecer las tareas."]);
    }
} catch (Exception $e) {
    logMessage("❌ Error general: " . $e->getMessage());
    echo json_encode(["error" => "Error en el script: " . $e->getMessage()]);
}
?>
