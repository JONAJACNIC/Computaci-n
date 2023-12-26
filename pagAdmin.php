<?php
include('conexion.php');
if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .dropdown-toggle {
      width: 150px;
      line-height: normal;
    }

    .dropdown-toggle::after {
      float: right;
      transform: translateY(-50%);
    }
  </style>
</head>

<body>
  <!-- Barra horizontal -->
  <div class="container-fluid p-0 border-bottom border-primary-subtle border-3" style="height:12vh">
    <nav class="navbar navbar-expand-lg bg-primary h-100">
      <div class="d-flex justify-content-between align-items-center w-100">
        <!-- Logo en la parte izquierda -->
        <img src="imagenes/logoCaja.png" alt="Logo" class="navbar-brand img-fluid" style="max-height: 95px" />
        <!-- Icono en la parte superior derecha -->
        <div class="position-relative me-4" >
         
          <img src="imagenes/default-profile-picture-male-icon.png" alt="Logo" class="  navbar-brand img-fluid ms-5" style="max-height: 62px; cursor: pointer; filter: invert(1);" onclick="toggleMenu()">
          <div class="menu-content position-absolute d-none me-5 bg-warning-subtle rounded  border border-5 border-black" id="menuContent" style="z-index: 1000;">
            <!-- Opción 1: Cerrar Sesión -->
            <a class="btn btn-link text-dark d-block text-truncate" href="index.php">Cerrar Sesión</a>
            <!-- Opción 2: Cambiar Sesión -->
            <!-- <a class="btn btn-link text-dark d-block text-truncate" href="">Cambiar Sesión</a> -->
          </div>

        </div>
    </nav>
  </div>
  <!-- fin barra horizontal -->
  <!-- Barra vertical -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-2 bg-primary position-fixed h-100">
        <h3 class="text-white">Menú</h3>
        <ul class="list-group d-flex flex-column mt-3">

        <!-- Dropdown para Administrador -->
        <li class="d-flex align-items-center bg-primary m-2 border-bottom border-white border-1">
            <img src="iconos/administrator-developer-icon.svg" alt="Icono de Administrador" class="mr-2 w-25 " />
            <div class="dropdown">
              <button class="btn btn-primary btn-lg" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Admin
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#" id ="admRolLink">Administrar Rol</a>
              </div>
            </div>
          </li>
          <!-- Dropdown de Inicio -->
          <li class=" d-flex align-items-center bg-primary m-2 border-bottom border-white border-1">
            <img src="iconos/house-hand-mortgage-icon.svg" alt="Icono de Socio" class="w-25 h-25" />
            <div>
              <button class="btn btn-primary btn-lg " data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Inicio
              </button>
            </div>
          </li>
          <!-- Dropdown para Socios -->
          <li class=" d-flex align-items-center bg-primary m-2 border-bottom border-white border-1">
            <img src="iconos/male-icon.svg" alt="Icono de Socio" class="w-25 h-25" />
            <div class="dropdown">
              <button class="btn btn-primary btn-lg " data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Socios
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#" id="nuevoSocioLink">Nuevo Socio</a>
                <a class="dropdown-item" href="#" id="actuSocioLink">Actualizar Socio</a>
              </div>
            </div>
          </li>

          <!-- Dropdown para Ingresos -->
          <li class=" d-flex align-items-center bg-primary m-2 border-bottom border-white border-1">
            <img src="iconos/hand-holding-dollar-solid.svg" alt="Icono de Ingresos" class=" w-25" />
            <div class="dropdown">
              <button class="btn btn-primary btn-lg " type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Ingresos
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#" id="pagAporLink">Pago Aportes</a>
                <a class="dropdown-item" href="#">Definir</a>
              </div>
            </div>
          </li>

          <!-- Dropdown para Egresos -->
          <li class="  d-flex align-items-center bg-primary m-2 border-bottom border-white border-1">
            <img src="iconos/pay-money-icon.svg" alt="Icono de Egresos" class="mr-2 w-25" />
            <div class="dropdown">
              <button class="btn btn-primary btn-lg " type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Egresos
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#" id="liquiSocioLink">Liquidación</a>
                <a class="dropdown-item" href="#">Caja Chica</a>
              </div>
            </div>
          </li>

          <!-- Dropdown para Préstamos -->
          <li class="d-flex align-items-center bg-primary m-2 border-bottom border-white border-1">
            <img src="iconos/income-tax-icon.svg" alt="Icono de Préstamos" class="mr-2 w-25" />
            <div class="dropdown">
              <button class="btn btn-primary btn-lg " type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Préstamos
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#" id="soliPresLink">Solicitud Préstamo</a>
                <a class="dropdown-item" href="#">Definir</a>
              </div>
            </div>
          </li>

          <!-- Dropdown para Reportes -->
          <li class="d-flex align-items-center bg-primary m-2 border-bottom border-white border-1">
            <img src="iconos/balance-sheet-icon.svg" alt="Icono de Reportes" class="mr-2 w-25" />
            <div class="dropdown">
              <button class="btn btn-primary btn-lg" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reportes
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#">Definir</a>
                <a class="dropdown-item" href="#">Definir</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!-- fin barra vertical -->
  <!-- Contenedor para formularios -->
  <div class="col-md-10 offset-md-2">
    <!-- Contenido nuevo socio -->
    <div id="contenidoContainer" class="border border-warning border-3 rounded-4 m-1" style="display: none;">
      <iframe id="iframeContainer" frameborder="0" class="w-100 p-0 rounded-4" style="height:84vh"></iframe>
    </div>
  </div>
  <!-- fin contenedor para formularios -->
  <script>
    function toggleMenu() {
      document.getElementById('menuContent').classList.toggle('d-none');
    }
  </script>
  <script src="node_modules/jquery/dist/jquery.js "></script>
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Función para cargar el contenido en el contenedor usando jQuery y mostrar el contenedor
    function cargarContenidoMostrar(contenidoURL) {
      $("#iframeContainer").attr("src", contenidoURL);
      $("#contenidoContainer").show(); // Mostrar el contenedor al cargar el contenido
    }

    // Manejar el clic en "Nuevo Socio"
    $("#nuevoSocioLink").on("click", function(e) {
      e.preventDefault();
      cargarContenidoMostrar("socios/formNueSc.php");
    });
    // Manejar el clic en "Actualizar información socio"
    $("#actuSocioLink").on("click", function(e) {
      e.preventDefault();
      cargarContenidoMostrar("socios/formActSc.php");
    });

    // Manejar el clic en "Liquidación"
    $("#liquiSocioLink").on("click", function(e) {
      e.preventDefault();
      cargarContenidoMostrar("formLiqui.php");
    });

    // Manejar el clic en "Socicitud préstamo"
    $("#soliPresLink").on("click", function(e) {
      e.preventDefault();
      cargarContenidoMostrar("formPres.php");
    });

    // Manejar el clic en "Pago aportes"
    $("#pagAporLink").on("click", function(e) {
      e.preventDefault();
      cargarContenidoMostrar("formPagApor.php");
    });

 // Manejar el clic en "Administrar Rol"
 $("#admRolLink").on("click", function(e) {
      e.preventDefault();
      cargarContenidoMostrar("seguridad/formAdmRol.php");
    });



</script>




</body>

</html>
