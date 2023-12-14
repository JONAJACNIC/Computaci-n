<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container-fluid">
    <h5 class="text-center ">  Solicitudes Pendientes </h5>
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
                        <input type="text" class="form-control" name="buscar" id="buscar">
                    </div>
                    <table class="table table-condensed table-bordered table-striped text-center ">
                        <thead>
                            <tr>
                                <th> N° Solicitud </th>
                                <th> Fecha </th>
                                <th> Cédula </th>
                                <th> Nombre </th>
                                <th> Apellido </th>
                                <th> Tipo Préstamo </th>
                                <th> Monto </th>
                                <th> Botones </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include('conexion.php');
                            $sql = "
                            SELECT s.sc_cedula,s.sc_nombre,s.sc_apellido , p.pk_soli_pres_id , p.soli_pres_montSolic ,p.soli_pres_fech, tp.tp_pres_dsc
                            FROM socio s
                            JOIN solicitud_prestamo p ON s.pk_sc_id = p.fk_sc_id
                            JOIN tipo_prestamo tp ON p.fk_tp_pres_id = tp.pk_tp_pres_id
                        ";
                            $result = $conn->query($sql);
                            // Mostrar datos en la tabla
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['pk_soli_pres_id'] . "</td>";
                                echo "<td>" . $row['soli_pres_fech'] . "</td>";
                                echo "<td>" . $row['sc_cedula'] . "</td>";
                                echo "<td>" . $row['sc_nombre'] . "</td>";
                                echo "<td>" . $row['sc_apellido'] . "</td>";
                                echo "<td>" . $row['tp_pres_dsc'] . "</td>";
                                echo "<td>" . $row['soli_pres_montSolic'] . "</td>";
                                echo "<td><button  class='btn btn-outline-success' )\"> Aprobado </button> <button  class='btn btn-outline-success' )\"> Rechazado </button></td>";
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
    </div>
    <script src="node_modules/jquery/dist/jquery.js "></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>