<?php
include('conexion.php');

if (!empty($_POST['btningresar'])) {
    session_start();
    //Archivos log
    function insertarRegistroEvento($nivel, $mensaje) {
        global $mysqli;
        $sql = "INSERT INTO registro_eventos (nivel, mensaje) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $nivel, $mensaje);
        $stmt->execute();
        $stmt->close();
    }
    
    $inicio_sesion_error = "";

    function manejarBloqueoTemporal() {
        $_SESSION['intento'] = 1;
        $_SESSION['tiempo_bloqueo'] = time() + 30; 
    }
    if (isset($_SESSION['tiempo_bloqueo']) && $_SESSION['tiempo_bloqueo'] > time()) {
        echo '<div class="alert-info">Demasiados intentos. Inténtalo de nuevo en 30 segundos.</div>';
        header("Refresh: 30; url=../view/login.php");
        die();
    }
    if (empty($_POST['usuario']) or empty($_POST['password'])) {
        echo '<div class="alert-info">Uno de los campos esta vacio</div>';
    }else{
        $usuario =$_POST['usuario'];
        $password = $_POST['password'];
        $sql = $mysqli->query("SELECT id,user,pass from usuario where user = '$usuario'");
        if ($row = $sql->fetch_object()) {
            $hashAlmacenado = $row->pass;
            if (password_verify($password, $hashAlmacenado)) {
                $_SESSION['user'] = $usuario;
                echo '<div class="alert-info">Logueado correctamente</div>';
                echo "<script>
                    setTimeout(function () {
                        window.location.href = '../index.php';
                    }, 1000);
                </script>";
                insertarRegistroEvento("INFO", "Inicio de sesión exitoso para el usuario: $usuario");
                exit;
            } else {
                $_SESSION['intento'] +=1;
                echo '<div class="alert-info">Datos erróneos</div>';
                if ($_SESSION['intento'] >= 3) {
                    manejarBloqueoTemporal();
                    insertarRegistroEvento("ERROR", "Intento de inicio de sesión fallido para el usuario: $usuario");
                }
            }
        }else{
                echo '<div class="alert-info">Usuario no encontrado</div>';
                if (!isset($_SESSION['intento'])) {
                    $_SESSION['intento'] += 1;
                } else {
                    $_SESSION['intento']++;
                }
    
                if ($_SESSION['intento'] >= 3) {
                    manejarBloqueoTemporal();
                }
        }
    }
}
if (!empty($_POST['btnregistro'])) {
    if (empty($_POST['usuario']) or empty($_POST['password'])) {
        echo '<div class="alert-info">Uno de los campos esta vacio</div>';
    }else{
        $usuario =$_POST['usuario'];
        $password = $_POST['password'];
        //validar
        $validate = $mysqli->query("SELECT id,user,pass from usuario where user = '$usuario'");
        if ($row = $validate->fetch_object()) {
                echo '<div class="alert-info">Este usuario ya existe</div>';
        }else{
            $hashAlmacenado = password_hash($password,PASSWORD_DEFAULT);
            $sql = $mysqli->query("INSERT into usuario(user,pass) values('$usuario', '$hashAlmacenado')");
            if ($sql == 1) {
                echo '<div class="alert-info">Usuario registrado correctamente</div>';
            }else{
                    echo '<div class="alert-info">Error al registrar</div>';
            }
        }
    }
}

// Manejo de AJAX
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    require_once "../models/conexion.php";

    // Verificar que se proporcionaron credenciales
    if (isset($_POST['usuarios']) && isset($_POST['contraseña'])) {
        $usuario = $mysqli->real_escape_string($_POST['usuarios']);
        $password = $mysqli->real_escape_string($_POST['contraseña']);

        // Preparar y ejecutar consulta SQL
        if ($nueva_consulta = $mysqli->prepare("SELECT * FROM usuarios WHERE correo = ? AND contraseña = ?")) {
            $nueva_consulta->bind_param('ss', $usuario, $password);
            $nueva_consulta->execute();

            // Obtener resultado
            $resultado = $nueva_consulta->get_result();

            // Manejar resultado
            if ($resultado->num_rows == 1) {
                $datos = $resultado->fetch_assoc();
                echo json_encode(array('error' => false));
            } else {
                echo json_encode(array('error' => true));
                // Registro de evento
                insertarRegistroEvento("ERROR", "Intento de inicio de sesión AJAX fallido");
            }
            // Cerrar consulta
            $nueva_consulta->close();
        }
    }
}

$mysqli->close();
?>