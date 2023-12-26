<?php
// Verificar si se recibieron los datos esperados
if (isset($_POST['dato_seleccionado'])) {
    // Obtener el objeto de sugerencia completo
    $datoSeleccionado = json_decode($_POST['dato_seleccionado'], true);
    if ($datoSeleccionado !== null) {
        // Almacenar la información en la sesión
        session_start();
        $_SESSION['datos_formulario'] = $datoSeleccionado;
        // Crear un array adicional con la información que deseas incluir en la respuesta
        $infoGuardada = $datoSeleccionado;
        // Respondemos con un mensaje de éxito y la información guardada
        echo json_encode(array('status' => 'success', 'message' => 'Dato seleccionado guardado con éxito.'));
    } else {
        // Si el JSON no se pudo decodificar, respondemos con un mensaje de error
        echo json_encode(array('status' => 'error', 'message' => 'No se recibieron datos válidos.'));
    }
}
?>