<?php
include('../conexion.php');
if (!isset($_SESSION)) {
    session_start();
}

// Verificar que la solicitud sea de tipo POST y se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRegistrar"])) {
    // Validar y llamar  los datos del formulario
    $id = isset($_POST['idSoc']) ? intval($_POST['idSoc']) : 0;
    $fechaIni = isset($_POST['fechaSol']) ? $_POST['fechaSol'] : '';
    $totalLi = $_POST['total'];
    $tpLiqui = $_POST['tpLiqui'];

    // Limpiar el valor eliminando caracteres no numéricos
    $totalLi = preg_replace("/[^0-9.]/", "", $totalLi);
    // Convertir a float
    $totalLi = floatval($totalLi);

    // Iniciar la transacción
    $conn->begin_transaction();
    // Sentencia SQL preparada para la inserción
    $sql = "INSERT INTO egreso (egre_val, egre_fech_ini, fk_tp_egre_id, fk_sc_id) 
            VALUES (?,  ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("dsii", $totalLi, $fechaIni, $tpLiqui, $id);

    // Ejecutar la consulta
    $ejecucionExitosa = $stmt->execute();

    // Confirmar o revertir la transacción según la ejecución de la consulta
    if ($ejecucionExitosa) {
        $conn->commit();
        $mensajeExito = '¡Registro exitoso!';
        $_SESSION['mensajeExito'] = $mensajeExito; // Guardar mensaje en la variable de sesión
        header("Location: formLiqui.php");
        exit();
    } else {
        $conn->rollback();
        echo "Error al insertar en la base de datos: " . $stmt->error;
        echo "Valor de totalLi después de la inserción: " . $totalLi;
    }
    // Cerrar la declaración
    $stmt->close();
}
// Cerrar la conexión a la base de datos
$conn->close();
