<?php
// Incluir el archivo de conexión
include('conexion.php');
include('regtSc.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">


</head>

<body>
    <!-- Contenedor principal -->
    <div class="container-fluid rounded-3" style="height: 100vh;">
        <h5 class="text-center ">Nuevo socio</h5>
        <form action="" method="post" id="miFormulario">
            <!-- Sección Información Personal -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header fw-bold">
                        Información Personal
                    </div>
                    <div class="card-body">
                        <!-- Fila 1 -->
                        <div class="row pb-1">
                            <!-- Campo cédula -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="cedula" class="form-label me-3">Cédula<span class="text-danger">*</span></label>
                                <input type="text" class="form-control w-75" name="cedula" id="cedula" maxlength="10" onkeypress="return soloNumeros(event)" autocomplete="off" required>
                                <p id="mensajeResultado"></p>
                            </div>
                            <!-- fin campo cédula -->
                            <!-- Campo fecha nacimiento -->
                            <div class="col-md-4 d-flex  align-items-center">
                                <label for="fechaNac" class="form-label ms-1">Fecha Nacimiento<span class="text-danger">*</span></label>
                                <input type="date" class="form-control w-50  ms-4" name="fechaNac" id="fechaNac" onchange="validarEdad()" required>
                                <p id="mensajeError" class="text-danger"></p>
                            </div>
                            <!-- fin campo fecha nacimiento -->
                            <!-- Campo sexo -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="sexo" class="form-label ps-4 me-4">Sexo <span class="text-danger">*</span></label>
                                <select name="sexo" id="sexo" class="form-select w-50" required>
                                    <option value="">----</option>
                                    <?php
                                    // Consulta para obtener los datos de la tabla sexo_socio
                                    $sql = "SELECT pk_sex_id, sex_dsc FROM sexo_socio";
                                    $result = $conn->query($sql);
                                    // Generar las opciones dinámicamente
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='{$row['pk_sex_id']}'>{$row['sex_dsc']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- fin campo sexo -->
                        </div>
                        <!-- fin fila 1 -->
                        <!-- Fila 2 -->
                        <div class="row">
                            <!-- Campo nombres -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="nombre" class="form-label">Nombres<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre" id="nombre" onkeypress="return soloLetras(event)" oninput="convertirMayusculas('nombre')">
                            </div>
                            <!-- fin campo nombres -->
                            <!-- Campo apellidos -->
                            <div class="col-md-4 d-flex align-items-center">
                                <label for="apellido" class="form-label">Apellidos<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="apellido" id="apellido" onkeypress="return soloLetras(event)" oninput="convertirMayusculas('apellido')">
                            </div>
                            <!-- fin campo apellidos -->
                            <!-- Campo dirección -->
                            <div class="col-md-5 d-flex align-items-center">
                                <label for="direccion" class="form-label me-3">Dirección<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="direccion" id="direccion" oninput="convertirMayusculas('direccion')" required>
                            </div>
                            <!-- fin campo dirección -->
                        </div>
                        <!-- fin fila 2 -->

                    </div>
                </div>
            </div>
            <!-- Fin Sección Información Personal -->
            <!-- Sección Información de Contacto -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header fw-bold">
                        Información de Contacto
                    </div>
                    <div class="card-body">
                        <!-- Fila 1 -->
                        <div class="row">
                            <!-- Campo correo -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="correo" class="form-label">Correo</label>
                                <input type="text" class="form-control" name="correo" id="correo">
                                <p id="mensajeErrorCorreo" class="text-danger"></p>
                            </div>
                            <!-- fin campo correo -->
                            <!-- Campo teléfono -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="tel" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="tel" id="tel" maxlength="10" onkeypress="return soloNumeros(event)">
                            </div>
                            <!-- fin campo teléfono -->
                            <!-- Campo celular -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="cel" class="form-label">Celular<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="cel" id="cel" maxlength="10" onkeypress="return soloNumeros(event)" required>
                            </div>
                            <!-- fin campo celular -->
                        </div>
                        <!-- fin fila 1 -->
                    </div>
                </div>
            </div>
            <!-- Fin Sección Información de Contacto -->
            <!-- Sección Información de Socio -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header fw-bold">
                        Información de Socio
                    </div>
                    <div class="card-body">
                        <!-- Fila 1 -->
                        <div class="row">
                            <!-- Campo razon social -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="razon" class="form-label me-3">Razon Social<span class="text-danger">*</span></label>
                                <select name="razon" id="razon" class="form-select w-50" required>
                                    <option value="">----</option>
                                    <?php
                                    // Consulta para obtener los datos de la tabla razon_social
                                    $sql = "SELECT pk_rzn_id, rzn_dsc FROM razon_social";
                                    $result = $conn->query($sql);
                                    // Generar las opciones dinámicamente
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='{$row['pk_rzn_id']}'>{$row['rzn_dsc']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- fin campo razon social -->
                            <!-- Campo condición socio -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="condi" class="form-label">Condición<span class="text-danger">*</span></label>
                                <select name="condi" id="condi" class="form-select" required>
                                    <option value="">----</option>
                                    <?php
                                    // Consulta para obtener los datos de la tabla condicion_socio
                                    $sql = "SELECT pk_con_id, con_dsc FROM condicion_socio";
                                    $result = $conn->query($sql);
                                    // Generar las opciones dinámicamente
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='{$row['pk_con_id']}'>{$row['con_dsc']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- fin campo condición socio -->
                            <!-- Campo tipo socio -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="tpSoc" class="form-label">Tipo Socio<span class="text-danger">*</span></label>
                                <select name="tpSoc" id="tpSoc" class="form-select w-50" required>
                                    <option value="">----</option>
                                    <?php
                                    // Consulta para obtener los datos de la tabla tipo_socio
                                    $sql = "SELECT pk_tp_sc, tp_dsc FROM tipo_socio";
                                    $result = $conn->query($sql);
                                    // Generar las opciones dinámicamente
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='{$row['pk_tp_sc']}'>{$row['tp_dsc']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- fin campo tipo socio -->
                        </div>
                        <!-- fin fila 1 -->
                    </div>
                </div>
                <div class="text-center m-1">
                    <input type="submit" value="Registrar" id="btnRegistrar" name="btnRegistrar" class="btn btn-outline-success">
                    <input type="submit" value="Limpiar" class="btn btn-outline-success">
                    <input type="submit" value="Imprimir" class="btn btn-outline-success">
                    <input type="hidden" value="Eliminar" class="btn btn-outline-success">
                </div>
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
    <!-- fin contenedor principal  -->
    <script src="node_modules/jquery/dist/jquery.js "></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="valdSc.js"></script>
    <script>
        $(document).ready(function() {
            $("#toastExito").toast("show");
            $("#toastError").toast("show");
            deshabilitarCamposExcepto("cedula");
        });
    </script>
</body>

</html>