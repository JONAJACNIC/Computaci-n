<?php
// Iniciar sesión si no está iniciada
date_default_timezone_set('America/Guayaquil');
// Calcular la fecha 90 días después
$fechaActual = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liquidaciones Pendientes</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="container-fluid">
        <h5 class="text-center "> Egresos Pendientes </h5>
        <div class="card">
            <div class="card-header fw-bold">
                Información
            </div>
            <div class="card-body ">
                <!-- Fila 1  -->
                <div class="row mb-2 ">
                    <!-- Campo fecha de desembolso-->
                    <div class="col-md-4 d-flex ">
                        <label for="fechaDes" class="form-label me-1">Fecha: </label>
                        <input type="date" class="form-control w-50" name="fechaDes" id="fechaDes" value="<?php echo date("Y-m-d"); ?>" readonly>
                    </div>
                    <div class="col-md-4 d-flex ">
                        <label for="tpCuenta" class="form-label me-1">Cuenta</label>
                        <select class="form-select w-50 " required>
                            <option selected>---</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex ">
                        <label for="tpCuenta" class="form-label me-1">SALDO CUENTA</label>
                        <?php
                        include('../conexion.php');

                        $consulta = "SELECT cta_val FROM cuenta_caja WHERE pk_cta_id=1";
                        $resultado = $conn->query($consulta);

                        // Verificar si hay resultados
                        if ($resultado->num_rows > 0) {
                            $fila = $resultado->fetch_assoc();
                            $saldoCuenta = $fila['cta_val'];
                        } else {
                            $saldoCuenta = "No se encontró el saldo de la cuenta.";
                        }

                        // Cerrar la conexión
                        $conn->close();
                        ?>

                        <input type="text" class="form-control w-50" name="tpCuenta" id="tpCuenta" value="<?php echo $saldoCuenta; ?>" readonly>
                    </div>
                </div> <!-- Fin Fila 1 -->
                <!-- Fila 2  -->
                <div class="row mb-2">
                    <table class="table table-bordered table-hover text-center  mt-2 ">
                        <thead>
                            <tr>
                                <th> N°</th>
                                <th> Cédula </th>
                                <th> Nombre </th>
                                <th> Apellido </th>
                                <th> Motivo </th>
                                <th> Total a liquidar </th>
                                <th> Pagar </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include('../conexion.php');
                            $sql = "SELECT
                                egreso.pk_egre_id,
                                egreso.egre_val,
                                egreso.egre_fdesm,
                                socio.sc_cedula,
                                socio.sc_nombre,
                                socio.sc_apellido,
                                tipo_egreso.tp_egre_dsc
                            FROM
                                egreso
                            JOIN
                                socio ON egreso.fk_sc_id = socio.pk_sc_id
                            JOIN
                                tipo_egreso ON egreso.fk_tp_egre_id = tipo_egreso.pk_tp_egre_id
                            WHERE
                                egreso.fk_est_pg_pend = 2;";
                            $result = $conn->query($sql);
                            // Mostrar datos en la tabla
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr id='row_" . $row['pk_egre_id'] . "'>";
                                echo "<td>" . $row['pk_egre_id'] . "</td>";
                                echo "<td>" . $row['sc_cedula'] . "</td>";
                                echo "<td>" . $row['sc_nombre'] . "</td>";
                                echo "<td>" . $row['sc_apellido'] . "</td>";
                                echo "<td>" . $row['tp_egre_dsc'] . "</td>";
                                echo "<td>" . $row['egre_val'] . "</td>";
                                echo "<td><button class='btn btn-outline-success btnDesembolso' data-id='" . $row['pk_egre_id'] . "'>Desembolso</button></td>";
                            }

                            // Cerrar la conexión
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div> <!-- Fin Fila 2 -->
                <div class="row">
                    <!-- Campo valor total -->
                    <div class="col-md-3 d-flex align-items-right">
                        <label for="valTot" class="form-label w-100 ">Total a Pagar</label>
                        <input type="text" class="form-control" name="valTot" id="valTot" value="0" readonly>
                    </div>
                    <!-- fin campo valor total  -->
                </div>
            </div>
            <!-- Seccion  Botón  -->
            <div class="card-body text-center bg-secondary">
                <input type="submit" value="Desembolsar" id="btnDesembolso" name="btnDesembolso" class="btn btn-outline-success">
                <input type="submit" value="Imprimir" class="btn btn-outline-success">
                <input type="submit" value="Cancelar" class="btn btn-outline-success">
            </div><!-- Fin Seccion  Botón  -->
        </div>
    </div>


    <!-- Agregar un modal para confirmar el desembolso -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmar Desembolso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de realizar el desembolso?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-success" id="btnConfirmarDesembolso">Sí</button>
                </div>
            </div>
        </div>
    </div>

    </div>
    <script src="../node_modules/jquery/dist/jquery.js "></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="valdPagLiqui.js"></script>r
</body>

</html>
