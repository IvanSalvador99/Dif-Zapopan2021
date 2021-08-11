<?php
    include("../../../funciones.php");
    iniciar_sesion(0);
    $con = conectar();
    $jsondata = array();
    $temp = array();
    
    //var_dump($_POST);
    
    if (!isset($_POST['idsesion'])) {
        $jsondata['exito'] = false;
        $jsondata['mensaje'] = "No esta especificado el tipo de sesión o el ID de la terapia.";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
    
    $query = "SELECT * FROM $vw_tpsicologica_sesiones WHERE id = '" . $_POST['idsesion'] . "'";
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_assoc($result);
        $jsondata['exito'] = false;
        $jsondata['mensaje'] = "No esta especificado el tipo de sesión o el ID de la terapia.";
        $jsondata['sesion'][] = $row;
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    } else {
        echo "Error en el query: " . $query . "</br>Error:" . mysql_error();
    }
?>