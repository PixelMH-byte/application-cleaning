<?php
require_once '../db/Database.php';

// Obtener tareas asignadas al trabajador
$database = new Database();
$conn = $database->getConnection();
$user_id = 1; // ID del trabajador actual (puede venir de la sesión)

$query = "SELECT * FROM tasks_time_tracking WHERE user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas del Trabajador</title>
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <h1>Tareas Asignadas</h1>
    <table>
        <thead>
            <tr>
                <th>Zona</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= $task['zone_id'] ?></td>
                    <td><?= $task['status'] ?></td>
                    <td>
                        <a href="start_task.php?task_id=<?= $task['id'] ?>">Iniciar</a>
                        <a href="end_task.php?task_id=<?= $task['id'] ?>">Finalizar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
