<?php
include("conexion.php");
// eliminar_admrol.php


// // // // Verifica si se ha enviado un ID válido

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    // Aquí deberías implementar la lógica para eliminar el registro con el ID dado
    $SQL = "DELETE FROM login WHERE pk_log_id = $id";
    $resultado = mysqli_query($conn, $SQL);

   if ($resultado) {
       // Redirige a la página principal o muestra un mensaje de éxito
       header("Location: formAdmRol.php");
       exit();
    } else {
        // Manejar el caso en que no se puede eliminar el registro
        echo "Error al eliminar el usuario";
    }
 } else {
    // Manejar el caso en que no se proporciona un ID válido
  echo "ID no válido";
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
    <div class="row">
    <div class="col-sm-6 offset-sm-3">
    <div class="alert alert-danger text-center">
    <p>¿Desea confirmar la eliminacion del registro?</p>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <form action="../includes/eliminar_admrol.php" method="POST">
                <input type="hidden" name="accion" value="eliminar_registro">
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                <input type="submit" name="" value="Eliminar" class= " btn btn-danger">
                <a href="user.php" class="btn btn-success">Cancelar</a>                        
        </div>
    </div>
</body>
</html>
