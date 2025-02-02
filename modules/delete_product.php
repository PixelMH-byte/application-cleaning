<?php
require_once '../db/Database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = $data['id'] ?? null;

    if (!$product_id) {
        echo json_encode(['error' => 'ID del producto no proporcionado.']);
        exit;
    }

    try {
        $database = new Database();
        $conn = $database->getConnection();

        // Eliminar relaciones en tareas_productos
        $query = "DELETE FROM tareas_productos WHERE producto_id = :producto_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':producto_id', $product_id);
        $stmt->execute();

        // Eliminar producto
        $query = "DELETE FROM productos WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $product_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente.']);
        } else {
            echo json_encode(['error' => 'Error al eliminar el producto.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Excepción: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Método no permitido.']);
}
?>
