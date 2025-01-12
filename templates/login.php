<?php
session_start();
require_once '../db/Database.php';

// Mostrar errores para depuración (quitar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Conexión a la base de datos
    $database = new Database();
    $conn = $database->getConnection();

    // Consultar usuario por email
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar usuario y contraseña
    if ($user && password_verify($password, $user['password'])) {
        // Guardar datos en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role_id'] = (int)$user['role_id'];

        // Redirigir según el rol
        if ($_SESSION['role_id'] === 1) {
            header('Location: admin_menu.php');
        } elseif ($_SESSION['role_id'] === 2) {
            header('Location: worker_menu.php');
        }
        exit;
    } else {
        $error = "Correo electrónico o contraseña incorrectos.";
    }
}

// Verificar si la solicitud viene desde una pantalla móvil
$isMobile = isset($_GET['mobile']) && $_GET['mobile'] === 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PixelClean</title>
	    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <div class="login-container">
	    <div class="login-logo">
            <img src="../src/logoclean.png" alt="PixelClean Logo">
        </div>
        <h1>Iniciar Sesión</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
	 <!-- Incluir footer dinámico para la parte del login solamente "quitar las letras" -->
    <?php 
    if ($isMobile) {
        include('footer2.php'); // Footer para móviles
    } else {
        include('footer2.php'); // Footer normal
    }
    ?>
</body>
</html>
