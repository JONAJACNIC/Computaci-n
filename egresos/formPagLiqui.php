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
        <h5 class="text-center "> Liquidaciones Pendientes </h5>
        <div class="card">
            <div class="card-header fw-bold">
                Información
            </div>
            <div class="card-body">
                <!-- Barra de busqueda -->
                <div class="row mb-2">
                    <!-- Campo nombres -->
                    <div class="col-md-3 d-flex align-items-center py-3">
                        <label for="buscar" class="form-label">Buscar</label>
                        <input type="text" class="form-control" name="buscar" id="buscar" placeholder="A futuro " readonly>
                    </div>
                    <table class="table table-condensed table-bordered table-striped text-center ">
                        <thead>
                            <tr>
                                <th> N° Liquidación </th>
                                <th> Cédula </th>
                                <th> Nombre </th>
                                <th> Apellido </th>
                                <th> Motivo </th>
                                <th> Total a Liquidar </th>
                                <th> Botones </th>
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
                                echo "</tr>";
                            }
                            // Cerrar la conexión
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
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
    <script>
        $(document).ready(function() {
            // Mostrar el modal al hacer clic en el botón Desembolso
            $('.btnDesembolso').on('click', function() {
                var idLiquidacion = $(this).data('id');

                // Configurar el modal para enviar el ID de la liquidación
                $('#btnConfirmarDesembolso').data('idLiquidacion', idLiquidacion);

                // Configurar el mensaje del modal
                $('#confirmModal .modal-body').text('¿Estás seguro de realizar el desembolso para la liquidación N° ' + idLiquidacion + '?');

                // Mostrar el modal
                $('#confirmModal').modal('show');
            });

            // Manejar el clic en el botón Sí en el modal
            $('#btnConfirmarDesembolso').on('click', function() {
                var idLiquidacion = $(this).data('idLiquidacion');

                // Enviar la solicitud AJAX para realizar el desembolso
                $.ajax({
                    type: 'POST',
                    url: '../egresos/DesemLiquidaciones.php', // Reemplaza 'tu_script.php' con la ruta correcta a tu script PHP
                    data: { btnDesembolso: true, idLiquidacion: idLiquidacion },
                    dataType: 'json',
                    success: function(response) {
                        // Manejar la respuesta del servidor
                        if (response.success) {
                            alert(response.message); // Puedes usar una modalidad diferente para mostrar mensajes
                            // Eliminar la fila de la tabla
                            $('#row_' + idLiquidacion).remove();
                        } else {
                            alert('Error al realizar el desembolso.');
                        }
                        // Cerrar el modal después de la respuesta del servidor
                        $('#confirmModal').modal('hide');
                    },
                    error: function() {
                        alert('Error en la solicitud AJAX.');
                    }
                });
            });
        });
    </script>
</body>

</html>
