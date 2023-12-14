<?php
include('conexion.php');

// Verificar que la solicitud sea de tipo POST y se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRegistrar"])) {
    // Recuperar los datos del formulario
    $cantPres = $_POST['cantPres'];
    $fechaSol = isset($_POST['fechaSol']) ? $_POST['fechaSol'] : '';
    $id = isset($_POST['idSoc']) ? intval($_POST['idSoc']) : 0;
    
    $tpPrest = $_POST['tipPrest'];
    //$idGarante = $_POST['garante'];
    $idRangoEs = $_POST['idRange'];
        // Verificar y formatear la fecha
        $fechaDesFormateada = date('Y-m-d', strtotime($fechaSol));
        if (strtotime($fechaDesFormateada) === false) {
            echo "Error: La fecha de desembolso no es válida.";
        } else {
    // Realizar la inserción en la base de datos
   $sqlInsert = "INSERT INTO solicitud_prestamo (soli_pres_montSolic, soli_pres_fech, fk_sc_id, fk_tp_pres_id, fk_ran_mont_id) VALUES ('$cantPres', '$fechaSol', '$id', '$tpPrest', '$idRangoEs')";

    // Ejecutar la consulta
    if ($conn->query($sqlInsert) === TRUE) {
        echo "Registro insertado con éxito";
    } else {
        echo "Error al insertar el registro: " . $conn->error;
    } 
}
}

// Cerrar la conexión a la base de datos
$conn->close();
?>