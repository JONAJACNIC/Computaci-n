<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes Pendientes</title>
    <link rel="stylesheet" href="../style.css">
    
</head>

<body>
    <div class="container-fluid">
        <h5 class="text-center"> Solicitudes Pendientes </h5>
        <div class="card">
            <div class="card-header fw-bold">
                Información
            </div>
            <div class="card-body">
                <!-- Barra de búsqueda -->
                <div class="row mb-2">
                    <!-- Campo nombres -->
                    <div class="col-md-3 d-flex align-items-center py-3">
                        <label for="buscar" class="form-label">Buscar</label>
                        <input type="text" class="form-control" name="buscar" id="buscar" placeholder="" readonly>
                    </div>
                    <table class="table table-condensed table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Cédula</th>
                                <th>Monto Solicitado</th>
                                <th>Fecha Solicitud</th>
                                <th>Tipo Préstamo</th>
                                <th>Estado Préstamo</th>
                                <th>Aprobaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include('../conexion.php');
                            $sql = "SELECT socio.pk_sc_id, socio.sc_nombre, socio.sc_apellido, socio.sc_cedula, solicitud_prestamo.soli_pres_montSolic, solicitud_prestamo.soli_pres_fech,
                            tipo_prestamo.tp_pres_dsc, estado_solicitud.est_soli_dsc
                            FROM socio
                            INNER JOIN solicitud_prestamo ON socio.pk_sc_id = solicitud_prestamo.fk_sc_id
                            INNER JOIN tipo_prestamo ON solicitud_prestamo.fk_tp_pres_id = tipo_prestamo.pk_tp_pres_id
                            LEFT JOIN estado_solicitud ON solicitud_prestamo.fk_est_soli_id = estado_solicitud.pk_est_soli_id
                            WHERE estado_solicitud.pk_est_soli_id = 1 
                            ORDER BY solicitud_prestamo.pk_soli_pres_id ASC;";

                            $result = $conn->query($sql);
                            // Mostrar datos en la tabla
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr id='row_" . $row['pk_sc_id'] . "'>";
                                echo "<td>" . $row['sc_nombre'] . "</td>";
                                echo "<td>" . $row['sc_apellido'] . "</td>";
                                echo "<td>" . $row['sc_cedula'] . "</td>";
                                echo "<td>" . $row['soli_pres_montSolic'] . "</td>";
                                echo "<td>" . $row['soli_pres_fech'] . "</td>";
                                echo "<td>" . $row['tp_pres_dsc'] . "</td>";
                                echo "<td>" . $row['est_soli_dsc'] . "</td>";
                                echo "<td><button class='btn btn-outline-success btnAprobar' data-id='" . $row['pk_sc_id'] . "'>Aprobar</button></td>";
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
        <!-- Agregar un modal para confirmar la aprobación -->
        <div class="modal fade" id="aprobModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirmar Aprobación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Seleccione resposnsables de aprobación?
                        <?php
                        include('../conexion.php');
                        // Consulta SQL para obtener los datos de la tabla cargo_socio
                        $sql = "SELECT pk_crg_id, crg_dsc FROM cargo_socio";
                        $result = $conn->query($sql);

                        // Verificar si hay resultados
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $pk_crg_id = $row['pk_crg_id'];
                                $crg_dsc = $row['crg_dsc'];

                                // Imprimir el HTML para cada checkbox con los datos de la base de datos
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="checkbox" id="checkbox_' . $pk_crg_id . '" name="checkbox[]" value="' . $pk_crg_id . '">';
                                echo '<label class="form-check-label" for="checkbox_' . $pk_crg_id . '">' . $crg_dsc . '</label>';
                                echo '</div>';
                            }
                        } else {
                            echo "No se encontraron cargos de socio en la base de datos.";
                        }

                        // Cerrar la conexión a la base de datos
                        $conn->close();
                        ?>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-success" id="btnConfirmarAprobacion" disabled>Sí</button>
                    </div>
            </div>
        </div>
    </div>
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Manejar el cambio en los checkboxes
                $('input[name="checkbox[]"]').on('change', function() {
                // Verificar si al menos dos están seleccionados
                if ($('input[name="checkbox[]"]:checked').length >= 2) {
                    // Habilitar el botón
                    $('#btnConfirmarAprobacion').prop('disabled', false);
                } else {
                    // Deshabilitar el botón si no se cumple la condición
                    $('#btnConfirmarAprobacion').prop('disabled', true);
                }
            });
            // Mostrar el modal al hacer clic en el botón Aprobar
            $('.btnAprobar').on('click', function() {
                var idAprobacion = $(this).data('id');

                // Configurar el modal para enviar el ID de la aprobación
                $('#btnConfirmarAprobacion').data('idAprobacion', idAprobacion);

                // Mostrar el modal
                $('#aprobModal').modal('show');
            });

            // Manejar el clic en el botón Sí en el modal
            $('#btnConfirmarAprobacion').on('click', function() {
                var idAprobacion = $(this).data('idAprobacion');

                // Enviar la solicitud AJAX para realizar la aprobación
                $.ajax({
                    type: 'POST',
                    url: '../prestamos/aprobar_Prestamo.php', // Ruta correcta a tu script PHP para aprobar el préstamo
                    data: { btnAprobar: true, idAprobacion: idAprobacion },
                    dataType: 'json',
                    success: function(response) {
                        // Manejar la respuesta del servidor
                        if (response.success) {
                            alert(response.message); // Puedes usar una modalidad diferente para mostrar mensajes
                            // Eliminar la fila de la tabla
                            $('#row_' + idAprobacion).remove();
                        } else {
                            alert('Error al realizar la aprobación.');
                        }
                        // Cerrar el modal después de la respuesta del servidor
                        $('#aprobModal').modal('hide');
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
