<?php
include('../conexion.php');
include('valdSc.php');

// Iniciar la sesión si no está iniciada
if (!isset($_SESSION)) {
    session_start();
}

// Verificar si se recibió una solicitud POST desde el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRegistrar"])) {
    $cedula = $_POST['cedula'];

    // Verificar si la cédula ya existe en la base de datos
    $resultadoExistencia = verificarCedulaExistente($cedula);

    if (empty($resultadoExistencia['message'])) {
        // La cédula no existe en la base de datos, realizar la inserción
        $nombre = strtoupper($_POST['nombre']);
        $apellido = strtoupper($_POST['apellido']);
        $fechaNac = $_POST['fechaNac'];
        $sexo = $_POST['sexo'];
        $direccion = $_POST['direccion'];
        $tel = $_POST['tel'];
        $cel = $_POST['cel'];
        $cor = $_POST['correo'];
        $razon = $_POST['razon'];
        $condi = $_POST['condi'];
        $tpSoc = $_POST['tpSoc'];

        // Consulta preparada para evitar la inyección SQL
        $sql = "INSERT INTO socio (sc_nombre, sc_apellido, sc_cedula, sc_fech_n, sc_telf, sc_dir, sc_cel, sc_correo, fk_rzn_id, fk_sex_id, fk_con_id, fk_tp_sc)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);

        // Vincular los parámetros
        $stmt->bind_param("ssssssssssss", $nombre, $apellido, $cedula, $fechaNac, $tel, $direccion, $cel, $cor, $razon, $sexo, $condi, $tpSoc);

        if ($stmt->execute()) {
            // Ejecutar el procedimiento almacenado después de la inserción exitosa
            $sqlProcedimiento = "CALL ActualizarTipoSocio()";

            if ($conn->query($sqlProcedimiento) === TRUE) {
                echo "Procedimiento almacenado ejecutado con éxito";
            } else {
                echo "Error al ejecutar el procedimiento almacenado: " . $conn->error;
            }

            // Mensaje de éxito
            $mensajeExito = '¡Registro exitoso!';
            $_SESSION['mensajeExito'] = $mensajeExito; // Guardar mensaje en la variable de sesión
            header("Location:formNueSc.php");
            exit();
        } else {
            // Mensaje de error
            $mensajeError = 'Error al insertar datos: ' . $stmt->error;
            $_SESSION['mensajeError'] = $mensajeError; // Guardar mensaje en la variable de sesión
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
    } else {
        // La cédula ya existe en la base de datos
        $mensajeError = $resultadoExistencia['message'];
        $_SESSION['mensajeError'] = $mensajeError; // Guardar mensaje en la variable de sesión
    }
}
?>

