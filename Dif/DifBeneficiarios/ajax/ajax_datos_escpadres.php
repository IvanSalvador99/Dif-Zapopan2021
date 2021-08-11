<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    $jsondata = array();
    
    if (isset($_POST['idpersona'])) {
        $persona = $_POST['idpersona'];
    } else {
        $jsondata['mensaje'] = "No enviaste nada";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
    
    $query = "SELECT * FROM $escpadres t WHERE t.idpersona = '$persona' ORDER BY t.fecha DESC LIMIT 1";
    if ($result = mysql_query($query, $con)) {
        $jsondata['mensaje'] = "Se encontraron " . mysql_num_rows($result) . " elementos.";
        $jsondata['persona'] = mysql_fetch_row($result);
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    } else {
        $jsondata['mensaje'] = "Error en el query: " . $query . "</br>Error: " . mysql_error();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
?>