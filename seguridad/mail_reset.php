<?php
// Varios destinatarios
$para  = 'pedrosua141@gmail.com' . ', '; // atención a la coma
//$para .= 'wez@example.com';

// título
$título = 'Restablabler Contraseña ';
$codigo =rand(1000,9999);
// mensaje
$mensaje = '
<html>
<head>
  <title>Recordatorio de cumpleaños para Agosto</title>
</head>
<body>
  <h1>¡CAJA DE AHORROS CRISOL !</h1>
  <img src="/http://localhost/crisol/imagenes/logoCaja.png">
  <div style ="text-aling:center; background-color:#ccc">
  <p> Restablecer contraseña </p>
  <h3>'.$codigo.' </h3>
  <p><small> Usted no envio este codigo favor omitir el mismo  </small></p>
</body>
</html>
';

// Para enviar un correo HTML, debe establecerse la cabecera Content-type
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// // Cabeceras adicionales
// $cabeceras .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
//$cabeceras .= 'From: Caja de Ahorros Crisol <pedrosua141@gmail.com>' . "\r\n";
// $cabeceras .= 'Cc: birthdayarchive@example.com' . "\r\n";
// $cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";

// Enviarlo
mail( $para,$título, $mensaje, $cabeceras);
?>
