<?php
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(3);
    $jsondata = array();
    
    if (!isset($_POST['idusuario'])) {
        echo "No enviaste nada";
        exit();
    }
    
    $query = "SELECT * FROM $trabajadores WHERE id = '" . $_POST['idusuario'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_array($result);
        echo $row['horasindicato'];
    } else {
        echo "Error en el query: " . $query . ", Error: " . mysql_error();
    }
?>