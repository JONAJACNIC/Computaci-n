<?php

$socioId = $_POST['socioId'];

// Connect to the database
$conexion = mysqli_connect("localhost", "root", "", "crisol");

// Update the estado_soli field to 2 (approved)
$sql = "UPDATE solicitud_prestamo SET fk_est_soli_id = 2 WHERE fk_sc_id = $socioId";

if (mysqli_query($conexion, $sql)) {
    // Update the aprobacion table
    $fechaActual = date("Y-m-d");
    $aprobacionDet = "Sin observaciones";
    $crgId = 1;

    $sql = "INSERT INTO aprobacion (aprob_fech, aprob_det, fk_crg_id, fk_soli_pres_id)
                          VALUES ('$fechaActual', '$aprobacionDet', $crgId, $socioId)";

    if (mysqli_query($conexion, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}

mysqli_close($conexion);
?>
