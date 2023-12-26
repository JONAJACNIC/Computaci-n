<?php
    // //variables para llamar la conexion
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "crisol";
    $conn = mysqli_connect($server,$user, $pass,$db);
   
    //comprobacion
    if (!$conn) {
      //die(" Conexion Fallida " . mysqli_connect_error());
    } else {
      //echo "conectado";
    }

// // //variables para llamar la conexion
// $server = "186.4.201.53";
// $user = "TekSoftSisProd";
// $pass = "TekSoftSisProd2019";
// $db = "crisol";
// $conn = mysqli_connect($server,$user, $pass,$db);

// //comprobacion
// if (!$conn) {
//   //die(" Conexion Fallida " . mysqli_connect_error());
// } else {
//   //echo "conectado";
// }
?>