<?php
require_once '../lib/qrcode-generator/php/qrcode.php'; 

if (isset($_GET['qr_code'])) {
    $qr_code = $_GET['qr_code'];
    $file_path = "../src/qr_codes/{$qr_code}.png";

    // Crear carpeta para almacenar los códigos QR si no existe
    if (!file_exists('../src/qr_codes')) {
        mkdir('../src/qr_codes', 0777, true);
    }
	
    // Crear una instancia de QRCode
    $qr = QRCode::getMinimumQRCode($qr_code, QR_ERROR_CORRECT_LEVEL_L);

    // Generar la imagen del QR Code
    $image = $qr->createImage(10, 2); 

    // Guardar la imagen en un archivo PNG
    imagepng($image, $file_path);
    imagedestroy($image); 

    // Forzar la descarga del archivo generado
    header('Content-Type: image/png');
    header("Content-Disposition: attachment; filename={$qr_code}.png");
    readfile($file_path);
    exit;
}
?>

