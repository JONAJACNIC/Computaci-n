<?php
include("conexion.php");
include('regtPagNueSc.php');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Nuevo Socio</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #sugerencias {
            position: absolute;
            top: 90%;
            left: 24;
            z-index: 9999;
            /* Ajusta el valor según sea necesario, asegúrate de que sea mayor que cualquier otro z-index en tu página */
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            width: 40%;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <form action="" method="post" id="miFormulario">
            <div class="card">
                <div class="card-header fw-bold">
                    Datos Nuevo Socio
                </div>
                <div class="card-body">
                    <!-- Barra de búsqueda -->
                    <div class="row mb-2">
                        <!-- Campo buscar -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="buscar" class="form-label">Buscar</label>
                            <input type="text" class="form-control" name="buscar" id="buscar" autocomplete="off">
                            <ul id="sugerencias" class="sugerencias-container" style="display: none;"></ul>
                            <p id="mensajeBuscarError"></p>
                        </div>
                        <!-- fin campo buscar -->
                        <!-- Campo id socio -->
                        <input type="hidden" class="form-control" name="idSoc" id="idSoc" required readonly>
                        <!-- fin campo id socio -->
                        <!-- Campo cuentaVal -->
                        <input type="hidden" class="form-control" name="cuentaVal" id="cuentaVal" required readonly>
                        <!-- fin campo cuentaVal -->
                        <!-- Campo nombres -->
                        <div class="col-md-4 d-flex align-items-center">
                            <label for="nombre" class="form-label me-3">Nombres</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" readonly>
                        </div>
                        <!-- fin campo nombres -->
                        <!-- Campo apellidos -->
                        <div class="col-md-4 d-flex align-items-center ">
                            <label for="apellido" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" name="apellido" id="apellido" readonly>
                        </div>
                        <!-- fin campo apellidos -->
                        <!-- Campo cédula -->
                        <div class="col-md-3 d-flex align-items-center mt-2">
                            <label for="cedula" class="form-label ">Cédula</label>
                            <input type="text" class="form-control" name="cedula" id="cedula" required readonly>
                        </div>
                        <!-- fin campo cédula -->
                        <!-- Campo tipo socio -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="tpSoc" class="form-label w-50">Tipo Socio</label>
                            <input name="tpSoc" id="tpSoc" class="form-control" readonly required>
                        </div>
                        <!-- fin campo tipo socio -->
                    </div>
                    <!-- fin barra de búsqueda -->
                </div>
            </div>
            <!-- Valores detallados -->
            <div class="card">
                <div class="card-header fw-bold">
                    Valores Detallados
                </div>
                <div class="card-body">
                    <!-- Fila 1 -->
                    <div class="row mb-2">

                        <!-- Campo valor Certificados Aportación -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="certApor" class="form-label w-100">Certificados Aportación</label>
                            <input type="text" class="form-control" name="certApor" id="certApor" readonly>
                        </div>
                        <!-- fin campo valor Certificados Aportación -->
                        <!-- Campo valor Fondo Estratégico -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="fondEst" class="form-label w-100">Fondo Estratégico</label>
                            <input type="text" class="form-control" name="fondEst" id="fondEst" autocomplete="off" readonly>
                        </div>
                        <!-- fin campo valor Fondo Estratégico -->
                        <!-- Campo valor Gastos Administrativos -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="gastAdm" class="form-label w-100">Gastos Administrativos</label>
                            <input type="text" class="form-control" name="gastAdm" id="gastAdm" readonly>
                        </div>
                        <!-- fin campo valor Gastos Administrativos -->
                    </div>
                    <!-- fin fila 1 -->
                </div>
            </div>
            <!-- fin valores detallados -->
            <!-- Información montos pendientes -->
            <div class="card">
                <div class="card-header fw-bold">
                   Información de Pago
                </div>
                <div class="card-body">
                    <!-- Fila 1 -->
                    <div class="row mb-2">
                        <!-- Campo valor total pendiente -->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="valPen" class="form-label w-100">Total a Pagar</label>
                            <input type="text" class="form-control" name="valPen" id="valPen" disabled>
                        </div>
                        <!-- fin campo valor total pendiente -->
                        <!-- Campo fecha actual-->
                        <div class="col-md-3 d-flex align-items-center">
                            <label for="fechaAct" class="form-label w-100 ">Fecha Pago</label>
                            <input type="text" class="form-control" name="fechaAct" id="fechaAct" readonly>
                        </div>
                        <!-- fin campo fecha actual- -->
                    </div>
                    <!-- fin fila 1 -->

                </div>
            </div>
            <!-- Fin información montos pendientes  -->
            <div class="text-center m-1">
                <input type="submit" value="Registrar" id="btnRegistrar" name="btnRegistrar" class="btn btn-outline-success">
                <input type="submit" value="Limpiar" class="btn btn-outline-success">
                <input type="submit" value="Imprimir" class="btn btn-outline-success">
                <input type="hidden" value="Eliminar" class="btn btn-outline-success">
            </div>
        </form>
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
        </div>
    </div>
    <!-- Fin Sección -->
    <!-- Fin buscar socio -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./node_modules/autonumeric/dist/autoNumeric.min.js"></script>
    <script src="valdPagNueSc.js"></script>
    <script>
        $(document).ready(function() {
            $("#toastExito").toast("show");
            $("#toastError").toast("show");
            deshabilitarCamposExcepto("buscar", "idSoc");

        });
    </script>
</body>

</html>