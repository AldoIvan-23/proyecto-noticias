<?php
session_start();
include '../models/conexion.php';

if(!isset($_SESSION['user'])){
    echo '
    <script>
        alert("Por favor inicia sesión");
        setTimeout(function () {
            window.location.href = "../index.php";
        }, 1200);
    </script>';
    die();
}
$sql = "SELECT * FROM usuario";
$res = mysqli_query($mysqli,$sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Login</title>
</head>
<body>
    
<div class="container">
    <div class="form-background">
    <h1>Iniciar sesión</h1>
    <?php 
    include('../models/controller.php')
    ?>
        <form method="POST">
                <div data-validate="Dato requerido">
                    <h5>Correo:</h5>
                    <input type="text" name="usuario">
                </div>

                <div class="contra" data-validate="Dato requerido">
                    <h5>Contraseña:</h5>
                    <input type="password" name="password">
                </div>

                <div class="botones">
                        <input type="submit" class="btn" name="btningresar" value="Ingresar">
                </div>
            </form>

  </div>
  <div class="login">
    <p>¿Necesitas una cuenta?<a href="../registro.php">Registrarse</a></p>
  </div>
  </div>
</body>
</html>


