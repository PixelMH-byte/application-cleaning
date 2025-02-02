<!DOCTYPE html>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Validar roles (solo administradores)
if ((int)$_SESSION['role_id'] !== 1) {
    header('Location: worker_menu.php');
    exit;
}

require_once '../../db/Database.php';
$database = new Database();
$conn = $database->getConnection();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelClean - Control de Precios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../src/styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light fixed-top">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <a class="navbar-brand d-flex align-items-center" href="../admin_menu.php">
                <img src="../../src/logoclean.png" alt="PixelClean Logo" width="60" height="60" class="d-inline-block align-text-top me-2">
                <span style="color:#ffc107; font-size: 24px;">Control de Precios</span>
            </a>
            <a href="../../db/logout.php" class="btn d-flex align-items-center logout-button">
                <img src="../../src/logout.png" alt="Logout" width="40" height="40" class="d-inline-block"> 
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Gestión de Productos y Costes</h1>

        <!-- Cards desplegables -->
        <div class="accordion mt-4" id="controlPreciosAccordion">
            
            <!-- Agregar Producto -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                        <img src="../../src/add_product.png" alt="Añadir Producto" width="30" height="30" class="me-2">
                        Añadir Producto
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#controlPreciosAccordion">
                    <div class="accordion-body">
                        <form id="addProductForm">
                            <div class="mb-3">
                                <label>Nombre del Producto:</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Categoría:</label>
                                <input type="text" name="categoria" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Costo Unitario (€):</label>
                                <input type="number" name="costo_unitario" step="0.01" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Añadir Producto</button>
                        </form>
                        <div id="productMessage" class="mt-2"></div>
                    </div>
                </div>
            </div>

            <!-- Lista de Productos -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                        <img src="../../src/list_products.png" alt="Lista de Productos" width="30" height="30" class="me-2">
                        Lista de Productos
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#controlPreciosAccordion">
                    <div class="accordion-body">
                        <select id="categoryFilter" class="form-select mb-3">
							<option value="">Todas las Categorías</option>
						</select>

                      <table class="table table-striped">
						<thead>
							<tr>
								<th>Nombre</th>
								<th>Categoría</th>
								<th>Costo (€)</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody id="productTable"></tbody>
					</table>
                    </div>
                </div>
            </div>

            <!-- Asignar Producto a Tarea -->
           <div class="accordion-item">
			<h2 class="accordion-header" id="headingThree">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
					<img src="../../src/assign_product.png" alt="Asignar Producto" width="30" height="30" class="me-2">
					Asignar Producto a Tarea
				</button>
			</h2>
			<div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#controlPreciosAccordion">
				<div class="accordion-body">
					<form id="assignProductForm">
						<div class="mb-3">
							<label>Tarea:</label>
							<select name="tarea_id" id="taskSelect" class="form-select" required></select>
						</div>
						<div class="mb-3">
							<label>Producto:</label>
							<select name="producto_id" id="productSelect" class="form-select" required></select>
						</div>
						<div class="mb-3">
							<label>Cantidad:</label>
							<input type="number" name="cantidad" step="0.01" class="form-control" required>
						</div>
						<div class="mb-3">
							<label>Turno:</label>
							<select name="turno" id="turnoSelect" class="form-select" required>
								<option value="M">Mañana</option>
								<option value="T">Tarde</option>
								<option value="N">Noche</option>
							</select>
						</div>
						<button type="submit" class="btn btn-success">Asignar Producto</button>
					</form>
					<div id="assignMessage" class="mt-2"></div>
				</div>
			</div>
		</div>

        </div>
    </div>

    <?php include('../footer.php'); ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
     
    async function loadProducts() {
        let response = await fetch('../../modules/get_products.php');
        let products = await response.json();
        let tableBody = document.getElementById('productTable');
        let categoryFilter = document.getElementById('categoryFilter');
        let productSelect = document.getElementById('productSelect');

        tableBody.innerHTML = '';
        categoryFilter.innerHTML = '<option value="">Todas las Categorías</option>';
        productSelect.innerHTML = '<option value="">Seleccione un Producto</option>';

        let categories = new Set();

        products.forEach(product => {
            tableBody.innerHTML += `
                <tr>
                    <td>${product.nombre}</td>
                    <td>${product.categoria}</td>
                    <td>${product.costo_unitario} €</td>
					 <td>
                    <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Eliminar</button>
                </td>
                </tr>
            `;

            categories.add(product.categoria);
            productSelect.innerHTML += `<option value="${product.id}">${product.nombre}</option>`;
        });

        categories.forEach(cat => {
            categoryFilter.innerHTML += `<option value="${cat}">${cat}</option>`;
        });
    }

    async function loadTasks() {
        let response = await fetch('../../modules/get_tasks.php');
        let tasks = await response.json();
        let taskSelect = document.getElementById('taskSelect');

        taskSelect.innerHTML = '<option value="">Seleccione una Tarea</option>';
        tasks.forEach(task => {
            taskSelect.innerHTML += `<option value="${task.id}">${task.name}</option>`;
        });
    }

    document.getElementById('addProductForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        let formData = new FormData(this);

        try {
            let response = await fetch('../../modules/add_product.php', {
                method: 'POST',
                body: formData
            });

            let result = await response.json();
            let messageElement = document.getElementById('productMessage');

            if (result.success) {
                messageElement.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                this.reset();
                loadProducts();
            } else {
                messageElement.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
            }
        } catch (error) {
            console.error('Error al añadir producto:', error);
            document.getElementById('productMessage').innerHTML = `<div class="alert alert-danger">Error en la comunicación con el servidor.</div>`;
        }
    });

		document.getElementById('assignProductForm').addEventListener('submit', async function(event) {
			event.preventDefault();
			let formData = new FormData(this);

			// Mostrar los valores en consola antes de enviarlos
			for (let pair of formData.entries()) {
				console.log(pair[0]+ ': ' + pair[1]);
			}

			try {
				let response = await fetch('../../modules/assign_product.php', {
					method: 'POST',
					body: formData
				});

				let textResult = await response.text();
				console.log("RAW RESPONSE:", textResult); // Verifica qué responde el servidor

				let result;
				try {
					result = JSON.parse(textResult);
				} catch (e) {
					console.error("Error al convertir respuesta en JSON:", e);
					document.getElementById('assignMessage').innerHTML = `<div class="alert alert-danger">Respuesta inesperada del servidor.</div>`;
					return;
				}

				let messageElement = document.getElementById('assignMessage');

				if (result.success) {
					messageElement.innerHTML = `<div class="alert alert-success">${result.success}</div>`;
					this.reset();
				} else {
					messageElement.innerHTML = `<div class="alert alert-danger">${result.error || 'Error desconocido'}</div>`;
				}
			} catch (error) {
				console.error('Error en la asignación:', error);
				document.getElementById('assignMessage').innerHTML = `<div class="alert alert-danger">Error en la comunicación con el servidor.</div>`;
			}
		});
			document.getElementById('categoryFilter').addEventListener('change', function() {
				filterProductsByCategory(this.value);
			});

			function filterProductsByCategory(selectedCategory) {
				let rows = document.querySelectorAll('#productTable tr');

				rows.forEach(row => {
					let category = row.children[1].textContent.trim(); // Obtiene la categoría de la fila
					if (selectedCategory === "" || category === selectedCategory) {
						row.style.display = ""; // Muestra la fila si coincide
					} else {
						row.style.display = "none"; // Oculta la fila si no coincide
					}
				});
			}
			async function deleteProduct(productId) {
				if (!confirm('¿Estás seguro de que deseas eliminar este producto?')) return;

				let response = await fetch('../../modules/delete_product.php', {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify({ id: productId })
				});

				let result = await response.json();
				
				if (result.success) {
					alert(result.message);
					loadProducts(); // Recargar la lista de productos después de eliminar
				} else {
					alert(result.error);
				}
			}



    document.addEventListener('DOMContentLoaded', () => {
        loadProducts();
        loadTasks();
    });
</script>

   
</body>
</html>
