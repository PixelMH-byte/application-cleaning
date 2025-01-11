<?php
require_once '../db/Database.php';

// Verificar si se recibió un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_zones.php?error=ID de zona inválido");
    exit;
}

// Obtenemos la conexión
$database = new Database();
$conn = $database->getConnection();

// Obtener datos de la zona para eliminar el QR
$query = "SELECT qr_code FROM zones_qr WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$zone = $stmt->fetch(PDO::FETCH_ASSOC);

if ($zone) {
    // Eliminar el archivo QR
    $qr_path = "../qr_codes/{$zone['qr_code']}.png";
    if (file_exists($qr_path)) {
        unlink($qr_path);
    }

    // Eliminar la zona de la base de datos
    $query = "DELETE FROM zones_qr WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $_GET['id']);

    if ($stmt->execute()) {
        header("Location: ../templates/manage_zones.php?success=Zona eliminada con éxito");
    } else {
        header("Location: ../templates/manage_zones.php?error=Error al eliminar la zona");
    }
} else {
    header("Location: ../templates/manage_zones.php?error=Zona no encontrada");
}
exit;
