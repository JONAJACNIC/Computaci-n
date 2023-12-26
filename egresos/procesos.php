<?php
include('conexion.php');

// Inicializar el array de respuesta
$response = array();

// Verificar si se cumplen las condiciones para procesar la solicitud
if (isset($_POST['tpLiqui']) && isset($_POST['idSoc'])) {
    $id_socio = $_POST['idSoc'];

    // Consulta para obtener el total de ingresos del socio
    $sql_total_ingresos = "SELECT cta_sc_saldo FROM cuenta_socio WHERE fk_sc_id = $id_socio;";
    $resultado_ingresos = $conn->query($sql_total_ingresos);

    // Inicializar $total_multas en 0
    $total_multas = 0;

    // Consulta para obtener multas
    $sql_multas = "SELECT m.mult_total FROM multa m 
            INNER JOIN cuenta_socio s ON m.fk_cta_sc_id = s.pk_cta_sc_id
            WHERE m.fk_est_multa = 2 AND s.pk_cta_sc_id = $id_socio";

    // Consulta para obtener prestamos
    $sql_prestamos = 4;

    // Consulta para obtener suma de prestamos y multas 
    $result_multas = $conn->query($sql_multas);

    // Verificar si hay resultados
    if ($result_multas->num_rows > 0) {
        // Iterar sobre los resultados para sumar las multas
        while ($row = $result_multas->fetch_assoc()) {
            $total_multas += $row['mult_total'];
        }
    }

    // Obtener el valor de ingresos
    if ($resultado_ingresos->num_rows > 0) {
        $fila_ingresos = $resultado_ingresos->fetch_assoc();
        $capital = $fila_ingresos['cta_sc_saldo'];

        // Verificar si el capital es mayor que 0
        if ($capital > 0) {
            // Sumar el valor de prestamos
            $total_suma = $total_multas + $sql_prestamos;

            // Total del valor a pagar
            $total_pagar = $capital - $total_suma;

            // Actualizar el array de respuesta
            $response = array(
                'success' => true,
                'total_multas' => $total_multas,
                'sql_prestamos' => $sql_prestamos,
                'total_suma' => $total_suma,
                'capital' => $capital,
                'total_pagar' => $total_pagar
            );
        }
    } else {
        // Actualizar el array de respuesta en caso de no encontrar ingresos
        $response = array('success' => false, 'message' => "No se encontraron ingresos para el socio con ID $id_socio.");
    }
} else {
    // Actualizar el array de respuesta en caso de condiciones no cumplidas
    $response = array('success' => false, 'message' => 'No se cumplen las condiciones para procesar la solicitud.');
}

// Enviar la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión a la base de datos
$conn->close();
?>
