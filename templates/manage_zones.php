<?php
require_once '../db/Database.php';

// Obtenemos la conexión
$database = new Database();
$conn = $database->getConnection();

// Obtener todas las zonas
$query = "SELECT * FROM zones_qr";
$stmt = $conn->prepare($query);
$stmt->execute();
$zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Zonas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../src/styles.css">
</head>
<body>
  <nav class="navbar navbar-light bg-light fixed-top">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <a class="navbar-brand d-flex align-items-center" href="admin_menu.php">
             
    <img src="../src/logoclean.png" alt="PixelClean Logo" width="60" height="60" class="d-inline-block align-text-top me-2">

                  <span style="color:#ffc107; font-size: 24px;">Admin Portal</span>
            </a>
            <a href="../db/logout.php" class="btn d-flex align-items-center logout-button">
                <img src="../src/logout.png" alt="Logout" width="40" height="40" class="d-inline-block"> 
            </a>
        </div>
    </nav>
	
<div class="container mt-4">
    <h1>Administrar Zonas</h1>

    <!-- Mensajes de éxito/error -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- Formulario de creación -->
    <form action="../modules/create_zone.php" method="POST" class="mb-4">
        <div class="mb-3">
            <label for="name" class="form-label">Nombre de la Zona:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripción:</label>
            <textarea id="description" name="description" class="form-control"></textarea>
        </div>
        <button type="submit" name="create_zone" class="btn btn-primary">Crear Zona</button>
    </form>

    <!-- Tabla de zonas existentes -->
    <h2>Zonas Existentes</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>QR</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($zones as $zone): ?>
                <tr>
                    <td><?= htmlspecialchars($zone['id']) ?></td>
                    <td><?= htmlspecialchars($zone['name']) ?></td>
                    <td><?= htmlspecialchars($zone['description']) ?></td>
                    <td>
                        <a href="../src/qr_codes/<?= htmlspecialchars($zone['qr_code']) ?>.png" download>Descargar QR</a>
                    </td>
                    <td>
                        <a href="../modules/delete_zone.php?id=<?= htmlspecialchars($zone['id']) ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include('footer.php'); ?>
</body>
</html>
