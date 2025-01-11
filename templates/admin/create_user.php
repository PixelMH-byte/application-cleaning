<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
	
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../src/styles.css">
    <script>
        async function createUser(event) {
            event.preventDefault();

            const form = document.getElementById('createUserForm');
            const formData = new FormData(form);
            
            const response = await fetch('../../modules/create_u.php', {
                method: 'POST',
                body: formData,
            });

            const result = await response.json();

            const alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = ''; // Limpiar mensajes previos

            if (result.success) {
                alertContainer.innerHTML = `<div class="alert alert-success">${result.success}</div>`;
                form.reset(); // Limpiar el formulario
            } else if (result.error) {
                alertContainer.innerHTML = `<div class="alert alert-danger">${result.error}</div>`;
            }
        }
    </script>
</head>
<body>
 <!-- Navbar -->
    <nav class="navbar navbar-light bg-light fixed-top">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../../src/logoclean.png" alt="PixelClean Logo" width="60" height="60" class="d-inline-block align-text-top me-2">
                <span style="color:#ffc107; font-size: 24px;">Admin Portal</span>
            </a>
            <a href="../../db/logout.php" class="btn d-flex align-items-center logout-button">
                <img src="../../src/logout.png" alt="Logout" width="40" height="40" class="d-inline-block"> 
            </a>
        </div>
    </nav>
	</br>
	</br>
    <div class="container mt-5">
        <h1>Crear Nuevo Usuario</h1>
        <div id="alertContainer"></div> <!-- Contenedor para mensajes -->
        <form id="createUserForm" onsubmit="createUser(event)">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role_id" class="form-label">Rol</label>
                <select class="form-select" id="role_id" name="role_id" required>
                    <option value="1">Administrador</option>
                    <option value="2">Trabajador</option>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <a href="../admin_menu.php" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</html>
