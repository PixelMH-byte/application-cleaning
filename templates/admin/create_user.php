<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../src/styles.css">
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
                loadWorkers(); // Recargar la tabla de trabajadores
            } else if (result.error) {
                alertContainer.innerHTML = `<div class="alert alert-danger">${result.error}</div>`;
            }
        }

        async function loadWorkers() {
            const response = await fetch('../../modules/get_workers.php');
            const workers = await response.json();

            const tableBody = document.getElementById('workersTableBody');
            tableBody.innerHTML = '';

            workers.forEach(worker => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${worker.id}</td>
                        <td>${worker.name}</td>
                        <td>${worker.email}</td>
                        <td>
                            <button class="btn btn-primary" onclick="openAssignModal(${worker.id})">Asignar Tareas</button>
                        </td>
                    </tr>
                `;
            });
        }

        async function openAssignModal(workerId) {
            document.getElementById('assignWorkerId').value = workerId;

            // Cargar zonas asignadas
            const response = await fetch(`../../modules/get_zones.php?worker_id=${workerId}`);
            const zones = await response.json();

            const assignedZonesTable = document.getElementById('assignedZonesTableBody');
            assignedZonesTable.innerHTML = '';

            zones.assigned.forEach(zone => {
                assignedZonesTable.innerHTML += `
                    <tr>
                        <td>${zone.id}</td>
                        <td>${zone.name}</td>
                        <td>${zone.description}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="removeZone(${workerId}, ${zone.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
            });

            // Cargar zonas no asignadas
            const unassignedSelect = document.getElementById('unassignedZones');
            unassignedSelect.innerHTML = '';
            zones.unassigned.forEach(zone => {
                const option = document.createElement('option');
                option.value = zone.id;
                option.textContent = zone.name;
                unassignedSelect.appendChild(option);
            });

            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('assignModal'));
            modal.show();
        }

        async function addZone() {
            const workerId = document.getElementById('assignWorkerId').value;
            const zoneId = document.getElementById('unassignedZones').value;

            const response = await fetch('../../modules/add_zone.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ worker_id: workerId, zone_id: zoneId }),
            });

            const result = await response.json();
            if (result.success) {
                openAssignModal(workerId); // Refrescar modal
				const backdrop = document.querySelector('.modal-backdrop');
				if (backdrop) {
					backdrop.remove();
				}
							
            } else {
                alert("Error al asignar zona.");
            }
        }

        async function removeZone(workerId, zoneId) {
            const response = await fetch('../../modules/remove_zone.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ worker_id: workerId, zone_id: zoneId }),
            });

            const result = await response.json();
            if (result.success) {
                openAssignModal(workerId); // Refrescar modal
				const backdrop = document.querySelector('.modal-backdrop');
				if (backdrop) {
					backdrop.remove();
				}
            } else {
                alert("Error al eliminar la zona.");
            }
        }
		function closeModal(modalId) {
			const modalElement = document.getElementById(modalId);
			const modalInstance = bootstrap.Modal.getInstance(modalElement);

			if (modalInstance) {
				modalInstance.hide(); // Cierra el modal
			}

			// Asegurarse de que no quede el backdrop
			const backdrop = document.querySelector('.modal-backdrop');
			if (backdrop) {
				backdrop.remove();
			}

			// Limpiar contenido del modal (opcional)
			document.getElementById('assignedZonesTableBody').innerHTML = '';
			document.getElementById('unassignedZones').innerHTML = '';
		}


        document.addEventListener('DOMContentLoaded', loadWorkers);
    </script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light fixed-top">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <a class="navbar-brand d-flex align-items-center" href="../admin_menu.php">
                <img src="../../src/logoclean.png" alt="PixelClean Logo" width="60" height="60" class="d-inline-block align-text-top me-2">
                <span style="color:#ffc107; font-size: 24px;">Admin Portal</span>
            </a>
            <a href="../../db/logout.php" class="btn d-flex align-items-center logout-button">
                <img src="../../src/logout.png" alt="Logout" width="40" height="40" class="d-inline-block"> 
            </a>
        </div>
    </nav>
 
    <div class="container mt-4">
        <h1>Crear Nuevo Usuario</h1>
        <div id="alertContainer"></div>
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
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
    </div>

    <div class="container mt-5">
        <h2>Trabajadores</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="workersTableBody"></tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">Asignar Zonas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="assignWorkerId">
                    <h6>Zonas Asignadas</h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="assignedZonesTableBody"></tbody>
                    </table>
                    <hr>
                    <h6>Asignar Nueva Zona</h6>
                    <select class="form-select" id="unassignedZones"></select>
                    <button class="btn btn-success mt-2" onclick="addZone()">Añadir Zona</button>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include('../footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</html>
