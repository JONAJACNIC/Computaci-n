<?php
include("conexion.php");

if (isset($_GET['buscar'])) {
    $buscar = $_GET['buscar'];

    $consulta = "SELECT s.pk_sc_id, s.sc_nombre, s.sc_apellido, s.sc_cedula,
                        cs.pk_cta_sc_id, cs.cta_sc_numero, cs.cta_sc_saldo,
                        pp.pk_pg_pend, pp.pg_pend_monto, pp.pg_pend_fech_ven, pp.fk_est_pg_pend
                 FROM socio s
                 INNER JOIN cuenta_socio cs ON s.pk_sc_id = cs.fk_sc_id
                 INNER JOIN pago_pendiente pp ON s.pk_sc_id = pp.fk_sc_id
                 WHERE s.fk_est_id = 3 AND (s.sc_nombre LIKE ? OR s.sc_apellido LIKE ? OR s.sc_cedula LIKE ?)
                 LIMIT 10"; // Puedes ajustar el límite según tus necesidades

    $stmt = $conn->prepare($consulta);
    $buscarParam = "%{$buscar}%";
    $stmt->bind_param('sss', $buscarParam, $buscarParam, $buscarParam);
    $stmt->execute();

    $resultados = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($resultados)) {
        // No hay resultados, enviar mensaje de error
        echo json_encode(array('error' => 'No se encontraron resultados'));
    } else {
        // Hay resultados, enviar la respuesta JSON
        echo json_encode($resultados);
    }
}
