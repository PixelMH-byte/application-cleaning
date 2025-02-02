<?php
require_once '../db/Database.php';

$database = new Database();
$conn = $database->getConnection();

$query = "SELECT id, nombre, categoria, costo_unitario FROM productos ORDER BY nombre ASC";
$stmt = $conn->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($productos);
?>
