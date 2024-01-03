<?php
include("conexion.php");

//usuario
if (isset($_POST['recuperar'])) {
  // Recuperar el valor del campo de nombre de usuario
  $nombreUsuario = $_POST['username'];
  // Varios destinatarios
  $para = 'pedrosua141@gmail.com' . ', '; // atención a la coma

  // título
  $título = 'Restablabler Contraseña ';
  $codigo = rand(1000, 9999);

  // encriptar contraseña
  $pass_fuerte = password_hash($codigo, PASSWORD_DEFAULT);
  //se elimina la contrasena del login
  mysqli_query($conn,"UPDATE `login` SET `log_clave` = '$pass_fuerte' WHERE login.log_usuario= '$nombreUsuario'");


  // mensaje
  $mensaje = '<html>
    <head>
      <title>Recuperar Contraseña</title>
    </head>
    <body>
      <h1>¡CAJA DE AHORROS CRISOL !</h1>
      <img src="/http://localhost/crisol/imagenes/logoCaja.png">
      <div style ="text-aling:center; background-color:#ccc">
      <p> Su usuario es: <p>
      <h3>' . $nombreUsuario . ' </h3>
      <p> Restablecer contraseña </p>
      <h3>' . $codigo . ' </h3>
      <p><small> Usted no envio este codigo favor omitir el mismo  </small></p>
    </body>
    </html>';

    // Para enviar un correo HTML, debe establecerse la cabecera Content-type
    $cabeceras = 'MIME-Version: 1.0' . "\r\n";
    $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Enviarlo
    mail($para, $título, $mensaje, $cabeceras);
}
?>

