

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Registro</title>
    
</head>
<body>
    
<div class="container">
    <div class="form-background">
    <h1>Registrarse</h1>
    <?php 
    include('models/controller.php')
    ?>
   <form method="post">
    <input type="email" name="usuario" placeholder="Email">
    <br>
    <input type="password" name="password" placeholder="Contraseña">
    <br><br>
    <input type="submit" value="Ingresar" class="btn" name="btnregistro">
  </form>
  </div>
  <div class="login">
    <p>¿Ya estas registrado?<a href="view/login.php"> Iniciar sesión</a></p>
  </div>
  </div>
</body>
</html>