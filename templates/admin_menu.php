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
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light fixed-top">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../src/logoclean.png" alt="PixelClean Logo" width="60" height="60" class="d-inline-block align-text-top me-2">
                <span style="color:#ffc107; font-size: 24px;">Admin Portal</span>
            </a>
            <a href="../db/logout.php" class="btn d-flex align-items-center logout-button">
                <img src="../src/logout.png" alt="Logout" width="40" height="40" class="d-inline-block"> 
            </a>
        </div>
    </nav>

    <!-- Contenido principal -->
       <div class="container mt-5 mt-sm-4">
        <div class="row gy-3">
            <!-- Botones del menú -->
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card text-center p-3 h-100">
                    <img src="../src/PRECIOS.png" alt="Precios Icon" class="menu-icon mb-2">
                    <h5 class="card-title">Control de Precios</h5>
                    <a href="admin/control_precios.php" class="btn btn-warning">Gestionar</a>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card text-center p-3 h-100">
                    <img src="../src/COSTEP.png" alt="Costos Icon" class="menu-icon mb-2">
                    <h5 class="card-title">Costes de Personal</h5>
                    <a href="#" class="btn btn-warning">Ver Costes</a>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card text-center p-3 h-100">
                    <img src="../src/ZONAS.png" alt="Zonas Icon" class="menu-icon mb-2">
                    <h5 class="card-title">Zonas</h5>
                    <a href="manage_zones.php" class="btn btn-warning">Configurar</a>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card text-center p-3 h-100">
                    <img src="../src/TRABAJADORES.png" alt="Trabajadores Icon" class="menu-icon mb-2">
                    <h5 class="card-title">Usuarios</h5>
                    <a href="admin/create_user.php" class="btn btn-warning">Administrar</a>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card text-center p-3 h-100">
                    <img src="../src/ESTADISTICAS.png" alt="Estadísticas Icon" class="menu-icon mb-2">
                    <h5 class="card-title">Estadísticas</h5>
                    <a href="#" class="btn btn-warning">Resumen</a>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card text-center p-3 h-100">
                    <img src="../src/INFORMES.png" alt="Informes Icon" class="menu-icon mb-2">
                    <h5 class="card-title">Informes</h5>
                    <a href="#" class="btn btn-warning">Descargar</a>
                </div>
            </div>
        </div>
    </div>
	    <?php include('footer.php'); ?>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
	
</body>
</html>
