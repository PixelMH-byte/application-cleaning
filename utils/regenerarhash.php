<?php
require_once '../db/Database.php';

// Conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    die("Error en la conexión a la base de datos.");
}

// Contraseñas originales
$users = [
    ['email' => 'admin@pixelclean.com', 'password' => 'admin123'],
    ['email' => 'worker@pixelclean.com', 'password' => 'worker123'],
];

foreach ($users as $user) {
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
    $query = "UPDATE users SET password = :password WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Contraseña actualizada para {$user['email']}<br>";
    } else {
        echo "Error al actualizar la contraseña para {$user['email']}<br>";
    }
}
?>
