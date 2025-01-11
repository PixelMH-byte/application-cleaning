<?php
require_once '../lib/phpqrcode/qrlib.php';

if (isset($_GET['qr_code'])) {
    $qr_code = $_GET['qr_code'];
    $file_path = "../src/qr_codes/{$qr_code}.png";

    // Generar el código QR
    QRcode::png($qr_code, $file_path);

    // Descarga del archivo
    header('Content-Type: image/png');
    header("Content-Disposition: attachment; filename={$qr_code}.png");
    readfile($file_path);
}
?>
