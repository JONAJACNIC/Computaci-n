<?php
session_start();
include("../conexion.php");

// Ingresar al Sistema  
if(isset($_POST['recuperar']))
{
    $nombre = $_POST['username'];
    $queryusuario = mysqli_query($conn, "SELECT * FROM login WHERE log_usuario= '$nombre'");
    $nr = mysqli_num_rows($queryusuario);
    if($nr == 1){
        include("mail_reset.php");
        echo "<script>alert('Se envio una contraseña temporal a su correo');</script>";
        // Puedes redirigir si es necesario
        header("Location: index.php");
        exit(); // Asegurarse de que el script se detenga aquí
    }
    else {
        echo "<script>alert('Usuario ingresado incorrecto');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
</head>
<body>
    <form action="" method="post" class="d-flex flex-column  justify-content-center">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit" name="recuperar" class="bg-white px-3 rounded-5">Recuperar Contraseña</button>
    </form>
</body>
</html>
