<?php
include('../conexion.php');

// Verificar si se ha hecho clic en el botón Desembolso
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnDesembolso"])) {
    // Obtener el ID de la liquidación seleccionada y la fecha
    $idLiquidacion = isset($_POST['idLiquidacion']) ? intval($_POST['idLiquidacion']) : 0;
    $fechaDes = isset($_POST['fechaDes']) ? $_POST['fechaDes'] : '';

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Actualizar la fecha y el estado de la liquidación en la tabla egreso
        $sqlUpdateEgreso = "UPDATE egreso SET fk_est_pg_pend = 1, egre_fdesm = ? WHERE pk_egre_id = ?";
        $stmtUpdateEgreso = $conn->prepare($sqlUpdateEgreso);
        $stmtUpdateEgreso->bind_param("si", $fechaDes, $idLiquidacion);
        $stmtUpdateEgreso->execute();
        $stmtUpdateEgreso->close();

        // Obtener el ID del socio asociado a la liquidación
        $sqlGetSocioId = "SELECT fk_sc_id FROM egreso WHERE pk_egre_id = ?";
        $stmtGetSocioId = $conn->prepare($sqlGetSocioId);
        $stmtGetSocioId->bind_param("i", $idLiquidacion);
        $stmtGetSocioId->execute();
        $stmtGetSocioId->bind_result($socioId);
        $stmtGetSocioId->fetch();
        $stmtGetSocioId->close();

        // Actualizar el saldo del socio en la tabla cuenta_socio
        $sqlUpdateCuentaSocio = "UPDATE cuenta_socio SET cta_sc_saldo = 0 WHERE fk_sc_id = ?";
        $stmtUpdateCuentaSocio = $conn->prepare($sqlUpdateCuentaSocio);
        $stmtUpdateCuentaSocio->bind_param("i", $socioId);
        $stmtUpdateCuentaSocio->execute();
        $stmtUpdateCuentaSocio->close();

        // Confirmar la transacción
        $conn->commit();

        // Imprimir un mensaje de éxito
        echo json_encode(['success' => true, 'message' => 'Desembolso exitoso.']);
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();

        // Imprimir un mensaje de error
        echo json_encode(['success' => false, 'message' => 'Error al realizar el desembolso.']);
        exit();
    } finally {
        // Cerrar la conexión
        $conn->close();
    }
}
?>
