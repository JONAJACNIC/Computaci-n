<?php
include('conexion.php');

// Función para obtener opciones de tipo_multa
function obtenerOpcionesTipoMulta() {
    // Realizar la consulta SQL para obtener las opciones desde la tabla tipo_multa
    $sql = "SELECT pk_tp_mult_id, mult_det, mult_val FROM tipo_multa";

    // Obtener la conexión global a la base de datos
    global $conn;

    // Ejecutar la consulta
    $resultado = $conn->query($sql);

    // Verificar si la consulta fue exitosa
    if ($resultado) {
        // Obtener los resultados como un array asociativo
        $opciones = $resultado->fetch_all(MYSQLI_ASSOC);

        // Cerrar el conjunto de resultados
        $resultado->close();

        return $opciones;
    } else {
        // Manejar el error si la consulta falla
        echo "Error en la consulta: " . $conn->error;
        return []; // Retorna un array vacío en caso de error
    }
}
?>
