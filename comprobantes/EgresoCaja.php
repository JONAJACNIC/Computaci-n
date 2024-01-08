<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tabla de datos</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
    }

    table {
      width: 80%;
      margin: 20px auto;
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .table-container {
      position: relative;
    }

    .imprimir-link {
      padding: 5px 10px;
      font-size: 12px;
      cursor: pointer;
      color: blue;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <h2>Comprobantes Egresos Caja</h2>

  <!-- Agregar formulario de filtro por fecha de pago -->
  <form method="get" action="">
    <label for="mes">Filtrar por Mes:</label>
    <input type="month" name="mes" id="mes" placeholder="" >
    <input type="submit" value="Filtrar">
  </form>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>Cédula</th>
          <th>Fecha de Pago</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
          include('../conexion.php');

          if (!$conn) {
            die("Error de conexión: " . mysqli_connect_error());
          }

          // Obtener el valor del filtro por mes
        $filtro_mes = isset($_GET['mes']) ? $_GET['mes'] : '';

        $sentencia_sql = "SELECT egre_val, egre_fech_ini, egre_fdesm, fk_tp_egre_id, fk_sc_id,
        sc_nombre, sc_apellido, sc_cedula,
        tp_egre_dsc
        FROM egreso
        INNER JOIN socio
        ON egreso.fk_sc_id = socio.pk_sc_id
        INNER JOIN tipo_egreso
        ON egreso.fk_tp_egre_id = tipo_egreso.pk_tp_egre_id
        WHERE egreso.fk_est_pg_pend = 1
        AND egreso.fk_tp_egre_id IN (4, 5, 6, 12, 17)  -- Agregar esta condición para filtrar por tp_egre_id
        ORDER BY sc_cedula, egre_fech_ini;";

                $resultado = mysqli_query($conn, $sentencia_sql);

                if (!$resultado) {
                    die("Error en la consulta: " . mysqli_error($conn));
                }

          // Array para almacenar resultados agrupados
          $egresos_agrupados = array();

          while ($fila = mysqli_fetch_assoc($resultado)) {
            $key = $fila["sc_cedula"] . $fila["sc_nombre"] . $fila["sc_apellido"] . $fila["egre_fech_ini"];

            // Verificar si ya existe la clave en el array
            if (array_key_exists($key, $egresos_agrupados)) {
              // Si existe, agregar el tipo de egreso y valor a los arrays correspondientes
              $egresos_agrupados[$key]["detalles"][] = array(
                "tp_egre_dsc" => $fila["tp_egre_dsc"],
                "egre_val" => $fila["egre_val"]
              );
            } else {
              // Si no existe, agregar la fila al array
              $egresos_agrupados[$key] = array(
                "sc_cedula" => $fila["sc_cedula"],
                "egre_fech_ini" => $fila["egre_fech_ini"],
                "sc_nombre" => $fila["sc_nombre"],
                "sc_apellido" => $fila["sc_apellido"],
                "detalles" => array(
                  array(
                    "tp_egre_dsc" => $fila["tp_egre_dsc"],
                    "egre_val" => $fila["egre_val"]
                  )
                )
              );
            }
          }

          // Filtrar los datos por mes si se seleccionó un mes
          if (!empty($filtro_mes)) {
            $egresos_agrupados = array_filter($egresos_agrupados, function($fila) use ($filtro_mes) {
              return date("Y-m", strtotime($fila["egre_fech_ini"])) == $filtro_mes;
            });
          }

          // Mostrar resultados agrupados
          foreach ($egresos_agrupados as $fila) {
            echo "<tr>";
            echo "<td>" . $fila["sc_cedula"] . "</td>";
            echo "<td>" . $fila["egre_fech_ini"] . "</td>";
            echo "<td>" . $fila["sc_nombre"] . "</td>";
            echo "<td>" . $fila["sc_apellido"] . "</td>";

            // Acciones
            echo "<td><a class='imprimir-link' href='imprimiregre.php?sc_cedula={$fila["sc_cedula"]}&egre_fech_ini={$fila["egre_fech_ini"]}&sc_nombre={$fila["sc_nombre"]}&sc_apellido={$fila["sc_apellido"]}&detalles=" . urlencode(json_encode($fila["detalles"])) . "'>ver</a></td>";
            echo "</tr>";
          }

          mysqli_close($conn);
        ?>
      </tbody>
    </table>

    
  </div>
</body>
</html>