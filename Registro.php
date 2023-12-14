<!doctype html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/stylelogin.css">
</head>

<body class="img js-fullheight" style="background-image: url(img/bg.jpg);">
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-1"  >
                    <img src="img/logoCaja.png" alt="Logo" style="width:100%;">
                </div>
            </div>
            <div class="row justify-content-center  mt-3">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <h2 class="mb-4 text-center font-weight-bold text-with-relief" style="color: white;">
                            Registro</h2>
                        <form class="signin-form" method="post" id="login-form" action="incriplogin.php" >
                            <div class="form-group">
                                <input type="text" class="form-control font-weight-bold" placeholder="Usuario"
                                    name="txtusuario" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control font-weight-bold" placeholder="Clave"
                                    name="txtpassword" required>
                            </div>
                            <div class="form-group">
                                <input id="password-field" type="password" class="form-control font-weight-bold"
                                    name="txtpassword" placeholder="Contraseña" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary submit px-3"
                                    name="btnRegistrar">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
 </body>

</html>