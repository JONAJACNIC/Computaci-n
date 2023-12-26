<?php
include('../conexion.php');
if (!isset($_SESSION)) {
    session_start();
}

// Verificar que la solicitud sea de tipo POST y se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRegistrar"])) {
    // Validar y escapar los datos del formulario
    $id = isset($_POST['idSoc']) ? intval($_POST['idSoc']) : 0;
    $fechaIni = isset($_POST['fechaIni']) ? $_POST['fechaIni'] : '';
    $fechaDes = isset($_POST['fechaDes']) ? $_POST['fechaDes'] : '';
    $tipGas = $_POST['tipGas'];
    $cantGas= isset($_POST['cantGas']) ? $_POST['cantGas']: '';
    
    // Verificar y formatear la fecha
    $fechaIniFormateada = date('Y-m-d', strtotime($fechaIni));
    $fechaDesFormateada = date('Y-m-d', strtotime($fechaDes));

    //Consultar Valor de Cuenta Gastos Adminsitrativos 
    $sql1="SELECT cta_val FROM cuenta_caja WHERE pk_cta_id=?";
    $aux= $conn->prepare($sql1);
    $pk_cta_id=3;
    $aux->bind_param("i",$pk_cta_id);
    $aux->execute();
    $aux->bind_result($cta_val);
    $aux->fetch();
    $aux->close(); 
    echo($cantGas);
   //Fin Valor Cuenta Gastos Administrativos

   //Comparación de Valor Solicitado y Saldo
    if ($cta_val > $cantGas){
        // Sentencia SQL preparada para la inserción
        $sql = "INSERT INTO egreso (egre_val, egre_fech_ini, egre_fdesm, fk_tp_egre_id, fk_sc_id) 
            VALUES (?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("dssii",$cantGas,$fechaIniFormateada, $fechaDesFormateada, $tipGas, $id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Inserción exitosa en la base de datos.";

        } else {
            echo "Error al insertar en la base de datos: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close(); 
       
    }else {
        echo ("No hay liquidez");
    }
   
    }

// Cerrar la conexión a la base de datos
$conn->close();
