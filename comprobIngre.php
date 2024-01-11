<?php
// Obtener los parámetros de la URL
$nombreSocio = $_GET['nombreSocio'];
$apellidoSocio = $_GET['apellidoSocio'];
$cedula1 = $_GET['cedula1'];
$detallesSeleccionados = json_decode($_GET['detallesSeleccionados'], true);

// Calcular la suma de los campos "Detalle Certificado"
$sumaDetalleCertificado = 0;
foreach ($detallesSeleccionados as $detalle) {
    $sumaDetalleCertificado += floatval($detalle['detalleCertificado']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante</title>
    <link rel="stylesheet" href="style.css">
    <style type="text/css">
    body {
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: #f0f0f0;
    }

    .contenedor-comprobante {
        width: 100%;
        max-width: 600px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        margin-bottom: 20px;
        padding: 20px;
        box-sizing: border-box;
    }

    img {
        width: 100%;
        max-width: 500px;
        height: auto;
        margin-bottom: 20px;
    }

    .info-socio {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .info-socio div {
        width: 48%;
    }

    .card {
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    .suma-detalle {
        margin-top: 10px;
        padding: 10px;
        border: 1px solid #ddd;
    }

    hr {
        width: 100%;
        border: 1px solid #1376EC;
        margin: 0;
        margin-bottom: 20px;
    }

    button {
        display: block;
        margin: 20px auto;
    }

    @media print {
        body {
            font-size: 12pt;
            margin: 0;
            padding: 0;
            size: A6;
        }

        .contenedor-comprobante {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
        }
        .card-body {
         font-size: 0.8em; /* Ajusta el tamaño de fuente según tus necesidades */
         line-height: 0,5;
}
    }
</style>

</head>

<body>
    <div class="contenedor-comprobante">
        <img src="imagenes/logo.png" alt="Logo Caja de Ahorro">
        <div class="info-socio">
            <div>
                <p><b>Dirección:</b> Machachi, Quito</p>
                <p><b>Teléfono:</b> 023269381</p>
                <p><b>Email:</b> cajarisol@gmail.com</p>
            </div>
            <div>
                <div
                    style="border: 2px solid #1376EC; text-align: center; display: inline-block; padding: 10px; border-radius: 10px; width: auto;">
                    <h4 style="margin: 0; font-size: 1em;">Comprobante de Ingreso</h4>
                    <h4 style="color: red; margin: 0; font-size: 1em;">000001</h4>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold" style="font-size: 1.2em;">Información del Socio</div>
            <div class="card-body" style="font-size: 0.9em; line-height: 1;">
                <p><strong>Nombre:</strong> <?php echo $nombreSocio . ' ' . $apellidoSocio; ?></p>
                <p><strong>Cédula:</strong> <?php echo $cedula1; ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">
                Detalles del Pago
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>Certificado</th>
                            <th>Fondo Estratégico</th>
                            <th>Mes</th>
                            <th>Detalle Certificado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detallesSeleccionados as $detalle): ?>
                            <tr>
                                <td>
                                    <?php echo $detalle['certificado']; ?>
                                </td>
                                <td>
                                    <?php echo $detalle['fondoEstrategico']; ?>
                                </td>
                                <td>
                                    <?php echo $detalle['mes']; ?>
                                </td>
                                <td>
                                    <?php echo $detalle['detalleCertificado']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Cuadro para mostrar la suma de los campos "Detalle Certificado" -->
                <div class="suma-detalle">
                    <p><strong>Total a pagar:</strong>
                        <?php echo number_format($sumaDetalleCertificado, 2); ?>
                    </p>
                </div>
            </div>
        </div>

        <hr>

        <!-- Sección de Firmas y Botón de Imprimir -->
        <div style="margin-top: 20px; text-align: center;">
            <hr style="width: 60%; border: 1px solid #1376EC; margin: 0 auto; margin-bottom: 10px;">
            <p style="font-weight: bold; margin-bottom: 5px;">Firmas:</p>

            <div style="display: flex; justify-content: space-between;">
                <div style="flex-grow: 1; margin-right: 20px;">
                    <p style="text-align: center; margin-bottom: 5px;">Presidente</p>
                    <div style="border-top: 1px solid #1376EC; margin: 0 auto; width: 50%;"></div>
                </div>
                <div style="flex-grow: 1; margin-right: 20px;">
                    <p style="text-align: center; margin-bottom: 5px;">Tesorero</p>
                    <div style="border-top: 1px solid #1376EC; margin: 0 auto; width: 50%;"></div>
                </div>
                <div style="flex-grow: 1;">
                    <p style="text-align: center; margin-bottom: 5px;">Beneficiario</p>
                    <div style="border-top: 1px solid #1376EC; margin: 0 auto; width: 50%;"></div>
                </div>
            </div>

            <button id="imprimir" onclick="imprimir()">Imprimir</button>
        </div>
    </div>

    <script>
        const today = new Date();
        const day = today.getDate();
        const month = today.getMonth() + 1; // enero es 0, por lo que debemos sumar 1
        const year = today.getFullYear();

        document.getElementById("fecha").innerHTML = `${day}/${month}/${year}`;

        function imprimir() {
            // Oculta el botón
            document.getElementById('imprimir').style.display = 'none';

            // Guarda los estilos originales
            var estilosOriginales = document.styleSheets[0].cssRules;

             // Aplica estilos de impresión
            document.styleSheets[0].insertRule('@media print { body { width: 100%; } }', 0);

            // Imprime
            window.print();

            // Restaura los estilos originales después de imprimir
            window.onafterprint = function () {
                document.getElementById('imprimir').style.display = 'block';
                document.styleSheets[0].cssRules = estilosOriginales;
            };
        }
    </script>
</body>

</html>
