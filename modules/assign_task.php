<?php
require_once '../db/Database.php';

$data = json_decode(file_get_contents("php://input"), true);
$worker_id = $data['worker_id'];
$task = $data['task'];

$database = new Database();
$conn = $database->getConnection();

$query = "INSERT INTO tasks (worker_id, task, created_at) VALUES (:worker_id, :task, NOW())";
$stmt = $conn->prepare($query);
$stmt->bindParam(':worker_id', $worker_id);
$stmt->bindParam(':task', $task);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Tarea asignada con éxito.']);
} else {
    echo json_encode(['error' => 'Error al asignar la tarea.']);
}
