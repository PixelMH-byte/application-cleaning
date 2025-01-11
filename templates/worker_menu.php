<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Validar roles
if ((int)$_SESSION['role_id'] !== 2) {
    header('Location: admin_menu.php');
    exit;
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelClean - Panel de Trabajador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PixelClean</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-danger" href="../db/logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card text-center p-3">
                    <h5 class="card-title">Tareas Asignadas</h5>
                    <a href="#" class="btn btn-warning">Ver Tareas</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center p-3">
                    <h5 class="card-title">Historial de Tareas</h5>
                    <a href="#" class="btn btn-warning">Ver Historial</a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card text-center p-3">
                    <h5 class="card-title">Escanear Código QR</h5>
                    <a href="#" class="btn btn-warning">Iniciar Escaneo</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center p-3">
                    <h5 class="card-title">Detalles de la Zona</h5>
                    <a href="#" class="btn btn-warning">Ver Detalles</a>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</html>
