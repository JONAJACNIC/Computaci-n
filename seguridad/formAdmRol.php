<?php
include("conexion.php");

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $pass = $_POST['clave'];
    $id= $_POST['rol'];
    $pass_fuerte = password_hash($pass, PASSWORD_DEFAULT);

    // Verificar si el usuario ya existe
    $query_verificar_usuario = "SELECT * FROM login WHERE log_usuario = '$nombre'";
    $result_verificar_usuario = mysqli_query($conn, $query_verificar_usuario);

    if (mysqli_num_rows($result_verificar_usuario) > 0) {
        echo "<script> alert('El usuario $nombre ya existe. Por favor, elige otro nombre de usuario.'); </script>";
    } else {
        // Insertar usuario si no existe
        $queryregistrar = "INSERT INTO login(log_usuario, log_clave, fk_rol_id) VALUES ('$nombre', '$pass_fuerte', '$id')";

        if (mysqli_query($conn, $queryregistrar)) {
            echo "<script> alert('Usuario registrado: $nombre'); </script>";
    
        } else {
            echo "Error: " . $queryregistrar . "<br>" . mysqli_error($conn);
        }
    }
}


$estado = "activo";

// Verifica si se ha enviado un formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cambia el estado del botón
    $estado = ($estado == "activo") ? "inactivo" : "activo";
}


// Consultar y mostrar usuarios

$SQL="SELECT login.pk_log_id, login.log_usuario , login.log_clave , rol.rol_dsc, login.fk_rol_id , login.fk_sc_id, rol.rol_dsc, login.pk_esl_id
FROM login
LEFT JOIN rol ON login.fk_rol_id = rol.pk_rol_id ";

$dato = mysqli_query($conn, $SQL);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrador de Roles</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
  <style>
    .table-container {
      max-height: 500px; /* Establece la altura máxima que deseas */
      overflow-y: auto; /* Agrega una barra de desplazamiento vertical si es necesario */
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <h5 class="text-center mb-4">Administrador de Roles</h5>

    <!-- Formulario para agregar nuevo usuario -->
    <div class="container">
    <div class="row">
        <div class="col-md-6">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
                
                <div class="mb-3">
                    <label for="clave" class="form-label">Clave</label>
                    <input type="password" class="form-control" name="clave" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select name="rol" id="rol" class="form-select" required>
                        <option value="">----</option>
                        <?php
                        // Consulta para obtener los datos de la tabla rol 
                        $sql = "SELECT pk_rol_id, rol_dsc FROM rol";
                        $result = $conn->query($sql);
                        // Generar las opciones dinámicamente
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['pk_rol_id']}'>{$row['rol_dsc']}</option>";
                        }
                        ?>
                    </select>
              
                <div class="col-md-6">
                <div class="py-2 text-center">
          <input type="submit" value="Registrar" id="btnRegistrar" name="btnRegistrar" class="btn btn-outline-success">
                <!-- <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Agregar Usuario</button>
                </div> -->
                </div>
            </div>
        </form>
    </div>
</div>
    <!-- Inicio de cuadro de visualización del personal -->
    <br>
    <div class="mx-auto table-container">
      <table class="table table-striped table-primary table_id">       
        <thead>    
          <tr>
            <!-- <th>ID</th> -->
            <th>Nombre</th>
            <th>Password</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if($dato->num_rows > 0) {
              while($fila=mysqli_fetch_array($dato)) {   
          ?>
          <tr>
            <!-- <td ><?php //echo $fila['pk_log_id']; ?></td> -->
            <td ><?php echo $fila['log_usuario']; ?></td>
            <td ><?php echo $fila['log_clave']; ?></td>
            <td ><?php echo $fila['rol_dsc']; ?></td>
            <td ><?php echo $fila['pk_esl_id']; ?></td>
            <td>
              <a class="btn btn-warning" href="editar_admrol.php?id=<?php echo $fila['pk_log_id']?> ">
              <i class="fa fa-edit"></i>Editar</a>
               <!-- Estado  -->
               <!-- Agrega un campo oculto para almacenar el estado actual -->
                <input type="hidden" name="estado" value="<?php echo $estado; ?>">
                <!-- Agrega un botón que cambia de estado cada vez que se hace clic -->
                <button type="submit" class="btn <?php echo ($estado == "activo") ? "btn-success" : "btn-secondary"; ?>">
                    <?php echo ($estado == "activo") ? "Activo" : "Inactivo"; ?>
                </button>
            </td>
          </tr>
          <?php
              }
            } else {
          ?>
          <tr class="text-center">
            <td colspan="5">No existen registros</td>
          </tr>  
          <?php  
            }
          ?>
        </tbody>
      </table>
    </div>
    <!-- Fin de cuadro de visualización del personal -->
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
  <script>
    $(document).ready( function () {
      $('.table_id').DataTable();
    });
  </script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
</body>

</html>