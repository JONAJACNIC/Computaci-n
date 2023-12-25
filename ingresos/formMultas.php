<?php
session_start();
include('guardar_dato.php');
include("conexion.php");
include('consultaTipoMult.php');
$opcionesTipoMulta = obtenerOpcionesTipoMulta();
$fechaActual = date("Y-m-d");
$cta_sc_id = 0;
$total_multas = "";
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
    <h5 class="text-center">Multas</h5>

    <form action="consultaTipoMult.php" method="post">
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
        <!-- Multas -->
        <div class="card">
          <div class="card-header fw-bold">
            Registro Multas
          </div>
          <div class="card-body">
            <div class="col-md-3 d-flex align-items-center">
              <label for="tipoMulta" class="form-label">Tipo de Multa</label>
              <select name="tipoMulta" id="tipoMulta" class="form-select">
                <option value=""> ---- </option>
                <?php
                // Consulta para obtener los datos de la tabla tipo_multa
                $sql = "SELECT pk_tp_mult_id, mult_det, mult_val FROM tipo_multa;";
                $result = $conn->query($sql);

                // Generar las opciones dinámicamente
                while ($row = $result->fetch_assoc()) {
                  echo "<option value='{$row['mult_val']}'>{$row['mult_det']}</option>";
                }
                ?>
              </select>
            </div>

            <!-- Botón "Agregar Multa" sin enviar el formulario -->
            <center><button type="button" class="btn btn-outline-success" id="btnAgregarMulta">Agregar Multa</button></center>
          </div>
        </div>
        <!-- fin registro multas -->

        <!-- Tabla de Multas -->
        <div class="card">
          <div class="card-header fw-bold">
            Detalles de Multas
          </div>
          <div class="card-body">
            <table id="tablaMultas">
              <thead>
                <tr>
                  <th>Tipo Multa</th>
                  <th>Valor</th>
                  <th>Pago</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <!-- Fin Tabla de Multas -->

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
    </form>
  </div>

  <!-- Scripts -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var cantidadMultas = 0;

      function agregarMulta() {
        // Evitar que el formulario se envíe automáticamente al hacer clic en el botón
        event.preventDefault();

        var tipoMultaSelect = document.getElementById("tipoMulta");
        var tipoMultaOption = tipoMultaSelect.options[tipoMultaSelect.selectedIndex];

        if (tipoMultaOption) {
          // Obtener el valor de "mult_val" del formato "mult_val (valor)"
          var multaValue = parseFloat(tipoMultaOption.value);


          // Crear una nueva fila en la tabla
          var tabla = document.getElementById("tablaMultas");
          var nuevaFila = tabla.insertRow(-1);

          // Crear celdas para cada columna
          var celdaTipoMulta = nuevaFila.insertCell(0);
          var celdaValorMulta = nuevaFila.insertCell(1);
          var celdaPago = nuevaFila.insertCell(2);

          // Asignar valores a las celdas
          celdaTipoMulta.textContent = tipoMultaOption.text;
          celdaValorMulta.textContent = multaValue.toFixed(2);

          // Agregar checkbox para el pago
          var checkboxPago = document.createElement("input");
          checkboxPago.type = "checkbox";
          checkboxPago.name = "Pago[]";
          checkboxPago.value = multaValue;
          checkboxPago.addEventListener("change", actualizarTotal);
          celdaPago.appendChild(checkboxPago);

          // Actualizar el valor del campo "Total"
          actualizarTotal();

          // Contar la cantidad de multas y actualizar el campo correspondiente
          cantidadMultas++;
          document.getElementById("cantidadMultas").value = cantidadMultas;

          // Limpiar el campo "Tipo de Multa" para futuras selecciones
          tipoMultaSelect.value = "";
        } else {
          alert("Selecciona un tipo de multa antes de agregar.");
        }
      }

      function actualizarTotal() {
        var totalInput = document.getElementById("total");
        var totalActual = 0;
        var checkboxesSeleccionados = document.querySelectorAll('input[name="Pago[]"]:checked');

        checkboxesSeleccionados.forEach(function(checkbox) {
          var valorMulta = parseFloat(checkbox.value);
          totalActual += valorMulta;
        });

        totalInput.value = totalActual.toFixed(2);
      }

      // Obtener el botón "Agregar Multa" y asociar la función agregarMulta al evento click
      var btnAgregarMulta = document.getElementById("btnAgregarMulta");
      btnAgregarMulta.addEventListener("click", agregarMulta);
    });
  </script>
</body>

</html>