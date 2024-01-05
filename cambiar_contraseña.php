<?php
session_start();
include("conexion.php");

if (isset($_POST['validar_cambiar'])) {
    $nombreUsuario = $_POST['username'];
    $contrasenaIngresada = $_POST['contrasena_ingresada'];
    $nuevaContrasena = $_POST['nueva_contrasena'];
    $confirmarContrasena = $_POST['confirmar_contrasena'];

    // Obtener datos del usuario
    $queryUsuario = mysqli_query($conn, "SELECT * FROM login WHERE log_usuario = '$nombreUsuario'");
    $usuario = mysqli_fetch_assoc($queryUsuario);

    // Verificar si la contraseña ingresada coincide con el hash almacenado
    if (password_verify($contrasenaIngresada, $usuario['log_clave'])) {
        // La contraseña ingresada es válida
        if ($nuevaContrasena === $confirmarContrasena) {
            // Las nuevas contraseñas coinciden
            if (strlen($nuevaContrasena) >= 8 && ($nuevaContrasena)) {
                // Longitud mínima de la nueva contraseña y requisitos adicionales
                $nuevaContrasenaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE `login` SET `log_clave` = '$nuevaContrasenaHash' WHERE log_usuario = '$nombreUsuario'");
                echo "<script>alert('Contraseña cambiada exitosamente.');</script>";
            } else {
                echo "<script>alert('La nueva contraseña no cumple con los requisitos.');</script>";
            }
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
    <link rel="stylesheet" href="style.css">
    <!-- Agrega el enlace a Font Awesome para obtener los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Agrega este script de JavaScript -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            var eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
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
                            <input type="text" id="username" name="username" class="form-control"
                                   value="<?php echo $_SESSION['username']; ?>" readonly>
                        </div>
                        <div class="form-group mb-4">
                            <input type="password" id="contrasena_ingresada" name="contrasena_ingresada"
                                   class="form-control" placeholder="Contraseña actual" required>
                            <a data-ui="action_forgot_password" href="Recuperar_Contrasena.php">¿Olvidaste tu contraseña?</a>
                        </div>
                        <div class="form-group mb-4">
                            <input type="password" id="nueva_contrasena" name="nueva_contrasena"
                                   class="form-control" placeholder="Nueva Contraseña" minlength="8" required>
                            <button type="button" onclick="togglePasswordVisibility('nueva_contrasena', 'eyeIcon1')">
                                <i id="eyeIcon1" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-group mb-4">
                            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena"
                                   class="form-control" placeholder="Confirmar Contraseña" required>
                            <button type="button" onclick="togglePasswordVisibility('confirmar_contrasena', 'eyeIcon2')">
                                <i id="eyeIcon2" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" name="validar_cambiar" class="btn btn-outline-success">Cambiar Contraseña</button>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
