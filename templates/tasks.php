<?php
session_start();
require_once '../db/Database.php';

// Verificar rol
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    header('Location: login.php');
    exit;
}

$worker_id = $_SESSION['user_id'];
$database = new Database();
$conn = $database->getConnection();

// Consultar zonas asignadas al trabajador
$query = "
    SELECT wz.id AS assignment_id, z.name, z.description, wz.status, wz.start_time, wz.end_time
    FROM worker_zones wz
    INNER JOIN zones_qr z ON wz.zone_id = z.id
    WHERE wz.worker_id = :worker_id
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':worker_id', $worker_id);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelClean - Tareas Asignadas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../src/styles.css">
	 
</head>
<body>
    <!-- Navbar -->
     <nav class="navbar navbar-light bg-light fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-flex align-items-center" href="worker_menu.php">
            <img src="../src/logoclean.png" alt="PixelClean Logo" width="60" height="60" class="d-inline-block align-text-top me-2">
            <span>Tareas Asignadas</span>
        </a>
           <a href="../db/logout.php" class="btn d-flex align-items-center logout-button">
            <img src="../src/logout.png" alt="Logout" width="40" height="40" class="d-inline-block"> 
       </a>
    </div>
</nav>
<div class="container mt-5">
    <h1 class="text-center">Tareas Asignadas</h1>
    <div class="table-responsive d-none d-md-block mt-4">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Zona</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task['name']) ?></td>
                        <td><?= htmlspecialchars($task['description']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($task['status'])) ?></td>
                        <td><?= $task['start_time'] ? htmlspecialchars($task['start_time']) : '—' ?></td>
                        <td><?= $task['end_time'] ? htmlspecialchars($task['end_time']) : '—' ?></td>
                        <td>
                            <?php if ($task['status'] !== 'finalizado'): ?>
                                <button class="btn btn-primary btn-sm" onclick="scanQRCode(<?= $task['assignment_id'] ?>)">
                                    Escanear QR
                                </button>
                            <?php else: ?>
                                <span class="text-success">Completado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Versión de la tabla como "cards" para pantallas pequeñas -->
    <div class="d-md-none mt-4">
        <?php foreach ($tasks as $task): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Zona: <?= htmlspecialchars($task['name']) ?></h5>
                    <p class="card-text"><strong>Descripción:</strong> <?= htmlspecialchars($task['description']) ?></p>
                    <p class="card-text"><strong>Estado:</strong> <?= ucfirst(htmlspecialchars($task['status'])) ?></p>
                    <p class="card-text"><strong>Inicio:</strong> <?= $task['start_time'] ? htmlspecialchars($task['start_time']) : '—' ?></p>
                    <p class="card-text"><strong>Fin:</strong> <?= $task['end_time'] ? htmlspecialchars($task['end_time']) : '—' ?></p>
                    <div>
                        <?php if ($task['status'] !== 'finalizado'): ?>
                            <button class="btn btn-primary btn-sm" onclick="scanQRCode(<?= $task['assignment_id'] ?>)">
                                Escanear QR
                            </button>
                        <?php else: ?>
                            <span class="text-success">Completado</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


    <script>
        async function scanQRCode(assignmentId) {
            const qrCode = prompt("Escanea el código QR o ingrésalo manualmente:");

            if (qrCode) {
                const response = await fetch('../modules/handle_qr.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ assignment_id: assignmentId, qr_code: qrCode })
                });

                const result = await response.json();
                if (result.success) {
                    alert(result.success);
                    location.reload(); // Recargar la página para ver los cambios
                } else {
                    alert(result.error || 'Error al procesar el QR.');
                }
            }
        }
    </script>
</body>
</html>
