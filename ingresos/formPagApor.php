<?php
include('../buscar/guardar_dato.php');
include("../conexion.php");
include('regtApor.php');
date_default_timezone_set('America/Mexico_City');
$fechaActual = date("Y-m-d");
$cta_sc_id = 0;
$total_multas = "";
$sumaCertificados = 0;
$sumaFondos = 0;
$row_aportaciones = 0;
// Iniciar sesión si no está iniciada
session_start();
if (!empty($_SESSION['datos_formulario'])) {
  $datosFormulario = $_SESSION['datos_formulario'];
  $id_socio = $datosFormulario['idSoc'];

  // Obtener el pk_cta_sc_id asociado al socio
  $sql_cta_sc_id = "SELECT pk_cta_sc_id FROM cuenta_socio WHERE fk_sc_id = $id_socio";
  $result_cta_sc_id = $conn->query($sql_cta_sc_id);

  if ($result_cta_sc_id->num_rows > 0) {
    $row_cta_sc_id = $result_cta_sc_id->fetch_assoc();
    $cta_sc_id = $row_cta_sc_id['pk_cta_sc_id'];

    // Consulta para obtener las aportaciones asociadas a la cuenta del socio
    $sql_aportaciones = "SELECT a.pk_aprt_id, a.aprt_fech, f.cert_val, f.cert_det, fe.fon_val, fe.fon_det,
     COALESCE(SUM(CASE WHEN m.fk_tp_mult_id = 1 THEN m.mult_total ELSE 0 END), 0) AS total_multas
FROM
    aportaciones a
    INNER JOIN certificado f ON a.fk_cert_id = f.pk_cert_id
    INNER JOIN fondo_estrategico fe ON a.fk_fon_id = fe.pk_fon_id
    LEFT JOIN multa m ON a.fk_cta_sc_id = m.fk_cta_sc_id
WHERE
    a.fk_cta_sc_id = $cta_sc_id
GROUP BY
    a.pk_aprt_id, a.aprt_fech, f.cert_val, f.cert_det, fe.fon_val, fe.fon_det;";
    $result_aportaciones = $conn->query($sql_aportaciones);
    if ($result_aportaciones->num_rows > 0) {
      while ($row_aportaciones = $result_aportaciones->fetch_assoc()) {
        $total_multas = $row_aportaciones['total_multas'];
        // Por ejemplo, imprimirlos en la página
        //echo "ID: $pk_aprt_id, Fecha: $aprt_fech, Certificado: $cert_val, Detalle: $cert_det, Fondo: $fon_val, Detalle: $fon_det, Multas: $total_multas<br>";
      }
    }
  }
}

function procesarValor($valor)
{
  $parts = explode(' - ', $valor);
  if (count($parts) >= 2) {
    return [$parts[0], $parts[1]];
  } else {
    return [$valor, ''];
  }
}

