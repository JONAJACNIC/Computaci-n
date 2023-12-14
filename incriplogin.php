<?php
include("conexion.php");
$nombre = $_POST['txtusuario'];
$pass= $_POST['txtpassword'];

// Registrar al Sistema 

if(isset($_POST['btnRegistrar']))
{
    $pass_fuerte=password_hash($pass,PASSWORD_DEFAULT);
    $queryregistrar="INSERT INTO login(log_usuario,log_clave,fk_rol_id)values ('$nombre','$pass_fuerte','1')";
    if(mysqli_query($conn,$queryregistrar))
    {
        echo "<script> alert ('Usuario registrado: $nombre');windos.location.host='index.php' </script>";
    }

} 

//Ingresar al Sistema  

if(isset($_POST['btnlogin']))
{
    $queryusuario = mysqli_query( $conn,"SELECT *FROM login WHERE log_usuario= '$nombre'");
    $nr = mysqli_num_rows($queryusuario);
    $buscarpass = mysqli_fetch_array ($queryusuario);
    
//desincriptación

    if(($nr == 1)&& (password_verify($pass,$buscarpass['log_clave'])))
    {
        //echo "Bienvenido: $nombre ";
        header("Location:prueba.html");
    }
    else 
    {
        echo "<script> alert ('Usuario o contraseña incorrecto');windos.location.host='index.php' </script> ";  
    }
}
// if (isset($_POST['btnlogin1'])) {
//     header('Location: Registro.php');
//   }
?>