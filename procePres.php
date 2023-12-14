<?php
include('conexion.php');
// Obtén el monto deseado desde la solicitud POST
$montoDeseado = isset($_POST['cantPres']) ? $_POST['cantPres'] : 0;
// Inicializa un array para almacenar los resultados
$resultados = array();
// Realiza la consulta SQL
$sql = "SELECT
            r.pk_ran_mont_id,
            p.ran_plz_cuota,
            i.ran_inte_tasa_inter
        FROM
            rango_monto r
        JOIN
            rango_plazo p ON r.fk_ran_plz_id = p.pk_ran_plz_id
        JOIN
            rango_interes i ON r.fk_ran_inte_id = i.pk_ran_inte_id
        WHERE
            $montoDeseado BETWEEN r.ran_mont_min AND r.ran_mont_max;";
            
$result = $conn->query($sql);
// Verificar si hay resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Almacena cada resultado en el array
        $resultados[] = $row;
    }
}
// Devuelve los resultados en formato JSON
echo json_encode($resultados);
// Cerrar la conexión
$conn->close();
?>