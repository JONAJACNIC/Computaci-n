<?php
// Archivo de conexión a la base de datos (asegúrate de tenerlo)
include("conexion.php");
session_start();

// Obtener el término de búsqueda desde la solicitud AJAX
$searchTerm = "%" . $_POST['term'] . "%";

// Realizar la consulta SQL
$sql = "SELECT s.pk_sc_id, s.sc_nombre, s.sc_apellido, s.sc_cedula, s.sc_fech_n, s.sc_telf, s.sc_dir, s.sc_cel, s.sc_correo, s.fk_rzn_id, s.fk_sex_id, s.fk_con_id, s.fk_tp_sc,c.pk_cta_sc_id, c.cta_sc_numero, c.cta_sc_saldo
        FROM socio s
        LEFT JOIN cuenta_socio c ON s.pk_sc_id = c.fk_sc_id
        WHERE (s.sc_nombre LIKE ? OR s.sc_apellido LIKE ? OR s.sc_cedula LIKE ?)
        AND s.fk_est_id = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Inicializar un array para almacenar las sugerencias
$suggestions = array();

// Mostrar sugerencias en formato de lista
while ($row = $result->fetch_assoc()) {
    $suggestions[] = array(
        'idSoc' => $row['pk_sc_id'],
        'nombre' => $row['sc_nombre'],
        'apellido' => $row['sc_apellido'],
        'cedula' => $row['sc_cedula'],
        'fecha_nac' => $row['sc_fech_n'],
        'telefono' => $row['sc_telf'],
        'direccion' => $row['sc_dir'],
        'celular' => $row['sc_cel'],
        'correo' => $row['sc_correo'],
        'razon_social' => $row['fk_rzn_id'],
        'sexo' => $row['fk_sex_id'],
        'condicion' => $row['fk_con_id'],
        'tipo_socio' => $row['fk_tp_sc'],
        'idCue' => $row['pk_cta_sc_id'],
        'numero_cuenta' => $row['cta_sc_numero'],
        'saldo_cuenta' => $row['cta_sc_saldo']
    );
}

// Almacenar los resultados en la sesión solo si hay resultados
if (!empty($suggestions)) {
    $_SESSION['searchResults'] = $suggestions;
} else {
    echo "No hay resultados disponibles.";
}

$stmt->close();
$conn->close();

// Cambiar el formato de salida para que sea un JSON
header('Content-Type: application/json');
echo json_encode($suggestions);
