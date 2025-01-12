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
   <nav class="navbar navbar-light bg-light fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="../src/logoclean.png" alt="PixelClean Logo" width="60" height="60" class="d-inline-block align-text-top me-2">
            <span>Worker Portal</span>
        </a>
           <a href="../db/logout.php" class="btn d-flex align-items-center logout-button">
            <img src="../src/logout.png" alt="Logout" width="40" height="40" class="d-inline-block"> 
       </a>
    </div>
</nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card text-center p-3">
                    <img src="../src/TAREAS.png" alt="Tareas Asignadas" width="50" class="mb-2">
                    <h5 class="card-title">Tareas Asignadas</h5>
                    <a href="#" class="btn btn-warning">Ver Tareas</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center p-3">
                    <img src="../src/HISTORIAL.png" alt="Historial de Tareas" width="50" class="mb-2">
                    <h5 class="card-title">Historial de Tareas</h5>
                    <a href="#" class="btn btn-warning">Ver Historial</a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card text-center p-3">
                    <img src="../src/JORNADA.png" alt="Jornada" width="50" class="mb-2">
                    <h5 class="card-title">Jornada</h5>
                    <a href="#" class="btn btn-warning">Ver Detalles</a>
                </div>
            </div>
        </div>
    </div>
	  <?php include('footer2.php'); ?>
	  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>

</html>