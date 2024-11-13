<?php
$conexion = new mysqli("localhost", "root", "", "develop");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$id = $_POST['id'];
$consulta = "DELETE FROM usuario WHERE id = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Éxito
    echo "Registro eliminado correctamente";
} else {
    // Error
    echo "Error al eliminar el registro: " . $stmt->error;
}
$stmt->close();
$conexion->close();
?>
