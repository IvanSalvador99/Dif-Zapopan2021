<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    $jsondata = array();
    
    $query = "UPDATE $variables_fijas SET valor = '" . strtotime("tomorrow") . "' WHERE id='1'";
    if (mysql_query($query, $con)) {
        $jsondata['mensaje'] = "Registro extemporaneo activado hasta finalizar el dia.";
    } else {
        $jsondata['mensaje'] = "Error en el query: $query, Error: " . mysql_error() . ", contactar a Sistemas.";
    }
    
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
?>