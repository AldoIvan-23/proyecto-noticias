<?php
$localhost = 'localhost';
$user = 'root';
$password = '';
$bd = 'develop';

$mysqli = new mysqli($localhost, $user, $password);
if ($mysqli) {
    $sql = "CREATE DATABASE IF NOT EXISTS $bd";
    $mysqli->query($sql);
    $mysqli = mysqli_connect($localhost, $user, $password,$bd);
}
$tabla = 'usuario';

$query = "CREATE TABLE IF NOT EXISTS $tabla(
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    user VARCHAR(60) NOT NULL,
    pass VARCHAR(255) NOT NULL
)";
if($mysqli->query($query)=== TRUE){
}else{
    echo 'Error al crear la tabla'. $mysqli->error;
}
//Preparar la consulta para evitar inyecciones sql
$sql = "SELECT * FROM usuario WHERE user = ? AND pass = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $user, $password);

//ejecutar la consulta 
$stmt->execute();
//Obtener resultado
$result = $stmt->get_result();

//Limpiar cadena para evitar inyecciones y filtracion de informacion 


?>