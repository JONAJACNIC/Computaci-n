<?php
include("conexion.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    // Aquí deberías implementar la lógica para eliminar el registro con el ID dado
    $SQL = "SELECT `pk_log_id`, `log_usuario`, `log_clave`, `fk_rol_id` FROM `login` WHERE `pk_log_id` = $id";
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
    $updateQuery = "UPDATE `login` SET `log_usuario`='$nuevoUsuario', `log_clave`='$nuevaClave', `fk_rol_id`='$nuevoRol' WHERE `pk_log_id`='$userId'";
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
</head>
<body>

<h2>Editar Usuario</h2>

<form method="post" action="">
    <input type="hidden" name="userId" value="<?php echo $userData['pk_log_id']; ?>">

    <label for="nuevoUsuario">Cambiar Usuario:</label>
    <input type="text" name="nuevoUsuario" value="<?php echo $userData['log_usuario']; ?>" required><br>

    <label for="nuevaClave">Nueva Contraseña:</label>
    <input type="password" name="nuevaClave" required><br>

    <label for="nuevoRol">Cambiar Rol:</label>
    <select name="nuevoRol" required>
        <option value="1" <?php echo ($userData['fk_rol_id'] == 1) ? 'selected' : ''; ?>>Administrador</option>
        <option value="2" <?php echo ($userData['fk_rol_id'] == 2) ? 'selected' : ''; ?>>Tesorero</option>
        <option value="3" <?php echo ($userData['fk_rol_id'] == 3) ? 'selected' : ''; ?>>Contador</option>
        <option value="4" <?php echo ($userData['fk_rol_id'] == 4) ? 'selected' : ''; ?>>Presidente</option>
    </select><br>

    <input type="submit" name="editar" value="Editar">
</form>

</body>
</html>
