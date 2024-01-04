<?php
session_start();
include("conexion.php");

if(isset($_POST['cambiar_contraseña'])) {
    $nombreUsuario = $_POST['username'];
    $contrasenaIngresada = $_POST['contrasena_ingresada'];
    $nuevaContrasena = $_POST['nueva_contrasena'];
    $confirmarContrasena = $_POST['confirmar_contrasena'];

    // Obtener datos del usuario
    $queryUsuario = mysqli_query($conn, "SELECT * FROM login WHERE log_usuario = '$nombreUsuario'");
    $usuario = mysqli_fetch_assoc($queryUsuario);

    if(password_verify($contrasenaIngresada, $usuario['log_clave'])) {
        // La contraseña ingresada es válida
        if($nuevaContrasena === $confirmarContrasena) {
            // Las nuevas contraseñas coinciden
            $nuevaContrasenaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE `login` SET `log_clave` = '$nuevaContrasenaHash' WHERE log_usuario = '$nombreUsuario'");

            echo "<script>alert('Contraseña cambiada exitosamente.');</script>";
        } else {
            echo "<script>alert('Las nuevas contraseñas no coinciden.');</script>";
        }
    } else {
        echo "<script>alert('Contraseña actual incorrecta.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    Cambiar Contraseña
                  </div>
                  <div class="card-body">
                  <!-- Formulario de cambio de contraseña -->
                  <form action="" method="post" class="d-flex flex-column">
                  <div class="form-group mb-4">
                      <label for="username">Usuario:</label>
                      <input type="text" id="username" name="username" class="form-control" value="<?php echo $_SESSION['username']; ?>" readonly>
                      </div>
                      <div class="form-group mb-4">
                        <input type="password" id="contrasena_ingresada" name="contrasena_ingresada" class="form-control" placeholder="Contraseña actual" required>
                        <a data-ui="action_forgot_password" href="seguridad/Recuperar_Contrasena.php">¿Olvidaste tu contraseña?</a>
                        </div>
                      <div class="form-group mb-4">
                        <input type="password" id="nueva_contrasena" name="nueva_contrasena" class="form-control" placeholder="Nueva Contraseña" required>
                        </div>
                      <div class="form-group mb-4">
                        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" class="form-control" placeholder="Confirmar Contraseña" required>
                      </div>
                      <div class="d-flex justify-content-between">
                        <button type="submit" name="validar_cambiar" class="btn btn-primary">Cambiar Contraseña</button>
                        <a href="p.php" class="btn btn-danger">Cancelar</a>
                      </div>
                  </form>
              </div>
          </div>
      </div>
    </div>
  </div>
</body>
</html>

