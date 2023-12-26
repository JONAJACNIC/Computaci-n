<?php
include('../conexion.php');

// Iniciar la sesión si no está iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Verificar si se recibió una solicitud POST desde el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRegistrar"])) {
    $cedula = $_POST['cedula'];
    $fechaNac = $_POST['fechaNac'];
    $direccion = $_POST['direccion'];
    $tel = $_POST['telefono'];
    $cel = $_POST['celular'];
    $cor = $_POST['correo'];
    // Consulta preparada para evitar la inyección SQL
    $sql = "UPDATE socio SET sc_fech_n = ?, sc_telf = ?, sc_dir = ?, sc_cel = ?, sc_correo = ? WHERE sc_cedula = ?";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("ssssss", $fechaNac, $tel, $direccion, $cel, $cor, $cedula);

    if ($stmt->execute()) {
        // Mensaje de éxito
        $mensajeExito = '¡Actualización exitosa!';
        $_SESSION['mensajeExito'] = $mensajeExito; // Guardar mensaje en la variable de sesión
        header("Location:formActSc.php");
        exit();
    } else {
        // Mensaje de error
        $mensajeError = 'Error: ' . $stmt->error;
        $_SESSION['mensajeError'] = $mensajeError; // Guardar mensaje en la variable de sesión
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
}
?>
