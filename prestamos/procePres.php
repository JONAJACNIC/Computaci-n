<?php
include('../conexion.php');

// Verifica si la solicitud es para obtener el valor máximo
if (isset($_POST['getMaxValue']) && $_POST['getMaxValue'] === 'true') {
    // Realiza la consulta SQL para obtener el valor máximo
    $sql = "SELECT MAX(ran_mont_max) AS max_value FROM rango_monto";
    $result = $conn->query($sql);

    // Verifica si hay resultados
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maxValue = $row['max_value'];

        // Devuelve el valor máximo en formato JSON
        echo json_encode(['max_value' => $maxValue]);
    } else {
        // Manejar el caso donde no hay resultados
        echo json_encode(['max_value' => null]);
    }

    // Cerrar la conexión y finalizar el script
    $conn->close();
    exit;
}

// Obtén el monto deseado desde la solicitud POST
$montoDeseado = isset($_POST['cantPres']) ? $_POST['cantPres'] : 0;
// Inicializa un array para almacenar los resultados
$resultados = array();
// Realiza la consulta SQL
$sql = "SELECT
            r.pk_ran_mont_id,
            p.ran_plz_ncto,
            i.ran_inte_tasa_inter
        FROM
            rango_monto r
        JOIN
            rango_plazo p ON r.fk_ran_plz_id = p.pk_ran_plz_id  
        JOIN
            rango_interes i ON r.fk_ran_inte_id = i.pk_ran_inte_id
        WHERE
            $montoDeseado BETWEEN r.ran_mont_min AND r.ran_mont_max; ";

$result = $conn->query($sql);
// Verificar si hay resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Almacena cada resultado en el array
        $resultados[] = array(
            'ran_plz_ncto' => $row['ran_plz_ncto'],
            'ran_inte_tasa_inter' => $row['ran_inte_tasa_inter'],
            'pk_ran_mont_id' => $row['pk_ran_mont_id']
        );
    }
}

// Devuelve los resultados en formato JSON
echo json_encode($resultados);
// Cerrar la conexión
$conn->close();
