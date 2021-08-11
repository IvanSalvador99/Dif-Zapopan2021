<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    var_dump($_POST);
    
    if (isset($_POST['colonia'])) {
        $query = "INSERT INTO $serviciosnv VALUES (null, '" . $_POST['servicio'] . "', 
                '" . $_POST['fechaservicio'] . "',
                '" . $_POST['locacion'] . "',
                '" . $_POST['colonia'] . "',
                '" . $_POST['mm'] . "',
                '" . $_POST['mf'] . "',
                '" . $_POST['am'] . "',
                '" . $_POST['af'] . "',
                '" . $_POST['amm'] . "',
                '" . $_POST['amf'] . "')";
                
        echo "</br></br>Query: " . $query;
    } else {
        $query = "INSERT INTO $serviciosnv VALUES (null, '" . $_POST['servicio'] . "', 
                '" . $_POST['fechaservicio'] . "',
                '" . $_POST['locacion'] . "', null,
                '" . $_POST['mm'] . "',
                '" . $_POST['mf'] . "',
                '" . $_POST['am'] . "',
                '" . $_POST['af'] . "',
                '" . $_POST['amm'] . "',
                '" . $_POST['amf'] . "')";
                
        echo "</br></br>Query: " . $query;
    }
    if (mysql_query($query, $con)) {
        header("Location: ../serviciosnv.php?action=1");
    } else {
        echo "</br></br>Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>