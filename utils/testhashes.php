<?php
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Contraseña original: $password<br>";
echo "Hash generado: $hashed_password<br>";

// Verificar el hash
if (password_verify($password, $hashed_password)) {
    echo "El hash es válido.";
} else {
    echo "El hash no es válido.";
}
?>
