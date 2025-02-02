<?php
require_once '../db/Database.php';

// Establecer el encabezado JSON para evitar problemas con `fetch`
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $costo_unitario = $_POST['costo_unitario'] ?? null;

    if (empty($nombre) || empty($categoria) || $costo_unitario === null) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    $database = new Database();
    $conn = $database->getConnection();

    $query = "INSERT INTO productos (nombre, categoria, costo_unitario) VALUES (:nombre, :categoria, :costo_unitario)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':costo_unitario', $costo_unitario);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Producto añadido correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al añadir el producto.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
