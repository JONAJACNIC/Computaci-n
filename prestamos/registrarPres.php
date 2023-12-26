<?php
include('conexion.php');

// Verificar que la solicitud sea de tipo POST y se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRegistrar"])) {
    // Recuperar los datos del formulario
    $cantPres = $_POST['cantPres'];
    $fechaSol = isset($_POST['fechaSol']) ? $_POST['fechaSol'] : '';
    $idSoc = isset($_POST['idSoc']) ? intval($_POST['idSoc']) : 0;
    $idSocio = isset($_POST['idSocio']) ? intval($_POST['idSocio']) : 0;
    $tpPrest = $_POST['tipPrest'];
    $idRangoEs = $_POST['idRange'];

    echo $idSoc . " idsoc<br>";
    echo $idSocio . " idsocio<br>";

    // Verificar y formatear la fecha
    $fechaDesFormateada = date('Y-m-d', strtotime($fechaSol));

    if ($idSocio !== 0) {
        // Utilizar una sentencia preparada para la inserción
        $sqlInsert = "INSERT INTO garante_prestamo (fk_sc_id) VALUES (?)";
        $stmtInsertGarante = $conn->prepare($sqlInsert);
        $stmtInsertGarante->bind_param("i", $idSocio);

        // Ejecutar la consulta para insertar en garante_prestamo
        if (!$stmtInsertGarante->execute()) {
            echo "Error al insertar en garante_prestamo: " . $stmtInsertGarante->error;
        }

        // Obtener el último ID insertado en garante_prestamo
        $lastGaranteId = $stmtInsertGarante->insert_id;

        // Cerrar la sentencia preparada
        $stmtInsertGarante->close();

        // Utilizar una sentencia preparada para la inserción en solicitud_prestamo
        $sqlInsertSolicitud = "INSERT INTO solicitud_prestamo (soli_pres_montSolic, soli_pres_fech, fk_sc_id, fk_tp_pres_id, fk_grt_pres_id, fk_ran_mont_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtSolicitud = $conn->prepare($sqlInsertSolicitud);
        $stmtSolicitud->bind_param("ssiiii", $cantPres, $fechaDesFormateada, $idSoc, $tpPrest, $lastGaranteId, $idRangoEs);

        // Ejecutar la consulta para insertar en solicitud_prestamo
        if ($stmtSolicitud->execute()) {
            echo "Registro insertado con éxito";
        } else {
            echo "Error al insertar el registro en solicitud_prestamo: " . $stmtSolicitud->error;
        }

        // Cerrar la sentencia preparada
        $stmtSolicitud->close();
    } else {
        // Utilizar una sentencia preparada para la inserción en solicitud_prestamo
        $sqlInsertSolicitud = "INSERT INTO solicitud_prestamo (soli_pres_montSolic, soli_pres_fech, fk_sc_id, fk_tp_pres_id, fk_ran_mont_id) VALUES (?, ?, ?, ?, ?)";
        $stmtSolicitud = $conn->prepare($sqlInsertSolicitud);
        $stmtSolicitud->bind_param("ssiii", $cantPres, $fechaDesFormateada, $idSoc, $tpPrest, $idRangoEs);

        // Ejecutar la consulta para insertar en solicitud_prestamo
        if ($stmtSolicitud->execute()) {
            echo "Registro insertado con éxito";
        } else {
            echo "Error al insertar el registro en solicitud_prestamo: " . $stmtSolicitud->error;
        }

        // Cerrar la sentencia preparada
        $stmtSolicitud->close();
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
