<?php
//include('registrarPres.php');
include('../buscar/guardar_dato.php');
include("../conexion.php");
// Iniciar sesión si no está iniciada
session_start();
date_default_timezone_set('America/Guayaquil');
// Calcular la fecha 90 días después
$fechaActual = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="container-fluid">
        <h5 class="text-center"> Solicitud Préstamo</h5>
        <form action="registrarPres.php" method="POST" id="miFormulario">
            <!-- Bucar socio -->
            <div class="card mb-1">
                <div class="card-header fw-bold">
                    Datos Socio
                </div>
                <div class="card-body">
                    <!-- Barra de busqueda -->
                    <div class="row mb-2">
                        <?php include('../buscar/buscar.php'); ?>
                        <?php
                        // Mostrar el dato seleccionado
                        if (!empty($_SESSION['datos_formulario'])) {
                            $datosFormulario = $_SESSION['datos_formulario'];
                            unset($_SESSION['datos_formulario']);
                        }
                        ?>
                        <!-- Fila 1 -->
                        <!-- Campo id socio -->
                       
                        <!-- Campo nombres -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="nombre" class="form-label">Nombres</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo isset($datosFormulario['nombre']) ? htmlspecialchars($datosFormulario['nombre']) : ''; ?>" readonly>
                        </div>
                        <!-- fin campo nombres -->
                        <!-- Campo apellidos -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="apellido" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" name="apellido" id="apellido" value="<?php echo isset($datosFormulario['apellido']) ? htmlspecialchars($datosFormulario['apellido']) : ''; ?>" readonly>
                        </div>
                        <!-- fin campo apellidos -->
                        <!-- Campo cédula -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="cedula" class="form-label ">Cédula</label>
                            <input type="text" class="form-control" name="cedula" id="cedula" value="<?php echo isset($datosFormulario['cedula']) ? htmlspecialchars($datosFormulario['cedula']) : ''; ?>" required readonly>
                        </div>
                        <!-- fin campo cédula -->
                        <!-- Datos de la cuenta socio -->
                        <?php
                        // Verificar si el saldo de la cuenta está establecido
                        if (isset($datosFormulario['saldo_cuenta'])) {
                            // Obtener el saldo de la cuenta y convertirlo a un número
                            $saldoCuenta = floatval($datosFormulario['saldo_cuenta']);

                            // Calcular el monto máximo sin garante (tres veces el saldo de la cuenta)
                            $montoMaxSinGarante = $saldoCuenta * 3;

                            // Verificar si el monto máximo sin garante excede los 6000
                        if ($montoMaxSinGarante > 6000) {
                            $aviso = "Solo se realizan préstamos hasta $6000";
                        } else {
                            $aviso = "";
                            }
                        } else {
                            // Si el saldo de la cuenta no está establecido, asignar un valor predeterminado
                            $montoMaxSinGarante = 0; // O cualquier otro valor por defecto
                            $aviso = ""; // No se puede calcular sin el saldo de la cuenta
                        }
                        ?>

                        <div class="col-md-3 d-flex align-items-center">
                        <label for="saldo_cuenta" class="form-label">Dinero en cuenta</label>
                        <input type="text" class="form-control" name="saldo_cuenta" id="saldo_cuenta" value="<?php echo isset($datosFormulario['saldo_cuenta']) ? htmlspecialchars($datosFormulario['saldo_cuenta']) : ''; ?>" readonly>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                        <label for="monto_maximo_sin_garante" class="form-label">Monto máximo sin garante</label>
                        <input type="text" class="form-control" name="monto_maximo_sin_garante" id="monto_maximo_sin_garante" value="<?php echo number_format($montoMaxSinGarante, 2); ?>" readonly>
                        <?php if (!empty($aviso)) : ?>
                            <p class="aviso"><?php echo $aviso; ?></p>
                        <?php endif; ?>
                    </div>
                        <!-- fin Datos de la cuenta socio -->
                    </div>
                    <!--  fin barra de busqueda -->
                </div>

            </div>
            <!-- fin bucar socio -->
            <!-- Sección Vericacion Documentos -->
            <div class="card mb-1 documentos-container">
                <div class="card-header">
                    Veficación Documentos
                </div>
                <div class="card-body">
                    <!-- Fila 1 -->
                    <div class="row mb-2 justify-content-center">
                        <?php                        
                        // Consulta SQL para obtener los datos de la tabla documento_prestamo
                        $sql = "SELECT pk_doc_pres_id, doc_pres_dsc, doc_pres_est FROM documento_prestamo WHERE doc_pres_est = 'Activo' ORDER BY pk_doc_pres_id";
                        $result = $conn->query($sql);
                        // Verificar si hay resultados
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $pk_doc_pres_id = $row['pk_doc_pres_id'];
                                $doc_pres_dsc = $row['doc_pres_dsc'];
                                // Imprimir el HTML para cada campo según los datos de la base de datos
                                echo '<div class="col-md-3">';
                                echo '<label for="doc_pres_' . $pk_doc_pres_id . '" class="form-label">' . $doc_pres_dsc . '</label>';
                                // Dentro del bucle while donde generas los checkboxes
                                echo '<input type="checkbox" id="doc_pres_' . $pk_doc_pres_id . '" name="doc_pres[]" value="' . $pk_doc_pres_id . '">';
                                echo '</div>';
                            }
                        } else {
                            echo "No se encontraron documentos activos en la base de datos.";
                        }
                        ?>
                        <!-- fin fila 1 -->
                    </div>
                </div>
            </div>
            <!-- Fin Sección Verificacion Documentos -->
            <!-- Sección Datos prestamo -->
            <div class="card mb-1">
                <div class="card-header">
                    Datos de Solicitud
                </div>
                <div class="card-body">
                    <!-- Fila 1 -->
                    <div class="row mb-2 justify-content-center">
                        <!-- Campo fecha de solicitud -->
                        <div class="col-md-3 d-flex">
                            <label for="fechaSol" class="form-label  me-2">Fecha</label>
                            <input type="date" class="form-control w-50" name="fechaSol" id="fechaSol" value="<?php echo date("Y-m-d"); ?>" readonly>
                        </div>
                        <!-- Cantidad prestamo -->
                        <div class="col-md-4 d-flex ">
                            <label for="cantPres" class="form-label me-3">Monto Deseado</label>
                            <input type="text" class="form-control w-50" name="cantPres" id="cantPres" >
                        </div>
                        <!-- fin campo cantidad prestamo -->
                        <!-- Campo tipo prestamo -->
                        <div class="col-md-5 d-flex mb-2 ">
                            <label for="tipPrest" class="form-label me-2">Tipo de Préstamo</label>
                            <select name="tipPrest" id="tipPrest" class="form-select w-50" required>
                                <option value=""> ---- </option>
                                <?php
                                // Consulta para obtener los datos de la tabla tipo de egresos
                                $sql = "SELECT pk_tp_pres_id, tp_pres_dsc FROM tipo_prestamo;";
                                $result = $conn->query($sql);
                                // Generar las opciones dinámicamente
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['pk_tp_pres_id']}'>{$row['tp_pres_dsc']}</option>";
                                }
                                ?>
                            </select>
                        </div><!-- fin campo tipo prestamo -->
                    </div><!-- fIN Fila 1 -->
                    <!-- Fila 2 -->
                    <div class="row mb-2 justify-content-center">
                        <input type="hidden" name="idRange" id="idRange">
                        <!-- tiempo PLAZO pago -->
                        <div class="col-md-4 d-flex">
                            <label for="tiemPag" class="form-label me-2">N° Meses</label>
                            <input name="tiemPag" id="tiemPag" class="form-control w-50" required>
                        </div>
                        <!-- fin campo tiempo Pago -->
                        <!-- Tasa interes -->
                        <div class="col-md-4 d-flex ">
                            <label for="rangInt" class="form-label me-5"> Tasa Interés</label>
                            <input name="rangInt" id="rangInt" class="form-control w-50" readonly>
                        </div>
                        <!-- fin Tasa interes -->
                        <!-- fin fila 1 -->
                    </div><!-- fIN Fila 2 -->
                </div>
            </div>
            <!-- Fin Sección Datos prestamo -->

            <!-- Informacion traida del modal -->
            <div class="card mb-1" id="datosGaranteContainer" style="display: none;">
                <div class="card-header fw-bold">
                    Datos Garante
                </div>
                <div class="card-body">
                    <!-- Campos para mostrar los datos del garante -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="cedulaGarante" class="form-label">Cédula Garante:</label>
                            <input type="text" class="form-control" id="cedulaGaranteInput" name="cedulaGarante" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="nombreGarante" class="form-label">Nombres Garante:</label>
                            <input type="text" class="form-control" id="nombreGaranteInput" name="nombreGarante" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="apellidoGarante" class="form-label">Apellidos Garante:</label>
                            <input type="text" class="form-control" id="apellidoGaranteInput" name="apellidoGarante" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Campo valor cuenta -->
            <div class="col-md-3 d-flex align-items-center">
                <input type="hidden" class="form-control" name="valCuenta" id="valCuenta" value="<?php echo isset($datosFormulario['saldo_cuenta']) ? htmlspecialchars($datosFormulario['saldo_cuenta']) : ''; ?>" required readonly>
            </div>
            <!-- fin campo valor cuenta -->
            <!-- Modal garante -->
            <div class="modal modal-lg fade" id="mGarante" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="miModalLabel"> Datos Secundarios</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="card mb-1 documentos-container">
                                <div class="card-header">
                                    Ingresa cedula garante:
                                </div>
                                <div class="card-body">
                                    <!-- Fila 1 -->
                                    <div class="row mb-2 justify-content-center">
                                        <!-- Barra de busqueda -->
                                        <div class="row mb-2">
                                            <div class="col-md-3 d-flex align-items-center">
                                                <label for="nombre" class="form-label">buscar:</label>
                                                <input type="text" class="form-control" id="cedulam" name="cedulam" >
                                            </div>

                                            <input type="hidden" class="form-control" id="idSocio" name="idSocio" readonly>
                                            <input type="hidden" class="form-control" name="idSoc" id="idSoc" value="<?php echo isset($datosFormulario['idSoc']) ? htmlspecialchars($datosFormulario['idSoc']) : ''; ?>" required readonly>

                                            <!-- Campos para mostrar los datos del garante -->
                                            <div class="col-md-3 d-flex align-items-center">
                                                <label for="nombre" class="form-label">Nombres:</label>
                                                <input type="text" class="form-control" id="nombreG" name="nombreG" readonly>
                                            </div>
                                            <div class="col-md-3 d-flex align-items-center">
                                                <label for="apellido" class="form-label">Apellidos:</label>
                                                <input type="text" class="form-control" id="apellidoG" name="apellidoG" readonly>
                                            </div>
                                            <!-- cuenta valor g -->
                                            <div class="col-md-3 d-flex align-items-center">
                                                <label for="apellido" class="form-label">Saldo Cuenta:</label>
                                                <input type="text" class="form-control" id="valG" name="valG" readonly>
                                            </div>
                                            <!-- fin cuenta valor g -->

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-1 documentos-container">
                                <div class="card-header">
                                    Verificación Documentos
                                </div>
                                <div class="card-body">
                                    <!-- Fila 1 -->
                                    <div class="row mb-2 justify-content-center">
                                        <?php
                                        
                                        // Consulta SQL para obtener los datos de la tabla documento_prestamo
                                        $sql = "SELECT pk_doc_pres_id, doc_pres_dsc, doc_pres_est FROM documento_prestamo WHERE doc_pres_est = 'Activo' ORDER BY pk_doc_pres_id";
                                        $result = $conn->query($sql);
                                        // Verificar si hay resultados
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $pk_doc_pres_id = $row['pk_doc_pres_id'];
                                                $doc_pres_dsc = $row['doc_pres_dsc'];
                                                // Imprimir el HTML para cada campo según los datos de la base de datos
                                                echo '<div class="col-md-3">';
                                                echo '<label for="doc_pres_' . $pk_doc_pres_id . '" class="form-label">' . $doc_pres_dsc . '</label>';
                                                // Dentro del bucle while donde generas los checkboxes
                                                echo '<input type="checkbox" ' . $pk_doc_pres_id . '" name="doc_nu[]" value="' . $pk_doc_pres_id . '">';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo "No se encontraron documentos activos en la base de datos.";
                                        }
                                        ?>
                                        <!-- fin fila 1 -->
                                    </div>
                                </div>
                            </div>
                            <!-- Mensaje de resultado -->
                            <div id="mensajeResultado" class="text-danger"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="btnAceptar" data-bs-dismiss="modal">Aceptar</button>

                        </div>
                    </div>
                </div>
            </div>

            <!-- fin modal garante -->
            <!-- Fin Sección Verificacion Documentos Garante-->
            <!-- Seccion  Botón  -->
            <div class="py-2 text-center">
                <input type="submit" value="Registrar" id="btnRegistrar" name="btnRegistrar" class="btn btn-outline-success">
                <input type="submit" value="Limpiar" class="btn btn-outline-success">
                <input type="submit" value="Imprimir" class="btn btn-outline-success">
                <input type="submit" value="Eliminar" class="btn btn-outline-success">
            </div>
        </form>
    </div>

    <script src="../node_modules/jquery/dist/jquery.js "></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>    
    <script src="valiPres.js"></script>
    

<script>
        $(document).ready(function() {
            $("#toastExito").toast("show");
            $("#toastError").toast("show");
            //deshabilitarCamposExcepto("searchInput","idSoc");
            //habilitarCamposExcepto("idSoc");
        });
    </script>   
 

</body>

</html>
