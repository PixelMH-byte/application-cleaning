<?php
require_once '../db/Database.php';
require_once '../lib/qrcode-generator/php/qrcode.php';

// Obtenemos la conexión
$database = new Database();
$conn = $database->getConnection();

// Manejo de creación de zona
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $qr_code = uniqid('QR_'); // Generar un código único para la zona

    // Ruta para guardar el QR
    $qr_folder = '../src/qr_codes/';
    if (!is_dir($qr_folder)) {
        mkdir($qr_folder, 0777, true); // Crear carpeta si no existe
    }
    $qr_path = $qr_folder . $qr_code . '.png';

    // Crear una instancia de QRCode
    $qr = QRCode::getMinimumQRCode("Zona: $name\nDescripción: $description\nQR: $qr_code", QR_ERROR_CORRECT_LEVEL_L);

    // Generar la imagen del QR Code
    $image = $qr->createImage(10, 2);

    // Guardar la imagen en un archivo PNG
    imagepng($image, $qr_path);
    imagedestroy($image);

    // Insertar en la base de datos
    $query = "INSERT INTO zones_qr (name, description, qr_code) VALUES (:name, :description, :qr_code)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':qr_code', $qr_code);

    if ($stmt->execute()) {
        header("Location: ../templates/manage_zones.php?success=Zona creada con éxito");
    } else {
        header("Location: ../templates/manage_zones.php?error=Error al crear la zona");
    }
    exit;
}
?>
