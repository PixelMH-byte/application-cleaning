<?php
require_once '../db/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workerId = json_decode(file_get_contents('php://input'), true)['worker_id'] ?? null;

    if ($workerId) {
        try {
            $database = new Database();
            $conn = $database->getConnection();

            $conn->beginTransaction();

            // Eliminar asignaciones de zonas relacionadas con el trabajador
            $query = "DELETE FROM worker_zones WHERE worker_id = :worker_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':worker_id', $workerId);
            $stmt->execute();

            // Eliminar el trabajador
            $query = "DELETE FROM users WHERE id = :worker_id AND role_id = 2"; // Solo trabajadores
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':worker_id', $workerId);
            $stmt->execute();

            $conn->commit();

            echo json_encode(['success' => 'Trabajador eliminado correctamente.']);
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode(['error' => 'Error al eliminar el trabajador: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'ID de trabajador no proporcionado.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido.']);
}
?>
