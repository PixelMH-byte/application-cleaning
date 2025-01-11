<?php
require_once '../db/Database.php';

if (isset($_GET['worker_id'])) {
    $worker_id = $_GET['worker_id'];

    // Conexión a la base de datos
    $database = new Database();
    $conn = $database->getConnection();

    // Obtener zonas asignadas
    $queryAssigned = "SELECT z.id, z.name, z.description 
                      FROM zones_qr z
                      INNER JOIN worker_zones wz ON z.id = wz.zone_id
                      WHERE wz.worker_id = :worker_id";
    $stmtAssigned = $conn->prepare($queryAssigned);
    $stmtAssigned->bindParam(':worker_id', $worker_id);
    $stmtAssigned->execute();
    $assigned = $stmtAssigned->fetchAll(PDO::FETCH_ASSOC);

    // Obtener zonas no asignadas
    $queryUnassigned = "SELECT z.id, z.name, z.description 
                        FROM zones_qr z
                        WHERE z.id NOT IN (
                            SELECT zone_id FROM worker_zones WHERE worker_id = :worker_id
                        )";
    $stmtUnassigned = $conn->prepare($queryUnassigned);
    $stmtUnassigned->bindParam(':worker_id', $worker_id);
    $stmtUnassigned->execute();
    $unassigned = $stmtUnassigned->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['assigned' => $assigned, 'unassigned' => $unassigned]);
} else {
    echo json_encode(['error' => 'No se proporcionó un ID de trabajador.']);
}
?>