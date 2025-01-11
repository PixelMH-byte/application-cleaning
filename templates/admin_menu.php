<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Validar roles
if ((int)$_SESSION['role_id'] !== 1) {
    header('Location: worker_menu.php');
    exit;
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelClean - Panel Administrativo</title>
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
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5 class="card-title">Control de Precios</h5>
                    <a href="#" class="btn btn-warning">Gestionar</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5 class="card-title">Costes de Personal</h5>
                    <a href="#" class="btn btn-warning">Ver Costes</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5 class="card-title">Zonas</h5>
                    <a href="#" class="btn btn-warning">Configurar</a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5 class="card-title">Usuarios</h5>
                    <a href="#" class="btn btn-warning">Administrar</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5 class="card-title">Estadísticas</h5>
                    <a href="#" class="btn btn-warning">Resumen</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5 class="card-title">Informes</h5>
                    <a href="#" class="btn btn-warning">Descargar</a>
                </div>
            </div>
	
        </div>
    </div>
	
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</html>
