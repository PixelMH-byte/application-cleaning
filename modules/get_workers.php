<?php
require_once '../db/Database.php';

$database = new Database();
$conn = $database->getConnection();

$query = "SELECT id, name, email FROM users WHERE role_id = 2";
$stmt = $conn->prepare($query);
$stmt->execute();

$workers = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($workers);
?>