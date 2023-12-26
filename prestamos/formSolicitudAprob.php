<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container-fluid">
    <h5 class="text-center ">  Solicitudes Aprobadas </h5>
        <div class="card">
            <div class="card-header fw-bold">
               Información 
               <br>
                    <?php
                    $conexion = mysqli_connect("localhost", "root", "", "crisol");

                    $query = "SELECT SUM(cta_val) AS suma_valores FROM cuenta_caja ORDER BY pk_cta_id LIMIT 4";
                    $result = mysqli_query($conexion, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $suma_valores = $row['suma_valores'];
                        echo "Fondos de la Caja Disponibles: " . $suma_valores;
                    } else {
                        echo "Error al obtener la suma de valores.";
                    }

                    mysqli_close($conexion);
                    ?>
            </div>
            <div class="card-body">
                <!-- Barra de busqueda -->
                <div class="row mb-2">
                <form class="d-flex">
                     <input class="form-control me-2 light-table-filter" data-table="table_id" type="text" 
                    placeholder="Buscar">
                </form>
                <br>
                    <table class="table table-condensed table-bordered table-striped text-center table_id">
                        <thead>
                            <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cédula</th>
                            <th>Monto Solicitado</th>
                            <th>Fecha Solicitud</th>
                            <th>Tipo Préstamo</th>
                            <th>Estado Préstamo</th>
                            <th>Por definir</th>
                        
                        </tr>
                        </thead>
                        <tbody>
                            
				<?php
                     $conexion=mysqli_connect("localhost","root","","crisol");

                        $SQL = "SELECT socio.pk_sc_id, socio.sc_nombre, socio.sc_apellido, socio.sc_cedula, solicitud_prestamo.soli_pres_montSolic, solicitud_prestamo.soli_pres_fech,
                        tipo_prestamo.tp_pres_dsc, estado_solicitud.est_soli_dsc
                        FROM socio
                        INNER JOIN solicitud_prestamo ON socio.pk_sc_id = solicitud_prestamo.fk_sc_id
                        INNER JOIN tipo_prestamo ON solicitud_prestamo.fk_tp_pres_id = tipo_prestamo.pk_tp_pres_id
                        LEFT JOIN estado_solicitud ON solicitud_prestamo.fk_est_soli_id = estado_solicitud.pk_est_soli_id
                        WHERE estado_solicitud.pk_est_soli_id = 2  -- Filter for approved requests
                        ORDER BY solicitud_prestamo.pk_soli_pres_id DESC;";
                        $dato = mysqli_query($conexion, $SQL);

                        if($dato -> num_rows >0){
                            while($fila=mysqli_fetch_array($dato)){
                            
                        ?>
                        <tr>
                        <td><?php echo $fila['sc_nombre']; ?></td>
                        <td><?php echo $fila['sc_apellido']; ?></td>
                        <td><?php echo $fila['sc_cedula']; ?></td>
                        <td><?php echo $fila['soli_pres_montSolic']; ?></td>
                        <td><?php echo $fila['soli_pres_fech']; ?></td>
                        <td><?php echo $fila['tp_pres_dsc']; ?></td>
                        <td><?php echo $fila['est_soli_dsc']; ?></td>


                    <td>
                    <button type="button" class="btn btn-success btn-aprobacion" data-socioid="<?php echo $fila['pk_sc_id']; ?>"></button>
                    </td>
                    </tr>


            <?php
            }
            }else{

                ?>
                <tr class="text-center">
                <td colspan="16">No existen registros</td>
                </tr>

                
                <?php
    
                }

?>
                       </tbody>
                    </table>
 
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <script src="../node_modules/jquery/dist/jquery.js "></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script src="../prestamos/buscadorSoliPen.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

</body>
</html>