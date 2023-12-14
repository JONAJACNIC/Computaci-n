<?php
include('conexion.php');
function verificarCedulaExistente($cedula)
{
    global $conn;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action']) && $_POST['action'] == 'verificarCedulaExistente') {
            if (isset($_POST['cedula'])) {
                $cedula = $_POST['cedula'];

                // Verificar que la cédula tenga 10 dígitos antes de realizar la consulta
                if (!ctype_digit($cedula) || strlen($cedula) !== 10) {
                    return array('error' => 'La cédula debe contener 10 dígitos.');
                }

                // Consulta preparada para evitar la inyección SQL
                $consultaExistencia = "SELECT COUNT(*) as count FROM socio WHERE sc_cedula = ?";
                $stmtExistencia = $conn->prepare($consultaExistencia);
                $stmtExistencia->bind_param("s", $cedula);
                $stmtExistencia->execute();
                $stmtExistencia->bind_result($count);
                $stmtExistencia->fetch();
                $stmtExistencia->close();

                if ($count > 0) {
                    // La cédula ya existe en la base de datos
                    return array('message' => 'Ya es socio. Por favor, verifica la información.');
                } else {
                    // La cédula no existe en la base de datos
                    return array('message' => '');
                }
            } else {
                // No se proporcionó la cédula en la solicitud
                return array('error' => 'No se proporcionó la cédula en la solicitud.');
            }
        }
    }

    // En caso de que no se haya ejecutado la verificación
    return array('error' => 'Error en la solicitud.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'verificarCedulaExistente') {
        if (isset($_POST['cedula'])) {
            $cedula = $_POST['cedula'];
            header('Content-Type: application/json');
            echo json_encode(verificarCedulaExistente($cedula));
            exit;
        } else {
            return array('error' => 'No se proporcionó la cédula en la solicitud.');
        }
    }
}
return array('error' => 'Error en la solicitud.');
?>

