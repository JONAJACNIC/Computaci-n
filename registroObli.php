<?php
include('conexion.php');

// Verificar que la solicitud sea de tipo POST y se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRegistrar"])) {
    // Validar y escapar los datos del formulario
    $id = isset($_POST['cuarto_valor']) ? intval($_POST['cuarto_valor']) : 0;
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;

    // Obtener las fechas seleccionadas del formulario
    $fechasSeleccionadas = isset($_POST['fechasSeleccionadas']) ? explode(",", $_POST['fechasSeleccionadas']) : [];

    // Convertir el array a una cadena usando implode
    $cadenaFechas = implode(", ", $fechasSeleccionadas);

    // Imprimir la cadena
    echo $cadenaFechas;

    // Actualizar el campo cta_sc_saldo en la tabla cuenta_socio
    $sql = "UPDATE cuenta_socio SET cta_sc_saldo = cta_sc_saldo + ? WHERE fk_sc_id = ?";

    // Preparar la declaración SQL
    $stmt = $conn->prepare($sql);

    // Vincular los parámetros
    $stmt->bind_param("di", $total, $id);

    // Ejecutar la declaración
    if ($stmt->execute()) {
        echo "Actualización exitosa.";
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    echo "Error: Solicitud no válida.";
}

// Cerrar la conexión a la base de datos
$conn->close();