function generarTabla($result_aportaciones)
{
  echo '<table class="table table-striped table-bordered table-hover">';
  echo '<thead>';
  echo '<tr>';
  echo '<th>Certificados</th>';
  echo '<th>Fondo Estratégico</th>';
  echo '<th>Mes</th>';
  echo '<th>Aporte total</th>';
  echo '<th>Pago</th>';
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

  while ($row_aportacion = $result_aportaciones->fetch_assoc()) {
    $sumaCertificados = 0;
    $sumaFondos = 0;

    echo '<tr data-pk_aprt_id="' . $row_aportacion['pk_aprt_id'] . '">'; // Agregar data-pk_aprt_id

    // Verificar y procesar 'cert_val'
    if (isset($row_aportacion['cert_val'])) {
      [$certificado_valor] = procesarValor($row_aportacion['cert_val']);
      echo '<td>' . $certificado_valor . '</td>';
      $sumaCertificados += floatval($certificado_valor);
    } else {
      echo '<td colspan="4">Certificado no disponible</td>';
      continue;  // Saltar al siguiente bucle si no hay certificado
    }

    // Verificar y procesar 'fon_val'
    if (isset($row_aportacion['fon_val'])) {
      [$fondo_valor] = procesarValor($row_aportacion['fon_val']);
      echo '<td>' . $fondo_valor . '</td>';
      $sumaFondos += floatval($fondo_valor);
    } else {
      echo '<td colspan="4">Fondo no disponible</td>';
      continue;  // Saltar al siguiente bucle si no hay fondo
    }
    // Definir un array asociativo de nombres de meses en español
    $meses_espanol = array(
      'January' => 'enero',
      'February' => 'febrero',
      'March' => 'marzo',
      'April' => 'abril',
      'May' => 'mayo',
      'June' => 'junio',
      'July' => 'julio',
      'August' => 'agosto',
      'September' => 'septiembre',
      'October' => 'octubre',
      'November' => 'noviembre',
      'December' => 'diciembre'
    );

    // Obtener la fecha de $row_aportacion['aprt_fech']
    $fecha = date('F', strtotime($row_aportacion['aprt_fech']));

    // Mostrar solo el nombre del mes en español
    echo '<td>' . $meses_espanol[$fecha] . '</td>';


    // Mostrar el detalle del certificado (suma de certificados y fondos)
    $detalleCertificado = $sumaCertificados + $sumaFondos;
    echo '<td>' . $detalleCertificado . '</td>';

    // Agregar la columna "Pago" con los checkboxes
    echo '<td><input type="checkbox" name="Pago[]" value="' . $row_aportacion['pk_aprt_id'] . '"></td>';

    echo '</tr>';
  }

  echo '</tbody>';
  echo '</table>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <div class="container-fuid">
    <h5 class="text-center">Aportaciones</h5>
    <form action="" method="post">
      <div class="container-fluid">
        <!-- Buscar socio -->
        <div class="card">
          <div class="card-header fw-bold">
            Datos Socio
          </div>
          <div class="card-body">
            <!-- Barra de busqueda -->
            <div class="row">
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
              <div class="col-md-2 d-flex align-items-center">
                <label for="cedula" class="form-label ">Cédula</label>
                <input type="text" class="form-control" name="cedula" id="cedula" value="<?php echo isset($datosFormulario['cedula']) ? htmlspecialchars($datosFormulario['cedula']) : ''; ?>" required readonly>
              </div>
              <!-- fin campo cédula -->
            </div>
            <!--  fin barra de busqueda -->
          </div>
        </div>
        <!-- Fin buscar socio -->
        <!-- Aportaciones -->
        <div class="card">
          <div class="card-header fw-bold">
            Detalle de Aportaciones
          </div>
          <div class="card-body">
            <!-- Tabla de aportaciones -->
            <?php
            if ($cta_sc_id > 0) {
              $sql_aportaciones = "SELECT a.pk_aprt_id, a.aprt_fech, f.cert_val, f.cert_det, fe.fon_val, fe.fon_det
                            FROM aportaciones a
                            INNER JOIN certificado f ON a.fk_cert_id = f.pk_cert_id
                            INNER JOIN fondo_estrategico fe ON a.fk_fon_id = fe.pk_fon_id
                            WHERE a.fk_cta_sc_id = $cta_sc_id";
              $result_aportaciones = $conn->query($sql_aportaciones);
              echo '<div>';
              if ($result_aportaciones->num_rows > 0) {
                generarTabla($result_aportaciones);

                // Botón "Pago Adicional"
                echo '<div class="col-md-9 d-flex">';
                echo '<button type="button" class="btn btn-primary" onclick="agregarCamposPagoAdicional()">Pago Adicional</button>';
                // Adicional certificados (inicialmente oculto)
                echo '<div class="col-md-3 d-none" id="containerCertificados">';
                echo '<label for="adiCert" class="form-label mt-2 ms-2">Certificados</label>';
                echo '<input type="text" class="form-control w-50" name="adiCert" id="adiCert">';
                echo '</div>';
                // Adicional fondo estratégico (inicialmente oculto)
                echo '<div class="col-md-3 d-none" id="containerFondoEstrategico">';
                echo '<label for="adiFond" class="form-label mt-2 ms-2" style="white-space: nowrap;">Fondo Estratégico</label>';
                echo '<input type="text" class="form-control w-50" name="adiFond" id="adiFond">';
                echo '</div>';
                echo '</div>'; // Cierre de la fila para botón y campos adicionales
                echo '</div>';
              } else {
                echo 'No se encontraron aportaciones asociadas a la cuenta del socio.';
              }
              echo '</div>';
            }
            ?>
            <!-- Fin tabla aportaciones -->
          </div>
          <!-- fin aportaciones -->
        </div>
        <!-- Valores a cancelar -->
        <div class="card">
          <div class="card-header fw-bold">
            Información de pago
          </div>
          <div class="card-body ">
            <div class="row mb-2">
              <!-- fecha pago -->
              <div class="col-md-3 d-flex ">
                <label for="fechap" class="form-label me-5">Fecha</label>
                <input type="date" class="form-control w-50" name="fechap" id="fechap" value="<?php echo date("Y-m-d"); ?>" readonly>
              </div>
              <!-- fin fecha pago -->
              <!-- multas -->
              <div class="col-md-3 d-flex ">
                <label for="multas" class="form-label me-5">Multas</label>
                <input type="text" class="form-control w-50" name="multas" id="multas" value="<?php echo $total_multas; ?>" readonly>
              </div>
              <!-- fin multas -->
              <input type="hidden" class="form-control w-50" name="totCert" id="totCert" value="<?php echo $sumaCertificados ?>" readonly>
              <input type="hidden" class="form-control w-50" name="totFond" id="totFond" value="<?php echo $sumaFondos ?>" readonly>
              <!-- total a pagar -->
              <div class="col-md-3 d-flex ">
                <label for="total" class="form-label me-5">Total</label>
                <input type="text" class="form-control w-50" name="total" id="total" readonly>
              </div>
              <!-- fin total a pagar -->
              <input type="hidden" name="clavesPrimariasSeleccionadas" id="clavesPrimariasSeleccionadas" value="" />
            </div>
          </div>
        </div>
        <!-- fin valores a cancelar -->
        <div class="py-2 text-center">
          <input type="submit" value="Registrar" id="btnRegistrar" name="btnRegistrar" class="btn btn-outline-success">
          <button type="button" class="btn btn-outline-success">Imprimir</button>
        </div>
      </div>
    </form>
  </div>
  <script src="../buscar/scrip.js"></script>
  <script src="valdPagApor.js"></script>
</body>

</html>