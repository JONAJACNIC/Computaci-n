<?php
$sc_cedula = $_GET['sc_cedula'];
$ingre_fech_ini = $_GET['ingre_fech_ini'];
$sc_nombre = $_GET['sc_nombre'];
$sc_apellido = $_GET['sc_apellido'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .comprobante-container {
            width: 50%;
            margin: 20px auto;
            border: 1px solid #ddd;
            padding: 20px;
        }




        h2 {
            color: #333;
        }

        img {
            width: 100%;
            max-width: 500px;
            height: auto;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
        }

        /* Estilos para la impresión */
        @media print {
            body {
                width: 100%;
            }

            .comprobante-container {
                width: 100%;
                margin: 0;
                padding: 20px;
                box-sizing: border-box;
            }
        }

        .info-socio {
            display: flex;
            justify-content: space-between;
        }

        .info-socio>div {
            width: 48%;
            /* Ajusta según tus necesidades */
            text-align: left;
        }

        .info-socio>div:nth-child(2) {
            text-align: right;
        }

        .info-socio div>div {
            border: 2px solid #1376EC;
            text-align: center;
            display: inline-block;
            padding: 10px;
            border-radius: 10px;
            width: auto;
        }
    </style>

</head>

<body>
    <div class="comprobante-container">
    <img src="../imagenes/logo.png" alt="Logo Caja de Ahorro">
        <div class="info-socio">
            <div>
                <p><b>Dirección:</b> Machachi, Quito</p>
                <p><b>Teléfono:</b> 023269381</p>
                <p><b>Email:</b> cajacrisol@gmail.com</p>
                <p><b>Fecha de emisión:</b> <?php echo $ingre_fech_ini?></p>
            </div>
            <div>
                <div
                    style="border: 2px solid #1376EC; text-align: center; display: inline-block; padding: 10px; border-radius: 10px; width: auto;">
                    <h4 style="margin: 0; font-size: 1em;">Comprobante de Ingreso</h4>
                    <h4 style="color: red; margin: 0; font-size: 1em;">000001</h4>
                </div>
            </div>
        </div>
        <div>
            <div class="card">
                <div class="card-header fw-bold" style="font-size: 1.2em; text-align: left;">Información del Socio</div>
                <div class="card" style="height: auto; text-align: left;">
                    <p style="margin-left: 5px;"><strong>Nombre:</strong>
                        <?php echo $sc_nombre . ' ' . $sc_apellido; ?>
                    </p>
                    <p style="margin-left: 5px;"><strong>Cédula:</strong>
                        <?php echo $sc_cedula; ?>
                    </p>
                </div>
            </div>


            <br>
            <?php
            // Obtener los parámetros del GET
            

            $detalles = json_decode(urldecode($_GET['detalles']), true);

            // Mostrar la información en el comprobante
            


            // Mostrar la tabla con descripción y valor
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Descripción</th>";
            echo "<th>Valor</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            // Mostrar cada tipo de ingreso en filas separadas
            $total = 0;

            foreach ($detalles as $detalle) {
                echo "<tr>";
                echo "<td>{$detalle['tp_ingre_dsc']}</td>";
                echo "<td>{$detalle['ingre_val']}</td>";
                echo "</tr>";
                $total += $detalle['ingre_val'];
            }

            echo "<tr class='total'>";
            echo "<td>Total</td>";
            echo "<td>$total</td>";
            echo "</tr>";

            echo "</tbody>";
            echo "</table>";
            ?>
        </div>
        <hr>

        <!-- Sección de Firmas y Botón de Imprimir -->
        <div style="margin-top: 20px; text-align: center;">
            <br>
            <div style="display: flex; justify-content: space-between;">
                <div style="flex-grow: 1; margin-right: 20px;">
                    <div style="border-top: 1px solid #1376EC; margin: 0 auto; width: 50%;"></div>
                    <p style="text-align: center; margin-bottom: 5px;">Tesorero</p>
                </div>
                <div style="flex-grow: 1;">
                    <div style="border-top: 1px solid #1376EC; margin: 0 auto; width: 50%;"></div>
                    <p style="text-align: center; margin-bottom: 5px;">Beneficiario</p>
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

            // Imprime
            window.print();

            // Espera a que se complete la impresión
            setTimeout(function () {
                // Redirige a comproprueba.php después de imprimir
                window.location.href = 'comproprueba.php';
            }, 100); // Espera 5 segundos (ajusta el tiempo según sea necesario)
        }
    </script>


</body>

</html>