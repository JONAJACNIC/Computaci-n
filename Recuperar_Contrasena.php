<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mail_reset.php'; // Asegúrate de ajustar la ruta al archivo autoload.php

// Incluye tu archivo de conexión
include 'conexion.php'; 

// Consultar la base de datos para obtener la información del usuario
// (Asegúrate de escapar y validar la entrada del usuario para evitar inyecciones SQL)
$sql = "SELECT * FROM login WHERE log_usuario";
$result = mysqli_query($conn, $sql);

if ($result) {
    $usuario = mysqli_fetch_assoc($result);

    // Configurar PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'pedrosua141@gmail.com'; // Tu dirección de correo electrónico de Gmail
    $mail->Password = 'edwwbjolswgipymz'; // Tu contraseña de Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Configurar el contenido del correo
    $mail->setFrom('pedrosua141@gmail.com', 'Tu Nombre');
    $mail->addAddress('pedrosua141@gmail.com', 'Tu Nombre');
    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de Contraseña';
    $mail->Body = 'Usuario: ' . $usuario['username'] . '<br>Contraseña: ' . $usuario['password'];

    // Enviar el correo
    try {
        $mail->send();
        echo 'Correo enviado correctamente';
    } catch (Exception $e) {
        echo 'Error al enviar el correo: ', $mail->ErrorInfo;
    }
} else {
    echo 'Error al consultar la base de datos: ', mysqli_error($conn);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
</head>
<body>
    <form action="recuperar_contrasena.php" method="post">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Recuperar Contraseña</button>
    </form>
</body>
</html>
