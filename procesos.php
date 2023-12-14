<?php
include('conexion.php');
if (!empty($_SESSION['datos_formulario'])) {
    $datosFormulario = $_SESSION['datos_formulario'];
    $id_socio = $datosFormulario['idSoc'];
    // Consulta para obtener el total de ingresos del socio
    $sql_total_ingresos = "SELECT cta_sc_saldo FROM cuenta_socio WHERE fk_sc_id = $id_socio;";
    $resultado_ingresos = $conn->query($sql_total_ingresos);
    // Inicializar $total_multas en 0
    $total_multas = 0;
    // Consulta para obtener multas
    $sql_multas =" SELECT m.mult_total FROM multa m 
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
        }
    } else {
        echo "No se encontraron ingresos para el socio con ID $id_socio.";
    }
}

// Cerrar la conexión a la base de datos si es necesario

$conn->close();
