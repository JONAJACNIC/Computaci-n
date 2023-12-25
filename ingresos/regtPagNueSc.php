<?php
include('conexion.php');

// Iniciar la sesión si no está iniciada
if (!isset($_SESSION)) {
    session_start();
}
// Verificar si se recibió una solicitud POST desde el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRegistrar"])) {
    $idSoc = $_POST['idSoc'];
    $certApor = $_POST['certApor'];
    $fondEst = $_POST['fondEst'];
    $gastAdm = $_POST['gastAdm'];
    $cuentaVal = $_POST['cuentaVal'];
    $fechaAct = $_POST['fechaAct'];
    $fechaAct = date('Y-m-d', strtotime($fechaAct));
    $valPen = $_POST['valPen'];
    // Eliminar el símbolo de dólar y las comas
    $certApor  =  str_replace(['$', '.', ','], ['', '', '.'], $certApor);
    // Eliminar el símbolo de dólar y las comas
    $fondEst  = str_replace(['$', '.', ','], ['', '', '.'], $fondEst);
    // Eliminar el símbolo de dólar y las comas
    $gastAdm  = str_replace(['$', '.', ','], ['', '', '.'], $gastAdm);
    // Eliminar el símbolo de dólar y las comas
    $valPen  = str_replace(['$', '.', ','], ['', '', '.'], $valPen);

    // Iniciar una transacción
    $conn->begin_transaction();
    $ingre_val = [$certApor, $fondEst, $gastAdm];
    $fk_tp_ingre_id = [12, 13, 16];
    $fk_cta_id = [2, 3, 5];
    // Construir la consulta SQL con una sentencia preparada
    $sql = "INSERT INTO ingreso (ingre_val, ingre_fech_ini, fk_tp_ingre_id, fk_sc_id, fk_cta_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // Dentro del bucle foreach
    foreach ($fk_tp_ingre_id as $index => $tipo) {
        // Obtén el valor correspondiente de $ingre_val
        $valorIngre = $ingre_val[$index];
        // Bind de los parámetros
        $stmt->bind_param('ssiii', $valorIngre, $fechaAct, $tipo, $idSoc, $fk_cta_id[$index]);
        // Ejecutar la sentencia preparada
        $stmt->execute();
    }

    // Primera consulta para actualizar pg_pend_monto en pago_pendiente
    $sql1 = "UPDATE pago_pendiente SET pg_pend_monto = ? WHERE fk_sc_id = ?";
    $montoSobrante = $valPen-$valPen;
    // Preparar la primera consulta
    $stmt1 = $conn->prepare($sql1);
    // Vincular los parámetros para la primera consulta
    $stmt1->bind_param("di", $montoSobrante, $idSoc);

    // Ejecutar la primera consulta
    if (!$stmt1->execute()) {
        // Rollback en caso de error
        $conn->rollback();
        // Mensaje de error
        $mensajeError = 'Error: ' . $stmt1->error;
        $_SESSION['mensajeError'] = $mensajeError; // Guardar mensaje en la variable de sesión
        $stmt1->close();
        // header("Location: formPagNueSc.php");
        exit();
    }

    // Segunda consulta para actualizar cta_sc_saldo en cuenta_socio
    $sql2 = "UPDATE cuenta_socio SET cta_sc_saldo = cta_sc_saldo + ? WHERE fk_sc_id = ?";
    // Preparar la segunda consulta
    $stmt2 = $conn->prepare($sql2);
    // Vincular los parámetros para la segunda consulta
    $stmt2->bind_param("di", $certApor, $idSoc);

    // Ejecutar la segunda consulta
    if ($stmt2->execute()) {
        // Tercera y cuarta consulta para actualizar cta_val en cuenta_caja
        $sql3 = "UPDATE cuenta_caja SET cta_val = cta_val + ? WHERE pk_cta_id = 2";
        $sql4 = "UPDATE cuenta_caja SET cta_val = cta_val + ? WHERE pk_cta_id = 3";
        // Preparar la tercera consulta
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("d", $gastAdm);
        $stmt3->execute();
        $stmt3->close();
        // Preparar la cuarta consulta
        $stmt4 = $conn->prepare($sql4);
        $stmt4->bind_param("d", $fondEst);
        if ($stmt4->execute()) {
            // Commit si todas las consultas fueron exitosas
            $conn->commit();
            // Mensaje de éxito
            $mensajeExito = '¡Pago realizado con exitoso!';
            $_SESSION['mensajeExito'] = $mensajeExito; // Guardar mensaje en la variable de sesión
            header("Location: formPagNueSc.php");
            exit();
        } else {
            // Rollback en caso de error
            $conn->rollback();
            // Mensaje de error
            $mensajeError = 'Error: ' . $stmt4->error;
            $_SESSION['mensajeError'] = $mensajeError; // Guardar mensaje en la variable de sesión
        }
        $stmt4->close();
    } else {
        // Rollback en caso de error
        $conn->rollback();
        // Mensaje de error
        $mensajeError = 'Error: ' . $stmt2->error;
        $_SESSION['mensajeError'] = $mensajeError; // Guardar mensaje en la variable de sesión
    }

    // Cerrar las consultas preparadas
    $stmt1->close();
    $stmt2->close();
    $stmt->close();

}
?>
