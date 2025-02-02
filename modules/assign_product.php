<?php
require_once '../db/Database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Registrar datos recibidos en un log
file_put_contents('../logs/debug.log', "Datos recibidos: " . json_encode($_POST) . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tarea_id = $_POST['tarea_id'] ?? null;
    $producto_id = $_POST['producto_id'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;
    $turno = $_POST['turno'] ?? null;

    if (!$tarea_id || !$producto_id || !$cantidad || !$turno) {
        echo json_encode(['error' => 'Datos insuficientes.', 'datos_recibidos' => $_POST]);
        exit;
    }

    try {
        $database = new Database();
        $conn = $database->getConnection();

        $query = "INSERT INTO tareas_productos (tarea_id, producto_id, cantidad, turno) 
                  VALUES (:tarea_id, :producto_id, :cantidad, :turno)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':tarea_id', $tarea_id);
        $stmt->bindParam(':producto_id', $producto_id);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':turno', $turno);

        if ($stmt->execute()) {
            echo json_encode(['success' => 'Producto asignado correctamente.']);
        } else {
            echo json_encode(['error' => 'Error al asignar el producto.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => "Excepción: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Método no permitido.']);
}

?>
