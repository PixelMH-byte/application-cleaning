<?php
session_start();

if (!isset($_SESSION['user_id']) || (int)$_SESSION['role_id'] !== 1) {
    header('Location: ../templates/login.php');
    exit;
}

require_once '../db/Database.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* verificación de parametros recibidos/ no nulos */
    error_log("Datos recibidos: " . print_r($_POST, true));

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_id = (int)($_POST['role_id'] ?? 0);



    if (empty($name) || empty($email) || empty($password) || !$role_id) {
        $response['error'] = "Todos los campos son obligatorios.";
    } else {
        $database = new Database();
        $conn = $database->getConnection();

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, password, role_id, created_at, updated_at)
                  VALUES (:name, :email, :password, :role_id, NOW(), NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role_id', $role_id);
       

        if ($stmt->execute()) {
            $response['success'] = "Usuario creado exitosamente.";
        } else {
            $response['error'] = "Error al crear el usuario. Por favor, inténtalo de nuevo.";
        }
    }
} else {
    $response['error'] = "Método no permitido.";
}

header('Content-Type: application/json');
echo json_encode($response);
?>