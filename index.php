<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComprobantesIndex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <style>
        body {
            background-color: lightblue;
        }

        .container {
            background-color: skyblue;
            border-radius: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Préstamos</h2>
        <div class="row">
            <div class="col-md-6">
                <form method="post">
                    <div class="mb-3 d-flex justify-content-between align-items-flex-end">
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cédula</label><br>
                            <input type="text" class="form-control" id="cedula" name="cedula" required pattern="[0-9]{10}" value="<?php echo isset($_POST['cedula']) ? htmlspecialchars($_POST['cedula']) : ''; ?>" placeholder="Ingrese 10 dígitos" style="margin-right: 250px;">
                        </div>
                        <button class="btn btn-primary" style="margin-top: 30px; margin-bottom: 30px;" name="buscar">Buscar</button>
                    </div>

                    <div class="mb-3" style="margin-top: -27px; margin-bottom: 30px;">
                        <label for="monto" class="form-label">Monto solicitado</label>
                        <input type="text" class="form-control" id="monto" name="monto" max="6000" placeholder="Límite $6.000">
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de préstamo</label>
                        <select class="form-control" name="tipo">
                            <option value="comercio">Comercio</option>
                            <option value="agropecuario">Agropecuario</option>
                            <option value="bienes">Bienes</option>
                            <option value="servicios">Servicios</option>
                            <option value="consumo">Consumo</option>
                            <option value="vivienda">Vivienda</option>
                            <option value="salud">Salud</option>
                            <option value="educacion">Educación</option>
                            <option value="otros">Otros</option>
                        </select>
                    </div>
                    <input type="submit" value="Enviar">
                    <input type="submit" id="imprimirBoton" class="btn btn-primary" onclick="abrirVentanaEmergente()" value="Imprimir Factura">
                </form>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombre_socio" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre_socio" readonly="true">
                </div>

                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="text" class="form-control" id="fecha" required readonly>
                </div>
                <div class="mb-3">
                    <label for="cuota" class="form-label">Cuotas</label>
                    <input type="text" class="form-control" id="cuota" required>
                </div>
            </div>
        </div>

        <center>
            <button type="button" class="btn btn-outline-secondary" onclick="limpiarCampos()">Limpiar</button>
        </center>
    </div>

    <?php
    include("consulta.php");
    ?>

<script>
    // Obtener la fecha actual
    const today = new Date();
    const dateOnly = today.getDate();
    const year = today.getFullYear();
    const month = today.getMonth() + 1;
    const day = today.getDate();
    
    // Establezca el valor del campo de fecha en la fecha y hora
    document.getElementById("fecha").value = day + "/" + month + "/" + year;

    function abrirVentanaEmergente() {
    // Obtener el valor del campo nombre_socio
    var nombreSocio = document.getElementById('nombre_socio').value;
    var cedula1 = document.getElementById('cedula').value;

    // Eliminar los espacios en blanco al principio y al final del valor de cedula1
    cedula1 = cedula1.trim();

    // Construir la URL con múltiples parámetros
    var url = 'comprobante.php?nombreSocio=' + encodeURIComponent(nombreSocio) + '&cedula1=' + encodeURIComponent(cedula1);

    // Abrir la ventana emergente con la URL que incluye los parámetros
    var nuevaVentana = window.open(url, '_blank', 'width=500,height=600');
    nuevaVentana.focus();
}
</script>

</body>
</html>
