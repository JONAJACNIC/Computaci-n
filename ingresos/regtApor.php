<?php
include('../conexion.php');

// Verificar que la solicitud sea de tipo POST y se haya enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnRegistrar"])) {
    // Validar y escapar los datos del formulario
    $id = isset($_POST['idSoc']) ? intval($_POST['idSoc']) : 0;
    $certTbl = isset($_POST['totCert']) ? floatval($_POST['totCert']) : 0;
    $fondTbl = isset($_POST['totFond']) ? floatval($_POST['totFond']) : 0;
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
    $adiCert = isset($_POST['adiCert']) ? floatval($_POST['adiCert']) : 0;
    $adiFond = isset($_POST['adiFond']) ? floatval($_POST['adiFond']) : 0;
    $fechaP = $_POST['fechap'];
    $fechaP = date('Y-m-d', strtotime($fechaP));    
    echo $fechaP;
    $aporteId = isset($_POST['clavesPrimariasSeleccionadas']) ? intval($_POST['clavesPrimariasSeleccionadas']) : 0;

    // Sentencia preparada para el primer INSERT
    $stmt1 = $conn->prepare("INSERT INTO ingreso (ingre_val, ingre_fech_ini, fk_tp_ingre_id, fk_sc_id, fk_cta_id) VALUES (?, ?, 12, ?, 5)");
    $stmt1->bind_param("dsi", $certTbl, $fechaP, $id);

    // Sentencia preparada para el segundo INSERT
    $stmt2 = $conn->prepare("INSERT INTO ingreso (ingre_val, ingre_fech_ini, fk_tp_ingre_id, fk_sc_id, fk_cta_id) VALUES (?, ?, 13, ?, 3)");
    $stmt2->bind_param("dsi", $fondTbl, $fechaP, $id);

    // Ejecutar las primeras dos declaraciones
    $resultado1 = $stmt1->execute();
    $resultado2 = $stmt2->execute();

    // Cerrar las sentencias preparadas
    $stmt1->close();
    $stmt2->close();

    // Verificar si las primeras dos sentencias se ejecutaron correctamente
    if ($resultado1 && $resultado2) {
        // Actualizar el campo cta_sc_saldo en la tabla cuenta_socio
        $sql1 = "UPDATE cuenta_socio SET cta_sc_saldo = cta_sc_saldo + ? WHERE fk_sc_id = ?";
        // Preparar la declaración SQL
        $stmt3 = $conn->prepare($sql1);
        // Vincular los parámetros
        $stmt3->bind_param("di", $certTbl, $id);

        // Ejecutar la declaración
        if ($stmt3->execute()) {
            echo "Operaciones hasta cuenta_socio fueron exitosas.";

            // Agregar la tercera sentencia después del UPDATE en cuenta_socio
            $sql2 = "UPDATE cuenta_caja SET cta_val = cta_val + $fondTbl WHERE pk_cta_id = 3";
            if ($conn->query($sql2)) {
                echo " Operación en cuenta_caja también fue exitosa.";

                // Agregar la cuarta sentencia DELETE después de la operación en cuenta_caja
                $sql3 = "DELETE FROM aportaciones WHERE pk_aprt_id = ?";
                $stmt4 = $conn->prepare($sql3);
                $stmt4->bind_param("i", $aporteId);

                // Ejecutar la sentencia DELETE
                if ($stmt4->execute()) {
                    echo " Operación DELETE en aportaciones fue exitosa.";
                } else {
                    echo " Error al ejecutar DELETE en aportaciones: " . $stmt4->error;
                }

                // Cerrar la sentencia preparada DELETE
                $stmt4->close();
            } else {
                echo " Error al actualizar cuenta_caja: " . $conn->error;
            }
        } else {
            echo "Error al actualizar cuenta_socio: " . $stmt3->error;
        }

        // Cerrar la declaración
        $stmt3->close();
    } else {
        echo "Error en al menos una de las primeras dos operaciones.";
    }
}

// Cerrar la conexión a la base de datos

?>













