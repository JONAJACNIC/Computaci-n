<?php
include("registroGasAdm.php");
include("../conexion.php");

$fechaActual = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../style.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
  <div class="container-fluid">
    <h5 class="text-center "> Gastos Administrativos </h5>
    <form action="" method="post">
      <!-- Datos tesoreria -->
      <div class="card">
        <div class="card-header fw-bold">
          Datos Tesoreria
        </div>
        <div class="card-body ">
          <!-- Campo id -->
          <input type="hidden" class="form-control" name="idSoc" id="idSoc" value="<?php $sql = "SELECT pk_sc_id FROM socio  INNER JOIN cargo_socio ON fk_sc_id = pk_sc_id WHERE pk_crg_id = 2 ";
                                                                                    $result = $conn->query($sql);
                                                                                    $row = $result->fetch_assoc();
                                                                                    echo $row['pk_sc_id']; ?>" readonly> <!-- fin campo id -->
          <!-- Fila 1 -->
          <div class="row mb-2 justify-content-center">
            <!-- Barra de busqueda -->
            <div class="col-md-3 d-flex align-items-center">
              <label for="nombre" class="form-label me-1">Buscar </label>
              <input type="text" class="form-control" name="buscar" id="buscar" value="" readonly>
            </div> <!-- Fin Barra Buscada -->
            <!-- Campo nombres -->
            <div class="col-md-3 d-flex align-items-center">
              <label for="nombre" class="form-label me-1">Nombres</label>
              <input type="text" class="form-control w-75" name="nombre" id="nombre" value="<?php $sql = "SELECT sc_nombre FROM socio  INNER JOIN cargo_socio ON fk_sc_id = pk_sc_id WHERE pk_crg_id = 2 ";
                                                                                            $result = $conn->query($sql);
                                                                                            $row = $result->fetch_assoc();
                                                                                            echo $row['sc_nombre'];
                                                                                            ?>" readonly>
            </div><!-- fin campo nombres -->
            <!-- Campo apellidos -->
            <div class="col-md-3 d-flex align-items-center">
              <label for="apellido" class="form-label me-1 ">Apellidos </label>
              <input type="text" class="form-control w-100" name="apellido" id="apellido" value="<?php $sql = "SELECT sc_apellido FROM socio  INNER JOIN cargo_socio ON fk_sc_id = pk_sc_id WHERE pk_crg_id = 2 ";
                                                                                                  $result = $conn->query($sql);
                                                                                                  $row = $result->fetch_assoc();
                                                                                                  echo $row['sc_apellido'];
                                                                                                  ?>" readonly>
            </div><!-- fin campo apellidos -->
            <!-- Campo cédula -->
            <div class="col-md-3 d-flex align-items-center">
              <label for="cedula" class="form-label ">Cédula</label>
              <input type="text" class="form-control w-50" name="cedula" id="cedula" value="<?php $sql = "SELECT sc_cedula FROM socio  INNER JOIN cargo_socio ON fk_sc_id = pk_sc_id WHERE pk_crg_id = 2 ";
                                                                                            $result = $conn->query($sql);
                                                                                            $row = $result->fetch_assoc();
                                                                                            echo $row['sc_cedula'];
                                                                                            ?>" readonly>
            </div><!-- fin campo cédula -->
          </div><!-- Fin Fila 1 -->
          <!-- Fila 2 -->
          <div class="row mb-2 ">
            <!-- Campo Cargo -->
            <div class="col-md-3 d-flex align-items-center">
              <label for="cargo" class="form-label">Cargo </label>
              <input type="text" class="form-control" name="apellido" id="apellido" value="<?php $sql = "SELECT crg_dsc FROM cargo_socio WHERE pk_crg_id = 2 ";
                                                                                            $result = $conn->query($sql);
                                                                                            $row = $result->fetch_assoc();
                                                                                            echo $row['crg_dsc'];
                                                                                            ?>" readonly>
            </div> <!-- fin campo cargo-->
          </div><!--Fin Fila 2 -->
        </div>
      </div><!-- Fin Datos tesoreria -->
      <!-- Registro de fondos Estratégico -->
      <div class="card mt-2">
        <div class="card-header fw-bold">
          Gastos Administrativos
        </div>
        <div class="card-body ">
          <!-- Fila 1 -->
          <div class="row mb-2 justify-content-center">
            <!-- Campo fecha de gastos administrativos-->
            <div class="col-md-4 d-flex">
              <label for="fecha" class="form-label  me-2">Fecha</label>
              <input type="date" class="form-control w-50" name="fechaIni" id="fechaIni" value="<?php echo date("Y-m-d"); ?>" readonly>
            </div> <!-- Fin campo fecha-->
            <!-- Campo medio de pago-->
            <div class="col-md-4 d-flex ">
              <label for="tpPago" class="form-label me-2"> Medio de Pago</label>
              <select name="tipPago" id="tipPago" class="form-select w-50" required readonly>
                <?php
                // Consulta para obtener los datos de la tabla tipo de pago
                $sql = "SELECT * FROM tipo_pago;";
                $result = $conn->query($sql);
                // Generar las opciones dinámicamente
                while ($row = $result->fetch_assoc()) {
                  echo "<option value='{$row['pk_tp_pago_id']}'>{$row['tp_pago_dsc']}</option>";
                }
                ?>
              </select>
            </div> <!-- Campo tipo de gasto -->
          </div><!-- Fin Fila 1 -->
          <!-- Fila 2 -->
          <div class="row mb-2 justify-content-center">
            <!-- Cantidad del gasto administrativo -->
            <div class="col-md-4 d-flex ">
              <label for="cantPres" class="form-label me-3">Monto </label>
              <input type="text" class="form-control w-50" name="cantGas" id="cantGas" onblur="verificarMonto()">
            </div> <!-- fin campo cantidad gasto-->
            <!-- Campo tipo de gasto -->
            <div class="col-md-5 d-flex mb-2 ">
              <label for="tipPrest" class="form-label me-2">Tipo de Gasto</label>
              <select name="tipGas" id="tipGas" class="form-select w-50" required>
                <option value=""> ---- </option>
                <?php
                // Consulta para obtener los datos de la tabla tipo de egresos
                $sql = "SELECT pk_tp_egre_id, tp_egre_dsc FROM tipo_egreso where pk_tp_egre_id IN (4, 5, 6, 12, 17);";
                $result = $conn->query($sql);
                // Generar las opciones dinámicamente
                while ($row = $result->fetch_assoc()) {
                  echo "<option value='{$row['pk_tp_egre_id']}'>{$row['tp_egre_dsc']}</option>";
                }
                ?>
              </select>
            </div><!-- fin campo tipo gastos administrativos-->
          </div><!-- Fin Fila 2 -->
        </div>
      </div><!--Fin  Registro de fondos Estratégico -->
      <!-- Seccion  Botón  -->
      <div class="py-2 text-center">
        <input type="submit" value="Registrar" id="btnRegistrar" name="btnRegistrar" class="btn btn-outline-success">
        <input type="submit" value="Limpiar" class="btn btn-outline-success">
        <input type="submit" value="Imprimir" class="btn btn-outline-success">
        <input type="submit" value="Cancelar" class="btn btn-outline-success">
      </div>
    </form>
  </div>
</body>

<script>
  function verificarMonto() {
    // Obtener el valor del campo "Monto del Préstamo"
    var montoDes = parseFloat(document.getElementById("cantGas").value);
    console.log(montoDes);
    if (montoDes >= 100) {
      alert("Necesita aprobación de la Asamblea");
    } else {
      alert("Registrado correctamente");
    }
  }
</script>

</html>
