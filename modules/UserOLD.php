<?php
require_once 'Database.php';

class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los usuarios
    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo usuario
    public function createUser($name, $email, $password, $role_id) {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password, role_id) VALUES (:name, :email, :password, :role_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", password_hash($password, PASSWORD_DEFAULT));
        $stmt->bindParam(":role_id", $role_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Obtener un usuario por ID
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar un usuario
    public function updateUser($id, $name, $email, $role_id) {
        $query = "UPDATE " . $this->table_name . " SET name = :name, email = :email, role_id = :role_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":role_id", $role_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Eliminar un usuario
    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
