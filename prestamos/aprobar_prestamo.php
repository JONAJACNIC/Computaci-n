<?php
// Incluir archivo de conexión a la base de datos
include('../conexion.php');
if (isset($_POST['btnAprobar'])) {
    // Obtener el ID de la aprobación desde la solicitud POST
    $idAprobacion = $_POST['idAprobacion'];
    // Supongamos que el campo que indica el estado de la solicitud en la tabla
    $sqlAprobacion = "UPDATE solicitud_prestamo SET fk_est_soli_id = 2 WHERE fk_sc_id = $idAprobacion";

    if ($conn->query($sqlAprobacion) === TRUE) {
        // Si la actualización se realizó con éxito, enviar una respuesta JSON al cliente
        $response = array('success' => true, 'message' => '¡Prestamo realizado con éxito!');
        echo json_encode($response);
    } else {
        // Si hubo un error en la actualización, enviar una respuesta de error al cliente
        $response = array('success' => false, 'message' => 'Error al realizar el Prestamo: ' . $conn->error);
        echo json_encode($response);
    }
} else {
    // Si no se envió el formulario correctamente, enviar una respuesta de error al cliente
    $response = array('success' => false, 'message' => 'Error en la solicitud de Prestamo.');
    echo json_encode($response);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
