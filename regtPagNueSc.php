<?php
include('conexion.php');
// Iniciar la sesión si no está iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Verificar si se recibió una solicitud POST desde el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRegistrar"])) {
    $idSoc = $_POST['idSoc'];
    $montoPago = $_POST['valPag'];
    $montoSobrante = $_POST['valSob'];
    $cuentaVal = $_POST['cuentaVal'];  
    // Eliminar el símbolo de dólar y las comas
    $montoPago =  str_replace(['$', '.', ','], ['', '', '.'], $montoPago);
    // Eliminar el símbolo de dólar y las comas
    $montoSobrante = str_replace(['$', '.', ','], ['', '', '.'], $montoSobrante);
    
    // Primera consulta para actualizar pg_pend_monto en pago_pendiente
    $sql1 = "UPDATE pago_pendiente SET pg_pend_monto = ? WHERE fk_sc_id = ?";
    
    // Preparar la primera consulta
    $stmt1 = $conn->prepare($sql1);

    // Vincular los parámetros
    $stmt1->bind_param("ss", $montoSobrante, $idSoc);

    if ($stmt1->execute()) {
        
        // Sumar el nuevo monto al valor actual de la cuenta
        $nuevoSaldo = $cuentaVal + $montoPago;

        // Segunda consulta para actualizar cta_sc_saldo en cuenta_socio
        $sql2 = "UPDATE cuenta_socio SET cta_sc_saldo = ? WHERE fk_sc_id = ?";
        
        // Preparar la segunda consulta
        $stmt2 = $conn->prepare($sql2);

        // Vincular los parámetros
        $stmt2->bind_param("ss", $nuevoSaldo, $idSoc);

        if ($stmt2->execute()) {
           // Mensaje de éxito
           $mensajeExito = '¡Pago realizado exitoso!';
           $_SESSION['mensajeExito'] = $mensajeExito; // Guardar mensaje en la variable de sesión
           header("Location: formPagNueSc.php");
           exit();
        } else {
            // Mensaje de error
            $mensajeError = 'Error: ' . $stmt2->error;
            $_SESSION['mensajeError'] = $mensajeError; // Guardar mensaje en la variable de sesión
        }
        $stmt2->close();
    } else {
        // Mensaje de error
        $mensajeError = 'Error: ' . $stmt1->error;
        $_SESSION['mensajeError'] = $mensajeError; // Guardar mensaje en la variable de sesión
    }

    // Cerrar la conexión a la base de datos
    $stmt1->close();
}
?>
