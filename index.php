<?php
include('conexion.php');
session_start();
if (isset($_POST['btnlogin'])) {
    $nombre = $_POST['txtusuario'];
    $pass = $_POST['txtpassword'];
    $queryusuario = mysqli_query($conn, "SELECT * FROM login WHERE log_usuario = '$nombre'");
    $nr = mysqli_num_rows($queryusuario);
    $buscarpass = mysqli_fetch_array($queryusuario);
    if ($nr == 1 && password_verify($pass, $buscarpass['log_clave'])) {
        $rol = $buscarpass['fk_rol_id'];
        // Redirigir según el rol del usuario
        if ($rol == '1') {
            // Puedes redirigir si es necesario
            header("Location: pagAdmin.php");
            exit(); // Asegurarse de que el script se detenga aquí
        } elseif ($rol == '2') {
            // Puedes redirigir si es necesario
            header("Location: pagTeso.php");
            exit();
        } elseif ($rol == '3') {
            // Puedes redirigir si es necesario
            header("Location: pagConta.php");
            exit();
        }
    } else {
        // Almacenar el mensaje de error en una variable de sesión
        $_SESSION['error'] = "Usuario o contraseña incorrecto";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Crisol</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url(imagenes/bg.jpg);
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h2 {
            margin-bottom: 20px;
            color: white;
            font-family: 'Times New Roman', Times, serif;
        }

        input {
            width: 80%;
            max-width: 300px;
            height: 50px;
            background: rgba(0, 0, 0, 0.4);
            color: white;
            padding: 8px;
            border: none;
            margin-bottom: 10px;
            font-size: 16px;
            text-align: center;
            box-sizing: border-box;
            margin-left: auto;
            margin-right: auto;
            caret-color: white;
            transition: background 0.3s, border 0.3s;
        }

        input:hover,
        input:focus {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid white;

        }

        input::placeholder {
            color: white;
        }

        .form-group {
            margin-top: 10px;
        }

        .logo {
            width: 100%;
        }

        button {
            width: 80%;
            max-width: 300px;
            height: 50px;
            border: none;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 16px;
            text-align: center;
            box-sizing: border-box;
            margin-left: auto;
            margin-right: auto;
            caret-color: white;
            transition: background 0.3s;
            /* Agregado para una transición suave */
        }

        button:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    <script>
        function hidePlaceholder(input) {
            input.setAttribute('placeholder', '');
        }

        function showPlaceholder(input) {
            input.setAttribute('placeholder', input.getAttribute('data-placeholder'));
        }
    </script>
</head>

<body>
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-1 w-50">
                <img src="imagenes/logoCaja.png" alt="Logo" class="logo">
            </div>
        </div>
        <div class="row justify-content-center mt-3 ">
            <div class="col-md-6 col-lg-4">
                <h2 class="mb-4 text-center text-with-relief">LOGIN</h2>
                <form action="" method="post" class="d-flex flex-column  justify-content-center">
                    <input type="text" class="rounded-5 mb-2" placeholder="Usuario" data-placeholder="Usuario" name="txtusuario" onfocus="hidePlaceholder(this)" onblur="showPlaceholder(this)" autocomplete="off">
                    <input type="password" class="rounded-5 mb-2" placeholder="Contraseña" data-placeholder="Contraseña" name="txtpassword" onfocus="hidePlaceholder(this)" onblur="showPlaceholder(this)" autocomplete="off">
                    <button type="submit" name="btnlogin" class="bg-white px-3 rounded-5">Ingresar</button>
                </form>
            </div>
        </div>
    </div>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>