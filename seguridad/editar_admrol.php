<?php
include("../conexion.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    // Aquí deberías implementar la lógica para eliminar el registro con el ID dado
    $SQL = "SELECT `pk_log_id`, `log_usuario`, `log_clave`, `fk_rol_id`, login.fk_est_id FROM `login` WHERE `pk_log_id` = $id";
    $resultado = mysqli_query($conn, $SQL);
    $userData = mysqli_fetch_assoc($resultado);

}

// Procesar el formulario cuando se envía
if (isset($_POST['editar'])) {
    $userId = $_POST['userId'];
    $nuevoUsuario = $_POST['nuevoUsuario'];
    $nuevaClave = password_hash($_POST['nuevaClave'], PASSWORD_DEFAULT); // Hashear la nueva contraseña
    $nuevoRol = $_POST['nuevoRol'];
    // Actualizar los datos en la base de datos
    $updateQuery = "UPDATE `login` SET `log_usuario`='$nuevoUsuario', `log_clave`='$nuevaClave', `fk_rol_id`='$nuevoRol'WHERE `pk_log_id`='$userId'";
    mysqli_query($conn, $updateQuery);
    // Redireccionar a la página de lista de usuarios después de la actualización
    header("Location: formAdmRol.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../style.css">
    <script>
        function confirmarEdicion() {
            return confirm("¿Estás seguro de que deseas editar este usuario?");
        }
    </script>
 
</head>
<body>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h5 class="text-center mb-4">Editar Usuario</h5>
            <form method="post" action="" onsubmit="return confirmarEdicion();">
            <input type="hidden" name="userId" value="<?php echo $userData['pk_log_id']; ?>">

            <div class="mb-3">
                <label for="nuevoUsuario" class="form-label"> Usuario:</label>
                <input type="text" name="nuevoUsuario"  class="form-control" value="<?php echo $userData['log_usuario']; ?>" required>
            </div>

            <div class="mb-3">
            <label for="nuevaClave">Nueva Contraseña:</label>
            <input type="password" name="nuevaClave" class="form-control"  required>
            </div>
            <br>

            <div class="mb-3">
            <label for="nuevoRol">Cambiar Rol:</label>
            <select name="nuevoRol" required>
            <option value="1" <?php echo ($userData['fk_rol_id'] == 1) ? 'selected' : ''; ?>>Administrador</option>
            <option value="2" <?php echo ($userData['fk_rol_id'] == 2) ? 'selected' : ''; ?>>Tesorero</option>
            <option value="3" <?php echo ($userData['fk_rol_id'] == 3) ? 'selected' : ''; ?>>Contador</option>
            <option value="4" <?php echo ($userData['fk_rol_id'] == 4) ? 'selected' : ''; ?>>Presidente</option>
            </select>
             </div>
             <br>

           

            <div class="mb-6">
                <input type="submit" class="btn btn-success" name="editar" value="Editar"> 
                <a href="formAdmRol.php" class="btn btn-danger">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>