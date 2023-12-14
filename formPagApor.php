<?php
include('guardar_dato.php');
include("conexion.php");
$fechaActual = date("Y-m-d");
$cta_sc_id = 0;
$total_multas = "";
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
    COALESCE(SUM(m.mult_total), 0) AS total_multas
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
      // Obtener la primera fila
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
  echo '<th>Detalle Certificado</th>';
  echo '<th>Pago</th>';
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

  while ($row_aportacion = $result_aportaciones->fetch_assoc()) {
    // Inicializar las variables de suma para cada fila
    $sumaCertificados = 0;
    $sumaFondos = 0;

    echo '<tr>';

    // Verificar y procesar 'cert_val'
    if (isset($row_aportacion['cert_val'])) {
      [$certificado_valor, $certificado_detalle] = procesarValor($row_aportacion['cert_val']);
      echo '<td>' . $certificado_valor . '</td>';
      $sumaCertificados += floatval($certificado_valor);
    } else {
      echo '<td colspan="4">Certificado no disponible</td>';
      continue;  // Saltar al siguiente bucle si no hay certificado
    }

    // Verificar y procesar 'fon_val'
    if (isset($row_aportacion['fon_val'])) {
      [$fondo_valor, $fondo_detalle] = procesarValor($row_aportacion['fon_val']);
      echo '<td>' . $fondo_valor . '</td>';
      $sumaFondos += floatval($fondo_valor);
    } else {
      echo '<td colspan="4">Fondo no disponible</td>';
      continue;  // Saltar al siguiente bucle si no hay fondo
    }

    // Mostrar solo el nombre del mes
    echo '<td>' . date('F', strtotime($row_aportacion['aprt_fech'])) . '</td>';

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
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="container-fuid">
    <h5 class="text-center">Aportaciones</h5>

    <form action="registroObli.php" method="post">
      <!-- Buscar socio -->
      <div class="container-fluid">
        <div class="card">
          <div class="card-header fw-bold">
            Datos Socio
          </div>
          <div class="card-body">
            <!-- Barra de busqueda -->
            <div class="row mb-2">
              <?php include('buscar.php'); ?>
              <?php
              // Mostrar el dato seleccionado
              if (!empty($_SESSION['datos_formulario'])) {
                $datosFormulario = $_SESSION['datos_formulario'];
                unset($_SESSION['datos_formulario']);
              }
              ?>
              <!-- Fila 1 -->

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
            Tabla de Aportaciones
          </div>
          <div class="card-body ">
            <!-- tabla de aportaciones -->
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
              } else {
                echo 'No se encontraron aportaciones asociadas a la cuenta del socio.';
              }
              echo '</div>';
            }
            ?>
            <!-- fin tabla aportaciones -->
          </div>
        </div>
        <!-- fin aportaciones -->
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
              <!-- total a pagar -->
              <div class="col-md-3 d-flex ">
                <label for="total" class="form-label me-5">Total</label>
                <input type="text" class="form-control w-50" name="total" id="total" readonly>
              </div>
              <!-- fin total a pagar -->
              <input type="hidden" name="fechasSeleccionadas" id="fechasSeleccionadas" value="" />
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
  <script src="scrip.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Obtener todos los checkboxes por su nombre
      var checkboxes = document.getElementsByName("Pago[]");

      // Variable para almacenar el índice del último checkbox seleccionado
      var ultimoSeleccionado = -1;

      // Agregar un evento de clic a cada checkbox
      checkboxes.forEach(function(checkbox, index) {
        checkbox.addEventListener("change", function() {
          // Verificar si el checkbox se seleccionó
          if (checkbox.checked) {
            // Verificar si se está seleccionando en orden
            if (index === ultimoSeleccionado + 1) {
              // Actualizar el índice del último checkbox seleccionado
              ultimoSeleccionado = index;
              // Calcular el total al seleccionar/deseleccionar un checkbox
              calcularTotal();
            } else {
              // Desmarcar el checkbox si no se selecciona en orden
              checkbox.checked = false;
            }
          } else {
            // Actualizar el índice del último checkbox seleccionado al deseleccionar
            ultimoSeleccionado = index - 1;
            // Calcular el total al seleccionar/deseleccionar un checkbox
            calcularTotal();
          }

          // Actualizar las fechas seleccionadas en el campo oculto
          actualizarFechasSeleccionadas();
        });
      });

      function calcularTotal() {
        // Obtener todos los checkboxes seleccionados
        var checkboxesSeleccionados = document.querySelectorAll('input[name="Pago[]"]:checked');

        // Inicializar la variable para almacenar la suma
        var sumaTotal = 0;

        // Iterar sobre los checkboxes seleccionados
        checkboxesSeleccionados.forEach(function(checkbox) {
          // Obtener la fila correspondiente a este checkbox
          var fila = checkbox.closest("tr");

          // Obtener el valor de la columna "Detalle Certificado" de la fila
          var detalleCertificado = parseFloat(fila.cells[3].textContent);

          // Sumar el valor al total
          sumaTotal += detalleCertificado;
        });

        // Actualizar el valor del input "Total"
        document.getElementById("total").value = sumaTotal.toFixed(2);
      }

      function actualizarFechasSeleccionadas() {
        // Obtener todas las fechas seleccionadas
        var fechasSeleccionadas = obtenerFechasSeleccionadas();

        // Actualizar el valor del campo oculto con las fechas seleccionadas
        document.getElementById("fechasSeleccionadas").value = fechasSeleccionadas.join(",");
      }

      function obtenerFechasSeleccionadas() {
        var fechas = [];
        var checkboxesSeleccionados = document.querySelectorAll('input[name="Pago[]"]:checked');

        checkboxesSeleccionados.forEach(function(checkbox) {
          var fila = checkbox.closest("tr");
          var fecha = fila.cells[2].textContent.trim(); // Ajusta el índice según tu estructura de tabla y elimina espacios en blanco
          fechas.push(fecha);
        });

        return fechas;
      }
    });
  </script>

</body>

</html>