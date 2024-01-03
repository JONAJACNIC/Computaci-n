<?php

// LLamado a los archivos parael funcionamiento del formulario 
include('registroLiqui.php');
include("../conexion.php");
include('../buscar/guardar_dato.php');
// Determina la fecha y zona horaria 
date_default_timezone_set('America/Guayaquil');
// Calcular fecha actual 
$fechaActual = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Liquidaciones </title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <!-- Contenedor Principal  -->
    <div class="container-fluid">
        <h5 class="text-center ">Liquidaciones</h5>
        <!-- Inicio del Formulario  -->
        <form action="" method="POST" id="soliLiqui" onsubmit="return validarFormulario()">
            <!-- Primer Card  -->
            <div class="card mb-1">
                <div class="card-header fw-bold">
                    Datos Socio
                </div>
                <div class="card-body">
                    <p id="mensajeBuscarAlert"></p>
                    <p id="mensajeCapitalError"></p>
                    <!-- Fila con datos del Buscar socio  -->
                    <div class="row mb-2">
                        <?php include('../buscar/buscar.php'); ?>
                        <?php
                        // Mostrar el dato seleccionado
                        if (!empty($_SESSION['datos_formulario'])) {
                            $datosFormulario = $_SESSION['datos_formulario'];
                            unset($_SESSION['datos_formulario']);
                        }
                        ?>
                        <!-- Campo id socio -->
                        <input type="hidden" class="form-control" name="idSoc" id="idSoc" value="<?php echo isset($datosFormulario['idSoc']) ? htmlspecialchars($datosFormulario['idSoc']) : ''; ?>" required readonly>
                        <!-- fin campo id socio -->
                        <!-- Campo nombres -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="nombre" class="form-label">Nombres</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo isset($datosFormulario['nombre']) ? htmlspecialchars($datosFormulario['nombre']) : ''; ?>" requiered readonly>
                        </div>
                        <!-- fin campo nombres -->
                        <!-- Campo apellidos -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="apellido" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" name="apellido" id="apellido" value="<?php echo isset($datosFormulario['apellido']) ? htmlspecialchars($datosFormulario['apellido']) : ''; ?>" readonly>
                        </div>
                        <!-- fin campo apellidos -->
                        <!-- Campo cédula -->
                        <div class="col-md-2 d-flex align-items-center">
                            <label for="cedula" class="form-label ">Cédula</label>
                            <input type="text" class="form-control" name="cedula" id="cedula" value="<?php echo isset($datosFormulario['cedula']) ? htmlspecialchars($datosFormulario['cedula']) : ''; ?>" required readonly>
                        </div>
                        <!-- fin campo cédula -->
                    </div><!--Fin  Fila con datos del Buscar socio  -->
                </div>
            </div><!-- Fin primer Card -->

            <!-- Segundo Card con Datos Generales -->
            <div class="card mb-1 ">
                <div class="card-header  fw-bolder">
                    Datos Generales
                </div>
                <div class="card-body ">
                    <!-- Fila 1 -->
                    <div class="row mb-2 justify-content-center">
                        <!-- Campo fecha de solicitud -->
                        <div class="col-md-4 d-flex ">
                            <label for="fechaSol" class="form-label me-1">Fecha de Solicitud</label>
                            <input type="date" class="form-control w-50" name="fechaSol" id="fechaSol" value="<?php echo date("Y-m-d"); ?>" readonly>
                        </div>
                        <!-- fin campo fecha de solicitud  -->
                        <!-- Campo Causa de Liquidación-->
                        <div class="col-md-4 d-flex ">
                            <label for="tpLiqui" class="form-label me-1">Causa de Liquidación</label>
                            <select name="tpLiqui" id="tpLiqui" class="form-select w-50" onchange="buscarSocio()" required>
                                <option value=""> ---- </option>
                                <?php
                                // Consulta para obtener los datos de la tabla tipo de egresos
                                $sql = "SELECT pk_tp_egre_id, tp_egre_dsc FROM tipo_egreso where pk_tp_egre_id IN (1, 2, 3);";
                                $result = $conn->query($sql);
                                // Generar las opciones dinámicamente
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['pk_tp_egre_id']}'>{$row['tp_egre_dsc']}</option>";
                                }
                                ?>
                            </select>
                        </div><!-- fin campo  Causa de Liquidación -->
                        <!-- Campo medio de pago-->
                        <div class="col-md-4 d-flex ">
                            <label for="tpPago" class="form-label me-2"> Medio de Pago</label>
                            <select name="tpPago" id="tpPago" class="form-select w-50" required readonly>
                                <?php
                                // Consulta para obtener los datos de la tabla tipo de pago
                                $sql = "SELECT * FROM tipo_pago;";
                                $result = $conn->query($sql);
                                // Generar las opciones dinámicamente
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['pk_tp_pago_id']}'>{$row['tp_pago_dsc']}</option>";
                                }
                                ?>
                            </select>
                        </div><!-- fin campo tipo de pago -->
                    </div><!-- Fin Fila 1 -->
                </div>
            </div><!-- Fin Segundo Card con Datos Generales -->

            <!-- Tercer  Card con Calculos de liquidacion -->
            <div class="card mb-2 ">
                <div class="card-header fw-bold">
                    Bases del Cálculo de Liquidación
                </div>
                <div class="card-body">
                    <!-- Fila 1 -->
                    <div class="row mb-2  justify-content-center">
                        <!--  Total de aportes de Capital  -->
                        <div class="col-md-3 d-flex ">
                            <label for="capital" class="form-label me-5"> Capital </label>
                            <input type="text" class="form-control w-50 " name="capital" id="capital" disabled>
                        </div><!-- Fin de aportes de Capital -->
                        <!-- Recargo de Multas -->
                        <div class="col-md-3 d-flex ">
                            <label for="multas" class="form-label pe-4"> Multas </label>
                            <input type="text" class="form-control w-50 " name="multas" id="multas" disabled>
                        </div><!-- Fin Recargo de Multas -->
                        <!-- Recargo de prestamos-->
                        <div class="col-md-3 d-flex ">
                            <label for="prest" class="form-label me-1"> Préstamos </label>
                            <input type="text" class="form-control w-50" name="prest" id="prest" disabled>
                        </div><!-- Fin Recargo de prestamos -->
                    </div> <!-- Fin Fila 1 -->
                    <!--  Fila 2-->
                    <div class="row justify-content-center ">
                        <!-- Valor Total deduciones-->
                        <div class="col-md-3 d-flex ">
                            <label for="totalR" class="form-label me-4"> Descuento </label>
                            <input type="text" class="form-control w-50" name="totalR" id="totalR" disabled>
                        </div><!-- Valor Total deduciones -->
                        <!-- Div para espacio en medio-->
                        <div class="col-md-3 d-flex ">
                        </div><!-- Fin para espacio en medio -->
                        <!-- Valor Total a Liquidar-->
                        <div class="col-md-3 d-flex ">
                            <label for="totalLi" class="form-label me-5"> Total </label>
                            <input type="text" class="form-control w-50" name="total" id="total" value="<?php echo isset($total_pagar) ? ' $' . $total_pagar : ''; ?>" readonly>
                        </div><!-- Valor Total deduciones -->
                    </div><!-- Fin Fila 2-->
                </div>
            </div><!-- Fin Tercer  Card con Calculos de liquidacion -->

            <!-- Seccion  Botón  -->
            <div class="py-2 text-center">
                <input type="submit" value="Registrar" id="btnRegistrar" name="btnRegistrar" class="btn btn-outline-success">
                <input type="submit" value="Cancelar" id="btnCancelar" name="btnCancelar" class="btn btn-outline-success">
            </div><!-- Fin Seccion  Botón  -->
        </form><!-- Fin del Formulario  -->

        <!-- Contenedor de Alertas -->
        <div class="mt-1 d-flex justify-content-center">
            <?php
            // Verifica si hay mensajes de éxito o error en la variable de sesión
            if (isset($_SESSION['mensajeExito'])) {
                echo '<div id="toastExito" class="w-25 toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">' .
                    '<div class="d-flex justify-content-center align-items-center">' . // Añadida clase "justify-content-center align-items-center"
                    '<div class="toast-body text-center">' . // Añadida clase "text-center"
                    '<img src="iconos/green-checkmark-icon.svg" alt="Icono de Socio" class="me-2" style="width: 24px; height: 24px;" />' .
                    $_SESSION['mensajeExito'] .
                    '</div>' .
                    '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' .
                    '</div>' .
                    '</div>';
                unset($_SESSION['mensajeExito']); // Limpiar la variable de sesión
            }
            ?>
        </div><!-- Fin  Contenedor de Alertas -->
    </div> <!-- Fin Contenedor Principal  -->

    <!-- Librerias de JavaScript utilizadas  -->
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../buscar/scrip.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="valdLiqui.js"></script>
    <script>
        $(document).ready(function() {
            $("#toastExito").toast("show");
            deshabilitarCamposExcepto("searchInput");
            habilitarCamposExcepto("idSoc");
        });
    </script>
</body>

</html>
