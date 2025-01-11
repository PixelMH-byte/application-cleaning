<?php
require_once '../db/Database.php';

// Obtenemos la conexión
$database = new Database();
$conn = $database->getConnection();

// Manejo de creación de zona
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_zone'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $qr_code = uniqid('QR_'); // Generar un código único para la zona

    $query = "INSERT INTO zones_qr (name, description, qr_code) VALUES (:name, :description, :qr_code)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':qr_code', $qr_code);

    if ($stmt->execute()) {
        echo "Zona creada con éxito.";
    } else {
        echo "Error al crear la zona.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Zonas</title>
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <h1>Administrar Zonas</h1>
    <form method="POST">
        <label for="name">Nombre de la Zona:</label>
        <input type="text" id="name" name="name" required>
        <label for="description">Descripción:</label>
        <textarea id="description" name="description"></textarea>
        <button type="submit" name="create_zone">Crear Zona</button>
    </form>

    <h2>Zonas existentes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>QR</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar zonas existentes
            $query = "SELECT * FROM zones_qr";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($zones as $zone) {
                echo "<tr>
                    <td>{$zone['id']}</td>
                    <td>{$zone['name']}</td>
                    <td><a href='generate_qr.php?qr_code={$zone['qr_code']}'>Descargar QR</a></td>
                    <td>
                        <a href='edit_zone.php?id={$zone['id']}'>Editar</a>
                        <a href='delete_zone.php?id={$zone['id']}'>Eliminar</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
