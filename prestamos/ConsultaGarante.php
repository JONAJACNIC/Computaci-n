<?php
// Incluye el archivo de conexión a la base de datos
include("../conexion.php");

// Verifica si la conexión a la base de datos fue exitosa
if (!$conn) {
    die(json_encode(array('error' => 'Error en la conexión a la base de datos: ' . mysqli_connect_error())));
}

// Verifica si la cédula está presente en la solicitud POST
if (isset($_POST['cedula'])) {
    // Obtener el valor de la cédula ingresada
    $cedula = trim($_POST['cedula']);

    // Utilizar una consulta preparada para prevenir la inyección SQL
    $consulta = "SELECT s.pk_sc_id, s.sc_nombre, s.sc_apellido, c.pk_cta_sc_id, c.cta_sc_numero, c.cta_sc_saldo
        FROM socio s
        LEFT JOIN cuenta_socio c ON s.pk_sc_id = c.fk_sc_id
        WHERE s.sc_cedula = ?";

    if ($stmt = mysqli_prepare($conn, $consulta)) {
        mysqli_stmt_bind_param($stmt, "s", $cedula);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $nombre, $apellido, $ctaId, $ctaNumero, $ctaSaldo);

        // Verificar si se encontraron resultados
        if (mysqli_stmt_fetch($stmt)) {
            $datos = array(
                'nombre' => $nombre,
                'apellido' => $apellido,
                'cedula' => $cedula,
                'id' => $id,
                'cta_id' => $ctaId,
                'cta_numero' => $ctaNumero,
                'cta_saldo' => $ctaSaldo
            );

            // Devuelve los datos en formato JSON
            echo json_encode($datos);
        } else {
            echo json_encode(array('error' => 'No se encontró ningún socio con esa cédula.'));
        }

        // Cierra la declaración
        mysqli_stmt_close($stmt);
    } else {
        // Manejo de errores en la preparación de la consulta
        echo json_encode(array('error' => 'Error en la preparación de la consulta: ' . mysqli_error($conn)));
    }
} else {
    // Si la cédula no está presente en la solicitud POST
    echo json_encode(array('error' => 'La cédula no está presente en la solicitud POST.'));
}

// Cierra la conexión
mysqli_close($conn);
?>