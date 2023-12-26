<?php
session_start();
include('regtActSc.php');
include('../buscar/guardar_dato.php');
include("../conexion.php");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Actualización Socio</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <!-- Contenedor principal -->
    <div class="container-fluid rounded-3" style="height: 100vh;">
        <h5 class="text-center ">Actualizar Datos</h5>
        <p id="mensajeBuscarAlert"></p>
        <form action="" method="post" id="miFormulario" onsubmit="return validarFormulario()">
            <!-- Sección de Busqueda -->
            <div class="container-fluid">
                <div class="card">
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
                            <input type="hidden" class="form-control" name="idSoc" id="idSoc" value="<?php echo isset($datosFormulario['idSoc']) ? htmlspecialchars($datosFormulario['idSoc']) : ''; ?>" required readonly>
                            <!-- fin campo id socio -->
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
                                <input type="text" class="form-control w-50" name="cedula" id="cedula" value="<?php echo isset($datosFormulario['cedula']) ? htmlspecialchars($datosFormulario['cedula']) : ''; ?>" required readonly>
                            </div>
                            <!-- fin campo cédula -->
                        </div>
                        <!-- fin barra de busqueda -->
                    </div>
                </div>
            </div>
            <!-- Fin Sección de Busqueda -->
            <!-- Sección Información Personal -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header fw-bold">
                        Información Personal
                    </div>
                    <div class="card-body">
                        <!-- Fila 1 -->
                        <div class="row ">
                            <!-- Campo fecha Nacimiento -->
                            <div class="col-md-5 d-flex  align-items-center">
                                <label for="fechaNac" class="form-label">Fecha Nacimiento<span class="text-danger">*</span></label>
                                <input type="date" class="form-control w-50  " name="fechaNac" id="fechaNac" value="<?php echo isset($datosFormulario['fecha_nac']) ? htmlspecialchars($datosFormulario['fecha_nac']) : ''; ?>" onchange="validarEdad()" required readonly>
                                <img src="../iconos/pencil-square-icon.svg" alt="Icono de Editar" class="ms-1" style="width: 30px;" onclick="habilitarEdicion('fechaNac', '¡Edición habilitada!')">
                            </div>

                            <!-- fin campo fecha nacimiento -->
                            <!-- Campo dirección-->
                            <div class="col-md-4 d-flex align-items-center">
                                <label for="direccion" class="form-label">Dirección<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo isset($datosFormulario['direccion']) ? htmlspecialchars($datosFormulario['direccion']) : ''; ?>" oninput="convertirMayusculas('direccion')" autocomplete="off" required readonly>
                                <img src="../iconos/pencil-square-icon.svg" alt="Icono de Editar" class="ms-1" style="width: 30px;" onclick="habilitarEdicion('direccion','¡Edición habilitada!')">
                            </div>
                            <!-- fin campo dirección-->
                        </div>
                        <!-- fin fila 1 -->
                    </div>
                </div>
            </div>
            <!-- Fin sección Información Personal -->
            <!-- Sección Información Contacto -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header fw-bold">
                        Información de Contacto
                    </div>
                    <div class="card-body">
                        <!-- Fila 1 -->
                        <div class="row ">
                            <!-- Campo celular -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="celular" class="form-label ">Celular<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="celular" id="celular" value="<?php echo isset($datosFormulario['celular']) ? htmlspecialchars($datosFormulario['celular']) : ''; ?>" maxlength="10" onkeypress="return soloNumeros(event)" required autocomplete="off" readonly>
                                <img src="../iconos/pencil-square-icon.svg" alt="Icono de Editar" class="ms-1" style="width: 30px;" onclick="habilitarEdicion('celular', '¡Edición habilitada!')">
                            </div>
                            <!-- fin campo celular-->
                            <!-- Campo Teléfono -->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="input" class="form-control" name="telefono" id="telefono" value="<?php echo isset($datosFormulario['telefono']) ? htmlspecialchars($datosFormulario['telefono']) : ''; ?>" readonly autocomplete="off">
                                <img src="../iconos/pencil-square-icon.svg" alt="Icono de Editar" class="ms-1" style="width: 30px;" onclick="habilitarEdicion('telefono','¡Edición habilitada!')">
                            </div>
                            <!-- fin campo Teléfono -->
                            <!-- Campo correo-->
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="correo" class="form-label">Correo</label>
                                <input type="text" class="form-control" name="correo" id="correo" value="<?php echo isset($datosFormulario['correo']) ? htmlspecialchars($datosFormulario['correo']) : ''; ?>" autocomplete="off" required readonly>
                                <img src="../iconos/pencil-square-icon.svg" alt="Icono de Editar" class="ms-1" style="width: 30px;" onclick="habilitarEdicion('correo','¡Edición habilitada!')">
                                <p id="mensajeErrorCorreo" class="text-danger"></p>
                            </div>
                            <!-- fin campo correo-->
                        </div>
                        <!-- fin fila 1 -->
                    </div>
                </div>
            </div>
            <!-- Fin sección Información de Contacto-->
            <!-- Sección de botones -->
            <div class="text-center m-1">
                <input type="submit" value="Registrar" id="btnRegistrar" name="btnRegistrar" class="btn btn-outline-success">
                <input type="submit" value="Limpiar" class="btn btn-outline-success">
                <input type="hidden" value="Imprimir" class="btn btn-outline-success">
                <input type="hidden" value="Eliminar" class="btn btn-outline-success">
            </div>
            <!-- Fin Sección de botones -->
        </form>
        <!-- Contenedor de Alertas -->
        <div class="mt-1 d-flex justify-content-center">
            <?php
            // Verifica si hay mensajes de éxito o error en la variable de sesión
            if (isset($_SESSION['mensajeExito'])) {
                echo '<div id="toastExito" class="w-25 toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">' .
                    '<div class="d-flex justify-content-center align-items-center">' . // Añadida clase "justify-content-center align-items-center"
                    '<div class="toast-body text-center">' . // Añadida clase "text-center"
                    '<img src="../iconos/green-checkmark-icon.svg" alt="Icono de Socio" class="me-2" style="width: 24px; height: 24px;" />' .
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
    <!-- Fin buscar socio -->
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../buscar/scrip.js"></script>
    <script src="valdActSc.js"></script>
    <script>
        $(document).ready(function() {
            $("#toastExito").toast("show");
            $("#toastError").toast("show");
            deshabilitarCamposExcepto("searchInput");
            habilitarCamposExcepto("idSoc");
        });
    </script>

</body>

</html>